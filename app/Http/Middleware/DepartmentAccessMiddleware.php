<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DepartmentAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $sessionKey = 'current_department_id_' . $user->id;
        $availableDepts = app(\App\Services\DepartmentAccessService::class)->getAvailableDepartments($user);

        // 1. Admin has access to everything; auto-switch session to match the visited path prefix
        if ($user->isAdmin()) {
            $path = $request->path();
            if (str_contains($path, 'grout-production') || str_contains($path, 'grout-colors') || str_contains($path, 'grout-formulas')) {
                $groutDept = $availableDepts->first(fn($d) => strtoupper($d->code) === 'GRT');
                if ($groutDept) {
                    session([$sessionKey => $groutDept->id]);
                }
            } elseif (str_contains($path, 'epoxy-production')) {
                $epoxyDept = $availableDepts->first(fn($d) => in_array(strtoupper($d->code), ['EPX', 'EP'], true));
                if ($epoxyDept) {
                    session([$sessionKey => $epoxyDept->id]);
                }
            } elseif (str_contains($path, 'production') || str_contains($path, 'grades') || str_contains($path, 'formulas')) {
                $tadDept = $availableDepts->first(fn($d) => strtoupper($d->code) === 'TAD');
                if ($tadDept) {
                    session([$sessionKey => $tadDept->id]);
                }
            }
            return $next($request);
        }

        // 2. Supervisor secure check: If the session explicitly requests an unauthorized department, block immediately
        $currentId = session($sessionKey);
        if ($currentId && !$user->canAccessDepartment($currentId)) {
            abort(403, 'You do not have permission to access the active department.');
        }

        // 3. Auto-switch active department session based on visited path prefixes for supervisors
        $path = $request->path();
        if (str_contains($path, 'grout-production') || str_contains($path, 'grout-colors') || str_contains($path, 'grout-formulas')) {
            $groutDept = $availableDepts->first(fn($d) => strtoupper($d->code) === 'GRT');
            if ($groutDept) {
                session([$sessionKey => $groutDept->id]);
            }
        } elseif (str_contains($path, 'epoxy-production')) {
            $epoxyDept = $availableDepts->first(fn($d) => in_array(strtoupper($d->code), ['EPX', 'EP'], true));
            if ($epoxyDept) {
                session([$sessionKey => $epoxyDept->id]);
            }
        } elseif (str_contains($path, 'production') || str_contains($path, 'grades') || str_contains($path, 'formulas')) {
            $tadDept = $availableDepts->first(fn($d) => strtoupper($d->code) === 'TAD');
            if ($tadDept) {
                session([$sessionKey => $tadDept->id]);
            }
        }

        $currentDept = currentDepartment();
        if (!$currentDept) {
            abort(403, 'No assigned department found.');
        }

        // Check if the supervisor user can access the current department
        if (!$user->canAccessDepartment($currentDept->id)) {
            abort(403, 'You do not have permission to access the active department.');
        }

        return $next($request);
    }
}
