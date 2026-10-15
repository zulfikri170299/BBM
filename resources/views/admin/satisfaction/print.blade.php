<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Indeks Kepuasan Layanan - {{ $periode }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 15mm 15mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            line-height: 1.35;
            color: #111;
            margin: 0;
            padding: 0;
        }

        .header-title {
            text-align: center;
            margin-top: 15px;
            margin-bottom: 20px;
        }

        .header-title h1 {
            font-size: 13pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-title h2 {
            font-size: 11pt;
            font-weight: bold;
            margin: 4px 0 0 0;
            text-transform: uppercase;
            color: #333;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 15px;
            font-size: 9.5pt;
        }

        .meta-table td {
            padding: 2px 4px;
            border: none;
            vertical-align: top;
        }

        /* Summary / KPI Box */
        .summary-box {
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #333;
            border-collapse: collapse;
        }

        .summary-box th {
            background-color: #f2f2f2;
            border: 1px solid #333;
            padding: 6px 8px;
            font-size: 9pt;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }

        .summary-box td {
            border: 1px solid #333;
            padding: 6px 8px;
            font-size: 9.5pt;
            text-align: center;
            vertical-align: middle;
        }

        /* Main Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 20px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #333;
            padding: 5px 6px;
            vertical-align: top;
            font-size: 9pt;
        }

        .data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            font-size: 8.5pt;
            letter-spacing: 0.3px;
        }

        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        .badge-rating {
            display: inline-block;
            padding: 2px 6px;
            font-size: 8pt;
            font-weight: bold;
            border-radius: 3px;
            text-align: center;
        }

        .badge-sangat-puas {
            background-color: #e6f9ed;
            color: #0e6230;
            border: 1px solid #a3e2bb;
        }

        .badge-puas {
            background-color: #fef8e7;
            color: #8f5b00;
            border: 1px solid #f8dfa1;
        }

        .badge-tidak-puas {
            background-color: #fde8e8;
            color: #9b1c1c;
            border: 1px solid #f8b4b4;
        }

        .catatan-box {
            font-style: italic;
            color: #333;
        }

        .empty-row {
            padding: 25px !important;
            text-align: center;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    @include('components.pdf-header')

    <div class="header-title">
        <h1>Laporan Evaluasi Indeks Kepuasan Layanan BBM</h1>
        <h2>Periode: {{ $periode }}</h2>
    </div>

    @php
        $totalResp = $stats['total'] > 0 ? $stats['total'] : 1;
        $pctSangatPuas = $stats['total'] > 0 ? round(($stats['sangat_puas'] / $totalResp) * 100, 1) : 0;
        $pctPuas = $stats['total'] > 0 ? round(($stats['puas'] / $totalResp) * 100, 1) : 0;
        $pctTidakPuas = $stats['total'] > 0 ? round(($stats['tidak_puas'] / $totalResp) * 100, 1) : 0;
    @endphp

    <!-- Tabel Rekapitulasi Indeks Kepuasan -->
    <table class="summary-box">
        <thead>
            <tr>
                <th style="width: 25%;">Total Responden</th>
                <th style="width: 25%;">Sangat Puas</th>
                <th style="width: 25%;">Puas</th>
                <th style="width: 25%;">Tidak Puas</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div style="font-size: 13pt; font-weight: bold;">{{ number_format($stats['total'], 0, ',', '.') }}</div>
                    <div style="font-size: 8pt; color: #555;">Responden</div>
                </td>
                <td>
                    <div style="font-size: 13pt; font-weight: bold; color: #0e6230;">{{ $stats['sangat_puas'] }}</div>
                    <div style="font-size: 8pt; font-weight: bold; color: #0e6230;">{{ $pctSangatPuas }}%</div>
                </td>
                <td>
                    <div style="font-size: 13pt; font-weight: bold; color: #8f5b00;">{{ $stats['puas'] }}</div>
                    <div style="font-size: 8pt; font-weight: bold; color: #8f5b00;">{{ $pctPuas }}%</div>
                </td>
                <td>
                    <div style="font-size: 13pt; font-weight: bold; color: #9b1c1c;">{{ $stats['tidak_puas'] }}</div>
                    <div style="font-size: 8pt; font-weight: bold; color: #9b1c1c;">{{ $pctTidakPuas }}%</div>
                </td>
            </tr>
        </tbody>
    </table>

    <div style="margin-bottom: 6px; font-weight: bold; font-size: 9.5pt; text-transform: uppercase;">
        Rincian Responden Penilaian Kepuasan:
    </div>

    <!-- Tabel Detail Responden -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 17%;">Waktu</th>
                <th style="width: 23%;">Nama Responden</th>
                <th style="width: 20%;">Satuan Kerja (Satker)</th>
                <th style="width: 15%;">Tingkat Kepuasan</th>
                <th style="width: 20%;">Catatan / Saran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ratings as $index => $rating)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">
                        {{ $rating->created_at->format('d/m/Y') }}<br>
                        <span style="font-size: 8pt; color: #555;">{{ $rating->created_at->format('H:i') }} WITA</span>
                    </td>
                    <td>
                        <div class="font-bold">{{ $rating->user->name ?? 'User Terhapus' }}</div>
                        <div style="font-size: 8pt; color: #555;">{{ $rating->user->role_label ?? '-' }}</div>
                    </td>
                    <td>
                        {{ $rating->user->satker->nama_satker ?? '-' }}
                    </td>
                    <td class="text-center">
                        @if($rating->rating == '3')
                            <span class="badge-rating badge-sangat-puas">SANGAT PUAS</span>
                        @elseif($rating->rating == '2')
                            <span class="badge-rating badge-puas">PUAS</span>
                        @else
                            <span class="badge-rating badge-tidak-puas">TIDAK PUAS</span>
                        @endif
                    </td>
                    <td class="catatan-box">
                        @if($rating->note)
                            "{{ $rating->note }}"
                        @else
                            <span style="color: #999; font-style: normal;">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty-row">
                        Tidak ada data penilaian kepuasan layanan pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @include('components.pdf-signature')
</body>
</html>
