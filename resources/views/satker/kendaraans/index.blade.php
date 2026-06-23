<x-app-layout>
    <div class="p-3 sm:p-6 lg:p-8 space-y-4 sm:space-y-6" x-data="{ 
        showTransferModal: false, 
        showMonthlyReportModal: false,
        selectedBbm: '',
        personels: {{ json_encode($personels) }},
        // Searchable Kendaraan
        kendaraanSearch: '',
        kendaraanOpen: false,
        kendaraanSelected: null,
        kendaraanLabel: '',
        kendaraans: {{ json_encode($availableKendaraans->map(fn($k) => ['id' => $k->id, 'no_polisi' => $k->no_polisi, 'jenis_bbm' => $k->jenis_bbm, 'saldo' => $k->saldo])) }},
        get filteredKendaraans() {
            if (!this.kendaraanSearch) return this.kendaraans;
            const q = this.kendaraanSearch.toLowerCase();
            return this.kendaraans.filter(k => k.no_polisi.toLowerCase().includes(q) || k.jenis_bbm.toLowerCase().includes(q));
        },
        selectKendaraan(k) {
            this.kendaraanSelected = k.id;
            this.kendaraanLabel = k.no_polisi + ' • ' + k.jenis_bbm + ' • ' + Number(k.saldo).toLocaleString('id-ID') + ' L';
            this.selectedBbm = k.jenis_bbm;
            this.kendaraanOpen = false;
            this.kendaraanSearch = '';
        },
        // Target Type
        tipeTujuan: 'personel',
        // Searchable Personel
        personelSearch: '',
        personelOpen: false,
        personelSelected: null,
        personelLabel: '',
        get filteredPersonels() {
            let list = this.personels.filter(p => !this.selectedBbm || !p.jenis_bbm || p.jenis_bbm === this.selectedBbm);
            if (!this.personelSearch) return list;
            const q = this.personelSearch.toLowerCase();
            return list.filter(p => p.nama.toLowerCase().includes(q) || (p.jenis_bbm && p.jenis_bbm.toLowerCase().includes(q)));
        },
        selectPersonel(p) {
            this.personelSelected = p.id;
            this.personelLabel = p.nama + ' • ' + (p.jenis_bbm ? p.jenis_bbm : 'Belum set BBM');
            this.personelOpen = false;
            this.personelSearch = '';
        },
        // Searchable Tujuan Kendaraan
        tujuanKendaraanSearch: '',
        tujuanKendaraanOpen: false,
        tujuanKendaraanSelected: null,
        tujuanKendaraanLabel: '',
        get filteredTujuanKendaraans() {
            // Filter: Bukan kendaraan sumber, dan BBM harus sama
            let list = this.kendaraans.filter(k => k.id !== this.kendaraanSelected && (!this.selectedBbm || k.jenis_bbm === this.selectedBbm));
            if (!this.tujuanKendaraanSearch) return list;
            const q = this.tujuanKendaraanSearch.toLowerCase();
            return list.filter(k => k.no_polisi.toLowerCase().includes(q));
        },
        selectTujuanKendaraan(k) {
            this.tujuanKendaraanSelected = k.id;
            this.tujuanKendaraanLabel = k.no_polisi + ' • ' + k.jenis_bbm;
            this.tujuanKendaraanOpen = false;
            this.tujuanKendaraanSearch = '';
        },
        get selectedSourceSaldo() {
            if (!this.kendaraanSelected) return 0;
            const k = this.kendaraans.find(v => v.id === this.kendaraanSelected);
            return k ? k.saldo : 0;
        },
        jumlahTransfer: 0
    }">
        <!-- Page Header -->
        <div class="flex flex-col gap-3 sm:gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900">Kendaraan</h1>
                <p class="mt-0.5 sm:mt-1 text-xs sm:text-sm text-slate-500">Kelola armada kendaraan
                    {{ Auth::user()->satker->nama_satker ?? '' }}
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                <a href="{{ route('satker.kendaraans.export') }}"
                    class="inline-flex items-center justify-center w-10 h-10 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 transition-all duration-200 hover:-translate-y-0.5 group relative"
                    title="Export Excel">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span
                        class="absolute -bottom-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">Export
                        Excel</span>
                </a>

                <button @click="showTransferModal = true"
                    class="inline-flex items-center justify-center w-10 h-10 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 transition-all duration-200 hover:-translate-y-0.5 group relative"
                    title="Transfer Saldo">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                    <span
                        class="absolute -bottom-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">Transfer
                        Saldo</span>
                </button>
                @if(\App\Models\Setting::where('key', 'satker_can_create_kendaraan')->value('value') ?? 1)
                    <a href="{{ route('satker.kendaraans.create') }}"
                        class="inline-flex items-center justify-center w-10 h-10 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 group relative"
                        title="Tambah Kendaraan">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span
                            class="absolute -bottom-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">Tambah
                            Kendaraan</span>
                    </a>
                @endif
                @if((\App\Models\Setting::where('key', 'satker_can_import_kendaraan')->value('value') ?? '1') == '1')
                    <button @click="$dispatch('open-import-kendaraan')"
                        class="inline-flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all duration-200 hover:-translate-y-0.5 group relative"
                        title="Import Excel">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                            </path>
                        </svg>
                        <span
                            class="absolute -bottom-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">Import
                            Excel</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Error Alert -->
        @if(session('error'))
            <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl mb-4" x-data="{ show: true }"
                x-show="show" x-transition>
                <div class="flex-shrink-0 p-1.5 bg-red-100 rounded-full mt-0.5">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-sm font-medium text-red-700 flex-1">{{ session('error') }}</p>
                <button @click="show = false" class="text-red-400 hover:text-red-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl mb-4">
                <div class="flex-shrink-0 p-1.5 bg-red-100 rounded-full mt-0.5">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-red-800">Terdapat beberapa kesalahan:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Success Alert -->
        @if(session('success'))
            <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl"
                x-data="{ show: true }" x-show="show" x-transition>
                <div class="flex-shrink-0 p-1.5 bg-emerald-100 rounded-full mt-0.5">
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
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2 sm:gap-3">
                    <div>
                        <h3 class="text-sm sm:text-base font-semibold text-slate-800">Daftar Kendaraan</h3>
                        <p class="text-[10px] sm:text-xs text-slate-400">{{ $kendaraans->total() }} kendaraan terdaftar
                        </p>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions Bar -->
            <div id="bulkActionsBar"
                class="hidden px-4 sm:px-6 py-3 bg-indigo-50 border-b border-indigo-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-semibold text-indigo-800"><span id="selectedCount">0</span> data
                        dipilih</span>
                </div>
                <button type="button" onclick="bulkDeleteKendaraan()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-lg shadow transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    Hapus Terpilih
                </button>
            </div>

            <!-- Hidden form for bulk delete -->
            <form id="bulkDeleteForm" action="{{ route('satker.kendaraans.bulk-delete') }}" method="POST"
                class="hidden">
                @csrf
                <div id="bulkDeleteInputs"></div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th colspan="8" class="px-6 py-3">
                                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                    <form action="{{ route('satker.kendaraans.index') }}" method="GET"
                                        class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full sm:w-auto">
                                        <x-per-page :current="request('per_page', 10)" />

                                        <div class="relative w-full sm:w-auto">
                                            <span
                                                class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-slate-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z">
                                                    </path>
                                                </svg>
                                            </span>
                                            <input type="text" name="search" value="{{ request('search') }}"
                                                placeholder="Cari nopol, jenis..."
                                                class="block w-full sm:w-48 pl-8 pr-3 py-1.5 border border-slate-200 rounded-lg text-xs placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                        </div>

                                        @if(request('search'))
                                            <a href="{{ route('satker.kendaraans.index') }}"
                                                class="text-xs font-medium text-slate-400 hover:text-indigo-600 transition-colors">
                                                Reset
                                            </a>
                                        @endif
                                    </form>
                                    <div class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">
                                        Menampilkan
                                        {{ $kendaraans->firstItem() ?? 0 }}-{{ $kendaraans->lastItem() ?? 0 }} dari
                                        {{ $kendaraans->total() }} data
                                    </div>
                                </div>
                            </th>
                        </tr>
                        <tr class="bg-slate-50/70">
                            <th class="px-4 py-3.5 text-center w-10">
                                <input type="checkbox" id="checkAll"
                                    class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 cursor-pointer">
                            </th>
                            <th
                                class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-12">
                                No</th>
                            <th
                                class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Jenis Kendaraan</th>
                            <th
                                class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Nopol</th>
                            <th
                                class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Jenis BBM</th>
                            <th
                                class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Saldo</th>
                            <th
                                class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                PIN</th>
                            <th
                                class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kendaraans as $kendaraan)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-4 text-center">
                                    <input type="checkbox" name="item_ids[]" value="{{ $kendaraan->id }}"
                                        class="item-checkbox w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 cursor-pointer">
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="text-xs font-semibold text-slate-500">{{ $loop->iteration + ($kendaraans->currentPage() - 1) * $kendaraans->perPage() }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-xs font-semibold text-slate-800">{{ $kendaraan->jenis_kendaraan }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold text-slate-800">{{ $kendaraan->no_polisi }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $bbmColors = [
                                            'Pertamax' => 'bg-blue-100 text-blue-700',
                                            'Pertamina Dex' => 'bg-yellow-200 text-yellow-700',
                                        ];
                                        $color = $bbmColors[$kendaraan->jenis_bbm] ?? 'bg-slate-100 text-slate-700';
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold whitespace-nowrap {{ $color }}">
                                        {{ $kendaraan->jenis_bbm }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span
                                        class="text-xs font-bold whitespace-nowrap {{ $kendaraan->saldo < 10 ? 'text-red-600' : 'text-slate-800' }}">
                                        {{ number_format($kendaraan->saldo, 0, ',', '.') }} Liter
                                    </span>
                                    @if($kendaraan->saldo < 10)
                                        <span class="block text-xs text-red-500 font-medium mt-0.5">Saldo rendah</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <code
                                        class="px-2.5 py-1 bg-amber-50 text-amber-700 rounded-lg text-xs font-mono font-bold tracking-widest">{{ $kendaraan->pin }}</code>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('satker.kendaraans.print', $kendaraan) }}"

                                            class="p-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-600 hover:text-indigo-700 rounded-lg transition-colors group"
                                            title="Print Barcode">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                </path>
                                            </svg>
                                        </a>
                                        @if(\App\Models\Setting::where('key', 'satker_can_edit_kendaraan')->value('value') ?? 1)
                                            <a href="{{ route('satker.kendaraans.edit', $kendaraan) }}"
                                                class="p-2 bg-amber-100 hover:bg-amber-200 text-amber-600 hover:text-amber-700 rounded-lg transition-colors group"
                                                title="Edit Kendaraan">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('satker.kendaraans.reset-pin', $kendaraan) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    data-confirm="Apakah Anda yakin ingin mereset PIN kendaraan ini? PIN baru akan di-generate secara acak."
                                                    data-confirm-title="Reset PIN"
                                                    data-confirm-text="Ya, Reset PIN"
                                                    data-confirm-type="warning"
                                                    class="p-2 bg-red-100 hover:bg-red-200 text-red-600 hover:text-red-700 rounded-lg transition-colors group"
                                                    title="Reset PIN">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="text-slate-500 font-medium">Belum ada kendaraan terdaftar</p>
                                        <a href="{{ route('satker.kendaraans.create') }}"
                                            class="mt-3 text-sm font-semibold text-indigo-600 hover:text-indigo-500">Tambah
                                            kendaraan pertama →</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($kendaraans->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $kendaraans->links() }}
                </div>
            @endif
        </div>

        <!-- Transfer Modal -->
        <div x-show="showTransferModal" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 py-6">
                <!-- Backdrop -->
                <div x-show="showTransferModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                    @click="showTransferModal = false"></div>

                <!-- Modal Panel -->
                <div x-show="showTransferModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto max-h-[90vh] flex flex-col overflow-hidden">
                    <form action="{{ route('satker.kendaraans.transfer') }}" method="POST" class="flex flex-col h-full min-h-0">
                        @csrf
                        @php
                            $personelAccessControl = \App\Models\Setting::where('key', 'personel_access_control')->value('value') ?? '1';
                        @endphp

                        <!-- Header with Gradient -->
                        <div
                            class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 px-4 sm:px-6 py-4 sm:py-5 shrink-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-white" id="modal-title">Transfer Saldo</h3>
                                        <p class="text-emerald-100 text-xs">
                                            <span x-show="tipeTujuan === 'personel'">Kendaraan → Personel</span>
                                            <span x-show="tipeTujuan === 'kendaraan'">Kendaraan → Kendaraan</span>
                                        </p>
                                    </div>
                                </div>
                                <button type="button" @click="showTransferModal = false"
                                    class="p-1.5 hover:bg-white/20 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Form Body -->
                        <div class="px-4 sm:px-6 py-4 sm:py-5 space-y-4 sm:space-y-5 overflow-y-auto flex-1">

                            <!-- Sumber Kendaraan (Searchable) -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                                    <span
                                        class="flex items-center justify-center w-6 h-6 rounded-lg bg-blue-100 text-blue-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1">
                                            </path>
                                        </svg>
                                    </span>
                                    Sumber Dana
                                </label>
                                <input type="hidden" name="kendaraan_id" :value="kendaraanSelected" required>
                                <div class="relative" @click.outside="kendaraanOpen = false">
                                    <div @click="kendaraanOpen = !kendaraanOpen; $nextTick(() => { if(kendaraanOpen) $refs.kendaraanInput.focus() })"
                                        class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm cursor-pointer flex items-center justify-between transition-all"
                                        :class="kendaraanOpen ? 'ring-2 ring-emerald-500 border-emerald-500' : ''">
                                        <span x-text="kendaraanLabel || '— Pilih Kendaraan —'"
                                            :class="kendaraanLabel ? 'text-slate-800' : 'text-slate-400'"></span>
                                        <svg class="w-4 h-4 text-slate-400 transition-transform"
                                            :class="kendaraanOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                    <div x-show="kendaraanOpen" x-transition.opacity.duration.150ms
                                        class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden"
                                        style="display:none;">
                                        <div class="p-2 border-b border-slate-100">
                                            <div class="relative">
                                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                                <input x-ref="kendaraanInput" x-model="kendaraanSearch" type="text"
                                                    placeholder="Cari kendaraan..."
                                                    class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                            </div>
                                        </div>
                                        <div class="max-h-48 overflow-y-auto">
                                            <template x-for="k in filteredKendaraans" :key="k.id">
                                                <div @click="selectKendaraan(k)"
                                                    class="px-4 py-2.5 text-sm text-slate-700 hover:bg-emerald-50 cursor-pointer flex items-center justify-between transition-colors"
                                                    :class="kendaraanSelected === k.id ? 'bg-emerald-50 text-emerald-700 font-semibold' : ''">
                                                    <span
                                                        x-text="k.no_polisi + ' • ' + k.jenis_bbm + ' • ' + Number(k.saldo).toLocaleString('id-ID') + ' L'"></span>
                                                    <svg x-show="kendaraanSelected === k.id"
                                                        class="w-4 h-4 text-emerald-500" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            </template>
                                            <div x-show="filteredKendaraans.length === 0"
                                                class="px-4 py-3 text-sm text-slate-400 text-center">Tidak ditemukan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Arrow Divider -->
                            <div class="flex items-center justify-center">
                                <div
                                    class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-b from-emerald-100 to-teal-100 border-2 border-emerald-200 shadow-sm">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Tipe Tujuan Selector -->
                            <div class="p-1 bg-slate-100 rounded-xl flex">
                                @if($personelAccessControl == '1')
                                <button type="button" @click="tipeTujuan = 'personel'"
                                    class="flex-1 px-4 py-2 text-xs font-bold rounded-lg transition-all"
                                    :class="tipeTujuan === 'personel' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'">
                                    Ke Personel
                                </button>
                                @endif
                                <button type="button" @click="tipeTujuan = 'kendaraan'"
                                    class="flex-1 px-4 py-2 text-xs font-bold rounded-lg transition-all"
                                    :class="tipeTujuan === 'kendaraan' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'">
                                    Antar Kendaraan
                                </button>
                            </div>

                            <input type="hidden" name="tipe_tujuan" :value="tipeTujuan">

                            <!-- Tujuan Personel (Searchable) -->
                            @if($personelAccessControl == '1')
                            <div x-show="tipeTujuan === 'personel'">
                                <label class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                                    <span
                                        class="flex items-center justify-center w-6 h-6 rounded-lg bg-purple-100 text-purple-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </span>
                                    Tujuan Personel
                                </label>
                                <input type="hidden" name="personel_id" :value="personelSelected" :required="tipeTujuan === 'personel'">
                                <div class="relative" @click.outside="personelOpen = false">
                                    <div @click="personelOpen = !personelOpen; $nextTick(() => { if(personelOpen) $refs.personelInput.focus() })"
                                        class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm cursor-pointer flex items-center justify-between transition-all"
                                        :class="personelOpen ? 'ring-2 ring-emerald-500 border-emerald-500' : ''">
                                        <span x-text="personelLabel || '— Pilih Personel —'"
                                            :class="personelLabel ? 'text-slate-800' : 'text-slate-400'"></span>
                                        <svg class="w-4 h-4 text-slate-400 transition-transform"
                                            :class="personelOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                    <div x-show="personelOpen" x-transition.opacity.duration.150ms
                                        class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden"
                                        style="display:none;">
                                        <div class="p-2 border-b border-slate-100">
                                            <div class="relative">
                                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                                <input x-ref="personelInput" x-model="personelSearch" type="text"
                                                    placeholder="Cari personel..."
                                                    class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                            </div>
                                        </div>
                                        <div class="max-h-48 overflow-y-auto">
                                            <template x-for="p in filteredPersonels" :key="p.id">
                                                <div @click="selectPersonel(p)"
                                                    class="px-4 py-2.5 text-sm text-slate-700 hover:bg-emerald-50 cursor-pointer flex items-center justify-between transition-colors"
                                                    :class="personelSelected === p.id ? 'bg-emerald-50 text-emerald-700 font-semibold' : ''">
                                                    <span
                                                        x-text="p.nama + ' • ' + (p.jenis_bbm ? p.jenis_bbm : 'Belum set BBM')"></span>
                                                    <svg x-show="personelSelected === p.id"
                                                        class="w-4 h-4 text-emerald-500" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            </template>
                                            <div x-show="filteredPersonels.length === 0"
                                                class="px-4 py-3 text-sm text-slate-400 text-center">Tidak ditemukan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Tujuan Kendaraan (Searchable) -->
                            <div x-show="tipeTujuan === 'kendaraan'">
                                <label class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                                    <span
                                        class="flex items-center justify-center w-6 h-6 rounded-lg bg-emerald-100 text-emerald-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                    </span>
                                    Tujuan Kendaraan
                                </label>
                                <input type="hidden" name="tujuan_kendaraan_id" :value="tujuanKendaraanSelected" :required="tipeTujuan === 'kendaraan'">
                                <div class="relative" @click.outside="tujuanKendaraanOpen = false">
                                    <div @click="tujuanKendaraanOpen = !tujuanKendaraanOpen; $nextTick(() => { if(tujuanKendaraanOpen) $refs.tujuanKendaraanInput.focus() })"
                                        class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm cursor-pointer flex items-center justify-between transition-all"
                                        :class="tujuanKendaraanOpen ? 'ring-2 ring-emerald-500 border-emerald-500' : ''">
                                        <span x-text="tujuanKendaraanLabel || '— Pilih Kendaraan Tujuan —'"
                                            :class="tujuanKendaraanLabel ? 'text-slate-800' : 'text-slate-400'"></span>
                                        <svg class="w-4 h-4 text-slate-400 transition-transform"
                                            :class="tujuanKendaraanOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                    <div x-show="tujuanKendaraanOpen" x-transition.opacity.duration.150ms
                                        class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden"
                                        style="display:none;">
                                        <div class="p-2 border-b border-slate-100">
                                            <div class="relative">
                                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                                <input x-ref="tujuanKendaraanInput" x-model="tujuanKendaraanSearch" type="text"
                                                    placeholder="Cari nopol kendaraan..."
                                                    class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                            </div>
                                        </div>
                                        <div class="max-h-48 overflow-y-auto">
                                            <template x-for="k in filteredTujuanKendaraans" :key="k.id">
                                                <div @click="selectTujuanKendaraan(k)"
                                                    class="px-4 py-2.5 text-sm text-slate-700 hover:bg-emerald-50 cursor-pointer flex items-center justify-between transition-colors"
                                                    :class="tujuanKendaraanSelected === k.id ? 'bg-emerald-50 text-emerald-700 font-semibold' : ''">
                                                    <span x-text="k.no_polisi + ' • ' + k.jenis_bbm"></span>
                                                    <svg x-show="tujuanKendaraanSelected === k.id"
                                                        class="w-4 h-4 text-emerald-500" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            </template>
                                            <div x-show="filteredTujuanKendaraans.length === 0"
                                                class="px-4 py-3 text-sm text-slate-400 text-center">Tidak ditemukan atau BBM tidak cocok atau kendaraan sama
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="h-px bg-slate-100"></div>

                            <!-- Jumlah Transfer -->
                            <div>
                                <label for="jumlah"
                                    class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                                    <span
                                        class="flex items-center justify-center w-6 h-6 rounded-lg bg-amber-100 text-amber-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                            </path>
                                        </svg>
                                    </span>
                                    Jumlah Transfer
                                </label>
                                <div class="relative">
                                    <input type="number" name="jumlah" id="jumlah" required step="0.01" min="0.1" x-model="jumlahTransfer"
                                        class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all pr-16"
                                        :class="jumlahTransfer > selectedSourceSaldo ? 'ring-2 ring-red-500 border-red-500' : ''"
                                        placeholder="Masukkan jumlah">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                        <span
                                            class="text-xs font-bold text-slate-400 bg-slate-200/60 px-2 py-0.5 rounded-md">LITER</span>
                                    </div>
                                </div>
                                <div x-show="jumlahTransfer > selectedSourceSaldo" class="mt-1 text-[10px] text-red-500 font-bold flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Saldo tidak mencukupi! (Tersedia: <span x-text="selectedSourceSaldo"></span> L)
                                </div>
                            </div>

                            <!-- Keterangan -->
                            <div>
                                <label for="keterangan"
                                    class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                                    <span
                                        class="flex items-center justify-center w-6 h-6 rounded-lg bg-slate-100 text-slate-500">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                                            </path>
                                        </svg>
                                    </span>
                                    Keterangan
                                    <span class="text-xs font-normal text-slate-400">(opsional)</span>
                                </label>
                                <input type="text" name="keterangan" id="keterangan"
                                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                                    placeholder="Catatan tambahan...">
                            </div>
                        </div>

                        <!-- Footer -->
                        <div
                            class="px-4 sm:px-6 py-3 sm:py-4 bg-slate-50/80 border-t border-slate-100 flex flex-row-reverse gap-2 sm:gap-3 shrink-0">
                            <button type="submit" :disabled="jumlahTransfer > selectedSourceSaldo || jumlahTransfer <= 0"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-sm font-bold rounded-xl hover:from-emerald-600 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-lg shadow-emerald-500/25 transition-all duration-200 hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Konfirmasi Transfer
                            </button>
                            <button type="button" @click="showTransferModal = false"
                                class="inline-flex items-center px-5 py-2.5 bg-white text-slate-600 text-sm font-semibold rounded-xl border border-slate-200 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-300 transition-all duration-200">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Monthly Report Modal -->
        <div x-show="showMonthlyReportModal" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 py-6">
                <!-- Backdrop -->
                <div x-show="showMonthlyReportModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                    @click="showMonthlyReportModal = false"></div>

                <!-- Modal Panel -->
                <div x-show="showMonthlyReportModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-auto max-h-[90vh] flex flex-col overflow-hidden">
                    <form action="{{ route('satker.kendaraans.laporan-bulanan') }}" method="GET">
                        <!-- Header with Gradient -->
                        <div
                            class="bg-gradient-to-r from-rose-500 via-rose-600 to-rose-700 px-4 sm:px-6 py-4 sm:py-5 shrink-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-white">Laporan Bulanan</h3>
                                        <p class="text-rose-100 text-xs">Pilih Periode Laporan</p>
                                    </div>
                                </div>
                                <button type="button" @click="showMonthlyReportModal = false"
                                    class="p-1.5 hover:bg-white/20 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Form Body -->
                        <div class="px-4 sm:px-6 py-4 sm:py-5 space-y-4 overflow-y-auto flex-1">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Bulan</label>
                                <select name="bulan"
                                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500 transition-all appearance-none cursor-pointer">
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ now()->month == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Tahun</label>
                                <select name="tahun"
                                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500 transition-all appearance-none cursor-pointer">
                                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                                        <option value="{{ $y }}" {{ now()->year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div
                            class="px-4 sm:px-6 py-3 sm:py-4 bg-slate-50/80 border-t border-slate-100 flex flex-row-reverse gap-2 sm:gap-3 shrink-0">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-rose-500 to-rose-600 text-white text-sm font-bold rounded-xl hover:from-rose-600 hover:to-rose-700 shadow-lg shadow-rose-500/25 transition-all duration-200 hover:-translate-y-0.5">
                                Buka Laporan
                            </button>
                            <button type="button" @click="showMonthlyReportModal = false"
                                class="inline-flex items-center px-5 py-2.5 bg-white text-slate-600 text-sm font-semibold rounded-xl border border-slate-200 hover:bg-slate-50 transition-all duration-200">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Import Kendaraan Modal (Multi-Step) -->
        @if((\App\Models\Setting::where('key', 'satker_can_import_kendaraan')->value('value') ?? '1') == '1')
            <div x-cloak x-data="importKendaraanModal()" @open-import-kendaraan.window="openModal()"
                @turbo:before-cache.window="showModal = false">
                <!-- Backdrop -->
                <div x-show="showModal" style="display: none;" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50"
                    @click="closeModal()"></div>

                <!-- Modal -->
                <div x-show="showModal" style="display: none;" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                    class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4" @click.self="closeModal()">
                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden"
                        @click.stop>
                        <!-- Modal Header -->
                        <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gradient-to-r from-violet-500 to-indigo-600 shrink-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 sm:gap-3">
                                    <div class="p-1.5 sm:p-2 bg-white/20 rounded-xl">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-base sm:text-lg font-bold text-white">Import Data Kendaraan</h3>
                                        <p class="text-xs sm:text-sm text-violet-100">Upload file Excel untuk menambah data
                                            kendaraan</p>
                                    </div>
                                </div>
                                <button @click="closeModal()"
                                    class="p-1 text-white/70 hover:text-white rounded-lg hover:bg-white/10 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <!-- Step Indicator -->
                            <div class="flex items-center gap-2 mt-3">
                                <div class="flex items-center gap-1.5">
                                    <div :class="step >= 1 ? 'bg-white text-violet-600' : 'bg-white/30 text-white'"
                                        class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold transition-colors">
                                        1</div>
                                    <span class="text-xs text-white/80 hidden sm:inline">Upload</span>
                                </div>
                                <div :class="step >= 2 ? 'bg-white/60' : 'bg-white/20'"
                                    class="flex-1 h-0.5 rounded transition-colors"></div>
                                <div class="flex items-center gap-1.5">
                                    <div :class="step >= 2 ? 'bg-white text-violet-600' : 'bg-white/30 text-white'"
                                        class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold transition-colors">
                                        2</div>
                                    <span class="text-xs text-white/80 hidden sm:inline">Preview</span>
                                </div>
                                <div :class="step >= 3 ? 'bg-white/60' : 'bg-white/20'"
                                    class="flex-1 h-0.5 rounded transition-colors"></div>
                                <div class="flex items-center gap-1.5">
                                    <div :class="step >= 3 ? 'bg-white text-violet-600' : 'bg-white/30 text-white'"
                                        class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold transition-colors">
                                        3</div>
                                    <span class="text-xs text-white/80 hidden sm:inline">Confirm</span>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Body (Scrollable) -->
                        <div class="p-4 sm:p-6 overflow-y-auto flex-1">

                            <!-- STEP 1: Upload File -->
                            <div x-show="step === 1" x-transition>
                                <!-- Info -->
                                <div
                                    class="flex items-start gap-2 sm:gap-3 p-3 sm:p-4 bg-violet-50 rounded-xl border border-violet-200 mb-4">
                                    <div class="p-1 sm:p-1.5 bg-violet-100 text-violet-600 rounded-lg mt-0.5 shrink-0">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-[10px] sm:text-xs text-violet-700">
                                        <p class="font-semibold mb-1">Format file Excel (header di baris ke-1):</p>
                                        <ul class="list-disc list-inside space-y-0.5">
                                            <li>Kolom <strong>NO</strong> — Nomor urut</li>
                                            <li>Kolom <strong>JENIS KENDARAAN</strong> — Tipe kendaraan</li>
                                            <li>Kolom <strong>NOPOL</strong> — Nomor polisi kendaraan</li>
                                            <li>Kolom <strong>JENIS BBM</strong> — Pertamax / Pertamina Dex</li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Download Template -->
                                <a href="{{ route('satker.kendaraans.download-template') }}"
                                    class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-violet-600 hover:text-violet-800 transition mb-4">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Download Template Excel
                                </a>

                                <!-- Drag & Drop Area -->
                                <div class="relative border-2 border-dashed rounded-xl p-6 sm:p-8 text-center transition-all"
                                    :class="isDragging ? 'border-violet-400 bg-violet-50 scale-[1.02]' : (selectedFile ? 'border-emerald-300 bg-emerald-50' : 'border-slate-300 hover:border-violet-400 hover:bg-violet-50/50')"
                                    @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false"
                                    @drop.prevent="handleDrop($event)">
                                    <input type="file" x-ref="fileInput" accept=".xlsx,.xls,.csv" class="hidden"
                                        @change="handleFileSelect($event)">

                                    <template x-if="!selectedFile">
                                        <div>
                                            <div
                                                class="mx-auto w-12 h-12 sm:w-14 sm:h-14 bg-violet-100 rounded-2xl flex items-center justify-center mb-3">
                                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-violet-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                    </path>
                                                </svg>
                                            </div>
                                            <p class="text-sm font-semibold text-slate-700 mb-1">Drag & drop file Excel di
                                                sini
                                            </p>
                                            <p class="text-xs text-slate-400 mb-3">atau</p>
                                            <button @click="$refs.fileInput.click()" type="button"
                                                class="px-4 py-2 bg-violet-600 text-white text-xs sm:text-sm font-semibold rounded-lg hover:bg-violet-700 transition shadow-md shadow-violet-500/20">
                                                Pilih File
                                            </button>
                                            <p class="mt-3 text-[10px] sm:text-xs text-slate-400">Maksimal 2MB. Format:
                                                .xlsx,
                                                .xls, .csv</p>
                                        </div>
                                    </template>

                                    <template x-if="selectedFile">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="text-left flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-slate-800 truncate"
                                                    x-text="selectedFile.name"></p>
                                                <p class="text-xs text-slate-400"
                                                    x-text="formatFileSize(selectedFile.size)">
                                                </p>
                                            </div>
                                            <button @click="clearFile()" type="button"
                                                class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>

                                <!-- Error Message -->
                                <div x-show="uploadError" x-transition
                                    class="mt-3 p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-600"
                                    x-text="uploadError"></div>
                            </div>

                            <!-- STEP 2: Preview Result -->
                            <div x-show="step === 2" x-transition>
                                <!-- Loading -->
                                <div x-show="isLoading" class="flex flex-col items-center justify-center py-8">
                                    <div
                                        class="w-10 h-10 border-4 border-violet-200 border-t-violet-600 rounded-full animate-spin mb-3">
                                    </div>
                                    <p class="text-sm text-slate-500">Menganalisis file...</p>
                                </div>

                                <div x-show="!isLoading && previewData">
                                    <!-- Summary Cards -->
                                    <div class="grid grid-cols-3 gap-2 sm:gap-3 mb-4">
                                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 text-center">
                                            <p class="text-lg sm:text-2xl font-black text-emerald-600"
                                                x-text="previewData?.new_count || 0"></p>
                                            <p
                                                class="text-[10px] sm:text-xs font-semibold text-emerald-500 uppercase tracking-wider">
                                                Data Baru</p>
                                        </div>
                                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-center">
                                            <p class="text-lg sm:text-2xl font-black text-amber-600"
                                                x-text="previewData?.duplicate_count || 0"></p>
                                            <p
                                                class="text-[10px] sm:text-xs font-semibold text-amber-500 uppercase tracking-wider">
                                                Duplikat</p>
                                        </div>
                                        <div class="bg-red-50 border border-red-200 rounded-xl p-3 text-center">
                                            <p class="text-lg sm:text-2xl font-black text-red-600"
                                                x-text="previewData?.error_count || 0"></p>
                                            <p
                                                class="text-[10px] sm:text-xs font-semibold text-red-500 uppercase tracking-wider">
                                                Error</p>
                                        </div>
                                    </div>

                                    <!-- New Entries Table -->
                                    <template x-if="previewData?.new_entries?.length > 0">
                                        <div class="mb-4">
                                            <h4
                                                class="text-xs sm:text-sm font-bold text-emerald-700 mb-2 flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                Data Baru
                                            </h4>
                                            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                                                <div class="overflow-x-auto">
                                                    <table class="w-full text-xs">
                                                        <thead class="bg-slate-50">
                                                            <tr>
                                                                <th
                                                                    class="px-3 py-2 text-left font-semibold text-slate-600">
                                                                    Nopol</th>
                                                                <th
                                                                    class="px-3 py-2 text-left font-semibold text-slate-600">
                                                                    Jenis Kendaraan</th>
                                                                <th
                                                                    class="px-3 py-2 text-left font-semibold text-slate-600">
                                                                    Jenis BBM</th>
                                                                <th
                                                                    class="px-3 py-2 text-left font-semibold text-slate-600">
                                                                    Satker</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-slate-100">
                                                            <template x-for="entry in previewData.new_entries.slice(0, 10)"
                                                                :key="entry.row">
                                                                <tr class="hover:bg-emerald-50/50">
                                                                    <td class="px-3 py-2 font-mono font-semibold"
                                                                        x-text="entry.no_polisi"></td>
                                                                    <td class="px-3 py-2" x-text="entry.jenis_kendaraan">
                                                                    </td>
                                                                    <td class="px-3 py-2" x-text="entry.jenis_bbm"></td>
                                                                    <td class="px-3 py-2" x-text="entry.satker"></td>
                                                                </tr>
                                                            </template>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <template x-if="previewData.new_entries.length > 10">
                                                    <div class="px-3 py-2 bg-slate-50 text-xs text-slate-500 text-center">
                                                        <span
                                                            x-text="'... dan ' + (previewData.new_entries.length - 10) + ' data lainnya'"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Duplicates Table -->
                                    <template x-if="previewData?.duplicates?.length > 0">
                                        <div class="mb-4">
                                            <h4
                                                class="text-xs sm:text-sm font-bold text-amber-700 mb-2 flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z">
                                                    </path>
                                                </svg>
                                                Data Duplikat
                                            </h4>
                                            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                                                <div class="overflow-x-auto">
                                                    <table class="w-full text-xs">
                                                        <thead class="bg-slate-50">
                                                            <tr>
                                                                <th
                                                                    class="px-3 py-2 text-left font-semibold text-slate-600">
                                                                    Nopol</th>
                                                                <th
                                                                    class="px-3 py-2 text-left font-semibold text-slate-600">
                                                                    Field</th>
                                                                <th
                                                                    class="px-3 py-2 text-left font-semibold text-slate-600">
                                                                    Data Lama</th>
                                                                <th
                                                                    class="px-3 py-2 text-left font-semibold text-slate-600">
                                                                    Data Baru</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-slate-100">
                                                            <template x-for="dup in previewData.duplicates.slice(0, 10)"
                                                                :key="dup.row">
                                                                <template x-if="dup.has_changes">
                                                                    <template x-for="(change, ci) in dup.changes" :key="ci">
                                                                        <tr class="hover:bg-amber-50/50">
                                                                            <td class="px-3 py-2 font-mono font-semibold"
                                                                                x-text="ci === 0 ? dup.no_polisi : ''"></td>
                                                                            <td class="px-3 py-2 font-semibold"
                                                                                x-text="change.field"></td>
                                                                            <td class="px-3 py-2 text-red-500 line-through"
                                                                                x-text="change.old"></td>
                                                                            <td class="px-3 py-2 text-emerald-600 font-semibold"
                                                                                x-text="change.new"></td>
                                                                        </tr>
                                                                    </template>
                                                                </template>
                                                            </template>
                                                            <template
                                                                x-for="dup in previewData.duplicates.filter(d => !d.has_changes).slice(0, 5)"
                                                                :key="'nochange-'+dup.row">
                                                                <tr class="hover:bg-slate-50">
                                                                    <td class="px-3 py-2 font-mono font-semibold"
                                                                        x-text="dup.no_polisi"></td>
                                                                    <td colspan="3" class="px-3 py-2 text-slate-400 italic">
                                                                        Tidak ada perubahan</td>
                                                                </tr>
                                                            </template>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <template x-if="previewData.duplicates.length > 10">
                                                    <div class="px-3 py-2 bg-slate-50 text-xs text-slate-500 text-center">
                                                        <span
                                                            x-text="'... dan ' + (previewData.duplicates.length - 10) + ' duplikat lainnya'"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Errors List -->
                                    <template x-if="previewData?.errors?.length > 0">
                                        <div class="mb-4">
                                            <h4
                                                class="text-xs sm:text-sm font-bold text-red-700 mb-2 flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                    </path>
                                                </svg>
                                                Error Validasi
                                            </h4>
                                            <div
                                                class="bg-red-50 border border-red-200 rounded-xl p-3 space-y-1 max-h-32 overflow-y-auto">
                                                <template x-for="(err, i) in previewData.errors.slice(0, 10)" :key="i">
                                                    <p class="text-xs text-red-600" x-text="err"></p>
                                                </template>
                                                <template x-if="previewData.errors.length > 10">
                                                    <p class="text-xs text-red-400 italic"
                                                        x-text="'... dan ' + (previewData.errors.length - 10) + ' error lainnya'">
                                                    </p>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- STEP 3: Confirm & Select Duplicate Action -->
                            <div x-show="step === 3" x-transition>
                                <div class="space-y-4">
                                    <!-- Summary -->
                                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                                        <p class="text-sm font-semibold text-slate-700 mb-2">Ringkasan Import:</p>
                                        <ul class="text-xs text-slate-600 space-y-1">
                                            <li class="flex items-center gap-2">
                                                <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                                <span
                                                    x-text="(previewData?.new_count || 0) + ' kendaraan baru akan ditambahkan'"></span>
                                            </li>
                                            <li class="flex items-center gap-2" x-show="previewData?.duplicate_count > 0">
                                                <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                                                <span
                                                    x-text="(previewData?.duplicate_count || 0) + ' kendaraan duplikat ditemukan'"></span>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Duplicate Action Selection -->
                                    <template x-if="previewData?.duplicate_count > 0">
                                        <div>
                                            <p class="text-xs sm:text-sm font-bold text-slate-700 mb-3">Apa yang ingin
                                                dilakukan
                                                dengan data duplikat?</p>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                <button @click="duplicateAction = 'skip'" type="button"
                                                    :class="duplicateAction === 'skip' ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-500/20' : 'border-slate-200 hover:border-slate-300'"
                                                    class="p-3 sm:p-4 border-2 rounded-xl text-left transition-all">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                                                        </svg>
                                                        <span class="text-sm font-bold text-slate-800">Lewati (Skip)</span>
                                                    </div>
                                                    <p class="text-xs text-slate-500">Data duplikat tidak diubah, hanya data
                                                        baru yang ditambahkan.</p>
                                                </button>
                                                <button @click="duplicateAction = 'update'" type="button"
                                                    :class="duplicateAction === 'update' ? 'border-amber-500 bg-amber-50 ring-2 ring-amber-500/20' : 'border-slate-200 hover:border-slate-300'"
                                                    class="p-3 sm:p-4 border-2 rounded-xl text-left transition-all">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <svg class="w-5 h-5 text-amber-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                            </path>
                                                        </svg>
                                                        <span class="text-sm font-bold text-slate-800">Perbarui
                                                            (Update)</span>
                                                    </div>
                                                    <p class="text-xs text-slate-500">Data duplikat akan diperbarui dengan
                                                        data
                                                        dari file Excel.</p>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="px-4 sm:px-6 py-3 sm:py-4 bg-slate-50 border-t border-slate-200 shrink-0">
                            <div class="flex gap-2 sm:gap-3">
                                <button @click="step > 1 ? step-- : closeModal()" type="button"
                                    class="flex-1 px-3 sm:px-4 py-2.5 sm:py-3 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors"
                                    x-text="step > 1 ? 'Kembali' : 'Batal'"></button>

                                <!-- Step 1: Preview Button -->
                                <button x-show="step === 1" @click="previewImport()" type="button"
                                    :disabled="!selectedFile || isLoading"
                                    class="flex-1 px-3 sm:px-4 py-2.5 sm:py-3 bg-gradient-to-r from-violet-500 to-indigo-600 text-white text-sm font-bold rounded-xl hover:from-violet-600 hover:to-indigo-700 shadow-lg shadow-violet-500/30 hover:shadow-violet-500/40 transition-all hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                                    <span x-show="!isLoading">🔍 Preview Import</span>
                                    <span x-show="isLoading">⏳ Menganalisis...</span>
                                </button>

                                <!-- Step 2: Next Button -->
                                <button x-show="step === 2" @click="step = 3" type="button"
                                    :disabled="!previewData || (previewData.new_count === 0 && previewData.duplicate_count === 0)"
                                    class="flex-1 px-3 sm:px-4 py-2.5 sm:py-3 bg-gradient-to-r from-violet-500 to-indigo-600 text-white text-sm font-bold rounded-xl hover:from-violet-600 hover:to-indigo-700 shadow-lg shadow-violet-500/30 hover:shadow-violet-500/40 transition-all hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                                    Lanjutkan ➡️
                                </button>

                                <!-- Step 3: Confirm Import -->
                                <button x-show="step === 3" @click="confirmImport()" type="button"
                                    :disabled="isImporting || (previewData?.duplicate_count > 0 && !duplicateAction)"
                                    class="flex-1 px-3 sm:px-4 py-2.5 sm:py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-sm font-bold rounded-xl hover:from-emerald-600 hover:to-teal-700 shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/40 transition-all hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                                    <span x-show="!isImporting">✅ Import Sekarang</span>
                                    <span x-show="isImporting">⏳ Memproses...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                window.importKendaraanModal = function() {
                    return {
                        showModal: false,
                        step: 1,
                        selectedFile: null,
                        isDragging: false,
                        isLoading: false,
                        isImporting: false,
                        uploadError: '',
                        previewData: null,
                        duplicateAction: 'skip',

                        openModal() {
                            this.showModal = true;
                            this.resetState();
                        },

                        closeModal() {
                            this.showModal = false;
                            this.resetState();
                        },

                        resetState() {
                            this.step = 1;
                            this.selectedFile = null;
                            this.isDragging = false;
                            this.isLoading = false;
                            this.isImporting = false;
                            this.uploadError = '';
                            this.previewData = null;
                            this.duplicateAction = 'skip';
                        },

                        handleDrop(e) {
                            this.isDragging = false;
                            const files = e.dataTransfer.files;
                            if (files.length > 0) {
                                this.validateAndSetFile(files[0]);
                            }
                        },

                        handleFileSelect(e) {
                            if (e.target.files.length > 0) {
                                this.validateAndSetFile(e.target.files[0]);
                            }
                        },

                        validateAndSetFile(file) {
                            this.uploadError = '';
                            const allowedExt = ['.xlsx', '.xls', '.csv'];
                            const ext = '.' + file.name.split('.').pop().toLowerCase();

                            if (!allowedExt.includes(ext)) {
                                this.uploadError = 'Format file tidak didukung. Gunakan .xlsx, .xls, atau .csv';
                                return;
                            }
                            if (file.size > 2 * 1024 * 1024) {
                                this.uploadError = 'Ukuran file melebihi batas 2MB.';
                                return;
                            }
                            this.selectedFile = file;
                        },

                        clearFile() {
                            this.selectedFile = null;
                            this.uploadError = '';
                            if (this.$refs.fileInput) this.$refs.fileInput.value = '';
                        },

                        formatFileSize(bytes) {
                            if (bytes < 1024) return bytes + ' B';
                            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
                            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
                        },

                        async previewImport() {
                            if (!this.selectedFile) return;
                            this.isLoading = true;
                            this.uploadError = '';
                            this.step = 2;

                            const formData = new FormData();
                            formData.append('file', this.selectedFile);

                            try {
                                const resp = await fetch('{{ route("satker.kendaraans.preview-import") }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json',
                                    },
                                    body: formData
                                });

                                if (!resp.ok) {
                                    const errData = await resp.json().catch(() => null);
                                    throw new Error(errData?.message || 'Gagal menganalisis file. Periksa format dan coba lagi.');
                                }

                                this.previewData = await resp.json();
                            } catch (err) {
                                this.uploadError = err.message;
                                this.step = 1;
                            } finally {
                                this.isLoading = false;
                            }
                        },

                        confirmImport() {
                            if (!this.selectedFile) return;
                            this.isImporting = true;

                            const formData = new FormData();
                            formData.append('file', this.selectedFile);
                            formData.append('duplicate_action', this.previewData?.duplicate_count > 0 ? this.duplicateAction : 'skip');
                            formData.append('_token', '{{ csrf_token() }}');

                            // Create a hidden form and submit traditionally (for redirect)
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ route("satker.kendaraans.import") }}';
                            form.enctype = 'multipart/form-data';
                            form.style.display = 'none';
                            form.setAttribute('data-turbo', 'false');

                            // CSRF
                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = '{{ csrf_token() }}';
                            form.appendChild(csrfInput);

                            // Duplicate action
                            const actionInput = document.createElement('input');
                            actionInput.type = 'hidden';
                            actionInput.name = 'duplicate_action';
                            actionInput.value = this.previewData?.duplicate_count > 0 ? this.duplicateAction : 'skip';
                            form.appendChild(actionInput);

                            // File - use DataTransfer to attach file
                            const fileInput = document.createElement('input');
                            fileInput.type = 'file';
                            fileInput.name = 'file';
                            const dt = new DataTransfer();
                            dt.items.add(this.selectedFile);
                            fileInput.files = dt.files;
                            form.appendChild(fileInput);

                            document.body.appendChild(form);
                            form.submit();
                        }
                    }
                }
            </script>
        @endif
    </div>

    <script>
        // Bulk Delete Checkboxes
        const checkAll = document.getElementById('checkAll');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const bulkActionsBar = document.getElementById('bulkActionsBar');
        const selectedCountEl = document.getElementById('selectedCount');

        function updateBulkUI() {
            const checked = document.querySelectorAll('.item-checkbox:checked');
            selectedCountEl.textContent = checked.length;
            bulkActionsBar.classList.toggle('hidden', checked.length === 0);
            checkAll.checked = checked.length === itemCheckboxes.length && itemCheckboxes.length > 0;
            checkAll.indeterminate = checked.length > 0 && checked.length < itemCheckboxes.length;
        }

        if (checkAll) {
            checkAll.addEventListener('change', function () {
                itemCheckboxes.forEach(cb => cb.checked = this.checked);
                updateBulkUI();
            });
        }

        itemCheckboxes.forEach(cb => cb.addEventListener('change', updateBulkUI));

        function bulkDeleteKendaraan() {
            const checked = document.querySelectorAll('.item-checkbox:checked');
            if (checked.length === 0) return;

            Swal.fire({
                title: 'Hapus ' + checked.length + ' Kendaraan?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const inputsDiv = document.getElementById('bulkDeleteInputs');
                    inputsDiv.innerHTML = '';
                    checked.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = cb.value;
                        inputsDiv.appendChild(input);
                    });
                    document.getElementById('bulkDeleteForm').submit();
                }
            });
        }
    </script>

</x-app-layout>