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
        // 1. Ambil TransaksiBbm
        $queryTrx = TransaksiBbm::with(['satker', 'kendaraan.satker', 'personel', 'petugas']);
        if ($request->filled('dari')) $queryTrx->whereDate('tanggal', '>=', $request->dari);
        if ($request->filled('sampai')) $queryTrx->whereDate('tanggal', '<=', $request->sampai);
        if ($request->filled('satker_id')) $queryTrx->where('satker_id', $request->satker_id);
        if ($request->filled('search')) {
            $search = $request->search;
            $queryTrx->where(function ($q) use ($search) {
                $q->whereHas('kendaraan', function ($sub) use ($search) { $sub->where('no_polisi', 'like', "%$search%"); })
                  ->orWhereHas('personel', function ($sub) use ($search) { $sub->where('nama', 'like', "%$search%"); });
            });
        }
        $trxs = $queryTrx->get()->map(function($item) {
            $item->row_type = 'pengisian';
            $item->sort_date = $item->tanggal;
            return $item;
        });

        // 2. Ambil RiwayatTopup (Hanya yang keluar / potong saldo)
        $queryTopup = \App\Models\RiwayatTopup::with(['satker', 'kendaraan.satker', 'user'])
            ->where('tipe', 'keluar');
        if ($request->filled('dari')) $queryTopup->whereDate('created_at', '>=', $request->dari);
        if ($request->filled('sampai')) $queryTopup->whereDate('created_at', '<=', $request->sampai);
        if ($request->filled('satker_id')) $queryTopup->where('satker_id', $request->satker_id);
        if ($request->filled('search')) {
            $search = $request->search;
            $queryTopup->whereHas('kendaraan', function ($sub) use ($search) {
                $sub->where('no_polisi', 'like', "%$search%");
            });
        }
        $topups = $queryTopup->get()->map(function($item) {
            $item->row_type = 'potong_saldo';
            $item->sort_date = $item->created_at;
            // Map fields to match TransaksiBbm for view consistency
            $item->tanggal = $item->created_at;
            $item->liter = $item->jumlah;
            $item->nama_driver = "POTONG SALDO";
            return $item;
        });

        // 3. Gabungkan dan Urutkan
        $merged = $trxs->concat($topups)->sortByDesc('sort_date')->values();

        // 4. Paginate Manual
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = $this->getPerPage($request);
        $currentItems = $merged->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $transaksis = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, $merged->count(), $perPage, $currentPage, [
            'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
            'query' => $request->query(),
        ]);

        $satkers = Satker::orderBy('nama_satker')->get();

        // Statistik (Mengecualikan Biro Logistik/ID 1 karena dianggap data pengujian/potong saldo)
        $trxs_murni = $trxs->where('satker_id', '!=', 1);
        $totalLiterMurni = $trxs_murni->sum('liter');
        
        $stats = [
            'total_transaksi' => $merged->count(),
            'total_liter' => $totalLiterMurni,
        ];

        // Summary per Jenis BBM (Hanya dari pengisian murni di luar Biro Logistik)
        $summaryBbm = $trxs_murni->groupBy('jenis_bbm')->map(function ($group) {
            return $group->sum('liter');
        })->sortKeys();

        return view('admin.riwayat.index', compact('transaksis', 'satkers', 'stats', 'summaryBbm'));
    }

    public function print(Request $request)
    {
        // Ambil TransaksiBbm
        $queryTrx = TransaksiBbm::with(['satker', 'kendaraan.satker', 'personel', 'petugas']);
        if ($request->filled('dari')) $queryTrx->whereDate('tanggal', '>=', $request->dari);
        if ($request->filled('sampai')) $queryTrx->whereDate('tanggal', '<=', $request->sampai);
        if ($request->filled('satker_id')) $queryTrx->where('satker_id', $request->satker_id);
        if ($request->filled('search')) {
            $search = $request->search;
            $queryTrx->where(function ($q) use ($search) {
                $q->whereHas('kendaraan', function ($sub) use ($search) { $sub->where('no_polisi', 'like', "%$search%"); })
                  ->orWhereHas('personel', function ($sub) use ($search) { $sub->where('nama', 'like', "%$search%"); });
            });
        }
        $trxs = $queryTrx->get()->map(function($item) {
            $item->row_type = 'pengisian';
            $item->sort_date = $item->tanggal;
            return $item;
        });

        // Ambil RiwayatTopup (keluar)
        $queryTopup = \App\Models\RiwayatTopup::with(['satker', 'kendaraan.satker', 'user'])->where('tipe', 'keluar');
        if ($request->filled('dari')) $queryTopup->whereDate('created_at', '>=', $request->dari);
        if ($request->filled('sampai')) $queryTopup->whereDate('created_at', '<=', $request->sampai);
        if ($request->filled('satker_id')) $queryTopup->where('satker_id', $request->satker_id);
        if ($request->filled('search')) {
            $search = $request->search;
            $queryTopup->whereHas('kendaraan', function ($sub) use ($search) { $sub->where('no_polisi', 'like', "%$search%"); });
        }
        $topups = $queryTopup->get()->map(function($item) {
            $item->row_type = 'potong_saldo';
            $item->sort_date = $item->created_at;
            $item->tanggal = $item->created_at;
            $item->liter = $item->jumlah;
            $item->nama_driver = "POTONG SALDO";
            return $item;
        });

        $transaksis = $trxs->concat($topups)->sortByDesc('sort_date')->values();

        $summaryBbm = $trxs->where('satker_id', '!=', 1)->groupBy('jenis_bbm')->map(function ($group) {
            return $group->sum('liter');
        })->sortKeys();

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
