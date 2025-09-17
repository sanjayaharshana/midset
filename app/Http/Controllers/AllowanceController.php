<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Allowance;
use Illuminate\Support\Facades\Validator;

class AllowanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view allowances')->only(['index', 'show']);
        $this->middleware('permission:create allowances')->only(['create', 'store']);
        $this->middleware('permission:edit allowances')->only(['edit', 'update']);
        $this->middleware('permission:delete allowances')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allowances = Allowance::latest()->paginate(10);
        return view('admin.allowances.index', compact('allowances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.allowances.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Allowance::create($request->all());

        return redirect()->route('admin.allowances.index')
            ->with('success', 'Allowance created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Allowance $allowance)
    {
        return view('admin.allowances.show', compact('allowance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Allowance $allowance)
    {
        return view('admin.allowances.edit', compact('allowance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Allowance $allowance)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $allowance->update($request->all());

        return redirect()->route('admin.allowances.index')
            ->with('success', 'Allowance updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Allowance $allowance)
    {
        $allowance->delete();

        return redirect()->route('admin.allowances.index')
            ->with('success', 'Allowance deleted successfully.');
    }
}
