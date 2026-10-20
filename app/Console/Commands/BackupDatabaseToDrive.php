<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Services\DatabaseBackupService;
use App\Services\GoogleDriveService;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BackupDatabaseToDrive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database-to-drive {--force : Jalankan meskipun fitur auto-backup dinonaktifkan}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Melakukan backup database otomatis dan mengunggahnya ke Google Drive';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai proses backup database...');

        $isEnabled = Setting::where('key', 'backup_gdrive_enabled')->value('value') === '1';
        $force = $this->option('force');

        if (!$isEnabled && !$force) {
            $this->warn('Auto-backup ke Google Drive sedang dinonaktifkan. Gunakan --force untuk tetap menjalankan.');
            return Command::SUCCESS;
        }

        // 1. Ekspor database
        try {
            $backup = DatabaseBackupService::createBackupFile();
            $this->info("Backup database berhasil dibuat: {$backup['file_name']} (" . number_format($backup['file_size'] / 1024, 2) . ' KB)');
        } catch (Exception $e) {
            $errorMsg = 'Gagal membuat backup database: ' . $e->getMessage();
            $this->error($errorMsg);
            Log::error("[AutoBackup] {$errorMsg}");

            Setting::updateOrCreate(['key' => 'backup_gdrive_last_run'], ['value' => Carbon::now()->toDateTimeString()]);
            Setting::updateOrCreate(['key' => 'backup_gdrive_last_status'], ['value' => 'error: ' . $e->getMessage()]);

            return Command::FAILURE;
        }

        // 2. Ambil konfigurasi Google Drive
        $folderId = Setting::where('key', 'backup_gdrive_folder_id')->value('value');
        $serviceAccountJson = Setting::where('key', 'backup_gdrive_service_account_json')->value('value');
        $webhookUrl = Setting::where('key', 'backup_gdrive_webhook_url')->value('value');

        // Simpan info backup lokal
        Setting::updateOrCreate(['key' => 'backup_gdrive_last_run'], ['value' => Carbon::now()->toDateTimeString()]);
        Setting::updateOrCreate(['key' => 'backup_gdrive_last_filename'], ['value' => $backup['file_name']]);

        // Cek jika Google Drive belum diatur
        if (empty($folderId) && empty($webhookUrl)) {
            $warningMsg = 'Backup lokal berhasil disimpan, namun Link Folder Google Drive belum diatur di menu Backup Database.';
            $this->warn($warningMsg);
            Log::warning("[AutoBackup] {$warningMsg}");

            Setting::updateOrCreate(['key' => 'backup_gdrive_last_status'], ['value' => 'warning: ' . $warningMsg]);
            return Command::SUCCESS;
        }

        // 3. Upload ke Google Drive
        try {
            $this->info('Mengunggah file backup ke Google Drive...');

            if (!empty($serviceAccountJson) && !empty($folderId)) {
                $credentials = json_decode($serviceAccountJson, true);
                if (!is_array($credentials)) {
                    throw new Exception('Kredensial Google Service Account JSON tidak valid.');
                }

                $uploadResult = GoogleDriveService::uploadViaServiceAccount(
                    $backup['file_path'],
                    $backup['file_name'],
                    $folderId,
                    $credentials
                );

                $this->info("Berhasil diunggah ke Google Drive! File ID: {$uploadResult['file_id']}");
                Setting::updateOrCreate(['key' => 'backup_gdrive_last_status'], ['value' => 'success']);
                Setting::updateOrCreate(['key' => 'backup_gdrive_last_file_id'], ['value' => $uploadResult['file_id']]);

                Log::info("[AutoBackup] Berhasil backup ke Google Drive: {$backup['file_name']} (ID: {$uploadResult['file_id']})");
            } elseif (!empty($webhookUrl) && !empty($folderId)) {
                $uploadResult = GoogleDriveService::uploadViaWebhook(
                    $backup['file_path'],
                    $backup['file_name'],
                    $folderId,
                    $webhookUrl
                );

                $this->info("Berhasil diunggah via Webhook! File ID: {$uploadResult['file_id']}");
                Setting::updateOrCreate(['key' => 'backup_gdrive_last_status'], ['value' => 'success']);
                Log::info("[AutoBackup] Berhasil backup via Webhook Google Drive: {$backup['file_name']}");
            } else {
                throw new Exception('Kredensial Service Account JSON atau Webhook URL belum diisi.');
            }
        } catch (Exception $e) {
            $errorMsg = 'Gagal upload ke Google Drive: ' . $e->getMessage();
            $this->error($errorMsg);
            Log::error("[AutoBackup] {$errorMsg}");

            Setting::updateOrCreate(['key' => 'backup_gdrive_last_status'], ['value' => 'error: ' . $e->getMessage()]);
            return Command::FAILURE;
        }

        // 4. Bersihkan backup lokal lama
        DatabaseBackupService::cleanOldBackups(14);

        return Command::SUCCESS;
    }
}
