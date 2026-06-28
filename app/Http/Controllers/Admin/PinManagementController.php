<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Personel;
use App\Models\Kendaraan;
use App\Models\Satker;
use Illuminate\Http\Request;
use App\Models\LogAktivitas;
use App\Traits\PaginatesTables;

class PinManagementController extends Controller
{
    use PaginatesTables;

    public function index(Request $request)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403);
        }

        $tab = $request->get('tab', 'personel');
        $search = $request->get('search');
        $satkerId = $request->get('satker_id');
        $perPage = $this->getPerPage($request);

        $satkers = Satker::orderBy('nama_satker')->get();

        if ($tab === 'personel') {
            $query = Personel::with('satker')->latest();
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('nrp', 'like', "%{$search}%");
                });
            }
            if ($satkerId) {
                $query->where('satker_id', $satkerId);
            }
            $data = $query->paginate($perPage, ['*'], 'personel_page')->withQueryString();
        } else {
            $query = Kendaraan::with('satker')->latest();
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('no_polisi', 'like', "%{$search}%")
                      ->orWhere('kode_kendaraan', 'like', "%{$search}%");
                });
            }
            if ($satkerId) {
                $query->where('satker_id', $satkerId);
            }
            $data = $query->paginate($perPage, ['*'], 'kendaraan_page')->withQueryString();
        }

        return view('admin.pin-management.index', compact('data', 'satkers', 'tab'));
    }

    public function updatePersonelPin(Request $request, Personel $personel)
    {
        if (auth()->user()->role !== 'super_admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'pin' => 'required|integer|digits:6',
        ]);

        $personel->update(['pin' => $request->pin]);

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Update PIN Personel (Manual): {$personel->nama} (NRP: {$personel->nrp})"
        ]);

        return response()->json(['success' => true, 'message' => 'PIN Personel berhasil diperbarui.']);
    }

    public function updateKendaraanPin(Request $request, Kendaraan $kendaraan)
    {
        if (auth()->user()->role !== 'super_admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'pin' => 'required|integer|digits:6',
        ]);

        $kendaraan->update(['pin' => $request->pin]);

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Update PIN Kendaraan (Manual): {$kendaraan->no_polisi}"
        ]);

        return response()->json(['success' => true, 'message' => 'PIN Kendaraan berhasil diperbarui.']);
    }
}
