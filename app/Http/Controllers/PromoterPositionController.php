<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PromoterPosition;
use Illuminate\Support\Facades\Validator;

class PromoterPositionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view promoter positions')->only(['index', 'show']);
        $this->middleware('permission:create promoter positions')->only(['create', 'store']);
        $this->middleware('permission:edit promoter positions')->only(['edit', 'update']);
        $this->middleware('permission:delete promoter positions')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $positions = PromoterPosition::latest()->paginate(10);
        return view('admin.promoter-positions.index', compact('positions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.promoter-positions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'position_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            PromoterPosition::create($request->all());

            return redirect()->route('admin.promoter-positions.index')
                ->with('success', 'Promoter position created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create promoter position: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PromoterPosition $promoterPosition)
    {
        return view('admin.promoter-positions.show', compact('promoterPosition'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PromoterPosition $promoterPosition)
    {
        return view('admin.promoter-positions.edit', compact('promoterPosition'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PromoterPosition $promoterPosition)
    {
        $validator = Validator::make($request->all(), [
            'position_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $promoterPosition->update($request->all());

        return redirect()->route('admin.promoter-positions.index')
            ->with('success', 'Promoter position updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PromoterPosition $promoterPosition)
    {
        $positionName = $promoterPosition->position_name;
        $promoterPosition->delete();

        return redirect()->route('admin.promoter-positions.index')
            ->with('success', 'Promoter position "' . $positionName . '" deleted successfully.');
    }
}