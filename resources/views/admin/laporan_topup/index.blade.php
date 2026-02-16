<x-app-layout>
    <div class="p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Laporan Top Up</h1>
                <p class="mt-1 text-slate-500">Riwayat pengisian saldo kendaraan.</p>
            </div>
            <!-- Export Button -->
             <a href="{{ route('admin.laporan-topup.print', request()->query()) }}" target="_blank" class="inline-flex items-center px-5 py-2.5 bg-red-600 text-white rounded-xl font-semibold text-sm hover:bg-red-700 shadow-lg shadow-red-500/30 transition-all duration-200 hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak PDF
            </a>
        </div>

        <!-- Filter Card -->
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-4">
            <form action="{{ route('admin.laporan-topup.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="w-full md:w-auto">
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Mulai Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10">
                </div>
                <div class="w-full md:w-auto">
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10">
                </div>
                <div class="w-full md:w-1/4">
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Satker</label>
                    <select name="satker_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10">
                        <option value="">Semua Satker</option>
                        @foreach($satkers as $satker)
                            <option value="{{ $satker->id }}" {{ request('satker_id') == $satker->id ? 'selected' : '' }}>{{ $satker->nama_satker }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg font-semibold text-sm hover:bg-indigo-700 transition">Filter</button>
                    <a href="{{ route('admin.laporan-topup.index') }}" class="px-5 py-2 bg-slate-100 text-slate-600 rounded-lg font-semibold text-sm hover:bg-slate-200 transition">Reset</a>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
            <!-- Table Header Info -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">Riwayat Top Up</h3>
                        <p class="text-xs text-slate-400">Total {{ $riwayats->total() }} catatan</p>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-slate-50/70">
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">User (Admin)</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Satker</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nopol</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis Kendaraan</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis BBM</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Metode</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Jumlah (Liter)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($riwayats as $riwayat)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-semibold text-slate-500">{{ $loop->iteration + ($riwayats->currentPage() - 1) * $riwayats->perPage() }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-slate-800">{{ $riwayat->created_at->setTimezone('Asia/Makassar')->format('d/m/Y H:i') }} WITA</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500">
                                        {{ substr($riwayat->user->name ?? '?', 0, 1) }}
                                    </div>
                                    <span class="text-sm text-slate-700">{{ $riwayat->user->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-md bg-slate-100 text-xs font-semibold text-slate-600">
                                    {{ $riwayat->kendaraan->satker->nama_satker ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-slate-800">{{ $riwayat->kendaraan->no_polisi ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs font-medium text-slate-600">
                                    {{ $riwayat->kendaraan->jenis_kendaraan ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded-md bg-slate-100 text-xs font-semibold text-slate-600">
                                    {{ $riwayat->kendaraan->jenis_bbm ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $riwayat->metode == 'IMPORT' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ $riwayat->metode }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-sm font-bold text-emerald-600">
                                    +{{ number_format($riwayat->jumlah, 0, ',', '.') }} L
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <p class="text-slate-500 font-medium">Belum ada riwayat top up</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($riwayats->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $riwayats->links() }}
            </div>
            @endif
        </div>

        <!-- Summary Card -->
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden w-full md:w-1/2">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800">Total Keseluruhan per Jenis BBM</h3>
                <p class="text-xs text-slate-400">Berdasarkan data yang difilter</p>
            </div>
            <div class="p-6">
                <table class="w-full">
                    <tbody class="divide-y divide-slate-100">
                        @foreach($summary as $jenis => $total)
                        <tr>
                            <td class="py-3 text-sm font-medium text-slate-600">{{ $jenis }}</td>
                            <td class="py-3 text-sm font-bold text-slate-900 text-right">{{ number_format($total, 0, ',', '.') }} Liter</td>
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
