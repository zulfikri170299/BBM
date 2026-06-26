<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tabel Sounding BBM</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h3 {
            margin: 0;
            padding: 0;
            font-size: 14px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }
        th {
            font-weight: bold;
            background-color: #f0f0f0;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
        .total-row {
            font-weight: bold;
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>

@include('components.pdf-header')

<div class="header">
    <h3>DATA SOUNDING BBM</h3>
    <h3>{{ $judulBulan }}</h3>
</div>

<table>
    <thead>
        <tr>
            <th rowspan="2" style="width: 30px;">NO</th>
            <th rowspan="2" style="width: 80px;">TANGGAL</th>
            <th rowspan="2" style="width: 130px;">JENIS BBM</th>
            <th colspan="4">PENGUKURAN (LITER)</th>
            <th rowspan="2" style="width: 150px;">KETERANGAN / DOKUMENTASI</th>
        </tr>
        <tr>
            <th>AWAL</th>
            <th>PEMAKAIAN</th>
            <th>AKHIR</th>
            <th>SUSUT</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totals = [];
        @endphp
        
        @forelse($soundings as $index => $item)
            @php
                $jenis = $item->jenis_bbm;
                if(!isset($totals[$jenis])) {
                    $totals[$jenis] = ['awal' => 0, 'akhir' => 0, 'pengeluaran' => 0, 'susut' => 0];
                }
                $totals[$jenis]['awal'] += $item->stok_awal;
                $totals[$jenis]['akhir'] += $item->stok_akhir;
                $totals[$jenis]['pengeluaran'] += $item->pengeluaran_aplikasi;
                $totals[$jenis]['susut'] += $item->susut;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-M-Y') }}</td>
                <td>{{ $item->jenis_bbm }}</td>
                <td class="text-right">{{ number_format($item->stok_awal, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($item->pengeluaran_aplikasi, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($item->stok_akhir, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($item->susut, 0, ',', '.') }}</td>
                <td class="text-left">
                    @if($item->dokumentasi)
                        Terdokumentasi
                    @else
                        -
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8">Tidak ada data.</td>
            </tr>
        @endforelse
    </tbody>
    @if(count($soundings) > 0)
    <tfoot>
        @foreach($totals as $jenis => $total)
        <tr class="total-row">
            <td colspan="3" class="text-right">TOTAL {{ $jenis }}</td>
            <td class="text-right">{{ number_format($total['awal'], 0, ',', '.') }}</td>
            <td class="text-right">{{ number_format($total['pengeluaran'], 0, ',', '.') }}</td>
            <td class="text-right">{{ number_format($total['akhir'], 0, ',', '.') }}</td>
            <td class="text-right">{{ number_format($total['susut'], 0, ',', '.') }}</td>
            <td></td>
        </tr>
        @endforeach
    </tfoot>
    @endif
</table>

    @php
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        $nama = $settings['nominatif_nama'] ?? '';
        $pangkat = $settings['nominatif_pangkat'] ?? '';
        $nrp = $settings['nominatif_nrp'] ?? '';
        $jabatan = $settings['nominatif_jabatan'] ?? '';
    @endphp

    <div class="signature-box" style="width: 320px; float: right; text-align: center; margin-top: 50px;">
        <table cellspacing="0" cellpadding="0" style="width: 90%; margin-left: 10%; border: none; font-size: 11pt; line-height: 1; border-collapse: collapse; margin-bottom: 0;">
            <tr>
                <td style="width: 45%; border: none; padding: 0px; margin: 0px; text-align: left;">Dikeluarkan di</td>
                <td style="width: 55%; border: none; padding: 0px; margin: 0px; text-align: left;">: Mataram</td>
            </tr>
            <tr>
                <td style="border: none; padding: 0px; margin: 0px; text-align: left;">Pada tanggal</td>
                <td style="border: none; padding: 0px; margin: 0px; text-align: left;">: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</td>
            </tr>
        </table>
        
        <div style="margin-top: 0px;">
            <div style="display: inline-block; border-top: 1px solid black; padding-top: 2px;">
                {{ $jabatan }}
            </div>
        </div>
        
        <div style="display: inline-block; margin-top: 70px;">
            <div style="border-bottom: 1px solid black;">{{ strtoupper($nama) }}</div>
            <div>{{ strtoupper($pangkat) }} NRP {{ $nrp }}</div>
        </div>
    </div>

</body>
</html>
