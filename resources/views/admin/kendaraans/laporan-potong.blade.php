<x-app-layout>
    <div class="p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Laporan Potong Saldo</h1>
                <p class="mt-1 text-xs sm:text-sm font-medium text-slate-500">Riwayat pemotongan saldo kendaraan oleh Admin.</p>
            </div>
            <a href="{{ route('admin.laporan-potong.print', request()->query()) }}" target="_blank"
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
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5">
            <form action="{{ route('admin.laporan-potong.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Mulai Tanggal</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full h-11 px-4 bg-slate-50 border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full h-11 px-4 bg-slate-50 border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 transition-all">
                    </div>
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Satker</label>
                        <select name="satker_id"
                            class="w-full h-11 px-4 bg-slate-50 border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 transition-all">
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
                            class="flex-1 h-11 bg-rose-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-rose-700 shadow-lg shadow-rose-100 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('admin.laporan-potong.index') }}"
                            class="flex-1 h-11 bg-slate-100 text-slate-600 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all active:scale-95 flex items-center justify-center gap-2">
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
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-slate-50/70">
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Admin</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Satker</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis Kendaraan</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nopol</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">BBM</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($riwayat as $r)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-semibold text-slate-500">{{ $loop->iteration + ($riwayat->currentPage() - 1) * $riwayat->perPage() }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-slate-800">{{ $r->created_at->setTimezone('Asia/Makassar')->format('d/m/Y H:i') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-slate-700">{{ $r->user->name ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-md bg-slate-100 text-xs font-semibold text-slate-600">
                                        {{ $r->satker->nama_satker ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-slate-600">{{ $r->kendaraan->jenis_kendaraan ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-slate-800">{{ $r->kendaraan->no_polisi ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 rounded-md bg-rose-50 text-xs font-semibold text-rose-600">
                                        {{ $r->jenis_bbm }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-black text-rose-600">
                                        -{{ number_format($r->jumlah, 0, ',', '.') }} L
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs text-slate-600 max-w-xs truncate" title="{{ $r->keterangan }}">
                                        {{ $r->keterangan ?? '-' }}
                                    </p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-slate-500">Belum ada data pemotongan saldo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($riwayat->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $riwayat->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
