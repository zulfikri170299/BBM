<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapan Pengisian BBM - {{ \Carbon\Carbon::create()->month($selectedMonth)->translatedFormat('F') }} {{ $selectedYear }}</title>
    <style>
        @page { size: A4 portrait; margin: 0; }
        body { 
            font-family: "Times New Roman", Times, serif; 
            font-size: 12pt; 
            line-height: 1.5;
            margin: 1cm 2cm 2cm 2cm;
            color: #000;
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            /* border-bottom: 3px double #000; Removed full width border */
            padding-bottom: 10px; 
            display: inline-block; /* Make header as wide as content */
            border-bottom: 3px double #000; /* Border matches content width */
        }
        .header-container {
            text-align: left; /* Container aligns left */
        }
        .header h1 { 
            margin: 0; 
            font-size: 14pt; 
            font-weight: bold; 
            text-transform: uppercase; 
        }
        .header h2 { 
            margin: 5px 0 0; 
            font-size: 12pt; 
            font-weight: bold; 
            text-transform: uppercase; 
        }
        .header p { 
            margin: 5px 0 0; 
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .meta {
            margin-bottom: 20px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
        }
        th, td { 
            border: 1px solid #000; 
            padding: 8px; 
            vertical-align: middle;
        }
        th { 
            background-color: #f2f2f2; 
            font-weight: bold; 
            text-align: center; 
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .totals-section {
            width: 100%;
            margin-top: 10px;
            display: flex;
            justify-content: flex-end;
        }
        /* Adjusted totals table to prevent wrapping */
        .totals-table {
            width: 60%; /* Increased width */
            float: right;
            border-collapse: collapse;
        }
        .totals-table td {
            border: none;
            padding: 5px;
        }
        .totals-table .label {
            font-weight: bold;
            width: 70%; /* Increased label width */
            white-space: nowrap; /* Prevent wrapping */
        }
        .totals-table .value {
            text-align: right;
            font-weight: bold;
            border-bottom: 1px solid #000;
            width: 30%;
        }

        .signatures {
            margin-top: 50px;
            width: 100%;
            display: table;
        }
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        /* Empty box for alignment */
        .signature-box-empty {
            display: table-cell;
            width: 50%;
        }
        .signature-space {
            height: 80px;
        }
        .footer {
            margin-top: 30px;
            font-size: 9pt;
            font-style: italic;
            text-align: right;
        }
        
        /* Utility to clear floats */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body onload="window.print()">
    @include('components.pdf-header')

    <div class="text-center" style="margin-top: 20px; margin-bottom: 20px; text-decoration: underline; font-weight: bold;">
        LAPORAN REKAPAN PENGISIAN BBM (METERAN)
    </div>

    <div class="meta">
        <table>
            <tr style="border: none;">
                <td style="border: none; width: 150px;">Bulan / Tahun</td>
                <td style="border: none; width: 10px;">:</td>
                <td style="border: none;">{{ \Carbon\Carbon::create()->month($selectedMonth)->translatedFormat('F') }} {{ $selectedYear }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;">Dicetak Oleh</td>
                <td style="border: none;">:</td>
                <td style="border: none;">{{ auth()->user()->name }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 25%">Jenis BBM</th>
                <th style="width: 20%">Meter Awal</th>
                <th style="width: 20%">Meter Akhir</th>
                <th style="width: 15%">Total Liter</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($recap as $date => $readings)
                @foreach($readings as $reading)
                    @php
                        $totalLiter = max(0, $reading->meter_akhir - $reading->meter_awal);
                    @endphp
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
                        <td class="text-center">{{ $reading->jenis_bbm }}</td>
                        <td class="text-right">{{ number_format($reading->meter_awal, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($reading->meter_akhir, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($totalLiter, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data pengisian pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="clearfix">
        <table class="totals-table">
            <tr>
                <td class="label">Total Pertamax</td>
                <td>:</td>
                <td class="value">{{ number_format($totalPertamax, 0, ',', '.') }} Liter</td>
            </tr>
            <tr>
                <td class="label">Total Pertamina Dex</td>
                <td>:</td>
                <td class="value">{{ number_format($totalDex, 0, ',', '.') }} Liter</td>
            </tr>
            <tr>
                <td class="label">TOTAL KESELURUHAN</td>
                <td>:</td>
                <td class="value">{{ number_format($totalPertamax + $totalDex, 0, ',', '.') }} Liter</td>
            </tr>
        </table>
    </div>

    <div class="signatures">
        <div class="signature-box-empty">
            <!-- Kosong, bekas Kasubbag Bekpal -->
        </div>
        <div class="signature-box">
            <p>Mataram, {{ now()->translatedFormat('d F Y') }}</p>
            <p>PETUGAS BBM</p>
            <div class="signature-space"></div>
            <p style="font-weight: bold; margin-bottom: 0;">_______________________</p>
        </div>
    </div>
</body>
</html>
