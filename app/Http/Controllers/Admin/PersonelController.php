<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Personel;
use Illuminate\Http\Request;

class PersonelController extends Controller
{
    public function index(Request $request)
    {
        $query = Personel::with('satker')->latest();

        // Filter by Search (Name or NRP)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nrp', 'like', "%{$search}%");
            });
        }

        // Filter by Satker
        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        $personels = $query->paginate(10)->withQueryString();
        $satkers = \App\Models\Satker::orderBy('nama_satker')->get();
        
        return view('admin.personels.index', compact('personels', 'satkers'));
    }

    public function print(Personel $personel)
    {
        return view('satker.personels.card', compact('personel'));
    }

    public function destroy(Personel $personel)
    {
        $personel->delete();
        return redirect()->route('admin.personels.index')->with('success', 'Personel berhasil dihapus.');
    }

    public function resetPin(Personel $personel)
    {
        if (auth()->user()->role !== 'super_admin') {
            return back()->with('error', 'Hanya Super Admin yang dapat mereset PIN.');
        }

        $newPin = Personel::generateUniquePin();
        $personel->update(['pin' => $newPin]);

        \App\Models\LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Reset PIN Personel (Super Admin): {$personel->nama} (NRP: {$personel->nrp})"
        ]);

        return back()->with('success', "PIN Personel {$personel->nama} berhasil di-reset. PIN Baru: {$newPin}");
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:personels,id',
        ]);

        $count = Personel::whereIn('id', $request->ids)->count();
        Personel::whereIn('id', $request->ids)->delete();

        \App\Models\LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menghapus {$count} Personel secara massal"
        ]);

        return back()->with('success', "{$count} personel berhasil dihapus.");
    }
}
