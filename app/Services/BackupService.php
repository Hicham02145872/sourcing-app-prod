<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class BackupService
{
    protected $backupPath;

    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');
        if (! File::exists($this->backupPath)) {
            File::makeDirectory($this->backupPath, 0755, true);
        }
    }

    public function createDatabaseBackup(): string
    {
        $filename = 'db_backup_' . date('Y-m-d_H-i-s') . '.sql';
        $path = $this->backupPath . '/' . $filename;

        // On Windows with XAMPP, we try to use mysqldump if it's in the path
        // Otherwise, we'll do a basic PHP-based dump (simplified)
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($host),
            escapeshellarg($database),
            escapeshellarg($path)
        );

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            // Fallback: Simple PHP dump (very basic)
            return $this->fallbackDatabaseDump($path);
        }

        return $path;
    }

    public function createFilesBackup(): string
    {
        $filename = 'files_backup_' . date('Y-m-d_H-i-s') . '.zip';
        $path = $this->backupPath . '/' . $filename;

        $zip = new ZipArchive();
        if ($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $filesPath = storage_path('app/public');
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($filesPath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                if (! $file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($filesPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
            return $path;
        }

        throw new \Exception('Could not create zip backup.');
    }

    public function listBackups(): array
    {
        $files = File::files($this->backupPath);
        return collect($files)
            ->map(function ($file) {
                return [
                    'name' => $file->getFilename(),
                    'size' => $this->formatBytes($file->getSize()),
                    'date' => date('Y-m-d H:i:s', $file->getMTime()),
                    'path' => $file->getRealPath(),
                ];
            })
            ->sortByDesc('date')
            ->values()
            ->toArray();
    }

    protected function fallbackDatabaseDump($path): string
    {
        // This is a last resort if mysqldump is not available
        $tables = DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $key = "Tables_in_{$dbName}";
        
        $content = "-- Manual Backup " . date('Y-m-d H:i:s') . "\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$key;
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`")[0]->{'Create Table'};
            $content .= "\n\n{$createTable};\n\n";

            $rows = DB::table($tableName)->get();
            foreach ($rows as $row) {
                $values = array_values((array)$row);
                $escapedValues = array_map(function($v) {
                    if (is_null($v)) return 'NULL';
                    return "'" . addslashes($v) . "'";
                }, $values);
                $content .= "INSERT INTO `{$tableName}` VALUES (" . implode(',', $escapedValues) . ");\n";
            }
        }

        File::put($path, $content);
        return $path;
    }

    protected function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
