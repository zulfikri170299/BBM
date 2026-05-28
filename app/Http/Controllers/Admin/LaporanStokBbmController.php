<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SinkronisasiBbm;
use App\Models\TransaksiBbm;
use Illuminate\Http\Request;
use App\Models\LogAktivitas;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Traits\PaginatesTables;

class LaporanStokBbmController extends Controller
{
    use PaginatesTables;

    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);
        
        $query = SinkronisasiBbm::with('petugas')->orderBy('created_at', 'desc');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $allSyncs = $query->get();
        // Calculate pemakaian and sisa
        foreach ($allSyncs as $index => $sync) {
            $nextSyncTime = isset($allSyncs[$index - 1]) ? $allSyncs[$index - 1]->created_at : now();

            $sync->pemakaian_pertamax = TransaksiBbm::where(function($q) {
                    $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX');
                })
                ->where('created_at', '>=', $sync->created_at)
                ->where('created_at', '<', $nextSyncTime)
                ->sum('liter');

            // Tambahkan Hutang Pertamax
            $sync->pemakaian_pertamax += \App\Models\Hutang::where(function($q) {
                    $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX');
                })
                ->where('created_at', '>=', $sync->created_at)
                ->where('created_at', '<', $nextSyncTime)
                ->sum('jumlah_bon');

            $sync->pemakaian_dex = TransaksiBbm::where(function($q) {
                    $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX');
                })
                ->where('created_at', '>=', $sync->created_at)
                ->where('created_at', '<', $nextSyncTime)
                ->sum('liter');

            // Tambahkan Hutang Dex
            $sync->pemakaian_dex += \App\Models\Hutang::where(function($q) {
                    $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX');
                })
                ->where('created_at', '>=', $sync->created_at)
                ->where('created_at', '<', $nextSyncTime)
                ->sum('jumlah_bon');

            $sync->sisa_pertamax = $sync->stok_awal_pertamax - $sync->pemakaian_pertamax;
            $sync->sisa_dex = $sync->stok_awal_dex - $sync->pemakaian_dex;
        }

        // Paginate manually since we need the entire dataset to calculate nextSyncTime accurately
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $currentPageItems = $allSyncs->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $syncs = new \Illuminate\Pagination\LengthAwarePaginator($currentPageItems, count($allSyncs), $perPage, $currentPage, [
            'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
            'query' => $request->query()
        ]);

        return view('admin.laporan-stok-bbm.index', compact('syncs'));
    }

    public function print(Request $request)
    {
        $query = SinkronisasiBbm::with('petugas')->orderBy('created_at', 'desc');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $allSyncs = $query->get();
        // Calculate pemakaian and sisa
        foreach ($allSyncs as $index => $sync) {
            $nextSyncTime = isset($allSyncs[$index - 1]) ? $allSyncs[$index - 1]->created_at : now();

            $sync->pemakaian_pertamax = TransaksiBbm::where(function($q) {
                    $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX');
                })
                ->where('created_at', '>=', $sync->created_at)
                ->where('created_at', '<', $nextSyncTime)
                ->sum('liter');

            // Tambahkan Hutang Pertamax
            $sync->pemakaian_pertamax += \App\Models\Hutang::where(function($q) {
                    $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX');
                })
                ->where('created_at', '>=', $sync->created_at)
                ->where('created_at', '<', $nextSyncTime)
                ->sum('jumlah_bon');

            $sync->pemakaian_dex = TransaksiBbm::where(function($q) {
                    $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX');
                })
                ->where('created_at', '>=', $sync->created_at)
                ->where('created_at', '<', $nextSyncTime)
                ->sum('liter');

            // Tambahkan Hutang Dex
            $sync->pemakaian_dex += \App\Models\Hutang::where(function($q) {
                    $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX');
                })
                ->where('created_at', '>=', $sync->created_at)
                ->where('created_at', '<', $nextSyncTime)
                ->sum('jumlah_bon');

            $sync->sisa_pertamax = $sync->stok_awal_pertamax - $sync->pemakaian_pertamax;
            $sync->sisa_dex = $sync->stok_awal_dex - $sync->pemakaian_dex;
        }

        $pdf = Pdf::loadView('admin.laporan-stok-bbm.print', compact('allSyncs'))
            ->setPaper([0, 0, 609.45, 935.43], 'landscape'); // F4 (215mm x 330mm)

        return $pdf->stream('laporan-stok-bbm-tangki-' . date('Y-m-d_H-i') . '.pdf');
    }

    public function store(Request $request)
    {
        $request->validate([
            'stok_awal_pertamax' => 'nullable|numeric|min:0',
            'stok_awal_dex' => 'nullable|numeric|min:0',
        ]);

        if ($request->stok_awal_pertamax === null && $request->stok_awal_dex === null) {
            return redirect()->back()->withErrors(['stok_awal_pertamax' => 'Salah satu stok harus diisi.'])->withInput();
        }

        $stokPertamax = $request->stok_awal_pertamax;
        $stokDex = $request->stok_awal_dex;

        // Get latest sync to calculate fallback current stock
        $latestSync = SinkronisasiBbm::latest('created_at')->first();

        if ($stokPertamax === null) {
            if ($latestSync) {
                $pemakaian = TransaksiBbm::where(function($q) {
                        $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX');
                    })
                    ->where('created_at', '>=', $latestSync->created_at)
                    ->sum('liter');
                
                // Tambahkan Hutang Pertamax
                $pemakaian += \App\Models\Hutang::where(function($q) {
                        $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX');
                    })
                    ->where('created_at', '>=', $latestSync->created_at)
                    ->sum('jumlah_bon');

                $stokPertamax = $latestSync->stok_awal_pertamax - $pemakaian;
            } else {
                $stokPertamax = 0;
            }
        }

        if ($stokDex === null) {
            if ($latestSync) {
                $pemakaian = TransaksiBbm::where(function($q) {
                        $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX');
                    })
                    ->where('created_at', '>=', $latestSync->created_at)
                    ->sum('liter');

                // Tambahkan Hutang Dex
                $pemakaian += \App\Models\Hutang::where(function($q) {
                        $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX');
                    })
                    ->where('created_at', '>=', $latestSync->created_at)
                    ->sum('jumlah_bon');

                $stokDex = $latestSync->stok_awal_dex - $pemakaian;
            } else {
                $stokDex = 0;
            }
        }

        SinkronisasiBbm::create([
            'petugas_id' => auth()->id(),
            'stok_awal_pertamax' => $stokPertamax,
            'stok_awal_dex' => $stokDex,
        ]);

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Input Stok BBM Tangki (Admin): Pertamax {$stokPertamax} L, Dex {$stokDex} L"
        ]);

        return redirect()->back()->with('success', 'Data stok BBM berhasil diperbarui. Pemakaian dihitung mulai sekarang.');
    }

    public function edit(SinkronisasiBbm $sinkronisasi)
    {
        return view('admin.laporan-stok-bbm.edit', compact('sinkronisasi'));
    }

    public function update(Request $request, SinkronisasiBbm $sinkronisasi)
    {
        $request->validate([
            'stok_awal_pertamax' => 'required|numeric|min:0',
            'stok_awal_dex' => 'required|numeric|min:0',
            'created_at' => 'required|date',
        ]);

        $sinkronisasi->update([
            'stok_awal_pertamax' => $request->stok_awal_pertamax,
            'stok_awal_dex' => $request->stok_awal_dex,
            'created_at' => $request->created_at,
        ]);

        return redirect()->route('admin.laporan-stok-bbm.index')->with('success', 'Data sinkronisasi berhasil diperbarui.');
    }

    public function destroy(SinkronisasiBbm $sinkronisasi)
    {
        \App\Models\SinkronisasiBbm::destroy($sinkronisasi->id);
        return redirect()->route('admin.laporan-stok-bbm.index')->with('success', 'Data sinkronisasi berhasil dihapus.');
    }
}
