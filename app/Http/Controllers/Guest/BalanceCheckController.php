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
        $personelAccessControl = \App\Models\Setting::where('key', 'personel_access_control')->value('value') ?? '1';
        return view('public.balance-check', compact('personelAccessControl'));
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

        return back()->with('error', 'Data tidak ditemukan. Pastikan Nopol yang Anda masukkan benar.');
    }
}
