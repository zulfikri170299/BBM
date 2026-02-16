<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Perubahan Stok BBM</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .header p { margin: 5px 0; color: #666; }
        
        .stock-summary { margin-bottom: 20px; width: 100%; border-collapse: collapse; }
        .stock-summary th, .stock-summary td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .stock-summary th { background-color: #f8f9fa; }
        
        .history-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .history-table th, .history-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .history-table th { background-color: #f8f9fa; font-weight: bold; }
        
        .type-masuk { color: #16a34a; font-weight: bold; }
        .type-keluar { color: #dc2626; font-weight: bold; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Riwayat Perubahan Stok BBM Pusat</h2>
        <p>Dicetak pada: {{ date('d/m/Y H:i') }} WIB</p>
    </div>

    <h3>Ringkasan Stok Saat Ini</h3>
    <table class="stock-summary">
        <thead>
            <tr>
                <th>Jenis BBM</th>
                <th>Saldo Saat Ini</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stocks as $stock)
            <tr>
                <td>{{ $stock->jenis_bbm }}</td>
                <td><strong>{{ number_format($stock->saldo, 0, ',', '.') }} Liter</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Log Perubahan Stok</h3>
    <table class="history-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jenis BBM</th>
                <th>Jumlah</th>
                <th>Tipe</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($history as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $item->jenis_bbm }}</td>
                <td>{{ number_format($item->jumlah, 0, ',', '.') }} L</td>
                <td class="{{ $item->tipe === 'masuk' ? 'type-masuk' : 'type-keluar' }}">
                    {{ ucfirst($item->tipe) }}
                </td>
                <td>{{ $item->keterangan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Aplikasi Manajemen BBM Poldan TB Rolog - {{ date('Y') }}
    </div>
</body>
</html>
