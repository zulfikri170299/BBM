<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Satker;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('satker');

        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $satkers = Satker::orderBy('nama_satker', 'asc')->get();
        return view('admin.users.index', compact('users', 'satkers'));
    }

    public function create()
    {
        $satkers = Satker::all();
        return view('admin.users.create', compact('satkers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin_satker,petugas_bbm,personel',
            'satker_id' => 'nullable|exists:satkers,id',
            'username' => 'nullable|string|unique:users',
        ]);

        $data = $request->except('password');
        $data['password'] = bcrypt($request->password);

        User::create($data);

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menambahkan User Baru: {$request->name} ({$request->role})"
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $satkers = Satker::all();
        return view('admin.users.edit', compact('user', 'satkers'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:super_admin,admin_satker,petugas_bbm,personel',
            'satker_id' => 'nullable|exists:satkers,id',
            'username' => ['nullable', 'string', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Memperbarui data User: {$user->name}"
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menghapus User: {$user->name}"
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function monitoring(Request $request)
    {
        $query = User::with('satker');

        // Filter Cari (Nama/Email)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter Satker
        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        // Filter Tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('last_activity_at', $request->tanggal);
        }

        $users = $query->orderByDesc('last_activity_at')->paginate(15)->withQueryString();
        $satkers = Satker::orderBy('nama_satker')->get();
            
        return view('admin.users.monitoring', compact('users', 'satkers'));
    }

    public function activityLogs(User $user)
    {
        // 1. Ambil log eksplisit dari tabel log_aktivitas
        $logs = \App\Models\LogAktivitas::where('user_id', $user->id)
            ->latest()
            ->take(50)
            ->get()
            ->map(function($item) {
                return [
                    'aktivitas' => $item->aktivitas,
                    'created_at' => $item->created_at,
                ];
            });

        // 2. Ambil riwayat pemrosesan Transaksi BBM (jika petugas)
        $transactions = \App\Models\TransaksiBbm::with(['kendaraan', 'personel'])
            ->where('petugas_id', $user->id)
            ->latest()
            ->take(50)
            ->get()
            ->map(function($item) {
                $target = $item->kendaraan ? "Kendaraan ({$item->kendaraan->no_polisi})" : "Personel ({$item->personel->nama})";
                return [
                    'aktivitas' => "Memproses pengisian BBM: {$item->liter} L untuk {$target}",
                    'created_at' => $item->tanggal ? \Carbon\Carbon::parse($item->tanggal) : $item->created_at,
                ];
            });

        // 3. Ambil riwayat Topup (jika admin)
        $topups = \App\Models\RiwayatTopup::with('kendaraan')
            ->where('user_id', $user->id)
            ->latest()
            ->take(50)
            ->get()
            ->map(function($item) {
                return [
                    'aktivitas' => "Melakukan Top-up Saldo: {$item->jumlah} L untuk Kendaraan ({$item->kendaraan->no_polisi})",
                    'created_at' => $item->created_at,
                ];
            });

        // 4. Ambil riwayat pengelolaan Stok Pusat
        $stocks = \App\Models\RiwayatStokAdmin::where('user_id', $user->id)
            ->latest()
            ->take(50)
            ->get()
            ->map(function($item) {
                $tipe = $item->tipe === 'masuk' ? 'Penambahan' : 'Pengeluaran';
                return [
                    'aktivitas' => "{$tipe} Stok Pusat: {$item->jumlah} L {$item->jenis_bbm} (" . ($item->keterangan ?? '-') . ")",
                    'created_at' => $item->created_at,
                ];
            });

        // 5. Ambil riwayat Transfer Saldo ke Personel (jika admin satker)
        $transfers = collect();
        if ($user->satker_id) {
            $transfers = \App\Models\RiwayatTransferSaldoPersonel::with(['kendaraan', 'personel'])
                ->where('satker_id', $user->satker_id)
                ->latest()
                ->take(50)
                ->get()
                ->map(function($item) {
                    return [
                        'aktivitas' => "Transfer saldo BBM: {$item->jumlah} L dari Kendaraan ({$item->kendaraan->no_polisi}) ke Personel ({$item->personel->nama})",
                        'created_at' => $item->created_at,
                    ];
                });
        }

        // 6. Ambil riwayat Berita Acara (jika admin)
        $balogs = collect();
        if ($user->satker_id) {
            $balogs = \App\Models\BaLog::where('satker_id', $user->satker_id)
                ->latest()
                ->take(50)
                ->get()
                ->map(function($item) {
                    return [
                        'aktivitas' => "Menghasilkan Berita Acara (BA) Bulan: {$item->bulan}, Tahun: {$item->tahun}",
                        'created_at' => $item->created_at,
                    ];
                });
        }

        // Gabungkan semua, urutkan berdasarkan waktu terbaru
        $combined = $logs->concat($transactions)
                        ->concat($topups)
                        ->concat($stocks)
                        ->concat($transfers)
                        ->concat($balogs)
                        ->sortByDesc('created_at')
                        ->values()
                        ->take(50);

        return response()->json($combined);
    }

    public function toggleStatus(User $user)
    {
        // Hindari menonaktifkan diri sendiri
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $statusText = $user->is_active ? 'Mengaktifkan' : 'Menonaktifkan';
        
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "{$statusText} Akun: {$user->name} ({$user->role})"
        ]);

        return back()->with('success', "Status akun {$user->name} berhasil diperbarui.");
    }

    public function bulkStatus(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'status' => 'required|in:active,inactive'
        ]);

        $isActive = $request->status === 'active';
        $userIds = array_diff($request->user_ids, [auth()->id()]); // Jangan nonaktifkan diri sendiri

        User::whereIn('id', $userIds, 'and', false)->update(['is_active' => $isActive]);

        $statusText = $isActive ? 'Mengaktifkan' : 'Menonaktifkan';
        $count = count($userIds);
        
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "{$statusText} {$count} Akun secara massal"
        ]);

        return back()->with('success', "{$count} akun berhasil diperbarui statusnya.");
    }
}
