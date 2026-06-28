<!DOCTYPE html>
<html>

<head>
    <title>Laporan Transfer Saldo</title>
    <style>
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
            font-size: 16px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .header h2 {
            margin: 4px 0;
            font-size: 13px;
            font-weight: normal;
            color: #555;
        }

        .header p {
            margin: 3px 0;
            font-size: 10px;
            color: #777;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background-color: #2d3748;
            color: #fff;
            padding: 8px 6px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 7px 6px;
            border-bottom: 1px solid #e2e8f0;
        }

        tr:nth-child(even) {
            background-color: #f7fafc;
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

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }

        .badge-blue {
            background-color: #ebf5ff;
            color: #1a56db;
        }

        .badge-green {
            background-color: #f0fdf4;
            color: #15803d;
        }

        .summary-table {
            width: 35%;
            margin-left: auto;
            margin-top: 25px;
        }

        .summary-table th {
            background-color: #059669;
            font-size: 11px;
        }

        .summary-table td {
            padding: 6px 10px;
        }

        .footer {
            margin-top: 30px;
            font-size: 9px;
            color: #999;
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }

        .arrow {
            color: #059669;
            font-weight: bold;
        }

        .small {
            font-size: 9px;
            color: #888;
        }
    </style>
</head>

<body>
    @include('components.pdf-header')
    <div class="header">
        <h1>Laporan Transfer Saldo Kendaraan</h1>
        @if(request('start_date') || request('end_date'))
            <p>Periode:
                {{ request('start_date') ? date('d/m/Y', strtotime(request('start_date'))) : 'Awal' }}
                s/d
                {{ request('end_date') ? date('d/m/Y', strtotime(request('end_date'))) : 'Sekarang' }}
            </p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 4%;" class="text-center">No</th>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 18%;">Kendaraan (Sumber)</th>
                <th style="width: 10%;" class="text-center">Jenis BBM</th>
                <th style="width: 3%;" class="text-center"></th>
                <th style="width: 18%;">Personel (Tujuan)</th>
                <th style="width: 8%;" class="text-right">Jumlah (L)</th>
                <th style="width: 23%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($riwayats as $index => $riwayat)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $riwayat->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($riwayat->tipe_log == 'masuk' || ($riwayat->tipe_log == 'transfer' && !$riwayat->kendaraan_id))
                            <b>STOK PUSAT</b><br>
                            <span class="small">SUPER ADMIN</span>
                        @else
                            <b>{{ $riwayat->kendaraan->no_polisi ?? '-' }}</b><br>
                            <span class="small">{{ $riwayat->kendaraan->jenis_kendaraan ?? '-' }}</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span
                            class="badge {{ (($riwayat->kendaraan->jenis_bbm ?? '') == 'Pertamina Dex' || ($riwayat->tujuanKendaraan->jenis_bbm ?? '') == 'Pertamina Dex') ? 'badge-green' : 'badge-blue' }}">
                            {{ $riwayat->kendaraan->jenis_bbm ?? ($riwayat->tujuanKendaraan->jenis_bbm ?? '-') }}
                        </span>
                    </td>
                    <td class="text-center arrow">→</td>
                    <td>
                        @if($riwayat->tipe_log == 'masuk' || ($riwayat->tipe_log == 'transfer' && $riwayat->tujuan_kendaraan_id))
                            <b>{{ $riwayat->tujuanKendaraan->no_polisi ?? '-' }}</b><br>
                            <span class="small">{{ $riwayat->tujuanKendaraan->jenis_kendaraan ?? '-' }}</span>
                        @elseif($riwayat->personel_id)
                            <b>{{ $riwayat->personel->nama ?? '-' }}</b><br>
                            <span class="small">NRP: {{ $riwayat->personel->nrp ?? '-' }}</span>
                        @else
                            <b style="color: #e11d48;">PUSAT (POTONGAN)</b><br>
                            <span class="small">Pengurangan Saldo</span>
                        @endif
                    </td>
                    <td class="text-right font-bold">{{ number_format($riwayat->jumlah, 0, ',', '.') }}</td>
                    <td>{{ $riwayat->keterangan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($summary->isNotEmpty())
        <table class="summary-table">
            <thead>
                <tr>
                    <th colspan="2" class="text-center">Total per Jenis BBM</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($summary as $jenis => $total)
                    <tr>
                        <td>
                            <span
                                class="badge {{ $jenis == 'Pertamina Dex' ? 'badge-green' : 'badge-blue' }}">{{ $jenis }}</span>
                        </td>
                        <td class="text-right font-bold">{{ rtrim(rtrim(number_format($total, 2, ',', '.'), '0'), ',') }} L</td>
                    </tr>
                    @php $grandTotal += $total; @endphp
                @endforeach
                <tr style="border-top: 2px solid #333;">
                    <td class="font-bold">GRAND TOTAL</td>
                    <td class="text-right font-bold">{{ rtrim(rtrim(number_format($grandTotal, 2, ',', '.'), '0'), ',') }} L</td>
                </tr>
            </tbody>
        </table>
    @endif

    <div class="footer">
    </div>
    @include('components.pdf-signature')
</body>

</html>