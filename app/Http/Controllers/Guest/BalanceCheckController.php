<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Personel;
use Illuminate\Http\Request;

class BalanceCheckController extends Controller
{
    public function index()
    {
        return view('public.balance-check');
    }

    public function check(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
        ]);

        $identifier = strtoupper(trim($request->identifier));
        $identifierNoSpace = str_replace(' ', '', $identifier);

        // Cari di Kendaraan (Nopol atau Barcode)
        // Coba cari yang persis dulu (dengan spasi)
        $kendaraan = Kendaraan::where('no_polisi', '=', $identifier, 'and')
            ->orWhere('barcode', '=', $identifier)
            ->first();

        // Jika tidak ketemu, coba cari tanpa spasi di DB
        if (!$kendaraan) {
            $kendaraan = Kendaraan::whereRaw("REPLACE(no_polisi, ' ', '') = ?", [$identifierNoSpace], 'and')
                ->first();
        }

        if ($kendaraan) {
            return view('public.balance-result', [
                'type' => 'kendaraan',
                'data' => $kendaraan,
                'title' => 'Data Kendaraan',
                'name' => $kendaraan->jenis_kendaraan,
                'id_label' => 'Nomor Polisi',
                'id_value' => $kendaraan->no_polisi,
                'jenis_bbm' => $kendaraan->jenis_bbm,
                'saldo' => $kendaraan->saldo,
            ]);
        }

        // Cari di Personel (NRP atau Barcode)
        // Coba cari yang persis dulu
        $personel = Personel::where('nrp', '=', $identifier, 'and')
            ->orWhere('barcode', '=', $identifier)
            ->first();

        // Jika tidak ketemu, coba cari tanpa spasi
        if (!$personel) {
            $personel = Personel::whereRaw("REPLACE(nrp, ' ', '') = ?", [$identifierNoSpace], 'and')
                ->first();
        }

        if ($personel) {
            return view('public.balance-result', [
                'type' => 'personel',
                'data' => $personel,
                'title' => 'Data Personel',
                'name' => $personel->nama,
                'id_label' => 'NRP',
                'id_value' => $personel->nrp,
                'jenis_bbm' => $personel->jenis_bbm,
                'saldo' => $personel->saldo,
            ]);
        }

        return back()->with('error', 'Data tidak ditemukan. Pastikan Nopol atau NRP yang Anda masukkan benar.');
    }
}
