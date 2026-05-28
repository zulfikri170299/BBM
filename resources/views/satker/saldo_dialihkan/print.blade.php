<!DOCTYPE html>
<html>
<head>
    <title>Laporan Saldo Dialihkan (Satker)</title>
    <style>
        @page { size: 330.2mm 215.9mm landscape; margin: 10mm; }
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #444; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; text-align: center; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 3px 0; color: #333; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .summary-box { margin-top: 20px; width: 40%; margin-left: auto; page-break-inside: avoid; }
        .footer { margin-top: 30px; font-size: 9px; text-align: right; color: #666; font-style: italic; }
    </style>
</head>
<body>
    @include('components.pdf-header')
    <div class="header">
        <h1>LAPORAN SALDO BBM DI ALIHKAN OLEH {{ strtoupper(auth()->user()->satker->nama_satker ?? 'SATKER') }}</h1>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="15%">TANGGAL</th>
                <th width="30%">KENDARAAN / NOPOL</th>
                <th width="15%">LITER</th>
                <th width="35%">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($riwayat as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($item->created_at)->timezone('Asia/Makassar')->translatedFormat('d M Y') }}<br>
                    <small>{{ \Carbon\Carbon::parse($item->created_at)->timezone('Asia/Makassar')->format('H:i') }} WITA</small>
                </td>
                <td>{{ $item->kendaraan->jenis_kendaraan ?? '-' }}<br><small>{{ $item->kendaraan->no_polisi ?? '-' }}</small></td>
                <td class="text-center">{{ $item->jumlah }} L<br><small>{{ $item->jenis_bbm }}</small></td>
                <td>{{ $item->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
            @if($riwayat->isEmpty())
            <tr>
                <td colspan="5" class="text-center" style="padding: 20px;">Tidak ada riwayat saldo yang dialihkan.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="summary-box">
        <table>
            <tr style="background-color: #e0e0e0;">
                <td colspan="2" style="font-weight: bold; text-align: center;">Total Pemotongan per Jenis BBM</td>
            </tr>
            <tr>
                <td>Pertamax</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($riwayat->where('jenis_bbm', 'Pertamax')->sum('jumlah'), 0, ',', '.') }} L</td>
            </tr>
            <tr>
                <td>Pertamina Dex</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($riwayat->where('jenis_bbm', 'Pertamina Dex')->sum('jumlah'), 0, ',', '.') }} L</td>
            </tr>
        </table>
    </div>

    @include('components.pdf-signature')
</body>
</html>
