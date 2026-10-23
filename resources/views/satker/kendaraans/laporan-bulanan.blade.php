@php
    $namaBulan = \Carbon\Carbon::create($tahun, $bulan, 1)->translatedFormat('F');
    $namaBulanSebelumnya = \Carbon\Carbon::create($tahun, $bulan, 1)->subMonth()->translatedFormat('F');
@endphp

<x-app-layout>
    <div class="p-3.5 sm:p-6 lg:p-8 space-y-3.5 sm:space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-white dark:bg-slate-900 p-3.5 sm:p-6 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm">
            <div class="flex items-center gap-2.5 sm:gap-3.5 w-full sm:w-auto">
                <a href="{{ route('satker.kendaraans.index') }}"
                    class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-white/10 dark:hover:bg-white/15 text-slate-600 dark:text-slate-200 flex items-center justify-center shrink-0 transition-all active:scale-95 border border-slate-200 dark:border-white/10 shadow-sm"
                    title="Kembali ke Daftar Kendaraan">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-base sm:text-2xl font-black text-slate-800 dark:text-white tracking-tight">Laporan Bulanan BBM</h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-brand-primary/10 text-brand-primary dark:bg-white/10 dark:text-white">
                            {{ $namaBulan }} {{ $tahun }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 truncate">
                        Satker: <strong class="text-slate-700 dark:text-slate-200">{{ $satker->nama_satker ?? '-' }}</strong>
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <a href="{{ route('satker.kendaraans.laporan-bulanan.print', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                    target="_blank" rel="nofollow"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center px-3.5 py-2 sm:py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white rounded-xl font-bold text-xs sm:text-sm shadow-md shadow-rose-600/20 transition-all duration-200 gap-1.5"
                    title="Cetak PDF">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak PDF</span>
                </a>
                <a href="{{ route('satker.kendaraans.laporan-bulanan.export', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                    target="_blank" rel="nofollow"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center px-3.5 py-2 sm:py-2.5 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white rounded-xl font-bold text-xs sm:text-sm shadow-md shadow-emerald-600/20 transition-all duration-200 gap-1.5"
                    title="Export Excel">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Excel</span>
                </a>
            </div>
        </div>

        <!-- Filter Card (1 Baris Lega, Tidak Terpotong) -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm p-3 sm:p-4 relative z-30">
            @php
                $bulanBulananOptions = [];
                for($m = 1; $m <= 12; $m++) {
                    $bulanBulananOptions[$m] = \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F');
                }
                $tahunBulananOptions = [];
                for($y = now()->year; $y >= now()->year - 5; $y--) {
                    $tahunBulananOptions[$y] = (string)$y;
                }
            @endphp
            <form action="{{ route('satker.kendaraans.laporan-bulanan') }}" method="GET"
                class="flex items-end gap-2 sm:gap-3">
                <div class="flex-1 min-w-0">
                    <label class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1 block">Bulan</label>
                    <x-filter-select 
                        name="bulan" 
                        :value="$bulan" 
                        placeholder="Pilih Bulan" 
                        icon="calendar" 
                        :searchable="false" 
                        :auto-submit="true" 
                        :options="$bulanBulananOptions" 
                    />
                </div>
                <div class="w-24 sm:w-36 shrink-0">
                    <label class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1 block">Tahun</label>
                    <x-filter-select 
                        name="tahun" 
                        :value="$tahun" 
                        placeholder="Pilih Tahun" 
                        icon="calendar" 
                        :searchable="false" 
                        :auto-submit="true" 
                        :options="$tahunBulananOptions" 
                    />
                </div>
                <div class="shrink-0">
                    <a href="{{ route('satker.kendaraans.laporan-bulanan') }}"
                        class="h-10 px-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-xs transition flex items-center justify-center gap-1 border border-slate-200 dark:border-white/10"
                        title="Reset Filter">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span class="hidden sm:inline">Reset</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/5 rounded-lg border border-slate-200 dark:border-white/10 overflow-hidden">
            <div class="overflow-x-auto">
                <style>
                    .laporan-tbl {
                        border-collapse: separate;
                        border-spacing: 0;
                        width: 100%;
                        font-size: 10px;
                        font-family: 'Outfit', sans-serif;
                        background: transparent;
                        color: #334155;
                    }
                    .dark .laporan-tbl {
                        color: #cbd5e1;
                    }
                    .laporan-tbl th,
                    .laporan-tbl td {
                        border: 1px solid rgba(0, 0, 0, 0.1);
                        padding: 6px 4px;
                        text-align: center;
                        vertical-align: middle;
                    }
                    .dark .laporan-tbl th,
                    .dark .laporan-tbl td {
                        border: 1px solid rgba(255, 255, 255, 0.1);
                    }
                    .laporan-tbl th {
                        background: rgba(241, 245, 249, 0.8);
                        font-weight: 700;
                        color: #475569;
                        text-transform: uppercase;
                        letter-spacing: 0.05em;
                    }
                    .dark .laporan-tbl th {
                        background: rgba(30, 41, 59, 0.8);
                        color: #94a3b8;
                    }
                    .laporan-tbl tbody tr:hover td {
                        background: rgba(0, 0, 0, 0.02);
                    }
                    .dark .laporan-tbl tbody tr:hover td {
                        background: rgba(255, 255, 255, 0.05);
                    }
                    .laporan-tbl .text-left {
                        text-align: left;
                        padding-left: 12px;
                    }
                    .laporan-tbl .bold {
                        font-weight: bold;
                        color: #0f172a;
                        background: rgba(226, 232, 240, 0.4);
                    }
                    .dark .laporan-tbl .bold {
                        color: #f8fafc;
                        background: rgba(30, 41, 59, 0.4);
                    }
                    .laporan-title {
                        text-align: center;
                        padding: 24px 0;
                        color: #0f172a;
                        background: rgba(241, 245, 249, 0.4);
                        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                    }
                    .dark .laporan-title {
                        color: #f8fafc;
                        background: rgba(15, 23, 42, 0.4);
                        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                    }
                    .laporan-title h2 {
                        font-size: 16px;
                        font-weight: 800;
                        text-transform: uppercase;
                        letter-spacing: 0.1em;
                        margin: 0;
                        color: #0284c7;
                    }
                    .dark .laporan-title h2 {
                        color: #38bdf8;
                    }
                    .laporan-title h3 {
                        font-size: 14px;
                        font-weight: 700;
                        text-transform: uppercase;
                        margin: 6px 0 0 0;
                        color: #475569;
                    }
                    .dark .laporan-title h3 {
                        color: #94a3b8;
                    }
                    .rotate-date {
                        font-size: 10px;
                        font-weight: 800;
                        display: block;
                        margin: 0 auto;
                        color: #334155;
                    }
                    .dark .rotate-date {
                        color: #e2e8f0;
                    }
                </style>

                <div class="laporan-title">
                    <h2>LAPORAN BBM BULAN {{ strtoupper($namaBulan) }} TAHUN {{ $tahun }}</h2>
                    <h3>{{ strtoupper($satker->nama_satker ?? '-') }}</h3>
                </div>

                <table class="laporan-tbl">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width:25px;">NO</th>
                            <th rowspan="2" style="width:120px;">JENIS KENDARAAN</th>
                            <th rowspan="2" style="width:100px;">NOPOL</th>
                            <th rowspan="2" style="width:100px;">JENIS BBM</th>
                            <th rowspan="2" style="width:70px;">SISA
                                BBM<br>BULAN<br>{{ strtoupper($namaBulanSebelumnya) }}</th>
                            <th rowspan="2" style="width:70px;">TOP UP BBM<br>BULAN<br>{{ strtoupper($namaBulan) }}</th>
                            <th rowspan="2" style="width:50px;">TM</th>
                            <th rowspan="2" style="width:50px;">TK</th>
                            <th colspan="{{ $daysInMonth }}">LAPORAN PEMAKAIAN BULAN {{ strtoupper($namaBulan) }}</th>
                            <th rowspan="2" style="width:50px;">TOTAL<br>PAKAI</th>
                            <th rowspan="2" style="width:50px;">SISA<br>BBM</th>
                        </tr>
                        <tr>
                            @for($d = 1; $d <= $daysInMonth; $d++)
                                <th style="padding:0; width:22px;">
                                    <div class="rotate-date">{{ $d }}</div>
                                </th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($rows) > 0)
                            @foreach($rows as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="text-left">{{ strtoupper($row['jenis_kendaraan']) }}</td>
                                    <td>{{ strtoupper($row['no_polisi']) }}</td>
                                    <td style="font-size:8px;">{{ strtoupper($row['jenis_bbm']) }}</td>
                                    <td>{{ $row['sisa_bulan_lalu'] != 0 ? number_format($row['sisa_bulan_lalu'], 0, ',', '.') : '' }}
                                    </td>
                                    <td>{{ $row['topup_bulan_ini'] > 0 ? number_format($row['topup_bulan_ini'], 0, ',', '.') : '' }}
                                    </td>
                                    <td>{{ $row['tm_bulan_ini'] > 0 ? number_format($row['tm_bulan_ini'], 0, ',', '.') : '' }}
                                    </td>
                                    <td>{{ $row['tk_bulan_ini'] > 0 ? number_format($row['tk_bulan_ini'], 0, ',', '.') : '' }}
                                    </td>
                                    @for($d = 1; $d <= $daysInMonth; $d++)
                                        <td style="font-size: 8px;">
                                            {{ $row['daily_usage'][$d] ? number_format($row['daily_usage'][$d], 0, ',', '.') : '' }}
                                        </td>
                                    @endfor
                                    <td class="bold">
                                        {{ $row['total_pemakaian'] > 0 ? number_format($row['total_pemakaian'], 0, ',', '.') : '' }}
                                    </td>
                                    <td class="bold">{{ number_format($row['sisa_bbm'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="{{ 8 + $daysInMonth + 2 }}" style="padding:20px; color:#999;">Belum ada data
                                    kendaraan</td>
                            </tr>
                        @endif

                        @foreach($summaryByBbm as $jenisBbm => $summary)
                            <tr class="bold">
                                <td colspan="4" class="text-left" style="padding-left:20px;">TOTAL
                                    {{ strtoupper($jenisBbm) }}</td>
                                <td>{{ number_format($summary['sisa_bulan_lalu'], 0, ',', '.') }}</td>
                                <td>{{ number_format($summary['topup_bulan_ini'], 0, ',', '.') }}</td>
                                <td>{{ number_format($summary['tm_bulan_ini'], 0, ',', '.') }}</td>
                                <td>{{ number_format($summary['tk_bulan_ini'], 0, ',', '.') }}</td>
                                @for($d = 1; $d <= $daysInMonth; $d++)
                                    <td></td>
                                @endfor
                                <td>{{ number_format($summary['total_pemakaian'], 0, ',', '.') }}</td>
                                <td>{{ number_format($summary['sisa_bbm'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
