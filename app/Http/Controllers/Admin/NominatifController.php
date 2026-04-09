<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BaLog;
use App\Exports\NominatifExport;
use Maatwebsite\Excel\Facades\Excel;

class NominatifController extends Controller
{
    public static function getSortedLogs($tahun, $bulan, $startDate = null, $endDate = null) 
    {
        $satkerOrder = [
            'SPRIPIM' => 1, 'ITWASDA' => 2, 'BIRO OPS' => 3, 'BIRO RENA' => 4,
            'BIRO SDM' => 5, 'BIRO LOGISTIK' => 6, 'DIT INTELKAM' => 7,
            'DIT RESKRIMUM' => 8, 'DIT RESKRIMSUS' => 9, 'DIT RES PPA DAN PPO' => 10,
            'DIT RESNARKOBA' => 11, 'DIT BINMAS' => 12, 'DIT TAHTI' => 13,
            'BID PROPAM' => 14, 'BID HUMAS' => 15, 'BID TIK' => 16, 'BID KEU' => 17,
            'BID DOKKES' => 18, 'BID KUM' => 19, 'RUMKIT BHAYANGKARA' => 20,
            'SETUM' => 21, 'SPKT' => 22
        ];

        // Jika rentang tanggal tidak diisi, gunakan awal s/d akhir bulan
        if (!$startDate || !$endDate) {
            $startDate = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth()->toDateTimeString();
            $endDate = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth()->toDateTimeString();
        }

        $transactions = \App\Models\RiwayatTopup::with('satker')
            ->where('tipe', 'masuk')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $logs = $transactions->groupBy(function($item) {
            return $item->satker_id . '-' . \Carbon\Carbon::parse($item->created_at)->format('Y-m-d');
        })->map(function ($items) {
            return (object) [
                'satker_id' => $items->first()->satker_id,
                'satker' => $items->first()->satker,
                'tanggal' => $items->first()->created_at,
                'total_pertamax' => $items->where('jenis_bbm', 'Pertamax')->sum('jumlah'),
                'total_dex' => $items->where('jenis_bbm', 'Pertamina Dex')->sum('jumlah'),
            ];
        })->values();

        return $logs->sortBy(function($log) use ($satkerOrder) {
            $nama = strtoupper(trim($log->satker->nama_satker ?? ''));
            $order = $satkerOrder[$nama] ?? 999;
            // Gunakan format Y-m-d untuk pengurutan tanggal yang benar
            $tgl = isset($log->tanggal) ? \Carbon\Carbon::parse($log->tanggal)->format('Y-m-d') : '0000-00-00';
            return sprintf('%03d-%s-%s', $order, $nama, $tgl);
        })->values();
    }

    public static function getTriwulan($bulan) 
    {
        if ($bulan >= 4 && $bulan <= 6) return 'II';
        if ($bulan >= 7 && $bulan <= 9) return 'III';
        if ($bulan >= 10 && $bulan <= 12) return 'IV';
        return 'I';
    }

    public static function getPeriodeText($tahun, $bulan, $startDate = null, $endDate = null)
    {
        if ($startDate && $endDate) {
            return \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') . ' S/D ' . \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y');
        }
        $namaBulan = \Carbon\Carbon::create()->month((int) $bulan)->translatedFormat('F');
        return strtoupper($namaBulan) . ' T.A. ' . $tahun;
    }

    public function index(Request $request)
    {
        $tahun = $request->input('tahun', now()->year);
        $bulan = $request->input('bulan', now()->month);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $logs = self::getSortedLogs($tahun, $bulan, $startDate, $endDate);
        $periodeText = self::getPeriodeText($tahun, $bulan, $startDate, $endDate);

        return view('admin.nominatif.index', compact('logs', 'tahun', 'bulan', 'startDate', 'endDate', 'periodeText'));
    }

    public function export(Request $request)
    {
        $tahun = $request->input('tahun', now()->year);
        $bulan = $request->input('bulan', now()->month);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $fileName = 'Nominatif_Berita_Acara_' . ($startDate ? $startDate . '_to_' . $endDate : str_pad($bulan, 2, '0', STR_PAD_LEFT) . '_' . $tahun) . '.xlsx';
        return Excel::download(new NominatifExport($tahun, $bulan, $startDate, $endDate), $fileName);
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->input('tahun', now()->year);
        $bulan = $request->input('bulan', now()->month);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $logs = self::getSortedLogs($tahun, $bulan, $startDate, $endDate);
        $periodeText = self::getPeriodeText($tahun, $bulan, $startDate, $endDate);

        $namaBulan = \Carbon\Carbon::create()->month((int) $bulan)->translatedFormat('F');
        $tw = self::getTriwulan($bulan);

        $data = [
            'logs' => $logs,
            'tahun' => $tahun,
            'namaBulan' => $namaBulan,
            'tw' => $tw,
            'periodeText' => $periodeText,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.nominatif.pdf', $data)
            ->setPaper('a4', 'portrait');

        $fileName = 'Nominatif_Berita_Acara_' . ($startDate ? $startDate . '_to_' . $endDate : str_pad($bulan, 2, '0', STR_PAD_LEFT) . '_' . $tahun) . '.pdf';
        
        return $pdf->download($fileName);
    }

    public function updateSettings(Request $request)
    {
        $data = $request->only(['nominatif_nama', 'nominatif_pangkat', 'nominatif_nrp', 'nominatif_jabatan']);

        foreach ($data as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }

        return redirect()->back()->with('success', 'Konfigurasi Penandatangan Nominatif berhasil disimpan.');
    }

    public function destroyGroup(Request $request)
    {
        $request->validate([
            'satker_id' => 'required|exists:satkers,id',
            'tanggal' => 'required|date'
        ]);

        $satkerId = $request->satker_id;
        $tanggal = \Carbon\Carbon::parse($request->tanggal)->format('Y-m-d');

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($satkerId, $tanggal) {
                // Ambil semua riwayat topup untuk satker ini pada tanggal tersebut
                $riwayats = \App\Models\RiwayatTopup::where('satker_id', $satkerId)
                    ->whereDate('created_at', $tanggal)
                    ->where('tipe', 'masuk')
                    ->get();

                foreach ($riwayats as $riwayat) {
                    // Cukup hapus record riwayat tanpa mengubah saldo kendaraan/admin
                    $riwayat->delete();
                }

                \App\Models\LogAktivitas::create([
                    'user_id' => auth()->id(),
                    'aktivitas' => "Menghapus catatan laporan nominatif Satker ID: {$satkerId} pada tanggal {$tanggal} (Tanpa memotong saldo)"
                ]);
            });

            return redirect()->back()->with('success', 'Catatan laporan berhasil dihapus. Saldo kendaraan tidak berubah.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Gagal menghapus catatan nominatif: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

