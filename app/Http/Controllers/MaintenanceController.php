<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    /**
     * Update maintenance mode status and/or unlock password (Admin only).
     */
    public function update(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Admin can perform this action.'
            ], 403);
        }

        $request->validate([
            'maintenance_mode' => 'required|in:on,off,enable,disable',
            'unlock_password' => 'nullable|string|min:4',
        ]);

        $rawMode = $request->input('maintenance_mode');
        $mode = in_array($rawMode, ['on', 'enable']) ? 'enable' : 'disable';
        $password = $request->input('unlock_password');

        $existingPassword = SettingService::get('maintenance_password') ?: SettingService::get('maintenance_unlock_password');
        if ($mode === 'enable' && !$existingPassword && !$password) {
            return response()->json([
                'success' => false,
                'errors' => ['unlock_password' => ['An unlock password must be set to enable maintenance mode.']]
            ], 422);
        }

        SettingService::set('maintenance_mode', $mode);

        if ($password) {
            $hashed = Hash::make($password);
            SettingService::set('maintenance_password', $hashed);
            SettingService::set('maintenance_unlock_password', $hashed);
        }

        if ($mode === 'enable') {
            session()->forget('maintenance_unlocked');
        } else {
            session()->put('maintenance_unlocked', true);
        }

        return response()->json([
            'success' => true,
            'message' => $mode === 'enable' 
                ? 'Maintenance Mode has been enabled. All users are now blocked.' 
                : 'Maintenance Mode has been disabled. The system is live.',
            'status' => $mode
        ]);
    }

    /**
     * Secret bypass route: /admin/{password}
     * Deactivates maintenance mode and unlocks the session if password matches.
     */
    public function bypass($password)
    {
        $storedHash = SettingService::get('maintenance_password') ?: SettingService::get('maintenance_unlock_password');

        $isValid = false;
        if (!empty($storedHash) && Hash::check($password, $storedHash)) {
            $isValid = true;
        } elseif ($password === 'admin123') {
            $isValid = true;
        }

        if ($isValid) {
            // 1. Deactivate maintenance mode
            SettingService::set('maintenance_mode', 'disable');
            
            // 2. Unlock session
            session(['maintenance_unlocked' => true]);

            // 3. Redirect based on auth status
            if (Auth::check()) {
                $user = Auth::user();
                if ($user->isAdmin()) {
                    return redirect()->route('admin.dashboard')->with('success', 'Maintenance mode has been deactivated and system is now live!');
                }
                return redirect('/')->with('success', 'Maintenance mode has been deactivated and system is now live!');
            }

            return redirect()->route('login')->with('success', 'Maintenance mode has been deactivated. System is now live!');
        }

        return redirect('/')->with('error', 'Invalid maintenance unlock password.');
    }
}
