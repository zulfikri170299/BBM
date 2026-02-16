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
    public function index()
    {
        $stocks = AdminBbmStock::all();
        $history = RiwayatStokAdmin::with('user')->latest()->paginate(20);
        return view('admin.stok.index', compact('stocks', 'history'));
    }

    public function print()
    {
        $stocks = AdminBbmStock::all();
        $history = RiwayatStokAdmin::with('user')->latest()->get();
        
        $pdf = Pdf::loadView('admin.stok.print', compact('stocks', 'history'))
            ->setPaper('a4', 'portrait');
            
        return $pdf->stream('riwayat-stok-pusat-' . date('Y-m-d') . '.pdf');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_bbm' => 'required|in:Pertamax,Pertamina Dex',
            'jumlah' => 'required|numeric|min:0.01',
            'keterangan' => 'nullable|string|max:255',
            'topup_security_code' => 'required|string',
        ]);

        $user = auth()->user();

        // Cek apakah user sudah mengatur password topup
        if (!$user->topup_password) {
            return back()->with('error', 'Anda belum mengatur Password Top-up. Silakan atur di menu Profil terlebih dahulu.');
        }

        // Verifikasi password topup
        if (!\Illuminate\Support\Facades\Hash::check($request->topup_security_code, $user->topup_password)) {
            return back()->with('error', 'Password konfirmasi salah.');
        }

        try {
            DB::beginTransaction();

            $stock = AdminBbmStock::where('jenis_bbm', $request->jenis_bbm)->firstOrFail();
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
