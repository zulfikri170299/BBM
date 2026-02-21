<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSystemLockdown
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Setting::isSystemLocked()) {
            // Check if user is NOT a developer
            if (!auth()->check() || !auth()->user()->is_developer) {
                // Allow login and logout routes even during lockdown if user isn't logged in
                // to avoid infinite loops or blocking administrative entry.
                // But generally we redirect to a custom error page.
                if (!$request->is('login') && !$request->is('logout')) {
                    return response()->view('errors.lockdown', [], 503);
                }
            }
        }

        return $next($request);
    }
}
