<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\LogAktivitas;
use Carbon\Carbon;

class HapusDataTahunanController extends Controller
{
    public function index()
    {
        $currentYear = (int) date('Y');
        // Data yang boleh dihapus adalah 3 tahun sebelumnya
        $minYear = $currentYear - 2;
        // Kumpulkan tahun yang bisa dihapus (dari 3 tahun sebelumnya ke bawah)
        $availableYears = [];
        for ($y = $minYear; $y >= $minYear - 10; $y--) {
            $availableYears[] = $y;
        }

        // Hitung jumlah data per tahun untuk preview
        $yearStats = [];
        foreach ($availableYears as $year) {
            $startDate = Carbon::create($year, 1, 1, 0, 0, 0)->format('Y-m-d H:i:s');
            $endDate = Carbon::create($year, 12, 31, 23, 59, 59)->format('Y-m-d H:i:s');

            $stats = [
                'transaksi_bbm' => DB::table('transaksi_bbms')->whereYear('created_at', $year)->count(),
                'riwayat_topup' => DB::table('riwayat_topups')->whereYear('created_at', $year)->count(),
                'riwayat_stok_admin' => DB::table('riwayat_stok_admins')->whereYear('created_at', $year)->count(),
                'pembelian_bbm' => DB::table('pembelian_bbms')->whereYear('created_at', $year)->count(),
                'ba_logs' => DB::table('ba_logs')->where('tahun', $year)->count(),
                'rendis_bbm' => DB::table('rendis_bbms')->where('tahun', $year)->count(),
                'rendis_kendaraan' => DB::table('rendis_kendaraans')
                    ->whereIn('rendis_bbm_id', DB::table('rendis_bbms')->where('tahun', $year)->pluck('id'))
                    ->count(),
                'hutang' => DB::table('hutangs')->whereYear('created_at', $year)->count(),
                'sinkronisasi' => DB::table('sinkronisasi_bbms')->whereYear('created_at', $year)->count(),
                'sounding' => DB::table('soundings')->whereYear('created_at', $year)->count(),
                'daily_meter' => DB::table('daily_meter_readings')->whereYear('created_at', $year)->count(),
                'transfer_saldo' => DB::table('riwayat_transfer_saldo_personels')->whereYear('created_at', $year)->count(),
                'transfer_antar_personel' => DB::table('riwayat_transfer_antar_personels')->whereYear('created_at', $year)->count(),
                'log_aktivitas' => DB::table('log_aktivitas')->whereYear('created_at', $year)->count(),
                'satisfaction_index' => DB::table('satisfaction_indices')->whereYear('created_at', $year)->count(),
                'catatan' => DB::table('catatans')->whereYear('created_at', $year)->count(),
                'chat' => DB::table('chats')->whereYear('created_at', $year)->count(),
                'notifikasi' => DB::table('notifications')->whereYear('created_at', $year)->count(),
            ];

            $stats['total'] = array_sum($stats);

            // Hanya tampilkan tahun yang punya data
            if ($stats['total'] > 0) {
                $yearStats[$year] = $stats;
            }
        }

        return view('admin.hapus-data-tahunan.index', compact('availableYears', 'yearStats', 'currentYear'));
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer',
            'pin' => 'required|string',
            'konfirmasi' => 'required|in:HAPUS',
        ], [
            'tahun.required' => 'Tahun wajib dipilih.',
            'pin.required' => 'PIN Top Up wajib diisi.',
            'konfirmasi.required' => 'Ketik "HAPUS" untuk konfirmasi.',
            'konfirmasi.in' => 'Ketik "HAPUS" (huruf kapital) untuk konfirmasi.',
        ]);

        $tahun = (int) $request->tahun;
        $currentYear = (int) date('Y');
        $minAllowed = $currentYear - 2;

        // Validasi: hanya boleh hapus data dari (tahun sekarang - 2) ke bawah
        // Contoh: sekarang 2026, boleh hapus 2024 ke bawah. Tahun 2027, boleh hapus 2025 ke bawah.
        if ($tahun > $minAllowed) {
            return back()->with('error', "Tidak diizinkan menghapus data tahun {$tahun}. Hanya data tahun {$minAllowed} ke bawah yang boleh dihapus.");
        }

        // Validasi PIN
        $user = auth()->user();
        if (!$user->topup_password) {
            return back()->with('error', 'Anda belum mengatur PIN Top Up. Silakan atur di menu Profil.');
        }

        if (!Hash::check($request->pin, $user->topup_password)) {
            return back()->with('error', 'PIN Top Up salah! Penghapusan dibatalkan.');
        }

        try {
            DB::beginTransaction();

            $deletedCounts = [];

            // 1. Transaksi BBM (Laporan Rutin / Harian / Bulanan / Triwulan / Tahunan)
            $deletedCounts['Transaksi BBM'] = DB::table('transaksi_bbms')->whereYear('created_at', $tahun)->delete();

            // 2. Riwayat Topup (Laporan Top Up / Laporan Bulanan)
            $deletedCounts['Riwayat Top Up'] = DB::table('riwayat_topups')->whereYear('created_at', $tahun)->delete();

            // 3. Riwayat Stok Admin (Data BBM Pada Tangki / Riwayat BBM)
            $deletedCounts['Riwayat Stok Admin'] = DB::table('riwayat_stok_admins')->whereYear('created_at', $tahun)->delete();

            // 4. Pembelian BBM
            $deletedCounts['Pembelian BBM'] = DB::table('pembelian_bbms')->whereYear('created_at', $tahun)->delete();

            // 5. BA Logs (Berita Acara)
            $deletedCounts['Berita Acara'] = DB::table('ba_logs')->where('tahun', $tahun)->delete();

            // 6. Rendis Kendaraan (harus sebelum Rendis BBM karena foreign key)
            $rendisIds = DB::table('rendis_bbms')->where('tahun', $tahun)->pluck('id');
            $deletedCounts['Rendis Kendaraan'] = DB::table('rendis_kendaraans')->whereIn('rendis_bbm_id', $rendisIds)->delete();

            // 7. Rendis BBM
            $deletedCounts['Rendis BBM'] = DB::table('rendis_bbms')->where('tahun', $tahun)->delete();

            // 8. Hutang (Laporan Bayar Hutang)
            $deletedCounts['Hutang BBM'] = DB::table('hutangs')->whereYear('created_at', $tahun)->delete();

            // 9. Sinkronisasi BBM
            $deletedCounts['Sinkronisasi BBM'] = DB::table('sinkronisasi_bbms')->whereYear('created_at', $tahun)->delete();

            // 10. Sounding (Data BBM Pada Tangki)
            $deletedCounts['Sounding'] = DB::table('soundings')->whereYear('created_at', $tahun)->delete();

            // 11. Daily Meter Readings (Laporan Harian)
            $deletedCounts['Meter Harian'] = DB::table('daily_meter_readings')->whereYear('created_at', $tahun)->delete();

            // 12. Transfer Saldo Personel (Laporan Transfer Saldo)
            $deletedCounts['Transfer Saldo'] = DB::table('riwayat_transfer_saldo_personels')->whereYear('created_at', $tahun)->delete();

            // 13. Transfer Antar Personel (Saldo Yang di Alihkan)
            $deletedCounts['Transfer Antar Personel'] = DB::table('riwayat_transfer_antar_personels')->whereYear('created_at', $tahun)->delete();

            // 14. Log Aktivitas
            $deletedCounts['Log Aktivitas'] = DB::table('log_aktivitas')->whereYear('created_at', $tahun)->delete();

            // 15. Satisfaction Index (Indeks Kepuasan)
            $deletedCounts['Indeks Kepuasan'] = DB::table('satisfaction_indices')->whereYear('created_at', $tahun)->delete();

            // 16. Catatan
            $deletedCounts['Catatan'] = DB::table('catatans')->whereYear('created_at', $tahun)->delete();

            // 17. Chat / Konsultasi
            $deletedCounts['Chat'] = DB::table('chats')->whereYear('created_at', $tahun)->delete();

            // 18. Notifikasi
            $deletedCounts['Notifikasi'] = DB::table('notifications')->whereYear('created_at', $tahun)->delete();

            DB::commit();

            // Catat aktivitas (setelah commit, karena log untuk tahun ini sudah dihapus)
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => "Menghapus seluruh data tahun {$tahun}. Detail: " . collect($deletedCounts)->map(fn($v, $k) => "{$k}: {$v}")->implode(', '),
            ]);

            $totalDeleted = array_sum($deletedCounts);
            $summary = collect($deletedCounts)->filter(fn($v) => $v > 0)->map(fn($v, $k) => "{$k}: {$v} data")->implode(', ');

            Log::info("Data tahunan {$tahun} dihapus oleh User #{$user->id}. Total: {$totalDeleted}. Detail: {$summary}");

            return back()->with('success', "Berhasil menghapus {$totalDeleted} data tahun {$tahun}. ({$summary})");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal hapus data tahunan: " . $e->getMessage());
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
