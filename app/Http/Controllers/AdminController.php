<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Client;
use App\Models\Promoter;
use App\Models\Coordinator;
use App\Models\Job;
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
            'total_clients' => Client::count(),
            'total_promoters' => Promoter::count(),
            'total_coordinators' => Coordinator::count(),
            'total_campaigns' => Job::count(),
        ];

        $recent_users = User::latest()->take(5)->get();
        $roles = Role::withCount('users')->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'roles'));
    }
}
