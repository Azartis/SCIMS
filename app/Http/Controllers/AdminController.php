<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SeniorCitizen;
use App\Models\AuditLog;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * AdminController - Admin Dashboard and Management
     * 
     * Handles admin-specific dashboard with system metrics and user management
     */
    public function __construct(private DashboardService $dashboardService)
    {
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
     * Display user management page (same as users.index but marked as admin)
     */
    public function userManagement(Request $request): View
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(15)->appends($request->query());

        return view('admin.user-management', compact('users'));
    }
}
