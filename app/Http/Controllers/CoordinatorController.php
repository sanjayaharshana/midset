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
    public function index()
    {
        $coordinators = Coordinator::latest()->paginate(10);
        return view('admin.coordinators.index', compact('coordinators'));
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
}