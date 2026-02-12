<?php

namespace App\Http\Controllers\Satker;

use App\Http\Controllers\Controller;
use App\Models\Personel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PersonelController extends Controller
{
    public function index()
    {
        $personels = Personel::where('satker_id', auth()->user()->satker_id)->latest()->paginate(10);
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

        Personel::create([
            'satker_id' => auth()->user()->satker_id,
            'nama' => $request->nama,
            'nrp' => $request->nrp,
            'saldo' => 0,
        ]);

        return redirect()->route('satker.personels.index')->with('success', 'Personel berhasil ditambahkan.');
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

        return redirect()->route('satker.personels.index')->with('success', 'Data personel berhasil diperbarui.');
    }

    public function destroy(Personel $personel)
    {
        if ($personel->satker_id !== auth()->user()->satker_id) {
            abort(403);
        }
        $personel->delete();
        return redirect()->route('satker.personels.index')->with('success', 'Personel berhasil dihapus.');
    }
}
