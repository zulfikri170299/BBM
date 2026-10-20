<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-sm text-white shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Satuan Kerja</h1>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-0.5">Kelola unit Satuan Kerja, distribusi armada kendaraan, dan hak akses pengguna.</p>
                </div>
            </div>
            <a href="{{ route('admin.satkers.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs font-bold shadow-sm shadow-indigo-600/20 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Satker</span>
            </a>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/40 rounded-xl shadow-sm"
                x-data="{ show: true }" x-show="show" x-transition>
                <div class="flex-shrink-0 p-1.5 bg-emerald-500 text-white rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="text-xs font-semibold text-emerald-800 dark:text-emerald-300 flex-1">{{ session('success') }}</p>
                <button @click="show = false" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Table Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden">
            <!-- Table Header Info -->
            <div class="px-5 py-4 bg-slate-50/70 dark:bg-slate-800/40 border-b border-slate-200 dark:border-white/10 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center border border-indigo-100 dark:border-indigo-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white">Daftar Satker</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">{{ $satkers->total() }} satker terdaftar dalam sistem</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Bulk Actions -->
                    <div id="bulkActions" class="hidden items-center gap-2">
                        <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10 px-2.5 py-1.5 rounded-lg border border-indigo-200 dark:border-indigo-500/20">
                            <span id="selectedCount">0</span> DIPILIH
                        </span>
                        <button type="button" id="bulkDeleteBtn"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white rounded-lg text-xs font-bold transition shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            <span>Hapus Terpilih</span>
                        </button>
                    </div>
                </div>
            </div>

            <form id="bulkDeleteForm" action="{{ route('admin.satkers.bulk-delete') }}" method="POST" class="hidden">
                @csrf
                <div id="bulkIdsContainer"></div>
            </form>

            <!-- Toolbar (Per-page & Search) -->
            <div class="p-4 border-b border-slate-200 dark:border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-white dark:bg-slate-900">
                <div class="flex flex-wrap items-center gap-3">
                    <form action="{{ route('admin.satkers.index') }}" method="GET" class="flex items-center">
                        <x-per-page :current="request('per_page', 15)" />
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                    </form>

                    <!-- Search Bar -->
                    <form action="{{ route('admin.satkers.index') }}" method="GET" class="flex items-center min-w-[220px] sm:min-w-[260px]">
                        @if(request('per_page'))
                            <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                        @endif
                        <div class="relative w-full">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama satker atau alamat..."
                                class="w-full pl-9 pr-3 py-1.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-semibold text-slate-800 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        </div>
                        @if(request('search'))
                            <a href="{{ route('admin.satkers.index', ['per_page' => request('per_page')]) }}"
                                class="ml-2 text-xs font-semibold text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <div class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[11px] font-semibold tracking-wide self-start md:self-auto">
                    Menampilkan {{ $satkers->firstItem() ?? 0 }}-{{ $satkers->lastItem() ?? 0 }} dari {{ $satkers->total() }} data
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 dark:bg-slate-800/50 border-b border-slate-200 dark:border-white/10">
                            <th class="w-12 px-4 py-3.5 text-center">
                                <input type="checkbox" id="checkAll"
                                    class="rounded border-slate-300 dark:border-white/20 text-indigo-600 focus:ring-indigo-500 shadow-sm cursor-pointer bg-white dark:bg-slate-800">
                            </th>
                            <th class="py-3.5 px-4 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                Nama Satker
                            </th>
                            <th class="py-3.5 px-4 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                Alamat
                            </th>
                            <th class="py-3.5 px-4 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">
                                Armada Kendaraan
                            </th>
                            <th class="py-3.5 px-4 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">
                                Pengguna Akun
                            </th>
                            <th class="py-3.5 px-4 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($satkers as $index => $satker)
                            @php
                                $colors = [
                                    'from-blue-500 to-indigo-600 shadow-blue-500/20',
                                    'from-indigo-500 to-purple-600 shadow-indigo-500/20',
                                    'from-emerald-500 to-teal-600 shadow-emerald-500/20',
                                    'from-amber-500 to-orange-600 shadow-amber-500/20',
                                    'from-rose-500 to-pink-600 shadow-rose-500/20',
                                    'from-cyan-500 to-blue-600 shadow-cyan-500/20',
                                ];
                                $color = $colors[$satker->id % count($colors)];
                            @endphp
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors group">
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <input type="checkbox" value="{{ $satker->id }}"
                                        class="bg-white dark:bg-slate-800 item-checkbox rounded border-slate-300 dark:border-white/20 text-indigo-600 focus:ring-indigo-500 shadow-sm cursor-pointer">
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 w-9 h-9 rounded-xl bg-gradient-to-br {{ $color }} flex items-center justify-center text-white font-black text-xs shadow-sm">
                                            {{ strtoupper(substr($satker->nama_satker, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="text-xs font-bold text-slate-800 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                                {{ $satker->nama_satker }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-300 max-w-xs font-medium">
                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="truncate">{{ $satker->alamat ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <a href="{{ route('admin.kendaraans.index', ['satker_id' => $satker->id]) }}" 
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-500/10 hover:bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/20 transition-all hover:scale-105"
                                        title="Lihat daftar kendaraan untuk {{ $satker->nama_satker }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1" />
                                        </svg>
                                        <span>{{ $satker->kendaraans_count ?? 0 }} Unit</span>
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <a href="{{ route('admin.users.index', ['satker_id' => $satker->id]) }}" 
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 transition-all hover:scale-105"
                                        title="Lihat daftar akun pengguna untuk {{ $satker->nama_satker }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <span>{{ $satker->users_count ?? 0 }} Akun</span>
                                    </a>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('admin.satkers.edit', $satker) }}"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-slate-100 hover:bg-indigo-50 dark:bg-slate-800 dark:hover:bg-indigo-950/40 text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-semibold transition"
                                            title="Edit Satker">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                            <span>Edit</span>
                                        </a>
                                        <form action="{{ route('admin.satkers.destroy', $satker) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="satker_id" value="{{ request('satker_id') }}">
                                            <button type="submit" data-confirm="Yakin ingin menghapus satker ini?"
                                                data-confirm-type="error"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-slate-100 hover:bg-rose-50 dark:bg-slate-800 dark:hover:bg-rose-950/40 text-slate-600 dark:text-slate-300 hover:text-rose-600 dark:hover:text-rose-400 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-semibold transition"
                                                title="Hapus Satker">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800/60 rounded-2xl flex items-center justify-center mb-4 text-slate-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                </path>
                                            </svg>
                                        </div>
                                        <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300">Belum ada Satker terdaftar</h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Klik tombol "Tambah Satker" di atas untuk menambah data baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($satkers->hasPages())
                <div class="px-5 py-4 border-t border-slate-200 dark:border-white/10 bg-slate-50/50 dark:bg-slate-800/40">
                    {{ $satkers->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            const checkAll = document.getElementById('checkAll');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            const bulkActions = document.getElementById('bulkActions');
            const selectedCountLabel = document.getElementById('selectedCount');

            function updateBulkUI() {
                const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
                selectedCountLabel.innerText = checkedCount;
                if (checkedCount > 0) {
                    bulkActions.classList.remove('hidden');
                    bulkActions.classList.add('flex');
                } else {
                    bulkActions.classList.add('hidden');
                    bulkActions.classList.remove('flex');
                }
            }

            checkAll.addEventListener('change', () => {
                itemCheckboxes.forEach(cb => { cb.checked = checkAll.checked; });
                updateBulkUI();
            });

            itemCheckboxes.forEach(cb => { cb.addEventListener('change', updateBulkUI); });

            document.getElementById('bulkDeleteBtn').addEventListener('click', function () {
                const selected = document.querySelectorAll('.item-checkbox:checked');
                if (selected.length === 0) return;

                Swal.fire({
                    title: 'Hapus Data Massal',
                    text: `Apakah Anda yakin ingin menghapus ${selected.length} satker yang terpilih? Tindakan ini tidak dapat dibatalkan.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const container = document.getElementById('bulkIdsContainer');
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
            });
        </script>
    @endpush
</x-app-layout>