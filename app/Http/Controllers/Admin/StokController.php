<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminBbmStock;
use App\Models\RiwayatStokAdmin;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class StokController extends Controller
{
    use \App\Traits\PaginatesTables;

    public function index(Request $request)
    {
        $stocks = AdminBbmStock::all();
        $perPage = $this->getPerPage($request, 20);
        
        $query = RiwayatStokAdmin::with('user')->latest();

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Hitung ringkasan mutasi (filtered)
        $summaryQuery = RiwayatStokAdmin::query();
        if ($request->filled('start_date')) {
            $summaryQuery->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $summaryQuery->whereDate('created_at', '<=', $request->end_date);
        }

        $summaryData = $summaryQuery->select('jenis_bbm', 'tipe', DB::raw('SUM(jumlah) as total'))
            ->groupBy('jenis_bbm', 'tipe')
            ->get();

        $summary = [];
        foreach ($summaryData as $row) {
            $summary[$row->jenis_bbm][$row->tipe] = $row->total;
        }

        $history = $query->paginate($perPage)->withQueryString();
        
        return view('admin.stok.index', compact('stocks', 'history', 'summary'));
    }

    public function print(Request $request)
    {
        $stocks = AdminBbmStock::all();
        
        $query = RiwayatStokAdmin::with('user')->latest();

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $history = $query->get();

        // Hitung ringkasan mutasi (filtered)
        $summaryQuery = RiwayatStokAdmin::query();
        if ($request->filled('start_date')) {
            $summaryQuery->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $summaryQuery->whereDate('created_at', '<=', $request->end_date);
        }

        $summaryData = $summaryQuery->select('jenis_bbm', 'tipe', DB::raw('SUM(jumlah) as total'))
            ->groupBy('jenis_bbm', 'tipe')
            ->get();

        $summary = [];
        foreach ($summaryData as $row) {
            $summary[$row->jenis_bbm][$row->tipe] = $row->total;
        }
        
        $pdf = Pdf::loadView('admin.stok.print', compact('stocks', 'history', 'summary'))
            ->setPaper('a4', 'portrait');
            
        return $pdf->stream('riwayat-stok-pusat-' . date('Y-m-d') . '.pdf');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_bbm' => 'required|in:Pertamax,Pertamina Dex',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
            'topup_password' => 'required|string',
        ]);

        $user = auth()->user();

        // Cek apakah user sudah mengatur password topup
        if (!$user->topup_password) {
            return back()->with('error', 'Anda belum mengatur Password Top-up. Silakan atur di menu Profil terlebih dahulu.');
        }

        // Verifikasi password topup
        if (!\Illuminate\Support\Facades\Hash::check($request->topup_password, $user->topup_password)) {
            return back()->with('error', 'Password Top Up salah.');
        }

        try {
            DB::beginTransaction();

            $stock = AdminBbmStock::firstOrCreate(
                ['jenis_bbm' => $request->jenis_bbm],
                ['saldo' => 0]
            );
            $stock->increment('saldo', $request->jumlah);

            RiwayatStokAdmin::create([
                'user_id' => $user->id,
                'jenis_bbm' => $request->jenis_bbm,
                'jumlah' => $request->jumlah,
                'tipe' => 'masuk',
                'keterangan' => $request->keterangan ?: 'Penambahan stok manual',
            ]);

            LogAktivitas::create([
                'user_id' => $user->id,
                'aktivitas' => "Menambahkan stok Pusat: {$request->jumlah} L {$request->jenis_bbm}"
            ]);

            DB::commit();
            return back()->with('success', "Stok {$request->jenis_bbm} berhasil ditambahkan sebesar {$request->jumlah} Liter.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan stok: ' . $e->getMessage());
        }
    }
}
