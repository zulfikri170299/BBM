<x-app-layout>
    <div class="py-6 px-3 sm:px-6 lg:px-8 min-h-screen">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Flash Message -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 dark:text-emerald-400 flex items-center justify-between shadow-lg shadow-emerald-500/5 animate-fade-in-down">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-xl bg-emerald-500/20 text-emerald-500 dark:text-emerald-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500/60 hover:text-emerald-500 p-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white/70 dark:bg-slate-900/60 backdrop-blur-xl p-4 sm:p-6 rounded-3xl border border-slate-200/80 dark:border-white/10 shadow-sm">
                <div class="flex items-center gap-3.5 sm:gap-4">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-purple-600 text-white flex items-center justify-center shadow-lg shadow-indigo-500/25 shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20 text-[10px] font-black uppercase tracking-widest mb-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                            Stok Fisik Tangki Pendam
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                            Input Stok Tangki (Deepstick)
                        </h1>
                        <p class="hidden sm:block text-xs text-slate-500 dark:text-slate-400 font-medium">
                            Pencatatan angka pengukuran tongkat ukur fisik BBM di tangki dispenser SPBP.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 self-start sm:self-auto px-3 py-1.5 rounded-2xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 text-xs font-bold shadow-inner">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ \Carbon\Carbon::now('Asia/Makassar')->translatedFormat('d M Y, H:i') }} WITA</span>
                </div>
            </div>

            <!-- Main Layout Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Column: Form Input Fisik -->
                <div class="lg:col-span-1">
                    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200/80 dark:border-white/10 rounded-3xl shadow-xl overflow-hidden lg:sticky lg:top-8 transition-all hover:border-slate-300 dark:hover:border-white/20">
                        
                        <!-- Form Header -->
                        <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-slate-800/40">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-base sm:text-lg font-black text-white">Form Input Fisik</h3>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Hasil ukur tongkat deepstick</p>
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20">
                                    WITA
                                </span>
                            </div>
                        </div>

                        <!-- Form Body -->
                        <form action="{{ route('petugas.sinkronisasi.store') }}" method="POST" class="p-5 sm:p-6 space-y-4 sm:space-y-5">
                            @csrf
                            
                            <!-- Input Pertamax -->
                            <div>
                                <label class="flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                        <span>Stok Pertamax</span>
                                    </span>
                                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-md bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                                        PERTAMAX
                                    </span>
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-blue-500 group-focus-within:text-blue-400 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                        </svg>
                                    </div>
                                    <input type="number" name="stok_awal_pertamax" step="1" min="0"
                                        class="w-full pl-11 pr-14 py-3 bg-slate-50 dark:bg-slate-800/80 border {{ $errors->has('stok_awal_pertamax') ? 'border-rose-500' : 'border-slate-200 dark:border-white/10' }} rounded-2xl focus:ring-4 focus:ring-blue-500/15 focus:border-blue-500 transition-all font-mono font-black text-slate-900 dark:text-white text-base sm:text-lg placeholder:text-slate-400"
                                        placeholder="0" value="{{ old('stok_awal_pertamax') }}">
                                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">LITER</span>
                                </div>
                                @error('stok_awal_pertamax')
                                    <p class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Input Pertamina Dex -->
                            <div>
                                <label class="flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                        <span>Stok Pertamina Dex</span>
                                    </span>
                                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-md bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                        P. DEX
                                    </span>
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-rose-500 group-focus-within:text-rose-400 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                        </svg>
                                    </div>
                                    <input type="number" name="stok_awal_dex" step="1" min="0"
                                        class="w-full pl-11 pr-14 py-3 bg-slate-50 dark:bg-slate-800/80 border {{ $errors->has('stok_awal_dex') ? 'border-rose-500' : 'border-slate-200 dark:border-white/10' }} rounded-2xl focus:ring-4 focus:ring-rose-500/15 focus:border-rose-500 transition-all font-mono font-black text-slate-900 dark:text-white text-base sm:text-lg placeholder:text-slate-400"
                                        placeholder="0" value="{{ old('stok_awal_dex') }}">
                                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">LITER</span>
                                </div>
                                @error('stok_awal_dex')
                                    <p class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Alert Box Glassmorphism -->
                            <div class="p-3.5 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 text-xs flex items-start gap-2.5">
                                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-[11px] leading-relaxed font-medium">
                                    Angka stok baru akan menggantikan stok lama dan mereset akumulasi pemakaian mulai waktu input ini.
                                </span>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-2">
                                <button type="submit" 
                                    data-confirm="Apakah Anda yakin data stok fisik sudah benar? Tindakan ini akan memperbarui stok awal dan mereset hitungan pemakaian."
                                    data-confirm-type="warning"
                                    class="w-full py-3.5 bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 hover:opacity-95 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg shadow-indigo-500/25 active:scale-[0.99] transition-all flex items-center justify-center gap-2 group">
                                    <svg class="w-4 h-4 transition-transform group-hover:-translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                    </svg>
                                    <span>Simpan Stok Fisik</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Riwayat Sinkronisasi Table -->
                <div class="lg:col-span-2">
                    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200/80 dark:border-white/10 rounded-3xl shadow-xl overflow-hidden transition-all hover:border-slate-300 dark:hover:border-white/20">
                        
                        <!-- Header Riwayat -->
                        <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-slate-800/40 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base sm:text-lg font-black text-white">Riwayat Sinkronisasi Tangki</h3>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Daftar input stok fisik dan rekonsiliasi pemakaian</p>
                                </div>
                            </div>
                            <span class="self-start sm:self-auto px-3 py-1 rounded-full text-[10px] font-extrabold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-white/10">
                                {{ $syncs->total() }} Catatan Log
                            </span>
                        </div>

                        <!-- Table Area -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50/70 dark:bg-slate-800/50 border-b border-slate-200 dark:border-white/10 text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                                        <th class="px-5 sm:px-6 py-3.5">Waktu &amp; Petugas</th>
                                        <th class="px-4 sm:px-5 py-3.5">Stok Awal Fisik</th>
                                        <th class="px-5 sm:px-6 py-3.5 text-right">Sisa Stok Akhir</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                    @forelse($syncs as $index => $sync)
                                        <!-- Pertamax Sub-Row -->
                                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors {{ $index === 0 ? 'bg-indigo-50/20 dark:bg-indigo-950/20' : '' }}">
                                            <td class="px-5 sm:px-6 py-4 align-top" rowspan="2">
                                                <div class="flex items-start gap-3">
                                                    <!-- Date Badge -->
                                                    <div class="p-2 sm:p-2.5 rounded-2xl bg-gradient-to-b from-slate-100 to-slate-200/80 dark:from-slate-800 dark:to-slate-850 border border-slate-200 dark:border-white/10 text-center shrink-0 shadow-sm min-w-[50px]">
                                                        <span class="block text-base sm:text-lg font-black font-mono text-white leading-tight">
                                                            {{ $sync->created_at->format('d') }}
                                                        </span>
                                                        <span class="block text-[9px] font-extrabold uppercase text-slate-500 dark:text-slate-400 mt-0.5">
                                                            {{ $sync->created_at->format('M Y') }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="flex items-center gap-1.5">
                                                            <span class="text-xs sm:text-sm font-black font-mono text-slate-800 dark:text-slate-100">
                                                                {{ $sync->created_at->format('H:i') }}
                                                            </span>
                                                            <span class="text-[10px] font-extrabold text-indigo-600 dark:text-indigo-400">WITA</span>
                                                            @if($index === 0)
                                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 text-[9px] font-extrabold">
                                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                                    Aktif
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="mt-1 flex items-center gap-1 text-[11px] font-bold text-slate-500 dark:text-slate-400">
                                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                            </svg>
                                                            <span>{{ $sync->petugas->name ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Pertamax Initial Stock -->
                                            <td class="px-4 sm:px-5 py-3 border-l border-slate-100 dark:border-white/5">
                                                <div class="flex items-center gap-2">
                                                    <span class="px-2 py-0.5 rounded-lg text-[9px] font-black uppercase bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20 shrink-0">
                                                        PERTAMAX
                                                    </span>
                                                    <span class="font-mono font-black text-sm sm:text-base text-white">
                                                        {{ number_format($sync->stok_awal_pertamax, 0, ',', '.') }} L
                                                    </span>
                                                </div>
                                            </td>

                                            <!-- Pertamax Remaining & Usage -->
                                            <td class="px-5 sm:px-6 py-3 text-right">
                                                <div class="font-mono font-black text-base sm:text-lg text-white">
                                                    {{ number_format($sync->sisa_pertamax, 0, ',', '.') }} L
                                                </div>
                                                <div class="inline-flex items-center gap-1 mt-0.5 px-2 py-0.5 rounded-md bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 text-[10px] font-bold">
                                                    <span>Keluar:</span>
                                                    <span class="font-mono font-black">{{ number_format($sync->pemakaian_pertamax, 0, ',', '.') }} L</span>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Dex Sub-Row -->
                                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors {{ $index === 0 ? 'bg-indigo-50/20 dark:bg-indigo-950/20' : '' }} border-b border-slate-200/80 dark:border-white/10">
                                            <!-- Dex Initial Stock -->
                                            <td class="px-4 sm:px-5 py-3 border-l border-slate-100 dark:border-white/5">
                                                <div class="flex items-center gap-2">
                                                    <span class="px-2 py-0.5 rounded-lg text-[9px] font-black uppercase bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 shrink-0">
                                                        P. DEX
                                                    </span>
                                                    <span class="font-mono font-black text-sm sm:text-base text-white">
                                                        {{ number_format($sync->stok_awal_dex, 0, ',', '.') }} L
                                                    </span>
                                                </div>
                                            </td>

                                            <!-- Dex Remaining & Usage -->
                                            <td class="px-5 sm:px-6 py-3 text-right">
                                                <div class="font-mono font-black text-base sm:text-lg text-white">
                                                    {{ number_format($sync->sisa_dex, 0, ',', '.') }} L
                                                </div>
                                                <div class="inline-flex items-center gap-1 mt-0.5 px-2 py-0.5 rounded-md bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 text-[10px] font-bold">
                                                    <span>Keluar:</span>
                                                    <span class="font-mono font-black">{{ number_format($sync->pemakaian_dex, 0, ',', '.') }} L</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-16 text-center">
                                                <div class="flex flex-col items-center">
                                                    <div class="w-14 h-14 bg-slate-100 dark:bg-slate-800/80 rounded-2xl flex items-center justify-center mb-3 text-slate-400 border border-slate-200 dark:border-white/10 shadow-sm">
                                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                        </svg>
                                                    </div>
                                                    <p class="text-slate-600 dark:text-slate-400 font-bold text-xs">Belum ada rekaman input stok fisik.</p>
                                                    <p class="text-[10px] text-slate-400 mt-0.5">Gunakan formulir di sebelah kiri untuk memasukkan hasil ukur tongkat pertama.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Footer -->
                        @if($syncs->hasPages())
                            <div class="px-6 py-4 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-slate-800/40">
                                {{ $syncs->links() }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
