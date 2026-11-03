<x-app-layout>
    <div class="max-w-7xl mx-auto p-3 sm:p-6 lg:p-8 space-y-6" x-data="pengecualianBonComponent()">
        
        <!-- Sub-Navigation Tabs (Menu Hutang) -->
        <div class="flex items-center gap-2 border-b border-slate-200 dark:border-white/10 pb-3">
            <a href="{{ route('admin.hutang.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/5">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Monitoring Hutang BBM</span>
            </a>

            <a href="{{ route('admin.hutang.pengecualian.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold bg-brand-primary text-white shadow-md shadow-brand-primary/20 transition-all">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>Pengecualian Bebas Bon</span>
                <span class="ml-1 px-2 py-0.5 rounded-full text-[10px] font-black bg-white/20 text-white">
                    {{ $totalBebasBon }}
                </span>
            </a>
        </div>

        <!-- Page Title & Header Card -->
        <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 bg-white dark:bg-slate-900 p-5 sm:p-6 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-500/20 to-emerald-600/10 text-amber-500 border border-amber-500/30 flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                            Pengecualian BON BBM (Bebas Limit)
                        </h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                            Khusus Super Admin
                        </span>
                    </div>
                    <p class="mt-0.5 text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                        Kelola armada kendaraan yang memiliki izin khusus pengisian BON BBM tanpa batas limit walaupun masih memiliki tanggungan bon hutang aktif.
                    </p>
                </div>
            </div>

            <!-- Action Button -->
            <div class="flex items-center gap-3 shrink-0 w-full xl:w-auto">
                <button type="button" @click="openAddModal()"
                    class="w-full xl:w-auto px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 active:scale-95 text-white font-bold rounded-xl shadow-md shadow-emerald-600/20 transition-all text-xs flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Kendaraan Pengecualian</span>
                </button>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/50 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm" role="alert">
                <div class="p-1.5 bg-emerald-500 text-white rounded-xl shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="text-xs font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/50 text-rose-800 dark:text-rose-300 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm" role="alert">
                <div class="p-1.5 bg-rose-500 text-white rounded-xl shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <span class="text-xs font-bold">{{ session('error') }}</span>
            </div>
        @endif

        <!-- KPI / Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Bebas Bon -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-5 rounded-2xl shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <div class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Armada Bebas Bon</div>
                    <div class="text-2xl font-black text-slate-900 dark:text-white tabular-nums">
                        {{ $totalBebasBon }}
                        <span class="text-xs font-bold text-slate-400">Unit</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Satker Terlibat -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-5 rounded-2xl shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-500 border border-blue-500/20 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <div class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Satuan Kerja</div>
                    <div class="text-2xl font-black text-slate-900 dark:text-white tabular-nums">
                        {{ $totalSatkerBebas }}
                        <span class="text-xs font-bold text-slate-400">Satker</span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Bon Aktif pada Armada Bebas Limit -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-5 rounded-2xl shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-500 border border-rose-500/20 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <div class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Bon Aktif Armada Bebas</div>
                    <div class="text-2xl font-black text-rose-600 dark:text-rose-400 tabular-nums">
                        {{ number_format($totalBonLitersBebas, 0, ',', '.') }}
                        <span class="text-xs font-bold text-slate-400">Liter</span>
                    </div>
                </div>
            </div>

            <!-- Card 4: Kebijakan Sistem -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-5 rounded-2xl shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-purple-500/10 text-purple-500 border border-purple-500/20 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <div class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Aturan Bon</div>
                    <div class="text-sm font-black text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5 mt-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Bypass Limit Aktif
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl shadow-sm p-4 sm:p-5">
            <form action="{{ route('admin.hutang.pengecualian.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 sm:gap-4 items-end">
                <!-- Satker (5 cols) -->
                <div class="lg:col-span-5">
                    <x-input-label for="filter_satker_id" value="Satuan Kerja (Satker)" class="text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider ml-0.5 mb-1.5" />
                    <x-filter-select 
                        name="satker_id" 
                        :value="request('satker_id')" 
                        placeholder="Semua Satker" 
                        search-placeholder="Cari nama satker..." 
                        icon="building" 
                        :options="$satkers" 
                    />
                </div>

                <!-- Search (5 cols) -->
                <div class="lg:col-span-5">
                    <x-input-label for="search" value="Cari Nopol / Kendaraan / Keterangan" class="text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider ml-0.5 mb-1.5" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Ketik Nopol atau Jenis Kendaraan..."
                            class="block w-full h-10 pl-9 pr-3 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all shadow-sm" />
                    </div>
                </div>

                <!-- Action Buttons (2 cols) -->
                <div class="lg:col-span-2 flex items-center gap-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-bold rounded-xl shadow-sm shadow-indigo-600/20 transition-all text-xs flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span>Cari</span>
                    </button>

                    @if(request()->hasAny(['satker_id', 'search']))
                        <a href="{{ route('admin.hutang.pengecualian.index') }}"
                            class="p-2 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/50 text-rose-600 dark:text-rose-400 rounded-xl border border-rose-200 dark:border-rose-900/50 transition shrink-0"
                            title="Reset Filter">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Data Card -->
        <div class="bg-white dark:bg-slate-900/90 backdrop-blur-xl border border-slate-200/80 dark:border-white/10 rounded-2xl shadow-sm overflow-hidden">
            <!-- Table Header Bar -->
            <div class="px-5 py-4 border-b border-slate-200/80 dark:border-white/10 flex flex-wrap justify-between items-center gap-3 bg-slate-50/70 dark:bg-slate-800/40">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-6 bg-emerald-500 rounded-full inline-block"></span>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider">Daftar Kendaraan Bebas Bon Tanpa Limit</h3>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold border border-slate-200/60 dark:border-white/5">
                        {{ $kendaraans->total() }} Armada
                    </span>
                </div>
                
                <div class="flex items-center gap-3">
                    <x-per-page :perPage="$kendaraans->perPage()" />
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200/80 dark:border-white/10 bg-slate-50/50 dark:bg-slate-800/30 text-slate-500 dark:text-slate-400 uppercase tracking-wider font-extrabold text-[10px]">
                            <th class="px-4 py-3.5 text-center w-12">No</th>
                            <th class="px-4 py-3.5">No. Polisi & Kendaraan</th>
                            <th class="px-4 py-3.5">Satuan Kerja</th>
                            <th class="px-4 py-3.5">Jenis BBM</th>
                            <th class="px-4 py-3.5 text-center">Status Limit</th>
                            <th class="px-4 py-3.5 text-center">Bon Aktif (Belum Lunas)</th>
                            <th class="px-4 py-3.5">Alasan / Keterangan</th>
                            <th class="px-4 py-3.5">Ditetapkan Pada</th>
                            <th class="px-4 py-3.5 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/70 dark:divide-white/5 font-medium text-slate-700 dark:text-slate-300">
                        @forelse($kendaraans as $index => $knd)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-4 py-3.5 text-center text-slate-400 font-bold">
                                    {{ $kendaraans->firstItem() + $index }}
                                </td>

                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center font-black shrink-0 border border-slate-200 dark:border-white/10">
                                            🚗
                                        </div>
                                        <div>
                                            <div class="font-black text-slate-900 dark:text-white tracking-wide text-xs">
                                                {{ $knd->no_polisi }}
                                            </div>
                                            <div class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                                                {{ $knd->jenis_kendaraan }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-3.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[11px] font-bold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                                        {{ $knd->satker->nama_satker ?? '-' }}
                                    </span>
                                </td>

                                <td class="px-4 py-3.5">
                                    @php
                                        $isDex = stripos($knd->jenis_bbm, 'Dex') !== false;
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg text-[11px] font-extrabold {{ $isDex ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $isDex ? 'bg-emerald-500' : 'bg-blue-500' }}"></span>
                                        {{ $knd->jenis_bbm }}
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 shadow-sm shadow-emerald-500/10 animate-pulse">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Bebas Limit Bon
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 text-center">
                                    @if($knd->unpaid_bon_liters > 0)
                                        <div class="inline-flex flex-col items-center">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-extrabold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                                <span>{{ number_format($knd->unpaid_bon_liters, 0, ',', '.') }} L</span>
                                            </span>
                                            <span class="text-[10px] text-slate-400 mt-0.5">{{ $knd->unpaid_bon_count }} Transaksi Bon</span>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-400">
                                            <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Tidak Ada Bon
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3.5 max-w-xs">
                                    <p class="text-xs text-slate-700 dark:text-slate-300 font-medium truncate" title="{{ $knd->alasan_bebas_bon }}">
                                        {{ $knd->alasan_bebas_bon ?: '-' }}
                                    </p>
                                </td>

                                <td class="px-4 py-3.5 text-[11px] text-slate-500 dark:text-slate-400">
                                    <div>{{ $knd->bebas_bon_updated_at ? $knd->bebas_bon_updated_at->translatedFormat('d M Y H:i') : '-' }}</div>
                                    @if($knd->bebasBonBy)
                                        <div class="text-[10px] text-slate-400">Oleh: {{ $knd->bebasBonBy->name }}</div>
                                    @endif
                                </td>

                                <td class="px-4 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Edit Alasan Button -->
                                        <button type="button" @click="openEditModal({{ json_encode($knd) }})"
                                            class="p-1.5 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/40 rounded-lg transition-colors border border-blue-500/20"
                                            title="Edit Keterangan / Alasan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        <!-- Hapus Pengecualian Button -->
                                        <button type="button" @click="confirmDelete({{ json_encode($knd) }})"
                                            class="p-1.5 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/40 rounded-lg transition-colors border border-rose-500/20"
                                            title="Cabut Status Bebas Bon">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mb-3">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Belum ada armada kendaraan yang diberi pengecualian</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Klik tombol "Tambah Kendaraan Pengecualian" di atas untuk menambahkan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($kendaraans->hasPages())
                <div class="p-4 border-t border-slate-200/80 dark:border-white/10 bg-slate-50/50 dark:bg-slate-800/30">
                    {{ $kendaraans->links() }}
                </div>
            @endif
        </div>

        <!-- ================= MODAL TAMBAH PENGECUALIAN ================= -->
        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title-add" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                <div x-show="showAddModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    @click="showAddModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

                <div x-show="showAddModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative bg-white dark:bg-slate-900 rounded-3xl text-left shadow-2xl transform transition-all sm:my-8 sm:max-w-xl sm:w-full border border-slate-200 dark:border-white/10">
                    
                    <div class="px-6 py-5 border-b border-slate-200 dark:border-white/10 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50 rounded-t-3xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-black tracking-tight text-slate-900 dark:text-white uppercase">Tambah Pengecualian Bon</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Beri izin bebas limit bon pada kendaraan</p>
                            </div>
                        </div>
                        <button @click="showAddModal = false" type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('admin.hutang.pengecualian.store') }}" method="POST" class="p-6 space-y-5">
                        @csrf

                        <!-- 1. Custom Interactive Dropdown Satker -->
                        <div class="relative" @click.outside="satkerDropdownOpen = false">
                            <label class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300">
                                <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span>1. Pilih Satuan Kerja (Satker)</span>
                                <span class="text-rose-500">*</span>
                            </label>

                            <!-- Hidden input untuk submit form -->
                            <input type="hidden" name="satker_id" :value="selectedSatkerId" required>

                            <!-- Trigger Button -->
                            <div class="mt-1.5 relative">
                                <button type="button" 
                                    @click="satkerDropdownOpen = !satkerDropdownOpen; $nextTick(() => { if(satkerDropdownOpen && $refs.satkerSearchInput) $refs.satkerSearchInput.focus() })"
                                    class="w-full h-11 px-3.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-medium text-left flex items-center justify-between transition-all hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'ring-2 ring-blue-500 border-transparent': satkerDropdownOpen, 'border-blue-500/50 text-blue-600 dark:text-blue-400': selectedSatkerId !== '' }">
                                    <div class="flex items-center gap-2.5 truncate pr-2">
                                        <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span class="truncate font-semibold text-xs" :class="selectedSatkerId ? 'text-slate-800 dark:text-white' : 'text-slate-400 dark:text-slate-500'" x-text="selectedSatkerName || '-- Pilih Satuan Kerja --'"></span>
                                    </div>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <template x-if="selectedSatkerId !== ''">
                                            <span @click.stop="selectSatker('', '')" class="p-1 rounded-full hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-400 hover:text-rose-500 transition-colors" title="Reset Satker">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </span>
                                        </template>
                                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="satkerDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </button>

                                <!-- Dropdown Menu Popover -->
                                <div x-show="satkerDropdownOpen" x-cloak
                                    x-transition:enter="transition ease-out duration-150"
                                    x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-100"
                                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                                    class="absolute left-0 right-0 mt-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl z-50 overflow-hidden flex flex-col max-h-72">
                                    
                                    <!-- Search input di dalam dropdown -->
                                    <div class="p-2.5 border-b border-slate-200 dark:border-white/10 bg-slate-50/80 dark:bg-slate-800/50 sticky top-0 z-10 shrink-0">
                                        <div class="relative">
                                            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                            <input type="text" x-ref="satkerSearchInput" x-model="satkerSearch" placeholder="Ketik untuk mencari Satker..."
                                                class="w-full pl-9 pr-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-semibold text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>

                                    <!-- Options List -->
                                    <div class="overflow-y-auto divide-y divide-slate-100 dark:divide-white/5 custom-scrollbar p-1.5 flex-1 min-h-0 overscroll-contain">
                                        <template x-for="s in filteredSatkers" :key="s.id">
                                            <button type="button" @click="selectSatker(s.id, s.nama_satker)"
                                                class="w-full text-left px-3.5 py-2.5 rounded-xl text-xs flex items-center justify-between transition-colors"
                                                :class="selectedSatkerId == s.id ? 'bg-blue-600/10 text-blue-600 dark:bg-blue-500/20 dark:text-white font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/80'">
                                                <div class="flex items-center gap-2.5 truncate pr-2">
                                                    <span class="w-2 h-2 rounded-full shrink-0" :class="selectedSatkerId == s.id ? 'bg-blue-500 ring-2 ring-blue-500/30' : 'bg-slate-300 dark:bg-slate-600'"></span>
                                                    <span class="truncate" x-text="s.nama_satker"></span>
                                                </div>
                                                <template x-if="selectedSatkerId == s.id">
                                                    <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                </template>
                                            </button>
                                        </template>

                                        <template x-if="filteredSatkers.length === 0">
                                            <div class="px-3 py-6 text-center text-xs text-slate-400">
                                                Tidak ditemukan Satker "<span x-text="satkerSearch" class="font-bold text-slate-300"></span>"
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Pilih Kendaraan (Bisa Multiple) -->
                        <div>
                            <div class="flex items-center justify-between">
                                <label class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1" />
                                    </svg>
                                    <span>2. Pilih Armada Kendaraan</span>
                                    <span class="text-rose-500">*</span>
                                </label>
                                <div class="flex items-center gap-3">
                                    <span x-show="loadingKendaraans" class="text-[10px] text-amber-500 flex items-center gap-1 font-semibold">
                                        <svg class="animate-spin h-3 w-3 text-amber-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                        Memuat...
                                    </span>
                                    <template x-if="availableKendaraans.length > 0">
                                        <div class="flex items-center gap-2 text-[10px]">
                                            <button type="button" @click="selectAllKendaraans()" class="text-emerald-500 hover:text-emerald-400 font-bold hover:underline">Pilih Semua</button>
                                            <span class="text-slate-500">•</span>
                                            <button type="button" @click="selectedKendaraanIds = []" class="text-slate-400 hover:text-slate-300 font-bold hover:underline">Batal</button>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="mt-2 border border-slate-200 dark:border-white/10 rounded-2xl p-3 bg-slate-50/50 dark:bg-slate-800/40 max-h-56 overflow-y-auto custom-scrollbar">
                                <template x-if="!selectedSatkerId">
                                    <p class="text-xs text-slate-400 text-center py-4">Silakan pilih Satker terlebih dahulu pada dropdown di atas</p>
                                </template>

                                <template x-if="selectedSatkerId && availableKendaraans.length === 0 && !loadingKendaraans">
                                    <p class="text-xs text-slate-400 text-center py-4">Semua kendaraan pada satker ini sudah berstatus Bebas Bon atau belum ada kendaraan terdaftar.</p>
                                </template>

                                <div class="space-y-1.5" x-show="availableKendaraans.length > 0">
                                    <template x-for="kend in availableKendaraans" :key="kend.id">
                                        <label class="flex items-center gap-3 p-2.5 rounded-xl cursor-pointer transition-all border"
                                            :class="selectedKendaraanIds.includes(kend.id) ? 'bg-emerald-500/10 border-emerald-500/30 dark:bg-emerald-500/15' : 'hover:bg-slate-100 dark:hover:bg-slate-700/50 border-transparent'">
                                            <input type="checkbox" name="kendaraan_ids[]" :value="kend.id" x-model="selectedKendaraanIds"
                                                class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs font-black text-slate-900 dark:text-white tracking-wide" x-text="kend.no_polisi"></span>
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full"
                                                        :class="kend.jenis_bbm.includes('Dex') ? 'bg-amber-500/10 text-amber-500 border border-amber-500/20' : 'bg-blue-500/10 text-blue-500 border border-blue-500/20'"
                                                        x-text="kend.jenis_bbm"></span>
                                                </div>
                                                <div class="text-[11px] text-slate-500 dark:text-slate-400 truncate mt-0.5" x-text="kend.jenis_kendaraan"></div>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </div>
                            <div class="mt-1.5 flex justify-between items-center text-[11px] text-slate-400">
                                <span>Pilih satu atau beberapa armada kendaraan sekaligus</span>
                                <span class="font-bold text-emerald-600 dark:text-emerald-400" x-text="`${selectedKendaraanIds.length} kendaraan terpilih`"></span>
                            </div>
                        </div>

                        <!-- 3. Alasan / Keterangan Pengecualian -->
                        <div>
                            <label for="alasan_bebas_bon" class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300">
                                <svg class="w-4 h-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                                <span>3. Alasan / Keterangan Pengecualian</span>
                            </label>
                            <div class="mt-1.5">
                                <textarea name="alasan_bebas_bon" id="alasan_bebas_bon" rows="3"
                                    placeholder="Contoh: Kendaraan Siaga Khusus / Unit Ambulans Darurat / Kendaraan Pimpinan Satker"
                                    class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 focus:bg-white dark:focus:bg-slate-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 rounded-xl transition-all shadow-sm font-semibold text-xs text-slate-800 dark:text-white resize-none"></textarea>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-slate-200 dark:border-white/10 flex justify-end gap-2.5">
                            <button type="button" @click="showAddModal = false"
                                class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-white/10 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                Batal
                            </button>
                            <button type="submit" :disabled="selectedKendaraanIds.length === 0"
                                class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Simpan Pengecualian</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ================= MODAL EDIT ALASAN ================= -->
        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title-edit" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                <div x-show="showEditModal" @click="showEditModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

                <div x-show="showEditModal"
                    class="relative bg-white dark:bg-slate-900 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-md sm:w-full border border-slate-200 dark:border-white/10">
                    
                    <div class="px-6 py-5 border-b border-slate-200 dark:border-white/10 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-500 border border-blue-500/20 flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-black tracking-tight text-slate-900 dark:text-white uppercase">Edit Keterangan</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium" x-text="editTarget.no_polisi"></p>
                            </div>
                        </div>
                        <button @click="showEditModal = false" type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form :action="'/admin/hutang/pengecualian/' + editTarget.id" method="POST" class="p-6 space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="edit_alasan" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                Alasan / Keterangan Pengecualian
                            </label>
                            <textarea name="alasan_bebas_bon" id="edit_alasan" rows="4" x-model="editTarget.alasan_bebas_bon"
                                class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 focus:bg-white dark:focus:bg-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-xl transition-all shadow-sm font-semibold text-xs text-slate-800 dark:text-white resize-none"></textarea>
                        </div>

                        <div class="pt-3 border-t border-slate-200 dark:border-white/10 flex justify-end gap-2.5">
                            <button type="button" @click="showEditModal = false"
                                class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-white/10 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold text-xs shadow-md shadow-blue-600/20 transition-all">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ================= MODAL KONFIRMASI HAPUS ================= -->
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title-del" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                <div x-show="showDeleteModal" @click="showDeleteModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

                <div x-show="showDeleteModal"
                    class="relative bg-white dark:bg-slate-900 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-md sm:w-full border border-slate-200 dark:border-white/10 p-6 space-y-4">
                    
                    <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-500 border border-rose-500/20 flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>

                    <div class="text-center space-y-1">
                        <h3 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tight">Cabut Status Bebas Bon?</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Kendaraan <span class="font-bold text-slate-800 dark:text-white" x-text="deleteTarget.no_polisi"></span> akan kembali ke batas limit standar sistem dan tidak dapat melakukan BON baru jika masih memiliki tanggungan bon belum lunas.
                        </p>
                    </div>

                    <form :action="'/admin/hutang/pengecualian/' + deleteTarget.id" method="POST" class="pt-2 flex justify-center gap-2.5">
                        @csrf
                        @method('DELETE')

                        <button type="button" @click="showDeleteModal = false"
                            class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-white/10 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 active:scale-95 text-white font-bold text-xs shadow-md shadow-rose-600/20 transition-all">
                            Cabut Bebas Bon
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        function pengecualianBonComponent() {
            return {
                showAddModal: false,
                showEditModal: false,
                showDeleteModal: false,

                // Satker custom dropdown state
                satkerList: @json($satkers),
                satkerDropdownOpen: false,
                satkerSearch: '',
                selectedSatkerId: '',
                selectedSatkerName: '',

                availableKendaraans: [],
                selectedKendaraanIds: [],
                loadingKendaraans: false,

                editTarget: {},
                deleteTarget: {},

                get filteredSatkers() {
                    if (!this.satkerSearch) return this.satkerList;
                    const q = this.satkerSearch.toLowerCase().trim();
                    return this.satkerList.filter(s => (s.nama_satker || '').toLowerCase().includes(q));
                },

                selectSatker(id, name) {
                    this.selectedSatkerId = id;
                    this.selectedSatkerName = name;
                    this.satkerDropdownOpen = false;
                    this.satkerSearch = '';
                    this.fetchAvailableKendaraans(id);
                },

                selectAllKendaraans() {
                    this.selectedKendaraanIds = this.availableKendaraans.map(k => k.id);
                },

                openAddModal() {
                    this.selectedSatkerId = '';
                    this.selectedSatkerName = '';
                    this.satkerSearch = '';
                    this.satkerDropdownOpen = false;
                    this.availableKendaraans = [];
                    this.selectedKendaraanIds = [];
                    this.showAddModal = true;
                },

                async fetchAvailableKendaraans(satkerId) {
                    if (!satkerId) {
                        this.availableKendaraans = [];
                        return;
                    }

                    this.loadingKendaraans = true;
                    this.selectedKendaraanIds = [];

                    try {
                        const response = await fetch(`{{ route('admin.hutang.pengecualian.available-kendaraans') }}?satker_id=${satkerId}`);
                        if (response.ok) {
                            this.availableKendaraans = await response.json();
                        } else {
                            this.availableKendaraans = [];
                        }
                    } catch (e) {
                        console.error('Error fetching available kendaraans:', e);
                        this.availableKendaraans = [];
                    } finally {
                        this.loadingKendaraans = false;
                    }
                },

                openEditModal(kendaraan) {
                    this.editTarget = Object.assign({}, kendaraan);
                    this.showEditModal = true;
                },

                confirmDelete(kendaraan) {
                    this.deleteTarget = Object.assign({}, kendaraan);
                    this.showDeleteModal = true;
                }
            };
        }
    </script>
    @endpush
</x-app-layout>

