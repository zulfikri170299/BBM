<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Kendaraan;
use App\Models\Personel;
use App\Models\RiwayatTopup;
use App\Models\LogAktivitas;
use App\Models\AdminBbmStock;
use App\Models\RiwayatStokAdmin;
use App\Models\Satker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class BulkPotongSaldoController extends Controller
{
    public function index()
    {
        $kendaraans = Kendaraan::with('satker')->orderBy('no_polisi')->get();
        $personels = Personel::with('satker')->orderBy('nama')->get();
        $satkers = Satker::orderBy('nama_satker')->get();
        
        return view('admin.potong-saldo.index', compact('kendaraans', 'personels', 'satkers'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'type' => 'required|in:kendaraan,personel',
            'ids' => 'required|array',
            'ids.*' => 'integer',
            'is_kosongkan' => 'required|in:1',
            'kembalikan_ke_stok' => 'required|in:ya,tidak',
            'keterangan' => 'required|string|max:255',
            'topup_password' => 'required|string',
        ]);

        $user = auth()->user();

        // 0. Validasi Password Top Up
        if (!$user->topup_password) {
            return back()->with('error', 'Anda belum mengatur Password Top Up. Silakan atur di Profil.');
        }

        if (!Hash::check($request->topup_password, $user->topup_password)) {
            return back()->with('error', 'Password Top Up salah!');
        }

        $type = $request->type;
        $ids = $request->ids;
        $isKosongkan = $request->is_kosongkan === '1';
        $jumlah = $isKosongkan ? 9999999 : $request->jumlah; // Jika kosongkan, set nilai sangat besar agar min(jumlah, saldo) selalu saldo
        $kembalikanKeStok = $request->kembalikan_ke_stok === 'ya';
        $keterangan = $request->keterangan;

        try {
            DB::beginTransaction();

            $successCount = 0;
            $failCount = 0;

            if ($type === 'kendaraan') {
                $entities = Kendaraan::whereIn('id', $ids)->get();
            } else {
                $entities = Personel::whereIn('id', $ids)->get();
            }

            foreach ($entities as $entity) {
                $potong = min($jumlah, $entity->saldo);
                
                if ($potong <= 0) {
                    $failCount++;
                    continue;
                }

                // 1. Kurangi Saldo
                $entity->decrement('saldo', $potong);

                // 2. Catat Riwayat
                RiwayatTopup::create([
                    'satker_id' => $entity->satker_id,
                    'kendaraan_id' => $type === 'kendaraan' ? $entity->id : null,
                    'personel_id' => $type === 'personel' ? $entity->id : null,
                    'user_id' => $user->id,
                    'jumlah' => $potong,
                    'tipe' => 'keluar',
                    'metode' => $kembalikanKeStok ? 'POTONG_SALDO_MASAL' : 'POTONG_SALDO_MASAL_HANGUS',
                    'status' => 'success',
                    'jenis_bbm' => $entity->jenis_bbm ?: 'TANPA JENIS',
                    'keterangan' => $keterangan,
                ]);

                // 3. Jika dikembalikan ke stok
                if ($kembalikanKeStok) {
                    $adminStock = AdminBbmStock::firstOrCreate(
                        ['jenis_bbm' => $entity->jenis_bbm],
                        ['saldo' => 0]
                    );
                    $adminStock->increment('saldo', $potong);

                    RiwayatStokAdmin::create([
                        'user_id' => $user->id,
                        'jenis_bbm' => $entity->jenis_bbm,
                        'jumlah' => $potong,
                        'tipe' => 'masuk',
                        'keterangan' => "Pengembalian saldo (Masal) dari " . ($type === 'kendaraan' ? "kendaraan {$entity->no_polisi}" : "personel {$entity->nama}") . ". Ket: {$keterangan}",
                    ]);
                }

                $successCount++;
            }

            LogAktivitas::create([
                'user_id' => $user->id,
                'aktivitas' => "Potong saldo masal {$type} sebanyak {$successCount} data. Ket: {$keterangan}"
            ]);

            DB::commit();

            return back()->with('success', "Berhasil memotong saldo {$successCount} {$type}. " . ($failCount > 0 ? "Gagal: {$failCount} (Saldo 0)." : ""));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Bulk Potong Saldo Error: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
