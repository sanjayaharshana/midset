<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_roles' => Role::count(),
            'total_permissions' => Permission::count(),
            'admin_users' => User::role('admin')->count(),
        ];

        $recent_users = User::latest()->take(5)->get();
        $roles = Role::withCount('users')->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'roles'));
    }
}
