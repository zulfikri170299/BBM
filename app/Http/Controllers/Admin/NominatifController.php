<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BaLog;
use App\Exports\NominatifExport;
use Maatwebsite\Excel\Facades\Excel;

class NominatifController extends Controller
{
    public static function getSortedLogs($tahun, $bulan) 
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

        return BaLog::with('satker')
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->get()
            ->sortBy(function($log) use ($satkerOrder) {
                $nama = strtoupper(trim($log->satker->nama_satker ?? ''));
                $order = $satkerOrder[$nama] ?? 999;
                return sprintf('%03d-%s', $order, $nama);
            })->values();
    }

    public static function getTriwulan($bulan) 
    {
        if ($bulan >= 4 && $bulan <= 6) return 'II';
        if ($bulan >= 7 && $bulan <= 9) return 'III';
        if ($bulan >= 10 && $bulan <= 12) return 'IV';
        return 'I';
    }

    public function index(Request $request)
    {
        $tahun = $request->input('tahun', now()->year);
        $bulan = $request->input('bulan', now()->month);
        
        $logs = self::getSortedLogs($tahun, $bulan);

        return view('admin.nominatif.index', compact('logs', 'tahun', 'bulan'));
    }

    public function export(Request $request)
    {
        $tahun = $request->input('tahun', now()->year);
        $bulan = $request->input('bulan', now()->month);
        
        $fileName = 'Nominatif_Berita_Acara_' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '_' . $tahun . '.xlsx';
        return Excel::download(new NominatifExport($tahun, $bulan), $fileName);
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->input('tahun', now()->year);
        $bulan = $request->input('bulan', now()->month);
        
        $logs = self::getSortedLogs($tahun, $bulan);

        $namaBulan = \Carbon\Carbon::create()->month((int) $bulan)->translatedFormat('F');
        $tw = self::getTriwulan($bulan);

        $data = [
            'logs' => $logs,
            'tahun' => $tahun,
            'namaBulan' => $namaBulan,
            'tw' => $tw
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.nominatif.pdf', $data)
            ->setPaper('a4', 'portrait');

        $fileName = 'Nominatif_Berita_Acara_' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '_' . $tahun . '.pdf';
        
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
}

