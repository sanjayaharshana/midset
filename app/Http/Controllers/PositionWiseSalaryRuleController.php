<?php

namespace App\Http\Controllers;

use App\Models\PositionWiseSalaryRule;
use App\Models\PromoterPosition;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PositionWiseSalaryRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rules = PositionWiseSalaryRule::with(['position', 'job'])->latest()->get();
        return view('admin.position-wise-salary-rules.index', compact('rules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $positions = PromoterPosition::active()->get();
        $jobs = Job::latest()->get();
        return view('admin.position-wise-salary-rules.create', compact('positions', 'jobs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'position_id' => 'required|exists:promoter_positions,id',
            'job_id' => 'nullable|exists:custom_jobs,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ]);

        // Custom validation for unique combination
        $validator->after(function ($validator) use ($request) {
            $exists = PositionWiseSalaryRule::where('position_id', $request->position_id)
                ->where('job_id', $request->job_id)
                ->exists();
            
            if ($exists) {
                $validator->errors()->add('position_id', 'A salary rule for this position and job combination already exists.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            PositionWiseSalaryRule::create($request->all());

            return redirect()->route('admin.position-wise-salary-rules.index')
                ->with('success', 'Position wise salary rule created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create salary rule: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PositionWiseSalaryRule $positionWiseSalaryRule)
    {
        $positionWiseSalaryRule->load('position');
        return view('admin.position-wise-salary-rules.show', compact('positionWiseSalaryRule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PositionWiseSalaryRule $positionWiseSalaryRule)
    {
        $positions = PromoterPosition::active()->get();
        $jobs = Job::latest()->get();
        return view('admin.position-wise-salary-rules.edit', compact('positionWiseSalaryRule', 'positions', 'jobs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PositionWiseSalaryRule $positionWiseSalaryRule)
    {
        $validator = Validator::make($request->all(), [
            'position_id' => 'required|exists:promoter_positions,id',
            'job_id' => 'nullable|exists:custom_jobs,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ]);

        // Custom validation for unique combination
        $validator->after(function ($validator) use ($request, $positionWiseSalaryRule) {
            $exists = PositionWiseSalaryRule::where('position_id', $request->position_id)
                ->where('job_id', $request->job_id)
                ->where('id', '!=', $positionWiseSalaryRule->id)
                ->exists();
            
            if ($exists) {
                $validator->errors()->add('position_id', 'A salary rule for this position and job combination already exists.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $positionWiseSalaryRule->update($request->all());

            return redirect()->route('admin.position-wise-salary-rules.index')
                ->with('success', 'Position wise salary rule updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update salary rule: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PositionWiseSalaryRule $positionWiseSalaryRule)
    {
        try {
            \Log::info('Attempting to delete salary rule', [
                'rule_id' => $positionWiseSalaryRule->id,
                'position_id' => $positionWiseSalaryRule->position_id,
                'job_id' => $positionWiseSalaryRule->job_id
            ]);
            
            $positionWiseSalaryRule->delete();

            // Check if request expects JSON response (AJAX)
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Position wise salary rule deleted successfully.'
                ]);
            }

            return redirect()->route('admin.position-wise-salary-rules.index')
                ->with('success', 'Position wise salary rule deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Failed to delete salary rule', [
                'error' => $e->getMessage(),
                'rule_id' => $positionWiseSalaryRule->id ?? 'unknown'
            ]);
            
            // Check if request expects JSON response (AJAX)
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete salary rule: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete salary rule: ' . $e->getMessage()]);
        }
    }

    /**
     * Store multiple salary rules via AJAX
     */
    public function storeMultiple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rules' => 'required|array|min:1',
            'rules.*.position_id' => 'required|exists:promoter_positions,id',
            'rules.*.job_id' => 'nullable|exists:custom_jobs,id',
            'rules.*.amount' => 'required|numeric|min:0',
            'rules.*.description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $createdRules = [];
            $skippedRules = [];

            foreach ($request->rules as $ruleData) {
                // Check if rule already exists for this position and job combination
                $existingRule = PositionWiseSalaryRule::where('position_id', $ruleData['position_id'])
                    ->where('job_id', $ruleData['job_id'] ?? null)
                    ->first();
                
                if ($existingRule) {
                    $skippedRules[] = $ruleData['position_id'];
                    continue;
                }

                $rule = PositionWiseSalaryRule::create([
                    'position_id' => $ruleData['position_id'],
                    'job_id' => $ruleData['job_id'] ?? null,
                    'amount' => $ruleData['amount'],
                    'description' => $ruleData['description'] ?? null,
                    'status' => 'active',
                ]);

                $createdRules[] = $rule;
            }

            $message = count($createdRules) . ' salary rule(s) created successfully.';
            if (count($skippedRules) > 0) {
                $message .= ' ' . count($skippedRules) . ' rule(s) skipped (already exist).';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'created_count' => count($createdRules),
                'skipped_count' => count($skippedRules)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create salary rules: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get salary rules for AJAX requests
     */
    public function getRules()
    {
        $rules = PositionWiseSalaryRule::with(['position', 'job'])->active()->get();
        return response()->json($rules);
    }
}