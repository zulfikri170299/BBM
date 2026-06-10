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
        $user = auth()->user();
        $satkers = ($user->role === 'super_admin') ? \App\Models\Satker::all() : collect([$user->satker]);
        
        $reportData = [];

        foreach ($satkers as $satker) {
            if (!$satker) continue;
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

            // --- PENDAPATAN ---
            $pendapatanPertamax = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'masuk')->where($isPertamaxTopup)->whereYear('created_at', $year)->sum('jumlah');
            $pendapatanPertamax += \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satker->id)->whereHas('tujuanKendaraan', function($k) { $k->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })->whereYear('created_at', $year)->sum('jumlah')
                + \App\Models\RiwayatTransferAntarPersonel::where('satker_id', $satker->id)->whereHas('targetKendaraan', function($k) { $k->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })->whereYear('created_at', $year)->sum('jumlah');

            $pendapatanDex = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'masuk')->where($isDexTopup)->whereYear('created_at', $year)->sum('jumlah');
            $pendapatanDex += \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satker->id)->whereHas('tujuanKendaraan', function($k) { $k->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })->whereYear('created_at', $year)->sum('jumlah')
                + \App\Models\RiwayatTransferAntarPersonel::where('satker_id', $satker->id)->whereHas('targetKendaraan', function($k) { $k->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })->whereYear('created_at', $year)->sum('jumlah');

            // --- PEMAKAIAN ---
            $pemakaianPertamax = TransaksiBbm::where('satker_id', $satker->id)->where($isPertamaxTrans)->whereYear('tanggal', $year)->sum('liter');
            $pemakaianDex = TransaksiBbm::where('satker_id', $satker->id)->where($isDexTrans)->whereYear('tanggal', $year)->sum('liter');
            
            // Hutang
            $hutangP = \App\Models\Hutang::where('satker_id', $satker->id)->where($isPertamaxTrans)->whereYear('tanggal_bon', $year)->sum('jumlah_bon');
            $hutangD = \App\Models\Hutang::where('satker_id', $satker->id)->where($isDexTrans)->whereYear('tanggal_bon', $year)->sum('jumlah_bon');

            // Potong Saldo / Keluar
            $psP = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'keluar')->where($isPertamaxTopup)->whereYear('created_at', $year)->sum('jumlah');
            $psD = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'keluar')->where($isDexTopup)->whereYear('created_at', $year)->sum('jumlah');

            // Transfer Keluar (Dari Kendaraan)
            $tkP = \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satker->id)->whereHas('kendaraan', function($k) { $k->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })->whereYear('created_at', $year)->sum('jumlah');
            
            $tkD = \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satker->id)->whereHas('kendaraan', function($k) { $k->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })->whereYear('created_at', $year)->sum('jumlah');
            
            $pemakaianPertamax += $psP + $hutangP + $tkP;
            $pemakaianDex += $psD + $hutangD + $tkD;
                
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

        $availableYears = collect(range(date('Y') - 5, date('Y')))->sortDesc()->values();

        return view('satker.laporan-tahunan.index', compact('reportData', 'year', 'availableYears'));
    }

    public function print(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $user = auth()->user();
        $satkers = ($user->role === 'super_admin') ? \App\Models\Satker::all() : collect([$user->satker]);
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
            if (!$satker) continue;
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

            // --- PENDAPATAN ---
            $pendapatanPertamax = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'masuk')->where($isPertamaxTopup)->whereYear('created_at', $year)->sum('jumlah');
            $pendapatanPertamax += \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satker->id)->whereHas('tujuanKendaraan', function($k) { $k->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })->whereYear('created_at', $year)->sum('jumlah')
                + \App\Models\RiwayatTransferAntarPersonel::where('satker_id', $satker->id)->whereHas('targetKendaraan', function($k) { $k->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })->whereYear('created_at', $year)->sum('jumlah');

            $pendapatanDex = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'masuk')->where($isDexTopup)->whereYear('created_at', $year)->sum('jumlah');
            $pendapatanDex += \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satker->id)->whereHas('tujuanKendaraan', function($k) { $k->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })->whereYear('created_at', $year)->sum('jumlah')
                + \App\Models\RiwayatTransferAntarPersonel::where('satker_id', $satker->id)->whereHas('targetKendaraan', function($k) { $k->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })->whereYear('created_at', $year)->sum('jumlah');

            // --- PEMAKAIAN ---
            $pemakaianPertamax = TransaksiBbm::where('satker_id', $satker->id)->where($isPertamaxTrans)->whereYear('tanggal', $year)->sum('liter');
            $pemakaianDex = TransaksiBbm::where('satker_id', $satker->id)->where($isDexTrans)->whereYear('tanggal', $year)->sum('liter');
            
            // Hutang
            $hutangP = \App\Models\Hutang::where('satker_id', $satker->id)->where($isPertamaxTrans)->whereYear('tanggal_bon', $year)->sum('jumlah_bon');
            $hutangD = \App\Models\Hutang::where('satker_id', $satker->id)->where($isDexTrans)->whereYear('tanggal_bon', $year)->sum('jumlah_bon');

            // Potong Saldo / Keluar
            $psP = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'keluar')->where($isPertamaxTopup)->whereYear('created_at', $year)->sum('jumlah');
            $psD = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'keluar')->where($isDexTopup)->whereYear('created_at', $year)->sum('jumlah');

            // Transfer Keluar (Dari Kendaraan)
            $tkP = \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satker->id)->whereHas('kendaraan', function($k) { $k->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })->whereYear('created_at', $year)->sum('jumlah');
            
            $tkD = \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satker->id)->whereHas('kendaraan', function($k) { $k->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })->whereYear('created_at', $year)->sum('jumlah');
            
            $pemakaianPertamax += $psP + $hutangP + $tkP;
            $pemakaianDex += $psD + $hutangD + $tkD;
                
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

        $pdf = Pdf::loadView('satker.laporan-tahunan.print', compact('reportData', 'year', 'total'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream("Laporan_BBM_Tahunan_Satker_{$year}.pdf");
    }
}
