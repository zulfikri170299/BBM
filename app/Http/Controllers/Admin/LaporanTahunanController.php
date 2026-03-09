<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RiwayatTopup;
use App\Models\TransaksiBbm;
use App\Models\Satker;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanTahunanController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));
        
        $satkers = Satker::orderBy('nama_satker')->get();
        $reportData = [];

        foreach ($satkers as $satker) {
            // Pendapatan: RiwayatTopup (tipe='masuk') for the year
            $pendapatanPertamax = RiwayatTopup::where('satker_id', $satker->id)
                ->where('tipe', 'masuk')
                ->where(function($q) {
                    $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX');
                })
                ->whereYear('created_at', $year)
                ->sum('jumlah');

            $pendapatanDex = RiwayatTopup::where('satker_id', $satker->id)
                ->where('tipe', 'masuk')
                ->where(function($q) {
                    $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX');
                })
                ->whereYear('created_at', $year)
                ->sum('jumlah');

            // Pemakaian: TransaksiBbm for the year
            $pemakaianPertamax = TransaksiBbm::where('satker_id', $satker->id)
                ->where(function($q) {
                    $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX');
                })
                ->whereYear('tanggal', $year)
                ->sum('liter');

            $pemakaianDex = TransaksiBbm::where('satker_id', $satker->id)
                ->where(function($q) {
                    $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX');
                })
                ->whereYear('tanggal', $year)
                ->sum('liter');
                
            $sisaPertamax = $pendapatanPertamax - $pemakaianPertamax;
            $sisaDex = $pendapatanDex - $pemakaianDex;

            $reportData[] = [
                'satker' => $satker->nama_satker,
                'pendapatan_pertamax' => $pendapatanPertamax,
                'pendapatan_dex' => $pendapatanDex,
                'pemakaian_pertamax' => $pemakaianPertamax,
                'pemakaian_dex' => $pemakaianDex,
                'sisa_pertamax' => $sisaPertamax,
                'sisa_dex' => $sisaDex,
            ];
        }

        // Available years for filter
        $availableYears = collect(range(date('Y') - 5, date('Y')))->sortDesc()->values();

        return view('admin.laporan-tahunan.index', compact('reportData', 'year', 'availableYears'));
    }

    public function print(Request $request)
    {
        $year = $request->input('year', date('Y'));
        
        $satkers = Satker::orderBy('nama_satker')->get();
        $reportData = [];
        $total = [
            'pendapatan_pertamax' => 0,
            'pendapatan_dex' => 0,
            'pemakaian_pertamax' => 0,
            'pemakaian_dex' => 0,
            'sisa_pertamax' => 0,
            'sisa_dex' => 0,
        ];

        foreach ($satkers as $satker) {
            $pendapatanPertamax = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'masuk')->where(function($q) { $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })->whereYear('created_at', $year)->sum('jumlah');
            $pendapatanDex = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'masuk')->where(function($q) { $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })->whereYear('created_at', $year)->sum('jumlah');
            $pemakaianPertamax = TransaksiBbm::where('satker_id', $satker->id)->where(function($q) { $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })->whereYear('tanggal', $year)->sum('liter');
            $pemakaianDex = TransaksiBbm::where('satker_id', $satker->id)->where(function($q) { $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })->whereYear('tanggal', $year)->sum('liter');
                
            $sisaPertamax = $pendapatanPertamax - $pemakaianPertamax;
            $sisaDex = $pendapatanDex - $pemakaianDex;

            $reportData[] = [
                'satker' => $satker->nama_satker,
                'pendapatan_pertamax' => $pendapatanPertamax,
                'pendapatan_dex' => $pendapatanDex,
                'pemakaian_pertamax' => $pemakaianPertamax,
                'pemakaian_dex' => $pemakaianDex,
                'sisa_pertamax' => $sisaPertamax,
                'sisa_dex' => $sisaDex,
            ];

            $total['pendapatan_pertamax'] += $pendapatanPertamax;
            $total['pendapatan_dex'] += $pendapatanDex;
            $total['pemakaian_pertamax'] += $pemakaianPertamax;
            $total['pemakaian_dex'] += $pemakaianDex;
            $total['sisa_pertamax'] += $sisaPertamax;
            $total['sisa_dex'] += $sisaDex;
        }

        $pdf = Pdf::loadView('admin.laporan-tahunan.print', compact('reportData', 'year', 'total'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream("Laporan_BBM_Tahunan_{$year}.pdf");
    }
}
