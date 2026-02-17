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
        }

        h2 {
            font-size: 11pt;
            margin: 2px 0 15px 0;
            padding: 0;
            font-weight: bold;
        }

        .header-section {
            margin-bottom: 5px;
        }

        .kop-surat {
            width: 100%;
            border: none;
            margin-bottom: 10px;
        }

        .kop-surat td {
            border: none;
            padding: 0;
            vertical-align: middle;
            text-align: center;
        }

        .kop-surat .logo-container {
            width: 60px;
        }

        .kop-surat .logo {
            width: 55px;
            height: 55px;
            object-fit: contain;
        }

        .kop-surat .title-container {
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            /* STRICTLY ENFORCE WIDTHS */
            font-size: 6.5pt;
            /* Reduced font size */
            border: 1pt solid #000;
        }

        th,
        td {
            border: 0.5pt solid #000;
            padding: 3px 2px;
            text-align: center;
            vertical-align: middle;
            overflow: hidden;
            white-space: nowrap;
        }

        th {
            background-color: transparent;
            font-weight: bold;
            font-size: 7pt;
            /* Reduced header font size */
            height: 30px;
            /* Slightly shorter */
            color: #000;
        }

        /* Rotated Text for Dates (Bottom-to-Top) */
        .vertical-text {
            writing-mode: horizontal-tb;
            /* Force reset */
            transform: rotate(-90deg);
            white-space: nowrap;
            height: 5.67pt;
            /* Precisely 2mm container height = width after rotation */
            width: 90px;
            /* Safe width for date string */
            line-height: 5.67pt;
            /* Match column width exactly */
            margin: 0;
            display: block;
            /* Ensure it takes dimensions */
            text-align: left;
            vertical-align: bottom;
            font-size: 5pt;
            /* Must be < 5.67pt to fit! */
            transform-origin: bottom left;
            /* Pivot at bottom-left corner */
            position: relative;
            left: 5.67pt;
            /* Shift right to compensate for pivot */
            bottom: 0;
        }

        .row-header-dates th {
            height: 95px;
            width: 5.67pt;
            /* Explicit width */
            padding: 0;
            vertical-align: bottom;
            text-align: center;
            padding-bottom: 0;
            overflow: visible;
            /* CRITICAL: Allow text to show even if tight */
            position: relative;
        }

        /* Maximized Metadata Widths + 2mm Date Columns (~879pt Total Printable) */
        .col-no {
            width: 20pt;
        }

        .col-jenis {
            width: 119pt;
        }

        /* Reduced from 149 */
        .col-nopol {
            width: 100pt;
        }

        /* Reduced from 120 */
        .col-bbm {
            width: 80pt;
        }

        /* Reduced from 90 */
        .col-sisa-lalu {
            width: 65pt;
        }

        .col-topup {
            width: 65pt;
        }

        .col-total-bbm {
            width: 65pt;
        }

        .col-transfer {
            width: 65pt;
        }

        /* NEW COLUMN */
        .col-day {
            width: 5.67pt;
        }

        /* 2mm * 31 = ~175.77pt */
        .col-total-pakai {
            width: 65pt;
        }

        .col-sisa-bbm {
            width: 65pt;
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
        <table class="kop-surat">
            <tr>
                <td class="logo-container">
                    <img src="{{ public_path('Lambang_Polda_NTB.png') }}" class="logo">
                </td>
                <td class="title-container">
                    <h1>LAPORAN PEMAKAIAN BBM BULAN {{ strtoupper($namaBulan) }} TAHUN {{ $tahun }}</h1>
                    <h2>{{ strtoupper($satkerName) }}</h2>
                </td>
                <td class="logo-container">
                    <img src="{{ public_path('rolog.png') }}" class="logo">
                </td>
            </tr>
        </table>
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
                <th rowspan="2">NO</th>
                <th rowspan="2" style="font-size: 8pt;">JENIS KENDARAAN</th>
                <th rowspan="2" style="font-size: 8pt;">NOPOL</th>
                <th rowspan="2" style="font-size: 8pt;">JENIS BBM</th>
                <th rowspan="2" style="font-size: 6.5pt; line-height: 1;">SISA BBM<br>BULAN
                    {{ strtoupper($namaBulanSebelumnya) }}
                </th>
                <th rowspan="2" style="font-size: 6.5pt; line-height: 1;">TOP UP BBM<br>BULAN
                    {{ strtoupper($namaBulan) }}
                </th>
                <th rowspan="2" style="font-size: 8pt;">TOTAL<br>BBM</th>
                <th rowspan="2" style="font-size: 6.5pt; line-height: 1;">TRANSFER<br>KE PERSONEL</th>
                <th colspan="{{ $daysInMonth }}" style="font-size: 9pt; height: 35px;">LAPORAN PEMAKAIAN BULAN
                    {{ strtoupper($namaBulan) }}
                </th>
                <th rowspan="2" style="font-size: 8pt;">TOTAL<br>PAKAI</th>
                <th rowspan="2" style="font-size: 8pt;">SISA<br>BBM</th>
            </tr>
            <tr class="row-header-dates">
                @for($d = 1; $d <= $daysInMonth; $d++)
                    @php
                        $dateStr = str_pad($d, 2, '0', STR_PAD_LEFT) . '/' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '/' . $tahun;
                    @endphp
                    <th>
                        <div class="vertical-text">{{ $dateStr }}</div>
                    </th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $index => $row)
                <tr>
                    <td style="font-size: 7.5pt;">{{ $index + 1 }}</td>
                    <td class="text-left" style="font-size: 7.5pt;">{{ strtoupper($row['jenis_kendaraan']) }}</td>
                    <td class="bold" style="font-size: 7.5pt;">{{ strtoupper($row['no_polisi']) }}</td>
                    <td style="font-size: 7.5pt;">{{ strtoupper($row['jenis_bbm']) }}</td>
                    <td style="font-size: 7.5pt;">
                        {{ $row['sisa_bulan_lalu'] > 0 ? number_format($row['sisa_bulan_lalu'], 0, ',', '.') : '-' }}
                    </td>
                    <td style="font-size: 7.5pt;">
                        {{ $row['topup_bulan_ini'] > 0 ? number_format($row['topup_bulan_ini'], 0, ',', '.') : '-' }}
                    </td>
                    <td class="bold" style="font-size: 7.5pt;">{{ number_format($row['total_bbm'], 0, ',', '.') }}</td>
                    <td style="font-size: 7.5pt;">
                        {{ $row['transfer_bulan_ini'] > 0 ? number_format($row['transfer_bulan_ini'], 0, ',', '.') : '-' }}
                    </td>
                    @for($d = 1; $d <= $daysInMonth; $d++)
                        <td style="font-size: 5pt; padding: 0; text-align: center;">
                            {{ $row['daily_usage'][$d] ? number_format($row['daily_usage'][$d], 0, ',', '.') : '' }}
                        </td>
                    @endfor
                    <td class="bold" style="font-size: 7.5pt;">
                        {{ $row['total_pemakaian'] > 0 ? number_format($row['total_pemakaian'], 0, ',', '.') : '-' }}
                    </td>
                    <td class="bold" style="font-size: 7.5pt;">{{ number_format($row['sisa_bbm'], 0, ',', '.') }}</td>
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
                        <td></td>
                    @endfor
                    <td>{{ number_format($summary['total_pemakaian'], 0, ',', '.') }}</td>
                    <td>{{ number_format($summary['sisa_bbm'], 0, ',', '.') }}</td>
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