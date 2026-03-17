<x-app-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6">
        <!-- Page Title -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1
                    class="text-2xl sm:text-3xl font-bold text-slate-900 border-b-4 border-indigo-600 pb-2 inline-block">
                    Laporan Riwayat Pembayaran Hutang BBM</h1>
                <p class="text-slate-500 mt-2 text-sm font-medium">Daftar hutang bon BBM satker yang telah dilunasi.</p>
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

        <!-- Filter & Search -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6 mb-6">
            <form action="{{ route('satker.laporan-hutang.index') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <x-input-label for="start_date" value="Dari Tanggal (Bayar)" />
                    <x-text-input id="start_date" name="start_date" type="date" class="flatpickr mt-1 block w-full"
                        :value="request('start_date')" />
                </div>

                <div>
                    <x-input-label for="end_date" value="Sampai Tanggal (Bayar)" />
                    <x-text-input id="end_date" name="end_date" type="date" class="flatpickr mt-1 block w-full"
                        :value="request('end_date')" />
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['start_date', 'end_date']))
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
                            <th class="px-6 py-4 font-bold">Tanggal Bon</th>
                            <th class="px-6 py-4 font-bold">Tanggal Bayar</th>
                            <th class="px-6 py-4 font-bold">Kendaraan Hutang</th>
                            <th class="px-6 py-4 font-bold">Driver</th>
                            <th class="px-6 py-4 font-bold text-center">Jumlah</th>
                            <th class="px-6 py-4 font-bold">Pelaksana Pelunasan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($hutangs as $hutang)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-xs text-slate-600 font-medium">
                                        {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->translatedFormat('d M Y') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400">
                                        Pencatat: {{ $hutang->petugas->name ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">
                                        {{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->translatedFormat('d F Y') }}
                                    </div>
                                    <div class="text-[10px] text-slate-500 font-medium">
                                        {{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->format('H:i') }}
                                        WITA
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-black text-indigo-600 uppercase">{{ $hutang->nopol }}</div>
                                    <div class="text-[10px] text-slate-500 font-medium uppercase">
                                        {{ $hutang->jenis_kendaraan }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800">{{ $hutang->nama_driver ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-xs font-black">
                                        {{ $hutang->jumlah_bon }} L {{ $hutang->jenis_bbm }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-700">{{ $hutang->adminBayar->name ?? '-' }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-slate-400 italic font-medium">Tidak ada data riwayat pembayaran.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
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
