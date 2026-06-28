<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Riwayat Pengisian BBM</h1>
                <p class="mt-1 text-xs sm:text-sm font-medium text-slate-400">Histori pengisian BBM seluruh kendaraan dari semua Satuan Kerja.</p>
            </div>
            <a href="{{ route('admin.riwayat.print', request()->all()) }}" target="_blank"
                class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 bg-rose-600 text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-rose-700 shadow-lg shadow-rose-500/30 transition-all active:scale-95 gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                Cetak PDF
            </a>
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
        <div class="bg-slate-900 border border-white/5 rounded-2xl border border-white/10 shadow-sm p-4 sm:p-5">
            <form method="GET" action="{{ route('admin.riwayat.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Dari Tanggal</label>
                        <input type="text" placeholder="Pilih Tanggal" name="dari" value="{{ request('dari') }}"
                            class="flatpickr w-full h-11 px-4 border-white/10 rounded-xl text-xs font-bold text-slate-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all bg-slate-800/50">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Sampai Tanggal</label>
                        <input type="text" placeholder="Pilih Tanggal" name="sampai" value="{{ request('sampai') }}"
                            class="flatpickr w-full h-11 px-4 border-white/10 rounded-xl text-xs font-bold text-slate-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all bg-slate-800/50">
                    </div>
                    <div>
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
                        <a href="{{ route('admin.riwayat.index') }}"
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


        <!-- Summary Statistics (Terfilter) -->
        <div class="bg-slate-900 rounded-2xl border border-white/10 shadow-sm p-6 overflow-hidden">
            <h3 class="text-sm font-black text-slate-200 uppercase tracking-widest mb-4 flex items-center gap-2">
                <div class="w-1.5 h-4 bg-indigo-500 rounded-full"></div>
                Total Pengisian BBM (Terfilter)
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
                @foreach($summaryBbm as $jenis => $total)
                    @php
                        $bbmColors = [
                            'Pertalite' => 'from-emerald-500/10 to-emerald-500/5 border-emerald-500/20 text-emerald-400 icon-emerald-500',
                            'Pertamax' => 'from-blue-500/10 to-blue-500/5 border-blue-500/20 text-blue-400 icon-blue-500',
                            'Solar' => 'from-amber-500/10 to-amber-500/5 border-amber-500/20 text-amber-400 icon-amber-500',
                            'Dexlite' => 'from-purple-500/10 to-purple-500/5 border-purple-500/20 text-purple-400 icon-purple-500',
                            'Pertamina Dex' => 'from-cyan-500/10 to-cyan-500/5 border-cyan-500/20 text-cyan-400 icon-cyan-500',
                        ];
                        $style = $bbmColors[$jenis] ?? 'from-slate-500/10 to-slate-500/5 border-slate-500/20 text-slate-400 icon-slate-500';
                    @endphp
                    <div class="relative overflow-hidden bg-gradient-to-br {{ explode(' icon-', $style)[0] }} border p-4 rounded-2xl shadow-sm hover:shadow-md transition-all group">
                        <div class="absolute -right-2 -top-2 opacity-[0.03] group-hover:opacity-[0.06] transition-all transform group-hover:scale-110">
                            <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M18.82 12.14l-1.32-1.32-4.23 4.23-2.09-2.09-1.32 1.32 3.41 3.41z"/></svg>
                        </div>
                        <p class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-1 leading-none">{{ $jenis ?: 'Lainnya' }}</p>
                        <div class="flex items-baseline gap-1">
                            <span class="text-xl font-black">{{ rtrim(rtrim(number_format($total, 2, ',', '.'), '0'), ',') }}</span>
                            <span class="text-xs font-bold opacity-60">Liter</span>
                        </div>
                    </div>
                @endforeach

                {{-- Total Keseluruhan --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-indigo-500/20 to-indigo-600/10 border-indigo-500/30 p-4 rounded-2xl shadow-lg shadow-indigo-500/20 text-indigo-400 group lg:col-span-1">
                     <div class="absolute -right-2 -top-2 opacity-10 group-hover:opacity-20 transition-all transform group-hover:scale-110">
                        <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-indigo-400/80 mb-1 leading-none">GRAND TOTAL</p>
                    <div class="flex items-baseline gap-1">
                        <span class="text-2xl font-black">{{ number_format($stats['total_liter'], 0, ',', '.') }}</span>
                        <span class="text-xs font-bold text-indigo-400/60">Liter</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-slate-900 rounded-2xl border border-white/10 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-white/5 flex items-center gap-3">
                <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex items-center gap-3">
                    <div>
                        <h3 class="font-semibold text-slate-200">Daftar Transaksi BBM</h3>
                        <p class="text-xs text-slate-400">Total {{ $transaksis->total() }} transaksi</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-800/50 border-b border-white/5">
                            <th colspan="8" class="px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <form action="{{ route('admin.riwayat.index') }}" method="GET"
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
                                Satker</th>
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
                            <th
                                class="px-4 py-3 text-center text-[11px] font-medium tracking-wider text-slate-400 uppercase tracking-wider">
                                Aksi</th>
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
                                    <div class="flex items-center gap-2">
                                        @php
                                            $satker = $trx->satker ?? ($trx->kendaraan->satker ?? ($trx->personel->satker ?? null));
                                        @endphp
                                        <div
                                            class="flex-shrink-0 w-7 h-7 rounded-lg {{ $isPotong ? 'bg-gradient-to-br from-amber-500 to-orange-600' : 'bg-gradient-to-br from-indigo-500 to-indigo-600' }} flex items-center justify-center text-white font-bold text-[10px] shadow-sm">
                                            {{ strtoupper(substr($satker->nama_satker ?? '-', 0, 2)) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-xs font-medium text-slate-300">{{ $satker->nama_satker ?? '-' }}</span>
                                            @if($isPotong)
                                                <span class="text-[10px] font-bold text-amber-600 uppercase tracking-tighter">Potong Saldo</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="text-xs text-slate-300">{{ $trx->kendaraan->jenis_kendaraan ?? ($trx->personel->nama ?? '-') }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="text-xs font-semibold text-slate-200">{{ $trx->kendaraan->no_polisi ?? ($trx->personel->nrp ?? '-') }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $jenisBbm = $trx->jenis_bbm;
                                        $bbmColors = [
                                            'Pertalite' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                                            'Pertamax' => 'bg-blue-500/10 text-blue-400 border border-blue-500/20',
                                            'Solar' => 'bg-amber-500/10 text-amber-400 border border-amber-500/20',
                                            'Dexlite' => 'bg-purple-500/10 text-purple-400 border border-purple-500/20',
                                            'Pertamina Dex' => 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20',
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
                                <td class="px-4 py-3 text-center">
                                    @if(!$isPotong)
                                        <form id="delete-form-{{ $trx->id }}"
                                            action="{{ route('admin.riwayat.destroy', $trx->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="topup_password_confirm" id="pwd-{{ $trx->id }}">
                                            <button type="button"
                                                class="text-rose-500 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 p-2 rounded-lg transition-colors"
                                                onclick="confirmDelete('{{ $trx->id }}', '{{ number_format($trx->liter, 0, ',', '.') }}')"
                                                title="Batalkan Transaksi">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M3 6h18"></path>
                                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400 font-medium italic">Fixed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
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
                            <th colspan="7" class="px-4 py-3 text-right text-xs font-bold text-slate-400 uppercase tracking-widest">
                                TOTAL PADA DATA INI (TERFILTER)
                            </th>
                            <th class="px-4 py-3 text-right">
                                <span class="text-lg font-black text-indigo-600">
                                    {{ number_format($stats['total_liter'], 0, ',', '.') }} L
                                </span>
                            </th>
                            <th></th>
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

    </div>
    @push('scripts')
        <script>
            function confirmDelete(transaksiId, liter) {
                Swal.fire({
                    title: 'Batalkan Transaksi?',
                    html: 'Saldo sebesar <b>' + liter + ' Liter</b> akan dikembalikan secara otomatis.<br><br>Masukkan PIN Top Up Anda untuk melanjutkan:',
                    icon: 'warning',
                    input: 'password',
                    inputAttributes: {
                        autocapitalize: 'off',
                        placeholder: 'Masukkan 6 digit PIN'
                    },
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Batalkan!',
                    cancelButtonText: 'Batal',
                    preConfirm: (password) => {
                        if (!password) {
                            Swal.showValidationMessage('PIN Top Up wajib diisi')
                        }
                        return password
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Set the hidden input value
                        document.getElementById('pwd-' + transaksiId).value = result.value;
                        // Submit the form
                        document.getElementById('delete-form-' + transaksiId).submit();
                    }
                })
            }
        </script>
    @endpush
</x-app-layout>