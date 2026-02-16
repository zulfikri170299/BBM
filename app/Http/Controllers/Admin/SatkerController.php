<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Satker;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class SatkerController extends Controller
{
    public function index()
    {
        $satkers = Satker::latest()->paginate(10);
        return view('admin.satkers.index', compact('satkers'));
    }

    public function create()
    {
        return view('admin.satkers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_satker' => 'required|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        Satker::create($request->all());

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menambahkan Satker Baru: {$request->nama_satker}"
        ]);

        return redirect()->route('admin.satkers.index')->with('success', 'Satker created successfully.');
    }

    public function edit(Satker $satker)
    {
        return view('admin.satkers.edit', compact('satker'));
    }

    public function update(Request $request, Satker $satker)
    {
        $request->validate([
            'nama_satker' => 'required|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        $satker->update($request->all());

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Memperbarui data Satker: {$satker->nama_satker}"
        ]);

        return redirect()->route('admin.satkers.index')->with('success', 'Satker updated successfully.');
    }

    public function destroy(Satker $satker)
    {
        $satker->delete();

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menghapus Satker: {$satker->nama_satker}"
        ]);

        return redirect()->route('admin.satkers.index')->with('success', 'Satker deleted successfully.');
    }
}
