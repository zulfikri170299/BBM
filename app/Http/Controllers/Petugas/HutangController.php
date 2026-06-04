<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HutangController extends Controller
{
    public function index(Request $request)
    {
        $satkers = \App\Models\Satker::orderBy('nama_satker')->get();
        $jenisBbm = \App\Models\AdminBbmStock::pluck('jenis_bbm');
        
        $query = \App\Models\Hutang::with(['satker', 'petugas'])
            ->orderBy('created_at', 'desc');

        // Filter Satker
        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->input('per_page', 15);
        $hutangs = $query->paginate($perPage)->withQueryString();

        // Hitung Summary Hutang sesuai Filter (Respect Satker & Status filter)
        $summaryQuery = \App\Models\Hutang::query();
        
        if ($request->filled('satker_id')) {
            $summaryQuery->where('satker_id', $request->satker_id);
        }
        
        if ($request->filled('status')) {
            $summaryQuery->where('status', $request->status);
        } else {
            // Default summary usually for unpaid debt if no status filter, 
            // but user said "sesuai dengan data yang di filter"
            // So if no status filter, we show ALL (Paid + Unpaid) in summary? 
            // Better to show only unpaid by default unless filtered?
            // "tampilkan total hutang sesuai dengan data yang di filter"
            // I'll make it reflect EXACTLY what the filter says.
        }

        $summaryHutang = $summaryQuery->selectRaw('jenis_bbm, SUM(jumlah_bon) as total')
            ->groupBy('jenis_bbm')
            ->pluck('total', 'jenis_bbm');

        // Hitung Real Physical Tank Stock
        $latestSync = \App\Models\SinkronisasiBbm::orderBy('created_at', 'desc')->first();
        $tankStock = ['Pertamax' => 0, 'Pertamina Dex' => 0];
        
        if ($latestSync) {
            $pemakaianPertamax = \App\Models\TransaksiBbm::where(function($q) { $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })
                ->where('created_at', '>=', $latestSync->created_at)->sum('liter');
            $pemakaianPertamax += \App\Models\Hutang::where(function($q) { $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })
                ->where('created_at', '>=', $latestSync->created_at)->sum('jumlah_bon');
                
            $pemakaianDex = \App\Models\TransaksiBbm::where(function($q) { $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })
                ->where('created_at', '>=', $latestSync->created_at)->sum('liter');
            $pemakaianDex += \App\Models\Hutang::where(function($q) { $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })
                ->where('created_at', '>=', $latestSync->created_at)->sum('jumlah_bon');

            $tankStock['Pertamax'] = max(0, $latestSync->stok_awal_pertamax - $pemakaianPertamax);
            $tankStock['Pertamina Dex'] = max(0, $latestSync->stok_awal_dex - $pemakaianDex);
        }

        // Ambil stok tangki BBM (key: jenis_bbm => saldo)
        $stokTangki = collect([
            'Pertamax' => $tankStock['Pertamax'],
            'Pertamina Dex' => $tankStock['Pertamina Dex'],
        ]);

        return view('petugas.hutang.index', compact('satkers', 'jenisBbm', 'hutangs', 'summaryHutang', 'stokTangki'));
    }

    public function getKendaraan(Request $request)
    {
        $satkerId = $request->satker_id;
        $kendaraans = \App\Models\Kendaraan::where('satker_id', $satkerId)
            ->where('saldo', '<=', 0)
            ->select('id', 'no_polisi', 'jenis_kendaraan', 'jenis_bbm')
            ->get();
            
        return response()->json($kendaraans);
    }

    public function store(Request $request)
    {
        $request->validate([
            'satker_id' => 'required|exists:satkers,id',
            'jenis_kendaraan' => 'required|string',
            'nopol' => 'required|string',
            'nama_driver' => 'required|string',
            'jenis_bbm' => 'required|string',
            'jumlah_bon' => 'required|numeric|min:0.1',
            'tanggal_bon' => 'required|date',
        ]);

        // Validasi jika kendaraan masih memiliki saldo
        $kendaraan = \App\Models\Kendaraan::where('satker_id', $request->satker_id)
            ->where('no_polisi', $request->nopol)
            ->first();
            
        if ($kendaraan && $kendaraan->saldo > 0) {
            return back()
                ->withInput()
                ->withErrors(['nopol' => "Kendaraan dengan No. Polisi {$request->nopol} masih memiliki saldo BBM ({$kendaraan->saldo} L), tidak diizinkan untuk hutang."]);
        }

        // Validasi stok tangki BBM (Real physical tank)
        $latestSync = \App\Models\SinkronisasiBbm::orderBy('created_at', 'desc')->first();
        $saldoTangki = 0;
        if ($latestSync) {
            $isPertamax = stripos($request->jenis_bbm, 'Pertamax') !== false;
            $pemakaian = \App\Models\TransaksiBbm::where(function($q) use ($request) { $q->where('jenis_bbm', $request->jenis_bbm)->orWhere('jenis_bbm', strtoupper($request->jenis_bbm)); })
                ->where('created_at', '>=', $latestSync->created_at)->sum('liter');
            $pemakaian += \App\Models\Hutang::where(function($q) use ($request) { $q->where('jenis_bbm', $request->jenis_bbm)->orWhere('jenis_bbm', strtoupper($request->jenis_bbm)); })
                ->where('created_at', '>=', $latestSync->created_at)->sum('jumlah_bon');
            $saldoTangki = $isPertamax ? ($latestSync->stok_awal_pertamax - $pemakaian) : ($latestSync->stok_awal_dex - $pemakaian);
        }

        if ($request->jumlah_bon > $saldoTangki) {
            return back()
                ->withInput()
                ->withErrors(['jumlah_bon' => "Jumlah bon ({$request->jumlah_bon} L) melebihi stok {$request->jenis_bbm} di tangki ({$saldoTangki} L). Tidak dapat menyimpan data."]);
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            // Note: Tidak perlu memotong stock apa pun karena physical tank terpotong secara otomatis by sum query

            $hutang = \App\Models\Hutang::create([
                'satker_id' => $request->satker_id,
                'petugas_id' => auth()->id(),
                'nama_driver' => $request->nama_driver,
                'jenis_kendaraan' => $request->jenis_kendaraan,
                'nopol' => $request->nopol,
                'jenis_bbm' => $request->jenis_bbm,
                'jumlah_bon' => $request->jumlah_bon,
                'tanggal_bon' => $request->tanggal_bon,
                'status' => 'belum_dibayar',
            ]);

            \App\Models\LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => "Menginput bon hutang BBM: {$request->jumlah_bon} L untuk Kendaraan ({$request->nopol}) - {$request->jenis_kendaraan}"
            ]);
        });

        return back()->with('success', 'Data bon hutang berhasil disimpan.');
    }
}
