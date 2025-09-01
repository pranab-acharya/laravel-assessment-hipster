<?php

namespace App\Http\Middleware;

use App\Events\UserPresenceChanged;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // For customer
        if (Auth::check()) {
            $user = Auth::user();
            $wasOnline = $user->is_online;

            $user->update([
                'is_online' => true,
                'last_seen' => now(),
            ]);

            if (! $wasOnline) {
                broadcast(new UserPresenceChanged($user, true, 'customer'));
            }
        }
        // For admin
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
            $wasOnline = $admin->is_online;

            $admin->update([
                'is_online' => true,
                'last_seen' => now(),
            ]);

            if (! $wasOnline) {
                broadcast(new UserPresenceChanged($admin, true, 'admin'));
            }
        }

        return $response;
    }
}
