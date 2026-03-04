<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HutangController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Hutang::with(['satker', 'petugas', 'adminBayar'])->orderBy('created_at', 'desc');

        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->input('per_page', 15);
        $hutangs = $query->paginate($perPage)->withQueryString();
        $satkers = \App\Models\Satker::orderBy('nama_satker')->get();

        // Hitung Summary Hutang (Hanya yang belum dibayar)
        $summaryQuery = \App\Models\Hutang::where('status', 'belum_dibayar');
        if ($request->filled('satker_id')) {
            $summaryQuery->where('satker_id', $request->satker_id);
        }
        $summaryHutang = $summaryQuery->selectRaw('jenis_bbm, SUM(jumlah_bon) as total')
            ->groupBy('jenis_bbm')
            ->pluck('total', 'jenis_bbm');

        return view('admin.hutang.index', compact('hutangs', 'satkers', 'summaryHutang'));
    }

    public function downloadPDF(Request $request)
    {
        $query = \App\Models\Hutang::with(['satker', 'petugas', 'adminBayar'])->orderBy('created_at', 'desc');

        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $hutangs = $query->get();
        $satker = null;
        if ($request->filled('satker_id')) {
            $satker = \App\Models\Satker::find($request->satker_id);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan_hutang.print', [
            'hutangs' => $hutangs,
            'satker' => $satker,
            'filter_status' => $request->status
        ])->setPaper('f4', 'landscape');

        return $pdf->download('Laporan_Monitoring_Hutang_BBM_' . now()->format('YmdHis') . '.pdf');
    }

    public function update(Request $request, \App\Models\Hutang $hutang)
    {
        $request->validate([
            'satker_id' => 'required|exists:satkers,id',
            'nopol' => 'required|string',
            'nama_driver' => 'required|string',
            'jenis_bbm' => 'required|string',
            'jumlah_bon' => 'required|numeric|min:0.1',
            'tanggal_bon' => 'required|date',
        ]);

        $hutang->update($request->only(['satker_id', 'nopol', 'nama_driver', 'jenis_bbm', 'jumlah_bon', 'tanggal_bon']));

        \App\Models\LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Super Admin mengedit data hutang BBM (ID: {$hutang->id}) untuk Satker {$hutang->satker->nama_satker}"
        ]);

        return back()->with('success', 'Data hutang berhasil diperbarui.');
    }

    public function destroy(\App\Models\Hutang $hutang)
    {
        $id = $hutang->id;
        $satker = $hutang->satker->nama_satker;
        
        $hutang->delete();

        \App\Models\LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Super Admin menghapus data hutang BBM (ID: {$id}) untuk Satker {$satker}"
        ]);

        return back()->with('success', 'Data hutang berhasil dihapus.');
    }

    public function getKendaraan(Request $request)
    {
        $request->validate(['satker_id' => 'required|exists:satkers,id']);

        $kendaraans = \App\Models\Kendaraan::where('satker_id', $request->satker_id)
            ->select('id', 'no_polisi', 'jenis_kendaraan', 'jenis_bbm', 'saldo')
            ->get();

        return response()->json($kendaraans);
    }

    public function bayar(Request $request, \App\Models\Hutang $hutang)
    {
        $user = auth()->user();

        if ($hutang->status === 'sudah_dibayar') {
            return back()->with('error', 'Hutang ini sudah dibayar sebelumnya.');
        }

        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id'
        ]);

        $kendaraan = \App\Models\Kendaraan::where('id', $request->kendaraan_id)
            ->where('satker_id', $hutang->satker_id)
            ->firstOrFail();

        if ($kendaraan->saldo < $hutang->jumlah_bon) {
            return back()->with('error', 'Saldo kendaraan tidak mencukupi. Saldo: ' . $kendaraan->saldo . ' L');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($hutang, $kendaraan, $user) {
            $kendaraan->decrement('saldo', $hutang->jumlah_bon);

            $hutang->update([
                'status' => 'sudah_dibayar',
                'admin_bayar_id' => $user->id,
                'tanggal_bayar' => now()
            ]);

            // Create RiwayatTopup for report integration
            \App\Models\RiwayatTopup::create([
                'satker_id' => $hutang->satker_id,
                'kendaraan_id' => $kendaraan->id,
                'user_id' => $user->id,
                'jumlah' => $hutang->jumlah_bon,
                'tipe' => 'keluar',
                'metode' => 'Potong Saldo',
                'status' => 'success',
                'jenis_bbm' => $kendaraan->jenis_bbm,
                'keterangan' => "Pelunasan bon hutang oleh Super Admin (Nopol: {$hutang->nopol}, Jenis BBM: {$hutang->jenis_bbm})",
            ]);

            \App\Models\LogAktivitas::create([
                'user_id' => $user->id,
                'aktivitas' => "Super Admin membayar hutang BBM sebesar {$hutang->jumlah_bon} L untuk Satker {$hutang->satker->nama_satker} menggunakan saldo kendaraan {$kendaraan->no_polisi}"
            ]);
        });

        return back()->with('success', 'Hutang berhasil dibayar oleh Super Admin.');
    }
}
