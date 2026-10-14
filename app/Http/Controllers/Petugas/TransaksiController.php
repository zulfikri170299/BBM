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

            } elseif ($mode === 'nopol') {
                $request->validate(['nopol' => 'required|string']);
                $target = Kendaraan::with('satker')
                    ->where('no_polisi', 'like', '%' . trim($request->nopol) . '%')
                    ->first();
            } else {
                $request->validate(['barcode' => 'required|string']);
                $target = Kendaraan::with('satker')->where('barcode', $request->barcode)->first();
            }

            if (!$target) {
                $message = $mode === 'barcode' ? 'Barcode kendaraan tidak ditemukan.' : 'Data kendaraan tidak ditemukan.';
                if ($request->wantsJson()) return response()->json(['success' => false, 'message' => $message], 404);
                return back()->withErrors(['error' => $message]);
            }

            // Validasi akun aktif untuk Kendaraan (via Admin Satker)
            if ($target instanceof Kendaraan) {
                if ($target->status_approval === 'pending') {
                    $message = "Kendaraan {$target->no_polisi} belum dapat bertransaksi karena masih MENUNGGU PERSETUJUAN Superadmin.";
                    if ($request->wantsJson()) return response()->json(['success' => false, 'message' => $message], 422);
                    return back()->withErrors(['error' => $message]);
                }
                if ($target->status_approval === 'ditolak' && $target->aksi_approval !== 'edit') {
                    $alasan = $target->alasan_penolakan ? " (Alasan: {$target->alasan_penolakan})" : "";
                    $message = "Kendaraan {$target->no_polisi} tidak dapat bertransaksi karena pengajuan DITOLAK Superadmin.{$alasan}";
                    if ($request->wantsJson()) return response()->json(['success' => false, 'message' => $message], 422);
                    return back()->withErrors(['error' => $message]);
                }

                // Coba lunasi otomatis jika saldo saat ini sudah mencukupi
                $target->autoSettleBon();

                // Validasi Saldo Dikunci jika Kendaraan memiliki BON yang belum dibayar
                if ($target->hasUnpaidBon()) {
                    $totalBon = $target->totalUnpaidBon();
                    $message = "Saldo kendaraan {$target->no_polisi} sedang DIKUNCI karena memiliki BON/Hutang BBM yang belum dibayar sebesar {$totalBon} Liter. Harap lunasi BON terlebih dahulu sebelum menggunakan saldo.";
                    if ($request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => $message,
                            'is_bon_locked' => true,
                            'bon_total' => $totalBon,
                        ], 422);
                    }
                    return back()->withErrors(['error' => $message]);
                }

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
                    'type' => 'kendaraan',
                    'satker' => $target->satker->nama_satker
                ]);
            }

            // Fallback
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
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'liter' => 'required|integer|min:1',
            'nama_driver' => 'required|string|max:255',
            'pin' => 'required|integer',
        ]);

        $target = Kendaraan::findOrFail($request->kendaraan_id);
        if ($target->status_approval === 'pending') {
            return redirect()->route('petugas.transaksi.index')->with('error', "Transaksi ditolak! Kendaraan {$target->no_polisi} masih menunggu persetujuan Superadmin.");
        }
        if ($target->status_approval === 'ditolak' && $target->aksi_approval !== 'edit') {
            return redirect()->route('petugas.transaksi.index')->with('error', "Transaksi ditolak! Pengajuan kendaraan {$target->no_polisi} ditolak oleh Superadmin.");
        }

        $target->autoSettleBon();
        if ($target->hasUnpaidBon()) {
            $totalBon = $target->totalUnpaidBon();
            return redirect()->route('petugas.transaksi.index')->with('error', "Transaksi ditolak! Saldo kendaraan {$target->no_polisi} sedang dikunci karena masih memiliki BON belum dibayar sebesar {$totalBon} Liter. Harap lunasi BON terlebih dahulu.");
        }

        // Verifikasi PIN
        if ($request->pin !== $target->pin) {
            return redirect()->route('petugas.transaksi.index')->withErrors(['pin' => 'PIN Salah!']);
        }

        // Cek saldo liter
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
            'satker_id' => $target->satker_id,
            'kendaraan_id' => $target->id,
            'nopol' => $target->no_polisi,
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
            'aktivitas' => "Memproses pengisian BBM: {$request->liter} L untuk Kendaraan ({$target->no_polisi})"
        ]);

        return redirect()->route('petugas.transaksi.print', $transaksi);
    }

    public function print(TransaksiBbm $transaksi)
    {
        $transaksi->load('kendaraan.satker', 'satker', 'petugas');

        return view('petugas.transaksi.print', compact('transaksi'));
    }
}
