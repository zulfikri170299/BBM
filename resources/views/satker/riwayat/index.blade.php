<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div>
            <h1 class="text-2xl font-bold text-white">Riwayat Pengisian BBM</h1>
            <p class="mt-1 text-slate-400">Histori pengisian BBM kendaraan
                {{ auth()->user()->satker->nama_satker ?? '' }}.
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-slate-900 rounded-2xl border border-white/10 shadow-sm p-5">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium">Total Transaksi</p>
                        <p class="text-2xl font-bold text-slate-200">{{ number_format($stats['total_transaksi']) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-slate-900 rounded-2xl border border-white/10 shadow-sm p-5">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-emerald-100 text-emerald-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium">Total Pengisian</p>
                        <p class="text-2xl font-bold text-slate-200">
                            {{ number_format($stats['total_liter'], 0, ',', '.') }} <span
                                class="text-sm font-medium text-slate-400">Liter</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-slate-900 rounded-2xl border border-white/10 shadow-sm p-5">
            <form method="GET" action="{{ route('satker.riwayat.index') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Dari Tanggal</label>
                    <input type="date" name="dari" value="{{ request('dari') }}"
                        class="flatpickr w-full border-2 border-white/10 rounded-xl text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all">
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Sampai Tanggal</label>
                    <input type="date" name="sampai" value="{{ request('sampai') }}"
                        class="flatpickr w-full border-2 border-white/10 rounded-xl text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all">
                </div>
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Kendaraan</label>
                    <select name="kendaraan_id"
                        class="tom-select w-full">
                        <option value="">Semua Kendaraan</option>
                        @foreach($kendaraans as $k)
                            <option value="{{ $k->id }}" {{ request('kendaraan_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->no_polisi }} — {{ $k->jenis_kendaraan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all flex items-center justify-center min-w-[44px]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span class="hidden sm:inline ml-2">Filter</span>
                    </button>
                    <a href="{{ route('satker.riwayat.index') }}"
                        class="px-4 py-2.5 bg-slate-800 text-slate-400 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-slate-200 transition-colors flex items-center justify-center min-w-[44px]">
                        <svg class="w-4 h-4 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span class="hidden sm:inline">Reset</span>
                    </a>
                    <a href="{{ route('satker.riwayat.print', request()->all()) }}" target="_blank"
                        class="px-4 py-2.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-rose-100 transition-colors flex items-center justify-center min-w-[44px] gap-2">
                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                            </path>
                        </svg>
                        <span class="hidden sm:inline">PDF</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-slate-900 rounded-2xl border border-white/10 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div>
                        <h3 class="font-semibold text-slate-200">Daftar Pengisian BBM</h3>
                        <p class="text-xs text-slate-400">{{ $transaksis->total() }} transaksi ditemukan</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-800/50 border-b border-white/5">
                            <th colspan="6" class="px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <form action="{{ route('satker.riwayat.index') }}" method="GET"
                                        class="flex items-center">
                                        <x-per-page :current="request('per_page', 15)" />
                                    </form>
                                    <div class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">
                                        Menampilkan
                                        {{ $transaksis->firstItem() ?? 0 }}-{{ $transaksis->lastItem() ?? 0 }} dari
                                        {{ $transaksis->total() }} data
                                    </div>
                                </div>
                            </th>
                        </tr>
                        <tr class="bg-slate-800/80">
                            <th
                                class="px-4 py-3 text-center text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider w-12">
                                No</th>
                            <th
                                class="px-4 py-3 text-left text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider">
                                Tanggal / Waktu</th>
                            <th
                                class="px-4 py-3 text-left text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider">
                                Jenis Kendaraan</th>
                            <th
                                class="px-4 py-3 text-left text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider">
                                Nopol</th>
                            <th
                                class="px-4 py-3 text-left text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider">
                                Jenis BBM</th>
                            <th
                                class="px-4 py-3 text-left text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider">
                                Nama Driver</th>
                            <th
                                class="px-4 py-3 text-right text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider">
                                Jumlah Liter</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($transaksis as $trx)
                            @php
                                $isPotong = ($trx->row_type ?? 'pengisian') === 'potong_saldo';
                            @endphp
                            <tr class="hover:bg-slate-800/50 transition-colors">
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="text-xs font-medium text-slate-400">{{ $loop->iteration + ($transaksis->currentPage() - 1) * $transaksis->perPage() }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div>
                                        <span
                                            class="text-xs font-medium text-slate-200">{{ \Carbon\Carbon::parse($trx->tanggal)->setTimezone('Asia/Makassar')->format('d M Y') }}</span>
                                        <span
                                            class="block text-xs text-slate-400">{{ \Carbon\Carbon::parse($trx->tanggal)->setTimezone('Asia/Makassar')->format('H:i') }}
                                            WITA</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-slate-300 font-medium">{{ $trx->kendaraan->jenis_kendaraan ?? ($trx->personel->nama ?? '-') }}</span>
                                        @if($isPotong)
                                            <span class="text-[10px] font-bold text-amber-600 uppercase tracking-tighter">Potong Saldo</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="text-xs font-semibold text-slate-200">{{ $trx->kendaraan->no_polisi ?? ($trx->personel->nrp ?? '-') }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $jenisBbm = $trx->kendaraan->jenis_bbm ?? ($trx->personel->jenis_bbm ?? null);
                                        $bbmColors = [
                                            'Pertalite' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                                            'Pertamax' => 'bg-blue-500/10 text-blue-400 border border-blue-500/20',
                                            'Solar' => 'bg-amber-500/10 text-amber-400 border border-amber-500/20',
                                            'Dexlite' => 'bg-purple-500/10 text-purple-400 border border-purple-500/20',
                                        ];
                                        $color = $bbmColors[$jenisBbm] ?? 'bg-slate-800/50 text-slate-400 border border-white/10';
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $color }}">
                                        {{ $jenisBbm ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($isPotong)
                                        <span class="text-xs text-slate-400 italic leading-tight block max-w-[150px] truncate" title="{{ $trx->keterangan }}">
                                            {{ $trx->keterangan }}
                                        </span>
                                    @else
                                        <span class="text-xs font-medium text-slate-300">{{ $trx->nama_driver ?? ($trx->personel->nama ?? '-') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span class="text-sm font-bold {{ $isPotong ? 'text-rose-600' : 'text-emerald-600' }}">
                                        {{ $isPotong ? '-' : '' }}{{ number_format($trx->liter, 0, ',', '.') }} L
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
                                        <p class="text-slate-400 font-medium">Belum ada riwayat pengisian BBM</p>
                                        <p class="text-xs text-slate-400 mt-1">Transaksi akan muncul setelah kendaraan
                                            melakukan pengisian BBM</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-slate-800/80 border-t border-white/10">
                        <tr>
                            <th colspan="6" class="px-4 py-3 text-right text-xs font-bold text-slate-400 uppercase tracking-widest">
                                TOTAL PADA DATA INI (TERFILTER)
                            </th>
                            <th class="px-4 py-3 text-right">
                                <span class="text-lg font-black text-indigo-600">
                                    {{ number_format($stats['total_liter'], 0, ',', '.') }} L
                                </span>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if($transaksis->hasPages())
                <div class="px-4 py-3 border-t border-white/5">
                    {{ $transaksis->links() }}
                </div>
            @endif
        </div>

        <!-- Summary Card -->
        <div class="bg-slate-900 rounded-2xl border border-white/10 shadow-sm overflow-hidden p-6 mt-6">
            <h3 class="text-lg font-bold text-slate-200 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                Total Pengisian BBM
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                @foreach($summaryBbm as $jenis => $total)
                    @php
                        $bbmColors = [
                            'Pertalite' => 'bg-green-50 border-green-100 text-green-700',
                            'Pertamax' => 'bg-blue-50 border-blue-100 text-blue-700',
                            'Solar' => 'bg-amber-50 border-amber-100 text-amber-700',
                            'Dexlite' => 'bg-purple-50 border-purple-100 text-purple-700',
                        ];
                        $style = $bbmColors[$jenis] ?? 'bg-slate-800/50 border-white/5 text-slate-300';
                    @endphp
                    <div class="p-4 rounded-xl border {{ $style }}">
                        <p class="text-xs font-semibold opacity-70 mb-1 uppercase tracking-wider">{{ $jenis ?: 'LAINNYA' }}</p>
                        <p class="text-xl font-bold">{{ rtrim(rtrim(number_format($total, 2, ',', '.'), '0'), ',') }} <span
                                class="text-sm font-medium opacity-70">L</span></p>
                    </div>
                @endforeach

                {{-- Grand Total Box --}}
                <div class="p-4 rounded-xl border bg-indigo-600 border-indigo-500 text-white shadow-lg shadow-indigo-500/20">
                    <p class="text-[10px] font-black uppercase tracking-widest text-indigo-400/80 mb-1 leading-none">GRAND TOTAL</p>
                    <div class="flex items-baseline gap-1">
                        <span class="text-2xl font-black">{{ number_format($stats['total_liter'], 0, ',', '.') }}</span>
                        <span class="text-xs font-bold text-indigo-400/60">Liter</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>