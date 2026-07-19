<x-app-layout>
    <div class="max-w-7xl mx-auto p-2 sm:p-6 lg:p-8 space-y-6 px-2 sm:px-6 lg:px-8">
        <!-- Page Title -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-white/10 pb-5">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight">Saldo Yang di Alihkan</h1>
                <p class="mt-1 text-xs sm:text-sm font-medium text-slate-400">Laporan pemotongan saldo kendaraan oleh Admin.</p>
            </div>
        </div>

        <!-- Filter & Action Actions -->
        <div class="bg-slate-900 border border-white/5 p-3 sm:p-4 rounded-2xl border border-white/10 shadow-sm">
            <form action="{{ route('satker.saldo-dialihkan.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="flex-1 w-full flex flex-col sm:flex-row gap-4">
                    <div class="w-full sm:w-1/2">
                        <label class="block text-xs font-bold text-slate-300 mb-1.5 uppercase tracking-wider">Mulai Tanggal</label>
                        <input type="date" name="start_date" value="{{ request('start_date', '') }}" 
                            class="w-full text-sm font-medium bg-slate-800/50 border border-white/10 text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 transition-all outline-none">
                    </div>
                    <div class="w-full sm:w-1/2">
                        <label class="block text-xs font-bold text-slate-300 mb-1.5 uppercase tracking-wider">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ request('end_date', '') }}" 
                            class="w-full text-sm font-medium bg-slate-800/50 border border-white/10 text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 transition-all outline-none">
                    </div>
                </div>
                
                <div class="flex items-center gap-2 w-full sm:w-auto mt-4 sm:mt-0">
                    <button type="submit" class="flex-1 sm:flex-none inline-flex justify-center items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-indigo-500/30 active:scale-95 gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filter
                    </button>
                    
                    @if(request()->has('start_date'))
                        <a href="{{ route('satker.saldo-dialihkan.index') }}" class="flex-1 sm:flex-none inline-flex justify-center items-center px-4 py-2.5 bg-slate-900 border border-white/5 hover:bg-slate-800/50 text-slate-300 text-sm font-bold rounded-xl border border-white/10 transition-all shadow-sm active:scale-95 gap-2">
                            Reset
                        </a>
                    @endif
                    
                    <a href="{{ route('satker.saldo-dialihkan.print', request()->all()) }}" target="_blank" class="flex-1 sm:flex-none inline-flex justify-center items-center px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-red-200 active:scale-95 gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        PDF
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Data -->
        <div class="bg-slate-900 border border-white/5 rounded-2xl border border-white/10 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-400 uppercase bg-slate-800/50 border-b border-white/10">
                        <tr>
                            <th class="px-4 py-3 font-black">NO</th>
                            <th class="px-4 py-3 font-black">TANGGAL</th>
                            <th class="px-4 py-3 font-black">JENIS KENDARAAN DAN NOPOL</th>
                            <th class="px-4 py-3 font-black text-center">LITER</th>
                            <th class="px-4 py-3 font-black">KETERANGAN</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($riwayat as $index => $item)
                        <tr class="hover:bg-slate-800/50 transition">
                            <td class="px-4 py-3 font-medium text-slate-400">
                                {{ $riwayat->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-white">{{ \Carbon\Carbon::parse($item->created_at)->timezone('Asia/Makassar')->translatedFormat('d M Y') }}</div>
                                <div class="text-[10px] text-slate-400 font-medium">{{ \Carbon\Carbon::parse($item->created_at)->timezone('Asia/Makassar')->format('H:i') }} WITA</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-slate-200">{{ $item->kendaraan->jenis_kendaraan ?? '-' }}</div>
                                <div class="text-[10px] font-black text-indigo-600 uppercase tracking-wider">{{ $item->kendaraan->no_polisi ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @php
                                    $isDex = stripos($item->jenis_bbm, 'dex') !== false;
                                    $badgeColor = $isDex ? 'bg-indigo-500/20 text-indigo-400 border-indigo-500/30' : 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 border {{ $badgeColor }} rounded-lg text-xs font-black">
                                    {{ $item->jumlah }} L <span class="text-[9px] font-black uppercase ml-1">{{ $item->jenis_bbm }}</span>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-xs text-slate-400 font-medium max-w-xs break-words">
                                    {{ $item->keterangan ?? '-' }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic font-medium">
                                Tidak ada riwayat saldo yang dialihkan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($riwayat->hasPages())
                <div class="px-4 py-3 border-t border-white/5">
                    {{ $riwayat->links() }}
                </div>
            @endif
        </div>
        
        <!-- Summary Box -->
        <div class="bg-slate-900 border border-white/5 p-3 sm:p-4 rounded-2xl border border-white/10 shadow-sm mt-6">
            <h3 class="text-xs font-semibold text-slate-200 mb-2">Total Liter Pemotongan (Data di tabel saat ini)</h3>
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1 bg-indigo-50 border border-indigo-100 p-3 rounded-xl flex items-center justify-between">
                    <span class="text-xs font-bold text-indigo-700 uppercase tracking-wider">Pertamax</span>
                    <span class="text-lg font-black text-indigo-900">{{ number_format($riwayat->where('jenis_bbm', 'Pertamax')->sum('jumlah'), 0, ',', '.') }} L</span>
                </div>
                <div class="flex-1 bg-teal-50 border border-teal-100 p-3 rounded-xl flex items-center justify-between">
                    <span class="text-xs font-bold text-teal-700 uppercase tracking-wider">Pertamina Dex</span>
                    <span class="text-lg font-black text-teal-900">{{ number_format($riwayat->where('jenis_bbm', 'Pertamina Dex')->sum('jumlah'), 0, ',', '.') }} L</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
