<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DailyBackup extends Command
{
    protected $signature = 'app:daily-backup';

    protected $description = 'Create a daily database backup and rotate older dumps (30-day retention)';

    public function handle(): int
    {
        $this->info('Starting daily database backup...');

        $backupDir = storage_path('app/backups');
        if (! is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        // Prevent web access to backup directory
        $htaccess = $backupDir . DIRECTORY_SEPARATOR . '.htaccess';
        if (! file_exists($htaccess)) {
            file_put_contents($htaccess, "Order Deny,Allow\nDeny from all\n");
        }

        $timestamp = now()->format('Y-m-d_His');
        $filename = "db-backup-{$timestamp}.sql";
        $filepath = $backupDir . DIRECTORY_SEPARATOR . $filename;

        // Get database config
        $connection = config('database.default');
        $dbConfig = config("database.connections.{$connection}");

        if ($connection === 'sqlite') {
            // For SQLite just copy the database file
            $dbPath = $dbConfig['database'];
            if ($dbPath !== ':memory:' && file_exists($dbPath)) {
                copy($dbPath, $filepath);
                $this->info("SQLite database copied to: {$filename}");
            } else {
                $this->warn('SQLite database is in-memory or not found. Skipping backup.');
                return self::SUCCESS;
            }
        } else {
            // MySQL dump
            $host = $dbConfig['host'] ?? '127.0.0.1';
            $port = $dbConfig['port'] ?? 3306;
            $database = $dbConfig['database'] ?? '';
            $username = $dbConfig['username'] ?? 'root';
            $password = $dbConfig['password'] ?? '';

            // Try mysqldump from common locations
            $mysqldump = $this->findMysqldump();

            if (! $mysqldump) {
                $this->error('mysqldump not found. Please ensure MySQL client tools are installed.');
                Log::error('DailyBackup: mysqldump binary not found on server.');
                return self::FAILURE;
            }

            $passwordArg = ! empty($password) ? "--password=" . escapeshellarg($password) : '';
            $command = sprintf(
                '%s --host=%s --port=%s --user=%s %s --single-transaction --routines --triggers %s > %s',
                escapeshellarg($mysqldump),
                escapeshellarg($host),
                escapeshellarg((string) $port),
                escapeshellarg($username),
                $passwordArg,
                escapeshellarg($database),
                escapeshellarg($filepath)
            );

            $returnCode = null;
            $output = [];
            exec($command . ' 2>&1', $output, $returnCode);

            if ($returnCode !== 0) {
                $this->error('mysqldump failed: ' . implode("\n", $output));
                Log::error('DailyBackup: mysqldump failed', ['output' => $output, 'code' => $returnCode]);
                // Remove partial file
                if (file_exists($filepath)) {
                    unlink($filepath);
                }
                return self::FAILURE;
            }
        }

        // Compress the backup
        if (file_exists($filepath) && function_exists('gzencode')) {
            $gzPath = $filepath . '.gz';
            $raw = file_get_contents($filepath);
            file_put_contents($gzPath, gzencode($raw, 9));
            unlink($filepath); // remove uncompressed
            $sizeKb = round(filesize($gzPath) / 1024, 1);
            $this->info("Backup compressed: {$filename}.gz ({$sizeKb} KB)");
            Log::info("DailyBackup: Created {$filename}.gz ({$sizeKb} KB)");
        } elseif (file_exists($filepath)) {
            $sizeKb = round(filesize($filepath) / 1024, 1);
            $this->info("Backup saved (uncompressed): {$filename} ({$sizeKb} KB)");
            Log::info("DailyBackup: Created {$filename} ({$sizeKb} KB)");
        }

        // Rotate: remove backups older than 30 days
        $this->rotateOldBackups($backupDir, 30);

        $this->info('Daily backup completed successfully.');
        return self::SUCCESS;
    }

    private function findMysqldump(): ?string
    {
        // Common paths
        $paths = [
            'mysqldump',                          // system PATH
            '/usr/bin/mysqldump',                  // Linux default
            '/usr/local/bin/mysqldump',            // macOS Homebrew
            'C:\\xampp\\mysql\\bin\\mysqldump.exe', // XAMPP Windows
            '/opt/cpanel/3rdparty/lib/path-bin/mysqldump', // cPanel
        ];

        foreach ($paths as $path) {
            $testCmd = PHP_OS_FAMILY === 'Windows'
                ? "where " . escapeshellarg($path) . " 2>NUL"
                : "which " . escapeshellarg($path) . " 2>/dev/null";

            $result = [];
            $code = null;
            exec($testCmd, $result, $code);

            if ($code === 0 || file_exists($path)) {
                return $path;
            }
        }

        return null;
    }

    private function rotateOldBackups(string $dir, int $retentionDays): void
    {
        $cutoff = now()->subDays($retentionDays)->timestamp;
        $removed = 0;

        foreach (glob($dir . DIRECTORY_SEPARATOR . 'db-backup-*') as $file) {
            if (filemtime($file) < $cutoff) {
                unlink($file);
                $removed++;
            }
        }

        if ($removed > 0) {
            $this->info("Rotated {$removed} backup(s) older than {$retentionDays} days.");
            Log::info("DailyBackup: Rotated {$removed} old backup(s).");
        }
    }
}
