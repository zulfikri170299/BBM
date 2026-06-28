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
        $personelAccessControl = \App\Models\Setting::where('key', 'personel_access_control')->value('value') ?? '1';
        return view('petugas.transaksi.index', compact('personelAccessControl'));
    }

    public function check(Request $request)
    {
        try {
            $mode = $request->input('mode', 'barcode');

            if ($mode === 'manual') {
                $val = str_replace([' ', '-', '.'], '', trim($request->value));
                if (empty($val)) {
                    return response()->json(['success' => false, 'message' => 'Input tidak boleh kosong.'], 422);
                }

                // Cari di Kendaraan (Nopol)
                $target = Kendaraan::with('satker')
                    ->whereRaw("REPLACE(REPLACE(REPLACE(no_polisi, ' ', ''), '-', ''), '.', '') LIKE ?", ["%$val%"])
                    ->first();

                // Jika tidak ditemukan di kendaraan, cari di personel
                if (!$target) {
                    $target = Personel::with(['satker', 'user'])
                        ->where('nrp', 'like', "%$val%")
                        ->first();
                }

            } elseif ($mode === 'nopol') {
                $request->validate(['nopol' => 'required|string']);
                $target = Kendaraan::with('satker')
                    ->where('no_polisi', 'like', '%' . trim($request->nopol) . '%')
                    ->first();
            } elseif ($mode === 'nrp') {
                $request->validate(['nrp' => 'required|string']);
                $target = Personel::with(['satker', 'user'])
                    ->where('nrp', 'like', '%' . trim($request->nrp) . '%')
                    ->first();
            } else {
                $request->validate(['barcode' => 'required|string']);
                $target = Kendaraan::with('satker')->where('barcode', $request->barcode)->first();
                if (!$target) {
                    $target = Personel::with(['satker', 'user'])->where('barcode', $request->barcode)->first();
                }
            }

            if (!$target) {
                $message = $mode === 'barcode' ? 'Barcode tidak ditemukan.' : 'Data tidak ditemukan.';
                if ($request->wantsJson()) return response()->json(['success' => false, 'message' => $message], 404);
                return back()->withErrors(['error' => $message]);
            }

            // Validasi akun aktif untuk Kendaraan (via Admin Satker)
            if ($target instanceof Kendaraan) {
                $hasAdminSatker = \App\Models\User::where('satker_id', $target->satker_id)
                    ->where('role', 'admin_satker')
                    ->exists();
                
                if ($hasAdminSatker) {
                    $activeAdminExists = \App\Models\User::where('satker_id', $target->satker_id)
                        ->where('role', 'admin_satker')
                        ->where('is_active', true)
                        ->exists();
                    
                    if (!$activeAdminExists) {
                        $message = 'Akun Satker Anda sedang dinonaktifkan. Silakan hubungi Super Admin.';
                        if ($request->wantsJson()) return response()->json(['success' => false, 'message' => $message], 403);
                        return back()->withErrors(['error' => $message]);
                    }
                }
            }

            // Validasi akun aktif untuk Personel
            if ($target instanceof Personel) {
                $personelAccessControl = \App\Models\Setting::where('key', 'personel_access_control')->value('value') ?? '1';
                if ($personelAccessControl == '0') {
                    $message = 'Kartu Anda Sedang di Nonaktifkan';
                    if ($request->wantsJson()) return response()->json(['success' => false, 'message' => $message], 403);
                    return back()->withErrors(['error' => $message]);
                }

                if ($target->user_id && (!$target->user || !($target->user->is_active ?? false))) {
                    $message = 'Akun Anda sedang dinonaktifkan. Silakan hubungi Super Admin.';
                    if ($request->wantsJson()) return response()->json(['success' => false, 'message' => $message], 403);
                    return back()->withErrors(['error' => $message]);
                }
            }

            // Data Satker corrupt check
            if (!$target->satker) {
                $message = 'Data Satker tidak ditemukan/corrupt. Silakan hubungi Super Admin.';
                if ($request->wantsJson()) return response()->json(['success' => false, 'message' => $message], 422);
                return back()->withErrors(['error' => $message]);
            }

            // Return SPA JSON Response
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $target,
                    'type' => ($target instanceof Kendaraan) ? 'kendaraan' : 'personel',
                    'satker' => $target->satker->nama_satker
                ]);
            }

            // Fallback (selalu respon error jika non-AJAX karena template diwajibkan SPA)
            return back()->withErrors(['error' => 'Harus melalui AJAX']);

        } catch (\Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'SYSTEM ERROR: ' . $e->getMessage()], 500);
            }

            report($e);

            return back()->withErrors(['error' => 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi admin.']);
        }
    }

    public function process(Request $request)
    {
        $request->validate([
            'kendaraan_id' => 'nullable|exists:kendaraans,id',
            'personel_id' => 'nullable|exists:personels,id',
            'liter' => 'required|integer|min:1',
            'nama_driver' => 'required|string|max:255',
            'pin' => 'required|integer',
        ]);

        if (!$request->kendaraan_id && !$request->personel_id) {
            return redirect()->route('petugas.transaksi.index')->withErrors(['error' => 'Data tidak valid.']);
        }

        if ($request->kendaraan_id) {
            $target = Kendaraan::findOrFail($request->kendaraan_id);
        } else {
            $personelAccessControl = \App\Models\Setting::where('key', 'personel_access_control')->value('value') ?? '1';
            if ($personelAccessControl == '0') {
                return redirect()->route('petugas.transaksi.index')->withErrors(['error' => 'Kartu Anda Sedang di Nonaktifkan']);
            }
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
        if ($target instanceof \App\Models\Personel) {
            $target->saldo -= $request->liter;
            $target->save(); // Trigger eloquent 'saving' event
        } else {
            $target->decrement('saldo', $request->liter);
        }

        // Catat transaksi
        $transaksi = TransaksiBbm::create([
            'satker_id' => $target->satker_id,
            'kendaraan_id' => $request->kendaraan_id,
            'personel_id' => $request->personel_id,
            'petugas_id' => auth()->id(),
            'nama_driver' => $request->nama_driver,
            'tanggal' => \Carbon\Carbon::now('Asia/Makassar'),
            'liter' => $request->liter,
            'harga_per_liter' => $hargaPerLiter,
            'total' => $totalHarga,
            'jenis_bbm' => $target->jenis_bbm ?: 'TANPA JENIS',
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
