<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Satker;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Traits\PaginatesTables;

class UserController extends Controller
{
    use PaginatesTables;

    public function __construct()
    {
        $this->middleware('role:super_admin');
    }

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

        $perPage = $this->getPerPage($request);
        $users = $query->latest()->paginate($perPage)->withQueryString();
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
            'role' => 'required|in:super_admin,kasubbag,admin_satker,petugas_bbm,personel',
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
            'role' => 'required|in:super_admin,kasubbag,admin_satker,petugas_bbm,personel',
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

        $perPage = $this->getPerPage($request);
        $users = $query->orderByDesc('last_activity_at')->paginate($perPage)->withQueryString();
        $satkers = Satker::orderBy('nama_satker')->get();
            
        return view('admin.users.monitoring', compact('users', 'satkers'));
    }

    public function activityLogs(User $user)
    {
        // Semua aktivitas sudah dicatat secara eksplisit di tabel log_aktivitas
        // Tidak perlu mengambil dan menggabungkan transaksi, topup, stok, dst
        // karena itu akan menyebabkan data ganda (duplicate).
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

        return response()->json($logs);
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

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id',
        ]);

        // Jangan hapus akun sendiri
        $ids = array_diff($request->ids, [auth()->id()]);
        $count = User::whereIn('id', $ids)->count();
        User::whereIn('id', $ids)->delete();

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menghapus {$count} User secara massal"
        ]);

        return back()->with('success', "{$count} user berhasil dihapus.");
    }

    public function resetPassword(User $user)
    {
        // Hindari mereset password sesama Super Admin
        if ($user->role === 'super_admin') {
            return back()->with('error', 'Akun Super Admin tidak dapat di-reset passwordnya.');
        }

        $user->password = bcrypt('password123');
        $user->save();

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Mereset Password Akun: {$user->name} ({$user->role})"
        ]);

        return back()->with('success', "Password untuk akun {$user->name} telah berhasil di-reset menjadi 'password123'.");
    }
}
