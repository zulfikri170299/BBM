<x-app-layout>
    <div class="py-6 px-3 sm:px-6 lg:px-8 min-h-screen" x-data="{ 
        selectedKendaraan: [],
        searchKendaraan: '',
        filterSatker: '',
        filterBbm: '',
        filterHanyaBersaldo: true,
        allSelectedKendaraan: false,
        
        kendaraanList: @js($kendaraans->map(fn($k) => [
            'id' => (string)$k->id,
            'nopol' => $k->no_polisi,
            'jenis' => $k->jenis_kendaraan,
            'satker_id' => (string)$k->satker_id,
            'satker' => $k->satker->nama_satker ?? '-',
            'bbm' => $k->jenis_bbm,
            'saldo' => (float)$k->saldo
        ])),

        get filteredKendaraans() {
            return this.kendaraanList.filter(item => {
                const q = this.searchKendaraan.toLowerCase().trim();
                const matchSearch = !q || item.nopol.toLowerCase().includes(q) || item.satker.toLowerCase().includes(q) || item.jenis.toLowerCase().includes(q);
                const matchSatker = !this.filterSatker || item.satker_id === this.filterSatker;
                const matchBbm = !this.filterBbm || item.bbm === this.filterBbm;
                const matchSaldo = !this.filterHanyaBersaldo || item.saldo > 0;
                return matchSearch && matchSatker && matchBbm && matchSaldo;
            });
        },

        get countPertamax() {
            return this.kendaraanList.filter(item => this.selectedKendaraan.includes(item.id) && item.bbm === 'Pertamax').length;
        },
        get countDex() {
            return this.kendaraanList.filter(item => this.selectedKendaraan.includes(item.id) && item.bbm === 'Pertamina Dex').length;
        },
        get literPertamax() {
            return this.kendaraanList.filter(item => this.selectedKendaraan.includes(item.id) && item.bbm === 'Pertamax').reduce((a, b) => a + b.saldo, 0);
        },
        get literDex() {
            return this.kendaraanList.filter(item => this.selectedKendaraan.includes(item.id) && item.bbm === 'Pertamina Dex').reduce((a, b) => a + b.saldo, 0);
        },
        get totalLiter() {
            return this.literPertamax + this.literDex;
        },

        toggleAllKendaraan() {
            const visibleIds = this.filteredKendaraans.map(k => k.id);
            if (this.allSelectedKendaraan) {
                this.selectedKendaraan = [...new Set([...this.selectedKendaraan, ...visibleIds])];
            } else {
                this.selectedKendaraan = this.selectedKendaraan.filter(id => !visibleIds.includes(id));
            }
        },

        selectAllVisible() {
            const visibleIds = this.filteredKendaraans.map(k => k.id);
            this.selectedKendaraan = [...new Set([...this.selectedKendaraan, ...visibleIds])];
            this.allSelectedKendaraan = true;
        },

        clearSelection() {
            this.selectedKendaraan = [];
            this.allSelectedKendaraan = false;
        },

        formatNumber(val) {
            return new Intl.NumberFormat('id-ID', { maximumFractionDigits: 1 }).format(val);
        }
    }">
        <div class="max-w-7xl mx-auto space-y-6">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-between shadow-lg shadow-emerald-500/5">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-xl bg-emerald-500/20 text-emerald-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-between shadow-lg shadow-rose-500/5">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-xl bg-rose-500/20 text-rose-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white/70 dark:bg-slate-900/60 backdrop-blur-xl p-6 rounded-3xl border border-slate-200/80 dark:border-white/10 shadow-sm">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 text-[10px] font-black uppercase tracking-widest mb-2">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Fitur Pengosongan Saldo</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Potong Saldo Masal
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium mt-1">
                        Kosongkan saldo kupon BBM kendaraan dinas secara serentak dengan opsi pengembalian ke stok tangki.
                    </p>
                </div>

                <!-- Stats summary badge -->
                <div class="flex flex-wrap items-center gap-3">
                    <div class="px-4 py-3 rounded-2xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-white/5">
                        <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Total Kendaraan</span>
                        <span class="text-lg font-black text-white">{{ $kendaraans->count() }} <span class="text-xs font-semibold text-slate-400">Unit</span></span>
                    </div>
                    <div class="px-4 py-3 rounded-2xl bg-indigo-500/10 border border-indigo-500/20">
                        <span class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider block">Kendaraan Bersaldo</span>
                        <span class="text-lg font-black text-indigo-600 dark:text-indigo-300">
                            {{ $kendaraans->filter(fn($k) => $k->saldo > 0)->count() }} <span class="text-xs font-semibold">Unit</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Main Content Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Panel: Table & Filters (col-span-8) -->
                <div class="lg:col-span-8 space-y-4">
                    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200/80 dark:border-white/10 rounded-3xl shadow-xl overflow-hidden">
                        
                        <!-- Toolbar Controls -->
                        <div class="p-4 border-b border-slate-200 dark:border-white/10 space-y-3 bg-slate-50/50 dark:bg-slate-800/40">
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                                
                                <!-- Search Field -->
                                <div class="relative flex-1">
                                    <input type="text" 
                                        x-model="searchKendaraan"
                                        placeholder="Cari nopol, jenis kendaraan, atau satker..." 
                                        class="w-full pl-9 pr-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-500 text-xs font-semibold text-slate-900 dark:text-white placeholder-slate-400 transition-all shadow-sm">
                                    <div class="absolute left-3 top-2.5 text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <button x-show="searchKendaraan" @click="searchKendaraan = ''" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 dark:hover:text-white">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>

                                <!-- Filter Buttons Group -->
                                <div class="flex flex-wrap items-center gap-2">
                                    <!-- Satker Dropdown -->
                                    <div class="relative" x-data="{ 
                                        open: false,
                                        search: '',
                                        get selectedLabel() {
                                            if (filterSatker === '') return 'Semua Satker';
                                            let item = @js($satkers).find(s => s.id == filterSatker);
                                            return item ? item.nama_satker : 'Semua Satker';
                                        }
                                    }" @click.away="open = false">
                                        <button @click="open = !open" type="button" 
                                            class="flex items-center justify-between gap-2 px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 hover:border-indigo-400 transition-all shadow-sm">
                                            <span x-text="selectedLabel" class="truncate max-w-[130px]"></span>
                                            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>

                                        <div x-show="open" 
                                            x-transition
                                            class="absolute right-0 mt-2 w-64 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl z-[100] overflow-hidden">
                                            <div class="p-2 border-b border-slate-100 dark:border-white/5 bg-slate-50 dark:bg-slate-800/50">
                                                <input type="text" x-model="search" placeholder="Cari nama satker..." 
                                                    class="w-full px-3 py-1.5 text-xs font-medium bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-lg text-slate-800 dark:text-white">
                                            </div>
                                            <div class="max-h-56 overflow-y-auto p-1 text-xs">
                                                <button @click="filterSatker = ''; open = false; search = ''" type="button"
                                                    class="w-full text-left px-3 py-2 rounded-xl font-bold transition-all"
                                                    :class="filterSatker === '' ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'">
                                                    Semua Satker
                                                </button>
                                                @foreach($satkers as $satker)
                                                    <button x-show="'{{ strtolower($satker->nama_satker) }}'.includes(search.toLowerCase())"
                                                        @click="filterSatker = '{{ $satker->id }}'; open = false; search = ''" type="button"
                                                        class="w-full text-left px-3 py-2 rounded-xl font-medium transition-all"
                                                        :class="filterSatker == '{{ $satker->id }}' ? 'bg-indigo-600 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'">
                                                        {{ $satker->nama_satker }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <!-- BBM Dropdown -->
                                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                        <button @click="open = !open" type="button" 
                                            class="flex items-center justify-between gap-2 px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 hover:border-indigo-400 transition-all shadow-sm">
                                            <span x-text="filterBbm === '' ? 'Semua BBM' : filterBbm"></span>
                                            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>

                                        <div x-show="open" x-transition
                                            class="absolute right-0 mt-2 w-44 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl z-[100] p-1 text-xs">
                                            <button @click="filterBbm = ''; open = false" type="button"
                                                class="w-full text-left px-3 py-2 rounded-xl font-bold transition-all"
                                                :class="filterBbm === '' ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'">
                                                Semua BBM
                                            </button>
                                            <button @click="filterBbm = 'Pertamax'; open = false" type="button"
                                                class="w-full text-left px-3 py-2 rounded-xl font-medium transition-all"
                                                :class="filterBbm === 'Pertamax' ? 'bg-indigo-600 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'">
                                                Pertamax
                                            </button>
                                            <button @click="filterBbm = 'Pertamina Dex'; open = false" type="button"
                                                class="w-full text-left px-3 py-2 rounded-xl font-medium transition-all"
                                                :class="filterBbm === 'Pertamina Dex' ? 'bg-indigo-600 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'">
                                                Pertamina Dex
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom Toolbar Sub-Bar: Quick Actions & Toggle -->
                            <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-slate-200/60 dark:border-white/5">
                                <div class="flex items-center gap-2">
                                    <!-- Toggle Filter Hanya Bersaldo -->
                                    <button type="button" @click="filterHanyaBersaldo = !filterHanyaBersaldo"
                                        class="flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold transition-all border"
                                        :class="filterHanyaBersaldo 
                                            ? 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border-indigo-500/30 shadow-sm' 
                                            : 'bg-slate-100 dark:bg-slate-800 text-slate-500 border-transparent'">
                                        <div class="w-3 h-3 rounded-full flex items-center justify-center text-[9px]"
                                            :class="filterHanyaBersaldo ? 'bg-indigo-600 text-white' : 'bg-slate-400 text-white'">
                                            <span x-show="filterHanyaBersaldo">✓</span>
                                        </div>
                                        <span>Hanya Yang Bersaldo (> 0 L)</span>
                                    </button>

                                    <!-- Quick selection buttons -->
                                    <button type="button" @click="selectAllVisible()"
                                        class="px-2.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-[11px] font-bold transition-all">
                                        Pilih Semua Terlihat
                                    </button>
                                    <button type="button" x-show="selectedKendaraan.length > 0" @click="clearSelection()"
                                        class="px-2.5 py-1.5 rounded-xl text-rose-500 hover:bg-rose-500/10 text-[11px] font-bold transition-all">
                                        Batal Pilih
                                    </button>
                                </div>

                                <!-- Selection counter badge -->
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Terpilih:</span>
                                    <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-gradient-to-r from-indigo-600 to-blue-600 text-white text-xs font-black shadow-md shadow-indigo-500/20">
                                        <span x-text="selectedKendaraan.length">0</span>
                                        <span class="text-[10px] uppercase font-bold opacity-80">Unit</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vehicles Table -->
                        <div class="max-h-[560px] overflow-y-auto">
                            <table class="w-full text-left border-collapse">
                                <thead class="sticky top-0 bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-white/10 z-10 text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest shadow-sm">
                                    <tr>
                                        <th class="px-4 py-3.5 w-12 text-center">
                                            <input type="checkbox" x-model="allSelectedKendaraan" @change="toggleAllKendaraan" 
                                                class="rounded-md border-slate-300 dark:border-white/20 text-indigo-600 focus:ring-indigo-500 bg-white dark:bg-slate-800 transition-all cursor-pointer">
                                        </th>
                                        <th class="px-3 py-3.5">Unit Kerja / Satker</th>
                                        <th class="px-3 py-3.5">Kendaraan Dinas</th>
                                        <th class="px-4 py-3.5 text-right">Sisa Saldo</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                    <template x-for="k in filteredKendaraans" :key="k.id">
                                        <tr class="hover:bg-indigo-50/40 dark:hover:bg-indigo-500/5 transition-colors cursor-pointer"
                                            :class="selectedKendaraan.includes(k.id) ? 'bg-indigo-50/70 dark:bg-indigo-950/30' : ''"
                                            @click="selectedKendaraan.includes(k.id) ? selectedKendaraan = selectedKendaraan.filter(id => id !== k.id) : selectedKendaraan.push(k.id)">
                                            
                                            <td class="px-4 py-3 text-center" @click.stop>
                                                <input type="checkbox" :value="k.id" x-model="selectedKendaraan" 
                                                    class="rounded-md border-slate-300 dark:border-white/20 text-indigo-600 focus:ring-indigo-500 bg-white dark:bg-slate-800 transition-all cursor-pointer">
                                            </td>
                                            
                                            <td class="px-3 py-3">
                                                <div class="flex items-center gap-2">
                                                    <div class="p-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 shrink-0">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                        </svg>
                                                    </div>
                                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate max-w-[190px]" x-text="k.satker"></span>
                                                </div>
                                            </td>
                                            
                                            <td class="px-3 py-3">
                                                <div class="flex flex-col">
                                                    <span class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-tight" x-text="k.jenis"></span>
                                                    <div class="flex items-center gap-2 mt-0.5">
                                                        <span class="text-xs font-black text-white" x-text="k.nopol"></span>
                                                        <span class="text-[9px] font-bold px-2 py-0.5 rounded-md uppercase"
                                                            :class="k.bbm === 'Pertamax' 
                                                                ? 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20' 
                                                                : 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20'"
                                                            x-text="k.bbm === 'Pertamax' ? 'Pertamax' : 'P. Dex'">
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td class="px-4 py-3 text-right">
                                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-black"
                                                    :class="k.saldo > 0 
                                                        ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' 
                                                        : 'bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500'">
                                                    <span x-text="formatNumber(k.saldo)"></span>
                                                    <span class="text-[10px] font-bold uppercase opacity-80">L</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>

                                    <!-- Empty State -->
                                    <tr x-show="filteredKendaraans.length === 0">
                                        <td colspan="4" class="px-6 py-16 text-center text-slate-400 dark:text-slate-500">
                                            <div class="flex flex-col items-center justify-center gap-2">
                                                <div class="p-3 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400">
                                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Tidak ada kendaraan yang sesuai filter</span>
                                                <p class="text-[11px] text-slate-400">Coba ubah kata kunci pencarian atau matikan opsi "Hanya Yang Bersaldo".</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer Info -->
                        <div class="px-4 py-3 bg-slate-50 dark:bg-slate-800/40 border-t border-slate-200 dark:border-white/10 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                            <span>Menampilkan <strong class="text-white" x-text="filteredKendaraans.length"></strong> kendaraan</span>
                            <span x-show="selectedKendaraan.length > 0" class="text-indigo-600 dark:text-indigo-400 font-bold">
                                <span x-text="selectedKendaraan.length"></span> item siap dikosongkan
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right Panel: Summary & Form (col-span-4) -->
                <div class="lg:col-span-4 sticky top-6 space-y-4">
                    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200/80 dark:border-white/10 rounded-3xl shadow-xl overflow-hidden">
                        
                        <!-- Summary Header -->
                        <div class="p-5 border-b border-slate-200 dark:border-white/10 bg-gradient-to-br from-indigo-500/5 to-purple-500/5">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-500/20 shadow-md shadow-indigo-500/10">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xs font-black uppercase tracking-wider text-white">Ringkasan Pemotongan</h3>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Kalkulasi total saldo yang akan dikosongkan</p>
                                </div>
                            </div>

                            <!-- Cards: Pertamax & Dex -->
                            <div class="grid grid-cols-2 gap-3">
                                <!-- Pertamax Box -->
                                <div class="p-3.5 rounded-2xl bg-blue-500/5 border border-blue-500/20">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-[9px] font-black uppercase text-blue-600 dark:text-blue-400 tracking-wider">Pertamax</span>
                                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-xl font-black text-white" x-text="countPertamax">0</span>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase">Unit</span>
                                        </div>
                                        <div class="pt-1.5 border-t border-blue-500/10 flex items-baseline gap-1">
                                            <span class="text-sm font-black text-blue-600 dark:text-blue-400" x-text="formatNumber(literPertamax)">0</span>
                                            <span class="text-[10px] font-bold text-blue-500/70 uppercase">Liter</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dex Box -->
                                <div class="p-3.5 rounded-2xl bg-amber-500/5 border border-amber-500/20">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-[9px] font-black uppercase text-amber-600 dark:text-amber-400 tracking-wider">P. Dex</span>
                                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-xl font-black text-white" x-text="countDex">0</span>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase">Unit</span>
                                        </div>
                                        <div class="pt-1.5 border-t border-amber-500/10 flex items-baseline gap-1">
                                            <span class="text-sm font-black text-amber-600 dark:text-amber-400" x-text="formatNumber(literDex)">0</span>
                                            <span class="text-[10px] font-bold text-amber-500/70 uppercase">Liter</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Summary Box -->
                            <div class="mt-3 p-3 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-between">
                                <span class="text-xs font-bold text-indigo-700 dark:text-indigo-300">Total Volume Pemotongan:</span>
                                <span class="text-sm font-black text-indigo-600 dark:text-indigo-400" x-text="formatNumber(totalLiter) + ' Liter'">0 Liter</span>
                            </div>

                            <!-- Alert Box -->
                            <div class="mt-3 p-3 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <p class="text-[11px] text-amber-700 dark:text-amber-300/90 leading-relaxed font-medium">
                                    Seluruh saldo kendaraan yang terpilih akan dikosongkan menjadi <strong>0 Liter</strong>.
                                </p>
                            </div>
                        </div>

                        <!-- Form Execution -->
                        <div class="p-5">
                            <form action="{{ route('admin.bulk-potong.process') }}" method="POST" class="space-y-4"
                                onsubmit="return confirm('Apakah Anda yakin ingin memproses pemotongan saldo untuk kendaraan yang dipilih?');">
                                @csrf
                                <input type="hidden" name="type" value="kendaraan">
                                <input type="hidden" name="is_kosongkan" value="1">
                                
                                <template x-for="id in selectedKendaraan" :key="id">
                                    <input type="hidden" name="ids[]" :value="id">
                                </template>

                                <!-- Option: Kembalikan ke Stok -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                        Kembalikan ke Stok Tangki?
                                    </label>
                                    <div class="grid grid-cols-2 p-1 bg-slate-100 dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-white/5">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="kembalikan_ke_stok" value="ya" checked class="sr-only peer">
                                            <div class="py-2 text-center text-xs font-black uppercase rounded-xl transition-all peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:shadow-md text-slate-600 dark:text-slate-400">
                                                YA (Kembalikan)
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="kembalikan_ke_stok" value="tidak" class="sr-only peer">
                                            <div class="py-2 text-center text-xs font-black uppercase rounded-xl transition-all peer-checked:bg-rose-600 peer-checked:text-white peer-checked:shadow-md text-slate-600 dark:text-slate-400">
                                                TIDAK (Hangus)
                                            </div>
                                        </label>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1 pl-1">
                                        Pilih "YA" jika sisa saldo ingin ditambahkan kembali ke stok tangki BBM induk.
                                    </p>
                                </div>

                                <!-- Password Top Up -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                        Password Top Up <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="password" name="topup_password" required autocomplete="new-password" placeholder="Masukkan password top up Anda" 
                                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-bold text-xs text-white transition-all">
                                    </div>
                                </div>

                                <!-- Keterangan / Alasan -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                        Keterangan / Alasan <span class="text-rose-500">*</span>
                                    </label>
                                    <textarea name="keterangan" required rows="2" 
                                        placeholder="Contoh: Reset kuota triwulan / pengosongan saldo akhir periode..." 
                                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-xs font-medium text-white resize-none transition-all"></textarea>
                                </div>

                                <!-- Submit Button -->
                                <div class="pt-2">
                                    <button type="submit" 
                                        :disabled="selectedKendaraan.length === 0"
                                        class="w-full py-3.5 bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-600 hover:from-indigo-500 hover:to-purple-500 disabled:opacity-40 disabled:cursor-not-allowed text-white rounded-2xl font-black text-xs uppercase tracking-wider shadow-lg shadow-indigo-500/25 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                                        <span>Konfirmasi Potong Saldo</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </button>
                                    <p x-show="selectedKendaraan.length === 0" class="text-center text-[11px] text-slate-400 mt-2 font-medium">
                                        Pilih minimal 1 kendaraan di tabel sebelah kiri.
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
