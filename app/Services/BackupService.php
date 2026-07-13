<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class BackupService
{
    /**
     * Get path of the backups directory.
     */
    protected static function getBackupPath(): string
    {
        $path = storage_path('app/backups');
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
        return $path;
    }

    /**
     * Generate a new database backup.
     */
    public static function generateBackup(): array
    {
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $backupDir = self::getBackupPath();

        if ($driver === 'sqlite') {
            $dbPath = config("database.connections.{$connection}.database");
            $filename = "backup_sqlite_{$timestamp}.sqlite";
            $targetPath = $backupDir . '/' . $filename;
            
            if ($dbPath === ':memory:') {
                File::put($targetPath, "-- SQLite In-Memory Database Backup --\n");
            } elseif (File::exists($dbPath)) {
                File::copy($dbPath, $targetPath);
            } else {
                throw new \Exception("SQLite database file not found at: {$dbPath}");
            }
        } else {
            // MySQL: Generate raw SQL statements (no command line tool needed)
            $filename = "backup_mysql_{$timestamp}.sql";
            $targetPath = $backupDir . '/' . $filename;
            
            $sqlDump = "-- Solcon Production Management System Database Backup\n";
            $sqlDump .= "-- Created at: " . Carbon::now()->toDateTimeString() . "\n";
            $sqlDump .= "-- Database: " . config("database.connections.{$connection}.database") . "\n\n";
            $sqlDump .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            $tables = DB::select('SHOW TABLES');
            $dbName = config("database.connections.{$connection}.database");
            $tableKey = "Tables_in_{$dbName}";

            foreach ($tables as $table) {
                $tableName = $table->$tableKey ?? current((array)$table);
                
                // SHOW CREATE TABLE
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $createSqlKey = 'Create Table';
                $sqlDump .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sqlDump .= $createTable[0]->$createSqlKey ?? array_values((array)$createTable[0])[1];
                $sqlDump .= ";\n\n";

                // SELECT * FROM TABLE
                $rows = DB::table($tableName)->get();
                foreach ($rows as $row) {
                    $rowArray = (array)$row;
                    $fields = array_keys($rowArray);
                    $escapedValues = array_map(function($val) {
                        if ($val === null) return 'NULL';
                        return DB::getPdo()->quote($val);
                    }, array_values($rowArray));
                    
                    $sqlDump .= "INSERT INTO `{$tableName}` (`" . implode('`, `', $fields) . "`) VALUES (" . implode(', ', $escapedValues) . ");\n";
                }
                $sqlDump .= "\n";
            }
            
            $sqlDump .= "SET FOREIGN_KEY_CHECKS=1;\n";
            File::put($targetPath, $sqlDump);
        }

        $size = File::size($targetPath);

        return [
            'filename' => $filename,
            'size' => self::formatBytes($size),
            'date' => Carbon::now()->toDateTimeString(),
        ];
    }

    /**
     * Get backup history lists.
     */
    public static function getBackupHistory(): array
    {
        $backupDir = self::getBackupPath();
        $files = File::files($backupDir);
        $history = [];

        foreach ($files as $file) {
            $history[] = [
                'filename' => $file->getFilename(),
                'size' => self::formatBytes($file->getSize()),
                'size_bytes' => $file->getSize(),
                'date' => Carbon::createFromTimestamp($file->getMTime())->toDateTimeString(),
                'timestamp' => $file->getMTime(),
            ];
        }

        // Sort by date descending
        usort($history, function($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });

        return $history;
    }

    /**
     * Delete a backup file.
     */
    public static function deleteBackup(string $filename): void
    {
        $filePath = self::getBackupPath() . '/' . basename($filename);
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
    }

    /**
     * Get backup path for download.
     */
    public static function downloadBackupPath(string $filename): string
    {
        $filePath = self::getBackupPath() . '/' . basename($filename);
        if (!File::exists($filePath)) {
            abort(404, "Backup file not found.");
        }
        return $filePath;
    }

    /**
     * Format bytes to human readable format.
     */
    protected static function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
