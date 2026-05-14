<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SeniorCitizen;
use App\Models\AuditLog;
use App\Services\DashboardService;
use App\Services\AuthorizationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * AdminController - Admin-specific actions
     * 
     * Redundant user management routes have been consolidated into UserController.
     * This controller now focuses on admin-specific dashboard and operations.
     */
    public function __construct(
        private DashboardService $dashboardService,
        private AuthorizationService $authService
    ) {
    }

    /**
     * Display the admin dashboard
     * Redirects to main dashboard (merged into DashboardController)
     */
    public function dashboard()
    {
        return redirect()->route('dashboard');
    }

    /**
     * DEPRECATED: Use UserController::index instead
     * 
     * This route is kept for backward compatibility
     * but redirects to the centralized UserController
     */
    public function userManagement(Request $request): \Illuminate\Http\RedirectResponse
    {
        // Redirect to the consolidated user management route
        return redirect()->route('users.index')->with(
            'info',
            'User management has been consolidated to a single interface.'
        );
    }
}
