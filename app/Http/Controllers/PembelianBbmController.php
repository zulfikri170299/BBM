<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PembelianBbm;
use Barryvdh\DomPDF\Facade\Pdf;

class PembelianBbmController extends Controller
{
    public function index(Request $request)
    {
        $query = PembelianBbm::query();

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        if ($request->filled('jenis_bbm')) {
            $query->where('jenis_bbm', $request->jenis_bbm);
        }

        $perPage = $request->input('per_page', 15);
        $pembelians = $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        return view('pembelian_bbm.index', compact('pembelians'));
    }

    public function print(Request $request)
    {
        $query = PembelianBbm::query();

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        if ($request->filled('jenis_bbm')) {
            $query->where('jenis_bbm', $request->jenis_bbm);
        }

        $pembelians = $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('pembelian_bbm.print', compact('pembelians'))->setPaper('a4', 'portrait');
        return $pdf->stream('laporan-pembelian-bbm-' . date('Y-m-d') . '.pdf');
    }

    public function create()
    {
        return view('pembelian_bbm.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_bbm' => 'required|in:Pertamax,Pertamina Dex',
            'jumlah' => 'required|numeric|min:1',
        ]);

        PembelianBbm::create($request->all());

        return redirect()->route('pembelian-bbm.index')->with('success', 'Data pembelian BBM berhasil disimpan.');
    }

    public function edit(PembelianBbm $pembelianBbm)
    {
        return view('pembelian_bbm.edit', compact('pembelianBbm'));
    }

    public function update(Request $request, PembelianBbm $pembelianBbm)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_bbm' => 'required|in:Pertamax,Pertamina Dex',
            'jumlah' => 'required|numeric|min:1',
        ]);

        $pembelianBbm->update($request->all());

        return redirect()->route('pembelian-bbm.index')->with('success', 'Data pembelian BBM berhasil diubah.');
    }

    public function destroy(PembelianBbm $pembelianBbm)
    {
        $pembelianBbm->delete();

        return redirect()->route('pembelian-bbm.index')->with('success', 'Data pembelian BBM berhasil dihapus.');
    }
}
