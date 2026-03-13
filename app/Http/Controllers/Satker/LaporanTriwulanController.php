<?php

namespace App\Http\Controllers\Satker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RiwayatTopup;
use App\Models\TransaksiBbm;
use App\Models\Satker;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanTriwulanController extends Controller
{
    private function getLaporanData(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $triwulan = $request->triwulan ?? 1;
        $satker = auth()->user()->satker;

        if ($triwulan == 1) {
            $startDateStr = "$tahun-01-01";
            $endDateStr = "$tahun-03-31";
            $periodeLabel = "Januari-Maret $tahun";
        } elseif ($triwulan == 2) {
            $startDateStr = "$tahun-04-01";
            $endDateStr = "$tahun-06-30";
            $periodeLabel = "April-Juni $tahun";
        } elseif ($triwulan == 3) {
            $startDateStr = "$tahun-07-01";
            $endDateStr = "$tahun-09-30";
            $periodeLabel = "Juli-September $tahun";
        } else {
            $startDateStr = "$tahun-10-01";
            $endDateStr = "$tahun-12-31";
            $periodeLabel = "Oktober-Desember $tahun";
        }

        $startDate = Carbon::createFromFormat('Y-m-d', $startDateStr, 'Asia/Makassar')->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $endDateStr, 'Asia/Makassar')->endOfDay();

        $bbmTypesTopup = RiwayatTopup::join('kendaraans', 'riwayat_topups.kendaraan_id', '=', 'kendaraans.id')
            ->where('riwayat_topups.satker_id', $satker->id)
            ->whereBetween('riwayat_topups.created_at', [$startDate, $endDate])
            ->whereIn('riwayat_topups.metode', ['manual', 'IMPORT', 'massal'])
            ->where('riwayat_topups.tipe', 'masuk')
            ->selectRaw("COALESCE(NULLIF(kendaraans.jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm")
            ->distinct()
            ->pluck('jenis_bbm')
            ->toArray();

        $bbmTypesTransaksi = TransaksiBbm::where('satker_id', $satker->id)
            ->whereBetween('tanggal', [$startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s')])
            ->selectRaw("COALESCE(NULLIF(jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm")
            ->distinct()
            ->pluck('jenis_bbm')
            ->toArray();

        $allBbmTypes = array_unique(array_merge($bbmTypesTopup, $bbmTypesTransaksi));
        sort($allBbmTypes);
        
        if (empty($allBbmTypes)) {
            $allBbmTypes = ['Pertamax', 'Pertamina Dex'];
        }
        
        $pendapatanRaw = RiwayatTopup::join('kendaraans', 'riwayat_topups.kendaraan_id', '=', 'kendaraans.id')
            ->select(
                DB::raw("COALESCE(NULLIF(kendaraans.jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"),
                DB::raw("SUM(riwayat_topups.jumlah) as total")
            )
            ->where('riwayat_topups.satker_id', $satker->id)
            ->whereBetween('riwayat_topups.created_at', [$startDate, $endDate])
            ->where('riwayat_topups.tipe', 'masuk')
            ->groupBy('kendaraans.jenis_bbm')
            ->get();
        
        $pendapatan = [];
        foreach($pendapatanRaw as $item) {
            $pendapatan[$item->jenis_bbm] = $item->total;
        }

        $pemakaianRaw = TransaksiBbm::select(
                DB::raw("COALESCE(NULLIF(jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"),
                DB::raw('SUM(liter) as total')
            )
            ->where('satker_id', $satker->id)
            ->whereBetween('tanggal', [$startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s')])
            ->groupBy('jenis_bbm')
            ->get();

        $pemakaian = [];
        foreach($pemakaianRaw as $item) {
            $pemakaian[$item->jenis_bbm] = $item->total;
        }

        // Tambahan: SEMUA RiwayatTopup 'keluar'
        $potongSaldoRaw = RiwayatTopup::select(
                DB::raw("COALESCE(NULLIF(jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"),
                DB::raw('SUM(jumlah) as total')
            )
            ->where('satker_id', $satker->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('tipe', 'keluar')
            ->groupBy('jenis_bbm')
            ->get();

        foreach($potongSaldoRaw as $item) {
            $pemakaian[$item->jenis_bbm] = ($pemakaian[$item->jenis_bbm] ?? 0) + $item->total;
        }

        // --- Perbaikan: Hitung Sisa BBM secara Kumulatif (Sampai Akhir Periode) ---
        $pendapatanKumulatifRaw = RiwayatTopup::join('kendaraans', 'riwayat_topups.kendaraan_id', '=', 'kendaraans.id')
            ->select(
                DB::raw("COALESCE(NULLIF(kendaraans.jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"),
                DB::raw("SUM(riwayat_topups.jumlah) as total")
            )
            ->where('riwayat_topups.satker_id', $satker->id)
            ->where('riwayat_topups.created_at', '<=', $endDate)
            ->where('riwayat_topups.tipe', 'masuk')
            ->groupBy('kendaraans.jenis_bbm')
            ->get();

        $pemakaianKumulatifRaw = TransaksiBbm::select(
                DB::raw("COALESCE(NULLIF(jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"),
                DB::raw('SUM(liter) as total')
            )
            ->where('satker_id', $satker->id)
            ->where('tanggal', '<=', $endDate->format('Y-m-d H:i:s'))
            ->groupBy('jenis_bbm')
            ->get();

        $potongSaldoKumulatifRaw = RiwayatTopup::select(
                DB::raw("COALESCE(NULLIF(jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"),
                DB::raw('SUM(jumlah) as total')
            )
            ->where('satker_id', $satker->id)
            ->where('created_at', '<=', $endDate)
            ->where('tipe', 'keluar')
            ->groupBy('jenis_bbm')
            ->get();

        $sisaBbm = [];
        foreach($pendapatanKumulatifRaw as $item) {
            $sisaBbm[$item->jenis_bbm] = ($sisaBbm[$item->jenis_bbm] ?? 0) + $item->total;
        }
        foreach($pemakaianKumulatifRaw as $item) {
            $sisaBbm[$item->jenis_bbm] = ($sisaBbm[$item->jenis_bbm] ?? 0) - $item->total;
        }
        foreach($potongSaldoKumulatifRaw as $item) {
            $sisaBbm[$item->jenis_bbm] = ($sisaBbm[$item->jenis_bbm] ?? 0) - $item->total;
        }

        return compact('tahun', 'triwulan', 'periodeLabel', 'allBbmTypes', 'satker', 'pendapatan', 'pemakaian', 'sisaBbm');
    }

    public function index(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $triwulan = $request->triwulan ?? 1;

        $request->merge(['tahun' => $tahun, 'triwulan' => $triwulan]);
        $data = $this->getLaporanData($request);
        
        return view('satker.laporan_triwulan.index', $data);
    }

    public function print(Request $request)
    {
        $request->validate([
            'tahun' => 'required|numeric',
            'triwulan' => 'required|numeric|in:1,2,3,4',
        ]);

        $data = $this->getLaporanData($request);

        $pdf = Pdf::loadView('satker.laporan_triwulan.print', $data)
            ->setPaper([0, 0, 609.45, 935.43], 'landscape');

        return $pdf->stream('laporan-triwulan-' . $data['tahun'] . '-T' . $data['triwulan'] . '-' . date('Y-m-d_H-i') . '.pdf');
    }
}
