<x-app-layout>
    <div class="p-3 sm:p-6 lg:p-7 space-y-4 sm:space-y-5 max-w-7xl mx-auto">
        <!-- Executive Welcome Banner (Compact di Mobile, Mewah di Desktop) -->
        <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-xl sm:rounded-3xl border border-indigo-500/30 p-3 sm:p-5 shadow-lg sm:shadow-xl shadow-indigo-950/20">
            <div class="absolute -top-20 -right-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 sm:gap-3">
                <div class="min-w-0">
                    <div class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-white/10 text-cyan-300 border border-white/10 mb-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        SPBP POLDA NTB • POS PELAYANAN BBM
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <h1 class="text-base sm:text-2xl font-black text-white tracking-tight truncate">
                            Dashboard Operasional SPBP
                        </h1>
                        <!-- Tanggal & Jam Ringkas di Mobile -->
                        <span class="sm:hidden text-[10px] font-bold text-cyan-300 bg-white/10 px-2 py-0.5 rounded-lg border border-white/10 shrink-0">
                            {{ \Carbon\Carbon::now('Asia/Makassar')->translatedFormat('d M') }} • {{ \Carbon\Carbon::now('Asia/Makassar')->format('H:i') }}
                        </span>
                    </div>
                    <p class="mt-0.5 text-xs text-slate-300">
                        Selamat bertugas, <span class="font-bold text-white">{{ auth()->user()->name }}</span>.<span class="hidden sm:inline"> Pantau kapasitas tangki fisik dan penyaluran BBM harian secara akurat.</span>
                    </p>
                </div>

                <!-- Date & Status di Desktop -->
                <div class="hidden sm:flex items-center gap-2 shrink-0">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/15 text-xs font-bold text-white shadow-sm">
                        <svg class="w-3.5 h-3.5 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ \Carbon\Carbon::now('Asia/Makassar')->translatedFormat('l, d F Y') }}</span>
                        <span class="text-cyan-300 font-extrabold">{{ \Carbon\Carbon::now('Asia/Makassar')->format('H:i') }} WITA</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Shortcut Actions Grid (3 Kolom Simetris) -->
        <div class="grid grid-cols-3 gap-2 sm:gap-3">
            <a href="{{ route('petugas.hutang.index') }}"
                class="group p-2 sm:p-3 rounded-xl bg-gradient-to-br from-amber-50 to-amber-100/70 dark:from-slate-900 dark:to-amber-950/40 border border-amber-200 dark:border-amber-500/30 hover:border-amber-500 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex flex-col sm:flex-row items-center sm:items-center text-center sm:text-left gap-1.5 sm:gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-amber-500/40 group-hover:scale-105 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-800 dark:text-white truncate group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">Bon BBM</p>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate hidden sm:block">Catat / Pelunasan</p>
                </div>
            </a>

            <a href="{{ route('petugas.sinkronisasi.index') }}"
                class="group p-2 sm:p-3 rounded-xl bg-gradient-to-br from-cyan-50 to-cyan-100/70 dark:from-slate-900 dark:to-cyan-950/40 border border-cyan-200 dark:border-cyan-500/30 hover:border-cyan-500 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex flex-col sm:flex-row items-center sm:items-center text-center sm:text-left gap-1.5 sm:gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-cyan-600 text-white flex items-center justify-center shrink-0 shadow-sm shadow-cyan-500/40 group-hover:scale-105 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-800 dark:text-white truncate group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">Sinkronisasi Tangki</p>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate hidden sm:block">Update Stok Fisik</p>
                </div>
            </a>

            <a href="{{ route('petugas.riwayat.index') }}"
                class="group p-2 sm:p-3 rounded-xl bg-gradient-to-br from-emerald-50 to-emerald-100/70 dark:from-slate-900 dark:to-emerald-950/40 border border-emerald-200 dark:border-emerald-500/30 hover:border-emerald-500 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex flex-col sm:flex-row items-center sm:items-center text-center sm:text-left gap-1.5 sm:gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-sm shadow-emerald-500/40 group-hover:scale-105 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-800 dark:text-white truncate group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Riwayat Penyaluran</p>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate hidden sm:block">Laporan & Arsip</p>
                </div>
            </a>
        </div>

        @php
            $pertamaxToday = 0;
            $dexToday = 0;
            if (isset($breakdownBbm) && is_iterable($breakdownBbm)) {
                foreach($breakdownBbm as $jenis => $total) {
                    if (stripos($jenis, 'dex') !== false) {
                        $dexToday += $total;
                    } else {
                        $pertamaxToday += $total;
                    }
                }
            }
        @endphp

        <!-- 1. Aktivitas Penyaluran BBM Hari Ini (Cards 1 to 4) -->
        <div>
            <div class="flex items-center justify-between mb-2.5">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-cyan-500 animate-pulse"></div>
                    <h2 class="text-xs sm:text-sm font-extrabold text-slate-800 dark:text-white uppercase tracking-wider">
                        Aktivitas Penyaluran BBM Hari Ini
                    </h2>
                </div>
                <span class="text-[10px] sm:text-[11px] font-bold text-slate-500 dark:text-slate-400">
                    {{ \Carbon\Carbon::now('Asia/Makassar')->translatedFormat('d F Y') }}
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4">
                <!-- Card 1: Frekuensi Pelayanan (Vibrant Indigo / Purple) -->
                <div class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white p-2.5 sm:p-4 shadow-md sm:shadow-lg shadow-indigo-500/20 border border-indigo-400/30 group hover:shadow-xl hover:shadow-indigo-500/30 hover:-translate-y-0.5 transition-all duration-300 flex flex-col justify-between">
                    <div class="absolute -top-10 -right-10 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between">
                            <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center shrink-0 shadow-inner">
                                <svg class="w-3.5 h-3.5 sm:w-4.5 sm:h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <span class="inline-flex items-center gap-1 text-[9px] sm:text-[10px] font-extrabold text-indigo-100 bg-white/20 backdrop-blur-sm px-1.5 sm:px-2 py-0.5 rounded border border-white/20">
                                Hari Ini
                            </span>
                        </div>
                        <div class="mt-2 sm:mt-3">
                            <span class="text-[9px] sm:text-[10px] font-bold text-indigo-100/90 uppercase tracking-wider block truncate">Frekuensi Pelayanan</span>
                            <div class="mt-0.5 flex items-baseline gap-1 sm:gap-1.5">
                                <span class="text-xl sm:text-3xl font-black text-white tabular-nums">
                                    {{ number_format($todayTransactions, 0, ',', '.') }}
                                </span>
                                <span class="text-[10px] sm:text-xs font-bold text-indigo-200">Transaksi</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative z-10 mt-2 sm:mt-3 pt-1.5 sm:pt-2 border-t border-white/20 text-[9px] sm:text-[10px] text-indigo-100/80 truncate">
                        Total kendaraan & personel
                    </div>
                </div>

                <!-- Card 2: Total Volume Keluar (Vibrant Emerald / Teal) -->
                <div class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-emerald-600 via-teal-600 to-emerald-800 text-white p-2.5 sm:p-4 shadow-md sm:shadow-lg shadow-emerald-500/20 border border-emerald-400/30 group hover:shadow-xl hover:shadow-emerald-500/30 hover:-translate-y-0.5 transition-all duration-300 flex flex-col justify-between">
                    <div class="absolute -top-10 -right-10 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between">
                            <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center shrink-0 shadow-inner">
                                <svg class="w-3.5 h-3.5 sm:w-4.5 sm:h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>
                            <span class="inline-flex items-center gap-1 text-[9px] sm:text-[10px] font-extrabold text-emerald-100 bg-white/20 backdrop-blur-sm px-1.5 sm:px-2 py-0.5 rounded border border-white/20">
                                Akumulasi
                            </span>
                        </div>
                        <div class="mt-2 sm:mt-3">
                            <span class="text-[9px] sm:text-[10px] font-bold text-emerald-100/90 uppercase tracking-wider block truncate">Total Volume Keluar</span>
                            <div class="mt-0.5 flex items-baseline gap-1 sm:gap-1.5">
                                <span class="text-xl sm:text-3xl font-black text-white tabular-nums">
                                    {{ rtrim(rtrim(number_format($todayLiter, 2, ',', '.'), '0'), ',') }}
                                </span>
                                <span class="text-[10px] sm:text-xs font-bold text-emerald-200">Liter</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative z-10 mt-2 sm:mt-3 pt-1.5 sm:pt-2 border-t border-white/20 text-[9px] sm:text-[10px] text-emerald-100/80 truncate">
                        Semua jenis BBM disalurkan
                    </div>
                </div>

                <!-- Card 3: Penyaluran Pertamax (Vibrant Royal Blue / Indigo) -->
                <div class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-blue-600 via-indigo-600 to-blue-800 text-white p-2.5 sm:p-4 shadow-md sm:shadow-lg shadow-blue-500/20 border border-blue-400/30 group hover:shadow-xl hover:shadow-blue-500/30 hover:-translate-y-0.5 transition-all duration-300 flex flex-col justify-between">
                    <div class="absolute -top-10 -right-10 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between">
                            <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 sm:w-4.5 sm:h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <span class="inline-flex items-center gap-1 text-[9px] sm:text-[10px] font-extrabold text-white bg-white/20 backdrop-blur-sm px-1.5 sm:px-2 py-0.5 rounded border border-white/20">
                                Pertamax
                            </span>
                        </div>
                        <div class="mt-2 sm:mt-3">
                            <span class="text-[9px] sm:text-[10px] font-bold text-white/90 uppercase tracking-wider block truncate">Penyaluran Pertamax</span>
                            <div class="mt-0.5 flex items-baseline gap-1 sm:gap-1.5">
                                <span class="text-xl sm:text-3xl font-black text-white tabular-nums">
                                    {{ rtrim(rtrim(number_format($pertamaxToday, 2, ',', '.'), '0'), ',') }}
                                </span>
                                <span class="text-[10px] sm:text-xs font-bold text-white/80">Liter</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative z-10 mt-2 sm:mt-3 pt-1.5 sm:pt-2 border-t border-white/20 text-[9px] sm:text-[10px] text-white/80 truncate">
                        Penyaluran hari berjalan
                    </div>
                </div>

                <!-- Card 4: Penyaluran Pertamina Dex (Vibrant Ruby / Rose / Red) -->
                <div class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-rose-600 via-rose-700 to-red-800 text-white p-2.5 sm:p-4 shadow-md sm:shadow-lg shadow-rose-500/20 border border-rose-400/30 group hover:shadow-xl hover:shadow-rose-500/30 hover:-translate-y-0.5 transition-all duration-300 flex flex-col justify-between">
                    <div class="absolute -top-10 -right-10 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between">
                            <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 sm:w-4.5 sm:h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <span class="inline-flex items-center gap-1 text-[9px] sm:text-[10px] font-extrabold text-white bg-white/20 backdrop-blur-sm px-1.5 sm:px-2 py-0.5 rounded border border-white/20">
                                Dex
                            </span>
                        </div>
                        <div class="mt-2 sm:mt-3">
                            <span class="text-[9px] sm:text-[10px] font-bold text-white/90 uppercase tracking-wider block truncate">Penyaluran Dex</span>
                            <div class="mt-0.5 flex items-baseline gap-1 sm:gap-1.5">
                                <span class="text-xl sm:text-3xl font-black text-white tabular-nums">
                                    {{ rtrim(rtrim(number_format($dexToday, 2, ',', '.'), '0'), ',') }}
                                </span>
                                <span class="text-[10px] sm:text-xs font-bold text-white/80">Liter</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative z-10 mt-2 sm:mt-3 pt-1.5 sm:pt-2 border-t border-white/20 text-[9px] sm:text-[10px] text-white/80 truncate">
                        Penyaluran hari berjalan
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Kondisi Stok Fisik di Tangki Pendam (Cards 5 & 6) -->
        @if($tankStock)
            @php
                $persenPertamax = $tankStock->stok_awal_pertamax > 0 
                    ? min(100, max(0, round(($tankStock->sisa_pertamax / $tankStock->stok_awal_pertamax) * 100))) 
                    : 0;
                $persenDex = $tankStock->stok_awal_dex > 0 
                    ? min(100, max(0, round(($tankStock->sisa_dex / $tankStock->stok_awal_dex) * 100))) 
                    : 0;
            @endphp
            <div>
                <div class="flex items-center justify-between mb-2.5">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
                        <h2 class="text-xs sm:text-sm font-extrabold text-slate-800 dark:text-white uppercase tracking-wider">
                            Kondisi Stok Fisik di Tangki Pendam
                        </h2>
                    </div>
                    <span class="text-[10px] sm:text-[11px] font-bold text-slate-500 dark:text-slate-400">
                        Sinkronisasi: {{ $tankStock->created_at ? $tankStock->created_at->timezone('Asia/Makassar')->translatedFormat('d M Y, H:i') . ' WITA' : '-' }}
                    </span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-2 gap-2.5 sm:gap-4">
                    <!-- Card 5: Sisa Pertamax Tank Card (Vibrant Royal Blue Gradient) -->
                    <div class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-900 text-white p-2.5 sm:p-4 shadow-md sm:shadow-lg shadow-blue-500/20 border border-blue-400/30 group hover:shadow-xl hover:shadow-blue-500/30 hover:-translate-y-0.5 transition-all duration-300 flex flex-col justify-between">
                        <div class="absolute -right-8 -top-8 w-24 h-24 bg-cyan-400/20 rounded-full blur-2xl pointer-events-none group-hover:scale-125 transition-transform duration-500"></div>

                        <div class="relative z-10 flex flex-col justify-between h-full space-y-2 sm:space-y-2.5">
                            <div class="flex items-center justify-between">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center shrink-0 shadow-inner">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                </div>
                                <span class="inline-flex items-center gap-1 px-1.5 sm:px-2 py-0.5 rounded-full text-[9px] sm:text-[10px] font-extrabold bg-white/20 backdrop-blur-sm text-cyan-200 border border-white/25">
                                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-300 animate-pulse"></span>
                                    {{ $persenPertamax }}% Sisa
                                </span>
                            </div>

                            <div>
                                <span class="text-[9px] sm:text-[10px] font-bold text-blue-100/90 uppercase tracking-wider block truncate">Sisa Pertamax</span>
                                <div class="flex items-baseline gap-1 mt-0.5">
                                    <span class="text-lg sm:text-2xl font-black text-white tracking-tight tabular-nums">
                                        {{ number_format($tankStock->sisa_pertamax, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[10px] sm:text-xs font-extrabold text-blue-200">Liter</span>
                                </div>
                            </div>

                            <div class="space-y-1 pt-0.5 sm:pt-1">
                                <div class="w-full bg-black/30 rounded-full h-1.5 p-0.5 backdrop-blur-sm overflow-hidden border border-white/20">
                                    <div class="bg-gradient-to-r from-cyan-300 to-white h-full rounded-full transition-all duration-700 shadow-sm"
                                        style="width: {{ $persenPertamax }}%"></div>
                                </div>
                                <div class="text-[9px] sm:text-[10px] text-blue-100/80 truncate">
                                    Awal: {{ number_format($tankStock->stok_awal_pertamax, 0, ',', '.') }} L
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 6: Sisa Dex Tank Card (Vibrant Ruby / Crimson Gradient) -->
                    <div class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-rose-600 via-rose-700 to-red-900 text-white p-2.5 sm:p-4 shadow-md sm:shadow-lg shadow-rose-500/20 border border-rose-400/30 group hover:shadow-xl hover:shadow-rose-500/30 hover:-translate-y-0.5 transition-all duration-300 flex flex-col justify-between">
                        <div class="absolute -right-8 -top-8 w-24 h-24 bg-amber-400/20 rounded-full blur-2xl pointer-events-none group-hover:scale-125 transition-transform duration-500"></div>

                        <div class="relative z-10 flex flex-col justify-between h-full space-y-2 sm:space-y-2.5">
                            <div class="flex items-center justify-between">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center shrink-0 shadow-inner">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                </div>
                                <span class="inline-flex items-center gap-1 px-1.5 sm:px-2 py-0.5 rounded-full text-[9px] sm:text-[10px] font-extrabold bg-white/20 backdrop-blur-sm text-rose-100 border border-white/25">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-300 animate-pulse"></span>
                                    {{ $persenDex }}% Sisa
                                </span>
                            </div>

                            <div>
                                <span class="text-[9px] sm:text-[10px] font-bold text-rose-100/90 uppercase tracking-wider block truncate">Sisa Dex</span>
                                <div class="flex items-baseline gap-1 mt-0.5">
                                    <span class="text-lg sm:text-2xl font-black text-white tracking-tight tabular-nums">
                                        {{ number_format($tankStock->sisa_dex, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[10px] sm:text-xs font-extrabold text-rose-200">Liter</span>
                                </div>
                            </div>

                            <div class="space-y-1 pt-0.5 sm:pt-1">
                                <div class="w-full bg-black/30 rounded-full h-1.5 p-0.5 backdrop-blur-sm overflow-hidden border border-white/20">
                                    <div class="bg-gradient-to-r from-amber-300 to-white h-full rounded-full transition-all duration-700 shadow-sm"
                                        style="width: {{ $persenDex }}%"></div>
                                </div>
                                <div class="text-[9px] sm:text-[10px] text-rose-100/80 truncate">
                                    Awal: {{ number_format($tankStock->stok_awal_dex, 0, ',', '.') }} L
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Hutang / Bon Belum Dibayar Section (Colorful Cards) -->
        <div>
            <div class="flex items-center justify-between mb-2.5">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></div>
                    <h2 class="text-xs sm:text-sm font-extrabold text-slate-800 dark:text-white uppercase tracking-wider">
                        Total Bon BBM Belum Dibayar (Tunggakan)
                    </h2>
                </div>
                <span class="text-[10px] sm:text-[11px] font-bold text-slate-500 dark:text-slate-400">
                    Status: Belum Lunas
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                @forelse($hutangPerBbm as $jenis => $total)
                    @php
                        $isPertamax = stripos($jenis, 'pertamax') !== false;
                        $gradientTheme = $isPertamax 
                            ? 'bg-gradient-to-br from-amber-500 via-orange-600 to-amber-700 text-white shadow-lg shadow-orange-500/20 border-amber-400/30' 
                            : 'bg-gradient-to-br from-rose-600 via-rose-700 to-red-800 text-white shadow-lg shadow-rose-500/20 border-rose-400/30';
                    @endphp
                    <div class="relative overflow-hidden rounded-xl sm:rounded-2xl {{ $gradientTheme }} p-3.5 sm:p-4 shadow-sm hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 group flex items-center justify-between border">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform shadow-inner">
                                <svg class="w-4.5 h-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-[9px] font-black uppercase tracking-wider px-1.5 py-0.5 rounded bg-white/20 backdrop-blur-sm border border-white/20 text-white">
                                    {{ $jenis }}
                                </span>
                                <div class="flex items-baseline gap-1 mt-1">
                                    <span class="text-xl sm:text-2xl font-black text-white tabular-nums">
                                        {{ rtrim(rtrim(number_format($total, 2, ',', '.'), '0'), ',') }}
                                    </span>
                                    <span class="text-xs font-bold text-white/80">Liter</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl sm:rounded-2xl p-3.5 sm:p-4 flex items-center justify-center gap-2.5 shadow-md shadow-emerald-500/20 border border-emerald-400/30">
                        <div class="w-7 h-7 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <p class="text-xs font-bold text-white">
                            Semua Bon Telah Lunas • Tidak ada tunggakan bon BBM saat ini.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Floating Draggable Icon Menu Transaksi BBM -->
    <div x-data="draggableFab()" 
         x-init="init()"
         class="fixed z-50 select-none touch-none"
         :style="`left: ${x}px; top: ${y}px; position: fixed;`"
         style="display: none;"
         x-show="isReady"
         x-cloak>
        
        <button type="button"
                @mousedown="startDrag($event)"
                @touchstart="startDrag($event)"
                @click="handleClick($event)"
                title="Pelayanan Transaksi BBM (Ketuk untuk buka, geser untuk ubah posisi)"
                class="relative group w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gradient-to-tr from-indigo-600 via-indigo-700 to-cyan-500 shadow-2xl shadow-indigo-600/50 border-2 border-white/70 flex items-center justify-center text-white cursor-grab active:cursor-grabbing hover:scale-110 active:scale-95 transition-transform duration-150">
            
            <!-- Animated Pulse Ring Halo -->
            <span class="absolute inset-0 rounded-full bg-cyan-400 animate-ping opacity-30 pointer-events-none"></span>

            <!-- Icon Dispenser BBM / Petir Kilat -->
            <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white filter drop-shadow-md pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>

            <!-- Mini Pulse Indicator Badge -->
            <span class="absolute top-0 right-0 w-3.5 h-3.5 rounded-full bg-emerald-400 border-2 border-white pointer-events-none shadow-sm animate-pulse"></span>
        </button>
    </div>

    @push('scripts')
    <script>
        function draggableFab() {
            return {
                x: 0,
                y: 0,
                isReady: false,
                isDragging: false,
                startX: 0,
                startY: 0,
                initialX: 0,
                initialY: 0,
                hasMoved: false,
                init() {
                    const btnSize = window.innerWidth >= 640 ? 64 : 56;
                    const savedX = localStorage.getItem('petugas_fab_x');
                    const savedY = localStorage.getItem('petugas_fab_y');
                    
                    if (savedX !== null && savedY !== null) {
                        this.x = Math.max(10, Math.min(window.innerWidth - btnSize - 10, parseInt(savedX)));
                        this.y = Math.max(10, Math.min(window.innerHeight - btnSize - 10, parseInt(savedY)));
                    } else {
                        // Posisi Standar: Kanan Bawah Mengambang
                        this.x = Math.max(10, window.innerWidth - btnSize - 20);
                        this.y = Math.max(10, window.innerHeight - btnSize - 90);
                    }
                    this.isReady = true;

                    window.addEventListener('resize', () => {
                        const size = window.innerWidth >= 640 ? 64 : 56;
                        this.x = Math.max(10, Math.min(window.innerWidth - size - 10, this.x));
                        this.y = Math.max(10, Math.min(window.innerHeight - size - 10, this.y));
                    });
                },
                startDrag(e) {
                    this.isDragging = true;
                    this.hasMoved = false;
                    const clientX = e.type.startsWith('touch') ? e.touches[0].clientX : e.clientX;
                    const clientY = e.type.startsWith('touch') ? e.touches[0].clientY : e.clientY;
                    this.startX = clientX;
                    this.startY = clientY;
                    this.initialX = this.x;
                    this.initialY = this.y;

                    const onMove = (moveEvent) => {
                        if (!this.isDragging) return;
                        const curX = moveEvent.type.startsWith('touch') ? moveEvent.touches[0].clientX : moveEvent.clientX;
                        const curY = moveEvent.type.startsWith('touch') ? moveEvent.touches[0].clientY : moveEvent.clientY;
                        const dx = curX - this.startX;
                        const dy = curY - this.startY;

                        if (Math.hypot(dx, dy) > 6) {
                            this.hasMoved = true;
                        }

                        const btnSize = window.innerWidth >= 640 ? 64 : 56;
                        const nextX = this.initialX + dx;
                        const nextY = this.initialY + dy;

                        this.x = Math.max(8, Math.min(window.innerWidth - btnSize - 8, nextX));
                        this.y = Math.max(8, Math.min(window.innerHeight - btnSize - 8, nextY));

                        if (moveEvent.cancelable && moveEvent.type.startsWith('touch')) {
                            moveEvent.preventDefault();
                        }
                    };

                    const onEnd = () => {
                        if (!this.isDragging) return;
                        this.isDragging = false;
                        window.removeEventListener('mousemove', onMove);
                        window.removeEventListener('mouseup', onEnd);
                        window.removeEventListener('touchmove', onMove);
                        window.removeEventListener('touchend', onEnd);

                        if (this.hasMoved) {
                            localStorage.setItem('petugas_fab_x', this.x);
                            localStorage.setItem('petugas_fab_y', this.y);
                        }
                    };

                    window.addEventListener('mousemove', onMove);
                    window.addEventListener('mouseup', onEnd);
                    window.addEventListener('touchmove', onMove, { passive: false });
                    window.addEventListener('touchend', onEnd);
                },
                handleClick(e) {
                    if (this.hasMoved) {
                        e.preventDefault();
                        e.stopPropagation();
                        return;
                    }
                    window.location.href = "{{ route('petugas.transaksi.index') }}";
                }
            }
        }
    </script>
    @endpush
</x-app-layout>