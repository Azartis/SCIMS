<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs.
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        // Filter by event type
        if ($request->filled('event') && $request->event !== '') {
            $query->where('event', $request->event);
        }

        // Filter by user
        if ($request->filled('user_id') && $request->user_id !== '') {
            $query->where('user_id', $request->user_id);
        }

        // Filter by model type
        if ($request->filled('model') && $request->model !== '') {
            $query->where('auditable_type', 'App\\Models\\' . $request->model);
        }

        // Date range
        if ($request->filled('date_from')) {
            $from = Carbon::parse($request->date_from)->startOfDay();
            $query->where('created_at', '>=', $from);
        }
        if ($request->filled('date_to')) {
            $to = Carbon::parse($request->date_to)->endOfDay();
            $query->where('created_at', '<=', $to);
        }

        // Free-text search: IP, URL, user agent
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                  ->orWhere('url', 'like', "%{$search}%")
                  ->orWhere('user_agent', 'like', "%{$search}%");
            });
        }

        $auditLogs = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->query());
        $users = \App\Models\User::orderBy('name')->get();

        return view('audit-logs.index', compact('auditLogs', 'users'));
    }

    /**
     * Display the specified audit log.
     */
    public function show($id)
    {
        $auditLog = AuditLog::findOrFail($id);

        return view('audit-logs.show', compact('auditLog'));
    }
}
