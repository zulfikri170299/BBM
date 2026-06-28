<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyMeterReading;
use App\Models\TransaksiBbm;
use App\Models\Satker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanHarianController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now('Asia/Makassar')->format('m'));
        $tahun = $request->get('tahun', Carbon::now('Asia/Makassar')->format('Y'));
        
        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        
        if ($endDate->isFuture()) {
            $endDate = Carbon::now('Asia/Makassar');
        }

        $dates = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }
        // $dates = array_reverse($dates); // Removed to sort from 1st downwards

        // Ambil data transaksi aplikasi (Gabungan Kendaraan & Personel)
        $appData = TransaksiBbm::leftJoin('kendaraans', 'transaksi_bbms.kendaraan_id', '=', 'kendaraans.id')
            ->leftJoin('personels', 'transaksi_bbms.personel_id', '=', 'personels.id')
            ->selectRaw('
                DATE(transaksi_bbms.tanggal) as tgl, 
                UPPER(COALESCE(kendaraans.jenis_bbm, personels.jenis_bbm)) as bbm_alias, 
                SUM(transaksi_bbms.liter) as total
            ')
            ->whereBetween('transaksi_bbms.tanggal', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->groupBy('tgl', 'bbm_alias')
            ->get()
            ->groupBy('tgl');

        // Ambil data meteran manual
        $manualData = DailyMeterReading::whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get()
            ->groupBy('tanggal');

        return view('admin.laporan_harian.index', compact('dates', 'appData', 'manualData', 'bulan', 'tahun'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_bbm' => 'required|string',
            'meter_awal' => 'required|integer|min:0',
            'meter_akhir' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        DailyMeterReading::updateOrCreate(
            ['tanggal' => $request->tanggal, 'jenis_bbm' => $request->jenis_bbm],
            [
                'meter_awal' => $request->meter_awal, 
                'meter_akhir' => $request->meter_akhir,
                'keterangan' => $request->keterangan
            ]
        );

        return back()->with('success', 'Data meteran berhasil diperbarui.');
    }

    public function exportPdf(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now('Asia/Makassar')->format('m'));
        $tahun = $request->get('tahun', Carbon::now('Asia/Makassar')->format('Y'));

        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        if ($endDate->isFuture()) {
            $endDate = Carbon::now('Asia/Makassar');
        }

        $dates = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }
        // $dates = array_reverse($dates); // Removed to sort from 1st downwards

        $appData = TransaksiBbm::leftJoin('kendaraans', 'transaksi_bbms.kendaraan_id', '=', 'kendaraans.id')
            ->leftJoin('personels', 'transaksi_bbms.personel_id', '=', 'personels.id')
            ->selectRaw('
                DATE(transaksi_bbms.tanggal) as tgl, 
                UPPER(COALESCE(kendaraans.jenis_bbm, personels.jenis_bbm)) as bbm_alias, 
                SUM(transaksi_bbms.liter) as total
            ')
            ->whereBetween('transaksi_bbms.tanggal', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->groupBy('tgl', 'bbm_alias')
            ->get()
            ->groupBy('tgl');

        $manualData = DailyMeterReading::whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get()
            ->groupBy('tanggal');

        $pdf = Pdf::loadView('admin.laporan_harian.pdf', compact('dates', 'appData', 'manualData', 'bulan', 'tahun'))
            ->setPaper([0, 0, 609.45, 935.43], 'portrait'); // F4 (215mm x 330mm)

        return $pdf->download("Laporan_Harian_BBM_({$bulan})_{$tahun}.pdf");
    }
}
