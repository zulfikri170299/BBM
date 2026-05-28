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
        $tahun = $request->input('tahun', now()->year);
        $query = BaLog::with('satker')->where('tahun', $tahun);
        $perPage = $this->getPerPage($request);
        $logs = $query->latest()->paginate($perPage)->withQueryString();
        $settings = Setting::all()->pluck('value', 'key');
        
        $tahunList = BaLog::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        if ($tahunList->isEmpty() || !$tahunList->contains(now()->year)) {
            $tahunList->push(now()->year);
            $tahunList = $tahunList->sortDesc()->values();
        }

        return view('admin.ba.index', compact('logs', 'settings', 'tahun', 'tahunList'));
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
            'aktivitas' => "Memperbarui data Pihak Kesatu Berita Acara dan merestorasi seluruh dokumen"
        ]);

        // Regenerate ALL existing BA files so that the new settings apply to them immediately
        $allLogs = BaLog::with('satker')->get();
        foreach($allLogs as $log) {
            $this->automatedGenerate(
                $log->satker, 
                ['Pertamax' => $log->total_pertamax, 'Pertamina Dex' => $log->total_dex], 
                $log->bulan, 
                $log->tahun,
                true, // isRegenerate
                $log  // pass existing log
            );
        }

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
    public function automatedGenerate(Satker $satker, array $fuelTotals, $bulan, $tahun, $isRegenerate = false, ?BaLog $existingLog = null)
    {
        try {
            $templatePath = public_path('word_media/BA.docx');
            
            if (!file_exists($templatePath)) {
                Log::error("Template BA tidak ditemukan di: {$templatePath}");
                return false;
            }

            $templateProcessor = new TemplateProcessor($templatePath);

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
            $templateProcessor->setValue('satker', ucwords(strtolower($satker->nama_satker)));
            
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

            // Force override on static texts left in the original DOCX Template using Closure binding to access protected property
            $getXml = function() { return $this->tempDocumentMainPart; };
            $setXml = function($xml) { $this->tempDocumentMainPart = $xml; };
            
            $xml = $getXml->call($templateProcessor);
            $xml = str_replace('BIRO LOGISTIK Polda NTB', ucwords(strtolower($satker->nama_satker)) . ' Polda NTB', $xml);
            $xml = str_replace('PIHAK kedua', 'Pihak kedua', $xml);
            // Fix space between Pertamina Dex and sejumlah using positive lookahead to avoid breaking XML tags
            $xml = preg_replace('/(Pertamina\s+Dex)(?=(<[^>]+>)*sejumlah)/i', '$1 ', $xml);
            
            // To handle cases where Word injects spelling-check tags inside the text like PI</w:t>...<w:t>HAK:
            $xml = preg_replace('/P(<[^>]+>)*I(<[^>]+>)*H(<[^>]+>)*A(<[^>]+>)*K(<[^>]+>)*(\s+)(<[^>]+>)*k(<[^>]+>)*e(<[^>]+>)*d(<[^>]+>)*u(<[^>]+>)*a/i', 'Pihak$6$7kedua', $xml);
            
            $setXml->call($templateProcessor, $xml);

            // Save File
            $fileName = 'BA_' . str_replace(' ', '_', $satker->nama_satker) . '_' . $bulan . '_' . $tahun . '_' . time() . '.docx';
            
            if ($isRegenerate && $existingLog) {
                // Keep the exact same filename for regeneration
                $storagePath = $existingLog->file_path;
            } else {
                $storagePath = 'public/berita-acara/' . $fileName;
            }
            
            if (!Storage::exists('public/berita-acara')) {
                Storage::makeDirectory('public/berita-acara');
            }

            // Remove old file ONLY if the storage path is different and it's not a new record
            if ($existingLog && $existingLog->file_path !== $storagePath && Storage::exists($existingLog->file_path)) {
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
                $existingLog->touch();
            } else {
                BaLog::create($data);
            }

            if (!$isRegenerate) {
                LogAktivitas::create([
                    'user_id' => auth()->id(),
                    'aktivitas' => "Menghasilkan Berita Acara otomatis untuk Satker: {$satker->nama_satker} (Bulan: {$bulan}, Tahun: {$tahun})"
                ]);
            }

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

    public function downloadPdf(BaLog $log)
    {
        $settings = Setting::all()->pluck('value', 'key');

        $namaHari = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $witaDate = $log->created_at->setTimezone('Asia/Makassar');
        $currentMonth = $log->bulan;
        $currentYear = $log->tahun;
        $currentDay = $witaDate->format('d');
        $currentDayName = $namaHari[$witaDate->format('l')] ?? '-';

        $data = [
            'log' => $log,
            'settings' => $settings,
            'satker' => ucwords(strtolower($log->satker->nama_satker)),
            'p_total' => number_format($log->total_pertamax, 0, ',', '.'),
            'd_total' => number_format($log->total_dex, 0, ',', '.'),
            'hari_huruf' => $currentDayName,
            'tanggal_huruf' => trim($this->terbilang($currentDay)),
            'bulan' => $namaBulan[$currentMonth] ?? '-',
            'tahun' => $currentYear,
            'bulang_angka_romawi' => $this->toRoman($currentMonth)
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.ba.pdf', $data)
            ->setPaper('a4', 'portrait');

        $fileName = 'BA_' . str_replace(' ', '_', $log->satker->nama_satker) . '_' . $log->bulan . '_' . $log->tahun . '.pdf';
        
        return $pdf->stream($fileName);
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
