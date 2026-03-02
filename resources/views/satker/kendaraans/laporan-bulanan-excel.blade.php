<table>
    <thead>
        <tr>
            <th colspan="{{ 8 + $daysInMonth + 2 }}" style="text-align: center; font-weight: bold; font-size: 14pt;">
                LAPORAN BBM BULAN {{ strtoupper($namaBulan) }} TAHUN {{ $tahun }}
            </th>
        </tr>
        <tr>
            <th colspan="{{ 8 + $daysInMonth + 2 }}" style="text-align: center; font-weight: bold; font-size: 12pt;">
                {{ strtoupper($satkerName ?? $satker->nama_satker) }}
            </th>
        </tr>
        <tr></tr>
        <tr>
            <th rowspan="2"
                style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle;">NO
            </th>
            <th rowspan="2"
                style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle;">JENIS
                KENDARAAN</th>
            <th rowspan="2"
                style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle;">NOPOL
            </th>
            <th rowspan="2"
                style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle;">JENIS
                BBM</th>
            <th rowspan="2"
                style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle;">SISA
                BBM<br>BULAN {{ strtoupper($namaBulanSebelumnya) }}</th>
            <th rowspan="2"
                style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle;">TOP UP
                BBM<br>BULAN {{ strtoupper($namaBulan) }}</th>
            <th rowspan="2"
                style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle;">
                TOTAL<br>BBM</th>
            <th rowspan="2"
                style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle;">
                TRANSFER</th>
            <th colspan="{{ $daysInMonth }}"
                style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle;">
                LAPORAN PEMAKAIAN BULAN {{ strtoupper($namaBulan) }}</th>
            <th rowspan="2"
                style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle;">
                TOTAL<br>PAKAI</th>
            <th rowspan="2"
                style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle;">
                SISA<br>BBM</th>
        </tr>
        <tr>
            @for($d = 1; $d <= $daysInMonth; $d++)
                @php
                    $dateStr = str_pad($d, 2, '0', STR_PAD_LEFT) . '/' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '/' . $tahun;
                @endphp
                <th
                    style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; rotation: 90;">
                    {{ $dateStr }}</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $index => $row)
            <tr>
                <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000; text-align: left;">{{ strtoupper($row['jenis_kendaraan']) }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ strtoupper($row['no_polisi']) }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ strtoupper($row['jenis_bbm']) }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $row['sisa_bulan_lalu'] }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $row['topup_bulan_ini'] }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $row['total_bbm'] }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $row['transfer_bulan_ini'] }}</td>
                @for($d = 1; $d <= $daysInMonth; $d++)
                    <td style="border: 1px solid #000000; text-align: center;">{{ $row['daily_usage'][$d] ?? '' }}</td>
                @endfor
                <td style="border: 1px solid #000000; text-align: center;">{{ $row['total_pemakaian'] }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $row['sisa_bbm'] }}</td>
            </tr>
        @endforeach

        @foreach($summaryByBbm as $jenisBbm => $summary)
            <tr>
                <td colspan="4" style="border: 1px solid #000000; font-weight: bold; text-align: left;">TOTAL
                    {{ strtoupper($jenisBbm) }}</td>
                <td style="border: 1px solid #000000; font-weight: bold; text-align: center;">
                    {{ $summary['sisa_bulan_lalu'] }}</td>
                <td style="border: 1px solid #000000; font-weight: bold; text-align: center;">
                    {{ $summary['topup_bulan_ini'] }}</td>
                <td style="border: 1px solid #000000; font-weight: bold; text-align: center;">{{ $summary['total_bbm'] }}
                </td>
                <td style="border: 1px solid #000000; font-weight: bold; text-align: center;">
                    {{ $summary['transfer_bulan_ini'] }}</td>
                @for($d = 1; $d <= $daysInMonth; $d++)
                    <td style="border: 1px solid #000000; background-color: #cccccc;"></td>
                @endfor
                <td style="border: 1px solid #000000; font-weight: bold; text-align: center;">
                    {{ $summary['total_pemakaian'] }}</td>
                <td style="border: 1px solid #000000; font-weight: bold; text-align: center;">{{ $summary['sisa_bbm'] }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>