<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan BBM {{ $satkerName }} - {{ $namaBulan }} {{ $tahun }}</title>
    <style>
        @page {
            margin: 10mm 5mm 10mm 5mm;
            /* Ultra-Narrow Margins (5mm sides) */
            size: 330.2mm 215.9mm landscape;
            /* F4 / Folio Landscape */
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h1 {
            font-size: 14pt;
            margin: 0;
            padding: 0;
            font-weight: bold;
            text-align: center;
        }

        h2 {
            font-size: 11pt;
            margin: 2px 0 15px 0;
            padding: 0;
            font-weight: bold;
            text-align: center;
        }

        .header-section {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            /* STRICTLY ENFORCE WIDTHS */
            font-size: 6pt;
            border: 1pt solid #000;
        }

        th,
        td {
            border: 0.5pt solid #000;
            padding: 2px 1px;
            text-align: center;
            vertical-align: middle;
            word-wrap: break-word;
            /* Allow wrapping */
            overflow: visible;
        }

        th {
            background-color: transparent;
            font-weight: bold;
            font-size: 6pt;
            height: 40px;
            /* Increased to allow wrapping */
            color: #000;
        }

        /* Dates Text */
        .vertical-text {
            width: 100%;
            display: block;
            text-align: center;
            vertical-align: middle;
            font-size: 5pt;
            font-weight: bold;
        }

        .row-header-dates th {
            height: 20px;
            width: 11.5pt;
            padding: 0;
            vertical-align: middle;
            text-align: center;
            overflow: visible;
        }

        /* Recalibrated Widths for F4 Landscape */
        .col-no {
            width: 18pt;
        }

        .col-jenis {
            width: 180pt;
        }

        .col-nopol {
            width: 60pt;
        }

        .col-bbm {
            width: 50pt;
        }

        .col-sisa-lalu {
            width: 35pt;
        }

        .col-topup {
            width: 35pt;
        }

        .col-total-bbm {
            width: 35pt;
        }

        .col-transfer {
            width: 35pt;
        }

        .col-day {
            width: 11.5pt;
        }

        .col-total-pakai {
            width: 35pt;
        }

        .col-sisa-bbm {
            width: 35pt;
        }

        .text-left {
            text-align: left;
            padding-left: 6px;
        }

        .bold {
            font-weight: bold;
        }

        .summary-row {
            background-color: #eee;
            font-weight: bold;
        }

        .summary-row td {
            font-size: 8.5pt;
            padding: 6px 3px;
            border-top: 2pt solid #000;
        }

        .footer-sig {
            margin-top: 30px;
            width: 100%;
        }

        .footer-sig table {
            border: none;
        }

        .footer-sig td {
            border: none;
            text-align: center;
            font-size: 9pt;
            height: auto;
            padding: 0;
        }
    </style>
</head>

<body>
    <div class="header-section">
        <h1>LAPORAN PEMAKAIAN BBM BULAN {{ strtoupper($namaBulan) }} TAHUN {{ $tahun }}</h1>
        <h2>{{ strtoupper($satkerName) }}</h2>
    </div>

    <table>
        <colgroup>
            <col class="col-no">
            <col class="col-jenis">
            <col class="col-nopol">
            <col class="col-bbm">
            <col class="col-sisa-lalu">
            <col class="col-topup">
            <col class="col-total-bbm">
            <col class="col-transfer">
            @for($d = 1; $d <= $daysInMonth; $d++)
                <col class="col-day">
            @endfor
            <col class="col-total-pakai">
            <col class="col-sisa-bbm">
        </colgroup>
        <thead>
            <tr>
                <th rowspan="2" class="col-no">NO</th>
                <th rowspan="2" class="col-jenis">JENIS KENDARAAN</th>
                <th rowspan="2" class="col-nopol">NOPOL</th>
                <th rowspan="2" class="col-bbm">JENIS BBM</th>
                <th rowspan="2" style="line-height: 1;">SISA BBM<br>BULAN<br>{{ strtoupper($namaBulanSebelumnya) }}</th>
                <th rowspan="2" style="line-height: 1;">TOP UP BBM<br>BULAN<br>{{ strtoupper($namaBulan) }}</th>
                <th rowspan="2">TOTAL<br>BBM</th>
                <th rowspan="2" style="line-height: 1;">TRANSFER<br>KE PERSONEL</th>
                <th colspan="{{ $daysInMonth }}" style="font-size: 7pt; height: 30px;">LAPORAN PEMAKAIAN BULAN
                    {{ strtoupper($namaBulan) }}</th>
                <th rowspan="2">TOTAL<br>PAKAI</th>
                <th rowspan="2">SISA<br>BBM</th>
            </tr>
            <tr class="row-header-dates">
                @for($d = 1; $d <= $daysInMonth; $d++)
                    <th class="col-day">
                        <div class="vertical-text">
                            {{ $d }}
                        </div>
                    </th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">{{ strtoupper($row['jenis_kendaraan']) }}</td>
                    <td class="bold">{{ strtoupper($row['no_polisi']) }}</td>
                    <td>{{ strtoupper($row['jenis_bbm']) }}</td>
                    <td>{{ $row['sisa_bulan_lalu'] > 0 ? number_format($row['sisa_bulan_lalu'], 0, ',', '.') : '-' }}</td>
                    <td>{{ $row['topup_bulan_ini'] > 0 ? number_format($row['topup_bulan_ini'], 0, ',', '.') : '-' }}</td>
                    <td class="bold">{{ number_format($row['total_bbm'], 0, ',', '.') }}</td>
                    <td>{{ $row['transfer_bulan_ini'] > 0 ? number_format($row['transfer_bulan_ini'], 0, ',', '.') : '-' }}
                    </td>
                    @for($d = 1; $d <= $daysInMonth; $d++)
                        <td class="col-day" style="font-size: 5pt; padding: 0;">
                            {{ $row['daily_usage'][$d] ? number_format($row['daily_usage'][$d], 0, ',', '.') : '' }}
                        </td>
                    @endfor
                    <td class="bold">
                        {{ $row['total_pemakaian'] > 0 ? number_format($row['total_pemakaian'], 0, ',', '.') : '-' }}</td>
                    <td class="bold">{{ number_format($row['sisa_bbm'], 0, ',', '.') }}</td>
                </tr>
            @endforeach

            @foreach($summaryByBbm as $jenisBbm => $summary)
                <tr class="summary-row">
                    <td colspan="4" class="text-left">TOTAL {{ strtoupper($jenisBbm) }}</td>
                    <td>{{ number_format($summary['sisa_bulan_lalu'], 0, ',', '.') }}</td>
                    <td>{{ number_format($summary['topup_bulan_ini'], 0, ',', '.') }}</td>
                    <td>{{ number_format($summary['total_bbm'], 0, ',', '.') }}</td>
                    <td>{{ number_format($summary['transfer_bulan_ini'], 0, ',', '.') }}</td>
                    @for($d = 1; $d <= $daysInMonth; $d++)
                        <td class="col-day"></td>
                    @endfor
                    <td class="bold">{{ number_format($summary['total_pemakaian'], 0, ',', '.') }}</td>
                    <td class="bold">{{ number_format($summary['sisa_bbm'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-sig">
        <table style="width: 100%;">
            <tr>
                <td style="width: 65%;"></td>
                <td>
                    Mataram, {{ now()->translatedFormat('d F Y') }}<br>
                    <strong>PENANGGUNG JAWAB BBM</strong>
                    <br><br><br><br><br>
                    <strong>( ............................................ )</strong>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>