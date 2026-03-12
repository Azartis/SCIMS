<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of users (Admin only).
     */
    public function index(Request $request)
    {
        $this->authorize('isAdmin');

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

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->paginate(10)->appends($request->query());
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $this->authorize('isAdmin');
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('isAdmin');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,staff',
            'status' => 'nullable|in:active,inactive,blocked',
        ]);

        // default to active when creating
        if (empty($validated['status'])) {
            $validated['status'] = 'active';
        }

        try {
            $validated['password'] = bcrypt($validated['password']);
            
            $user = User::create($validated);
            
            \Log::info('User created successfully', ['user_id' => $user->id, 'email' => $user->email]);

            return redirect()->route('users.index')
                            ->with('success', 'User "' . $user->name . '" created successfully!');
        } catch (\Exception $e) {
            \Log::error('Error creating user', ['error' => $e->getMessage()]);
            
            return back()
                ->withInput()
                ->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $this->authorize('isAdmin');
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('isAdmin');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,staff',
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'required|in:active,inactive,blocked',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $oldStatus = $user->status;
        $user->update($validated);

        // If status changed and user has active sessions, notify them
        if ($oldStatus !== $user->status) {
            $this->notifyStatusChange($user);
        }

        // Store the old role before updating
        $oldRole = $user->role;
        $newRole = $validated['role'];

        // If role was changed to admin, redirect to admin dashboard
        if ($oldRole !== 'admin' && $newRole === 'admin') {
            return redirect()->route('admin.dashboard')
                            ->with('success', 'User "' . $user->name . '" has been promoted to Admin! Redirecting to Admin Dashboard...');
        }

        return redirect()->route('users.index')
                        ->with('success', 'User "' . $user->name . '" updated successfully!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('isAdmin');

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                            ->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()->route('users.index')
                        ->with('success', 'User deleted successfully!');
    }

    /**
     * Update only the status of a user (activate/deactivate/block).
     */
    public function updateStatus(Request $request, User $user)
    {
        $this->authorize('isAdmin');

        // ensure database schema has the status column
        if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'status')) {
            // migration likely hasn't been run
            return redirect()->route('users.index')
                            ->with('error', 'Unable to update user status: database column does not exist. Please run the pending migrations.');
        }

        $data = $request->validate([
            'status' => 'required|in:active,inactive,blocked',
        ]);

        $oldStatus = $user->status;
        $user->status = $data['status'];
        $user->save();

        if ($oldStatus !== $user->status) {
            $this->notifyStatusChange($user);
        }

        return redirect()->route('users.index')
                        ->with('success', 'Status for "' . $user->name . '" updated to ' . ucfirst($user->status) . '.');
    }

    /**
     * Notify user of their status change if they have active sessions.
     */
    protected function notifyStatusChange(User $user)
    {
        // if the status column doesn't exist we can't meaningfully notify
        if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'status')) {
            return;
        }

        // check sessions table for active sessions belonging to this user
        $sessions = \DB::table('sessions')->where('user_id', $user->id)->count();
        if ($sessions > 0) {
            // send notification to any active session owner
            $user->notify(new \App\Notifications\AccountStatusChanged($user->status));

            // if the account is no longer active, remove sessions to force logout
            if (! $user->isActive()) {
                \DB::table('sessions')->where('user_id', $user->id)->delete();
            }
        }
    }

    /**
     * Export users to CSV
     */
    public function export()
    {
        $this->authorize('isAdmin');

        // Get all users with optional filters
        $query = User::query();

        if (request()->filled('role')) {
            $query->where('role', request()->role);
        }

        $users = $query->get();

        // Create CSV content
        $csvData = [];
        $csvData[] = ['Name', 'Email', 'Role', 'Status', 'Created At'];

        foreach ($users as $user) {
            $csvData[] = [
                $user->name,
                $user->email,
                ucfirst($user->role),
                ucfirst($user->status),
                $user->created_at->format('Y-m-d H:i:s'),
            ];
        }

        // Create CSV file
        $fileName = 'users_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://memory', 'r+');

        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }
}
