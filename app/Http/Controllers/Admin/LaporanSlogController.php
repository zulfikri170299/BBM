<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RiwayatStokAdmin;
use App\Models\TransaksiBbm;
use App\Models\Hutang;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanSlogController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now('Asia/Makassar')->format('m'));
        $tahun = $request->get('tahun', Carbon::now('Asia/Makassar')->format('Y'));
        $jenisLaporan = $request->get('jenis_laporan', 'harian');

        if ($jenisLaporan === 'bulanan') {
            $data = $this->generateBulananData($bulan, $tahun);
        } else {
            $data = $this->generateHarianData($bulan, $tahun);
        }

        return view('admin.laporan_slog.index', compact('data', 'bulan', 'tahun', 'jenisLaporan'));
    }

    public function print(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now('Asia/Makassar')->format('m'));
        $tahun = $request->get('tahun', Carbon::now('Asia/Makassar')->format('Y'));
        $jenisLaporan = $request->get('jenis_laporan', 'harian');

        if ($jenisLaporan === 'bulanan') {
            $data = $this->generateBulananData($bulan, $tahun);
        } else {
            $data = $this->generateHarianData($bulan, $tahun);
        }
        
        $pdf = Pdf::loadView('admin.laporan_slog.print', compact('data', 'bulan', 'tahun', 'jenisLaporan'))
            ->setPaper('a4', 'landscape');

        $jenisStr = ucfirst($jenisLaporan);
        return $pdf->stream("Laporan_BBM_Rutin_Slog_{$jenisStr}_{$bulan}_{$tahun}.pdf");
    }

    private function getWeeksForMonth($bulan, $tahun)
    {
        $startOfMonth = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $weeks = [];
        // Mulai dari hari Senin pada minggu yang memuat tanggal 1
        $currentStart = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        
        $weekNumber = 1;
        $romanNumerals = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI'];

        while ($currentStart <= $endOfMonth) {
            $currentEnd = $currentStart->copy()->endOfWeek(Carbon::SUNDAY)->endOfDay();
            if ($currentEnd > $endOfMonth) {
                $currentEnd = $endOfMonth->copy();
            }

            $weeks['MINGGU ' . $romanNumerals[$weekNumber]] = [$currentStart->copy(), $currentEnd->copy()];
            $currentStart = $currentEnd->copy()->addSecond()->startOfDay();
            $weekNumber++;
        }

        return $weeks;
    }

    private function generateBulananData($bulan, $tahun)
    {
        $weeks = $this->getWeeksForMonth($bulan, $tahun);

        $firstWeekStart = reset($weeks)[0];
        $initialPertamax = $this->getHistoricalStock('Pertamax', $firstWeekStart);
        $initialDex = $this->getHistoricalStock('Pertamina Dex', $firstWeekStart);

        $report = [];
        $rekap = [
            'awal_pertamax' => $initialPertamax,
            'awal_dex' => $initialDex,
            'terima_pertamax' => 0,
            'terima_dex' => 0,
            'keluar_pertamax' => 0,
            'keluar_dex' => 0,
        ];

        $currentPertamax = $initialPertamax;
        $currentDex = $initialDex;

        foreach ($weeks as $weekName => $dates) {
            $start = $dates[0];
            $end = $dates[1];

            $terimaPertamax = RiwayatStokAdmin::where('tipe', 'masuk')
                ->where(function($q) { $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })
                ->whereBetween('created_at', [$start, $end])->sum('jumlah');

            $terimaDex = RiwayatStokAdmin::where('tipe', 'masuk')
                ->where(function($q) { $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })
                ->whereBetween('created_at', [$start, $end])->sum('jumlah');

            $keluarPertamax = TransaksiBbm::where(function($q) { $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })
                ->whereBetween('tanggal', [$start, $end])->sum('liter');
            $keluarPertamax += Hutang::where(function($q) { $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })
                ->whereBetween('tanggal', [$start, $end])->sum('jumlah_bon');

            $keluarDex = TransaksiBbm::where(function($q) { $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })
                ->whereBetween('tanggal', [$start, $end])->sum('liter');
            $keluarDex += Hutang::where(function($q) { $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })
                ->whereBetween('tanggal', [$start, $end])->sum('jumlah_bon');

            $report[$weekName] = [
                'awal_pertamax' => $currentPertamax,
                'awal_dex' => $currentDex,
                'terima_pertamax' => $terimaPertamax,
                'terima_dex' => $terimaDex,
                'jumlah_pertamax' => $currentPertamax + $terimaPertamax,
                'jumlah_dex' => $currentDex + $terimaDex,
                'keluar_pertamax' => $keluarPertamax,
                'keluar_dex' => $keluarDex,
                'akhir_pertamax' => ($currentPertamax + $terimaPertamax) - $keluarPertamax,
                'akhir_dex' => ($currentDex + $terimaDex) - $keluarDex,
            ];

            $rekap['terima_pertamax'] += $terimaPertamax;
            $rekap['terima_dex'] += $terimaDex;
            $rekap['keluar_pertamax'] += $keluarPertamax;
            $rekap['keluar_dex'] += $keluarDex;

            $currentPertamax = $report[$weekName]['akhir_pertamax'];
            $currentDex = $report[$weekName]['akhir_dex'];
        }

        $rekap['jumlah_pertamax'] = $rekap['awal_pertamax'] + $rekap['terima_pertamax'];
        $rekap['jumlah_dex'] = $rekap['awal_dex'] + $rekap['terima_dex'];
        $rekap['akhir_pertamax'] = $rekap['jumlah_pertamax'] - $rekap['keluar_pertamax'];
        $rekap['akhir_dex'] = $rekap['jumlah_dex'] - $rekap['keluar_dex'];

        return [
            'weeks' => $report,
            'rekap' => $rekap
        ];
    }

    private function generateHarianData($bulan, $tahun)
    {
        $weeks = $this->getWeeksForMonth($bulan, $tahun);

        $firstWeekStart = reset($weeks)[0];
        $initialPertamax = $this->getHistoricalStock('Pertamax', $firstWeekStart);
        $initialDex = $this->getHistoricalStock('Pertamina Dex', $firstWeekStart);

        $currentPertamax = $initialPertamax;
        $currentDex = $initialDex;

        $report = [];

        foreach ($weeks as $weekName => $dates) {
            $start = $dates[0];
            $end = $dates[1];
            
            $daysInWeek = [];
            $currentDate = $start->copy();
            
            $rekapMinggu = [
                'awal_pertamax' => $currentPertamax,
                'awal_dex' => $currentDex,
                'terima_pertamax' => 0,
                'terima_dex' => 0,
                'keluar_pertamax' => 0,
                'keluar_dex' => 0,
            ];

            while ($currentDate <= $end) {
                $dayStart = $currentDate->copy()->startOfDay();
                $dayEnd = $currentDate->copy()->endOfDay();

                $terimaPertamax = RiwayatStokAdmin::where('tipe', 'masuk')
                    ->where(function($q) { $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })
                    ->whereBetween('created_at', [$dayStart, $dayEnd])->sum('jumlah');

                $terimaDex = RiwayatStokAdmin::where('tipe', 'masuk')
                    ->where(function($q) { $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })
                    ->whereBetween('created_at', [$dayStart, $dayEnd])->sum('jumlah');

                $keluarPertamax = TransaksiBbm::where(function($q) { $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })
                    ->whereBetween('tanggal', [$dayStart, $dayEnd])->sum('liter');
                $keluarPertamax += Hutang::where(function($q) { $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })
                    ->whereBetween('tanggal', [$dayStart, $dayEnd])->sum('jumlah_bon');

                $keluarDex = TransaksiBbm::where(function($q) { $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })
                    ->whereBetween('tanggal', [$dayStart, $dayEnd])->sum('liter');
                $keluarDex += Hutang::where(function($q) { $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })
                    ->whereBetween('tanggal', [$dayStart, $dayEnd])->sum('jumlah_bon');

                if ($currentDate->month == (int)$bulan && $currentDate->year == (int)$tahun && 
                   ($currentDate->isWeekday() || $terimaPertamax > 0 || $terimaDex > 0 || $keluarPertamax > 0 || $keluarDex > 0)) {
                    $daysInWeek[] = [
                        'date' => $currentDate->copy(),
                        'nama_hari' => strtoupper($currentDate->translatedFormat('l d-n-Y')),
                        'awal_pertamax' => $currentPertamax,
                        'awal_dex' => $currentDex,
                        'terima_pertamax' => $terimaPertamax,
                        'terima_dex' => $terimaDex,
                        'jumlah_pertamax' => $currentPertamax + $terimaPertamax,
                        'jumlah_dex' => $currentDex + $terimaDex,
                        'keluar_pertamax' => $keluarPertamax,
                        'keluar_dex' => $keluarDex,
                        'akhir_pertamax' => ($currentPertamax + $terimaPertamax) - $keluarPertamax,
                        'akhir_dex' => ($currentDex + $terimaDex) - $keluarDex,
                    ];

                    $rekapMinggu['terima_pertamax'] += $terimaPertamax;
                    $rekapMinggu['terima_dex'] += $terimaDex;
                    $rekapMinggu['keluar_pertamax'] += $keluarPertamax;
                    $rekapMinggu['keluar_dex'] += $keluarDex;
                }

                $currentPertamax = ($currentPertamax + $terimaPertamax) - $keluarPertamax;
                $currentDex = ($currentDex + $terimaDex) - $keluarDex;

                $currentDate->addDay();
            }

            $rekapMinggu['jumlah_pertamax'] = $rekapMinggu['awal_pertamax'] + $rekapMinggu['terima_pertamax'];
            $rekapMinggu['jumlah_dex'] = $rekapMinggu['awal_dex'] + $rekapMinggu['terima_dex'];
            $rekapMinggu['akhir_pertamax'] = $currentPertamax;
            $rekapMinggu['akhir_dex'] = $currentDex;

            if (!empty($daysInWeek)) {
                $report[$weekName] = [
                    'days' => $daysInWeek,
                    'rekap' => $rekapMinggu
                ];
            }
        }

        return $report;
    }

    private function getHistoricalStock($jenisBbm, $date)
    {
        $totalMasuk = RiwayatStokAdmin::where('tipe', 'masuk')
            ->where(function($q) use ($jenisBbm) {
                $q->where('jenis_bbm', $jenisBbm)->orWhere('jenis_bbm', strtoupper($jenisBbm));
            })
            ->where('created_at', '<', $date)
            ->sum('jumlah');

        $totalKeluarTransaksi = TransaksiBbm::where(function($q) use ($jenisBbm) {
                $q->where('jenis_bbm', $jenisBbm)->orWhere('jenis_bbm', strtoupper($jenisBbm));
            })
            ->where('tanggal', '<', $date)
            ->sum('liter');

        $totalKeluarHutang = Hutang::where(function($q) use ($jenisBbm) {
                $q->where('jenis_bbm', $jenisBbm)->orWhere('jenis_bbm', strtoupper($jenisBbm));
            })
            ->where('tanggal', '<', $date)
            ->sum('jumlah_bon');

        return $totalMasuk - ($totalKeluarTransaksi + $totalKeluarHutang);
    }
}
