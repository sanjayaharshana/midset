<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coordinator;
use Illuminate\Support\Facades\Validator;

class CoordinatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view coordinators')->only(['index', 'show']);
        $this->middleware('permission:create coordinators')->only(['create', 'store']);
        $this->middleware('permission:edit coordinators')->only(['edit', 'update']);
        $this->middleware('permission:delete coordinators')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Coordinator::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('coordinator_name', 'like', "%{$searchTerm}%")
                  ->orWhere('coordinator_id', 'like', "%{$searchTerm}%")
                  ->orWhere('nic_no', 'like', "%{$searchTerm}%")
                  ->orWhere('phone_no', 'like', "%{$searchTerm}%")
                  ->orWhere('bank_name', 'like', "%{$searchTerm}%")
                  ->orWhere('account_number', 'like', "%{$searchTerm}%");
            });
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
        
        $allowedSortFields = ['coordinator_name', 'coordinator_id', 'created_at', 'status'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        $coordinators = $query->paginate(20)->withQueryString();

        // Get filter options
        $statuses = ['active', 'inactive', 'suspended'];

        return view('admin.coordinators.index', compact('coordinators', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.coordinators.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coordinator_name' => 'required|string|max:255',
            'nic_no' => 'required|string|max:20|unique:coordinators,nic_no',
            'phone_no' => 'required|string|max:20',
            'bank_name' => 'required|string|max:255',
            'bank_branch_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Generate coordinator ID automatically
            $coordinatorId = Coordinator::generateCoordinatorId();
            
            $coordinatorData = $request->all();
            $coordinatorData['coordinator_id'] = $coordinatorId;
            
            Coordinator::create($coordinatorData);

            return redirect()->route('admin.coordinators.index')
                ->with('success', 'Coordinator created successfully with ID: ' . $coordinatorId);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create coordinator: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Coordinator $coordinator)
    {
        return view('admin.coordinators.show', compact('coordinator'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coordinator $coordinator)
    {
        return view('admin.coordinators.edit', compact('coordinator'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coordinator $coordinator)
    {
        $validator = Validator::make($request->all(), [
            'coordinator_name' => 'required|string|max:255',
            'nic_no' => 'required|string|max:20|unique:coordinators,nic_no,' . $coordinator->id,
            'phone_no' => 'required|string|max:20',
            'bank_name' => 'required|string|max:255',
            'bank_branch_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $coordinator->update($request->all());

        return redirect()->route('admin.coordinators.index')
            ->with('success', 'Coordinator updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coordinator $coordinator)
    {
        $coordinatorId = $coordinator->coordinator_id;
        $coordinator->delete();

        return redirect()->route('admin.coordinators.index')
            ->with('success', 'Coordinator ' . $coordinatorId . ' deleted successfully.');
    }

    /**
     * Lightweight AJAX search for coordinators by name or ID
     */
    public function ajaxSearch(Request $request)
    {
        $q = trim($request->get('q', ''));
        $excludeIds = (array) $request->get('exclude', []);
        $limit = (int) ($request->get('limit', 10));
        if ($limit <= 0 || $limit > 50) { $limit = 10; }

        if ($q === '') {
            return response()->json(['data' => []]);
        }

        $results = Coordinator::when(!empty($excludeIds), function ($query) use ($excludeIds) {
                $query->whereNotIn('id', $excludeIds);
            })
            ->where(function($query) use ($q) {
                $query->where('coordinator_name', 'like', "%{$q}%")
                    ->orWhere('coordinator_id', 'like', "%{$q}%")
                    ->orWhere('phone_no', 'like', "%{$q}%");
            })
            ->orderBy('coordinator_name')
            ->limit($limit)
            ->get()
            ->map(function($c) {
                return [
                    'id' => $c->id,
                    'coordinator_id' => $c->coordinator_id,
                    'coordinator_name' => $c->coordinator_name,
                    'phone_no' => $c->phone_no,
                    'nic_no' => $c->nic_no,
                    'bank_name' => $c->bank_name,
                    'account_number' => $c->account_number,
                    'status' => $c->status,
                ];
            });

        return response()->json(['data' => $results]);
    }
}