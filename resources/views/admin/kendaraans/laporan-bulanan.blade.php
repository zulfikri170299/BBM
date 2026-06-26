@php
    $namaBulan = \Carbon\Carbon::create($tahun, $bulan, 1)->translatedFormat('F');
    $namaBulanSebelumnya = \Carbon\Carbon::create($tahun, $bulan, 1)->subMonth()->translatedFormat('F');
@endphp

<x-app-layout>
    <div class="p-4 lg:p-6 space-y-4">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h1 class="text-2xl font-bold text-white">Laporan Bulanan BBM</h1>
                <p class="text-xs text-slate-400">Satker: <strong>{{ $satker->nama_satker ?? '-' }}</strong> | Periode:
                    {{ $namaBulan }} {{ $tahun }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.kendaraans.laporan-bulanan.export', ['satker_id' => $satkerId, 'bulan' => $bulan, 'tahun' => $tahun]) }}"
                    target="_blank" rel="nofollow"
                    class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-emerald-600 text-white rounded-lg font-semibold text-sm hover:bg-emerald-700 shadow transition-all" title="Export Excel">
                    <svg class="w-4 h-4 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span class="hidden sm:inline">Export Excel</span>
                </a>
                <a href="{{ route('admin.kendaraans.laporan-bulanan.print', ['satker_id' => $satkerId, 'bulan' => $bulan, 'tahun' => $tahun]) }}"
                    target="_blank" rel="nofollow"
                    class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 shadow transition-all" title="Cetak PDF">
                    <svg class="w-4 h-4 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    <span class="hidden sm:inline">Cetak PDF</span>
                </a>
                <a href="{{ route('admin.kendaraans.index') }}"
                    class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-indigo-600 text-white rounded-lg font-semibold text-sm hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all hover:-translate-y-0.5" title="Kembali">
                    <svg class="w-4 h-4 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="hidden sm:inline">Kembali</span>
                </a>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-slate-900 border border-white/5 rounded-lg border border-white/10 p-4">
            <form action="{{ route('admin.kendaraans.laporan-bulanan') }}" method="GET"
                class="flex flex-wrap gap-3 items-end">
                <input type="hidden" name="satker_id" value="{{ $satkerId }}">
                <div>
                    <label class="text-xs font-semibold text-slate-400 mb-1 block">Bulan</label>
                    <select name="bulan" class="tom-select w-32">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-400 mb-1 block">Tahun</label>
                    <select name="tahun" class="tom-select w-28">
                        @for($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg font-semibold text-sm hover:bg-emerald-700 transition-all">Filter</button>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-slate-900 border border-white/5 rounded-lg border border-white/10 overflow-hidden">
            <div class="overflow-x-auto">
                <style>
                    .laporan-tbl {
                        border-collapse: separate;
                        border-spacing: 0;
                        width: 100%;
                        font-size: 10px;
                        font-family: 'Outfit', sans-serif;
                        background: transparent;
                        color: #cbd5e1;
                    }
                    .laporan-tbl th,
                    .laporan-tbl td {
                        border: 1px solid rgba(255, 255, 255, 0.1);
                        padding: 6px 4px;
                        text-align: center;
                        vertical-align: middle;
                    }
                    .laporan-tbl th {
                        background: rgba(30, 41, 59, 0.8);
                        font-weight: 700;
                        color: #94a3b8;
                        text-transform: uppercase;
                        letter-spacing: 0.05em;
                    }
                    .laporan-tbl tbody tr:hover td {
                        background: rgba(255, 255, 255, 0.05);
                    }
                    .laporan-tbl .text-left {
                        text-align: left;
                        padding-left: 12px;
                    }
                    .laporan-tbl .bold {
                        font-weight: bold;
                        color: #f8fafc;
                        background: rgba(30, 41, 59, 0.4);
                    }
                    .laporan-title {
                        text-align: center;
                        padding: 24px 0;
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
                        color: #38bdf8;
                    }
                    .laporan-title h3 {
                        font-size: 14px;
                        font-weight: 700;
                        text-transform: uppercase;
                        margin: 6px 0 0 0;
                        color: #94a3b8;
                    }
                    .rotate-date {
                        font-size: 10px;
                        font-weight: 800;
                        display: block;
                        margin: 0 auto;
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
