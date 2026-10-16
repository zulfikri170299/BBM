<x-app-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6">
        <!-- Page Title & Action Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-800 dark:text-white tracking-tight">
                    Laporan Hutang BBM
                </h1>
                <p class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">
                    Daftar riwayat hutang bon BBM satker (Lunas & Belum Lunas).
                </p>
            </div>

            <a href="{{ route('satker.laporan-hutang.print', request()->all()) }}" target="_blank"
                class="inline-flex items-center justify-center px-5 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white font-bold rounded-xl shadow-sm shadow-rose-600/20 transition-all gap-2 uppercase tracking-wider text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak PDF</span>
            </a>
        </div>

        <!-- KPI / Summary Cards -->
        @php
            $totBelum = $totalPertamaxBelum + $totalDexBelum;
            $totSudah = $totalPertamaxSudah + $totalDexSudah;
            $totSemua = $totBelum + $totSudah;
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Belum Lunas (Warning / Rose) -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 p-5 shadow-sm flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                        <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Hutang Belum Lunas</p>
                    </div>
                    <h3 class="text-2xl font-black text-rose-600 dark:text-rose-400 mt-1.5">
                        {{ number_format($totBelum, 0, ',', '.') }} <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Liter</span>
                    </h3>
                    <div class="flex items-center gap-2 mt-1 text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                        <span>Pertamax: <b class="text-rose-600 dark:text-rose-400">{{ number_format($totalPertamaxBelum, 0) }}L</b></span>
                        <span>•</span>
                        <span>Dex: <b class="text-rose-600 dark:text-rose-400">{{ number_format($totalDexBelum, 0) }}L</b></span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center border border-rose-100 dark:border-rose-500/20 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <!-- Sudah Lunas (Success / Emerald) -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 p-5 shadow-sm flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Hutang Sudah Lunas</p>
                    </div>
                    <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1.5">
                        {{ number_format($totSudah, 0, ',', '.') }} <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Liter</span>
                    </h3>
                    <div class="flex items-center gap-2 mt-1 text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                        <span>Pertamax: <b class="text-emerald-600 dark:text-emerald-400">{{ number_format($totalPertamaxSudah, 0) }}L</b></span>
                        <span>•</span>
                        <span>Dex: <b class="text-emerald-600 dark:text-emerald-400">{{ number_format($totalDexSudah, 0) }}L</b></span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-100 dark:border-emerald-500/20 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <!-- Total Keseluruhan (Indigo) -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 p-5 shadow-sm flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Volume Bon</p>
                    </div>
                    <h3 class="text-2xl font-black text-indigo-600 dark:text-indigo-400 mt-1.5">
                        {{ number_format($totSemua, 0, ',', '.') }} <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Liter</span>
                    </h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 font-medium">
                        Total {{ $hutangs->total() }} data bon tercatat
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-100 dark:border-indigo-500/20 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- 1-Row Compact Filter & Search Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden">
            <!-- Header with Title & Presets in 1 sleek bar -->
            <div class="px-5 py-3 bg-slate-50/70 dark:bg-slate-800/40 border-b border-slate-200 dark:border-white/10 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-lg flex items-center justify-center border border-indigo-100 dark:border-indigo-500/20">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                    </div>
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Filter Laporan Hutang</h3>
                </div>

                <!-- Presets & Filter Status in Header -->
                <div class="flex items-center gap-1.5">
                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mr-1">Preset:</span>
                    <button type="button" onclick="setPreset('today')"
                        class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-white dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 text-slate-600 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 border border-slate-200 dark:border-white/10 shadow-sm transition">
                        Hari Ini
                    </button>
                    <button type="button" onclick="setPreset('this_month')"
                        class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-white dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 text-slate-600 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 border border-slate-200 dark:border-white/10 shadow-sm transition">
                        Bulan Ini
                    </button>
                    <button type="button" onclick="setPreset('this_year')"
                        class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-white dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 text-slate-600 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 border border-slate-200 dark:border-white/10 shadow-sm transition">
                        Tahun Ini
                    </button>
                    
                    @if(request('start_date') || request('end_date') || request('status'))
                        <div class="h-4 w-px bg-slate-200 dark:border-white/10 mx-1"></div>
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            Filter Aktif
                        </span>
                        <a href="{{ route('satker.laporan-hutang.index') }}" class="text-[10px] font-bold text-rose-500 hover:text-rose-600 dark:text-rose-400 transition" title="Hapus Filter">
                            Hapus Filter
                        </a>
                    @endif
                </div>
            </div>

            <!-- Form: Exactly 1 Row Layout -->
            <form id="filter-form" method="GET" action="{{ route('satker.laporan-hutang.index') }}" class="p-4 sm:p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
                    <!-- Dari Tanggal (Bon) (3 cols) -->
                    <div class="lg:col-span-3">
                        <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-0.5">
                            Dari Tanggal (Bon)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="text" id="start_date" placeholder="Dari Tgl" name="start_date" value="{{ request('start_date') }}"
                                style="padding-left: 2.35rem !important;"
                                class="flatpickr w-full h-10 !pl-9 pr-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-semibold text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        </div>
                    </div>

                    <!-- Sampai Tanggal (Bon) (3 cols) -->
                    <div class="lg:col-span-3">
                        <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-0.5">
                            Sampai Tanggal (Bon)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="text" id="end_date" placeholder="Sampai Tgl" name="end_date" value="{{ request('end_date') }}"
                                style="padding-left: 2.35rem !important;"
                                class="flatpickr w-full h-10 !pl-9 pr-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-semibold text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        </div>
                    </div>

                    <!-- Status (4 cols) -->
                    <div class="lg:col-span-4">
                        <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-0.5">
                            Status Pembayaran
                        </label>
                        <select name="status" id="status" class="tom-select w-full">
                            <option value="">Semua Status</option>
                            <option value="sudah_dibayar" {{ request('status') === 'sudah_dibayar' ? 'selected' : '' }}>Lunas</option>
                            <option value="belum_dibayar" {{ request('status') === 'belum_dibayar' ? 'selected' : '' }}>Belum Lunas</option>
                        </select>
                    </div>

                    <!-- Tombol Filter & Reset (2 cols) -->
                    <div class="lg:col-span-2 flex items-center gap-1.5">
                        <button type="submit"
                            class="flex-1 h-10 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs font-bold shadow-sm shadow-indigo-600/20 transition-all flex items-center justify-center gap-1.5 px-3">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('satker.laporan-hutang.index') }}"
                            class="h-10 px-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 active:scale-95 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-bold border border-slate-200 dark:border-white/10 transition-all flex items-center justify-center gap-1"
                            title="Reset Filter">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Reset</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden">
            <!-- Table Header Info & Per-Page Toolbar -->
            <div class="px-5 py-3.5 bg-slate-50/70 dark:bg-slate-800/40 border-b border-slate-200 dark:border-white/10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center border border-indigo-100 dark:border-indigo-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-white">Daftar Riwayat Hutang BBM Satker</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                            Menampilkan {{ $hutangs->firstItem() ?? 0 }}-{{ $hutangs->lastItem() ?? 0 }} dari total {{ $hutangs->total() }} catatan hutang
                        </p>
                    </div>
                </div>

                <!-- Per Page Selector Form -->
                <form action="{{ route('satker.laporan-hutang.index') }}" method="GET" class="flex items-center">
                    <x-per-page :current="request('per_page', 15)" />
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/75 dark:bg-slate-800/50 border-b border-slate-200 dark:border-white/10 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            <th scope="col" class="py-3 px-3.5 text-center w-12">No</th>
                            <th scope="col" class="py-3 px-3.5 text-left">Tgl Bon</th>
                            <th scope="col" class="py-3 px-3.5 text-left">Tgl Bayar</th>
                            <th scope="col" class="py-3 px-3.5 text-left">Kendaraan</th>
                            <th scope="col" class="py-3 px-3.5 text-left">Driver</th>
                            <th scope="col" class="py-3 px-3.5 text-center">Jumlah</th>
                            <th scope="col" class="py-3 px-3.5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($hutangs as $index => $hutang)
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                                <!-- No -->
                                <td class="px-3.5 py-2.5 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold border border-slate-200/80 dark:border-white/5">
                                        {{ ($hutangs->currentPage() - 1) * $hutangs->perPage() + $index + 1 }}
                                    </span>
                                </td>

                                <!-- Tgl Bon -->
                                <td class="px-3.5 py-2.5">
                                    <div class="text-xs font-bold text-slate-800 dark:text-white leading-tight">
                                        {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->translatedFormat('d M Y') }}
                                    </div>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400 font-medium mt-0.5">
                                        {{ $hutang->petugas->name ?? 'spbp' }}
                                    </div>
                                </td>

                                <!-- Tgl Bayar -->
                                <td class="px-3.5 py-2.5">
                                    @if($hutang->tanggal_bayar)
                                        <div class="text-xs font-bold text-slate-800 dark:text-white leading-tight">
                                            {{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->translatedFormat('d M Y') }}
                                        </div>
                                        <div class="text-[10px] text-slate-500 dark:text-slate-400 font-medium mt-0.5">
                                            {{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->format('H:i') }} WITA
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 border border-rose-200 dark:border-rose-500/20">
                                            Belum Dibayar
                                        </span>
                                    @endif
                                </td>

                                <!-- Kendaraan -->
                                <td class="px-3.5 py-2.5">
                                    <div class="flex items-center gap-1.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-900 text-amber-300 dark:bg-slate-950 dark:text-amber-400 text-xs font-mono font-bold tracking-wider border border-slate-700">
                                            {{ $hutang->nopol }}
                                        </span>
                                    </div>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400 font-medium uppercase mt-0.5">
                                        {{ $hutang->jenis_kendaraan ?? '-' }}
                                    </div>
                                </td>

                                <!-- Driver -->
                                <td class="px-3.5 py-2.5 text-xs font-bold text-slate-800 dark:text-white">
                                    {{ $hutang->nama_driver ?? '-' }}
                                </td>

                                <!-- Jumlah -->
                                <td class="px-3.5 py-2.5 text-center">
                                    @php
                                        $isPtx = str_contains(strtoupper($hutang->jenis_bbm ?? ''), 'PERTAMAX');
                                    @endphp
                                    <div class="text-sm font-black text-slate-800 dark:text-white leading-tight">
                                        {{ number_format($hutang->jumlah_bon, 0, ',', '.') }} <span class="text-[10px] font-bold text-slate-400 uppercase">L</span>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-bold mt-0.5 {{ $isPtx ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20' : 'bg-cyan-50 text-cyan-700 dark:bg-cyan-500/10 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-500/20' }}">
                                        {{ $hutang->jenis_bbm }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="px-3.5 py-2.5 text-center">
                                    @if($hutang->status === 'sudah_dibayar')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                            <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Lunas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 border border-rose-200 dark:border-rose-500/20 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                            Belum Lunas
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800/60 rounded-2xl flex items-center justify-center mb-4 text-slate-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300">Tidak ada data hutang</h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Data riwayat bon BBM untuk kriteria filter ini tidak ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    <!-- Table Footer: Clean Summary -->
                    @if($hutangs->count() > 0)
                        <tfoot class="bg-slate-50/75 dark:bg-slate-800/50 border-t border-slate-200 dark:border-white/10 text-xs font-bold text-slate-600 dark:text-slate-400">
                            <!-- Sudah Lunas -->
                            <tr>
                                <td colspan="5" class="px-4 py-2.5 text-right uppercase tracking-wider text-[10px]">
                                    Total Sudah Dibayar (Lunas)
                                </td>
                                <td class="px-4 py-2.5 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-black">
                                            {{ number_format($totalPertamaxSudah, 0) }}L <span class="text-[9px] font-bold text-slate-400">Ptx</span>
                                        </span>
                                        <span class="text-slate-300 dark:text-white/20">|</span>
                                        <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-black">
                                            {{ number_format($totalDexSudah, 0) }}L <span class="text-[9px] font-bold text-slate-400">Dex</span>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-2.5 text-center">
                                    <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($totSudah, 0) }} L</span>
                                </td>
                            </tr>
                            <!-- Belum Lunas -->
                            <tr class="border-t border-slate-200/60 dark:border-white/5">
                                <td colspan="5" class="px-4 py-2.5 text-right uppercase tracking-wider text-[10px]">
                                    Total Belum Dibayar (Hutang)
                                </td>
                                <td class="px-4 py-2.5 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1 text-rose-600 dark:text-rose-400 font-black">
                                            {{ number_format($totalPertamaxBelum, 0) }}L <span class="text-[9px] font-bold text-slate-400">Ptx</span>
                                        </span>
                                        <span class="text-slate-300 dark:text-white/20">|</span>
                                        <span class="inline-flex items-center gap-1 text-rose-600 dark:text-rose-400 font-black">
                                            {{ number_format($totalDexBelum, 0) }}L <span class="text-[9px] font-bold text-slate-400">Dex</span>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-2.5 text-center">
                                    <span class="text-[10px] font-bold text-rose-600 dark:text-rose-400">{{ number_format($totBelum, 0) }} L</span>
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>

            <!-- Pagination -->
            @if($hutangs->hasPages())
                <div class="px-5 py-3.5 bg-slate-50/70 dark:bg-slate-800/40 border-t border-slate-200 dark:border-white/10">
                    {{ $hutangs->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function setPreset(type) {
                const today = new Date();
                const yyyy = today.getFullYear();
                const mm = String(today.getMonth() + 1).padStart(2, '0');
                const dd = String(today.getDate()).padStart(2, '0');
                let start = '', end = '';

                if (type === 'today') {
                    start = `${yyyy}-${mm}-${dd}`;
                    end = `${yyyy}-${mm}-${dd}`;
                } else if (type === 'this_month') {
                    const lastDay = new Date(yyyy, today.getMonth() + 1, 0).getDate();
                    start = `${yyyy}-${mm}-01`;
                    end = `${yyyy}-${mm}-${String(lastDay).padStart(2, '0')}`;
                } else if (type === 'this_year') {
                    start = `${yyyy}-01-01`;
                    end = `${yyyy}-12-31`;
                }

                const dariEl = document.getElementById('start_date');
                const sampaiEl = document.getElementById('end_date');
                if (dariEl && dariEl._flatpickr) {
                    dariEl._flatpickr.setDate(start);
                } else if (dariEl) {
                    dariEl.value = start;
                }
                if (sampaiEl && sampaiEl._flatpickr) {
                    sampaiEl._flatpickr.setDate(end);
                } else if (sampaiEl) {
                    sampaiEl.value = end;
                }
                document.getElementById('filter-form').submit();
            }
        </script>
    @endpush
</x-app-layout>
