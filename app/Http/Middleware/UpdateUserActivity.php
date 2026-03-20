<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();



            $expiresAt = now()->addMinutes(2); // User considered online for 2 mins
            Cache::put('user-is-online-' . $user->id, true, $expiresAt);
            
            // Update last_activity_at in DB every once in a while (throttle 1 minute)
            $lastActivity = $user->last_activity_at;
            if (!$lastActivity) {
                $user->update(['last_activity_at' => now()]);
            } else {
                $lastActivity = $lastActivity instanceof \Carbon\Carbon ? $lastActivity : \Carbon\Carbon::parse($lastActivity);
                if ($lastActivity->diffInMinutes(now()) >= 1) {
                    $user->update(['last_activity_at' => now()]);
                }
            }
        }

        return $next($request);
    }
}
