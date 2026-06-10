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
        $rows = [];
        $totalPertamax = 0;
        $totalDex = 0;

        $isPertamaxTopup = function($q) {
            $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX')
              ->orWhereHas('kendaraan', fn($k) => $k->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'));
        };
        $isDexTopup = function($q) {
            $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX')
              ->orWhereHas('kendaraan', fn($k) => $k->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'));
        };
        $isPertamaxTrans = fn($q) => $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX');
        $isDexTrans      = fn($q) => $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX');

        foreach ($satkers as $satker) {
            // Total topup masuk
            $pendP = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'masuk')->where($isPertamaxTopup)->sum('jumlah');
            $pendD = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'masuk')->where($isDexTopup)->sum('jumlah');

            // Total pemakaian (transaksi BBM)
            $pakaiP = TransaksiBbm::where('satker_id', $satker->id)->where($isPertamaxTrans)->sum('liter');
            $pakaiD = TransaksiBbm::where('satker_id', $satker->id)->where($isDexTrans)->sum('liter');

            // Total potong saldo (RiwayatTopup keluar)
            $potongP = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'keluar')->where($isPertamaxTopup)->sum('jumlah');
            $potongD = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'keluar')->where($isDexTopup)->sum('jumlah');

            // Total hutang (semua status — sudah_dibayar sudah ada di RiwayatTopup keluar, jadi hanya kurangi belum_dibayar)
            $hutangP = Hutang::where('satker_id', $satker->id)->where('jenis_bbm', 'Pertamax')->where('status', 'belum_dibayar')->sum('jumlah_bon');
            $hutangD = Hutang::where('satker_id', $satker->id)->where('jenis_bbm', 'Pertamina Dex')->where('status', 'belum_dibayar')->sum('jumlah_bon');

            $pertamax = $pendP - $pakaiP - $potongP;
            $dex      = $pendD - $pakaiD - $potongD;

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

        $isPertamaxTopup = function($q) {
            $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX')
              ->orWhereHas('kendaraan', fn($k) => $k->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'));
        };
        $isDexTopup = function($q) {
            $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX')
              ->orWhereHas('kendaraan', fn($k) => $k->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'));
        };
        $isPertamaxTrans = fn($q) => $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX');
        $isDexTrans      = fn($q) => $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX');

        foreach ($satkers as $satker) {
            $pendP = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'masuk')->where($isPertamaxTopup)->sum('jumlah');
            $pendD = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'masuk')->where($isDexTopup)->sum('jumlah');

            $pakaiP = TransaksiBbm::where('satker_id', $satker->id)->where($isPertamaxTrans)->sum('liter');
            $pakaiD = TransaksiBbm::where('satker_id', $satker->id)->where($isDexTrans)->sum('liter');

            $potongP = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'keluar')->where($isPertamaxTopup)->sum('jumlah');
            $potongD = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'keluar')->where($isDexTopup)->sum('jumlah');

            $hutangP = Hutang::where('satker_id', $satker->id)->where('jenis_bbm', 'Pertamax')->where('status', 'belum_dibayar')->sum('jumlah_bon');
            $hutangD = Hutang::where('satker_id', $satker->id)->where('jenis_bbm', 'Pertamina Dex')->where('status', 'belum_dibayar')->sum('jumlah_bon');

            $pertamax = $pendP - $pakaiP - $potongP;
            $dex      = $pendD - $pakaiD - $potongD;

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
