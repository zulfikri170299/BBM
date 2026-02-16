<!DOCTYPE html>
<html>
<head>
    <title>Laporan Top Up Saldo</title>
    <style>
        @page { size: 330.2mm 215.9mm landscape; margin: 10mm; }
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 5px 0; color: #555; }
        .total { font-weight: bold; margin-top: 10px; text-align: right; }
        .footer { margin-top: 30px; font-size: 10px; text-align: right; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN TOP UP SALDO KENDARAAN</h1>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->setTimezone('Asia/Makassar')->format('d F Y H:i') }} WITA</p>
        @if(request('start_date') || request('end_date'))
            <p>Periode: 
                {{ request('start_date') ? date('d/m/Y', strtotime(request('start_date'))) : 'Awal' }} 
                s/d 
                {{ request('end_date') ? date('d/m/Y', strtotime(request('end_date'))) : 'Sekarang' }}
            </p>
        @endif
        @if(request('satker_id') && $riwayats->isNotEmpty())
            <p>Satker: {{ $riwayats->first()->kendaraan->satker->nama_satker }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 20%;">Satker</th>
                <th style="width: 20%;">Kendaraan</th>
                <th style="width: 15%;">Jenis Kendaraan</th>
                <th style="width: 10%;">Jenis BBM</th>
                <th style="width: 10%; text-align: center;">Metode</th>
                <th style="width: 15%; text-align: right;">Jumlah (L)</th>
                <th style="width: 10%;">User</th>
            </tr>
        </thead>
        <tbody>
            @php $totalLiter = 0; @endphp
            @foreach($riwayats as $index => $riwayat)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $riwayat->created_at->setTimezone('Asia/Makassar')->format('d/m/Y H:i') }} WITA</td>
                <td>{{ $riwayat->kendaraan->satker->nama_satker ?? '-' }}</td>
                <td>
                    <b>{{ $riwayat->kendaraan->no_polisi ?? '-' }}</b><br>
                    <small>{{ $riwayat->kendaraan->kode_kendaraan ?? '' }}</small>
                </td>
                <td>{{ $riwayat->kendaraan->jenis_kendaraan ?? '-' }}</td>
                <td>{{ $riwayat->kendaraan->jenis_bbm ?? '-' }}</td>
                <td style="text-align: center;">{{ $riwayat->metode }}</td>
                <td style="text-align: right;">{{ number_format($riwayat->jumlah, 0, ',', '.') }}</td>
                <td>{{ $riwayat->user->name ?? '-' }}</td>
            </tr>
            @php $totalLiter += $riwayat->jumlah; @endphp
            @endforeach
        </tbody>
    </table>

    @if(isset($summary) && $summary->isNotEmpty())
    <div style="margin-top: 30px; width: 40%; margin-left: auto;">
        <table style="border: 1px solid #ddd;">
            <tr style="background-color: #f9f9f9;">
                <td colspan="2" style="font-weight: bold; text-align: center;">Total per Jenis BBM</td>
            </tr>
            @foreach($summary as $jenis => $total)
            <tr>
                <td>{{ $jenis }}</td>
                <td style="text-align: right; font-weight: bold;">{{ number_format($total, 0, ',', '.') }} L</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    <div class="footer">
        Dicetak oleh: {{ auth()->user()->name }}
    </div>
</body>
</html>
