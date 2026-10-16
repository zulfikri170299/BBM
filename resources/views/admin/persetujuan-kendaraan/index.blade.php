<x-app-layout>
    <div class="py-6 px-2 sm:px-6 lg:px-8 min-h-screen" x-data="{
        selectedIds: [],
        allSelected: false,
        rejectModalOpen: false,
        rejectSingleId: null,
        rejectSingleNopol: '',
        rejectBulkModalOpen: false,
        rejectionReason: '',

        toggleAll() {
            if (this.allSelected) {
                this.selectedIds = Array.from(document.querySelectorAll('.item-checkbox')).map(el => el.value);
            } else {
                this.selectedIds = [];
            }
        },
        openRejectModal(id, nopol) {
            this.rejectSingleId = id;
            this.rejectSingleNopol = nopol;
            this.rejectionReason = '';
            this.rejectModalOpen = true;
        },
        openBulkRejectModal() {
            if (this.selectedIds.length === 0) return;
            this.rejectionReason = '';
            this.rejectBulkModalOpen = true;
        }
    }">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Header Card -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-5 rounded-2xl shadow-sm">
                <div>
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-brand-primary/10 text-brand-primary dark:bg-white/10 dark:text-white rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white">Persetujuan Data Kendaraan</h1>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Tinjau dan setujui penambahan, pembaruan, serta import kendaraan dari Admin Satker</p>
                        </div>
                    </div>
                </div>

                <!-- Stats Quick Cards -->
                <div class="flex items-center gap-2 self-stretch sm:self-auto overflow-x-auto pb-1 sm:pb-0">
                    <div class="px-3.5 py-2 bg-amber-500/10 border border-amber-500/20 rounded-xl text-center flex-1 sm:flex-initial min-w-[90px]">
                        <p class="text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider">Menunggu</p>
                        <p class="text-lg font-black text-amber-600 dark:text-amber-400 leading-none mt-0.5">{{ number_format($counts['pending']) }}</p>
                    </div>
                    <div class="px-3.5 py-2 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-center flex-1 sm:flex-initial min-w-[90px]">
                        <p class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Disetujui</p>
                        <p class="text-lg font-black text-emerald-600 dark:text-emerald-400 leading-none mt-0.5">{{ number_format($counts['disetujui']) }}</p>
                    </div>
                    <div class="px-3.5 py-2 bg-rose-500/10 border border-rose-500/20 rounded-xl text-center flex-1 sm:flex-initial min-w-[90px]">
                        <p class="text-[10px] font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider">Ditolak</p>
                        <p class="text-lg font-black text-rose-600 dark:text-rose-400 leading-none mt-0.5">{{ number_format($counts['ditolak']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Tab Status Navigation -->
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 dark:border-white/10 pb-3">
                <div class="flex items-center gap-1.5 p-1 bg-slate-100 dark:bg-slate-800/60 rounded-xl">
                    <a href="{{ route('admin.persetujuan-kendaraan.index', array_merge(request()->except(['page']), ['status' => 'pending'])) }}"
                        class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-2 {{ $status === 'pending' ? 'bg-white dark:bg-slate-900 text-amber-600 dark:text-amber-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-white dark:hover:text-white' }}">
                        <span>Menunggu Persetujuan</span>
                        @if($counts['pending'] > 0)
                            <span class="px-1.5 py-0.2 bg-amber-500 text-white text-[10px] rounded-full font-black">{{ $counts['pending'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.persetujuan-kendaraan.index', array_merge(request()->except(['page']), ['status' => 'disetujui'])) }}"
                        class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-2 {{ $status === 'disetujui' ? 'bg-white dark:bg-slate-900 text-emerald-600 dark:text-emerald-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-white dark:hover:text-white' }}">
                        <span>Disetujui</span>
                        <span class="text-[10px] opacity-75">({{ $counts['disetujui'] }})</span>
                    </a>
                    <a href="{{ route('admin.persetujuan-kendaraan.index', array_merge(request()->except(['page']), ['status' => 'ditolak'])) }}"
                        class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-2 {{ $status === 'ditolak' ? 'bg-white dark:bg-slate-900 text-rose-600 dark:text-rose-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-white dark:hover:text-white' }}">
                        <span>Ditolak</span>
                        <span class="text-[10px] opacity-75">({{ $counts['ditolak'] }})</span>
                    </a>
                    <a href="{{ route('admin.persetujuan-kendaraan.index', array_merge(request()->except(['page']), ['status' => 'all'])) }}"
                        class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all {{ $status === 'all' ? 'bg-white dark:bg-slate-900 text-brand-primary dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-white dark:hover:text-white' }}">
                        Semua Data
                    </a>
                </div>

                <!-- Bulk Actions (Shown when rows are selected) -->
                <div x-show="selectedIds.length > 0" x-cloak class="flex items-center gap-2 animate-fade-in">
                    <span class="text-xs font-bold text-slate-600 dark:text-slate-400">
                        <span x-text="selectedIds.length"></span> dipilih:
                    </span>
                    <form action="{{ route('admin.persetujuan-kendaraan.bulk-approve') }}" method="POST" class="inline" onsubmit="event.preventDefault(); window.confirmDialog({
    title: 'Setujui Semua Terpilih?',
    message: 'Apakah Anda yakin ingin menyetujui seluruh kendaraan yang telah dipilih?',
    type: 'question',
    confirmText: 'Ya, Setujui Semua',
    cancelText: 'Batal'
}, () => this.submit());">
                        @csrf
                        <template x-for="id in selectedIds" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm flex items-center gap-1.5 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Setujui Terpilih
                        </button>
                    </form>
                    <button type="button" @click="openBulkRejectModal()" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg shadow-sm flex items-center gap-1.5 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Tolak Terpilih
                    </button>
                </div>
            </div>

            <!-- Filter Toolbar -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-3.5 sm:p-4 rounded-2xl shadow-sm relative z-30"
                x-data="{
                    satkerOpen: false,
                    satkerSearch: '',
                    selectedSatkerId: '{{ $satkerId ?? '' }}',
                    selectedSatkerName: '{{ $satkers->firstWhere('id', $satkerId)->nama_satker ?? 'Semua Satker' }}',
                    aksiOpen: false,
                    selectedAksi: '{{ $aksi ?? '' }}',
                    selectedAksiLabel: '{{ $aksi === 'tambah' ? 'Tambah Baru' : ($aksi === 'edit' ? 'Edit Data' : ($aksi === 'import' ? 'Import Excel' : 'Semua Aksi')) }}',
                    satkers: {{ json_encode($satkers->map(fn($s) => ['id' => (string)$s->id, 'nama' => $s->nama_satker])) }},
                    get filteredSatkers() {
                        if (!this.satkerSearch) return this.satkers;
                        const q = this.satkerSearch.toLowerCase();
                        return this.satkers.filter(s => s.nama.toLowerCase().includes(q));
                    },
                    selectSatker(id, name) {
                        this.selectedSatkerId = id;
                        this.selectedSatkerName = name;
                        this.satkerOpen = false;
                        this.satkerSearch = '';
                        $nextTick(() => $refs.filterForm.submit());
                    },
                    selectAksi(val, label) {
                        this.selectedAksi = val;
                        this.selectedAksiLabel = label;
                        this.aksiOpen = false;
                        $nextTick(() => $refs.filterForm.submit());
                    }
                }">
                <form x-ref="filterForm" action="{{ route('admin.persetujuan-kendaraan.index') }}" method="GET">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="hidden" name="satker_id" :value="selectedSatkerId">
                    <input type="hidden" name="aksi" :value="selectedAksi">
                    
                    <div class="flex flex-col lg:flex-row items-stretch lg:items-center gap-2.5 w-full">
                        
                        <!-- Search Input -->
                        <div class="relative flex-1 min-w-[200px]">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Cari NOPOL, jenis kendaraan, atau pengaju..."
                                class="w-full pl-9 pr-9 py-2.5 bg-slate-50 dark:bg-slate-800/70 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-medium text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all">
                            @if(!empty($search))
                                <a href="{{ route('admin.persetujuan-kendaraan.index', request()->except('search', 'page')) }}"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200" title="Hapus pencarian">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </a>
                            @endif
                        </div>

                        <!-- Custom Searchable Satker Dropdown -->
                        <div class="relative w-full lg:w-64 shrink-0" @click.outside="satkerOpen = false">
                            <button type="button" @click="satkerOpen = !satkerOpen; $nextTick(() => { if(satkerOpen) $refs.satkerInput.focus() })"
                                class="w-full py-2.5 px-3 bg-slate-50 dark:bg-slate-800/70 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-medium text-left flex items-center justify-between transition-all hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-primary"
                                :class="{ 'ring-2 ring-brand-primary border-transparent': satkerOpen, 'border-brand-primary/50 text-brand-primary': selectedSatkerId !== '' }">
                                <div class="flex items-center gap-2 truncate pr-2">
                                    <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <span class="truncate font-semibold" :class="selectedSatkerId ? 'text-slate-800 dark:text-white' : 'text-slate-500 dark:text-slate-400'" x-text="selectedSatkerName"></span>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <template x-if="selectedSatkerId">
                                        <span @click.stop="selectSatker('', 'Semua Satker')" class="p-0.5 rounded-full hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-400 hover:text-rose-500 transition-colors" title="Reset Satker">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </span>
                                    </template>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="satkerOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </button>

                            <!-- Dropdown Menu Satker -->
                            <div x-show="satkerOpen" x-cloak
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                                class="absolute left-0 right-0 mt-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl z-50 overflow-hidden flex flex-col max-h-80">
                                
                                <!-- Search input inside dropdown -->
                                <div class="p-2.5 border-b border-slate-200 dark:border-white/10 bg-slate-50/70 dark:bg-slate-800/40 sticky top-0 z-10">
                                    <div class="relative">
                                        <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        <input type="text" x-ref="satkerInput" x-model="satkerSearch" placeholder="Cari nama satker..."
                                            class="w-full pl-8 pr-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-medium text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-brand-primary">
                                    </div>
                                </div>

                                <!-- Satker List -->
                                <div class="overflow-y-auto divide-y divide-slate-100 dark:divide-white/5 custom-scrollbar p-1">
                                    <button type="button" @click="selectSatker('', 'Semua Satker')"
                                        class="w-full text-left px-3 py-2 rounded-lg text-xs flex items-center justify-between transition-colors"
                                        :class="selectedSatkerId === '' ? 'bg-brand-primary/10 text-brand-primary dark:bg-brand-primary/20 dark:text-white font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'">
                                        <span class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full" :class="selectedSatkerId === '' ? 'bg-brand-primary' : 'bg-slate-300 dark:bg-slate-600'"></span>
                                            Semua Satker
                                        </span>
                                        <template x-if="selectedSatkerId === ''">
                                            <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </template>
                                    </button>

                                    <template x-for="s in filteredSatkers" :key="s.id">
                                        <button type="button" @click="selectSatker(s.id, s.nama)"
                                            class="w-full text-left px-3 py-2 rounded-lg text-xs flex items-center justify-between transition-colors"
                                            :class="selectedSatkerId === s.id ? 'bg-brand-primary/10 text-brand-primary dark:bg-brand-primary/20 dark:text-white font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'">
                                            <span class="truncate pr-2" x-text="s.nama"></span>
                                            <template x-if="selectedSatkerId === s.id">
                                                <svg class="w-4 h-4 text-brand-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </template>
                                        </button>
                                    </template>

                                    <template x-if="filteredSatkers.length === 0">
                                        <div class="px-3 py-4 text-center text-xs text-slate-400">
                                            Tidak ditemukan satker "<span x-text="satkerSearch" class="font-bold"></span>"
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Custom Aksi Dropdown -->
                        <div class="relative w-full lg:w-48 shrink-0" @click.outside="aksiOpen = false">
                            <button type="button" @click="aksiOpen = !aksiOpen"
                                class="w-full py-2.5 px-3 bg-slate-50 dark:bg-slate-800/70 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-medium text-left flex items-center justify-between transition-all hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-primary"
                                :class="{ 'ring-2 ring-brand-primary border-transparent': aksiOpen, 'border-brand-primary/50 text-brand-primary': selectedAksi !== '' }">
                                <div class="flex items-center gap-2 truncate pr-2">
                                    <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                    </svg>
                                    <span class="truncate font-semibold" :class="selectedAksi ? 'text-slate-800 dark:text-white' : 'text-slate-500 dark:text-slate-400'" x-text="selectedAksiLabel"></span>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <template x-if="selectedAksi">
                                        <span @click.stop="selectAksi('', 'Semua Aksi')" class="p-0.5 rounded-full hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-400 hover:text-rose-500 transition-colors" title="Reset Aksi">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </span>
                                    </template>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="aksiOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </button>

                            <!-- Dropdown Menu Aksi -->
                            <div x-show="aksiOpen" x-cloak
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                                class="absolute left-0 right-0 mt-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl z-50 overflow-hidden p-1.5 space-y-1">
                                
                                <button type="button" @click="selectAksi('', 'Semua Aksi')"
                                    class="w-full text-left px-3 py-2 rounded-lg text-xs flex items-center justify-between transition-colors"
                                    :class="selectedAksi === '' ? 'bg-brand-primary/10 text-brand-primary dark:bg-brand-primary/20 dark:text-white font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'">
                                    <span class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full" :class="selectedAksi === '' ? 'bg-brand-primary' : 'bg-slate-300 dark:bg-slate-600'"></span>
                                        Semua Aksi
                                    </span>
                                    <template x-if="selectedAksi === ''">
                                        <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </template>
                                </button>

                                <button type="button" @click="selectAksi('tambah', 'Tambah Baru')"
                                    class="w-full text-left px-3 py-2 rounded-lg text-xs flex items-center justify-between transition-colors"
                                    :class="selectedAksi === 'tambah' ? 'bg-brand-primary/10 text-brand-primary dark:bg-brand-primary/20 dark:text-white font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'">
                                    <span class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                        Tambah Baru
                                    </span>
                                    <template x-if="selectedAksi === 'tambah'">
                                        <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </template>
                                </button>

                                <button type="button" @click="selectAksi('edit', 'Edit Data')"
                                    class="w-full text-left px-3 py-2 rounded-lg text-xs flex items-center justify-between transition-colors"
                                    :class="selectedAksi === 'edit' ? 'bg-brand-primary/10 text-brand-primary dark:bg-brand-primary/20 dark:text-white font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'">
                                    <span class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                        Edit Data
                                    </span>
                                    <template x-if="selectedAksi === 'edit'">
                                        <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </template>
                                </button>

                                <button type="button" @click="selectAksi('import', 'Import Excel')"
                                    class="w-full text-left px-3 py-2 rounded-lg text-xs flex items-center justify-between transition-colors"
                                    :class="selectedAksi === 'import' ? 'bg-brand-primary/10 text-brand-primary dark:bg-brand-primary/20 dark:text-white font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'">
                                    <span class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        Import Excel
                                    </span>
                                    <template x-if="selectedAksi === 'import'">
                                        <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </template>
                                </button>
                            </div>
                        </div>

                        <!-- Reset / Apply Action Buttons -->
                        <div class="flex items-center gap-2 shrink-0">
                            <button type="submit" class="py-2.5 px-4 bg-brand-primary hover:bg-brand-primary/90 text-white text-xs font-bold rounded-xl transition shadow-sm text-center flex items-center justify-center gap-1.5 whitespace-nowrap">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                                Filter
                            </button>
                            
                            @if(!empty($search) || !empty($satkerId) || !empty($aksi))
                                <a href="{{ route('admin.persetujuan-kendaraan.index', ['status' => $status]) }}"
                                    class="py-2.5 px-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-semibold rounded-xl transition flex items-center justify-center shrink-0"
                                    title="Reset Semua Filter">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 uppercase tracking-wider text-[10px] font-black">
                                <th class="py-3 px-4 w-10 text-center">
                                    <input type="checkbox" x-model="allSelected" @change="toggleAll()" class="rounded border-slate-300 dark:border-white/20 text-brand-primary focus:ring-brand-primary scale-95">
                                </th>
                                <th class="py-3 px-4">Satker & Pengaju</th>
                                <th class="py-3 px-4">Tipe Pengajuan</th>
                                <th class="py-3 px-4">Data Kendaraan</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 text-center">Aksi Persetujuan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($kendaraans as $k)
                                @php
                                    $isPending = $k->status_approval === 'pending';
                                    $isEdit = $k->aksi_approval === 'edit';
                                    $changes = $isEdit && !empty($k->pending_changes) 
                                        ? (is_array($k->pending_changes) ? $k->pending_changes : json_decode($k->pending_changes, true)) 
                                        : [];
                                @endphp
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-colors {{ $isPending ? 'bg-amber-500/[0.02]' : '' }}">
                                    <!-- Checkbox -->
                                    <td class="py-3.5 px-4 text-center">
                                        <input type="checkbox" value="{{ $k->id }}" x-model="selectedIds" class="item-checkbox rounded border-slate-300 dark:border-white/20 text-brand-primary focus:ring-brand-primary scale-95">
                                    </td>

                                    <!-- Satker & Pengaju -->
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-slate-800 dark:text-white leading-tight">
                                            {{ $k->satker->nama_satker ?? '-' }}
                                        </div>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            {{ $k->diajukanOleh->name ?? 'Admin Satker' }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">
                                            {{ $k->updated_at ? $k->updated_at->timezone('Asia/Makassar')->format('d M Y, H:i') . ' WITA' : '-' }}
                                        </div>
                                    </td>

                                    <!-- Tipe Pengajuan -->
                                    <td class="py-3.5 px-4">
                                        @if($k->aksi_approval === 'edit')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                Edit Data
                                            </span>
                                        @elseif($k->aksi_approval === 'import')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-teal-500/10 text-teal-600 dark:text-teal-400 border border-teal-500/20">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                                Import Excel
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                Tambah Baru
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Data Kendaraan -->
                                    <td class="py-3.5 px-4">
                                        @if($isEdit && count($changes) > 0)
                                            <!-- Edit Diff View -->
                                            <div class="space-y-1.5 p-2 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-slate-200 dark:border-white/5">
                                                <div class="text-[11px] font-bold text-slate-700 dark:text-slate-300">
                                                    Nopol: <span class="font-black text-slate-900 dark:text-white">{{ $k->no_polisi }}</span>
                                                    @if(isset($changes['no_polisi']) && $changes['no_polisi'] !== $k->no_polisi)
                                                        <span class="text-emerald-600 dark:text-emerald-400">➔ {{ $changes['no_polisi'] }}</span>
                                                    @endif
                                                </div>
                                                <div class="text-[10px] text-slate-500 dark:text-slate-400 space-y-0.5">
                                                    @if(isset($changes['jenis_kendaraan']) && $changes['jenis_kendaraan'] !== $k->jenis_kendaraan)
                                                        <div class="flex items-center gap-1">
                                                            <span class="font-semibold text-slate-600 dark:text-slate-300">Jenis:</span>
                                                            <span class="line-through text-rose-500">{{ $k->jenis_kendaraan }}</span>
                                                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">➔ {{ $changes['jenis_kendaraan'] }}</span>
                                                        </div>
                                                    @endif
                                                    @if(isset($changes['jenis_bbm']) && $changes['jenis_bbm'] !== $k->jenis_bbm)
                                                        <div class="flex items-center gap-1">
                                                            <span class="font-semibold text-slate-600 dark:text-slate-300">BBM:</span>
                                                            <span class="line-through text-rose-500">{{ $k->jenis_bbm }}</span>
                                                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">➔ {{ $changes['jenis_bbm'] }}</span>
                                                        </div>
                                                    @endif
                                                    @if(isset($changes['roda']) && $changes['roda'] !== $k->roda)
                                                        <div class="flex items-center gap-1">
                                                            <span class="font-semibold text-slate-600 dark:text-slate-300">Roda:</span>
                                                            <span class="line-through text-rose-500">{{ $k->roda ?: '-' }}</span>
                                                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">➔ {{ $changes['roda'] }}</span>
                                                        </div>
                                                    @endif
                                                    @if(isset($changes['cc']) && $changes['cc'] !== $k->cc)
                                                        <div class="flex items-center gap-1">
                                                            <span class="font-semibold text-slate-600 dark:text-slate-300">CC:</span>
                                                            <span class="line-through text-rose-500">{{ $k->cc ?: '-' }}</span>
                                                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">➔ {{ $changes['cc'] }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <!-- Standard Data View -->
                                            <div class="flex items-center gap-2">
                                                <span class="font-black text-sm text-slate-800 dark:text-white">{{ $k->no_polisi }}</span>
                                                <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider {{ str_contains(strtolower($k->jenis_bbm), 'dex') ? 'bg-amber-500/10 text-amber-600 border border-amber-500/20' : 'bg-indigo-500/10 text-indigo-600 border border-indigo-500/20' }}">
                                                    {{ $k->jenis_bbm }}
                                                </span>
                                            </div>
                                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                                                {{ $k->jenis_kendaraan }} • {{ $k->roda ?: 'Non Roda' }} {{ $k->cc ? '• ' . $k->cc . ' cc' : '' }}
                                            </div>
                                            <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">
                                                Kode: {{ $k->kode_kendaraan }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Status -->
                                    <td class="py-3.5 px-4">
                                        @if($k->status_approval === 'pending')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/30 animate-pulse">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Menunggu
                                            </span>
                                        @elseif($k->status_approval === 'disetujui')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Disetujui
                                            </span>
                                        @else
                                            <div class="space-y-1">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/30">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    Ditolak
                                                </span>
                                                @if($k->alasan_penolakan)
                                                    <div class="text-[10px] text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 p-1.5 rounded border border-rose-200 dark:border-rose-900/50 max-w-xs">
                                                        <span class="font-bold">Alasan:</span> {{ $k->alasan_penolakan }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Action Buttons -->
                                    <td class="py-3.5 px-4 text-center">
                                        @if($isPending)
                                            <div class="flex items-center justify-center gap-1.5">
                                                <!-- Tombol Setujui -->
                                                <form action="{{ route('admin.persetujuan-kendaraan.approve', $k) }}" method="POST" onsubmit="event.preventDefault(); window.confirmDialog({
    title: 'Setujui Kendaraan?',
    message: 'Setujui pengajuan unit kendaraan {{ $k->no_polisi }}?',
    type: 'question',
    confirmText: 'Ya, Setujui',
    cancelText: 'Batal'
}, () => this.submit());">
                                                    @csrf
                                                    <button type="submit" title="Setujui" class="p-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-sm transition flex items-center justify-center">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                    </button>
                                                </form>

                                                <!-- Tombol Tolak -->
                                                <button type="button" @click="openRejectModal('{{ $k->id }}', '{{ $k->no_polisi }}')" title="Tolak dengan Alasan" class="p-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl shadow-sm transition flex items-center justify-center">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-[11px] text-slate-400 italic">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center">
                                        <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 mx-auto flex items-center justify-center mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <p class="text-sm font-bold text-slate-600 dark:text-slate-400">Tidak ada pengajuan kendaraan</p>
                                        <p class="text-xs text-slate-400 mt-1">Belum ada data yang cocok dengan kriteria filter saat ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($kendaraans->hasPages())
                    <div class="p-4 border-t border-slate-200 dark:border-white/10 bg-slate-50/50 dark:bg-slate-900">
                        {{ $kendaraans->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Single Reject Modal -->
        <div x-show="rejectModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="rejectModalOpen = false"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl p-6 overflow-hidden">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-white/10">
                        <div class="flex items-center gap-2 text-rose-600 dark:text-rose-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <h3 class="text-sm font-black uppercase tracking-wider">Tolak Pengajuan Kendaraan</h3>
                        </div>
                        <button @click="rejectModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form :action="'{{ url('admin/persetujuan-kendaraan') }}/' + rejectSingleId + '/tolak'" method="POST" class="mt-4 space-y-4">
                        @csrf
                        <div>
                            <p class="text-xs text-slate-600 dark:text-slate-300">
                                Anda akan menolak pengajuan untuk kendaraan: <span class="font-black text-slate-800 dark:text-white" x-text="rejectSingleNopol"></span>.
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                Alasan Penolakan <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="alasan_penolakan" x-model="rejectionReason" required rows="3" placeholder="Contoh: Nomor polisi tidak sesuai STNK, jenis BBM salah, atau dokumen belum lengkap..."
                                class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 rounded-xl text-xs text-slate-800 dark:text-white focus:ring-2 focus:ring-rose-500 resize-none"></textarea>
                            <p class="text-[10px] text-slate-400 mt-1">Alasan ini akan dapat dilihat langsung oleh Admin Satker terkait.</p>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" @click="rejectModalOpen = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-sm transition">
                                Kirim Penolakan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bulk Reject Modal -->
        <div x-show="rejectBulkModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="rejectBulkModalOpen = false"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl p-6 overflow-hidden">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-white/10">
                        <div class="flex items-center gap-2 text-rose-600 dark:text-rose-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <h3 class="text-sm font-black uppercase tracking-wider">Tolak Semua Terpilih</h3>
                        </div>
                        <button @click="rejectBulkModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form action="{{ route('admin.persetujuan-kendaraan.bulk-reject') }}" method="POST" class="mt-4 space-y-4">
                        @csrf
                        <template x-for="id in selectedIds" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>

                        <div>
                            <p class="text-xs text-slate-600 dark:text-slate-300">
                                Anda akan menolak <span class="font-black text-rose-600" x-text="selectedIds.length"></span> pengajuan kendaraan sekaligus.
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                Alasan Penolakan Bersama <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="alasan_penolakan" required rows="3" placeholder="Tuliskan alasan penolakan untuk seluruh kendaraan terpilih..."
                                class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 rounded-xl text-xs text-slate-800 dark:text-white focus:ring-2 focus:ring-rose-500 resize-none"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" @click="rejectBulkModalOpen = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-sm transition">
                                Tolak Semua Terpilih
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
