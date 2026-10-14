<x-app-layout>
<div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ showPrintModal: false, printBaseUrl: '{{ route('admin.rendis.print-pdf', $rendisBbm->id) }}', printRendisTitle: '{{ $rendisBbm->triwulan }} {{ $rendisBbm->tahun }}' }">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Rendis BBM - {{ $rendisBbm->triwulan }} {{ $rendisBbm->tahun }}</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tampilan rekapitulasi data rencana pendistribusian BBM</p>
        </div>
        <div class="flex space-x-3">
            <button type="button" @click="showPrintModal = true" class="inline-flex items-center px-4 py-2 bg-rose-600 hover:bg-rose-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest active:bg-rose-800 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm cursor-pointer">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak PDF
            </button>
            <a href="{{ route('admin.rendis.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 bg-white text-slate-900 dark:bg-gray-700 border border-transparent rounded-lg font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 transition ease-in-out duration-150">
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
                    <tr class="bg-white text-slate-900 dark:text-gray-200 dark:bg-gray-700">
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
                    <tr class="bg-white text-slate-900 dark:text-gray-200 dark:bg-gray-700">
                        <th rowspan="2" class="border border-gray-400 dark:border-gray-600 px-2 py-1 w-10">NO</th>
                        <th rowspan="2" class="border border-gray-400 dark:border-gray-600 px-2 py-1">URAIAN</th>
                        <th rowspan="2" class="border border-gray-400 dark:border-gray-600 px-2 py-1">JENIS RANDIS</th>
                        <th rowspan="2" class="border border-gray-400 dark:border-gray-600 px-2 py-1">NOPOL</th>
                        <th colspan="3" class="border border-gray-400 dark:border-gray-600 px-2 py-1">{{ strtoupper($namaBulan[0]) }}</th>
                        <th colspan="3" class="border border-gray-400 dark:border-gray-600 px-2 py-1">{{ strtoupper($namaBulan[1]) }}</th>
                        <th colspan="3" class="border border-gray-400 dark:border-gray-600 px-2 py-1">{{ strtoupper($namaBulan[2]) }}</th>
                    </tr>
                    <tr class="bg-white text-slate-900 dark:text-gray-200 dark:bg-gray-700">
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

                    @foreach($satkers as $satker)
                        @php
                            $satkerId = $satker->id;
                            $items = $kendaraansBySatker->has($satkerId) ? $kendaraansBySatker->get($satkerId) : collect();
                            $satkerName = $satker->nama_satker ?? 'LAINNYA';
                            $satkerLabel = $romawi[$satkerIdx] ?? ($satkerIdx + 1);
                            $satkerIdx++;
                            $subPertamax = [0, 0, 0];
                            $subDex = [0, 0, 0];
                        @endphp

                        <tr class="bg-yellow-50 dark:bg-yellow-900/20 font-bold">
                            <td class="border border-gray-400 dark:border-gray-600 px-2 py-2 text-center text-gray-900 dark:text-yellow-400">{{ $satkerLabel }}</td>
                            <td colspan="12" class="border border-gray-400 dark:border-gray-600 px-2 py-2 text-gray-900 dark:text-yellow-400 uppercase">{{ $satkerName }}</td>
                        </tr>

                        @if($items->count() > 0)
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
                                    <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 whitespace-nowrap">{{ $rk->uraian ?? $k->kategori_kendaraan ?? 'Operasional' }}</td>
                                    <td class="border border-gray-400 dark:border-gray-600 px-2 py-1">{{ $k->jenis_kendaraan ?? '-' }}</td>
                                    <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center font-semibold">{{ $k->no_polisi ?? '-' }}</td>
                                    <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center text-gray-500">{{ $rk->liter_per_hari }} x {{ $rk->bulan1_total > 0 ? round($rk->bulan1_total / max($rk->liter_per_hari, 1)) : 0 }}</td>
                                    <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center font-semibold text-blue-600 dark:text-blue-400">{{ $isPertamax && $rk->bulan1_total > 0 ? number_format($rk->bulan1_total, 0, ',', '.') : '' }}</td>
                                    <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center font-semibold text-emerald-600 dark:text-emerald-400">{{ !$isPertamax && $rk->bulan1_total > 0 ? number_format($rk->bulan1_total, 0, ',', '.') : '' }}</td>
                                    <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center text-gray-500">{{ $rk->liter_per_hari_b2 }} x {{ $rk->bulan2_total > 0 ? round($rk->bulan2_total / max($rk->liter_per_hari_b2, 1)) : 0 }}</td>
                                    <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center font-semibold text-blue-600 dark:text-blue-400">{{ $isPertamax && $rk->bulan2_total > 0 ? number_format($rk->bulan2_total, 0, ',', '.') : '' }}</td>
                                    <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center font-semibold text-emerald-600 dark:text-emerald-400">{{ !$isPertamax && $rk->bulan2_total > 0 ? number_format($rk->bulan2_total, 0, ',', '.') : '' }}</td>
                                    <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center text-gray-500">{{ $rk->liter_per_hari_b3 }} x {{ $rk->bulan3_total > 0 ? round($rk->bulan3_total / max($rk->liter_per_hari_b3, 1)) : 0 }}</td>
                                    <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center font-semibold text-blue-600 dark:text-blue-400">{{ $isPertamax && $rk->bulan3_total > 0 ? number_format($rk->bulan3_total, 0, ',', '.') : '' }}</td>
                                    <td class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center font-semibold text-emerald-600 dark:text-emerald-400">{{ !$isPertamax && $rk->bulan3_total > 0 ? number_format($rk->bulan3_total, 0, ',', '.') : '' }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td colspan="13" class="border border-gray-400 dark:border-gray-600 px-2 py-1 text-center text-gray-500">Tidak ada kendaraan</td>
                            </tr>
                        @endif

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

    {{-- MODAL PILIH UKURAN KERTAS CETAK PDF --}}
    <div x-show="showPrintModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showPrintModal = false"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-gray-700 p-6 w-full max-w-md z-10" @click.stop>
            <div class="text-center mb-6">
                <div class="mx-auto w-14 h-14 flex items-center justify-center rounded-2xl bg-rose-50 dark:bg-rose-900/30 border border-rose-100 dark:border-rose-800/40 text-rose-600 dark:text-rose-400 mb-3 shadow-inner">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">Pilih Ukuran Kertas PDF</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Cetak dokumen Rendis BBM <span class="font-bold text-rose-600 dark:text-rose-400" x-text="printRendisTitle"></span>
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                {{-- Opsi A4 --}}
                <a :href="printBaseUrl + '?paper=a4'" target="_blank" @click="showPrintModal = false" class="group relative flex flex-col p-4 rounded-xl border-2 border-slate-200 dark:border-gray-700 hover:border-indigo-500 dark:hover:border-indigo-400 bg-slate-50/50 dark:bg-gray-700/30 hover:bg-indigo-50/40 dark:hover:bg-indigo-950/30 transition-all duration-200 text-left cursor-pointer">
                    <div class="flex items-center justify-between mb-2">
                        <span class="inline-flex items-center justify-center px-2 py-0.5 text-[10px] font-bold uppercase rounded bg-slate-200 dark:bg-gray-600 text-slate-700 dark:text-slate-200 group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                            A4
                        </span>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </div>
                    <div class="font-black text-slate-800 dark:text-white text-base group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Kertas A4</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 font-medium">210 x 297 mm</div>
                    <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-2">Standar ISO Internasional</div>
                </a>

                {{-- Opsi F4 --}}
                <a :href="printBaseUrl + '?paper=f4'" target="_blank" @click="showPrintModal = false" class="group relative flex flex-col p-4 rounded-xl border-2 border-rose-300 dark:border-rose-900/60 hover:border-rose-500 dark:hover:border-rose-400 bg-rose-50/30 dark:bg-rose-950/20 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-all duration-200 text-left cursor-pointer shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="inline-flex items-center justify-center px-2 py-0.5 text-[10px] font-bold uppercase rounded bg-rose-500 text-white">
                            F4 (Folio)
                        </span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                            Disarankan
                        </span>
                    </div>
                    <div class="font-black text-slate-800 dark:text-white text-base group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">Kertas F4</div>
                    <div class="text-[11px] text-rose-600 dark:text-rose-400 mt-1 font-bold">215 x 345 mm</div>
                    <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-2">Tinggi 345mm & Lebar 215mm</div>
                </a>
            </div>

            <button type="button" @click="showPrintModal = false" class="w-full px-4 py-2.5 text-sm font-semibold rounded-xl border border-slate-300 dark:border-gray-600 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                Batal
            </button>
        </div>
    </div>
</div>
</x-app-layout>
