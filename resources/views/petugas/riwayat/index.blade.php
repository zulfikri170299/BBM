<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-sm text-white shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 dark:text-white tracking-tight">Riwayat Pengisian BBM</h1>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-0.5">Histori pengisian BBM seluruh kendaraan dari semua Satuan Kerja.</p>
                </div>
            </div>

            <!-- Action Button: Cetak PDF -->
            <a href="{{ route('petugas.riwayat.print', request()->all()) }}" target="_blank"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white rounded-xl text-xs font-bold shadow-sm shadow-rose-600/20 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                <span>Cetak PDF</span>
            </a>
        </div>

        <!-- KPI Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Total Transaksi -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 p-4 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Transaksi</p>
                    <h3 class="text-xl font-black text-slate-800 dark:text-white mt-1">{{ number_format($stats['total_transaksi']) }}</h3>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Catatan riwayat</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-100 dark:border-indigo-500/20 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>

            <!-- Grand Total Pengisian -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 p-4 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Pengisian</p>
                    <h3 class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-1">
                        {{ number_format($stats['total_liter'], 0, ',', '.') }} <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Liter</span>
                    </h3>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Akumulasi volume</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-100 dark:border-emerald-500/20 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>

            <!-- Breakdown per BBM -->
            @foreach($summaryBbm as $jenis => $total)
                @php
                    $isPertamax = str_contains(strtoupper($jenis), 'PERTAMAX');
                    $badgeBg = $isPertamax ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-100 dark:border-blue-500/20' : 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border-cyan-100 dark:border-cyan-500/20';
                    $textColor = $isPertamax ? 'text-blue-600 dark:text-blue-400' : 'text-cyan-600 dark:text-cyan-400';
                @endphp
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 p-4 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ $jenis ?: 'BBM' }}</p>
                        <h3 class="text-xl font-black {{ $textColor }} mt-1">
                            {{ rtrim(rtrim(number_format($total, 2, ',', '.'), '0'), ',') }} <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Liter</span>
                        </h3>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Data terfilter</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl {{ $badgeBg }} flex items-center justify-center border shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                </div>
            @endforeach
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
                    <h3 class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider">Filter & Pencarian Riwayat</h3>
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
                    
                    @if(request('dari') || request('sampai') || request('satker_id') || request('search'))
                        <div class="h-4 w-px bg-slate-200 dark:border-white/10 mx-1"></div>
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            Filter Aktif
                        </span>
                        <a href="{{ route('petugas.riwayat.index') }}" class="text-[10px] font-bold text-rose-500 hover:text-rose-600 dark:text-rose-400 transition" title="Hapus Filter">
                            Hapus Filter
                        </a>
                    @endif
                </div>
            </div>

            <!-- Form: Exactly 1 Row Layout -->
            <form id="filter-form" method="GET" action="{{ route('petugas.riwayat.index') }}" class="p-4 sm:p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
                    <!-- Dari Tanggal (2 cols) -->
                    <div class="lg:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-0.5">
                            Dari Tanggal
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="text" id="filter_dari" placeholder="Dari Tgl" name="dari" value="{{ request('dari') }}"
                                style="padding-left: 2.35rem !important;"
                                class="flatpickr w-full h-10 !pl-9 pr-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-semibold text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        </div>
                    </div>

                    <!-- Sampai Tanggal (2 cols) -->
                    <div class="lg:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-0.5">
                            Sampai Tanggal
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="text" id="filter_sampai" placeholder="Sampai Tgl" name="sampai" value="{{ request('sampai') }}"
                                style="padding-left: 2.35rem !important;"
                                class="flatpickr w-full h-10 !pl-9 pr-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-semibold text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        </div>
                    </div>

                    <!-- Satker (3 cols) -->
                    <div class="lg:col-span-3">
                        <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-0.5">
                            Satker
                        </label>
                        <select name="satker_id" id="filter_satker_id" class="tom-select w-full">
                            <option value="">Semua Satker</option>
                            @foreach($satkers as $satker)
                                <option value="{{ $satker->id }}" {{ request('satker_id') == $satker->id ? 'selected' : '' }}>
                                    {{ $satker->nama_satker }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kata Kunci Pencarian (3 cols) -->
                    <div class="lg:col-span-3">
                        <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-0.5">
                            Kata Kunci Pencarian
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" placeholder="Nopol atau Nama Driver..." name="search" value="{{ request('search') }}"
                                style="padding-left: 2.35rem !important;"
                                class="w-full h-10 !pl-9 pr-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-semibold text-slate-800 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        </div>
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
                        <a href="{{ route('petugas.riwayat.index') }}"
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white">Daftar Transaksi BBM</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Menampilkan {{ $transaksis->firstItem() ?? 0 }}-{{ $transaksis->lastItem() ?? 0 }} dari total {{ $transaksis->total() }} catatan transaksi</p>
                    </div>
                </div>

                <!-- Per Page Selector Form -->
                <form action="{{ route('petugas.riwayat.index') }}" method="GET" class="flex items-center">
                    <x-per-page :current="request('per_page', 15)" />
                </form>
            </div>

            <!-- Table (Responsive without scroll on standard desktop) -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 dark:bg-slate-800/50 border-b border-slate-200 dark:border-white/10 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            <th scope="col" class="py-3 px-3 text-center w-12">No</th>
                            <th scope="col" class="py-3 px-3 text-left w-28 whitespace-nowrap">Tanggal & Waktu</th>
                            <th scope="col" class="py-3 px-3 text-left max-w-[130px]">Satker</th>
                            <th scope="col" class="py-3 px-3 text-left max-w-[110px]">Jenis Kendaraan</th>
                            <th scope="col" class="py-3 px-3 text-left w-24 whitespace-nowrap">Nopol</th>
                            <th scope="col" class="py-3 px-3 text-center w-24 whitespace-nowrap">Jenis BBM</th>
                            <th scope="col" class="py-3 px-3 text-left max-w-[140px]">Nama Driver / Ket</th>
                            <th scope="col" class="py-3 px-3 text-right w-24 whitespace-nowrap">Jumlah Liter</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($transaksis as $trx)
                            @php
                                $isPotong = ($trx->row_type ?? 'pengisian') === 'potong_saldo';
                            @endphp
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                                <!-- No -->
                                <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold border border-slate-200/80 dark:border-white/5">
                                        {{ $loop->iteration + ($transaksis->currentPage() - 1) * $transaksis->perPage() }}
                                    </span>
                                </td>

                                <!-- Tanggal -->
                                <td class="px-3 py-2.5 whitespace-nowrap">
                                    <div class="text-xs font-bold text-slate-800 dark:text-white leading-tight">
                                        {{ \Carbon\Carbon::parse($trx->tanggal)->setTimezone('Asia/Makassar')->format('d M Y') }}
                                    </div>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">
                                        {{ \Carbon\Carbon::parse($trx->tanggal)->setTimezone('Asia/Makassar')->format('H:i') }} WITA
                                    </div>
                                </td>

                                <!-- Satker -->
                                <td class="px-3 py-2.5 max-w-[130px]">
                                    @php
                                        $satker = $trx->satker ?? ($trx->kendaraan->satker ?? ($trx->personel->satker ?? null));
                                        $satkerNama = $satker->nama_satker ?? '-';
                                    @endphp
                                    <div class="flex items-center gap-1.5 truncate">
                                        <div class="w-5 h-5 rounded-md {{ $isPotong ? 'bg-gradient-to-br from-amber-500 to-orange-600' : 'bg-gradient-to-br from-indigo-500 to-indigo-600' }} flex items-center justify-center text-white font-bold text-[9px] shadow-sm shrink-0">
                                            {{ strtoupper(substr($satkerNama, 0, 2)) }}
                                        </div>
                                        <span class="text-xs font-bold text-slate-800 dark:text-white truncate" title="{{ $satkerNama }}">
                                            {{ $satkerNama }}
                                        </span>
                                    </div>
                                    @if($isPotong)
                                        <span class="inline-block text-[9px] font-bold text-amber-600 dark:text-amber-400 tracking-tight leading-none mt-0.5">
                                            POTONG SALDO
                                        </span>
                                    @endif
                                </td>

                                <!-- Jenis Kendaraan -->
                                <td class="px-3 py-2.5 max-w-[110px] truncate text-xs font-medium text-slate-700 dark:text-slate-300" title="{{ $trx->kendaraan?->jenis_kendaraan ?? '-' }}">
                                    {{ $trx->kendaraan?->jenis_kendaraan ?? '-' }}
                                </td>

                                <!-- Nopol -->
                                <td class="px-3 py-2.5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-900 text-amber-300 dark:bg-slate-950 dark:text-amber-400 text-xs font-mono font-bold tracking-wider border border-slate-700">
                                        {{ $trx->kendaraan?->no_polisi ?? $trx->nopol ?? '-' }}
                                    </span>
                                </td>

                                <!-- Jenis BBM -->
                                <td class="px-3 py-2.5 whitespace-nowrap text-center">
                                    @php
                                        $jenisBbm = $trx->jenis_bbm;
                                        $isPtx = str_contains(strtoupper($jenisBbm ?? ''), 'PERTAMAX');
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold {{ $isPtx ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20' : 'bg-cyan-50 text-cyan-700 dark:bg-cyan-500/10 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-500/20' }}">
                                        {{ $jenisBbm ?? '-' }}
                                    </span>
                                </td>

                                <!-- Driver / Keterangan -->
                                <td class="px-3 py-2.5 max-w-[140px]">
                                    @if($isPotong)
                                        <span class="text-xs text-slate-500 dark:text-slate-400 italic block truncate" title="{{ $trx->keterangan }}">
                                            {{ $trx->keterangan ?: 'Potong Saldo Otomatis' }}
                                        </span>
                                    @else
                                        @php
                                            $driverNama = $trx->nama_driver ?? ($trx->personel->nama ?? '-');
                                        @endphp
                                        <span class="text-xs font-medium text-slate-700 dark:text-slate-300 block truncate" title="{{ $driverNama }}">
                                            {{ $driverNama }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Jumlah Liter -->
                                <td class="px-3 py-2.5 whitespace-nowrap text-right">
                                    <div class="inline-flex items-baseline gap-1">
                                        <span class="text-xs font-black {{ $isPotong ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                            {{ $isPotong ? '-' : '' }}{{ number_format($trx->liter, 0, ',', '.') }}
                                        </span>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase">L</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800/60 rounded-2xl flex items-center justify-center mb-4 text-slate-400">
                                            <svg class="w-8 h-8 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300">Belum ada riwayat pengisian BBM</h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Gunakan filter di atas untuk menyesuaikan periode atau Satker.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-slate-50/75 dark:bg-slate-800/50 border-t border-slate-200 dark:border-white/10">
                        <tr>
                            <td colspan="7" class="px-3 py-3 text-right text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                                Total Volume Pada Data Terfilter
                            </td>
                            <td class="px-3 py-3 text-right whitespace-nowrap">
                                <div class="inline-flex items-baseline gap-1">
                                    <span class="text-sm font-black text-indigo-600 dark:text-indigo-400">
                                        {{ number_format($stats['total_liter'], 0, ',', '.') }}
                                    </span>
                                    <span class="text-[10px] font-bold text-indigo-500 dark:text-indigo-400/80 uppercase">Liter</span>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Pagination -->
            @if($transaksis->hasPages())
                <div class="px-5 py-3.5 bg-slate-50/70 dark:bg-slate-800/40 border-t border-slate-200 dark:border-white/10">
                    {{ $transaksis->links() }}
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

                const dariEl = document.getElementById('filter_dari');
                const sampaiEl = document.getElementById('filter_sampai');
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