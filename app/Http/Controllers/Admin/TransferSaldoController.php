<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Personel;
use App\Models\Satker;
use App\Models\RiwayatTransferSaldoPersonel;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                ->where('saldo', '>', 0)
                ->orderBy('no_polisi')
                ->get();
            $personels = Personel::where('satker_id', $selectedSatkerId)
                ->orderBy('nama')
                ->get();
        }

        $perPage = $this->getPerPage($request, 20);
        $query = RiwayatTransferSaldoPersonel::with(['kendaraan', 'personel', 'satker'])->latest();
        if ($selectedSatkerId) {
            $query->where('satker_id', $selectedSatkerId);
        }
        $riwayat = $query->paginate($perPage)->withQueryString();

        return view('admin.transfer-saldo.index', compact('satkers', 'kendaraans', 'personels', 'riwayat', 'selectedSatkerId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'personel_id' => 'required|exists:personels,id',
            'jumlah' => 'required|numeric|min:0.1',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'kendaraan_id.required' => 'Pilih kendaraan sumber.',
            'personel_id.required' => 'Pilih personel tujuan.',
            'jumlah.required' => 'Jumlah transfer wajib diisi.',
            'jumlah.min' => 'Jumlah transfer minimal 0.1 Liter.',
        ]);

        $kendaraan = Kendaraan::findOrFail($request->kendaraan_id);
        $personel = Personel::findOrFail($request->personel_id);

        if ($kendaraan->saldo < $request->jumlah) {
            return back()->with('error', 'Saldo kendaraan tidak mencukupi. Tersedia: ' . $kendaraan->saldo . ' L.');
        }

        // Tolak transfer jika personel sudah punya jenis BBM berbeda
        if ($personel->jenis_bbm && $personel->jenis_bbm !== $kendaraan->jenis_bbm) {
            return back()->with('error', 'Transfer ditolak! Personel "' . $personel->nama . '" sudah memiliki jenis BBM ' . $personel->jenis_bbm . '. Tidak bisa menerima BBM ' . $kendaraan->jenis_bbm . '.');
        }

        DB::transaction(function () use ($kendaraan, $personel, $request) {
            // Kurangi saldo kendaraan
            $kendaraan->decrement('saldo', $request->jumlah);

            // Tambah saldo personel & update jenis BBM
            $personel->increment('saldo', $request->jumlah);
            $personel->update(['jenis_bbm' => $kendaraan->jenis_bbm]);

            // Catat riwayat
            RiwayatTransferSaldoPersonel::create([
                'satker_id' => $kendaraan->satker_id,
                'kendaraan_id' => $kendaraan->id,
                'personel_id' => $personel->id,
                'jumlah' => $request->jumlah,
                'keterangan' => $request->keterangan,
            ]);

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => "Transfer saldo BBM (Super Admin): {$request->jumlah} L dari Kendaraan ({$kendaraan->no_polisi}) ke Personel ({$personel->nama})"
            ]);
        });

        return back()->with('success', "Transfer {$request->jumlah} L dari {$kendaraan->no_polisi} ke {$personel->nama} berhasil.");
    }
}
