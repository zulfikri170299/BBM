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

        return view('petugas.hutang.index', compact('satkers', 'jenisBbm', 'hutangs', 'summaryHutang'));
    }

    public function getKendaraan(Request $request)
    {
        $satkerId = $request->satker_id;
        $kendaraans = \App\Models\Kendaraan::where('satker_id', $satkerId)
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

        \App\Models\Hutang::create([
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

        return back()->with('success', 'Data bon hutang berhasil disimpan.');
    }
}
