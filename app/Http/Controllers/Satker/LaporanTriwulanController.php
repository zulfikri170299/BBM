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
        $user = auth()->user();
        
        // Jika Super Admin, kita gunakan query tanpa filter satker_id
        // Namun view mengharapkan $satker objek, kita beri objek palsu atau Birolistrik
        $satker = $user->satker ?? \App\Models\Satker::find(1); 
        $isSuperAdmin = ($user->role === 'super_admin');

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

        $startLocal = $startDate->format('Y-m-d H:i:s');
        $endLocal = $endDate->format('Y-m-d H:i:s');

        $queryTopup = RiwayatTopup::join('kendaraans', 'riwayat_topups.kendaraan_id', '=', 'kendaraans.id')
            ->whereBetween('riwayat_topups.created_at', [$startLocal, $endLocal])
            ->whereIn('riwayat_topups.metode', ['manual', 'IMPORT', 'massal'])
            ->where('riwayat_topups.tipe', 'masuk');
        
        if (!$isSuperAdmin) {
            $queryTopup->where('riwayat_topups.satker_id', $satker->id);
        }

        $bbmTypesTopup = $queryTopup
            ->selectRaw("COALESCE(NULLIF(kendaraans.jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm")
            ->distinct()
            ->pluck('jenis_bbm')
            ->toArray();

        $queryTrans = TransaksiBbm::whereBetween('tanggal', [$startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s')]);
        if (!$isSuperAdmin) {
            $queryTrans->where('satker_id', $satker->id);
        }
        $bbmTypesTransaksi = $queryTrans
            ->selectRaw("COALESCE(NULLIF(jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm")
            ->distinct()
            ->pluck('jenis_bbm')
            ->toArray();

        $queryHutang = \App\Models\Hutang::whereBetween('tanggal_bon', [$startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s')]);
        if (!$isSuperAdmin) {
            $queryHutang->where('satker_id', $satker->id);
        }
        $bbmTypesHutang = $queryHutang
            ->selectRaw("COALESCE(NULLIF(jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm")
            ->distinct()
            ->pluck('jenis_bbm')
            ->toArray();

        $allBbmTypes = array_unique(array_merge($bbmTypesTopup, $bbmTypesTransaksi, $bbmTypesHutang));
        sort($allBbmTypes);
        
        if (empty($allBbmTypes)) {
            $allBbmTypes = ['Pertamax', 'Pertamina Dex'];
        }
        
        $queryPendapatanRaw = RiwayatTopup::join('kendaraans', 'riwayat_topups.kendaraan_id', '=', 'kendaraans.id')
            ->select(
                DB::raw("COALESCE(NULLIF(kendaraans.jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"),
                DB::raw("SUM(riwayat_topups.jumlah) as total")
            )
            ->whereBetween('riwayat_topups.created_at', [$startLocal, $endLocal])
            ->where('riwayat_topups.tipe', 'masuk');
        
        if (!$isSuperAdmin) {
            $queryPendapatanRaw->where('riwayat_topups.satker_id', $satker->id);
        }

        $pendapatanRaw = $queryPendapatanRaw
            ->groupBy('kendaraans.jenis_bbm')
            ->get();
        
        $pendapatan = [];
        foreach($pendapatanRaw as $item) {
            $pendapatan[$item->jenis_bbm] = $item->total;
        }


        $queryPemakaianRaw = TransaksiBbm::select(
                DB::raw("COALESCE(NULLIF(jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"),
                DB::raw('SUM(liter) as total')
            )
            ->whereBetween('tanggal', [$startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s')]);
        
        if (!$isSuperAdmin) {
            $queryPemakaianRaw->where('satker_id', $satker->id);
        }

        $pemakaianRaw = $queryPemakaianRaw->groupBy('jenis_bbm')->get();

        $pemakaian = [];
        foreach($pemakaianRaw as $item) {
            $pemakaian[$item->jenis_bbm] = $item->total;
        }



        $sisaBbm = [];
        foreach($allBbmTypes as $jenis) {
            $p = $pendapatan[$jenis] ?? 0;
            $m = $pemakaian[$jenis] ?? 0;
            $sisaBbm[$jenis] = $p - $m;
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
