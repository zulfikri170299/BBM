<x-app-layout>
    <div class="p-3 sm:p-6 lg:p-8 space-y-6 w-full max-w-full overflow-x-hidden">
        <!-- Executive Welcome Banner (Satker) -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-white/10 p-5 sm:p-7 shadow-sm">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-brand-primary/10 dark:bg-brand-primary/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-brand-primary/10 text-brand-primary dark:bg-white/10 dark:text-white mb-2.5">
                        <span class="w-2 h-2 rounded-full bg-brand-primary dark:bg-emerald-400 animate-pulse"></span>
                        SIM BBM • {{ strtoupper(auth()->user()->satker->nama_satker ?? 'SATKER') }}
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white tracking-tight">
                        Dashboard Operasional Satker
                    </h1>
                    <p class="mt-1 text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-2xl">
                        Pusat kendali armada kendaraan, ketersediaan saldo BBM aktif, status bon hutang, dan riwayat transaksi pengisian secara realtime.
                    </p>
                </div>

                <!-- Date & Status Chips -->
                <div class="flex flex-wrap items-center gap-2.5 self-start lg:self-auto">
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

        <!-- Main Stats Grid (Executive Modern Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5">
            <!-- Card 1: Total Kendaraan -->
            <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 p-5 shadow-sm hover:shadow-xl hover:border-amber-500/40 transition-all duration-300 group flex flex-col justify-between">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-500/10 dark:bg-amber-500/15 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1" />
                            </svg>
                        </div>
                        <a href="{{ route('satker.kendaraans.index') }}"
                            class="relative z-20 cursor-pointer inline-flex items-center gap-1 text-[11px] font-bold text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 bg-amber-500/10 hover:bg-amber-500/20 px-2.5 py-1 rounded-lg transition-colors uppercase tracking-wider">
                            Kelola
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    <div class="mt-4">
                        <span class="text-[11px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">Total Kendaraan Satker</span>
                        <div class="mt-1 flex items-baseline gap-2">
                            <span class="text-3xl sm:text-4xl font-black text-slate-800 dark:text-white tabular-nums">
                                {{ $totalKendaraan }}
                            </span>
                            <span class="text-xs font-bold text-slate-400">Unit</span>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 mt-5 pt-3.5 border-t border-slate-100 dark:border-white/5 grid grid-cols-4 gap-1 text-center">
                    <div class="bg-slate-50 dark:bg-slate-800/60 rounded-lg py-1 px-1 border border-slate-100 dark:border-white/5">
                        <span class="block text-[9px] font-bold text-slate-400">R2</span>
                        <span class="text-xs font-black text-slate-700 dark:text-slate-300">{{ $rodaR2 }}</span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/60 rounded-lg py-1 px-1 border border-slate-100 dark:border-white/5">
                        <span class="block text-[9px] font-bold text-slate-400">R4</span>
                        <span class="text-xs font-black text-slate-700 dark:text-slate-300">{{ $rodaR4 }}</span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/60 rounded-lg py-1 px-1 border border-slate-100 dark:border-white/5">
                        <span class="block text-[9px] font-bold text-slate-400">R6</span>
                        <span class="text-xs font-black text-slate-700 dark:text-slate-300">{{ $rodaR6 }}</span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/60 rounded-lg py-1 px-1 border border-slate-100 dark:border-white/5">
                        <span class="block text-[9px] font-bold text-slate-400">NON</span>
                        <span class="text-xs font-black text-slate-700 dark:text-slate-300">{{ $rodaNon }}</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Total Transaksi BBM -->
            <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 p-5 shadow-sm hover:shadow-xl hover:border-rose-500/40 transition-all duration-300 group flex flex-col justify-between">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-rose-500/10 dark:bg-rose-500/15 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <a href="{{ route('satker.riwayat.index') }}"
                            class="relative z-20 cursor-pointer inline-flex items-center gap-1 text-[11px] font-bold text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 bg-rose-500/10 hover:bg-rose-500/20 px-2.5 py-1 rounded-lg transition-colors uppercase tracking-wider">
                            Riwayat
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    <div class="mt-4">
                        <span class="text-[11px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">Total Transaksi BBM</span>
                        <div class="mt-1 flex items-baseline gap-2">
                            <span class="text-3xl sm:text-4xl font-black text-slate-800 dark:text-white tabular-nums">
                                {{ number_format($totalTransaksi, 0, ',', '.') }}
                            </span>
                            <span class="text-xs font-bold text-slate-400">Pengisian</span>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 mt-5 pt-3.5 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs">
                    <span class="text-slate-500 dark:text-slate-400">Pencatatan</span>
                    <span class="inline-flex items-center gap-1.5 font-bold text-emerald-600 dark:text-emerald-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Realtime & Tervalidasi
                    </span>
                </div>
            </div>

            <!-- Card 3: Saldo Kendaraan -->
            <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 p-5 shadow-sm hover:shadow-xl hover:border-blue-500/40 transition-all duration-300 group flex flex-col justify-between">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-500/10 dark:bg-blue-500/15 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                        <a href="{{ route('satker.kendaraans.index') }}"
                            class="relative z-20 cursor-pointer inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 bg-blue-500/10 hover:bg-blue-500/20 px-2.5 py-1 rounded-lg transition-colors uppercase tracking-wider">
                            Rincian
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    <div class="mt-4">
                        <span class="text-[11px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">Total Saldo Armada</span>
                        <div class="mt-1 flex items-baseline gap-2">
                            <span class="text-3xl sm:text-4xl font-black text-slate-800 dark:text-white tabular-nums">
                                {{ rtrim(rtrim(number_format($totalSaldoKendaraan, 2, ',', '.'), '0'), ',') }}
                            </span>
                            <span class="text-xs font-bold text-slate-400">Liter</span>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 mt-5 pt-3.5 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs">
                    <span class="text-slate-500 dark:text-slate-400 font-medium">Pertamax: <strong class="text-blue-600 dark:text-blue-400">{{ rtrim(rtrim(number_format($saldoKendaraanPerBbm['Pertamax'] ?? 0, 2, ',', '.'), '0'), ',') }} L</strong></span>
                    <span class="text-slate-500 dark:text-slate-400 font-medium">Dex: <strong class="text-emerald-600 dark:text-emerald-400">{{ rtrim(rtrim(number_format($saldoKendaraanPerBbm['Pertamina Dex'] ?? 0, 2, ',', '.'), '0'), ',') }} L</strong></span>
                </div>
            </div>

            <!-- Card 4: Total Hutang (Bon) -->
            <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 p-5 shadow-sm hover:shadow-xl hover:border-slate-400/40 transition-all duration-300 group flex flex-col justify-between">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-slate-500/10 dark:bg-slate-500/15 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-slate-500/10 text-slate-600 dark:text-slate-400 border border-slate-500/20 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <a href="{{ route('satker.hutang.index') }}"
                            class="relative z-20 cursor-pointer inline-flex items-center gap-1 text-[11px] font-bold text-slate-600 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 bg-slate-500/10 hover:bg-slate-500/20 px-2.5 py-1 rounded-lg transition-colors uppercase tracking-wider">
                            Kelola
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    <div class="mt-4">
                        <span class="text-[11px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">Total Hutang (Bon BBM)</span>
                        <div class="mt-1 flex items-baseline gap-2">
                            <span class="text-3xl sm:text-4xl font-black {{ $totalHutang > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-800 dark:text-white' }} tabular-nums">
                                {{ rtrim(rtrim(number_format($totalHutang, 2, ',', '.'), '0'), ',') }}
                            </span>
                            <span class="text-xs font-bold text-slate-400">Liter</span>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-3.5 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs">
                    <span class="text-slate-500 dark:text-slate-400">Status Bon</span>
                    @if($totalHutang > 0)
                        <span class="inline-flex items-center gap-1 font-bold text-rose-600 dark:text-rose-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span> Ada Tunggakan Bon
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 font-bold text-emerald-600 dark:text-emerald-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Nihil (Tidak Ada Bon)
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- DIAGRAM SECTION: DIAGRAM BATANG & DIAGRAM LINGKAR -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5 w-full min-w-0">
            <!-- Diagram Batang (2 cols on xl) -->
            <div class="xl:col-span-2 w-full min-w-0 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 rounded-2xl shadow-sm p-5 sm:p-6 flex flex-col justify-between overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-2.5 h-6 bg-emerald-500 rounded-full"></div>
                        <div>
                            <h3 class="text-base font-black text-slate-800 dark:text-white">Diagram Batang: Konsumsi Harian BBM Armada Satker</h3>
                            <p class="text-xs text-slate-400">Volume pengisian bahan bakar minyak harian armada (7 hari terakhir)</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 self-start sm:self-auto">
                        Batang Rounded • Liter/Hari
                    </span>
                </div>
                <div class="w-full min-w-0 overflow-hidden">
                    <div id="barConsumptionChart" class="w-full min-h-[290px]"></div>
                </div>
            </div>

            <!-- Diagram Lingkar: Proporsi Saldo BBM Armada (1 col on xl) -->
            <div class="w-full min-w-0 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 rounded-2xl shadow-sm p-5 sm:p-6 flex flex-col justify-between overflow-hidden">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-2.5 h-6 bg-blue-500 rounded-full"></div>
                            <div>
                                <h3 class="text-base font-black text-slate-800 dark:text-white">Diagram Lingkar: Saldo BBM</h3>
                                <p class="text-xs text-slate-400">Proporsi Pertamax vs Pertamina Dex</p>
                            </div>
                        </div>
                    </div>
                    <div class="w-full min-w-0 overflow-hidden flex items-center justify-center">
                        <div id="fuelDonutChart" class="w-full max-w-[340px] min-h-[220px]"></div>
                    </div>
                </div>

                <div class="mt-4 pt-3.5 border-t border-slate-100 dark:border-white/5 space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="inline-flex items-center gap-1.5 font-bold text-slate-600 dark:text-slate-300">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span> Pertamax
                        </span>
                        <span class="font-mono font-black text-blue-600 dark:text-blue-400">
                            {{ rtrim(rtrim(number_format($saldoKendaraanPerBbm['Pertamax'] ?? 0, 1, ',', '.'), '0'), ',') }} L
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="inline-flex items-center gap-1.5 font-bold text-slate-600 dark:text-slate-300">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Pertamina Dex
                        </span>
                        <span class="font-mono font-black text-emerald-600 dark:text-emerald-400">
                            {{ rtrim(rtrim(number_format($saldoKendaraanPerBbm['Pertamina Dex'] ?? 0, 1, ',', '.'), '0'), ',') }} L
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions & Fleet Distribution Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5 w-full min-w-0">
            <!-- Left: Diagram Lingkar Tipe Roda (1 col) -->
            <div class="w-full min-w-0 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 rounded-2xl shadow-sm p-5 sm:p-6 flex flex-col justify-between overflow-hidden">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-2.5 h-6 bg-purple-500 rounded-full"></div>
                            <div>
                                <h3 class="text-base font-black text-slate-800 dark:text-white">Diagram Lingkar: Tipe Roda</h3>
                                <p class="text-xs text-slate-400">Komposisi armada kendaraan</p>
                            </div>
                        </div>
                    </div>
                    <div class="w-full min-w-0 overflow-hidden flex items-center justify-center">
                        <div id="satkerFleetDonutChart" class="w-full max-w-[340px] min-h-[210px]"></div>
                    </div>
                </div>
                <div class="mt-4 pt-3.5 border-t border-slate-100 dark:border-white/5 grid grid-cols-2 gap-2 text-center text-xs">
                    <div class="p-2 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-white/5">
                        <span class="text-slate-400 font-bold block text-[10px]">Roda 2 (R2)</span>
                        <span class="font-black text-emerald-600 dark:text-emerald-400">{{ $rodaR2 }} Unit</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-white/5">
                        <span class="text-slate-400 font-bold block text-[10px]">Roda 4 (R4)</span>
                        <span class="font-black text-blue-600 dark:text-blue-400">{{ $rodaR4 }} Unit</span>
                    </div>
                </div>
            </div>

            <!-- Right: Recent Transactions (2 cols on xl) -->
            <div class="xl:col-span-2 w-full min-w-0 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 rounded-2xl shadow-sm overflow-hidden flex flex-col">
                <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-brand-primary/10 text-brand-primary flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-black text-slate-800 dark:text-white">Aktivitas Transaksi Pengisian Terkini</h3>
                    </div>
                    <a href="{{ route('satker.riwayat.index') }}" class="text-xs font-bold text-brand-primary hover:underline">Lihat Semua Riwayat</a>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-white/5 overflow-y-auto max-h-[320px]">
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
                                        {{ $trx->kendaraan?->no_polisi ?? '-' }}
                                    </p>
                                    <p class="text-[10px] text-slate-400">
                                        {{ $trx->created_at->diffForHumans() }} • {{ $trx->kendaraan?->jenis_kendaraan ?? '-' }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-mono font-black text-sm text-emerald-600 dark:text-emerald-400 tabular-nums">
                                    {{ number_format($trx->liter, 0, ',', '.') }} L
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-xs text-slate-400">Belum ada aktivitas transaksi pengisian.</div>
                    @endforelse
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

            // 1. DIAGRAM BATANG (BAR CHART): Konsumsi Harian BBM
            var barEl = document.querySelector("#barConsumptionChart");
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
                            columnWidth: '40%',
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
                            return val > 0 ? val + 'L' : '';
                        },
                        offsetY: -20,
                        style: {
                            fontSize: '10px',
                            fontFamily: 'Outfit, sans-serif',
                            fontWeight: 'bold',
                            colors: ["#10b981"]
                        }
                    },
                    series: [{
                        name: 'Liter Konsumsi',
                        data: {!! json_encode(array_column($chartData, 'liter')) !!}
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
                            style: { colors: '#94a3b8', fontSize: '11px' },
                            formatter: function (val) {
                                return val + ' L';
                            }
                        }
                    },
                    colors: ['#10b981'],
                    grid: {
                        borderColor: '#33415520',
                        strokeDashArray: 4
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
                window.mySatkerBarChart = new ApexCharts(barEl, barOptions);
                window.mySatkerBarChart.render();
            }

            // 2. DIAGRAM LINGKAR (DONUT CHART): Proporsi Saldo BBM
            var fuelEl = document.querySelector("#fuelDonutChart");
            if (fuelEl && typeof ApexCharts !== 'undefined') {
                var fuelOptions = {
                    chart: {
                        type: 'donut',
                        height: 220,
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
                                        label: 'Total Saldo',
                                        color: '#94a3b8',
                                        fontSize: '11px',
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

            // 3. DIAGRAM LINGKAR (DONUT CHART): Tipe Roda Kendaraan Satker
            var satkerFleetEl = document.querySelector("#satkerFleetDonutChart");
            if (satkerFleetEl && typeof ApexCharts !== 'undefined') {
                var satkerFleetOptions = {
                    chart: {
                        type: 'donut',
                        height: 220,
                        width: '100%',
                        fontFamily: 'Outfit, sans-serif',
                    },
                    series: [
                        {{ (int) $rodaR2 }},
                        {{ (int) $rodaR4 }},
                        {{ (int) $rodaR6 }},
                        {{ (int) $rodaNon }}
                    ],
                    labels: ['R2', 'R4', 'R6', 'Non Kendaraan'],
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
                                size: '70%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Armada',
                                        color: '#94a3b8',
                                        fontSize: '11px',
                                        fontWeight: 600,
                                        formatter: function () {
                                            return "{{ $totalKendaraan }} Unit";
                                        }
                                    }
                                }
                            }
                        }
                    },
                    tooltip: { theme: 'dark' }
                };
                window.mySatkerFleetChart = new ApexCharts(satkerFleetEl, satkerFleetOptions);
                window.mySatkerFleetChart.render();
            }
        }

        document.addEventListener('turbo:load', initSatkerCharts);
        document.addEventListener('turbo:before-cache', destroySatkerCharts);
    </script>
</x-app-layout>