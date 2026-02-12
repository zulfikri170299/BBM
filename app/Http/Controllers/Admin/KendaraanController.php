<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KendaraanController extends Controller
{
    public function index()
    {
        $kendaraans = Kendaraan::with('satker')->latest()->paginate(15);
        return view('admin.kendaraans.index', compact('kendaraans'));
    }

    public function topup(Request $request, Kendaraan $kendaraan)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:0.1|max:10000',
        ], [
            'jumlah.required' => 'Jumlah liter wajib diisi.',
            'jumlah.numeric' => 'Jumlah harus berupa angka.',
            'jumlah.min' => 'Jumlah minimal 0.1 liter.',
            'jumlah.max' => 'Jumlah maksimal 10.000 liter.',
        ]);

        $kendaraan->increment('saldo', $request->jumlah);

        return redirect()->route('admin.kendaraans.index')->with('success', 'Top up berhasil! ' . number_format($request->jumlah, 1, ',', '.') . ' Liter ditambahkan ke ' . $kendaraan->no_polisi . '. Saldo sekarang: ' . number_format($kendaraan->saldo, 1, ',', '.') . ' Liter.');
    }

    public function edit(Kendaraan $kendaraan)
    {
        return view('admin.kendaraans.edit', compact('kendaraan'));
    }

    public function update(Request $request, Kendaraan $kendaraan)
    {
        $request->validate([
            'no_polisi' => ['required', 'string', 'max:20', Rule::unique('kendaraans')->ignore($kendaraan->id)],
            'jenis_kendaraan' => 'required|string',
            'jenis_bbm' => 'required|string',
            'pin' => 'nullable|numeric|digits:6',
        ]);

        $data = $request->except('pin');
        if ($request->filled('pin')) {
            $data['pin'] = $request->pin;
        }

        $kendaraan->update($data);

        return redirect()->route('admin.kendaraans.index')->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    public function destroy(Kendaraan $kendaraan)
    {
        $kendaraan->delete();
        return redirect()->route('admin.kendaraans.index')->with('success', 'Kendaraan berhasil dihapus.');
    }
}
