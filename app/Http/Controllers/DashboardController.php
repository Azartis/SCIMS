<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SeniorCitizen;
use App\Models\AuditLog;
use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * DashboardService - handles all dashboard data aggregation
     */
    public function __construct(private DashboardService $dashboardService)
    {
    }

    /**
     * Display the application dashboard
     *
     * Orchestrates dashboard data via service layer.
     * Shows admin dashboard if user is admin, otherwise shows regular dashboard.
     */
    public function index(): View
    {
        // Service handles:
        // - Caching strategy (cache tags, TTL values)
        // - Data aggregation (metrics, charts)
        // - Performance optimization (eager loading)
        // - Error handling
        $dashboardData = $this->dashboardService->getDashboardData();

        // Check if user is admin and show admin dashboard
        if (auth()->user()->role === 'admin') {
            $adminMetrics = [
                'totalUsers' => User::count(),
                'adminCount' => User::where('role', 'admin')->count(),
                'staffCount' => User::where('role', 'staff')->count(),
                'recentUsers' => User::latest()->take(5)->get(),
                'totalRecords' => SeniorCitizen::count(),
                'recentChanges' => AuditLog::latest()->take(10)->get(),
                'auditLogsCount' => AuditLog::count(),
            ];

            return view('admin.dashboard', [
                'dashboardData' => $dashboardData,
                'adminMetrics' => $adminMetrics,
            ]);
        }

        return view('dashboard', [
            'dashboardData' => $dashboardData,
        ]);
    }
}
