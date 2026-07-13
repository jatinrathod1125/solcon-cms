<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user is active
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been deactivated.',
            ]);
        }

        // Check if user has any of the required roles
        if (!$user->hasRole($roles)) {
            abort(403, 'Unauthorized action. You do not have the required role.');
        }

        return $next($request);
    }
}
