<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\SinkronisasiBbm;
use App\Models\TransaksiBbm;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

use App\Traits\PaginatesTables;

class SinkronisasiBbmController extends Controller
{
    use PaginatesTables;

    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);
        
        // Fetch all descending to calculate usage correctly. Then we will paginate the collection.
        $allSyncs = SinkronisasiBbm::with('petugas')->orderBy('created_at', 'desc')->get();
        
        foreach ($allSyncs as $index => $sync) {
            $nextSyncTime = isset($allSyncs[$index - 1]) ? $allSyncs[$index - 1]->created_at : now();

            $sync->pemakaian_pertamax = TransaksiBbm::where(function($q) {
                    $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX');
                })
                ->where('created_at', '>=', $sync->created_at)
                ->where('created_at', '<', $nextSyncTime)
                ->sum('liter');

            $sync->pemakaian_dex = TransaksiBbm::where(function($q) {
                    $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX');
                })
                ->where('created_at', '>=', $sync->created_at)
                ->where('created_at', '<', $nextSyncTime)
                ->sum('liter');

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

        return view('petugas.sinkronisasi.index', compact('syncs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'stok_awal_pertamax' => 'required|numeric|min:0',
            'stok_awal_dex' => 'required|numeric|min:0',
        ]);

        SinkronisasiBbm::create([
            'petugas_id' => auth()->id(),
            'stok_awal_pertamax' => $request->stok_awal_pertamax,
            'stok_awal_dex' => $request->stok_awal_dex,
        ]);

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Input Stok BBM Tangki: Pertamax {$request->stok_awal_pertamax} L, Dex {$request->stok_awal_dex} L"
        ]);

        return redirect()->back()->with('success', 'Data stok BBM berhasil diperbarui. Pemakaian dihitung mulai sekarang.');
    }
}
