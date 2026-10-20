<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-sm text-white shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Data BBM Pada Tangki</h1>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-0.5 flex items-center gap-2">
                        <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
                        Sinkronisasi Fisik Tangki (Pertamax & Dex)
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.laporan-stok-bbm.print', request()->all()) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white rounded-xl text-xs font-bold shadow-sm shadow-rose-600/20 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Cetak Laporan</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/40 p-4 rounded-xl shadow-sm flex items-center gap-3">
                <div class="p-1.5 bg-emerald-500 text-white rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="text-emerald-800 dark:text-emerald-300 font-semibold text-xs">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Input Form Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 bg-slate-50/70 dark:bg-slate-800/40 border-b border-slate-200 dark:border-white/10 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center border border-indigo-100 dark:border-indigo-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white">Input Stok Fisik Tangki</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Masukkan hasil pengukuran terbaru dari tongkat ukur tangki</p>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('admin.laporan-stok-bbm.store') }}" method="POST" class="p-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-4">
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            Stok Pertamax (Liter)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-blue-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>
                            <input type="number" name="stok_awal_pertamax" step="1" min="0" required
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800/60 border {{ $errors->has('stok_awal_pertamax') ? 'border-red-500' : 'border-slate-200 dark:border-white/10' }} rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm font-bold text-slate-800 dark:text-white placeholder:text-slate-400 transition"
                                placeholder="0" value="{{ old('stok_awal_pertamax') }}">
                        </div>
                    </div>

                    <div class="md:col-span-4">
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            Stok Pertamina DEX (Liter)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-rose-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>
                            <input type="number" name="stok_awal_dex" step="1" min="0" required
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800/60 border {{ $errors->has('stok_awal_dex') ? 'border-red-500' : 'border-slate-200 dark:border-white/10' }} rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm font-bold text-slate-800 dark:text-white placeholder:text-slate-400 transition"
                                placeholder="0" value="{{ old('stok_awal_dex') }}">
                        </div>
                    </div>

                    <div class="md:col-span-4">
                        <button type="submit" 
                            data-confirm="Apakah Anda yakin data stok sudah benar? Tindakan ini akan mereset nilai pemakaian mulai sekarang."
                            data-confirm-type="warning"
                            class="w-full h-[42px] px-5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl font-bold text-xs shadow-sm shadow-indigo-600/20 transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            <span>SIMPAN STOK FISIK</span>
                        </button>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mt-4 p-3.5 bg-rose-50 dark:bg-rose-950/30 rounded-xl border border-rose-200 dark:border-rose-900/40">
                        <ul class="list-disc list-inside text-xs font-semibold text-rose-600 dark:text-rose-400">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
        </div>

        <!-- Filter Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl shadow-sm p-4 sm:p-5">
            <form action="{{ route('admin.laporan-stok-bbm.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-4">
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Mulai Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="flatpickr w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm font-semibold text-slate-800 dark:text-white transition">
                </div>
                <div class="md:col-span-4">
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="flatpickr w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm font-semibold text-slate-800 dark:text-white transition">
                </div>
                <div class="md:col-span-4 flex items-center gap-2">
                    <button type="submit" class="flex-1 h-[42px] px-4 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs font-bold shadow-sm shadow-indigo-600/20 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <span>TAMPILKAN DATA</span>
                    </button>
                    <a href="{{ route('admin.laporan-stok-bbm.index') }}" class="h-[42px] px-4 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 active:scale-95 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-semibold border border-slate-200 dark:border-white/10 transition-all flex items-center justify-center">
                        RESET
                    </a>
                </div>
            </form>
        </div>

        <!-- Main Table -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 dark:bg-slate-800/50 border-b border-slate-200 dark:border-white/10">
                            <th class="py-3.5 px-4 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Waktu Input & Petugas</th>
                            <th class="py-3.5 px-4 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Stok Awal Fisik</th>
                            <th class="py-3.5 px-4 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">Pemakaian Sistem</th>
                            <th class="py-3.5 px-4 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">Sisa Stok Akhir</th>
                            @if(in_array(auth()->user()->role, ['super_admin', 'kasubbag']))
                            <th class="py-3.5 px-4 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($syncs as $index => $sync)
                            <!-- Pertamax Row -->
                            <tr class="group hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="px-4 py-3 align-top border-b border-slate-200 dark:border-white/10" rowspan="2">
                                    <div class="flex items-start gap-3">
                                        <div class="w-11 h-11 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-white/10 rounded-xl flex flex-col items-center justify-center shrink-0">
                                            <span class="text-sm font-black text-slate-800 dark:text-white leading-tight">{{ $sync->created_at->format('d') }}</span>
                                            <span class="text-[9px] font-bold text-slate-500 dark:text-slate-400 uppercase leading-none">{{ $sync->created_at->format('M Y') }}</span>
                                        </div>
                                        <div class="space-y-1">
                                            <div class="text-xs font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>{{ $sync->created_at->format('H:i') }} <span class="text-[10px] text-slate-400">WITA</span></span>
                                            </div>
                                            <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200/80 dark:border-white/5">
                                                <svg class="w-2.5 h-2.5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="truncate max-w-[130px]">{{ $sync->petugas->name ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2.5 border-l border-slate-100 dark:border-white/5">
                                    <div class="flex items-center justify-between gap-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20">
                                            PERTAMAX
                                        </span>
                                        <span class="text-xs font-bold text-slate-800 dark:text-white tabular-nums">
                                            {{ number_format($sync->stok_awal_pertamax, 0, ',', '.') }} L
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-2.5 text-center">
                                    <span class="text-xs font-bold text-rose-500 dark:text-rose-400 tabular-nums">
                                        {{ number_format($sync->pemakaian_pertamax, 0, ',', '.') }} L
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-center bg-slate-50/40 dark:bg-slate-800/20">
                                    <span class="text-xs font-bold text-slate-800 dark:text-white tabular-nums">
                                        {{ number_format($sync->sisa_pertamax, 0, ',', '.') }} L
                                    </span>
                                </td>
                                @if(in_array(auth()->user()->role, ['super_admin', 'kasubbag']))
                                <td class="px-4 py-3 text-center align-middle border-b border-slate-200 dark:border-white/10" rowspan="2">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('admin.laporan-stok-bbm.edit', $sync->id) }}" 
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-slate-100 hover:bg-indigo-50 dark:bg-slate-800 dark:hover:bg-indigo-950/40 text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 border border-slate-200 dark:border-white/10 rounded-lg text-[11px] font-semibold transition"
                                            title="Edit Data">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span>Edit</span>
                                        </a>
                                        <form action="{{ route('admin.laporan-stok-bbm.destroy', $sync->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                data-confirm="Hapus data sinkronisasi ini? Perhitungan pada data lainnya akan menyesuaikan secara otomatis."
                                                data-confirm-type="error"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-slate-100 hover:bg-rose-50 dark:bg-slate-800 dark:hover:bg-rose-950/40 text-slate-600 dark:text-slate-300 hover:text-rose-600 dark:hover:text-rose-400 border border-slate-200 dark:border-white/10 rounded-lg text-[11px] font-semibold transition"
                                                title="Hapus Data">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            <!-- Dex Row -->
                            <tr class="group hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors border-b border-slate-200 dark:border-white/10">
                                <td class="px-4 py-2.5 border-l border-slate-100 dark:border-white/5">
                                    <div class="flex items-center justify-between gap-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 border border-rose-200 dark:border-rose-500/20">
                                            P. DEX
                                        </span>
                                        <span class="text-xs font-bold text-slate-800 dark:text-white tabular-nums">
                                            {{ number_format($sync->stok_awal_dex, 0, ',', '.') }} L
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-2.5 text-center">
                                    <span class="text-xs font-bold text-rose-500 dark:text-rose-400 tabular-nums">
                                        {{ number_format($sync->pemakaian_dex, 0, ',', '.') }} L
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-center bg-slate-50/40 dark:bg-slate-800/20">
                                    <span class="text-xs font-bold text-slate-800 dark:text-white tabular-nums">
                                        {{ number_format($sync->sisa_dex, 0, ',', '.') }} L
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ in_array(auth()->user()->role, ['super_admin', 'kasubbag']) ? 5 : 4 }}" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800/60 rounded-2xl flex items-center justify-center mb-4 text-slate-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300">DATA TIDAK DITEMUKAN</h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Silakan sesuaikan filter tanggal di atas atau input stok baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($syncs->hasPages())
                <div class="px-5 py-4 border-t border-slate-200 dark:border-white/10 bg-slate-50/50 dark:bg-slate-800/40">
                    {{ $syncs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
