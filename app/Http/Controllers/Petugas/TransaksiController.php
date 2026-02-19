<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\TransaksiBbm;
use App\Models\Personel;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        return view('petugas.transaksi.index');
    }

    public function check(Request $request)
    {
        try {
            $mode = $request->input('mode', 'barcode');

            if ($mode === 'nopol') {
                $request->validate([
                    'nopol' => 'required|string',
                ]);

                $kendaraan = Kendaraan::with('satker')
                    ->where('no_polisi', 'like', '%' . trim($request->nopol) . '%')
                    ->first();

                if (!$kendaraan) {
                    return back()->withErrors(['nopol' => 'Kendaraan dengan nopol "' . $request->nopol . '" tidak ditemukan.']);
                }

                // Cek apakah Admin Satker aktif
                $satkerAdminActive = \App\Models\User::where('satker_id', $kendaraan->satker_id)
                    ->where('role', 'admin_satker')
                    ->where('is_active', true)
                    ->exists();
                
                if (!$satkerAdminActive) {
                    return back()->withErrors(['nopol' => 'Akun Satker Anda sedang dinonaktifkan. Silakan hubungi Super Admin.']);
                }

                // Fix White Screen: Pastikan data satker ada
                // Fix White Screen: Pastikan data satker ada
                if (!$kendaraan->satker) {
                    return back()->withErrors(['nopol' => 'Data Satker untuk kendaraan ini tidak ditemukan/corrupt. Silakan hubungi Super Admin.']);
                }

                // DEBUG CHECKPOINT 2
                dd([
                    'STATUS' => 'LOGIC SUCCESS (NOPOL)',
                    'KENDARAAN' => $kendaraan->toArray(),
                    'SATKER' => $kendaraan->satker->toArray()
                ]);

            } elseif ($mode === 'nrp') {
                $request->validate([
                    'nrp' => 'required|string',
                ]);

                $personel = Personel::with(['satker', 'user'])
                    ->where('nrp', 'like', '%' . trim($request->nrp) . '%')
                    ->first();

                if (!$personel) {
                    return back()->withErrors(['nrp' => 'Personel dengan NRP "' . $request->nrp . '" tidak ditemukan.']);
                }

                // Cek apakah Akun User Personel aktif
                if ($personel->user_id && (!$personel->user || !($personel->user->is_active ?? false))) {
                    return back()->withErrors(['nrp' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi Super Admin.']);
                }

                // Fix White Screen: Pastikan data satker ada
                if (!$personel->satker) {
                    return back()->withErrors(['nrp' => 'Data Satker untuk personel ini tidak ditemukan/corrupt. Silakan hubungi Super Admin.']);
                }

                if (!$personel->satker) {
                    return back()->withErrors(['nrp' => 'Data Satker untuk personel ini tidak ditemukan/corrupt. Silakan hubungi Super Admin.']);
                }

                return view('petugas.transaksi.create', compact('personel'));
            } else {
                $request->validate([
                    'barcode' => 'required|string',
                ]);

                $kendaraan = Kendaraan::with('satker')
                    ->where('barcode', $request->barcode)
                    ->first();

                if ($kendaraan) {
                    // Cek apakah Admin Satker aktif
                    $satkerAdminActive = \App\Models\User::where('satker_id', $kendaraan->satker_id)
                        ->where('role', 'admin_satker')
                        ->where('is_active', true)
                        ->exists();
                    
                    
                    if (!$satkerAdminActive) {
                        return back()->withErrors(['barcode' => 'Akun Satker Anda sedang dinonaktifkan. Silakan hubungi Super Admin.']);
                    }
                    
                    // Fix White Screen: Pastikan data satker ada
                    if (!$kendaraan->satker) {
                        return back()->withErrors(['barcode' => 'Data Satker untuk kendaraan ini tidak ditemukan/corrupt. Silakan hubungi Super Admin.']);
                    }
                    
                if (!$kendaraan->satker) {
                    return back()->withErrors(['barcode' => 'Data Satker untuk kendaraan ini tidak ditemukan/corrupt. Silakan hubungi Super Admin.']);
                }
                
                return response(view('petugas.transaksi.create', compact('kendaraan'))->render());
                }

                // Jika kendaraan tidak ditemukan, cari di personel
                $personel = Personel::with(['satker', 'user'])
                    ->where('barcode', $request->barcode)
                    ->first();

                if ($personel) {
                    // Cek apakah Akun User Personel aktif
                    if ($personel->user_id && (!$personel->user || !($personel->user->is_active ?? false))) {
                        return back()->withErrors(['barcode' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi Super Admin.']);
                    }

                    // Fix White Screen: Pastikan data satker ada
                    if (!$personel->satker) {
                        return back()->withErrors(['barcode' => 'Data Satker untuk personel ini tidak ditemukan/corrupt. Silakan hubungi Super Admin.']);
                    }

                if (!$personel->satker) {
                    return back()->withErrors(['barcode' => 'Data Satker untuk personel ini tidak ditemukan/corrupt. Silakan hubungi Super Admin.']);
                }

                return view('petugas.transaksi.create', compact('personel'));
                }

                return back()->withErrors(['barcode' => 'Barcode "' . $request->barcode . '" tidak ditemukan.']);
            }
        } catch (\Throwable $e) {
            die('SYSTEM ERROR (VIEW RENDERING): ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        }
    }

    public function process(Request $request)
    {
        $request->validate([
            'kendaraan_id' => 'nullable|exists:kendaraans,id',
            'personel_id' => 'nullable|exists:personels,id',
            'liter' => 'required|numeric|min:0.1',
            'nama_driver' => 'required|string|max:255',
            'pin' => 'required|numeric',
        ]);

        if (!$request->kendaraan_id && !$request->personel_id) {
            return redirect()->route('petugas.transaksi.index')->withErrors(['error' => 'Data tidak valid.']);
        }

        if ($request->kendaraan_id) {
            $target = Kendaraan::findOrFail($request->kendaraan_id);
        } else {
            $target = Personel::findOrFail($request->personel_id);
        }

        // Verifikasi PIN
        if ($request->pin !== $target->pin) {
            return redirect()->route('petugas.transaksi.index')->withErrors(['pin' => 'PIN Salah!']);
        }

        // Cek saldo liter (sudah dibulatkan di tampilan, pesan harus bulat)
        if ($target->saldo < $request->liter) {
            return redirect()->route('petugas.transaksi.index')->with('error', 'Saldo tidak mencukupi! Saldo tersisa: ' . number_format($target->saldo, 0, ',', '.') . ' Liter');
        }

        // Harga BBM per liter
        $prices = [
            'Pertamax' => 12950,
            'Pertamina Dex' => 13900,
        ];

        $hargaPerLiter = $prices[$target->jenis_bbm] ?? 0;
        $totalHarga = $hargaPerLiter * $request->liter;

        // Potong saldo
        $target->decrement('saldo', $request->liter);

        // Catat transaksi
        $transaksi = TransaksiBbm::create([
            'kendaraan_id' => $request->kendaraan_id,
            'personel_id' => $request->personel_id,
            'petugas_id' => auth()->id(),
            'nama_driver' => $request->nama_driver,
            'tanggal' => now(),
            'liter' => $request->liter,
            'harga_per_liter' => $hargaPerLiter,
            'total' => $totalHarga,
        ]);

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Memproses pengisian BBM: {$request->liter} L untuk " . ($request->kendaraan_id ? "Kendaraan ({$target->no_polisi})" : "Personel ({$target->nama})")
        ]);

        return redirect()->route('petugas.transaksi.print', $transaksi);
    }

    public function print(TransaksiBbm $transaksi)
    {
        $transaksi->load('kendaraan.satker', 'personel.satker', 'petugas');

        return view('petugas.transaksi.print', compact('transaksi'));
    }
}
