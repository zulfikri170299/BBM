<x-app-layout>
    <div class="container-fluid py-4 px-3 sm:py-8 sm:px-6 bg-slate-50 min-h-screen">
        <!-- Header Section -->
        <div class="mb-5 sm:mb-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4 sm:gap-6">
            <div>
                <h1 class="text-xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Rekapan Pengisian</h1>
                <p class="text-slate-500 font-medium mt-1 text-xs sm:text-base">Laporan total pengisian berdasarkan
                    input meteran.</p>
            </div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                <!-- Filter Form -->
                <form action="{{ route('petugas.rekapan.index') }}" method="GET"
                    class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto">
                    <select name="month" onchange="this.form.submit()"
                        class="bg-white border border-slate-200 text-slate-700 text-xs sm:text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-2 sm:p-2.5 font-bold flex-1 sm:flex-none">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                    <select name="year" onchange="this.form.submit()"
                        class="bg-white border border-slate-200 text-slate-700 text-xs sm:text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-2 sm:p-2.5 font-bold flex-1 sm:flex-none">
                        @foreach(range(date('Y'), 2024) as $y)
                            <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <!-- Print Button -->
                <a href="{{ route('petugas.rekapan.print', ['month' => $selectedMonth, 'year' => $selectedYear]) }}"
                    target="_blank"
                    class="flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 sm:px-5 sm:py-2.5 rounded-xl font-bold text-xs sm:text-sm transition-colors shadow-lg shadow-indigo-200 w-full sm:w-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    <span>Cetak PDF</span>
                </a>
            </div>
        </div>

        <!-- Grand Total Summary -->
        <div class="grid grid-cols-2 gap-3 sm:gap-6 mb-5 sm:mb-8">
            <div
                class="bg-emerald-50 rounded-xl sm:rounded-2xl p-3 sm:p-6 border border-emerald-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                <div>
                    <span
                        class="block text-[8px] sm:text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-0.5 sm:mb-1">Total
                        Pertamax</span>
                    <h3 class="text-lg sm:text-2xl font-black text-emerald-700 truncate">
                        {{ number_format($totalPertamax, 0, ',', '.') }} L</h3>
                </div>
                <div
                    class="w-8 h-8 sm:w-12 sm:h-12 bg-emerald-100 rounded-lg sm:rounded-xl flex items-center justify-center text-emerald-600 self-end sm:self-center">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
            <div
                class="bg-indigo-50 rounded-xl sm:rounded-2xl p-3 sm:p-6 border border-indigo-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                <div>
                    <span
                        class="block text-[8px] sm:text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-0.5 sm:mb-1">Total
                        Dex</span>
                    <h3 class="text-lg sm:text-2xl font-black text-indigo-700 truncate">
                        {{ number_format($totalDex, 0, ',', '.') }} L</h3>
                </div>
                <div
                    class="w-8 h-8 sm:w-12 sm:h-12 bg-indigo-100 rounded-lg sm:rounded-xl flex items-center justify-center text-indigo-600 self-end sm:self-center">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div
            class="bg-white rounded-xl sm:rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th
                                class="px-3 py-3 sm:px-6 sm:py-5 text-[8px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">
                                Tanggal</th>
                            <th
                                class="px-3 py-3 sm:px-6 sm:py-5 text-[8px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
                                BBM</th>
                            <th
                                class="px-3 py-3 sm:px-6 sm:py-5 text-[8px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest text-right hidden sm:table-cell">
                                Awal</th>
                            <th
                                class="px-3 py-3 sm:px-6 sm:py-5 text-[8px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest text-right hidden sm:table-cell">
                                Akhir</th>
                            <th
                                class="px-3 py-3 sm:px-6 sm:py-5 text-[8px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                                Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($recap as $date => $readings)
                            @foreach($readings as $reading)
                                @php
                                    $totalLiter = $reading->meter_akhir - $reading->meter_awal;
                                    if ($totalLiter < 0)
                                        $totalLiter = 0;

                                    $colorClass = match ($reading->jenis_bbm) {
                                        'Pertamax', 'PERTAMAX' => 'emerald',
                                        'Pertamina Dex', 'PERTAMINA DEX' => 'indigo',
                                        default => 'slate'
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-3 py-3 sm:px-6 sm:py-5 whitespace-nowrap">
                                        <div class="flex items-center gap-2 sm:gap-3">
                                            <div
                                                class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-[10px] sm:text-xs group-hover:scale-110 transition-transform">
                                                {{ \Carbon\Carbon::parse($date)->format('d') }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800 text-xs sm:text-sm">
                                                    {{ \Carbon\Carbon::parse($date)->translatedFormat('F') }}</div>
                                                <div class="text-[9px] sm:text-xs font-medium text-slate-400">
                                                    {{ \Carbon\Carbon::parse($date)->translatedFormat('l') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 sm:px-6 sm:py-5 whitespace-nowrap text-center">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2 py-1 sm:px-3 sm:py-1.5 rounded-lg bg-{{ $colorClass }}-50 text-{{ $colorClass }}-700 border border-{{ $colorClass }}-100 font-bold text-[9px] sm:text-xs uppercase tracking-wider">
                                            {{ $reading->jenis_bbm == 'PERTAMINA DEX' ? 'DEX' : $reading->jenis_bbm }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-3 py-3 sm:px-6 sm:py-5 whitespace-nowrap text-right font-medium text-slate-500 hidden sm:table-cell text-xs sm:text-sm">
                                        {{ number_format($reading->meter_awal, 0, ',', '.') }}
                                    </td>
                                    <td
                                        class="px-3 py-3 sm:px-6 sm:py-5 whitespace-nowrap text-right font-medium text-slate-500 hidden sm:table-cell text-xs sm:text-sm">
                                        {{ number_format($reading->meter_akhir, 0, ',', '.') }}
                                    </td>
                                    <td class="px-3 py-3 sm:px-6 sm:py-5 whitespace-nowrap text-right">
                                        <div class="flex flex-col items-end">
                                            <span
                                                class="font-black text-slate-800 text-xs sm:text-sm">{{ number_format($totalLiter, 0, ',', '.') }}
                                                L</span>
                                            <!-- Mobile only meter detail -->
                                            <span class="sm:hidden text-[9px] text-slate-400">
                                                {{ number_format($reading->meter_awal, 0, ',', '.') }} ->
                                                {{ number_format($reading->meter_akhir, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 sm:px-8 sm:py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-8 h-8 sm:w-12 sm:h-12 text-slate-200 mb-2 sm:mb-3" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                            </path>
                                        </svg>
                                        <p class="font-medium text-xs sm:text-base">Belum ada data input meteran.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>