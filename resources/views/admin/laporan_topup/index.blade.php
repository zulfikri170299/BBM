<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight">Laporan Top Up</h1>
                <p class="mt-1 text-xs sm:text-sm font-medium text-slate-400">Riwayat pengisian saldo kendaraan.</p>
            </div>
            <a href="{{ route('admin.laporan-topup.print', request()->query()) }}" target="_blank"
                class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 bg-rose-600 text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-rose-700 shadow-lg shadow-rose-200 transition-all active:scale-95 gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                Cetak PDF
            </a>
        </div>

        <!-- Filter Card -->
        <div class="bg-slate-900 border border-white/5 rounded-2xl border border-white/10 shadow-sm p-4 sm:p-5">
            <form action="{{ route('admin.laporan-topup.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Mulai Tanggal</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="flatpickr w-full h-11 px-4 bg-slate-800/50 border-white/10 rounded-xl text-xs font-bold text-slate-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="flatpickr w-full h-11 px-4 bg-slate-800/50 border-white/10 rounded-xl text-xs font-bold text-slate-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                    </div>
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Satker</label>
                        <select name="satker_id" id="filter_satker_id"
                            class="tom-select w-full">
                            <option value="">Semua Satker</option>
                            @foreach($satkers as $satker)
                                <option value="{{ $satker->id }}" {{ request('satker_id') == $satker->id ? 'selected' : '' }}>
                                    {{ $satker->nama_satker }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 h-11 bg-indigo-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-500/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('admin.laporan-topup.index') }}"
                            class="flex-1 h-11 bg-slate-800 text-slate-400 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-slate-900 rounded-2xl border border-white/10 shadow-sm overflow-hidden">
            <!-- Table Header Info -->
            <div class="px-4 py-3 border-b border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-200">Riwayat Top Up</h3>
                        <p class="text-xs text-slate-400">Total {{ $riwayats->total() }} catatan</p>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-800/50 border-b border-white/5">
                            <th colspan="9" class="px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <form action="{{ route('admin.laporan-topup.index') }}" method="GET"
                                        class="flex items-center">
                                        <x-per-page :current="request('per_page', 15)" />
                                    </form>
                                    <div class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">
                                        Menampilkan {{ $riwayats->firstItem() ?? 0 }}-{{ $riwayats->lastItem() ?? 0 }}
                                        dari {{ $riwayats->total() }} data
                                    </div>
                                </div>
                            </th>
                        </tr>
                        <tr class="bg-slate-800/50/70">
                            <th
                                class="px-4 py-3 text-center text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider w-12">
                                No</th>
                            <th
                                class="px-4 py-3 text-left text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider">
                                Tanggal</th>
                            <th
                                class="px-4 py-3 text-left text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider">
                                User (Admin)</th>
                            <th
                                class="px-4 py-3 text-left text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider">
                                Satker</th>
                            <th
                                class="px-4 py-3 text-left text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider">
                                Nopol</th>
                            <th
                                class="px-4 py-3 text-center text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider">
                                Jenis Kendaraan</th>
                            <th
                                class="px-4 py-3 text-center text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider">
                                Jenis BBM</th>
                            <th
                                class="px-4 py-3 text-center text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider">
                                Metode</th>
                            <th
                                class="px-4 py-3 text-right text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider">
                                Jumlah (Liter)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($riwayats as $riwayat)
                            <tr class="hover:bg-slate-800/50 transition-colors">
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="text-xs font-medium text-slate-400">{{ $loop->iteration + ($riwayats->currentPage() - 1) * $riwayats->perPage() }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="text-sm font-medium text-slate-200">{{ $riwayat->created_at->setTimezone('Asia/Makassar')->format('d/m/Y H:i') }}
                                        WITA</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-6 h-6 rounded-full bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-400">
                                            {{ substr($riwayat->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <span class="text-xs text-slate-300">{{ $riwayat->user->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-md bg-slate-800 text-xs font-semibold text-slate-400">
                                        {{ $riwayat->satker->nama_satker ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="text-xs font-semibold text-slate-200">{{ $riwayat->kendaraan->no_polisi ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-xs font-medium text-slate-400">
                                        {{ $riwayat->kendaraan->jenis_kendaraan ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 rounded-md bg-slate-800 text-xs font-semibold text-slate-400">
                                        {{ $riwayat->kendaraan->jenis_bbm ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $riwayat->metode == 'IMPORT' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">
                                        {{ $riwayat->metode }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span
                                        class="text-sm font-bold {{ $riwayat->tipe == 'masuk' ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ number_format($riwayat->jumlah, 0, ',', '.') }}
                                        L
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-slate-400 font-medium">Belum ada riwayat top up</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($riwayats->hasPages())
                <div class="px-4 py-3 border-t border-white/5">
                    {{ $riwayats->links() }}
                </div>
            @endif
        </div>

        <!-- Summary Card -->
        <div class="bg-slate-900 rounded-2xl border border-white/10 shadow-sm overflow-hidden w-full md:w-1/2">
            <div class="px-4 py-3 border-b border-white/5">
                <h3 class="font-semibold text-slate-200">Total Keseluruhan per Jenis BBM</h3>
                <p class="text-xs text-slate-400">Berdasarkan data yang difilter</p>
            </div>
            <div class="p-6">
                <table class="w-full">
                    <tbody class="divide-y divide-white/5">
                        @foreach($summary as $jenis => $total)
                            <tr>
                                <td class="py-3 text-sm font-medium text-slate-400">{{ $jenis }}</td>
                                <td class="py-3 text-sm font-bold text-white text-right">
                                    {{ rtrim(rtrim(number_format(abs($total), 2, ',', '.'), '0'), ',') }} Liter
                                </td>
                            </tr>
                        @endforeach
                        @if($summary->isEmpty())
                            <tr>
                                <td colspan="2" class="py-3 text-sm text-center text-slate-400">Tidak ada data</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>