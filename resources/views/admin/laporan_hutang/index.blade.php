<x-app-layout>
    <div class="max-w-7xl mx-auto p-2 sm:p-6 lg:p-8 space-y-6 px-2 sm:px-6 lg:px-8">
        <!-- Page Title -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Laporan Hutang BBM</h1>
                <p class="mt-1 text-xs sm:text-sm font-medium text-slate-400">Monitoring riwayat hutang bon BBM oleh Satker.</p>
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
        <div class="bg-slate-900 border border-white/5 rounded-2xl border border-white/10 shadow-sm p-4 sm:p-5 mb-6">
            <form action="{{ route('admin.laporan-hutang.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Dari Tanggal</label>
                        <input id="start_date" name="start_date" type="date"
                            class="flatpickr w-full h-11 px-4 border-white/10 rounded-xl text-xs font-bold text-slate-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all bg-slate-800/50"
                            value="{{ request('start_date') }}" />
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Sampai Tanggal</label>
                        <input id="end_date" name="end_date" type="date"
                            class="flatpickr w-full h-11 px-4 border-white/10 rounded-xl text-xs font-bold text-slate-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all bg-slate-800/50"
                            value="{{ request('end_date') }}" />
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Filter Satker</label>
                        <select name="satker_id" id="filter_satker_id"
                            class="tom-select w-full rounded-xl border-white/10 bg-slate-800/50 text-xs font-bold text-slate-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                            <option value="">Semua Satker</option>
                            @foreach($satkers as $satker)
                                <option value="{{ $satker->id }}" {{ request('satker_id') == $satker->id ? 'selected' : '' }}>
                                    {{ $satker->nama_satker }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Jenis BBM</label>
                        <select name="jenis_bbm" id="filter_jenis_bbm"
                            class="tom-select w-full rounded-xl border-white/10 bg-slate-800/50 text-xs font-bold text-slate-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                            <option value="">Semua Jenis</option>
                            @foreach($jenisBbm as $jenis)
                                <option value="{{ $jenis }}" {{ request('jenis_bbm') == $jenis ? 'selected' : '' }}>
                                    {{ $jenis }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Status</label>
                        <select name="status" id="filter_status"
                            class="tom-select w-full rounded-xl border-white/10 bg-slate-800/50 text-xs font-bold text-slate-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                            <option value="">Semua Status</option>
                            <option value="sudah_dibayar" {{ request('status') === 'sudah_dibayar' ? 'selected' : '' }}>Lunas</option>
                            <option value="belum_dibayar" {{ request('status') === 'belum_dibayar' ? 'selected' : '' }}>Belum Lunas</option>
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
                        <a href="{{ route('admin.laporan-hutang.index') }}"
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

        <!-- Table Data -->
        <div class="bg-slate-900 border border-white/5 rounded-2xl border border-white/10 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-400 uppercase bg-slate-800/50/80 border-b border-white/10">
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
                    <tbody class="divide-y divide-white/5">
                        @forelse($hutangs as $hutang)
                            <tr class="hover:bg-slate-800/50 transition">
                                <td class="px-4 py-3">
                                    <div class="text-xs text-slate-400 font-medium">
                                        {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->translatedFormat('d M Y') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 uppercase">
                                        Oleh: {{ $hutang->petugas->name ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($hutang->tanggal_bayar)
                                        <div class="font-bold text-white">
                                            {{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->translatedFormat('d F Y') }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-medium">
                                            {{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->format('H:i') }}
                                            WITA
                                        </div>
                                    @else
                                        <div class="text-[10px] text-rose-400 font-bold italic">BELUM DIBAYAR</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-slate-200">{{ $hutang->satker->nama_satker }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-black text-indigo-600 uppercase">{{ $hutang->nopol }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium uppercase">
                                        {{ $hutang->jenis_kendaraan }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-slate-200">{{ $hutang->nama_driver ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-xs font-black">
                                        {{ $hutang->jumlah_bon }} L {{ $hutang->jenis_bbm }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($hutang->status === 'sudah_dibayar')
                                        <div class="flex flex-col items-center">
                                            <span class="inline-flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                                Lunas
                                            </span>
                                            <span class="text-[9px] font-medium text-slate-400 mt-1">Oleh: {{ $hutang->adminBayar->name ?? '-' }}</span>
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
                                        <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-slate-400 italic font-medium">Tidak ada data riwayat pembayaran
                                            ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($hutangs->hasPages())
                <div class="p-4 border-t border-white/10">
                    {{ $hutangs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
