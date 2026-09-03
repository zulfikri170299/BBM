<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rendis BBM {{ $rendisBbm->triwulan }} {{ $rendisBbm->tahun }}</title>
</head>
<body>
    @php
        $namaBulan = $rendisBbm->nama_bulan;
        $susutPtx = $rendisBbm->pembelian_pertamax * ($rendisBbm->susut_persen / 100);
        $distribusiPtx = $rendisBbm->pembelian_pertamax - $susutPtx;
        
        $susutDex = $rendisBbm->pembelian_pertamina_dex * ($rendisBbm->susut_persen / 100);
        $distribusiDex = $rendisBbm->pembelian_pertamina_dex - $susutDex;
        
        $satkerId = auth()->check() ? auth()->user()->satker_id : null;
        $penandaTangan = \App\Models\PenandaTangan::getForPdf($satkerId);
    @endphp

    <table>
        <tr>
            <td colspan="13" style="text-align: center; font-weight: bold; font-size: 14px;">
                RENCANA PENDISTRIBUSIAN BBM RUTIN {{ $rendisBbm->triwulan }} TAHUN {{ $rendisBbm->tahun }}
            </td>
        </tr>
        <tr>
            <td colspan="13"></td>
        </tr>

        <!-- TABEL PEMBELIAN -->
        <tr>
            <td style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f0f0f0;">NO</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f0f0f0;">JENIS BBM</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f0f0f0;">PEMBELIAN</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f0f0f0;">SUSUT {{ $rendisBbm->susut_persen }}%</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f0f0f0;">DISTRIBUSI</td>
            <td colspan="8"></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; text-align: center;">1</td>
            <td style="border: 1px solid #000;">PERTAMAX</td>
            <td style="border: 1px solid #000; text-align: right;">{{ round($rendisBbm->pembelian_pertamax) }}</td>
            <td style="border: 1px solid #000; text-align: right;">{{ round($susutPtx) }}</td>
            <td style="border: 1px solid #000; text-align: right;">{{ round($distribusiPtx) }}</td>
            <td colspan="8"></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; text-align: center;">2</td>
            <td style="border: 1px solid #000;">PERTAMINA DEX</td>
            <td style="border: 1px solid #000; text-align: right;">{{ round($rendisBbm->pembelian_pertamina_dex) }}</td>
            <td style="border: 1px solid #000; text-align: right;">{{ round($susutDex) }}</td>
            <td style="border: 1px solid #000; text-align: right;">{{ round($distribusiDex) }}</td>
            <td colspan="8"></td>
        </tr>
        <tr>
            <td colspan="13"></td>
        </tr>

        <!-- TABEL KENDARAAN -->
        <tr>
            <td rowspan="2" style="font-weight: bold; border: 1px solid #000; text-align: center; vertical-align: middle; background-color: #f0f0f0; width: 50px;">NO</td>
            <td rowspan="2" style="font-weight: bold; border: 1px solid #000; text-align: center; vertical-align: middle; background-color: #f0f0f0; width: 150px;">URAIAN</td>
            <td rowspan="2" style="font-weight: bold; border: 1px solid #000; text-align: center; vertical-align: middle; background-color: #f0f0f0; width: 150px;">JENIS RANDIS</td>
            <td rowspan="2" style="font-weight: bold; border: 1px solid #000; text-align: center; vertical-align: middle; background-color: #f0f0f0; width: 100px;">NOPOL</td>
            <td colspan="3" style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f0f0f0;">{{ strtoupper($namaBulan[0]) }}</td>
            <td colspan="3" style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f0f0f0;">{{ strtoupper($namaBulan[1]) }}</td>
            <td colspan="3" style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f0f0f0;">{{ strtoupper($namaBulan[2]) }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f0f0f0; width: 120px;">Indeks (Liter x Hari)</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f0f0f0; width: 80px;">Pertamax</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f0f0f0; width: 100px;">Pertamina Dex</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f0f0f0; width: 120px;">Indeks (Liter x Hari)</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f0f0f0; width: 80px;">Pertamax</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f0f0f0; width: 100px;">Pertamina Dex</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f0f0f0; width: 120px;">Indeks (Liter x Hari)</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f0f0f0; width: 80px;">Pertamax</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f0f0f0; width: 100px;">Pertamina Dex</td>
        </tr>

        @php
            $romawi = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII','XVIII','XIX','XX','XXI','XXII','XXIII','XXIV'];
            $satkerIdx = 0;
            $grandTotalPertamax = [0, 0, 0];
            $grandTotalDex = [0, 0, 0];
        @endphp

        @foreach($kendaraansBySatker as $satkerId => $items)
            @php
                $satkerName = $satkers[$satkerId]->nama_satker ?? 'LAINNYA';
                $satkerLabel = $romawi[$satkerIdx] ?? ($satkerIdx + 1);
                $satkerIdx++;
                $subPertamax = [0, 0, 0];
                $subDex = [0, 0, 0];
            @endphp

            <tr>
                <td style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #ffffcc;">{{ $satkerLabel }}</td>
                <td colspan="12" style="font-weight: bold; border: 1px solid #000; background-color: #ffffcc;">{{ strtoupper($satkerName) }}</td>
            </tr>

            @foreach($items as $idx => $rk)
                @php
                    $k = $rk->kendaraan;
                    $isPertamax = $rk->jenis_bbm === 'pertamax';
                    if ($isPertamax) {
                        $subPertamax[0] += $rk->bulan1_total;
                        $subPertamax[1] += $rk->bulan2_total;
                        $subPertamax[2] += $rk->bulan3_total;
                    } else {
                        $subDex[0] += $rk->bulan1_total;
                        $subDex[1] += $rk->bulan2_total;
                        $subDex[2] += $rk->bulan3_total;
                    }
                @endphp
                <tr>
                    <td style="border: 1px solid #000; text-align: center;">{{ $idx + 1 }}</td>
                    <td style="border: 1px solid #000;">{{ $rk->uraian ?? $k->kategori_kendaraan ?? 'Operasional' }}</td>
                    <td style="border: 1px solid #000;">{{ $k->jenis_kendaraan ?? '-' }}</td>
                    <td style="border: 1px solid #000; text-align: center;">{{ $k->no_polisi ?? '-' }}</td>
                    <td style="border: 1px solid #000; text-align: center;">{{ $rk->liter_per_hari }} x {{ $rk->bulan1_total > 0 ? round(round($rk->bulan1_total / max($rk->liter_per_hari, 1))) : 0 }}</td>
                    <td style="border: 1px solid #000; text-align: right;">{{ $isPertamax ? round($rk->bulan1_total) : '' }}</td>
                    <td style="border: 1px solid #000; text-align: right;">{{ !$isPertamax ? round($rk->bulan1_total) : '' }}</td>
                    <td style="border: 1px solid #000; text-align: center;">{{ $rk->liter_per_hari }} x {{ $rk->bulan2_total > 0 ? round(round($rk->bulan2_total / max($rk->liter_per_hari, 1))) : 0 }}</td>
                    <td style="border: 1px solid #000; text-align: right;">{{ $isPertamax ? round($rk->bulan2_total) : '' }}</td>
                    <td style="border: 1px solid #000; text-align: right;">{{ !$isPertamax ? round($rk->bulan2_total) : '' }}</td>
                    <td style="border: 1px solid #000; text-align: center;">{{ $rk->liter_per_hari }} x {{ $rk->bulan3_total > 0 ? round(round($rk->bulan3_total / max($rk->liter_per_hari, 1))) : 0 }}</td>
                    <td style="border: 1px solid #000; text-align: right;">{{ $isPertamax ? round($rk->bulan3_total) : '' }}</td>
                    <td style="border: 1px solid #000; text-align: right;">{{ !$isPertamax ? round($rk->bulan3_total) : '' }}</td>
                </tr>
            @endforeach

            @php
                $grandTotalPertamax[0] += $subPertamax[0];
                $grandTotalPertamax[1] += $subPertamax[1];
                $grandTotalPertamax[2] += $subPertamax[2];
                $grandTotalDex[0] += $subDex[0];
                $grandTotalDex[1] += $subDex[1];
                $grandTotalDex[2] += $subDex[2];
            @endphp

            <tr>
                <td colspan="4" style="border: 1px solid #000; background-color: #f5f5f5;"></td>
                <td style="border: 1px solid #000; text-align: center; font-weight: bold; background-color: #f5f5f5;">JUMLAH</td>
                <td style="border: 1px solid #000; text-align: right; font-weight: bold; background-color: #f5f5f5;">{{ round($subPertamax[0]) }}</td>
                <td style="border: 1px solid #000; text-align: right; font-weight: bold; background-color: #f5f5f5;">{{ round($subDex[0]) }}</td>
                <td style="border: 1px solid #000; text-align: center; font-weight: bold; background-color: #f5f5f5;">JUMLAH</td>
                <td style="border: 1px solid #000; text-align: right; font-weight: bold; background-color: #f5f5f5;">{{ round($subPertamax[1]) }}</td>
                <td style="border: 1px solid #000; text-align: right; font-weight: bold; background-color: #f5f5f5;">{{ round($subDex[1]) }}</td>
                <td style="border: 1px solid #000; text-align: center; font-weight: bold; background-color: #f5f5f5;">JUMLAH</td>
                <td style="border: 1px solid #000; text-align: right; font-weight: bold; background-color: #f5f5f5;">{{ round($subPertamax[2]) }}</td>
                <td style="border: 1px solid #000; text-align: right; font-weight: bold; background-color: #f5f5f5;">{{ round($subDex[2]) }}</td>
            </tr>
        @endforeach

        <!-- GRAND TOTAL -->
        <tr>
            <td colspan="4" style="border: 1px solid #000; background-color: #ddd; font-weight: bold;"></td>
            <td style="border: 1px solid #000; text-align: center; background-color: #ddd; font-weight: bold;">TOTAL</td>
            <td style="border: 1px solid #000; text-align: right; background-color: #ddd; font-weight: bold;">{{ round($grandTotalPertamax[0]) }}</td>
            <td style="border: 1px solid #000; text-align: right; background-color: #ddd; font-weight: bold;">{{ round($grandTotalDex[0]) }}</td>
            <td style="border: 1px solid #000; text-align: center; background-color: #ddd; font-weight: bold;">TOTAL</td>
            <td style="border: 1px solid #000; text-align: right; background-color: #ddd; font-weight: bold;">{{ round($grandTotalPertamax[1]) }}</td>
            <td style="border: 1px solid #000; text-align: right; background-color: #ddd; font-weight: bold;">{{ round($grandTotalDex[1]) }}</td>
            <td style="border: 1px solid #000; text-align: center; background-color: #ddd; font-weight: bold;">TOTAL</td>
            <td style="border: 1px solid #000; text-align: right; background-color: #ddd; font-weight: bold;">{{ round($grandTotalPertamax[2]) }}</td>
            <td style="border: 1px solid #000; text-align: right; background-color: #ddd; font-weight: bold;">{{ round($grandTotalDex[2]) }}</td>
        </tr>

        <tr><td colspan="13"></td></tr>
        <tr><td colspan="13"></td></tr>

        @if($penandaTangan)
        <tr>
            <td colspan="9"></td>
            <td colspan="4" style="text-align: center;">Mataram, {{ \Carbon\Carbon::now()->setTimezone('Asia/Makassar')->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td colspan="9"></td>
            <td colspan="4" style="text-align: center;">{{ $penandaTangan->jabatan }}</td>
        </tr>
        @if($penandaTangan->jabatan2)
        <tr>
            <td colspan="9"></td>
            <td colspan="4" style="text-align: center;">{{ $penandaTangan->jabatan2 }}</td>
        </tr>
        @endif
        <tr>
            <td colspan="9"></td>
            <td colspan="4" style="text-align: center;"></td>
        </tr>
        <tr>
            <td colspan="9"></td>
            <td colspan="4" style="text-align: center;"></td>
        </tr>
        <tr>
            <td colspan="9"></td>
            <td colspan="4" style="text-align: center;"></td>
        </tr>
        <tr>
            <td colspan="9"></td>
            <td colspan="4" style="text-align: center; font-weight: bold; text-decoration: underline;">{{ $penandaTangan->nama }}</td>
        </tr>
        <tr>
            <td colspan="9"></td>
            <td colspan="4" style="text-align: center;">{{ $penandaTangan->pangkat }} NRP {{ $penandaTangan->nrp }}</td>
        </tr>
        @endif
    </table>
</body>
</html>
