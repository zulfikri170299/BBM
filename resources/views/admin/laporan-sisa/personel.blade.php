<x-app-layout>
    <div class="p-4 lg:p-6 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $title }}</h1>
                <p class="text-sm text-slate-500">{{ $periode }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.laporan-sisa.personel.print') }}" target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 shadow-lg shadow-red-500/30 transition-all hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak PDF
                </a>
                <a href="{{ route('admin.dashboard') }}"
                    class="inline-flex items-center px-4 py-2 bg-slate-600 text-white rounded-lg font-semibold text-sm hover:bg-slate-700 shadow-lg shadow-slate-500/30 transition-all hover:-translate-y-0.5">
                    ← Kembali
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead
                        class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase tracking-wider text-center">
                        <tr>
                            <th rowspan="2" class="px-4 py-4 border-r border-slate-200 w-16">NO</th>
                            <th rowspan="2" class="px-6 py-4 border-r border-slate-200">SATKER</th>
                            <th colspan="2" class="px-6 py-3 border-b border-slate-200">SISA BBM</th>
                        </tr>
                        <tr>
                            <th class="px-6 py-3 border-r border-slate-200">PERTAMAX</th>
                            <th class="px-6 py-3">PERTAMINA DEX</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($rows as $index => $row)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-4 text-center font-medium text-slate-500 border-r border-slate-100">
                                    {{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-900 border-r border-slate-100">
                                    {{ $row['satker'] }}</td>
                                <td
                                    class="px-6 py-4 text-center font-bold text-emerald-600 border-r border-slate-100 bg-emerald-50/20">
                                    {{ number_format($row['pertamax'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-indigo-600 bg-indigo-50/20">
                                    {{ number_format($row['dex'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-900 text-white font-bold text-lg text-center">
                        <tr>
                            <td colspan="2" class="px-6 py-4 text-right uppercase tracking-wider">JUMLAH</td>
                            <td class="px-6 py-4 bg-emerald-900/50">
                                {{ number_format($totalPertamax, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 bg-indigo-900/50">
                                {{ number_format($totalDex, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>