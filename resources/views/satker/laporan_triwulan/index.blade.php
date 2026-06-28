<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white">Laporan Per 3 Bulan</h1>
                <p class="mt-1 text-slate-400">Rekapitulasi total pendapatan, pemakaian, dan sisa BBM Satker {{ $satker->nama_satker }}.</p>
            </div>
            <!-- Print Button -->
            <div class="flex gap-2">
                <a href="{{ route('satker.laporan-triwulan.print', request()->query()) }}" target="_blank"
                    class="inline-flex items-center px-4 py-2.5 bg-red-600 text-white rounded-xl font-semibold text-sm hover:bg-red-700 shadow-lg shadow-red-500/30 transition-all duration-200 hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Cetak PDF
                </a>
            </div>
        </div>

        <div class="bg-slate-900 rounded-2xl border border-white/10 shadow-sm overflow-hidden p-6 mb-6">
            <form action="{{ route('satker.laporan-triwulan.index') }}" method="GET"
                class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Tahun</label>
                    <select name="tahun" id="filter_tahun" required onchange="this.form.submit()"
                        class="tom-select w-full">
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
                        class="tom-select w-full">
                        <option value="1" {{ request('triwulan', 1) == 1 ? 'selected' : '' }}>Triwulan I (Jan-Mar)
                        </option>
                        <option value="2" {{ request('triwulan') == 2 ? 'selected' : '' }}>Triwulan II (Apr-Jun)</option>
                        <option value="3" {{ request('triwulan') == 3 ? 'selected' : '' }}>Triwulan III (Jul-Sep)</option>
                        <option value="4" {{ request('triwulan') == 4 ? 'selected' : '' }}>Triwulan IV (Okt-Des)</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('satker.laporan-triwulan.index') }}"
                        class="px-5 py-2.5 bg-slate-800 text-slate-400 rounded-xl font-bold text-xs hover:bg-slate-200 transition flex items-center justify-center">RESET</a>
                </div>
            </form>
        </div>

        <div class="bg-slate-900 rounded-2xl border border-white/10 shadow-sm overflow-hidden">
            <div
                class="p-6 sm:p-8 border-b border-white/5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-200">Rekapan Periode: {{ $periodeLabel }}</h3>
                    <p class="text-xs text-slate-400">Berikut adalah data rekapan pemakaian BBM Satker Anda pada periode ini.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/10">
                    <thead class="bg-slate-800">
                        <tr>
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
                        <tr class="hover:bg-slate-800/50 transition">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-slate-300 border-r border-white/5">
                                {{ strtoupper($satker->nama_satker) }}
                            </td>

                            @foreach($allBbmTypes as $jenis)
                                <td class="px-4 py-3 whitespace-nowrap text-center text-xs text-slate-400 border-r border-white/5">
                                    {{ number_format($pendapatan[$jenis] ?? 0, 0, ',', '.') }}
                                </td>
                            @endforeach

                            @foreach($allBbmTypes as $jenis)
                                <td class="px-4 py-3 whitespace-nowrap text-center text-xs text-slate-400 border-r border-white/5">
                                    {{ number_format($pemakaian[$jenis] ?? 0, 0, ',', '.') }}
                                </td>
                            @endforeach

                            @foreach($allBbmTypes as $jenis)
                                @php
                                    $sisa = $sisaBbm[$jenis] ?? 0;
                                @endphp
                                <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-bold {{ $sisa < 0 ? 'text-red-600' : 'text-emerald-600' }} border-r border-white/5">
                                    {{ rtrim(rtrim(number_format($sisa, 2, ',', '.'), '0'), ',') }}
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
