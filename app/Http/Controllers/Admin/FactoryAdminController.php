<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BagSize;
use App\Models\ActivityLog;
use App\Services\SettingService;
use App\Services\BackupService;
use App\Services\SystemService;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class FactoryAdminController extends Controller
{
    /**
     * Show the tabbed administration settings index.
     */
    public function settingsIndex()
    {
        $settings = SettingService::all();
        $bagSizes = BagSize::orderBy('value')->get();
        
        $timezones = [
            'UTC' => 'UTC (GMT)',
            'Asia/Kolkata' => 'Asia/Kolkata (IST)',
            'Asia/Dubai' => 'Asia/Dubai (GST)',
            'Europe/London' => 'Europe/London (BST/GMT)',
            'America/New_York' => 'America/New_York (EST/EDT)',
            'Asia/Singapore' => 'Asia/Singapore (SGT)',
        ];

        return view('admin.admin_settings.index', compact('settings', 'bagSizes', 'timezones'));
    }

    /**
     * Update factory, system, smtp, and UI settings.
     */
    public function settingsUpdate(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user && $user->isSuperAdmin();

        $rules = [
            // Factory Settings
            'company_name' => 'required|string|max:100',
            'company_address' => 'nullable|string|max:200',
            'company_phone' => 'nullable|string|max:30',
            'company_email' => 'nullable|email|max:100',
            'gst_number' => 'nullable|string|max:30',
            'default_timezone' => 'required|string|max:50',
            'default_currency' => 'required|string|max:10',
            'default_language' => 'required|string|max:10',
            'report_header' => 'required|string|max:200',
            'report_footer' => 'required|string|max:200',
            'default_bag_size' => 'required|numeric',
            'company_logo' => 'nullable|image|max:2048',

            // System Settings
            'auto_batch_number' => 'required|in:enable,disable',
            'auto_report_generation' => 'required|in:enable,disable',
            'production_timer' => 'required|in:enable,disable',

            // SMTP Settings
            'smtp_host' => 'nullable|string|max:100',
            'smtp_port' => 'nullable|integer',
            'smtp_username' => 'nullable|string|max:100',
            'smtp_password' => 'nullable|string|max:100',
            'smtp_encryption' => 'nullable|string|max:20',

            // UI Settings
            'ui_theme' => 'required|in:light,dark',
            'ui_primary_color' => 'required|in:indigo,cyan,emerald,rose,slate',
            'ui_sidebar_style' => 'required|in:light,dark',
            'ui_compact_mode' => 'required|in:enable,disable',
            'ui_table_density' => 'required|in:normal,dense,spacious',
        ];

        if ($isSuperAdmin) {
            $rules['maintenance_mode'] = 'required|in:enable,disable';
            $rules['maintenance_password'] = 'nullable|string|min:4';
            $rules['maintenance_title'] = 'required|string|max:150';
            $rules['maintenance_message'] = 'required|string|max:1000';
            $rules['maintenance_downtime'] = 'required|string|max:50';
            $rules['maintenance_contact'] = 'required|string|max:100';
            $rules['maintenance_logo'] = 'nullable|image|max:2048';
        } else {
            $rules['maintenance_mode'] = 'nullable|in:enable,disable';
        }

        $request->validate($rules);

        $settingsData = $request->except(['_token', 'company_logo', 'maintenance_logo', 'maintenance_password']);

        if (!$isSuperAdmin) {
            $settingsData = array_diff_key($settingsData, array_flip([
                'maintenance_mode',
                'maintenance_title',
                'maintenance_message',
                'maintenance_downtime',
                'maintenance_contact',
            ]));
        }

        // Handle Company Logo Upload
        if ($request->hasFile('company_logo')) {
            $logo = $request->file('company_logo');
            $logoName = 'company_logo_' . time() . '.' . $logo->getClientOriginalExtension();
            
            if (!file_exists(public_path('images'))) {
                File::makeDirectory(public_path('images'), 0755, true);
            }
            
            $logo->move(public_path('images'), $logoName);
            SettingService::set('company_logo', 'images/' . $logoName);
        }

        // Handle Maintenance Logo Upload
        if ($isSuperAdmin && $request->hasFile('maintenance_logo')) {
            $mLogo = $request->file('maintenance_logo');
            $mLogoName = 'maintenance_logo_' . time() . '.' . $mLogo->getClientOriginalExtension();

            if (!file_exists(public_path('images'))) {
                File::makeDirectory(public_path('images'), 0755, true);
            }

            $mLogo->move(public_path('images'), $mLogoName);
            SettingService::set('maintenance_logo', 'images/' . $mLogoName);
        }

        // Handle Maintenance Password Update
        if ($isSuperAdmin && $request->filled('maintenance_password')) {
            SettingService::set('maintenance_password', \Illuminate\Support\Facades\Hash::make($request->input('maintenance_password')));
        }

        // Also update standard settings keys to maintain compatibility with dashboard
        SettingService::set('factory_name', $request->input('company_name'));
        SettingService::set('timezone', $request->input('default_timezone'));

        // Save bulk settings
        SettingService::updateBulk($settingsData);

        ActivityLogService::log(
            'SETTINGS_UPDATED',
            'Global factory administration and system configurations updated.',
            auth()->id(),
            'Factory Settings'
        );

        return redirect()->back()->with('success', 'Factory administration settings saved successfully.');
    }

    /**
     * Show User Profile page with recent logins history.
     */
    public function profileEdit()
    {
        $user = auth()->user();
        
        $loginHistory = ActivityLog::where('user_id', $user->id)
            ->where('action', 'USER_LOGIN')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.profile.edit', compact('user', 'loginHistory'));
    }

    /**
     * Update user profile information & avatar photo.
     */
    public function profileUpdate(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        // Handle Avatar File Upload
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = 'avatar_' . $user->id . '_' . time() . '.' . $avatar->getClientOriginalExtension();
            
            if (!file_exists(public_path('images/avatars'))) {
                File::makeDirectory(public_path('images/avatars'), 0755, true);
            }

            $avatar->move(public_path('images/avatars'), $avatarName);
            $user->profile_photo = 'images/avatars/' . $avatarName;
        }

        $user->save();

        ActivityLogService::log(
            'PROFILE_UPDATED',
            'User profile contact details and photo updated.',
            $user->id,
            'User Profile'
        );

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Change user profile password.
     */
    public function passwordUpdate(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The provided current password does not match our records.']);
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        ActivityLogService::log(
            'PASSWORD_CHANGED',
            'User account password modified successfully.',
            $user->id,
            'User Profile'
        );

        return redirect()->back()->with('success', 'Password changed successfully.');
    }

    /**
     * Display vertical activity timeline logs with advanced filtering.
     */
    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with('user');

        // Advanced Filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }
        if ($request->filled('module')) {
            $query->where('module', $request->input('module'));
        }
        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . trim($request->input('action')) . '%');
        }
        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', '%' . trim($request->input('ip_address')) . '%');
        }
        if ($request->filled('user_agent')) {
            $query->where('user_agent', 'like', '%' . trim($request->input('user_agent')) . '%');
        }

        // CSV Export Trigger
        if ($request->input('export') === 'csv') {
            $logsToExport = $query->latest('id')->get();
            $csvFileName = 'activity_logs_' . now()->format('Ymd_His') . '.csv';

            ActivityLogService::log(
                'LOGS_EXPORTED',
                "Activity logs CSV exported containing " . $logsToExport->count() . " records.",
                auth()->id(),
                'System'
            );

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$csvFileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];
            
            $columns = ['ID', 'User', 'Action', 'Module', 'Description', 'IP Address', 'User Agent', 'Date Time'];
            
            $callback = function() use($logsToExport, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                
                foreach ($logsToExport as $log) {
                    fputcsv($file, [
                        $log->id,
                        $log->user->name ?? 'System/Guest',
                        $log->action,
                        $log->module,
                        $log->description,
                        $log->ip_address,
                        $log->user_agent,
                        $log->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        }

        $logs = $query->latest('id')->paginate(15)->withQueryString();

        $users = User::orderBy('name')->get();
        
        $modules = ActivityLog::whereNotNull('module')
            ->distinct()
            ->pluck('module')
            ->toArray();

        return view('admin.activity_logs.index', compact('logs', 'users', 'modules'));
    }

    /**
     * Manage database SQL backup files.
     */
    public function backupsIndex()
    {
        $backups = BackupService::getBackupHistory();
        return view('admin.backups.index', compact('backups'));
    }

    /**
     * Trigger dynamic backup creation.
     */
    public function backupGenerate()
    {
        try {
            $backup = BackupService::generateBackup();
            
            ActivityLogService::log(
                'BACKUP_CREATED',
                "Database SQL backup generated successfully: {$backup['filename']} ({$backup['size']}).",
                auth()->id(),
                'Database Backup'
            );

            return redirect()->back()->with('success', 'Database backup file generated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Database backup generation failed: ' . $e->getMessage());
        }
    }

    /**
     * Download SQLite/MySQL SQL backup file.
     */
    public function backupDownload($filename)
    {
        $path = BackupService::downloadBackupPath($filename);

        ActivityLogService::log(
            'BACKUP_DOWNLOADED',
            "Database SQL backup file downloaded: {$filename}.",
            auth()->id(),
            'Database Backup'
        );

        return response()->download($path);
    }

    /**
     * Delete SQLite/MySQL SQL backup file.
     */
    public function backupDestroy($filename)
    {
        BackupService::deleteBackup($filename);

        ActivityLogService::log(
            'BACKUP_DELETED',
            "Database SQL backup file deleted from storage: {$filename}.",
            auth()->id(),
            'Database Backup'
        );

        return redirect()->back()->with('success', 'Database backup file deleted successfully.');
    }

    /**
     * Show server diagnostics, health dashboard, cache options, and mail forms.
     */
    public function systemHealth()
    {
        $health = SystemService::getSystemHealth();
        return view('admin.system.health', compact('health'));
    }

    /**
     * Clear application caches.
     */
    public function clearCache(Request $request)
    {
        $type = $request->input('type');
        
        try {
            $msg = SystemService::clearCache($type);

            ActivityLogService::log(
                'CACHE_CLEARED',
                "System cache cleared: {$type}.",
                auth()->id(),
                'Cache Management'
            );

            return redirect()->back()->with('success', $msg);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Cache clear failed: ' . $e->getMessage());
        }
    }

    /**
     * Trigger test SMTP email dispatch.
     */
    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $recipient = $request->input('email');

        try {
            SystemService::sendTestEmail($recipient);

            ActivityLogService::log(
                'EMAIL_TEST_SENT',
                "SMTP connection test email successfully sent to {$recipient}.",
                auth()->id(),
                'Email Settings'
            );

            return redirect()->back()->with('success', "Test email sent successfully to {$recipient}. Please verify your mailbox / mail logs.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'SMTP email dispatch failed: ' . $e->getMessage());
        }
    }
}
