<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Satker;
use App\Models\RiwayatTopup;
use App\Models\TransaksiBbm;
use App\Models\Hutang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanSisaController extends Controller
{
    public function kendaraan()
    {
        $data = $this->getSisaKendaraanData();
        return view('admin.laporan-sisa.kendaraan', $data);
    }

    public function printKendaraan()
    {
        $data = $this->getSisaKendaraanData();
        $pdf = Pdf::loadView('admin.laporan-sisa.kendaraan-print', $data)
            ->setPaper('a4', 'portrait');
        return $pdf->stream('laporan-sisa-bbm-kendaraan.pdf');
    }

    public function personel()
    {
        $data = $this->getSisaPersonelData();
        return view('admin.laporan-sisa.personel', $data);
    }

    public function printPersonel()
    {
        $data = $this->getSisaPersonelData();
        $pdf = Pdf::loadView('admin.laporan-sisa.personel-print', $data)
            ->setPaper('a4', 'portrait');
        return $pdf->stream('laporan-sisa-bbm-personel.pdf');
    }

    private function getSisaKendaraanData()
    {
        $satkers = Satker::orderBy('nama_satker')->get();
        $kendaraansBySatker = \App\Models\Kendaraan::where('saldo', '>', 0)
            ->orderBy('no_polisi')
            ->get()
            ->groupBy('satker_id');

        // Preload unpaid hutangs for fast and accurate status lookup
        $unpaidHutangsBySatker = \App\Models\Hutang::where('status', 'belum_dibayar')
            ->get()
            ->groupBy('satker_id');

        $rows = [];
        $totalPertamax = 0;
        $totalDex = 0;

        foreach ($satkers as $satker) {
            $satkerKendaraans = $kendaraansBySatker->get($satker->id, collect());
            $satkerHutangs = $unpaidHutangsBySatker->get($satker->id, collect());

            $pertamax = $satkerKendaraans->filter(function($k) {
                return strcasecmp($k->jenis_bbm, 'Pertamax') === 0;
            })->sum('saldo');
            
            $dex = $satkerKendaraans->filter(function($k) {
                return strcasecmp($k->jenis_bbm, 'Pertamina Dex') === 0;
            })->sum('saldo');

            $lockedCount = 0;

            $kendaraanList = $satkerKendaraans->map(function($k) use ($satkerHutangs, &$lockedCount) {
                $cleanNopol = preg_replace('/[^A-Za-z0-9]/', '', strtoupper($k->no_polisi));
                $matchingHutangs = $satkerHutangs->filter(function($h) use ($k, $cleanNopol) {
                    if (strcasecmp(trim($h->nopol), trim($k->no_polisi)) === 0) return true;
                    $cleanH = preg_replace('/[^A-Za-z0-9]/', '', strtoupper($h->nopol));
                    return $cleanH !== '' && $cleanH === $cleanNopol;
                });

                $unpaidBonTotal = (int) $matchingHutangs->sum('jumlah_bon');
                $hasUnpaidBon = $matchingHutangs->isNotEmpty();
                $isBebasBon = $k->isBebasBon();
                $isLocked = $hasUnpaidBon && !$isBebasBon;

                if ($isLocked) {
                    $lockedCount++;
                }

                return [
                    'id'               => $k->id,
                    'no_polisi'        => $k->no_polisi,
                    'jenis_kendaraan'  => $k->jenis_kendaraan ?? '-',
                    'jenis_bbm'        => $k->jenis_bbm,
                    'saldo'            => (float) $k->saldo,
                    'roda'             => $k->roda ?? 4,
                    'is_locked'        => $isLocked,
                    'has_unpaid_bon'   => $hasUnpaidBon,
                    'is_bebas_bon'     => $isBebasBon,
                    'unpaid_bon_total' => $unpaidBonTotal,
                ];
            })->values()->toArray();

            $rows[] = [
                'satker_id'    => $satker->id,
                'satker'       => $satker->nama_satker,
                'pertamax'     => $pertamax,
                'dex'          => $dex,
                'locked_count' => $lockedCount,
                'kendaraans'   => $kendaraanList,
            ];

            $totalPertamax += $pertamax;
            $totalDex      += $dex;
        }

        return [
            'rows'          => $rows,
            'totalPertamax' => $totalPertamax,
            'totalDex'      => $totalDex,
            'title'         => 'DATA BBM KENDARAAN PADA SATKER MAPOLDA NTB',
            'periode'       => 'SAMPAI DENGAN BULAN ' . strtoupper(now()->translatedFormat('F Y'))
        ];
    }

    private function getSisaPersonelData()
    {
        $satkers = Satker::orderBy('nama_satker')->get();
        $rows = [];
        $totalPertamax = 0;
        $totalDex = 0;

        foreach ($satkers as $satker) {
            $pertamax = \App\Models\Personel::where('satker_id', $satker->id)
                ->where(function($q) {
                    $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX');
                })->sum('saldo');
            
            $dex = \App\Models\Personel::where('satker_id', $satker->id)
                ->where(function($q) {
                    $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX');
                })->sum('saldo');

            $rows[] = [
                'satker'   => $satker->nama_satker,
                'pertamax' => $pertamax,
                'dex'      => $dex,
            ];

            $totalPertamax += $pertamax;
            $totalDex      += $dex;
        }

        return [
            'rows'          => $rows,
            'totalPertamax' => $totalPertamax,
            'totalDex'      => $totalDex,
            'title'         => 'DATA BBM PERSONEL PADA SATKER MAPOLDA NTB',
            'periode'       => 'SAMPAI DENGAN BULAN ' . strtoupper(now()->translatedFormat('F Y'))
        ];
    }
}
