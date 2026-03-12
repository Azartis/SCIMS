<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            if (! $user->isActive()) {
                // log the user out if they are inactive or blocked
                auth()->logout();

                $message = 'Your account is ' . ucfirst($user->status) . '. ';
                if ($user->status === 'inactive') {
                    $message .= 'Please contact an administrator to reactivate.';
                } elseif ($user->status === 'blocked') {
                    $message .= 'Access has been blocked; contact support.';
                }

                return redirect()->route('welcome')
                                 ->with('error', $message);
            }
        }

        return $next($request);
    }
}
