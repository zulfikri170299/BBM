<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\TopupNotification;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super_admin')->only(['topup', 'processTopup']);
    }

    public function index()
    {
        $stats = [
            'totalSatker' => \App\Models\Satker::count(),
            'totalUsers' => \App\Models\User::count(),
            'totalKendaraan' => \App\Models\Kendaraan::count(),
            'totalTransaksi' => \App\Models\TransaksiBbm::count(),
            'totalPersonel' => \App\Models\Personel::count(),
            'totalLiter' => \App\Models\TransaksiBbm::sum('liter'),
            'totalHutangPertamax' => \App\Models\Hutang::where('status', 'belum_dibayar')->where('jenis_bbm', 'Pertamax')->sum('jumlah_bon'),
            'totalHutangDex' => \App\Models\Hutang::where('status', 'belum_dibayar')->where('jenis_bbm', 'Pertamina Dex')->sum('jumlah_bon'),
        ];

        $recentTransactions = \App\Models\TransaksiBbm::with(['kendaraan', 'petugas'])
            ->orderBy('created_at', 'desc')
            ->take(7)
            ->get();

        // Saldo Kendaraan per Jenis BBM
        $kendaraanFuel = \App\Models\Kendaraan::select('jenis_bbm', \Illuminate\Support\Facades\DB::raw('SUM(saldo) as total'))
            ->groupBy('jenis_bbm')
            ->get();

        // Saldo Personel per Jenis BBM
        $personelFuel = \App\Models\Personel::select('jenis_bbm', \Illuminate\Support\Facades\DB::raw('SUM(saldo) as total'))
            ->groupBy('jenis_bbm')
            ->get();

        // Get Users with Location
        $usersWithLocation = \App\Models\User::whereNotNull('last_latitude')
            ->whereNotNull('last_longitude')
            ->select('id', 'name', 'role', 'last_latitude', 'last_longitude', 'last_activity_at')
            ->get();

        // Satisfaction Stats
        $sangatPuasCount = \App\Models\SatisfactionIndex::where('rating', '=', '3')->count();
        $puasCount = \App\Models\SatisfactionIndex::where('rating', '=', '2')->count();
        $tidakPuasCount = \App\Models\SatisfactionIndex::where('rating', '=', '1')->count();
        $totalSatisfaction = $sangatPuasCount + $puasCount + $tidakPuasCount;

        $satisfactionStats = [
            'sangat_puas' => $sangatPuasCount,
            'puas' => $puasCount,
            'tidak_puas' => $tidakPuasCount,
            'total' => $totalSatisfaction,
            'p_sangat_puas' => $totalSatisfaction > 0 ? round(($sangatPuasCount / $totalSatisfaction) * 100, 1) : 0,
            'p_puas' => $totalSatisfaction > 0 ? round(($puasCount / $totalSatisfaction) * 100, 1) : 0,
            'p_tidak_puas' => $totalSatisfaction > 0 ? round(($tidakPuasCount / $totalSatisfaction) * 100, 1) : 0,
        ];

        // Admin Stock Balances
        $adminStocks = \App\Models\AdminBbmStock::all();

        return view('admin.dashboard', compact('stats', 'recentTransactions', 'kendaraanFuel', 'personelFuel', 'usersWithLocation', 'satisfactionStats', 'adminStocks'));
    }

    public function topup()
    {
        return view('admin.topup');
    }

    public function processTopup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.1',
            'topup_password' => 'required|string',
        ], [
            'amount.required' => 'Jumlah top up wajib diisi.',
            'amount.min' => 'Jumlah top up minimal 0.1 Liter.',
            'topup_password.required' => 'Password Top Up wajib diisi.',
        ]);

        $user = auth()->user();

        // Validasi Password Top Up
        if (!$user->topup_password) {
            return back()->with('error', 'Anda belum mengatur Password Top Up. Silakan atur di menu Profil.');
        }

        if (!\Illuminate\Support\Facades\Hash::check($request->topup_password, $user->topup_password)) {
            return back()->with('error', 'Password Top Up salah! Transaksi massal dibatalkan.');
        }

        $amount = $request->amount;

        try {
            DB::beginTransaction();

            // 1. Ambil semua kendaraan dikelompokkan per jenis bbm
            $kendaraans = \App\Models\Kendaraan::all()->groupBy('jenis_bbm');

            // 2. Cek stok admin untuk setiap jenis bbm yang ada
            foreach ($kendaraans as $jenisBbm => $group) {
                $countInGroup = $group->count();
                $totalNeeded = $countInGroup * $amount;

                $adminStock = \App\Models\AdminBbmStock::where('jenis_bbm', $jenisBbm)->first();

                if (!$adminStock || $adminStock->saldo < $totalNeeded) {
                    throw new \Exception("Stok Pusat untuk {$jenisBbm} tidak cukup. Dibutuhkan: {$totalNeeded} L, Tersedia: " . ($adminStock ? $adminStock->saldo : 0) . " L.");
                }

                // 3. Potong stok admin
                $adminStock->decrement('saldo', $totalNeeded);

                // 4. Catat riwayat stok admin
                \App\Models\RiwayatStokAdmin::create([
                    'user_id' => auth()->id(),
                    'jenis_bbm' => $jenisBbm,
                    'jumlah' => $totalNeeded,
                    'tipe' => 'keluar',
                    'keterangan' => "Top-up massal untuk {$countInGroup} kendaraan ({$amount} L/kendaraan)",
                ]);

                // 5. Update saldo kendaraan dalam grup ini
                foreach ($group as $kendaraan) {
                    $kendaraan->increment('saldo', $amount);

                    // Catat riwayat topup per kendaraan
                    \App\Models\RiwayatTopup::create([
                        'satker_id' => $kendaraan->satker_id,
                        'kendaraan_id' => $kendaraan->id,
                        'user_id' => auth()->id(),
                        'jumlah' => $amount,
                        'tipe' => 'masuk',
                        'metode' => 'massal',
                        'status' => 'success',
                        'jenis_bbm' => $jenisBbm ?: 'TANPA JENIS',
                        'keterangan' => 'Top-up massal dari Super Admin',
                    ]);
                }
            }

            \App\Models\LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => "Top-up massal sebesar {$amount} L untuk seluruh kendaraan."
            ]);

            DB::commit();

            // Kirim Notifikasi ke SEMUA Admin Satker (Async)
            $adminSatkers = User::where('role', 'admin_satker')->get();
            foreach ($adminSatkers as $admin) {
                $admin->notify(new TopupNotification([
                    'title' => 'Penerimaan Saldo Massal',
                    'message' => "Super Admin telah melakukan top-up saldo massal sebesar " . number_format($amount, 0, ',', '.') . " Liter untuk semua kendaraan.",
                    'amount' => $amount,
                ]));
            }

            return redirect()->route('admin.topup')->with('success', "Berhasil top-up {$amount} L untuk seluruh kendaraan.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
