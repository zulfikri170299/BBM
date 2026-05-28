<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan BBM {{ $satkerName ?? '-' }} - {{ $namaBulan }} {{ $tahun }}</title>
    <style>
        @page {
            margin: 10mm 10mm;
            size: 330.2mm 215.9mm landscape;
            /* F4 Landscape */
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        .laporan-title {
            text-align: center;
            padding: 0 0 15px 0;
        }

        .laporan-title h2 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 4px 0;
        }

        .laporan-title h3 {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }

        .laporan-tbl {
            border-collapse: collapse;
            width: 100%;
            font-size: 7.5pt;
        }

        .laporan-tbl th,
        .laporan-tbl td {
            border: 0.8pt solid #000;
            padding: 3px 2px;
            text-align: center;
            vertical-align: middle;
        }

        .laporan-tbl th {
            font-weight: bold;
        }

        .laporan-tbl .text-left {
            text-align: left;
            padding-left: 5px;
        }

        .laporan-tbl .bold {
            font-weight: bold;
        }

        .rotate-date {
            font-size: 7pt;
            font-weight: bold;
            display: block;
            margin: 0 auto;
        }

        .footer-sig {
            margin-top: 30px;
            width: 100%;
            page-break-inside: avoid;
        }

        .footer-sig table {
            border: none;
            width: 100%;
        }

        .footer-sig th,
        .footer-sig td {
            border: none;
            text-align: center;
            font-size: 10pt;
            padding: 0;
        }
    </style>
</head>

<body>
    @include('components.pdf-header')
    <div class="laporan-title">
        <h2>LAPORAN BBM BULAN {{ strtoupper($namaBulan) }} TAHUN {{ $tahun }}</h2>
    </div>

    <table class="laporan-tbl">
        <thead>
            <tr>
                <th rowspan="2" style="width:20px;">NO</th>
                <th rowspan="2" style="width:110px;">JENIS KENDARAAN</th>
                <th rowspan="2" style="width:75px;">NOPOL</th>
                <th rowspan="2" style="width:65px;">JENIS BBM</th>
                <th rowspan="2" style="width:55px;">SISA BBM<br>BULAN<br>{{ strtoupper($namaBulanSebelumnya) }}</th>
                <th rowspan="2" style="width:55px;">TOP UP BBM<br>BULAN<br>{{ strtoupper($namaBulan) }}</th>
                <th rowspan="2" style="width:25px;">TM</th>
                <th rowspan="2" style="width:35px;">TOTAL<br>BBM</th>
                <th rowspan="2" style="width:35px;">TK</th>
                <th colspan="{{ $daysInMonth }}">LAPORAN PEMAKAIAN BULAN {{ strtoupper($namaBulan) }}</th>
                <th rowspan="2" style="width:35px;">TOTAL<br>PAKAI</th>
                <th rowspan="2" style="width:35px;">SISA<br>BBM</th>
            </tr>
            <tr>
                @for($d = 1; $d <= $daysInMonth; $d++)
                    <th style="padding:0; width:17px;">
                        <div class="rotate-date">{{ $d }}</div>
                    </th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">{{ strtoupper($row['jenis_kendaraan']) }}</td>
                    <td>{{ strtoupper($row['no_polisi']) }}</td>
                    <td style="font-size:6.5pt;">{{ strtoupper($row['jenis_bbm']) }}</td>
                    <td>{{ $row['sisa_bulan_lalu'] > 0 ? number_format($row['sisa_bulan_lalu'], 0, ',', '.') : '' }}</td>
                    <td>{{ $row['topup_bulan_ini'] > 0 ? number_format($row['topup_bulan_ini'], 0, ',', '.') : '' }}</td>
                    <td>{{ $row['tm_bulan_ini'] > 0 ? number_format($row['tm_bulan_ini'], 0, ',', '.') : '' }}</td>
                    <td class="bold">
                        {{ number_format($row['total_bbm'], 0, ',', '.') }}
                    </td>
                    <td>{{ $row['tk_bulan_ini'] > 0 ? number_format($row['tk_bulan_ini'], 0, ',', '.') : '' }}
                    </td>
                    @for($d = 1; $d <= $daysInMonth; $d++)
                        <td style="font-size: 6.5pt; padding:0;">
                            {{ $row['daily_usage'][$d] ? number_format($row['daily_usage'][$d], 0, ',', '.') : '' }}
                        </td>
                    @endfor
                    <td class="bold">
                        {{ $row['total_pemakaian'] > 0 ? number_format($row['total_pemakaian'], 0, ',', '.') : '' }}
                    </td>
                    <td class="bold">{{ number_format($row['sisa_bbm'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 9 + $daysInMonth + 2 }}" style="padding:20px; color:#999;">Belum ada data kendaraan</td>
                </tr>
            @endforelse

            <!-- Summary Rows -->
            @foreach($summaryByBbm as $jenisBbm => $summary)
                <tr class="bold">
                    <td colspan="4" class="text-left" style="padding-left:15px;">TOTAL {{ strtoupper($jenisBbm) }}</td>
                    <td>{{ $summary['sisa_bulan_lalu'] == 0 ? '0' : number_format($summary['sisa_bulan_lalu'], 0, ',', '.') }}
                    </td>
                    <td>{{ $summary['topup_bulan_ini'] == 0 ? '0' : number_format($summary['topup_bulan_ini'], 0, ',', '.') }}
                    </td>
                    <td>{{ $summary['tm_bulan_ini'] == 0 ? '0' : number_format($summary['tm_bulan_ini'], 0, ',', '.') }}
                    </td>
                    <td>{{ $summary['total_bbm'] == 0 ? '0' : number_format($summary['total_bbm'], 0, ',', '.') }}</td>
                    <td>{{ $summary['tk_bulan_ini'] == 0 ? '0' : number_format($summary['tk_bulan_ini'], 0, ',', '.') }}
                    </td>
                    @for($d = 1; $d <= $daysInMonth; $d++)
                        <td></td>
                    @endfor
                    <td>{{ $summary['total_pemakaian'] == 0 ? '0' : number_format($summary['total_pemakaian'], 0, ',', '.') }}
                    </td>
                    <td>{{ $summary['sisa_bbm'] == 0 ? '0' : number_format($summary['sisa_bbm'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @include('components.pdf-signature')
</body>

</html>