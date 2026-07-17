<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnlockRequest;
use App\Services\SettingService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;

class MaintenanceUnlockController extends Controller
{
    /**
     * Show the maintenance mode unlock password form.
     */
    public function showUnlockForm()
    {
        // If maintenance mode is not active, no need to show the unlock screen
        $maintenanceMode = SettingService::get('maintenance_mode', 'disable');
        if ($maintenanceMode !== 'enable') {
            return redirect('/');
        }

        return view('admin.settings.unlock');
    }

    /**
     * Verify the unlock password and bypass maintenance mode for this session.
     */
    public function unlock(UnlockRequest $request)
    {
        $throttleKey = 'maintenance_unlock_attempts:' . $request->ip();

        // 1. Check rate limits (5 attempts per minute)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            ActivityLogService::log(
                'MAINTENANCE_UNLOCK_BLOCKED',
                "Rate limit exceeded for maintenance unlock attempts from IP: {$request->ip()}.",
                Auth::id() ?: null,
                'System'
            );

            return back()->withErrors([
                'password' => "Too many attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        $password = $request->input('password');
        $storedHash = SettingService::get('maintenance_password');

        // 2. Validate password
        if ($storedHash && Hash::check($password, $storedHash)) {
            // Success
            RateLimiter::clear($throttleKey);

            // Regenerate session to prevent session fixation attacks
            $request->session()->regenerate();
            $request->session()->put('maintenance_unlocked', true);

            ActivityLogService::log(
                'MAINTENANCE_UNLOCKED',
                "Maintenance bypass unlocked successfully via password from IP: {$request->ip()}.",
                Auth::id() ?: null,
                'System'
            );

            return redirect('/')->with('success', 'Maintenance bypass unlocked.');
        }

        // Failure
        RateLimiter::hit($throttleKey, 60);

        ActivityLogService::log(
            'MAINTENANCE_UNLOCK_FAILED',
            "Failed maintenance unlock attempt with incorrect password from IP: {$request->ip()}.",
            Auth::id() ?: null,
            'System'
        );

        return back()->withErrors([
            'password' => 'Invalid unlock password.',
        ]);
    }
}
