<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nominatif Berita Acara</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11pt; }
        .header { margin-bottom: 20px; font-weight: bold; }
        .header div { line-height: 1.2; text-decoration: underline; }
        .title { text-align: center; font-weight: bold; margin: 30px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid black; padding: 6px; vertical-align: middle; }
        tbody tr { height: 45px; }
        th { text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .signature-box { width: 300px; float: right; text-align: center; margin-top: 20px; }
        .signature-name { text-decoration: underline; font-weight: bold; margin-top: 70px; }
    </style>
</head>
<body>
    <div class="header" style="display: inline-block; text-align: center; border-bottom: 1px solid black; padding-bottom: 2px;">
        <div style="font-weight: bold; text-decoration: none;">KEPOLISIAN NEGARA REPUBLIK INDONESIA</div>
        <div style="font-weight: bold; text-decoration: none;">DAERAH NUSA TENGGARA BARAT</div>
        <div style="font-weight: bold; text-decoration: none;">BIRO LOGISTIK</div>
    </div>

    <div class="title">
        DAFTAR NOMINATIF BUKTI PENERIMAAN BBM {{ $startDate && $endDate ? '' : 'TW ' . $tw }}<br>
        PERIODE {{ strtoupper($periodeText) }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="25%">URAIAN</th>
                <th width="13%">PERTAMAX</th>
                <th width="17%">PERTAMINADEX</th>
                <th width="10%">SATUAN</th>
                <th colspan="2" width="30%">TANDA TANGAN</th>
            </tr>
            <tr>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>4</th>
                <th>5</th>
                <th colspan="2">6</th>
            </tr>
        </thead>
        <tbody>
            @php $totalP = 0; $totalD = 0; $idx = 0; @endphp
            @foreach($logs as $log)
                @php 
                    $totalP += $log->total_pertamax; 
                    $totalD += $log->total_dex; 
                    $idx++;
                    $dots = str_repeat('.', 12);
                @endphp
                <tr>
                    <td class="text-center">{{ $idx }}</td>
                    <td>{{ ucwords(strtolower($log->satker->nama_satker)) }}</td>
                    <td class="text-center">{{ number_format($log->total_pertamax, 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($log->total_dex, 0, ',', '.') }}</td>
                    <td class="text-center">Liter</td>
                    <td style="border-right: none;" class="text-left">
                        {{ $idx % 2 != 0 ? $idx . $dots : '' }}
                    </td>
                    <td style="border-left: none;" class="text-left">
                        {{ $idx % 2 == 0 ? $idx . $dots : '' }}
                    </td>
                </tr>
            @endforeach
            <tr class="font-bold">
                <td colspan="2" class="text-center">JUMLAH</td>
                <td class="text-center">{{ rtrim(rtrim(number_format($totalP, 2, ',', '.'), '0'), ',') }}</td>
                <td class="text-center">{{ rtrim(rtrim(number_format($totalD, 2, ',', '.'), '0'), ',') }}</td>
                <td></td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

    @php
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        $nama = $settings['nominatif_nama'] ?? '';
        $pangkat = $settings['nominatif_pangkat'] ?? '';
        $nrp = $settings['nominatif_nrp'] ?? '';
        $jabatan = $settings['nominatif_jabatan'] ?? '';
    @endphp

    <div class="signature-box" style="width: 320px;">
        <table cellspacing="0" cellpadding="0" style="width: 90%; margin-left: 10%; border: none; font-size: 11pt; line-height: 1; border-collapse: collapse; margin-bottom: 0;">
            <tr>
                <td style="width: 45%; border: none; padding: 0px; margin: 0px; text-align: left;">Dikeluarkan di</td>
                <td style="width: 55%; border: none; padding: 0px; margin: 0px; text-align: left;">: Mataram</td>
            </tr>
            <tr>
                <td style="border: none; padding: 0px; margin: 0px; text-align: left;">Pada tanggal</td>
                <td style="border: none; padding: 0px; margin: 0px; text-align: left;">: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $endDate ? \Carbon\Carbon::parse($endDate)->translatedFormat('F Y') : ($namaBulan . ' ' . $tahun) }}</td>
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
