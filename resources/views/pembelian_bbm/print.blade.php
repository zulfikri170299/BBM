<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pembelian BBM</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .header p { margin: 5px 0; color: #666; }
        
        .history-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .history-table th, .history-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .history-table th { background-color: #f8f9fa; font-weight: bold; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    @include('components.pdf-header')
    <div class="header">
        <h2>Laporan Riwayat Pembelian BBM</h2>
        <p>
            @if(request('start_date') && request('end_date'))
                Periode: {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}
            @elseif(request('start_date'))
                Dari Tanggal: {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }}
            @elseif(request('end_date'))
                Sampai Tanggal: {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}
            @endif
        </p>
        <p>
            @if(request('jenis_bbm'))
                Jenis BBM: {{ request('jenis_bbm') }}
            @else
                Jenis BBM: Semua Jenis (Pertamax & Pertamina Dex)
            @endif
        </p>
    </div>

    <table class="history-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 35%;">Tanggal Pembelian</th>
                <th style="width: 30%; text-align: center;">Jenis BBM</th>
                <th style="width: 30%; text-align: right;">Jumlah (Liter)</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalPertamax = 0; 
                $totalDex = 0; 
            @endphp
            @forelse($pembelians as $index => $item)
            @php 
                if($item->jenis_bbm == 'Pertamax') {
                    $totalPertamax += $item->jumlah;
                } else {
                    $totalDex += $item->jumlah;
                }
            @endphp
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}</td>
                <td style="text-align: center;">{{ $item->jenis_bbm }}</td>
                <td style="text-align: right;">{{ number_format($item->jumlah, 0, ',', '.') }} L</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; font-style: italic; color: #666;">Belum ada data pembelian BBM untuk filter ini.</td>
            </tr>
            @endforelse
        </tbody>
        @if($pembelians->count() > 0)
        <tfoot>
            @if(request('jenis_bbm') == 'Pertamax')
            <tr>
                <th colspan="3" style="text-align: right; background-color: #f8f9fa;">Total Pertamax</th>
                <th style="text-align: right; background-color: #f8f9fa;">{{ rtrim(rtrim(number_format($totalPertamax, 2, ',', '.'), '0'), ',') }} L</th>
            </tr>
            @elseif(request('jenis_bbm') == 'Pertamina Dex')
            <tr>
                <th colspan="3" style="text-align: right; background-color: #f8f9fa;">Total Pertamina Dex</th>
                <th style="text-align: right; background-color: #f8f9fa;">{{ rtrim(rtrim(number_format($totalDex, 2, ',', '.'), '0'), ',') }} L</th>
            </tr>
            @else
            <tr>
                <th colspan="3" style="text-align: right; background-color: #f8f9fa;">Total Pertamax</th>
                <th style="text-align: right; background-color: #f8f9fa;">{{ rtrim(rtrim(number_format($totalPertamax, 2, ',', '.'), '0'), ',') }} L</th>
            </tr>
            <tr>
                <th colspan="3" style="text-align: right; background-color: #f8f9fa;">Total Pertamina Dex</th>
                <th style="text-align: right; background-color: #f8f9fa;">{{ rtrim(rtrim(number_format($totalDex, 2, ',', '.'), '0'), ',') }} L</th>
            </tr>
            <tr>
                <th colspan="3" style="text-align: right; background-color: #f8f9fa;">Total Keseluruhan</th>
                <th style="text-align: right; background-color: #f8f9fa;">{{ number_format($totalPertamax + $totalDex, 0, ',', '.') }} L</th>
            </tr>
            @endif
        </tfoot>
        @endif
    </table>

    @include('components.pdf-signature')
</body>
</html>
