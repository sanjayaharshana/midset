<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalarySheet;
use App\Models\EmployersSalarySheetItem;
use App\Models\Promoter;
use App\Models\Coordinator;
use App\Models\Job;
use App\Models\Allowance;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SalarySheetCompleteNotification;
use App\Mail\SalarySheetApprovedNotification;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SalarySheetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = SalarySheet::with(['job.client', 'items.position', 'creator'])
            ->withCount(['items'])
            ->orderBy('created_at', 'desc');

        // If logged-in user is an officer, only show salary sheets for their assigned jobs
        $user = auth()->user();
        if ($user && method_exists($user, 'hasRole') && $user->hasRole('officer')) {
            $query->whereHas('job', function ($q) use ($user) {
                $q->where('officer_id', $user->id);
            });
        }

        $salarySheets = $query->paginate(20);

        // Add promoters count for each salary sheet
        $salarySheets->getCollection()->transform(function ($sheet) {
            $sheet->promoters_count = $sheet->items->filter(function ($item) {
                return isset($item->attendance_data['promoter_id']) && !empty($item->attendance_data['promoter_id']);
            })->count();
            return $sheet;
        });

        return view('admin.salary-sheets.index', compact('salarySheets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $promoters = Promoter::with('position')->get();
        $coordinators = Coordinator::all();

        // If officer, only allow selecting jobs assigned to that officer
        $user = auth()->user();
        if ($user && method_exists($user, 'hasRole') && $user->hasRole('officer')) {
            $jobs = Job::with('client')
                ->where('officer_id', $user->id)
                ->where('status', '!=', 'completed')
                ->whereDoesntHave('salarySheets', function ($query) {
                    $query->whereIn('status', ['paid', 'complete', 'approve']);
                })
                ->get();
        } else {
            $jobs = Job::with('client')
                ->where('status', '!=', 'completed')
                ->whereDoesntHave('salarySheets', function ($query) {
                    $query->whereIn('status', ['paid', 'complete', 'approve']);
                })
                ->get();
        }
        $allowances = Allowance::all();

        return view('admin.salary-sheets.create', compact('promoters', 'coordinators', 'jobs', 'allowances'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Debug: Log the incoming request data
        Log::info('Salary Sheet Store Request:', $request->all());

        try {
            $job = Job::findOrFail($request->job_id);

            // Prevent creating salary sheets for completed jobs
            if ($job->status === 'completed') {
                return redirect()->back()
                    ->withErrors(['error' => 'Cannot create salary sheets for completed jobs.'])
                    ->withInput();
            }

            // Access control: officers can only create salary sheets for their assigned jobs
            $user = auth()->user();
            if ($user && method_exists($user, 'hasRole') && $user->hasRole('officer')) {
                if ((int) $job->officer_id !== (int) $user->id) {
                    abort(403);
                }
            }

            Log::info('Processing rows:', $request->rows ?? []);

            // Create the main salary sheet
            $sheetNumber = SalarySheet::generateSheetNumber();

            $salarySheet = SalarySheet::create([
                'sheet_no' => $sheetNumber,
                'job_id' => $request->job_id,
                'status' => $request->status,
                'location' => $request->location,
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            Log::info('Created salary sheet:', $salarySheet->toArray());



            // Process each promoter row
            $createdItems = [];
            foreach ($request->rows as $rowData) {
                if (empty($rowData['promoter_id'])) {
                    continue; // Skip empty rows
                }

                Log::info('Processing row data:', $rowData);
                Log::info('Row attendance data:', ['attendance' => $rowData['attendance'] ?? 'No attendance data']);
                Log::info('Row attendance_total:', ['attendance_total' => $rowData['attendance_total'] ?? 'No attendance_total']);
                Log::info('Row attendance_amount:', ['attendance_amount' => $rowData['attendance_amount'] ?? 'No attendance_amount']);

                // Get promoter to find position
                $promoter = Promoter::find($rowData['promoter_id']);
                if (!$promoter || !$promoter->position_id) {
                    Log::warning('Promoter not found or no position assigned:', ['promoter_id' => $rowData['promoter_id']]);
                    continue;
                }

                // Structure attendance data properly
                $attendanceData = [];
                if (isset($rowData['attendance']) && is_array($rowData['attendance'])) {
                    foreach ($rowData['attendance'] as $date => $value) {
                        $attendanceData[$date] = (int) $value;
                    }
                }

                // Create structured attendance data
                $structuredAttendanceData = [
                    'attendance' => $attendanceData,
                    'total' => (int) ($rowData['attendance_total'] ?? 0),
                    'amount' => (float) ($rowData['attendance_amount'] ?? 0)
                ];

                // Create payment data
                $paymentData = [
                    'amount' => (float) ($rowData['amount'] ?? 0),
                    'food_allowance' => (float) ($rowData['food_allowance'] ?? 0),
                    'expenses' => (float) ($rowData['expenses'] ?? 0),
                    'accommodation_allowance' => (float) ($rowData['accommodation_allowance'] ?? 0),
                    'hold_for_weeks' => (float) ($rowData['hold_for_8_weeks'] ?? 0),
                    'net_amount' => 0 // Will be calculated below
                ];

                // Calculate net amount (excluding coordination fee)
                $totalEarnings = $paymentData['amount'] + $paymentData['food_allowance'] +
                                $paymentData['accommodation_allowance'];
                $totalDeductions = $paymentData['expenses'] + $paymentData['hold_for_weeks'];
                $paymentData['net_amount'] = $totalEarnings - $totalDeductions;

                // Create coordinator details
                $coordinatorDetails = null;
                if (!empty($rowData['coordinator_id'])) {
                    $coordinator = Coordinator::find($rowData['coordinator_id']);
                    $coordinatorDetails = [
                        'coordinator_id' => $coordinator->coordinator_id ?? $rowData['coordinator_id'],
                        'current_coordinator' => $coordinator->coordinator_name ?? 'Unknown',
                        'amount' => (float) ($rowData['coordination_fee'] ?? 0)
                    ];
                }

                // Create the salary sheet item
                $itemNumber = EmployersSalarySheetItem::generateItemNumber();

                $item = EmployersSalarySheetItem::create([
                    'no' => $itemNumber,
                    'location' => $rowData['location'] ?? $request->location,
                    'position_id' => $promoter->position_id,
                    'promoter_id' => $promoter->id,
                    'attendance_data' => $structuredAttendanceData,
                    'payment_data' => $paymentData,
                    'coordinator_details' => $coordinatorDetails,
                    'job_id' => $request->job_id,
                    'sheet_no' => $salarySheet->sheet_no,
                ]);

                $createdItems[] = $item->no;

                Log::info('Created salary sheet item:', $item->toArray());
                Log::info('Structured attendance data:', $structuredAttendanceData);
                Log::info('Payment data:', $paymentData);
                Log::info('Coordinator details:', $coordinatorDetails ?? []);
            }

            if (empty($createdItems)) {
                Log::warning('No salary sheet items were created');
                return redirect()->back()
                    ->withErrors(['error' => 'No valid salary sheet items were created. Please ensure at least one promoter is selected.'])
                    ->withInput();
            }

            Log::info('Successfully created salary sheet with items:', $createdItems);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Salary sheet created successfully for job ' . $job->job_number . ': ' . $salarySheet->sheet_no,
                    'redirect' => route('admin.salary-sheets.index')
                ]);
            }

            return redirect()->route('admin.salary-sheets.index')
                ->with('success', 'Salary sheet created successfully for job ' . $job->job_number . ': ' . $salarySheet->sheet_no);
        } catch (\Exception $e) {
            Log::error('Error creating salary sheet:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create salary sheet: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withErrors(['error' => 'Failed to create salary sheet: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SalarySheet $salarySheet)
    {
        // Access control: officers can only view salary sheets for their assigned jobs
        $user = auth()->user();
        if ($user && method_exists($user, 'hasRole') && $user->hasRole('officer')) {
            $salarySheet->loadMissing('job');
            if (!$salarySheet->job || (int) $salarySheet->job->officer_id !== (int) $user->id) {
                abort(403);
            }
        }

        $salarySheet->load(['job.client', 'items.position', 'items.promoter', 'creator']);

        return view('admin.salary-sheets.show', compact('salarySheet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalarySheet $salarySheet)
    {
        // Prevent editing salary sheets with complete, approve, or paid status
        if (in_array($salarySheet->status, ['complete', 'approve', 'paid'])) {
            return redirect()->route('admin.salary-sheets.index')
                ->with('error', 'Cannot edit salary sheets with complete, approve, or paid status.');
        }

        // Access control: officers can only edit salary sheets for their assigned jobs
        $user = auth()->user();
        if ($user && method_exists($user, 'hasRole') && $user->hasRole('officer')) {
            $salarySheet->loadMissing('job');
            if (!$salarySheet->job || (int) $salarySheet->job->officer_id !== (int) $user->id) {
                abort(403);
            }
        }

        $promoters = Promoter::with('position')->get();
        $coordinators = Coordinator::all();

        // If officer, only allow selecting jobs assigned to that officer
        // Also exclude jobs with paid/complete salary sheets, except for the current salary sheet's job
        if ($user && method_exists($user, 'hasRole') && $user->hasRole('officer')) {
            $jobs = Job::with('client')
                ->where('officer_id', $user->id)
                ->where('status', '!=', 'completed')
                ->where(function ($query) use ($salarySheet) {
                    $query->whereDoesntHave('salarySheets', function ($q) {
                        $q->whereIn('status', ['paid', 'complete', 'approve']);
                    })
                    ->orWhere('id', $salarySheet->job_id);
                })
                ->get();
        } else {
            $jobs = Job::with('client')
                ->where('status', '!=', 'completed')
                ->where(function ($query) use ($salarySheet) {
                    $query->whereDoesntHave('salarySheets', function ($q) {
                        $q->whereIn('status', ['paid', 'complete', 'approve']);
                    })
                    ->orWhere('id', $salarySheet->job_id);
                })
                ->get();
        }

        $salarySheet->load(['job', 'items.position']);

        return view('admin.salary-sheets.edit', compact('salarySheet', 'promoters', 'coordinators', 'jobs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalarySheet $salarySheet)
    {
        // Prevent updating salary sheets with complete, approve, or paid status
        if (in_array($salarySheet->status, ['complete', 'approve', 'paid'])) {
            return redirect()->route('admin.salary-sheets.index')
                ->with('error', 'Cannot update salary sheets with complete, approve, or paid status.');
        }

        // Access control: officers can only update salary sheets for their assigned jobs
        $user = auth()->user();
        if ($user && method_exists($user, 'hasRole') && $user->hasRole('officer')) {
            $salarySheet->loadMissing('job');
            if (!$salarySheet->job || (int) $salarySheet->job->officer_id !== (int) $user->id) {
                abort(403);
            }
        }

        $validator = Validator::make($request->all(), [
            'job_id' => 'required|exists:custom_jobs,id',
            'status' => 'required|in:draft,complete,reject,paid,approve',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // If officer, ensure the selected job belongs to the officer
            if ($user && method_exists($user, 'hasRole') && $user->hasRole('officer')) {
                $job = Job::findOrFail($request->job_id);
                if ((int) $job->officer_id !== (int) $user->id) {
                    abort(403);
                }
            }

            // Prevent updating salary sheets for completed jobs
            $job = Job::findOrFail($request->job_id);
            if ($job->status === 'completed') {
                return redirect()->back()
                    ->withErrors(['error' => 'Cannot update salary sheets for completed jobs.'])
                    ->withInput();
            }

            $salarySheet->update([
                'job_id' => $request->job_id,
                'status' => $request->status,
                'location' => $request->location,
                'notes' => $request->notes,
            ]);

            return redirect()->route('admin.salary-sheets.index')
                ->with('success', 'Salary sheet updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update salary sheet: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalarySheet $salarySheet)
    {
        // Access control: officers can only delete salary sheets for their assigned jobs
        $user = auth()->user();
        if ($user && method_exists($user, 'hasRole') && $user->hasRole('officer')) {
            $salarySheet->loadMissing('job');
            if (!$salarySheet->job || (int) $salarySheet->job->officer_id !== (int) $user->id) {
                abort(403);
            }
        }

        try {
        $salarySheet->delete();

        return redirect()->route('admin.salary-sheets.index')
                ->with('success', 'Salary sheet deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete salary sheet: ' . $e->getMessage()]);
        }
    }

    /**
     * Get salary sheets by job ID (AJAX endpoint)
     */
    public function getByJob($jobId)
    {
        try {
            // Access control: officers can only fetch salary sheets for their assigned jobs
            $user = auth()->user();
            if ($user && method_exists($user, 'hasRole') && $user->hasRole('officer')) {
                $job = Job::findOrFail($jobId);
                if ((int) $job->officer_id !== (int) $user->id) {
                    abort(403);
                }
            }

            $salarySheets = SalarySheet::with(['job', 'items.position'])
                ->where('job_id', $jobId)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'salarySheets' => $salarySheets
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch salary sheets: ' . $e->getMessage()
            ], 500);
        }
    }

    public function enforce(Request $request)
    {
        // Debug: Log the incoming request data
        Log::info('Salary Sheet Enforce Request:', $request->all());

        try {
            $job = Job::findOrFail($request->job_id);

            // Prevent enforcing salary sheets for completed jobs
            if ($job->status === 'completed') {
                return redirect()->back()
                    ->withErrors(['error' => 'Cannot enforce salary sheets for completed jobs.'])
                    ->withInput();
            }

            // Access control: officers can only create/update salary sheets for their assigned jobs
            $user = auth()->user();
            if ($user && method_exists($user, 'hasRole') && $user->hasRole('officer')) {
                if ((int) $job->officer_id !== (int) $user->id) {
                    abort(403);
                }
            }

            Log::info('Processing rows:', $request->rows ?? []);

            // SALARY_SHEET TABLE LOGIC: Check if data exists for job_id
            // If exists: UPDATE the existing record
            // If not exists: INSERT new record

            $existingSalarySheet = SalarySheet::where('job_id', $request->job_id)->first();

            if ($existingSalarySheet) {
                // UPDATE: Existing salary sheet found for this job_id
                $salarySheet = $existingSalarySheet;
                $salarySheet->update([
                    'status' => $request->status,
                    'location' => $request->location,
                    'notes' => $request->notes,
                ]);

                Log::info('Updated existing salary sheet for job_id:', [
                    'job_id' => $request->job_id,
                    'sheet_no' => $salarySheet->sheet_no,
                    'updated_data' => $salarySheet->toArray()
                ]);
            } else {
                // INSERT: No existing salary sheet for this job_id, create new
                $maxRetries = 3;
                $retryCount = 0;
                $salarySheet = null;

                while ($retryCount < $maxRetries && !$salarySheet) {
                    try {
                        $sheetNumber = SalarySheet::generateSheetNumber();

                        $salarySheet = SalarySheet::create([
                            'sheet_no' => $sheetNumber,
                            'job_id' => $request->job_id,
                            'status' => $request->status,
                            'location' => $request->location,
                            'notes' => $request->notes,
                            'created_by' => auth()->id(),
                        ]);

                        Log::info('Created new salary sheet for job_id:', [
                            'job_id' => $request->job_id,
                            'sheet_no' => $sheetNumber,
                            'created_data' => $salarySheet->toArray()
                        ]);
                        break; // Success, exit the retry loop

                    } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                        $retryCount++;
                        Log::warning("Duplicate sheet number detected, retry attempt {$retryCount}/{$maxRetries}: {$sheetNumber}");

                        if ($retryCount >= $maxRetries) {
                            throw new \Exception("Failed to generate unique sheet number after {$maxRetries} attempts");
                        }

                        // Small delay before retry
                        usleep(100000); // 100ms
                    }
                }
            }

            // EMPLOYERS_SALARY_SHEET_ITEM TABLE LOGIC: Always remove all items with same sheet_no, then insert everything fresh
            // This ensures clean data without duplicates or orphaned records

            $deletedItemsCount = EmployersSalarySheetItem::where('sheet_no', $salarySheet->sheet_no)->delete();
            Log::info('Removed existing salary sheet items:', [
                'sheet_no' => $salarySheet->sheet_no,
                'deleted_count' => $deletedItemsCount
            ]);

            // Process each promoter row
            $createdItems = [];

            // Debug: Check if salarySheet is properly set
            if (!$salarySheet || !$salarySheet->sheet_no) {
                Log::error('SalarySheet is null or sheet_no is empty:', [
                    'salarySheet' => $salarySheet ? $salarySheet->toArray() : 'null',
                    'existingSalarySheet' => $existingSalarySheet ? 'found' : 'not found'
                ]);
                throw new \Exception('SalarySheet is not properly initialized');
            }

            Log::info('Processing salary sheet items for sheet_no:', ['sheet_no' => $salarySheet->sheet_no]);

            foreach ($request->rows as $rowIndex => $rowData) {
                if (empty($rowData['promoter_id'])) {
                    continue; // Skip empty rows
                }

                Log::info('Processing row data:', $rowData);

                // Get promoter to find position
                $promoter = Promoter::find($rowData['promoter_id']);
                if (!$promoter || !$promoter->position_id) {
                    Log::warning('Promoter not found or no position assigned:', ['promoter_id' => $rowData['promoter_id']]);
                    continue;
                }

                // Structure attendance data properly - handle null values
                $attendanceData = [];
                if (isset($rowData['attendance']) && is_array($rowData['attendance'])) {
                    foreach ($rowData['attendance'] as $date => $value) {
                        // Convert null to 0, and ensure it's an integer
                        $attendanceData[$date] = $value === null ? 0 : (int) $value;
                    }
                }

                // Create structured attendance data with promoter information
                $structuredAttendanceData = [
                    'attendance' => $attendanceData,
                    'total' => (int) ($rowData['attendance_total'] ?? 0),
                    'amount' => (float) ($rowData['attendance_amount'] ?? 0),
                    'promoter_id' => $rowData['promoter_id'],
                    'promoter_name' => $rowData['promoter_name'] ?? 'Unknown',
                    'position' => $rowData['position'] ?? 'Unknown'
                ];

                // Create payment data
                $paymentData = [
                    'amount' => (float) ($rowData['amount'] ?? 0),
                    'food_allowance' => (float) ($rowData['food_allowance'] ?? 0),
                    'expenses' => (float) ($rowData['expenses'] ?? 0),
                    'accommodation_allowance' => (float) ($rowData['accommodation_allowance'] ?? 0),
                    'hold_for_weeks' => (float) ($rowData['hold_for_8_weeks'] ?? 0),
                    'net_amount' => (float) ($rowData['net_amount'] ?? 0)
                ];

                // Create coordinator details
                $coordinatorDetails = null;
                if (!empty($rowData['coordinator_id'])) {
                    $coordinator = Coordinator::find($rowData['coordinator_id']);
                    $coordinatorDetails = [
                        'coordinator_id' => $coordinator->coordinator_id ?? $rowData['coordinator_id'],
                        'current_coordinator' => $coordinator->coordinator_name ?? $rowData['current_coordinator'] ?? 'Unknown',
                        'amount' => (float) ($rowData['coordination_fee'] ?? 0)
                    ];
                }

                // Create the salary sheet item
                $itemNumber = EmployersSalarySheetItem::generateItemNumber();

                $item = EmployersSalarySheetItem::create([
                    'no' => $itemNumber,
                    'location' => $rowData['location'] ?? $request->location,
                    'position_id' => $promoter->position_id,
                    'promoter_id' => $promoter->id,
                    'attendance_data' => $structuredAttendanceData,
                    'payment_data' => $paymentData,
                    'coordinator_details' => $coordinatorDetails,
                    'job_id' => $request->job_id,
                    'sheet_no' => $salarySheet->sheet_no,
                    'allowances_data' => $rowData['allowances'] ?? null,
                ]);

                $createdItems[] = $item->no;

                Log::info('Created salary sheet item:', $item->toArray());
            }

            if (empty($createdItems)) {
                Log::warning('No salary sheet items were created');
                return redirect()->back()
                    ->withErrors(['error' => 'No valid salary sheet items were created. Please ensure at least one promoter is selected.'])
                    ->withInput();
            }

            Log::info('Successfully processed salary sheet with items:', $createdItems);

            $action = $existingSalarySheet ? 'updated' : 'created';




            // Send email notification to reporters if status is 'complete'
            if ($request->status === 'complete') {
                $this->sendCompleteNotificationToReporters($salarySheet);
            }

            return redirect()->route('admin.salary-sheets.index')
                ->with('success', 'Salary sheet ' . $action . ' successfully for job ' . $job->job_number . ': ' . $salarySheet->sheet_no);
        } catch (\Exception $e) {
            dd($e);
            Log::error('Error processing salary sheet:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $action = $existingSalarySheet ? 'update' : 'create';
            return redirect()->back()
                ->withErrors(['error' => 'Failed to ' . $action . ' salary sheet: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Print salary sheet
     */
    public function print(SalarySheet $salarySheet)
    {
        // Access control: officers can only print salary sheets for their assigned jobs
        $user = auth()->user();
        if ($user && method_exists($user, 'hasRole') && $user->hasRole('officer')) {
            $salarySheet->loadMissing('job');
            if (!$salarySheet->job || (int) $salarySheet->job->officer_id !== (int) $user->id) {
                abort(403);
            }
        }

        $salarySheet->load(['job.client', 'job.officer', 'job.reporter', 'items.position']);

        return view('admin.salary-sheets.print', compact('salarySheet'));
    }

    /**
     * Generate JSON data for salary sheet (API endpoint)
     */
    public function generateJsonData($salarySheetId)
    {
        try {
            $salarySheet = SalarySheet::with(['job', 'items.position'])
                ->findOrFail($salarySheetId);

            Log::info('Generating JSON data for salary sheet:', $salarySheet->toArray());

            // Base data structure
            $jsonData = [
                '_token' => csrf_token(),
                'salary_sheet_id' => $salarySheet->id,
                'sheet_number' => $salarySheet->sheet_no,
                'job_id' => (string) $salarySheet->job_id,
                'status' => $salarySheet->status,
                'location' => $salarySheet->location,
                'rows' => [],
                'notes' => $salarySheet->notes
            ];

            // Process each item
            foreach ($salarySheet->items as $index => $item) {
                $rowIndex = $index + 1; // Start from 1, not 0

                // Extract attendance data
                $attendanceData = [];
                if (isset($item->attendance_data['attendance']) && is_array($item->attendance_data['attendance'])) {
                    foreach ($item->attendance_data['attendance'] as $date => $value) {
                        // Convert 0 to null for consistency with your format
                        $attendanceData[$date] = $value == 0 ? null : (string) $value;
                    }
                }

                // Extract payment data
                $paymentData = $item->payment_data ?? [];

                // Extract coordinator data and find the correct coordinator ID
                $coordinatorData = $item->coordinator_details ?? [];
                $coordinatorDatabaseId = null;

                if (!empty($coordinatorData['coordinator_id'])) {
                    // Find coordinator by their custom ID to get the database ID
                    $coordinator = Coordinator::where('coordinator_id', $coordinatorData['coordinator_id'])->first();
                    if ($coordinator) {
                        $coordinatorDatabaseId = $coordinator->id;
                    }
                }

                // Extract allowances data
                $allowancesData = $item->allowances_data ?? [];

                // Build row data
                $rowData = [
                    'location' => $item->location,
                    'promoter_id' => (string) ($item->attendance_data['promoter_id'] ?? ''),
                    'promoter_name' => $item->attendance_data['promoter_name'] ?? '',
                    'position' => $item->attendance_data['position'] ?? ($item->position->position_name ?? ''),
                    'attendance' => $attendanceData,
                    'attendance_total' => (string) ($item->attendance_data['total'] ?? 0),
                    'attendance_amount' => (float)$item->attendance_data['amount'] ?? 0,
                    'amount' => (float) $paymentData['amount'] ?? 0,
                    'food_allowance' => $paymentData['food_allowance'] ?? 0,
                    'expenses' => $paymentData['expenses'] ?? 0,
                    'accommodation_allowance' => $paymentData['accommodation_allowance'] ?? 0,
                    'hold_for_8_weeks' => $paymentData['hold_for_weeks'] ?? 0,
                    'net_amount' => (float) $paymentData['net_amount'] ?? 0,
                    'coordinator_id' => $coordinatorDatabaseId,
                    'current_coordinator' => $coordinatorData['current_coordinator'] ?? null,
                    'coordination_fee' => $coordinatorData['amount'] ?? null,
                    'allowances' => $allowancesData
                ];

                // Convert null values to null (not empty strings)
                foreach ($rowData as $key => $value) {
                    if ($value === '' || $value === '0.00') {
                        $rowData[$key] = null;
                    }
                }

                $jsonData['rows'][(string) $rowIndex] = $rowData;
            }

            Log::info('Generated JSON data:', $jsonData);

            return response()->json($jsonData);

        } catch (\Exception $e) {
            Log::error('Error generating JSON data:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Failed to generate JSON data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a salary sheet (change status from complete to approve)
     * Only accessible by reporter role
     */
    public function approve(Request $request, SalarySheet $salarySheet)
    {
        try {
            $user = auth()->user();

            // Check if user has reporter role
            if (!$user || !method_exists($user, 'hasRole') || !$user->hasRole('reporter')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only reporters can approve salary sheets.'
                ], 403);
            }

            // Check if salary sheet status is 'complete'
            if ($salarySheet->status !== 'complete') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only salary sheets with "complete" status can be approved.'
                ], 400);
            }

            // Update status to 'approve'
            $salarySheet->update([
                'status' => 'approve'
            ]);

            Log::info('Salary sheet approved:', [
                'sheet_no' => $salarySheet->sheet_no,
                'approved_by' => $user->id,
                'approved_at' => now()
            ]);

            // Send email notification to officer
            $this->sendApprovalNotificationToOfficer($salarySheet, $user);

            return response()->json([
                'success' => true,
                'message' => 'Salary sheet ' . $salarySheet->sheet_no . ' has been approved successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error approving salary sheet:', [
                'sheet_id' => $salarySheet->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to approve salary sheet: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send email notification to all reporters when salary sheet status is complete
     */
    private function sendCompleteNotificationToReporters(SalarySheet $salarySheet)
    {
        try {
            $mailDriver = config('mail.default');

            // Check if mail driver is set to 'log' (emails won't actually be sent)
            if ($mailDriver === 'log') {
                Log::warning('Mail driver is set to "log" - emails will be logged but not actually sent. Change MAIL_MAILER to "smtp" in .env to send real emails.');
            }

            Log::info('=== EMAIL NOTIFICATION DEBUG START ===');
            Log::info('Mail driver: ' . $mailDriver);
            Log::info('Mail from: ' . config('mail.from.address'));

            // Get all users with reporter role
            $reporters = User::role('reporter')->get();

            Log::info('Reporters found: ' . $reporters->count());

            if ($reporters->isEmpty()) {
                Log::info('No reporters found to send salary sheet notification');
                return;
            }

            // Load relationships for email
            $salarySheet->load(['job.client']);

            Log::info('Salary sheet loaded:', [
                'sheet_no' => $salarySheet->sheet_no,
                'job_id' => $salarySheet->job_id,
                'has_job' => $salarySheet->job ? 'yes' : 'no',
                'has_client' => ($salarySheet->job && $salarySheet->job->client) ? 'yes' : 'no'
            ]);

            // Send email to each reporter
            foreach ($reporters as $reporter) {
                try {
                    Log::info('Attempting to send email to reporter:', [
                        'reporter_id' => $reporter->id,
                        'reporter_email' => $reporter->email,
                        'reporter_name' => $reporter->name
                    ]);

                    Mail::to($reporter->email)->send(new SalarySheetCompleteNotification($salarySheet));

                    if ($mailDriver === 'log') {
                        Log::info('Email logged (not actually sent - mail driver is "log")', [
                            'reporter_email' => $reporter->email,
                            'sheet_no' => $salarySheet->sheet_no
                        ]);
                    } else {
                        Log::info('Salary sheet notification sent to reporter', [
                            'reporter_email' => $reporter->email,
                            'sheet_no' => $salarySheet->sheet_no,
                            'mail_driver' => $mailDriver
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to send salary sheet notification to reporter', [
                        'reporter_email' => $reporter->email,
                        'sheet_no' => $salarySheet->sheet_no,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            Log::info('=== EMAIL NOTIFICATION DEBUG END ===');
        } catch (\Exception $e) {
            Log::error('Error sending salary sheet notifications to reporters', [
                'sheet_no' => $salarySheet->sheet_no,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Send email notification to officer when salary sheet is approved
     */
    private function sendApprovalNotificationToOfficer(SalarySheet $salarySheet, $approvedBy = null)
    {
        try {
            $mailDriver = config('mail.default');

            // Check if mail driver is set to 'log' (emails won't actually be sent)
            if ($mailDriver === 'log') {
                Log::warning('Mail driver is set to "log" - emails will be logged but not actually sent. Change MAIL_MAILER to "smtp" in .env to send real emails.');
            }

            Log::info('=== APPROVAL EMAIL NOTIFICATION DEBUG START ===');
            Log::info('Mail driver: ' . $mailDriver);
            Log::info('Mail from: ' . config('mail.from.address'));

            // Load job and officer relationship
            $salarySheet->load(['job.officer']);

            // Check if job exists and has an officer
            if (!$salarySheet->job) {
                Log::warning('No job found for salary sheet - cannot send approval notification', [
                    'sheet_no' => $salarySheet->sheet_no
                ]);
                return;
            }

            $officer = $salarySheet->job->officer;

            if (!$officer) {
                Log::warning('No officer assigned to job - cannot send approval notification', [
                    'sheet_no' => $salarySheet->sheet_no,
                    'job_id' => $salarySheet->job->id
                ]);
                return;
            }

            if (!$officer->email) {
                Log::warning('Officer does not have an email address - cannot send approval notification', [
                    'sheet_no' => $salarySheet->sheet_no,
                    'officer_id' => $officer->id
                ]);
                return;
            }

            Log::info('Attempting to send approval email to officer:', [
                'officer_id' => $officer->id,
                'officer_email' => $officer->email,
                'officer_name' => $officer->name,
                'sheet_no' => $salarySheet->sheet_no
            ]);

            // Send email to officer
            Mail::to($officer->email)->send(new SalarySheetApprovedNotification($salarySheet, $approvedBy));

            if ($mailDriver === 'log') {
                Log::info('Approval email logged (not actually sent - mail driver is "log")', [
                    'officer_email' => $officer->email,
                    'sheet_no' => $salarySheet->sheet_no
                ]);
            } else {
                Log::info('Salary sheet approval notification sent to officer', [
                    'officer_email' => $officer->email,
                    'sheet_no' => $salarySheet->sheet_no,
                    'mail_driver' => $mailDriver
                ]);
            }

            Log::info('=== APPROVAL EMAIL NOTIFICATION DEBUG END ===');
        } catch (\Exception $e) {
            Log::error('Error sending salary sheet approval notification to officer', [
                'sheet_no' => $salarySheet->sheet_no,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Export salary sheet to Excel
     */
    public function export(SalarySheet $salarySheet)
    {
        // Access control: officers can only export salary sheets for their assigned jobs
        $user = auth()->user();
        if ($user && method_exists($user, 'hasRole') && $user->hasRole('officer')) {
            $salarySheet->loadMissing('job');
            if (!$salarySheet->job || (int) $salarySheet->job->officer_id !== (int) $user->id) {
                abort(403);
            }
        }

        $salarySheet->load(['job.client', 'items.position']);

        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Salary Management System')
            ->setTitle('Salary Sheet - ' . $salarySheet->sheet_no)
            ->setSubject('Salary Sheet Export')
            ->setDescription('Exported salary sheet data');

        // Header Information
        $row = 1;
        $sheet->setCellValue('A' . $row, 'SALARY SHEET');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(16);
        $sheet->mergeCells('A1:D1');

        $row++;
        $sheet->setCellValue('A' . $row, 'Sheet Number:');
        $sheet->setCellValue('B' . $row, $salarySheet->sheet_no);
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);

        $row++;
        $sheet->setCellValue('A' . $row, 'Status:');
        $sheet->setCellValue('B' . $row, ucfirst($salarySheet->status));

        $row++;
        $sheet->setCellValue('A' . $row, 'Date:');
        $sheet->setCellValue('B' . $row, $salarySheet->created_at->format('Y-m-d'));

        if ($salarySheet->job) {
            $row++;
            $sheet->setCellValue('A' . $row, 'Job Number:');
            $sheet->setCellValue('B' . $row, $salarySheet->job->job_number ?? 'N/A');

            if ($salarySheet->job->client) {
                $row++;
                $sheet->setCellValue('A' . $row, 'Client:');
                $sheet->setCellValue('B' . $row, $salarySheet->job->client->client_name ?? 'N/A');
            }
        }

        if ($salarySheet->location) {
            $row++;
            $sheet->setCellValue('A' . $row, 'Location:');
            $sheet->setCellValue('B' . $row, $salarySheet->location);
        }

        // Load promoters data for bank details
        $promoterIds = [];
        if ($salarySheet->items->count() > 0) {
            foreach ($salarySheet->items as $item) {
                if (isset($item->attendance_data['promoter_id']) && !empty($item->attendance_data['promoter_id'])) {
                    $promoterIds[] = $item->attendance_data['promoter_id'];
                }
            }
        }
        $promoterIds = array_unique($promoterIds);
        $promoters = !empty($promoterIds) ? Promoter::whereIn('id', $promoterIds)->get()->keyBy('id') : collect();

        // Collect all attendance dates
        $allAttendanceDates = [];
        $dynamicAllowances = [];

        if ($salarySheet->items->count() > 0) {
            foreach ($salarySheet->items as $item) {
                if (isset($item->attendance_data['attendance']) && is_array($item->attendance_data['attendance'])) {
                    $dates = array_keys($item->attendance_data['attendance']);
                    $allAttendanceDates = array_merge($allAttendanceDates, $dates);
                }
            }
            $allAttendanceDates = array_unique($allAttendanceDates);
            sort($allAttendanceDates);

            // Extract dynamic allowances from job
            if ($salarySheet->job && isset($salarySheet->job->allowance) && is_array($salarySheet->job->allowance)) {
                $dynamicAllowances = $salarySheet->job->allowance;
            }
        }

        // Table Headers
        $row += 2;
        $startRow = $row;
        $col = 'A';

        // Header row 1
        $headers = ['Item #', 'Location', 'Position', 'Promoter', 'Bank Name', 'Bank Branch', 'Bank Account Number'];

        // Add attendance date columns
        foreach ($allAttendanceDates as $date) {
            $headers[] = \Carbon\Carbon::parse($date)->format('M d');
        }

        $headers[] = 'Total Days';
        $headers[] = 'Attendance Amount';
        $headers[] = 'Base Amount';

        // Add dynamic allowance columns
        foreach ($dynamicAllowances as $allowance) {
            $headers[] = $allowance['allowance_name'] ?? 'Allowance';
        }

        $headers[] = 'Expenses';
        $headers[] = 'Hold for Weeks';
        $headers[] = 'Net Amount';
        $headers[] = 'Coordinator';
        $headers[] = 'Coordination Fee';

        // Write headers
        $colIndex = 1;
        foreach ($headers as $header) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue($colLetter . $row, $header);
            $sheet->getStyle($colLetter . $row)->getFont()->setBold(true);
            $sheet->getStyle($colLetter . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('4472C4');
            $sheet->getStyle($colLetter . $row)->getFont()->getColor()->setRGB('FFFFFF');
            $sheet->getStyle($colLetter . $row)->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($colLetter . $row)->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);
            $colIndex++;
        }

        // Calculate total columns (Item, Location, Position, Promoter, Bank Name, Bank Branch, Bank Account + Attendance dates + Total Days, Att Amount, Base Amount + Allowances + Expenses, Hold, Net, Coordinator, Coord Fee)
        $totalColumns = 7 + count($allAttendanceDates) + 3 + count($dynamicAllowances) + 4;
        $lastColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalColumns);

        // Data rows
        $row++;
        $itemNumber = 1;
        foreach ($salarySheet->items as $item) {
            $colIndex = 1;

            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex++);
            $sheet->setCellValue($colLetter . $row, $item->no);

            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex++);
            $sheet->setCellValue($colLetter . $row, $item->location ?? 'N/A');

            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex++);
            $sheet->setCellValue($colLetter . $row, $item->position->position_name ?? 'N/A');

            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex++);
            $sheet->setCellValue($colLetter . $row, $item->attendance_data['promoter_name'] ?? 'N/A');

            // Bank details - get from promoter data
            $promoterId = isset($item->attendance_data['promoter_id']) ? $item->attendance_data['promoter_id'] : null;
            $promoter = $promoterId && isset($promoters[$promoterId]) ? $promoters[$promoterId] : null;

            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex++);
            $sheet->setCellValue($colLetter . $row, $promoter ? ($promoter->bank_name ?? 'N/A') : 'N/A');

            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex++);
            $sheet->setCellValue($colLetter . $row, $promoter ? ($promoter->bank_branch_name ?? 'N/A') : 'N/A');

            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex++);
            $sheet->setCellValue($colLetter . $row, $promoter ? ($promoter->bank_account_number ?? 'N/A') : 'N/A');

            // Attendance dates
            foreach ($allAttendanceDates as $date) {
                $attendanceValue = isset($item->attendance_data['attendance'][$date]) ? $item->attendance_data['attendance'][$date] : 0;
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex++);
                $sheet->setCellValue($colLetter . $row, $attendanceValue > 0 ? 'P' : 'A');
            }

            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex++);
            $sheet->setCellValue($colLetter . $row, $item->attendance_data['total'] ?? 0);

            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex++);
            $sheet->setCellValue($colLetter . $row, number_format($item->attendance_data['amount'] ?? 0, 2));

            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex++);
            $sheet->setCellValue($colLetter . $row, number_format($item->payment_data['amount'] ?? 0, 2));

            // Dynamic allowances
            foreach ($dynamicAllowances as $allowance) {
                $allowanceName = $allowance['allowance_name'] ?? '';
                $allowanceValue = 0;
                if (isset($item->allowances_data) && is_array($item->allowances_data) && isset($item->allowances_data[$allowanceName])) {
                    $allowanceValue = $item->allowances_data[$allowanceName];
                }
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex++);
                $sheet->setCellValue($colLetter . $row, number_format($allowanceValue, 2));
            }

            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex++);
            $sheet->setCellValue($colLetter . $row, number_format($item->payment_data['expenses'] ?? 0, 2));

            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex++);
            $sheet->setCellValue($colLetter . $row, number_format($item->payment_data['hold_for_weeks'] ?? 0, 2));

            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex++);
            $sheet->setCellValue($colLetter . $row, number_format($item->payment_data['net_amount'] ?? 0, 2));

            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex++);
            $sheet->setCellValue($colLetter . $row, $item->coordinator_details['current_coordinator'] ?? 'N/A');

            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex++);
            $sheet->setCellValue($colLetter . $row, number_format($item->coordinator_details['amount'] ?? 0, 2));

            // Apply borders to data row
            for ($c = 1; $c <= $totalColumns; $c++) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
                $sheet->getStyle($colLetter . $row)->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
            }

            $row++;
            $itemNumber++;
        }

        // Auto-size columns
        for ($c = 1; $c <= $totalColumns; $c++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // Set column widths for better readability (override auto-size for key columns)
        $sheet->getColumnDimension('A')->setWidth(10); // Item #
        $sheet->getColumnDimension('B')->setWidth(15); // Location
        $sheet->getColumnDimension('C')->setWidth(20); // Position
        $sheet->getColumnDimension('D')->setWidth(25); // Promoter
        $sheet->getColumnDimension('E')->setWidth(20); // Bank Name
        $sheet->getColumnDimension('F')->setWidth(20); // Bank Branch
        $sheet->getColumnDimension('G')->setWidth(20); // Bank Account Number

        // Notes section
        if ($salarySheet->notes) {
            $row += 2;
            $sheet->setCellValue('A' . $row, 'Notes:');
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;
            $sheet->setCellValue('A' . $row, $salarySheet->notes);
            $sheet->mergeCells('A' . $row . ':D' . $row);
            $sheet->getStyle('A' . $row)->getAlignment()->setWrapText(true);
        }

        // Create writer and download
        $filename = 'salary_sheet_' . $salarySheet->sheet_no . '_' . date('Y-m-d') . '.xlsx';

        return new StreamedResponse(
            function () use ($spreadsheet) {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'max-age=0',
            ]
        );
    }
}
