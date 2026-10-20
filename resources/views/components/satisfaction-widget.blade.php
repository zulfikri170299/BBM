@props(['todaySatisfaction' => false, 'todayRating' => null])

<div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-white/10 p-6 sm:p-7 shadow-sm relative overflow-hidden">
    <!-- Subtle Ambient Glow (Dark Mode Compatible) -->
    <div class="absolute -top-12 -right-12 w-48 h-48 bg-amber-500/10 dark:bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-12 -left-12 w-40 h-40 bg-brand-primary/5 dark:bg-brand-primary/10 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="relative z-10">
        <!-- Widget Header -->
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-amber-500/10 text-amber-500 border border-amber-500/20 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight">
                        Indeks Kepuasan Layanan
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Bagaimana kualitas pelayanan pengisian dan distribusi BBM kami hari ini?
                    </p>
                </div>
            </div>

            @if($todaySatisfaction)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Terisi Hari Ini
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Menunggu Penilaian
                </span>
            @endif
        </div>

        @if($todaySatisfaction)
            <!-- State: Already submitted today -->
            <div class="rounded-2xl bg-emerald-500/10 border border-emerald-500/20 p-6 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-2xl mb-3 shadow-inner">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h4 class="text-base font-black text-slate-900 dark:text-white">Terima Kasih Atas Penilaian Anda!</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-md mx-auto">
                    Masukan dan penilaian Anda telah berhasil kami simpan dan sangat berharga dalam meningkatkan mutu pelayanan logistik BBM Polda NTB.
                </p>

                @if($todayRating)
                    <div class="mt-4 pt-4 border-t border-emerald-500/20 inline-flex flex-wrap items-center justify-center gap-2">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Penilaian Anda:</span>
                        @if($todayRating->rating == 3)
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 font-bold text-xs">
                                <span>🤩</span> SANGAT PUAS
                            </span>
                        @elseif($todayRating->rating == 2)
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl bg-amber-500/20 text-amber-700 dark:text-amber-300 font-bold text-xs">
                                <span>🙂</span> PUAS
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl bg-rose-500/20 text-rose-700 dark:text-rose-300 font-bold text-xs">
                                <span>😡</span> TIDAK PUAS
                            </span>
                        @endif

                        @if($todayRating->note)
                            <span class="text-xs text-slate-600 dark:text-slate-300 italic">"{{ $todayRating->note }}"</span>
                        @endif
                    </div>
                @endif
            </div>
        @else
            <!-- State: Form ready to submit -->
            <form action="{{ route('satisfaction.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <!-- Sangat Puas -->
                    <label class="cursor-pointer group relative">
                        <input type="radio" name="rating" value="3" class="peer sr-only" required>
                        <div class="flex flex-col items-center justify-center p-4 rounded-2xl border-2 border-slate-200/80 dark:border-white/10 bg-slate-50/50 dark:bg-slate-800/40 hover:border-emerald-500 hover:bg-emerald-500/5 peer-checked:border-emerald-500 peer-checked:bg-emerald-500/10 peer-checked:shadow-lg peer-checked:shadow-emerald-500/10 transition-all duration-200">
                            <span class="text-4xl transition-transform transform group-hover:scale-125 peer-checked:scale-125 duration-200">🤩</span>
                            <span class="text-xs font-black uppercase tracking-wider text-slate-600 dark:text-slate-400 mt-2.5 group-hover:text-emerald-600 peer-checked:text-emerald-600 dark:peer-checked:text-emerald-400">
                                SANGAT PUAS
                            </span>
                            <span class="text-[10px] text-slate-400 mt-0.5">Layanan Luar Biasa</span>
                        </div>
                    </label>

                    <!-- Puas -->
                    <label class="cursor-pointer group relative">
                        <input type="radio" name="rating" value="2" class="peer sr-only">
                        <div class="flex flex-col items-center justify-center p-4 rounded-2xl border-2 border-slate-200/80 dark:border-white/10 bg-slate-50/50 dark:bg-slate-800/40 hover:border-amber-500 hover:bg-amber-500/5 peer-checked:border-amber-500 peer-checked:bg-amber-500/10 peer-checked:shadow-lg peer-checked:shadow-amber-500/10 transition-all duration-200">
                            <span class="text-4xl transition-transform transform group-hover:scale-125 peer-checked:scale-125 duration-200">🙂</span>
                            <span class="text-xs font-black uppercase tracking-wider text-slate-600 dark:text-slate-400 mt-2.5 group-hover:text-amber-600 peer-checked:text-amber-600 dark:peer-checked:text-amber-400">
                                PUAS
                            </span>
                            <span class="text-[10px] text-slate-400 mt-0.5">Layanan Sesuai Harapan</span>
                        </div>
                    </label>

                    <!-- Tidak Puas -->
                    <label class="cursor-pointer group relative">
                        <input type="radio" name="rating" value="1" class="peer sr-only">
                        <div class="flex flex-col items-center justify-center p-4 rounded-2xl border-2 border-slate-200/80 dark:border-white/10 bg-slate-50/50 dark:bg-slate-800/40 hover:border-rose-500 hover:bg-rose-500/5 peer-checked:border-rose-500 peer-checked:bg-rose-500/10 peer-checked:shadow-lg peer-checked:shadow-rose-500/10 transition-all duration-200">
                            <span class="text-4xl transition-transform transform group-hover:scale-125 peer-checked:scale-125 duration-200">😡</span>
                            <span class="text-xs font-black uppercase tracking-wider text-slate-600 dark:text-slate-400 mt-2.5 group-hover:text-rose-600 peer-checked:text-rose-600 dark:peer-checked:text-rose-400">
                                TIDAK PUAS
                            </span>
                            <span class="text-[10px] text-slate-400 mt-0.5">Perlu Evaluasi Perbaikan</span>
                        </div>
                    </label>
                </div>

                <!-- Catatan Opsional -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                        Catatan & Masukan (Opsional)
                    </label>
                    <input type="text" name="note" placeholder="Tuliskan saran atau masukan untuk petugas kami..."
                        class="w-full text-xs font-medium bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 text-white rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-brand-primary p-3 transition-all outline-none placeholder:text-slate-400">
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-brand-primary hover:bg-brand-primary/90 text-white font-bold py-3 px-5 rounded-xl shadow-lg shadow-brand-primary/20 hover:shadow-brand-primary/30 transition-all active:scale-95 text-xs sm:text-sm flex items-center justify-center gap-2">
                    <span>Kirim Penilaian Hari Ini</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>
        @endif
    </div>
</div>
