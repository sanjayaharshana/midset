<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalarySheet;
use App\Models\EmployersSalarySheetItem;
use App\Models\Promoter;
use App\Models\Coordinator;
use App\Models\Job;
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
        $salarySheets = SalarySheet::with(['job', 'items.position'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

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

        return view('admin.salary-sheets.create', compact('promoters', 'coordinators', 'jobs'));
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

                // Calculate net amount
                $totalEarnings = $paymentData['amount'] + $paymentData['food_allowance'] +
                                $paymentData['accommodation_allowance'] + (float) ($rowData['coordination_fee'] ?? 0);
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
                    'attendance_data' => $structuredAttendanceData,
                    'payment_data' => $paymentData,
                    'coordinator_details' => $coordinatorDetails,
                    'job_id' => $request->job_id,
                    'sheet_no' => $sheetNumber,
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
        $salarySheet->load(['job.client', 'items.position']);

        return view('admin.salary-sheets.show', compact('salarySheet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalarySheet $salarySheet)
    {
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

    public function enforce(Request  $request)
    {
        dd($request);
    }
}
