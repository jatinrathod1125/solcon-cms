<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if maintenance mode is ON
        if (Setting::get('maintenance_mode', 'off') !== 'on') {
            return $next($request);
        }

        // 2. Check if trying to access secret URL: /admin/{password}
        if ($request->is('admin/*')) {
            $segments = $request->segments();
            // We want exactly 2 segments: "admin" and "{password}"
            if (count($segments) === 2) {
                $password = $segments[1];
                $hashedPassword = Setting::get('maintenance_unlock_password');

                if ($hashedPassword && Hash::check($password, $hashedPassword)) {
                    // Regenerate session for security
                    $request->session()->regenerate();
                    
                    // Mark as unlocked
                    session(['maintenance_unlocked' => true]);

                    // Redirect to home/dashboard
                    return redirect('/');
                }
            }
        }

        // 3. Check if the session is unlocked
        if ($request->session()->get('maintenance_unlocked') === true) {
            return $next($request);
        }

        // 4. Return Maintenance Page or JSON response for AJAX/API requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'maintenance',
                'message' => 'System is currently under maintenance. Please try again later.'
            ], 503);
        }

        return response()->view('maintenance', [], 503);
    }
}
