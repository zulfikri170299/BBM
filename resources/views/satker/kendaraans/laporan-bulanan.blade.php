@php
    $namaBulan = \Carbon\Carbon::create($tahun, $bulan, 1)->translatedFormat('F');
    $namaBulanSebelumnya = \Carbon\Carbon::create($tahun, $bulan, 1)->subMonth()->translatedFormat('F');
@endphp

<x-app-layout>
    <div class="p-4 lg:p-6 space-y-4">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Laporan Bulanan BBM</h1>
                <p class="text-sm text-slate-500">Satker: <strong>{{ $satker->nama_satker ?? '-' }}</strong> | Periode:
                    {{ $namaBulan }} {{ $tahun }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('satker.kendaraans.laporan-bulanan.export', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                    target="_blank" rel="nofollow"
                    class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg font-semibold text-sm hover:bg-emerald-700 shadow transition-all">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Export Excel
                </a>
                <a href="{{ route('satker.kendaraans.laporan-bulanan.print', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                    target="_blank" rel="nofollow"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 shadow transition-all">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Cetak PDF
                </a>
                <a href="{{ route('satker.kendaraans.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg font-semibold text-sm hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all hover:-translate-y-0.5">
                    ← Kembali
                </a>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-lg border border-slate-200 p-4">
            <form action="{{ route('satker.kendaraans.laporan-bulanan') }}" method="GET"
                class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="text-xs font-semibold text-slate-500 mb-1 block">Bulan</label>
                    <select name="bulan" class="px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-500 mb-1 block">Tahun</label>
                    <select name="tahun" class="px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm">
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
        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <style>
                    .laporan-tbl {
                        border-collapse: collapse;
                        width: 100%;
                        font-size: 9px;
                        font-family: Arial, sans-serif;
                        background: #fff;
                    }

                    .laporan-tbl th,
                    .laporan-tbl td {
                        border: 1px solid #000;
                        padding: 1px 2px;
                        text-align: center;
                        vertical-align: middle;
                    }

                    .laporan-tbl th {
                        font-weight: bold;
                    }

                    .laporan-tbl .text-left {
                        text-align: left;
                    }

                    .laporan-tbl .bold {
                        font-weight: bold;
                    }

                    .laporan-title {
                        text-align: center;
                        padding: 12px 0 12px 0;
                    }

                    .laporan-title h2 {
                        font-size: 15px;
                        font-weight: bold;
                        text-transform: uppercase;
                        margin: 0;
                    }

                    .laporan-title h3 {
                        font-size: 13px;
                        font-weight: bold;
                        text-transform: uppercase;
                        margin: 0;
                    }

                    .rotate-date {
                        font-size: 8px;
                        font-weight: bold;
                        display: block;
                        margin: 0 auto;
                        padding: 2px 0;
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
                            <th rowspan="2" style="width:50px;">TOTAL<br>BBM</th>
                            <th rowspan="2" style="width:70px;">TRANSFER</th>
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
                                    <td>{{ $row['sisa_bulan_lalu'] > 0 ? number_format($row['sisa_bulan_lalu'], 0, ',', '.') : '' }}
                                    </td>
                                    <td>{{ $row['topup_bulan_ini'] > 0 ? number_format($row['topup_bulan_ini'], 0, ',', '.') : '' }}
                                    </td>
                                    <td class="bold">
                                        {{ number_format($row['total_bbm'], 0, ',', '.') }}</td>
                                    <td>{{ $row['transfer_bulan_ini'] > 0 ? number_format($row['transfer_bulan_ini'], 0, ',', '.') : '' }}
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
                                <td>{{ number_format($summary['total_bbm'], 0, ',', '.') }}</td>
                                <td>{{ number_format($summary['transfer_bulan_ini'], 0, ',', '.') }}</td>
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