<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promoter;
use App\Models\PromoterPosition;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PromoterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view promoters')->only(['index', 'show']);
        $this->middleware('permission:create promoters')->only(['create', 'store', 'importCsv']);
        $this->middleware('permission:edit promoters')->only(['edit', 'update']);
        $this->middleware('permission:delete promoters')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Promoter::with('position');

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('promoter_name', 'like', "%{$searchTerm}%")
                  ->orWhere('promoter_id', 'like', "%{$searchTerm}%")
                  ->orWhere('identity_card_no', 'like', "%{$searchTerm}%")
                  ->orWhere('phone_no', 'like', "%{$searchTerm}%")
                  ->orWhere('bank_name', 'like', "%{$searchTerm}%")
                  ->orWhere('bank_account_number', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by position
        if ($request->filled('position')) {
            $query->where('position_id', $request->position);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortFields = ['promoter_name', 'promoter_id', 'created_at', 'status'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        $promoters = $query->paginate(20)->withQueryString();

        // Get filter options
        $positions = PromoterPosition::active()->get();
        $statuses = ['active', 'inactive', 'suspended'];

        return view('admin.promoters.index', compact('promoters', 'positions', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $positions = PromoterPosition::active()->get();
        return view('admin.promoters.create', compact('positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'position_id' => 'nullable|exists:promoter_positions,id',
            'promoter_name' => 'required|string|max:255',
            'identity_card_no' => 'required|string|max:20|unique:promoters,identity_card_no',
            'phone_no' => 'required|string|max:20',
            'bank_name' => 'required|string|max:255',
            'bank_branch_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:50',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Generate promoter ID automatically
            $promoterId = Promoter::generatePromoterId();
            
            $promoterData = $request->all();
            $promoterData['promoter_id'] = $promoterId;
            
            Promoter::create($promoterData);

            return redirect()->route('admin.promoters.index')
                ->with('success', 'Promoter created successfully with ID: ' . $promoterId);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create promoter: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Promoter $promoter)
    {
    
        // Load promoter with position
        $promoter->load('position');

        // Get all salary sheet items where this promoter appears
        $salarySheetItems = \App\Models\EmployersSalarySheetItem::where('promoter_id', $promoter->id)
            ->with(['salarySheet.job.client', 'position'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate earnings summary from actual database records
        $earningsSummary = $this->calculateEarningsFromDatabase($promoter, $salarySheetItems);

        // Group salary sheets by month/year for better organization
        $salarySheetsByMonth = $salarySheetItems->groupBy(function ($item) {
            return $item->salarySheet->created_at->format('Y-m');
        });

        // Get recent activity (last 6 months)
        $recentActivity = $salarySheetItems->take(10);

        return view('admin.promoters.show', compact('promoter', 'salarySheetItems', 'earningsSummary', 'salarySheetsByMonth', 'recentActivity'));
    }

    /**
     * Calculate earnings summary from actual database tables
     */
    private function calculateEarningsFromDatabase($promoter, $salarySheetItems)
    {
        // Initialize totals
        $totals = [
            'total_salary_sheets' => 0,
            'total_attendance_amount' => 0,
            'total_net_amount' => 0,
            'total_food_allowance' => 0,
            'total_expenses' => 0,
            'total_accommodation_allowance' => 0,
            'total_coordination_fee' => 0,
            'total_attendance_days' => 0,
            'total_basic_amount' => 0,
            'total_hold_amount' => 0,
        ];

        // Process each salary sheet item
        foreach ($salarySheetItems as $item) {
            $totals['total_salary_sheets']++;

            // Get attendance data from JSON
            $attendanceData = $item->attendance_data ?? [];
            $paymentData = $item->payment_data ?? [];
            $coordinatorData = $item->coordinator_details ?? [];

            // Calculate attendance totals
            $totals['total_attendance_days'] += $attendanceData['total'] ?? 0;
            $totals['total_attendance_amount'] += $attendanceData['amount'] ?? 0;

            // Calculate payment totals
            $totals['total_net_amount'] += $paymentData['net_amount'] ?? 0;
            $totals['total_food_allowance'] += $paymentData['food_allowance'] ?? 0;
            $totals['total_expenses'] += $paymentData['expenses'] ?? 0;
            $totals['total_accommodation_allowance'] += $paymentData['accommodation_allowance'] ?? 0;
            $totals['total_hold_amount'] += $paymentData['hold_for_weeks'] ?? 0;

            // Calculate coordinator fees
            $totals['total_coordination_fee'] += $coordinatorData['amount'] ?? 0;

            // Calculate basic amount (attendance amount - expenses - coordination fee)
            $basicAmount = ($attendanceData['amount'] ?? 0) - ($paymentData['expenses'] ?? 0) - ($coordinatorData['amount'] ?? 0);
            $totals['total_basic_amount'] += max(0, $basicAmount);
        }

        // Get additional statistics from database
        $totals['unique_jobs'] = $salarySheetItems->pluck('job_id')->unique()->count();
        $totals['unique_positions'] = $salarySheetItems->pluck('position_id')->unique()->count();
        $totals['total_salary_sheets_paid'] = $salarySheetItems->where('salarySheet.status', 'paid')->count();
        $totals['total_salary_sheets_approved'] = $salarySheetItems->where('salarySheet.status', 'approved')->count();
        $totals['total_salary_sheets_draft'] = $salarySheetItems->where('salarySheet.status', 'draft')->count();

        // Calculate average earnings per day
        $totals['average_earnings_per_day'] = $totals['total_attendance_days'] > 0 
            ? $totals['total_net_amount'] / $totals['total_attendance_days'] 
            : 0;

        // Calculate average earnings per salary sheet
        $totals['average_earnings_per_sheet'] = $totals['total_salary_sheets'] > 0 
            ? $totals['total_net_amount'] / $totals['total_salary_sheets'] 
            : 0;

        return $totals;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promoter $promoter)
    {
        $positions = PromoterPosition::active()->get();
        return view('admin.promoters.edit', compact('promoter', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Promoter $promoter)
    {
        $validator = Validator::make($request->all(), [
            'position_id' => 'nullable|exists:promoter_positions,id',
            'promoter_name' => 'required|string|max:255',
            'identity_card_no' => 'required|string|max:20|unique:promoters,identity_card_no,' . $promoter->id,
            'phone_no' => 'required|string|max:20',
            'bank_name' => 'required|string|max:255',
            'bank_branch_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:50',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $promoter->update($request->all());

        return redirect()->route('admin.promoters.index')
            ->with('success', 'Promoter updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promoter $promoter)
    {
        $promoterId = $promoter->promoter_id;
        $promoter->delete();

        return redirect()->route('admin.promoters.index')
            ->with('success', 'Promoter ' . $promoterId . ' deleted successfully.');
    }

    /**
     * Import promoters from CSV file
     */
    public function importCsv(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'csv_file' => 'required|file|mimes:csv,txt|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file. Please upload a CSV file (max 2MB).'
                ], 400);
            }

            $file = $request->file('csv_file');
            $csvData = $this->parseCsvFile($file);
            
            if (empty($csvData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'CSV file is empty or invalid format.'
                ], 400);
            }

            $importedCount = 0;
            $skippedCount = 0;
            $errors = [];

            foreach ($csvData as $index => $row) {
                try {
                    $result = $this->processCsvRow($row, $index + 1);
                    if ($result['success']) {
                        $importedCount++;
                    } else {
                        $skippedCount++;
                        $errors[] = "Row " . ($index + 1) . ": " . $result['message'];
                    }
                } catch (\Exception $e) {
                    $skippedCount++;
                    $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
                }
            }

            Log::info('CSV Import completed', [
                'imported_count' => $importedCount,
                'skipped_count' => $skippedCount,
                'total_rows' => count($csvData)
            ]);

            return response()->json([
                'success' => true,
                'imported_count' => $importedCount,
                'skipped_count' => $skippedCount,
                'message' => $importedCount > 0 ? 
                    "Successfully imported {$importedCount} promoters. {$skippedCount} rows were skipped." :
                    "No promoters were imported. All rows were skipped due to validation errors.",
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            Log::error('CSV Import failed', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Parse CSV file and return array of data
     */
    private function parseCsvFile($file)
    {
        $csvData = [];
        $handle = fopen($file->getPathname(), 'r');
        
        if ($handle === false) {
            return [];
        }

        // Get headers
        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            return [];
        }

        // Normalize headers (lowercase, trim, replace spaces with underscores)
        $headers = array_map(function($header) {
            return strtolower(trim(str_replace(' ', '_', $header)));
        }, $headers);

        // Read data rows
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) === count($headers)) {
                $csvData[] = array_combine($headers, $row);
            }
        }

        fclose($handle);
        return $csvData;
    }

    /**
     * Process a single CSV row and create promoter
     */
    private function processCsvRow($row, $rowNumber)
    {
        // Validate required fields
        $requiredFields = ['promoter_name', 'position_name', 'identity_card_no', 'phone_no', 'bank_name', 'bank_branch_name', 'bank_account_number', 'status'];
        
        foreach ($requiredFields as $field) {
            if (empty($row[$field])) {
                return [
                    'success' => false,
                    'message' => "Missing required field: {$field}"
                ];
            }
        }

        // Validate status
        $validStatuses = ['active', 'inactive', 'suspended'];
        if (!in_array(strtolower($row['status']), $validStatuses)) {
            return [
                'success' => false,
                'message' => "Invalid status. Must be one of: " . implode(', ', $validStatuses)
            ];
        }

        // Check if promoter already exists (by phone or ID card)
        $existingPromoter = Promoter::where('phone_no', $row['phone_no'])
            ->orWhere('identity_card_no', $row['identity_card_no'])
            ->first();

        if ($existingPromoter) {
            return [
                'success' => false,
                'message' => "Promoter already exists with phone: {$row['phone_no']} or ID card: {$row['identity_card_no']}"
            ];
        }

        // Find position by name
        $position = PromoterPosition::where('position_name', $row['position_name'])->first();
        if (!$position) {
            return [
                'success' => false,
                'message' => "Position '{$row['position_name']}' not found"
            ];
        }

        // Create promoter
        $promoterData = [
            'promoter_id' => Promoter::generatePromoterId(),
            'position_id' => $position->id,
            'promoter_name' => trim($row['promoter_name']),
            'identity_card_no' => trim($row['identity_card_no']),
            'phone_no' => trim($row['phone_no']),
            'bank_name' => trim($row['bank_name']),
            'bank_branch_name' => trim($row['bank_branch_name']),
            'bank_account_number' => trim($row['bank_account_number']),
            'status' => strtolower(trim($row['status']))
        ];

        Promoter::create($promoterData);

        return [
            'success' => true,
            'message' => "Promoter created successfully"
        ];
    }

    /**
     * Print individual salary slip for a promoter
     */
    public function printSalarySlip(Promoter $promoter, $itemId)
    {
        // Find the salary sheet item
        $item = \App\Models\EmployersSalarySheetItem::where('id', $itemId)
            ->where('promoter_id', $promoter->id)
            ->with(['salarySheet.job.client', 'position'])
            ->first();

        if (!$item) {
            abort(404, 'Salary sheet item not found');
        }

        // Load the salary sheet with job and client
        $salarySheet = $item->salarySheet;
        $salarySheet->load(['job.client']);

        return view('admin.promoters.salary-slip-print', compact('promoter', 'item', 'salarySheet'));
    }
}