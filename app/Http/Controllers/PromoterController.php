<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promoter;
use App\Models\PromoterPosition;
use Illuminate\Support\Facades\Validator;

class PromoterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view promoters')->only(['index', 'show']);
        $this->middleware('permission:create promoters')->only(['create', 'store']);
        $this->middleware('permission:edit promoters')->only(['edit', 'update']);
        $this->middleware('permission:delete promoters')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promoters = Promoter::with('position')->latest()->paginate(10);
        return view('admin.promoters.index', compact('promoters'));
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
        return view('admin.promoters.show', compact('promoter'));
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
}