<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // pull active sessions for the user so they can view/log out
        $rawSessions = \DB::table('sessions')
            ->where('user_id', $request->user()->id)
            ->orderBy('last_activity', 'desc')
            ->get();

        $sessions = $rawSessions->map(function ($s) {
            return (object) [
                'id' => $s->id,
                'ip_address' => $s->ip_address,
                'user_agent' => $s->user_agent,
                'last_active' => \Carbon\Carbon::createFromTimestamp($s->last_activity),
            ];
        });

        return view('profile.edit', [
            'user' => $request->user(),
            'sessions' => $sessions,
        ]);
    }

    /**
     * Log out other browser sessions for the user.
     */
    public function destroyOtherSessions(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        // Laravel helper will invalidate other sessions
        Auth::logoutOtherDevices($request->password);

        return Redirect::route('profile.edit')->with('status', 'other-sessions-logged-out');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
