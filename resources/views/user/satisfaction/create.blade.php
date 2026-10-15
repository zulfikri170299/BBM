<x-app-layout>
    <div class="p-3 sm:p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-900 p-5 sm:p-6 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white tracking-tight">
                            Indeks Kepuasan Layanan
                        </h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-500/10 text-amber-700 dark:text-amber-300 border border-amber-500/20">
                            Survei Harian
                        </span>
                    </div>
                    <p class="mt-0.5 text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                        Evaluasi kualitas dan mutu pelayanan pengisian serta penyaluran bahan bakar minyak Polda NTB.
                    </p>
                </div>
            </div>

            <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 text-xs font-bold text-slate-700 dark:text-slate-300 shadow-sm self-start sm:self-auto">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>

        <!-- Content Grid: Form (Left) & Insights/History (Right) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <!-- Left Column: Feedback Card Widget (2 cols on lg) -->
            <div class="lg:col-span-2">
                <x-satisfaction-widget :todaySatisfaction="$todaySatisfaction" :todayRating="$todayRating" />
            </div>

            <!-- Right Column: Survey Recap & User History (1 col on lg) -->
            <div class="space-y-6">
                <!-- Overall Community Satisfaction Stats -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-white/10 p-5 sm:p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-5 bg-amber-500 rounded-full"></div>
                            <h3 class="text-sm font-black text-slate-800 dark:text-white">Rekapitulasi Layanan</h3>
                        </div>
                        <span class="text-xs font-bold text-slate-400">{{ $stats['total'] }} Penilaian</span>
                    </div>

                    <div class="space-y-3">
                        <!-- Sangat Puas Progress -->
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="flex items-center gap-1.5 font-bold text-slate-700 dark:text-slate-300">
                                    <span>🤩</span> Sangat Puas
                                </span>
                                <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $stats['p_sangat_puas'] }}%</span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" style="width: {{ $stats['p_sangat_puas'] }}%"></div>
                            </div>
                        </div>

                        <!-- Puas Progress -->
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="flex items-center gap-1.5 font-bold text-slate-700 dark:text-slate-300">
                                    <span>🙂</span> Puas
                                </span>
                                <span class="font-bold text-amber-600 dark:text-amber-400">{{ $stats['p_puas'] }}%</span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                <div class="h-full bg-amber-500 rounded-full transition-all duration-500" style="width: {{ $stats['p_puas'] }}%"></div>
                            </div>
                        </div>

                        <!-- Tidak Puas Progress -->
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="flex items-center gap-1.5 font-bold text-slate-700 dark:text-slate-300">
                                    <span>😡</span> Tidak Puas
                                </span>
                                <span class="font-bold text-rose-600 dark:text-rose-400">{{ $stats['p_tidak_puas'] }}%</span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                <div class="h-full bg-rose-500 rounded-full transition-all duration-500" style="width: {{ $stats['p_tidak_puas'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Rating History -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-white/10 shadow-sm overflow-hidden flex flex-col">
                    <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-5 bg-brand-primary rounded-full"></div>
                            <h3 class="text-sm font-black text-slate-800 dark:text-white">Riwayat Penilaian Anda</h3>
                        </div>
                        <span class="text-[11px] font-bold text-slate-400">5 Terakhir</span>
                    </div>

                    <div class="divide-y divide-slate-100 dark:divide-white/5 overflow-y-auto max-h-[300px]">
                        @forelse($userRatings as $uRating)
                            <div class="p-3.5 hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-colors flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="text-2xl shrink-0">
                                        @if($uRating->rating == 3)
                                            🤩
                                        @elseif($uRating->rating == 2)
                                            🙂
                                        @else
                                            😡
                                        @endif
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">
                                            @if($uRating->rating == 3)
                                                Sangat Puas
                                            @elseif($uRating->rating == 2)
                                                Puas
                                            @else
                                                Tidak Puas
                                            @endif
                                        </p>
                                        @if($uRating->note)
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate italic">
                                                "{{ $uRating->note }}"
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <span class="text-[10px] font-mono text-slate-400 shrink-0">
                                    {{ $uRating->created_at->format('d/m/Y') }}
                                </span>
                            </div>
                        @empty
                            <div class="p-6 text-center text-xs text-slate-400">
                                Belum ada riwayat penilaian dari akun Anda.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
