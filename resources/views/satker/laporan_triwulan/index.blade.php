<x-app-layout>
    <div class="p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Laporan Per 3 Bulan</h1>
                <p class="mt-1 text-slate-500">Rekapitulasi total pendapatan, pemakaian, dan sisa BBM Satker {{ $satker->nama_satker }}.</p>
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

        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden p-6 mb-6">
            <form action="{{ route('satker.laporan-triwulan.index') }}" method="GET"
                class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tahun</label>
                    <select name="tahun" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-700">
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
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Triwulan</label>
                    <select name="triwulan" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-700">
                        <option value="1" {{ request('triwulan', 1) == 1 ? 'selected' : '' }}>Triwulan I (Jan-Mar)
                        </option>
                        <option value="2" {{ request('triwulan') == 2 ? 'selected' : '' }}>Triwulan II (Apr-Jun)</option>
                        <option value="3" {{ request('triwulan') == 3 ? 'selected' : '' }}>Triwulan III (Jul-Sep)</option>
                        <option value="4" {{ request('triwulan') == 4 ? 'selected' : '' }}>Triwulan IV (Okt-Des)</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Tampilkan
                    </button>
                    <a href="{{ route('satker.laporan-triwulan.index') }}"
                        class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-semibold text-sm hover:bg-slate-200 transition">Reset</a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
            <div
                class="p-6 sm:p-8 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Rekapan Periode: {{ $periodeLabel }}</h3>
                    <p class="text-sm text-slate-500">Berikut adalah data rekapan pemakaian BBM Satker Anda pada periode ini.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-100">
                        <tr>
                            <th rowspan="2"
                                class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider border-b border-r border-slate-200 align-middle">
                                SATKER
                            </th>
                            <th colspan="{{ count($allBbmTypes) }}"
                                class="px-6 py-3 text-center text-xs font-bold text-slate-600 uppercase tracking-wider border-b border-r border-slate-200">
                                JUMLAH PENDAPATAN
                            </th>
                            <th colspan="{{ count($allBbmTypes) }}"
                                class="px-6 py-3 text-center text-xs font-bold text-slate-600 uppercase tracking-wider border-b border-r border-slate-200">
                                PEMAKAIAN
                            </th>
                            <th colspan="{{ count($allBbmTypes) }}"
                                class="px-6 py-3 text-center text-xs font-bold text-slate-600 uppercase tracking-wider border-b border-slate-200">
                                SISA BBM
                            </th>
                        </tr>
                        <tr>
                            @foreach($allBbmTypes as $jenis)
                                <th
                                    class="px-4 py-2 text-center text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-r border-slate-200 bg-slate-50 whitespace-nowrap">
                                    {{ $jenis }}
                                </th>
                            @endforeach
                            @foreach($allBbmTypes as $jenis)
                                <th
                                    class="px-4 py-2 text-center text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-r border-slate-200 bg-slate-50 whitespace-nowrap">
                                    {{ $jenis }}
                                </th>
                            @endforeach
                            @foreach($allBbmTypes as $jenis)
                                <th
                                    class="px-4 py-2 text-center text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-r border-slate-200 bg-slate-50 whitespace-nowrap">
                                    {{ $jenis }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-700 border-r border-slate-100">
                                {{ strtoupper($satker->nama_satker) }}
                            </td>

                            @foreach($allBbmTypes as $jenis)
                                <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-slate-600 border-r border-slate-100">
                                    {{ number_format($pendapatan[$jenis] ?? 0, 0, ',', '.') }}
                                </td>
                            @endforeach

                            @foreach($allBbmTypes as $jenis)
                                <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-slate-600 border-r border-slate-100">
                                    {{ number_format($pemakaian[$jenis] ?? 0, 0, ',', '.') }}
                                </td>
                            @endforeach

                            @foreach($allBbmTypes as $jenis)
                                @php
                                    $sisa = $sisaBbm[$jenis] ?? 0;
                                @endphp
                                <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-bold {{ $sisa < 0 ? 'text-red-600' : 'text-emerald-600' }} border-r border-slate-100">
                                    {{ number_format($sisa, 0, ',', '.') }}
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
