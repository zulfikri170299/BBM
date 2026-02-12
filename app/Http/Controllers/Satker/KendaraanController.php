<?php

namespace App\Http\Controllers\Satker;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use Barryvdh\DomPDF\Facade\Pdf;

class KendaraanController extends Controller
{
    public function index()
    {
        $kendaraans = Kendaraan::where('satker_id', auth()->user()->satker_id)->latest()->paginate(10);
        return view('satker.kendaraans.index', compact('kendaraans'));
    }

    public function print(Kendaraan $kendaraan)
    {
        if ($kendaraan->satker_id !== auth()->user()->satker_id) {
            abort(403);
        }

        return view('satker.kendaraans.print', compact('kendaraan'));
    }

    public function create()
    {
        return view('satker.kendaraans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_polisi' => 'required|string|max:20|unique:kendaraans',
            'jenis_kendaraan' => 'required|string',
            'jenis_bbm' => 'required|string',
        ]);

        // Auto-generate unique barcode
        $barcode = strtoupper(Str::random(10));
        while (Kendaraan::where('barcode', $barcode)->exists()) {
            $barcode = strtoupper(Str::random(10));
        }

        // Auto-generate 6-digit PIN
        $pin = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Kendaraan::create([
            'satker_id' => auth()->user()->satker_id,
            'no_polisi' => $request->no_polisi,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'jenis_bbm' => $request->jenis_bbm,
            'barcode' => $barcode,
            'pin' => $pin,
            'saldo' => 0,
        ]);

        return redirect()->route('satker.kendaraans.index')->with('success', 'Kendaraan berhasil ditambahkan! Barcode: ' . $barcode . ' | PIN: ' . $pin . ' (Simpan PIN ini, tidak bisa dilihat lagi!)');
    }
}
