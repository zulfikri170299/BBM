<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-sm text-white shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">Laporan Top Up</h1>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-0.5">Riwayat dan rekapitulasi pengisian saldo BBM kendaraan dinas.</p>
                </div>
            </div>

            <!-- Action Button -->
            <a href="{{ route('admin.laporan-topup.print', request()->query()) }}" target="_blank"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white rounded-xl text-xs font-bold shadow-sm shadow-rose-600/20 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                <span>Cetak PDF</span>
            </a>
        </div>

        <!-- Summary KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Transaksi -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 p-4 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Transaksi</p>
                    <h3 class="text-xl font-black text-white mt-1">{{ number_format($riwayats->total(), 0, ',', '.') }}</h3>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Catatan pengisian saldo</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-100 dark:border-indigo-500/20 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>

            <!-- Total Volume BBM -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 p-4 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Top Up</p>
                    <h3 class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-1">
                        {{ number_format(abs($summary->sum()), 0, ',', '.') }} <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Liter</span>
                    </h3>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Akumulasi volume BBM</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-100 dark:border-emerald-500/20 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>

            <!-- Breakdown per BBM -->
            @forelse($summary as $jenis => $total)
                @php
                    $isPertamax = str_contains(strtoupper($jenis), 'PERTAMAX');
                    $badgeBg = $isPertamax ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-100 dark:border-blue-500/20' : 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-100 dark:border-amber-500/20';
                    $textColor = $isPertamax ? 'text-blue-600 dark:text-blue-400' : 'text-amber-600 dark:text-amber-400';
                @endphp
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 p-4 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ $jenis }}</p>
                        <h3 class="text-xl font-black {{ $textColor }} mt-1">
                            {{ number_format(abs($total), 0, ',', '.') }} <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Liter</span>
                        </h3>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Berdasarkan data filter</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl {{ $badgeBg }} flex items-center justify-center border shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                </div>
            @empty
                <div class="sm:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 p-4 shadow-sm flex items-center">
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Tidak ada rincian BBM pada periode yang dipilih.</p>
                </div>
            @endforelse
        </div>

        <!-- Filter Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm p-4 sm:p-5">
            <form action="{{ route('admin.laporan-topup.index') }}" method="GET">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <!-- Mulai Tanggal -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-0.5">
                            Mulai Tanggal
                        </label>
                        <div class="relative">
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="flatpickr w-full h-10 px-3.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-semibold text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        </div>
                    </div>

                    <!-- Sampai Tanggal -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-0.5">
                            Sampai Tanggal
                        </label>
                        <div class="relative">
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="flatpickr w-full h-10 px-3.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-semibold text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        </div>
                    </div>

                    <!-- Satker -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-0.5">
                            Satker
                        </label>
                        <select name="satker_id" id="filter_satker_id" class="tom-select w-full">
                            <option value="">Semua Satker</option>
                            @foreach($satkers as $satker)
                                <option value="{{ $satker->id }}" {{ request('satker_id') == $satker->id ? 'selected' : '' }}>
                                    {{ $satker->nama_satker }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2">
                        <button type="submit"
                            class="flex-1 h-10 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs font-bold shadow-sm shadow-indigo-600/20 transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('admin.laporan-topup.index') }}"
                            class="h-10 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 active:scale-95 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-bold border border-slate-200 dark:border-white/10 transition-all flex items-center justify-center gap-1.5"
                            title="Reset Filter">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Reset</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden">
            <!-- Table Header Info & Per-Page Toolbar -->
            <div class="px-5 py-4 bg-slate-50/70 dark:bg-slate-800/40 border-b border-slate-200 dark:border-white/10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center border border-emerald-100 dark:border-emerald-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-white">Riwayat Transaksi Top Up</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Menampilkan {{ $riwayats->firstItem() ?? 0 }}-{{ $riwayats->lastItem() ?? 0 }} dari total {{ $riwayats->total() }} catatan</p>
                    </div>
                </div>

                <!-- Per Page Selector Form -->
                <form action="{{ route('admin.laporan-topup.index') }}" method="GET" class="flex items-center">
                    <x-per-page :current="request('per_page', 15)" />
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/75 dark:bg-slate-800/50 border-b border-slate-200 dark:border-white/10">
                            <th scope="col" class="py-3.5 px-4 text-center text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider w-14">No</th>
                            <th scope="col" class="py-3.5 px-4 text-left text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tanggal & Waktu</th>
                            <th scope="col" class="py-3.5 px-4 text-left text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">User (Admin)</th>
                            <th scope="col" class="py-3.5 px-4 text-left text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Satker</th>
                            <th scope="col" class="py-3.5 px-4 text-left text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nopol</th>
                            <th scope="col" class="py-3.5 px-4 text-left text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Jenis Kendaraan</th>
                            <th scope="col" class="py-3.5 px-4 text-center text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Jenis BBM</th>
                            <th scope="col" class="py-3.5 px-4 text-center text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Metode</th>
                            <th scope="col" class="py-3.5 px-4 text-right text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Jumlah (Liter)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($riwayats as $riwayat)
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                                <!-- No -->
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold border border-slate-200/80 dark:border-white/5">
                                        {{ $loop->iteration + ($riwayats->currentPage() - 1) * $riwayats->perPage() }}
                                    </span>
                                </td>

                                <!-- Tanggal -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-xs font-bold text-slate-800 dark:text-white">
                                        {{ $riwayat->created_at->setTimezone('Asia/Makassar')->format('d/m/Y') }}
                                    </div>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400 font-medium mt-0.5">
                                        {{ $riwayat->created_at->setTimezone('Asia/Makassar')->format('H:i') }} WITA
                                    </div>
                                </td>

                                <!-- User Admin -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-500/20 flex items-center justify-center text-[11px] font-bold shrink-0">
                                            {{ strtoupper(substr($riwayat->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                            {{ $riwayat->user->name ?? '-' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Satker -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold border border-slate-200/80 dark:border-white/5">
                                        {{ $riwayat->satker->nama_satker ?? '-' }}
                                    </span>
                                </td>

                                <!-- Nopol -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-900 text-amber-300 dark:bg-slate-950 dark:text-amber-400 text-xs font-mono font-bold tracking-wider border border-slate-700">
                                        {{ $riwayat->kendaraan->no_polisi ?? '-' }}
                                    </span>
                                </td>

                                <!-- Jenis Kendaraan -->
                                <td class="px-4 py-3 whitespace-nowrap text-xs font-medium text-slate-700 dark:text-slate-300">
                                    {{ $riwayat->kendaraan->jenis_kendaraan ?? '-' }}
                                </td>

                                <!-- Jenis BBM -->
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    @php
                                        $bbm = strtoupper($riwayat->kendaraan->jenis_bbm ?? '');
                                        $isPtx = str_contains($bbm, 'PERTAMAX');
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[11px] font-bold {{ $isPtx ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20' : 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20' }}">
                                        {{ $riwayat->kendaraan->jenis_bbm ?? '-' }}
                                    </span>
                                </td>

                                <!-- Metode -->
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    @php
                                        $mtd = strtoupper($riwayat->metode ?? '');
                                        $mtdClass = match($mtd) {
                                            'RENDIS' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20',
                                            'IMPORT' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400 border-indigo-200 dark:border-indigo-500/20',
                                            default => 'bg-slate-100 text-white dark:bg-slate-800 dark:text-slate-300 border-slate-200 dark:border-white/10'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[10px] font-bold border {{ $mtdClass }}">
                                        {{ $riwayat->metode ?? '-' }}
                                    </span>
                                </td>

                                <!-- Jumlah -->
                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                    <div class="inline-flex items-baseline gap-1">
                                        <span class="text-sm font-black {{ $riwayat->tipe == 'masuk' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                            {{ number_format($riwayat->jumlah, 0, ',', '.') }}
                                        </span>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase">L</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800/60 rounded-2xl flex items-center justify-center mb-4 text-slate-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300">Belum ada riwayat top up</h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Gunakan filter di atas untuk menyesuaikan periode tanggal atau Satker.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($riwayats->hasPages())
                <div class="px-5 py-4 bg-slate-50/70 dark:bg-slate-800/40 border-t border-slate-200 dark:border-white/10">
                    {{ $riwayats->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>