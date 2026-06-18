<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Personel;
use App\Models\Satker;
use App\Models\RiwayatTransferSaldoPersonel;
use App\Models\RiwayatStokAdmin;
use App\Models\AdminBbmStock;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Traits\PaginatesTables;

class TransferSaldoController extends Controller
{
    use PaginatesTables;

    public function index(Request $request)
    {
        $satkers = Satker::orderBy('nama_satker')->get();
        $selectedSatkerId = $request->input('satker_id');

        $kendaraans = collect();
        $personels = collect();

        if ($selectedSatkerId) {
            $kendaraans = Kendaraan::where('satker_id', $selectedSatkerId)
                ->orderBy('no_polisi')
                ->get();
            $personels = Personel::where('satker_id', $selectedSatkerId)
                ->orderBy('nama')
                ->get();
        }

        $perPage = $this->getPerPage($request, 20);
        
        $query = RiwayatTransferSaldoPersonel::with(['satker', 'tujuanKendaraan', 'kendaraan', 'personel']);

        if ($selectedSatkerId) {
            $query->where('satker_id', $selectedSatkerId);
        }

        $riwayat = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
        
        $adminStocks = AdminBbmStock::all();

        return view('admin.transfer-saldo.index', compact('satkers', 'kendaraans', 'personels', 'riwayat', 'selectedSatkerId', 'adminStocks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe_tujuan' => 'required|in:personel,kendaraan',
            'kendaraan_id' => 'required_if:tipe_tujuan,personel|nullable|exists:kendaraans,id',
            'personel_id' => 'required_if:tipe_tujuan,personel|nullable|exists:personels,id',
            'tujuan_kendaraan_id' => 'required_if:tipe_tujuan,kendaraan|nullable|exists:kendaraans,id',
            'jumlah' => 'required|numeric|min:0.1',
            'topup_password' => 'required|string',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'kendaraan_id.required_if' => 'Pilih kendaraan sumber.',
            'personel_id.required_if' => 'Pilih personel tujuan.',
            'tujuan_kendaraan_id.required_if' => 'Pilih kendaraan tujuan.',
            'jumlah.required' => 'Jumlah transfer wajib diisi.',
            'jumlah.min' => 'Jumlah transfer minimal 0.1 Liter.',
            'topup_password.required' => 'PIN/Password Top Up wajib diisi.',
        ]);

        $user = auth()->user();

        if (!$user->topup_password) {
            return back()->with('error', 'Anda belum mengatur Password Top Up. Silakan atur di menu Profil > Password Top Up.');
        }

        if (!Hash::check($request->topup_password, $user->topup_password)) {
            return back()->with('error', 'PIN/Password Top Up salah! Transaksi dibatalkan.');
        }

        try {
            DB::beginTransaction();

            if ($request->tipe_tujuan === 'personel') {
                $personelAccessControl = \App\Models\Setting::where('key', 'personel_access_control')->value('value') ?? '1';
                if ($personelAccessControl == '0') {
                    return back()->with('error', 'Fitur transfer ke personel sedang dinonaktifkan.');
                }

                // KENDARAAN -> PERSONEL
                $kendaraan = Kendaraan::findOrFail($request->kendaraan_id);
                $personel = Personel::findOrFail($request->personel_id);

                if ($kendaraan->saldo < $request->jumlah) {
                    return back()->with('error', "Saldo kendaraan {$kendaraan->no_polisi} tidak mencukupi. Tersedia: {$kendaraan->saldo} L.");
                }

                if ($personel->jenis_bbm && $personel->jenis_bbm !== $kendaraan->jenis_bbm) {
                    return back()->with('error', "Transfer ditolak! Personel {$personel->nama} memiliki jenis BBM {$personel->jenis_bbm}.");
                }

                $kendaraan->decrement('saldo', $request->jumlah);
                $personel->increment('saldo', $request->jumlah);
                $personel->update(['jenis_bbm' => $kendaraan->jenis_bbm]);

                RiwayatTransferSaldoPersonel::create([
                    'satker_id' => $kendaraan->satker_id,
                    'kendaraan_id' => $kendaraan->id,
                    'personel_id' => $personel->id,
                    'tujuan_kendaraan_id' => null,
                    'jumlah' => $request->jumlah,
                    'jenis_bbm' => $kendaraan->jenis_bbm ?: 'TANPA JENIS',
                    'keterangan' => $request->keterangan ?: 'Transfer dari Kendaraan ke Personel',
                ]);

                LogAktivitas::create([
                    'user_id' => $user->id,
                    'aktivitas' => "Transfer {$request->jumlah} L dari Kendaraan ({$kendaraan->no_polisi}) ke Personel ({$personel->nama})"
                ]);

                DB::commit();
                return back()->with('success', "Transfer {$request->jumlah} L dari Kendaraan {$kendaraan->no_polisi} ke Personel {$personel->nama} berhasil.");

            } else {
                // STOK PUSAT -> KENDARAAN
                $tujuan = Kendaraan::findOrFail($request->tujuan_kendaraan_id);

                $adminStock = AdminBbmStock::where('jenis_bbm', $tujuan->jenis_bbm)->first();
                if (!$adminStock || $adminStock->saldo < $request->jumlah) {
                    return back()->with('error', "Stok Pusat untuk {$tujuan->jenis_bbm} tidak cukup. Tersedia: " . ($adminStock ? $adminStock->saldo : 0) . " L.");
                }

                $adminStock->decrement('saldo', $request->jumlah);

                RiwayatStokAdmin::create([
                    'user_id' => $user->id,
                    'jenis_bbm' => $tujuan->jenis_bbm,
                    'jumlah' => $request->jumlah,
                    'tipe' => 'keluar',
                    'keterangan' => "Transfer saldo (TM) untuk kendaraan {$tujuan->no_polisi}. Ket: {$request->keterangan}",
                ]);

                $tujuan->increment('saldo', $request->jumlah);

                RiwayatTransferSaldoPersonel::create([
                    'satker_id' => $tujuan->satker_id,
                    'kendaraan_id' => null,
                    'personel_id' => null,
                    'tujuan_kendaraan_id' => $tujuan->id,
                    'jumlah' => $request->jumlah,
                    'jenis_bbm' => $tujuan->jenis_bbm ?: 'TANPA JENIS',
                    'keterangan' => $request->keterangan ?: 'Transfer dari Pusat ke Kendaraan',
                ]);

                LogAktivitas::create([
                    'user_id' => $user->id,
                    'aktivitas' => "Transfer saldo BBM Pusat sebesar {$request->jumlah} L ke Kendaraan ({$tujuan->no_polisi})"
                ]);

                DB::commit();
                return back()->with('success', "Transfer {$request->jumlah} L dari Pusat ke Kendaraan {$tujuan->no_polisi} berhasil (Masuk ke kolom TM).");
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

