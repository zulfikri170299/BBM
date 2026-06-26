<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white">Laporan Per 3 Bulan</h1>
                <p class="mt-1 text-slate-400">Rekapitulasi total pendapatan, pemakaian, dan sisa BBM per Satker dan
                    Personel.</p>
            </div>
            <!-- Export & Print Button -->
            <div class="flex gap-2">
                <a href="{{ route('admin.laporan-triwulan.print', request()->query()) }}" target="_blank"
                    class="inline-flex items-center px-4 py-2.5 bg-red-600 text-white rounded-xl font-semibold text-sm hover:bg-red-700 shadow-lg shadow-red-500/30 transition-all duration-200 hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Cetak PDF
                </a>
                <a href="{{ route('admin.laporan-triwulan.export', request()->query()) }}"
                    class="inline-flex items-center px-4 py-2.5 bg-emerald-600 text-white rounded-xl font-semibold text-sm hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 transition-all duration-200 hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Export Excel
                </a>
            </div>
        </div>

        <div class="bg-slate-900 rounded-2xl border border-white/10 shadow-sm overflow-hidden p-6 mb-6">
            <form action="{{ route('admin.laporan-triwulan.index') }}" method="GET"
                class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Tahun</label>
                    <select name="tahun" id="filter_tahun" required onchange="this.form.submit()"
                        class="tom-select w-full bg-slate-800/50 border-white/10 rounded-xl transition-all font-semibold text-sm">
                        @php
                            $currentYear = date('Y');
                        @endphp
                        @for($i = $currentYear - 2; $i <= $currentYear + 1; $i++)
                            <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="flex-1 w-full">
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Triwulan</label>
                    <select name="triwulan" id="filter_triwulan" required onchange="this.form.submit()"
                        class="tom-select w-full bg-slate-800/50 border-white/10 rounded-xl transition-all font-semibold text-sm">
                        <option value="1" {{ request('triwulan', 1) == 1 ? 'selected' : '' }}>Triwulan I (Jan-Mar)
                        </option>
                        <option value="2" {{ request('triwulan') == 2 ? 'selected' : '' }}>Triwulan II (Apr-Jun)</option>
                        <option value="3" {{ request('triwulan') == 3 ? 'selected' : '' }}>Triwulan III (Jul-Sep)</option>
                        <option value="4" {{ request('triwulan') == 4 ? 'selected' : '' }}>Triwulan IV (Okt-Des)</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('admin.laporan-triwulan.index') }}"
                        class="px-5 py-2.5 bg-slate-800 text-slate-400 rounded-xl font-bold text-xs hover:bg-slate-200 transition flex items-center justify-center">RESET</a>
                </div>
            </form>
        </div>

        <div class="bg-slate-900 rounded-2xl border border-white/10 shadow-sm overflow-hidden">
            <div
                class="p-6 sm:p-8 border-b border-white/5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-200">Rekapan Periode: {{ $periodeLabel }}</h3>
                    <p class="text-xs text-slate-400">Berikut adalah data rekapan pemakaian BBM pada peride ini.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/10">
                    <thead class="bg-slate-800">
                        <tr>
                            <th rowspan="2"
                                class="px-4 py-3 text-center text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-r border-white/10 w-12 align-middle">
                                NO
                            </th>
                            <th rowspan="2"
                                class="px-4 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-r border-white/10 align-middle">
                                SATKER
                            </th>
                            <th colspan="{{ count($allBbmTypes) }}"
                                class="px-4 py-3 text-center text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-r border-white/10">
                                JUMLAH PENDAPATAN
                            </th>
                            <th colspan="{{ count($allBbmTypes) }}"
                                class="px-4 py-3 text-center text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-r border-white/10">
                                PEMAKAIAN
                            </th>
                            <th colspan="{{ count($allBbmTypes) }}"
                                class="px-4 py-3 text-center text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-white/10">
                                SISA BBM
                            </th>
                        </tr>
                        <tr>
                            @foreach($allBbmTypes as $jenis)
                                <th
                                    class="px-4 py-2 text-center text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-r border-white/10 bg-slate-800/50 whitespace-nowrap">
                                    {{ $jenis }}
                                </th>
                            @endforeach
                            @foreach($allBbmTypes as $jenis)
                                <th
                                    class="px-4 py-2 text-center text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-r border-white/10 bg-slate-800/50 whitespace-nowrap">
                                    {{ $jenis }}
                                </th>
                            @endforeach
                            @foreach($allBbmTypes as $jenis)
                                <th
                                    class="px-4 py-2 text-center text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-r border-white/10 bg-slate-800/50 whitespace-nowrap">
                                    {{ $jenis }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-slate-900 border border-white/5 divide-y divide-white/5">
                        @php
                            $sumPendapatan = [];
                            $sumPemakaian = [];
                            $sumSisa = [];
                            foreach ($allBbmTypes as $jenis) {
                                $sumPendapatan[$jenis] = 0;
                                $sumPemakaian[$jenis] = 0;
                                $sumSisa[$jenis] = 0;
                            }
                        @endphp
                        @foreach($satkers as $satker)
                            <tr class="hover:bg-slate-800/50 transition">
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium text-white border-r border-white/5">
                                    {{ $loop->iteration }}
                                </td>
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-sm font-bold text-slate-300 border-r border-white/5">
                                    {{ strtoupper($satker->nama_satker) }}
                                </td>

                                @foreach($allBbmTypes as $jenis)
                                    @php
                                        $valP = $pendapatan[$satker->id][$jenis] ?? 0;
                                        $sumPendapatan[$jenis] += $valP;
                                    @endphp
                                    <td
                                        class="px-4 py-3 whitespace-nowrap text-center text-xs text-slate-400 border-r border-white/5">
                                        {{ number_format($valP, 0, ',', '.') }}
                                    </td>
                                @endforeach

                                @foreach($allBbmTypes as $jenis)
                                    @php
                                        $valM = $pemakaian[$satker->id][$jenis] ?? 0;
                                        $sumPemakaian[$jenis] += $valM;
                                    @endphp
                                    <td
                                        class="px-4 py-3 whitespace-nowrap text-center text-xs text-slate-400 border-r border-white/5">
                                        {{ number_format($valM, 0, ',', '.') }}
                                    </td>
                                @endforeach

                                @foreach($allBbmTypes as $jenis)
                                    @php
                                        $sisa = $sisaBbm[$satker->id][$jenis] ?? 0;
                                        $sumSisa[$jenis] += $sisa;
                                    @endphp
                                    <td
                                        class="px-4 py-3 whitespace-nowrap text-center text-sm font-bold {{ $sisa < 0 ? 'text-red-600' : 'text-emerald-600' }} border-r border-white/5">
                                        {{ number_format($sisa, 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-800 divide-y divide-white/10">
                        <tr>
                            <td colspan="2"
                                class="px-4 py-3 whitespace-nowrap text-center text-sm font-bold text-white border-r border-white/10">
                                TOTAL</td>
                            @foreach($allBbmTypes as $jenis)
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-center text-sm font-bold text-white border-r border-white/10">
                                    {{ number_format($sumPendapatan[$jenis], 0, ',', '.') }}
                                </td>
                            @endforeach
                            @foreach($allBbmTypes as $jenis)
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-center text-sm font-bold text-white border-r border-white/10">
                                    {{ number_format($sumPemakaian[$jenis], 0, ',', '.') }}
                                </td>
                            @endforeach
                            @foreach($allBbmTypes as $jenis)
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-center text-sm font-bold {{ $sumSisa[$jenis] < 0 ? 'text-red-600' : 'text-emerald-700' }} border-r border-white/10">
                                    {{ number_format($sumSisa[$jenis], 0, ',', '.') }}
                                </td>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>