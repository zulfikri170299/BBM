<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-500/30 text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-white tracking-tight">Data BBM Pada Tangki</h1>
                    <p class="text-slate-400 text-sm font-medium mt-1 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
                        Sinkronisasi Fisik Tangki (Pertamax & Dex)
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.laporan-stok-bbm.print', request()->all()) }}" target="_blank"
                    class="px-4 py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-bold shadow-lg shadow-rose-200 transition-all active:scale-[0.98] flex items-center gap-2 group">
                    <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak Laporan
                </a>
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

        <!-- Input Form Card (Restored & Cleaned) -->
        <div class="bg-slate-900 border border-white/5 rounded-3xl shadow-sm border border-white/10 overflow-hidden group mb-8">
            <div class="p-6 bg-slate-800/50 border-b border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl border border-indigo-100 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-200 tracking-tight leading-none">Input Stok Fisik Tangki</h3>
                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-1.5">Masukkan hASIL TERBARU DARI TONGKAT UKUR TANGKI</p>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('admin.laporan-stok-bbm.store') }}" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                    <div class="md:col-span-4">
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Stok Pertamax (Liter)</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-blue-500/80 group-focus-within:text-indigo-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>
                            <input type="number" name="stok_awal_pertamax" step="1" min="0" required
                                class="w-full pl-11 pr-12 py-3 bg-slate-800/50 border {{ $errors->has('stok_awal_pertamax') ? 'border-red-500' : 'border-white/10' }} rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-black text-slate-200 placeholder:text-slate-300"
                                placeholder="0.00" value="{{ old('stok_awal_pertamax') }}">
                        </div>
                    </div>

                    <div class="md:col-span-4">
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Stok P. DEX (Liter)</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-rose-500/80 group-focus-within:text-indigo-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>
                            <input type="number" name="stok_awal_dex" step="1" min="0" required
                                class="w-full pl-11 pr-12 py-3 bg-slate-800/50 border {{ $errors->has('stok_awal_dex') ? 'border-red-500' : 'border-white/10' }} rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-black text-slate-200 placeholder:text-slate-300"
                                placeholder="0.00" value="{{ old('stok_awal_dex') }}">
                        </div>
                    </div>

                    <div class="md:col-span-4">
                        <button type="submit" 
                            data-confirm="Apakah Anda yakin data stok sudah benar? Tindakan ini akan mereset nilai pemakaian mulai sekarang."
                            data-confirm-type="warning"
                            class="w-full py-3.5 bg-indigo-600 hover:bg-slate-900 text-white rounded-2xl font-black shadow-lg shadow-indigo-500/30 transition-all active:scale-[0.98] flex items-center justify-center gap-3 tracking-widest uppercase text-xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            SIMPAN STOK FISIK
                        </button>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mt-4 p-4 bg-red-50 rounded-2xl border border-red-100">
                        <ul class="list-disc list-inside text-xs font-bold text-red-600 uppercase tracking-widest italic">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
        </div>

        <!-- Filter Card -->
        <div class="bg-slate-900 border border-white/5 rounded-3xl shadow-sm border border-white/10 overflow-hidden relative group">
            <div class="absolute top-0 right-0 p-8 opacity-[0.03] group-hover:opacity-[0.05] transition-opacity">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
            </div>
            <form action="{{ route('admin.laporan-stok-bbm.index') }}" method="GET" class="p-8 grid grid-cols-1 md:grid-cols-12 gap-6 items-end relative z-10">
                <div class="md:col-span-4 space-y-2">
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Mulai Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="flatpickr w-full px-5 py-3 bg-slate-800/50 border border-white/10 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-200">
                </div>
                <div class="md:col-span-4 space-y-2">
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="flatpickr w-full px-5 py-3 bg-slate-800/50 border border-white/10 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-200">
                </div>
                <div class="md:col-span-4 flex gap-3">
                    <button type="submit" class="flex-1 py-3.5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl font-black transition-all active:scale-[0.98] shadow-lg shadow-slate-200 uppercase tracking-widest text-xs">
                        TAMPILKAN DATA
                    </button>
                    <a href="{{ route('admin.laporan-stok-bbm.index') }}" class="px-4 py-3 bg-slate-800 hover:bg-slate-200 text-slate-400 rounded-2xl font-black transition-all active:scale-[0.98] uppercase tracking-widest text-xs">
                        RESET
                    </a>
                </div>
            </form>
        </div>

        <!-- Main Table -->
        <div class="bg-slate-900 border border-white/5 rounded-3xl shadow-sm border border-white/10 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-800/50">
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-white/5">Waktu Input & Petugas</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-white/5 text-center">Stok Awal Fisik</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-white/5 text-center">Pemakaian Sistem</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-white/5 text-center">Sisa Stok Akhir</th>
                            @if(in_array(auth()->user()->role, ['super_admin', 'kasubbag']))
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-white/5 text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($syncs as $index => $sync)
                            <!-- Pertamax Row -->
                            <tr class="group hover:bg-indigo-50/30 transition-colors {{ $index % 2 == 0 ? 'bg-slate-900 border border-white/5' : 'bg-slate-800/50/20' }}">
                                <td class="px-8 py-6" rowspan="2">
                                    <div class="flex items-start gap-4">
                                        <div class="p-2.5 bg-slate-900 border border-white/5 border border-white/10 rounded-xl shadow-sm">
                                            <div class="font-black text-white text-lg leading-none">{{ $sync->created_at->format('d') }}</div>
                                            <div class="text-[9px] font-black text-slate-400 uppercase tracking-tighter text-center mt-1">{{ $sync->created_at->format('M Y') }}</div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-black text-slate-200">{{ $sync->created_at->format('H:i') }} <span class="text-slate-400 font-bold">WITA</span></div>
                                            <div class="mt-2 inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-black bg-indigo-50 text-indigo-600 border border-indigo-100 uppercase tracking-wider">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                                                {{ $sync->petugas->name ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 border-l border-white/5">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[9px] font-black text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-100">PERTAMAX</span>
                                        <span class="font-black text-white text-base">{{ number_format($sync->stok_awal_pertamax, 0, ',', '.') }} L</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="font-black text-rose-500">{{ number_format($sync->pemakaian_pertamax, 0, ',', '.') }} L</div>
                                </td>
                                <td class="px-8 py-6 text-center bg-slate-800/50/20">
                                    <div class="font-black text-white text-lg">{{ number_format($sync->sisa_pertamax, 0, ',', '.') }} L</div>
                                </td>
                                @if(in_array(auth()->user()->role, ['super_admin', 'kasubbag']))
                                <td class="px-8 py-6 text-center" rowspan="2">
                                    <div class="flex flex-col gap-2 items-center">
                                        <a href="{{ route('admin.laporan-stok-bbm.edit', $sync->id) }}" 
                                            class="w-full max-w-[100px] py-2 bg-slate-900 border border-white/5 border border-white/10 text-slate-400 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 rounded-xl font-black text-[10px] transition-all flex items-center justify-center gap-1.5 shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            EDIT
                                        </a>
                                        <form action="{{ route('admin.laporan-stok-bbm.destroy', $sync->id) }}" method="POST" class="w-full max-w-[100px]">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                data-confirm="Hapus data sinkronisasi ini? Perhitungan pada data lainnya akan menyesuaikan secara otomatis."
                                                data-confirm-type="error"
                                                class="w-full py-2 bg-slate-900 border border-white/5 border border-white/10 text-rose-600 hover:bg-rose-600 hover:text-white hover:border-rose-600 rounded-xl font-black text-[10px] transition-all flex items-center justify-center gap-1.5 shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                HAPUS
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            <!-- Dex Row -->
                            <tr class="group hover:bg-indigo-50/30 transition-colors {{ $index % 2 == 0 ? 'bg-slate-900 border border-white/5' : 'bg-slate-800/50/20' }} border-b border-white/5">
                                <td class="px-8 py-6 border-l border-white/5">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[9px] font-black text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-100">P. DEX</span>
                                        <span class="font-black text-white text-base">{{ number_format($sync->stok_awal_dex, 0, ',', '.') }} L</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="font-black text-rose-500">{{ number_format($sync->pemakaian_dex, 0, ',', '.') }} L</div>
                                </td>
                                <td class="px-8 py-6 text-center bg-slate-800/50/20">
                                    <div class="font-black text-white text-lg">{{ number_format($sync->sisa_dex, 0, ',', '.') }} L</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ in_array(auth()->user()->role, ['super_admin', 'kasubbag']) ? 5 : 4 }}" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-24 h-24 bg-slate-800/50 rounded-3xl flex items-center justify-center mb-6 text-slate-200">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-xl font-black text-slate-400 font-bold uppercase tracking-widest text-[10px]">DATA TIDAK DITEMUKAN</h4>
                                        <p class="text-slate-400 mt-2 font-bold uppercase tracking-widest text-[10px]">Silakan sesuaikan filter tanggal di atas.</p>
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
</x-app-layout>
