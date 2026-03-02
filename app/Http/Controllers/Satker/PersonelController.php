<?php

namespace App\Http\Controllers\Satker;

use App\Http\Controllers\Controller;
use App\Models\Personel;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Traits\PaginatesTables;

class PersonelController extends Controller
{
    use PaginatesTables;

    public function index(Request $request)
    {
        $personels = Personel::where('satker_id', auth()->user()->satker_id)
            ->when(request('search'), function ($query) {
                $query->where(function ($q) {
                    $q->where('nama', 'like', '%' . request('search') . '%')
                      ->orWhere('nrp', 'like', '%' . request('search') . '%');
                });
            })
            ->latest()
            ->paginate($this->getPerPage($request));
        
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
            'jenis_bbm' => 'required|string',
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
            'jenis_bbm' => $request->jenis_bbm,
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

        if ($personel->saldo > 0) {
            return redirect()->route('satker.personels.index')->with('error', 'Tidak dapat mengedit personel "' . $personel->nama . '" karena masih memiliki saldo ' . number_format($personel->saldo, 0, ',', '.') . ' L.');
        }

        return view('satker.personels.edit', compact('personel'));
    }

    public function update(Request $request, Personel $personel)
    {
        if ($personel->satker_id !== auth()->user()->satker_id) {
            abort(403);
        }

        if ($personel->saldo > 0) {
            return redirect()->route('satker.personels.index')->with('error', 'Tidak dapat memperbarui personel "' . $personel->nama . '" karena masih memiliki saldo ' . number_format($personel->saldo, 0, ',', '.') . ' L.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'nrp' => ['required', 'string', 'max:20', 
                Rule::unique('personels')->where(function ($query) {
                    return $query->where('satker_id', auth()->user()->satker_id);
                })->ignore($personel->id)
            ],
            'jenis_bbm' => 'nullable|string',
        ]);

        $data = $request->only(['nama', 'nrp']);

        // Hanya update jenis_bbm jika saldo == 0
        if ($personel->saldo <= 0) {
            $data['jenis_bbm'] = $request->jenis_bbm;
        }

        $personel->update($data);

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

    public function previewImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ], [
            'file.required' => 'File Excel harus dipilih.',
            'file.mimes' => 'File harus berformat .xlsx, .xls, atau .csv.',
            'file.max' => 'Ukuran file maksimal 2MB.',
        ]);

        $satkerId = auth()->user()->satker_id;

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
                $jenisBbm = isset($colMap['jenis_bbm']) ? trim((string) $sheet->getCell($colMap['jenis_bbm'] . $r)->getValue()) : 'Pertamax';

                if (empty($nrp) && empty($nama)) continue;

                if (empty($nrp)) { $errors[] = "Baris {$r}: NRP kosong."; continue; }
                if (empty($nama)) { $errors[] = "Baris {$r}: Nama kosong."; continue; }

                // Satker tidak perlu dari Excel - otomatis dari user yang login
                $currentUserSatkerName = auth()->user()->satker->nama_satker ?? '';

                // Check duplicate
                $existing = Personel::where('nrp', $nrp)->where('satker_id', auth()->user()->satker_id)->first();
                if ($existing) {
                    $changes = [];
                    if ($existing->nama !== $nama) {
                        $changes[] = ['field' => 'Nama', 'old' => $existing->nama, 'new' => $nama];
                    }
                    if ($existing->jenis_bbm !== $jenisBbm) {
                        $changes[] = ['field' => 'Jenis BBM', 'old' => $existing->jenis_bbm, 'new' => $jenisBbm];
                    }
                    $duplicates[] = [
                        'row' => $r, 'nrp' => $nrp, 'nama' => $nama, 'satker_name' => $currentUserSatkerName,
                        'jenis_bbm' => $jenisBbm,
                        'changes' => $changes, 'has_changes' => count($changes) > 0
                    ];
                } else {
                    $newEntries[] = [
                        'row' => $r, 'nrp' => $nrp, 'nama' => $nama, 'satker_id' => $satkerId,
                        'satker_name' => $currentUserSatkerName, 'jenis_bbm' => $jenisBbm
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
        $satkerId = auth()->user()->satker_id;

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
                elseif (in_array($val, ['jenis bbm', 'bbm', 'jenis_bbm', 'tipe bbm'])) { $colMap['jenis_bbm'] = $col; }
            }

            $successCount = 0; $updatedCount = 0; $skippedCount = 0;

            for ($r = $headerRow + 1; $r <= $highestRow; $r++) {
                $nrp = isset($colMap['nrp']) ? trim((string) $sheet->getCell($colMap['nrp'] . $r)->getValue()) : '';
                $nama = isset($colMap['nama']) ? trim((string) $sheet->getCell($colMap['nama'] . $r)->getValue()) : '';
                $jenisBbm = isset($colMap['jenis_bbm']) ? trim((string) $sheet->getCell($colMap['jenis_bbm'] . $r)->getValue()) : 'Pertamax';

                if (empty($nrp) || empty($nama)) continue;

                $existing = Personel::where('nrp', $nrp)->where('satker_id', auth()->user()->satker_id)->first();
                if ($existing) {
                    if ($duplicateAction === 'skip') { $skippedCount++; continue; }
                    
                    $existing->update([
                        'nama' => $nama, 'satker_id' => $satkerId, 'jenis_bbm' => $jenisBbm
                    ]);
                    if ($existing->user) { $existing->user->update(['name' => $nama]); }
                    $updatedCount++;
                } else {
                    $user = \App\Models\User::updateOrCreate(['username' => $nrp], [
                        'name' => $nama, 'email' => $nrp, 'password' => \Illuminate\Support\Facades\Hash::make($nrp),
                        'role' => 'personel', 'satker_id' => $satkerId,
                    ]);
                    Personel::create([
                        'nrp' => $nrp, 'nama' => $nama, 'satker_id' => $satkerId, 'user_id' => $user->id,
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

    public function print(Personel $personel)
    {
        if ($personel->satker_id !== auth()->user()->satker_id) {
            abort(403);
        }

        return view('satker.personels.card', compact('personel'));
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:personels,id',
        ]);

        $satkerId = auth()->user()->satker_id;
        $skipped = Personel::where('satker_id', $satkerId)
            ->whereIn('id', $request->ids)
            ->where('saldo', '>', 0)
            ->count();

        $deleted = Personel::where('satker_id', $satkerId)
            ->whereIn('id', $request->ids)
            ->where('saldo', '<=', 0)
            ->delete();

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menghapus {$deleted} personel secara massal",
        ]);

        $msg = "{$deleted} personel berhasil dihapus.";
        if ($skipped > 0) {
            $msg .= " {$skipped} personel dilewati karena masih memiliki saldo.";
        }

        return redirect()->route('satker.personels.index')->with('success', $msg);
    }

    public function downloadTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PersonelTemplateExport(false), 'template_import_personel.xlsx');
    }

    public function export()
    {
        $satkerId = auth()->user()->satker_id;
        $satkerName = auth()->user()->satker->nama ?? 'Satker';
        $filename = 'data_personel_' . strtolower(str_replace(' ', '_', $satkerName)) . '_' . date('Ymd_His') . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\SatkerPersonelExport($satkerId), $filename);
    }
}
