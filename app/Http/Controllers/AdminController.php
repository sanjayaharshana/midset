<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\Promoter;
use App\Models\Coordinator;
use App\Models\Job;

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

        // Get last 6 months data for campaigns
        $campaignData = $this->getLast6MonthsData(Job::class, 'created_at');
        
        // Get last 6 months data for promoters
        $promotersData = $this->getLast6MonthsData(Promoter::class, 'created_at');

        return view('admin.dashboard', compact('stats', 'campaignData', 'promotersData'));
    }

    /**
     * Get data for the last 6 months for charts
     */
    private function getLast6MonthsData($model, $dateColumn = 'created_at')
    {
        $months = [];
        $data = [];
        
        // Generate last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');
            $months[] = $monthName;
            
            // Count records for this month
            $count = $model::whereYear($dateColumn, $date->year)
                ->whereMonth($dateColumn, $date->month)
                ->count();
            
            $data[] = $count;
        }
        
        return [
            'labels' => $months,
            'data' => $data
        ];
    }
}
