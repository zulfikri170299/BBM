<!DOCTYPE html>
<html>
<head>
    <title>Laporan Triwulan - {{ $satker->nama_satker }}</title>
    <style>
        @page {
            margin: 1cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 16px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .header p {
            font-size: 12px;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px 5px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
        }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .bg-gray { background-color: #f9f9f9; }
        .footer {
            margin-top: 30px;
            width: 100%;
        }
        .signature-table {
            border: none;
            width: 100%;
        }
        .signature-table td {
            border: none;
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 20px;
        }
    </style>
</head>
<body>
    @include('components.pdf-header')

    <div class="header">
        <h1>REKAPAN PER 3 BULAN (TRIWULAN)</h1>
        <p>PERIODE: {{ strtoupper($periodeLabel) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2">SATKER</th>
                <th colspan="{{ count($allBbmTypes) }}">JUMLAH PENDAPATAN</th>
                <th colspan="{{ count($allBbmTypes) }}">PEMAKAIAN</th>
                <th colspan="{{ count($allBbmTypes) }}">SISA BBM</th>
            </tr>
            <tr>
                @foreach($allBbmTypes as $jenis)
                    <th>{{ $jenis }}</th>
                @endforeach
                @foreach($allBbmTypes as $jenis)
                    <th>{{ $jenis }}</th>
                @endforeach
                @foreach($allBbmTypes as $jenis)
                    <th>{{ $jenis }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-left font-bold">{{ strtoupper($satker->nama_satker) }}</td>
                @foreach($allBbmTypes as $jenis)
                    <td>{{ number_format($pendapatan[$jenis] ?? 0, 0, ',', '.') }}</td>
                @endforeach
                @foreach($allBbmTypes as $jenis)
                    <td>{{ number_format($pemakaian[$jenis] ?? 0, 0, ',', '.') }}</td>
                @endforeach
                @foreach($allBbmTypes as $jenis)
                    @php
                        $sisa = ($pendapatan[$jenis] ?? 0) - ($pemakaian[$jenis] ?? 0);
                    @endphp
                    <td class="font-bold">{{ number_format($sisa, 0, ',', '.') }}</td>
                @endforeach
            </tr>
        </tbody>
    </table>

    @include('components.pdf-signature')
</body>
</html>
