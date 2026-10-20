<x-app-layout>
    <div class="max-w-7xl mx-auto p-2 sm:p-6 lg:p-8 space-y-6 px-2 sm:px-6 lg:px-8">
        <!-- Page Title -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-800 dark:text-white tracking-tight">Laporan Hutang BBM</h1>
                <p class="mt-1 text-xs sm:text-sm font-medium text-slate-600 dark:text-slate-400">Monitoring riwayat hutang bon BBM oleh Satker.</p>
            </div>

            <a href="{{ route('admin.laporan-hutang.print', request()->all()) }}" target="_blank"
                class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 bg-rose-600 text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-rose-700 shadow-lg shadow-rose-200 transition-all active:scale-95 gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak PDF
            </a>
        </div>

        <!-- Filter & Search -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/5 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm p-4 sm:p-5 mb-6 relative z-30">
            <form action="{{ route('admin.laporan-hutang.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Dari Tanggal</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input id="start_date" name="start_date" type="text" placeholder="Dari Tanggal"
                                style="padding-left: 2.35rem !important;"
                                class="flatpickr w-full h-11 !pl-9 pr-3 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all bg-slate-50 dark:bg-slate-800/50"
                                value="{{ request('start_date') }}" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Sampai Tanggal</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input id="end_date" name="end_date" type="text" placeholder="Sampai Tanggal"
                                style="padding-left: 2.35rem !important;"
                                class="flatpickr w-full h-11 !pl-9 pr-3 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all bg-slate-50 dark:bg-slate-800/50"
                                value="{{ request('end_date') }}" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Filter Satker</label>
                        <x-filter-select 
                            name="satker_id" 
                            :value="request('satker_id')" 
                            placeholder="Semua Satker" 
                            search-placeholder="Cari nama satker..." 
                            icon="building" 
                            :options="$satkers" 
                        />
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Jenis BBM</label>
                        <x-filter-select 
                            name="jenis_bbm" 
                            :value="request('jenis_bbm')" 
                            placeholder="Semua Jenis" 
                            search-placeholder="Cari jenis BBM..." 
                            icon="bbm" 
                            :options="$jenisBbm" 
                        />
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Status</label>
                        <x-filter-select 
                            name="status" 
                            :value="request('status')" 
                            placeholder="Semua Status" 
                            icon="shield" 
                            :searchable="false" 
                            :options="['sudah_dibayar' => 'Lunas', 'belum_dibayar' => 'Belum Lunas']" 
                        />
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 h-11 bg-indigo-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-500/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('admin.laporan-hutang.index') }}"
                            class="flex-1 h-11 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Data -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/5 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-600 dark:text-slate-400 uppercase bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-white/10">
                        <tr>
                            <th class="px-4 py-3 font-bold">Tanggal Bon</th>
                            <th class="px-4 py-3 font-bold">Tanggal Bayar</th>
                            <th class="px-4 py-3 font-bold">Satker</th>
                            <th class="px-4 py-3 font-bold">Kendaraan Hutang</th>
                            <th class="px-4 py-3 font-bold">Driver</th>
                            <th class="px-4 py-3 font-bold text-center">Jumlah</th>
                            <th class="px-4 py-3 font-bold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-white/5">
                        @forelse($hutangs as $hutang)
                            <tr class="hover:bg-slate-50 dark:bg-slate-800/50 transition">
                                <td class="px-4 py-3">
                                    <div class="text-xs text-slate-600 dark:text-slate-400 font-medium">
                                        {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->translatedFormat('d M Y') }}
                                    </div>
                                    <div class="text-[10px] text-slate-600 dark:text-slate-400 uppercase">
                                        Oleh: {{ $hutang->petugas->name ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($hutang->tanggal_bayar)
                                        <div class="font-bold text-slate-800 dark:text-white">
                                            {{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->translatedFormat('d F Y') }}
                                        </div>
                                        <div class="text-[10px] text-slate-600 dark:text-slate-400 font-medium">
                                            {{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->format('H:i') }}
                                            WITA
                                        </div>
                                    @else
                                        <div class="text-[10px] text-rose-600 dark:text-rose-400 font-bold italic">BELUM DIBAYAR</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-slate-800 dark:text-slate-200">{{ $hutang->satker->nama_satker }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-black text-indigo-600 uppercase">{{ $hutang->nopol }}</div>
                                    <div class="text-[10px] text-slate-600 dark:text-slate-400 font-medium uppercase">
                                        {{ $hutang->jenis_kendaraan }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-slate-800 dark:text-slate-200">{{ $hutang->nama_driver ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @php
                                        $isDex = stripos($hutang->jenis_bbm, 'dex') !== false;
                                        $badgeColor = $isDex ? 'bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 border-indigo-500/30' : 'bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border-emerald-500/30';
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 border {{ $badgeColor }} rounded-lg text-xs font-black uppercase">
                                        {{ $hutang->jumlah_bon }} L {{ $hutang->jenis_bbm }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($hutang->status === 'sudah_dibayar')
                                        <div class="flex flex-col items-center">
                                            <span class="inline-flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                                Lunas
                                            </span>
                                            <span class="text-[9px] font-medium text-slate-600 dark:text-slate-400 mt-1">Oleh: {{ $hutang->adminBayar ? $hutang->adminBayar->name : 'Otomatis Sistem' }}</span>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                            Belum Lunas
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-12 h-12 text-slate-800 dark:text-slate-200" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-slate-600 dark:text-slate-400 italic font-medium">Tidak ada data riwayat pembayaran
                                            ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($hutangs->hasPages())
                <div class="bg-white dark:bg-slate-900 dark:text-white p-4 border-t border-slate-200 dark:border-white/10">
                    {{ $hutangs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
