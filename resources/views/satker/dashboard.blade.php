<x-app-layout>
    <div class="p-3 sm:p-6 lg:p-8 space-y-6 w-full max-w-full overflow-x-hidden">
        <!-- Executive Welcome Banner (Compact di Mobile, Mewah di Desktop) -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl border border-slate-200/80 dark:border-white/10 p-3.5 sm:p-7 shadow-sm">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-brand-primary/10 dark:bg-brand-primary/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-1.5 sm:gap-5">
                <div class="min-w-0">
                    <div class="hidden sm:inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-brand-primary/10 text-brand-primary dark:bg-white/10 dark:text-white mb-2.5">
                        <span class="w-2 h-2 rounded-full bg-brand-primary dark:bg-emerald-400 animate-pulse"></span>
                        SIM BBM • {{ strtoupper(auth()->user()->satker->nama_satker ?? 'SATKER') }}
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <h1 class="text-base sm:text-3xl font-black text-slate-800 dark:text-white tracking-tight truncate">
                            Dashboard <span class="hidden sm:inline">Operasional</span> Satker
                        </h1>
                        <!-- Tanggal Ringkas di Mobile -->
                        <span class="lg:hidden text-[10px] font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-lg border border-slate-200 dark:border-white/10 shrink-0">
                            {{ now()->translatedFormat('d M Y') }}
                        </span>
                    </div>
                    <p class="mt-0.5 text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                        <span class="sm:hidden font-bold text-slate-700 dark:text-slate-200">{{ auth()->user()->satker->nama_satker ?? 'Satker' }} • </span>Pusat kendali armada & saldo BBM.<span class="hidden sm:inline"> Ketersediaan saldo BBM aktif, status bon hutang, dan riwayat transaksi pengisian secara realtime.</span>
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
                        <span>Sinkronisasi Aktif</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main 4-Card Row (Total Transaksi, Total Hutang, Diagram Saldo BBM, Diagram Tipe Roda) -->
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-2.5 sm:gap-4 lg:gap-5 items-stretch">
            <!-- Card 1: Total Transaksi BBM (Vibrant Cyan / Teal Gradient) -->
            <div class="col-span-1 relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-cyan-600 via-teal-600 to-blue-800 text-white p-3 sm:p-5 shadow-md sm:shadow-lg shadow-cyan-500/20 border border-cyan-400/30 hover:shadow-xl hover:shadow-cyan-500/30 hover:-translate-y-0.5 transition-all duration-300 group flex flex-col justify-between h-full">
                <div class="absolute -top-10 -right-10 w-28 sm:w-36 h-28 sm:h-36 bg-white/10 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between gap-1">
                        <div class="w-7 h-7 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-white/20 backdrop-blur-md border border-white/30 text-white flex items-center justify-center shrink-0 shadow-inner">
                            <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <a href="{{ route('satker.riwayat.index') }}"
                            class="relative z-20 cursor-pointer inline-flex items-center gap-0.5 sm:gap-1 text-[9px] sm:text-[11px] font-extrabold text-cyan-100 hover:text-white bg-white/15 hover:bg-white/25 border border-white/20 px-1.5 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg backdrop-blur-sm transition-all uppercase tracking-wider shadow-sm">
                            Riwayat
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    <div class="mt-2.5 sm:mt-4">
                        <span class="text-[9px] sm:text-[11px] font-bold text-cyan-100/90 uppercase tracking-wider block truncate">Total Transaksi</span>
                        <div class="mt-0.5 sm:mt-1 flex items-baseline gap-1">
                            <span class="text-xl sm:text-3xl xl:text-4xl font-black text-white tabular-nums tracking-tight">
                                {{ number_format($totalTransaksi, 0, ',', '.') }}
                            </span>
                            <span class="text-[9px] sm:text-xs font-extrabold text-cyan-200 uppercase tracking-wider">Log</span>
                        </div>
                        <div class="mt-1.5 sm:mt-2.5 bg-black/20 backdrop-blur-md px-1.5 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg border border-white/15 inline-flex items-center gap-1 max-w-full truncate">
                            <span class="text-[8px] sm:text-[10px] font-medium text-cyan-100/90 hidden xs:inline">Terisi:</span>
                            <span class="text-[9px] sm:text-xs font-black text-white tabular-nums truncate">{{ number_format($totalLiterTransaksi, 1, ',', '.') }} L</span>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 mt-2.5 sm:mt-4 pt-2 sm:pt-3 border-t border-white/20 flex items-center justify-between text-[9px] sm:text-[11px] text-cyan-100/90">
                    <span class="font-medium hidden sm:inline">Status</span>
                    <span class="inline-flex items-center gap-1 font-bold text-cyan-200 bg-white/15 px-1.5 py-0.5 sm:px-2 rounded-full border border-white/20 text-[8px] sm:text-[10px] shadow-sm ml-auto sm:ml-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-300 animate-pulse"></span> Realtime
                    </span>
                </div>
            </div>

            <!-- Card 2: Total Hutang (Bon BBM) (Vibrant Ruby / Crimson Gradient) -->
            <div class="col-span-1 relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-rose-600 via-rose-700 to-red-900 text-white p-3 sm:p-5 shadow-md sm:shadow-lg shadow-rose-500/20 border border-rose-400/30 hover:shadow-xl hover:shadow-rose-500/30 hover:-translate-y-0.5 transition-all duration-300 group flex flex-col justify-between h-full">
                <div class="absolute -top-10 -right-10 w-28 sm:w-36 h-28 sm:h-36 bg-amber-400/20 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between gap-1">
                        <div class="w-7 h-7 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-white/20 backdrop-blur-md border border-white/30 text-white flex items-center justify-center shrink-0 shadow-inner">
                            <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <a href="{{ route('satker.hutang.index') }}"
                            class="relative z-20 cursor-pointer inline-flex items-center gap-0.5 sm:gap-1 text-[9px] sm:text-[11px] font-extrabold text-rose-100 hover:text-white bg-white/15 hover:bg-white/25 border border-white/20 px-1.5 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg backdrop-blur-sm transition-all uppercase tracking-wider shadow-sm">
                            Kelola
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    <div class="mt-2.5 sm:mt-4">
                        <span class="text-[9px] sm:text-[11px] font-bold text-rose-100/90 uppercase tracking-wider block truncate">Total Hutang</span>
                        <div class="mt-0.5 sm:mt-1 flex items-baseline gap-1">
                            <span class="text-xl sm:text-3xl xl:text-4xl font-black text-white tabular-nums tracking-tight">
                                {{ rtrim(rtrim(number_format($totalHutang, 2, ',', '.'), '0'), ',') }}
                            </span>
                            <span class="text-[9px] sm:text-xs font-extrabold text-rose-200 uppercase tracking-wider">Liter</span>
                        </div>
                        <div class="mt-1.5 sm:mt-2.5 bg-black/20 backdrop-blur-md px-1.5 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg border border-white/15 inline-flex items-center gap-1 text-[8px] sm:text-[10px] max-w-full truncate">
                            <span class="font-bold text-rose-100 truncate">P: <strong class="text-white">{{ rtrim(rtrim(number_format($hutangPerBbm['Pertamax'] ?? 0, 0, ',', '.'), '0'), ',') }}L</strong></span>
                            <span class="text-white/40">•</span>
                            <span class="font-bold text-rose-100 truncate">D: <strong class="text-white">{{ rtrim(rtrim(number_format($hutangPerBbm['Pertamina Dex'] ?? 0, 0, ',', '.'), '0'), ',') }}L</strong></span>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 mt-2.5 sm:mt-4 pt-2 sm:pt-3 border-t border-white/20 flex items-center justify-between text-[9px] sm:text-[11px] text-rose-100/90">
                    <span class="font-medium hidden sm:inline">Status</span>
                    @if($totalHutang > 0)
                        <span class="inline-flex items-center gap-1 font-bold text-amber-200 bg-amber-500/20 px-1.5 py-0.5 sm:px-2 rounded-full border border-amber-400/30 text-[8px] sm:text-[10px] shadow-sm ml-auto sm:ml-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span> Ada Bon
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 font-bold text-emerald-200 bg-emerald-500/20 px-1.5 py-0.5 sm:px-2 rounded-full border border-emerald-400/30 text-[8px] sm:text-[10px] shadow-sm ml-auto sm:ml-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Nihil
                        </span>
                    @endif
                </div>
            </div>

            <!-- Card 3: Diagram Lingkar Saldo BBM (Vibrant Dark/Card) -->
            <div class="col-span-2 sm:col-span-1 xl:col-span-1 w-full min-w-0 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 rounded-xl sm:rounded-2xl shadow-sm p-3.5 sm:p-5 flex flex-col justify-between h-full overflow-hidden">
                <div>
                    <div class="flex items-center justify-between gap-1 mb-2">
                        <div class="flex items-center gap-1.5 min-w-0">
                            <div class="w-2 h-4 bg-blue-500 rounded-full shrink-0"></div>
                            <h3 class="text-xs sm:text-sm font-black text-slate-800 dark:text-white truncate">Saldo BBM</h3>
                        </div>
                        <a href="{{ route('satker.kendaraans.index') }}" class="text-[10px] sm:text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:underline shrink-0">
                            Rincian &rarr;
                        </a>
                    </div>
                    <div class="w-full min-w-0 overflow-hidden flex items-center justify-center py-1">
                        <div id="fuelDonutChart" class="w-full max-w-[260px] min-h-[160px]"></div>
                    </div>
                </div>

                <div class="mt-2 pt-2.5 border-t border-slate-100 dark:border-white/5 grid grid-cols-2 gap-1.5 text-xs">
                    <div class="p-1.5 rounded-lg bg-blue-500/5 dark:bg-blue-500/10 border border-blue-500/15 flex items-center justify-between">
                        <span class="inline-flex items-center gap-1 text-[9px] sm:text-[10px] font-bold text-slate-600 dark:text-slate-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> P
                        </span>
                        <span class="font-mono font-black text-blue-600 dark:text-blue-400 text-[10px] sm:text-[11px]">
                            {{ rtrim(rtrim(number_format($saldoKendaraanPerBbm['Pertamax'] ?? 0, 1, ',', '.'), '0'), ',') }} L
                        </span>
                    </div>
                    <div class="p-1.5 rounded-lg bg-emerald-500/5 dark:bg-emerald-500/10 border border-emerald-500/15 flex items-center justify-between">
                        <span class="inline-flex items-center gap-1 text-[9px] sm:text-[10px] font-bold text-slate-600 dark:text-slate-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> D
                        </span>
                        <span class="font-mono font-black text-emerald-600 dark:text-emerald-400 text-[10px] sm:text-[11px]">
                            {{ rtrim(rtrim(number_format($saldoKendaraanPerBbm['Pertamina Dex'] ?? 0, 1, ',', '.'), '0'), ',') }} L
                        </span>
                    </div>
                </div>
            </div>

            <!-- Card 4: Diagram Lingkar Tipe Roda (Vibrant Dark/Card) -->
            <div class="col-span-2 sm:col-span-1 xl:col-span-1 w-full min-w-0 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 rounded-xl sm:rounded-2xl shadow-sm p-3.5 sm:p-5 flex flex-col justify-between h-full overflow-hidden">
                <div>
                    <div class="flex items-center justify-between gap-1 mb-2">
                        <div class="flex items-center gap-1.5 min-w-0">
                            <div class="w-2 h-4 bg-purple-500 rounded-full shrink-0"></div>
                            <h3 class="text-xs sm:text-sm font-black text-slate-800 dark:text-white truncate">Tipe Roda</h3>
                        </div>
                        <a href="{{ route('satker.kendaraans.index') }}" class="text-[10px] sm:text-[11px] font-bold text-purple-600 dark:text-purple-400 hover:underline shrink-0">
                            Kelola &rarr;
                        </a>
                    </div>
                    <div class="w-full min-w-0 overflow-hidden flex items-center justify-center py-1">
                        <div id="satkerFleetDonutChart" class="w-full max-w-[260px] min-h-[160px]"></div>
                    </div>
                </div>

                <div class="mt-2 pt-2.5 border-t border-slate-100 dark:border-white/5 grid grid-cols-4 gap-1 text-center text-xs">
                    <div class="p-1 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-white/5">
                        <span class="text-slate-400 font-bold block text-[8px] uppercase">R2</span>
                        <span class="font-black text-emerald-600 dark:text-emerald-400 text-[10px]">{{ $rodaR2 }}</span>
                    </div>
                    <div class="p-1 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-white/5">
                        <span class="text-slate-400 font-bold block text-[8px] uppercase">R4</span>
                        <span class="font-black text-blue-600 dark:text-blue-400 text-[10px]">{{ $rodaR4 }}</span>
                    </div>
                    <div class="p-1 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-white/5">
                        <span class="text-slate-400 font-bold block text-[8px] uppercase">R6</span>
                        <span class="font-black text-amber-600 dark:text-amber-400 text-[10px]">{{ $rodaR6 }}</span>
                    </div>
                    <div class="p-1 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-white/5">
                        <span class="text-slate-400 font-bold block text-[8px] uppercase">NON</span>
                        <span class="font-black text-purple-600 dark:text-purple-400 text-[10px]">{{ $rodaNon }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- BAR CHART & RECENT ACTIVITY SECTION -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5 w-full min-w-0">
            <!-- Diagram Batang (2 cols on xl) -->
            <div class="xl:col-span-2 w-full min-w-0 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 rounded-2xl shadow-sm p-5 sm:p-6 flex flex-col justify-between overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-2.5 h-6 bg-gradient-to-b from-blue-500 to-emerald-500 rounded-full"></div>
                        <div>
                            <h3 class="text-sm sm:text-base font-black text-slate-800 dark:text-white"><span class="hidden sm:inline">Diagram Batang: </span>Konsumsi Harian BBM Armada Satker</h3>
                            <p class="text-xs text-slate-400">Volume pengisian bahan bakar minyak harian armada (7 hari terakhir)</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 self-start sm:self-auto flex-wrap">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span> Pertamax
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Pertamina Dex
                        </span>
                    </div>
                </div>
                <div class="w-full min-w-0 overflow-hidden">
                    <div id="barConsumptionChart" class="w-full min-h-[300px]"></div>
                </div>
            </div>

            <!-- Aktivitas Transaksi Pengisian Terkini (1 col on xl) -->
            <div class="xl:col-span-1 w-full min-w-0 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-brand-primary/10 text-brand-primary flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-black text-slate-800 dark:text-white">Aktivitas Transaksi Pengisian</h3>
                        </div>
                        <a href="{{ route('satker.riwayat.index') }}" class="text-xs font-bold text-brand-primary hover:underline">Semua</a>
                    </div>
                    <div class="divide-y divide-slate-100 dark:divide-white/5 overflow-y-auto max-h-[300px]">
                        @forelse($recentTransactions as $trx)
                            <div class="p-3.5 hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-colors flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-mono font-black text-slate-800 dark:text-slate-100 truncate">
                                            {{ $trx->kendaraan?->no_polisi ?? '-' }}
                                        </p>
                                        <p class="text-[10px] text-slate-400 truncate">
                                            {{ $trx->created_at->diffForHumans() }} • {{ $trx->kendaraan?->jenis_kendaraan ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="font-mono font-black text-xs sm:text-sm text-emerald-600 dark:text-emerald-400 tabular-nums">
                                        {{ number_format($trx->liter, 0, ',', '.') }} L
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-xs text-slate-400">Belum ada aktivitas transaksi pengisian.</div>
                        @endforelse
                    </div>
                </div>
                <div class="p-3 bg-slate-50 dark:bg-slate-800/40 border-t border-slate-100 dark:border-white/5 text-center">
                    <a href="{{ route('satker.riwayat.index') }}" class="text-xs font-extrabold text-brand-primary hover:underline inline-flex items-center gap-1">
                        Buka Riwayat Lengkap
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Script (Diagram Batang & Diagram Lingkar) -->
    <script>
        function destroySatkerCharts() {
            if (window.mySatkerBarChart) {
                try { window.mySatkerBarChart.destroy(); } catch (e) {}
                window.mySatkerBarChart = null;
            }
            if (window.mySatkerFuelChart) {
                try { window.mySatkerFuelChart.destroy(); } catch (e) {}
                window.mySatkerFuelChart = null;
            }
            if (window.mySatkerFleetChart) {
                try { window.mySatkerFleetChart.destroy(); } catch (e) {}
                window.mySatkerFleetChart = null;
            }
            var b = document.querySelector("#barConsumptionChart");
            if (b) b.innerHTML = '';
            var fu = document.querySelector("#fuelDonutChart");
            if (fu) fu.innerHTML = '';
            var fl = document.querySelector("#satkerFleetDonutChart");
            if (fl) fl.innerHTML = '';
        }

        function initSatkerCharts() {
            destroySatkerCharts();

            // 1. DIAGRAM BATANG (BAR CHART): Konsumsi Harian BBM (Pertamax & Pertamina Dex)
            var barEl = document.querySelector("#barConsumptionChart");
            if (barEl && typeof ApexCharts !== 'undefined') {
                var barOptions = {
                    chart: {
                        type: 'bar',
                        height: 300,
                        width: '100%',
                        fontFamily: 'Outfit, sans-serif',
                        toolbar: { show: false },
                        zoom: { enabled: false }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            borderRadius: 6,
                            borderRadiusApplication: 'end',
                            dataLabels: {
                                position: 'top',
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function (val) {
                            return val > 0 ? val + 'L' : '';
                        },
                        offsetY: -20,
                        style: {
                            fontSize: '10px',
                            fontFamily: 'Outfit, sans-serif',
                            fontWeight: 'bold',
                            colors: ['#3b82f6', '#10b981']
                        }
                    },
                    series: [
                        {
                            name: 'Pertamax',
                            data: {!! json_encode(array_column($chartData, 'pertamax')) !!}
                        },
                        {
                            name: 'Pertamina Dex',
                            data: {!! json_encode(array_column($chartData, 'dex')) !!}
                        }
                    ],
                    colors: ['#3b82f6', '#10b981'],
                    legend: {
                        show: true,
                        position: 'top',
                        horizontalAlign: 'right',
                        fontFamily: 'Outfit, sans-serif',
                        fontSize: '11px',
                        fontWeight: 600,
                        labels: {
                            colors: '#94a3b8'
                        },
                        markers: {
                            width: 9,
                            height: 9,
                            radius: 12
                        }
                    },
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
                            style: { colors: '#94a3b8', fontSize: '11px' },
                            formatter: function (val) {
                                return val + ' L';
                            }
                        }
                    },
                    grid: {
                        borderColor: '#33415520',
                        strokeDashArray: 4
                    },
                    tooltip: {
                        theme: 'dark',
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function (val) {
                                return val + ' Liter';
                            }
                        }
                    }
                };
                window.mySatkerBarChart = new ApexCharts(barEl, barOptions);
                window.mySatkerBarChart.render();
            }

            // 2. DIAGRAM LINGKAR (DONUT CHART): Proporsi Saldo BBM
            var fuelEl = document.querySelector("#fuelDonutChart");
            if (fuelEl && typeof ApexCharts !== 'undefined') {
                var isDark = document.documentElement.classList.contains('dark');
                var fuelOptions = {
                    chart: {
                        type: 'donut',
                        height: 165,
                        width: '100%',
                        fontFamily: 'Outfit, sans-serif',
                    },
                    series: [
                        {{ (float) ($saldoKendaraanPerBbm['Pertamax'] ?? 0) }},
                        {{ (float) ($saldoKendaraanPerBbm['Pertamina Dex'] ?? 0) }}
                    ],
                    labels: ['Pertamax', 'Pertamina Dex'],
                    colors: ['#3b82f6', '#10b981'],
                    legend: {
                        show: false
                    },
                    dataLabels: { enabled: false },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '72%',
                                labels: {
                                    show: true,
                                    name: {
                                        show: true,
                                        fontSize: '10px',
                                        fontFamily: 'Outfit, sans-serif',
                                        fontWeight: 600,
                                        color: '#94a3b8',
                                        offsetY: -3
                                    },
                                    value: {
                                        show: true,
                                        fontSize: '15px',
                                        fontFamily: 'Outfit, sans-serif',
                                        fontWeight: 900,
                                        color: isDark ? '#f8fafc' : '#0f172a',
                                        offsetY: 2,
                                        formatter: function(val) {
                                            return val + ' L';
                                        }
                                    },
                                    total: {
                                        show: true,
                                        label: 'Total Saldo',
                                        color: '#94a3b8',
                                        fontSize: '10px',
                                        fontWeight: 600,
                                        formatter: function () {
                                            return "{{ rtrim(rtrim(number_format($totalSaldoKendaraan, 1, ',', '.'), '0'), ',') }} L";
                                        }
                                    }
                                }
                            }
                        }
                    },
                    tooltip: {
                        theme: 'dark',
                        y: {
                            formatter: function (val) {
                                return val + ' Liter';
                            }
                        }
                    }
                };
                window.mySatkerFuelChart = new ApexCharts(fuelEl, fuelOptions);
                window.mySatkerFuelChart.render();
            }

            // 3. DIAGRAM LINGKAR (DONUT CHART): Tipe Roda Kendaraan Satker (Armada)
            var satkerFleetEl = document.querySelector("#satkerFleetDonutChart");
            if (satkerFleetEl && typeof ApexCharts !== 'undefined') {
                var isDark = document.documentElement.classList.contains('dark');
                var r2 = {{ (int) $rodaR2 }};
                var r4 = {{ (int) $rodaR4 }};
                var r6 = {{ (int) $rodaR6 }};
                var rNon = {{ (int) $rodaNon }};
                var totalRoda = r2 + r4 + r6 + rNon;
                var hasArmada = totalRoda > 0;
                var satkerFleetOptions = {
                    chart: {
                        type: 'donut',
                        height: 165,
                        width: '100%',
                        fontFamily: 'Outfit, sans-serif',
                    },
                    series: hasArmada ? [r2, r4, r6, rNon] : [1],
                    labels: hasArmada ? ['Roda 2 (R2)', 'Roda 4 (R4)', 'Roda 6 (R6)', 'Non Kendaraan'] : ['Belum Ada Armada'],
                    colors: hasArmada ? ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6'] : [isDark ? '#334155' : '#cbd5e1'],
                    legend: {
                        show: false
                    },
                    dataLabels: { enabled: false },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '72%',
                                labels: {
                                    show: true,
                                    name: {
                                        show: true,
                                        fontSize: '10px',
                                        fontFamily: 'Outfit, sans-serif',
                                        fontWeight: 600,
                                        color: '#94a3b8',
                                        offsetY: -3
                                    },
                                    value: {
                                        show: true,
                                        fontSize: '15px',
                                        fontFamily: 'Outfit, sans-serif',
                                        fontWeight: 900,
                                        color: isDark ? '#f8fafc' : '#0f172a',
                                        offsetY: 2,
                                        formatter: function(val) {
                                            return val + ' Unit';
                                        }
                                    },
                                    total: {
                                        show: true,
                                        label: 'Total Armada',
                                        color: '#94a3b8',
                                        fontSize: '10px',
                                        fontWeight: 600,
                                        formatter: function () {
                                            return "{{ $totalKendaraan }} Unit";
                                        }
                                    }
                                }
                            }
                        }
                    },
                    tooltip: {
                        theme: 'dark',
                        y: {
                            formatter: function (val) {
                                return hasArmada ? val + ' Unit' : '0 Unit';
                            }
                        }
                    }
                };
                window.mySatkerFleetChart = new ApexCharts(satkerFleetEl, satkerFleetOptions);
                window.mySatkerFleetChart.render();
            }
        }

        document.addEventListener('DOMContentLoaded', initSatkerCharts);
        document.addEventListener('turbo:load', initSatkerCharts);
        document.addEventListener('turbo:before-cache', destroySatkerCharts);
        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            setTimeout(initSatkerCharts, 50);
        }

        if (!window.satkerThemeObserver) {
            window.satkerThemeObserver = new MutationObserver(function(mutations) {
                mutations.forEach(function(m) {
                    if (m.attributeName === 'class') {
                        setTimeout(initSatkerCharts, 100);
                    }
                });
            });
            window.satkerThemeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
        }
    </script>
</x-app-layout>