<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\TransaksiBbm;
use App\Models\Personel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    public function index()
    {
        return view('petugas.transaksi.index');
    }

    public function check(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string|exists:kendaraans,barcode',
        ]);

        $kendaraan = Kendaraan::with('satker')->where('barcode', $request->barcode)->firstOrFail();

        return view('petugas.transaksi.create', compact('kendaraan'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'liter' => 'required|numeric|min:0.1',
            'pin' => 'required|numeric',
        ]);

        $kendaraan = Kendaraan::findOrFail($request->kendaraan_id);

        // Verify PIN
        if ($request->pin !== $kendaraan->pin) {
            return back()->withErrors(['pin' => 'PIN Salah!'])->withInput();
        }

        // Calculate total price (Simplified logic: Assuming fixed prices for now or input)
        // In a real app, prices should be managed in DB.
        $prices = [
            'Pertalite' => 10000,
            'Pertamax' => 12950,
            'Solar' => 6800,
            'Dexlite' => 14550,
        ];
        
        $hargaPerLiter = $prices[$kendaraan->jenis_bbm] ?? 0;
        $totalHarga = $hargaPerLiter * $request->liter;

        // Check Balance
        if ($kendaraan->saldo < $totalHarga) {
            return back()->with('error', 'Saldo tidak mencukupi! Saldo: Rp ' . number_format($kendaraan->saldo) . ', Total: Rp ' . number_format($totalHarga));
        }

        // Deduct Balance
        $kendaraan->decrement('saldo', $totalHarga);

        // Record Transaction
        $transaksi = TransaksiBbm::create([
            'kendaraan_id' => $kendaraan->id,
            'petugas_id' => auth()->id(),
            'tanggal' => now(),
            'liter' => $request->liter,
            'harga_per_liter' => $hargaPerLiter,
            'total' => $totalHarga,
        ]);

        return redirect()->route('petugas.transaksi.print', $transaksi);
    }

    public function print(TransaksiBbm $transaksi)
    {
        // Allow access if user is petugas who created it OR admin/superadmin (optional, but for now strict)
        if ($transaksi->petugas_id !== auth()->id()) {
            abort(403);
        }

        $pdf = Pdf::loadView('petugas.transaksi.print', compact('transaksi'));
        // Set paper size for thermal printer (e.g., 58mm or 80mm width)
        $pdf->setPaper([0, 0, 226.77, 500], 'portrait'); // ~80mm width

        return $pdf->stream('struk-bbm-' . $transaksi->id . '.pdf');
    }
}
