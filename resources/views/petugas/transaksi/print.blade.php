<!DOCTYPE html>
<html>
<head>
    <title>Struk BBM - {{ $transaksi->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 10px;
            margin: 0;
            padding: 5px;
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 10px;
        }
        .line {
            border-bottom: 1px dashed #000;
            margin: 5px 0;
        }
        table {
            width: 100%;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <strong>SIMAK BBM</strong><br>
        Bukti Transaksi Pengisian BBM
    </div>
    <div class="line"></div>
    <table>
        <tr>
            <td>Tgl</td>
            <td class="text-right">{{ $transaksi->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td>Petugas</td>
            <td class="text-right">{{ auth()->user()->name }}</td>
        </tr>
        <tr>
            <td>Kendaraan</td>
            <td class="text-right">{{ $transaksi->kendaraan->no_polisi }}</td>
        </tr>
        <tr>
            <td>Satker</td>
            <td class="text-right">{{ $transaksi->kendaraan->satker->nama_satker }}</td>
        </tr>
    </table>
    <div class="line"></div>
    <table>
        <tr>
            <td>Jenis BBM</td>
            <td class="text-right">{{ $transaksi->kendaraan->jenis_bbm }}</td>
        </tr>
        <tr>
            <td>Liter</td>
            <td class="text-right">{{ $transaksi->liter }} L</td>
        </tr>
        <tr>
            <td>Harga/L</td>
            <td class="text-right">{{ number_format($transaksi->harga_per_liter) }}</td>
        </tr>
        <tr>
            <td><strong>TOTAL</strong></td>
            <td class="text-right"><strong>Rp {{ number_format($transaksi->total) }}</strong></td>
        </tr>
    </table>
    <div class="line"></div>
    <div class="footer">
        Sisa Saldo: {{ number_format($transaksi->kendaraan->saldo, 1) }} Liter<br>
        <br>
        Terima Kasih
    </div>
</body>
</html>
