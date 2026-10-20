<x-app-layout>
    @php
        $sumPendapatan = [];
        $sumPemakaian = [];
        $sumSisa = [];
        foreach ($allBbmTypes as $jenis) {
            $sumPendapatan[$jenis] = 0;
            $sumPemakaian[$jenis] = 0;
            $sumSisa[$jenis] = 0;
        }

        foreach($satkers as $s) {
            foreach($allBbmTypes as $jenis) {
                $sumPendapatan[$jenis] += $pendapatan[$s->id][$jenis] ?? 0;
                $sumPemakaian[$jenis] += $pemakaian[$s->id][$jenis] ?? 0;
                $sumSisa[$jenis] += $sisaBbm[$s->id][$jenis] ?? 0;
            }
        }

        $grandPendapatan = array_sum($sumPendapatan);
        $grandPemakaian = array_sum($sumPemakaian);
        $grandSisa = array_sum($sumSisa);
    @endphp

    <div class="p-4 sm:p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-900 p-5 sm:p-6 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-brand-primary/10 text-brand-primary dark:bg-white/10 dark:text-white flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white tracking-tight">Laporan Per 3 Bulan</h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-brand-primary/10 text-brand-primary dark:bg-white/10 dark:text-white">
                            Triwulan {{ $triwulan }}
                        </span>
                    </div>
                    <p class="mt-0.5 text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                        Rekapitulasi total pendapatan, pemakaian, dan sisa BBM per Satker dan Personel.
                    </p>
                </div>
            </div>

            <!-- Export & Print Buttons -->
            <div class="flex flex-wrap items-center gap-2.5 self-stretch sm:self-auto">
                <a href="{{ route('admin.laporan-triwulan.print', request()->query()) }}" target="_blank"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center px-4 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white rounded-xl font-bold text-xs sm:text-sm shadow-lg shadow-rose-600/20 hover:shadow-rose-600/30 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak PDF
                </a>
                <a href="{{ route('admin.laporan-triwulan.export', request()->query()) }}"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white rounded-xl font-bold text-xs sm:text-sm shadow-lg shadow-emerald-600/20 hover:shadow-emerald-600/30 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export Excel
                </a>
            </div>
        </div>

        <!-- KPI Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Card 1: Total Pendapatan -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 p-5 shadow-sm relative overflow-hidden group hover:border-emerald-500/40 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Pendapatan Seluruh Satker</span>
                    <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-1.5">
                    <span class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tabular-nums">
                        {{ number_format($grandPendapatan, 0, ',', '.') }}
                    </span>
                    <span class="text-xs font-bold text-slate-400">Liter</span>
                </div>
                <div class="mt-3.5 pt-3 border-t border-slate-100 dark:border-white/5 flex flex-wrap gap-1.5">
                    @foreach($allBbmTypes as $jenis)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-semibold bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border border-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            {{ $jenis }}: {{ number_format($sumPendapatan[$jenis] ?? 0, 0, ',', '.') }} L
                        </span>
                    @endforeach
                </div>
            </div>

            <!-- Card 2: Total Pemakaian -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 p-5 shadow-sm relative overflow-hidden group hover:border-blue-500/40 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Pemakaian Seluruh Satker</span>
                    <div class="w-9 h-9 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-1.5">
                    <span class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tabular-nums">
                        {{ number_format($grandPemakaian, 0, ',', '.') }}
                    </span>
                    <span class="text-xs font-bold text-slate-400">Liter</span>
                </div>
                <div class="mt-3.5 pt-3 border-t border-slate-100 dark:border-white/5 flex flex-wrap gap-1.5">
                    @foreach($allBbmTypes as $jenis)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-semibold bg-blue-500/10 text-blue-700 dark:text-blue-300 border border-blue-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                            {{ $jenis }}: {{ number_format($sumPemakaian[$jenis] ?? 0, 0, ',', '.') }} L
                        </span>
                    @endforeach
                </div>
            </div>

            <!-- Card 3: Sisa BBM -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 p-5 shadow-sm relative overflow-hidden group hover:border-purple-500/40 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Saldo Sisa BBM</span>
                    <div class="w-9 h-9 rounded-xl {{ $grandSisa < 0 ? 'bg-rose-500/10 text-rose-600 dark:text-rose-400' : 'bg-purple-500/10 text-purple-600 dark:text-purple-400' }} flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-2xl sm:text-3xl font-black tabular-nums {{ $grandSisa < 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                        {{ ($grandSisa >= 0 ? '+' : '') . number_format($grandSisa, 0, ',', '.') }}
                    </span>
                    <span class="text-xs font-bold text-slate-400">Liter</span>
                    <span class="ml-auto inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $grandSisa < 0 ? 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20' : 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' }}">
                        {{ $grandSisa < 0 ? 'Defisit' : 'Surplus' }}
                    </span>
                </div>
                <div class="mt-3.5 pt-3 border-t border-slate-100 dark:border-white/5 flex flex-wrap gap-1.5">
                    @foreach($allBbmTypes as $jenis)
                        @php $s = $sumSisa[$jenis] ?? 0; @endphp
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-semibold {{ $s < 0 ? 'bg-rose-500/10 text-rose-700 dark:text-rose-300 border-rose-500/20' : 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border-emerald-500/20' }} border">
                            <span class="w-1.5 h-1.5 rounded-full {{ $s < 0 ? 'bg-rose-500' : 'bg-emerald-500' }}"></span>
                            {{ $jenis }}: {{ ($s >= 0 ? '+' : '') . number_format($s, 0, ',', '.') }} L
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm p-4 sm:p-5 relative z-30">
            <form action="{{ route('admin.laporan-triwulan.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-end gap-3.5">
                <div class="flex-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Tahun
                        </span>
                    @php
                        $currentYear = date('Y');
                        $tahunOptions = [];
                        for($i = $currentYear - 2; $i <= $currentYear + 1; $i++) {
                            $tahunOptions[$i] = (string)$i;
                        }
                    @endphp
                    <x-filter-select 
                        name="tahun" 
                        :value="request('tahun', date('Y'))" 
                        placeholder="Pilih Tahun" 
                        icon="calendar" 
                        :searchable="false" 
                        :auto-submit="true" 
                        :options="$tahunOptions" 
                    />
                </div>

                <div class="flex-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Triwulan
                        </span>
                    </label>
                    <x-filter-select 
                        name="triwulan" 
                        :value="request('triwulan', 1)" 
                        placeholder="Pilih Triwulan" 
                        icon="calendar" 
                        :searchable="false" 
                        :auto-submit="true" 
                        :options="[
                            '1' => 'Triwulan I (Jan - Mar)',
                            '2' => 'Triwulan II (Apr - Jun)',
                            '3' => 'Triwulan III (Jul - Sep)',
                            '4' => 'Triwulan IV (Okt - Des)'
                        ]" 
                    />
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.laporan-triwulan.index') }}"
                        class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-xs transition flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Filter
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden">
            <!-- Table Header Bar -->
            <div class="px-5 py-4 border-b border-slate-200 dark:border-white/10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-brand-primary/10 text-brand-primary dark:bg-white/10 dark:text-white flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M3 14h18m-9-4v8m-7 4h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-black text-slate-800 dark:text-white">Rekapan Periode: {{ $periodeLabel }}</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Menampilkan rekapitulasi data seluruh Satker ({{ count($satkers) }} Satker).</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-white/10">
                        <span class="w-2 h-2 rounded-full bg-brand-primary"></span>
                        Total Satker: {{ count($satkers) }}
                    </span>
                </div>
            </div>

            <!-- Table Responsive Wrapper -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-xs border-collapse">
                    <thead>
                        <!-- Header Level 1: Category Groups -->
                        <tr class="border-b border-slate-200 dark:border-white/10">
                            <th rowspan="2"
                                class="px-3 py-3.5 text-center bg-slate-50/80 dark:bg-slate-800/50 text-slate-600 dark:text-slate-300 font-black uppercase tracking-wider text-[11px] w-12 align-middle border-r border-slate-200 dark:border-white/10">
                                NO
                            </th>
                            <th rowspan="2"
                                class="px-5 py-3.5 bg-slate-50/80 dark:bg-slate-800/50 text-slate-600 dark:text-slate-300 font-black uppercase tracking-wider text-[11px] align-middle border-r border-slate-200 dark:border-white/10">
                                SATKER
                            </th>
                            <th colspan="{{ count($allBbmTypes) }}"
                                class="px-4 py-2.5 text-center font-black uppercase tracking-wider text-[11px] bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border-r border-slate-200 dark:border-white/10">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                    </svg>
                                    JUMLAH PENDAPATAN (L)
                                </span>
                            </th>
                            <th colspan="{{ count($allBbmTypes) }}"
                                class="px-4 py-2.5 text-center font-black uppercase tracking-wider text-[11px] bg-blue-500/10 text-blue-700 dark:text-blue-400 border-r border-slate-200 dark:border-white/10">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    PEMAKAIAN (L)
                                </span>
                            </th>
                            <th colspan="{{ count($allBbmTypes) }}"
                                class="px-4 py-2.5 text-center font-black uppercase tracking-wider text-[11px] bg-purple-500/10 text-purple-700 dark:text-purple-400">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2" />
                                    </svg>
                                    SISA BBM (L)
                                </span>
                            </th>
                        </tr>
                        <!-- Header Level 2: BBM Types -->
                        <tr class="border-b border-slate-200 dark:border-white/10 bg-slate-50/50 dark:bg-slate-800/30">
                            @foreach($allBbmTypes as $jenis)
                                <th class="px-4 py-2.5 text-center text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 border-r border-slate-200 dark:border-white/10 whitespace-nowrap">
                                    {{ $jenis }}
                                </th>
                            @endforeach
                            @foreach($allBbmTypes as $jenis)
                                <th class="px-4 py-2.5 text-center text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 border-r border-slate-200 dark:border-white/10 whitespace-nowrap">
                                    {{ $jenis }}
                                </th>
                            @endforeach
                            @foreach($allBbmTypes as $jenis)
                                <th class="px-4 py-2.5 text-center text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 {{ !$loop->last ? 'border-r border-slate-200 dark:border-white/10' : '' }} whitespace-nowrap">
                                    {{ $jenis }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5 bg-white dark:bg-slate-900">
                        @foreach($satkers as $satker)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                <!-- No -->
                                <td class="px-3 py-3.5 whitespace-nowrap text-center font-mono font-bold text-slate-500 dark:text-slate-400 border-r border-slate-200 dark:border-white/10">
                                    {{ $loop->iteration }}
                                </td>

                                <!-- Satker Column with Avatar -->
                                <td class="px-5 py-3.5 whitespace-nowrap border-r border-slate-200 dark:border-white/10">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-brand-primary/10 text-brand-primary dark:bg-white/10 dark:text-white flex items-center justify-center font-black text-xs shrink-0">
                                            {{ substr(trim($satker->nama_satker), 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 dark:text-white text-xs sm:text-sm">
                                                {{ strtoupper($satker->nama_satker) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Pendapatan Columns -->
                                @foreach($allBbmTypes as $jenis)
                                    @php
                                        $valP = $pendapatan[$satker->id][$jenis] ?? 0;
                                    @endphp
                                    <td class="px-4 py-3.5 whitespace-nowrap text-center border-r border-slate-200 dark:border-white/10">
                                        <span class="font-mono font-bold text-sm text-slate-800 dark:text-slate-200 tabular-nums">
                                            {{ rtrim(rtrim(number_format($valP, 2, ',', '.'), '0'), ',') }}
                                        </span>
                                        <span class="text-[10px] font-medium text-slate-400 ml-0.5">L</span>
                                    </td>
                                @endforeach

                                <!-- Pemakaian Columns -->
                                @foreach($allBbmTypes as $jenis)
                                    @php
                                        $valM = $pemakaian[$satker->id][$jenis] ?? 0;
                                    @endphp
                                    <td class="px-4 py-3.5 whitespace-nowrap text-center border-r border-slate-200 dark:border-white/10">
                                        <span class="font-mono font-bold text-sm text-slate-800 dark:text-slate-200 tabular-nums">
                                            {{ rtrim(rtrim(number_format($valM, 2, ',', '.'), '0'), ',') }}
                                        </span>
                                        <span class="text-[10px] font-medium text-slate-400 ml-0.5">L</span>
                                    </td>
                                @endforeach

                                <!-- Sisa BBM Columns with Pill Badges -->
                                @foreach($allBbmTypes as $jenis)
                                    @php
                                        $sisa = $sisaBbm[$satker->id][$jenis] ?? 0;
                                    @endphp
                                    <td class="px-4 py-3.5 whitespace-nowrap text-center {{ !$loop->last ? 'border-r border-slate-200 dark:border-white/10' : '' }}">
                                        @if($sisa < 0)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-black font-mono bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 tabular-nums">
                                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                                </svg>
                                                {{ rtrim(rtrim(number_format($sisa, 2, ',', '.'), '0'), ',') }} L
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-black font-mono bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 tabular-nums">
                                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                                </svg>
                                                +{{ rtrim(rtrim(number_format($sisa, 2, ',', '.'), '0'), ',') }} L
                                            </span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                    <!-- Table Footer: TOTAL -->
                    <tfoot class="bg-slate-50/90 dark:bg-slate-800/80 border-t-2 border-slate-300 dark:border-white/20 font-black">
                        <tr>
                            <td colspan="2" class="px-5 py-4 whitespace-nowrap text-center text-xs font-black uppercase tracking-wider text-slate-800 dark:text-white border-r border-slate-200 dark:border-white/10">
                                TOTAL REKAPITULASI
                            </td>
                            @foreach($allBbmTypes as $jenis)
                                <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-black font-mono text-emerald-700 dark:text-emerald-400 border-r border-slate-200 dark:border-white/10 tabular-nums">
                                    {{ number_format($sumPendapatan[$jenis], 0, ',', '.') }} L
                                </td>
                            @endforeach
                            @foreach($allBbmTypes as $jenis)
                                <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-black font-mono text-blue-700 dark:text-blue-400 border-r border-slate-200 dark:border-white/10 tabular-nums">
                                    {{ number_format($sumPemakaian[$jenis], 0, ',', '.') }} L
                                </td>
                            @endforeach
                            @foreach($allBbmTypes as $jenis)
                                @php $totalS = $sumSisa[$jenis]; @endphp
                                <td class="px-4 py-4 whitespace-nowrap text-center {{ !$loop->last ? 'border-r border-slate-200 dark:border-white/10' : '' }}">
                                    @if($totalS < 0)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-black font-mono bg-rose-500/20 text-rose-700 dark:text-rose-300 border border-rose-500/30 tabular-nums">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                            </svg>
                                            {{ number_format($totalS, 0, ',', '.') }} L
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-black font-mono bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 tabular-nums">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                            </svg>
                                            +{{ number_format($totalS, 0, ',', '.') }} L
                                        </span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Table Note Footer -->
            <div class="px-5 py-3.5 bg-slate-50/50 dark:bg-slate-800/30 border-t border-slate-200 dark:border-white/10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 text-xs text-slate-500 dark:text-slate-400">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span><strong>Keterangan:</strong> Nilai minus berwarna merah (-) menandakan volume pemakaian melebihi jumlah pendapatan BBM pada triwulan ini.</span>
                </div>
                <div class="text-[11px] font-semibold text-slate-400">
                    Periode: {{ $periodeLabel }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
