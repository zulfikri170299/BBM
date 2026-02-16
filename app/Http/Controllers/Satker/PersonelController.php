<?php

namespace App\Http\Controllers\Satker;

use App\Http\Controllers\Controller;
use App\Models\Personel;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PersonelController extends Controller
{
    public function index()
    {
        $personels = Personel::where('satker_id', auth()->user()->satker_id)
            ->when(request('search'), function ($query) {
                $query->where(function ($q) {
                    $q->where('nama', 'like', '%' . request('search') . '%')
                      ->orWhere('nrp', 'like', '%' . request('search') . '%');
                });
            })
            ->latest()
            ->paginate(10);
        
        return view('satker.personels.index', compact('personels'));
    }



    public function create()
    {
        return view('satker.personels.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nrp' => ['required', 'string', 'max:20', 
                Rule::unique('personels')->where(function ($query) {
                    return $query->where('satker_id', auth()->user()->satker_id);
                })
            ],
        ]);

        // Create or update user account for personel
        $user = \App\Models\User::updateOrCreate(
            ['username' => $request->nrp],
            [
                'name' => $request->nama,
                'email' => $request->nrp,
                'password' => \Illuminate\Support\Facades\Hash::make($request->nrp),
                'role' => 'personel',
                'satker_id' => auth()->user()->satker_id,
            ]
        );

        Personel::create([
            'satker_id' => auth()->user()->satker_id,
            'user_id' => $user->id,
            'nama' => $request->nama,
            'nrp' => $request->nrp,
            'pin' => Personel::generateUniquePin(),
            'barcode' => $request->nrp,
        ]);

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menambahkan Personel Baru: {$request->nama} ({$request->nrp})"
        ]);

        return redirect()->route('satker.personels.index')->with('success', 'Personel dan akun user berhasil ditambahkan.');
    }

    public function edit(Personel $personel)
    {
        if ($personel->satker_id !== auth()->user()->satker_id) {
            abort(403);
        }
        return view('satker.personels.edit', compact('personel'));
    }

    public function update(Request $request, Personel $personel)
    {
        if ($personel->satker_id !== auth()->user()->satker_id) {
            abort(403);
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'nrp' => ['required', 'string', 'max:20', 
                Rule::unique('personels')->where(function ($query) {
                    return $query->where('satker_id', auth()->user()->satker_id);
                })->ignore($personel->id)
            ],
        ]);

        $personel->update($request->only(['nama', 'nrp']));

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Memperbarui data Personel: {$personel->nama} ({$personel->nrp})"
        ]);

        return redirect()->route('satker.personels.index')->with('success', 'Data personel berhasil diperbarui.');
    }

    public function destroy(Personel $personel)
    {
        if ($personel->satker_id !== auth()->user()->satker_id) {
            abort(403);
        }

        if ($personel->saldo > 0) {
            return redirect()->route('satker.personels.index')->with('error', 'Tidak dapat menghapus personel "' . $personel->nama . '" karena masih memiliki saldo ' . number_format($personel->saldo, 1) . ' L.');
        }

        $personel->delete();

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menghapus Personel: {$personel->nama} ({$personel->nrp})"
        ]);

        return redirect()->route('satker.personels.index')->with('success', 'Personel berhasil dihapus.');
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
                $countSkipped = count($import->skipped);
                $details = array_map(function($item) {
                    return "{$item['nama']} ({$item['nrp']})";
                }, $import->skipped);
                
                $detailsString = implode(', ', $details);
                $message .= " {$countSkipped} data dilewati karena NRP sudah terdaftar: {$detailsString}.";
                
                return back()->with('warning', $message);
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    public function print(Personel $personel)
    {
        if ($personel->satker_id !== auth()->user()->satker_id) {
            abort(403);
        }

        return view('satker.personels.card', compact('personel'));
    }

    public function downloadTemplate()
    {
        // Simple CSV generation matching user's format (Header at Row 3)
        $headers = [
            'Content-Type' => 'text/csv', 
            'Content-Disposition' => 'attachment; filename="template_personel_custom.csv"',
        ];
        
        $callback = function() {
            $handle = fopen('php://output', 'w');
            
            // Row 1 & 2 Empty
            fputcsv($handle, ['', '', '', '']);
            fputcsv($handle, ['', '', '', '']);
            
            // Row 3 Header
            fputcsv($handle, ['NO', 'SATKER', 'NAMA', 'NRP/NIP']);
            
            // Example Data
            fputcsv($handle, ['1', 'CONTOH SATKER', 'Fulan bin Fulan', '12345678']);
            
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
