<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Satker;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use App\Traits\PaginatesTables;

class KendaraanApprovalController extends Controller
{
    use PaginatesTables;

    public function index(Request $request)
    {
        $status = $request->input('status', 'pending');
        $satkerId = $request->input('satker_id');
        $aksi = $request->input('aksi');
        $search = $request->input('search');

        $query = Kendaraan::with(['satker', 'diajukanOleh'])->latest('updated_at');

        if ($status && $status !== 'all') {
            $query->where('status_approval', $status);
        }

        if ($satkerId) {
            $query->where('satker_id', $satkerId);
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

        $perPage = $this->getPerPage($request, 20);
        $kendaraans = $query->paginate($perPage)->withQueryString();

        $satkers = Satker::orderBy('nama_satker')->get();

        $counts = [
            'pending' => Kendaraan::where('status_approval', 'pending')->count(),
            'disetujui' => Kendaraan::where('status_approval', 'disetujui')->count(),
            'ditolak' => Kendaraan::where('status_approval', 'ditolak')->count(),
            'pending_tambah' => Kendaraan::where('status_approval', 'pending')->where('aksi_approval', 'tambah')->count(),
            'pending_edit' => Kendaraan::where('status_approval', 'pending')->where('aksi_approval', 'edit')->count(),
            'pending_import' => Kendaraan::where('status_approval', 'pending')->where('aksi_approval', 'import')->count(),
        ];

        return view('admin.persetujuan-kendaraan.index', compact('kendaraans', 'satkers', 'counts', 'status', 'satkerId', 'aksi', 'search'));
    }

    public function approve(Kendaraan $kendaraan)
    {
        $aksi = $kendaraan->aksi_approval;
        $satkerName = $kendaraan->satker->nama_satker ?? 'Satker';

        if ($aksi === 'edit' && !empty($kendaraan->pending_changes)) {
            $changes = is_array($kendaraan->pending_changes) 
                ? $kendaraan->pending_changes 
                : json_decode($kendaraan->pending_changes, true);

            $updateData = [];
            if (isset($changes['no_polisi'])) $updateData['no_polisi'] = $changes['no_polisi'];
            if (isset($changes['jenis_kendaraan'])) $updateData['jenis_kendaraan'] = $changes['jenis_kendaraan'];
            if (isset($changes['jenis_bbm'])) $updateData['jenis_bbm'] = $changes['jenis_bbm'];
            if (isset($changes['roda'])) $updateData['roda'] = $changes['roda'];
            if (isset($changes['cc'])) $updateData['cc'] = $changes['cc'];

            $updateData['pending_changes'] = null;
            $updateData['status_approval'] = 'disetujui';
            $updateData['disetujui_oleh'] = auth()->id();
            $updateData['disetujui_pada'] = now();
            $updateData['alasan_penolakan'] = null;

            $kendaraan->update($updateData);

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => "Menyetujui perubahan data kendaraan: {$kendaraan->no_polisi} ({$satkerName})"
            ]);
        } else {
            $kendaraan->update([
                'status_approval' => 'disetujui',
                'disetujui_oleh' => auth()->id(),
                'disetujui_pada' => now(),
                'alasan_penolakan' => null,
            ]);

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => "Menyetujui pengajuan kendaraan " . ($aksi === 'import' ? 'import' : 'baru') . ": {$kendaraan->no_polisi} ({$satkerName})"
            ]);
        }

        return back()->with('success', "Kendaraan {$kendaraan->no_polisi} berhasil disetujui!");
    }

    public function reject(Request $request, Kendaraan $kendaraan)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan wajib dicantumkan!',
            'alasan_penolakan.max' => 'Alasan penolakan maksimal 500 karakter.',
        ]);

        $aksi = $kendaraan->aksi_approval;
        $satkerName = $kendaraan->satker->nama_satker ?? 'Satker';
        $alasan = $request->input('alasan_penolakan');

        if ($aksi === 'edit') {
            // Untuk edit, data kendaraan dikembalikan ke data sebelumnya yang aktif,
            // namun mencatat alasan penolakan agar satker tahu perubahannya ditolak.
            $kendaraan->update([
                'status_approval' => 'ditolak',
                'alasan_penolakan' => $alasan,
                'disetujui_oleh' => auth()->id(),
                'disetujui_pada' => now(),
            ]);

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => "Menolak perubahan data kendaraan {$kendaraan->no_polisi} ({$satkerName}). Alasan: {$alasan}"
            ]);
        } else {
            // Untuk tambah atau import
            $kendaraan->update([
                'status_approval' => 'ditolak',
                'alasan_penolakan' => $alasan,
                'disetujui_oleh' => auth()->id(),
                'disetujui_pada' => now(),
            ]);

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => "Menolak pengajuan kendaraan " . ($aksi === 'import' ? 'import' : 'baru') . " {$kendaraan->no_polisi} ({$satkerName}). Alasan: {$alasan}"
            ]);
        }

        return back()->with('success', "Pengajuan kendaraan {$kendaraan->no_polisi} berhasil ditolak.");
    }

    public function bulkApprove(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:kendaraans,id',
        ], [
            'ids.required' => 'Pilih minimal satu kendaraan untuk disetujui.',
        ]);

        $kendaraans = Kendaraan::whereIn('id', $request->ids)->get();
        $count = 0;

        foreach ($kendaraans as $kendaraan) {
            if ($kendaraan->aksi_approval === 'edit' && !empty($kendaraan->pending_changes)) {
                $changes = is_array($kendaraan->pending_changes) 
                    ? $kendaraan->pending_changes 
                    : json_decode($kendaraan->pending_changes, true);

                $updateData = [];
                if (isset($changes['no_polisi'])) $updateData['no_polisi'] = $changes['no_polisi'];
                if (isset($changes['jenis_kendaraan'])) $updateData['jenis_kendaraan'] = $changes['jenis_kendaraan'];
                if (isset($changes['jenis_bbm'])) $updateData['jenis_bbm'] = $changes['jenis_bbm'];
                if (isset($changes['roda'])) $updateData['roda'] = $changes['roda'];
                if (isset($changes['cc'])) $updateData['cc'] = $changes['cc'];

                $updateData['pending_changes'] = null;
                $updateData['status_approval'] = 'disetujui';
                $updateData['disetujui_oleh'] = auth()->id();
                $updateData['disetujui_pada'] = now();
                $updateData['alasan_penolakan'] = null;

                $kendaraan->update($updateData);
            } else {
                $kendaraan->update([
                    'status_approval' => 'disetujui',
                    'disetujui_oleh' => auth()->id(),
                    'disetujui_pada' => now(),
                    'alasan_penolakan' => null,
                ]);
            }
            $count++;
        }

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menyetujui {$count} pengajuan kendaraan sekaligus."
        ]);

        return back()->with('success', "Berhasil menyetujui {$count} pengajuan kendaraan!");
    }

    public function bulkReject(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:kendaraans,id',
            'alasan_penolakan' => 'required|string|max:500',
        ], [
            'ids.required' => 'Pilih minimal satu kendaraan untuk ditolak.',
            'alasan_penolakan.required' => 'Alasan penolakan wajib dicantumkan!',
            'alasan_penolakan.max' => 'Alasan penolakan maksimal 500 karakter.',
        ]);

        $kendaraans = Kendaraan::whereIn('id', $request->ids)->get();
        $alasan = $request->input('alasan_penolakan');
        $count = 0;

        foreach ($kendaraans as $kendaraan) {
            $kendaraan->update([
                'status_approval' => 'ditolak',
                'alasan_penolakan' => $alasan,
                'disetujui_oleh' => auth()->id(),
                'disetujui_pada' => now(),
            ]);
            $count++;
        }

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menolak {$count} pengajuan kendaraan sekaligus. Alasan: {$alasan}"
        ]);

        return back()->with('success', "Berhasil menolak {$count} pengajuan kendaraan.");
    }
}
