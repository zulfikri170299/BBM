<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HutangController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Hutang::with(['satker', 'petugas', 'adminBayar'])->orderBy('created_at', 'desc');

        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            
            $query->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_bon', [$startDate, $endDate])
                  ->orWhere(function($subq) use ($startDate, $endDate) {
                      $subq->whereNull('tanggal_bon')
                           ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                  });
            });
        }

        $perPage = $request->input('per_page', 15);
        $hutangs = $query->paginate($perPage)->withQueryString();
        $satkers = \App\Models\Satker::orderBy('nama_satker')->get();

        // Hitung Summary Hutang (Hanya yang belum dibayar)
        $summaryQuery = \App\Models\Hutang::where('status', 'belum_dibayar');
        if ($request->filled('satker_id')) {
            $summaryQuery->where('satker_id', $request->satker_id);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $summaryQuery->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_bon', [$startDate, $endDate])
                  ->orWhere(function($subq) use ($startDate, $endDate) {
                      $subq->whereNull('tanggal_bon')
                           ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                  });
            });
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
        $stocks = $tankStock; // Passed to index.blade.php

        return view('admin.hutang.index', compact('hutangs', 'satkers', 'summaryHutang', 'stocks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'satker_id' => 'required|exists:satkers,id',
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'nama_driver' => 'required|string',
            'jumlah_bon' => 'required|integer|min:1',
            'tanggal_bon' => 'required|date',
        ]);

        $kendaraan = \App\Models\Kendaraan::findOrFail($request->kendaraan_id);

        if ($kendaraan->satker_id != $request->satker_id) {
            return back()->with('error', 'Kendaraan tidak sesuai dengan Satker yang dipilih.');
        }

        if ((float) $kendaraan->saldo > 0) {
            return back()->with('error', 'Kendaraan ' . $kendaraan->no_polisi . ' masih memiliki saldo ' . number_format($kendaraan->saldo, 0, ',', '.') . ' L. Hutang BBM hanya dapat dibuat jika saldo kendaraan kosong atau 0.');
        }

        // Validasi Real Physical Tank Stock
        $latestSync = \App\Models\SinkronisasiBbm::orderBy('created_at', 'desc')->first();
        $saldoTangki = 0;
        if ($latestSync) {
            $isPertamax = stripos($kendaraan->jenis_bbm, 'Pertamax') !== false;
            $pemakaian = \App\Models\TransaksiBbm::where(function($q) use ($kendaraan) { $q->where('jenis_bbm', $kendaraan->jenis_bbm)->orWhere('jenis_bbm', strtoupper($kendaraan->jenis_bbm)); })
                ->where('created_at', '>=', $latestSync->created_at)->sum('liter');
            $pemakaian += \App\Models\Hutang::where(function($q) use ($kendaraan) { $q->where('jenis_bbm', $kendaraan->jenis_bbm)->orWhere('jenis_bbm', strtoupper($kendaraan->jenis_bbm)); })
                ->where('created_at', '>=', $latestSync->created_at)->sum('jumlah_bon');
            $saldoTangki = $isPertamax ? ($latestSync->stok_awal_pertamax - $pemakaian) : ($latestSync->stok_awal_dex - $pemakaian);
        }

        if ($request->jumlah_bon > $saldoTangki) {
            return back()->with('error', "Stok tangki {$kendaraan->jenis_bbm} tidak mencukupi. (Sisa: {$saldoTangki} L)");
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $kendaraan, &$hutang) {
            // Note: Tidak perlu memotong stock apa pun karena physical tank terpotong secara otomatis by sum query

            $hutang = \App\Models\Hutang::create([
                'satker_id' => $request->satker_id,
                'petugas_id' => auth()->id(),
                'nama_driver' => $request->nama_driver,
                'jenis_kendaraan' => $kendaraan->jenis_kendaraan,
                'nopol' => $kendaraan->no_polisi,
                'jenis_bbm' => $kendaraan->jenis_bbm,
                'jumlah_bon' => $request->jumlah_bon,
                'tanggal_bon' => $request->tanggal_bon,
                'status' => 'belum_dibayar',
            ]);

            \App\Models\LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => "Super Admin menginput data hutang BBM baru (ID: {$hutang->id}) untuk Satker {$hutang->satker->nama_satker}"
            ]);
        });

        return back()->with('success', 'Data hutang berhasil ditambahkan dan stok tangki dikurangi.');
    }

    public function downloadPDF(Request $request)
    {
        $query = \App\Models\Hutang::with(['satker', 'petugas', 'adminBayar'])->orderBy('created_at', 'desc');

        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            
            $query->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_bon', [$startDate, $endDate])
                  ->orWhere(function($subq) use ($startDate, $endDate) {
                      $subq->whereNull('tanggal_bon')
                           ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                  });
            });
        }

        $hutangs = $query->get();
        $satker = null;
        if ($request->filled('satker_id')) {
            $satker = \App\Models\Satker::find($request->satker_id);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan_hutang.print', [
            'hutangs' => $hutangs,
            'satker' => $satker,
            'filter_status' => $request->status
        ])->setPaper('f4', 'landscape');

        return $pdf->download('Laporan_Monitoring_Hutang_BBM_' . now()->format('YmdHis') . '.pdf');
    }

    public function update(Request $request, \App\Models\Hutang $hutang)
    {
        $request->validate([
            'satker_id' => 'required|exists:satkers,id',
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'nama_driver' => 'required|string',
            'jumlah_bon' => 'required|integer|min:1',
            'tanggal_bon' => 'required|date',
        ]);

        $kendaraan = \App\Models\Kendaraan::findOrFail($request->kendaraan_id);

        $selisih = $request->jumlah_bon - $hutang->jumlah_bon;

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $kendaraan, $hutang, $selisih) {
            if ($selisih > 0) {
                // Validasi ulang physical tank stock jika jumlah bon bertambah
                $latestSync = \App\Models\SinkronisasiBbm::orderBy('created_at', 'desc')->first();
                $saldoTangki = 0;
                if ($latestSync) {
                    $isPertamax = stripos($kendaraan->jenis_bbm, 'Pertamax') !== false;
                    $pemakaian = \App\Models\TransaksiBbm::where(function($q) use ($kendaraan) { $q->where('jenis_bbm', $kendaraan->jenis_bbm)->orWhere('jenis_bbm', strtoupper($kendaraan->jenis_bbm)); })
                        ->where('created_at', '>=', $latestSync->created_at)->sum('liter');
                    $pemakaian += \App\Models\Hutang::where(function($q) use ($kendaraan) { $q->where('jenis_bbm', $kendaraan->jenis_bbm)->orWhere('jenis_bbm', strtoupper($kendaraan->jenis_bbm)); })
                        ->where('created_at', '>=', $latestSync->created_at)->sum('jumlah_bon');
                    $saldoTangki = $isPertamax ? ($latestSync->stok_awal_pertamax - $pemakaian) : ($latestSync->stok_awal_dex - $pemakaian);
                }

                if ($selisih > $saldoTangki) {
                    throw new \Exception("Stok tangki {$kendaraan->jenis_bbm} tidak mencukupi untuk penambahan jumlah hutang. Mks: {$saldoTangki} L.");
                }
            }

            $hutang->update([
                'satker_id' => $request->satker_id,
                'nama_driver' => $request->nama_driver,
                'jenis_kendaraan' => $kendaraan->jenis_kendaraan,
                'nopol' => $kendaraan->no_polisi,
                'jenis_bbm' => $kendaraan->jenis_bbm,
                'jumlah_bon' => $request->jumlah_bon,
                'tanggal_bon' => $request->tanggal_bon,
            ]);

            \App\Models\LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => "Super Admin mengedit data hutang BBM (ID: {$hutang->id}) untuk Satker {$hutang->satker->nama_satker}"
            ]);
        });

        return back()->with('success', 'Data hutang berhasil diperbarui.');
    }

    public function destroy(\App\Models\Hutang $hutang)
    {
        $id = $hutang->id;
        $satker = $hutang->satker->nama_satker;
        
        $hutang->delete();

        \App\Models\LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Super Admin menghapus data hutang BBM (ID: {$id}) untuk Satker {$satker}"
        ]);

        return back()->with('success', 'Data hutang berhasil dihapus.');
    }

    public function getKendaraan(Request $request)
    {
        $request->validate(['satker_id' => 'required|exists:satkers,id']);

        $kendaraans = \App\Models\Kendaraan::where('satker_id', $request->satker_id)
            ->where('saldo', '<=', 0)
            ->select('id', 'no_polisi', 'jenis_kendaraan', 'jenis_bbm', 'saldo')
            ->get();

        return response()->json($kendaraans);
    }

    public function bayar(Request $request, \App\Models\Hutang $hutang)
    {
        $user = auth()->user();

        if ($hutang->status === 'sudah_dibayar') {
            return back()->with('error', 'Hutang ini sudah dibayar sebelumnya.');
        }

        $kendaraan = \App\Models\Kendaraan::where('no_polisi', $hutang->nopol)
            ->where('satker_id', $hutang->satker_id)
            ->firstOrFail();

        if ($kendaraan->saldo < $hutang->jumlah_bon) {
            return back()->with('error', 'Saldo kendaraan tidak mencukupi. Saldo: ' . $kendaraan->saldo . ' L');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($hutang, $kendaraan, $user) {
            $kendaraan->decrement('saldo', $hutang->jumlah_bon);

            $hutang->update([
                'status' => 'sudah_dibayar',
                'admin_bayar_id' => $user->id,
                'tanggal_bayar' => now()
            ]);

            // Create RiwayatTopup for report integration
            \App\Models\RiwayatTopup::create([
                'satker_id' => $hutang->satker_id,
                'kendaraan_id' => $kendaraan->id,
                'user_id' => $user->id,
                'jumlah' => $hutang->jumlah_bon,
                'tipe' => 'keluar',
                'metode' => 'Potong Saldo',
                'status' => 'success',
                'jenis_bbm' => $kendaraan->jenis_bbm,
                'keterangan' => "Pelunasan bon hutang oleh Super Admin (Nopol: {$hutang->nopol}, Jenis BBM: {$hutang->jenis_bbm})",
            ]);

            \App\Models\LogAktivitas::create([
                'user_id' => $user->id,
                'aktivitas' => "Super Admin membayar hutang BBM sebesar {$hutang->jumlah_bon} L untuk Satker {$hutang->satker->nama_satker} menggunakan saldo kendaraan {$kendaraan->no_polisi}"
            ]);
        });

        return back()->with('success', 'Hutang berhasil dibayar oleh Super Admin.');
    }
}
