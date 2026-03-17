<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Personel;
use Illuminate\Http\Request;

use App\Traits\PaginatesTables;

class PersonelController extends Controller
{
    use PaginatesTables;

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

        $perPage = $this->getPerPage($request);
        $personels = $query->latest()->paginate($perPage)->withQueryString();
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
            'nrp' => 'required|regex:/^[0-9]+$/|unique:personels,nrp',
            'jenis_bbm' => 'required|in:Pertamax,Pertamina Dex',
            'saldo' => 'required|numeric|min:0',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->nama,
            'username' => $request->nrp,
            'email' => $request->nrp,
            'password' => \Illuminate\Support\Facades\Hash::make($request->nrp),
            'role' => 'personel',
            'satker_id' => $request->satker_id,
        ]);

        Personel::create([
            'nama' => $request->nama,
            'nrp' => $request->nrp,
            'satker_id' => $request->satker_id,
            'user_id' => $user->id,
            'saldo' => $request->saldo ?? 0,
            'jenis_bbm' => $request->jenis_bbm,
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
        if (auth()->user()->role !== 'super_admin' && $personel->saldo > 0) {
            return redirect()->route('admin.personels.index')->with('error', 'Tidak dapat mengedit personel "' . $personel->nama . '" karena masih memiliki saldo ' . number_format($personel->saldo, 0, ',', '.') . ' L.');
        }

        $satkers = \App\Models\Satker::orderBy('nama_satker')->get();
        return view('admin.personels.edit', compact('personel', 'satkers'));
    }

    public function update(Request $request, Personel $personel)
    {
        if (auth()->user()->role !== 'super_admin' && $personel->saldo > 0) {
            return redirect()->route('admin.personels.index')->with('error', 'Tidak dapat memperbarui personel "' . $personel->nama . '" karena masih memiliki saldo ' . number_format($personel->saldo, 0, ',', '.') . ' L.');
        }

        $request->validate([
            'satker_id' => 'required|exists:satkers,id',
            'nama' => 'required|string|max:255',
            'nrp' => 'required|regex:/^[0-9]+$/|unique:personels,nrp,' . $personel->id,
            'jenis_bbm' => 'required|in:Pertamax,Pertamina Dex',
            'saldo' => 'required|numeric|min:0',
        ]);

        $data = $request->only(['satker_id', 'nama', 'nrp', 'saldo']);
        
        // Hanya update jenis_bbm jika saldo == 0 (Kecuali Super Admin)
        if (auth()->user()->role === 'super_admin' || $personel->saldo <= 0) {
            $data['jenis_bbm'] = $request->jenis_bbm;
        }

        $personel->update($data);

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

    public function previewImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ], [
            'file.required' => 'File Excel harus dipilih.',
            'file.mimes' => 'File harus berformat .xlsx, .xls, atau .csv.',
            'file.max' => 'Ukuran file maksimal 2MB.',
        ]);

        try {
            $filePath = $request->file('file')->getRealPath();
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestCol = $sheet->getHighestColumn();

            // Auto-detect header row
            $headerRow = null;
            for ($r = 1; $r <= min(5, $highestRow); $r++) {
                foreach (range('A', $highestCol) as $col) {
                    $val = strtolower(trim((string) $sheet->getCell($col . $r)->getValue()));
                    if (in_array($val, ['nrp', 'nrp/nip', 'nrp_nip', 'nip'])) {
                        $headerRow = $r;
                        break 2;
                    }
                }
            }

            if (!$headerRow) {
                return response()->json([
                    'success' => false,
                    'message' => 'Header NRP tidak ditemukan dalam file.',
                ], 422);
            }

            // Build column map
            $colMap = [];
            foreach (range('A', $highestCol) as $col) {
                $val = strtolower(trim((string) $sheet->getCell($col . $headerRow)->getValue()));
                if (in_array($val, ['nrp', 'nrp/nip', 'nrp_nip', 'nip'])) {
                    $colMap['nrp'] = $col;
                } elseif (in_array($val, ['nama', 'nama lengkap', 'nama_lengkap', 'personel'])) {
                    $colMap['nama'] = $col;
                } elseif (in_array($val, ['satker', 'satuan kerja', 'satuan_kerja', 'nama_satker'])) {
                    $colMap['satker'] = $col;
                } elseif (in_array($val, ['jenis bbm', 'bbm', 'jenis_bbm', 'tipe bbm'])) {
                    $colMap['jenis_bbm'] = $col;
                }
            }

            $newEntries = [];
            $duplicates = [];
            $errors = [];
            $successCount = 0;

            for ($r = $headerRow + 1; $r <= $highestRow; $r++) {
                $nrp = isset($colMap['nrp']) ? trim((string) $sheet->getCell($colMap['nrp'] . $r)->getValue()) : '';
                $nama = isset($colMap['nama']) ? trim((string) $sheet->getCell($colMap['nama'] . $r)->getValue()) : '';
                $namaSatker = isset($colMap['satker']) ? trim((string) $sheet->getCell($colMap['satker'] . $r)->getValue()) : '';
                $jenisBbm = isset($colMap['jenis_bbm']) ? trim((string) $sheet->getCell($colMap['jenis_bbm'] . $r)->getValue()) : 'Pertamax';

                if (empty($nrp) && empty($nama)) continue;

                if (empty($nrp)) { $errors[] = "Baris {$r}: NRP kosong."; continue; }
                if (empty($nama)) { $errors[] = "Baris {$r}: Nama kosong."; continue; }
                if (empty($namaSatker)) { $errors[] = "Baris {$r}: Satker kosong."; continue; }

                // Find Satker
                $satker = \App\Models\Satker::where('nama_satker', 'like', "%{$namaSatker}%")->first();
                if (!$satker) { $errors[] = "Baris {$r}: Satker '{$namaSatker}' tidak ditemukan."; continue; }

                // Find Existing
                $existing = Personel::where('nrp', $nrp)->first();
                if ($existing) {
                    $changes = [];
                    if ($existing->nama !== $nama) {
                        $changes[] = ['field' => 'Nama', 'old' => $existing->nama, 'new' => $nama];
                    }
                    if ($existing->satker_id != $satker->id) {
                        $changes[] = ['field' => 'Satker', 'old' => $existing->satker->nama_satker ?? '-', 'new' => $satker->nama_satker];
                    }
                    if ($existing->jenis_bbm !== $jenisBbm) {
                        $changes[] = ['field' => 'Jenis BBM', 'old' => $existing->jenis_bbm, 'new' => $jenisBbm];
                    }
                    $duplicates[] = [
                        'row' => $r, 'nrp' => $nrp, 'nama' => $nama, 'satker_name' => $satker->nama_satker,
                        'jenis_bbm' => $jenisBbm,
                        'changes' => $changes, 'has_changes' => count($changes) > 0
                    ];
                } else {
                    $newEntries[] = [
                        'row' => $r, 'nrp' => $nrp, 'nama' => $nama, 'satker_id' => $satker->id, 
                        'satker_name' => $satker->nama_satker, 'jenis_bbm' => $jenisBbm
                    ];
                    $successCount++;
                }
            }

            return response()->json([
                'success' => true,
                'new_count' => $successCount,
                'duplicate_count' => count($duplicates),
                'error_count' => count($errors),
                'new_entries' => $newEntries,
                'duplicates' => $duplicates,
                'errors' => $errors,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses file: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
            'duplicate_action' => 'required|in:skip,update',
        ]);

        $duplicateAction = $request->input('duplicate_action', 'skip');

        try {
            $filePath = $request->file('file')->getRealPath();
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestCol = $sheet->getHighestColumn();

            // Use same detection as fallback
            $headerRow = 1;
            for ($r = 1; $r <= min(5, $highestRow); $r++) {
                foreach (range('A', $highestCol) as $col) {
                    $val = strtolower(trim((string) $sheet->getCell($col . $r)->getValue()));
                    if (in_array($val, ['nrp', 'nrp/nip', 'nrp_nip', 'nip'])) { $headerRow = $r; break 2; }
                }
            }

            $colMap = [];
            foreach (range('A', $highestCol) as $col) {
                $val = strtolower(trim((string) $sheet->getCell($col . $headerRow)->getValue()));
                if (in_array($val, ['nrp', 'nrp/nip', 'nrp_nip', 'nip'])) { $colMap['nrp'] = $col; }
                elseif (in_array($val, ['nama', 'nama lengkap', 'nama_lengkap', 'personel'])) { $colMap['nama'] = $col; }
                elseif (in_array($val, ['satker', 'satuan kerja', 'satuan_kerja', 'nama_satker'])) { $colMap['satker'] = $col; }
                elseif (in_array($val, ['jenis bbm', 'bbm', 'jenis_bbm', 'tipe bbm'])) { $colMap['jenis_bbm'] = $col; }
            }

            $successCount = 0; $updatedCount = 0; $skippedCount = 0;

            for ($r = $headerRow + 1; $r <= $highestRow; $r++) {
                $nrp = isset($colMap['nrp']) ? trim((string) $sheet->getCell($colMap['nrp'] . $r)->getValue()) : '';
                $nama = isset($colMap['nama']) ? trim((string) $sheet->getCell($colMap['nama'] . $r)->getValue()) : '';
                $namaSatker = isset($colMap['satker']) ? trim((string) $sheet->getCell($colMap['satker'] . $r)->getValue()) : '';
                $jenisBbm = isset($colMap['jenis_bbm']) ? trim((string) $sheet->getCell($colMap['jenis_bbm'] . $r)->getValue()) : 'Pertamax';

                if (empty($nrp) || empty($nama) || empty($namaSatker)) continue;

                $satker = \App\Models\Satker::where('nama_satker', 'like', "%{$namaSatker}%")->first();
                if (!$satker) continue;

                $existing = Personel::where('nrp', $nrp)->first();
                if ($existing) {
                    if ($duplicateAction === 'skip') { $skippedCount++; continue; }
                    
                    $existing->update([
                        'nama' => $nama, 'satker_id' => $satker->id, 'jenis_bbm' => $jenisBbm
                    ]);
                    // Update user account too
                    if ($existing->user) { $existing->user->update(['name' => $nama, 'satker_id' => $satker->id]); }
                    $updatedCount++;
                } else {
                    $user = \App\Models\User::updateOrCreate(['username' => $nrp], [
                        'name' => $nama, 'email' => $nrp, 'password' => \Illuminate\Support\Facades\Hash::make($nrp),
                        'role' => 'personel', 'satker_id' => $satker->id,
                    ]);
                    Personel::create([
                        'nrp' => $nrp, 'nama' => $nama, 'satker_id' => $satker->id, 'user_id' => $user->id,
                        'jenis_bbm' => $jenisBbm, 'pin' => Personel::generateUniquePin(), 'barcode' => $nrp
                    ]);
                    $successCount++;
                }
            }

            $msg = "Import berhasil. {$successCount} data baru ditambahkan.";
            if ($updatedCount > 0) $msg .= " {$updatedCount} data diperbarui.";
            if ($skippedCount > 0) $msg .= " {$skippedCount} data duplikat dilewati.";

            return back()->with('success', $msg);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PersonelTemplateExport(true), 'template_import_personel.xlsx');
    }
    public function print(Personel $personel)
    {
        return view('satker.personels.card', compact('personel'));
    }

    public function destroy(Personel $personel)
    {
        if (auth()->user()->role !== 'super_admin' && $personel->saldo > 0) {
            return redirect()->route('admin.personels.index')->with('error', 'Tidak dapat menghapus personel "' . $personel->nama . '" karena masih memiliki saldo ' . number_format($personel->saldo, 0, ',', '.') . ' L.');
        }

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

        $personels = Personel::whereIn('id', $request->ids)->get();
        $deleted = 0;
        $skipped = 0;

        foreach ($personels as $personel) {
            if (auth()->user()->role !== 'super_admin' && $personel->saldo > 0) {
                $skipped++;
                continue;
            }
            $personel->delete();
            $deleted++;
        }

        if ($deleted > 0) {
            \App\Models\LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => "Menghapus {$deleted} Personel secara massal"
            ]);
        }

        $message = "{$deleted} personel berhasil dihapus.";
        if ($skipped > 0) {
            $message .= " {$skipped} personel dilewati karena masih memiliki saldo.";
        }

        return back()->with($skipped > 0 ? 'warning' : 'success', $message);
    }
}
