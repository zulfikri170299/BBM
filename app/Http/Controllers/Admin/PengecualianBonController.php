<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Satker;
use App\Models\Hutang;
use App\Models\LogAktivitas;
use App\Traits\PaginatesTables;
use Illuminate\Http\Request;

class PengecualianBonController extends Controller
{
    use PaginatesTables;

    public function index(Request $request)
    {
        $query = Kendaraan::with(['satker', 'bebasBonBy'])
            ->where('bebas_bon', true);

        // Filter Satker
        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        // Search Nopol, Jenis Kendaraan, Alasan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_polisi', 'like', "%{$search}%")
                  ->orWhere('jenis_kendaraan', 'like', "%{$search}%")
                  ->orWhere('alasan_bebas_bon', 'like', "%{$search}%");
            });
        }

        $perPage = $this->getPerPage($request);
        $kendaraans = $query->latest('bebas_bon_updated_at')->paginate($perPage)->withQueryString();

        // Batch pre-load unpaid bon for these vehicles
        $allBebasNopols = $kendaraans->pluck('no_polisi')->toArray();
        $unpaidHutangList = Hutang::where('status', 'belum_dibayar')
            ->whereIn('nopol', $allBebasNopols)
            ->get(['nopol', 'jumlah_bon', 'satker_id']);

        $kendaraans->getCollection()->each(function ($k) use ($unpaidHutangList) {
            $cleanNopol = preg_replace('/[^A-Za-z0-9]/', '', strtoupper($k->no_polisi));
            $matched = $unpaidHutangList->filter(function ($h) use ($k, $cleanNopol) {
                if ($h->satker_id != $k->satker_id) return false;
                $cleanH = preg_replace('/[^A-Za-z0-9]/', '', strtoupper($h->nopol));
                return $cleanH === $cleanNopol || trim(strtoupper($h->nopol)) === trim(strtoupper($k->no_polisi));
            });
            $k->setAttribute('unpaid_bon_count', $matched->count());
            $k->setAttribute('unpaid_bon_liters', (int) $matched->sum('jumlah_bon'));
        });

        // Summary KPI Stats
        $totalBebasBon = Kendaraan::where('bebas_bon', true)->count();
        $totalSatkerBebas = Kendaraan::where('bebas_bon', true)->distinct('satker_id')->count('satker_id');
        $totalKendaraanAll = Kendaraan::count();
        
        // Total Bon yang aktif pada kendaraan bebas bon
        $bebasNopolsAll = Kendaraan::where('bebas_bon', true)->pluck('no_polisi')->toArray();
        $totalBonLitersBebas = Hutang::where('status', 'belum_dibayar')
            ->whereIn('nopol', $bebasNopolsAll)
            ->sum('jumlah_bon');

        $satkers = Satker::orderBy('nama_satker')->get();

        return view('admin.hutang.pengecualian', compact(
            'kendaraans',
            'satkers',
            'totalBebasBon',
            'totalSatkerBebas',
            'totalKendaraanAll',
            'totalBonLitersBebas'
        ));
    }

    public function getAvailableKendaraan(Request $request)
    {
        $request->validate([
            'satker_id' => 'required|exists:satkers,id'
        ]);

        $kendaraans = Kendaraan::where('satker_id', $request->satker_id)
            ->where(function($q) {
                $q->where('bebas_bon', false)->orWhereNull('bebas_bon');
            })
            ->select('id', 'no_polisi', 'jenis_kendaraan', 'jenis_bbm', 'saldo')
            ->orderBy('no_polisi')
            ->get();

        return response()->json($kendaraans);
    }

    public function store(Request $request)
    {
        $request->validate([
            'satker_id' => 'required|exists:satkers,id',
            'kendaraan_ids' => 'required|array|min:1',
            'kendaraan_ids.*' => 'exists:kendaraans,id',
            'alasan_bebas_bon' => 'nullable|string|max:500',
        ], [
            'kendaraan_ids.required' => 'Pilih minimal satu kendaraan untuk diberikan pengecualian.',
            'kendaraan_ids.min' => 'Pilih minimal satu kendaraan.',
        ]);

        $kendaraans = Kendaraan::whereIn('id', $request->kendaraan_ids)->get();
        $nopols = [];

        foreach ($kendaraans as $k) {
            $k->update([
                'bebas_bon' => true,
                'alasan_bebas_bon' => $request->alasan_bebas_bon ?: 'Pengecualian Bon BBM Tanpa Batas Limit (Super Admin)',
                'bebas_bon_updated_at' => now(),
                'bebas_bon_by' => auth()->id(),
            ]);
            $nopols[] = $k->no_polisi;
        }

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Super Admin menambahkan ' . count($nopols) . ' kendaraan ke daftar Pengecualian Bebas Bon: ' . implode(', ', $nopols)
        ]);

        return redirect()->route('admin.hutang.pengecualian.index')
            ->with('success', count($nopols) . ' kendaraan berhasil ditambahkan ke daftar Pengecualian Bebas Bon.');
    }

    public function update(Request $request, Kendaraan $kendaraan)
    {
        $request->validate([
            'alasan_bebas_bon' => 'nullable|string|max:500',
        ]);

        $kendaraan->update([
            'alasan_bebas_bon' => $request->alasan_bebas_bon,
            'bebas_bon_updated_at' => now(),
            'bebas_bon_by' => auth()->id(),
        ]);

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Super Admin memperbarui alasan Pengecualian Bebas Bon kendaraan: {$kendaraan->no_polisi}"
        ]);

        return back()->with('success', "Keterangan pengecualian untuk {$kendaraan->no_polisi} berhasil diperbarui.");
    }

    public function destroy(Kendaraan $kendaraan)
    {
        $nopol = $kendaraan->no_polisi;

        $kendaraan->update([
            'bebas_bon' => false,
            'alasan_bebas_bon' => null,
            'bebas_bon_updated_at' => now(),
            'bebas_bon_by' => auth()->id(),
        ]);

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Super Admin mencabut status Bebas Bon dari kendaraan: {$nopol}"
        ]);

        return back()->with('success', "Status Bebas Bon untuk kendaraan {$nopol} berhasil dicabut (kembali ke batas normal).");
    }
}

