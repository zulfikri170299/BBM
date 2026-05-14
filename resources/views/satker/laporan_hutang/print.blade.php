<!DOCTYPE html>
<html>

<head>
    <title>Laporan Hutang BBM</title>
    <style>
        @page {
            margin: 1cm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9pt;
            color: #333;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
            font-weight: 800;
        }

        .filter-info {
            margin-bottom: 10px;
            font-size: 8pt;
            font-style: italic;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8pt;
            border: 1px solid #999;
            padding: 8px 4px;
            text-align: center;
        }

        td {
            border: 1px solid #999;
            padding: 6px 4px;
            vertical-align: middle;
            font-size: 8pt;
            word-wrap: break-word;
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

        .status-lunas {
            color: #059669;
            font-weight: bold;
        }

        .status-belum {
            color: #dc2626;
            font-weight: bold;
        }
    </style>
</head>

<body>
    @include('components.pdf-header')
    
    <div class="header">
        <h1>LAPORAN HUTANG BBM</h1>
    </div>

    <div class="filter-info text-right">
        @if(request('start_date') || request('end_date'))
            Periode: {{ request('start_date') ?? '...' }} s/d {{ request('end_date') ?? '...' }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="40">NO</th>
                <th width="80">TGL BON</th>
                <th width="100">TGL BAYAR</th>
                <th>KENDARAAN</th>
                <th>DRIVER</th>
                <th width="100">JUMLAH</th>
                <th width="80">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hutangs as $index => $hutang)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->format('d/m/Y') }}
                    </td>
                    <td class="text-center">
                        @if($hutang->tanggal_bayar)
                            {{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->format('d/m/Y') }}<br>
                            <span style="font-size: 7pt; color: #777;">{{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->format('H:i') }} WITA</span>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <span class="font-bold">{{ $hutang->nopol }}</span><br>
                        <span style="font-size: 7pt; color: #666; text-transform: uppercase;">{{ $hutang->jenis_kendaraan }}</span>
                    </td>
                    <td class="text-center">{{ $hutang->nama_driver ?? '-' }}</td>
                    <td class="text-center">
                        <span class="font-bold">{{ number_format($hutang->jumlah_bon, 0) }} L</span><br>
                        <span style="font-size: 7pt; color: #666;">{{ $hutang->jenis_bbm }}</span>
                    </td>
                    <td class="text-center">
                        @if($hutang->status === 'sudah_dibayar')
                            <span class="status-lunas">Lunas</span>
                        @else
                            <span class="status-belum">Belum Lunas</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center italic">Tidak ada data hutang.</td>
                </tr>
            @endforelse
        </tbody>
        @if($hutangs->count() > 0)
            <tfoot>
                <tr style="background-color: #f8f9fa;">
                    <th colspan="5" class="text-right" style="padding-right: 10px;">TOTAL HUTANG PERTAMAX</th>
                    <th class="text-center">{{ number_format($totalPertamax, 0) }} L</th>
                    <th></th>
                </tr>
                <tr style="background-color: #f8f9fa;">
                    <th colspan="5" class="text-right" style="padding-right: 10px;">TOTAL HUTANG PERTAMINA DEX</th>
                    <th class="text-center">{{ number_format($totalDex, 0) }} L</th>
                    <th></th>
                </tr>
            </tfoot>
        @endif
    </table>

    @include('components.pdf-signature')
</body>

</html>