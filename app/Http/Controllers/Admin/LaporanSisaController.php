<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Satker;
use App\Models\Kendaraan;
use App\Models\Personel;
use Illuminate\Http\Request;
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
        $rows = [];
        $totalPertamax = 0;
        $totalDex = 0;

        foreach ($satkers as $satker) {
            $pertamax = Kendaraan::where('satker_id', $satker->id)
                ->where('jenis_bbm', 'Pertamax')
                ->sum('saldo');
            
            $dex = Kendaraan::where('satker_id', $satker->id)
                ->where('jenis_bbm', 'Pertamina Dex')
                ->sum('saldo');

            $rows[] = [
                'satker' => $satker->nama_satker,
                'pertamax' => $pertamax,
                'dex' => $dex
            ];

            $totalPertamax += $pertamax;
            $totalDex += $dex;
        }

        return [
            'rows' => $rows,
            'totalPertamax' => $totalPertamax,
            'totalDex' => $totalDex,
            'title' => 'DATA BBM KENDARAAN PADA SATKER MAPOLDA NTB',
            'periode' => 'SAMPAI DENGAN BULAN ' . strtoupper(now()->translatedFormat('F Y'))
        ];
    }

    private function getSisaPersonelData()
    {
        $satkers = Satker::orderBy('nama_satker')->get();
        $rows = [];
        $totalPertamax = 0;
        $totalDex = 0;

        foreach ($satkers as $satker) {
            $pertamax = Personel::where('satker_id', $satker->id)
                ->where('jenis_bbm', 'Pertamax')
                ->sum('saldo');
            
            $dex = Personel::where('satker_id', $satker->id)
                ->where('jenis_bbm', 'Pertamina Dex')
                ->sum('saldo');

            $rows[] = [
                'satker' => $satker->nama_satker,
                'pertamax' => $pertamax,
                'dex' => $dex
            ];

            $totalPertamax += $pertamax;
            $totalDex += $dex;
        }

        return [
            'rows' => $rows,
            'totalPertamax' => $totalPertamax,
            'totalDex' => $totalDex,
            'title' => 'DATA BBM PERSONEL PADA SATKER MAPOLDA NTB',
            'periode' => 'SAMPAI DENGAN BULAN ' . strtoupper(now()->translatedFormat('F Y'))
        ];
    }
}
