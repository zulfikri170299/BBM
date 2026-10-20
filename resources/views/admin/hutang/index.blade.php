<x-app-layout>
    <div class="max-w-7xl mx-auto p-3 sm:p-6 lg:p-8 space-y-6" x-data="hutangComponent()">
        <!-- Page Title & Header Card -->
        <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 bg-white dark:bg-slate-900 p-5 sm:p-6 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-rose-500/20 to-amber-600/10 text-rose-500 border border-rose-500/30 flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                            Monitoring Hutang BBM
                        </h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                            Pengawasan Bon BBM
                        </span>
                    </div>
                    <p class="mt-0.5 text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                        Rekapitulasi pencatatan bon pengisian BBM dan status pelunasan transaksi seluruh Satker.
                    </p>
                </div>
            </div>

            <!-- Summary Outstanding per BBM (Dynamic KPI Cards) -->
            <div class="flex flex-wrap items-center gap-3 shrink-0 w-full xl:w-auto">
                @forelse($summaryHutang as $bbm => $total)
                    @php
                        $isDex = stripos($bbm, 'Dex') !== false;
                    @endphp
                    <div class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 px-4 py-2.5 rounded-2xl shadow-sm flex items-center gap-3">
                        <div class="w-2.5 h-9 rounded-full {{ $isDex ? 'bg-emerald-500' : 'bg-blue-500' }}"></div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                    {{ $bbm }}
                                </span>
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse" title="Belum Lunas"></span>
                            </div>
                            <div class="text-lg font-black text-rose-600 dark:text-rose-400 tabular-nums leading-tight">
                                {{ rtrim(rtrim(number_format($total, 2, ',', '.'), '0'), ',') }}
                                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Liter</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-emerald-500/10 border border-emerald-500/30 px-4 py-2.5 rounded-2xl flex items-center gap-2 text-emerald-600 dark:text-emerald-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-xs font-bold uppercase tracking-wider">Semua Bon Lunas</span>
                    </div>
                @endforelse
            </div>
        </div>

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

        <!-- Filter & Action Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl shadow-sm p-4 sm:p-5 relative z-30">
            <form action="{{ route('admin.hutang.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 sm:gap-4 items-end">
                <!-- Satker (4 cols) -->
                <div class="lg:col-span-4">
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

                <!-- Status (2 cols) -->
                <div class="lg:col-span-2">
                    <x-input-label for="filter_status" value="Status" class="text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider ml-0.5 mb-1.5" />
                    <x-filter-select 
                        name="status" 
                        :value="request('status')" 
                        placeholder="Semua Status" 
                        icon="shield" 
                        :searchable="false" 
                        :options="['belum_dibayar' => 'Belum Bayar', 'sudah_dibayar' => 'Lunas']" 
                    />
                </div>

                <!-- Dari (2 cols) -->
                <div class="lg:col-span-2">
                    <x-input-label for="start_date" value="Dari Tanggal" class="text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider ml-0.5 mb-1.5" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input type="text" name="start_date" id="start_date" value="{{ request('start_date') }}" placeholder="Dari Tanggal"
                            style="padding-left: 2.35rem !important;"
                            class="flatpickr block w-full h-10 !pl-9 pr-3 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm" />
                    </div>
                </div>

                <!-- Sampai (2 cols) -->
                <div class="lg:col-span-2">
                    <x-input-label for="end_date" value="Sampai Tanggal" class="text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider ml-0.5 mb-1.5" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input type="text" name="end_date" id="end_date" value="{{ request('end_date') }}" placeholder="Sampai Tanggal"
                            style="padding-left: 2.35rem !important;"
                            class="flatpickr block w-full h-10 !pl-9 pr-3 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm" />
                    </div>
                </div>

                <!-- Buttons Group (2 cols) -->
                <div class="lg:col-span-2 flex items-center gap-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-bold rounded-xl shadow-sm shadow-indigo-600/20 transition-all text-xs flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span>Filter</span>
                    </button>

                    @if(request()->hasAny(['satker_id', 'status', 'start_date', 'end_date']))
                        <a href="{{ route('admin.hutang.index') }}"
                            class="p-2 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/50 text-rose-600 dark:text-rose-400 rounded-xl border border-rose-200 dark:border-rose-900/50 transition shrink-0"
                            title="Reset Filter">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif

                    <button type="button" @click="openCreateModal()"
                        class="flex-1 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold rounded-xl shadow-sm shadow-emerald-600/20 transition-all text-xs flex items-center justify-center gap-1.5 shrink-0"
                        title="Tambah Catatan Bon Hutang Baru">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Table Data Card -->
        <div class="bg-white dark:bg-slate-900/90 backdrop-blur-xl border border-slate-200/80 dark:border-white/10 rounded-2xl shadow-sm overflow-hidden transition-all duration-200">
            <!-- Table Header Bar -->
            <div class="px-5 py-4 border-b border-slate-200/80 dark:border-white/10 flex flex-wrap justify-between items-center gap-3 bg-slate-50/70 dark:bg-slate-800/40">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-6 bg-brand-primary rounded-full inline-block"></span>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider">Data Hutang BBM</h3>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold border border-slate-200/60 dark:border-white/5">
                        {{ $hutangs->total() }} Data
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] uppercase font-bold text-slate-500 dark:text-slate-400 tracking-wider">Tampilkan</span>
                    <form action="{{ route('admin.hutang.index') }}" method="GET" class="inline">
                        @foreach(request()->except('per_page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <select name="per_page" onchange="this.form.submit()"
                            class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-xs font-semibold py-1.5 px-3 rounded-xl border border-slate-200 dark:border-white/10 focus:border-brand-primary focus:ring-1 focus:ring-brand-primary shadow-sm cursor-pointer transition">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ request('per_page') == 15 || !request('per_page') ? 'selected' : '' }}>15</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-[11px] text-slate-500 dark:text-slate-400 uppercase bg-slate-50/90 dark:bg-slate-800/80 border-b border-slate-200/80 dark:border-white/10 tracking-wider">
                        <tr>
                            <th class="px-4 py-3.5 font-bold hidden lg:table-cell">Tanggal</th>
                            <th class="px-4 py-3.5 font-bold">Satker</th>
                            <th class="px-4 py-3.5 font-bold">Kendaraan</th>
                            <th class="px-4 py-3.5 font-bold hidden lg:table-cell">Driver</th>
                            <th class="px-4 py-3.5 font-bold hidden lg:table-cell">Jumlah Bon</th>
                            <th class="px-4 py-3.5 font-bold hidden lg:table-cell">Petugas</th>
                            <th class="px-4 py-3.5 font-bold">Status</th>
                            @if(auth()->user()->role !== 'kasubbag')
                                <th class="px-4 py-3.5 font-bold text-center border-l border-slate-200/80 dark:border-white/10">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($hutangs as $hutang)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                                <!-- Tanggal -->
                                <td class="px-4 py-3 hidden lg:table-cell whitespace-nowrap">
                                    <div class="text-xs font-bold text-slate-800 dark:text-white">
                                        @if($hutang->tanggal_bon)
                                            {{ \Carbon\Carbon::parse($hutang->tanggal_bon)->format('d-m-Y') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->format('d-m-Y') }}
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-slate-400 dark:text-slate-500 uppercase tracking-wider font-semibold">
                                        @if($hutang->tanggal_bon)
                                            Catatan Manual
                                        @else
                                            {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->format('H:i') }} WITA
                                        @endif
                                    </div>
                                </td>

                                <!-- Satker -->
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 font-black text-xs flex items-center justify-center shrink-0 border border-indigo-200/50 dark:border-indigo-500/20">
                                            {{ strtoupper(substr($hutang->satker->nama_satker ?? 'S', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-white leading-tight">
                                                {{ $hutang->satker->nama_satker }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kendaraan -->
                                <td class="px-4 py-3">
                                    <div class="text-xs font-black text-white tracking-wide uppercase">
                                        {{ $hutang->nopol }}
                                    </div>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400">
                                        {{ $hutang->jenis_kendaraan ?: 'Kendaraan Dinas' }}
                                    </div>
                                </td>

                                <!-- Driver -->
                                <td class="px-4 py-3 hidden lg:table-cell">
                                    <div class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $hutang->nama_driver ?: '-' }}
                                    </div>
                                </td>

                                <!-- Jumlah Bon -->
                                <td class="px-4 py-3 hidden lg:table-cell whitespace-nowrap">
                                    @php
                                        $bbmUpper = strtoupper($hutang->jenis_bbm);
                                        $isDex = str_contains($bbmUpper, 'DEX');
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold {{ $isDex ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20' : 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $isDex ? 'bg-amber-500' : 'bg-emerald-500' }}"></span>
                                        {{ number_format($hutang->jumlah_bon, 0, ',', '.') }} L {{ $hutang->jenis_bbm }}
                                    </span>
                                </td>

                                <!-- Petugas -->
                                <td class="px-4 py-3 text-xs font-medium text-slate-600 dark:text-slate-400 hidden lg:table-cell">
                                    {{ $hutang->petugas->name ?? '-' }}
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($hutang->status === 'belum_dibayar')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                            Belum Bayar
                                        </span>
                                    @else
                                        <div class="flex flex-col gap-0.5">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 rounded-lg text-[10px] font-black uppercase tracking-wider w-fit">
                                                <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                Lunas
                                            </span>
                                            <span class="text-[9px] text-slate-400 dark:text-slate-500 font-medium">
                                                Oleh: {{ $hutang->adminBayar ? $hutang->adminBayar->name : 'Otomatis' }}
                                            </span>
                                            @if($hutang->tanggal_bayar)
                                                <span class="text-[9px] text-slate-400 dark:text-slate-500">
                                                    {{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->format('d-m-Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                @if(auth()->user()->role !== 'kasubbag')
                                    <td class="px-4 py-3 border-l border-slate-200/80 dark:border-white/5 whitespace-nowrap">
                                        <!-- Desktop actions -->
                                        <div class="hidden lg:flex items-center justify-center gap-1.5">
                                            @php
                                                $editData = [
                                                    'id' => $hutang->id,
                                                    'satker_id' => $hutang->satker_id,
                                                    'nopol' => $hutang->nopol,
                                                    'jenis_kendaraan' => $hutang->jenis_kendaraan,
                                                    'nama_driver' => $hutang->nama_driver,
                                                    'jenis_bbm' => $hutang->jenis_bbm,
                                                    'jumlah_bon' => $hutang->jumlah_bon,
                                                    'tanggal_bon' => $hutang->tanggal_bon ?? $hutang->created_at->format('Y-m-d')
                                                ];
                                            @endphp
                                            @if($hutang->status === 'belum_dibayar')
                                                <!-- Edit -->
                                                <button type="button"
                                                    @click="openEditModal(@js($editData))"
                                                    class="p-1.5 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 rounded-lg transition-colors border border-blue-200 dark:border-blue-500/20"
                                                    title="Edit Data Bon">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>

                                                <!-- Hapus -->
                                                <form action="{{ route('admin.hutang.destroy', $hutang) }}" method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="button"
                                                        data-confirm="Apakah Anda yakin ingin menghapus data hutang ini?"
                                                        data-confirm-type="warning"
                                                        class="p-1.5 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-lg transition-colors border border-rose-200 dark:border-rose-500/20"
                                                        title="Hapus Data">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400 px-2 py-1 bg-emerald-500/10 rounded-lg border border-emerald-500/20">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Telah Lunas
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Mobile Detail Button & Modal -->
                                        <div x-data="{ showDetail: false }" class="lg:hidden flex justify-center">
                                            <button type="button" @click="showDetail = true" class="inline-flex items-center p-2 bg-indigo-600/10 text-indigo-600 dark:text-indigo-400 rounded-lg border border-indigo-600/20 shadow-sm" title="Lihat Detail">
                                                <svg class="w-4 h-4 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                            
                                            <template x-teleport="body">
                                                <div x-show="showDetail" class="fixed inset-0 z-[9999] flex items-end sm:items-center justify-center p-4" style="display: none;">
                                                    <div x-show="showDetail" x-transition.opacity @click="showDetail = false" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm"></div>
                                                    <div x-show="showDetail" x-transition.translate.y @click.outside="showDetail = false" class="relative w-full max-w-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl flex flex-col max-h-[85vh] overflow-hidden">
                                                        <div class="p-4 border-b border-slate-200 dark:border-white/10 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50">
                                                            <div class="text-left">
                                                                <h3 class="text-slate-800 dark:text-white font-bold text-lg leading-tight">{{ $hutang->nopol }}</h3>
                                                                <p class="text-slate-500 dark:text-slate-400 text-xs">{{ $hutang->satker->nama_satker }}</p>
                                                            </div>
                                                            <button type="button" @click="showDetail = false" class="p-2 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white rounded-xl border border-slate-200 dark:border-white/5 transition">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                            </button>
                                                        </div>
                                                        <div class="p-4 overflow-y-auto w-full custom-scrollbar text-left text-sm space-y-4">
                                                            <div class="grid grid-cols-2 gap-4">
                                                                <div>
                                                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">Tanggal</span>
                                                                    <span class="font-bold text-slate-800 dark:text-white uppercase">{{ $hutang->tanggal_bon ? \Carbon\Carbon::parse($hutang->tanggal_bon)->format('d-m-Y') : \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->format('d-m-Y') }}</span>
                                                                </div>
                                                                <div>
                                                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">Jenis BBM</span>
                                                                    <span class="font-bold text-slate-800 dark:text-white">{{ strtoupper($hutang->jenis_bbm) }}</span>
                                                                </div>
                                                                <div>
                                                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">Jumlah Bon</span>
                                                                    <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($hutang->jumlah_bon, 0, ',', '.') }} L</span>
                                                                </div>
                                                                <div>
                                                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">Driver</span>
                                                                    <span class="font-bold text-slate-800 dark:text-white">{{ $hutang->nama_driver ?? '-' }}</span>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="pt-4 border-t border-slate-200 dark:border-white/10 flex flex-col gap-2">
                                                                @if($hutang->status === 'belum_dibayar')
                                                                    <button type="button" @click="showDetail = false; openEditModal(@js($editData))" class="flex items-center gap-3 w-full p-3 bg-slate-50 dark:bg-slate-800 rounded-xl text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition">
                                                                        <div class="p-1.5 bg-blue-500/20 text-blue-600 dark:text-blue-400 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></div>
                                                                        <span class="font-semibold text-xs">Edit Hutang</span>
                                                                    </button>

                                                                    <form action="{{ route('admin.hutang.destroy', $hutang) }}" method="POST" class="w-full">
                                                                        @csrf @method('DELETE')
                                                                        <button type="button" data-confirm="Apakah Anda yakin ingin menghapus data hutang ini?" data-confirm-type="warning" class="flex items-center w-full gap-3 p-3 bg-slate-50 dark:bg-slate-800 rounded-xl text-slate-700 dark:text-slate-300 hover:text-rose-500 transition text-left">
                                                                            <div class="p-1.5 bg-rose-500/20 text-rose-600 dark:text-rose-400 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></div>
                                                                            <span class="font-semibold text-xs">Hapus</span>
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    <div class="flex items-center justify-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400 py-3 bg-emerald-500/10 rounded-xl border border-emerald-500/20">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                                        <span>Pembayaran Telah Lunas</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role === 'kasubbag' ? 7 : 8 }}"
                                    class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <svg class="w-10 h-10 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span class="text-xs font-semibold">Tidak ada data hutang bon BBM ditemukan.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($hutangs->hasPages())
                <div class="p-4 border-t border-slate-200/80 dark:border-white/10 bg-slate-50/50 dark:bg-slate-800/30">
                    {{ $hutangs->links() }}
                </div>
            @endif
        </div>


        <!-- Edit Modal -->
        <template x-teleport="body">
            <div x-show="showEditModal" class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;"
                aria-labelledby="modal-title-edit" role="dialog" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div x-show="showEditModal" x-transition.opacity
                        class="fixed inset-0 bg-slate-950/75 backdrop-blur-sm transition-opacity" aria-hidden="true"
                        @click="showEditModal = false"></div>

                    <div x-show="showEditModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-xl w-full">

                        <!-- Modal Header -->
                        <div class="px-6 py-5 border-b border-slate-100 dark:border-white/10 flex items-center justify-between bg-slate-50/60 dark:bg-slate-800/40">
                            <div class="flex items-center gap-3.5">
                                <div class="w-11 h-11 rounded-2xl bg-blue-500/10 dark:bg-blue-500/20 border border-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center shadow-lg shadow-blue-500/10">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-black tracking-tight text-white uppercase" id="modal-title-edit">Edit Data Bon Hutang</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Perbarui informasi catatan bon hutang BBM</p>
                                </div>
                            </div>
                            <button @click="showEditModal = false" type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form :action="'/admin/hutang/' + editData.id" method="POST" class="p-6 space-y-5">
                            @csrf @method('PUT')

                            <!-- Satker Selection -->
                            <div>
                                <label for="edit_satker_id" class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <span>Unit Kerja / Satker</span>
                                    <span class="text-rose-500">*</span>
                                </label>
                                <div class="mt-1.5">
                                    <select name="satker_id" id="edit_satker_id" x-model="editData.satker_id" @change="fetchFormKendaraans($event.target.value); editData.kendaraan_id = ''"
                                        class="tom-select w-full" required>
                                        @foreach($satkers as $satker)
                                            <option value="{{ $satker->id }}">{{ $satker->nama_satker }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Kendaraan Selection -->
                            <div>
                                <div class="flex items-center justify-between">
                                    <label for="edit_kendaraan_id" class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                        </svg>
                                        <span>Kendaraan Dinas</span>
                                        <span class="text-rose-500">*</span>
                                    </label>
                                    <span x-show="loadingFormKendaraan" class="text-[10px] text-amber-500 flex items-center gap-1 font-semibold">
                                        <svg class="animate-spin h-3 w-3 text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                        Memuat data...
                                    </span>
                                </div>
                                <div class="mt-1.5 relative">
                                    <select name="kendaraan_id" id="edit_kendaraan_id" x-model="editData.kendaraan_id" required
                                        :disabled="loadingFormKendaraan || !editData.satker_id || formKendaraans.length === 0"
                                        class="block w-full px-4 py-2.5 pr-10 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 focus:bg-white dark:focus:bg-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-xl transition-all shadow-sm font-semibold text-xs text-slate-800 dark:text-white disabled:opacity-50 disabled:cursor-not-allowed appearance-none cursor-pointer">
                                        <option value="" disabled selected x-text="!editData.satker_id ? '-- Pilih Satker Terlebih Dahulu --' : (loadingFormKendaraan ? 'Sedang memuat data kendaraan...' : (formKendaraans.length ? '-- Pilih Kendaraan --' : 'Tidak ada kendaraan pada satker ini'))"></option>
                                        <template x-for="kend in formKendaraans" :key="kend.id">
                                            <option :value="kend.id" x-text="`${kend.no_polisi} - ${kend.jenis_kendaraan} (${kend.jenis_bbm})`" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-white py-1.5"></option>
                                        </template>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Driver Name -->
                            <div>
                                <label for="edit_nama_driver" class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>Nama Driver / Pengemudi</span>
                                    <span class="text-rose-500">*</span>
                                </label>
                                <div class="mt-1.5">
                                    <input id="edit_nama_driver" name="nama_driver" type="text" x-model="editData.nama_driver" 
                                        class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 focus:bg-white dark:focus:bg-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-xl transition-all shadow-sm font-semibold text-xs text-slate-800 dark:text-white" 
                                        placeholder="Masukkan nama driver" required />
                                </div>
                            </div>

                            <!-- Date and Amount Row -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="edit_tanggal_bon" class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>Tanggal Bon</span>
                                        <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="mt-1.5">
                                        <input id="edit_tanggal_bon" name="tanggal_bon" type="date" x-model="editData.tanggal_bon" 
                                            class="flatpickr block w-full py-2.5 px-3.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 focus:bg-white dark:focus:bg-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-xl transition-all shadow-sm font-bold text-xs text-slate-800 dark:text-white" 
                                            required />
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between items-center">
                                        <label for="edit_jumlah_bon" class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300">
                                            <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L7.05 15.12a2 2 0 00-1.022.547l-2.387 2.387a2 2 0 000 2.828l.141.141a2 2 0 002.828 0l2.628-2.628a2 2 0 012.33-.213l.317.158a2 2 0 002.33-.213l2.628-2.628a2 2 0 000-2.828l-.141-.141z" />
                                            </svg>
                                            <span>Jumlah Bon</span>
                                            <span class="text-rose-500">*</span>
                                        </label>
                                        <span x-show="editCurrentStock !== null" class="text-[10px] font-bold text-blue-600 dark:text-blue-400 bg-blue-500/10 px-2 py-0.5 rounded-md border border-blue-500/20">
                                            Max: <span x-text="(parseFloat(editCurrentStock) + parseFloat(editData.original_jumlah_bon || 0)).toFixed(0)"></span> L
                                        </span>
                                    </div>
                                    <div class="mt-1.5 relative">
                                        <input id="edit_jumlah_bon" name="jumlah_bon" type="number" step="1" x-model="editData.jumlah_bon" 
                                            :max="(parseFloat(editCurrentStock) + parseFloat(editData.original_jumlah_bon || 0)).toFixed(0)"
                                            class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 focus:bg-white dark:focus:bg-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-xl transition-all shadow-sm font-black text-rose-500 dark:text-rose-400 text-sm" 
                                            placeholder="0" required />
                                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-xs font-bold text-slate-400">
                                            Liter
                                        </div>
                                    </div>
                                    <p x-show="editCurrentStock !== null && editData.jumlah_bon > (parseFloat(editCurrentStock) + parseFloat(editData.original_jumlah_bon || 0))" class="text-[11px] text-rose-500 font-bold mt-1.5">
                                        ⚠️ Melebihi batas maksimal limit yang diizinkan!
                                    </p>
                                </div>
                            </div>

                            <div class="pt-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 border-t border-slate-100 dark:border-white/10">
                                <button type="button" @click="showEditModal = false"
                                    class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-bold text-xs uppercase tracking-wider transition-all">
                                    Batal
                                </button>
                                <button type="submit"
                                    :disabled="editCurrentStock !== null && editData.jumlah_bon > (parseFloat(editCurrentStock) + parseFloat(editData.original_jumlah_bon || 0))"
                                    class="w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/25 active:scale-95 transition-all text-xs uppercase tracking-wider disabled:opacity-40 disabled:cursor-not-allowed">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        <!-- Create Modal -->
        <template x-teleport="body">
            <div x-show="showCreateModal" class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;"
                aria-labelledby="modal-title-create" role="dialog" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div x-show="showCreateModal" x-transition.opacity
                        class="fixed inset-0 bg-slate-950/75 backdrop-blur-sm transition-opacity" aria-hidden="true"
                        @click="showCreateModal = false"></div>

                    <div x-show="showCreateModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-xl w-full">

                        <!-- Modal Header -->
                        <div class="px-6 py-5 border-b border-slate-100 dark:border-white/10 flex items-center justify-between bg-slate-50/60 dark:bg-slate-800/40">
                            <div class="flex items-center gap-3.5">
                                <div class="w-11 h-11 rounded-2xl bg-emerald-500/10 dark:bg-emerald-500/20 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shadow-lg shadow-emerald-500/10">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-black tracking-tight text-white uppercase" id="modal-title-create">Tambah Bon Hutang BBM</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Catat pengambilan BBM darurat saat kupon habis</p>
                                </div>
                            </div>
                            <button @click="showCreateModal = false" type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form action="{{ route('admin.hutang.store') }}" method="POST" class="p-6 space-y-5">
                            @csrf
                            
                            <!-- Satker Selection -->
                            <div>
                                <label for="create_satker_id" class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <span>Unit Kerja / Satker</span>
                                    <span class="text-rose-500">*</span>
                                </label>
                                <div class="mt-1.5">
                                    <select name="satker_id" id="create_satker_id" x-model="createData.satker_id" @change="fetchFormKendaraans($event.target.value); createData.kendaraan_id = ''"
                                        class="tom-select w-full" required>
                                        <option value="">-- Pilih Satker --</option>
                                        @foreach($satkers as $satker)
                                            <option value="{{ $satker->id }}">{{ $satker->nama_satker }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Kendaraan Selection -->
                            <div>
                                <div class="flex items-center justify-between">
                                    <label for="create_kendaraan_id" class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300">
                                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                        </svg>
                                        <span>Kendaraan Dinas</span>
                                        <span class="text-rose-500">*</span>
                                    </label>
                                    <span x-show="loadingFormKendaraan" class="text-[10px] text-amber-500 flex items-center gap-1 font-semibold">
                                        <svg class="animate-spin h-3 w-3 text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                        Memuat kendaraan...
                                    </span>
                                </div>
                                <div class="mt-1.5 relative">
                                    <select name="kendaraan_id" id="create_kendaraan_id" x-model="createData.kendaraan_id" required
                                        :disabled="loadingFormKendaraan || !createData.satker_id || createKendaraans.length === 0"
                                        class="block w-full px-4 py-2.5 pr-10 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 focus:bg-white dark:focus:bg-slate-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 rounded-xl transition-all shadow-sm font-semibold text-xs text-slate-800 dark:text-white disabled:opacity-50 disabled:cursor-not-allowed appearance-none cursor-pointer">
                                        <option value="" disabled selected x-text="!createData.satker_id ? '-- Pilih Satker Terlebih Dahulu --' : (loadingFormKendaraan ? 'Sedang memuat data kendaraan...' : (createKendaraans.length ? '-- Pilih Kendaraan (Saldo 0 L) --' : 'Tidak ada kendaraan bersaldo 0 L pada satker ini'))"></option>
                                        <template x-for="kend in createKendaraans" :key="kend.id">
                                            <option :value="kend.id" x-text="`${kend.no_polisi} - ${kend.jenis_kendaraan} (${kend.jenis_bbm})`" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-white py-1.5"></option>
                                        </template>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Alert jika semua kendaraan pada satker masih punya saldo atau terkunci hutang -->
                                <div x-show="createData.satker_id && !loadingFormKendaraan && createKendaraans.length === 0" x-cloak class="mt-2.5 p-3.5 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-300 text-xs flex items-center gap-3">
                                    <div class="p-1.5 rounded-xl bg-amber-500/20 text-amber-400 shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <span class="leading-relaxed font-medium">Semua kendaraan pada Satker ini masih memiliki saldo kupon BBM atau masih memiliki hutang belum lunas (saldo terkunci). Bon hutang baru hanya dapat diajukan jika saldo kendaraan 0 Liter dan tidak memiliki hutang belum lunas.</span>
                                </div>

                                <!-- Vehicle Saldo Alert / Status Box (Ketika kendaraan dipilih) -->
                                <div x-show="createSelectedKendaraan" x-cloak class="mt-2.5 transition-all">
                                    <div class="p-3.5 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 flex items-center justify-between">
                                        <div class="flex items-center gap-2.5 text-xs">
                                            <div class="p-1.5 rounded-xl bg-emerald-500/20 text-emerald-400 shrink-0">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="font-bold text-emerald-200 block text-xs" x-text="`${createSelectedKendaraan?.no_polisi} - ${createSelectedKendaraan?.jenis_kendaraan}`"></span>
                                                <span class="text-[10px] text-emerald-300/80">Saldo: 0 Liter • Siap mengajukan bon hutang</span>
                                            </div>
                                        </div>
                                        <div class="text-right pl-3 border-l border-emerald-500/20">
                                            <span class="text-[9px] text-slate-400 block font-semibold uppercase">Jenis BBM</span>
                                            <span class="text-xs font-black text-white uppercase tracking-wider" x-text="createSelectedKendaraan?.jenis_bbm"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Driver Name -->
                            <div>
                                <label for="create_nama_driver" class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>Nama Driver / Pengemudi</span>
                                    <span class="text-rose-500">*</span>
                                </label>
                                <div class="mt-1.5">
                                    <input id="create_nama_driver" name="nama_driver" type="text" x-model="createData.nama_driver" 
                                        class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 focus:bg-white dark:focus:bg-slate-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 rounded-xl transition-all shadow-sm font-semibold text-xs text-white" 
                                        placeholder="Masukkan nama driver yang membon" required />
                                </div>
                            </div>

                            <!-- Date and Amount Row -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="create_tanggal_bon" class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300">
                                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>Tanggal Bon</span>
                                        <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="mt-1.5">
                                        <input id="create_tanggal_bon" name="tanggal_bon" type="date" x-model="createData.tanggal_bon" 
                                            class="flatpickr block w-full py-2.5 px-3.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 focus:bg-white dark:focus:bg-slate-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 rounded-xl transition-all shadow-sm font-bold text-xs text-white" 
                                            required />
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between items-center">
                                        <label for="create_jumlah_bon" class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300">
                                            <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L7.05 15.12a2 2 0 00-1.022.547l-2.387 2.387a2 2 0 000 2.828l.141.141a2 2 0 002.828 0l2.628-2.628a2 2 0 012.33-.213l.317.158a2 2 0 002.33-.213l2.628-2.628a2 2 0 000-2.828l-.141-.141z" />
                                            </svg>
                                            <span>Jumlah Bon</span>
                                            <span class="text-rose-500">*</span>
                                        </label>
                                        <span x-show="createCurrentStock !== null" class="text-[10px] font-bold text-emerald-500 dark:text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-md border border-emerald-500/20">
                                            Stok: <span x-text="createCurrentStock"></span> L
                                        </span>
                                    </div>
                                    <div class="mt-1.5 relative">
                                        <input id="create_jumlah_bon" name="jumlah_bon" type="number" step="1" x-model="createData.jumlah_bon" 
                                            :max="createCurrentStock"
                                            :disabled="!createData.kendaraan_id || createVehicleHasSaldo"
                                            class="block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 focus:bg-white dark:focus:bg-slate-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 rounded-xl transition-all shadow-sm font-black text-rose-500 dark:text-rose-400 text-sm disabled:opacity-40 disabled:cursor-not-allowed" 
                                            placeholder="0" required />
                                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-xs font-bold text-slate-400">
                                            Liter
                                        </div>
                                    </div>
                                    <p x-show="createCurrentStock !== null && createData.jumlah_bon > createCurrentStock" class="text-[11px] text-rose-500 font-bold mt-1.5">
                                        ⚠️ Melebihi stok tangki yang tersedia (<span x-text="createCurrentStock"></span> L)!
                                    </p>
                                </div>
                            </div>

                            <div class="pt-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 border-t border-slate-100 dark:border-white/10">
                                <button type="button" @click="showCreateModal = false"
                                    class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-bold text-xs uppercase tracking-wider transition-all">
                                    Batal
                                </button>
                                <button type="submit"
                                    :disabled="createSubmitDisabled"
                                    class="w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/25 active:scale-95 transition-all text-xs uppercase tracking-wider disabled:opacity-40 disabled:cursor-not-allowed disabled:shadow-none">
                                    Simpan Data Hutang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <script>
        const hutangComponentData = () => ({
            showModal: false,
                showEditModal: false,
                showCreateModal: false,
                selectedHutang: null,
                selectedSatker: null,
                stocks: @json($stocks ?? []),
                hutangData: { nopol: '', bbm: '', jumlah: 0, saldo: 'Tidak ada' },
                editData: { id: '', satker_id: '', kendaraan_id: '', nama_driver: '', jumlah_bon: '', tanggal_bon: '', original_jumlah_bon: 0 },
                createData: { satker_id: '', kendaraan_id: '', nama_driver: '', jumlah_bon: '', tanggal_bon: '{{ date('Y-m-d') }}' },
                selectedKendaraan: '',
                selectedBbm: '',
                kendaraans: [],
                loadingKendaraan: false,
                get filteredKendaraans() {
                    return this.kendaraans.filter(k => k.jenis_bbm === this.selectedBbm);
                },
                formKendaraans: [],
                loadingFormKendaraan: false,
                normalizeBbmKey(bbm) {
                    if (!bbm) return '';
                    let upper = bbm.toUpperCase();
                    if (upper.includes('DEX')) return 'Pertamina Dex';
                    if (upper.includes('PERTAMAX')) return 'Pertamax';
                    return bbm;
                },
                formatLiter(value) {
                    const number = Number(value || 0);
                    return new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(number);
                },
                get createKendaraans() {
                    return this.formKendaraans.filter(k => Number(k.saldo || 0) <= 0);
                },
                get createSelectedKendaraan() {
                    if (!this.createData.kendaraan_id || !this.formKendaraans.length) return null;
                    return this.formKendaraans.find(k => k.id == this.createData.kendaraan_id) || null;
                },
                get createSelectedSaldo() {
                    return this.createSelectedKendaraan ? Number(this.createSelectedKendaraan.saldo || 0) : 0;
                },
                get createVehicleHasSaldo() {
                    return this.createSelectedKendaraan && this.createSelectedSaldo > 0;
                },
                get createSubmitDisabled() {
                    if (!this.createData.kendaraan_id) return true;
                    if (this.createVehicleHasSaldo) return true;
                    if (this.createCurrentStock !== null && (this.createData.jumlah_bon > this.createCurrentStock || this.createCurrentStock <= 0)) return true;
                    return false;
                },
                get createCurrentStock() {
                    if (!this.createData.kendaraan_id || !this.formKendaraans.length) return null;
                    const kend = this.formKendaraans.find(k => k.id == this.createData.kendaraan_id);
                    return kend ? (this.stocks[this.normalizeBbmKey(kend.jenis_bbm)] ?? null) : null;
                },
                get editCurrentStock() {
                    if (!this.editData.kendaraan_id || !this.formKendaraans.length) return null;
                    const kend = this.formKendaraans.find(k => k.id == this.editData.kendaraan_id);
                    return kend ? (this.stocks[this.normalizeBbmKey(kend.jenis_bbm)] ?? null) : null;
                },
                init() {
                    this.$watch('createData.satker_id', val => {
                        this.createData.kendaraan_id = '';
                        if (val) this.fetchFormKendaraans(val, false);
                        else this.formKendaraans = [];
                    });
                    this.$watch('editData.satker_id', val => {
                        this.editData.kendaraan_id = '';
                        if (val) this.fetchFormKendaraans(val, true);
                        else this.formKendaraans = [];
                    });
                },
                async fetchFormKendaraans(satkerId, includeAll = false) {
                    if (!satkerId) {
                        this.formKendaraans = [];
                        return;
                    }
                    this.loadingFormKendaraan = true;
                    try {
                        const response = await fetch(`/admin/hutang/get-kendaraan?satker_id=${satkerId}${includeAll ? '&include_all=1' : ''}`);
                        this.formKendaraans = await response.json();
                    } catch (error) {
                        console.error('Gagal mengambil data kendaraan:', error);
                        this.formKendaraans = [];
                    } finally {
                        this.loadingFormKendaraan = false;
                    }
                },
                async openModal(id, satkerId, bbm, nopol, jumlah, currentSaldo = null) {
                    this.selectedHutang = id;
                    this.selectedSatker = satkerId;
                    this.selectedBbm = bbm;
                    this.hutangData = { 
                        nopol, 
                        bbm, 
                        jumlah, 
                        saldo: (currentSaldo !== null && currentSaldo !== undefined) ? currentSaldo : 'Tidak ada' 
                    };
                    this.showModal = true;
                    
                    try {
                        if (!this.formKendaraans.some(k => k.no_polisi === nopol)) {
                            await this.fetchFormKendaraans(satkerId, true);
                        }
                        const foundKend = this.formKendaraans.find(k => k.no_polisi === nopol);
                        if (foundKend) {
                            this.hutangData.saldo = foundKend.saldo;
                        }
                    } catch (e) {
                        console.error('Error fetching kendaraan saldo:', e);
                    }
                },
                async openEditModal(hutang) {
                    this.editData = {
                        id: hutang.id,
                        satker_id: hutang.satker_id,
                        kendaraan_id: '',
                        nama_driver: hutang.nama_driver,
                        jumlah_bon: hutang.jumlah_bon,
                        original_jumlah_bon: hutang.jumlah_bon,
                        tanggal_bon: hutang.tanggal_bon
                    };
                    this.showEditModal = true;
                    
                    this.$nextTick(() => {
                        const satkerEl = document.getElementById('edit_satker_id');
                        if (satkerEl && satkerEl.tomselect) {
                            satkerEl.tomselect.setValue(hutang.satker_id);
                            satkerEl.tomselect.off('change');
                            satkerEl.tomselect.on('change', (val) => {
                                this.editData.satker_id = val;
                                this.editData.kendaraan_id = '';
                                if (val) this.fetchFormKendaraans(val, true);
                            });
                        }
                    });

                    await this.fetchFormKendaraans(hutang.satker_id, true);
                    const foundKend = this.formKendaraans.find(k => k.no_polisi === hutang.nopol);
                    if (foundKend) {
                        this.editData.kendaraan_id = foundKend.id;
                    }
                },
                openCreateModal() {
                    this.createData = { satker_id: '', kendaraan_id: '', nama_driver: '', jumlah_bon: '', tanggal_bon: '{{ date('Y-m-d') }}' };
                    this.formKendaraans = [];
                    this.showCreateModal = true;
                    
                    this.$nextTick(() => {
                        const satkerEl = document.getElementById('create_satker_id');
                        if (satkerEl && satkerEl.tomselect) {
                            satkerEl.tomselect.clear();
                            satkerEl.tomselect.off('change');
                            satkerEl.tomselect.on('change', (val) => {
                                this.createData.satker_id = val;
                                this.createData.kendaraan_id = '';
                                if (val) this.fetchFormKendaraans(val, false);
                            });
                        }
                    });
                }
            });
            
        if (typeof window.Alpine !== 'undefined') {
            window.Alpine.data('hutangComponent', hutangComponentData);
        } else {
            document.addEventListener('alpine:init', () => {
                window.Alpine.data('hutangComponent', hutangComponentData);
            });
        }
    </script>
</x-app-layout>
