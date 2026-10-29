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
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Riwayat Pengisian BBM</h1>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-0.5">Histori pengisian BBM seluruh kendaraan dari semua Satuan Kerja.</p>
                </div>
            </div>

            <!-- Action Button: Cetak PDF -->
            <a href="{{ route('admin.riwayat.print', request()->all()) }}" target="_blank"
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
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
            <!-- Total Transaksi -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 p-4 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Transaksi</p>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white mt-1">{{ number_format($stats['total_transaksi']) }}</h3>
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
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm relative z-30">
            <!-- Header with Title & Presets in 1 sleek bar -->
            <div class="px-5 py-3 bg-slate-50/70 dark:bg-slate-800/40 border-b border-slate-200 dark:border-white/10 flex flex-wrap items-center justify-between gap-3 rounded-t-2xl">
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
                        <a href="{{ route('admin.riwayat.index') }}" class="text-[10px] font-bold text-rose-500 hover:text-rose-600 dark:text-rose-400 transition" title="Hapus Filter">
                            Hapus Filter
                        </a>
                    @endif
                </div>
            </div>

            <!-- Form: Exactly 1 Row Layout -->
            <form id="filter-form" method="GET" action="{{ route('admin.riwayat.index') }}" class="p-4 sm:p-5">
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
                        <x-filter-select 
                            name="satker_id" 
                            :value="request('satker_id')" 
                            placeholder="Semua Satker" 
                            search-placeholder="Cari nama satker..." 
                            icon="building" 
                            :options="$satkers" 
                        />
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
                        <a href="{{ route('admin.riwayat.index') }}"
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
            <!-- Table Header Info & Per-Page Toolbar (Always 1 Single Row) -->
            <div class="px-4 py-3 sm:px-5 sm:py-3.5 bg-slate-50/70 dark:bg-slate-800/40 border-b border-slate-200 dark:border-white/10 flex items-center justify-between gap-2.5 sm:gap-4">
                <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                    <div class="w-8 h-8 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center border border-indigo-100 dark:border-indigo-500/20 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white truncate">Daftar Transaksi BBM</h3>
                        <p class="text-[10px] sm:text-[11px] text-slate-500 dark:text-slate-400 font-medium truncate">Menampilkan {{ $transaksis->firstItem() ?? 0 }}-{{ $transaksis->lastItem() ?? 0 }} dari total {{ $transaksis->total() }} catatan transaksi</p>
                    </div>
                </div>

                <!-- Per Page Selector Form (Sejajar di Mobile & Desktop) -->
                <div class="shrink-0">
                    <form action="{{ route('admin.riwayat.index') }}" method="GET" class="flex items-center">
                        <x-per-page :current="request('per_page', 15)" />
                    </form>
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
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
                            <th scope="col" class="py-3 px-3 text-center w-20 whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($transaksis as $trx)
                            @php
                                $isPotong = ($trx->row_type ?? 'pengisian') === 'potong_saldo';
                                $jenisBbm = $trx->jenis_bbm;
                                $isPtx = str_contains(strtoupper($jenisBbm ?? ''), 'PERTAMAX');
                                $satker = $trx->satker ?? ($trx->kendaraan->satker ?? ($trx->personel->satker ?? null));
                                $satkerNama = $satker->nama_satker ?? '-';
                                $nopol = $trx->kendaraan?->no_polisi ?? ($trx->nopol ?? '-');
                                $jenisKendaraan = $trx->kendaraan?->jenis_kendaraan ?? '-';
                                $roda = $trx->kendaraan?->roda ?? '';
                                $tglStr = \Carbon\Carbon::parse($trx->tanggal)->setTimezone('Asia/Makassar')->format('d M Y, H:i') . ' WITA';
                                $literStr = ($isPotong ? '-' : '') . number_format($trx->liter, 0, ',', '.') . ' Liter';
                                $driverStr = $isPotong ? 'Pemotongan Saldo' : ($trx->nama_driver ?? ($trx->personel->nama ?? '-'));
                                $petugasStr = !$isPotong ? ($trx->petugas?->name ?? 'Petugas SPBP') : ($trx->user?->name ?? 'Admin');
                                $ketStr = $trx->keterangan ?? ($isPotong ? 'Pemotongan saldo otomatis' : '-');
                            @endphp
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                                <!-- No -->
                                <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold border border-slate-200/80 dark:border-white/5">
                                        {{ $loop->iteration + ($transaksis->currentPage() - 1) * $transaksis->perPage() }}
                                    </span>
                                </td>

                                <!-- Tanggal & Waktu -->
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
                                <td class="px-3 py-2.5 max-w-[110px] truncate text-xs font-medium text-slate-700 dark:text-slate-300" title="{{ $jenisKendaraan }}">
                                    {{ $jenisKendaraan }}
                                </td>

                                <!-- Nopol -->
                                <td class="px-3 py-2.5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-900 text-amber-300 dark:bg-slate-950 dark:text-amber-400 text-xs font-mono font-bold tracking-wider border border-slate-700">
                                        {{ $nopol }}
                                    </span>
                                </td>

                                <!-- Jenis BBM -->
                                <td class="px-3 py-2.5 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold {{ $isPtx ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20' : 'bg-cyan-50 text-cyan-700 dark:bg-cyan-500/10 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-500/20' }}">
                                        {{ $jenisBbm ?? '-' }}
                                    </span>
                                </td>

                                <!-- Nama Driver / Keterangan -->
                                <td class="px-3 py-2.5 max-w-[140px]">
                                    @if($isPotong)
                                        <span class="text-xs text-slate-500 dark:text-slate-400 italic block truncate" title="{{ $ketStr }}">
                                            {{ $ketStr }}
                                        </span>
                                    @else
                                        <span class="text-xs font-medium text-slate-700 dark:text-slate-300 block truncate" title="{{ $driverStr }}">
                                            {{ $driverStr }}
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

                                <!-- Aksi (Detail & Batal) -->
                                <td class="px-3 py-2.5 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        @if(!$isPotong)
                                            <form id="delete-form-{{ $trx->id }}"
                                                action="{{ route('admin.riwayat.destroy', $trx->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="topup_password_confirm" id="pwd-{{ $trx->id }}">
                                                <button type="button"
                                                    class="w-7 h-7 inline-flex items-center justify-center text-rose-500 hover:text-white bg-rose-50 hover:bg-rose-600 dark:bg-rose-950/40 dark:hover:bg-rose-600 border border-rose-200/80 dark:border-rose-800/40 rounded-lg transition-all active:scale-95 shadow-sm"
                                                    onclick="confirmDelete('{{ $trx->id }}', '{{ number_format($trx->liter, 0, ',', '.') }}')"
                                                    title="Batalkan & Kembalikan Saldo">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-white/5" title="Transaksi pemotongan saldo tidak dapat dibatalkan manual">
                                                Fixed
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800/60 rounded-2xl flex items-center justify-center mb-4 text-slate-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Mobile Table View (Visible on mobile screens) -->
            <div class="block md:hidden w-full overflow-hidden">
                <table class="w-full text-left text-xs border-collapse table-fixed">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 uppercase tracking-wider text-[10px] font-black">
                            <th class="py-2.5 px-2 w-9 text-center">No</th>
                            <th class="py-2.5 px-2 w-[32%]">Tanggal</th>
                            <th class="py-2.5 px-2">Satker & Kendaraan</th>
                            <th class="py-2.5 px-1.5 text-center w-16">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($transaksis as $trx)
                            @php
                                $isPotong = ($trx->row_type ?? 'pengisian') === 'potong_saldo';
                                $jenisBbm = $trx->jenis_bbm;
                                $isPtx = str_contains(strtoupper($jenisBbm ?? ''), 'PERTAMAX');
                                $satker = $trx->satker ?? ($trx->kendaraan->satker ?? ($trx->personel->satker ?? null));
                                $satkerNama = $satker->nama_satker ?? '-';
                                $nopol = $trx->kendaraan?->no_polisi ?? ($trx->nopol ?? '-');
                                $jenisKendaraan = $trx->kendaraan?->jenis_kendaraan ?? '-';
                                $roda = $trx->kendaraan?->roda ?? '';
                                $tglStr = \Carbon\Carbon::parse($trx->tanggal)->setTimezone('Asia/Makassar')->format('d M Y, H:i') . ' WITA';
                                $literStr = ($isPotong ? '-' : '') . number_format($trx->liter, 0, ',', '.') . ' Liter';
                                $driverStr = $isPotong ? 'Pemotongan Saldo' : ($trx->nama_driver ?? ($trx->personel->nama ?? '-'));
                                $petugasStr = !$isPotong ? ($trx->petugas?->name ?? 'Petugas SPBP') : ($trx->user?->name ?? 'Admin');
                                $ketStr = $trx->keterangan ?? ($isPotong ? 'Pemotongan saldo otomatis' : '-');
                            @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="py-2.5 px-2 text-center">
                                    <span class="font-bold text-slate-500 dark:text-slate-400 text-xs">
                                        {{ $loop->iteration + ($transaksis->currentPage() - 1) * $transaksis->perPage() }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-2 overflow-hidden">
                                    <span class="font-bold text-slate-800 dark:text-white truncate block leading-tight text-xs">
                                        {{ \Carbon\Carbon::parse($trx->tanggal)->setTimezone('Asia/Makassar')->format('d M Y') }}
                                    </span>
                                    <span class="text-[10px] text-slate-500 dark:text-slate-400 font-medium block mt-0.5">
                                        {{ \Carbon\Carbon::parse($trx->tanggal)->setTimezone('Asia/Makassar')->format('H:i') }} WITA
                                    </span>
                                </td>
                                <td class="py-2.5 px-2 overflow-hidden">
                                    <div class="flex flex-col min-w-0">
                                        <!-- Satker & Badge -->
                                        <div class="flex items-center gap-1.5 truncate">
                                            <span class="text-[11px] font-black text-indigo-600 dark:text-indigo-400 truncate" title="{{ $satkerNama }}">
                                                {{ $satkerNama }}
                                            </span>
                                            @if($isPotong)
                                                <span class="px-1 py-0.2 rounded text-[8px] font-black uppercase tracking-wider bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/30 shrink-0">
                                                    POTONG
                                                </span>
                                            @endif
                                        </div>
                                        <!-- Nopol, Kendaraan, dan Liter -->
                                        <div class="flex items-center gap-1.5 mt-0.5 whitespace-nowrap overflow-hidden">
                                            <span class="font-black text-slate-900 dark:text-white tracking-wide font-mono text-[11px]">{{ $nopol }}</span>
                                            <span class="text-[10px] text-slate-400 truncate">({{ $jenisKendaraan }})</span>
                                        </div>
                                        <div class="mt-0.5">
                                            <span class="text-[11px] font-black {{ $isPotong ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                                {{ $isPotong ? '-' : '' }}{{ number_format($trx->liter, 0, ',', '.') }} L
                                            </span>
                                            <span class="text-[10px] font-bold text-slate-400 ml-1">({{ $jenisBbm ?? '-' }})</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2.5 px-1.5 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center gap-1">
                                        <!-- Detail Button -->
                                        <button type="button"
                                            @click="$dispatch('open-detail-transaksi', {
                                                id: {{ $trx->id ?? 0 }},
                                                title: '{{ $isPotong ? 'Potong Saldo' : 'Pengisian BBM' }}',
                                                badge: '{{ $isPotong ? 'POTONG SALDO' : 'PENGISIAN BBM' }}',
                                                is_potong: {{ $isPotong ? 'true' : 'false' }},
                                                is_ptx: {{ $isPtx ? 'true' : 'false' }},
                                                tanggal: '{{ addslashes($tglStr) }}',
                                                nopol: '{{ addslashes($nopol) }}',
                                                jenis_kendaraan: '{{ addslashes($jenisKendaraan) }}',
                                                roda: '{{ addslashes($roda) }}',
                                                satker: '{{ addslashes($satkerNama) }}',
                                                jenis_bbm: '{{ addslashes($jenisBbm ?? '-') }}',
                                                liter: '{{ addslashes($literStr) }}',
                                                liter_raw: '{{ number_format($trx->liter, 0, ',', '.') }}',
                                                driver: '{{ addslashes($driverStr) }}',
                                                petugas: '{{ addslashes($petugasStr) }}',
                                                keterangan: '{{ addslashes($ketStr) }}'
                                            })"
                                            class="w-7 h-7 inline-flex items-center justify-center text-emerald-600 dark:text-emerald-400 hover:text-white dark:hover:text-white bg-emerald-500/15 hover:bg-emerald-600 border border-emerald-500/30 rounded-lg transition-all active:scale-95 shadow-sm"
                                            title="Detail Transaksi">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>

                                        <!-- Delete / Batalkan Button -->
                                        @if(!$isPotong)
                                            <button type="button"
                                                class="w-7 h-7 inline-flex items-center justify-center text-rose-500 hover:text-white bg-rose-50 hover:bg-rose-600 dark:bg-rose-950/40 dark:hover:bg-rose-600 border border-rose-200/80 dark:border-rose-800/40 rounded-lg transition-all active:scale-95 shadow-sm"
                                                onclick="confirmDelete('{{ $trx->id }}', '{{ number_format($trx->liter, 0, ',', '.') }}')"
                                                title="Batalkan & Kembalikan Saldo">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-500">
                                    <p class="font-bold text-xs">Belum ada riwayat transaksi</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
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

    <!-- Modal Detail Transaksi -->
    <div x-data="{
            showDetail: false,
            detail: {},
            openDetail(data) {
                this.detail = data;
                this.showDetail = true;
                document.body.style.overflow = 'hidden';
            },
            closeDetail() {
                this.showDetail = false;
                document.body.style.overflow = '';
            }
        }"
        @open-detail-transaksi.window="openDetail($event.detail)"
        @turbo:before-cache.window="closeDetail()"
        @keydown.escape.window="closeDetail()">

        <template x-teleport="body">
            <div x-show="showDetail" style="display: none;" class="fixed inset-0 z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4">
                <!-- Backdrop -->
                <div x-show="showDetail"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm"
                    @click="closeDetail()"></div>

                <!-- Modal Dialog -->
                <div x-show="showDetail"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95 sm:translate-y-0"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-8 sm:scale-95 sm:translate-y-0"
                    class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-t-3xl sm:rounded-2xl shadow-2xl w-full max-w-lg flex flex-col max-h-[90vh] overflow-hidden"
                    @click.stop>

                    <!-- Header -->
                    <div class="px-5 py-4 border-b border-slate-200 dark:border-white/10 flex items-center justify-between bg-slate-50 dark:bg-slate-800/50 shrink-0">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                                :class="detail.is_potong ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400' : 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'">
                                <template x-if="detail.is_potong">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </template>
                                <template x-if="!detail.is_potong">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                    </svg>
                                </template>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-base font-black text-slate-800 dark:text-white truncate" x-text="detail.title || 'Detail Transaksi'"></h3>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-mono font-extrabold uppercase shrink-0"
                                        :class="detail.is_potong ? 'bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/30' : 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30'"
                                        x-text="detail.badge"></span>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5" x-text="detail.tanggal"></p>
                            </div>
                        </div>
                        <button type="button" @click="closeDetail()" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-white rounded-xl transition shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-5 overflow-y-auto space-y-4">
                        <!-- Volume Highlight Card -->
                        <div class="p-4 rounded-2xl border"
                            :class="detail.is_potong ? 'bg-rose-50/60 dark:bg-rose-950/20 border-rose-200 dark:border-rose-900/40' : 'bg-emerald-50/60 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-900/40'">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] font-black tracking-wider uppercase"
                                        :class="detail.is_potong ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400'">
                                        <span x-text="detail.is_potong ? 'Volume Saldo Terpotong' : 'Volume Pengisian BBM'"></span>
                                    </span>
                                    <div class="flex items-baseline gap-1 mt-1">
                                        <span class="text-2xl font-black font-mono"
                                            :class="detail.is_potong ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400'"
                                            x-text="detail.liter"></span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Jenis BBM</span>
                                    <span class="inline-block mt-1 px-2.5 py-1 rounded-lg text-xs font-black uppercase tracking-wider"
                                        :class="detail.is_ptx ? 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20' : 'bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border border-cyan-500/20'"
                                        x-text="detail.jenis_bbm"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Grid -->
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-white/5">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Satuan Kerja</span>
                                <span class="font-bold text-slate-800 dark:text-white block mt-1 truncate" x-text="detail.satker"></span>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-white/5">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">No. Polisi</span>
                                <span class="font-black text-slate-900 dark:text-white font-mono text-xs block mt-1" x-text="detail.nopol"></span>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-white/5">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Jenis Kendaraan</span>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <span class="font-bold text-slate-800 dark:text-white truncate" x-text="detail.jenis_kendaraan"></span>
                                    <span class="text-[10px] font-mono px-1 rounded bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300" x-show="detail.roda" x-text="detail.roda"></span>
                                </div>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-white/5">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Pengemudi / Driver</span>
                                <span class="font-bold text-slate-800 dark:text-white block mt-1 truncate" x-text="detail.driver"></span>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-white/5">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Petugas / Operator</span>
                                <span class="font-bold text-slate-800 dark:text-white block mt-1 truncate" x-text="detail.petugas"></span>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-white/5">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Waktu Transaksi</span>
                                <span class="font-bold text-slate-800 dark:text-white block mt-1 text-[11px]" x-text="detail.tanggal"></span>
                            </div>
                            <div class="col-span-2 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-white/5">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Keterangan / Catatan</span>
                                <p class="text-xs text-slate-700 dark:text-slate-300 mt-1 leading-relaxed font-medium" x-text="detail.keterangan"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-5 py-3 border-t border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-between shrink-0">
                        <template x-if="!detail.is_potong">
                            <button type="button"
                                @click="closeDetail(); confirmDelete(detail.id, detail.liter_raw)"
                                class="px-3.5 py-2 bg-rose-50 hover:bg-rose-600 dark:bg-rose-950/40 dark:hover:bg-rose-600 text-rose-600 hover:text-white dark:text-rose-400 dark:hover:text-white border border-rose-200 dark:border-rose-800/40 text-xs font-bold rounded-xl transition active:scale-95 flex items-center gap-1.5 shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span>Batalkan Transaksi</span>
                            </button>
                        </template>
                        <template x-if="detail.is_potong">
                            <div></div>
                        </template>

                        <button type="button" @click="closeDetail()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl transition active:scale-95">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </template>
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

            function confirmDelete(transaksiId, liter) {
                Swal.fire({
                    title: 'Batalkan Transaksi?',
                    html: 'Saldo sebesar <b>' + liter + ' Liter</b> akan dikembalikan secara otomatis.<br><br>Masukkan PIN Top Up Anda untuk melanjutkan:',
                    icon: 'warning',
                    input: 'password',
                    inputAttributes: {
                        autocapitalize: 'off',
                        placeholder: 'Masukkan 6 digit PIN'
                    },
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Batalkan!',
                    cancelButtonText: 'Batal',
                    preConfirm: (password) => {
                        if (!password) {
                            Swal.showValidationMessage('PIN Top Up wajib diisi')
                        }
                        return password
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('pwd-' + transaksiId).value = result.value;
                        document.getElementById('delete-form-' + transaksiId).submit();
                    }
                })
            }
        </script>
    @endpush
</x-app-layout>