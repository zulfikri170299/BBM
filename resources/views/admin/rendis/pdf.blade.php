<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rendis BBM {{ $rendisBbm->triwulan }} {{ $rendisBbm->tahun }}</title>
    <style>
        @page { margin-top: 15px; }
        body { font-family: sans-serif; font-size: 10px; margin: 10px; }
        .title { font-size: 16px; font-weight: bold; text-align: center; text-transform: uppercase; margin-top: 30px; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 4px 5px; }
        th { background-color: #f0f0f0; font-size: 9px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .satker-header { background-color: #f3f4f6; font-weight: bold; color: #0062ff; text-align: left; }
        .jumlah-row { background-color: #f9fafb; font-weight: bold; }
    </style>
</head>
<body>
    @include('components.pdf-header')

    <div class="title">
        RENCANA PENDISTRIBUSIAN BBM RUTIN {{ $rendisBbm->triwulan }} TAHUN {{ $rendisBbm->tahun }}
    </div>

    @php
        $namaBulan = $rendisBbm->nama_bulan;
        
        $susutPtx = $rendisBbm->pembelian_pertamax * ($rendisBbm->susut_persen / 100);
        $distribusiPtx = $rendisBbm->pembelian_pertamax - $susutPtx;
        
        $susutDex = $rendisBbm->pembelian_pertamina_dex * ($rendisBbm->susut_persen / 100);
        $distribusiDex = $rendisBbm->pembelian_pertamina_dex - $susutDex;
    @endphp

    {{-- TABEL PEMBELIAN --}}
    <table style="width: 45%; margin-bottom: 15px;">
        <thead>
            <tr>
                <th>NO</th>
                <th>JENIS BBM</th>
                <th>PEMBELIAN</th>
                <th>SUSUT {{ $rendisBbm->susut_persen }}%</th>
                <th>DISTRIBUSI</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td>PERTAMAX</td>
                <td class="text-right">{{ number_format($rendisBbm->pembelian_pertamax, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($susutPtx, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($distribusiPtx, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td>PERTAMINA DEX</td>
                <td class="text-right">{{ number_format($rendisBbm->pembelian_pertamina_dex, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($susutDex, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($distribusiDex, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    {{-- TABEL KENDARAAN --}}
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width:3%">NO</th>
                <th rowspan="2" style="width:8%">URAIAN</th>
                <th rowspan="2" style="width:14%">JENIS RANDIS</th>
                <th rowspan="2" style="width:9%">NOPOL</th>
                <th colspan="3">{{ strtoupper($namaBulan[0]) }}</th>
                <th colspan="3">{{ strtoupper($namaBulan[1]) }}</th>
                <th colspan="3">{{ strtoupper($namaBulan[2]) }}</th>
            </tr>
            <tr>
                <th>Indeks<br><span style="font-size:7px; font-weight:normal">(Liter x Hari)</span></th>
                <th style="color: #2563eb">Pertamax</th>
                <th style="color: #059669">Pertamina Dex</th>
                <th>Indeks<br><span style="font-size:7px; font-weight:normal">(Liter x Hari)</span></th>
                <th style="color: #2563eb">Pertamax</th>
                <th style="color: #059669">Pertamina Dex</th>
                <th>Indeks<br><span style="font-size:7px; font-weight:normal">(Liter x Hari)</span></th>
                <th style="color: #2563eb">Pertamax</th>
                <th style="color: #059669">Pertamina Dex</th>
            </tr>
        </thead>
        <tbody>
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

                <tr class="satker-header">
                    <td class="text-center font-bold">{{ $satkerLabel }}</td>
                    <td colspan="12" class="font-bold" style="text-transform:uppercase">{{ $satkerName }}</td>
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
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td>{{ $rk->uraian ?? $k->kategori_kendaraan ?? 'Operasional' }}</td>
                        <td>{{ $k->jenis_kendaraan ?? '-' }}</td>
                        <td class="text-center font-bold">{{ $k->no_polisi ?? '-' }}</td>
                        <td class="text-center" style="color: #000000;">{{ $rk->liter_per_hari }} x {{ $rk->bulan1_total > 0 ? round($rk->bulan1_total / max($rk->liter_per_hari, 1)) : 0 }}</td>
                        <td class="text-center font-bold" style="color: #2563eb;">{{ $isPertamax && $rk->bulan1_total > 0 ? number_format($rk->bulan1_total, 0, ',', '.') : '' }}</td>
                        <td class="text-center font-bold" style="color: #059669;">{{ !$isPertamax && $rk->bulan1_total > 0 ? number_format($rk->bulan1_total, 0, ',', '.') : '' }}</td>
                        <td class="text-center" style="color: #000000;">{{ $rk->liter_per_hari }} x {{ $rk->bulan2_total > 0 ? round($rk->bulan2_total / max($rk->liter_per_hari, 1)) : 0 }}</td>
                        <td class="text-center font-bold" style="color: #2563eb;">{{ $isPertamax && $rk->bulan2_total > 0 ? number_format($rk->bulan2_total, 0, ',', '.') : '' }}</td>
                        <td class="text-center font-bold" style="color: #059669;">{{ !$isPertamax && $rk->bulan2_total > 0 ? number_format($rk->bulan2_total, 0, ',', '.') : '' }}</td>
                        <td class="text-center" style="color: #000000;">{{ $rk->liter_per_hari }} x {{ $rk->bulan3_total > 0 ? round($rk->bulan3_total / max($rk->liter_per_hari, 1)) : 0 }}</td>
                        <td class="text-center font-bold" style="color: #2563eb;">{{ $isPertamax && $rk->bulan3_total > 0 ? number_format($rk->bulan3_total, 0, ',', '.') : '' }}</td>
                        <td class="text-center font-bold" style="color: #059669;">{{ !$isPertamax && $rk->bulan3_total > 0 ? number_format($rk->bulan3_total, 0, ',', '.') : '' }}</td>
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

                <tr class="jumlah-row">
                    <td colspan="4"></td>
                    <td class="text-center font-bold">JUMLAH</td>
                    <td class="text-center font-bold" style="color: #2563eb;">{{ $subPertamax[0] > 0 ? number_format($subPertamax[0], 0, ',', '.') : '0' }}</td>
                    <td class="text-center font-bold" style="color: #059669;">{{ $subDex[0] > 0 ? number_format($subDex[0], 0, ',', '.') : '0' }}</td>
                    <td class="text-center font-bold">JUMLAH</td>
                    <td class="text-center font-bold" style="color: #2563eb;">{{ $subPertamax[1] > 0 ? number_format($subPertamax[1], 0, ',', '.') : '0' }}</td>
                    <td class="text-center font-bold" style="color: #059669;">{{ $subDex[1] > 0 ? number_format($subDex[1], 0, ',', '.') : '0' }}</td>
                    <td class="text-center font-bold">JUMLAH</td>
                    <td class="text-center font-bold" style="color: #2563eb;">{{ $subPertamax[2] > 0 ? number_format($subPertamax[2], 0, ',', '.') : '0' }}</td>
                    <td class="text-center font-bold" style="color: #059669;">{{ $subDex[2] > 0 ? number_format($subDex[2], 0, ',', '.') : '0' }}</td>
                </tr>
            @endforeach

            {{-- GRAND TOTAL --}}
            <tr style="background-color:#e5e7eb; font-weight:bold; font-size:10px;">
                <td colspan="4"></td>
                <td class="text-center">TOTAL</td>
                <td class="text-center" style="color: #1d4ed8;">{{ number_format($grandTotalPertamax[0], 0, ',', '.') }}</td>
                <td class="text-center" style="color: #047857;">{{ number_format($grandTotalDex[0], 0, ',', '.') }}</td>
                <td class="text-center">TOTAL</td>
                <td class="text-center" style="color: #1d4ed8;">{{ number_format($grandTotalPertamax[1], 0, ',', '.') }}</td>
                <td class="text-center" style="color: #047857;">{{ number_format($grandTotalDex[1], 0, ',', '.') }}</td>
                <td class="text-center">TOTAL</td>
                <td class="text-center" style="color: #1d4ed8;">{{ number_format($grandTotalPertamax[2], 0, ',', '.') }}</td>
                <td class="text-center" style="color: #047857;">{{ number_format($grandTotalDex[2], 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    @include('components.pdf-signature')
</body>
</html>
