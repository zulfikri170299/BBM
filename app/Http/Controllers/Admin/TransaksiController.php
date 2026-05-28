<?php

namespace App\Http\Controllers\Admin;

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
        return view('admin.transaksi.index');
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
                
                // Jika tidak ditemukan, cari di Personel (NRP)
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

            if (!$target->satker) {
                $message = 'Data Satker tidak ditemukan/corrupt.';
                if ($request->wantsJson()) return response()->json(['success' => false, 'message' => $message], 422);
                return back()->withErrors(['error' => $message]);
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $target,
                    'type' => ($target instanceof Kendaraan) ? 'kendaraan' : 'personel',
                    'satker' => $target->satker->nama_satker
                ]);
            }

            $kendaraan = ($target instanceof Kendaraan) ? $target : null;
            $personel = ($target instanceof Personel) ? $target : null;
            
            // Fallback for non-AJAX requests (though unlikely in current SPA design)
            return redirect()->route('admin.transaksi.index')->with([
                'target_data' => $target,
                'target_type' => ($target instanceof Kendaraan) ? 'kendaraan' : 'personel'
            ]);

        } catch (\Throwable $e) {
            if ($request->wantsJson()) return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            return back()->withErrors(['error' => 'SYSTEM ERROR: ' . $e->getMessage()]);
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
            return redirect()->route('admin.transaksi.index')->withErrors(['error' => 'Data tidak valid.']);
        }

        if ($request->kendaraan_id) {
            $target = Kendaraan::findOrFail($request->kendaraan_id);
        } else {
            $target = Personel::findOrFail($request->personel_id);
        }

        if ($request->pin !== $target->pin) {
            return redirect()->route('admin.transaksi.index')->withErrors(['pin' => 'PIN Salah!']);
        }

        if ($target->saldo < $request->liter) {
            return redirect()->route('admin.transaksi.index')->with('error', 'Saldo tidak mencukupi! Saldo tersisa: ' . number_format($target->saldo, 0, ',', '.') . ' Liter');
        }

        $prices = [
            'Pertamax' => 12950,
            'Pertamina Dex' => 13900,
        ];

        $hargaPerLiter = $prices[$target->jenis_bbm] ?? 0;
        $totalHarga = $hargaPerLiter * $request->liter;

        if ($target instanceof \App\Models\Personel) {
            $target->saldo -= $request->liter;
            $target->save();
        } else {
            $target->decrement('saldo', $request->liter);
        }

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
            'aktivitas' => "(Admin) Memproses pengisian BBM: {$request->liter} L untuk " . ($request->kendaraan_id ? "Kendaraan ({$target->no_polisi})" : "Personel ({$target->nama})")
        ]);

        return redirect()->route('admin.transaksi.print', $transaksi);
    }

    public function print(TransaksiBbm $transaksi)
    {
        $transaksi->load('kendaraan.satker', 'personel.satker', 'petugas');
        return view('admin.transaksi.print', compact('transaksi'));
    }
}
