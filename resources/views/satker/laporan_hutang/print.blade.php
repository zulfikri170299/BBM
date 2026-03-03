<!DOCTYPE html>
<html>

<head>
    <title>Laporan Riwayat Pembayaran Hutang</title>
    <style>
        @page {
            margin: 1cm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18pt;
            text-transform: uppercase;
        }

        .satker-name {
            font-size: 14pt;
            font-weight: bold;
            color: #4338ca;
            margin-top: 5px;
        }

        .filter-info {
            margin-bottom: 15px;
            font-size: 9pt;
            font-style: italic;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8pt;
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        td {
            border: 1px solid #eee;
            padding: 8px;
            vertical-align: top;
            font-size: 9pt;
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
            margin-top: 40px;
            width: 100%;
        }

        .signature-box {
            width: 250px;
            float: right;
            text-align: center;
        }

        .signature-space {
            height: 70px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Riwayat Pembayaran Hutang BBM</h1>
        <div class="satker-name">{{ $satker->nama_satker }}</div>
        <p>SIM BBM POLDA NTB - BIRO LOGISTIK</p>
    </div>

    <div class="filter-info">
        Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }} WITA<br>
        @if(request('start_date') || request('end_date'))
            Periode: {{ request('start_date') ?? '...' }} s/d {{ request('end_date') ?? '...' }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Tanggal Bon</th>
                <th width="18%">Tanggal Bayar</th>
                <th width="20%">Kendaraan Hutang</th>
                <th width="20%">Driver</th>
                <th width="15%">Jumlah Bon</th>
                <th width="10%">Pelaksana Pelunasan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hutangs as $index => $hutang)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->format('d/m/y') }}
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->translatedFormat('d M Y') }}<br>
                        {{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->format('H:i') }} WITA
                    </td>
                    <td>
                        <span class="font-bold">{{ $hutang->nopol }}</span><br>
                        {{ $hutang->jenis_kendaraan }}
                    </td>
                    <td>{{ $hutang->nama_driver ?? '-' }}</td>
                    <td class="text-center font-bold">
                        {{ $hutang->jumlah_bon }} L {{ $hutang->jenis_bbm }}
                    </td>
                    <td>
                        {{ $hutang->adminBayar->name ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center italic">Tidak ada data riwayat pembayaran.</td>
                </tr>
            @endforelse
        </tbody>
        @if($hutangs->count() > 0)
            <tfoot>
                <tr>
                    <th colspan="4" class="text-right">TOTAL PEMBAYARAN</th>
                    <th class="text-center">{{ $hutangs->sum('jumlah_bon') }} L</th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        @endif
    </table>

</body>
</body>

</html>