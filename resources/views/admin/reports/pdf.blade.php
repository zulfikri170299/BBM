<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi BBM</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background-color: #eee; }
        .text-right { text-align: right; }
        .center { text-align: center; }
    </style>
</head>
<body>
    <h2 class="center">Laporan Transaksi BBM</h2>
    <p class="center">Periode: {{ $request->start_date }} s/d {{ $request->end_date }}</p>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Satker</th>
                <th>Kendaraan</th>
                <th>BBM</th>
                <th>Liter</th>
                <th>Total</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $key => $trx)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $trx->kendaraan->satker->nama_satker }}</td>
                <td>{{ $trx->kendaraan->no_polisi }}</td>
                <td>{{ $trx->kendaraan->jenis_bbm }}</td>
                <td class="text-right">{{ $trx->liter }}</td>
                <td class="text-right">{{ number_format($trx->total) }}</td>
                <td>{{ $trx->petugas->name }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-right">Total</th>
                <th class="text-right">{{ $transaksi->sum('liter') }}</th>
                <th class="text-right">{{ number_format($transaksi->sum('total')) }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
