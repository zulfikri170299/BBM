<x-app-layout>
<div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Rendis BBM - {{ $rendisBbm->triwulan }} {{ $rendisBbm->tahun }}</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tampilan rekapitulasi data rencana pendistribusian BBM</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.rendis.print-pdf', $rendisBbm->id) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak PDF
            </a>
            <a href="{{ route('admin.rendis.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-lg font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 transition ease-in-out duration-150">
                Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800">
            <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-8 overflow-x-auto">
        <div class="min-w-[1200px]">
            <div class="text-center mb-6">
                <h1 class="text-lg font-bold uppercase text-gray-900 dark:text-white">Rencana Pendistribusian BBM Rutin {{ $rendisBbm->triwulan }} Tahun {{ $rendisBbm->tahun }}</h1>
            </div>

            @php
                $namaBulan = $rendisBbm->nama_bulan;
                $susutPtx = $rendisBbm->pembelian_pertamax * ($rendisBbm->susut_persen / 100);
                $distribusiPtx = $rendisBbm->pembelian_pertamax - $susutPtx;
                $susutDex = $rendisBbm->pembelian_pertamina_dex * ($rendisBbm->susut_persen / 100);
                $distribusiDex = $rendisBbm->pembelian_pertamina_dex - $susutDex;
            @endphp

            {{-- TABEL PEMBELIAN --}}
            <table class="w-1/2 mb-8 border-collapse border border-gray-400 dark:border-gray-600 text-sm">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="border border-gray-400 dark:border-gray-600 px-3 py-2">NO</th>
                        <th class="border border-gray-400 dark:border-gray-600 px-3 py-2">JENIS BBM</th>
                        <th class="border border-gray-400 dark:border-gray-600 px-3 py-2 text-right">PEMBELIAN</th>
                        <th class="border border-gray-400 dark:border-gray-600 px-3 py-2 text-right">SUSUT {{ $rendisBbm->susut_persen }}%</th>
                        <th class="border border-gray-400 dark:border-gray-600 px-3 py-2 text-right">DISTRIBUSI</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 dark:text-gray-200">
                    <tr>
                        <td class="border border-gray-400 dark:border-gray-600 px-3 py-2 text-center">1</td>
                        <td class="border border-gray-400 dark:border-gray-600 px-3 py-2 font-bold text-blue-600 dark:text-blue-400">PERTAMAX</td>
                        <td class="border border-gray-400 dark:border-gray-600 px-3 py-2 text-right">{{ number_format($rendisBbm->pembelian_pertamax, 0, ',', '.') }}</td>
                        <td class="border border-gray-400 dark:border-gray-600 px-3 py-2 text-right">{{ number_format($susutPtx, 0, ',', '.') }}</td>
                        <td class="border border-gray-400 dark:border-gray-600 px-3 py-2 text-right">{{ number_format($distribusiPtx, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-400 dark:border-gray-600 px-3 py-2 text-center">2</td>
                        <td class="border border-gray-400 dark:border-gray-600 px-3 py-2 font-bold text-emerald-600 dark:text-emerald-400">PERTAMINA DEX</td>
                        <td class="border border-gray-400 dark:border-gray-600 px-3 py-2 text-right">{{ number_format($rendisBbm->pembelian_pertamina_dex, 0, ',', '.') }}</td>
                        <td class="border border-gray-400 dark:border-gray-600 px-3 py-2 text-right">{{ number_format($susutDex, 0, ',', '.') }}</td>
                        <td class="border border-gray-400 dark:border-gray-600 px-3 py-2 text-right">{{ number_format($distribusiDex, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- TABEL KENDARAAN --}}
            <table class="w-full border-collapse border border-gray-400 dark:border-gray-600 text-xs">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th rowspan="2" class="border border-gray-400 dark:border-gray-600 px-2 py-1 w-10">NO</th>
                        <th rowspan="2" class="border border-gray-400 dark:border-gray-600 px-2 py-1">URAIAN</th>
                        <th rowspan="2" class="border border-gray-400 dark:border-gray-600 px-2 py-1">JENIS RANDIS</th>
                        <th rowspan="2" class="border border-gray-400 dark:border-gray-600 px-2 py-1">NOPOL</th>
                        <th colspan="3" class="border border-gray-400 dark:border-gray-600 px-2 py-1">{{ strtoupper($namaBulan[0]) }}</th>
                        <th colspan="3" class="border border-gray-400 dark:border-gray-600 px-2 py-1">{{ strtoupper($namaBulan[1]) }}</th>
                        <th colspan="3" class="border border-gray-400 dark:border-gray-600 px-2 py-1">{{ strtoupper($namaBulan[2]) }}</th>
                    </tr>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="border border-gray-400 dark:border-gray-600 px-2 py-1 whitespace-nowrap">Indeks<br><span class="text-[9px] font-normal text-gray-700 dark:text-gray-300">(Liter x Hari)</span></th>
                        <th class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-blue-600 dark:text-blue-400">Pertamax</th>
                        <th class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-emerald-600 dark:text-emerald-400">Pertamina Dex</th>
                        <th class="border border-gray-400 dark:border-gray-600 px-2 py-1 whitespace-nowrap">Indeks<br><span class="text-[9px] font-normal text-gray-700 dark:text-gray-300">(Liter x Hari)</span></th>
                        <th class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-blue-600 dark:text-blue-400">Pertamax</th>
                        <th class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-emerald-600 dark:text-emerald-400">Pertamina Dex</th>
                        <th class="border border-gray-400 dark:border-gray-600 px-2 py-1 whitespace-nowrap">Indeks<br><span class="text-[9px] font-normal text-gray-700 dark:text-gray-300">(Liter x Hari)</span></th>
                        <th class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-blue-600 dark:text-blue-400">Pertamax</th>
                        <th class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-emerald-600 dark:text-emerald-400">Pertamina Dex</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800">
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

                        <tr class="bg-yellow-50 dark:bg-yellow-900/20 font-bold">
                            <td class="border border-gray-400 dark:border-gray-600 px-2 py-2 text-center text-gray-900 dark:text-yellow-400">{{ $satkerLabel }}</td>
                            <td colspan="12" class="border border-gray-400 dark:border-gray-600 px-2 py-2 text-gray-900 dark:text-yellow-400 uppercase">{{ $satkerName }}</td>
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
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center">{{ $idx + 1 }}</td>
                                <td class="border border-gray-400 dark:border-gray-600 px-2 py-1">{{ $rk->uraian ?? $k->kategori_kendaraan ?? 'Operasional' }}</td>
                                <td class="border border-gray-400 dark:border-gray-600 px-2 py-1">{{ $k->jenis_kendaraan ?? '-' }}</td>
                                <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center font-semibold">{{ $k->no_polisi ?? '-' }}</td>
                                <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center text-gray-500">{{ $rk->liter_per_hari }} x {{ $rk->bulan1_total > 0 ? round($rk->bulan1_total / max($rk->liter_per_hari, 1)) : 0 }}</td>
                                <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center font-semibold text-blue-600 dark:text-blue-400">{{ $isPertamax && $rk->bulan1_total > 0 ? number_format($rk->bulan1_total, 0, ',', '.') : '' }}</td>
                                <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center font-semibold text-emerald-600 dark:text-emerald-400">{{ !$isPertamax && $rk->bulan1_total > 0 ? number_format($rk->bulan1_total, 0, ',', '.') : '' }}</td>
                                <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center text-gray-500">{{ $rk->liter_per_hari }} x {{ $rk->bulan2_total > 0 ? round($rk->bulan2_total / max($rk->liter_per_hari, 1)) : 0 }}</td>
                                <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center font-semibold text-blue-600 dark:text-blue-400">{{ $isPertamax && $rk->bulan2_total > 0 ? number_format($rk->bulan2_total, 0, ',', '.') : '' }}</td>
                                <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center font-semibold text-emerald-600 dark:text-emerald-400">{{ !$isPertamax && $rk->bulan2_total > 0 ? number_format($rk->bulan2_total, 0, ',', '.') : '' }}</td>
                                <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center text-gray-500">{{ $rk->liter_per_hari }} x {{ $rk->bulan3_total > 0 ? round($rk->bulan3_total / max($rk->liter_per_hari, 1)) : 0 }}</td>
                                <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center font-semibold text-blue-600 dark:text-blue-400">{{ $isPertamax && $rk->bulan3_total > 0 ? number_format($rk->bulan3_total, 0, ',', '.') : '' }}</td>
                                <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center font-semibold text-emerald-600 dark:text-emerald-400">{{ !$isPertamax && $rk->bulan3_total > 0 ? number_format($rk->bulan3_total, 0, ',', '.') : '' }}</td>
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

                        <tr class="bg-gray-50 dark:bg-gray-700/50 font-bold">
                            <td colspan="4" class="border border-gray-400 dark:border-gray-600 px-2 py-1"></td>
                            <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center">JUMLAH</td>
                            <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center text-blue-600 dark:text-blue-400">{{ $subPertamax[0] > 0 ? number_format($subPertamax[0], 0, ',', '.') : '0' }}</td>
                            <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center text-emerald-600 dark:text-emerald-400">{{ $subDex[0] > 0 ? number_format($subDex[0], 0, ',', '.') : '0' }}</td>
                            <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center">JUMLAH</td>
                            <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center text-blue-600 dark:text-blue-400">{{ $subPertamax[1] > 0 ? number_format($subPertamax[1], 0, ',', '.') : '0' }}</td>
                            <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center text-emerald-600 dark:text-emerald-400">{{ $subDex[1] > 0 ? number_format($subDex[1], 0, ',', '.') : '0' }}</td>
                            <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center">JUMLAH</td>
                            <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center text-blue-600 dark:text-blue-400">{{ $subPertamax[2] > 0 ? number_format($subPertamax[2], 0, ',', '.') : '0' }}</td>
                            <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center text-emerald-600 dark:text-emerald-400">{{ $subDex[2] > 0 ? number_format($subDex[2], 0, ',', '.') : '0' }}</td>
                        </tr>
                    @endforeach

                    {{-- GRAND TOTAL --}}
                    <tr class="bg-gray-200 dark:bg-gray-600 font-bold text-gray-900 dark:text-white">
                        <td colspan="4" class="border border-gray-400 dark:border-gray-600 px-2 py-2"></td>
                        <td class="border border-gray-400 dark:border-gray-600 px-2 py-2 text-center">TOTAL</td>
                        <td class="border border-gray-400 dark:border-gray-600 px-2 py-2 text-center text-blue-700 dark:text-blue-300">{{ number_format($grandTotalPertamax[0], 0, ',', '.') }}</td>
                        <td class="border border-gray-400 dark:border-gray-600 px-2 py-2 text-center text-emerald-700 dark:text-emerald-300">{{ number_format($grandTotalDex[0], 0, ',', '.') }}</td>
                        <td class="border border-gray-400 dark:border-gray-600 px-2 py-2 text-center">TOTAL</td>
                        <td class="border border-gray-400 dark:border-gray-600 px-2 py-2 text-center text-blue-700 dark:text-blue-300">{{ number_format($grandTotalPertamax[1], 0, ',', '.') }}</td>
                        <td class="border border-gray-400 dark:border-gray-600 px-2 py-2 text-center text-emerald-700 dark:text-emerald-300">{{ number_format($grandTotalDex[1], 0, ',', '.') }}</td>
                        <td class="border border-gray-400 dark:border-gray-600 px-2 py-2 text-center">TOTAL</td>
                        <td class="border border-gray-400 dark:border-gray-600 px-2 py-2 text-center text-blue-700 dark:text-blue-300">{{ number_format($grandTotalPertamax[2], 0, ',', '.') }}</td>
                        <td class="border border-gray-400 dark:border-gray-600 px-2 py-2 text-center text-emerald-700 dark:text-emerald-300">{{ number_format($grandTotalDex[2], 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-app-layout>
