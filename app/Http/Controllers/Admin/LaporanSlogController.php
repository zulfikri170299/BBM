<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RiwayatStokAdmin;
use App\Models\TransaksiBbm;
use App\Models\Hutang;
use App\Models\PembelianBbm;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanSlogController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now('Asia/Makassar')->format('m'));
        $tahun = $request->get('tahun', Carbon::now('Asia/Makassar')->format('Y'));
        $tw = $request->get('tw', 1);
        $jenisLaporan = $request->get('jenis_laporan', 'harian');

        if ($jenisLaporan === 'bulanan') {
            $data = $this->generateBulananData($bulan, $tahun);
        } elseif ($jenisLaporan === 'triwulan') {
            $data = $this->generateTriwulanData($tw, $tahun);
        } else {
            $data = $this->generateHarianData($bulan, $tahun);
        }

        return view('admin.laporan_slog.index', compact('data', 'bulan', 'tahun', 'tw', 'jenisLaporan'));
    }

    public function print(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now('Asia/Makassar')->format('m'));
        $tahun = $request->get('tahun', Carbon::now('Asia/Makassar')->format('Y'));
        $tw = $request->get('tw', 1);
        $jenisLaporan = $request->get('jenis_laporan', 'harian');

        if ($jenisLaporan === 'bulanan') {
            $data = $this->generateBulananData($bulan, $tahun);
        } elseif ($jenisLaporan === 'triwulan') {
            $data = $this->generateTriwulanData($tw, $tahun);
        } else {
            $data = $this->generateHarianData($bulan, $tahun);
        }
        
        $pdf = Pdf::loadView('admin.laporan_slog.print', compact('data', 'bulan', 'tahun', 'tw', 'jenisLaporan'))
            ->setPaper('a4', 'landscape');

        $jenisStr = ucfirst($jenisLaporan);
        $periodeStr = $jenisLaporan === 'triwulan' ? "TW_{$tw}" : $bulan;
        return $pdf->stream("Laporan_Rutin_{$jenisStr}_{$periodeStr}_{$tahun}.pdf");
    }

    public function word(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now('Asia/Makassar')->format('m'));
        $tahun = $request->get('tahun', Carbon::now('Asia/Makassar')->format('Y'));
        $tw = $request->get('tw', 1);
        $jenisLaporan = $request->get('jenis_laporan', 'harian');

        if ($jenisLaporan === 'bulanan') {
            $data = $this->generateBulananData($bulan, $tahun);
        } elseif ($jenisLaporan === 'triwulan') {
            $data = $this->generateTriwulanData($tw, $tahun);
        } else {
            $data = $this->generateHarianData($bulan, $tahun);
        }

        $jenisStr = ucfirst($jenisLaporan);
        $periodeStr = $jenisLaporan === 'triwulan' ? "TW_{$tw}" : $bulan;
        $filename = "Laporan_Rutin_{$jenisStr}_{$periodeStr}_{$tahun}.doc";

        $headers = [
            "Content-type" => "application/vnd.ms-word",
            "Content-Disposition" => "attachment;Filename={$filename}"
        ];

        return response()->view('admin.laporan_slog.print', compact('data', 'bulan', 'tahun', 'tw', 'jenisLaporan'), 200, $headers);
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

            $terimaPertamax = PembelianBbm::where(function($q) { $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })
                ->whereBetween('tanggal', [$start->format('Y-m-d'), $end->format('Y-m-d')])->sum('jumlah');

            $terimaDex = PembelianBbm::where(function($q) { $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })
                ->whereBetween('tanggal', [$start->format('Y-m-d'), $end->format('Y-m-d')])->sum('jumlah');

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

    private function generateTriwulanData($tw, $tahun)
    {
        $months = [];
        if ($tw == 1) $months = [1, 2, 3];
        elseif ($tw == 2) $months = [4, 5, 6];
        elseif ($tw == 3) $months = [7, 8, 9];
        elseif ($tw == 4) $months = [10, 11, 12];
        else $months = [1, 2, 3];

        $firstMonthStart = Carbon::createFromDate($tahun, $months[0], 1, 'Asia/Makassar')->startOfMonth();
        $initialPertamax = $this->getHistoricalStock('Pertamax', $firstMonthStart);
        $initialDex = $this->getHistoricalStock('Pertamina Dex', $firstMonthStart);

        $currentPertamax = $initialPertamax;
        $currentDex = $initialDex;

        $report = [];
        $rekap = [
            'awal_pertamax' => $currentPertamax,
            'awal_dex' => $currentDex,
            'terima_pertamax' => 0,
            'terima_dex' => 0,
            'keluar_pertamax' => 0,
            'keluar_dex' => 0,
        ];

        foreach ($months as $m) {
            $startOfMonth = Carbon::createFromDate($tahun, $m, 1, 'Asia/Makassar')->startOfMonth();
            $endOfMonth = $startOfMonth->copy()->endOfMonth();

            $monthName = $startOfMonth->translatedFormat('F');

            $terimaPertamax = PembelianBbm::where(function($q) { $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })
                ->whereBetween('tanggal', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])->sum('jumlah');

            $terimaDex = PembelianBbm::where(function($q) { $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })
                ->whereBetween('tanggal', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])->sum('jumlah');

            $keluarPertamax = TransaksiBbm::where(function($q) { $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])->sum('liter');
            $keluarPertamax += Hutang::where(function($q) { $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])->sum('jumlah_bon');

            $keluarDex = TransaksiBbm::where(function($q) { $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])->sum('liter');
            $keluarDex += Hutang::where(function($q) { $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])->sum('jumlah_bon');

            $report['months'][$monthName] = [
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

            $currentPertamax = $report['months'][$monthName]['akhir_pertamax'];
            $currentDex = $report['months'][$monthName]['akhir_dex'];
        }

        $rekap['jumlah_pertamax'] = $rekap['awal_pertamax'] + $rekap['terima_pertamax'];
        $rekap['jumlah_dex'] = $rekap['awal_dex'] + $rekap['terima_dex'];
        $rekap['akhir_pertamax'] = $rekap['jumlah_pertamax'] - $rekap['keluar_pertamax'];
        $rekap['akhir_dex'] = $rekap['jumlah_dex'] - $rekap['keluar_dex'];

        $report['rekap'] = $rekap;

        return $report;
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

                $terimaPertamax = PembelianBbm::where(function($q) { $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })
                    ->whereDate('tanggal', $currentDate->format('Y-m-d'))->sum('jumlah');

                $terimaDex = PembelianBbm::where(function($q) { $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })
                    ->whereDate('tanggal', $currentDate->format('Y-m-d'))->sum('jumlah');

                $keluarPertamax = TransaksiBbm::where(function($q) { $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })
                    ->whereBetween('tanggal', [$dayStart, $dayEnd])->sum('liter');
                $keluarPertamax += Hutang::where(function($q) { $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })
                    ->whereBetween('tanggal', [$dayStart, $dayEnd])->sum('jumlah_bon');

                $keluarDex = TransaksiBbm::where(function($q) { $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })
                    ->whereBetween('tanggal', [$dayStart, $dayEnd])->sum('liter');
                $keluarDex += Hutang::where(function($q) { $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })
                    ->whereBetween('tanggal', [$dayStart, $dayEnd])->sum('jumlah_bon');

                if ($currentDate->month == (int)$bulan && $currentDate->year == (int)$tahun) {
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
        $totalMasuk = PembelianBbm::where(function($q) use ($jenisBbm) {
                $q->where('jenis_bbm', $jenisBbm)->orWhere('jenis_bbm', strtoupper($jenisBbm));
            })
            ->whereDate('tanggal', '<', $date->format('Y-m-d'))
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
