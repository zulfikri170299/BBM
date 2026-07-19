<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-4 sm:space-y-8">
        <!-- Header Section -->
        <div class="hidden md:flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-500/30 text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-white tracking-tight">Input Stok Tangki</h1>
                    <p class="text-slate-400 text-sm font-medium mt-1 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
                        Update Stok Fisik BBM Lapangan
                    </p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm flex items-center gap-3 animate-slide-in">
                <div class="p-2 bg-emerald-500 rounded-lg text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="text-emerald-800 font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Card -->
            <div class="lg:col-span-1">
                <div class="bg-slate-900 border border-white/5 rounded-3xl shadow-sm border border-white/10 overflow-hidden sticky top-8">
                    <div class="p-4 sm:p-8 bg-slate-800/50 border-b border-white/5">
                        <h3 class="text-lg sm:text-xl font-black text-white">Form Input Fisik</h3>
                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-1">Masukkan angka dari tongkat ukur</p>
                    </div>
                    <form action="{{ route('petugas.sinkronisasi.store') }}" method="POST" class="p-4 sm:p-8 space-y-4 sm:space-y-6">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Stok BBM di Tangki (Pertamax)</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none text-blue-500 group-focus-within:text-indigo-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                        </svg>
                                    </div>
                                    <input type="number" name="stok_awal_pertamax" step="1" min="0"
                                        class="w-full pl-10 sm:pl-12 pr-12 py-3 sm:py-4 bg-slate-800/50 border {{ $errors->has('stok_awal_pertamax') ? 'border-red-500' : 'border-white/10' }} rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-black text-white text-base sm:text-lg placeholder:text-slate-300"
                                        placeholder="0.00" value="{{ old('stok_awal_pertamax') }}">
                                    <span class="absolute right-3 sm:right-4 top-1/2 -translate-y-1/2 text-slate-400 font-black text-[10px]">LITER</span>
                                </div>
                                @error('stok_awal_pertamax')
                                    <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Stok BBM di Tangki (Dex)</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none text-rose-500 group-focus-within:text-indigo-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                        </svg>
                                    </div>
                                    <input type="number" name="stok_awal_dex" step="1" min="0"
                                        class="w-full pl-10 sm:pl-12 pr-12 py-3 sm:py-4 bg-slate-800/50 border {{ $errors->has('stok_awal_dex') ? 'border-red-500' : 'border-white/10' }} rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-black text-white text-base sm:text-lg placeholder:text-slate-300"
                                        placeholder="0.00" value="{{ old('stok_awal_dex') }}">
                                    <span class="absolute right-3 sm:right-4 top-1/2 -translate-y-1/2 text-slate-400 font-black text-[10px]">LITER</span>
                                </div>
                                @error('stok_awal_dex')
                                    <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="p-3 bg-amber-50 rounded-2xl border border-amber-100">
                                <p class="text-[9px] font-black text-amber-700 leading-relaxed uppercase tracking-wide italic text-center">
                                    * Data lama akan digantikan data baru. Hitungan pemakaian akan direset mulai waktu input ini.
                                </p>
                            </div>
                        </div>

                        <button type="submit" 
                            data-confirm="Apakah Anda yakin data stok sudah benar? Tindakan ini akan mereset nilai pemakaian."
                            data-confirm-type="warning"
                            class="w-full py-3.5 sm:py-4 bg-indigo-600 hover:bg-slate-900 text-white rounded-2xl font-black shadow-lg shadow-indigo-500/30 hover:shadow-slate-200 transition-all active:scale-[0.98] flex items-center justify-center gap-2 group">
                            <svg class="w-5 h-5 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            SIMPAN
                        </button>
                    </form>
                </div>
            </div>

            <!-- List Card -->
            <div class="lg:col-span-2">
                <div class="bg-slate-900 border border-white/5 rounded-3xl shadow-sm border border-white/10 overflow-hidden">
                    <div class="p-8 border-b border-white/5 flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-black text-white">Riwayat Sinkronisasi</h3>
                            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-1">Daftar input stok fisik terakhir</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white/5 backdrop-blur-sm">
                                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-white/5">Waktu & Petugas</th>
                                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-white/5 text-center">Stok Awal Fisik</th>
                                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-white/5 text-center">Sisa Stok Akhir</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($syncs as $index => $sync)
                                    <!-- Pertamax Row -->
                                    <tr class="group hover:bg-white/5 transition-colors {{ $index === 0 ? 'bg-indigo-900/20' : '' }}">
                                        <td class="px-8 py-6" rowspan="2">
                                            <div class="flex items-start gap-4">
                                                <div class="p-2.5 bg-white/10 border border-white/20 rounded-xl backdrop-blur-md shadow-lg shadow-black/20">
                                                    <div class="font-black text-white text-lg leading-none text-center">{{ $sync->created_at->format('d') }}</div>
                                                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-tighter text-center mt-1">{{ $sync->created_at->format('M Y') }}</div>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-black text-slate-200">{{ $sync->created_at->format('H:i') }} <span class="text-slate-400 font-bold">WITA</span></div>
                                                    <div class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                                        {{ $sync->petugas->name ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 border-l border-white/5">
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                                <span class="text-[9px] font-black text-blue-400 bg-blue-500/20 px-3 py-1 rounded-full border border-blue-500/30 shadow-[0_0_10px_rgba(59,130,246,0.2)] whitespace-nowrap">PERTAMAX</span>
                                                <span class="font-black text-white text-lg pl-2 border-l-2 border-white/5">{{ number_format($sync->stok_awal_pertamax, 0, ',', '.') }} L</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 text-center bg-white/[0.02]">
                                            <div class="font-black text-white text-xl">{{ number_format($sync->sisa_pertamax, 0, ',', '.') }} L</div>
                                            <div class="text-[9px] font-bold text-rose-400 border border-rose-500/30 uppercase tracking-widest mt-2 bg-rose-500/20 inline-block px-2 py-1 rounded-md">PEMAKAIAN: {{ number_format($sync->pemakaian_pertamax, 0, ',', '.') }} L</div>
                                        </td>
                                    </tr>
                                    <!-- Dex Row -->
                                    <tr class="group hover:bg-white/5 transition-colors {{ $index === 0 ? 'bg-indigo-900/20' : '' }} border-b border-white/5">
                                        <td class="px-8 py-6 border-l border-white/5">
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                                <span class="text-[9px] font-black text-rose-400 bg-rose-500/20 px-3 py-1 rounded-full border border-rose-500/30 shadow-[0_0_10px_rgba(244,63,94,0.2)] whitespace-nowrap">P. DEX</span>
                                                <span class="font-black text-white text-lg pl-2 border-l-2 border-white/5">{{ number_format($sync->stok_awal_dex, 0, ',', '.') }} L</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 text-center bg-white/[0.02]">
                                            <div class="font-black text-white text-xl">{{ number_format($sync->sisa_dex, 0, ',', '.') }} L</div>
                                            <div class="text-[9px] font-bold text-rose-400 border border-rose-500/30 uppercase tracking-widest mt-2 bg-rose-500/20 inline-block px-2 py-1 rounded-md">PEMAKAIAN: {{ number_format($sync->pemakaian_dex, 0, ',', '.') }} L</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-8 py-20 text-center">
                                            <div class="flex flex-col items-center">
                                                <div class="w-16 h-16 bg-slate-800/50 rounded-2xl flex items-center justify-center mb-4 text-slate-200">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                    </svg>
                                                </div>
                                                <p class="text-slate-400 font-black text-[10px] uppercase tracking-widest">Belum ada data input stok fisik.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($syncs->hasPages())
                        <div class="px-8 py-6 border-t border-white/5 bg-slate-800/50">
                            {{ $syncs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
