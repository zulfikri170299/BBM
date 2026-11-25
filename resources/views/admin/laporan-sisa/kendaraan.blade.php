<x-app-layout>
    <div class="max-w-7xl mx-auto p-3 sm:p-6 lg:p-8 space-y-6" x-data="{
        search: '',
        rows: {{ json_encode($rows) }},
        modalOpen: false,
        activeSatker: null,
        activeBbmFilter: 'all',
        modalSearch: '',
        get filteredRows() {
            if (!this.search.trim()) return this.rows;
            const q = this.search.toLowerCase().trim();
            return this.rows.filter(r => r.satker.toLowerCase().includes(q));
        },
        openModal(row, bbm = 'all') {
            this.activeSatker = row;
            this.activeBbmFilter = bbm;
            this.modalSearch = '';
            this.modalOpen = true;
        },
        closeModal() {
            this.modalOpen = false;
        },
        get modalKendaraans() {
            if (!this.activeSatker || !this.activeSatker.kendaraans) return [];
            let list = this.activeSatker.kendaraans;
            if (this.activeBbmFilter === 'locked') {
                list = list.filter(k => k.is_locked);
            } else if (this.activeBbmFilter !== 'all') {
                list = list.filter(k => k.jenis_bbm && k.jenis_bbm.toLowerCase() === this.activeBbmFilter.toLowerCase());
            }
            if (this.modalSearch.trim()) {
                const q = this.modalSearch.toLowerCase().trim();
                list = list.filter(k => 
                    (k.no_polisi && k.no_polisi.toLowerCase().includes(q)) || 
                    (k.jenis_kendaraan && k.jenis_kendaraan.toLowerCase().includes(q))
                );
            }
            return list;
        },
        get modalTotalSaldo() {
            return this.modalKendaraans.reduce((acc, k) => acc + (parseFloat(k.saldo) || 0), 0);
        },
        countByBbm(bbm) {
            if (!this.activeSatker || !this.activeSatker.kendaraans) return 0;
            return this.activeSatker.kendaraans.filter(k => k.jenis_bbm && k.jenis_bbm.toLowerCase() === bbm.toLowerCase()).length;
        },
        countLocked() {
            if (!this.activeSatker || !this.activeSatker.kendaraans) return 0;
            return this.activeSatker.kendaraans.filter(k => k.is_locked).length;
        },
        formatNum(num) {
            return new Intl.NumberFormat('id-ID').format(Math.round(num || 0));
        }
    }">
        <!-- Header Banner Card (Cyber Police Luxury Style) -->
        <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white p-4 sm:p-7 border border-slate-200/10 dark:border-white/10 shadow-2xl shadow-indigo-950/40">
            <!-- Decorative Glow Background Effects -->
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 sm:gap-6">
                <div class="space-y-1 sm:space-y-2">
                    <div class="inline-flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full text-[9px] sm:text-[11px] font-black uppercase tracking-wider bg-white/10 text-cyan-300 border border-white/15 backdrop-blur-md">
                        <span class="w-1.5 sm:w-2 h-1.5 sm:h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                        SPBP POLDA NTB • LOGISTIK & DISTRIBUSI BBM
                    </div>
                    <h1 class="text-base sm:text-2xl lg:text-3xl font-black text-white tracking-tight leading-tight">
                        {{ $title }}
                    </h1>
                    <p class="text-[11px] sm:text-sm font-medium text-slate-300 flex items-center gap-1.5 sm:gap-2">
                        <svg class="w-3.5 sm:w-4 h-3.5 sm:h-4 text-cyan-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $periode }}</span>
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-2 w-full sm:w-auto sm:flex sm:items-center sm:gap-3 shrink-0">
                    <a href="{{ route('admin.laporan-sisa.kendaraan.print') }}" target="_blank"
                        class="inline-flex items-center justify-center gap-1.5 sm:gap-2 px-3 sm:px-5 py-2 sm:py-3 bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-500 hover:to-red-500 text-white font-black text-[11px] sm:text-xs uppercase tracking-wider rounded-xl sm:rounded-2xl shadow-lg shadow-rose-600/30 hover:shadow-rose-600/50 transition-all duration-200 active:scale-95 border border-white/20">
                        <svg class="w-3.5 sm:w-4 h-3.5 sm:h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        <span>Cetak PDF</span>
                    </a>

                    <a href="{{ route('admin.dashboard') }}"
                        class="inline-flex items-center justify-center gap-1.5 sm:gap-2 px-3 sm:px-5 py-2 sm:py-3 bg-white/10 hover:bg-white/20 text-white font-bold text-[11px] sm:text-xs uppercase tracking-wider rounded-xl sm:rounded-2xl backdrop-blur-md border border-white/15 transition-all duration-200 active:scale-95">
                        <svg class="w-3.5 sm:w-4 h-3.5 sm:h-4 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Summary KPI Cards Grid (Compact & Neat on Mobile) -->
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-4">
            <!-- Card 1: Total Pertamax -->
            <div class="relative overflow-hidden rounded-xl sm:rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-3 sm:p-6 shadow-sm hover:shadow-xl hover:shadow-cyan-500/10 transition-all duration-300 group">
                <div class="absolute -top-10 -right-10 w-28 h-28 bg-cyan-500/10 rounded-full blur-2xl group-hover:bg-cyan-500/20 transition-all"></div>
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] sm:text-[10px] font-black uppercase tracking-wider bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border border-cyan-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span>
                            Pertamax
                        </span>
                        <h4 class="text-[10px] sm:text-xs font-bold text-slate-500 dark:text-slate-400 mt-1 sm:mt-2">Total Pertamax</h4>
                        <div class="flex items-baseline gap-1 mt-0.5 sm:mt-1">
                            <span class="text-base sm:text-2xl lg:text-3xl font-black text-slate-900 dark:text-white tabular-nums tracking-tight">
                                {{ number_format($totalPertamax, 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] sm:text-xs font-bold text-cyan-600 dark:text-cyan-400">Liter</span>
                        </div>
                    </div>
                    <div class="w-7 h-7 sm:w-12 sm:h-12 rounded-lg sm:rounded-2xl bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0 border border-cyan-500/20 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
                <div class="hidden sm:flex mt-4 pt-3 border-t border-slate-100 dark:border-white/5 items-center justify-between text-[11px] text-slate-500 dark:text-slate-400">
                    <span>Sisa saldo siap alokasi</span>
                    <span class="font-bold text-cyan-600 dark:text-cyan-400">Seluruh Satker</span>
                </div>
            </div>

            <!-- Card 2: Total Pertamina Dex -->
            <div class="relative overflow-hidden rounded-xl sm:rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-3 sm:p-6 shadow-sm hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-300 group">
                <div class="absolute -top-10 -right-10 w-28 h-28 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all"></div>
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] sm:text-[10px] font-black uppercase tracking-wider bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                            P. Dex
                        </span>
                        <h4 class="text-[10px] sm:text-xs font-bold text-slate-500 dark:text-slate-400 mt-1 sm:mt-2">Total Dex</h4>
                        <div class="flex items-baseline gap-1 mt-0.5 sm:mt-1">
                            <span class="text-base sm:text-2xl lg:text-3xl font-black text-slate-900 dark:text-white tabular-nums tracking-tight">
                                {{ number_format($totalDex, 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] sm:text-xs font-bold text-indigo-600 dark:text-indigo-400">Liter</span>
                        </div>
                    </div>
                    <div class="w-7 h-7 sm:w-12 sm:h-12 rounded-lg sm:rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 border border-indigo-500/20 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                </div>
                <div class="hidden sm:flex mt-4 pt-3 border-t border-slate-100 dark:border-white/5 items-center justify-between text-[11px] text-slate-500 dark:text-slate-400">
                    <span>Sisa saldo siap alokasi</span>
                    <span class="font-bold text-indigo-600 dark:text-indigo-400">Seluruh Satker</span>
                </div>
            </div>

            <!-- Card 3: Total Akumulasi (Full Width Span on Mobile) -->
            <div class="col-span-2 lg:col-span-1 relative overflow-hidden rounded-xl sm:rounded-3xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-blue-700 text-white p-3 sm:p-6 shadow-lg shadow-indigo-600/20 group">
                <div class="absolute -top-10 -right-10 w-28 h-28 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] sm:text-[10px] font-black uppercase tracking-wider bg-white/20 text-white border border-white/20">
                            Akumulasi Total
                        </span>
                        <h4 class="text-[10px] sm:text-xs font-bold text-indigo-100 mt-1 sm:mt-2">Gabungan Seluruh Sisa BBM</h4>
                        <div class="flex items-baseline gap-1 mt-0.5 sm:mt-1">
                            <span class="text-lg sm:text-2xl lg:text-3xl font-black text-white tabular-nums tracking-tight">
                                {{ number_format($totalPertamax + $totalDex, 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] sm:text-xs font-bold text-cyan-200">Liter</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <div class="w-7 h-7 sm:w-12 sm:h-12 rounded-lg sm:rounded-2xl bg-white/20 text-white flex items-center justify-center shrink-0 border border-white/20 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <span class="text-[9px] text-indigo-100 font-bold block sm:hidden">{{ count($rows) }} Satker</span>
                    </div>
                </div>
                <div class="hidden sm:flex mt-4 pt-3 border-t border-white/15 items-center justify-between text-[11px] text-indigo-100">
                    <span>Total data terdaftar</span>
                    <span class="font-bold text-white">{{ count($rows) }} Satuan Kerja</span>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl sm:rounded-2xl shadow-sm p-2.5 sm:p-5 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2.5 sm:gap-4">
            <!-- Search Bar Live Filter -->
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 sm:w-4 h-3.5 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" x-model="search" placeholder="Cari Satuan Kerja (DOKKES, HUMAS)..."
                    class="block w-full h-9 sm:h-11 pl-9 pr-8 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm" />
                <button type="button" x-show="search.length > 0" @click="search = ''"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200" title="Hapus pencarian">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Total Counters Badge -->
            <div class="flex items-center justify-between sm:justify-start gap-2 text-[11px] sm:text-xs font-bold text-slate-500 dark:text-slate-400">
                <span>Menampilkan:</span>
                <span class="px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-900/50 font-black"
                    x-text="`${filteredRows.length} dari ${rows.length} Satker`"></span>
            </div>
        </div>

        <!-- Table Data Card (Desktop View) -->
        <div class="hidden lg:block bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-3xl shadow-xl overflow-hidden transition-all duration-200">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/90 dark:bg-slate-800/60 border-b border-slate-200 dark:border-white/10 text-[11px] font-black uppercase tracking-wider text-slate-600 dark:text-slate-300">
                            <th rowspan="2" class="px-4 py-4 text-center w-16 border-r border-slate-200 dark:border-white/5">NO</th>
                            <th rowspan="2" class="px-6 py-4 border-r border-slate-200 dark:border-white/5">SATUAN KERJA (SATKER)</th>
                            <th colspan="2" class="px-6 py-3 text-center border-b border-r border-slate-200 dark:border-white/10 bg-indigo-50/40 dark:bg-indigo-950/20 text-indigo-700 dark:text-indigo-300 font-extrabold">
                                SISA SALDO BBM (LITER)
                            </th>
                            <th rowspan="2" class="px-6 py-4 text-center w-48">TOTAL SISA</th>
                        </tr>
                        <tr class="bg-slate-50/70 dark:bg-slate-800/40 border-b border-slate-200 dark:border-white/10 text-[10px] font-black uppercase tracking-wider">
                            <th class="px-6 py-3 text-center border-r border-slate-200 dark:border-white/5 text-cyan-600 dark:text-cyan-400">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span>
                                    PERTAMAX
                                </span>
                            </th>
                            <th class="px-6 py-3 text-center border-r border-slate-200 dark:border-white/5 text-indigo-600 dark:text-indigo-400">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                    PERTAMINA DEX
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        <template x-for="(row, idx) in filteredRows" :key="idx">
                            <tr class="hover:bg-indigo-50/30 dark:hover:bg-slate-800/50 transition-colors duration-150 group">
                                <!-- No -->
                                <td class="px-4 py-4 text-center font-bold text-slate-400 dark:text-slate-500 border-r border-slate-200 dark:border-white/5 text-xs tabular-nums"
                                    x-text="idx + 1"></td>

                                <!-- Satker with Avatar Initial -->
                                <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-100 border-r border-slate-200 dark:border-white/5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-500 text-white flex items-center justify-center font-black text-xs shadow-md shadow-indigo-500/20 shrink-0 uppercase"
                                            x-text="row.satker.substring(0, 2)">
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-extrabold uppercase tracking-tight text-xs sm:text-sm group-hover:text-indigo-600 dark:group-hover:text-cyan-400 transition-colors"
                                                    x-text="row.satker"></span>
                                                <!-- Terkunci Badge in Table -->
                                                <template x-if="row.locked_count > 0">
                                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/25"
                                                        :title="`${row.locked_count} kendaraan memiliki saldo terkunci karena hutang BON`">
                                                        <svg class="w-2.5 h-2.5 text-rose-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                        <span x-text="`${row.locked_count} Terkunci`"></span>
                                                    </span>
                                                </template>
                                            </div>
                                            <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500">Polda Nusa Tenggara Barat</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Pertamax Column -->
                                <td class="px-6 py-4 text-center border-r border-slate-200 dark:border-white/5">
                                    <button type="button" @click="openModal(row, 'Pertamax')"
                                        class="group/btn inline-flex items-center justify-center min-w-[95px] px-3 py-1.5 rounded-xl text-xs font-black tabular-nums transition-all duration-150 cursor-pointer hover:scale-105 active:scale-95 shadow-sm"
                                        :class="row.pertamax <= 0 ? 'bg-slate-100 dark:bg-slate-800 text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' : (row.pertamax < 50 ? 'bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-900/50 hover:bg-amber-100' : 'bg-cyan-50 dark:bg-cyan-950/40 text-cyan-700 dark:text-cyan-300 border border-cyan-200 dark:border-cyan-800/50 hover:bg-cyan-100 dark:hover:bg-cyan-900/60 hover:shadow-cyan-500/20')"
                                        title="Klik untuk melihat daftar kendaraan Pertamax">
                                        <span x-text="formatNum(row.pertamax)"></span>
                                        <span class="text-[10px] font-bold text-slate-400 ml-1">L</span>
                                        <svg class="w-3 h-3 ml-1 text-cyan-500 opacity-60 group-hover/btn:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </td>

                                <!-- Pertamina Dex Column -->
                                <td class="px-6 py-4 text-center border-r border-slate-200 dark:border-white/5">
                                    <button type="button" @click="openModal(row, 'Pertamina Dex')"
                                        class="group/btn inline-flex items-center justify-center min-w-[95px] px-3 py-1.5 rounded-xl text-xs font-black tabular-nums transition-all duration-150 cursor-pointer hover:scale-105 active:scale-95 shadow-sm"
                                        :class="row.dex <= 0 ? 'bg-slate-100 dark:bg-slate-800 text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' : (row.dex < 50 ? 'bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-900/50 hover:bg-amber-100' : 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/50 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 hover:shadow-indigo-500/20')"
                                        title="Klik untuk melihat daftar kendaraan Pertamina Dex">
                                        <span x-text="formatNum(row.dex)"></span>
                                        <span class="text-[10px] font-bold text-slate-400 ml-1">L</span>
                                        <svg class="w-3 h-3 ml-1 text-indigo-500 opacity-60 group-hover/btn:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </td>

                                <!-- Total Satker Column -->
                                <td class="px-6 py-4 text-center font-black text-slate-700 dark:text-slate-200 tabular-nums text-xs">
                                    <button type="button" @click="openModal(row, 'all')"
                                        class="group/btn inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-white/10 hover:border-indigo-500/50 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 hover:text-indigo-600 dark:hover:text-indigo-300 transition-all cursor-pointer hover:scale-105 active:scale-95"
                                        title="Klik untuk melihat semua kendaraan pada Satker ini">
                                        <span x-text="`${formatNum(row.pertamax + row.dex)} L`"></span>
                                        <svg class="w-3 h-3 ml-1 text-indigo-400 opacity-60 group-hover/btn:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </template>

                        <!-- Empty State if Search Not Found -->
                        <tr x-show="filteredRows.length === 0">
                            <td colspan="5" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                <svg class="w-12 h-12 mx-auto text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm font-bold text-slate-600 dark:text-slate-300">Tidak ada Satuan Kerja yang cocok</p>
                                <p class="text-xs text-slate-400 mt-1">Coba kata kunci pencarian yang lain.</p>
                            </td>
                        </tr>
                    </tbody>

                    <!-- Footer Total Keseluruhan -->
                    <tfoot class="bg-slate-50 dark:bg-slate-800/90 border-t-2 border-slate-200 dark:border-white/10 text-slate-800 dark:text-white font-black text-center">
                        <tr class="divide-x divide-slate-200 dark:divide-white/5">
                            <td colspan="2" class="px-6 py-5 text-right font-black uppercase tracking-wider text-xs text-slate-600 dark:text-slate-300">
                                TOTAL KESELURUHAN (SEMUA SATKER)
                            </td>
                            <td class="px-6 py-5 text-center font-black text-base text-cyan-600 dark:text-cyan-400 tabular-nums">
                                {{ number_format($totalPertamax, 0, ',', '.') }}
                                <span class="text-xs font-bold text-cyan-600/70 ml-0.5">L</span>
                            </td>
                            <td class="px-6 py-5 text-center font-black text-base text-indigo-600 dark:text-indigo-400 tabular-nums">
                                {{ number_format($totalDex, 0, ',', '.') }}
                                <span class="text-xs font-bold text-indigo-600/70 ml-0.5">L</span>
                            </td>
                            <td class="px-6 py-5 text-center font-black text-base text-slate-900 dark:text-white tabular-nums bg-indigo-500/10">
                                {{ number_format($totalPertamax + $totalDex, 0, ',', '.') }}
                                <span class="text-xs font-bold text-slate-500 ml-0.5">L</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Mobile Card View (Responsive Smartphone View) -->
        <div class="lg:hidden space-y-2.5">
            <template x-for="(row, idx) in filteredRows" :key="idx">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl p-3 shadow-sm relative overflow-hidden group">
                    <div class="flex items-center justify-between mb-2 pb-1.5 border-b border-slate-100 dark:border-white/5">
                        <div class="flex items-center gap-2">
                            <span class="w-5 h-5 rounded-md bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[9px] font-black flex items-center justify-center tabular-nums"
                                x-text="idx + 1"></span>
                            <div class="flex items-center gap-1.5">
                                <h3 class="text-xs font-black text-slate-800 dark:text-slate-100 uppercase tracking-tight"
                                    x-text="row.satker"></h3>
                                <template x-if="row.locked_count > 0">
                                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.2 rounded text-[8px] font-black uppercase tracking-wider bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/25">
                                        <svg class="w-2 h-2 text-rose-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <span x-text="`${row.locked_count} Terkunci`"></span>
                                    </span>
                                </template>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400"
                            x-text="`Total: ${formatNum(row.pertamax + row.dex)} L`"></span>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <!-- Pertamax Mini Card -->
                        <button type="button" @click="openModal(row, 'Pertamax')"
                            class="text-left bg-cyan-50/50 dark:bg-cyan-950/20 border border-cyan-200/60 dark:border-cyan-900/30 p-2 sm:p-2.5 rounded-lg hover:border-cyan-400 transition-all active:scale-95 cursor-pointer">
                            <span class="text-[8px] font-black text-cyan-600 dark:text-cyan-400 uppercase tracking-wider flex items-center justify-between mb-0.5">
                                <span>Pertamax</span>
                                <svg class="w-2.5 h-2.5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-base font-black text-cyan-700 dark:text-cyan-300 tabular-nums tracking-tight"
                                    x-text="formatNum(row.pertamax)"></span>
                                <span class="text-[9px] font-bold text-cyan-500">L</span>
                            </div>
                        </button>

                        <!-- Dex Mini Card -->
                        <button type="button" @click="openModal(row, 'Pertamina Dex')"
                            class="text-left bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-200/60 dark:border-indigo-900/30 p-2 sm:p-2.5 rounded-lg hover:border-indigo-400 transition-all active:scale-95 cursor-pointer">
                            <span class="text-[8px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-wider flex items-center justify-between mb-0.5">
                                <span>P. Dex</span>
                                <svg class="w-2.5 h-2.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-base font-black text-indigo-700 dark:text-indigo-300 tabular-nums tracking-tight"
                                    x-text="formatNum(row.dex)"></span>
                                <span class="text-[9px] font-bold text-indigo-500">L</span>
                            </div>
                        </button>
                    </div>
                </div>
            </template>

            <!-- Mobile Empty State -->
            <div x-show="filteredRows.length === 0" class="py-8 text-center text-slate-400">
                <p class="text-xs font-bold">Satker tidak ditemukan</p>
            </div>

            <!-- Mobile Total Summary Card -->
            <div class="bg-gradient-to-br from-indigo-900 via-slate-900 to-slate-900 text-white rounded-2xl p-3.5 border border-white/10 shadow-xl space-y-2">
                <span class="text-[9px] font-black uppercase tracking-widest text-cyan-300">Total Keseluruhan Sisa</span>
                <div class="grid grid-cols-2 gap-2 pt-1 border-t border-white/10">
                    <div>
                        <span class="text-[9px] text-slate-400 uppercase font-bold">Pertamax</span>
                        <p class="text-base font-black text-cyan-400 tabular-nums">
                            {{ number_format($totalPertamax, 0, ',', '.') }} <span class="text-[10px] font-normal text-white">L</span>
                        </p>
                    </div>
                    <div>
                        <span class="text-[9px] text-slate-400 uppercase font-bold">Pertamina Dex</span>
                        <p class="text-base font-black text-indigo-400 tabular-nums">
                            {{ number_format($totalDex, 0, ',', '.') }} <span class="text-[10px] font-normal text-white">L</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Detail Kendaraan Ber-Saldo (Cyber Luxury Style) -->
        <div x-show="modalOpen" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="closeModal()"
             class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-slate-950/80 backdrop-blur-md">
            
            <!-- Backdrop Click to Close -->
            <div class="fixed inset-0" @click="closeModal()"></div>

            <!-- Modal Card Box -->
            <div x-show="modalOpen"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="relative z-10 w-full max-w-2xl bg-slate-900 border border-white/10 rounded-3xl shadow-2xl shadow-indigo-950/60 overflow-hidden flex flex-col max-h-[90vh]">
                
                <!-- Modal Header -->
                <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-5 sm:p-6 border-b border-white/10 text-white shrink-0">
                    <div class="absolute -top-12 -right-12 w-40 h-40 bg-cyan-500/10 rounded-full blur-2xl pointer-events-none"></div>
                    
                    <div class="relative z-10 flex items-start justify-between gap-4">
                        <div>
                            <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-white/10 text-cyan-300 border border-white/15 mb-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                                RINCIAN SISA SALDO KENDARAAN
                            </div>
                            <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight uppercase"
                                x-text="activeSatker ? activeSatker.satker : ''"></h2>
                            <p class="text-xs text-slate-300 mt-1 flex items-center gap-1.5">
                                <span>Polda Nusa Tenggara Barat</span>
                            </p>
                        </div>

                        <button type="button" @click="closeModal()"
                            class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center border border-white/10 transition-colors shrink-0"
                            title="Tutup">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Filter Tabs & In-Modal Search -->
                    <div class="mt-4 pt-4 border-t border-white/10 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                        <!-- Fuel Filter Tabs -->
                        <div class="flex flex-wrap items-center gap-1.5 bg-slate-950/60 p-1 rounded-xl border border-white/10 text-xs">
                            <button type="button" @click="activeBbmFilter = 'all'"
                                :class="activeBbmFilter === 'all' ? 'bg-indigo-600 text-white font-black shadow-md' : 'text-slate-400 hover:text-white font-medium'"
                                class="px-3 py-1.5 rounded-lg transition-all text-xs flex items-center gap-1.5 cursor-pointer">
                                <span>Semua</span>
                                <span class="text-[10px] px-1.5 py-0.2 rounded-full"
                                    :class="activeBbmFilter === 'all' ? 'bg-white/20 text-white' : 'bg-slate-800 text-slate-400'"
                                    x-text="activeSatker && activeSatker.kendaraans ? activeSatker.kendaraans.length : 0"></span>
                            </button>

                            <button type="button" @click="activeBbmFilter = 'Pertamax'"
                                :class="activeBbmFilter.toLowerCase() === 'pertamax' ? 'bg-cyan-600 text-white font-black shadow-md' : 'text-slate-400 hover:text-white font-medium'"
                                class="px-3 py-1.5 rounded-lg transition-all text-xs flex items-center gap-1.5 cursor-pointer">
                                <span>Pertamax</span>
                                <span class="text-[10px] px-1.5 py-0.2 rounded-full"
                                    :class="activeBbmFilter.toLowerCase() === 'pertamax' ? 'bg-white/20 text-white' : 'bg-slate-800 text-slate-400'"
                                    x-text="countByBbm('Pertamax')"></span>
                            </button>

                            <button type="button" @click="activeBbmFilter = 'Pertamina Dex'"
                                :class="activeBbmFilter.toLowerCase() === 'pertamina dex' ? 'bg-indigo-600 text-white font-black shadow-md' : 'text-slate-400 hover:text-white font-medium'"
                                class="px-3 py-1.5 rounded-lg transition-all text-xs flex items-center gap-1.5 cursor-pointer">
                                <span>Dex</span>
                                <span class="text-[10px] px-1.5 py-0.2 rounded-full"
                                    :class="activeBbmFilter.toLowerCase() === 'pertamina dex' ? 'bg-white/20 text-white' : 'bg-slate-800 text-slate-400'"
                                    x-text="countByBbm('Pertamina Dex')"></span>
                            </button>

                            <!-- Terkunci Tab -->
                            <template x-if="countLocked() > 0">
                                <button type="button" @click="activeBbmFilter = 'locked'"
                                    :class="activeBbmFilter === 'locked' ? 'bg-rose-600 text-white font-black shadow-md' : 'text-rose-400 hover:text-rose-300 font-medium'"
                                    class="px-2.5 sm:px-3 py-1.5 rounded-lg transition-all text-xs flex items-center gap-1.5 cursor-pointer">
                                    <svg class="w-3 h-3 text-rose-300 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Terkunci</span>
                                    <span class="text-[10px] px-1.5 py-0.2 rounded-full"
                                        :class="activeBbmFilter === 'locked' ? 'bg-white/20 text-white' : 'bg-rose-950/60 text-rose-400 border border-rose-500/30'"
                                        x-text="countLocked()"></span>
                                </button>
                            </template>
                        </div>

                        <!-- Quick Filter Search inside Modal -->
                        <div class="relative flex-1 sm:max-w-[200px]">
                            <input type="text" x-model="modalSearch" placeholder="Cari Nopol / Jenis..."
                                class="w-full h-8 pl-8 pr-3 bg-slate-950/70 border border-white/10 rounded-lg text-xs font-semibold text-white placeholder-slate-400 focus:outline-none focus:border-cyan-500 transition-all" />
                            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Modal Body: Vehicle List (Scrollable) -->
                <div class="p-4 sm:p-6 overflow-y-auto space-y-2.5 max-h-[50vh]">
                    <!-- Empty State in Modal -->
                    <div x-show="modalKendaraans.length === 0" class="py-10 text-center text-slate-400">
                        <svg class="w-12 h-12 mx-auto text-slate-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                        <p class="text-sm font-bold text-slate-300">Tidak ada kendaraan dengan sisa saldo</p>
                        <p class="text-xs text-slate-500 mt-1">Sesuai filter BBM atau pencarian yang dipilih.</p>
                    </div>

                    <!-- List of Vehicles -->
                    <template x-for="k in modalKendaraans" :key="k.id">
                        <div class="flex items-center justify-between gap-3 p-3 sm:p-3.5 rounded-2xl border transition-all"
                            :class="k.is_locked ? 'bg-rose-950/15 border-rose-500/25 hover:border-rose-500/40 hover:bg-rose-950/25' : 'bg-slate-800/40 hover:bg-slate-800/80 border-white/5'">
                            <div class="flex items-center gap-3">
                                <!-- Vehicle Icon -->
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border"
                                    :class="k.is_locked ? 'bg-rose-500/10 text-rose-400 border-rose-500/25' : (k.jenis_bbm && k.jenis_bbm.toLowerCase() === 'pertamax' ? 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20' : 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 17a2 2 0 100 4 2 2 0 000-4zm8 0a2 2 0 100 4 2 2 0 000-4z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 11l2-4h14l2 4M3 11h18v6H3v-6zm3-7h12" />
                                    </svg>
                                </div>

                                <!-- Details -->
                                <div>
                                    <div class="flex items-center gap-1.5 sm:gap-2">
                                        <span class="font-black text-xs sm:text-sm text-white tracking-wide uppercase px-2 py-0.5 rounded-md bg-slate-950/70 border border-white/10"
                                            x-text="k.no_polisi"></span>
                                        <span class="text-[9px] sm:text-[10px] font-bold px-2 py-0.5 rounded-full uppercase"
                                            :class="k.jenis_bbm && k.jenis_bbm.toLowerCase() === 'pertamax' ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30' : 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30'"
                                            x-text="k.jenis_bbm"></span>
                                    </div>
                                    <p class="text-[11px] font-semibold text-slate-400 mt-1 uppercase"
                                        x-text="k.jenis_kendaraan || 'Kendaraan Dinas'"></p>
                                </div>
                            </div>

                            <!-- Saldo Liter Display -->
                            <div class="text-right shrink-0">
                                <span class="text-[10px] font-bold text-slate-400 block mb-0.5">Sisa Saldo</span>
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Simbol Kunci di sebelah kiri angka saldo jika terkunci -->
                                    <template x-if="k.is_locked">
                                        <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="currentColor" viewBox="0 0 20 20" title="Saldo Terkunci (Ada Hutang BON)">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </template>
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-base sm:text-lg font-black tabular-nums tracking-tight"
                                            :class="k.is_locked ? 'text-rose-400' : (k.jenis_bbm && k.jenis_bbm.toLowerCase() === 'pertamax' ? 'text-cyan-400' : 'text-indigo-400')"
                                            x-text="formatNum(k.saldo)"></span>
                                        <span class="text-xs font-bold" :class="k.is_locked ? 'text-rose-400' : 'text-slate-400'">L</span>
                                    </div>
                                </div>
                                <!-- Hutang Info if locked -->
                                <template x-if="k.is_locked && k.unpaid_bon_total > 0">
                                    <div class="text-[10px] font-bold text-rose-400/90 flex items-center justify-end gap-1 mt-0.5">
                                        <span>Hutang:</span>
                                        <span class="tabular-nums font-black text-rose-300" x-text="`${formatNum(k.unpaid_bon_total)} L`"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Modal Footer -->
                <div class="p-4 sm:p-5 bg-slate-950/90 border-t border-white/10 flex items-center justify-between text-xs shrink-0">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <span class="text-slate-400">Total:</span>
                        <span class="font-extrabold text-white" x-text="`${modalKendaraans.length} Kendaraan`"></span>
                        <span class="text-slate-600">•</span>
                        <span class="font-black text-cyan-400 tabular-nums" x-text="`${formatNum(modalTotalSaldo)} Liter`"></span>
                    </div>

                    <button type="button" @click="closeModal()"
                        class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition-all cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
