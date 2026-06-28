<x-app-layout>
<div class="py-8 px-2 sm:px-6 lg:px-8 bg-slate-800/50/30 min-h-screen">
    <!-- Clean & Professional Header -->
    <div class="max-w-7xl mx-auto mb-8 px-2 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h1 class="text-2xl font-bold text-white tracking-tight">Laporan Harian BBM</h1>
                <p class="mt-1 text-xs text-slate-400 font-medium italic">Rekonsiliasi data pengisian fisik (pompa) vs pencatatan sistem digital.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">

                <!-- PDF Export Button -->
                <a href="{{ route('admin.laporan-harian.pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-xl transition-all duration-200 shadow-sm font-bold text-xs uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Cetak PDF
                </a>

                <form action="{{ route('admin.laporan-harian.index') }}" method="GET" class="flex items-center gap-2 bg-slate-900 border border-white/5 p-1 rounded-xl shadow-sm border border-white/10 ring-1 ring-black/5">
                    <div class="px-3 border-r border-white/5">
                        <select name="bulan" class="border-0 bg-transparent text-xs font-bold text-slate-300 focus:ring-0 cursor-pointer py-2 pl-0 pr-6 appearance-none">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ sprintf('%02d', $m) }}" {{ $bulan == sprintf('%02d', $m) ? 'selected' : '' }}>
                                    {{ Carbon\Carbon::create()->month((int)$m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="px-3">
                        <select name="tahun" class="border-0 bg-transparent text-xs font-bold text-slate-300 focus:ring-0 cursor-pointer py-2 pl-0 pr-6 appearance-none">
                            @foreach(range(date('Y')-2, date('Y')) as $y)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-all duration-200 font-bold text-xs uppercase tracking-wider">
                        Filter
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
         class="max-w-7xl mx-auto mb-6 p-3 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl flex items-center shadow-sm animate-fade-in px-2 sm:px-6 lg:px-8">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
        <span class="text-xs font-bold uppercase tracking-tight">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Modern Data Grid Table -->
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="bg-slate-900 border border-white/5 rounded-2xl shadow-sm border border-white/10 overflow-hidden ring-1 ring-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/5">
                    <thead>
                        <tr class="bg-slate-800/50">
                            <th class="px-4 py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-white/10 w-12">#</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-white/10">Tanggal Operasional</th>
                            <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-white/10 w-40">Jenis BBM</th>
                            <th class="px-4 py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-white/10">Meter Awal</th>
                            <th class="px-4 py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-white/10">Meter Akhir</th>
                            <th class="px-4 py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-white/10 bg-slate-800/50">Output Fisik</th>
                            <th class="px-4 py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-white/10">Log Aplikasi</th>
                            <th class="px-4 py-3 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-white/10 bg-slate-800/50">Selisih (Audit)</th>
                            <th class="px-4 py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-white/10">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @php 
                            $i = 1;
                            $totalPertamaxManual = 0;
                            $totalPertamaxApp = 0;
                            $totalDexManual = 0;
                            $totalDexApp = 0;
                        @endphp
                        @foreach($dates as $date)
                            @php
                                $carbonDate = Carbon\Carbon::parse($date);
                                $isWeekend = $carbonDate->isWeekend();
                                $types = ['PERTAMAX', 'PERTAMINA DEX'];
                            @endphp
                            
                            @foreach($types as $type)
                                @php
                                    $manual = $manualData->get($date)?->where('jenis_bbm', $type)->first();
                                    $appTotal = $appData->get($date)?->where('bbm_alias', $type)->first()?->total ?? 0;
                                    $manualTotal = $manual ? ($manual->meter_akhir - $manual->meter_awal) : 0;
                                    $diff = $appTotal - $manualTotal;
                                    
                                    if($type == 'PERTAMAX') {
                                        $totalPertamaxManual += $manualTotal;
                                        $totalPertamaxApp += $appTotal;
                                    } else {
                                        $totalDexManual += $manualTotal;
                                        $totalDexApp += $appTotal;
                                    }
                                    
                                    $typeStyles = $type === 'PERTAMAX' ? 'text-emerald-400 bg-emerald-500/20 border-emerald-500/30' : 'text-indigo-400 bg-indigo-500/20 border-indigo-500/30';
                                @endphp
                                <tr class="hover:bg-slate-800/50 transition-colors">
                                    @if($loop->first)
                                    <td rowspan="2" class="px-4 py-4 whitespace-nowrap text-[11px] font-bold text-center text-gray-300 border-r border-white/5 align-middle">
                                        {{ $i++ }}
                                    </td>
                                    <td rowspan="2" class="px-4 py-3 whitespace-nowrap border-r border-white/5 align-middle">
                                        <div class="flex items-baseline gap-2">
                                            <span class="text-sm font-bold text-white tracking-tight">{{ $carbonDate->format('d') }}</span>
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $carbonDate->translatedFormat('M Y') }}</span>
                                            <span class="ml-auto text-[9px] font-black uppercase tracking-tighter {{ $isWeekend ? 'text-rose-500' : 'text-gray-400' }}">
                                                {{ $carbonDate->translatedFormat('D') }}
                                            </span>
                                        </div>
                                    </td>
                                    @endif
                                    <td class="px-4 py-3 whitespace-nowrap border-r border-white/5">
                                        <div class="inline-flex items-center px-2 py-0.5 rounded-md border {{ $typeStyles }} text-[9px] font-black tracking-wide uppercase">
                                            {{ $type }}
                                        </div>
                                    </td>
                                    
                                    <td class="px-4 py-3 whitespace-nowrap text-center border-r border-white/5">
                                        <span class="text-xs font-bold text-slate-300">
                                            {{ $manual !== null && $manual->meter_awal != 0 ? number_format($manual->meter_awal, 0, ',', '.') : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center border-r border-white/5">
                                        <span class="text-xs font-bold text-slate-300">
                                            {{ $manual !== null && $manual->meter_akhir != 0 ? number_format($manual->meter_akhir, 0, ',', '.') : '-' }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 whitespace-nowrap text-center bg-slate-800/30 border-r border-white/5 align-middle">
                                        <span class="text-xs font-black text-indigo-400">
                                            {{ $manual !== null && (float)$manualTotal != 0 ? rtrim(rtrim(number_format($manualTotal, 2, ',', '.'), '0'), ',') : '' }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-4 py-3 whitespace-nowrap text-center border-r border-white/5 align-middle">
                                        <span class="text-xs font-bold text-slate-300">
                                            @php $hasAppData = $appData->get($date)?->where('bbm_alias', $type)->isNotEmpty(); @endphp
                                            {{ $hasAppData && (float)$appTotal != 0 ? rtrim(rtrim(number_format($appTotal, 2, ',', '.'), '0'), ',') : '' }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-4 py-3 whitespace-nowrap text-center align-middle border-r border-white/5 bg-slate-800/30">
                                        <div class="inline-flex items-center justify-center gap-1.5 w-full">
                                            <div class="w-1.5 h-1.5 rounded-full {{ ($manual !== null || $hasAppData) && $diff != 0 ? 'bg-rose-500' : '' }}"></div>
                                            <span class="text-xs font-black tracking-tight {{ $diff != 0 ? 'text-rose-400' : 'text-emerald-400' }}">
                                                {{ ($manual !== null || $hasAppData) && (float)$diff != 0 ? rtrim(rtrim(number_format($diff, 2, ',', '.'), '0'), ',') : '' }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 border-r border-white/5">
                                        <div class="text-[10px] font-medium text-slate-400 break-words line-clamp-2" title="{{ $manual !== null ? $manual->keterangan : '' }}">
                                            {{ $manual !== null && $manual->keterangan ? $manual->keterangan : '-' }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-800/50 uppercase tracking-widest">
                        <tr class="divide-x divide-white/5 font-bold">
                            <td colspan="5" class="px-4 py-3 text-right text-[10px] text-slate-400 border-b border-white/5 italic">Total Audit: <span class="text-emerald-400 font-black not-italic">PERTAMAX</span></td>
                            <td class="px-4 py-4 text-center text-xs text-indigo-400 border-b border-white/5 bg-slate-800/30">{{ $totalPertamaxManual != 0 ? rtrim(rtrim(number_format($totalPertamaxManual, 2, ',', '.'), '0'), ',') : '' }}</td>
                            <td class="px-4 py-4 text-center text-xs text-slate-300 border-b border-white/5">{{ $totalPertamaxApp != 0 ? rtrim(rtrim(number_format($totalPertamaxApp, 2, ',', '.'), '0'), ',') : '' }}</td>
                            <td class="px-4 py-3 text-center align-middle border-b border-white/5 bg-slate-800/30">
                                @php $pDiff = $totalPertamaxApp - $totalPertamaxManual; @endphp
                                <span class="text-xs font-black {{ $pDiff != 0 ? 'text-rose-400' : 'text-emerald-400' }}">
                                    {{ $pDiff != 0 ? rtrim(rtrim(number_format($pDiff, 2, ',', '.'), '0'), ',') : '' }}
                                </span>
                            </td>
                            <td class="bg-slate-900 border-b border-white/5"></td>
                        </tr>
                        <tr class="divide-x divide-white/5 font-bold">
                            <td colspan="5" class="px-4 py-3 text-right text-[10px] text-slate-400 italic">Total Audit: <span class="text-indigo-400 font-black not-italic">PERTAMINA DEX</span></td>
                            <td class="px-4 py-4 text-center text-xs text-indigo-400 bg-slate-800/30">{{ $totalDexManual != 0 ? rtrim(rtrim(number_format($totalDexManual, 2, ',', '.'), '0'), ',') : '' }}</td>
                            <td class="px-4 py-4 text-center text-xs text-slate-300">{{ $totalDexApp != 0 ? rtrim(rtrim(number_format($totalDexApp, 2, ',', '.'), '0'), ',') : '' }}</td>
                            <td class="px-4 py-3 text-center align-middle bg-slate-800/30">
                                @php $dDiff = $totalDexApp - $totalDexManual; @endphp
                                <span class="text-xs font-black {{ $dDiff != 0 ? 'text-rose-400' : 'text-emerald-400' }}">
                                    {{ $dDiff != 0 ? rtrim(rtrim(number_format($dDiff, 2, ',', '.'), '0'), ',') : '' }}
                                </span>
                            </td>
                            <td class="bg-slate-900 border border-white/5"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <!-- Subtle Legend -->
        <div class="mt-6 flex flex-wrap items-center gap-6 justify-center text-[10px] font-bold uppercase tracking-widest text-slate-400">
            <div class="flex items-center gap-2">
                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div>
                <span>Data Rekonsiliasi Sesuai</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-2.5 h-2.5 rounded-full bg-rose-500"></div>
                <span>Terdapat Selisih Audit</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-2.5 h-2.5 rounded-lg bg-slate-800/50 border border-white/10"></div>
                <span>Input / Output Audit Fisik</span>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.4s ease-out forwards;
    }
    
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] { -moz-appearance: textfield; }
    
    /* Clean scrollbar for the table */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>
</x-app-layout>
