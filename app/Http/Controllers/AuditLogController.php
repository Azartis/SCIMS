<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs.
     */
    public function index(Request $request)
    {
        $query = AuditLog::query();

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

        // Search by IP address
        if ($request->filled('search')) {
            $query->where('ip_address', 'like', "%{$request->search}%");
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
