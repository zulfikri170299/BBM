<x-app-layout>
    <div class="max-w-7xl mx-auto p-3 sm:p-6 lg:p-8 space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Daftar Hutang BBM Satker</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Pantau riwayat hutang / BON BBM armada kendaraan yang belum lunas dan yang telah terpotong otomatis.</p>
            </div>
        </div>

        <!-- Summary Statistics Cards -->
        @php
            $pertamaxBelum = $belumDibayar['Pertamax'] ?? 0;
            $pertamaxSudah = $sudahDibayar['Pertamax'] ?? 0;
            $dexBelum = $belumDibayar['Pertamina Dex'] ?? 0;
            $dexSudah = $sudahDibayar['Pertamina Dex'] ?? 0;
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
            <!-- Belum Dibayar Pertamax -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl p-4 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[9px] font-black uppercase tracking-wider text-slate-400">Belum Bayar (Pertamax)</span>
                    <div class="text-2xl font-black text-rose-600 dark:text-rose-400 mt-1">
                        {{ rtrim(rtrim(number_format($pertamaxBelum, 2, ',', '.'), '0'), ',') ?: '0' }} <span class="text-xs font-bold text-slate-400">L</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center border border-rose-100 dark:border-rose-900/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <!-- Sudah Dibayar Pertamax -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl p-4 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[9px] font-black uppercase tracking-wider text-slate-400">Lunas (Pertamax)</span>
                    <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">
                        {{ rtrim(rtrim(number_format($pertamaxSudah, 2, ',', '.'), '0'), ',') ?: '0' }} <span class="text-xs font-bold text-slate-400">L</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-100 dark:border-emerald-900/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <!-- Belum Dibayar Pertamina Dex -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl p-4 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[9px] font-black uppercase tracking-wider text-slate-400">Belum Bayar (Dex)</span>
                    <div class="text-2xl font-black text-rose-600 dark:text-rose-400 mt-1">
                        {{ rtrim(rtrim(number_format($dexBelum, 2, ',', '.'), '0'), ',') ?: '0' }} <span class="text-xs font-bold text-slate-400">L</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center border border-rose-100 dark:border-rose-900/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <!-- Sudah Dibayar Pertamina Dex -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl p-4 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[9px] font-black uppercase tracking-wider text-slate-400">Lunas (Dex)</span>
                    <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">
                        {{ rtrim(rtrim(number_format($dexSudah, 2, ',', '.'), '0'), ',') ?: '0' }} <span class="text-xs font-bold text-slate-400">L</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-100 dark:border-emerald-900/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm p-4">
            <form action="{{ route('satker.hutang.index') }}" method="GET" class="flex flex-col md:flex-row items-stretch md:items-end gap-3">
                <div class="flex-1 space-y-1">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider pl-0.5">BBM</label>
                    <select name="jenis_bbm" class="tom-select w-full">
                        <option class="bg-white text-slate-900 dark:bg-slate-900 dark:text-white" value="">Semua BBM</option>
                        <option class="bg-white text-slate-900 dark:bg-slate-900 dark:text-white" value="Pertamax" {{ request('jenis_bbm') == 'Pertamax' ? 'selected' : '' }}>Pertamax</option>
                        <option class="bg-white text-slate-900 dark:bg-slate-900 dark:text-white" value="Pertamina Dex" {{ request('jenis_bbm') == 'Pertamina Dex' ? 'selected' : '' }}>Pertamina Dex</option>
                    </select>
                </div>

                <div class="flex-1 space-y-1">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider pl-0.5">Status</label>
                    <select name="status" class="tom-select w-full">
                        <option class="bg-white text-slate-900 dark:bg-slate-900 dark:text-white" value="">Semua Status</option>
                        <option class="bg-white text-slate-900 dark:bg-slate-900 dark:text-white" value="belum_dibayar" {{ request('status') == 'belum_dibayar' ? 'selected' : '' }}>Belum Bayar</option>
                        <option class="bg-white text-slate-900 dark:bg-slate-900 dark:text-white" value="sudah_dibayar" {{ request('status') == 'sudah_dibayar' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>

                <div class="flex items-center gap-2 pt-1 md:pt-0">
                    <button type="submit" class="h-10 px-5 bg-brand-primary text-white rounded-xl text-xs font-bold hover:bg-brand-primary/90 transition shadow-sm flex items-center justify-center gap-1.5 flex-1 md:flex-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        <span>Filter</span>
                    </button>
                    @if(request('jenis_bbm') || request('status'))
                        <a href="{{ route('satker.hutang.index') }}" class="h-10 px-3.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-bold transition flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-xl text-xs font-bold text-emerald-800 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-3 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 rounded-xl text-xs font-bold text-rose-800 dark:text-rose-200">
                {{ session('error') }}
            </div>
        @endif

        <!-- Table Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden">
            <!-- Table Sub-header (Limit & Count) -->
            <div class="px-5 py-3.5 border-b border-slate-200 dark:border-white/10 flex flex-wrap justify-between items-center gap-3 bg-slate-50 dark:bg-slate-800/40">
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Tampilkan</span>
                    <form action="{{ route('satker.hutang.index') }}" method="GET" class="inline">
                        @foreach(request()->except('per_page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <select name="per_page" onchange="this.form.submit()" class="h-8 px-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-bold text-slate-700 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500">
                            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </form>
                </div>
                <div class="text-[11px] font-bold text-slate-500 dark:text-slate-400">
                    Menampilkan <span class="text-slate-800 dark:text-white">{{ $hutangs->firstItem() ?? 0 }}-{{ $hutangs->lastItem() ?? 0 }}</span> dari <span class="text-slate-800 dark:text-white">{{ $hutangs->total() }}</span> Data
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-xs whitespace-nowrap">
                    <thead class="bg-slate-50 dark:bg-slate-800/70 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200 dark:border-white/10">
                        <tr>
                            <th class="px-5 py-3.5 text-center w-12">No</th>
                            <th class="px-5 py-3.5">Tanggal Bon</th>
                            <th class="px-5 py-3.5">Kendaraan</th>
                            <th class="px-5 py-3.5">Driver</th>
                            <th class="px-5 py-3.5 text-center">Jumlah Bon</th>
                            <th class="px-5 py-3.5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-white/5 text-slate-700 dark:text-slate-300">
                        @forelse($hutangs as $hutang)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                <td class="px-5 py-3.5 text-center font-bold text-slate-400">
                                    {{ $loop->iteration + ($hutangs->currentPage() - 1) * $hutangs->perPage() }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="font-bold text-slate-900 dark:text-white">
                                        @if($hutang->tanggal_bon)
                                            {{ \Carbon\Carbon::parse($hutang->tanggal_bon)->format('d M Y') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->format('d M Y') }}
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-slate-400 uppercase mt-0.5">
                                        @if($hutang->tanggal_bon)
                                            Catatan Manual
                                        @else
                                            {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->format('H:i') }} WITA
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="font-mono font-bold text-slate-900 dark:text-white text-xs">
                                        {{ $hutang->nopol }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                        {{ $hutang->jenis_kendaraan }}
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 font-medium text-slate-800 dark:text-slate-200">
                                    {{ $hutang->nama_driver ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="font-black text-rose-600 dark:text-rose-400 text-sm">
                                            {{ number_format($hutang->jumlah_bon, 0, ',', '.') }} L
                                        </span>
                                        <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider {{ str_contains(strtolower($hutang->jenis_bbm), 'dex') ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300' }}">
                                            {{ $hutang->jenis_bbm }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    @if($hutang->status === 'belum_dibayar')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300 border border-rose-200 dark:border-rose-800/50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                            Belum Bayar
                                        </span>
                                    @else
                                        <div class="flex flex-col items-center">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/50">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                Lunas
                                            </span>
                                            @if($hutang->tanggal_bayar)
                                                <span class="text-[9px] text-slate-400 mt-0.5">
                                                    {{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->format('d/m/Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center text-slate-400 mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-bold text-slate-600 dark:text-slate-400">Tidak ada data catatan hutang</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($hutangs->hasPages())
                <div class="px-5 py-4 border-t border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-800/40">
                    {{ $hutangs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>