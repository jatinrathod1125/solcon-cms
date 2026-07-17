<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\SettingService;

class MaintenanceMiddleware
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
        // 1. Check if maintenance mode is enabled
        $maintenanceMode = SettingService::get('maintenance_mode', 'disable');
        if ($maintenanceMode !== 'enable') {
            return $next($request);
        }

        // 2. Exclude specific routes (unlock screen, login, logout, debugbar)
        if ($request->is('unlock') || $request->is('login') || $request->is('logout') || $request->is('_debugbar*')) {
            return $next($request);
        }

        // 3. Exclude Super Admin users (if auto-bypass is enabled)
        $bypassSuperAdmin = SettingService::get('maintenance_bypass_super_admin', 'enable');
        if ($bypassSuperAdmin === 'enable') {
            $user = auth()->user();
            if ($user && $user->isSuperAdmin()) {
                return $next($request);
            }
        }

        // 4. Exclude if the session has been unlocked via password
        if ($request->hasSession() && $request->session()->get('maintenance_unlocked') === true) {
            return $next($request);
        }

        // 5. Handle AJAX/JSON requests
        if ($request->expectsJson()) {
            return response()->json([
                'message' => SettingService::get('maintenance_message', 'System is currently undergoing scheduled maintenance.'),
                'title' => SettingService::get('maintenance_title', 'System Under Maintenance'),
                'downtime' => SettingService::get('maintenance_downtime', '2 hours'),
            ], 503);
        }

        // 6. Otherwise, display the custom premium 503 maintenance page
        $settings = [
            'title' => SettingService::get('maintenance_title', 'System Under Maintenance'),
            'message' => SettingService::get('maintenance_message', 'Solcon ERP is currently undergoing scheduled updates and maintenance. We will be back online shortly.'),
            'downtime' => SettingService::get('maintenance_downtime', '2 hours'),
            'logo' => SettingService::get('maintenance_logo') ?: SettingService::get('company_logo'),
            'contact' => SettingService::get('maintenance_contact', 'support@solcon.com'),
        ];

        return response()->view('errors.503', compact('settings'), 503);
    }
}
