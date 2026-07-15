<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    /**
     * Update maintenance mode status and/or unlock password (Admin only).
     */
    public function update(Request $request)
    {
        // Security check: Only Super Admin (role admin) can perform this
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Super Admin can perform this action.'
            ], 403);
        }

        $request->validate([
            'maintenance_mode' => 'required|in:on,off',
            'unlock_password' => 'nullable|string|min:4',
        ]);

        $mode = $request->input('maintenance_mode');
        $password = $request->input('unlock_password');

        // Verify if there is an existing password if we are enabling maintenance mode
        $existingPassword = Setting::get('maintenance_unlock_password');
        if ($mode === 'on' && !$existingPassword && !$password) {
            return response()->json([
                'success' => false,
                'errors' => ['unlock_password' => ['An unlock password must be set to enable maintenance mode.']]
            ], 422);
        }

        // Update settings in database
        Setting::set('maintenance_mode', $mode);

        if ($password) {
            Setting::set('maintenance_unlock_password', Hash::make($password));
        }

        // Lock out the current session immediately upon activation, requiring a password bypass to enter
        session()->forget('maintenance_unlocked');

        return response()->json([
            'success' => true,
            'message' => $mode === 'on' 
                ? 'Maintenance Mode has been enabled. Non-admin users are now blocked.' 
                : 'Maintenance Mode has been disabled. The system is open.',
            'status' => $mode
        ]);
    }

    /**
     * Secret bypass route fallback.
     */
    public function bypass($password)
    {
        // If reached (e.g. maintenance mode is OFF), return 404 to look like a normal non-existent route.
        abort(404);
    }
}
