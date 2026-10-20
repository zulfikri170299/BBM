<x-app-layout>
    <div class="p-3 sm:p-6 lg:p-8 space-y-6 w-full max-w-full overflow-x-hidden">
        <!-- Executive Welcome Banner (Compact di Mobile, Mewah di Desktop) -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl border border-slate-200/80 dark:border-white/10 p-3.5 sm:p-7 shadow-sm">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-brand-primary/10 dark:bg-brand-primary/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-1.5 sm:gap-5">
                <div class="min-w-0">
                    <div class="hidden sm:inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-brand-primary/10 text-brand-primary dark:bg-white/10 dark:text-white mb-2.5">
                        <span class="w-2 h-2 rounded-full bg-brand-primary dark:bg-emerald-400 animate-pulse"></span>
                        SIM BBM • POLDA NTB
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <h1 class="text-base sm:text-3xl font-black text-slate-800 dark:text-white tracking-tight truncate">
                            Pusat Komando & Monitoring Eksekutif
                        </h1>
                        <!-- Tanggal Ringkas di Mobile -->
                        <span class="lg:hidden text-[10px] font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-lg border border-slate-200 dark:border-white/10 shrink-0">
                            {{ now()->translatedFormat('d M Y') }}
                        </span>
                    </div>
                    <p class="mt-0.5 text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                        Pantauan alokasi BBM & pergerakan transaksi seluruh jajaran Satker.<span class="hidden sm:inline"> Pengawasan komprehensif kapasitas stok tangki fisik dan armada kendaraan.</span>
                    </p>
                </div>

                <!-- Date & Status Chips di Desktop / Layar Besar -->
                <div class="hidden lg:flex flex-wrap items-center gap-2.5 shrink-0">
                    <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 text-xs font-bold text-slate-700 dark:text-slate-300 shadow-sm">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                    </div>

                    <div class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 text-xs font-bold shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        <span>Sistem Realtime</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Stats Grid (Executive Modern Cards - 2 Kolom di Mobile) -->
        <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-4 gap-2.5 sm:gap-5">
            <!-- Card 1: Total Satker (Vibrant Royal Blue Gradient) -->
            <div class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-900 text-white p-3 sm:p-5 shadow-md sm:shadow-lg shadow-blue-500/20 border border-blue-400/30 hover:shadow-xl hover:shadow-blue-500/30 hover:-translate-y-0.5 transition-all duration-300 group flex flex-col justify-between">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-cyan-400/20 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 sm:w-11 sm:h-11 rounded-lg sm:rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 text-white flex items-center justify-center shrink-0 shadow-inner">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <a href="{{ route('admin.satkers.index') }}"
                            class="relative z-20 cursor-pointer inline-flex items-center gap-1 text-[9px] sm:text-[11px] font-extrabold text-blue-100 hover:text-white bg-white/15 hover:bg-white/25 border border-white/20 px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-lg backdrop-blur-sm transition-all uppercase tracking-wider">
                            Kelola
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    <div class="mt-2.5 sm:mt-4">
                        <span class="text-[9px] sm:text-[11px] font-bold text-blue-100/90 uppercase tracking-wider block truncate">Total Satuan Kerja</span>
                        <div class="mt-0.5 sm:mt-1 flex items-baseline gap-1 sm:gap-2">
                            <span class="text-xl sm:text-4xl font-black text-white tabular-nums">
                                {{ number_format($stats['totalSatker'], 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] sm:text-xs font-extrabold text-blue-200">Satker</span>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 mt-3 sm:mt-5 pt-2 sm:pt-3.5 border-t border-white/20 flex items-center justify-between text-[10px] sm:text-xs text-blue-100/90">
                    <span class="font-medium hidden sm:inline">Status</span>
                    <span class="inline-flex items-center gap-1 font-bold text-cyan-200 bg-white/15 px-1.5 sm:px-2 py-0.5 rounded-full border border-white/20 text-[9px] sm:text-xs">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-300 animate-pulse"></span> 100% Aktif
                    </span>
                </div>
            </div>

            <!-- Card 2: Total Users (Vibrant Cyan / Teal Gradient) -->
            <div class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-cyan-600 via-teal-600 to-blue-800 text-white p-3 sm:p-5 shadow-md sm:shadow-lg shadow-cyan-500/20 border border-cyan-400/30 hover:shadow-xl hover:shadow-cyan-500/30 hover:-translate-y-0.5 transition-all duration-300 group flex flex-col justify-between">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 sm:w-11 sm:h-11 rounded-lg sm:rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 text-white flex items-center justify-center shrink-0 shadow-inner">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <a href="{{ route('admin.users.index') }}"
                            class="relative z-20 cursor-pointer inline-flex items-center gap-1 text-[9px] sm:text-[11px] font-extrabold text-cyan-100 hover:text-white bg-white/15 hover:bg-white/25 border border-white/20 px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-lg backdrop-blur-sm transition-all uppercase tracking-wider">
                            Kelola
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    <div class="mt-2.5 sm:mt-4">
                        <span class="text-[9px] sm:text-[11px] font-bold text-cyan-100/90 uppercase tracking-wider block truncate">Pengguna Terdaftar</span>
                        <div class="mt-0.5 sm:mt-1 flex items-baseline gap-1 sm:gap-2">
                            <span class="text-xl sm:text-4xl font-black text-white tabular-nums">
                                {{ number_format($stats['totalUsers'], 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] sm:text-xs font-extrabold text-cyan-200">Akun</span>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 mt-3 sm:mt-5 pt-2 sm:pt-3.5 border-t border-white/20 flex items-center justify-between text-[10px] sm:text-xs text-cyan-100/90">
                    <span class="font-medium hidden sm:inline">Hak Akses</span>
                    <span class="font-extrabold text-white bg-white/15 px-1.5 sm:px-2 py-0.5 rounded-full border border-white/20 text-[9px] sm:text-xs">Terverifikasi</span>
                </div>
            </div>

            <!-- Card 3: Total Kendaraan (Vibrant Amber / Orange Gradient) -->
            <div class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-amber-500 via-orange-600 to-rose-700 text-white p-3 sm:p-5 shadow-md sm:shadow-lg shadow-orange-500/20 border border-orange-400/30 hover:shadow-xl hover:shadow-orange-500/30 hover:-translate-y-0.5 transition-all duration-300 group flex flex-col justify-between">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 sm:w-11 sm:h-11 rounded-lg sm:rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 text-white flex items-center justify-center shrink-0 shadow-inner">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1" />
                            </svg>
                        </div>
                        <a href="{{ route('admin.kendaraans.index') }}"
                            class="relative z-20 cursor-pointer inline-flex items-center gap-1 text-[9px] sm:text-[11px] font-extrabold text-amber-100 hover:text-white bg-white/15 hover:bg-white/25 border border-white/20 px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-lg backdrop-blur-sm transition-all uppercase tracking-wider">
                            Kelola
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    <div class="mt-2.5 sm:mt-4">
                        <span class="text-[9px] sm:text-[11px] font-bold text-amber-100/90 uppercase tracking-wider block truncate">Kendaraan Dinas</span>
                        <div class="mt-0.5 sm:mt-1 flex items-baseline gap-1 sm:gap-2">
                            <span class="text-xl sm:text-4xl font-black text-white tabular-nums">
                                {{ number_format($stats['totalKendaraan'], 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] sm:text-xs font-extrabold text-amber-200">Unit</span>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 mt-3 sm:mt-5 pt-2 sm:pt-3.5 border-t border-white/20 grid grid-cols-4 gap-1 text-center">
                    <div class="bg-black/20 backdrop-blur-sm rounded py-0.5 sm:py-1 px-0.5 border border-white/15">
                        <span class="block text-[8px] sm:text-[9px] font-bold text-amber-100/80">R2</span>
                        <span class="text-[10px] sm:text-xs font-black text-white">{{ $stats['rodaR2'] }}</span>
                    </div>
                    <div class="bg-black/20 backdrop-blur-sm rounded py-0.5 sm:py-1 px-0.5 border border-white/15">
                        <span class="block text-[8px] sm:text-[9px] font-bold text-amber-100/80">R4</span>
                        <span class="text-[10px] sm:text-xs font-black text-white">{{ $stats['rodaR4'] }}</span>
                    </div>
                    <div class="bg-black/20 backdrop-blur-sm rounded py-0.5 sm:py-1 px-0.5 border border-white/15">
                        <span class="block text-[8px] sm:text-[9px] font-bold text-amber-100/80">R6</span>
                        <span class="text-[10px] sm:text-xs font-black text-white">{{ $stats['rodaR6'] }}</span>
                    </div>
                    <div class="bg-black/20 backdrop-blur-sm rounded py-0.5 sm:py-1 px-0.5 border border-white/15">
                        <span class="block text-[8px] sm:text-[9px] font-bold text-amber-100/80">NON</span>
                        <span class="text-[10px] sm:text-xs font-black text-white">{{ $stats['rodaNon'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Card 4: Total Transaksi (Vibrant Ruby / Crimson Gradient) -->
            <div class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-rose-600 via-rose-700 to-red-900 text-white p-3 sm:p-5 shadow-md sm:shadow-lg shadow-rose-500/20 border border-rose-400/30 hover:shadow-xl hover:shadow-rose-500/30 hover:-translate-y-0.5 transition-all duration-300 group flex flex-col justify-between">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-400/20 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 sm:w-11 sm:h-11 rounded-lg sm:rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 text-white flex items-center justify-center shrink-0 shadow-inner">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <a href="{{ route('admin.riwayat.index') }}"
                            class="relative z-20 cursor-pointer inline-flex items-center gap-1 text-[9px] sm:text-[11px] font-extrabold text-rose-100 hover:text-white bg-white/15 hover:bg-white/25 border border-white/20 px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-lg backdrop-blur-sm transition-all uppercase tracking-wider">
                            Riwayat
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    <div class="mt-2.5 sm:mt-4">
                        <span class="text-[9px] sm:text-[11px] font-bold text-rose-100/90 uppercase tracking-wider block truncate">Total Transaksi BBM</span>
                        <div class="mt-0.5 sm:mt-1 flex items-baseline gap-1 sm:gap-2">
                            <span class="text-xl sm:text-4xl font-black text-white tabular-nums">
                                {{ number_format($stats['totalTransaksi'], 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] sm:text-xs font-extrabold text-rose-200">Log</span>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 mt-3 sm:mt-5 pt-2 sm:pt-3.5 border-t border-white/20 flex items-center justify-between text-[10px] sm:text-xs text-rose-100/90">
                    <span>Vol: <strong class="text-white">{{ number_format($stats['totalLiter'] ?? 0, 0, ',', '.') }} L</strong></span>
                    <span class="hidden sm:inline">Bon: <strong class="text-amber-200 bg-white/15 px-2 py-0.5 rounded-full border border-white/20">{{ number_format(($stats['totalHutangPertamax'] ?? 0) + ($stats['totalHutangDex'] ?? 0), 0, ',', '.') }} L</strong></span>
                </div>
            </div>
        </div>

        @if(in_array(auth()->user()->role, ['super_admin', 'kasubbag']))
        <!-- DUA DIAGRAM BATANG TERPISAH: STOK TANGKI FISIK & HUTANG BBM -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 w-full min-w-0">
            <!-- DIAGRAM BATANG 1: STOK TANGKI FISIK BBM -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 rounded-3xl p-4 sm:p-6 shadow-sm relative overflow-hidden flex flex-col justify-between w-full min-w-0 group hover:border-blue-500/30 transition-all">
                <div class="absolute -top-16 -right-16 w-60 h-60 bg-blue-500/10 dark:bg-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>

                <div>
                    <!-- Header -->
                    <div class="relative z-10 flex items-center justify-between gap-2.5 sm:gap-3 pb-3.5 sm:pb-4 border-b border-slate-100 dark:border-white/5">
                        <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/20 shrink-0">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5 sm:gap-2">
                                    <h3 class="text-xs sm:text-base font-black text-slate-800 dark:text-white whitespace-nowrap">
                                        <span class="hidden sm:inline">Diagram Batang: </span>Stok Tangki Fisik
                                    </h3>
                                    <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded-full text-[9px] sm:text-[10px] font-black uppercase bg-blue-500/10 text-blue-600 dark:text-blue-400 shrink-0">
                                        Tersedia
                                    </span>
                                </div>
                                <p class="text-[11px] sm:text-xs text-slate-400 mt-0.5 truncate sm:whitespace-normal">Ketersediaan volume riil BBM di tangki fisik SPBP</p>
                            </div>
                        </div>

                        <a href="{{ route('admin.laporan-stok-bbm.index') }}"
                            class="inline-flex items-center gap-1 sm:gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-[11px] sm:text-xs font-bold transition shrink-0">
                            <span>Laporan</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    <!-- Chart Container -->
                    <div class="relative z-10 w-full min-w-0 overflow-hidden my-3">
                        <div id="tankStockBarChart" class="w-full min-h-[260px]"></div>
                    </div>
                </div>

                <!-- Footer Summary Pills -->
                <div class="relative z-10 pt-3 border-t border-slate-100 dark:border-white/5 grid grid-cols-2 gap-3 text-xs">
                    <div class="p-3 rounded-2xl bg-blue-50/50 dark:bg-blue-500/5 border border-blue-500/20 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-blue-600 dark:text-blue-400 block uppercase">Pertamax</span>
                            <span class="text-lg font-black text-slate-800 dark:text-white tabular-nums">
                                {{ number_format($tankStock['pertamax'], 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] font-bold text-slate-400"> L</span>
                        </div>
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500 shadow-sm shadow-blue-500/50"></span>
                    </div>

                    <div class="p-3 rounded-2xl bg-emerald-50/50 dark:bg-emerald-500/5 border border-emerald-500/20 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 block uppercase">Pertamina Dex</span>
                            <span class="text-lg font-black text-slate-800 dark:text-white tabular-nums">
                                {{ number_format($tankStock['dex'], 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] font-bold text-slate-400"> L</span>
                        </div>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></span>
                    </div>
                </div>
            </div>

            <!-- DIAGRAM BATANG 2: HUTANG BBM TERTUNDA (BELUM BAYAR) -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 rounded-3xl p-4 sm:p-6 shadow-sm relative overflow-hidden flex flex-col justify-between w-full min-w-0 group hover:border-rose-500/30 transition-all">
                <div class="absolute -top-16 -right-16 w-60 h-60 bg-rose-500/10 dark:bg-rose-500/15 rounded-full blur-3xl pointer-events-none"></div>

                <div>
                    <!-- Header -->
                    <div class="relative z-10 flex items-center justify-between gap-2.5 sm:gap-3 pb-3.5 sm:pb-4 border-b border-slate-100 dark:border-white/5">
                        <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-gradient-to-br from-amber-500 to-rose-600 text-white flex items-center justify-center shadow-lg shadow-rose-500/20 shrink-0">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5 sm:gap-2">
                                    <h3 class="text-xs sm:text-base font-black text-slate-800 dark:text-white whitespace-nowrap">
                                        <span class="hidden sm:inline">Diagram Batang: </span>Hutang BBM
                                    </h3>
                                    <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded-full text-[9px] sm:text-[10px] font-black uppercase bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 shrink-0">
                                        Tertunda
                                    </span>
                                </div>
                                <p class="text-[11px] sm:text-xs text-slate-400 mt-0.5 truncate sm:whitespace-normal">Akumulasi volume bon BBM yang belum diselesaikan</p>
                            </div>
                        </div>

                        <a href="{{ route('admin.hutang.index') }}"
                            class="inline-flex items-center gap-1 sm:gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 text-[11px] sm:text-xs font-bold transition border border-rose-500/20 shrink-0">
                            <span>Kelola</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    <!-- Chart Container -->
                    <div class="relative z-10 w-full min-w-0 overflow-hidden my-3">
                        <div id="debtStockBarChart" class="w-full min-h-[260px]"></div>
                    </div>
                </div>

                <!-- Footer Summary Pills -->
                <div class="relative z-10 pt-3 border-t border-slate-100 dark:border-white/5 grid grid-cols-2 gap-3 text-xs">
                    <div class="p-3 rounded-2xl bg-amber-50/50 dark:bg-amber-500/5 border border-amber-500/20 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 block uppercase">Bon Pertamax</span>
                            <span class="text-lg font-black text-amber-600 dark:text-amber-400 tabular-nums">
                                {{ number_format($stats['totalHutangPertamax'], 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] font-bold text-slate-400"> L</span>
                        </div>
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shadow-sm shadow-amber-500/50"></span>
                    </div>

                    <div class="p-3 rounded-2xl bg-rose-50/50 dark:bg-rose-500/5 border border-rose-500/20 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-rose-600 dark:text-rose-400 block uppercase">Bon Dex</span>
                            <span class="text-lg font-black text-rose-600 dark:text-rose-400 tabular-nums">
                                {{ number_format($stats['totalHutangDex'], 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] font-bold text-slate-400"> L</span>
                        </div>
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shadow-sm shadow-rose-500/50"></span>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Hutang Stats Row (Untuk Role Non-SuperAdmin) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-amber-500/5 dark:bg-amber-500/10 rounded-2xl border border-amber-500/20 p-5 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-[11px] font-black uppercase tracking-wider text-amber-700 dark:text-amber-400">Hutang Pertamax (Belum Bayar)</span>
                        <div class="mt-0.5 flex items-baseline gap-1.5">
                            <span class="text-2xl font-black text-slate-800 dark:text-white tabular-nums">
                                {{ number_format($stats['totalHutangPertamax'], 0, ',', '.') }}
                            </span>
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Liter</span>
                        </div>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-500/20 text-amber-700 dark:text-amber-300 uppercase">
                    Tertunda
                </span>
            </div>

            <div class="bg-rose-500/5 dark:bg-rose-500/10 rounded-2xl border border-rose-500/20 p-5 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-[11px] font-black uppercase tracking-wider text-rose-700 dark:text-rose-400">Hutang Dex (Belum Bayar)</span>
                        <div class="mt-0.5 flex items-baseline gap-1.5">
                            <span class="text-2xl font-black text-slate-800 dark:text-white tabular-nums">
                                {{ number_format($stats['totalHutangDex'], 0, ',', '.') }}
                            </span>
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Liter</span>
                        </div>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black bg-rose-500/20 text-rose-700 dark:text-rose-300 uppercase">
                    Tertunda
                </span>
            </div>
        </div>
        @endif

        <!-- DIAGRAM SECTION 1: DIAGRAM BATANG (TRANSAKSI HARIAN) & DIAGRAM LINGKAR (DISTRIBUSI ARMADA) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 w-full min-w-0">
            <!-- Diagram Batang (2 cols) -->
            <div class="lg:col-span-2 w-full min-w-0 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col justify-between overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-2.5 h-6 bg-indigo-600 rounded-full"></div>
                        <div>
                            <h3 class="text-sm sm:text-base font-black text-slate-800 dark:text-white"><span class="hidden sm:inline">Diagram Batang: </span>Transaksi & Konsumsi BBM Harian</h3>
                            <p class="text-xs text-slate-400">Pantauan frekuensi transaksi dan pergerakan volume liter selama 7 hari terakhir</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20 self-start sm:self-auto">
                        Batang Rounded • 7 Hari
                    </span>
                </div>
                <div class="w-full min-w-0 overflow-hidden">
                    <div id="barTransactionChart" class="w-full min-h-[290px]"></div>
                </div>
            </div>

            <!-- Diagram Lingkar 1: Distribusi Armada Kendaraan (1 col) -->
            <div class="w-full min-w-0 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col justify-between overflow-hidden">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-2.5 h-6 bg-emerald-500 rounded-full"></div>
                            <div>
                                <h3 class="text-sm sm:text-base font-black text-slate-800 dark:text-white"><span class="hidden sm:inline">Diagram Lingkar: </span>Armada Roda</h3>
                                <p class="text-xs text-slate-400">Komposisi tipe roda kendaraan</p>
                            </div>
                        </div>
                    </div>
                    <div class="w-full min-w-0 overflow-hidden flex items-center justify-center">
                        <div id="fleetDonutChart" class="w-full max-w-[340px] min-h-[220px]"></div>
                    </div>
                </div>
                <div class="mt-4 pt-3.5 border-t border-slate-100 dark:border-white/5 grid grid-cols-2 gap-2 text-center text-xs">
                    <div class="p-2 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-white/5">
                        <span class="text-slate-400 font-bold block text-[10px]">Roda 2 (R2)</span>
                        <span class="font-black text-emerald-600 dark:text-emerald-400 text-sm">{{ $stats['rodaR2'] }} Unit</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-white/5">
                        <span class="text-slate-400 font-bold block text-[10px]">Roda 4 (R4)</span>
                        <span class="font-black text-blue-600 dark:text-blue-400 text-sm">{{ $stats['rodaR4'] }} Unit</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- DIAGRAM SECTION 2: INDEKS KEPUASAN (DIAGRAM LINGKAR) & SALDO BBM PUSAT/KENDARAAN -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 w-full min-w-0">
            <!-- Diagram Lingkar 2: Indeks Kepuasan Petugas -->
            <div class="w-full min-w-0 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col justify-between overflow-hidden">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-2.5 h-6 bg-amber-500 rounded-full"></div>
                            <div>
                                <h3 class="text-sm sm:text-base font-black text-slate-800 dark:text-white"><span class="hidden sm:inline">Diagram Lingkar: </span>Indeks Kepuasan</h3>
                                <p class="text-xs text-slate-400">Evaluasi respon kepuasan petugas BBM</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-slate-400">{{ $satisfactionStats['total'] }} Responden</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                        <div class="space-y-2.5 order-2 md:order-1">
                            <div class="p-2.5 bg-emerald-500/10 rounded-xl border border-emerald-500/20 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-lg">🤩</span>
                                    <div>
                                        <p class="text-[11px] font-bold text-emerald-700 dark:text-emerald-400 uppercase">Sangat Puas</p>
                                        <p class="text-[10px] text-slate-500">{{ $satisfactionStats['p_sangat_puas'] }}%</p>
                                    </div>
                                </div>
                                <span class="text-sm font-black text-emerald-600 dark:text-emerald-400 tabular-nums">{{ $satisfactionStats['sangat_puas'] }}</span>
                            </div>

                            <div class="p-2.5 bg-amber-500/10 rounded-xl border border-amber-500/20 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-lg">🙂</span>
                                    <div>
                                        <p class="text-[11px] font-bold text-amber-700 dark:text-amber-400 uppercase">Puas</p>
                                        <p class="text-[10px] text-slate-500">{{ $satisfactionStats['p_puas'] }}%</p>
                                    </div>
                                </div>
                                <span class="text-sm font-black text-amber-600 dark:text-amber-400 tabular-nums">{{ $satisfactionStats['puas'] }}</span>
                            </div>

                            <div class="p-2.5 bg-rose-500/10 rounded-xl border border-rose-500/20 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-lg">😡</span>
                                    <div>
                                        <p class="text-[11px] font-bold text-rose-700 dark:text-rose-400 uppercase">Tidak Puas</p>
                                        <p class="text-[10px] text-slate-500">{{ $satisfactionStats['p_tidak_puas'] }}%</p>
                                    </div>
                                </div>
                                <span class="text-sm font-black text-rose-600 dark:text-rose-400 tabular-nums">{{ $satisfactionStats['tidak_puas'] }}</span>
                            </div>
                        </div>
                        <div class="w-full min-w-0 overflow-hidden flex items-center justify-center order-1 md:order-2">
                            <div id="satisfactionDonutChart" class="w-full max-w-[280px] min-h-[190px]"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Saldo BBM Belum Distribusi (Pusat) & Saldo Kendaraan -->
            <div class="w-full min-w-0 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col justify-between overflow-hidden">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-2.5 h-6 bg-blue-500 rounded-full"></div>
                            <div>
                                <h3 class="text-base font-black text-slate-800 dark:text-white">Ketersediaan Saldo BBM Armada & Pusat</h3>
                                <p class="text-xs text-slate-400">Distribusi saldo bahan bakar aktif</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.stok.index') }}"
                            class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                            Kelola Stok
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    <div class="space-y-3">
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">Saldo BBM di Seluruh Kendaraan Armada</span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($kendaraanFuel as $kFuel)
                                @php
                                    $isDex = stripos($kFuel->jenis_bbm, 'dex') !== false;
                                @endphp
                                <div class="p-3.5 rounded-xl border {{ $isDex ? 'bg-emerald-500/5 border-emerald-500/20' : 'bg-blue-500/5 border-blue-500/20' }}">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-black uppercase tracking-wider {{ $isDex ? 'text-emerald-600 dark:text-emerald-400' : 'text-blue-600 dark:text-blue-400' }}">
                                            {{ $kFuel->jenis_bbm }}
                                        </span>
                                        <span class="w-2 h-2 rounded-full {{ $isDex ? 'bg-emerald-500' : 'bg-blue-500' }}"></span>
                                    </div>
                                    <div class="mt-1.5 flex items-baseline gap-1.5">
                                        <span class="text-xl font-black text-slate-800 dark:text-white tabular-nums">
                                            {{ number_format($kFuel->total, 0, ',', '.') }}
                                        </span>
                                        <span class="text-xs font-bold text-slate-400">Liter</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block pt-2">Saldo Pusat (Belum Distribusi ke Satker)</span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($adminStocks as $aStock)
                                @php
                                    $isDexStock = stripos($aStock->jenis_bbm, 'dex') !== false;
                                @endphp
                                <div class="p-3 rounded-xl border {{ $isDexStock ? 'bg-emerald-500/5 border-emerald-500/20' : 'bg-blue-500/5 border-blue-500/20' }} flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] font-bold text-slate-400 block">{{ $aStock->jenis_bbm }}</span>
                                        <span class="text-base font-black text-slate-800 dark:text-white tabular-nums">
                                            {{ rtrim(rtrim(number_format($aStock->saldo, 2, ',', '.'), '0'), ',') }} L
                                        </span>
                                    </div>
                                    <div class="w-7 h-7 rounded-lg {{ $isDexStock ? 'bg-emerald-500/10 text-emerald-600' : 'bg-blue-500/10 text-blue-600' }} flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions & Map Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <!-- Left: Recent Transactions (1 col) -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 rounded-2xl shadow-sm overflow-hidden flex flex-col">
                <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-brand-primary/10 text-brand-primary flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-black text-slate-800 dark:text-white">Aktivitas Transaksi Terbaru</h3>
                    </div>
                    <a href="{{ route('admin.riwayat.index') }}" class="text-xs font-bold text-brand-primary hover:underline">Semua</a>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-white/5 overflow-y-auto max-h-[380px]">
                    @forelse($recentTransactions as $trx)
                        <div class="p-4 hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-colors flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-mono font-black text-slate-800 dark:text-slate-100 truncate">
                                        {{ $trx->kendaraan?->no_polisi ?? $trx->nopol ?? '-' }}
                                    </p>
                                    <p class="text-[10px] text-slate-400">
                                        {{ $trx->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-mono font-black text-xs text-emerald-600 dark:text-emerald-400 tabular-nums">
                                    {{ number_format($trx->liter, 0, ',', '.') }} L
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-xs text-slate-400">Belum ada riwayat transaksi terbaru.</div>
                    @endforelse
                </div>
            </div>

            <!-- Right: Leaflet Map (2 cols) -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 rounded-2xl shadow-sm p-4 sm:p-5 flex flex-col">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-800 dark:text-white">Peta Sebaran Lokasi Operator & Personel</h3>
                            <p class="text-[10px] text-slate-400">Pemantauan lokasi aktif petugas di wilayah hukum Polda NTB</p>
                        </div>
                    </div>
                </div>
                <div id="userMap" class="w-full h-[320px] rounded-xl overflow-hidden border border-slate-200 dark:border-white/10"></div>
            </div>
        </div>
    </div>

    <!-- Chart Script & Data -->
    @php
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $q = \App\Models\TransaksiBbm::whereDate('created_at', $date);
            $count = (clone $q)->count();
            $liter = (clone $q)->sum('liter');
            $chartData[] = [
                'date' => $date->format('d M'), 
                'count' => $count,
                'liter' => round($liter, 1)
            ];
        }
    @endphp

    @push('styles')
        <style>
            .leaflet-popup-content-wrapper {
                border-radius: 14px;
                padding: 0.5rem;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
            }
            .leaflet-popup-tip {
                background: white;
            }
        </style>
    @endpush

    @push('scripts')
        <!-- Map Script -->
        <script>
            document.addEventListener('turbo:load', function () {
                var mapContainer = document.getElementById('userMap');
                if (mapContainer && typeof L !== 'undefined') {
                    mapContainer.style.height = '320px';
                    
                    if (window.myUserMap) {
                        window.myUserMap.off();
                        window.myUserMap.remove();
                        window.myUserMap = null;
                    }

                    window.myUserMap = L.map('userMap', {
                        maxBounds: [[-11.0, 95.0], [6.0, 141.0]],
                        minZoom: 5,
                        maxBoundsViscosity: 1.0
                    }).setView([-8.5833, 116.1167], 8);

                    var map = window.myUserMap;

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    var users = {!! json_encode($usersWithLocation) !!};
                    var markers = [];

                    function getRoleColor(role) {
                        switch (role) {
                            case 'super_admin': return 'bg-rose-500';
                            case 'admin_satker': return 'bg-indigo-600';
                            case 'petugas_bbm': return 'bg-amber-500';
                            case 'personel': return 'bg-emerald-500';
                            default: return 'bg-slate-500';
                        }
                    }

                    users.forEach(function (user) {
                        if (user.last_latitude && user.last_longitude) {
                            var roleColor = getRoleColor(user.role);
                            var initials = user.name ? user.name.substring(0, 2).toUpperCase() : 'U';

                            var iconHtml = `
                                <div class="relative flex items-center justify-center w-8 h-8 rounded-full shadow-lg border-2 border-white ${roleColor} text-white font-bold text-xs transform hover:scale-110 transition-transform">
                                    ${initials}
                                    <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-[4px] border-l-transparent border-r-[4px] border-r-transparent border-t-[6px] border-t-white opacity-50"></div>
                                </div>
                            `;

                            var customIcon = L.divIcon({
                                html: iconHtml,
                                className: '',
                                iconSize: [32, 32],
                                iconAnchor: [16, 32],
                                popupAnchor: [0, -32]
                            });

                            var marker = L.marker([user.last_latitude, user.last_longitude], { icon: customIcon }).addTo(map);

                            var lastActive = user.last_activity_at ? new Date(user.last_activity_at).toLocaleString('id-ID') : '-';
                            var roleLabel = user.role ? user.role.replace('_', ' ').toUpperCase() : '-';

                            var popupContent = `
                                <div class="p-2 min-w-[200px]">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="w-8 h-8 rounded-full ${roleColor} flex items-center justify-center text-white text-xs font-bold shadow-sm">${initials}</span>
                                        <div>
                                            <h4 class="font-bold text-slate-800 text-sm leading-tight">${user.name}</h4>
                                            <p class="text-[10px] text-indigo-600 font-bold tracking-wide">${roleLabel}</p>
                                        </div>
                                    </div>
                                    <div class="text-xs text-slate-500 border-t border-slate-200 pt-2 space-y-1">
                                        <p class="flex items-center gap-1.5">
                                            <span class="font-medium">Aktif:</span> ${lastActive}
                                        </p>
                                    </div>
                                </div>
                            `;

                            marker.bindPopup(popupContent);
                            markers.push(marker);
                        }
                    });

                    if (markers.length > 0) {
                        var group = new L.featureGroup(markers);
                        map.fitBounds(group.getBounds().pad(0.1));
                    }

                    setTimeout(function () { map.invalidateSize(); }, 500);
                }
            });

            document.addEventListener('turbo:before-cache', function () {
                if (window.myUserMap) {
                    try {
                        window.myUserMap.off();
                        window.myUserMap.remove();
                    } catch (e) {}
                    window.myUserMap = null;
                }
            });
        </script>

        <!-- Charts Script (Diagram Batang & Diagram Lingkar) -->
        <script>
            function destroyAdminCharts() {
                if (window.myAdminTankStockChart) {
                    try { window.myAdminTankStockChart.destroy(); } catch (e) {}
                    window.myAdminTankStockChart = null;
                }
                if (window.myAdminDebtStockChart) {
                    try { window.myAdminDebtStockChart.destroy(); } catch (e) {}
                    window.myAdminDebtStockChart = null;
                }
                if (window.myAdminBarChart) {
                    try { window.myAdminBarChart.destroy(); } catch (e) {}
                    window.myAdminBarChart = null;
                }
                if (window.myAdminFleetChart) {
                    try { window.myAdminFleetChart.destroy(); } catch (e) {}
                    window.myAdminFleetChart = null;
                }
                if (window.myAdminSatisfactionChart) {
                    try { window.myAdminSatisfactionChart.destroy(); } catch (e) {}
                    window.myAdminSatisfactionChart = null;
                }
                var tsEl = document.querySelector("#tankStockBarChart");
                if (tsEl) tsEl.innerHTML = '';
                var dsEl = document.querySelector("#debtStockBarChart");
                if (dsEl) dsEl.innerHTML = '';
                var barEl = document.querySelector("#barTransactionChart");
                if (barEl) barEl.innerHTML = '';
                var fleetEl = document.querySelector("#fleetDonutChart");
                if (fleetEl) fleetEl.innerHTML = '';
                var donutEl = document.querySelector("#satisfactionDonutChart");
                if (donutEl) donutEl.innerHTML = '';
            }

            function initAdminCharts() {
                destroyAdminCharts();

                // 0A. DIAGRAM BATANG 1: STOK TANGKI FISIK BBM
                var tankStockEl = document.querySelector("#tankStockBarChart");
                if (tankStockEl && typeof ApexCharts !== 'undefined') {
                    var tankStockOptions = {
                        chart: {
                            type: 'bar',
                            height: 250,
                            width: '100%',
                            fontFamily: 'Outfit, sans-serif',
                            toolbar: { show: false },
                            zoom: { enabled: false }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '38%',
                                borderRadius: 10,
                                borderRadiusApplication: 'end',
                                distributed: true,
                                dataLabels: {
                                    position: 'top'
                                }
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: function (val) {
                                return val > 0 ? val.toLocaleString('id-ID') + ' L' : '0 L';
                            },
                            offsetY: -22,
                            style: {
                                fontSize: '11px',
                                fontFamily: 'Outfit, sans-serif',
                                fontWeight: 'bold',
                                colors: ["#64748b"]
                            }
                        },
                        series: [{
                            name: 'Stok Fisik Tersedia',
                            data: [
                                {{ (int) ($tankStock['pertamax'] ?? 0) }},
                                {{ (int) ($tankStock['dex'] ?? 0) }}
                            ]
                        }],
                        xaxis: {
                            categories: ['Pertamax', 'Pertamina Dex'],
                            labels: {
                                style: {
                                    colors: '#94a3b8',
                                    fontSize: '12px',
                                    fontFamily: 'Outfit, sans-serif',
                                    fontWeight: 'bold'
                                }
                            },
                            axisBorder: { show: false },
                            axisTicks: { show: false }
                        },
                        yaxis: {
                            labels: {
                                style: { colors: '#94a3b8', fontSize: '11px' },
                                formatter: function (val) {
                                    return val.toLocaleString('id-ID') + ' L';
                                }
                            }
                        },
                        colors: ['#0284c7', '#10b981'],
                        grid: {
                            borderColor: '#33415520',
                            strokeDashArray: 4
                        },
                        legend: { show: false },
                        tooltip: {
                            theme: 'dark',
                            y: {
                                formatter: function (val) {
                                    return val.toLocaleString('id-ID') + ' Liter Tersedia';
                                }
                            }
                        }
                    };
                    window.myAdminTankStockChart = new ApexCharts(tankStockEl, tankStockOptions);
                    window.myAdminTankStockChart.render();
                }

                // 0B. DIAGRAM BATANG 2: HUTANG BBM TERTUNDA (BELUM BAYAR)
                var debtStockEl = document.querySelector("#debtStockBarChart");
                if (debtStockEl && typeof ApexCharts !== 'undefined') {
                    var debtStockOptions = {
                        chart: {
                            type: 'bar',
                            height: 250,
                            width: '100%',
                            fontFamily: 'Outfit, sans-serif',
                            toolbar: { show: false },
                            zoom: { enabled: false }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '38%',
                                borderRadius: 10,
                                borderRadiusApplication: 'end',
                                distributed: true,
                                dataLabels: {
                                    position: 'top'
                                }
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: function (val) {
                                return val > 0 ? val.toLocaleString('id-ID') + ' L' : '0 L';
                            },
                            offsetY: -22,
                            style: {
                                fontSize: '11px',
                                fontFamily: 'Outfit, sans-serif',
                                fontWeight: 'bold',
                                colors: ["#64748b"]
                            }
                        },
                        series: [{
                            name: 'Hutang Bon Belum Bayar',
                            data: [
                                {{ (int) ($stats['totalHutangPertamax'] ?? 0) }},
                                {{ (int) ($stats['totalHutangDex'] ?? 0) }}
                            ]
                        }],
                        xaxis: {
                            categories: ['Pertamax (Bon)', 'Pertamina Dex (Bon)'],
                            labels: {
                                style: {
                                    colors: '#94a3b8',
                                    fontSize: '12px',
                                    fontFamily: 'Outfit, sans-serif',
                                    fontWeight: 'bold'
                                }
                            },
                            axisBorder: { show: false },
                            axisTicks: { show: false }
                        },
                        yaxis: {
                            labels: {
                                style: { colors: '#94a3b8', fontSize: '11px' },
                                formatter: function (val) {
                                    return val.toLocaleString('id-ID') + ' L';
                                }
                            }
                        },
                        colors: ['#f59e0b', '#f43f5e'],
                        grid: {
                            borderColor: '#33415520',
                            strokeDashArray: 4
                        },
                        legend: { show: false },
                        tooltip: {
                            theme: 'dark',
                            y: {
                                formatter: function (val) {
                                    return val.toLocaleString('id-ID') + ' Liter Tertunda';
                                }
                            }
                        }
                    };
                    window.myAdminDebtStockChart = new ApexCharts(debtStockEl, debtStockOptions);
                    window.myAdminDebtStockChart.render();
                }

                // 1. DIAGRAM BATANG (BAR CHART): Transaksi & Liter 7 Hari
                var barEl = document.querySelector("#barTransactionChart");
                if (barEl && typeof ApexCharts !== 'undefined') {
                    var barOptions = {
                        chart: {
                            type: 'bar',
                            height: 290,
                            width: '100%',
                            fontFamily: 'Outfit, sans-serif',
                            toolbar: { show: false },
                            zoom: { enabled: false }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '45%',
                                borderRadius: 8,
                                borderRadiusApplication: 'end',
                                dataLabels: {
                                    position: 'top',
                                }
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: function (val) {
                                return val > 0 ? val : '';
                            },
                            offsetY: -20,
                            style: {
                                fontSize: '10px',
                                fontFamily: 'Outfit, sans-serif',
                                fontWeight: 'bold',
                                colors: ["#64748b"]
                            }
                        },
                        series: [{
                            name: 'Volume Liter',
                            data: {!! json_encode(array_column($chartData, 'liter')) !!}
                        }, {
                            name: 'Frekuensi Transaksi',
                            data: {!! json_encode(array_column($chartData, 'count')) !!}
                        }],
                        xaxis: {
                            categories: {!! json_encode(array_column($chartData, 'date')) !!},
                            labels: {
                                style: { colors: '#94a3b8', fontSize: '11px', fontFamily: 'Outfit, sans-serif' }
                            },
                            axisBorder: { show: false },
                            axisTicks: { show: false }
                        },
                        yaxis: {
                            labels: {
                                style: { colors: '#94a3b8', fontSize: '11px' }
                            }
                        },
                        colors: ['#4f46e5', '#06b6d4'], // Indigo & Cyan
                        grid: {
                            borderColor: '#33415520',
                            strokeDashArray: 4
                        },
                        legend: {
                            position: 'top',
                            horizontalAlign: 'right',
                            fontFamily: 'Outfit, sans-serif',
                            labels: {
                                colors: '#94a3b8'
                            }
                        },
                        tooltip: {
                            theme: 'dark'
                        }
                    };
                    window.myAdminBarChart = new ApexCharts(barEl, barOptions);
                    window.myAdminBarChart.render();
                }

                // 2. DIAGRAM LINGKAR (DONUT CHART): Komposisi Armada Kendaraan
                var fleetEl = document.querySelector("#fleetDonutChart");
                if (fleetEl && typeof ApexCharts !== 'undefined') {
                    var fleetOptions = {
                        chart: {
                            type: 'donut',
                            height: 230,
                            width: '100%',
                            fontFamily: 'Outfit, sans-serif',
                        },
                        series: [
                            {{ (int) $stats['rodaR2'] }},
                            {{ (int) $stats['rodaR4'] }},
                            {{ (int) $stats['rodaR6'] }},
                            {{ (int) $stats['rodaNon'] }}
                        ],
                        labels: ['Roda 2 (R2)', 'Roda 4 (R4)', 'Roda 6 (R6)', 'Non Kendaraan'],
                        colors: ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6'],
                        legend: {
                            position: 'bottom',
                            fontSize: '11px',
                            fontFamily: 'Outfit, sans-serif',
                            labels: { colors: '#94a3b8' }
                        },
                        dataLabels: { enabled: false },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '72%',
                                    labels: {
                                        show: true,
                                        total: {
                                            show: true,
                                            label: 'Total Unit',
                                            color: '#94a3b8',
                                            fontSize: '11px',
                                            fontWeight: 600,
                                            formatter: function () {
                                                return {{ (int) $stats['totalKendaraan'] }};
                                            }
                                        }
                                    }
                                }
                            }
                        },
                        tooltip: { theme: 'dark' }
                    };
                    window.myAdminFleetChart = new ApexCharts(fleetEl, fleetOptions);
                    window.myAdminFleetChart.render();
                }

                // 3. DIAGRAM LINGKAR (DONUT CHART): Indeks Kepuasan
                var donutEl = document.querySelector("#satisfactionDonutChart");
                if (donutEl && typeof ApexCharts !== 'undefined') {
                    var satisfactionOptions = {
                        chart: {
                            type: 'donut',
                            height: 200,
                            width: '100%',
                            fontFamily: 'Outfit, sans-serif',
                        },
                        series: [
                            {{ (int) $satisfactionStats['sangat_puas'] }},
                            {{ (int) $satisfactionStats['puas'] }},
                            {{ (int) $satisfactionStats['tidak_puas'] }}
                        ],
                        labels: ['Sangat Puas', 'Puas', 'Tidak Puas'],
                        colors: ['#10b981', '#f59e0b', '#f43f5e'],
                        legend: { show: false },
                        dataLabels: { enabled: false },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '75%',
                                    labels: {
                                        show: true,
                                        total: {
                                            show: true,
                                            label: 'Total',
                                            color: '#94a3b8',
                                            fontSize: '11px',
                                            fontWeight: 600,
                                            formatter: function () {
                                                return {{ (int) $satisfactionStats['total'] }};
                                            }
                                        }
                                    }
                                }
                            }
                        },
                        tooltip: { theme: 'dark' }
                    };
                    window.myAdminSatisfactionChart = new ApexCharts(donutEl, satisfactionOptions);
                    window.myAdminSatisfactionChart.render();
                }
            }

            document.addEventListener('turbo:load', initAdminCharts);
            document.addEventListener('turbo:before-cache', destroyAdminCharts);
        </script>
    @endpush
</x-app-layout>