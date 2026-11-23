<x-app-layout>
    <div class="max-w-7xl mx-auto p-3 sm:p-6 lg:p-8 space-y-6" x-data="{
        search: '',
        rows: {{ json_encode($rows) }},
        get filteredRows() {
            if (!this.search.trim()) return this.rows;
            const q = this.search.toLowerCase().trim();
            return this.rows.filter(r => r.satker.toLowerCase().includes(q));
        },
        formatNum(num) {
            return new Intl.NumberFormat('id-ID').format(Math.round(num || 0));
        }
    }">
        <!-- Header Banner Card (Cyber Police Luxury Style) -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white p-6 sm:p-8 border border-slate-200/10 dark:border-white/10 shadow-2xl shadow-indigo-950/40">
            <!-- Decorative Glow Background Effects -->
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-white/10 text-cyan-300 border border-white/15 backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                        SPBP POLDA NTB • LOGISTIK & DISTRIBUSI BBM
                    </div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white tracking-tight leading-tight">
                        {{ $title }}
                    </h1>
                    <p class="text-xs sm:text-sm font-medium text-slate-300 flex items-center gap-2">
                        <svg class="w-4 h-4 text-cyan-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $periode }}</span>
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <a href="{{ route('admin.laporan-sisa.personel.print') }}" target="_blank"
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-500 hover:to-red-500 text-white font-black text-xs uppercase tracking-wider rounded-2xl shadow-lg shadow-rose-600/30 hover:shadow-rose-600/50 transition-all duration-200 active:scale-95 border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        <span>Cetak Laporan PDF</span>
                    </a>

                    <a href="{{ route('admin.dashboard') }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-white/10 hover:bg-white/20 text-white font-bold text-xs uppercase tracking-wider rounded-2xl backdrop-blur-md border border-white/15 transition-all duration-200 active:scale-95">
                        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Summary KPI Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Card 1: Total Pertamax -->
            <div class="relative overflow-hidden rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-5 sm:p-6 shadow-sm hover:shadow-xl hover:shadow-cyan-500/10 transition-all duration-300 group">
                <div class="absolute -top-10 -right-10 w-28 h-28 bg-cyan-500/10 rounded-full blur-2xl group-hover:bg-cyan-500/20 transition-all"></div>
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border border-cyan-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span>
                            BBM Pertamax
                        </span>
                        <h4 class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-2">Total Sisa Pertamax</h4>
                        <div class="flex items-baseline gap-1.5 mt-1">
                            <span class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tabular-nums tracking-tight">
                                {{ number_format($totalPertamax, 0, ',', '.') }}
                            </span>
                            <span class="text-xs font-bold text-cyan-600 dark:text-cyan-400">Liter</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0 border border-cyan-500/20 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400">
                    <span>Sisa saldo siap alokasi</span>
                    <span class="font-bold text-cyan-600 dark:text-cyan-400">Seluruh Satker</span>
                </div>
            </div>

            <!-- Card 2: Total Pertamina Dex -->
            <div class="relative overflow-hidden rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-5 sm:p-6 shadow-sm hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-300 group">
                <div class="absolute -top-10 -right-10 w-28 h-28 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all"></div>
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                            Pertamina Dex
                        </span>
                        <h4 class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-2">Total Sisa Dex</h4>
                        <div class="flex items-baseline gap-1.5 mt-1">
                            <span class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tabular-nums tracking-tight">
                                {{ number_format($totalDex, 0, ',', '.') }}
                            </span>
                            <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">Liter</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 border border-indigo-500/20 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400">
                    <span>Sisa saldo siap alokasi</span>
                    <span class="font-bold text-indigo-600 dark:text-indigo-400">Seluruh Satker</span>
                </div>
            </div>

            <!-- Card 3: Total Akumulasi -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-blue-700 text-white p-5 sm:p-6 shadow-xl shadow-indigo-600/20 sm:col-span-2 lg:col-span-1 group">
                <div class="absolute -top-10 -right-10 w-28 h-28 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all"></div>
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-white/20 text-white border border-white/20">
                            Akumulasi Total
                        </span>
                        <h4 class="text-xs font-bold text-indigo-100 mt-2">Gabungan Seluruh Sisa BBM</h4>
                        <div class="flex items-baseline gap-1.5 mt-1">
                            <span class="text-2xl sm:text-3xl font-black text-white tabular-nums tracking-tight">
                                {{ number_format($totalPertamax + $totalDex, 0, ',', '.') }}
                            </span>
                            <span class="text-xs font-bold text-cyan-200">Liter</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-white/20 text-white flex items-center justify-center shrink-0 border border-white/20 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-white/15 flex items-center justify-between text-[11px] text-indigo-100">
                    <span>Total data terdaftar</span>
                    <span class="font-bold text-white">{{ count($rows) }} Satuan Kerja</span>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl shadow-sm p-4 sm:p-5 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
            <!-- Search Bar Live Filter -->
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" x-model="search" placeholder="Cari Satuan Kerja (contoh: DOKKES, HUMAS)..."
                    class="block w-full h-11 pl-10 pr-10 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm" />
                <button type="button" x-show="search.length > 0" @click="search = ''"
                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200" title="Hapus pencarian">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Total Counters Badge -->
            <div class="flex items-center gap-2 self-end sm:self-center text-xs font-bold text-slate-500 dark:text-slate-400">
                <span>Menampilkan:</span>
                <span class="px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-900/50 font-black"
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
                                            <span class="font-extrabold uppercase tracking-tight text-xs sm:text-sm group-hover:text-indigo-600 dark:group-hover:text-cyan-400 transition-colors"
                                                x-text="row.satker"></span>
                                            <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500">Polda Nusa Tenggara Barat</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Pertamax Column -->
                                <td class="px-6 py-4 text-center border-r border-slate-200 dark:border-white/5">
                                    <span class="inline-flex items-center justify-center min-w-[90px] px-3 py-1.5 rounded-xl text-xs font-black tabular-nums transition-all"
                                        :class="row.pertamax <= 0 ? 'bg-slate-100 dark:bg-slate-800 text-slate-400' : (row.pertamax < 50 ? 'bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-900/50' : 'bg-cyan-50 dark:bg-cyan-950/40 text-cyan-700 dark:text-cyan-300 border border-cyan-200 dark:border-cyan-800/50')">
                                        <span x-text="formatNum(row.pertamax)"></span>
                                        <span class="text-[10px] font-bold text-slate-400 ml-1">L</span>
                                    </span>
                                </td>

                                <!-- Pertamina Dex Column -->
                                <td class="px-6 py-4 text-center border-r border-slate-200 dark:border-white/5">
                                    <span class="inline-flex items-center justify-center min-w-[90px] px-3 py-1.5 rounded-xl text-xs font-black tabular-nums transition-all"
                                        :class="row.dex <= 0 ? 'bg-slate-100 dark:bg-slate-800 text-slate-400' : (row.dex < 50 ? 'bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-900/50' : 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/50')">
                                        <span x-text="formatNum(row.dex)"></span>
                                        <span class="text-[10px] font-bold text-slate-400 ml-1">L</span>
                                    </span>
                                </td>

                                <!-- Total Satker Column -->
                                <td class="px-6 py-4 text-center font-black text-slate-700 dark:text-slate-200 tabular-nums text-xs">
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-white/10"
                                        x-text="`${formatNum(row.pertamax + row.dex)} L`"></span>
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
        <div class="lg:hidden space-y-3.5">
            <template x-for="(row, idx) in filteredRows" :key="idx">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl p-4 shadow-sm relative overflow-hidden group">
                    <div class="flex items-center justify-between mb-3.5 pb-2.5 border-b border-slate-100 dark:border-white/5">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[10px] font-black flex items-center justify-center tabular-nums"
                                x-text="idx + 1"></span>
                            <h3 class="text-xs sm:text-sm font-black text-slate-800 dark:text-slate-100 uppercase tracking-tight"
                                x-text="row.satker"></h3>
                        </div>
                        <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400"
                            x-text="`Total: ${formatNum(row.pertamax + row.dex)} L`"></span>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5">
                        <!-- Pertamax Mini Card -->
                        <div class="bg-cyan-50/50 dark:bg-cyan-950/20 border border-cyan-200/60 dark:border-cyan-900/30 p-3 rounded-xl">
                            <span class="text-[9px] font-black text-cyan-600 dark:text-cyan-400 uppercase tracking-wider block mb-1">
                                Sisa Pertamax
                            </span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-lg font-black text-cyan-700 dark:text-cyan-300 tabular-nums tracking-tight"
                                    x-text="formatNum(row.pertamax)"></span>
                                <span class="text-[10px] font-bold text-cyan-500">Liter</span>
                            </div>
                        </div>

                        <!-- Dex Mini Card -->
                        <div class="bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-200/60 dark:border-indigo-900/30 p-3 rounded-xl">
                            <span class="text-[9px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-wider block mb-1">
                                Sisa P. Dex
                            </span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-lg font-black text-indigo-700 dark:text-indigo-300 tabular-nums tracking-tight"
                                    x-text="formatNum(row.dex)"></span>
                                <span class="text-[10px] font-bold text-indigo-500">Liter</span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Mobile Empty State -->
            <div x-show="filteredRows.length === 0" class="py-10 text-center text-slate-400">
                <p class="text-xs font-bold">Satker tidak ditemukan</p>
            </div>

            <!-- Mobile Total Summary Card -->
            <div class="bg-gradient-to-br from-indigo-900 via-slate-900 to-slate-900 text-white rounded-3xl p-5 border border-white/10 shadow-xl space-y-3">
                <span class="text-[10px] font-black uppercase tracking-widest text-cyan-300">Total Keseluruhan Sisa</span>
                <div class="grid grid-cols-2 gap-3 pt-1 border-t border-white/10">
                    <div>
                        <span class="text-[10px] text-slate-400 uppercase font-bold">Pertamax</span>
                        <p class="text-lg font-black text-cyan-400 tabular-nums">
                            {{ number_format($totalPertamax, 0, ',', '.') }} <span class="text-xs font-normal text-white">L</span>
                        </p>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 uppercase font-bold">Pertamina Dex</span>
                        <p class="text-lg font-black text-indigo-400 tabular-nums">
                            {{ number_format($totalDex, 0, ',', '.') }} <span class="text-xs font-normal text-white">L</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
