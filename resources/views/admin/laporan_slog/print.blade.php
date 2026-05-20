<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40">

<head>
    <title>Laporan Rutin</title>
    <!--[if gte mso 9]>
    <xml>
        <w:WordDocument>
            <w:View>Print</w:View>
            <w:Zoom>100</w:Zoom>
            <w:DoNotOptimizeForBrowser/>
        </w:WordDocument>
    </xml>
    <![endif]-->
    <style>
        @page {
            size: a4 landscape;
            margin: 5mm 10mm 0mm 10mm;
        }
        @page Section1 {
            size: 841.9pt 595.3pt;
            mso-page-orientation: landscape;
            margin: 5mm 10mm 0mm 10mm;
        }
        div.Section1 {
            page: Section1;
        }

        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #000;
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

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            empty-cells: show;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 0.5pt solid #000;
            padding: 3px;
            vertical-align: middle;
        }

        th {
            background-color: #fff;
            text-align: center;
            font-weight: bold;
            font-size: 10px;
        }

        td {
            font-size: 10px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
        
        .text-left {
            text-align: left;
        }

        .font-bold {
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: right;
            color: #777;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
<div class="Section1">
    @if($jenisLaporan == 'bulanan')
        @include('components.pdf-header')
        
        <div style="margin-bottom: 5px;">
            <span style="font-weight: bold; font-size: 12px; border-bottom: 1px solid #000;">LAPORAN BBM BULANAN :</span>
            <br>
            <span style="font-size: 11px;">BULAN : {{ Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F') }} {{ $tahun }}</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th rowspan="2" style="width: 3%; vertical-align: middle;">No</th>
                    <th rowspan="2" style="width: 10%; vertical-align: middle;">Tanggal</th>
                    <th rowspan="2" style="width: 15%; vertical-align: middle;">Uraian</th>
                    <th colspan="6" class="text-center">Jenis dan Kuantum BMP</th>
                    <th rowspan="2" style="width: 8%; vertical-align: middle;">Satuan</th>
                    <th rowspan="2" style="width: 15%; vertical-align: middle;">Keterangan</th>
                </tr>
                <tr>
                    <th style="width: 8%;">Pertamax</th>
                    <th style="width: 8%;">Pertamina Dex</th>
                    <th style="width: 8%;">Mesran</th>
                    <th style="width: 8%;">Meditran</th>
                    <th style="width: 8%;">Mesrania 2T</th>
                    <th style="width: 8%;">Rored</th>
                </tr>
                <tr>
                    @for($col = 1; $col <= 11; $col++)
                        <th style="background-color: #f2f2f2;">{{ $col }}</th>
                    @endfor
                </tr>
            </thead>
                @php $i = 1; @endphp
                @foreach($data['weeks'] as $weekName => $weekData)
                    <tbody style="page-break-inside: avoid;">
                        <!-- Persediaan Awal -->
                        <tr>
                            <td rowspan="5" class="text-center" style="vertical-align: top;">{{ $i++ }}.</td>
                            <td rowspan="5" class="text-left font-bold" style="vertical-align: top;">{{ $weekName }}</td>
                            <td class="text-left">Persediaan awal</td>
                            <td class="text-center">{{ number_format($weekData['awal_pertamax'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">{{ number_format($weekData['awal_dex'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                            <td class="text-center">Liter</td>
                            <td class="text-center"></td>
                        </tr>
                        <!-- Penerimaan -->
                        <tr>
                            <td class="text-left">Penerimaan</td>
                            <td class="text-center">{{ number_format($weekData['terima_pertamax'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">{{ number_format($weekData['terima_dex'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                            <td class="text-center">Liter</td>
                            <td class="text-center"></td>
                        </tr>
                        <!-- Jumlah -->
                        <tr>
                            <td class="text-left font-bold">Jumlah</td>
                            <td class="text-center font-bold">{{ number_format($weekData['jumlah_pertamax'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center font-bold">{{ number_format($weekData['jumlah_dex'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                            <td class="text-center">Liter</td>
                            <td class="text-center"></td>
                        </tr>
                        <!-- Pengeluaran -->
                        <tr>
                            <td class="text-left">Pengeluaran</td>
                            <td class="text-center">{{ number_format($weekData['keluar_pertamax'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">{{ number_format($weekData['keluar_dex'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                            <td class="text-center">Liter</td>
                            <td class="text-center"></td>
                        </tr>
                        <!-- Persediaan Akhir -->
                        <tr>
                            <td class="text-left font-bold">Persediaan akhir</td>
                            <td class="text-center font-bold">{{ number_format($weekData['akhir_pertamax'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center font-bold">{{ number_format($weekData['akhir_dex'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                            <td class="text-center">Liter</td>
                            <td class="text-center"></td>
                        </tr>
                    </tbody>
                @endforeach

                <tbody style="page-break-inside: avoid;">
                    <!-- Rekapitulasi -->
                    <tr>
                        <td colspan="2" class="text-right font-bold" style="vertical-align: top; border-bottom: none;">Rekapitulasi :</td>
                        <td class="text-left">Persediaan awal</td>
                        <td class="text-center">{{ number_format($data['rekap']['awal_pertamax'] ?? $weekData['rekap']['awal_pertamax'], 0, ',', '.') ?: '-' }}</td>
                        <td class="text-center">{{ number_format($data['rekap']['awal_dex'] ?? $weekData['rekap']['awal_dex'], 0, ',', '.') ?: '-' }}</td>
                        <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                        <td class="text-center">Liter</td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-top: none; border-bottom: none;"></td>
                        <td class="text-left">Penerimaan</td>
                        <td class="text-center">{{ number_format($data['rekap']['terima_pertamax'] ?? $weekData['rekap']['terima_pertamax'], 0, ',', '.') ?: '-' }}</td>
                        <td class="text-center">{{ number_format($data['rekap']['terima_dex'] ?? $weekData['rekap']['terima_dex'], 0, ',', '.') ?: '-' }}</td>
                        <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                        <td class="text-center">Liter</td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-top: none; border-bottom: none;"></td>
                        <td class="text-left font-bold">Jumlah</td>
                        <td class="text-center font-bold">{{ number_format($data['rekap']['jumlah_pertamax'] ?? $weekData['rekap']['jumlah_pertamax'], 0, ',', '.') ?: '-' }}</td>
                        <td class="text-center font-bold">{{ number_format($data['rekap']['jumlah_dex'] ?? $weekData['rekap']['jumlah_dex'], 0, ',', '.') ?: '-' }}</td>
                        <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                        <td class="text-center">Liter</td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-top: none; border-bottom: none;"></td>
                        <td class="text-left">Pengeluaran</td>
                        <td class="text-center">{{ number_format($data['rekap']['keluar_pertamax'] ?? $weekData['rekap']['keluar_pertamax'], 0, ',', '.') ?: '-' }}</td>
                        <td class="text-center">{{ number_format($data['rekap']['keluar_dex'] ?? $weekData['rekap']['keluar_dex'], 0, ',', '.') ?: '-' }}</td>
                        <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                        <td class="text-center">Liter</td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-top: none;"></td>
                        <td class="text-left font-bold">Persediaan akhir</td>
                        <td class="text-center font-bold">{{ number_format($data['rekap']['akhir_pertamax'] ?? $weekData['rekap']['akhir_pertamax'], 0, ',', '.') ?: '-' }}</td>
                        <td class="text-center font-bold">{{ number_format($data['rekap']['akhir_dex'] ?? $weekData['rekap']['akhir_dex'], 0, ',', '.') ?: '-' }}</td>
                        <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                        <td class="text-center">Liter</td>
                        <td class="text-center"></td>
                    </tr>
                </tbody>
        </table>

        @include('components.pdf-signature-slog', ['bulan' => $bulan, 'tahun' => $tahun])
    @elseif($jenisLaporan == 'triwulan')
        @include('components.pdf-header')
        
        <div style="margin-bottom: 5px;">
            <span style="font-weight: bold; font-size: 12px; border-bottom: 1px solid #000;">LAPORAN BBM TRIWULAN :</span>
            <br>
            <span style="font-size: 11px;">TRIWULAN : {{ $tw }} TAHUN {{ $tahun }}</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th rowspan="2" style="width: 3%; vertical-align: middle;">No</th>
                    <th rowspan="2" style="width: 10%; vertical-align: middle;">Bulan</th>
                    <th rowspan="2" style="width: 15%; vertical-align: middle;">Uraian</th>
                    <th colspan="6" class="text-center">Jenis dan Kuantum BMP</th>
                    <th rowspan="2" style="width: 8%; vertical-align: middle;">Satuan</th>
                    <th rowspan="2" style="width: 15%; vertical-align: middle;">Keterangan</th>
                </tr>
                <tr>
                    <th style="width: 8%;">Pertamax</th>
                    <th style="width: 8%;">Pertamina Dex</th>
                    <th style="width: 8%;">Mesran</th>
                    <th style="width: 8%;">Meditran</th>
                    <th style="width: 8%;">Mesrania 2T</th>
                    <th style="width: 8%;">Rored</th>
                </tr>
                <tr>
                    @for($col = 1; $col <= 11; $col++)
                        <th style="background-color: #f2f2f2;">{{ $col }}</th>
                    @endfor
                </tr>
            </thead>
                @php $i = 1; @endphp
                @foreach($data['months'] as $monthName => $monthData)
                    <tbody style="page-break-inside: avoid;">
                        <!-- Persediaan Awal -->
                        <tr>
                            <td rowspan="5" class="text-center" style="vertical-align: top;">{{ $i++ }}.</td>
                            <td rowspan="5" class="text-left font-bold" style="vertical-align: top;">{{ $monthName }}</td>
                            <td class="text-left">Persediaan awal</td>
                            <td class="text-center">{{ number_format($monthData['awal_pertamax'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">{{ number_format($monthData['awal_dex'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                            <td class="text-center">Liter</td>
                            <td class="text-center"></td>
                        </tr>
                        <!-- Penerimaan -->
                        <tr>
                            <td class="text-left">Penerimaan</td>
                            <td class="text-center">{{ number_format($monthData['terima_pertamax'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">{{ number_format($monthData['terima_dex'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                            <td class="text-center">Liter</td>
                            <td class="text-center"></td>
                        </tr>
                        <!-- Jumlah -->
                        <tr>
                            <td class="text-left font-bold">Jumlah</td>
                            <td class="text-center font-bold">{{ number_format($monthData['jumlah_pertamax'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center font-bold">{{ number_format($monthData['jumlah_dex'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                            <td class="text-center">Liter</td>
                            <td class="text-center"></td>
                        </tr>
                        <!-- Pengeluaran -->
                        <tr>
                            <td class="text-left">Pengeluaran</td>
                            <td class="text-center">{{ number_format($monthData['keluar_pertamax'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">{{ number_format($monthData['keluar_dex'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                            <td class="text-center">Liter</td>
                            <td class="text-center"></td>
                        </tr>
                        <!-- Persediaan Akhir -->
                        <tr>
                            <td class="text-left font-bold">Persediaan akhir</td>
                            <td class="text-center font-bold">{{ number_format($monthData['akhir_pertamax'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center font-bold">{{ number_format($monthData['akhir_dex'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                            <td class="text-center">Liter</td>
                            <td class="text-center"></td>
                        </tr>
                    </tbody>
                @endforeach

                <tbody style="page-break-inside: avoid;">
                    <!-- Rekapitulasi -->
                    <tr>
                        <td colspan="2" class="text-right font-bold" style="vertical-align: top; border-bottom: none;">Rekapitulasi :</td>
                        <td class="text-left">Persediaan awal</td>
                        <td class="text-center">{{ number_format($data['rekap']['awal_pertamax'], 0, ',', '.') ?: '-' }}</td>
                        <td class="text-center">{{ number_format($data['rekap']['awal_dex'], 0, ',', '.') ?: '-' }}</td>
                        <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                        <td class="text-center">Liter</td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-top: none; border-bottom: none;"></td>
                        <td class="text-left">Penerimaan</td>
                        <td class="text-center">{{ number_format($data['rekap']['terima_pertamax'], 0, ',', '.') ?: '-' }}</td>
                        <td class="text-center">{{ number_format($data['rekap']['terima_dex'], 0, ',', '.') ?: '-' }}</td>
                        <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                        <td class="text-center">Liter</td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-top: none; border-bottom: none;"></td>
                        <td class="text-left font-bold">Jumlah</td>
                        <td class="text-center font-bold">{{ number_format($data['rekap']['jumlah_pertamax'], 0, ',', '.') ?: '-' }}</td>
                        <td class="text-center font-bold">{{ number_format($data['rekap']['jumlah_dex'], 0, ',', '.') ?: '-' }}</td>
                        <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                        <td class="text-center">Liter</td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-top: none; border-bottom: none;"></td>
                        <td class="text-left">Pengeluaran</td>
                        <td class="text-center">{{ number_format($data['rekap']['keluar_pertamax'], 0, ',', '.') ?: '-' }}</td>
                        <td class="text-center">{{ number_format($data['rekap']['keluar_dex'], 0, ',', '.') ?: '-' }}</td>
                        <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                        <td class="text-center">Liter</td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-top: none;"></td>
                        <td class="text-left font-bold">Persediaan akhir</td>
                        <td class="text-center font-bold">{{ number_format($data['rekap']['akhir_pertamax'], 0, ',', '.') ?: '-' }}</td>
                        <td class="text-center font-bold">{{ number_format($data['rekap']['akhir_dex'], 0, ',', '.') ?: '-' }}</td>
                        <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                        <td class="text-center">Liter</td>
                        <td class="text-center"></td>
                    </tr>
                </tbody>
        </table>

        @php
            $signatureMonth = $tw * 3;
            $signatureMonthStr = str_pad($signatureMonth, 2, '0', STR_PAD_LEFT);
        @endphp
        @include('components.pdf-signature-slog', ['bulan' => $signatureMonthStr, 'tahun' => $tahun])
    @else
        @php $loopIndex = 0; $totalWeeks = count($data); @endphp
        @foreach($data as $weekName => $weekData)
            @include('components.pdf-header')
            
            <div style="margin-bottom: 5px;">
                <span style="font-weight: bold; font-size: 12px; border-bottom: 1px solid #000;">LAPORAN BBM HARIAN ({{ str_replace('MINGGU ', 'MINGGU KE ', $weekName) }}) :</span>
                <br>
                <span style="font-size: 11px;">BULAN : {{ Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F') }} {{ $tahun }}</span>
            </div>

            <table>
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 3%; vertical-align: middle;">No</th>
                        <th rowspan="2" style="width: 10%; vertical-align: middle;">Tanggal</th>
                        <th rowspan="2" style="width: 15%; vertical-align: middle;">Uraian</th>
                        <th colspan="6" class="text-center">Jenis dan Kuantum BMP</th>
                        <th rowspan="2" style="width: 8%; vertical-align: middle;">Satuan</th>
                        <th rowspan="2" style="width: 15%; vertical-align: middle;">Keterangan</th>
                    </tr>
                    <tr>
                        <th style="width: 8%;">Pertamax</th>
                        <th style="width: 8%;">Pertamina Dex</th>
                        <th style="width: 8%;">Mesran</th>
                        <th style="width: 8%;">Meditran</th>
                        <th style="width: 8%;">Mesrania 2T</th>
                        <th style="width: 8%;">Rored</th>
                    </tr>
                    <tr>
                        @for($col = 1; $col <= 11; $col++)
                            <th style="background-color: #f2f2f2;">{{ $col }}</th>
                        @endfor
                    </tr>
                </thead>
                @php $i = 1; @endphp
                @foreach($weekData['days'] as $day)
                    <tbody style="page-break-inside: avoid;">
                        <!-- Persediaan Awal -->
                        <tr>
                            <td class="text-center" style="vertical-align: top; border-bottom: none;">{{ $i++ }}.</td>
                            <td class="text-left font-bold" style="vertical-align: top; border-bottom: none;">{{ $day['nama_hari'] }}</td>
                            <td class="text-left">Persediaan awal</td>
                            <td class="text-center">{{ number_format($day['awal_pertamax'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">{{ number_format($day['awal_dex'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                            <td class="text-center">Liter</td>
                            <td class="text-center"></td>
                        </tr>
                        <!-- Penerimaan -->
                        <tr>
                            <td style="border-top: none; border-bottom: none;"></td>
                            <td style="border-top: none; border-bottom: none;"></td>
                            <td class="text-left">Penerimaan</td>
                            <td class="text-center">{{ number_format($day['terima_pertamax'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">{{ number_format($day['terima_dex'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                            <td class="text-center">Liter</td>
                            <td class="text-center"></td>
                        </tr>
                        <!-- Jumlah -->
                        <tr>
                            <td style="border-top: none; border-bottom: none;"></td>
                            <td style="border-top: none; border-bottom: none;"></td>
                            <td class="text-left font-bold">Jumlah</td>
                            <td class="text-center font-bold">{{ number_format($day['jumlah_pertamax'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center font-bold">{{ number_format($day['jumlah_dex'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                            <td class="text-center">Liter</td>
                            <td class="text-center"></td>
                        </tr>
                        <!-- Pengeluaran -->
                        <tr>
                            <td style="border-top: none; border-bottom: none;"></td>
                            <td style="border-top: none; border-bottom: none;"></td>
                            <td class="text-left">Pengeluaran</td>
                            <td class="text-center">{{ number_format($day['keluar_pertamax'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">{{ number_format($day['keluar_dex'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                            <td class="text-center">Liter</td>
                            <td class="text-center"></td>
                        </tr>
                        <!-- Persediaan Akhir -->
                        <tr>
                            <td style="border-top: none;"></td>
                            <td style="border-top: none;"></td>
                            <td class="text-left font-bold">Persediaan akhir</td>
                            <td class="text-center font-bold">{{ number_format($day['akhir_pertamax'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center font-bold">{{ number_format($day['akhir_dex'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                            <td class="text-center">Liter</td>
                            <td class="text-center"></td>
                        </tr>
                    </tbody>
                @endforeach
                    
                    <tbody style="page-break-inside: avoid;">
                        <!-- Rekapitulasi -->
                        <tr>
                            <td rowspan="5" colspan="2" class="text-right font-bold" style="vertical-align: top;">Rekapitulasi :</td>
                            <td class="text-left">Persediaan awal</td>
                            <td class="text-center">{{ number_format($weekData['rekap']['awal_pertamax'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">{{ number_format($weekData['rekap']['awal_dex'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                            <td class="text-center">Liter</td>
                            <td class="text-center"></td>
                        </tr>
                        <tr>
                            <td class="text-left">Penerimaan</td>
                            <td class="text-center">{{ number_format($weekData['rekap']['terima_pertamax'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">{{ number_format($weekData['rekap']['terima_dex'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                            <td class="text-center">Liter</td>
                            <td class="text-center"></td>
                        </tr>
                        <tr>
                            <td class="text-left font-bold">Jumlah</td>
                            <td class="text-center font-bold">{{ number_format($weekData['rekap']['jumlah_pertamax'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center font-bold">{{ number_format($weekData['rekap']['jumlah_dex'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                            <td class="text-center">Liter</td>
                            <td class="text-center"></td>
                        </tr>
                        <tr>
                            <td class="text-left">Pengeluaran</td>
                            <td class="text-center">{{ number_format($weekData['rekap']['keluar_pertamax'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">{{ number_format($weekData['rekap']['keluar_dex'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                            <td class="text-center">Liter</td>
                            <td class="text-center"></td>
                        </tr>
                        <tr>
                            <td class="text-left font-bold">Persediaan akhir</td>
                            <td class="text-center font-bold">{{ number_format($weekData['rekap']['akhir_pertamax'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center font-bold">{{ number_format($weekData['rekap']['akhir_dex'], 0, ',', '.') ?: '-' }}</td>
                            <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td>
                            <td class="text-center">Liter</td>
                            <td class="text-center"></td>
                        </tr>
                    </tbody>
            </table>

            @include('components.pdf-signature-slog', ['bulan' => $bulan, 'tahun' => $tahun])

            @php $loopIndex++; @endphp
            @if($loopIndex < $totalWeeks)
                <div class="page-break"></div>
            @endif
        @endforeach
    @endif
</div>
</body>

</html>
