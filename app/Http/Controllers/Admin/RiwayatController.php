<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransaksiBbm;
use App\Models\Satker;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Traits\PaginatesTables;

class RiwayatController extends Controller
{
    use PaginatesTables;

    public function index(Request $request)
    {
        $query = TransaksiBbm::with(['satker', 'kendaraan.satker', 'personel', 'petugas']);

        // Filter tanggal
        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }

        // Filter satker
        if ($request->filled('satker_id')) {
            $query->whereHas('kendaraan', function ($q) use ($request) {
                $q->where('satker_id', $request->satker_id);
            });
        }

        // Search nopol
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->whereHas('kendaraan', function ($subQ) use ($search) {
                $subQ->where('no_polisi', 'like', "%{$search}%");
            })->orWhereHas('personel', function ($subQ) use ($search) {
                $subQ->where('nama', 'like', "%{$search}%");
            });
        });
    }

        $perPage = $this->getPerPage($request);
        $transaksis = $query->latest()->paginate($perPage)->withQueryString();

        $satkers = Satker::orderBy('nama_satker')->get();

        // Statistik
    $statsQuery = TransaksiBbm::query();
    if ($request->filled('dari')) {
        $statsQuery->whereDate('transaksi_bbms.tanggal', '>=', $request->dari);
    }
    if ($request->filled('sampai')) {
        $statsQuery->whereDate('transaksi_bbms.tanggal', '<=', $request->sampai);
    }
    if ($request->filled('satker_id')) {
        $statsQuery->where('transaksi_bbms.satker_id', $request->satker_id);
    }

        $stats = [
            'total_transaksi' => (clone $statsQuery)->count(),
            'total_liter' => (clone $statsQuery)->sum('liter'),
        ];

        // Hitung total per jenis BBM 
    $summaryBbm = (clone $statsQuery)
        ->selectRaw("COALESCE(NULLIF(jenis_bbm, ''), 'TANPA JENIS') as bbm, SUM(liter) as total")
        ->groupBy('bbm')
        ->pluck('total', 'bbm');

    // Urutkan jenis bbm
    $summaryBbm = $summaryBbm->sortKeys();

        return view('admin.riwayat.index', compact('transaksis', 'satkers', 'stats', 'summaryBbm'));
    }

    public function print(Request $request)
    {
        $query = TransaksiBbm::with(['satker', 'kendaraan.satker', 'personel', 'petugas']);

        // Filter tanggal
        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }

        // Filter satker
        if ($request->filled('satker_id')) {
            $query->whereHas('kendaraan', function ($q) use ($request) {
                $q->where('satker_id', $request->satker_id);
            });
        }

        // Search nopol (optional for print, but good consistency)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('kendaraan', function ($q) use ($search) {
                $q->where('no_polisi', 'like', "%{$search}%");
            });
        }

        // Hitung Summary per Jenis BBM
        $summaryBbm = (clone $query)
            ->selectRaw("COALESCE(NULLIF(jenis_bbm, ''), 'TANPA JENIS') as bbm, SUM(liter) as total")
            ->groupBy('bbm')
            ->pluck('total', 'bbm')
            ->sortKeys();

        $transaksis = $query->latest('tanggal')->get();

        $pdf = Pdf::loadView('admin.riwayat.print', compact('transaksis', 'summaryBbm'))
            ->setPaper([0, 0, 609.45, 935.43], 'landscape'); // F4 (215mm x 330mm)

        return $pdf->stream('laporan-riwayat-bbm-' . date('Y-m-d_H-i') . '.pdf');
    }

    public function destroy(Request $request, TransaksiBbm $transaksi)
    {
        // Validasi Top Up Password Super Admin
        $user = auth()->user();
        if (!\Illuminate\Support\Facades\Hash::check($request->topup_password_confirm, $user->topup_password)) {
            return back()->with('error', 'Gagal membatalkan transaksi! Password Top Up salah.');
        }

        $targetStr = "Tidak diketahui";

        if ($transaksi->kendaraan_id) {
            $kendaraan = $transaksi->kendaraan;
            if ($kendaraan) {
                $kendaraan->saldo += $transaksi->liter;
                $kendaraan->save();
                $targetStr = "Kendaraan ({$kendaraan->no_polisi})";
            }
        } elseif ($transaksi->personel_id) {
            $personel = $transaksi->personel;
            if ($personel) {
                $personel->saldo += $transaksi->liter;
                $personel->save();
                $targetStr = "Personel ({$personel->nama})";
            }
        }

        \App\Models\LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Membatalkan Transaksi BBM sebesar " . number_format($transaksi->liter, 0, ',', '.') . " L untuk {$targetStr}"
        ]);

        $transaksi->delete();

        return back()->with('success', 'Transaksi berhasil dibatalkan dan saldo dikembalikan sebesar ' . number_format($transaksi->liter, 0, ',', '.') . ' L.');
    }
}
