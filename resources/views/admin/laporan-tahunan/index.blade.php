<x-app-layout>
    @php
        $total_pendapatan_pertamax = 0;
        $total_pendapatan_dex = 0;
        $total_pemakaian_pertamax = 0;
        $total_pemakaian_dex = 0;
        $total_sisa_pertamax = 0;
        $total_sisa_dex = 0;

        foreach ($reportData as $data) {
            $total_pendapatan_pertamax += $data['pendapatan_pertamax'];
            $total_pendapatan_dex += $data['pendapatan_dex'];
            $total_pemakaian_pertamax += $data['pemakaian_pertamax'];
            $total_pemakaian_dex += $data['pemakaian_dex'];
            $total_sisa_pertamax += $data['sisa_pertamax'];
            $total_sisa_dex += $data['sisa_dex'];
        }

        $grandPendapatan = $total_pendapatan_pertamax + $total_pendapatan_dex;
        $grandPemakaian = $total_pemakaian_pertamax + $total_pemakaian_dex;
        $grandSisa = $total_sisa_pertamax + $total_sisa_dex;
    @endphp

    <div class="p-4 sm:p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-900 p-5 sm:p-6 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-brand-primary/10 text-brand-primary dark:bg-white/10 dark:text-white flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white tracking-tight">Laporan Tahunan</h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-brand-primary/10 text-brand-primary dark:bg-white/10 dark:text-white">
                            Tahun {{ $year }}
                        </span>
                    </div>
                    <p class="mt-0.5 text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                        Rekapitulasi total pendapatan, pemakaian, dan sisa saldo BBM tahunan seluruh Satker.
                    </p>
                </div>
            </div>

            <!-- Action Button -->
            <div class="flex items-center gap-2 self-stretch sm:self-auto">
                <a href="{{ route('admin.laporan-tahunan.print', ['year' => $year]) }}" target="_blank"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white rounded-xl font-bold text-xs sm:text-sm shadow-lg shadow-rose-600/20 hover:shadow-rose-600/30 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak PDF
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
                    <span class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white tabular-nums">
                        {{ number_format($grandPendapatan, 0, ',', '.') }}
                    </span>
                    <span class="text-xs font-bold text-slate-400">Liter</span>
                </div>
                <div class="mt-3.5 pt-3 border-t border-slate-100 dark:border-white/5 flex flex-wrap gap-1.5">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-semibold bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border border-emerald-500/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Pertamax: {{ number_format($total_pendapatan_pertamax, 0, ',', '.') }} L
                    </span>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-semibold bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border border-emerald-500/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Dex: {{ number_format($total_pendapatan_dex, 0, ',', '.') }} L
                    </span>
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
                    <span class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white tabular-nums">
                        {{ number_format($grandPemakaian, 0, ',', '.') }}
                    </span>
                    <span class="text-xs font-bold text-slate-400">Liter</span>
                </div>
                <div class="mt-3.5 pt-3 border-t border-slate-100 dark:border-white/5 flex flex-wrap gap-1.5">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-semibold bg-blue-500/10 text-blue-700 dark:text-blue-300 border border-blue-500/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                        Pertamax: {{ number_format($total_pemakaian_pertamax, 0, ',', '.') }} L
                    </span>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-semibold bg-blue-500/10 text-blue-700 dark:text-blue-300 border border-blue-500/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                        Dex: {{ number_format($total_pemakaian_dex, 0, ',', '.') }} L
                    </span>
                </div>
            </div>

            <!-- Card 3: Sisa Pemakaian -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 p-5 shadow-sm relative overflow-hidden group hover:border-purple-500/40 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Sisa Saldo BBM</span>
                    <div class="w-9 h-9 rounded-xl {{ $grandSisa < 0 ? 'bg-rose-500/10 text-rose-600 dark:text-rose-400' : 'bg-purple-500/10 text-purple-600 dark:text-purple-400' }} flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2" />
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
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-semibold {{ $total_sisa_pertamax < 0 ? 'bg-rose-500/10 text-rose-700 dark:text-rose-300 border-rose-500/20' : 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border-emerald-500/20' }} border">
                        <span class="w-1.5 h-1.5 rounded-full {{ $total_sisa_pertamax < 0 ? 'bg-rose-500' : 'bg-emerald-500' }}"></span>
                        Pertamax: {{ ($total_sisa_pertamax >= 0 ? '+' : '') . number_format($total_sisa_pertamax, 0, ',', '.') }} L
                    </span>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-semibold {{ $total_sisa_dex < 0 ? 'bg-rose-500/10 text-rose-700 dark:text-rose-300 border-rose-500/20' : 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border-emerald-500/20' }} border">
                        <span class="w-1.5 h-1.5 rounded-full {{ $total_sisa_dex < 0 ? 'bg-rose-500' : 'bg-emerald-500' }}"></span>
                        Dex: {{ ($total_sisa_dex >= 0 ? '+' : '') . number_format($total_sisa_dex, 0, ',', '.') }} L
                    </span>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm p-4 sm:p-5">
            <form action="{{ route('admin.laporan-tahunan.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-end gap-3.5">
                <div class="flex-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Tahun Laporan
                        </span>
                    </label>
                    <select name="year" onchange="this.form.submit()" class="tom-select w-full">
                        @foreach($availableYears as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                Tahun {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="px-4 py-2.5 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-xl font-bold text-xs transition flex items-center justify-center gap-1.5 shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.laporan-tahunan.index') }}"
                        class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-xs transition flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset
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
                        <h2 class="text-base font-black text-slate-800 dark:text-white">Rekapan BBM Tahun {{ $year }}</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Menampilkan rekapitulasi data seluruh Satker ({{ count($reportData) }} Satker).</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-white/10">
                        <span class="w-2 h-2 rounded-full bg-brand-primary"></span>
                        Total Satker: {{ count($reportData) }}
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
                            <th colspan="2"
                                class="px-4 py-2.5 text-center font-black uppercase tracking-wider text-[11px] bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border-r border-slate-200 dark:border-white/10">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                    </svg>
                                    JUMLAH PENDAPATAN (L)
                                </span>
                            </th>
                            <th colspan="2"
                                class="px-4 py-2.5 text-center font-black uppercase tracking-wider text-[11px] bg-blue-500/10 text-blue-700 dark:text-blue-400 border-r border-slate-200 dark:border-white/10">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    PEMAKAIAN (L)
                                </span>
                            </th>
                            <th colspan="2"
                                class="px-4 py-2.5 text-center font-black uppercase tracking-wider text-[11px] bg-purple-500/10 text-purple-700 dark:text-purple-400">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2" />
                                    </svg>
                                    SISA PEMAKAIAN (L)
                                </span>
                            </th>
                        </tr>
                        <!-- Header Level 2: BBM Types -->
                        <tr class="border-b border-slate-200 dark:border-white/10 bg-slate-50/50 dark:bg-slate-800/30">
                            <th class="px-4 py-2.5 text-center text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 border-r border-slate-200 dark:border-white/10 whitespace-nowrap">
                                PERTAMAX
                            </th>
                            <th class="px-4 py-2.5 text-center text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 border-r border-slate-200 dark:border-white/10 whitespace-nowrap">
                                PERTAMINA DEX
                            </th>
                            <th class="px-4 py-2.5 text-center text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 border-r border-slate-200 dark:border-white/10 whitespace-nowrap">
                                PERTAMAX
                            </th>
                            <th class="px-4 py-2.5 text-center text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 border-r border-slate-200 dark:border-white/10 whitespace-nowrap">
                                PERTAMINA DEX
                            </th>
                            <th class="px-4 py-2.5 text-center text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 border-r border-slate-200 dark:border-white/10 whitespace-nowrap">
                                PERTAMAX
                            </th>
                            <th class="px-4 py-2.5 text-center text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                PERTAMINA DEX
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5 bg-white dark:bg-slate-900">
                        @forelse($reportData as $index => $data)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                <!-- No -->
                                <td class="px-3 py-3.5 whitespace-nowrap text-center font-mono font-bold text-slate-500 dark:text-slate-400 border-r border-slate-200 dark:border-white/10">
                                    {{ $index + 1 }}
                                </td>

                                <!-- Satker Column with Avatar -->
                                <td class="px-5 py-3.5 whitespace-nowrap border-r border-slate-200 dark:border-white/10">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-brand-primary/10 text-brand-primary dark:bg-white/10 dark:text-white flex items-center justify-center font-black text-xs shrink-0">
                                            {{ substr(trim($data['satker']), 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 dark:text-white text-xs sm:text-sm">
                                                {{ strtoupper($data['satker']) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Pendapatan Pertamax -->
                                <td class="px-4 py-3.5 whitespace-nowrap text-center border-r border-slate-200 dark:border-white/10">
                                    <span class="font-mono font-bold text-sm text-slate-800 dark:text-slate-200 tabular-nums">
                                        {{ number_format($data['pendapatan_pertamax'], 0, ',', '.') }}
                                    </span>
                                    <span class="text-[10px] font-medium text-slate-400 ml-0.5">L</span>
                                </td>

                                <!-- Pendapatan Dex -->
                                <td class="px-4 py-3.5 whitespace-nowrap text-center border-r border-slate-200 dark:border-white/10">
                                    <span class="font-mono font-bold text-sm text-slate-800 dark:text-slate-200 tabular-nums">
                                        {{ number_format($data['pendapatan_dex'], 0, ',', '.') }}
                                    </span>
                                    <span class="text-[10px] font-medium text-slate-400 ml-0.5">L</span>
                                </td>

                                <!-- Pemakaian Pertamax -->
                                <td class="px-4 py-3.5 whitespace-nowrap text-center border-r border-slate-200 dark:border-white/10">
                                    <span class="font-mono font-bold text-sm text-slate-800 dark:text-slate-200 tabular-nums">
                                        {{ number_format($data['pemakaian_pertamax'], 0, ',', '.') }}
                                    </span>
                                    <span class="text-[10px] font-medium text-slate-400 ml-0.5">L</span>
                                </td>

                                <!-- Pemakaian Dex -->
                                <td class="px-4 py-3.5 whitespace-nowrap text-center border-r border-slate-200 dark:border-white/10">
                                    <span class="font-mono font-bold text-sm text-slate-800 dark:text-slate-200 tabular-nums">
                                        {{ number_format($data['pemakaian_dex'], 0, ',', '.') }}
                                    </span>
                                    <span class="text-[10px] font-medium text-slate-400 ml-0.5">L</span>
                                </td>

                                <!-- Sisa Pertamax -->
                                <td class="px-4 py-3.5 whitespace-nowrap text-center border-r border-slate-200 dark:border-white/10">
                                    @if($data['sisa_pertamax'] < 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-black font-mono bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 tabular-nums">
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                            </svg>
                                            {{ number_format($data['sisa_pertamax'], 0, ',', '.') }} L
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-black font-mono bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 tabular-nums">
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                            </svg>
                                            +{{ number_format($data['sisa_pertamax'], 0, ',', '.') }} L
                                        </span>
                                    @endif
                                </td>

                                <!-- Sisa Dex -->
                                <td class="px-4 py-3.5 whitespace-nowrap text-center">
                                    @if($data['sisa_dex'] < 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-black font-mono bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 tabular-nums">
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                            </svg>
                                            {{ number_format($data['sisa_dex'], 0, ',', '.') }} L
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-black font-mono bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 tabular-nums">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                            </svg>
                                            +{{ number_format($data['sisa_dex'], 0, ',', '.') }} L
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 text-slate-400 rounded-2xl flex items-center justify-center mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-bold text-slate-600 dark:text-slate-400">Tidak ada data rekapitulasi untuk tahun ini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <!-- Table Footer: TOTAL -->
                    @if(count($reportData) > 0)
                        <tfoot class="bg-slate-50/90 dark:bg-slate-800/80 border-t-2 border-slate-300 dark:border-white/20 font-black">
                            <tr>
                                <td colspan="2" class="px-5 py-4 whitespace-nowrap text-center text-xs font-black uppercase tracking-wider text-slate-800 dark:text-white border-r border-slate-200 dark:border-white/10">
                                    TOTAL REKAPITULASI
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-black font-mono text-emerald-700 dark:text-emerald-400 border-r border-slate-200 dark:border-white/10 tabular-nums">
                                    {{ number_format($total_pendapatan_pertamax, 0, ',', '.') }} L
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-black font-mono text-emerald-700 dark:text-emerald-400 border-r border-slate-200 dark:border-white/10 tabular-nums">
                                    {{ number_format($total_pendapatan_dex, 0, ',', '.') }} L
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-black font-mono text-blue-700 dark:text-blue-400 border-r border-slate-200 dark:border-white/10 tabular-nums">
                                    {{ number_format($total_pemakaian_pertamax, 0, ',', '.') }} L
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-black font-mono text-blue-700 dark:text-blue-400 border-r border-slate-200 dark:border-white/10 tabular-nums">
                                    {{ number_format($total_pemakaian_dex, 0, ',', '.') }} L
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center border-r border-slate-200 dark:border-white/10">
                                    @if($total_sisa_pertamax < 0)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-black font-mono bg-rose-500/20 text-rose-700 dark:text-rose-300 border border-rose-500/30 tabular-nums">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                            </svg>
                                            {{ number_format($total_sisa_pertamax, 0, ',', '.') }} L
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-black font-mono bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 tabular-nums">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                            </svg>
                                            +{{ number_format($total_sisa_pertamax, 0, ',', '.') }} L
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    @if($total_sisa_dex < 0)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-black font-mono bg-rose-500/20 text-rose-700 dark:text-rose-300 border border-rose-500/30 tabular-nums">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                            </svg>
                                            {{ number_format($total_sisa_dex, 0, ',', '.') }} L
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-black font-mono bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 tabular-nums">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                            </svg>
                                            +{{ number_format($total_sisa_dex, 0, ',', '.') }} L
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>

            <!-- Table Note Footer -->
            <div class="px-5 py-3.5 bg-slate-50/50 dark:bg-slate-800/30 border-t border-slate-200 dark:border-white/10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 text-xs text-slate-500 dark:text-slate-400">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span><strong>Keterangan:</strong> Nilai minus berwarna merah (-) menandakan volume pemakaian melebihi jumlah pendapatan BBM pada tahun ini.</span>
                </div>
                <div class="text-[11px] font-semibold text-slate-400">
                    Tahun Anggaran: {{ $year }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

