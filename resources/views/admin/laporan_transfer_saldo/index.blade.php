<x-app-layout>
    <div class="p-4 lg:p-6 space-y-4">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-xl font-bold text-white tracking-tight">Laporan Transfer Saldo</h1>
                <p class="mt-1 text-xs text-slate-400">Riwayat transfer saldo BBM kendaraan atau ke personel.</p>
            </div>
            <a href="{{ route('admin.laporan-transfer-saldo.print', request()->query()) }}" target="_blank"
                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-rose-600 text-white rounded-xl font-semibold text-[11px] uppercase tracking-wider hover:bg-rose-700 shadow-lg shadow-rose-200 transition-all active:scale-95 gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                Cetak PDF
            </a>
        </div>

        <!-- Filter Card -->
        <div class="bg-slate-900 border border-white/5 rounded-2xl shadow-sm p-4">
            <form action="{{ route('admin.laporan-transfer-saldo.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1 ml-1">Mulai Tanggal</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full h-9 px-3 bg-slate-800/50 border-white/10 rounded-lg text-xs font-medium text-slate-300 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1 ml-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full h-9 px-3 bg-slate-800/50 border-white/10 rounded-lg text-xs font-medium text-slate-300 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 transition-all">
                    </div>
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1 ml-1">Satker</label>
                        <select name="satker_id"
                            class="w-full h-9 px-3 bg-slate-800/50 border-white/10 rounded-lg text-xs font-medium text-slate-300 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 transition-all">
                            <option value="" class="bg-slate-800 text-slate-200">Semua Satker</option>
                            @foreach($satkers as $satker)
                                <option value="{{ $satker->id }}" class="bg-slate-800 text-slate-200" {{ request('satker_id') == $satker->id ? 'selected' : '' }}>
                                    {{ $satker->nama_satker }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 h-9 bg-rose-600 text-white rounded-lg font-semibold text-[11px] uppercase tracking-wider hover:bg-rose-700 shadow-lg shadow-rose-500/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('admin.laporan-transfer-saldo.index') }}"
                            class="flex-1 h-9 bg-slate-800 text-slate-400 rounded-lg font-semibold text-[11px] uppercase tracking-wider hover:bg-slate-200 transition-all active:scale-95 flex items-center justify-center gap-2">
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
            <div class="overflow-x-auto">
                <table class="min-w-full whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-800/50">
                            <th class="px-4 py-3 text-center text-[11px] font-medium tracking-wider text-slate-400 uppercase w-12">No</th>
                            <th class="px-4 py-3 text-left text-[11px] font-medium tracking-wider text-slate-400 uppercase">Waktu</th>
                            <th class="px-4 py-3 text-left text-[11px] font-medium tracking-wider text-slate-400 uppercase">Satker</th>
                            <th class="px-4 py-3 text-left text-[11px] font-medium tracking-wider text-slate-400 uppercase">Tipe Tujuan</th>
                            <th class="px-4 py-3 text-left text-[11px] font-medium tracking-wider text-slate-400 uppercase">Dari</th>
                            <th class="px-4 py-3 text-left text-[11px] font-medium tracking-wider text-slate-400 uppercase">Ke</th>
                            <th class="px-4 py-3 text-center text-[11px] font-medium tracking-wider text-slate-400 uppercase">BBM</th>
                            <th class="px-4 py-3 text-right text-[11px] font-medium tracking-wider text-slate-400 uppercase">Jumlah</th>
                            <th class="px-4 py-3 text-left text-[11px] font-medium tracking-wider text-slate-400 uppercase">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($riwayat as $r)
                            <tr class="hover:bg-slate-800/50 transition-colors">
                                <td class="px-4 py-3 text-center">
                                    <span class="text-xs font-medium text-slate-400">{{ $loop->iteration + ($riwayat->currentPage() - 1) * $riwayat->perPage() }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-xs text-slate-200">{{ $r->created_at->setTimezone('Asia/Makassar')->format('d/m/Y H:i') }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-xs font-medium text-slate-300">
                                        {{ $r->satker->nama_satker ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($r->personel_id && !$r->tujuan_kendaraan_id)
                                        <span class="px-2 py-1 bg-blue-500/20 text-blue-400 text-[10px] uppercase font-bold rounded-full">KE PERSONEL</span>
                                    @elseif(!$r->kendaraan_id && $r->tujuan_kendaraan_id)
                                        <span class="px-2 py-1 bg-amber-500/20 text-amber-400 text-[10px] uppercase font-bold rounded-full">PUSAT -> KENDARAAN</span>
                                    @else
                                        <span class="px-2 py-1 bg-slate-500/20 text-slate-400 text-[10px] uppercase font-bold rounded-full">LAINNYA</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($r->kendaraan)
                                        <span class="text-xs font-semibold text-slate-200">{{ $r->kendaraan->no_polisi }}</span>
                                    @else
                                        <span class="text-xs text-slate-500 italic">Pusat</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($r->personel)
                                        <span class="text-xs font-semibold text-slate-200">{{ $r->personel->nama }}</span>
                                    @elseif($r->tujuanKendaraan)
                                        <span class="text-xs font-semibold text-slate-200">{{ $r->tujuanKendaraan->no_polisi }}</span>
                                    @else
                                        <span class="text-xs text-slate-500">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 rounded-md bg-rose-500/10 text-[11px] font-medium text-rose-400">
                                        {{ $r->jenis_bbm }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                        {{ number_format($r->jumlah, 0, ',', '.') }} L
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs text-slate-400 max-w-xs truncate" title="{{ $r->keterangan }}">
                                        {{ $r->keterangan ?? '-' }}
                                    </p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-slate-400">Belum ada data transfer saldo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($riwayat->hasPages())
                <div class="px-3 sm:px-4 py-3 border-t border-white/5">
                    {{ $riwayat->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
