<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Client;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view jobs')->only(['index', 'show']);
        $this->middleware('permission:create jobs')->only(['create', 'store']);
        $this->middleware('permission:edit jobs')->only(['edit', 'update']);
        $this->middleware('permission:delete jobs')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = Job::with('client')->latest()->paginate(10);
        return view('admin.jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        return view('admin.jobs.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'officer_name' => 'nullable|string|max:255',
            'reporter_officer_name' => 'nullable|string|max:255',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Generate job number automatically
            $jobNumber = Job::generateJobNumber($request->client_id);
            
            $jobData = $request->all();
            $jobData['job_number'] = $jobNumber;
            
            Job::create($jobData);

            return redirect()->route('admin.jobs.index')
                ->with('success', 'Job created successfully with job number: ' . $jobNumber);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create job: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        $job->load('client');
        return view('admin.jobs.show', compact('job'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job)
    {
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        return view('admin.jobs.edit', compact('job', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Job $job)
    {
        $validator = Validator::make($request->all(), [
            'job_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'officer_name' => 'nullable|string|max:255',
            'reporter_officer_name' => 'nullable|string|max:255',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // If client changed, generate new job number
        if ($request->client_id != $job->client_id) {
            try {
                $jobNumber = Job::generateJobNumber($request->client_id);
                $request->merge(['job_number' => $jobNumber]);
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withErrors(['error' => 'Failed to generate new job number: ' . $e->getMessage()])
                    ->withInput();
            }
        }

        $job->update($request->all());

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job updated successfully.');
    }

    /**
     * Update job settings
     */
    public function updateSettings(Request $request, Job $job)
    {
        $validator = Validator::make($request->all(), [
            'default_coordinator_fee' => 'nullable|numeric|min:0',
            'default_hold_for_8_weeks' => 'nullable|numeric|min:0',
            'default_food_allowance' => 'nullable|numeric|min:0',
            'default_accommodation_allowance' => 'nullable|numeric|min:0',
            'default_expenses' => 'nullable|numeric|min:0',
            'default_location' => 'nullable|string|max:255',
            'location_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $job->update([
                'default_coordinator_fee' => $request->default_coordinator_fee,
                'default_hold_for_8_weeks' => $request->default_hold_for_8_weeks,
                'default_food_allowance' => $request->default_food_allowance,
                'default_accommodation_allowance' => $request->default_accommodation_allowance,
                'default_expenses' => $request->default_expenses,
                'default_location' => $request->default_location,
                'location_notes' => $request->location_notes,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Job settings updated successfully.',
                    'job' => $job->fresh()
                ]);
            }

            return redirect()->back()
                ->with('success', 'Job settings updated successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update job settings: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withErrors(['error' => 'Failed to update job settings: ' . $e->getMessage()]);
        }
    }

    /**
     * Update allowance rules for a job
     */
    public function updateAllowanceRules(Request $request, Job $job)
    {
        $validator = Validator::make($request->all(), [
            'allowance' => 'nullable|array',
            'allowance.*.allowance_name' => 'required_with:allowance|string|max:255',
            'allowance.*.price' => 'required_with:allowance|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $job->update([
                'allowance' => $request->allowance ?? []
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Allowance rules updated successfully.',
                'job' => $job->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update allowance rules: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {
        $jobNumber = $job->job_number;
        $job->delete();

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job ' . $jobNumber . ' deleted successfully.');
    }
}