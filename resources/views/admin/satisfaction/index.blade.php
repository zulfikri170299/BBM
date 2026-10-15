<x-app-layout>
    <div class="p-3 sm:p-6 lg:p-8 space-y-6">
        <!-- Page Title & Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white dark:bg-slate-900 p-5 sm:p-6 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-500/20 to-amber-600/10 text-amber-500 border border-amber-500/30 flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white tracking-tight">
                            Indeks Kepuasan Layanan
                        </h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                            Evaluasi Pelayanan
                        </span>
                        @if($bulan && $bulan !== 'all')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800/40">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                {{ $daftarBulan[(int)$bulan] ?? 'Bulan ' . $bulan }}
                            </span>
                        @endif
                    </div>
                    <p class="mt-0.5 text-xs sm:text-sm text-slate-500 dark:text-slate-400 truncate sm:whitespace-normal">
                        Laporan dan rekapitulasi penilaian kepuasan mutu distribusi BBM dari seluruh Satker & Personel.
                    </p>
                </div>
            </div>

            <!-- Actions: Filter Bulan & Cetak Laporan (Selalu sejajar horizontal di samping) -->
            <div class="flex items-center gap-2 shrink-0 flex-nowrap self-start md:self-auto">
                <!-- Custom Dropdown Filter Bulan (Alpine.js) -->
                @php
                    $activeBulanLabel = ($bulan && isset($daftarBulan[(int)$bulan])) 
                        ? 'Bulan ' . $daftarBulan[(int)$bulan] 
                        : 'Semua Bulan (' . session('filter_tahun', date('Y')) . ')';
                @endphp
                <div x-data="{ open: false }" class="relative shrink-0">
                    <button @click="open = !open" type="button"
                        class="inline-flex items-center justify-between gap-2 px-3 py-2 text-xs font-bold rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/90 dark:hover:bg-slate-800 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30 transition-all cursor-pointer shrink-0 whitespace-nowrap">
                        <div class="flex items-center gap-1.5 truncate">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="truncate">{{ $activeBulanLabel }}</span>
                        </div>
                        <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu List -->
                    <div x-show="open" @click.away="open = false"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="transform opacity-0 scale-95 -translate-y-1"
                        x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="transform opacity-0 scale-95 -translate-y-1"
                        class="absolute left-0 xl:right-0 xl:left-auto mt-2 z-50 w-56 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 shadow-2xl ring-1 ring-black/5 overflow-hidden focus:outline-none"
                        style="display: none;">
                        
                        <div class="px-3.5 py-2.5 border-b border-slate-100 dark:border-white/5 bg-slate-50/70 dark:bg-slate-800/50 flex items-center justify-between">
                            <span class="text-[11px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">Pilih Periode Bulan</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-200/60 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300">{{ session('filter_tahun', date('Y')) }}</span>
                        </div>

                        <div class="p-1.5 max-h-64 overflow-y-auto space-y-0.5 custom-scrollbar">
                            <!-- Opsi Semua Bulan -->
                            <a href="{{ route('admin.satisfaction.index', array_merge(request()->except(['bulan', 'page']), ['bulan' => 'all'])) }}"
                                class="flex items-center justify-between px-3 py-2 rounded-xl text-xs transition-colors {{ ($bulan === 'all' || !$bulan) ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400 font-extrabold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 font-medium' }}">
                                <span class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full {{ ($bulan === 'all' || !$bulan) ? 'bg-amber-500' : 'bg-transparent' }}"></span>
                                    Semua Bulan ({{ session('filter_tahun', date('Y')) }})
                                </span>
                                @if($bulan === 'all' || !$bulan)
                                    <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                            </a>

                            <div class="h-px bg-slate-100 dark:bg-white/5 my-1"></div>

                            <!-- Opsi Bulan 1 - 12 -->
                            @foreach($daftarBulan as $num => $nama)
                                @php
                                    $isSelected = ((string)$bulan === (string)$num);
                                @endphp
                                <a href="{{ route('admin.satisfaction.index', array_merge(request()->except(['bulan', 'page']), ['bulan' => $num])) }}"
                                    class="flex items-center justify-between px-3 py-2 rounded-xl text-xs transition-colors {{ $isSelected ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400 font-extrabold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 font-medium' }}">
                                    <span class="flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $isSelected ? 'bg-amber-500' : 'bg-transparent' }}"></span>
                                        Bulan {{ $nama }}
                                    </span>
                                    @if($isSelected)
                                        <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if($bulan && $bulan !== 'all')
                    <a href="{{ route('admin.satisfaction.index', array_filter(['per_page' => request('per_page')])) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-rose-500 hover:text-rose-600 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/50 border border-rose-200 dark:border-rose-900/50 rounded-xl transition shrink-0 whitespace-nowrap"
                       title="Reset Filter Bulan">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>Reset</span>
                    </a>
                @endif

                <!-- Tombol Cetak Laporan -->
                <a href="{{ route('admin.satisfaction.print', request()->all()) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-3.5 sm:px-4 py-2 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white rounded-xl text-xs font-bold shadow-sm shadow-rose-600/25 transition-all shrink-0 whitespace-nowrap"
                    title="Cetak Laporan Rekapitulasi Indeks Kepuasan">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak Laporan</span>
                </a>
            </div>
        </div>

        @php
            $totalResp = $stats['total'] > 0 ? $stats['total'] : 1;
            $pctSangatPuas = $stats['total'] > 0 ? round(($stats['sangat_puas'] / $totalResp) * 100, 1) : 0;
            $pctPuas = $stats['total'] > 0 ? round(($stats['puas'] / $totalResp) * 100, 1) : 0;
            $pctTidakPuas = $stats['total'] > 0 ? round(($stats['tidak_puas'] / $totalResp) * 100, 1) : 0;
        @endphp

        <!-- Stats Cards (KPI) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
            <!-- Total Responden -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 p-4 sm:p-5 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Total Responden
                    </span>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 border border-indigo-200/50 dark:border-indigo-800/40">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-800 dark:text-white tabular-nums">
                    {{ number_format($stats['total'], 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Responden terdata dalam sistem</p>
            </div>

            <!-- Sangat Puas -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 hover:border-emerald-500/40 p-4 sm:p-5 rounded-2xl shadow-sm hover:shadow-md transition-all relative overflow-hidden group">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] sm:text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Sangat Puas
                    </span>
                    <span class="text-2xl">🤩</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl sm:text-3xl font-extrabold text-slate-800 dark:text-white tabular-nums">
                        {{ $stats['sangat_puas'] }}
                    </span>
                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">
                        ({{ $pctSangatPuas }}%)
                    </span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full mt-3 overflow-hidden">
                    <div class="bg-emerald-500 h-full rounded-full transition-all duration-500" style="width: {{ $pctSangatPuas }}%;"></div>
                </div>
            </div>

            <!-- Puas -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 hover:border-amber-500/40 p-4 sm:p-5 rounded-2xl shadow-sm hover:shadow-md transition-all relative overflow-hidden group">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] sm:text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span> Puas
                    </span>
                    <span class="text-2xl">🙂</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl sm:text-3xl font-extrabold text-slate-800 dark:text-white tabular-nums">
                        {{ $stats['puas'] }}
                    </span>
                    <span class="text-xs font-bold text-amber-600 dark:text-amber-400">
                        ({{ $pctPuas }}%)
                    </span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full mt-3 overflow-hidden">
                    <div class="bg-amber-500 h-full rounded-full transition-all duration-500" style="width: {{ $pctPuas }}%;"></div>
                </div>
            </div>

            <!-- Tidak Puas -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 hover:border-rose-500/40 p-4 sm:p-5 rounded-2xl shadow-sm hover:shadow-md transition-all relative overflow-hidden group">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] sm:text-xs font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span> Tidak Puas
                    </span>
                    <span class="text-2xl">😡</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl sm:text-3xl font-extrabold text-slate-800 dark:text-white tabular-nums">
                        {{ $stats['tidak_puas'] }}
                    </span>
                    <span class="text-xs font-bold text-rose-600 dark:text-rose-400">
                        ({{ $pctTidakPuas }}%)
                    </span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full mt-3 overflow-hidden">
                    <div class="bg-rose-500 h-full rounded-full transition-all duration-500" style="width: {{ $pctTidakPuas }}%;"></div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-white/10 shadow-sm overflow-hidden">
            <!-- Table Header Bar -->
            <div class="p-4 sm:p-6 border-b border-slate-200 dark:border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-2.5 h-6 bg-brand-primary rounded-full"></div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white">
                            Riwayat Penilaian Pelayanan
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Daftar responden penilaian mutu BBM dari Satker dan Personel
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 self-end sm:self-auto">
                    <form action="{{ route('admin.satisfaction.index') }}" method="GET" class="flex items-center">
                        @if(request('bulan'))
                            <input type="hidden" name="bulan" value="{{ request('bulan') }}">
                        @endif
                        <x-per-page :current="request('per_page', 15)" />
                    </form>
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10">
                        {{ $ratings->total() }} Total
                    </span>
                </div>
            </div>

            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-white/10 text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800/60 uppercase font-black tracking-wider text-slate-500 dark:text-slate-400">
                        <tr>
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5">Responden</th>
                            <th class="px-5 py-3.5">Role</th>
                            <th class="px-5 py-3.5 text-center">Tingkat Kepuasan</th>
                            <th class="px-5 py-3.5">Catatan / Saran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-white/10 bg-white dark:bg-slate-900">
                        @forelse($ratings as $rating)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                <!-- Tanggal -->
                                <td class="px-5 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300 font-medium">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>{{ $rating->created_at->format('d M Y, H:i') }} WITA</span>
                                    </div>
                                </td>

                                <!-- User & Satker -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white font-extrabold text-xs shadow-sm shrink-0">
                                            {{ strtoupper(substr($rating->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-bold text-slate-900 dark:text-white truncate max-w-xs">
                                                {{ $rating->user->name ?? 'User Terhapus' }}
                                            </div>
                                            @if($rating->user && $rating->user->satker)
                                                <div class="text-[11px] text-slate-500 dark:text-slate-400 truncate">
                                                    {{ $rating->user->satker->nama_satker }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Role -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-white/10">
                                        {{ $rating->user->role_label ?? '-' }}
                                    </span>
                                </td>

                                <!-- Rating -->
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    @if($rating->rating == '3')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">
                                            <span>🤩</span> Sangat Puas
                                        </span>
                                    @elseif($rating->rating == '2')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/10 text-amber-700 dark:text-amber-300 border border-amber-500/30">
                                            <span>🙂</span> Puas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-500/10 text-rose-700 dark:text-rose-300 border border-rose-500/30">
                                            <span>😡</span> Tidak Puas
                                        </span>
                                    @endif
                                </td>

                                <!-- Catatan -->
                                <td class="px-5 py-4 text-slate-700 dark:text-slate-300 max-w-xs">
                                    @if($rating->note)
                                        <div class="italic bg-slate-50 dark:bg-slate-800/60 p-2 rounded-xl border border-slate-200/60 dark:border-white/5 line-clamp-2" title="{{ $rating->note }}">
                                            "{{ $rating->note }}"
                                        </div>
                                    @else
                                        <span class="text-slate-400 font-mono">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-14 h-14 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-3 text-slate-400">
                                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </div>
                                        <p class="font-bold text-slate-600 dark:text-slate-300 text-sm">Belum Ada Data Penilaian</p>
                                        <p class="text-xs text-slate-400 mt-1">Data survei indeks kepuasan akan otomatis terisi saat responden memberikan penilaian.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            @if($ratings->hasPages())
                <div class="p-4 border-t border-slate-200 dark:border-white/10 bg-slate-50/50 dark:bg-slate-800/30">
                    {{ $ratings->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>