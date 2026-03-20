<?php

namespace App\Http\Controllers\Satker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RiwayatTopup;
use App\Models\TransaksiBbm;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanTahunanController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $satker = auth()->user()->satker;
        
        $reportData = [];

        if ($satker) {
            $isPertamaxTopup = function($q) {
                $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX')
                  ->orWhereHas('kendaraan', function($k) { $k->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); });
            };
            $isDexTopup = function($q) {
                $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX')
                  ->orWhereHas('kendaraan', function($k) { $k->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); });
            };
            $isPertamaxTrans = function($q) { $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); };
            $isDexTrans = function($q) { $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); };

            $pendapatanPertamax = RiwayatTopup::where('satker_id', $satker->id)
                ->where('tipe', 'masuk')->where($isPertamaxTopup)->whereYear('created_at', $year)->sum('jumlah');

            $pendapatanDex = RiwayatTopup::where('satker_id', $satker->id)
                ->where('tipe', 'masuk')->where($isDexTopup)->whereYear('created_at', $year)->sum('jumlah');

            $pemakaianPertamax = TransaksiBbm::where('satker_id', $satker->id)
                ->where($isPertamaxTrans)->whereYear('tanggal', $year)->sum('liter');

            $pemakaianDex = TransaksiBbm::where('satker_id', $satker->id)
                ->where($isDexTrans)->whereYear('tanggal', $year)->sum('liter');
            
            $psP = RiwayatTopup::where('satker_id', $satker->id)
                ->where('tipe', 'keluar')->where($isPertamaxTopup)->whereYear('created_at', $year)->sum('jumlah');
            
            $psD = RiwayatTopup::where('satker_id', $satker->id)
                ->where('tipe', 'keluar')->where($isDexTopup)->whereYear('created_at', $year)->sum('jumlah');
            
            $pemakaianPertamax += $psP;
            $pemakaianDex += $psD;
                
            $totalPendapatanPertamax = RiwayatTopup::where('satker_id', $satker->id)
                ->where('tipe', 'masuk')->where($isPertamaxTopup)->where('created_at', '<=', "$year-12-31 23:59:59")->sum('jumlah');
            
            $totalPendapatanDex = RiwayatTopup::where('satker_id', $satker->id)
                ->where('tipe', 'masuk')->where($isDexTopup)->where('created_at', '<=', "$year-12-31 23:59:59")->sum('jumlah');

            $totalKeluarPertamax = TransaksiBbm::where('satker_id', $satker->id)
                ->where($isPertamaxTrans)->where('tanggal', '<=', "$year-12-31 23:59:59")->sum('liter');
            
            $totalKeluarPertamax += RiwayatTopup::where('satker_id', $satker->id)
                ->where('tipe', 'keluar')->where($isPertamaxTopup)->where('created_at', '<=', "$year-12-31 23:59:59")->sum('jumlah');

            $totalKeluarDex = TransaksiBbm::where('satker_id', $satker->id)
                ->where($isDexTrans)->where('tanggal', '<=', "$year-12-31 23:59:59")->sum('liter');
            
            $totalKeluarDex += RiwayatTopup::where('satker_id', $satker->id)
                ->where('tipe', 'keluar')->where($isDexTopup)->where('created_at', '<=', "$year-12-31 23:59:59")->sum('jumlah');

            $sisaPertamax = $totalPendapatanPertamax - $totalKeluarPertamax;
            $sisaDex = $totalPendapatanDex - $totalKeluarDex;

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

        $availableYears = collect(range(date('Y') - 5, date('Y')))->sortDesc()->values();

        return view('satker.laporan-tahunan.index', compact('reportData', 'year', 'availableYears'));
    }

    public function print(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $satker = auth()->user()->satker;
        $reportData = [];
        
        $total = [
            'pendapatan_pertamax' => 0,
            'pendapatan_dex' => 0,
            'pemakaian_pertamax' => 0,
            'pemakaian_dex' => 0,
            'sisa_pertamax' => 0,
            'sisa_dex' => 0,
        ];

        if ($satker) {
            $isPertamaxTopup = function($q) {
                $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX')
                  ->orWhereHas('kendaraan', function($k) { $k->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); });
            };
            $isDexTopup = function($q) {
                $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX')
                  ->orWhereHas('kendaraan', function($k) { $k->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); });
            };
            $isPertamaxTrans = function($q) { $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); };
            $isDexTrans = function($q) { $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); };

            $pendapatanPertamax = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'masuk')->where($isPertamaxTopup)->whereYear('created_at', $year)->sum('jumlah');
            $pendapatanDex = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'masuk')->where($isDexTopup)->whereYear('created_at', $year)->sum('jumlah');
            $pemakaianPertamax = TransaksiBbm::where('satker_id', $satker->id)->where($isPertamaxTrans)->whereYear('tanggal', $year)->sum('liter');
            $pemakaianDex = TransaksiBbm::where('satker_id', $satker->id)->where($isDexTrans)->whereYear('tanggal', $year)->sum('liter');
            
            $psP = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'keluar')->where($isPertamaxTopup)->whereYear('created_at', $year)->sum('jumlah');
            $psD = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'keluar')->where($isDexTopup)->whereYear('created_at', $year)->sum('jumlah');
            
            $pemakaianPertamax += $psP;
            $pemakaianDex += $psD;
                
            $totalPendapatanPertamax = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'masuk')->where($isPertamaxTopup)->where('created_at', '<=', "$year-12-31 23:59:59")->sum('jumlah');
            $totalPendapatanDex = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'masuk')->where($isDexTopup)->where('created_at', '<=', "$year-12-31 23:59:59")->sum('jumlah');
            
            $totalKeluarPertamax = TransaksiBbm::where('satker_id', $satker->id)->where($isPertamaxTrans)->where('tanggal', '<=', "$year-12-31 23:59:59")->sum('liter');
            $totalKeluarPertamax += RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'keluar')->where($isPertamaxTopup)->where('created_at', '<=', "$year-12-31 23:59:59")->sum('jumlah');

            $totalKeluarDex = TransaksiBbm::where('satker_id', $satker->id)->where($isDexTrans)->where('tanggal', '<=', "$year-12-31 23:59:59")->sum('liter');
            $totalKeluarDex += RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'keluar')->where($isDexTopup)->where('created_at', '<=', "$year-12-31 23:59:59")->sum('jumlah');

            $sisaPertamax = $totalPendapatanPertamax - $totalKeluarPertamax;
            $sisaDex = $totalPendapatanDex - $totalKeluarDex;

            $reportData[] = [
                'satker' => $satker->nama_satker,
                'pendapatan_pertamax' => $pendapatanPertamax,
                'pendapatan_dex' => $pendapatanDex,
                'pemakaian_pertamax' => $pemakaianPertamax,
                'pemakaian_dex' => $pemakaianDex,
                'sisa_pertamax' => $sisaPertamax,
                'sisa_dex' => $sisaDex,
            ];

            $total['pendapatan_pertamax'] = $pendapatanPertamax;
            $total['pendapatan_dex'] = $pendapatanDex;
            $total['pemakaian_pertamax'] = $pemakaianPertamax;
            $total['pemakaian_dex'] = $pemakaianDex;
            $total['sisa_pertamax'] = $sisaPertamax;
            $total['sisa_dex'] = $sisaDex;
        }

        $pdf = Pdf::loadView('satker.laporan-tahunan.print', compact('reportData', 'year', 'total'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream("Laporan_BBM_Tahunan_Satker_{$year}.pdf");
    }
}
