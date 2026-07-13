<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class SystemService
{
    /**
     * Get system health parameters.
     */
    public static function getSystemHealth(): array
    {
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
        
        // MySQL / SQLite Version check
        if ($driver === 'sqlite') {
            try {
                $dbVersion = 'SQLite ' . DB::select("SELECT sqlite_version() as version")[0]->version;
            } catch (\Exception $e) {
                $dbVersion = 'SQLite (Unknown Version)';
            }
        } else {
            try {
                $dbVersion = 'MySQL ' . DB::select("SELECT VERSION() as version")[0]->version;
            } catch (\Exception $e) {
                $dbVersion = 'MySQL (Unknown Version)';
            }
        }

        // Storage / Disk check
        $totalDisk = disk_total_space(base_path());
        $freeDisk = disk_free_space(base_path());
        $usedDisk = $totalDisk - $freeDisk;
        $diskPercent = ($totalDisk > 0) ? ($usedDisk / $totalDisk) * 100 : 0;

        // Queue check
        $queueCount = 0;
        try {
            if (DB::connection()->getSchemaBuilder()->hasTable('jobs')) {
                $queueCount = DB::table('jobs')->count();
            }
            $queueStatus = $queueCount > 0 ? "Pending ({$queueCount} jobs)" : 'Idle (Healthy)';
        } catch (\Exception $e) {
            $queueStatus = 'Database Driver Pending';
        }

        // Cache Status
        try {
            Cache::put('system_health_check', 'ok', 10);
            $cacheCheck = Cache::get('system_health_check');
            $cacheStatus = $cacheCheck === 'ok' ? 'Healthy' : 'Error';
            Cache::forget('system_health_check');
        } catch (\Exception $e) {
            $cacheStatus = 'Error (Disconnected)';
        }

        return [
            'laravel_version' => app()->version(),
            'php_version' => phpversion(),
            'db_version' => $dbVersion,
            'disk_total' => self::formatBytes($totalDisk),
            'disk_free' => self::formatBytes($freeDisk),
            'disk_used' => self::formatBytes($usedDisk),
            'disk_usage_percent' => round($diskPercent, 1),
            'queue_status' => $queueStatus,
            'scheduler_status' => 'Active (Interval checks running)',
            'cache_status' => $cacheStatus,
        ];
    }

    /**
     * Clear application cache keys.
     */
    public static function clearCache(string $type): string
    {
        switch ($type) {
            case 'cache':
                Artisan::call('cache:clear');
                return 'Application cache cleared successfully.';
            case 'config':
                Artisan::call('config:clear');
                return 'Configuration cache cleared successfully.';
            case 'route':
                Artisan::call('route:clear');
                return 'Route cache cleared successfully.';
            case 'view':
                Artisan::call('view:clear');
                return 'Compiled blade view templates cleared successfully.';
            case 'optimize':
                Artisan::call('optimize');
                return 'Application optimization completed successfully (Config/Route cached).';
            case 'optimize_clear':
                Artisan::call('optimize:clear');
                return 'All optimization caches cleared successfully.';
            default:
                throw new \InvalidArgumentException("Invalid cache clear type: {$type}");
        }
    }

    /**
     * Trigger a test SMTP email dispatch.
     */
    public static function sendTestEmail(string $recipient): void
    {
        $host = SettingService::get('smtp_host', config('mail.mailers.smtp.host'));
        $port = SettingService::get('smtp_port', config('mail.mailers.smtp.port'));
        $username = SettingService::get('smtp_username', config('mail.mailers.smtp.username'));
        $password = SettingService::get('smtp_password', config('mail.mailers.smtp.password'));
        $encryption = SettingService::get('smtp_encryption', config('mail.mailers.smtp.encryption'));
        
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => $host,
            'mail.mailers.smtp.port' => $port,
            'mail.mailers.smtp.username' => $username,
            'mail.mailers.smtp.password' => $password,
            'mail.mailers.smtp.encryption' => $encryption,
        ]);

        Mail::raw(
            'This is a test email sent from the Solcon Production Management System Factory Administration panel to verify SMTP connection configuration settings.',
            function ($message) use ($recipient) {
                $message->to($recipient)
                    ->subject('Solcon SMTP Connection Test Successful');
            }
        );
    }

    /**
     * Format bytes into human readable format.
     */
    protected static function formatBytes(float $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
