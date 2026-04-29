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

    /**
     * Aperçu sommaire d'un dump SQL (premières lignes non vides, nombre de lignes).
     *
     * @return array{lines: int, preview: string, tables_hint: array<int, string>}
     */
    public function previewSqlFile(string $absolutePath): array
    {
        if (! File::exists($absolutePath)) {
            throw new \InvalidArgumentException('Fichier introuvable.');
        }

        $content = File::get($absolutePath);
        $lines = preg_split('/\R/', $content) ?: [];
        $nonEmpty = array_values(array_filter($lines, fn ($l) => trim((string) $l) !== ''));

        $preview = implode("\n", array_slice($nonEmpty, 0, 15));
        $tablesHint = [];
        foreach (array_slice($nonEmpty, 0, 200) as $line) {
            if (preg_match('/^(CREATE TABLE|INSERT INTO)\s+`?([a-zA-Z0-9_]+)`?/i', $line, $m)) {
                $tablesHint[] = $m[2];
            }
        }
        $tablesHint = array_values(array_unique($tablesHint));

        return [
            'lines' => count($lines),
            'preview' => $preview,
            'tables_hint' => array_slice($tablesHint, 0, 20),
        ];
    }

    /**
     * Restaure la base MySQL depuis un fichier .sql (mysqldump).
     * Attention : écrase les données existantes selon le contenu du dump.
     */
    public function restoreDatabaseFromSql(string $absolutePath): void
    {
        if (! File::exists($absolutePath)) {
            throw new \InvalidArgumentException('Fichier de sauvegarde introuvable.');
        }

        if (pathinfo($absolutePath, PATHINFO_EXTENSION) !== 'sql') {
            throw new \InvalidArgumentException('Seuls les fichiers .sql sont pris en charge.');
        }

        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $command = sprintf(
            'mysql --user=%s --password=%s --host=%s %s < %s',
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($host),
            escapeshellarg($database),
            escapeshellarg($absolutePath)
        );

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \RuntimeException('Échec mysql restore (code '.$returnVar.'). Vérifiez que mysql est dans le PATH et les droits du compte DB.');
        }
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
