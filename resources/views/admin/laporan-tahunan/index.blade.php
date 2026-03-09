<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Laporan Tahunan</h1>
                    <p class="text-slate-500 mt-1">Rekapitulasi BBM Tahunan</p>
                </div>

                <div class="flex items-center gap-3">
                    <form action="{{ route('admin.laporan-tahunan.index') }}" method="GET" class="flex gap-2">
                        <select name="year" class="rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-medium">
                            @foreach($availableYears as $y)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-slate-900 text-white text-sm font-semibold rounded-xl hover:bg-slate-800 transition-colors shadow-sm gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filter
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.laporan-tahunan.print', ['year' => $year]) }}" target="_blank"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak PDF
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50/50 text-slate-600 font-semibold border-b border-slate-100">
                            <tr>
                                <th rowspan="2" class="px-6 py-4 border-r border-slate-100 text-center w-16">NO</th>
                                <th rowspan="2" class="px-6 py-4 border-r border-slate-100">SATKER</th>
                                <th colspan="2" class="px-6 py-4 border-r border-slate-100 text-center">PENDAPATAN</th>
                                <th colspan="2" class="px-6 py-4 border-r border-slate-100 text-center">PEMAKAIAN</th>
                                <th colspan="2" class="px-6 py-4 text-center">SISA PEMAKAIAN</th>
                            </tr>
                            <tr class="bg-slate-50 border-b border-slate-100 text-xs tracking-wider">
                                <th class="px-6 py-3 border-r border-slate-100 text-center text-indigo-600">PERTAMAX</th>
                                <th class="px-6 py-3 border-r border-slate-100 text-center text-emerald-600">PERTAMINA DEX</th>
                                <th class="px-6 py-3 border-r border-slate-100 text-center text-indigo-600">PERTAMAX</th>
                                <th class="px-6 py-3 border-r border-slate-100 text-center text-emerald-600">PERTAMINA DEX</th>
                                <th class="px-6 py-3 border-r border-slate-100 text-center text-indigo-600">PERTAMAX</th>
                                <th class="px-6 py-3 text-center text-emerald-600">PERTAMINA DEX</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @php
                                $total_pendapatan_pertamax = 0;
                                $total_pendapatan_dex = 0;
                                $total_pemakaian_pertamax = 0;
                                $total_pemakaian_dex = 0;
                                $total_sisa_pertamax = 0;
                                $total_sisa_dex = 0;
                            @endphp
                            @forelse($reportData as $index => $data)
                                @php
                                    $total_pendapatan_pertamax += $data['pendapatan_pertamax'];
                                    $total_pendapatan_dex += $data['pendapatan_dex'];
                                    $total_pemakaian_pertamax += $data['pemakaian_pertamax'];
                                    $total_pemakaian_dex += $data['pemakaian_dex'];
                                    $total_sisa_pertamax += $data['sisa_pertamax'];
                                    $total_sisa_dex += $data['sisa_dex'];
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 border-r border-slate-100 text-center text-slate-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 border-r border-slate-100 font-medium text-slate-700">{{ $data['satker'] }}</td>
                                    <td class="px-6 py-4 border-r border-slate-100 text-center">{{ number_format($data['pendapatan_pertamax'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 border-r border-slate-100 text-center">{{ number_format($data['pendapatan_dex'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 border-r border-slate-100 text-center">{{ number_format($data['pemakaian_pertamax'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 border-r border-slate-100 text-center">{{ number_format($data['pemakaian_dex'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 border-r border-slate-100 text-center font-medium {{ $data['sisa_pertamax'] < 0 ? 'text-rose-600' : 'text-slate-700' }}">{{ number_format($data['sisa_pertamax'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-center font-medium {{ $data['sisa_dex'] < 0 ? 'text-rose-600' : 'text-slate-700' }}">{{ number_format($data['sisa_dex'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <p class="text-slate-500 font-medium">Tidak ada data untuk tahun ini</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            @if(count($reportData) > 0)
                                <tr class="bg-indigo-50/50 font-bold border-t-2 border-slate-200">
                                    <td colspan="2" class="px-6 py-4 border-r border-slate-200 text-center text-indigo-900">TOTAL</td>
                                    <td class="px-6 py-4 border-r border-slate-200 text-center text-indigo-900">{{ number_format($total_pendapatan_pertamax, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 border-r border-slate-200 text-center text-indigo-900">{{ number_format($total_pendapatan_dex, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 border-r border-slate-200 text-center text-indigo-900">{{ number_format($total_pemakaian_pertamax, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 border-r border-slate-200 text-center text-indigo-900">{{ number_format($total_pemakaian_dex, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 border-r border-slate-200 text-center {{ $total_sisa_pertamax < 0 ? 'text-rose-600' : 'text-indigo-900' }}">{{ number_format($total_sisa_pertamax, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-center {{ $total_sisa_dex < 0 ? 'text-rose-600' : 'text-indigo-900' }}">{{ number_format($total_sisa_dex, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
