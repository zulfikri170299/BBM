<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\GoogleDriveService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class BackupDatabaseController extends Controller
{
    public function index()
    {
        $gdriveFolderUrl = Setting::where('key', 'backup_gdrive_folder_url')->value('value') ?? '';
        $gdriveFolderId = Setting::where('key', 'backup_gdrive_folder_id')->value('value') ?? '';
        $gdriveEnabled = Setting::where('key', 'backup_gdrive_enabled')->value('value') === '1';
        $serviceAccountJson = Setting::where('key', 'backup_gdrive_service_account_json')->value('value');
        $webhookUrl = Setting::where('key', 'backup_gdrive_webhook_url')->value('value') ?? '';
        $lastRun = Setting::where('key', 'backup_gdrive_last_run')->value('value');
        $lastStatus = Setting::where('key', 'backup_gdrive_last_status')->value('value');
        $lastFileName = Setting::where('key', 'backup_gdrive_last_filename')->value('value');
        $gdriveTime = Setting::where('key', 'backup_gdrive_time')->value('value') ?: '23:00';
        $gdriveTimezone = Setting::where('key', 'backup_gdrive_timezone')->value('value') ?: 'WITA';

        $serviceAccountEmail = '';
        if (!empty($serviceAccountJson)) {
            $parsed = json_decode($serviceAccountJson, true);
            $serviceAccountEmail = $parsed['client_email'] ?? '';
        }

        // Ambil daftar file backup lokal yang tersimpan
        $localBackups = [];
        $backupDir = storage_path('app/backups');
        if (File::exists($backupDir)) {
            $files = File::files($backupDir);
            usort($files, fn($a, $b) => $b->getMTime() <=> $a->getMTime());
            foreach (array_slice($files, 0, 10) as $f) {
                $localBackups[] = [
                    'name' => $f->getFilename(),
                    'size' => number_format($f->getSize() / 1024, 2) . ' KB',
                    'time' => Carbon::createFromTimestamp($f->getMTime())->format('d M Y, H:i:s'),
                ];
            }
        }

        return view('admin.backup.index', compact(
            'gdriveFolderUrl',
            'gdriveFolderId',
            'gdriveEnabled',
            'gdriveTime',
            'gdriveTimezone',
            'serviceAccountEmail',
            'webhookUrl',
            'lastRun',
            'lastStatus',
            'lastFileName',
            'localBackups'
        ));
    }

    public function export(Request $request)
    {
        $request->validate([
            'topup_password' => 'required|string',
        ], [
            'topup_password.required' => 'PIN Wajib diisi.',
            'topup_password.string' => 'Format PIN tidak valid.'
        ]);

        $user = auth()->user();

        // Cek apakah user sudah mengatur password topup
        if (!$user->topup_password) {
            return back()->withErrors(['message' => 'Anda belum mengatur PIN Keamanan. Silakan atur di menu Profil terlebih dahulu.']);
        }

        // Verifikasi password topup
        if (!Hash::check($request->topup_password, $user->topup_password)) {
            return back()->withErrors(['message' => 'PIN Keamanan salah! Proses dibatalkan demi keamanan.']);
        }

        $connection = config('database.default');
        $storagePath = storage_path('app/temp');
        
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        if ($connection === 'sqlite') {
            $dbPath = config('database.connections.sqlite.database');
            $fileName = 'backup_bbm_' . Carbon::now()->format('Y-m-d_H-i-s') . '.sqlite';
            $filePath = $storagePath . '/' . $fileName;

            try {
                File::copy($dbPath, $filePath);
                return response()->download($filePath)->deleteFileAfterSend(true);
            } catch (\Exception $e) {
                return back()->withErrors(['message' => 'Gagal menyalin file SQLite: ' . $e->getMessage()]);
            }
        }

        // MySQL Fallback
        $database = config('database.connections.mysql.database');
        $fileName = 'backup_' . $database . '_' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';
        $filePath = $storagePath . '/' . $fileName;

        try {
            $tables = DB::select('SHOW TABLES');
            $sql = "SET FOREIGN_KEY_CHECKS = 0;\n\n";

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

            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Terjadi kesalahan sistem saat mengekspor: ' . $e->getMessage()]);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file',
            'topup_password' => 'required|string',
        ], [
            'backup_file.required' => 'File Backup wajib diisi.',
            'topup_password.required' => 'PIN Wajib diisi.',
            'topup_password.string' => 'Format PIN tidak valid.'
        ]);

        $user = auth()->user();

        // Cek apakah user sudah mengatur password topup
        if (!$user->topup_password) {
            return back()->withErrors(['message' => 'Anda belum mengatur PIN Keamanan (Top-up). Silakan atur di menu Profil terlebih dahulu untuk merestore data.']);
        }

        // Verifikasi password topup
        if (!Hash::check($request->topup_password, $user->topup_password)) {
            return back()->withErrors(['message' => 'PIN Keamanan salah! Proses dibatalkan demi keamanan.']);
        }

        $file = $request->file('backup_file');
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, ['sql', 'txt', 'sqlite', 'db'])) {
            return back()->withErrors(['message' => 'Format file tidak didukung. Harap unggah file .sqlite atau .sql']);
        }

        $connection = config('database.default');

        // Simpan sesi dan user login saat ini sebelum restore agar tidak hilang saat SQLite di-overwrite
        $currentSessionId = session()->getId();
        $currentSessionData = null;
        try {
            $currentSessionData = DB::table('sessions')->where('id', $currentSessionId)->first();
        } catch (\Throwable $e) {}
        $currentUserId = auth()->id();

        try {
            if ($connection === 'sqlite' && in_array($extension, ['sqlite', 'db'])) {
                $dbPath = config('database.connections.sqlite.database');
                File::copy($file->getRealPath(), $dbPath);

                // Pulihkan session user yang sedang aktif ke database yang baru
                if ($currentSessionData) {
                    try {
                        DB::table('sessions')->updateOrInsert(
                            ['id' => $currentSessionId],
                            (array) $currentSessionData
                        );
                    } catch (\Throwable $e) {}
                }

                if ($currentUserId) {
                    try {
                        auth()->loginUsingId($currentUserId);
                    } catch (\Throwable $e) {}
                }

                session()->flash('success', 'Database SQLite berhasil di-restore dan seluruh data telah dipulihkan sepenuhnya!');
                session()->save();

                return back();
            }

            $sql = file_get_contents($file->getRealPath());
            DB::unprepared($sql);
            
            session()->flash('success', 'Database berhasil di-restore dan seluruh data telah dipulihkan sepenuhnya!');
            session()->save();

            return back();
        } catch (\Throwable $e) {
            return back()->withErrors(['message' => 'Gagal memulihkan database: ' . $e->getMessage()]);
        }
    }

    public function saveGoogleDriveSettings(Request $request)
    {
        $request->validate([
            'backup_gdrive_folder_url' => 'nullable|string',
            'backup_gdrive_time' => 'nullable|string',
            'backup_gdrive_timezone' => 'nullable|in:WITA,WIB',
            'service_account_file' => 'nullable|file|max:2048',
            'service_account_json' => 'nullable|string',
            'backup_gdrive_webhook_url' => 'nullable|string',
        ]);

        $folderUrl = trim($request->backup_gdrive_folder_url ?? '');
        $folderId = null;

        if (!empty($folderUrl)) {
            $folderId = GoogleDriveService::extractFolderId($folderUrl);
            if (!$folderId) {
                return back()->withErrors(['message' => 'Format Link Folder Google Drive tidak dikenali. Masukkan URL folder (misal: https://drive.google.com/drive/folders/xxxx) atau Folder ID yang valid.']);
            }
        }

        Setting::updateOrCreate(['key' => 'backup_gdrive_folder_url'], ['value' => $folderUrl]);
        Setting::updateOrCreate(['key' => 'backup_gdrive_folder_id'], ['value' => $folderId]);
        Setting::updateOrCreate(['key' => 'backup_gdrive_enabled'], ['value' => $request->has('backup_gdrive_enabled') ? '1' : '0']);
        Setting::updateOrCreate(['key' => 'backup_gdrive_time'], ['value' => $request->input('backup_gdrive_time', '23:00')]);
        Setting::updateOrCreate(['key' => 'backup_gdrive_timezone'], ['value' => $request->input('backup_gdrive_timezone', 'WITA')]);

        if ($request->has('backup_gdrive_webhook_url')) {
            Setting::updateOrCreate(['key' => 'backup_gdrive_webhook_url'], ['value' => trim($request->backup_gdrive_webhook_url ?? '')]);
        }

        // Handle Service Account File Upload atau Raw Text JSON
        if ($request->hasFile('service_account_file')) {
            $jsonContent = file_get_contents($request->file('service_account_file')->getRealPath());
            $decoded = json_decode($jsonContent, true);
            if (!is_array($decoded) || empty($decoded['client_email']) || empty($decoded['private_key'])) {
                return back()->withErrors(['message' => 'File JSON Service Account tidak valid. Pastikan file adalah file kredensial Google Cloud Service Account resmi.']);
            }
            Setting::updateOrCreate(['key' => 'backup_gdrive_service_account_json'], ['value' => $jsonContent]);
        } elseif ($request->filled('service_account_json')) {
            $jsonContent = trim($request->service_account_json);
            $decoded = json_decode($jsonContent, true);
            if (!is_array($decoded) || empty($decoded['client_email']) || empty($decoded['private_key'])) {
                return back()->withErrors(['message' => 'Teks JSON Service Account tidak valid. Pastikan memiliki client_email dan private_key.']);
            }
            Setting::updateOrCreate(['key' => 'backup_gdrive_service_account_json'], ['value' => $jsonContent]);
        }

        session()->flash('success', 'Pengaturan otomatisasi Backup Google Drive berhasil disimpan!');
        session()->save();

        return back();
    }

    public function testGoogleDriveBackup()
    {
        @set_time_limit(300);
        @ini_set('max_execution_time', '300');
        @ini_set('memory_limit', '512M');

        try {
            Artisan::call('backup:database-to-drive', ['--force' => true]);
            $lastStatus = Setting::where('key', 'backup_gdrive_last_status')->value('value') ?? '';

            if ($lastStatus === 'success') {
                session()->flash('success', 'Tes Berhasil! Database telah diekspor dan sukses terunggah ke Google Drive Anda.');
                session()->save();
                return back();
            }

            if (str_starts_with($lastStatus, 'warning:')) {
                session()->flash('warning', substr($lastStatus, 8));
                session()->save();
                return back();
            }

            if (str_starts_with($lastStatus, 'error:')) {
                return back()->withErrors(['message' => 'Gagal upload: ' . substr($lastStatus, 6)]);
            }

            session()->flash('success', 'Proses backup selesai.');
            session()->save();
            return back();
        } catch (\Throwable $e) {
            return back()->withErrors(['message' => 'Terjadi kesalahan saat menguji backup: ' . $e->getMessage()]);
        }
    }

    public function downloadLocalBackup($fileName)
    {
        $safeName = basename($fileName);
        $filePath = storage_path('app/backups/' . $safeName);

        if (!File::exists($filePath)) {
            return back()->withErrors(['message' => 'File backup lokal tidak ditemukan atau telah kedaluwarsa.']);
        }

        return response()->download($filePath);
    }
}
