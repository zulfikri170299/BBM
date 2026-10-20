<?php

namespace App\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class DatabaseBackupService
{
    /**
     * Hasilkan file backup database lokal (format .sqlite atau .sql).
     *
     * @return array [ 'file_path' => string, 'file_name' => string, 'file_size' => int, 'connection' => string ]
     */
    public static function createBackupFile(): array
    {
        $connection = config('database.default');
        $backupDir = storage_path('app/backups');

        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');

        if ($connection === 'sqlite') {
            $dbPath = config('database.connections.sqlite.database');
            if (!File::exists($dbPath)) {
                throw new Exception("File database SQLite tidak ditemukan di: {$dbPath}");
            }

            $fileName = "backup_bbm_{$timestamp}.sqlite";
            $filePath = $backupDir . DIRECTORY_SEPARATOR . $fileName;

            // Salin file sqlite secara atomic
            File::copy($dbPath, $filePath);

            return [
                'file_path' => $filePath,
                'file_name' => $fileName,
                'file_size' => filesize($filePath),
                'connection' => 'sqlite',
            ];
        }

        // MySQL Dump
        $database = config('database.connections.mysql.database');
        $fileName = "backup_{$database}_{$timestamp}.sql";
        $filePath = $backupDir . DIRECTORY_SEPARATOR . $fileName;

        $tables = DB::select('SHOW TABLES');
        $sql = "-- Database Backup: {$database}\n";
        $sql .= "-- Created At: " . Carbon::now()->toDateTimeString() . "\n";
        $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

        foreach ($tables as $table) {
            $tableArray = get_object_vars($table);
            $tableName = reset($tableArray);

            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $createTableArray = get_object_vars($createTable[0]);

            $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            $sql .= $createTableArray['Create Table'] . ";\n\n";

            $rows = DB::table($tableName)->get();
            if ($rows->count() > 0) {
                foreach ($rows as $row) {
                    $values = get_object_vars($row);
                    $escapedValues = array_map(function ($val) {
                        if (is_null($val)) return "NULL";
                        return "'" . addslashes((string)$val) . "'";
                    }, $values);

                    $sql .= "INSERT INTO `{$tableName}` VALUES (" . implode(", ", $escapedValues) . ");\n";
                }
                $sql .= "\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";
        File::put($filePath, $sql);

        return [
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => filesize($filePath),
            'connection' => 'mysql',
        ];
    }

    /**
     * Bersihkan file backup lokal yang lebih lama dari X hari.
     */
    public static function cleanOldBackups(int $keepDays = 14): int
    {
        $backupDir = storage_path('app/backups');
        if (!File::exists($backupDir)) {
            return 0;
        }

        $deletedCount = 0;
        $files = File::files($backupDir);
        $threshold = Carbon::now()->subDays($keepDays)->timestamp;

        foreach ($files as $file) {
            if ($file->getMTime() < $threshold) {
                File::delete($file->getRealPath());
                $deletedCount++;
            }
        }

        return $deletedCount;
    }
}
