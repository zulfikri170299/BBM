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
                ->orderBy('no_polisi')
                ->get();
            $personels = Personel::where('satker_id', $selectedSatkerId)
                ->orderBy('nama')
                ->get();
        }

        $perPage = $this->getPerPage($request, 20);
        
        // Combined query using DB union
        $transferQuery = \DB::table('riwayat_transfer_saldo_personels')
            ->select('created_at', 'satker_id', 'kendaraan_id', 'personel_id', 'jumlah', 'keterangan');
        
        $potonganQuery = \DB::table('riwayat_topups')
            ->where('tipe', 'keluar')
            ->select('created_at', 'satker_id', 'kendaraan_id', \DB::raw('NULL as personel_id'), 'jumlah', 'keterangan');

        if ($selectedSatkerId) {
            $transferQuery->where('satker_id', $selectedSatkerId);
            $potonganQuery->where('satker_id', $selectedSatkerId);
        }

        $combinedQuery = \DB::table(\DB::raw("({$transferQuery->toSql()}) AS combined"))
            ->mergeBindings($transferQuery)
            ->union($potonganQuery)
            ->orderBy('created_at', 'desc');

        $riwayatRaw = $combinedQuery->paginate($perPage)->withQueryString();

        // Transform collection to include models/relationships
        $riwayatRaw->getCollection()->transform(function($item) {
            $item->satker = \App\Models\Satker::find($item->satker_id);
            $item->kendaraan = \App\Models\Kendaraan::find($item->kendaraan_id);
            $item->personel = $item->personel_id ? \App\Models\Personel::find($item->personel_id) : null;
            $item->tujuan_kendaraan = isset($item->tujuan_kendaraan_id) ? \App\Models\Kendaraan::find($item->tujuan_kendaraan_id) : null;
            $item->created_at = \Carbon\Carbon::parse($item->created_at);
            return $item;
        });

        $riwayat = $riwayatRaw;

        return view('admin.transfer-saldo.index', compact('satkers', 'kendaraans', 'personels', 'riwayat', 'selectedSatkerId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'tipe_tujuan' => 'required|in:personel,kendaraan',
            'personel_id' => 'required_if:tipe_tujuan,personel|nullable|exists:personels,id',
            'tujuan_kendaraan_id' => 'required_if:tipe_tujuan,kendaraan|nullable|exists:kendaraans,id',
            'jumlah' => 'required|numeric|min:0.1',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'kendaraan_id.required' => 'Pilih kendaraan sumber.',
            'personel_id.required_if' => 'Pilih personel tujuan.',
            'tujuan_kendaraan_id.required_if' => 'Pilih kendaraan tujuan.',
            'jumlah.required' => 'Jumlah transfer wajib diisi.',
            'jumlah.min' => 'Jumlah transfer minimal 0.1 Liter.',
        ]);

        $kendaraan = Kendaraan::findOrFail($request->kendaraan_id);
        
        if ($kendaraan->saldo < $request->jumlah) {
            return back()->with('error', 'Saldo kendaraan tidak mencukupi. Tersedia: ' . $kendaraan->saldo . ' L.');
        }

        $tujuan = null;
        if ($request->tipe_tujuan === 'personel') {
            $tujuan = Personel::findOrFail($request->personel_id);
            // Validasi BBM Personel
            if ($tujuan->jenis_bbm && $tujuan->jenis_bbm !== $kendaraan->jenis_bbm) {
                return back()->with('error', 'Transfer ditolak! Personel "' . $tujuan->nama . '" sudah memiliki jenis BBM ' . $tujuan->jenis_bbm . '.');
            }
        } else {
            $tujuan = Kendaraan::findOrFail($request->tujuan_kendaraan_id);
            if ($tujuan->id === $kendaraan->id) {
                return back()->with('error', 'Kendaraan sumber dan tujuan tidak boleh sama.');
            }
            // Validasi BBM Kendaraan
            if ($tujuan->jenis_bbm !== $kendaraan->jenis_bbm) {
                return back()->with('error', 'Transfer ditolak! Kendaraan tujuan memiliki jenis BBM berbeda (' . $tujuan->jenis_bbm . ').');
            }
        }

        DB::transaction(function () use ($kendaraan, $tujuan, $request) {
            // Kurangi saldo kendaraan sumber
            $kendaraan->decrement('saldo', $request->jumlah);

            // Tambah saldo tujuan
            $tujuan->increment('saldo', $request->jumlah);
            
            if ($request->tipe_tujuan === 'personel') {
                $tujuan->update(['jenis_bbm' => $kendaraan->jenis_bbm]);
            }

            // Catat riwayat
            RiwayatTransferSaldoPersonel::create([
                'satker_id' => $kendaraan->satker_id,
                'kendaraan_id' => $kendaraan->id,
                'personel_id' => $request->tipe_tujuan === 'personel' ? $tujuan->id : null,
                'tujuan_kendaraan_id' => $request->tipe_tujuan === 'kendaraan' ? $tujuan->id : null,
                'jumlah' => $request->jumlah,
                'jenis_bbm' => $kendaraan->jenis_bbm ?: 'TANPA JENIS',
                'keterangan' => $request->keterangan,
            ]);

            $targetName = $request->tipe_tujuan === 'personel' ? "Personel ({$tujuan->nama})" : "Kendaraan ({$tujuan->no_polisi})";
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => "Transfer saldo BBM: {$request->jumlah} L dari Kendaraan ({$kendaraan->no_polisi}) ke {$targetName}"
            ]);
        });

        $targetName = $request->tipe_tujuan === 'personel' ? $tujuan->nama : $tujuan->no_polisi;
        return back()->with('success', "Transfer {$request->jumlah} L dari {$kendaraan->no_polisi} ke {$targetName} berhasil.");
    }
}
