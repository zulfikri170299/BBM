<x-app-layout>
    @php
        $summary = $summary ?? collect();
        $totalSemuaLiter = $summary->sum() ?: $riwayat->sum('jumlah');
        $totalPertamax = $summary->get('Pertamax') ?? $riwayat->where('jenis_bbm', 'Pertamax')->sum('jumlah');
        $totalDex = $summary->get('Pertamina Dex') ?? $riwayat->where('jenis_bbm', 'Pertamina Dex')->sum('jumlah');
    @endphp

    <div class="p-4 sm:p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-900 p-5 sm:p-6 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 border border-amber-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white tracking-tight">Saldo Yang di Alihkan</h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-500/10 text-amber-700 dark:text-amber-300 border border-amber-500/20">
                            {{ number_format($riwayat->total()) }} Pemotongan
                        </span>
                    </div>
                    <p class="mt-0.5 text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                        Laporan pemotongan atau pengalihan saldo BBM kendaraan oleh Admin.
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2.5 self-stretch sm:self-auto">
                <a href="{{ route('satker.saldo-dialihkan.print', request()->query()) }}" target="_blank"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center px-4 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white rounded-xl font-bold text-xs sm:text-sm shadow-lg shadow-rose-600/20 hover:shadow-rose-600/30 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak PDF
                </a>
                <a href="{{ route('satker.kendaraans.index') }}"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-xs sm:text-sm transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- KPI Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Card 1: Total Saldo Dialihkan -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 p-5 shadow-sm relative overflow-hidden group hover:border-amber-500/40 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Saldo Dialihkan</span>
                    <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-1.5">
                    <span class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white tabular-nums">
                        {{ number_format($totalSemuaLiter, 0, ',', '.') }}
                    </span>
                    <span class="text-xs font-bold text-slate-400">Liter</span>
                </div>
                <div class="mt-3.5 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    <span>Total Transaksi</span>
                    <span class="font-bold text-amber-600 dark:text-amber-400">{{ number_format($riwayat->total()) }} Pemotongan</span>
                </div>
            </div>

            <!-- Card 2: Dialihkan Pertamax -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 p-5 shadow-sm relative overflow-hidden group hover:border-blue-500/40 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Dialihkan Pertamax</span>
                    <div class="w-9 h-9 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-1.5">
                    <span class="text-2xl sm:text-3xl font-black text-blue-600 dark:text-blue-400 tabular-nums">
                        {{ number_format($totalPertamax, 0, ',', '.') }}
                    </span>
                    <span class="text-xs font-bold text-slate-400">Liter</span>
                </div>
                <div class="mt-3.5 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    <span>Jenis BBM</span>
                    <span class="inline-flex items-center gap-1 font-bold text-blue-600 dark:text-blue-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Pertamax
                    </span>
                </div>
            </div>

            <!-- Card 3: Dialihkan Pertamina Dex -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 p-5 shadow-sm relative overflow-hidden group hover:border-emerald-500/40 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Dialihkan Pertamina Dex</span>
                    <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-1.5">
                    <span class="text-2xl sm:text-3xl font-black text-emerald-600 dark:text-emerald-400 tabular-nums">
                        {{ number_format($totalDex, 0, ',', '.') }}
                    </span>
                    <span class="text-xs font-bold text-slate-400">Liter</span>
                </div>
                <div class="mt-3.5 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    <span>Jenis BBM</span>
                    <span class="inline-flex items-center gap-1 font-bold text-emerald-600 dark:text-emerald-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Pertamina Dex
                    </span>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm p-4 sm:p-5">
            <form action="{{ route('satker.saldo-dialihkan.index') }}" method="GET"
                class="flex flex-col sm:flex-row items-stretch sm:items-end gap-3.5">
                <div class="flex-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Mulai Tanggal
                        </span>
                    </label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" placeholder="Pilih Mulai Tanggal"
                        class="flatpickr w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-brand-primary transition-all">
                </div>

                <div class="flex-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Sampai Tanggal
                        </span>
                    </label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" placeholder="Pilih Sampai Tanggal"
                        class="flatpickr w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-brand-primary transition-all">
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="px-5 py-2.5 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-xl font-bold text-xs transition flex items-center justify-center gap-1.5 shadow-sm active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('satker.saldo-dialihkan.index') }}"
                        class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-xs transition flex items-center justify-center gap-1.5 active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden">
            <!-- Table Header Info & Toolbar -->
            <div class="px-5 py-4 border-b border-slate-200 dark:border-white/10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-black text-slate-800 dark:text-white">Riwayat Pemotongan Saldo</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Total {{ number_format($riwayat->total()) }} transaksi tercatat.</p>
                    </div>
                </div>

                <!-- Per Page Toolbar & Range Count -->
                <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">
                    <form action="{{ route('satker.saldo-dialihkan.index') }}" method="GET" class="flex items-center gap-2">
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Tampilkan:</span>
                        <x-per-page :current="request('per_page', 15)" />
                    </form>
                    <div class="text-[11px] font-bold text-slate-500 dark:text-slate-400">
                        Menampilkan {{ $riwayat->firstItem() ?? 0 }}-{{ $riwayat->lastItem() ?? 0 }} dari {{ $riwayat->total() }} data
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 uppercase tracking-wider text-[10px] font-black">
                            <th class="px-4 py-3 text-center w-12">NO</th>
                            <th class="px-4 py-3">TANGGAL</th>
                            <th class="px-4 py-3">KENDARAAN & NOPOL</th>
                            <th class="px-4 py-3 text-center">JENIS BBM</th>
                            <th class="px-4 py-3 text-right">LITER</th>
                            <th class="px-4 py-3">KETERANGAN</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5 bg-white dark:bg-slate-900">
                        @forelse($riwayat as $index => $item)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                <!-- No -->
                                <td class="px-4 py-3.5 text-center font-mono font-bold text-slate-400 text-xs">
                                    {{ $riwayat->firstItem() + $index }}
                                </td>

                                <!-- Tanggal -->
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <div class="font-bold text-slate-800 dark:text-slate-200 text-xs">
                                        {{ \Carbon\Carbon::parse($item->created_at)->timezone('Asia/Makassar')->translatedFormat('d M Y') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-mono mt-0.5">
                                        {{ \Carbon\Carbon::parse($item->created_at)->timezone('Asia/Makassar')->format('H:i') }} WITA
                                    </div>
                                </td>

                                <!-- Kendaraan & Nopol -->
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20 flex items-center justify-center shrink-0">
                                            <i class="fas fa-car-side text-xs"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs font-mono font-black text-blue-600 dark:text-blue-400 uppercase tracking-wider">
                                                {{ $item->kendaraan->no_polisi ?? '-' }}
                                            </div>
                                            <div class="text-[11px] font-bold text-slate-700 dark:text-slate-300">
                                                {{ $item->kendaraan->jenis_kendaraan ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Jenis BBM -->
                                <td class="px-4 py-3.5 whitespace-nowrap text-center">
                                    @php
                                        $jBbm = $item->jenis_bbm ?? ($item->kendaraan->jenis_bbm ?? '-');
                                    @endphp
                                    @if(stripos($jBbm, 'dex') !== false)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            {{ $jBbm }}
                                        </span>
                                    @elseif(stripos($jBbm, 'pertamax') !== false)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            {{ $jBbm }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-white/10">
                                            {{ $jBbm }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Liter -->
                                <td class="px-4 py-3.5 whitespace-nowrap text-right">
                                    <span class="font-mono font-black text-sm text-amber-600 dark:text-amber-400 tabular-nums">
                                        -{{ number_format($item->jumlah, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[10px] font-bold text-slate-400 ml-0.5">L</span>
                                </td>

                                <!-- Keterangan -->
                                <td class="px-4 py-3.5">
                                    <div class="text-xs text-slate-600 dark:text-slate-400 line-clamp-1 max-w-md" title="{{ $item->keterangan ?? '-' }}">
                                        {{ $item->keterangan ?? '-' }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-14 h-14 bg-slate-100 dark:bg-slate-800 text-slate-400 rounded-2xl flex items-center justify-center mb-3">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Belum ada riwayat saldo yang dialihkan</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Tidak ada data pemotongan saldo kendaraan pada filter saat ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            @if($riwayat->hasPages())
                <div class="px-5 py-3.5 bg-slate-50/50 dark:bg-slate-800/30 border-t border-slate-200 dark:border-white/10">
                    {{ $riwayat->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
