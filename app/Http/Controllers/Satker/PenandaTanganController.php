<?php

namespace App\Http\Controllers\Satker;

use App\Http\Controllers\Controller;
use App\Models\PenandaTangan;
use Illuminate\Http\Request;

class PenandaTanganController extends Controller
{
    public function index()
    {
        $satkerId = auth()->user()->satker_id;
        $penandaTangans = PenandaTangan::where('satker_id', $satkerId)->latest()->get();
        
        return view('satker.penanda_tangan.index', compact('penandaTangans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'jabatan2' => 'nullable|string|max:255',
            'pangkat' => 'nullable|string|max:255',
            'nrp' => 'nullable|string|max:255',
        ]);

        PenandaTangan::create([
            'user_id' => auth()->id(),
            'satker_id' => auth()->user()->satker_id,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'jabatan2' => $request->jabatan2,
            'pangkat' => $request->pangkat,
            'nrp' => $request->nrp,
        ]);

        return back()->with('success', 'Data penanda tangan berhasil disimpan.');
    }

    public function update(Request $request, PenandaTangan $penandaTangan)
    {
        if ($penandaTangan->satker_id !== auth()->user()->satker_id) {
            abort(403);
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'jabatan2' => 'nullable|string|max:255',
            'pangkat' => 'nullable|string|max:255',
            'nrp' => 'nullable|string|max:255',
        ]);

        $penandaTangan->update($request->only(['nama', 'jabatan', 'jabatan2', 'pangkat', 'nrp']));

        return back()->with('success', 'Data penanda tangan berhasil diperbarui.');
    }

    public function destroy(PenandaTangan $penandaTangan)
    {
        if ($penandaTangan->satker_id !== auth()->user()->satker_id) {
            abort(403);
        }

        $penandaTangan->delete();
        return back()->with('success', 'Data penanda tangan berhasil dihapus.');
    }
}
