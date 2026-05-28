<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Satker;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

use App\Traits\PaginatesTables;

class SatkerController extends Controller
{
    use PaginatesTables;

    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);
        $query = Satker::latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_satker', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        $satkers = $query->paginate($perPage)->withQueryString();
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

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:satkers,id',
        ]);

        $count = Satker::whereIn('id', $request->ids)->count();
        Satker::whereIn('id', $request->ids)->delete();

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menghapus {$count} Satker secara massal"
        ]);

        return back()->with('success', "{$count} satker berhasil dihapus.");
    }
}
