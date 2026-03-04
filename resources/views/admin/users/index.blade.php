<x-app-layout>
    <div class="p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Manajemen User</h1>
                <p class="mt-1 text-slate-500">Kelola semua akun pengguna dan peran dalam sistem.</p>
            </div>
            <a href="{{ route('admin.users.create') }}"
                class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold text-sm hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah User
            </a>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl"
                x-data="{ show: true }" x-show="show" x-transition>
                <div class="flex-shrink-0 p-1.5 bg-emerald-100 rounded-full">
                    <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p class="text-sm font-medium text-emerald-700 flex-1">{{ session('success') }}</p>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center gap-3 p-4 bg-rose-50 border border-rose-200 rounded-xl" x-data="{ show: true }"
                x-show="show" x-transition>
                <div class="flex-shrink-0 p-1.5 bg-rose-100 rounded-full">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-sm font-medium text-rose-700 flex-1">{{ session('error') }}</p>
                <button @click="show = false" class="text-rose-400 hover:text-rose-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/70 shadow-sm">
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-[240px]">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama atau email..."
                            class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                    </div>
                </div>

                <div class="w-full sm:w-60">
                    <select name="satker_id"
                        class="block w-full py-2 pl-3 pr-10 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                        <option value="">Semua Satker</option>
                        @foreach($satkers as $satker)
                            <option value="{{ $satker->id }}" {{ request('satker_id') == $satker->id ? 'selected' : '' }}>
                                {{ $satker->nama_satker }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 transition shadow-md">
                        Filter
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-200 transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
            <!-- Table Header Info -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 01-9-3.354">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">Daftar Pengguna</h3>
                        <p class="text-xs text-slate-400">{{ $users->total() }} user terdaftar dalam sistem</p>
                    </div>
                </div>

                <!-- Bulk Actions -->
                <div id="bulkActions"
                    class="hidden flex items-center gap-3 animate-in fade-in slide-in-from-right-4 duration-300">
                    <span
                        class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100"><span
                            id="selectedCount">0</span> DIPILIH</span>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="submitBulk('active')"
                            class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 transition shadow-sm">Aktifkan</button>
                        <button type="button" onclick="submitBulk('inactive')"
                            class="px-3 py-1.5 bg-rose-600 text-white rounded-lg text-xs font-bold hover:bg-rose-700 transition shadow-sm">Nonaktifkan</button>
                        <button type="button" onclick="submitBulkDelete()"
                            class="px-3 py-1.5 bg-slate-800 text-white rounded-lg text-xs font-bold hover:bg-slate-900 transition shadow-sm">
                            <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            Hapus Terpilih
                        </button>
                    </div>
                </div>
            </div>

            <form id="bulkForm" action="{{ route('admin.users.bulk-status') }}" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="status" id="bulkStatusInput">
                <div id="bulkIdsContainer"></div>
            </form>

            <form id="bulkDeleteForm" action="{{ route('admin.users.bulk-delete') }}" method="POST" class="hidden">
                @csrf
                <div id="bulkDeleteIdsContainer"></div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th colspan="6" class="px-6 py-3">
                                <div class="flex items-center justify-between">
                                    <form action="{{ route('admin.users.index') }}" method="GET"
                                        class="flex items-center">
                                        @if(request('search'))
                                            <input type="hidden" name="search" value="{{ request('search') }}">
                                        @endif
                                        @if(request('satker_id'))
                                            <input type="hidden" name="satker_id" value="{{ request('satker_id') }}">
                                        @endif
                                        <x-per-page :current="request('per_page', 15)" />
                                    </form>
                                    <div class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">
                                        Menampilkan {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} dari
                                        {{ $users->total() }} data
                                    </div>
                                </div>
                            </th>
                        </tr>
                        <tr class="bg-slate-50/30">
                            <th class="w-10 px-6 py-3.5">
                                <input type="checkbox" id="checkAll"
                                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 shadow-sm cursor-pointer">
                            </th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Identitas User</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                NRP / Email</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Role & Unit</th>
                            <th
                                class="px-6 py-3.5 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3.5 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $user)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if(auth()->id() !== $user->id)
                                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                            class="user-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 shadow-sm cursor-pointer">
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $loop->index % 2 == 0 ? 'from-indigo-500 to-purple-600' : 'from-emerald-500 to-teal-600' }} flex items-center justify-center text-white font-bold text-sm shadow-md ring-2 ring-white">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-900">{{ $user->name }}</p>
                                            <p class="text-[11px] font-medium text-slate-400 mt-0.5">ID: #{{ $user->id }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm font-semibold text-slate-700">{{ $user->email }}</p>
                                    <p class="text-[11px] text-slate-400">Username: {{ $user->username ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col gap-1.5">
                                        @php
                                            $roleColors = [
                                                'super_admin' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                                'kasubbag' => 'bg-violet-50 text-violet-700 border-violet-100',
                                                'admin_satker' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                'personel' => 'bg-blue-50 text-blue-700 border-blue-100',
                                                'petugas_bbm' => 'bg-amber-50 text-amber-700 border-amber-100',
                                            ];
                                            $colorClass = $roleColors[$user->role] ?? 'bg-slate-50 text-slate-700 border-slate-100';
                                        @endphp
                                        <span
                                            class="inline-flex items-center w-fit px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $colorClass }} uppercase tracking-wider">
                                            {{ str_replace('_', ' ', $user->role) }}
                                        </span>
                                        <p class="text-xs font-medium text-slate-500 truncate max-w-[150px]">
                                            {{ $user->satker ? $user->satker->nama_satker : 'Pusat / SuperAdmin' }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($user->is_active)
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="p-2 {{ $user->is_active ? 'text-rose-400 hover:text-rose-600 hover:bg-rose-50' : 'text-emerald-400 hover:text-emerald-600 hover:bg-emerald-50' }} rounded-lg transition-all"
                                                    data-confirm="Aksi ini akan {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan' }} akun {{ $user->name }}."
                                                    title="{{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                                    @if($user->is_active)
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                                            </path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    @endif
                                                </button>
                                            </form>

                                            @if($user->role !== 'super_admin')
                                                <form action="{{ route('admin.users.reset-password', $user) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all"
                                                        data-confirm="Reset password untuk {{ $user->name }} menjadi password123?"
                                                        data-confirm-type="warning" title="Reset Password">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all"
                                            title="Edit User">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all"
                                                    data-confirm="Apakah Anda yakin ingin menghapus user {{ $user->name }}? Tindakan ini tidak dapat dibatalkan."
                                                    data-confirm-type="error" title="Hapus User">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            const checkAll = document.getElementById('checkAll');
            const userCheckboxes = document.querySelectorAll('.user-checkbox');
            const bulkActions = document.getElementById('bulkActions');
            const selectedCountLabel = document.getElementById('selectedCount');
            const bulkStatusInput = document.getElementById('bulkStatusInput');
            const bulkForm = document.getElementById('bulkForm');

            function updateBulkUI() {
                const checkedCount = document.querySelectorAll('.user-checkbox:checked').length;
                selectedCountLabel.innerText = checkedCount;
                if (checkedCount > 0) {
                    bulkActions.classList.remove('hidden');
                } else {
                    bulkActions.classList.add('hidden');
                }
            }

            checkAll.addEventListener('change', () => {
                userCheckboxes.forEach(cb => {
                    cb.checked = checkAll.checked;
                });
                updateBulkUI();
            });

            userCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateBulkUI);
            });

            function submitBulk(status) {
                const actionText = status === 'active' ? 'mengaktifkan' : 'menonaktifkan';
                const selected = document.querySelectorAll('.user-checkbox:checked');

                if (selected.length === 0) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Ops!',
                        text: 'Pilih setidaknya satu user.',
                        confirmButtonColor: '#4338ca',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Konfirmasi Massal',
                    text: `Apakah Anda yakin ingin ${actionText} ${selected.length} akun yang terpilih?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: status === 'active' ? '#10b981' : '#e11d48',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const container = document.getElementById('bulkIdsContainer');
                        container.innerHTML = '';

                        selected.forEach(cb => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'user_ids[]';
                            input.value = cb.value;
                            container.appendChild(input);
                        });

                        bulkStatusInput.value = status;
                        bulkForm.submit();
                    }
                });
            }
            function submitBulkDelete() {
                const selected = document.querySelectorAll('.user-checkbox:checked');

                if (selected.length === 0) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Ops!',
                        text: 'Pilih setidaknya satu user.',
                        confirmButtonColor: '#4338ca',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Hapus Data Massal',
                    text: `Apakah Anda yakin ingin menghapus ${selected.length} user yang terpilih? Tindakan ini tidak dapat dibatalkan.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const container = document.getElementById('bulkDeleteIdsContainer');
                        container.innerHTML = '';

                        selected.forEach(cb => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'ids[]';
                            input.value = cb.value;
                            container.appendChild(input);
                        });

                        document.getElementById('bulkDeleteForm').submit();
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>