<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class BackupDatabaseController extends Controller
{
    public function index()
    {
        return view('admin.backup.index');
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
        if (!\Illuminate\Support\Facades\Hash::check($request->topup_password, $user->topup_password)) {
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
                            return "'" . addslashes($val) . "'";
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
        if (!\Illuminate\Support\Facades\Hash::check($request->topup_password, $user->topup_password)) {
            return back()->withErrors(['message' => 'PIN Keamanan salah! Proses dibatalkan demi keamanan.']);
        }

        $file = $request->file('backup_file');
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, ['sql', 'txt', 'sqlite', 'db'])) {
            return back()->withErrors(['message' => 'Format file tidak didukung. Harap unggah file .sqlite atau .sql']);
        }

        $connection = config('database.default');

        try {
            if ($connection === 'sqlite' && in_array($extension, ['sqlite', 'db'])) {
                $dbPath = config('database.connections.sqlite.database');
                // Back up the current sqlite first (optional, but let's just replace it directly)
                File::copy($file->getRealPath(), $dbPath);
                return back()->with('success', 'Database SQLite berhasil di-restore dan dipulihkan sepenuhnya!');
            }

            $sql = file_get_contents($file->getRealPath());
            DB::unprepared($sql);
            
            return back()->with('success', 'Database berhasil di-restore dan dipulihkan sepenuhnya!');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Gagal memulihkan database: ' . $e->getMessage()]);
        }
    }
}
