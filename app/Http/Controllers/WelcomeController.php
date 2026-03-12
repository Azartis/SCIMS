<?php

namespace App\Http\Controllers;

use App\Models\SeniorCitizen;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\View\View;

/**
 * Welcome Controller
 * 
 * Handles the public welcome page and guest statistics
 * All database queries are moved from the view to this controller
 */
class WelcomeController extends Controller
{
    /**
     * Display the application welcome page
     * 
     * @return View
     */
    public function index(): View
    {
        $stats = [
            'senior_citizens' => SeniorCitizen::whereNull('deleted_at')->count(),
            'total_users' => User::count(),
            'audit_logs' => AuditLog::count(),
        ];

        return view('welcome', $stats);
    }
}
