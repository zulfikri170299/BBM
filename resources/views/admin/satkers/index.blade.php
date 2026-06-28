<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white">Satuan Kerja</h1>
                <p class="mt-1 text-slate-400">Kelola semua unit Satuan Kerja yang terdaftar.</p>
            </div>
            <a href="{{ route('admin.satkers.create') }}"
                        class="flex-1 lg:flex-none inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold text-sm hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Satker
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

        <!-- Table Card -->
        <div class="bg-slate-900 rounded-2xl border border-white/10 shadow-sm overflow-hidden">
            <!-- Table Header Info -->
            <div class="px-4 py-3 border-b border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-200">Daftar Satker</h3>
                        <p class="text-xs text-slate-400">{{ $satkers->total() }} satker terdaftar</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">

                    <!-- Bulk Actions -->
                    <div id="bulkActions" class="hidden flex items-center gap-3">
                        <span
                            class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100">
                            <span id="selectedCount">0</span> DIPILIH
                        </span>
                        <button type="button" id="bulkDeleteBtn"
                            class="px-3 py-1.5 bg-rose-600 text-white rounded-lg text-xs font-bold hover:bg-rose-700 transition shadow-sm">
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

            <form id="bulkDeleteForm" action="{{ route('admin.satkers.bulk-delete') }}" method="POST" class="hidden">
                @csrf
                <div id="bulkIdsContainer"></div>
            </form>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-800/50 border-b border-white/5">
                            <th colspan="4" class="px-4 py-3">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="flex flex-wrap items-center gap-4">
                                        <form action="{{ route('admin.satkers.index') }}" method="GET"
                                            class="flex items-center">
                                            <x-per-page :current="request('per_page', 15)" />
                                            @if(request('search'))
                                                <input type="hidden" name="search" value="{{ request('search') }}">
                                            @endif
                                        </form>

                                        <!-- Search Bar -->
                                        <form action="{{ route('admin.satkers.index') }}" method="GET"
                                            class="flex items-center min-w-[240px]">
                                            @if(request('per_page'))
                                                <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                                            @endif
                                            <div class="relative w-full">
                                                <span
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                    </svg>
                                                </span>
                                                <input type="text" name="search" value="{{ request('search') }}"
                                                    placeholder="Cari satker..."
                                                    class="block w-full pl-9 pr-3 py-1.5 border border-white/10 rounded-lg text-xs placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                            </div>
                                            @if(request('search'))
                                                <a href="{{ route('admin.satkers.index', ['per_page' => request('per_page')]) }}"
                                                    class="ml-2 text-xs text-slate-400 hover:text-slate-400 transition-colors">
                                                    Reset
                                                </a>
                                            @endif
                                        </form>
                                    </div>
                                    <div class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">
                                        Menampilkan {{ $satkers->firstItem() ?? 0 }}-{{ $satkers->lastItem() ?? 0 }}
                                        dari {{ $satkers->total() }} data
                                    </div>
                                </div>
                            </th>
                        </tr>
                        <tr class="bg-slate-800/50/70">
                            <th class="w-10 px-6 py-3.5">
                                <input type="checkbox" id="checkAll"
                                    class="rounded border-white/20 text-indigo-600 focus:ring-indigo-500 shadow-sm cursor-pointer">
                            </th>
                            <th
                                class="px-4 py-3 text-left text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider">
                                Nama Satker</th>
                            <th
                                class="px-4 py-3 text-left text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider">
                                Alamat</th>
                            <th
                                class="px-4 py-3 text-right text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($satkers as $index => $satker)
                            <tr class="hover:bg-slate-800/50 transition-colors group">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <input type="checkbox" value="{{ $satker->id }}"
                                        class="item-checkbox rounded border-white/20 text-indigo-600 focus:ring-indigo-500 shadow-sm cursor-pointer">
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-md shadow-indigo-500/20">
                                            {{ strtoupper(substr($satker->nama_satker, 0, 2)) }}
                                        </div>
                                        <span class="text-xs font-medium text-slate-200">{{ $satker->nama_satker }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2 text-xs text-slate-400 max-w-xs">
                                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="truncate">{{ $satker->alamat ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.satkers.edit', $satker) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-slate-800 hover:bg-indigo-100 text-slate-400 hover:text-indigo-700 rounded-lg text-xs font-semibold transition-colors">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.satkers.destroy', $satker) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="satker_id" id="satker_id"
                                                value="{{ request('satker_id') }}">
                                            <button type="submit" data-confirm="Yakin ingin menghapus satker ini?"
                                                data-confirm-type="error"
                                                class="inline-flex items-center px-3 py-1.5 bg-slate-800 hover:bg-red-100 text-slate-400 hover:text-red-700 rounded-lg text-xs font-semibold transition-colors">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="text-slate-400 font-medium">Belum ada Satker terdaftar</p>
                                        <p class="text-xs text-slate-400 mt-1">Klik tombol "Tambah Satker" untuk
                                            menambah
                                            data.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($satkers->hasPages())
                <div class="px-4 py-3 border-t border-white/5">
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