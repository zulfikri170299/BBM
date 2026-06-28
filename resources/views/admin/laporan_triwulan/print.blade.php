<!DOCTYPE html>
<html>

<head>
    <title>Laporan Per 3 Bulan</title>
    <style>
        @page {
            size: 330.2mm 215.9mm landscape;
            /* F4 landscape */
            margin: 10mm;
        }

        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0;
            font-size: 12px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            font-size: 10px;
        }

        td {
            font-size: 10px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: right;
            color: #777;
        }

        .text-red {
            color: #e11d48;
        }

        .bg-gray {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    @include('components.pdf-header')
    <div class="header">
        <h1>REKAPAN PER 3 BULAN</h1>
        <p>Periode {{ $periodeLabel }}</p>
    </div>

    @php
        $jumlahBbm = count($allBbmTypes);
        $totalCols = 2 + ($jumlahBbm * 3);
    @endphp

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 3%; vertical-align: middle;">NO</th>
                <th rowspan="2" style="width: 25%; vertical-align: middle; text-align: left;">SATKER</th>
                <th colspan="{{ $jumlahBbm }}" class="text-center">JUMLAH PENDAPATAN</th>
                <th colspan="{{ $jumlahBbm }}" class="text-center">PEMAKAIAN</th>
                <th colspan="{{ $jumlahBbm }}" class="text-center">SISA BBM</th>
            </tr>
            <tr>
                @foreach($allBbmTypes as $jenis)
                    <th>{{ strtoupper($jenis) }}</th>
                @endforeach
                @foreach($allBbmTypes as $jenis)
                    <th>{{ strtoupper($jenis) }}</th>
                @endforeach
                @foreach($allBbmTypes as $jenis)
                    <th>{{ strtoupper($jenis) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                $sumPendapatan = [];
                $sumPemakaian = [];
                $sumSisa = [];
                foreach ($allBbmTypes as $jenis) {
                    $sumPendapatan[$jenis] = 0;
                    $sumPemakaian[$jenis] = 0;
                    $sumSisa[$jenis] = 0;
                }
            @endphp
            @foreach($satkers as $satker)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="font-bold">{{ strtoupper($satker->nama_satker) }}</td>

                    @foreach($allBbmTypes as $jenis)
                        @php
                            $valP = $pendapatan[$satker->id][$jenis] ?? 0;
                            $sumPendapatan[$jenis] += $valP;
                        @endphp
                        <td class="text-center">{{ rtrim(rtrim(number_format($valP, 2, ',', '.'), '0'), ',') }}</td>
                    @endforeach

                    @foreach($allBbmTypes as $jenis)
                        @php
                            $valM = $pemakaian[$satker->id][$jenis] ?? 0;
                            $sumPemakaian[$jenis] += $valM;
                        @endphp
                        <td class="text-center">{{ rtrim(rtrim(number_format($valM, 2, ',', '.'), '0'), ',') }}</td>
                    @endforeach

                    @foreach($allBbmTypes as $jenis)
                        @php
                            $sisa = $sisaBbm[$satker->id][$jenis] ?? 0;
                            $sumSisa[$jenis] += $sisa;
                        @endphp
                        <td class="text-center font-bold {{ $sisa < 0 ? 'text-red' : '' }}">
                            {{ rtrim(rtrim(number_format($sisa, 2, ',', '.'), '0'), ',') }}
                        </td>
                    @endforeach
                </tr>
            @endforeach

            <tr class="bg-gray">
                <td colspan="2" class="text-center font-bold">TOTAL</td>
                @foreach($allBbmTypes as $jenis)
                    <td class="text-center font-bold">{{ number_format($sumPendapatan[$jenis], 0, ',', '.') }}</td>
                @endforeach
                @foreach($allBbmTypes as $jenis)
                    <td class="text-center font-bold">{{ number_format($sumPemakaian[$jenis], 0, ',', '.') }}</td>
                @endforeach
                @foreach($allBbmTypes as $jenis)
                    <td class="text-center font-bold {{ $sumSisa[$jenis] < 0 ? 'text-red' : '' }}">
                        {{ number_format($sumSisa[$jenis], 0, ',', '.') }}
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <div class="footer">
    </div>
    @include('components.pdf-signature')
</body>

</html>