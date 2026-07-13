<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
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

        // Check if user has the required permission
        if (!$user->hasPermission($permission)) {
            abort(403, 'Unauthorized action. You do not have the required permission.');
        }

        return $next($request);
    }
}
