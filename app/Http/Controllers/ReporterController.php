<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ReporterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of reporter users.
     */
    public function index()
    {
        // Get users with reporter role
        $reporters = User::role('reporter')->with('roles')->paginate(10);
        return view('admin.reporters.index', compact('reporters'));
    }

    /**
     * Show the form for creating a new reporter.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.reporters.create', compact('roles'));
    }

    /**
     * Store a newly created reporter in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'xelenic_id' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'xelenic_id' => $request->xelenic_id,
        ]);

        // Assign reporter role
        $user->assignRole('reporter');

        return redirect()->route('admin.reporters.index')->with('success', 'Reporter created successfully!');
    }

    /**
     * Display the specified reporter.
     */
    public function show(User $reporter)
    {
        // Ensure the user has reporter role
        if (!$reporter->hasRole('reporter')) {
            abort(404, 'Reporter not found');
        }

        $reporter->load('roles', 'permissions');
        return view('admin.reporters.show', compact('reporter'));
    }

    /**
     * Show the form for editing the specified reporter.
     */
    public function edit(User $reporter)
    {
        // Ensure the user has reporter role
        if (!$reporter->hasRole('reporter')) {
            abort(404, 'Reporter not found');
        }

        $roles = Role::all();
        $userRoles = $reporter->roles->pluck('name')->toArray();
        return view('admin.reporters.edit', compact('reporter', 'roles', 'userRoles'));
    }

    /**
     * Update the specified reporter in storage.
     */
    public function update(Request $request, User $reporter)
    {
        // Ensure the user has reporter role
        if (!$reporter->hasRole('reporter')) {
            abort(404, 'Reporter not found');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $reporter->id,
            'password' => 'nullable|string|min:8|confirmed',
            'xelenic_id' => 'nullable|string|max:255',
            'roles' => 'array',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'xelenic_id' => $request->xelenic_id,
        ];

        // Only update password if provided
        if ($request->password) {
            $updateData['password'] = Hash::make($request->password);
        }

        $reporter->update($updateData);

        // Sync roles if provided
        if ($request->has('roles')) {
            $reporter->syncRoles($request->roles);
        } else {
            // Ensure reporter role is maintained
            if (!$reporter->hasRole('reporter')) {
                $reporter->assignRole('reporter');
            }
        }

        return redirect()->route('admin.reporters.index')->with('success', 'Reporter updated successfully!');
    }

    /**
     * Remove the specified reporter from storage.
     */
    public function destroy(User $reporter)
    {
        // Ensure the user has reporter role
        if (!$reporter->hasRole('reporter')) {
            abort(404, 'Reporter not found');
        }

        $reporter->delete();
        return redirect()->route('admin.reporters.index')->with('success', 'Reporter deleted successfully!');
    }
}
