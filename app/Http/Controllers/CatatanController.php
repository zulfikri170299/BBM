<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Catatan;
use Illuminate\Support\Facades\Auth;

class CatatanController extends Controller
{
    public function index()
    {
        $catatans = Catatan::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('catatan.index', compact('catatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'warna' => 'nullable|string',
        ]);

        Catatan::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'isi' => $request->isi,
            'warna' => $request->warna ?? 'indigo',
        ]);

        return back()->with('success', 'Catatan berhasil ditambahkan');
    }

    public function update(Request $request, Catatan $catatan)
    {
        if ($catatan->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'warna' => 'nullable|string',
        ]);

        $catatan->update($request->all());

        return back()->with('success', 'Catatan berhasil diperbarui');
    }

    public function destroy(Catatan $catatan)
    {
        if ($catatan->user_id !== Auth::id()) {
            abort(403);
        }

        $catatan->delete();

        return back()->with('success', 'Catatan berhasil dihapus');
    }
}
