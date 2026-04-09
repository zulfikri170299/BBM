<table>
    <tr>
        <td colspan="3" style="text-align: center; font-weight: bold;">KEPOLISIAN NEGARA REPUBLIK INDONESIA</td>
        <td colspan="4"></td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: center; font-weight: bold;">DAERAH NUSA TENGGARA BARAT</td>
        <td colspan="4"></td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: center; font-weight: bold; border-bottom: 1px solid #000000;">BIRO LOGISTIK</td>
        <td colspan="4"></td>
    </tr>
    <tr><td colspan="7"></td></tr>
    <tr><td colspan="7"></td></tr>
    <tr>
        <td colspan="7" align="center" style="font-weight: bold;">
            DAFTAR NOMINATIF BUKTI PENERIMAAN BBM {{ $startDate && $endDate ? '' : 'TW ' . $tw }}
        </td>
    </tr>
    <tr>
        <td colspan="7" align="center" style="font-weight: bold;">
            PERIODE {{ strtoupper($periodeText) }}
        </td>
    </tr>
    <tr><td colspan="7"></td></tr>
    
    <!-- Table Header -->
    <tr style="font-weight: bold; text-align: center;">
        <td style="border: 1px solid #000000; vertical-align: middle;">NO</td>
        <td style="border: 1px solid #000000; vertical-align: middle;">URAIAN</td>
        <td style="border: 1px solid #000000; vertical-align: middle;">PERTAMAX</td>
        <td style="border: 1px solid #000000; vertical-align: middle;">PERTAMINADEX</td>
        <td style="border: 1px solid #000000; vertical-align: middle;">SATUAN</td>
        <td colspan="2" style="border: 1px solid #000000; vertical-align: middle;">TANDA TANGAN</td>
    </tr>
    <!-- Column Numbers -->
    <tr style="text-align: center;">
        <td style="border: 1px solid #000000;">1</td>
        <td style="border: 1px solid #000000;">2</td>
        <td style="border: 1px solid #000000;">3</td>
        <td style="border: 1px solid #000000;">4</td>
        <td style="border: 1px solid #000000;">5</td>
        <td colspan="2" style="border: 1px solid #000000;">6</td>
    </tr>
    
    <!-- Data -->
    @php
        $totalP = 0;
        $totalD = 0;
        $idx = 0;
    @endphp
    @foreach($logs as $log)
        @php
            $totalP += $log->total_pertamax;
            $totalD += $log->total_dex;
            $idx++;
            $dots = str_repeat('.', 15);
        @endphp
        <tr style="height: 45px;">
            <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">{{ $idx }}</td>
            <td style="border: 1px solid #000000; vertical-align: middle;">{{ ucwords(strtolower($log->satker->nama_satker)) }}</td>
            <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">{{ $log->total_pertamax }}</td>
            <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">{{ $log->total_dex }}</td>
            <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">Liter</td>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; vertical-align: middle;">
                {{ $idx % 2 != 0 ? $idx . $dots : '' }}
            </td>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: none; vertical-align: middle;">
                {{ $idx % 2 == 0 ? $idx . $dots : '' }}
            </td>
        </tr>
    @endforeach

    <!-- Footer Total -->
    <tr>
        <td colspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center;">JUMLAH</td>
        <td style="border: 1px solid #000000; font-weight: bold; text-align: center;">{{ $totalP }}</td>
        <td style="border: 1px solid #000000; font-weight: bold; text-align: center;">{{ $totalD }}</td>
        <td style="border: 1px solid #000000;"></td>
        <td colspan="2" style="border: 1px solid #000000;"></td>
    </tr>

    <!-- Spacer rows -->
    <tr><td colspan="7"></td></tr>
    <tr><td colspan="7"></td></tr>
    <tr><td colspan="7"></td></tr>

    <!-- Signatures placeholder -->
    @php
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        $nama = $settings['nominatif_nama'] ?? '';
        $pangkat = $settings['nominatif_pangkat'] ?? '';
        $nrp = $settings['nominatif_nrp'] ?? '';
        $jabatan = $settings['nominatif_jabatan'] ?? '';
    @endphp
    <tr><td colspan="7"></td></tr>

    <tr>
        <td colspan="5"></td>
        <td colspan="2" style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dikeluarkan di : Mataram</td>
    </tr>
    <tr>
        <td colspan="5"></td>
        <td colspan="2" style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pada tanggal &nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $endDate ? \Carbon\Carbon::parse($endDate)->translatedFormat('F Y') : ($namaBulan . ' ' . $tahun) }}</td>
    </tr>
    <tr>
        <td colspan="5"></td>
        <td colspan="2" style="text-align: center; vertical-align: top; border-top: 1px solid #000000;">{{ $jabatan }}</td>
    </tr>
    <tr>
        <td colspan="5"></td>
        <td colspan="2" style="height: 60px;"></td>
    </tr>
    <tr>
        <td colspan="5"></td>
        <td colspan="2" style="text-align: center; border-bottom: 1px solid #000000;">{{ strtoupper($nama) }}</td>
    </tr>
    <tr>
        <td colspan="5"></td>
        <td colspan="2" style="text-align: center;">{{ strtoupper($pangkat) }} NRP {{ $nrp }}</td>
    </tr>
</table>
