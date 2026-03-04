<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BaLog;
use App\Models\Satker;
use App\Models\Setting;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use App\Traits\PaginatesTables;

class BaController extends Controller
{
    use PaginatesTables;

    public function index(Request $request)
    {
        $query = BaLog::with('satker');
        $perPage = $this->getPerPage($request);
        $logs = $query->latest()->paginate($perPage)->withQueryString();
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.ba.index', compact('logs', 'settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->only(['ba_pihak_1_nama', 'ba_pihak_1_pangkat', 'ba_pihak_1_nrp', 'ba_pihak_1_jabatan']);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Memperbarui data Pihak Kesatu Berita Acara"
        ]);

        return back()->with('success', 'Data Pihak Kesatu berhasil diperbarui.');
    }

    private function terbilang($nilai) {
        $nilai = abs($nilai);
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " ". $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = $this->terbilang($nilai - 10). " Belas";
        } else if ($nilai < 100) {
            $temp = $this->terbilang($nilai/10)." Puluh". $this->terbilang($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " Seratus" . $this->terbilang($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = $this->terbilang($nilai/100) . " Ratus" . $this->terbilang($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " Seribu" . $this->terbilang($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = $this->terbilang($nilai/1000) . " Ribu" . $this->terbilang($nilai % 1000);
        }
        return $temp;
    }

    private function toRoman($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    /**
     * Generate BA Otomatis setelah import
     */
    public function automatedGenerate(Satker $satker, array $fuelTotals, $bulan, $tahun)
    {
        try {
            $templatePath = 'E:\\BA.docx';
            
            if (!file_exists($templatePath)) {
                Log::error("Template BA tidak ditemukan di: {$templatePath}");
                return false;
            }

            $templateProcessor = new TemplateProcessor($templatePath);

            $namaHari = [
                'Sunday' => 'Senin', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
            ];
            // Fix map keys and Sunday mapping
            $namaHari = [
                'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
            ];

            $namaBulan = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            $now = now();
            // Allow override month/year if provided
            $currentMonth = (int)($bulan ?? $now->month);
            $currentYear = $tahun ?? $now->year;
            $currentDay = $now->format('d');
            $currentDayName = $namaHari[$now->format('l')] ?? '-';

            // Fill Placeholders (Detected from BA.docx v2)
            $templateProcessor->setValue('satker', strtoupper($satker->nama_satker));
            
            $p_total = number_format($fuelTotals['Pertamax'] ?? 0, 0, ',', '.');
            $d_total = number_format($fuelTotals['Pertamina Dex'] ?? 0, 0, ',', '.');

            $templateProcessor->setValue('total pertamax', $p_total);
            $templateProcessor->setValue('pertamax', 'Pertamax'); 

            $templateProcessor->setValue('total pertamina dex', $d_total);
            $templateProcessor->setValue('pertamina dex', 'Pertamina Dex'); 
            
            $namaBulanValue = $namaBulan[$currentMonth] ?? '-';

            $templateProcessor->setValue('hari_huruf', $currentDayName);
            $templateProcessor->setValue('tanggal_huruf', trim($this->terbilang($currentDay)));
            $templateProcessor->setValue('bulan', $namaBulanValue);
            $templateProcessor->setValue('tahun', $currentYear);
            $templateProcessor->setValue('bulang_angka romawi', $this->toRoman($currentMonth));

            // Fill Pihak Kesatu Data from Settings
            $settings = Setting::all()->pluck('value', 'key');
            $templateProcessor->setValue('nama pihak1', $settings['ba_pihak_1_nama'] ?? '-');
            $templateProcessor->setValue('pangkat pihak1', $settings['ba_pihak_1_pangkat'] ?? '-');
            $templateProcessor->setValue('nrp pihak1', $settings['ba_pihak_1_nrp'] ?? '-');
            $templateProcessor->setValue('jabatan pihak1', $settings['ba_pihak_1_jabatan'] ?? '-');

            // Save File
            // Check for existing BA log for same satker, month, year
            $existingLog = BaLog::where('satker_id', $satker->id)
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->first();

            // Save File - Remove time() suffix to keep it clean if we reuse filename
            $fileName = 'BA_' . str_replace(' ', '_', $satker->nama_satker) . '_' . $bulan . '_' . $tahun . '.docx';
            $storagePath = 'public/berita-acara/' . $fileName;
            
            if (!Storage::exists('public/berita-acara')) {
                Storage::makeDirectory('public/berita-acara');
            }

            // If existing file is different, delete old one
            if ($existingLog && $existingLog->file_path !== $storagePath) {
                Storage::delete($existingLog->file_path);
            }

            $tempPath = tempnam(sys_get_temp_dir(), 'ba');
            $templateProcessor->saveAs($tempPath);
            
            Storage::put($storagePath, file_get_contents($tempPath));
            unlink($tempPath);

            // Save or Update Log
            $data = [
                'satker_id' => $satker->id,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'total_pertamax' => $fuelTotals['Pertamax'] ?? 0,
                'total_dex' => $fuelTotals['Pertamina Dex'] ?? 0,
                'file_path' => $storagePath,
            ];

            if ($existingLog) {
                $existingLog->update($data);
            } else {
                BaLog::create($data);
            }

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => "Menghasilkan Berita Acara otomatis untuk Satker: {$satker->nama_satker} (Bulan: {$bulan}, Tahun: {$tahun})"
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Gagal generate BA untuk {$satker->nama_satker}: " . $e->getMessage());
            return false;
        }
    }

    public function downloadLog(BaLog $log)
    {
        if (!Storage::exists($log->file_path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::download($log->file_path);
    }

    public function destroy(BaLog $log)
    {
        try {
            // Delete physical file
            if (Storage::exists($log->file_path)) {
                Storage::delete($log->file_path);
            }

            // Delete database record
            $log->delete();

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => "Menghapus riwayat Berita Acara: {$log->satker->nama_satker} ({$log->bulan}/{$log->tahun})"
            ]);

            return back()->with('success', 'Riwayat Berita Acara berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error("Gagal menghapus BA Log: " . $e->getMessage());
            return back()->with('error', 'Gagal menghapus riwayat.');
        }
    }
}
