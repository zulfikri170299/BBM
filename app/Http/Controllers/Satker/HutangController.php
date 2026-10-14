<?php

namespace App\Http\Controllers\Satker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HutangController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $satkerId = $user->satker_id;

        // Jalankan pelunasan otomatis untuk BON satker yang saldonya mencukupi
        \App\Models\Kendaraan::autoSettleAllEligible($user->role !== 'super_admin' ? $satkerId : null);

        $query = \App\Models\Hutang::with(['petugas', 'adminBayar'])
            ->orderBy('created_at', 'desc');

        if ($user->role !== 'super_admin') {
            $query->where('satker_id', $satkerId);
        }

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
        $queryKendaraan = \App\Models\Kendaraan::query();
        if ($user->role !== 'super_admin') {
            $queryKendaraan->where('satker_id', $satkerId);
        }
        $kendaraans = $queryKendaraan->get();

        // Calculate unpaid hutang per BBM type
        $queryBelumDibayar = \App\Models\Hutang::where('status', 'belum_dibayar');
        $querySudahDibayar = \App\Models\Hutang::where('status', 'sudah_dibayar');
        
        if ($user->role !== 'super_admin') {
            $queryBelumDibayar->where('satker_id', $satkerId);
            $querySudahDibayar->where('satker_id', $satkerId);
        }
        
        $belumDibayar = $queryBelumDibayar
            ->selectRaw('jenis_bbm, sum(jumlah_bon) as total')
            ->groupBy('jenis_bbm')
            ->pluck('total', 'jenis_bbm');
            
        $sudahDibayar = $querySudahDibayar
            ->selectRaw('jenis_bbm, sum(jumlah_bon) as total')
            ->groupBy('jenis_bbm')
            ->pluck('total', 'jenis_bbm');
            
        // For backwards compatibility or loop structure if needed
        $hutangPerBbm = $belumDibayar;

        return view('satker.hutang.index', compact('hutangs', 'kendaraans', 'hutangPerBbm', 'belumDibayar', 'sudahDibayar'));
    }

    public function downloadPDF(Request $request)
    {
        $user = auth()->user();
        $satkerId = $user->satker_id;
        $satker = $user->satker;

        // Jalankan pelunasan otomatis untuk BON satker yang saldonya mencukupi
        \App\Models\Kendaraan::autoSettleAllEligible($user->role !== 'super_admin' ? $satkerId : null);

        $query = \App\Models\Hutang::with(['petugas', 'adminBayar'])
            ->orderBy('created_at', 'desc');

        if ($user->role !== 'super_admin') {
            $query->where('satker_id', $satkerId);
        }

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
        if ($user->role !== 'super_admin' && $hutang->satker_id !== $user->satker_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($hutang->status === 'sudah_dibayar') {
            return back()->with('error', 'Hutang ini sudah dibayar sebelumnya.');
        }

        $satkerTargetId = ($user->role !== 'super_admin') ? $user->satker_id : $hutang->satker_id;
        $kendaraan = \App\Models\Kendaraan::where('satker_id', $satkerTargetId)
            ->where(function($q) use ($hutang) {
                $q->where('no_polisi', $hutang->nopol)
                  ->orWhereRaw("TRIM(UPPER(no_polisi)) = ?", [trim(strtoupper($hutang->nopol))]);
            })
            ->first();

        if (!$kendaraan) {
            $cleanNopol = preg_replace('/[^A-Za-z0-9]/', '', strtoupper($hutang->nopol));
            $kendaraan = \App\Models\Kendaraan::where('satker_id', $satkerTargetId)
                ->whereRaw("REPLACE(REPLACE(REPLACE(UPPER(no_polisi), ' ', ''), '-', ''), '.', '') = ?", [$cleanNopol])
                ->firstOrFail();
        }

        if ($kendaraan->saldo < $hutang->jumlah_bon) {
            return back()->with('error', 'Saldo kendaraan tidak mencukupi untuk membayar hutang ini. Saldo: ' . $kendaraan->saldo . ' L, Jumlah BON: ' . $hutang->jumlah_bon . ' L');
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

        $isFullyPaid = !$kendaraan->fresh()->hasUnpaidBon();
        $msg = $isFullyPaid 
            ? "Hutang berhasil dibayar. Semua BON kendaraan {$kendaraan->no_polisi} telah lunas dan saldo kini dapat digunakan kembali."
            : "Hutang berhasil dibayar. Kendaraan {$kendaraan->no_polisi} masih memiliki sisa BON lain sebesar {$kendaraan->totalUnpaidBon()} L (saldo tetap dikunci hingga lunas).";

        return back()->with('success', $msg);
    }
}
