<x-app-layout>
    <div class="max-w-7xl mx-auto p-2 sm:p-6 lg:p-8 space-y-6 px-2 sm:px-6 lg:px-8" x-data="hutangComponent">
        <!-- Page Title -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h1 class="text-2xl sm:text-3xl font-bold text-white border-b-2 border-indigo-500/50 pb-2 inline-block">
                Monitoring Hutang BBM</h1>

            <!-- Summary Outstanding per BBM (Dynamic) -->
            <div class="flex flex-wrap gap-3">
                @forelse($summaryHutang as $bbm => $total)
                    <div
                        class="bg-slate-900 border border-white/5 px-4 py-2 rounded-2xl border border-white/10 shadow-sm flex items-center gap-3 animate-in fade-in slide-in-from-right-4 duration-500">
                        <div class="w-2 h-8 bg-rose-500 rounded-full"></div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">
                                {{ $bbm }}
                            </p>
                            <p class="text-lg font-black text-rose-600 leading-none">
                                {{ rtrim(rtrim(number_format($total, 2, ',', '.'), '0'), ',') }} <span
                                    class="text-xs font-bold text-slate-400">L</span>
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="bg-emerald-50 px-4 py-2 rounded-2xl border border-emerald-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-xs font-semibold text-emerald-500">Semua Terbayar</span>
                    </div>
                @endforelse
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Condensed Filter & Action Bar -->
        <div class="bg-slate-900 border border-white/5 rounded-3xl border border-white/10/60 shadow-lg shadow-black/20 p-4 mb-6 relative overflow-hidden group">
            <!-- Subtle Decorative Flare -->
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-500/5 rounded-full blur-2xl"></div>
            
            <form action="{{ route('admin.hutang.index') }}" method="GET" class="relative z-10 grid grid-cols-1 sm:grid-cols-2 lg:flex items-end gap-3 lg:gap-4">
                <!-- Satker -->
                <div class="w-full lg:flex-1 lg:min-w-[200px]">
                    <x-input-label for="filter_satker_id" value="Satker" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-1" />
                    <div class="relative group/input">
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
                </div>

                <!-- Status -->
                <div class="w-full sm:w-44">
                    <x-input-label for="filter_status" value="Status" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-1" />
                    <div class="relative group/input">
                        <select name="status" id="filter_status"
                            class="tom-select w-full">
                            <option value="">Semua Status</option>
                            <option value="belum_dibayar" {{ request('status') === 'belum_dibayar' ? 'selected' : '' }}>BELUM</option>
                            <option value="sudah_dibayar" {{ request('status') === 'sudah_dibayar' ? 'selected' : '' }}>LUNAS</option>
                        </select>
                    </div>
                </div>

                <!-- Date Range Group -->
                <div class="w-full sm:w-auto flex items-end gap-3 sm:gap-4">
                    <!-- Start Date -->
                    <div class="flex-1 sm:w-40">
                        <x-input-label for="start_date" value="Dari" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-1" />
                        <div class="relative group/input">
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                class="flatpickr block w-full py-2 bg-slate-800/50 border-white/10 focus:bg-slate-900 border border-white/5 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 rounded-xl transition-all shadow-sm font-bold text-xs text-slate-300" />
                        </div>
                    </div>

                    <!-- End Date -->
                    <div class="flex-1 sm:w-40">
                        <x-input-label for="end_date" value="Sampai" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-1" />
                        <div class="relative group/input">
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                class="flatpickr block w-full py-2 bg-slate-800/50 border-white/10 focus:bg-slate-900 border border-white/5 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 rounded-xl transition-all shadow-sm font-bold text-xs text-slate-300" />
                        </div>
                    </div>
                </div>

                <!-- Buttons Group -->
                <div class="w-full lg:w-auto grid grid-cols-2 lg:flex items-center gap-2 sm:col-span-2 lg:col-span-1">
                    <button type="submit"
                        class="flex-1 lg:flex-none px-4 py-2.5 bg-indigo-600 text-white font-black rounded-xl shadow-lg shadow-indigo-500/20 hover:bg-indigo-700 active:scale-95 transition-all text-[10px] uppercase tracking-widest flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span class="hidden xl:inline">Filter</span>
                    </button>
                    
                    @if(request()->hasAny(['satker_id', 'status', 'start_date', 'end_date']))
                        <a href="{{ route('admin.hutang.index') }}"
                            class="flex-1 lg:flex-none px-4 py-2.5 bg-slate-800 text-slate-400 font-black rounded-xl hover:bg-slate-200 active:scale-95 transition-all text-[10px] uppercase tracking-widest flex items-center justify-center gap-2" title="Reset Filter">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span class="hidden xl:inline">Reset</span>
                        </a>
                    @endif

                    <button type="button" @click="openCreateModal()"
                        class="flex-1 lg:flex-none px-4 py-2.5 bg-emerald-600 text-white font-black rounded-xl shadow-lg shadow-emerald-500/20 hover:bg-emerald-700 active:scale-95 transition-all text-[10px] uppercase tracking-widest flex items-center justify-center group/btn gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="hidden xl:inline">Tambah</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Table Data -->
        <div class="bg-slate-900 border border-white/5 rounded-2xl border border-white/10 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-white/5 flex justify-between items-center bg-slate-800/50">
                <h3 class="text-sm font-bold text-slate-300 uppercase tracking-wider">Data Hutang BBM</h3>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Tampilkan</span>
                    <form action="{{ route('admin.hutang.index') }}" method="GET" class="inline">
                        @foreach(request()->except('per_page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <select name="per_page" onchange="this.form.submit()"
                            class="block border-white/10 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-xs py-1.5 font-bold text-slate-300">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ request('per_page') == 15 || !request('per_page') ? 'selected' : '' }}>
                                15</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-[10px] text-slate-400 uppercase bg-slate-800/50/80 border-b border-white/10">
                        <tr>
                            <th class="px-4 py-3 font-bold">Tanggal</th>
                            <th class="px-4 py-3 font-bold">Satker</th>
                            <th class="px-4 py-3 font-bold">Kendaraan</th>
                            <th class="px-4 py-3 font-bold">Driver</th>
                            <th class="px-4 py-3 font-bold">Jumlah Bon</th>
                            <th class="px-4 py-3 font-bold">Petugas Pencatat</th>
                            <th class="px-4 py-3 font-bold">Status</th>
                            @if(auth()->user()->role !== 'kasubbag')
                                <th class="px-4 py-3 font-bold text-center border-l border-white/10 bg-slate-800/30">
                                    Pembayaran</th>
                                <th class="px-4 py-3 font-bold text-center border-l border-white/10">Kelola</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($hutangs as $hutang)
                            <tr class="hover:bg-slate-800/50 transition">
                                <td class="px-4 py-2">
                                    <div class="text-[10px] sm:text-xs font-bold text-white">
                                        @if($hutang->tanggal_bon)
                                            {{ \Carbon\Carbon::parse($hutang->tanggal_bon)->format('d-m-Y') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->format('d-m-Y') }}
                                        @endif
                                    </div>
                                    <div class="text-[9px] sm:text-[10px] text-slate-400 uppercase tracking-tight">
                                        @if($hutang->tanggal_bon)
                                            CATATAN MANUAL
                                        @else
                                            {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->format('H:i') }}
                                            WITA
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-2 text-[10px] sm:text-xs font-semibold text-slate-200 leading-tight">{{ $hutang->satker->nama_satker }}</td>
                                <td class="px-4 py-2">
                                    <div class="text-[11px] sm:text-xs font-black text-white leading-tight uppercase">{{ $hutang->nopol }}</div>
                                    <div class="text-[9px] sm:text-[10px] text-slate-400">{{ $hutang->jenis_kendaraan }}</div>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="font-bold text-slate-200">{{ $hutang->nama_driver ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-2">
                                    @php
                                        $bbmUpper = strtoupper($hutang->jenis_bbm);
                                        $bbmColorClass = str_contains($bbmUpper, 'DEX')
                                            ? 'bg-blue-50 text-blue-700 border-blue-200'
                                            : 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2 py-1 {{ $bbmColorClass }} rounded text-xs font-bold">
                                        {{ number_format($hutang->jumlah_bon, 0, ',', '.') }} L {{ $hutang->jenis_bbm }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-slate-300 text-xs">{{ $hutang->petugas->name ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    @if($hutang->status === 'belum_dibayar')
                                        <span
                                            class="px-2 py-0.5 bg-rose-100 text-rose-700 border border-rose-200 rounded-lg text-[10px] font-bold uppercase">BELUM
                                            BAYAR</span>
                                    @else
                                        <div class="flex flex-col gap-0.5">
                                            <span
                                                class="px-2 py-0.5 bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-lg text-[10px] font-bold uppercase w-fit">LUNAS</span>
                                            <span class="text-[9px] text-slate-400">Oleh:
                                                {{ $hutang->adminBayar->name ?? '-' }}</span>
                                            <span
                                                class="text-[9px] text-slate-400">{{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->format('d-m-Y') }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                @if(auth()->user()->role !== 'kasubbag')
                                    <td class="px-4 py-2 border-l border-white/5 bg-slate-800/50/20">
                                        <div class="flex items-center justify-center">
                                            @if($hutang->status === 'belum_dibayar')
                                                <button
                                                    @click="openModal({{ $hutang->id }}, {{ $hutang->satker_id }}, '{{ $hutang->jenis_bbm }}', '{{ $hutang->nopol }}', {{ $hutang->jumlah_bon }})"
                                                    class="px-3 py-1.5 bg-indigo-600 font-bold text-white rounded-lg hover:bg-indigo-700 transition shadow-sm text-[10px] uppercase tracking-wider">
                                                    Bayar
                                                </button>
                                            @else
                                                <span class="text-[10px] font-bold text-slate-400 italic">Selesai</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 border-l border-white/5">
                                        <div class="flex items-center justify-center gap-2">
                                            @php
                                                $editData = [
                                                    'id' => $hutang->id,
                                                    'satker_id' => $hutang->satker_id,
                                                    'nopol' => $hutang->nopol,
                                                    'jenis_kendaraan' => $hutang->jenis_kendaraan,
                                                    'nama_driver' => $hutang->nama_driver,
                                                    'jenis_bbm' => $hutang->jenis_bbm,
                                                    'jumlah_bon' => $hutang->jumlah_bon,
                                                    'tanggal_bon' => $hutang->tanggal_bon ?? $hutang->created_at->format('Y-m-d')
                                                ];
                                            @endphp
                                            @if($hutang->status === 'belum_dibayar')
                                                <button type="button"
                                                    @click="openEditModal(@js($editData))"
                                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors border border-blue-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>

                                                <form action="{{ route('admin.hutang.destroy', $hutang) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="button"
                                                        data-confirm="Apakah Anda yakin ingin menghapus data hutang ini?"
                                                        data-confirm-type="warning"
                                                        class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors border border-rose-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-[10px] font-bold text-slate-400 italic">No Actions</span>
                                            @endif
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role === 'kasubbag' ? 7 : 9 }}"
                                    class="px-6 py-8 text-center text-slate-400 italic">Tidak ada data hutang
                                    ditemukan.</td>
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

        <!-- Payment Modal -->
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal" x-transition.opacity
                    class="fixed inset-0 bg-slate-900/60 transition-opacity backdrop-blur-sm" aria-hidden="true"
                    @click="showModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    class="inline-block align-bottom bg-slate-900 border border-white/5 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full">

                    <!-- Modal Header -->
                    <div
                        class="bg-indigo-600 px-6 py-4 text-white flex justify-between items-center bg-gradient-to-r from-indigo-600 to-indigo-700">
                        <div class="flex items-center gap-3">
                            <div class="bg-slate-900 border border-white/5/20 p-2 rounded-xl">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-black tracking-tight" id="modal-title">PEMBAYARAN HUTANG</h3>
                        </div>
                        <button @click="showModal = false" class="text-white/80 hover:text-white transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="bg-slate-900 border border-white/5 px-6 pt-6 pb-6 border-b border-white/5">
                        <div class="mb-6">
                            <!-- Summary Card -->
                            <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-4 flex flex-col gap-3">
                                <div class="flex justify-between items-center border-b border-white/10 pb-2">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Detail
                                        Hutang</span>
                                    <span
                                        class="text-indigo-600 font-bold text-xs bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100 uppercase"
                                        x-text="hutangData.bbm"></span>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase">No. Polisi</p>
                                        <p class="text-lg font-black text-slate-200" x-text="hutangData.nopol"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase">Jumlah Bon</p>
                                        <p class="text-lg font-black text-rose-600" x-text="`${hutangData.jumlah} L`">
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form :action="`/admin/hutang/${selectedHutang}/bayar`" method="POST" id="paymentForm"
                            class="space-y-4">
                            @csrf
                            <div>
                                <input type="hidden" name="nopol" :value="hutangData.nopol">
                                <p class="text-sm font-bold text-slate-400 mb-4 bg-indigo-50 p-4 rounded-2xl border border-indigo-100/50 flex gap-3">
                                    <svg class="h-6 w-6 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                    </svg>
                                    <span>
                                        Pembayaran ini akan secara otomatis memotong saldo kendaraan dengan no. polisi <span class="font-black text-indigo-700" x-text="hutangData.nopol"></span>.
                                    </span>
                                </p>
                            </div>
                        </form>
                    </div>

                    <div class="bg-slate-900 border border-white/5 px-6 py-5 flex flex-col sm:flex-row-reverse gap-3 rounded-b-3xl">
                        <button type="submit" form="paymentForm"
                            class="w-full sm:w-auto px-8 py-3.5 bg-indigo-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-500/20 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 active:scale-95 transition-all text-sm uppercase tracking-widest disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none">
                            KONFIRMASI PEMBAYARAN
                        </button>
                        <button type="button" @click="showModal = false"
                            class="w-full sm:w-auto px-8 py-3.5 bg-slate-800 text-slate-400 font-bold rounded-2xl hover:bg-slate-200 transition-all text-sm uppercase tracking-widest">
                            BATAL
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEditModal" x-transition.opacity
                    class="fixed inset-0 bg-slate-900/60 transition-opacity backdrop-blur-sm" aria-hidden="true"
                    @click="showEditModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showEditModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    class="inline-block align-bottom bg-slate-900 border border-white/5 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full border border-white/10">

                    <!-- Modal Header -->
                    <div
                        class="bg-blue-600 px-6 py-4 text-white flex justify-between items-center bg-gradient-to-r from-blue-600 to-blue-700">
                        <div class="flex items-center gap-3">
                            <div class="bg-slate-900 border border-white/5/20 p-2 rounded-xl">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-black tracking-tight">EDIT DATA HUTANG</h3>
                        </div>
                        <button @click="showEditModal = false"
                            class="text-white/80 hover:text-white transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form :action="'/admin/hutang/' + editData.id" method="POST" class="p-8 space-y-6">
                        @csrf @method('PUT')

                        <!-- Satker Selection -->
                        <div class="space-y-1.5">
                            <label for="edit_satker_id" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Unit Kerja / Satker</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors z-10">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <select name="satker_id" id="edit_satker_id" x-model="editData.satker_id" @change="fetchFormKendaraans($event.target.value); editData.kendaraan_id = ''"
                                    class="tom-select w-full">
                                    @foreach($satkers as $satker)
                                        <option value="{{ $satker->id }}">{{ $satker->nama_satker }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Kendaraan Selection -->
                        <div class="space-y-1.5">
                            <label for="edit_kendaraan_id" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Kendaraan Dinas</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors z-10">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                    </svg>
                                </div>
                                <select name="kendaraan_id" id="edit_kendaraan_id" x-model="editData.kendaraan_id" required
                                    :disabled="loadingFormKendaraan || !editData.satker_id"
                                    class="tom-select-dynamic w-full disabled:opacity-50">
                                    <option value="" disabled x-text="loadingFormKendaraan ? 'Sedang mengambil data...' : '-- Pilih Kendaraan --'"></option>
                                    <template x-for="kend in formKendaraans" :key="kend.id">
                                        <option :value="kend.id" x-text="`${kend.no_polisi} - ${kend.jenis_kendaraan} (${kend.jenis_bbm})`"></option>
                                    </template>
                                </select>
                            </div>
                            <div x-show="createSelectedKendaraan" x-cloak
                                :class="createVehicleHasSaldo ? 'bg-rose-50 border-rose-100 text-rose-700' : 'bg-emerald-50 border-emerald-100 text-emerald-700'"
                                class="flex items-start gap-2 rounded-xl border px-3 py-2 text-[10px] font-bold">
                                <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 9v-1m0 1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <div>Saldo Kendaraan: <span x-text="`${formatLiter(createSelectedSaldo)} L`"></span></div>
                                    <div x-show="createVehicleHasSaldo" class="mt-0.5 font-black">Saldo masih ada, hutang BBM tidak dapat disimpan.</div>
                                    <div x-show="!createVehicleHasSaldo" class="mt-0.5 font-black">Saldo kosong, kendaraan boleh hutang BBM.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Driver Name -->
                        <div class="space-y-1.5">
                            <label for="edit_nama_driver" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Driver</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors z-10">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input id="edit_nama_driver" name="nama_driver" type="text" x-model="editData.nama_driver" 
                                    class="block w-full pl-11 pr-4 py-2.5 bg-slate-800/50 border-white/10 focus:bg-slate-900 border border-white/5 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl transition-all shadow-sm font-bold text-xs text-slate-300" 
                                    placeholder="Masukkan nama pengemudi" required />
                            </div>
                        </div>

                        <!-- Date and Amount Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label for="edit_tanggal_bon" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Bon</label>
                                <div class="relative group">
                                    <input id="edit_tanggal_bon" name="tanggal_bon" type="date" x-model="editData.tanggal_bon" 
                                        class="flatpickr block w-full py-2.5 bg-slate-800/50 border-white/10 focus:bg-slate-900 border border-white/5 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl transition-all shadow-sm font-bold text-xs text-slate-300" 
                                        required />
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <div class="flex justify-between items-end">
                                    <label for="edit_jumlah_bon" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jumlah Bon (Liter)</label>
                                    <span x-show="editCurrentStock !== null" class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100">
                                        Max: <span x-text="(parseFloat(editCurrentStock) + parseFloat(editData.original_jumlah_bon || 0)).toFixed(0)"></span> L
                                    </span>
                                </div>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors z-10">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L7.05 15.12a2 2 0 00-1.022.547l-2.387 2.387a2 2 0 000 2.828l.141.141a2 2 0 002.828 0l2.628-2.628a2 2 0 012.33-.213l.317.158a2 2 0 002.33-.213l2.628-2.628a2 2 0 000-2.828l-.141-.141z" />
                                        </svg>
                                    </div>
                                    <input id="edit_jumlah_bon" name="jumlah_bon" type="number" step="1" x-model="editData.jumlah_bon" 
                                        :max="(parseFloat(editCurrentStock) + parseFloat(editData.original_jumlah_bon || 0)).toFixed(0)"
                                        class="block w-full pl-11 pr-4 py-2.5 bg-slate-800/50 border-white/10 focus:bg-slate-900 border border-white/5 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl transition-all shadow-sm font-black text-rose-600 text-xs" 
                                        placeholder="0.0" required />
                                </div>
                                <p x-show="editCurrentStock !== null && editData.jumlah_bon > (parseFloat(editCurrentStock) + parseFloat(editData.original_jumlah_bon || 0))" class="text-[10px] text-rose-500 font-bold ml-1 mt-1">Melebihi maksimal limit!</p>
                            </div>
                        </div>

                        <div class="pt-6 flex flex-col sm:flex-row-reverse gap-3 border-t border-white/5">
                            <button type="submit"
                                :disabled="editCurrentStock !== null && editData.jumlah_bon > (parseFloat(editCurrentStock) + parseFloat(editData.original_jumlah_bon || 0))"
                                class="w-full sm:w-auto px-10 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-black rounded-2xl shadow-xl shadow-blue-500/20 hover:shadow-blue-200 active:scale-95 transition-all text-sm uppercase tracking-widest disabled:opacity-50 disabled:cursor-not-allowed">
                                SIMPAN PERUBAHAN
                            </button>
                            <button type="button" @click="showEditModal = false"
                                class="w-full sm:w-auto px-10 py-4 bg-slate-800 text-slate-400 font-bold rounded-2xl hover:bg-slate-200 transition-all text-sm uppercase tracking-widest">
                                BATAL
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        <div x-show="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showCreateModal" x-transition.opacity
                    class="fixed inset-0 bg-slate-900/60 transition-opacity backdrop-blur-sm" aria-hidden="true"
                    @click="showCreateModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showCreateModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    class="inline-block align-bottom bg-slate-900 border border-white/5 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full border border-white/10">

                    <!-- Modal Header -->
                    <div
                        class="bg-emerald-600 px-6 py-4 text-white flex justify-between items-center bg-gradient-to-r from-emerald-600 to-emerald-700">
                        <div class="flex items-center gap-3">
                            <div class="bg-slate-900 border border-white/5/20 p-2 rounded-xl">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-black tracking-tight" id="modal-title-create">TAMBAH DATA HUTANG</h3>
                        </div>
                        <button @click="showCreateModal = false"
                            class="text-white/80 hover:text-white transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('admin.hutang.store') }}" method="POST" class="p-8 space-y-6">
                        @csrf
                        
                        <!-- Satker Selection -->
                        <div class="space-y-1.5">
                            <label for="create_satker_id" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Unit Kerja / Satker</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-600 transition-colors z-10">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <select name="satker_id" id="create_satker_id" x-model="createData.satker_id" @change="fetchFormKendaraans($event.target.value); createData.kendaraan_id = ''"
                                    class="tom-select w-full" required>
                                    <option value="">-- Pilih Satker --</option>
                                    @foreach($satkers as $satker)
                                        <option value="{{ $satker->id }}">{{ $satker->nama_satker }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Kendaraan Selection -->
                        <div class="space-y-1.5">
                            <label for="create_kendaraan_id" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Kendaraan Dinas</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-600 transition-colors z-10">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                    </svg>
                                </div>
                                <select name="kendaraan_id" id="create_kendaraan_id" x-model="createData.kendaraan_id" required
                                    :disabled="loadingFormKendaraan || !createData.satker_id"
                                    class="tom-select-dynamic w-full disabled:opacity-50">
                                    <option value="" disabled x-text="loadingFormKendaraan ? 'Sedang mengambil data...' : '-- Pilih Kendaraan --'"></option>
                                    <template x-for="kend in formKendaraans" :key="kend.id">
                                        <option :value="kend.id" x-text="`${kend.no_polisi} - ${kend.jenis_kendaraan} (${kend.jenis_bbm})`"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <!-- Driver Name -->
                        <div class="space-y-1.5">
                            <label for="create_nama_driver" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Driver / Pengemudi</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-600 transition-colors z-10">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input id="create_nama_driver" name="nama_driver" type="text" x-model="createData.nama_driver" 
                                    class="block w-full pl-11 pr-4 py-2.5 bg-slate-800/50 border-white/10 focus:bg-slate-900 border border-white/5 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-xl transition-all shadow-sm font-bold text-xs text-slate-300" 
                                    placeholder="Nama personil yang membon" required />
                            </div>
                        </div>

                        <!-- Date and Amount Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label for="create_tanggal_bon" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Bon</label>
                                <div class="relative group">
                                    <input id="create_tanggal_bon" name="tanggal_bon" type="date" x-model="createData.tanggal_bon" 
                                        class="flatpickr block w-full py-2.5 bg-slate-800/50 border-white/10 focus:bg-slate-900 border border-white/5 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-xl transition-all shadow-sm font-bold text-xs text-slate-300" 
                                        required />
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <div class="flex justify-between items-end">
                                    <label for="create_jumlah_bon" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jumlah Bon (Liter)</label>
                                    <span x-show="createCurrentStock !== null" class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">
                                        Stok Tangki: <span x-text="createCurrentStock"></span> L
                                    </span>
                                </div>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-600 transition-colors z-10">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L7.05 15.12a2 2 0 00-1.022.547l-2.387 2.387a2 2 0 000 2.828l.141.141a2 2 0 002.828 0l2.628-2.628a2 2 0 012.33-.213l.317.158a2 2 0 002.33-.213l2.628-2.628a2 2 0 000-2.828l-.141-.141z" />
                                        </svg>
                                    </div>
                                    <input id="create_jumlah_bon" name="jumlah_bon" type="number" step="1" x-model="createData.jumlah_bon" 
                                        :max="createCurrentStock"
                                        :disabled="!createData.kendaraan_id || createVehicleHasSaldo"
                                        class="block w-full pl-11 pr-4 py-2.5 bg-slate-800/50 border-white/10 focus:bg-slate-900 border border-white/5 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-xl transition-all shadow-sm font-black text-rose-600 text-xs disabled:opacity-50" 
                                        placeholder="0.0" required />
                                </div>
                                <p x-show="createCurrentStock !== null && createData.jumlah_bon > createCurrentStock" class="text-[10px] text-rose-500 font-bold ml-1 mt-1">Melebihi stok tangki!</p>
                            </div>
                        </div>

                        <div class="pt-6 flex flex-col sm:flex-row-reverse gap-3 border-t border-white/5">
                            <button type="submit"
                                :disabled="createSubmitDisabled"
                                class="w-full sm:w-auto px-10 py-4 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-black rounded-2xl shadow-xl shadow-emerald-500/20 hover:shadow-emerald-200 active:scale-95 transition-all text-sm uppercase tracking-widest disabled:opacity-50 disabled:cursor-not-allowed">
                                SIMPAN DATA HUTANG
                            </button>
                            <button type="button" @click="showCreateModal = false"
                                class="w-full sm:w-auto px-10 py-4 bg-slate-800 text-slate-400 font-bold rounded-2xl hover:bg-slate-200 transition-all text-sm uppercase tracking-widest">
                                BATAL
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const hutangComponentData = () => ({
            showModal: false,
                showEditModal: false,
                showCreateModal: false,
                selectedHutang: null,
                selectedSatker: null,
                stocks: @json($stocks ?? []),
                hutangData: { nopol: '', bbm: '', jumlah: 0 },
                editData: { id: '', satker_id: '', kendaraan_id: '', nama_driver: '', jumlah_bon: '', tanggal_bon: '', original_jumlah_bon: 0 },
                createData: { satker_id: '', kendaraan_id: '', nama_driver: '', jumlah_bon: '', tanggal_bon: '{{ date('Y-m-d') }}' },
                selectedKendaraan: '',
                selectedBbm: '',
                kendaraans: [],
                loadingKendaraan: false,
                get filteredKendaraans() {
                    return this.kendaraans.filter(k => k.jenis_bbm === this.selectedBbm);
                },
                formKendaraans: [],
                loadingFormKendaraan: false,
                normalizeBbmKey(bbm) {
                    if (!bbm) return '';
                    let upper = bbm.toUpperCase();
                    if (upper.includes('DEX')) return 'Pertamina Dex';
                    if (upper.includes('PERTAMAX')) return 'Pertamax';
                    return bbm;
                },
                formatLiter(value) {
                    const number = Number(value || 0);
                    return new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(number);
                },
                get createSelectedKendaraan() {
                    if (!this.createData.kendaraan_id || !this.formKendaraans.length) return null;
                    return this.formKendaraans.find(k => k.id == this.createData.kendaraan_id) || null;
                },
                get createSelectedSaldo() {
                    return this.createSelectedKendaraan ? Number(this.createSelectedKendaraan.saldo || 0) : 0;
                },
                get createVehicleHasSaldo() {
                    return this.createSelectedKendaraan && this.createSelectedSaldo > 0;
                },
                get createSubmitDisabled() {
                    if (!this.createData.kendaraan_id) return true;
                    if (this.createVehicleHasSaldo) return true;
                    if (this.createCurrentStock !== null && (this.createData.jumlah_bon > this.createCurrentStock || this.createCurrentStock <= 0)) return true;
                    return false;
                },
                get createCurrentStock() {
                    if (!this.createData.kendaraan_id || !this.formKendaraans.length) return null;
                    const kend = this.formKendaraans.find(k => k.id == this.createData.kendaraan_id);
                    return kend ? (this.stocks[this.normalizeBbmKey(kend.jenis_bbm)] ?? null) : null;
                },
                get editCurrentStock() {
                    if (!this.editData.kendaraan_id || !this.formKendaraans.length) return null;
                    const kend = this.formKendaraans.find(k => k.id == this.editData.kendaraan_id);
                    return kend ? (this.stocks[this.normalizeBbmKey(kend.jenis_bbm)] ?? null) : null;
                },
                init() {
                    this.$watch('createData.satker_id', val => {
                        if (val) this.fetchFormKendaraans(val);
                        else { 
                            this.formKendaraans = []; 
                            this.createData.kendaraan_id = '';
                            const el = document.getElementById('create_kendaraan_id');
                            if (el && el.tomselect) {
                                el.tomselect.clearOptions();
                                el.tomselect.disable();
                            }
                        }
                    });
                    this.$watch('editData.satker_id', val => {
                        if (val) this.fetchFormKendaraans(val);
                        else { 
                            this.formKendaraans = []; 
                            this.editData.kendaraan_id = '';
                            const el = document.getElementById('edit_kendaraan_id');
                            if (el && el.tomselect) {
                                el.tomselect.clearOptions();
                                el.tomselect.disable();
                            }
                        }
                    });
                },
                async fetchFormKendaraans(satkerId) {
                    if (!satkerId) {
                        this.formKendaraans = [];
                        return;
                    }
                    this.loadingFormKendaraan = true;
                    try {
                        const response = await fetch(`/admin/hutang/get-kendaraan?satker_id=${satkerId}`);
                        this.formKendaraans = await response.json();
                        
                        // Sync TomSelect after Alpine updates the DOM
                        this.$nextTick(() => {
                            const ids = ['create_kendaraan_id', 'edit_kendaraan_id'];
                            ids.forEach(id => {
                                const el = document.getElementById(id);
                                if (!el) return;
                                if (el.tomselect) {
                                    el.tomselect.clearOptions();
                                    el.tomselect.addOptions(this.formKendaraans.map(k => ({
                                        value: k.id,
                                        text: `${k.no_polisi} - ${k.jenis_kendaraan} (${k.jenis_bbm}) | Saldo: ${this.formatLiter(k.saldo)} L`
                                    })));
                                    el.tomselect.refreshOptions(false);
                                    el.tomselect.enable();
                                } else {
                                    // Initialize if not already done
                                    new TomSelect(el, {
                                        create: false,
                                        dropdownParent: 'body',
                                        onDropdownOpen: (dropdown) => {
                                            dropdown.style.zIndex = "9999";
                                        },
                                        labelField: 'text',
                                        valueField: 'value',
                                        searchField: ['text'],
                                        options: this.formKendaraans.map(k => ({
                                            value: k.id,
                                            text: `${k.no_polisi} - ${k.jenis_kendaraan} (${k.jenis_bbm}) | Saldo: ${this.formatLiter(k.saldo)} L`
                                        }))
                                    });
                                }
                            });
                        });
                    } catch (error) {
                        console.error('Gagal mengambil data kendaraan:', error);
                    } finally {
                        this.loadingFormKendaraan = false;
                    }
                },
                async openModal(id, satkerId, bbm, nopol, jumlah) {
                    this.selectedHutang = id;
                    this.selectedSatker = satkerId;
                    this.selectedBbm = bbm;
                    this.hutangData = { nopol, bbm, jumlah };
                    this.showModal = true;
                },
                async openEditModal(hutang) {
                    this.editData = {
                        id: hutang.id,
                        satker_id: hutang.satker_id,
                        kendaraan_id: '',
                        nama_driver: hutang.nama_driver,
                        jumlah_bon: hutang.jumlah_bon,
                        original_jumlah_bon: hutang.jumlah_bon,
                        tanggal_bon: hutang.tanggal_bon
                    };
                    
                    await this.fetchFormKendaraans(hutang.satker_id);
                    const foundKend = this.formKendaraans.find(k => k.no_polisi === hutang.nopol);
                    if (foundKend) {
                        this.editData.kendaraan_id = foundKend.id;
                        this.$nextTick(() => {
                            const el = document.getElementById('edit_kendaraan_id');
                            if (el && el.tomselect) el.tomselect.setValue(foundKend.id);
                        });
                    }
                    this.showEditModal = true;
                },
                openCreateModal() {
                    this.createData = { satker_id: '', kendaraan_id: '', nama_driver: '', jumlah_bon: '', tanggal_bon: '{{ date('Y-m-d') }}' };
                    
                    // Sync TomSelect
                    this.$nextTick(() => {
                        const el = document.getElementById('create_satker_id');
                        if (el && el.tomselect) el.tomselect.clear();
                    });

                }
            });
            
        if (typeof window.Alpine !== 'undefined') {
            window.Alpine.data('hutangComponent', hutangComponentData);
        } else {
            document.addEventListener('alpine:init', () => {
                window.Alpine.data('hutangComponent', hutangComponentData);
            });
        }
    </script>
</x-app-layout>
