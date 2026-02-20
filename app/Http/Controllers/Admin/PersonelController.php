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

    public function create()
    {
        $satkers = \App\Models\Satker::orderBy('nama_satker')->get();
        return view('admin.personels.create', compact('satkers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'satker_id' => 'required|exists:satkers,id',
            'nama' => 'required|string|max:255',
            'nrp' => 'required|string|max:20|unique:personels,nrp',
            'jenis_bbm' => 'required|string',
            'saldo' => 'required|numeric|min:0',
        ]);

        $user = \App\Models\User::updateOrCreate(
            ['username' => $request->nrp],
            [
                'name' => $request->nama,
                'email' => $request->nrp,
                'password' => \Illuminate\Support\Facades\Hash::make($request->nrp),
                'role' => 'personel',
                'satker_id' => $request->satker_id,
            ]
        );

        Personel::create([
            'satker_id' => $request->satker_id,
            'user_id' => $user->id,
            'nama' => $request->nama,
            'nrp' => $request->nrp,
            'jenis_bbm' => $request->jenis_bbm,
            'saldo' => $request->saldo,
            'pin' => Personel::generateUniquePin(),
            'barcode' => $request->nrp,
        ]);

        \App\Models\LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menambahkan Personel Baru (Admin): {$request->nama} ({$request->nrp})"
        ]);

        return redirect()->route('admin.personels.index')->with('success', 'Personel berhasil ditambahkan.');
    }

    public function edit(Personel $personel)
    {
        $satkers = \App\Models\Satker::orderBy('nama_satker')->get();
        return view('admin.personels.edit', compact('personel', 'satkers'));
    }

    public function update(Request $request, Personel $personel)
    {
        $request->validate([
            'satker_id' => 'required|exists:satkers,id',
            'nama' => 'required|string|max:255',
            'nrp' => 'required|string|max:20|unique:personels,nrp,' . $personel->id,
            'jenis_bbm' => 'required|string',
            'saldo' => 'required|numeric|min:0',
        ]);

        $personel->update($request->all());

        \App\Models\LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Memperbarui data Personel (Admin): {$personel->nama} ({$personel->nrp})"
        ]);

        return redirect()->route('admin.personels.index')->with('success', 'Data personel berhasil diperbarui.');
    }

    public function export(Request $request)
    {
        $satkerId = $request->satker_id;
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PersonelExport($satkerId), 'data_personel.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            $import = new \App\Imports\PersonelImport;
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));
            
            $message = "Import selesai. {$import->imported} data berhasil ditambahkan.";
            
            if (count($import->skipped) > 0) {
                return back()->with('warning', $message . " Beberapa data dilewati karena sudah terdaftar atau Satker tidak ditemukan.");
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv', 
            'Content-Disposition' => 'attachment; filename="template_personel.csv"',
        ];
        
        $callback = function() {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['NO', 'SATKER', 'NAMA', 'NRP/NIP'], ';'); // Baris 1 Header dengan ;
            fputcsv($handle, ['1', 'CONTOH SATKER', 'Fulan bin Fulan', '12345678'], ';');
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
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
