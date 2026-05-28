<x-app-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6">
        <!-- Page Title -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1
                    class="text-2xl sm:text-3xl font-bold text-slate-900 border-b-4 border-indigo-600 pb-2 inline-block">
                    Laporan Hutang BBM</h1>
                <p class="text-slate-500 mt-2 text-sm font-medium">Daftar riwayat hutang bon BBM satker (Lunas & Belum Lunas).</p>
            </div>

            <a href="{{ route('satker.laporan-hutang.print', request()->all()) }}" target="_blank"
                class="inline-flex items-center px-6 py-3 bg-rose-600 text-white font-bold rounded-2xl shadow-lg shadow-rose-200 hover:bg-rose-700 transition active:scale-95 gap-2 uppercase tracking-wider text-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak PDF
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6 mb-6">
            <form action="{{ route('satker.laporan-hutang.index') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <x-input-label for="start_date" value="Dari Tanggal (Bon)" />
                    <x-text-input id="start_date" name="start_date" type="date" class="flatpickr mt-1 block w-full"
                        :value="request('start_date')" />
                </div>

                <div>
                    <x-input-label for="end_date" value="Sampai Tanggal (Bon)" />
                    <x-text-input id="end_date" name="end_date" type="date" class="flatpickr mt-1 block w-full"
                        :value="request('end_date')" />
                </div>

                <div>
                    <x-input-label for="status" value="Status" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1" />
                    <select name="status" id="status" class="tom-select w-full rounded-xl border-slate-200 bg-slate-50 text-xs font-bold text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                        <option value="">Semua Status</option>
                        <option value="sudah_dibayar" {{ request('status') === 'sudah_dibayar' ? 'selected' : '' }}>Lunas</option>
                        <option value="belum_dibayar" {{ request('status') === 'belum_dibayar' ? 'selected' : '' }}>Belum Lunas</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['start_date', 'end_date', 'status']))
                        <a href="{{ route('satker.laporan-hutang.index') }}"
                            class="px-4 py-2.5 bg-slate-100 text-slate-700 font-semibold rounded-lg hover:bg-slate-200 transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Data -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-50/80 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 font-bold">No</th>
                            <th class="px-6 py-4 font-bold">Tgl Bon</th>
                            <th class="px-6 py-4 font-bold">Tgl Bayar</th>
                            <th class="px-6 py-4 font-bold">Kendaraan</th>
                            <th class="px-6 py-4 font-bold">Driver</th>
                            <th class="px-6 py-4 font-bold text-center">Jumlah</th>
                            <th class="px-6 py-4 font-bold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($hutangs as $index => $hutang)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 font-medium text-slate-400">
                                    {{ ($hutangs->currentPage() - 1) * $hutangs->perPage() + $index + 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-slate-600 font-bold">
                                        {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->translatedFormat('d M Y') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400">
                                        {{ $hutang->petugas->name ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($hutang->tanggal_bayar)
                                        <div class="font-bold text-slate-900">
                                            {{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->translatedFormat('d M Y') }}
                                        </div>
                                        <div class="text-[10px] text-slate-500 font-medium">
                                            {{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->format('H:i') }} WITA
                                        </div>
                                    @else
                                        <div class="text-[10px] text-rose-400 font-bold italic">BELUM DIBAYAR</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-black text-indigo-600 uppercase">{{ $hutang->nopol }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium uppercase">
                                        {{ $hutang->jenis_kendaraan }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800">{{ $hutang->nama_driver ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-sm font-black text-slate-900">{{ number_format($hutang->jumlah_bon, 0) }} L</span>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $hutang->jenis_bbm }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($hutang->status === 'sudah_dibayar')
                                        <span class="inline-flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                            Lunas
                                        </span>
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
                                        <p class="text-slate-400 italic font-medium">Tidak ada data hutang.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($hutangs->count() > 0)
                        <tfoot class="bg-slate-50 font-black border-t-2 border-slate-200">
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-right text-slate-500 uppercase tracking-widest text-[10px]">TOTAL HUTANG</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col items-center gap-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-indigo-600 text-xs">{{ number_format($totalPertamax, 0) }} L</span>
                                            <span class="text-[8px] bg-indigo-100 text-indigo-600 px-1 rounded">PERTAMAX</span>
                                        </div>
                                        <div class="flex items-center gap-2 border-t border-slate-200 pt-1">
                                            <span class="text-amber-600 text-xs">{{ number_format($totalDex, 0) }} L</span>
                                            <span class="text-[8px] bg-amber-100 text-amber-600 px-1 rounded">P. DEX</span>
                                        </div>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
            @if($hutangs->hasPages())
                <div class="p-4 border-t border-slate-200">
                    {{ $hutangs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
