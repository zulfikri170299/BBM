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
            font-size: 10pt;
            color: #333;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18pt;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 10pt;
            color: #666;
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
            margin-top: 30px;
            width: 100%;
        }

        .signature-box {
            width: 250px;
            float: right;
            text-align: center;
        }

        .signature-space {
            height: 60px;
        }
    </style>
</head>

<body>
    @include('components.pdf-header')
    <div class="header">
        <h1>Laporan Hutang BBM</h1>
    </div>

    <div class="filter-info">
        @if(request('start_date') || request('end_date'))
            Periode: {{ request('start_date') ?? '...' }} s/d {{ request('end_date') ?? '...' }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Tanggal Bon</th>
                <th width="14%">Tanggal Bayar</th>
                <th width="18%">Satker</th>
                <th width="18%">Kendaraan Hutang</th>
                <th width="12%">Driver</th>
                <th width="11%">Jumlah Bon</th>
                <th width="12%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hutangs as $index => $hutang)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        @if($hutang->tanggal_bon)
                            {{ \Carbon\Carbon::parse($hutang->tanggal_bon)->translatedFormat('d/m/Y') }}
                        @else
                            {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->translatedFormat('d/m/Y') }}
                        @endif
                    </td>
                    <td>
                        @if($hutang->tanggal_bayar)
                            {{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->translatedFormat('d M Y') }}<br>
                            {{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->format('H:i') }} WITA
                        @else
                            <span style="font-style: italic; color: #999;">-</span>
                        @endif
                    </td>
                    <td class="font-bold">{{ $hutang->satker->nama_satker }}</td>
                    <td>
                        <span class="font-bold">{{ $hutang->nopol }}</span><br>
                        {{ $hutang->jenis_kendaraan }}
                    </td>
                    <td>{{ $hutang->nama_driver ?? '-' }}</td>
                    <td class="text-center font-bold">
                        {{ $hutang->jumlah_bon }} L {{ $hutang->jenis_bbm }}
                    </td>
                    <td class="text-center">
                        @if($hutang->status === 'sudah_dibayar')
                            <span class="font-bold" style="color: #059669;">LUNAS</span><br>
                            <span style="font-size: 7.5pt; color: #64748b;">Oleh: {{ $hutang->adminBayar->name ?? '-' }}</span>
                        @else
                            <span class="font-bold" style="color: #e11d48;">BELUM LUNAS</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center italic">Tidak ada data riwayat pembayaran.</td>
                </tr>
            @endforelse
        </tbody>
        @if($hutangs->count() > 0)
            @php
                $totalPertamax = $hutangs->where('jenis_bbm', 'Pertamax')->sum('jumlah_bon') + $hutangs->where('jenis_bbm', 'PERTAMAX')->sum('jumlah_bon');
                $totalDex = $hutangs->where('jenis_bbm', 'Pertamina Dex')->sum('jumlah_bon') + $hutangs->where('jenis_bbm', 'PERTAMINA DEX')->sum('jumlah_bon');
            @endphp
            <tfoot>
                @if($totalPertamax > 0)
                <tr>
                    <th colspan="6" class="text-right">TOTAL PERTAMAX</th>
                    <th class="text-center">{{ $totalPertamax }} L</th>
                    <th></th>
                </tr>
                @endif
                @if($totalDex > 0)
                <tr>
                    <th colspan="6" class="text-right">TOTAL PERTAMINA DEX</th>
                    <th class="text-center">{{ $totalDex }} L</th>
                    <th></th>
                </tr>
                @endif
                <tr>
                    <th colspan="6" class="text-right">TOTAL KESELURUHAN</th>
                    <th class="text-center">{{ $totalPertamax + $totalDex }} L</th>
                    <th></th>
                </tr>
            </tfoot>
        @endif
    </table>

    @include('components.pdf-signature')
</body>

</html>
