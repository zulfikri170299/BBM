<x-app-layout>
    <div class="py-6 px-3 sm:px-6 lg:px-8 min-h-screen">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white/70 dark:bg-slate-900/60 backdrop-blur-xl p-4 sm:p-6 rounded-3xl border border-slate-200/80 dark:border-white/10 shadow-sm">
                <div class="flex items-center gap-3.5 sm:gap-4">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-purple-600 text-white flex items-center justify-center shadow-lg shadow-indigo-500/25 shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20 text-[10px] font-black uppercase tracking-widest mb-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                            Laporan Penyaluran • Meteran
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                            Rekapan Pengisian
                        </h1>
                        <p class="hidden sm:block text-xs text-slate-500 dark:text-slate-400 font-medium">
                            Laporan akumulasi pengisian BBM berdasarkan pencatatan angka meter dispenser SPBP.
                        </p>
                    </div>
                </div>

                <!-- Filter & Action Controls -->
                <div class="flex flex-wrap sm:flex-nowrap items-center gap-2.5">
                    <!-- Form Filter Bulan & Tahun -->
                    @php
                        $monthOptions = [];
                        foreach(range(1, 12) as $m) {
                            $monthOptions[$m] = \Carbon\Carbon::create()->month($m)->translatedFormat('F');
                        }
                        $yearOptions = [];
                        foreach(range(date('Y'), 2024) as $y) {
                            $yearOptions[$y] = (string)$y;
                        }
                    @endphp
                    <form action="{{ route('petugas.rekapan.index') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
                        <div class="w-40">
                            <x-filter-select 
                                name="month" 
                                :value="$selectedMonth" 
                                placeholder="Pilih Bulan" 
                                icon="calendar" 
                                :searchable="false" 
                                :auto-submit="true" 
                                :options="$monthOptions" 
                            />
                        </div>

                        <div class="w-28">
                            <x-filter-select 
                                name="year" 
                                :value="$selectedYear" 
                                placeholder="Pilih Tahun" 
                                icon="calendar" 
                                :searchable="false" 
                                :auto-submit="true" 
                                :options="$yearOptions" 
                            />
                        </div>
                    </form>

                    <!-- Tombol Cetak PDF -->
                    <a href="{{ route('petugas.rekapan.print', ['month' => $selectedMonth, 'year' => $selectedYear]) }}"
                        target="_blank"
                        class="flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 hover:opacity-95 text-white rounded-2xl font-black text-xs uppercase tracking-wider shadow-lg shadow-indigo-500/25 active:scale-[0.98] transition-all w-full sm:w-auto shrink-0 group">
                        <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                            </path>
                        </svg>
                        <span>Cetak PDF</span>
                    </a>
                </div>
            </div>

            <!-- Grand Total Summary Cards (Pertamax & Dex) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <!-- 1. Total Pertamax Card (Vibrant Royal Blue Gradient) -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-900 text-white p-5 sm:p-6 shadow-xl shadow-blue-500/20 border border-blue-400/30 group hover:shadow-2xl hover:shadow-blue-500/30 transition-all duration-300 flex flex-col justify-between">
                    <div class="absolute -right-10 -top-10 w-36 h-36 bg-cyan-400/20 rounded-full blur-2xl pointer-events-none group-hover:scale-125 transition-transform duration-500"></div>

                    <div class="relative z-10 flex flex-col justify-between h-full space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center shrink-0 shadow-inner">
                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black text-blue-200 uppercase tracking-widest block">Total Pertamax</span>
                                    <h4 class="text-xs font-semibold text-blue-100/80">Meteran Penyaluran</h4>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-white/20 backdrop-blur-sm text-cyan-200 border border-white/25">
                                <span class="w-1.5 h-1.5 rounded-full bg-cyan-300 animate-pulse"></span>
                                RON 92
                            </span>
                        </div>

                        <div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl sm:text-4xl font-black text-white font-mono tracking-tight tabular-nums">
                                    {{ rtrim(rtrim(number_format($totalPertamax, 2, ',', '.'), '0'), ',') }}
                                </span>
                                <span class="text-sm font-extrabold text-blue-200">Liter</span>
                            </div>
                            <p class="mt-1.5 text-[11px] text-blue-200/80 font-medium">
                                Periode: {{ \Carbon\Carbon::create()->month($selectedMonth)->translatedFormat('F') }} {{ $selectedYear }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 2. Total Dex Card (Vibrant Ruby / Crimson Gradient) -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-rose-600 via-rose-700 to-red-900 text-white p-5 sm:p-6 shadow-xl shadow-rose-500/20 border border-rose-400/30 group hover:shadow-2xl hover:shadow-rose-500/30 transition-all duration-300 flex flex-col justify-between">
                    <div class="absolute -right-10 -top-10 w-36 h-36 bg-amber-400/20 rounded-full blur-2xl pointer-events-none group-hover:scale-125 transition-transform duration-500"></div>

                    <div class="relative z-10 flex flex-col justify-between h-full space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center shrink-0 shadow-inner">
                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black text-rose-200 uppercase tracking-widest block">Total Dex</span>
                                    <h4 class="text-xs font-semibold text-rose-100/80">Meteran Penyaluran</h4>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-white/20 backdrop-blur-sm text-rose-100 border border-white/25">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-300 animate-pulse"></span>
                                CN 53
                            </span>
                        </div>

                        <div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl sm:text-4xl font-black text-white font-mono tracking-tight tabular-nums">
                                    {{ rtrim(rtrim(number_format($totalDex, 2, ',', '.'), '0'), ',') }}
                                </span>
                                <span class="text-sm font-extrabold text-rose-200">Liter</span>
                            </div>
                            <p class="mt-1.5 text-[11px] text-rose-200/80 font-medium">
                                Periode: {{ \Carbon\Carbon::create()->month($selectedMonth)->translatedFormat('F') }} {{ $selectedYear }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Card: Rincian Log Meteran Harian -->
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200/80 dark:border-white/10 rounded-3xl shadow-xl overflow-hidden transition-all hover:border-slate-300 dark:hover:border-white/20">
                <!-- Header Card Table -->
                <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-slate-800/40 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-black text-white">Rincian Log Meteran Harian</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pergerakan angka meter dispenser dan volume BBM tersalurkan</p>
                        </div>
                    </div>
                    <span class="self-start sm:self-auto px-3 py-1 rounded-full text-[10px] font-extrabold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-white/10">
                        {{ $recap->count() }} Hari Terdata
                    </span>
                </div>

                <!-- Table Area -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 dark:bg-slate-800/50 border-b border-slate-200 dark:border-white/10 text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                                <th class="px-5 sm:px-6 py-3.5">Tanggal &amp; Hari</th>
                                <th class="px-4 sm:px-5 py-3.5 text-center">BBM</th>
                                <th class="px-4 sm:px-5 py-3.5 text-right hidden sm:table-cell">Meter Awal</th>
                                <th class="px-4 sm:px-5 py-3.5 text-right hidden sm:table-cell">Meter Akhir</th>
                                <th class="px-5 sm:px-6 py-3.5 text-right">Volume Penyaluran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($recap as $date => $readings)
                                @foreach($readings as $reading)
                                    @php
                                        $totalLiter = max(0, $reading->meter_akhir - $reading->meter_awal);
                                        $isPertamax = in_array($reading->jenis_bbm, ['Pertamax', 'PERTAMAX']);
                                    @endphp
                                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors group">
                                        <!-- Tanggal & Hari -->
                                        <td class="px-5 sm:px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-b from-slate-100 to-slate-200/80 dark:from-slate-800 dark:to-slate-850 border border-slate-200 dark:border-white/10 text-center flex flex-col items-center justify-center shrink-0 shadow-sm group-hover:border-indigo-500/40 transition-colors">
                                                    <span class="text-sm font-black font-mono text-white leading-none">
                                                        {{ \Carbon\Carbon::parse($date)->format('d') }}
                                                    </span>
                                                    <span class="text-[8px] font-extrabold uppercase text-slate-500 dark:text-slate-400 mt-0.5 leading-none">
                                                        {{ \Carbon\Carbon::parse($date)->format('M') }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="font-extrabold text-white text-xs sm:text-sm">
                                                        {{ \Carbon\Carbon::parse($date)->translatedFormat('l') }}
                                                    </div>
                                                    <div class="text-[10px] font-semibold text-slate-400">
                                                        {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- BBM Pill -->
                                        <td class="px-4 sm:px-5 py-4 whitespace-nowrap text-center">
                                            @if($isPertamax)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20 font-black text-[10px] uppercase tracking-wider">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                                    PERTAMAX
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 font-black text-[10px] uppercase tracking-wider">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    P. DEX
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Meter Awal (Desktop) -->
                                        <td class="px-4 sm:px-5 py-4 whitespace-nowrap text-right font-mono font-bold text-slate-600 dark:text-slate-300 hidden sm:table-cell text-xs sm:text-sm">
                                            {{ number_format($reading->meter_awal, 0, ',', '.') }}
                                        </td>

                                        <!-- Meter Akhir (Desktop) -->
                                        <td class="px-4 sm:px-5 py-4 whitespace-nowrap text-right font-mono font-bold text-slate-600 dark:text-slate-300 hidden sm:table-cell text-xs sm:text-sm">
                                            {{ number_format($reading->meter_akhir, 0, ',', '.') }}
                                        </td>

                                        <!-- Volume Penyaluran -->
                                        <td class="px-5 sm:px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex flex-col items-end">
                                                <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl {{ $isPertamax ? 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20' }}">
                                                    <span class="font-mono font-black text-xs sm:text-sm">
                                                        {{ rtrim(rtrim(number_format($totalLiter, 2, ',', '.'), '0'), ',') }}
                                                    </span>
                                                    <span class="text-[10px] font-bold">L</span>
                                                </div>
                                                <!-- Mobile only meter range -->
                                                <span class="sm:hidden text-[9px] font-mono text-slate-400 mt-1">
                                                    {{ number_format($reading->meter_awal, 0, ',', '.') }} &rarr; {{ number_format($reading->meter_akhir, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                            <div class="w-16 h-16 rounded-3xl bg-indigo-500/10 text-indigo-500 dark:text-indigo-400 border border-indigo-500/20 flex items-center justify-center mb-4 shadow-lg shadow-indigo-500/10">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                                    </path>
                                                </svg>
                                            </div>
                                            <h4 class="text-base font-black text-white">Belum Ada Data Input Meteran</h4>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                                                Belum ada catatan meter dispenser BBM untuk bulan <span class="font-bold text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::create()->month($selectedMonth)->translatedFormat('F') }} {{ $selectedYear }}</span>.
                                            </p>
                                            <a href="{{ route('petugas.meter.index') }}" 
                                                class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-500/25 transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                <span>Input Meter Sekarang</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>