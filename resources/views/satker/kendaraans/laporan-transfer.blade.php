<x-app-layout>
    @php
        $totalSemuaLiter = $summary->sum();
        $totalPertamax = $summary->get('Pertamax', 0);
        $totalDex = $summary->get('Pertamina Dex', 0);
    @endphp

    <div class="p-3.5 sm:p-6 lg:p-8 space-y-3.5 sm:space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-white dark:bg-slate-900 p-3.5 sm:p-6 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-brand-primary/10 text-brand-primary dark:bg-white/10 dark:text-white flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-base sm:text-2xl font-black text-slate-800 dark:text-white tracking-tight">Laporan Transfer Saldo</h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-brand-primary/10 text-brand-primary dark:bg-white/10 dark:text-white">
                            {{ number_format($riwayats->total()) }} Transaksi
                        </span>
                    </div>
                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                        Riwayat transfer dan alokasi saldo kendaraan ke personel maupun antar kendaraan.
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <a href="{{ route('satker.kendaraans.laporan-transfer.print', request()->query()) }}" target="_blank"
                    rel="nofollow"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center px-3.5 py-2 sm:py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white rounded-xl font-bold text-xs sm:text-sm shadow-md shadow-rose-600/20 transition-all duration-200 gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak PDF</span>
                </a>
                <a href="{{ route('satker.kendaraans.index') }}"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center px-3.5 py-2 sm:py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-xs sm:text-sm border border-slate-200 dark:border-white/10 transition-all duration-200 gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        <!-- Filter Card (Ditempatkan di Atas KPI Cards, 1 Baris di Mobile) -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm p-3 sm:p-4 relative z-30">
            <form action="{{ route('satker.kendaraans.laporan-transfer') }}" method="GET"
                class="flex items-end gap-2 sm:gap-3">
                <div class="flex-1 min-w-0">
                    <label class="block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">
                        Mulai
                    </label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" placeholder="Mulai"
                        class="flatpickr w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 rounded-xl px-2.5 sm:px-3.5 py-2 text-xs font-semibold text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-brand-primary transition-all">
                </div>

                <div class="flex-1 min-w-0">
                    <label class="block text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">
                        Sampai
                    </label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" placeholder="Sampai"
                        class="flatpickr w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 rounded-xl px-2.5 sm:px-3.5 py-2 text-xs font-semibold text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-brand-primary transition-all">
                </div>

                <div class="shrink-0 flex items-center gap-1.5">
                    <button type="submit"
                        class="h-9 sm:h-10 px-3 bg-brand-primary hover:bg-brand-primary/90 active:scale-95 text-white rounded-xl font-bold text-xs transition flex items-center justify-center gap-1 shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span class="hidden sm:inline">Filter</span>
                    </button>
                    <a href="{{ route('satker.kendaraans.laporan-transfer') }}"
                        class="h-9 sm:h-10 px-2.5 sm:px-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-xs transition flex items-center justify-center gap-1 border border-slate-200 dark:border-white/10"
                        title="Reset Filter">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span class="hidden sm:inline">Reset</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- KPI Summary Cards (Compact 3 Kolom di Mobile, Tidak Menghabiskan Layar) -->
        <div class="grid grid-cols-3 gap-2 sm:gap-4">
            <!-- Card 1: Total Volume Transfer -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 p-2.5 sm:p-5 shadow-sm relative overflow-hidden group hover:border-brand-primary/40 transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] sm:text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider truncate">Total Transfer</span>
                        <div class="hidden sm:flex w-8 h-8 rounded-xl bg-brand-primary/10 text-brand-primary dark:bg-white/10 dark:text-white items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-1.5 sm:mt-3 flex items-baseline gap-1">
                        <span class="text-sm sm:text-3xl font-black text-slate-800 dark:text-white tabular-nums">
                            {{ number_format($totalSemuaLiter, 0, ',', '.') }}
                        </span>
                        <span class="text-[10px] sm:text-xs font-bold text-slate-400">L</span>
                    </div>
                </div>

                <!-- Sub details di mobile -->
                <div class="mt-2 text-[9px] text-slate-400 sm:hidden flex flex-col gap-0.5 border-t border-slate-100 dark:border-white/5 pt-1.5">
                    <span class="truncate">{{ number_format($riwayats->total()) }} Mutasi</span>
                </div>

                <!-- Desktop Footer -->
                <div class="hidden sm:flex mt-3.5 pt-3 border-t border-slate-100 dark:border-white/5 items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    <span>Total Keseluruhan</span>
                    <span class="font-bold text-brand-primary">{{ number_format($riwayats->total()) }} Log Mutasi</span>
                </div>
            </div>

            <!-- Card 2: Transfer Pertamax -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 p-2.5 sm:p-5 shadow-sm relative overflow-hidden group hover:border-blue-500/40 transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] sm:text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider truncate">Pertamax</span>
                        <div class="hidden sm:flex w-8 h-8 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-1.5 sm:mt-3 flex items-baseline gap-1">
                        <span class="text-sm sm:text-3xl font-black text-blue-600 dark:text-blue-400 tabular-nums">
                            {{ number_format($totalPertamax, 0, ',', '.') }}
                        </span>
                        <span class="text-[10px] sm:text-xs font-bold text-slate-400">L</span>
                    </div>
                </div>

                <!-- Sub details di mobile -->
                <div class="mt-2 text-[9px] text-blue-500 dark:text-blue-400 sm:hidden flex flex-col gap-0.5 border-t border-slate-100 dark:border-white/5 pt-1.5 font-bold">
                    <span>Pertamax</span>
                </div>

                <!-- Desktop Footer -->
                <div class="hidden sm:flex mt-3.5 pt-3 border-t border-slate-100 dark:border-white/5 items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    <span>Jenis BBM</span>
                    <span class="inline-flex items-center gap-1 font-bold text-blue-600 dark:text-blue-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Pertamax
                    </span>
                </div>
            </div>

            <!-- Card 3: Transfer Pertamina Dex -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 p-2.5 sm:p-5 shadow-sm relative overflow-hidden group hover:border-emerald-500/40 transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] sm:text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider truncate">Dex</span>
                        <div class="hidden sm:flex w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-1.5 sm:mt-3 flex items-baseline gap-1">
                        <span class="text-sm sm:text-3xl font-black text-emerald-600 dark:text-emerald-400 tabular-nums">
                            {{ number_format($totalDex, 0, ',', '.') }}
                        </span>
                        <span class="text-[10px] sm:text-xs font-bold text-slate-400">L</span>
                    </div>
                </div>

                <!-- Sub details di mobile -->
                <div class="mt-2 text-[9px] text-emerald-500 dark:text-emerald-400 sm:hidden flex flex-col gap-0.5 border-t border-slate-100 dark:border-white/5 pt-1.5 font-bold">
                    <span>Dex</span>
                </div>

                <!-- Desktop Footer -->
                <div class="hidden sm:flex mt-3.5 pt-3 border-t border-slate-100 dark:border-white/5 items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    <span>Jenis BBM</span>
                    <span class="inline-flex items-center gap-1 font-bold text-emerald-600 dark:text-emerald-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Pertamina Dex
                    </span>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden">
            <!-- Table Header Info & Toolbar -->
            <div class="px-3.5 py-3 sm:px-5 sm:py-4 border-b border-slate-200 dark:border-white/10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2.5 sm:gap-3">
                <div class="flex items-center gap-2.5 sm:gap-3 min-w-0 w-full sm:w-auto">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-brand-primary/10 text-brand-primary dark:bg-white/10 dark:text-white flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="text-xs sm:text-base font-black text-slate-800 dark:text-white tracking-tight leading-snug">Riwayat Transfer Saldo</h2>
                        <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">Total {{ number_format($riwayats->total()) }} transaksi tercatat.</p>
                    </div>
                </div>

                <!-- Per Page Toolbar & Range Count -->
                <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto justify-between sm:justify-end">
                    <form action="{{ route('satker.kendaraans.laporan-transfer') }}" method="GET" class="flex items-center gap-1.5">
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        <span class="text-[11px] sm:text-xs font-semibold text-slate-500 dark:text-slate-400">Tampilkan:</span>
                        <x-per-page :current="request('per_page', 15)" />
                    </form>
                    <div class="text-[10px] sm:text-[11px] font-bold text-slate-500 dark:text-slate-400">
                        {{ $riwayats->firstItem() ?? 0 }}-{{ $riwayats->lastItem() ?? 0 }} dari {{ $riwayats->total() }} data
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 uppercase tracking-wider text-[10px] font-black">
                            <th class="px-4 py-3 text-center w-12">NO</th>
                            <th class="px-4 py-3">TANGGAL</th>
                            <th class="px-4 py-3">KENDARAAN (SUMBER)</th>
                            <th class="px-4 py-3 text-center">JENIS BBM</th>
                            <th class="px-2 py-3 text-center w-10"></th>
                            <th class="px-4 py-3">PERSONEL / TUJUAN</th>
                            <th class="px-4 py-3 text-right">JUMLAH</th>
                            <th class="px-4 py-3">KETERANGAN</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5 bg-white dark:bg-slate-900">
                        @forelse($riwayats as $riwayat)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                <!-- No -->
                                <td class="px-4 py-3.5 text-center font-mono font-bold text-slate-400 text-xs">
                                    {{ $loop->iteration + ($riwayats->currentPage() - 1) * $riwayats->perPage() }}
                                </td>

                                <!-- Tanggal -->
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <div class="font-bold text-slate-800 dark:text-slate-200 text-xs">
                                        {{ $riwayat->created_at->format('d/m/Y') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-mono mt-0.5">
                                        {{ $riwayat->created_at->format('H:i') }} WITA
                                    </div>
                                </td>

                                <!-- Kendaraan Sumber -->
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <div class="flex items-center gap-2.5">
                                        @if($riwayat->tipe_log == 'masuk' || ($riwayat->tipe_log == 'transfer' && !$riwayat->kendaraan_id))
                                            <div class="w-8 h-8 rounded-xl bg-rose-500/10 text-rose-500 border border-rose-500/20 flex items-center justify-center shrink-0">
                                                <i class="fas fa-gas-pump text-xs"></i>
                                            </div>
                                            <div>
                                                <div class="text-xs font-black text-rose-600 dark:text-rose-400">STOK PUSAT</div>
                                                <div class="text-[10px] text-slate-400">SUPER ADMIN</div>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20 flex items-center justify-center shrink-0">
                                                <i class="fas fa-car-side text-xs"></i>
                                            </div>
                                            <div>
                                                <div class="text-xs font-mono font-bold text-slate-800 dark:text-slate-100">
                                                    {{ $riwayat->kendaraan->no_polisi ?? '-' }}
                                                </div>
                                                <div class="text-[10px] text-slate-400">
                                                    {{ $riwayat->kendaraan->jenis_kendaraan ?? '-' }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Jenis BBM -->
                                <td class="px-4 py-3.5 whitespace-nowrap text-center">
                                    @php
                                        $jBbm = $riwayat->kendaraan->jenis_bbm ?? '-';
                                    @endphp
                                    @if($jBbm == 'Pertamina Dex')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            {{ $jBbm }}
                                        </span>
                                    @elseif($jBbm == 'Pertamax')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            {{ $jBbm }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-white/10">
                                            {{ $jBbm }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Arah Arrow -->
                                <td class="px-2 py-3.5 whitespace-nowrap text-center">
                                    <div class="w-7 h-7 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 dark:text-slate-500 mx-auto">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </div>
                                </td>

                                <!-- Personel / Tujuan -->
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <div class="flex items-center gap-2.5">
                                        @if($riwayat->tipe_log == 'masuk' || ($riwayat->tipe_log == 'transfer' && $riwayat->tujuan_kendaraan_id))
                                            <div class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 flex items-center justify-center shrink-0">
                                                <i class="fas fa-car-side text-xs"></i>
                                            </div>
                                            <div>
                                                <div class="text-xs font-mono font-bold text-slate-800 dark:text-slate-100">
                                                    {{ $riwayat->tujuanKendaraan->no_polisi ?? ($riwayat->kendaraan->no_polisi ?? '-') }}
                                                </div>
                                                <div class="text-[10px] text-slate-400">
                                                    {{ $riwayat->tujuanKendaraan->jenis_kendaraan ?? ($riwayat->kendaraan->jenis_kendaraan ?? '-') }}
                                                </div>
                                            </div>
                                        @elseif($riwayat->personel_id)
                                            <div class="w-8 h-8 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20 flex items-center justify-center shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-xs font-bold text-slate-800 dark:text-slate-100">
                                                    {{ $riwayat->personel->nama ?? '-' }}
                                                </div>
                                                <div class="text-[10px] text-slate-400 font-mono">
                                                    NRP: {{ $riwayat->personel->nrp ?? '-' }}
                                                </div>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 rounded-xl bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 flex items-center justify-center shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-xs font-bold text-rose-500">PUSAT (POTONGAN)</div>
                                                <div class="text-[10px] text-slate-400">Pengurangan Saldo</div>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Jumlah Volume -->
                                <td class="px-4 py-3.5 whitespace-nowrap text-right">
                                    <span class="font-mono font-black text-sm text-emerald-600 dark:text-emerald-400 tabular-nums">
                                        {{ number_format($riwayat->jumlah, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[10px] font-bold text-slate-400 ml-0.5">L</span>
                                </td>

                                <!-- Keterangan -->
                                <td class="px-4 py-3.5">
                                    <span class="text-xs text-slate-600 dark:text-slate-400 line-clamp-1 max-w-xs" title="{{ $riwayat->keterangan ?? '-' }}">
                                        {{ $riwayat->keterangan ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-14 h-14 bg-slate-100 dark:bg-slate-800 text-slate-400 rounded-2xl flex items-center justify-center mb-3">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Belum ada riwayat transfer</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Coba sesuaikan filter rentang tanggal atau lakukan transfer terlebih dahulu.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            @if($riwayats->hasPages())
                <div class="px-5 py-3.5 bg-slate-50/50 dark:bg-slate-800/30 border-t border-slate-200 dark:border-white/10">
                    {{ $riwayats->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
