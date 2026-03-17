<x-app-layout>
    <div class="p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Riwayat Pengisian BBM</h1>
                <p class="mt-1 text-xs sm:text-sm font-medium text-slate-500">Histori pengisian BBM seluruh kendaraan dari semua Satuan Kerja.</p>
            </div>
            <a href="{{ route('admin.riwayat.print', request()->all()) }}" target="_blank"
                class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 bg-rose-600 text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-rose-700 shadow-lg shadow-rose-200 transition-all active:scale-95 gap-2">
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
            <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-5">
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
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($stats['total_transaksi']) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-5">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-emerald-100 text-emerald-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium">Total Pengisian</p>
                        <p class="text-2xl font-bold text-slate-800">
                            {{ number_format($stats['total_liter'], 0, ',', '.') }} <span
                                class="text-sm font-medium text-slate-400">Liter</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5">
            <form method="GET" action="{{ route('admin.riwayat.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Dari Tanggal</label>
                        <input type="date" name="dari" value="{{ request('dari') }}"
                            class="flatpickr w-full h-11 px-4 border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all bg-slate-50/50">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Sampai Tanggal</label>
                        <input type="date" name="sampai" value="{{ request('sampai') }}"
                            class="flatpickr w-full h-11 px-4 border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all bg-slate-50/50">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Satker</label>
                        <select name="satker_id" id="filter_satker_id"
                            class="tom-select w-full rounded-xl border-slate-200 bg-slate-50 text-xs font-bold text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
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
                            class="flex-1 h-11 bg-indigo-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('admin.riwayat.index') }}"
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
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex items-center gap-3">
                    <div>
                        <h3 class="font-semibold text-slate-800">Daftar Transaksi BBM</h3>
                        <p class="text-xs text-slate-400">Total {{ $transaksis->total() }} transaksi</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th colspan="8" class="px-6 py-3">
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
                        <tr class="bg-slate-50/70">
                            <th
                                class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-12">
                                No</th>
                            <th
                                class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Tanggal / Waktu</th>
                            <th
                                class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Satker</th>
                            <th
                                class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Jenis Kendaraan</th>
                            <th
                                class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Nopol</th>
                            <th
                                class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Jenis BBM</th>
                            <th
                                class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Nama Driver</th>
                            <th
                                class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Jumlah Liter</th>
                            <th
                                class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transaksis as $trx)
                            @php
                                $isPotong = ($trx->row_type ?? 'pengisian') === 'potong_saldo';
                            @endphp
                            <tr class="{{ $isPotong ? 'bg-amber-50/30' : 'hover:bg-slate-50/50' }} transition-colors">
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="text-sm font-semibold text-slate-500">{{ $loop->iteration + ($transaksis->currentPage() - 1) * $transaksis->perPage() }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <span
                                            class="text-sm font-semibold text-slate-800">{{ \Carbon\Carbon::parse($trx->tanggal)->setTimezone('Asia/Makassar')->format('d M Y') }}</span>
                                        <span
                                            class="block text-xs text-slate-400">{{ \Carbon\Carbon::parse($trx->tanggal)->setTimezone('Asia/Makassar')->format('H:i') }}
                                            WITA</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @php
                                            $satker = $trx->satker ?? ($trx->kendaraan->satker ?? ($trx->personel->satker ?? null));
                                        @endphp
                                        <div
                                            class="flex-shrink-0 w-7 h-7 rounded-lg {{ $isPotong ? 'bg-gradient-to-br from-amber-500 to-orange-600' : 'bg-gradient-to-br from-indigo-500 to-indigo-600' }} flex items-center justify-center text-white font-bold text-[10px] shadow-sm">
                                            {{ strtoupper(substr($satker->nama_satker ?? '-', 0, 2)) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-slate-700">{{ $satker->nama_satker ?? '-' }}</span>
                                            @if($isPotong)
                                                <span class="text-[10px] font-bold text-amber-600 uppercase tracking-tighter">Potong Saldo</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm text-slate-700">{{ $trx->kendaraan->jenis_kendaraan ?? ($trx->personel->nama ?? '-') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm font-bold text-slate-800">{{ $trx->kendaraan->no_polisi ?? ($trx->personel->nrp ?? '-') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $jenisBbm = $trx->jenis_bbm;
                                        $bbmColors = [
                                            'Pertalite' => 'bg-green-100 text-green-700',
                                            'Pertamax' => 'bg-blue-100 text-blue-700',
                                            'Solar' => 'bg-amber-100 text-amber-700',
                                            'Dexlite' => 'bg-purple-100 text-purple-700',
                                            'Pertamina Dex' => 'bg-cyan-100 text-cyan-700',
                                        ];
                                        $color = $bbmColors[$jenisBbm] ?? 'bg-slate-100 text-slate-700';
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $color }}">
                                        {{ $jenisBbm ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($isPotong)
                                        <span class="text-xs text-slate-500 italic leading-tight block max-w-[150px] truncate" title="{{ $trx->keterangan }}">
                                            {{ $trx->keterangan }}
                                        </span>
                                    @else
                                        <span class="text-sm font-medium text-slate-700">{{ $trx->nama_driver ?? ($trx->personel->nama ?? '-') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-bold {{ $isPotong ? 'text-rose-600' : 'text-emerald-600' }}">
                                        {{ $isPotong ? '-' : '' }}{{ number_format($trx->liter, 0, ',', '.') }} L
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
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
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-slate-500 font-medium">Belum ada riwayat pengisian BBM</p>
                                        <p class="text-sm text-slate-400 mt-1">Transaksi akan muncul setelah kendaraan
                                            melakukan pengisian BBM</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($transaksis->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $transaksis->links() }}
                </div>
            @endif
        </div>

        <!-- Summary Card -->
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden p-6 mt-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                Total Pengisian BBM
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($summaryBbm as $jenis => $total)
                    @php
                        $bbmColors = [
                            'Pertalite' => 'bg-green-50 border-green-100 text-green-700',
                            'Pertamax' => 'bg-blue-50 border-blue-100 text-blue-700',
                            'Solar' => 'bg-amber-50 border-amber-100 text-amber-700',
                            'Dexlite' => 'bg-purple-50 border-purple-100 text-purple-700',
                        ];
                        $style = $bbmColors[$jenis] ?? 'bg-slate-50 border-slate-100 text-slate-700';
                    @endphp
                    <div class="p-4 rounded-xl border {{ $style }}">
                        <p class="text-xs font-semibold opacity-70 mb-1 uppercase tracking-wider">{{ $jenis }}</p>
                        <p class="text-xl font-bold">{{ number_format($total, 0, ',', '.') }} <span
                                class="text-sm font-medium opacity-70">L</span></p>
                    </div>
                @endforeach
                <!-- Total Keseluruhan (Optional, but user asked for per Fuel Type, but usually total is also good. User said 'SESUAI DENGAN JENIS BBM NYA'. existing stats has total_liter already) -->
            </div>
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