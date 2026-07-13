<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Log an action in the database.
     */
    public static function log(string $action, string $description, ?int $userId = null, ?string $module = null): ActivityLog
    {
        if (!$module) {
            $module = self::detectModule($action);
        }

        return ActivityLog::create([
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'description' => $description,
            'module' => $module,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Auto-detect the module name based on the action identifier.
     */
    protected static function detectModule(string $action): string
    {
        $actionUpper = strtoupper($action);

        if (str_contains($actionUpper, 'FORMULA')) {
            return 'Formula';
        }
        if (str_contains($actionUpper, 'BATCH') || str_contains($actionUpper, 'PRODUCTION')) {
            return 'Production';
        }
        if (str_contains($actionUpper, 'STOCK') || str_contains($actionUpper, 'LEDGER') || str_contains($actionUpper, 'ADJUSTMENT')) {
            return 'Stock';
        }
        if (str_contains($actionUpper, 'USER') || str_contains($actionUpper, 'PROFILE') || str_contains($actionUpper, 'PASSWORD') || str_contains($actionUpper, 'LOGIN')) {
            return 'User Profile';
        }
        if (str_contains($actionUpper, 'SETTINGS') || str_contains($actionUpper, 'TIMEZONE')) {
            return 'Factory Settings';
        }
        if (str_contains($actionUpper, 'BACKUP')) {
            return 'Database Backup';
        }
        if (str_contains($actionUpper, 'CACHE') || str_contains($actionUpper, 'OPTIMIZE')) {
            return 'Cache Management';
        }
        if (str_contains($actionUpper, 'EMAIL') || str_contains($actionUpper, 'SMTP')) {
            return 'Email Settings';
        }
        if (str_contains($actionUpper, 'MASTER') || str_contains($actionUpper, 'DEPARTMENT') || str_contains($actionUpper, 'MACHINE') || str_contains($actionUpper, 'UNIT') || str_contains($actionUpper, 'BAG_SIZE') || str_contains($actionUpper, 'GRADE')) {
            return 'Masters';
        }

        return 'System';
    }
}
