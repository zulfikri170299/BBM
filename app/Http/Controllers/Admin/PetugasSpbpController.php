<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PetugasSpbp;
use Illuminate\Http\Request;

class PetugasSpbpController extends Controller
{
    public function index()
    {
        $petugas = PetugasSpbp::orderBy('urutan')->get();
        return view('admin.petugas_spbp.index', compact('petugas'));
    }

    public function create()
    {
        return view('admin.petugas_spbp.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'pangkat_nrp' => 'required|string|max:255',
            'urutan' => 'required|integer',
        ]);

        PetugasSpbp::create($request->all());

        return redirect()->route('admin.petugas-spbp.index')->with('success', 'Petugas SPBP berhasil ditambahkan.');
    }

    public function edit(PetugasSpbp $petugas_spbp)
    {
        return view('admin.petugas_spbp.edit', compact('petugas_spbp'));
    }

    public function update(Request $request, PetugasSpbp $petugas_spbp)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'pangkat_nrp' => 'required|string|max:255',
            'urutan' => 'required|integer',
        ]);

        $petugas_spbp->update($request->all());

        return redirect()->route('admin.petugas-spbp.index')->with('success', 'Petugas SPBP berhasil diperbarui.');
    }

    public function destroy(PetugasSpbp $petugas_spbp)
    {
        $petugas_spbp->delete();
        return redirect()->route('admin.petugas-spbp.index')->with('success', 'Petugas SPBP berhasil dihapus.');
    }
}
