<x-app-layout>
    <div class="p-3 sm:p-6 lg:p-8 space-y-4 sm:space-y-6 w-full max-w-full overflow-x-hidden font-sans">

        @php
            // Perhitungan Persentase Saldo BBM
            $totalSaldoAll = ($saldoKendaraanPerBbm['Pertamax'] ?? 0) + ($saldoKendaraanPerBbm['Pertamina Dex'] ?? 0);
            $persenPertamax = $totalSaldoAll > 0 ? round((($saldoKendaraanPerBbm['Pertamax'] ?? 0) / $totalSaldoAll) * 100, 1) : 0;
            $persenDex = $totalSaldoAll > 0 ? round((($saldoKendaraanPerBbm['Pertamina Dex'] ?? 0) / $totalSaldoAll) * 100, 1) : 0;

            // Perhitungan Persentase Armada
            $totalArmadaAll = (int) $rodaR2 + (int) $rodaR4 + (int) $rodaR6 + (int) $rodaNon;
            $persenR2 = $totalArmadaAll > 0 ? round(($rodaR2 / $totalArmadaAll) * 100) : 0;
            $persenR4 = $totalArmadaAll > 0 ? round(($rodaR4 / $totalArmadaAll) * 100) : 0;
            $persenR6 = $totalArmadaAll > 0 ? round(($rodaR6 / $totalArmadaAll) * 100) : 0;
            $persenNon = $totalArmadaAll > 0 ? round(($rodaNon / $totalArmadaAll) * 100) : 0;

            // Konsumsi Total 7 Hari & Quick Stats
            $totalPertamax7Hari = 0;
            $totalDex7Hari = 0;
            $peakDay = '-';
            $peakVal = 0;
            if (!empty($chartData)) {
                $totalPertamax7Hari = array_sum(array_column($chartData, 'pertamax'));
                $totalDex7Hari = array_sum(array_column($chartData, 'dex'));
                $totalVolume7Hari = $totalPertamax7Hari + $totalDex7Hari;
                foreach ($chartData as $cd) {
                    $dayTotal = ($cd['pertamax'] ?? 0) + ($cd['dex'] ?? 0);
                    if ($dayTotal > $peakVal) {
                        $peakVal = $dayTotal;
                        $peakDay = $cd['date'];
                    }
                }
            } else {
                $totalVolume7Hari = 0;
            }
            $avgVolumeHarian = round($totalVolume7Hari / 7, 1);
            $persenPertamax7Hari = $totalVolume7Hari > 0 ? round(($totalPertamax7Hari / $totalVolume7Hari) * 100, 1) : 0;
            $persenDex7Hari = $totalVolume7Hari > 0 ? round(($totalDex7Hari / $totalVolume7Hari) * 100, 1) : 0;
        @endphp

        {{-- ═══════════════════════════════════════════════════════════════════
             SECTION 1: COMPACT SLIM HEADER (MODERN & MINIMALIS)
        ═══════════════════════════════════════════════════════════════════ --}}
        <div class="relative overflow-hidden bg-white/80 dark:bg-slate-900/80 rounded-2xl border border-slate-200/70 dark:border-white/[0.08] px-4 py-3 sm:px-6 sm:py-3.5 shadow-sm dark:shadow-xl dark:shadow-black/20 backdrop-blur-xl">
            {{-- Glowing Accent Top Border --}}
            <div class="absolute inset-x-0 top-0 h-[2px] bg-gradient-to-r from-transparent via-cyan-500 to-transparent opacity-80"></div>
            <div class="absolute -top-16 -right-16 w-44 h-44 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-cyan-500/20 via-blue-500/20 to-indigo-500/20 border border-cyan-500/30 text-cyan-500 dark:text-cyan-400 flex items-center justify-center shrink-0 shadow-sm shadow-cyan-500/10">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <h1 class="text-sm sm:text-base font-black text-slate-800 dark:text-white tracking-tight truncate">
                                Dashboard Operasional Satker
                            </h1>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Realtime
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">
                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ strtoupper(auth()->user()->satker->nama_satker ?? 'SATKER') }}</span> • Pusat pemantauan armada, sisa saldo BBM & riwayat log
                        </p>
                    </div>
                </div>

                {{-- Tanggal & Status --}}
                <div class="flex items-center gap-2 shrink-0">
                    <div class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100/80 dark:bg-white/[0.04] border border-slate-200/60 dark:border-white/[0.06] text-xs font-semibold text-slate-600 dark:text-slate-300">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                    </div>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border border-cyan-500/20 text-xs font-bold">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
                        </span>
                        <span>Aktif</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════
             SECTION 2: 4-CARD BENTO GRID (2 KOLOM DI MOBILE, 4 DI DESKTOP)
        ═══════════════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-2 sm:gap-4 lg:gap-5 items-stretch">

            {{-- ── CARD 1: TOTAL TRANSAKSI BBM (Vibrant Cyan Theme) ── --}}
            <div class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-cyan-50/90 via-white to-teal-50/60 dark:from-cyan-950/70 dark:via-slate-900/95 dark:to-slate-950 border border-cyan-200/80 dark:border-cyan-500/30 p-2.5 sm:p-5 shadow-sm shadow-cyan-900/5 dark:shadow-lg dark:shadow-cyan-950/30 hover:border-cyan-400/80 hover:-translate-y-1 transition-all duration-300 group flex flex-col justify-between">
                {{-- Ambient Multi-layer Glow --}}
                <div class="absolute -top-14 -right-14 w-40 h-40 bg-cyan-400/15 dark:bg-cyan-500/20 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
                <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-blue-500/10 dark:bg-blue-600/15 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute inset-x-0 top-0 h-[2.5px] bg-gradient-to-r from-cyan-500 via-teal-400 to-transparent"></div>

                <div class="space-y-2 sm:space-y-3.5">
                    {{-- Header Kartu --}}
                    <div class="flex items-center justify-between gap-1">
                        <div class="flex items-center gap-1.5 sm:gap-2.5 min-w-0">
                            <div class="w-6 h-6 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-cyan-100 border border-cyan-300/80 text-cyan-700 dark:bg-cyan-500/20 dark:border-cyan-400/40 dark:text-cyan-300 flex items-center justify-center shrink-0 shadow-sm shadow-cyan-500/10 group-hover:scale-105 transition-transform">
                                <svg class="w-3 h-3 sm:w-4.5 sm:h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <span class="hidden sm:block text-[9px] sm:text-[10px] font-black uppercase tracking-wider text-cyan-600 dark:text-cyan-400/90 truncate">Aktivitas</span>
                                <h3 class="text-[11px] sm:text-xs font-black text-slate-800 dark:text-white truncate">Transaksi</h3>
                            </div>
                        </div>
                        <a href="{{ route('satker.riwayat.index') }}"
                            class="inline-flex items-center gap-0.5 sm:gap-1 text-[8px] sm:text-[10px] font-extrabold text-cyan-700 hover:text-cyan-800 dark:text-cyan-300 dark:hover:text-white bg-cyan-100/70 hover:bg-cyan-200/70 dark:bg-cyan-500/15 dark:hover:bg-cyan-500/30 border border-cyan-300/80 dark:border-cyan-400/30 px-1.5 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg transition-all shadow-sm shrink-0">
                            Riwayat
                            <svg class="w-2 h-2 sm:w-2.5 sm:h-2.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    {{-- Main Metric Number --}}
                    <div>
                        <div class="flex items-baseline gap-1.5 sm:gap-2">
                            <span class="text-xl sm:text-3xl xl:text-4xl font-black text-slate-900 dark:text-white tabular-nums tracking-tight font-outfit drop-shadow-sm">
                                {{ number_format($totalTransaksi, 0, ',', '.') }}
                            </span>
                            <span class="text-[8px] sm:text-[10px] font-black text-cyan-800 dark:text-cyan-300 uppercase tracking-wider bg-cyan-100 dark:bg-cyan-500/20 px-1 py-0.5 sm:px-1.5 rounded sm:rounded-md border border-cyan-300 dark:border-cyan-400/30 shrink-0">
                                <span class="sm:hidden">Log</span><span class="hidden sm:inline">Log Trx</span>
                            </span>
                        </div>
                    </div>

                    {{-- Pengisi Ruang Tengah: 2 Box Metrik Kompak & Berwarna --}}
                    @php
                        $avgLiter = $totalTransaksi > 0 ? round($totalLiterTransaksi / $totalTransaksi, 1) : 0;
                    @endphp
                    <div class="grid grid-cols-2 gap-1 sm:gap-2 pt-0.5">
                        <div class="p-1.5 sm:p-2.5 rounded-lg sm:rounded-xl bg-cyan-50/80 dark:bg-cyan-500/10 border border-cyan-200/80 dark:border-cyan-500/20 flex flex-col justify-between">
                            <span class="text-[7.5px] sm:text-[9px] font-bold text-cyan-700 dark:text-cyan-300/80 uppercase tracking-wider flex items-center gap-0.5 sm:gap-1 truncate">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-cyan-600 dark:text-cyan-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                Terisi
                            </span>
                            <span class="text-[10px] sm:text-sm font-black text-slate-900 dark:text-white font-mono mt-0.5 tabular-nums truncate">
                                {{ number_format($totalLiterTransaksi, 0, ',', '.') }}<span class="text-[8px] sm:text-[10px] text-cyan-600 dark:text-cyan-300 font-sans font-bold"> L</span>
                            </span>
                        </div>

                        <div class="p-1.5 sm:p-2.5 rounded-lg sm:rounded-xl bg-blue-50/80 dark:bg-blue-500/10 border border-blue-200/80 dark:border-blue-500/20 flex flex-col justify-between">
                            <span class="text-[7.5px] sm:text-[9px] font-bold text-blue-700 dark:text-blue-300/80 uppercase tracking-wider flex items-center gap-0.5 sm:gap-1 truncate">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-blue-600 dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                Rerata
                            </span>
                            <span class="text-[10px] sm:text-sm font-black text-slate-900 dark:text-white font-mono mt-0.5 tabular-nums truncate">
                                ~{{ $avgLiter }}<span class="text-[8px] sm:text-[10px] text-blue-600 dark:text-blue-300 font-sans font-bold"> L</span>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Footer Info Box --}}
                <div class="mt-2 sm:mt-4 pt-1.5 sm:pt-3 border-t border-slate-200/70 dark:border-cyan-500/20 flex items-center justify-between text-[8px] sm:text-[11px]">
                    <span class="inline-flex items-center gap-1 text-slate-600 dark:text-cyan-200/90 font-medium truncate">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 animate-ping shrink-0"></span> Realtime
                    </span>
                    <span class="font-bold text-cyan-800 dark:text-cyan-300 bg-cyan-100 dark:bg-cyan-500/20 px-1.5 py-0.5 rounded border border-cyan-300 dark:border-cyan-400/30 text-[7.5px] sm:text-[10px] shrink-0">
                        Valid
                    </span>
                </div>
            </div>

            {{-- ── CARD 2: TOTAL HUTANG / BON BBM (Vibrant Crimson Rose Theme) ── --}}
            <div class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-rose-50/90 via-white to-pink-50/60 dark:from-rose-950/70 dark:via-slate-900/95 dark:to-slate-950 border border-rose-200/80 dark:border-rose-500/30 p-2.5 sm:p-5 shadow-sm shadow-rose-900/5 dark:shadow-lg dark:shadow-rose-950/30 hover:border-rose-400/80 hover:-translate-y-1 transition-all duration-300 group flex flex-col justify-between">
                {{-- Ambient Multi-layer Glow --}}
                <div class="absolute -top-14 -right-14 w-40 h-40 bg-rose-400/15 dark:bg-rose-500/20 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
                <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-amber-500/10 dark:bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute inset-x-0 top-0 h-[2.5px] bg-gradient-to-r from-rose-500 via-pink-400 to-transparent"></div>

                <div class="space-y-2 sm:space-y-3.5">
                    {{-- Header Kartu --}}
                    <div class="flex items-center justify-between gap-1">
                        <div class="flex items-center gap-1.5 sm:gap-2.5 min-w-0">
                            <div class="w-6 h-6 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-rose-100 border border-rose-300/80 text-rose-700 dark:bg-rose-500/20 dark:border-rose-400/40 dark:text-rose-300 flex items-center justify-center shrink-0 shadow-sm shadow-rose-500/10 group-hover:scale-105 transition-transform">
                                <svg class="w-3.5 h-3.5 sm:w-4.5 sm:h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <span class="hidden sm:block text-[9px] sm:text-[10px] font-black uppercase tracking-wider text-rose-600 dark:text-rose-400/90 truncate">Tanggungan</span>
                                <h3 class="text-[11px] sm:text-xs font-black text-slate-800 dark:text-white truncate">Hutang BBM</h3>
                            </div>
                        </div>
                        <a href="{{ route('satker.hutang.index') }}"
                            class="inline-flex items-center gap-0.5 sm:gap-1 text-[8px] sm:text-[10px] font-extrabold text-rose-700 hover:text-rose-800 dark:text-rose-300 dark:hover:text-white bg-rose-100/70 hover:bg-rose-200/70 dark:bg-rose-500/15 dark:hover:bg-rose-500/30 border border-rose-300/80 dark:border-rose-400/30 px-1.5 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg transition-all shadow-sm shrink-0">
                            Kelola
                            <svg class="w-2 h-2 sm:w-2.5 sm:h-2.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    {{-- Main Metric Number --}}
                    <div>
                        <div class="flex items-baseline gap-1.5 sm:gap-2">
                            <span class="text-xl sm:text-3xl xl:text-4xl font-black text-slate-900 dark:text-white tabular-nums tracking-tight font-outfit drop-shadow-sm">
                                {{ rtrim(rtrim(number_format($totalHutang, 2, ',', '.'), '0'), ',') }}
                            </span>
                            <span class="text-[8px] sm:text-[10px] font-black text-rose-800 dark:text-rose-300 uppercase tracking-wider bg-rose-100 dark:bg-rose-500/20 px-1 py-0.5 sm:px-1.5 rounded sm:rounded-md border border-rose-300 dark:border-rose-400/30 shrink-0">
                                Liter
                            </span>
                        </div>
                    </div>

                    {{-- Pengisi Ruang Tengah: 2 Box Rincian Bon BBM Kompak & Kontras --}}
                    <div class="grid grid-cols-2 gap-1 sm:gap-2 pt-0.5">
                        <div class="p-1.5 sm:p-2 rounded-lg sm:rounded-xl bg-blue-50/80 dark:bg-blue-500/10 border border-blue-200/80 dark:border-blue-400/30 flex flex-col justify-between">
                            <span class="text-[7.5px] sm:text-[9.5px] font-bold text-blue-700 dark:text-blue-300 uppercase tracking-wider flex items-center gap-1 truncate">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 shadow-sm shrink-0"></span>
                                Pertamax
                            </span>
                            <span class="text-[10.5px] sm:text-sm font-black text-slate-900 dark:text-white font-mono mt-0.5 tabular-nums truncate">
                                {{ rtrim(rtrim(number_format($hutangPerBbm['Pertamax'] ?? 0, 0, ',', '.'), '0'), ',') }}<span class="text-[8px] sm:text-[10px] text-blue-600 dark:text-blue-300 font-sans font-bold"> L</span>
                            </span>
                        </div>

                        <div class="p-1.5 sm:p-2 rounded-lg sm:rounded-xl bg-emerald-50/80 dark:bg-emerald-500/10 border border-emerald-200/80 dark:border-emerald-400/30 flex flex-col justify-between">
                            <span class="text-[7.5px] sm:text-[9.5px] font-bold text-emerald-700 dark:text-emerald-300 uppercase tracking-wider flex items-center gap-1 truncate">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-sm shrink-0"></span>
                                Pertamina Dex
                            </span>
                            <span class="text-[10.5px] sm:text-sm font-black text-slate-900 dark:text-white font-mono mt-0.5 tabular-nums truncate">
                                {{ rtrim(rtrim(number_format($hutangPerBbm['Pertamina Dex'] ?? 0, 0, ',', '.'), '0'), ',') }}<span class="text-[8px] sm:text-[10px] text-emerald-600 dark:text-emerald-300 font-sans font-bold"> L</span>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Status Alert Bar --}}
                <div class="mt-2 sm:mt-4 pt-1.5 sm:pt-3 border-t border-slate-200/70 dark:border-rose-500/20 flex items-center justify-between text-[8px] sm:text-[11px]">
                    <span class="hidden xs:inline text-slate-600 dark:text-rose-200/90 font-medium">Status:</span>
                    @if($totalHutang > 0)
                        <span class="inline-flex items-center gap-1 font-black text-amber-800 dark:text-amber-300 bg-amber-100 dark:bg-amber-500/20 px-1.5 sm:px-2.5 py-0.5 rounded border border-amber-300 dark:border-amber-400/30 text-[7.5px] sm:text-[10px] shadow-sm ml-auto xs:ml-0 shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Ada Bon
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 font-black text-emerald-800 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-500/20 px-1.5 sm:px-2.5 py-0.5 rounded border border-emerald-300 dark:border-emerald-400/30 text-[7.5px] sm:text-[10px] shadow-sm ml-auto xs:ml-0 shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Nihil
                        </span>
                    @endif
                </div>
            </div>

            {{-- ── CARD 3: DIAGRAM SALDO BBM (Interactive Donut & Ratio Bar) ── --}}
            <div class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-blue-50/90 via-white to-sky-50/60 dark:from-blue-950/50 dark:via-slate-900/95 dark:to-slate-950 border border-blue-200/80 dark:border-blue-500/25 p-2.5 sm:p-5 shadow-sm shadow-blue-900/5 dark:shadow-lg dark:shadow-blue-950/20 hover:border-blue-400/80 hover:-translate-y-1 transition-all duration-300 group flex flex-col justify-between">
                {{-- Ambient Top Glow --}}
                <div class="absolute -top-16 -right-16 w-36 h-36 bg-blue-400/15 dark:bg-blue-500/20 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
                <div class="absolute inset-x-0 top-0 h-[2.5px] bg-gradient-to-r from-blue-500 via-cyan-400 to-transparent"></div>

                <div>
                    {{-- Header Kartu --}}
                    <div class="flex items-center justify-between gap-1">
                        <div class="flex items-center gap-1.5 sm:gap-2.5 min-w-0">
                            <div class="w-6 h-6 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-blue-100 border border-blue-300/80 text-blue-700 dark:bg-blue-500/20 dark:border-blue-400/40 dark:text-blue-300 flex items-center justify-center shrink-0 shadow-sm shadow-blue-500/10 group-hover:scale-105 transition-transform">
                                <svg class="w-3 h-3 sm:w-4.5 sm:h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <span class="hidden sm:block text-[9px] sm:text-[10px] font-black uppercase tracking-wider text-blue-600 dark:text-blue-400/90 truncate">Ketersediaan</span>
                                <h3 class="text-[11px] sm:text-xs font-black text-slate-800 dark:text-white truncate">Saldo BBM</h3>
                            </div>
                        </div>
                        <a href="{{ route('satker.kendaraans.index') }}"
                            class="inline-flex items-center gap-0.5 sm:gap-1 text-[8px] sm:text-[10px] font-extrabold text-blue-700 hover:text-blue-800 dark:text-blue-300 dark:hover:text-white bg-blue-100/70 hover:bg-blue-200/70 dark:bg-blue-500/15 dark:hover:bg-blue-500/30 border border-blue-300/80 dark:border-blue-400/30 px-1.5 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg transition-all shadow-sm shrink-0">
                            Rincian
                            <svg class="w-2 h-2 sm:w-2.5 sm:h-2.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    {{-- Modern Donut Chart Container --}}
                    <div class="w-full flex items-center justify-center py-0.5 sm:py-2 relative">
                        <div id="fuelDonutChart" class="w-full max-w-[150px] sm:max-w-[210px] min-h-[130px] sm:min-h-[165px]"></div>
                    </div>
                </div>

                {{-- Modern Segmented Ratio Bar & Micro Stats --}}
                <div class="mt-1 sm:mt-2 pt-1.5 sm:pt-3 border-t border-slate-200/70 dark:border-blue-500/20 space-y-1 sm:space-y-2">
                    {{-- Visual Ratio Bar --}}
                    <div class="w-full h-1 sm:h-1.5 rounded-full bg-slate-200 dark:bg-slate-800 overflow-hidden flex">
                        <div class="bg-blue-500 h-full transition-all duration-700" style="width: {{ $persenPertamax }}%"></div>
                        <div class="bg-emerald-500 h-full transition-all duration-700" style="width: {{ $persenDex }}%"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-1 sm:gap-2 text-[9px] sm:text-[11px]">
                        <div class="flex flex-col justify-between p-1.5 sm:p-2 rounded-lg sm:rounded-xl bg-blue-50/80 dark:bg-blue-500/10 border border-blue-200/80 dark:border-blue-400/20">
                            <span class="inline-flex items-center gap-1 font-bold text-blue-700 dark:text-blue-300 text-[7.5px] sm:text-[9.5px] truncate">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 shrink-0"></span> Pertamax
                            </span>
                            <span class="font-black text-slate-900 dark:text-white font-mono text-[10.5px] sm:text-xs mt-0.5 truncate">
                                {{ rtrim(rtrim(number_format($saldoKendaraanPerBbm['Pertamax'] ?? 0, 0, ',', '.'), '0'), ',') }} L
                            </span>
                        </div>
                        <div class="flex flex-col justify-between p-1.5 sm:p-2 rounded-lg sm:rounded-xl bg-emerald-50/80 dark:bg-emerald-500/10 border border-emerald-200/80 dark:border-emerald-400/20">
                            <span class="inline-flex items-center gap-1 font-bold text-emerald-700 dark:text-emerald-300 text-[7.5px] sm:text-[9.5px] truncate">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span> Pertamina Dex
                            </span>
                            <span class="font-black text-slate-900 dark:text-white font-mono text-[10.5px] sm:text-xs mt-0.5 truncate">
                                {{ rtrim(rtrim(number_format($saldoKendaraanPerBbm['Pertamina Dex'] ?? 0, 0, ',', '.'), '0'), ',') }} L
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── CARD 4: DIAGRAM TIPE RODA (Interactive Fleet Donut & Badges) ── --}}
            <div class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-purple-50/90 via-white to-violet-50/60 dark:from-purple-950/50 dark:via-slate-900/95 dark:to-slate-950 border border-purple-200/80 dark:border-purple-500/25 p-2.5 sm:p-5 shadow-sm shadow-purple-900/5 dark:shadow-lg dark:shadow-purple-950/20 hover:border-purple-400/80 hover:-translate-y-1 transition-all duration-300 group flex flex-col justify-between">
                {{-- Ambient Top Glow --}}
                <div class="absolute -top-16 -right-16 w-36 h-36 bg-purple-400/15 dark:bg-purple-500/20 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
                <div class="absolute inset-x-0 top-0 h-[2.5px] bg-gradient-to-r from-purple-500 via-pink-400 to-transparent"></div>

                <div>
                    {{-- Header Kartu --}}
                    <div class="flex items-center justify-between gap-1">
                        <div class="flex items-center gap-1.5 sm:gap-2.5 min-w-0">
                            <div class="w-6 h-6 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-purple-100 border border-purple-300/80 text-purple-700 dark:bg-purple-500/20 dark:border-purple-400/40 dark:text-purple-300 flex items-center justify-center shrink-0 shadow-sm shadow-purple-500/10 group-hover:scale-105 transition-transform">
                                <svg class="w-3 h-3 sm:w-4.5 sm:h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <span class="hidden sm:block text-[9px] sm:text-[10px] font-black uppercase tracking-wider text-purple-600 dark:text-purple-400/90 truncate">Distribusi</span>
                                <h3 class="text-[11px] sm:text-xs font-black text-slate-800 dark:text-white truncate">Tipe Armada</h3>
                            </div>
                        </div>
                        <a href="{{ route('satker.kendaraans.index') }}"
                            class="inline-flex items-center gap-0.5 sm:gap-1 text-[8px] sm:text-[10px] font-extrabold text-purple-700 hover:text-purple-800 dark:text-purple-300 dark:hover:text-white bg-purple-100/70 hover:bg-purple-200/70 dark:bg-purple-500/15 dark:hover:bg-purple-500/30 border border-purple-300/80 dark:border-purple-400/30 px-1.5 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg transition-all shadow-sm shrink-0">
                            Kelola
                            <svg class="w-2 h-2 sm:w-2.5 sm:h-2.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    {{-- Modern Donut Chart Container --}}
                    <div class="w-full flex items-center justify-center py-0.5 sm:py-2 relative">
                        <div id="satkerFleetDonutChart" class="w-full max-w-[150px] sm:max-w-[210px] min-h-[130px] sm:min-h-[165px]"></div>
                    </div>
                </div>

                {{-- Segmented Grid Badges R2, R4, R6, NON --}}
                <div class="mt-1 sm:mt-2 pt-1.5 sm:pt-3 border-t border-slate-200/70 dark:border-purple-500/20 space-y-1 sm:space-y-2">
                    {{-- Visual Multi-Segment Bar --}}
                    <div class="w-full h-1 sm:h-1.5 rounded-full bg-slate-200 dark:bg-slate-800 overflow-hidden flex">
                        <div class="bg-emerald-500 h-full" style="width: {{ $persenR2 }}%"></div>
                        <div class="bg-blue-500 h-full" style="width: {{ $persenR4 }}%"></div>
                        <div class="bg-amber-500 h-full" style="width: {{ $persenR6 }}%"></div>
                        <div class="bg-purple-500 h-full" style="width: {{ $persenNon }}%"></div>
                    </div>

                    <div class="grid grid-cols-4 gap-0.5 sm:gap-1 text-center">
                        <div class="p-0.5 sm:p-1 rounded bg-emerald-50 border border-emerald-200 dark:bg-emerald-500/15 dark:border-emerald-400/30">
                            <span class="text-[6.5px] sm:text-[8px] font-black uppercase text-emerald-700 dark:text-emerald-300 block">R2</span>
                            <span class="text-[8.5px] sm:text-[11px] font-black text-slate-900 dark:text-white font-mono">{{ $rodaR2 }}</span>
                        </div>
                        <div class="p-0.5 sm:p-1 rounded bg-blue-50 border border-blue-200 dark:bg-blue-500/15 dark:border-blue-400/30">
                            <span class="text-[6.5px] sm:text-[8px] font-black uppercase text-blue-700 dark:text-blue-300 block">R4</span>
                            <span class="text-[8.5px] sm:text-[11px] font-black text-slate-900 dark:text-white font-mono">{{ $rodaR4 }}</span>
                        </div>
                        <div class="p-0.5 sm:p-1 rounded bg-amber-50 border border-amber-200 dark:bg-amber-500/15 dark:border-amber-400/30">
                            <span class="text-[6.5px] sm:text-[8px] font-black uppercase text-amber-700 dark:text-amber-300 block">R6</span>
                            <span class="text-[8.5px] sm:text-[11px] font-black text-slate-900 dark:text-white font-mono">{{ $rodaR6 }}</span>
                        </div>
                        <div class="p-0.5 sm:p-1 rounded bg-purple-50 border border-purple-200 dark:bg-purple-500/15 dark:border-purple-400/30">
                            <span class="text-[6.5px] sm:text-[8px] font-black uppercase text-purple-700 dark:text-purple-300 block">NON</span>
                            <span class="text-[8.5px] sm:text-[11px] font-black text-slate-900 dark:text-white font-mono">{{ $rodaNon }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ═══════════════════════════════════════════════════════════════════
             SECTION 3: DIAGRAM BATANG & AKTIVITAS PENGISIAN TERKINI
        ═══════════════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 sm:gap-5 w-full min-w-0">

            {{-- ── DIAGRAM KONSUMSI BBM (MODERN AREA & PILLAR BAR) ── --}}
            <div class="xl:col-span-2 w-full min-w-0 relative overflow-hidden bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-white/[0.08] rounded-2xl p-4 sm:p-6 shadow-sm dark:shadow-xl dark:shadow-black/20 backdrop-blur-xl flex flex-col justify-between">
                {{-- Glow Accent --}}
                <div class="absolute top-0 right-0 w-72 h-72 bg-gradient-to-bl from-blue-500/10 via-emerald-500/5 to-transparent rounded-bl-full pointer-events-none"></div>
                <div class="absolute inset-x-0 top-0 h-[2.5px] bg-gradient-to-r from-blue-500 via-cyan-400 to-emerald-400"></div>

                <div>
                    {{-- Header Diagram --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3 sm:mb-4">
                        <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-br from-blue-500/15 via-cyan-500/15 to-emerald-500/15 border border-blue-500/20 flex items-center justify-center text-blue-500 dark:text-cyan-400 shadow-sm shadow-blue-500/10 shrink-0">
                                <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-xs sm:text-base font-black text-slate-800 dark:text-white tracking-tight truncate">
                                        Konsumsi BBM Harian Armada
                                    </h3>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-slate-100 dark:bg-white/10 text-slate-600 dark:text-slate-300 shrink-0">
                                        7 Hari
                                    </span>
                                </div>
                                <p class="text-[10px] sm:text-[11px] text-slate-400 dark:text-slate-500 mt-0.5 truncate">
                                    Total: <strong class="text-slate-700 dark:text-slate-200 font-bold font-mono">{{ number_format($totalVolume7Hari, 1, ',', '.') }} L</strong> • Rerata: <strong class="text-slate-700 dark:text-slate-200 font-bold font-mono">{{ $avgVolumeHarian }} L/hari</strong>
                                </p>
                            </div>
                        </div>

                        {{-- Controls: Mode Switcher & Legend --}}
                        <div class="flex items-center justify-between sm:justify-end gap-2 shrink-0 flex-wrap">
                            {{-- View Mode Toggle Switcher --}}
                            <div class="inline-flex items-center p-0.5 rounded-xl bg-slate-100 dark:bg-white/[0.06] border border-slate-200/80 dark:border-white/[0.08] text-[10px] font-bold">
                                <button type="button" id="btnChartModeArea" onclick="setSatkerChartMode('area')"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg transition-all duration-200 text-white bg-blue-600 shadow-sm">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18"/></svg>
                                    <span>Tren</span>
                                </button>
                                <button type="button" id="btnChartModeBar" onclick="setSatkerChartMode('bar')"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg transition-all duration-200 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                    <span>Pilar</span>
                                </button>
                            </div>

                            {{-- Legend Chips --}}
                            <div class="flex items-center gap-1.5">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-bold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                                    <span class="w-2 h-2 rounded-full bg-blue-500 shadow-sm shadow-blue-500/50"></span> Pertamax
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></span> Dex
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Chart Area --}}
                    <div class="w-full min-w-0 overflow-hidden relative">
                        <div id="barConsumptionChart" class="w-full min-h-[290px] sm:min-h-[310px]"></div>
                    </div>
                </div>

                {{-- Micro-Summary 4 Metric Cards di Bawah Bagan --}}
                <div class="mt-3 pt-3 border-t border-slate-100 dark:border-white/[0.06] grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <div class="p-2 sm:p-2.5 rounded-xl bg-blue-50/60 dark:bg-blue-500/10 border border-blue-200/70 dark:border-blue-400/20 flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <span class="text-[9px] font-bold text-blue-700 dark:text-blue-300 uppercase tracking-wider">Pertamax</span>
                            <span class="text-[8px] font-extrabold text-blue-600 dark:text-blue-300 font-mono">{{ $persenPertamax7Hari }}%</span>
                        </div>
                        <span class="text-xs sm:text-sm font-black text-slate-900 dark:text-white font-mono mt-0.5 tabular-nums">
                            {{ number_format($totalPertamax7Hari, 1, ',', '.') }}<span class="text-[9px] font-sans font-bold text-blue-600 dark:text-blue-300"> L</span>
                        </span>
                    </div>

                    <div class="p-2 sm:p-2.5 rounded-xl bg-emerald-50/60 dark:bg-emerald-500/10 border border-emerald-200/70 dark:border-emerald-400/20 flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <span class="text-[9px] font-bold text-emerald-700 dark:text-emerald-300 uppercase tracking-wider">Pertamina Dex</span>
                            <span class="text-[8px] font-extrabold text-emerald-600 dark:text-emerald-300 font-mono">{{ $persenDex7Hari }}%</span>
                        </div>
                        <span class="text-xs sm:text-sm font-black text-slate-900 dark:text-white font-mono mt-0.5 tabular-nums">
                            {{ number_format($totalDex7Hari, 1, ',', '.') }}<span class="text-[9px] font-sans font-bold text-emerald-600 dark:text-emerald-300"> L</span>
                        </span>
                    </div>

                    <div class="p-2 sm:p-2.5 rounded-xl bg-slate-50 dark:bg-white/[0.04] border border-slate-200/70 dark:border-white/[0.06] flex flex-col justify-between">
                        <span class="text-[9px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider truncate">Rerata Harian</span>
                        <span class="text-xs sm:text-sm font-black text-slate-900 dark:text-white font-mono mt-0.5 tabular-nums truncate">
                            ~{{ $avgVolumeHarian }}<span class="text-[9px] font-sans font-bold text-slate-500 dark:text-slate-400"> L/hr</span>
                        </span>
                    </div>

                    <div class="p-2 sm:p-2.5 rounded-xl bg-purple-50/60 dark:bg-purple-500/10 border border-purple-200/70 dark:border-purple-400/20 flex flex-col justify-between">
                        <span class="text-[9px] font-bold text-purple-700 dark:text-purple-300 uppercase tracking-wider truncate">Puncak Pengisian</span>
                        <span class="text-xs sm:text-sm font-black text-purple-900 dark:text-purple-200 font-mono mt-0.5 truncate">
                            {{ $peakVal > 0 ? $peakDay . ' (' . number_format($peakVal, 0) . 'L)' : '-' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- ── AKTIVITAS TRANSAKSI PENGISIAN (1 Kolom di Layar Lebar) ── --}}
            <div class="xl:col-span-1 w-full min-w-0 relative overflow-hidden bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-white/[0.08] rounded-2xl shadow-sm dark:shadow-xl dark:shadow-black/20 flex flex-col justify-between backdrop-blur-xl">
                <div>
                    {{-- Header List --}}
                    <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-white/[0.06] flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-indigo-500/10 dark:bg-indigo-500/15 border border-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-800 dark:text-white tracking-tight">Aktivitas Terkini</h3>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Transaksi pengisian terakhir</p>
                            </div>
                        </div>
                        <a href="{{ route('satker.riwayat.index') }}" class="text-[11px] font-bold text-cyan-600 dark:text-cyan-400 hover:underline inline-flex items-center gap-0.5">
                            Semua
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    {{-- Activity List Items --}}
                    <div class="divide-y divide-slate-100 dark:divide-white/[0.04] overflow-y-auto max-h-[310px] custom-scrollbar">
                        @forelse($recentTransactions as $trx)
                            <div class="px-4 py-3 hover:bg-slate-50/70 dark:hover:bg-white/[0.02] transition-colors flex items-center justify-between gap-3 group">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-blue-500/10 dark:bg-blue-500/15 text-blue-600 dark:text-blue-400 border border-blue-500/20 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-mono font-black text-slate-800 dark:text-slate-100 truncate tracking-wide">
                                            {{ $trx->kendaraan?->no_polisi ?? '-' }}
                                        </p>
                                        <p class="text-[10px] text-slate-400 dark:text-slate-500 truncate">
                                            {{ $trx->created_at->diffForHumans() }} • {{ $trx->kendaraan?->jenis_kendaraan ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="font-mono font-black text-xs text-emerald-600 dark:text-emerald-400 tabular-nums bg-emerald-500/10 px-2 py-0.5 rounded-md border border-emerald-500/20">
                                        {{ number_format($trx->liter, 0, ',', '.') }} L
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center">
                                <div class="w-12 h-12 mx-auto mb-2 rounded-xl bg-slate-100 dark:bg-white/[0.04] flex items-center justify-center text-slate-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                </div>
                                <p class="text-xs text-slate-400 font-medium">Belum ada aktivitas transaksi pengisian.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Footer Link --}}
                <div class="p-3 bg-slate-50/70 dark:bg-white/[0.02] border-t border-slate-100 dark:border-white/[0.06] text-center">
                    <a href="{{ route('satker.riwayat.index') }}" class="text-xs font-bold text-cyan-600 dark:text-cyan-400 hover:text-cyan-700 dark:hover:text-cyan-300 transition-colors inline-flex items-center gap-1 group/btn">
                        Lihat Riwayat Lengkap
                        <svg class="w-3.5 h-3.5 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════
         APEXCHARTS SCRIPT (KEKINIAN & HIGHLY REFINED)
    ═══════════════════════════════════════════════════════════════════ --}}
    <script>
        var currentSatkerChartMode = 'area';

        function setSatkerChartMode(mode) {
            currentSatkerChartMode = mode;
            var btnArea = document.querySelector("#btnChartModeArea");
            var btnBar = document.querySelector("#btnChartModeBar");

            if (mode === 'area') {
                if (btnArea) {
                    btnArea.className = "inline-flex items-center gap-1 px-2.5 py-1 rounded-lg transition-all duration-200 text-white bg-blue-600 shadow-sm";
                }
                if (btnBar) {
                    btnBar.className = "inline-flex items-center gap-1 px-2.5 py-1 rounded-lg transition-all duration-200 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white";
                }
            } else {
                if (btnBar) {
                    btnBar.className = "inline-flex items-center gap-1 px-2.5 py-1 rounded-lg transition-all duration-200 text-white bg-blue-600 shadow-sm";
                }
                if (btnArea) {
                    btnArea.className = "inline-flex items-center gap-1 px-2.5 py-1 rounded-lg transition-all duration-200 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white";
                }
            }

            renderSatkerConsumptionChart();
        }

        function renderSatkerConsumptionChart() {
            var barEl = document.querySelector("#barConsumptionChart");
            if (!barEl || typeof ApexCharts === 'undefined') return;

            if (window.mySatkerBarChart) {
                try { window.mySatkerBarChart.destroy(); } catch (e) {}
                window.mySatkerBarChart = null;
                barEl.innerHTML = '';
            }

            var isDark = document.documentElement.classList.contains('dark');
            var isArea = currentSatkerChartMode === 'area';
            var pertamaxSeries = {!! json_encode(array_column($chartData, 'pertamax')) !!};
            var dexSeries = {!! json_encode(array_column($chartData, 'dex')) !!};
            var dateCategories = {!! json_encode(array_column($chartData, 'date')) !!};

            var options = {
                chart: {
                    type: isArea ? 'area' : 'bar',
                    height: 310,
                    width: '100%',
                    fontFamily: 'Inter, Outfit, sans-serif',
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    background: 'transparent',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 550,
                        animateGradually: { enabled: true, delay: 50 }
                    },
                    dropShadow: isArea ? {
                        enabled: true,
                        top: 4,
                        left: 0,
                        blur: 6,
                        opacity: isDark ? 0.25 : 0.15,
                        color: ['#3b82f6', '#10b981']
                    } : { enabled: false }
                },
                series: [
                    { name: 'Pertamax', data: pertamaxSeries },
                    { name: 'Pertamina Dex', data: dexSeries }
                ],
                colors: ['#3b82f6', '#10b981'],
                stroke: {
                    curve: 'smooth',
                    width: isArea ? 3 : 0
                },
                fill: isArea ? {
                    type: 'gradient',
                    gradient: {
                        shade: isDark ? 'dark' : 'light',
                        type: 'vertical',
                        shadeIntensity: 0.5,
                        opacityFrom: isDark ? 0.45 : 0.35,
                        opacityTo: 0.02,
                        stops: [0, 90, 100]
                    }
                } : {
                    type: 'gradient',
                    gradient: {
                        shade: isDark ? 'dark' : 'light',
                        type: 'vertical',
                        shadeIntensity: 0.25,
                        opacityFrom: 0.95,
                        opacityTo: 0.8,
                        stops: [0, 100]
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '50%',
                        borderRadius: 8,
                        borderRadiusApplication: 'end',
                        dataLabels: { position: 'top' },
                        colors: {
                            backgroundBarColors: [isDark ? 'rgba(255,255,255,0.03)' : 'rgba(0,0,0,0.03)'],
                            backgroundBarRadius: 8,
                            backgroundBarOpacity: 1
                        }
                    }
                },
                markers: isArea ? {
                    size: 4,
                    strokeColors: isDark ? '#0f172a' : '#ffffff',
                    strokeWidth: 2,
                    hover: { size: 6 }
                } : { size: 0 },
                dataLabels: {
                    enabled: !isArea,
                    formatter: function (val) {
                        return val > 0 ? val + 'L' : '';
                    },
                    offsetY: -20,
                    style: {
                        fontSize: '10px',
                        fontFamily: 'Inter, Outfit, sans-serif',
                        fontWeight: 'bold',
                        colors: [isDark ? '#60a5fa' : '#3b82f6', isDark ? '#34d399' : '#10b981']
                    }
                },
                legend: { show: false },
                xaxis: {
                    categories: dateCategories,
                    labels: {
                        style: {
                            colors: isDark ? '#64748b' : '#94a3b8',
                            fontSize: '11px',
                            fontFamily: 'Inter, Outfit, sans-serif',
                            fontWeight: 600
                        }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: isDark ? '#64748b' : '#94a3b8',
                            fontSize: '11px',
                            fontWeight: 500
                        },
                        formatter: function (val) {
                            return val + ' L';
                        }
                    }
                },
                grid: {
                    borderColor: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)',
                    strokeDashArray: 4,
                    xaxis: { lines: { show: false } },
                    padding: { top: 10, right: 10, bottom: 0, left: 10 }
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light',
                    shared: true,
                    intersect: false,
                    custom: function({ series, seriesIndex, dataPointIndex, w }) {
                        var pVal = series[0][dataPointIndex] || 0;
                        var dVal = series[1][dataPointIndex] || 0;
                        var totalVal = (pVal + dVal).toFixed(1);
                        var dateLabel = w.globals.labels[dataPointIndex];

                        return `
                            <div class="p-2.5 rounded-xl text-xs font-sans shadow-xl border backdrop-blur-md ${isDark ? 'bg-slate-900/95 border-slate-700/80 text-white' : 'bg-white/95 border-slate-200 text-slate-800'}">
                                <div class="font-black pb-1.5 mb-1.5 border-b ${isDark ? 'border-slate-800 text-slate-300' : 'border-slate-100 text-slate-600'} flex items-center justify-between gap-3">
                                    <span>📅 ${dateLabel}</span>
                                    <span class="font-mono font-bold ${isDark ? 'text-cyan-400' : 'text-blue-600'}">Total: ${totalVal} L</span>
                                </div>
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between gap-4">
                                        <span class="inline-flex items-center gap-1.5 text-blue-500 font-bold">
                                            <span class="w-2 h-2 rounded-full bg-blue-500"></span> Pertamax:
                                        </span>
                                        <span class="font-mono font-black">${pVal} L</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-4">
                                        <span class="inline-flex items-center gap-1.5 text-emerald-500 font-bold">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Pertamina Dex:
                                        </span>
                                        <span class="font-mono font-black">${dVal} L</span>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                }
            };

            window.mySatkerBarChart = new ApexCharts(barEl, options);
            window.mySatkerBarChart.render();
        }

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
            var isDark = document.documentElement.classList.contains('dark');
            var cardBorderColor = isDark ? '#0f172a' : '#ffffff';

            // ─────────────────────────────────────────────────────────────
            // 1. DIAGRAM KONSUMSI HARIAN BBM (MODERN AREA & PILLAR SWITCHABLE)
            // ─────────────────────────────────────────────────────────────
            renderSatkerConsumptionChart();

            // ─────────────────────────────────────────────────────────────
            // 2. DIAGRAM LINGKAR: SALDO BBM (MODERN DONUT WITH GLOW RING)
            // ─────────────────────────────────────────────────────────────
            var fuelEl = document.querySelector("#fuelDonutChart");
            if (fuelEl && typeof ApexCharts !== 'undefined') {
                var valP = {{ (float) ($saldoKendaraanPerBbm['Pertamax'] ?? 0) }};
                var valD = {{ (float) ($saldoKendaraanPerBbm['Pertamina Dex'] ?? 0) }};
                var fuelTotal = valP + valD;
                var isMobile = window.innerWidth < 640;

                var fuelOptions = {
                    chart: {
                        type: 'donut',
                        height: isMobile ? 135 : 180,
                        width: '100%',
                        fontFamily: 'Inter, Outfit, sans-serif',
                        animations: { enabled: true, speed: 700 }
                    },
                    series: fuelTotal > 0 ? [valP, valD] : [1],
                    labels: fuelTotal > 0 ? ['Pertamax', 'Pertamina Dex'] : ['Kosong'],
                    colors: fuelTotal > 0 ? ['#3b82f6', '#10b981'] : [isDark ? '#334155' : '#cbd5e1'],
                    stroke: {
                        width: isMobile ? 3 : 4,
                        colors: [cardBorderColor]
                    },
                    legend: { show: false },
                    dataLabels: { enabled: false },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: isMobile ? '72%' : '74%',
                                labels: {
                                    show: true,
                                    name: {
                                        show: true,
                                        fontSize: isMobile ? '8.5px' : '10px',
                                        fontFamily: 'Inter, sans-serif',
                                        fontWeight: 700,
                                        color: isDark ? '#94a3b8' : '#64748b',
                                        offsetY: isMobile ? -2 : -4
                                    },
                                    value: {
                                        show: true,
                                        fontSize: isMobile ? '13px' : '17px',
                                        fontFamily: 'Outfit, Inter, sans-serif',
                                        fontWeight: 900,
                                        color: isDark ? '#ffffff' : '#0f172a',
                                        offsetY: isMobile ? 2 : 4,
                                        formatter: function(val) {
                                            return parseFloat(val).toLocaleString('id-ID') + ' L';
                                        }
                                    },
                                    total: {
                                        show: true,
                                        label: 'TOTAL SALDO',
                                        color: isDark ? '#94a3b8' : '#64748b',
                                        fontSize: isMobile ? '7.5px' : '9px',
                                        fontWeight: 800,
                                        formatter: function () {
                                            return "{{ rtrim(rtrim(number_format($totalSaldoKendaraan, 1, ',', '.'), '0'), ',') }} L";
                                        }
                                    }
                                }
                            }
                        }
                    },
                    tooltip: {
                        theme: isDark ? 'dark' : 'light',
                        y: {
                            formatter: function (val) {
                                return parseFloat(val).toLocaleString('id-ID') + ' Liter';
                            }
                        }
                    }
                };
                window.mySatkerFuelChart = new ApexCharts(fuelEl, fuelOptions);
                window.mySatkerFuelChart.render();
            }

            // ─────────────────────────────────────────────────────────────
            // 3. DIAGRAM LINGKAR: TIPE RODA (MULTI-SEGMENTED ARMADA DONUT)
            // ─────────────────────────────────────────────────────────────
            var satkerFleetEl = document.querySelector("#satkerFleetDonutChart");
            if (satkerFleetEl && typeof ApexCharts !== 'undefined') {
                var r2 = {{ (int) $rodaR2 }};
                var r4 = {{ (int) $rodaR4 }};
                var r6 = {{ (int) $rodaR6 }};
                var rNon = {{ (int) $rodaNon }};
                var totalRoda = r2 + r4 + r6 + rNon;
                var hasArmada = totalRoda > 0;
                var isMobile = window.innerWidth < 640;

                var satkerFleetOptions = {
                    chart: {
                        type: 'donut',
                        height: isMobile ? 135 : 180,
                        width: '100%',
                        fontFamily: 'Inter, Outfit, sans-serif',
                        animations: { enabled: true, speed: 700 }
                    },
                    series: hasArmada ? [r2, r4, r6, rNon] : [1],
                    labels: hasArmada ? ['Roda 2', 'Roda 4', 'Roda 6', 'Non Kendaraan'] : ['Belum Ada Data'],
                    colors: hasArmada ? ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6'] : [isDark ? '#334155' : '#cbd5e1'],
                    stroke: {
                        width: isMobile ? 3 : 4,
                        colors: [cardBorderColor]
                    },
                    legend: { show: false },
                    dataLabels: { enabled: false },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: isMobile ? '72%' : '74%',
                                labels: {
                                    show: true,
                                    name: {
                                        show: true,
                                        fontSize: isMobile ? '8.5px' : '10px',
                                        fontFamily: 'Inter, sans-serif',
                                        fontWeight: 700,
                                        color: isDark ? '#94a3b8' : '#64748b',
                                        offsetY: isMobile ? -2 : -4
                                    },
                                    value: {
                                        show: true,
                                        fontSize: isMobile ? '13px' : '17px',
                                        fontFamily: 'Outfit, Inter, sans-serif',
                                        fontWeight: 900,
                                        color: isDark ? '#ffffff' : '#0f172a',
                                        offsetY: isMobile ? 2 : 4,
                                        formatter: function(val) {
                                            return val + ' Unit';
                                        }
                                    },
                                    total: {
                                        show: true,
                                        label: 'TOTAL ARMADA',
                                        color: isDark ? '#94a3b8' : '#64748b',
                                        fontSize: isMobile ? '7.5px' : '9px',
                                        fontWeight: 800,
                                        formatter: function () {
                                            return "{{ $totalKendaraan }} Unit";
                                        }
                                    }
                                }
                            }
                        }
                    },
                    tooltip: {
                        theme: isDark ? 'dark' : 'light',
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
