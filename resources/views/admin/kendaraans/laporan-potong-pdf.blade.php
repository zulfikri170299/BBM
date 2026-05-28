<!DOCTYPE html>
<html>
<head>
    <title>Laporan Potong Saldo</title>
    <style>
        @page {
            size: 330.2mm 215.9mm landscape;
            margin: 10mm;
        }
        body {
            font-family: sans-serif;
            font-size: 11px;
            line-height: 1.4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    @include('components.pdf-header')

    <div class="header">
        <h1>LAPORAN POTONG SALDO KENDARAAN</h1>
        @if(request('start_date') || request('end_date'))
            <p>Periode: 
                {{ request('start_date') ? date('d/m/Y', strtotime(request('start_date'))) : 'Awal' }} 
                s/d 
                {{ request('end_date') ? date('d/m/Y', strtotime(request('end_date'))) : 'Sekarang' }}
            </p>
        @endif
        @if($riwayat->isNotEmpty() && request('satker_id'))
            <p>Satker: {{ $riwayat->first()->satker->nama_satker ?? '-' }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">NO</th>
                <th style="width: 15%">TANGGAL</th>
                <th style="width: 15%">ADMIN</th>
                <th style="width: 15%">SATKER</th>
                <th style="width: 15%">JENIS KENDARAAN</th>
                <th style="width: 13%">NOPOL</th>
                <th style="width: 10%">JENIS BBM</th>
                <th style="width: 10%">JUMLAH</th>
                <th style="width: 18%">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat as $index => $r)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $r->created_at->setTimezone('Asia/Makassar')->format('d/m/Y H:i') }}</td>
                    <td>{{ $r->user->name ?? '-' }}</td>
                    <td>{{ $r->satker->nama_satker ?? '-' }}</td>
                    <td>{{ $r->kendaraan->jenis_kendaraan ?? '-' }}</td>
                    <td class="text-center font-bold">{{ $r->kendaraan->no_polisi ?? '-' }}</td>
                    <td class="text-center">{{ $r->jenis_bbm }}</td>
                    <td class="text-right font-bold">-{{ number_format($r->jumlah, 0, ',', '.') }} L</td>
                    <td>{{ $r->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada data pemotongan saldo.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @include('components.pdf-signature')
</body>
</html>
