<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white">Laporan Transfer Saldo</h1>
                <p class="mt-1 text-slate-400">Riwayat transfer saldo kendaraan ke personel.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('satker.kendaraans.laporan-transfer.print', request()->query()) }}" target="_blank"
                    rel="nofollow"
                    class="inline-flex items-center px-4 py-2.5 bg-red-600 text-white rounded-xl font-semibold text-sm hover:bg-red-700 shadow-lg shadow-red-500/25 transition-all duration-200 hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Cetak PDF
                </a>
                <a href="{{ route('satker.kendaraans.index') }}"
                        class="flex-1 lg:flex-none inline-flex items-center px-4 py-2.5 bg-slate-800 text-slate-400 rounded-xl font-semibold text-sm hover:bg-slate-200 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="bg-slate-900 rounded-2xl border border-white/10 shadow-sm p-5">
            <form action="{{ route('satker.kendaraans.laporan-transfer') }}" method="GET"
                class="flex flex-col md:flex-row gap-4 items-end">
                <div class="w-full md:w-auto">
                    <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-400 mb-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Mulai Tanggal
                    </label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="flatpickr w-full bg-slate-800/50 border border-white/10 rounded-xl text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all">
                </div>
                <div class="w-full md:w-auto">
                    <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-400 mb-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Sampai Tanggal
                    </label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="flatpickr w-full bg-slate-800/50 border border-white/10 rounded-xl text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl font-semibold text-sm hover:from-emerald-600 hover:to-teal-700 shadow-lg shadow-emerald-500/20 transition-all duration-200 hover:-translate-y-0.5">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('satker.kendaraans.laporan-transfer') }}"
                        class="inline-flex items-center px-4 py-2.5 bg-slate-800 text-slate-400 rounded-xl font-semibold text-sm hover:bg-slate-200 transition-all duration-200">Reset</a>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-slate-900 rounded-2xl border border-white/10 shadow-sm overflow-hidden">
            <!-- Table Header Info -->
            <div class="px-4 py-3 border-b border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div>
                        <h3 class="font-semibold text-slate-200">Riwayat Transfer</h3>
                        <p class="text-xs text-slate-400">Total {{ $riwayats->total() }} transaksi</p>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-800/50 border-b border-white/5">
                            <th colspan="8" class="px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <form action="{{ route('satker.kendaraans.laporan-transfer') }}" method="GET"
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
                                Kendaraan (Sumber)</th>
                            <th
                                class="px-4 py-3 text-center text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider">
                                Jenis BBM</th>
                            <th
                                class="px-4 py-3 text-center text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider w-10">
                            </th>
                            <th
                                class="px-4 py-3 text-left text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider">
                                Personel (Tujuan)</th>
                            <th
                                class="px-4 py-3 text-right text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider">
                                Jumlah</th>
                            <th
                                class="px-4 py-3 text-left text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider">
                                Keterangan</th>
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
                                    <div>
                                        <span
                                            class="text-sm font-medium text-slate-200">{{ $riwayat->created_at->format('d/m/Y') }}</span>
                                        <span
                                            class="text-xs text-slate-400 ml-1">{{ $riwayat->created_at->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        @if($riwayat->tipe_log == 'masuk' || ($riwayat->tipe_log == 'transfer' && !$riwayat->kendaraan_id))
                                            <div class="w-7 h-7 bg-rose-50 rounded-lg flex items-center justify-center text-rose-500">
                                                <i class="fas fa-gas-pump text-[10px]"></i>
                                            </div>
                                            <div>
                                                <span class="text-xs font-semibold text-rose-500">STOK PUSAT</span>
                                                <span class="text-xs text-slate-400 block">SUPER ADMIN</span>
                                            </div>
                                        @else
                                            <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center">
                                                <i class="fas fa-car-side text-[10px] text-blue-600"></i>
                                            </div>
                                            <div>
                                                <span class="text-xs font-semibold text-slate-200">{{ $riwayat->kendaraan->no_polisi ?? '-' }}</span>
                                                <span class="text-xs text-slate-400 block">{{ $riwayat->kendaraan->jenis_kendaraan ?? '-' }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="px-2.5 py-1 rounded-lg text-xs font-bold {{ ($riwayat->kendaraan->jenis_bbm ?? '') == 'Pertamina Dex' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ $riwayat->kendaraan->jenis_bbm ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center">
                                        <div
                                            class="w-6 h-6 rounded-full bg-gradient-to-b from-emerald-100 to-teal-100 border border-emerald-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        @if($riwayat->tipe_log == 'masuk' || ($riwayat->tipe_log == 'transfer' && $riwayat->tujuan_kendaraan_id))
                                            <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center">
                                                <i class="fas fa-car-side text-[10px] text-emerald-600"></i>
                                            </div>
                                            <div>
                                                <span class="text-xs font-semibold text-slate-200">{{ $riwayat->tujuanKendaraan->no_polisi ?? ($riwayat->kendaraan->no_polisi ?? '-') }}</span>
                                                <span class="text-xs text-slate-400 block">{{ $riwayat->tujuanKendaraan->jenis_kendaraan ?? ($riwayat->kendaraan->jenis_kendaraan ?? '-') }}</span>
                                            </div>
                                        @elseif($riwayat->personel_id)
                                            <div class="w-7 h-7 rounded-lg bg-purple-100 flex items-center justify-center">
                                                <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="text-sm font-medium text-slate-200">{{ $riwayat->personel->nama ?? '-' }}</span>
                                                <span class="text-xs text-slate-400 block">NRP: {{ $riwayat->personel->nrp ?? '-' }}</span>
                                            </div>
                                        @else
                                            <div class="w-7 h-7 rounded-lg bg-rose-100 flex items-center justify-center">
                                                <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="text-xs font-semibold text-rose-500">PUSAT (POTONGAN)</span>
                                                <span class="text-xs text-slate-400 block">Pengurangan Saldo</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span
                                        class="text-xs font-semibold text-emerald-500">{{ number_format($riwayat->jumlah, 0, ',', '.') }}
                                        L</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-xs text-slate-400">{{ $riwayat->keterangan ?? '-' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                            </svg>
                                        </div>
                                        <p class="text-slate-400 font-medium">Belum ada riwayat transfer</p>
                                        <p class="text-slate-400 text-sm mt-1">Coba ubah filter tanggal atau lakukan
                                            transfer terlebih dahulu</p>
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

        <!-- Summary Card: Total per Jenis BBM -->
        <div class="bg-slate-900 rounded-2xl border border-white/10 shadow-sm overflow-hidden w-full md:w-1/2">
            <div class="px-4 py-3 border-b border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <form action="{{ route('satker.kendaraans.laporan-transfer') }}" method="GET" class="mr-1">
                        <x-per-page :current="request('per_page', 15)" />
                    </form>
                    <div>
                        <h3 class="font-semibold text-slate-200">Total Transfer per Jenis BBM</h3>
                        <p class="text-xs text-slate-400">Berdasarkan data yang difilter</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <table class="w-full">
                    <tbody class="divide-y divide-white/5">
                        @foreach($summary as $jenis => $total)
                            <tr>
                                <td class="py-3 flex items-center gap-2">
                                    <span
                                        class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $jenis == 'Pertamina Dex' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ $jenis }}
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <span class="text-sm font-bold text-white">{{ rtrim(rtrim(number_format($total, 2, ',', '.'), '0'), ',') }}
                                        Liter</span>
                                </td>
                            </tr>
                        @endforeach
                        @if($summary->isEmpty())
                            <tr>
                                <td colspan="2" class="py-4 text-sm text-center text-slate-400">Tidak ada data transfer</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>