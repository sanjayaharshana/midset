<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalarySheet;
use App\Models\EmployersSalarySheetItem;
use App\Models\Promoter;
use App\Models\Coordinator;
use App\Models\Job;
use App\Models\Allowance;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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
        $query = SalarySheet::with(['job.client', 'items.position'])
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
        $jobs = Job::with('client')->get();
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

            Log::info('Processing rows:', $request->rows ?? []);

            // Create the main salary sheet
            $sheetNumber = SalarySheet::generateSheetNumber();

            $salarySheet = SalarySheet::create([
                'sheet_no' => $sheetNumber,
                'job_id' => $request->job_id,
                'status' => $request->status,
                'location' => $request->location,
                'notes' => $request->notes,
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

        $salarySheet->load(['job.client', 'items.position']);

        return view('admin.salary-sheets.show', compact('salarySheet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalarySheet $salarySheet)
    {
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
        $jobs = Job::with('client')->get();

        $salarySheet->load(['job', 'items.position']);

        return view('admin.salary-sheets.edit', compact('salarySheet', 'promoters', 'coordinators', 'jobs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalarySheet $salarySheet)
    {
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
            'status' => 'required|in:draft,approved,paid',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
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
            return redirect()->route('admin.salary-sheets.index')
                ->with('success', 'Salary sheet ' . $action . ' successfully for job ' . $job->job_number . ': ' . $salarySheet->sheet_no);
        } catch (\Exception $e) {
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
}
