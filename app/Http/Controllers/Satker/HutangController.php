<?php

namespace App\Http\Controllers\Satker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HutangController extends Controller
{
    public function index(Request $request)
    {
        $satkerId = auth()->user()->satker_id;

        $query = \App\Models\Hutang::with(['petugas', 'adminBayar'])
            ->where('satker_id', $satkerId)
            ->orderBy('created_at', 'desc');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('jenis_bbm')) {
            $query->where('jenis_bbm', $request->jenis_bbm);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->input('per_page', 15);
        $hutangs = $query->paginate($perPage)->withQueryString();

        // Fetch kendaraans for the payment modal
        $kendaraans = \App\Models\Kendaraan::where('satker_id', $satkerId)->get();

        // Calculate unpaid hutang per BBM type
        $hutangPerBbm = \App\Models\Hutang::where('satker_id', $satkerId)
            ->where('status', 'belum_dibayar')
            ->selectRaw('jenis_bbm, sum(jumlah_bon) as total')
            ->groupBy('jenis_bbm')
            ->pluck('total', 'jenis_bbm');

        return view('satker.hutang.index', compact('hutangs', 'kendaraans', 'hutangPerBbm'));
    }

    public function downloadPDF(Request $request)
    {
        $satkerId = auth()->user()->satker_id;
        $satker = auth()->user()->satker;

        $query = \App\Models\Hutang::with(['petugas', 'adminBayar'])
            ->where('satker_id', $satkerId)
            ->orderBy('created_at', 'desc');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('jenis_bbm')) {
            $query->where('jenis_bbm', $request->jenis_bbm);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $hutangs = $query->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan_hutang.print', [
            'hutangs' => $hutangs,
            'satker' => $satker,
            'filter_status' => $request->status
        ])->setPaper('f4', 'landscape');

        return $pdf->download('Laporan_Hutang_BBM_' . $satker->nama_satker . '_' . now()->format('YmdHis') . '.pdf');
    }

    public function bayar(Request $request, \App\Models\Hutang $hutang)
    {
        $user = auth()->user();

        // Security check
        if ($hutang->satker_id !== $user->satker_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($hutang->status === 'sudah_dibayar') {
            return back()->with('error', 'Hutang ini sudah dibayar sebelumnya.');
        }

        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id'
        ]);

        $kendaraan = \App\Models\Kendaraan::where('id', $request->kendaraan_id)
            ->where('satker_id', $user->satker_id)
            ->firstOrFail();

        if ($kendaraan->saldo < $hutang->jumlah_bon) {
            return back()->with('error', 'Saldo kendaraan tidak mencukupi untuk membayar hutang ini. Saldo: ' . $kendaraan->saldo . ' L');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($hutang, $kendaraan, $user) {
            // Deduct balance
            $kendaraan->decrement('saldo', $hutang->jumlah_bon);

            // Mark as paid
            $hutang->update([
                'status' => 'sudah_dibayar',
                'admin_bayar_id' => $user->id,
                'tanggal_bayar' => now()
            ]);

            // Create RiwayatTopup for report integration (type: keluar/transfer)
            \App\Models\RiwayatTopup::create([
                'satker_id' => $hutang->satker_id,
                'kendaraan_id' => $kendaraan->id,
                'user_id' => $user->id,
                'jumlah' => $hutang->jumlah_bon,
                'tipe' => 'keluar',
                'metode' => 'Potong Saldo',
                'status' => 'success',
                'jenis_bbm' => $kendaraan->jenis_bbm,
                'keterangan' => "Pelunasan bon hutang (Nopol: {$hutang->nopol}, Jenis BBM: {$hutang->jenis_bbm})",
            ]);

            // Log activity
            \App\Models\LogAktivitas::create([
                'user_id' => $user->id,
                'aktivitas' => "Membayar hutang BBM sebesar {$hutang->jumlah_bon} L menggunakan saldo kendaraan {$kendaraan->no_polisi}"
            ]);
        });

        return back()->with('success', 'Hutang berhasil dibayar dan saldo kendaraan telah dipotong.');
    }
}
