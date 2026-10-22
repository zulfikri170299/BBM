<?php

namespace App\Http\Controllers\Satker;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use App\Traits\PaginatesTables;

class KendaraanApprovalController extends Controller
{
    use PaginatesTables;

    public function index(Request $request)
    {
        $user = auth()->user();
        $satkerId = $user->satker_id;

        $status = $request->input('status', 'pending');
        $aksi = $request->input('aksi');
        $search = $request->input('search');

        $query = Kendaraan::with(['diajukanOleh'])
            ->where('satker_id', $satkerId)
            ->latest('updated_at');

        if ($status && $status !== 'all') {
            $query->where('status_approval', $status);
        }

        if ($aksi) {
            $query->where('aksi_approval', $aksi);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('no_polisi', 'like', "%{$search}%")
                  ->orWhere('kode_kendaraan', 'like', "%{$search}%")
                  ->orWhere('jenis_kendaraan', 'like', "%{$search}%");
            });
        }

        $perPage = $this->getPerPage($request, 15);
        $kendaraans = $query->paginate($perPage)->withQueryString();

        $statusCounts = Kendaraan::where('satker_id', $satkerId)
            ->selectRaw('status_approval, count(*) as total')
            ->groupBy('status_approval')
            ->pluck('total', 'status_approval');

        $counts = [
            'pending' => (int) ($statusCounts['pending'] ?? 0),
            'disetujui' => (int) ($statusCounts['disetujui'] ?? 0),
            'ditolak' => (int) ($statusCounts['ditolak'] ?? 0),
            'all' => (int) $statusCounts->sum(),
        ];

        return view('satker.persetujuan-kendaraan.index', compact(
            'kendaraans',
            'counts',
            'status',
            'aksi',
            'search'
        ));
    }

    public function destroy(Kendaraan $kendaraan)
    {
        $user = auth()->user();
        if ($kendaraan->satker_id !== $user->satker_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk membatalkan pengajuan ini.');
        }

        if ($kendaraan->status_approval === 'disetujui') {
            return back()->with('error', 'Kendaraan yang sudah disetujui tidak dapat dibatalkan dari menu pengajuan.');
        }

        $noPolisi = $kendaraan->no_polisi;
        $aksi = $kendaraan->aksi_approval;

        if ($aksi === 'edit') {
            // Jika aksi edit yang dibatalkan, kembalikan status ke disetujui dan bersihkan pending_changes
            $kendaraan->update([
                'status_approval' => 'disetujui',
                'aksi_approval' => null,
                'pending_changes' => null,
                'alasan_penolakan' => null,
            ]);
            $pesan = "Pengajuan perubahan data kendaraan {$noPolisi} berhasil dibatalkan.";
        } else {
            // Jika penambahan baru / import yang belum disetujui, hapus datanya
            $kendaraan->delete();
            $pesan = "Pengajuan kendaraan {$noPolisi} berhasil dibatalkan dan dihapus.";
        }

        LogAktivitas::create([
            'user_id' => $user->id,
            'aktivitas' => 'Membatalkan Pengajuan Kendaraan',
            'deskripsi' => "Membatalkan pengajuan {$aksi} untuk kendaraan {$noPolisi}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', $pesan);
    }
}
