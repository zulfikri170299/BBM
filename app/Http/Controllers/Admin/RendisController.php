<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\RendisBbm;
use App\Models\RendisKendaraan;
use App\Models\Satker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RendisController extends Controller
{
    public function index(Request $request)
    {
        $rendisList = RendisBbm::orderBy('created_at', 'desc')->get();
        return view('admin.rendis.index', compact('rendisList'));
    }

    public function create()
    {
        $satkers = Satker::orderBy('nama_satker')->get();
        $kendaraans = Kendaraan::with('satker')->orderBy('satker_id')->get();
        $kendaraansBySatker = $kendaraans->groupBy('satker_id');

        return view('admin.rendis.create', compact('satkers', 'kendaraans', 'kendaraansBySatker'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'triwulan' => 'required|string|in:TW I,TW II,TW III,TW IV',
            'tahun' => 'required|string',
            'pembelian_pertamax' => 'required|numeric|min:0',
            'pembelian_pertamina_dex' => 'required|numeric|min:0',
            'susut_persen' => 'nullable|numeric|min:0|max:100',
            'bulan1_hari_operasional' => 'required|integer|min:0',
            'bulan1_hari_staff' => 'required|integer|min:0',
            'bulan1_hari_pimpinan' => 'required|integer|min:0',
            'bulan2_hari_operasional' => 'required|integer|min:0',
            'bulan2_hari_staff' => 'required|integer|min:0',
            'bulan2_hari_pimpinan' => 'required|integer|min:0',
            'bulan3_hari_operasional' => 'required|integer|min:0',
            'bulan3_hari_staff' => 'required|integer|min:0',
            'bulan3_hari_pimpinan' => 'required|integer|min:0',
            'kendaraan' => 'required|array',
        ]);

        $rendis = DB::transaction(function () use ($request) {
            $rendis = RendisBbm::create([
                'satker_id' => auth()->user()->satker_id,
                'triwulan' => $request->triwulan,
                'tahun' => $request->tahun,
                'pembelian_pertamax' => $request->pembelian_pertamax,
                'pembelian_pertamina_dex' => $request->pembelian_pertamina_dex,
                'susut_persen' => $request->susut_persen ?? 1.5,
                'bulan1_hari_operasional' => $request->bulan1_hari_operasional,
                'bulan1_hari_staff' => $request->bulan1_hari_staff,
                'bulan1_hari_pimpinan' => $request->bulan1_hari_pimpinan,
                'bulan2_hari_operasional' => $request->bulan2_hari_operasional,
                'bulan2_hari_staff' => $request->bulan2_hari_staff,
                'bulan2_hari_pimpinan' => $request->bulan2_hari_pimpinan,
                'bulan3_hari_operasional' => $request->bulan3_hari_operasional,
                'bulan3_hari_staff' => $request->bulan3_hari_staff,
                'bulan3_hari_pimpinan' => $request->bulan3_hari_pimpinan,
            ]);

            foreach ($request->kendaraan as $kId => $data) {
                $kendaraan = Kendaraan::find($kId);
                $literPerHari = floatval($data['liter_per_hari'] ?? 0);
                $literPerHariB2 = floatval($data['liter_per_hari_b2'] ?? $literPerHari);
                $literPerHariB3 = floatval($data['liter_per_hari_b3'] ?? $literPerHari);
                $bulan1Total = floatval($data['bulan1_total'] ?? 0);
                $bulan2Total = floatval($data['bulan2_total'] ?? 0);
                $bulan3Total = floatval($data['bulan3_total'] ?? 0);
                // Jenis BBM otomatis dari master kendaraan
                $jenisBbm = strtolower(str_replace(' ', '_', $kendaraan->jenis_bbm ?? 'pertamax'));

                RendisKendaraan::create([
                    'rendis_bbm_id' => $rendis->id,
                    'kendaraan_id' => $kId,
                    'uraian' => $data['uraian'] ?? $kendaraan->kategori_kendaraan ?? 'Operasional',
                    'liter_per_hari' => $literPerHari,
                    'liter_per_hari_b2' => $literPerHariB2,
                    'liter_per_hari_b3' => $literPerHariB3,
                    'bulan1_total' => $bulan1Total,
                    'bulan2_total' => $bulan2Total,
                    'bulan3_total' => $bulan3Total,
                    'total_liter' => $bulan1Total + $bulan2Total + $bulan3Total,
                    'jenis_bbm' => $jenisBbm,
                ]);
            }
            return $rendis;
        });

        return redirect()->route('admin.rendis.show', $rendis->id)->with('success', 'Rendis berhasil dibuat.');
    }

    public function show(RendisBbm $rendi)
    {
        $rendisBbm = $rendi;
        $rendisBbm->load('rendisKendaraans.kendaraan.satker');
        $kendaraansBySatker = $rendisBbm->rendisKendaraans->groupBy(function ($rk) {
            return $rk->kendaraan->satker_id ?? 0;
        });
        $satkers = Satker::all()->keyBy('id');
        return view('admin.rendis.show', compact('rendisBbm', 'kendaraansBySatker', 'satkers'));
    }

    public function edit(RendisBbm $rendi)
    {
        $rendisBbm = $rendi;
        
        if (!session('verified_edit_rendis_' . $rendisBbm->id)) {
            return redirect()->route('admin.rendis.index')->with('error', 'Sesi edit tidak valid atau sudah kedaluwarsa. Silakan masukkan PIN kembali.');
        }

        $rendisBbm->load('rendisKendaraans.kendaraan');

        $satkers = Satker::orderBy('nama_satker')->get();
        $kendaraans = Kendaraan::with('satker')->orderBy('satker_id')->get();
        $kendaraansBySatker = $kendaraans->groupBy('satker_id');

        $existingRendisKendaraans = $rendisBbm->rendisKendaraans->keyBy('kendaraan_id');

        return view('admin.rendis.edit', compact('rendisBbm', 'satkers', 'kendaraans', 'kendaraansBySatker', 'existingRendisKendaraans'));
    }

    public function update(Request $request, RendisBbm $rendi)
    {
        $rendisBbm = $rendi;
        if ($rendisBbm->is_topup_b1 && $rendisBbm->is_topup_b2 && $rendisBbm->is_topup_b3) {
            return back()->with('error', 'Rendis sudah dieksekusi seluruhnya, tidak bisa diubah.');
        }

        $request->validate([
            'triwulan' => 'required|string|in:TW I,TW II,TW III,TW IV',
            'tahun' => 'required|string',
            'pembelian_pertamax' => 'required|numeric|min:0',
            'pembelian_pertamina_dex' => 'required|numeric|min:0',
            'kendaraan' => 'required|array',
        ]);

        DB::transaction(function () use ($request, $rendisBbm) {
            $rendisBbm->update([
                'triwulan' => $request->triwulan,
                'tahun' => $request->tahun,
                'pembelian_pertamax' => $request->pembelian_pertamax,
                'pembelian_pertamina_dex' => $request->pembelian_pertamina_dex,
                'susut_persen' => $request->susut_persen ?? 1.5,
                'bulan1_hari_operasional' => $request->bulan1_hari_operasional,
                'bulan1_hari_staff' => $request->bulan1_hari_staff,
                'bulan1_hari_pimpinan' => $request->bulan1_hari_pimpinan,
                'bulan2_hari_operasional' => $request->bulan2_hari_operasional,
                'bulan2_hari_staff' => $request->bulan2_hari_staff,
                'bulan2_hari_pimpinan' => $request->bulan2_hari_pimpinan,
                'bulan3_hari_operasional' => $request->bulan3_hari_operasional,
                'bulan3_hari_staff' => $request->bulan3_hari_staff,
                'bulan3_hari_pimpinan' => $request->bulan3_hari_pimpinan,
            ]);

            $rendisBbm->rendisKendaraans()->delete();

            foreach ($request->kendaraan as $kId => $data) {
                $kendaraan = Kendaraan::find($kId);
                $literPerHari = floatval($data['liter_per_hari'] ?? 0);
                $literPerHariB2 = floatval($data['liter_per_hari_b2'] ?? $literPerHari);
                $literPerHariB3 = floatval($data['liter_per_hari_b3'] ?? $literPerHari);
                $bulan1Total = floatval($data['bulan1_total'] ?? 0);
                $bulan2Total = floatval($data['bulan2_total'] ?? 0);
                $bulan3Total = floatval($data['bulan3_total'] ?? 0);
                $jenisBbm = strtolower(str_replace(' ', '_', $kendaraan->jenis_bbm ?? 'pertamax'));

                RendisKendaraan::create([
                    'rendis_bbm_id' => $rendisBbm->id,
                    'kendaraan_id' => $kId,
                    'uraian' => $data['uraian'] ?? $kendaraan->kategori_kendaraan ?? 'Operasional',
                    'liter_per_hari' => $literPerHari,
                    'liter_per_hari_b2' => $literPerHariB2,
                    'liter_per_hari_b3' => $literPerHariB3,
                    'bulan1_total' => $bulan1Total,
                    'bulan2_total' => $bulan2Total,
                    'bulan3_total' => $bulan3Total,
                    'total_liter' => $bulan1Total + $bulan2Total + $bulan3Total,
                    'jenis_bbm' => $jenisBbm,
                ]);
            }
        });

        return redirect()->route('admin.rendis.show', $rendisBbm->id)->with('success', 'Rendis berhasil diperbarui.');
    }

    public function destroy(RendisBbm $rendi)
    {
        $rendisBbm = $rendi;
        if ($rendisBbm->is_topup_b1 || $rendisBbm->is_topup_b2 || $rendisBbm->is_topup_b3) {
            return back()->with('error', 'Rendis sudah mulai dieksekusi, tidak bisa dihapus.');
        }
        $rendisBbm->delete();
        return redirect()->route('admin.rendis.index')->with('success', 'Rendis berhasil dihapus.');
    }

    public function verifyEdit(Request $request, RendisBbm $rendisBbm)
    {
        $request->validate([
            'topup_password' => 'required|string',
        ], [
            'topup_password.required' => 'Password Top Up wajib diisi.',
        ]);

        $user = auth()->user();
        if (!$user->topup_password) {
            return back()->with('error', 'Anda belum mengatur Password Top Up. Silakan atur di Profil Anda.');
        }

        if (!\Illuminate\Support\Facades\Hash::check($request->topup_password, $user->topup_password)) {
            return back()->with('error', 'Password Top Up salah.');
        }

        session(['verified_edit_rendis_' . $rendisBbm->id => true]);

        return redirect()->route('admin.rendis.edit', $rendisBbm->id);
    }

    public function executeTopup(Request $request, RendisBbm $rendisBbm)
    {
        $request->validate([
            'topup_password' => 'required|string',
        ], [
            'topup_password.required' => 'Password Top Up wajib diisi.',
        ]);

        $user = auth()->user();
        if (!$user->topup_password) {
            return back()->with('error', 'Anda belum mengatur Password Top Up. Silakan atur di Profil Anda.');
        }

        if (!\Illuminate\Support\Facades\Hash::check($request->topup_password, $user->topup_password)) {
            return back()->with('error', 'Password Top Up salah.');
        }

        $bulan = $request->query('bulan');
        
        if (!in_array($bulan, ['1', '2', '3'])) {
            return back()->with('error', 'Bulan tidak valid.');
        }

        $colName = 'is_topup_b' . $bulan;

        if ($rendisBbm->$colName) {
            return back()->with('error', 'Top Up untuk bulan ini sudah dieksekusi sebelumnya.');
        }

        DB::transaction(function () use ($rendisBbm, $bulan, $colName) {
            $kendaraans = $rendisBbm->rendisKendaraans()->with('kendaraan')->get();

            foreach ($kendaraans as $rk) {
                $kendaraan = $rk->kendaraan;
                $jumlahTopup = 0;
                
                if ($bulan === '1') $jumlahTopup = $rk->bulan1_total;
                elseif ($bulan === '2') $jumlahTopup = $rk->bulan2_total;
                elseif ($bulan === '3') $jumlahTopup = $rk->bulan3_total;

                if ($jumlahTopup > 0) {
                    $kendaraan->saldo += $jumlahTopup;
                    $kendaraan->save();

                    \App\Models\RiwayatTopup::create([
                        'kendaraan_id' => $kendaraan->id,
                        'jumlah' => $jumlahTopup,
                        'satker_id' => $kendaraan->satker_id,
                        'tipe' => 'kendaraan',
                        'metode' => 'RENDIS',
                        'jenis_bbm' => $kendaraan->jenis_bbm ?? 'Pertamax',
                        'keterangan' => 'Top Up Rendis ' . $rendisBbm->triwulan . ' ' . $rendisBbm->tahun . ' Bulan ' . $bulan,
                        'status' => 'success',
                        'user_id' => auth()->id(),
                    ]);
                }
            }

            $rendisBbm->update([$colName => true]);
        });

        return back()->with('success', 'Top Up Massal Bulan ' . $bulan . ' berhasil dieksekusi!');
    }

    public function printPdf(RendisBbm $rendisBbm)
    {
        $rendisBbm->load('rendisKendaraans.kendaraan.satker');
        $kendaraansBySatker = $rendisBbm->rendisKendaraans->groupBy(function ($rk) {
            return $rk->kendaraan->satker_id ?? 0;
        });
        $satkers = Satker::all()->keyBy('id');
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        $pdf = \PDF::loadView('admin.rendis.pdf', compact('rendisBbm', 'kendaraansBySatker', 'satkers', 'settings'))
            ->setPaper('legal', 'landscape');
        return $pdf->stream('Rendis_BBM_' . $rendisBbm->triwulan . '_' . $rendisBbm->tahun . '.pdf');
    }

    public function printExcel(RendisBbm $rendisBbm)
    {
        $fileName = 'Rendis_BBM_' . $rendisBbm->triwulan . '_' . $rendisBbm->tahun . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\RendisExport($rendisBbm), $fileName);
    }
}
