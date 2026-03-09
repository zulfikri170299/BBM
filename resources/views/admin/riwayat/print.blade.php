<!DOCTYPE html>
<html>
<head>
    <title>Laporan Riwayat Pengisian BBM</title>
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
        .badge { display: inline-block; padding: 2px 5px; font-size: 10px; font-weight: bold; border-radius: 3px; border: 1px solid #aaa; }
    </style>
</head>
<body>
    @include('components.pdf-header')
    <div class="header">
        <h1>Laporan Riwayat Pengisian BBM</h1>

        
        @if(request('dari') || request('sampai'))
            <p>Periode: 
                {{ request('dari') ? date('d/m/Y', strtotime(request('dari'))) : 'Awal' }} 
                s.d. 
                {{ request('sampai') ? date('d/m/Y', strtotime(request('sampai'))) : 'Sekarang' }}
            </p>
        @endif


    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="12%">Waktu</th>
                @if(!isset($satker))
                    <th width="18%">Satker</th>
                @endif                <th width="15%">Kendaraan</th>
                <th width="12%">No. Polisi</th>
                <th width="10%">Jenis BBM</th>
                <th width="15%">Nama Driver</th>
                <th width="10%" class="text-right">Jumlah (L)</th>
                <th width="12%">Petugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $index => $trx)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($trx->tanggal)->format('d/m/Y') }}<br>
                    <small>{{ \Carbon\Carbon::parse($trx->tanggal)->format('H:i') }}</small>
                </td>
                @if(!isset($satker))
                    <td>{{ $trx->satker->nama_satker ?? ($trx->kendaraan->satker->nama_satker ?? ($trx->personel->satker->nama_satker ?? '-')) }}</td>
                @endif                <td>{{ $trx->kendaraan->jenis_kendaraan ?? ($trx->personel->nama ?? '-') }}</td>
                <td class="text-center"><b>{{ $trx->kendaraan->no_polisi ?? ($trx->personel->nrp ?? '-') }}</b></td>
                <td class="text-center">{{ $trx->jenis_bbm ?? '-' }}</td>
                <td>{{ $trx->nama_driver ?? ($trx->personel->nama ?? '-') }}</td>
                <td class="text-right"><strong>{{ number_format($trx->liter, 0, ',', '.') }}</strong></td>
                <td>{{ $trx->petugas->name ?? '-' }}</td>
            </tr>
            @endforeach
            
            @if($transaksis->isEmpty())
            <tr>
                <td colspan="8" class="text-center" style="padding: 20px;">Tidak ada data transaksi.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- Summary Box -->
    @if(isset($summaryBbm) && $summaryBbm->isNotEmpty())
    <div class="summary-box">
        <table>
            <tr style="background-color: #e0e0e0;">
                <td colspan="2" style="font-weight: bold; text-align: center;">Total Pengisian per Jenis BBM</td>
            </tr>
            @foreach($summaryBbm as $jenis => $total)
            <tr>
                <td>{{ $jenis }}</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($total, 0, ',', '.') }} L</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif


    @include('components.pdf-signature')
</body>
</html>
