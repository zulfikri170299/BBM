<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush

    <div class="p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Data Kendaraan</h1>
                <p class="mt-1 text-slate-500">Semua kendaraan dari seluruh Satuan Kerja.</p>
            </div>
            <div class="flex gap-3">
                @if(auth()->user()->role === 'super_admin')
                    <button @click="$dispatch('open-import-kendaraan')"
                        class="inline-flex items-center justify-center w-10 h-10 bg-violet-600 text-white rounded-xl hover:bg-violet-700 shadow-lg shadow-violet-500/30 transition-all duration-200 hover:-translate-y-0.5 group relative"
                        title="Import Data Kendaraan">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span
                            class="absolute -bottom-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">Import
                            Data Kendaraan</span>
                    </button>
                    <button @click="$dispatch('open-import')"
                        class="inline-flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all duration-200 hover:-translate-y-0.5 group relative"
                        title="Import Top Up">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                            </path>
                        </svg>
                        <span
                            class="absolute -bottom-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">Import
                            Top Up</span>
                    </button>
                    <button @click="$dispatch('open-topup-select')"
                        class="inline-flex items-center justify-center w-10 h-10 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 transition-all duration-200 hover:-translate-y-0.5 group relative"
                        title="Top Up Saldo">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a1 1 0 11-2 0 1 1 0 012 0z">
                            </path>
                        </svg>
                        <!-- Tooltip -->
                        <span
                            class="absolute -bottom-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">Top
                            Up Saldo</span>
                    </button>
                @endif
                <a href="{{ route('admin.kendaraans.export', ['satker_id' => request('satker_id')]) }}" target="_blank"
                    rel="nofollow"
                    class="inline-flex items-center justify-center w-10 h-10 bg-amber-500 text-white rounded-xl hover:bg-amber-600 shadow-lg shadow-amber-500/30 transition-all duration-200 hover:-translate-y-0.5 group relative"
                    title="Export Excel">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    <!-- Tooltip -->
                    <span
                        class="absolute -bottom-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">Export
                        Excel</span>
                </a>
                <button @click="$dispatch('open-monthly-report')"
                    class="inline-flex items-center justify-center w-10 h-10 bg-rose-600 text-white rounded-xl hover:bg-rose-700 shadow-lg shadow-rose-500/30 transition-all duration-200 hover:-translate-y-0.5 group relative"
                    title="Laporan Bulanan">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <!-- Tooltip -->
                    <span
                        class="absolute -bottom-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">Laporan
                        Bulanan Satker</span>
                </button>
                @if(auth()->user()->role !== 'kasubbag')
                    <a href="{{ route('admin.kendaraans.create') }}"
                        class="inline-flex items-center justify-center w-10 h-10 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 group relative"
                        title="Tambah Kendaraan">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <!-- Tooltip -->
                        <span
                            class="absolute -bottom-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">Tambah
                            Kendaraan</span>
                    </a>
                @endif

                <!-- Filter Satker (Icon-only) -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="inline-flex items-center justify-center w-10 h-10 bg-white border-2 border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 hover:border-indigo-500 hover:text-indigo-600 shadow-sm transition-all duration-200 group relative"
                        :class="{ 'border-indigo-500 text-indigo-600 ring-4 ring-indigo-500/10': open || '{{ request('satker_id') }}' != '' }"
                        title="Filter Satker">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>

                        @if(request('satker_id'))
                            <span class="absolute top-2 right-2 w-2 h-2 bg-indigo-600 rounded-full border-2 border-white"></span>
                        @endif

                        <!-- Tooltip -->
                        <span
                            class="absolute -bottom-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">
                            Filter Satker {{ request('satker_id') ? '(Aktif)' : '' }}
                        </span>
                    </button>

                    <!-- Dropdown Content -->
                    <div x-show="open" @click.away="open = false" x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        class="absolute right-0 mt-3 w-72 bg-white rounded-2xl shadow-xl border border-slate-100 p-4 z-50">
                        <div class="mb-3">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Pilih Satuan Kerja</h4>
                        </div>
                        <form action="{{ route('admin.kendaraans.index') }}" method="GET">
                            <select name="satker_id" id="filter_satker_id" onchange="this.form.submit()"
                                class="tom-select block w-full bg-slate-50 border-slate-200 rounded-xl transition-all shadow-sm font-semibold text-sm">
                                <option value="">Semua Satker</option>
                                @foreach($satkers as $satker)
                                    <option value="{{ $satker->id }}" {{ request('satker_id') == $satker->id ? 'selected' : '' }}>
                                        {{ $satker->nama_satker }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <!-- Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
            <!-- Table Header Info -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">Daftar Kendaraan</h3>
                        <p class="text-xs text-slate-400">{{ $kendaraans->total() }} kendaraan dari seluruh satker</p>
                    </div>
                </div>

                <!-- Bulk Actions -->
                @if(auth()->user()->role !== 'kasubbag')
                    <div id="bulkActions" class="hidden flex items-center gap-3">
                        <span
                            class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100">
                            <span id="selectedCount">0</span> DIPILIH
                        </span>
                        <button type="button" id="bulkDeleteBtn"
                            class="px-3 py-1.5 bg-rose-600 text-white rounded-lg text-xs font-bold hover:bg-rose-700 transition shadow-sm">
                            <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            Hapus Terpilih
                        </button>
                    </div>
                @endif
            </div>

            @if(auth()->user()->role !== 'kasubbag')
                <form id="bulkDeleteForm" action="{{ route('admin.kendaraans.bulk-delete') }}" method="POST" class="hidden">
                    @csrf
                    <div id="bulkIdsContainer"></div>
                </form>
            @endif

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th colspan="8" class="px-6 py-3">
                                <div class="flex items-center justify-between">
                                    <form action="{{ route('admin.kendaraans.index') }}" method="GET"
                                        class="flex items-center gap-3">
                                        @if(request('satker_id'))
                                            <input type="hidden" name="satker_id" value="{{ request('satker_id') }}">
                                        @endif
                                        <x-per-page :current="request('per_page', 15)" />

                                        <div class="relative">
                                            <span
                                                class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-slate-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z">
                                                    </path>
                                                </svg>
                                            </span>
                                            <input type="text" name="search" value="{{ request('search') }}"
                                                placeholder="Cari nopol, jenis kendaraan..."
                                                class="block w-48 pl-8 pr-3 py-1.5 border border-slate-200 rounded-lg text-xs placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                        </div>

                                        @if(request('search'))
                                            <a href="{{ route('admin.kendaraans.index', ['satker_id' => request('satker_id')]) }}"
                                                class="text-xs font-medium text-slate-400 hover:text-indigo-600 transition-colors">
                                                Reset
                                            </a>
                                        @endif
                                    </form>
                                    <div class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">
                                        Menampilkan
                                        {{ $kendaraans->firstItem() ?? 0 }}-{{ $kendaraans->lastItem() ?? 0 }} dari
                                        {{ $kendaraans->total() }} data
                                    </div>
                                </div>
                            </th>
                        </tr>
                        <tr class="bg-slate-50/70">
                            @if(auth()->user()->role !== 'kasubbag')
                                <th class="w-10 px-6 py-3.5">
                                    <input type="checkbox" id="checkAll"
                                        class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 shadow-sm cursor-pointer">
                                </th>
                            @endif
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
                                class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Saldo</th>
                            <th
                                class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                PIN</th>
                            <th
                                class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kendaraans as $kendaraan)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                @if(auth()->user()->role !== 'kasubbag')
                                    <td class="px-6 py-4">
                                        <input type="checkbox" value="{{ $kendaraan->id }}"
                                            class="item-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 shadow-sm cursor-pointer">
                                    </td>
                                @endif
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex-shrink-0 w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                            {{ strtoupper(substr($kendaraan->satker->nama_satker ?? '-', 0, 2)) }}
                                        </div>
                                        <span
                                            class="text-xs font-medium text-slate-700">{{ $kendaraan->satker->nama_satker ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-xs font-semibold text-slate-800">{{ $kendaraan->jenis_kendaraan }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold text-slate-800">{{ $kendaraan->no_polisi }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $bbmColors = [
                                            'Pertamax' => 'bg-blue-100 text-blue-700',
                                            'Pertamina Dex' => 'bg-yellow-200 text-yellow-700',
                                        ];
                                        $color = $bbmColors[$kendaraan->jenis_bbm] ?? 'bg-slate-100 text-slate-700';
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold whitespace-nowrap {{ $color }}">
                                        {{ $kendaraan->jenis_bbm }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span
                                        class="text-xs font-bold whitespace-nowrap {{ $kendaraan->saldo < 10 ? 'text-red-600' : 'text-slate-800' }}">
                                        {{ number_format($kendaraan->saldo, 0, ',', '.') }} Liter
                                    </span>
                                    @if($kendaraan->saldo < 10)
                                        <span class="block text-xs text-red-500 font-medium mt-0.5">Saldo rendah</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <code
                                        class="px-2.5 py-1 bg-amber-50 text-amber-700 rounded-lg text-xs font-mono font-bold tracking-widest">{{ $kendaraan->pin }}</code>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.kendaraans.print', $kendaraan) }}" target="_blank"
                                            rel="nofollow"
                                            class="inline-flex items-center p-2 bg-slate-100 hover:bg-indigo-100 text-slate-500 hover:text-indigo-600 rounded-lg transition-colors"
                                            title="Print Kartu Kendaraan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                </path>
                                            </svg>
                                        </a>
                                        @if(auth()->user()->role !== 'kasubbag')
                                            <button type="button"
                                                @click="$dispatch('open-transfer', {id: {{ $kendaraan->id }}, nopol: '{{ $kendaraan->no_polisi }}', current_satker: '{{ $kendaraan->satker->nama_satker ?? '-' }}'})"
                                                class="inline-flex items-center p-2 bg-slate-100 hover:bg-violet-100 text-slate-500 hover:text-violet-600 rounded-lg transition-colors"
                                                title="Pindah Satker">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                                </svg>
                                            </button>
                                            <form action="{{ route('admin.kendaraans.reset-pin', $kendaraan) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    data-confirm="Reset PIN kendaraan {{ $kendaraan->no_polisi }}? PIN baru akan di-generate secara acak."
                                                    data-confirm-type="warning"
                                                    class="inline-flex items-center p-2 bg-slate-100 hover:bg-amber-100 text-slate-500 hover:text-amber-600 rounded-lg transition-colors"
                                                    title="Reset PIN">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.kendaraans.edit', $kendaraan) }}"
                                                class="inline-flex items-center p-2 bg-slate-100 hover:bg-indigo-100 text-slate-500 hover:text-indigo-600 rounded-lg transition-colors"
                                                title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.kendaraans.destroy', $kendaraan) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" data-confirm="Yakin ingin menghapus kendaraan ini?"
                                                    data-confirm-type="error"
                                                    class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all"
                                                    title="Hapus Kendaraan">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="text-slate-500 font-medium">Belum ada kendaraan terdaftar</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($kendaraans->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $kendaraans->links() }}
                </div>
            @endif
        </div>
    </div>

    @if(auth()->user()->role === 'super_admin')
        <!-- Top Up Modal -->
        <!-- Top Up Modal -->
        <div x-cloak
            x-data="{
                                                                                                                                    showTopup: false,
                                                                                                                                    topupId: null,
                                                                                                                                    topupNopol: '',
                                                                                                                                    topupSaldo: '',
                                                                                                                                    jumlah: '',
                                                                                                                                    topupPassword: '',
                                                                                                                                    topupTanggal: '{{ date('Y-m-d') }}',
                                                                                                                                    keterangan: '',
                                                                                                                                    selectMode: false,
                                                                                                                                    selectedSatkerId: '',
                                                                                                                                    // Satker Search
                                                                                                                                    satkerSearch: '',
                                                                                                                                    satkerOpen: false,
                                                                                                                                    satkerLabel: '',
                                                                                                                                    satkers: {{ json_encode($satkers->map(fn($s) => ['id' => $s->id, 'nama' => $s->nama_satker])) }},
                                                                                                                                    get filteredSatkers() {
                                                                                                                                        if (!this.satkerSearch) return this.satkers;
                                                                                                                                        return this.satkers.filter(s => s.nama.toLowerCase().includes(this.satkerSearch.toLowerCase()));
                                                                                                                                    },
                                                                                                                                    selectSatker(s) {
                                                                                                                                        this.selectedSatkerId = s.id;
                                                                                                                                        this.satkerLabel = s.nama;
                                                                                                                                        this.satkerOpen = false;
                                                                                                                                        this.satkerSearch = '';
                                                                                                                                        this.topupId = null;
                                                                                                                                        this.topupNopol = '';
                                                                                                                                        this.kendaraanLabel = '';
                                                                                                                                    },
                                                                                                                                    // Kendaraan Search
                                                                                                                                    kendaraanSearch: '',
                                                                                                                                    kendaraanOpen: false,
                                                                                                                                    kendaraanLabel: '',
                                                                                                                                    get filteredKendaraans() {
                                                                                                                                        let list = this.allKendaraans.filter(x => x.satker_id == this.selectedSatkerId);
                                                                                                                                        if (!this.kendaraanSearch) return list;
                                                                                                                                        return list.filter(k => k.nopol.toLowerCase().includes(this.kendaraanSearch.toLowerCase()));
                                                                                                                                    },
                                                                                                                                    selectKendaraanManual(k) {
                                                                                                                                        this.topupId = k.id;
                                                                                                                                        this.topupNopol = k.satker_nama + ' - ' + k.nopol;
                                                                                                                                        this.topupSaldo = k.saldo;
                                                                                                                                        this.kendaraanLabel = k.nopol + ' (' + k.saldo + ' L)';
                                                                                                                                        this.kendaraanOpen = false;
                                                                                                                                        this.kendaraanSearch = '';
                                                                                                                                    },
                                                                                                                                    adminStocks: [
                                                                                                                                        @foreach($adminStocks as $s)
                                                                                                                                            { jenis_bbm: '{{ $s->jenis_bbm }}', saldo: {{ $s->saldo }} },
                                                                                                                                        @endforeach
                                                                                                                                    ],
                                                                                                                                    allKendaraans: [
                                                                                                                                        @foreach($allKendaraans as $k)
                                                                                                                                            { id: {{ $k->id }}, satker_id: {{ $k->satker_id }}, satker_nama: '{{ $k->satker->nama_satker ?? '-' }}', nopol: '{{ $k->no_polisi }}', jenis_bbm: '{{ $k->jenis_bbm }}', saldo: '{{ number_format($k->saldo, 0, ',', '.') }}', saldoRaw: {{ $k->saldo }} },
                                                                                                                                        @endforeach
                                                                                                                                    ],
                                                                                                                                    get currentAdminStock() {
                                                                                                                                        if (!this.topupId) return 0;
                                                                                                                                        const k = this.allKendaraans.find(x => x.id == this.topupId);
                                                                                                                                        if (!k) return 0;
                                                                                                                                        const s = this.adminStocks.find(x => x.jenis_bbm == k.jenis_bbm);
                                                                                                                                        return s ? s.saldo : 0;
                                                                                                                                    },
                                                                                                                                    get canSubmitManual() {
                                                                                                                                        return this.topupId && this.jumlah && this.jumlah > 0 && this.jumlah <= this.currentAdminStock && this.topupPassword && this.topupTanggal;
                                                                                                                                    },
                                                                                                                                    selectKendaraan(id) {
                                                                                                                                        const k = this.allKendaraans.find(x => x.id == id);
                                                                                                                                        if (k) {
                                                                                                                                            this.topupId = k.id;
                                                                                                                                            this.topupNopol = k.satker_nama + ' - ' + k.nopol;
                                                                                                                                            this.topupSaldo = k.saldo;
                                                                                                                                        }
                                                                                                                                    },
                                                                                                                                    reset() {
                                                                                                                                        this.showTopup = false;
                                                                                                                                        setTimeout(() => {
                                                                                                                                            this.jumlah = '';
                                                                                                                                            this.topupPassword = '';
                                                                                                                                            this.topupTanggal = '{{ date('Y-m-d') }}';
                                                                                                                                            this.topupId = null;
                                                                                                                                            this.keterangan = '';
                                                                                                                                            this.selectedSatkerId = '';
                                                                                                                                            this.satkerLabel = '';
                                                                                                                                            this.kendaraanLabel = '';
                                                                                                                                        }, 300);
                                                                                                                                    },
                                                                                                                                    number_format(number, decimals, dec_point, thousands_sep) {
                                                                                                                                        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
                                                                                                                                        var n = !isFinite(+number) ? 0 : +number,
                                                                                                                                            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                                                                                                                                            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                                                                                                                                            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                                                                                                                                            s = '',
                                                                                                                                            toFixedFix = function(n, prec) {
                                                                                                                                                var k = Math.pow(10, prec);
                                                                                                                                                return '' + Math.round(n * k) / k;
                                                                                                                                            };
                                                                                                                                        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
                                                                                                                                        if (s[0].length > 3) {
                                                                                                                                            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                                                                                                                                        }
                                                                                                                                        if ((s[1] || '').length < prec) {
                                                                                                                                            s[1] = s[1] || '';
                                                                                                                                            s[1] += new Array(prec - s[1].length + 1).join('0');
                                                                                                                                        }
                                                                                                                                        return s.join(dec);
                                                                                                                                    }
                                                                                                                                }"
            @open-topup.window="topupId = $event.detail.id; topupNopol = $event.detail.nopol; topupSaldo = $event.detail.saldo; jumlah = ''; topupPassword = ''; selectMode = false; showTopup = true"
            @open-topup-select.window="topupId = null; topupNopol = ''; topupSaldo = ''; jumlah = ''; topupPassword = ''; selectMode = true; showTopup = true"
            @turbo:before-cache.window="showTopup = false">
            <!-- Backdrop -->
            <div x-show="showTopup" style="display: none;" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50"
                @click="showTopup = false"></div>

            <!-- Modal -->
            <div x-show="showTopup" style="display: none;" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4" @click.self="showTopup = false">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[95vh] flex flex-col overflow-hidden"
                    @click.stop>
                    <!-- Modal Header -->
                    <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gradient-to-r from-emerald-500 to-green-600 shrink-0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="p-1.5 sm:p-2 bg-white/20 rounded-xl">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base sm:text-lg font-bold text-white">Top Up Saldo</h3>
                                    <p class="text-xs sm:text-sm text-emerald-100" x-text="topupNopol || 'Pilih kendaraan'">
                                    </p>
                                </div>
                            </div>
                            <button @click="showTopup = false"
                                class="p-1 text-white/70 hover:text-white rounded-lg hover:bg-white/10 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body & Form -->
                    <form :action="'/admin/kendaraans/' + topupId + '/topup'" method="POST"
                        class="flex flex-col flex-1 overflow-hidden">
                        @csrf
                        <div class="p-2 sm:p-4 space-y-2 overflow-y-auto flex-1 bg-slate-50/30">
                            <!-- Hidden Inputs -->
                            <input type="hidden" name="kendaraan_id" :value="topupId">

                            <!-- Tanggal Top Up (Paling Atas) -->
                            <div class="bg-white p-2.5 sm:p-3 rounded-xl border border-slate-200 shadow-sm">
                                <label for="tanggal_topup"
                                    class="block text-[10px] sm:text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Tanggal Top Up</label>
                                <input type="date" id="tanggal_topup" name="tanggal_topup" x-model="topupTanggal"
                                    required
                                    class="w-full px-3 py-1.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-xs font-bold focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all">
                            </div>

                            <!-- Section 1: Pemilihan -->
                            <div class="bg-white p-2.5 sm:p-3 rounded-xl border border-slate-200 shadow-sm space-y-2">
                                <div x-show="selectMode" class="space-y-3">
                                    <div>
                                        <label
                                            class="block text-[10px] sm:text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">1.
                                            Pilih Satuan Kerja</label>
                                        <div class="relative" @click.outside="satkerOpen = false">
                                            <div @click="satkerOpen = !satkerOpen; $nextTick(() => { if(satkerOpen) $refs.satkerInput.focus() })"
                                                class="w-full px-3 py-1.5 sm:py-2 bg-white border-2 border-slate-200 rounded-xl text-xs font-semibold text-slate-800 flex items-center justify-between cursor-pointer transition-all"
                                                :class="satkerOpen ? 'border-emerald-500 ring-4 ring-emerald-500/10' : ''">
                                                <span x-text="satkerLabel || '-- Pilih Satker --'"
                                                    :class="satkerLabel ? 'text-slate-800' : 'text-slate-400'"></span>
                                                <svg class="w-4 h-4 text-slate-400 transition-transform"
                                                    :class="satkerOpen ? 'rotate-180' : ''" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                            <div x-show="satkerOpen" x-transition.opacity.duration.150ms
                                                class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden"
                                                style="display:none;">
                                                <div class="p-2 border-b border-slate-100">
                                                    <div class="relative">
                                                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                        </svg>
                                                        <input x-ref="satkerInput" x-model="satkerSearch" type="text"
                                                            placeholder="Cari Satker..."
                                                            class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                                    </div>
                                                </div>
                                                <div class="max-h-48 overflow-y-auto">
                                                    <template x-for="s in filteredSatkers" :key="s.id">
                                                        <div @click="selectSatker(s)"
                                                            class="px-4 py-2.5 text-xs sm:text-sm text-slate-700 hover:bg-emerald-50 cursor-pointer flex items-center justify-between transition-colors"
                                                            :class="selectedSatkerId == s.id ? 'bg-emerald-50 text-emerald-700 font-semibold' : ''">
                                                            <span x-text="s.nama"></span>
                                                            <svg x-show="selectedSatkerId == s.id"
                                                                class="w-4 h-4 text-emerald-500" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                    </template>
                                                    <div x-show="filteredSatkers.length === 0"
                                                        class="px-4 py-3 text-sm text-slate-400 text-center">Tidak ditemukan
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div x-show="selectedSatkerId">
                                        <label
                                            class="block text-[10px] sm:text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">2.
                                            Pilih Kendaraan</label>
                                        <div class="relative" @click.outside="kendaraanOpen = false">
                                            <div @click="kendaraanOpen = !kendaraanOpen; $nextTick(() => { if(kendaraanOpen) $refs.kendaraanInputManual.focus() })"
                                                class="w-full px-3 py-1.5 sm:py-2 bg-white border-2 border-slate-200 rounded-xl text-xs font-semibold text-slate-800 flex items-center justify-between cursor-pointer transition-all"
                                                :class="kendaraanOpen ? 'border-emerald-500 ring-4 ring-emerald-500/10' : ''">
                                                <span x-text="kendaraanLabel || '-- Pilih Kendaraan --'"
                                                    :class="kendaraanLabel ? 'text-slate-800' : 'text-slate-400'"></span>
                                                <svg class="w-4 h-4 text-slate-400 transition-transform"
                                                    :class="kendaraanOpen ? 'rotate-180' : ''" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                            <div x-show="kendaraanOpen" x-transition.opacity.duration.150ms
                                                class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden"
                                                style="display:none;">
                                                <div class="p-2 border-b border-slate-100">
                                                    <div class="relative">
                                                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                        </svg>
                                                        <input x-ref="kendaraanInputManual" x-model="kendaraanSearch"
                                                            type="text" placeholder="Cari nopol..."
                                                            class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                                    </div>
                                                </div>
                                                <div class="max-h-48 overflow-y-auto">
                                                    <template x-for="k in filteredKendaraans" :key="k.id">
                                                        <div @click="selectKendaraanManual(k)"
                                                            class="px-4 py-2.5 text-xs sm:text-sm text-slate-700 hover:bg-emerald-50 cursor-pointer flex items-center justify-between transition-colors"
                                                            :class="topupId == k.id ? 'bg-emerald-50 text-emerald-700 font-semibold' : ''">
                                                            <span x-text="k.nopol + ' (' + k.saldo + ' L)'"></span>
                                                            <svg x-show="topupId == k.id" class="w-4 h-4 text-emerald-500"
                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                    </template>
                                                    <div x-show="filteredKendaraans.length === 0"
                                                        class="px-4 py-3 text-sm text-slate-400 text-center">Tidak ditemukan
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Informasi Saldo & Stok -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2" x-transition>
                                <!-- Current Saldo -->
                                <div class="flex items-center gap-2.5 p-2 bg-blue-50/50 rounded-xl border border-blue-100">
                                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="truncate">
                                        <p class="text-[10px] text-blue-600 font-bold uppercase tracking-tight">Saldo Saat
                                            Ini</p>
                                        <p class="text-lg font-black text-blue-700 truncate"><span
                                                x-text="topupSaldo"></span> L</p>
                                    </div>
                                </div>

                                <!-- Admin Stock Info -->
                                <div class="flex items-center gap-2.5 p-2 bg-amber-50 rounded-xl border border-amber-200">
                                    <div class="p-2 bg-amber-100 text-amber-600 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="truncate">
                                        <p class="text-[10px] text-amber-600 font-bold uppercase tracking-tight">Stok Pusat
                                        </p>
                                        <p class="text-lg font-black text-amber-700 truncate"><span
                                                x-text="number_format(currentAdminStock, 0, ',', '.')"></span> L</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3: Input Nominal -->
                            <div class="bg-white p-2.5 sm:p-3.5 rounded-xl border border-slate-200 shadow-sm space-y-2"
                                x-transition>
                                <div>
                                    <label for="jumlah"
                                        class="block text-[10px] sm:text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-center">Jumlah
                                        Top Up</label>
                                    <div class="relative max-w-[180px] mx-auto">
                                        <input type="number" name="jumlah" id="jumlah" x-model="jumlah" step="0.1" min="0.1"
                                            max="10000" required placeholder="0.0"
                                            class="w-full px-3 py-1.5 pr-12 bg-slate-50 border-2 border-slate-200 rounded-xl text-lg font-bold text-slate-800 text-center focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all placeholder:text-slate-200">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-[10px] font-bold text-slate-400">LITER</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Amount Buttons -->
                                <div class="grid grid-cols-4 gap-1.5">
                                    <template x-for="q in [5, 10, 15, 20, 25, 30, 40, 50]">
                                        <button type="button" @click="jumlah = q"
                                            class="py-1 text-[10px] font-bold rounded-lg border-2 transition-all hover:scale-105 active:scale-95"
                                            :class="jumlah == q ? 'border-emerald-500 bg-emerald-50 text-emerald-700 ring-2 ring-emerald-200' : 'border-slate-100 bg-white text-slate-500 hover:border-emerald-200 hover:bg-emerald-50/50'"
                                            x-text="q + ' L'"></button>
                                    </template>
                                </div>

                                <div class="pt-1.5">
                                    <label for="topup_password"
                                        class="block text-[10px] font-bold text-slate-400 mb-1.5 uppercase tracking-widest text-center">Password
                                        Top Up</label>
                                    <input type="password" id="topup_password" name="topup_password" x-model="topupPassword"
                                        required placeholder="Masukkan password keamanan"
                                        class="w-full px-3 py-1.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-center text-sm font-bold tracking-normal focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all placeholder:tracking-normal placeholder:font-normal placeholder:text-slate-200"
                                        autocomplete="off">
                                </div>

                                <p x-show="jumlah > currentAdminStock"
                                    class="mt-1.5 sm:mt-2 text-[10px] sm:text-xs font-bold text-red-600 flex items-center justify-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                    <span>Jumlah melebihi stok pusat!</span>
                                </p>
                            </div>
                        </div>

                        <!-- Footer Buttons -->
                        <div class="px-3 sm:px-4 py-3 bg-white border-t border-slate-100 flex gap-2 shrink-0">
                            <button type="button" @click="reset()"
                                class="flex-1 px-3 py-2 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all active:scale-95 text-[10px] sm:text-xs uppercase tracking-wider">Batal</button>
                            <button type="submit"
                                class="flex-[2] px-3 py-2 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-black rounded-xl hover:from-emerald-600 hover:to-green-700 shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/40 transition-all hover:-translate-y-0.5 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:grayscale text-[10px] sm:text-xs uppercase tracking-widest flex items-center justify-center gap-1.5"
                                :disabled="!canSubmitManual">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span>Simpan Top Up</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Potong Saldo Modal -->
            <div x-cloak x-show="showPotong" style="display: none;" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4" @click.self="reset()">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[95vh] flex flex-col overflow-hidden"
                    @click.stop>
                    <!-- Modal Header -->
                    <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gradient-to-r from-rose-500 to-red-600 shrink-0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="p-1.5 sm:p-2 bg-white/20 rounded-xl">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base sm:text-lg font-bold text-white">Potong Saldo (Hutang)</h3>
                                    <p class="text-xs sm:text-sm text-rose-100" x-text="topupNopol"></p>
                                </div>
                            </div>
                            <button @click="reset()"
                                class="p-1 text-white/70 hover:text-white rounded-lg hover:bg-white/10 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body & Form -->
                    <form :action="'/admin/kendaraans/' + topupId + '/potong-saldo'" method="POST"
                        class="flex flex-col flex-1 overflow-hidden">
                        @csrf
                        <div class="p-3 sm:p-5 space-y-4 overflow-y-auto flex-1 bg-slate-50/30">
                            <!-- Info Saldo -->
                            <div class="p-3 bg-rose-50 border border-rose-100 rounded-xl flex items-center justify-between">
                                <span class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Saldo Saat
                                    Ini</span>
                                <span class="text-lg font-black text-rose-700" x-text="topupSaldo + ' L'"></span>
                            </div>

                            <!-- Input Jumlah -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Jumlah
                                    Potongan
                                    (Liter)</label>
                                <div class="relative group">
                                    <input type="number" step="0.01" name="jumlah" x-model.number="jumlah"
                                        class="w-full pl-4 pr-12 py-3 bg-white border-2 border-slate-200 rounded-xl focus:border-rose-500 focus:ring-0 transition-all font-black text-lg"
                                        placeholder="0.00" required>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">L</div>
                                </div>
                                <p x-show="jumlah > 0 && topupId"
                                    class="text-[10px] sm:text-xs text-slate-500 font-medium italic">
                                    * Saldo akan berkurang menjadi <span class="font-bold text-rose-600"
                                        x-text="number_format((allKendaraans.find(x => x.id == topupId)?.saldoRaw || 0) - jumlah, 2, ',', '.')"></span>
                                    L
                                </p>
                            </div>

                            <!-- Input Keterangan -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Keterangan
                                    (Opsional)</label>
                                <textarea name="keterangan" x-model="keterangan"
                                    class="w-full px-4 py-2.5 bg-white border-2 border-slate-200 rounded-xl focus:border-rose-500 focus:ring-0 transition-all text-sm"
                                    placeholder="Alasan pemotongan saldo..." rows="2"></textarea>
                            </div>

                            <!-- Security Password -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password
                                    Keamanan</label>
                                <div class="relative">
                                    <input type="password" name="topup_password" x-model="topupPassword"
                                        class="w-full pl-10 pr-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:border-rose-500 focus:ring-0 transition-all text-sm"
                                        placeholder="Masukkan Password Top Up" required>
                                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Buttons -->
                        <div class="px-4 sm:px-6 py-4 bg-white border-t border-slate-100 flex gap-3 shrink-0">
                            <button type="button" @click="reset()"
                                class="flex-1 px-4 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all active:scale-95 text-xs sm:text-sm uppercase tracking-wider">Batal</button>
                            <button type="submit"
                                class="flex-[2] px-4 py-3 bg-gradient-to-r from-rose-500 to-red-600 text-white font-black rounded-xl hover:from-rose-600 hover:to-red-700 shadow-lg shadow-rose-500/30 hover:shadow-rose-500/40 transition-all hover:-translate-y-0.5 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:grayscale text-xs sm:text-sm uppercase tracking-widest flex items-center justify-center gap-2"
                                :disabled="!canSubmitPotong">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Potong Saldo</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if(auth()->user()->role === 'super_admin')
        <!-- Import Excel Modal -->
        <div x-cloak x-data="{ showImport: false }" @open-import.window="showImport = true"
            @turbo:before-cache.window="showImport = false">
            <!-- Backdrop -->
            <div x-show="showImport" style="display: none;" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50"
                @click="showImport = false"></div>

            <!-- Modal -->
            <div x-show="showImport" style="display: none;" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4" @click.self="showImport = false">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] flex flex-col overflow-hidden"
                    @click.stop>
                    <!-- Modal Header -->
                    <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gradient-to-r from-blue-500 to-indigo-600 shrink-0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="p-1.5 sm:p-2 bg-white/20 rounded-xl">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base sm:text-lg font-bold text-white">Import Top Up Saldo</h3>
                                    <p class="text-xs sm:text-sm text-blue-100">Upload file Excel (.xlsx, .xls, .csv)</p>
                                </div>
                            </div>
                            <button @click="showImport = false"
                                class="p-1 text-white/70 hover:text-white rounded-lg hover:bg-white/10 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body (Scrollable) -->
                    <form action="{{ route('admin.kendaraans.import-topup') }}" method="POST" enctype="multipart/form-data"
                        data-turbo="false" class="p-4 sm:p-6 space-y-4 sm:space-y-5 overflow-y-auto flex-1">
                        @csrf

                        <!-- Info -->
                        <div
                            class="flex items-start gap-2 sm:gap-3 p-3 sm:p-4 bg-blue-50 rounded-xl border border-blue-200">
                            <div class="p-1 sm:p-1.5 bg-blue-100 text-blue-600 rounded-lg mt-0.5 shrink-0">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="text-[10px] sm:text-xs text-blue-700">
                                <p class="font-semibold mb-1">Format file Excel (header di baris ke-2):</p>
                                <ul class="list-disc list-inside space-y-0.5">
                                    <li>Kolom <strong>NO</strong> â€” Nomor urut</li>
                                    <li>Kolom <strong>SATKER</strong> â€” Nama satuan kerja</li>
                                    <li>Kolom <strong>KODE KENDARAAN</strong> â€” Kode unik kendaraan</li>
                                    <li>Kolom <strong>JENIS KENDARAAN</strong> â€” Tipe kendaraan</li>
                                    <li>Kolom <strong>NOPOL</strong> â€” Nomor polisi kendaraan</li>
                                    <li>Kolom <strong>JENIS BBM</strong> â€” Tipe BBM</li>
                                    <li>Kolom <strong>JUMLAH LITER</strong> â€” Jumlah liter top up</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Mass Import Admin Stock Info -->
                        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-3 sm:p-4">
                            <p
                                class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-2 sm:mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                Stok Pusat Tersedia
                            </p>
                            <div class="grid grid-cols-2 gap-2 sm:gap-3">
                                @foreach($adminStocks as $as)
                                    <div class="bg-white p-2 rounded-lg border border-indigo-100">
                                        <p class="text-[9px] font-bold text-slate-400 uppercase">{{ $as->jenis_bbm }}</p>
                                        <p class="text-sm font-black text-indigo-700">
                                            {{ number_format($as->saldo, 0, ',', '.') }} L
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Download Format -->
                        <a href="{{ route('admin.kendaraans.download-format') }}"
                            class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-blue-600 hover:text-blue-800 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Download Format Excel (Data Kendaraan)
                        </a>

                        <!-- File Input -->
                        <div>
                            <label for="file"
                                class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Pilih
                                File</label>
                            <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv" required
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 bg-white border-2 border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all file:mr-3 sm:file:mr-4 file:py-1 file:px-2 sm:file:px-3 file:rounded-lg file:border-0 file:text-xs sm:file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 sm:mt-1.5 text-[10px] sm:text-xs text-slate-400">Maksimal 2MB. Format: .xlsx,
                                .xls, .csv</p>
                        </div>

                        <!-- Password Top Up -->
                        <div>
                            <label for="import_topup_password"
                                class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Password Top
                                Up</label>
                            <div class="relative">
                                <input type="password" name="topup_password" id="import_topup_password" required
                                    placeholder="Masukkan password keamanan"
                                    class="w-full px-3 sm:px-4 py-2.5 sm:py-3 bg-white border-2 border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-300 placeholder:font-normal"
                                    autocomplete="off">
                            </div>
                            <p class="mt-1 sm:mt-1.5 text-[10px] sm:text-xs text-slate-400">Masukkan PIN/Password khusus
                                untuk konfirmasi import.</p>
                        </div>

                        <!-- Submit -->
                        <div class="flex gap-2 sm:gap-3 pt-1 sm:pt-2">
                            <button type="button" @click="showImport = false"
                                class="flex-1 px-3 sm:px-4 py-2.5 sm:py-3 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                            <button type="submit"
                                class="flex-1 px-3 sm:px-4 py-2.5 sm:py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-sm font-bold rounded-xl hover:from-blue-600 hover:to-indigo-700 shadow-lg shadow-blue-500/30 hover:shadow-blue-500/40 transition-all hover:-translate-y-0.5">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg> Import Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Import Data Kendaraan Modal -->
    @if(auth()->user()->role === 'super_admin')
        <div x-cloak x-data="importKendaraanModal()" @open-import-kendaraan.window="openModal()"
            @turbo:before-cache.window="showModal = false">
            <!-- Backdrop -->
            <div x-show="showModal" style="display: none;" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50"
                @click="closeModal()"></div>

            <!-- Modal -->
            <div x-show="showModal" style="display: none;" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4" @click.self="closeModal()">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden"
                    @click.stop>
                    <!-- Modal Header -->
                    <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gradient-to-r from-violet-500 to-indigo-600 shrink-0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="p-1.5 sm:p-2 bg-white/20 rounded-xl">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base sm:text-lg font-bold text-white">Import Data Kendaraan</h3>
                                    <p class="text-xs sm:text-sm text-violet-100">Upload file Excel untuk menambah data
                                        kendaraan</p>
                                </div>
                            </div>
                            <button @click="closeModal()"
                                class="p-1 text-white/70 hover:text-white rounded-lg hover:bg-white/10 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <!-- Step Indicator -->
                        <div class="flex items-center gap-2 mt-3">
                            <div class="flex items-center gap-1.5">
                                <div :class="step >= 1 ? 'bg-white text-violet-600' : 'bg-white/30 text-white'"
                                    class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold transition-colors">
                                    1</div>
                                <span class="text-xs text-white/80 hidden sm:inline">Upload</span>
                            </div>
                            <div :class="step >= 2 ? 'bg-white/60' : 'bg-white/20'"
                                class="flex-1 h-0.5 rounded transition-colors"></div>
                            <div class="flex items-center gap-1.5">
                                <div :class="step >= 2 ? 'bg-white text-violet-600' : 'bg-white/30 text-white'"
                                    class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold transition-colors">
                                    2</div>
                                <span class="text-xs text-white/80 hidden sm:inline">Preview</span>
                            </div>
                            <div :class="step >= 3 ? 'bg-white/60' : 'bg-white/20'"
                                class="flex-1 h-0.5 rounded transition-colors"></div>
                            <div class="flex items-center gap-1.5">
                                <div :class="step >= 3 ? 'bg-white text-violet-600' : 'bg-white/30 text-white'"
                                    class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold transition-colors">
                                    3</div>
                                <span class="text-xs text-white/80 hidden sm:inline">Confirm</span>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Body (Scrollable) -->
                    <div class="p-4 sm:p-6 overflow-y-auto flex-1">

                        <!-- STEP 1: Upload File -->
                        <div x-show="step === 1" x-transition>
                            <!-- Info -->
                            <div
                                class="flex items-start gap-2 sm:gap-3 p-3 sm:p-4 bg-violet-50 rounded-xl border border-violet-200 mb-4">
                                <div class="p-1 sm:p-1.5 bg-violet-100 text-violet-600 rounded-lg mt-0.5 shrink-0">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="text-[10px] sm:text-xs text-violet-700">
                                    <p class="font-semibold mb-1">Format file Excel (header di baris ke-2):</p>
                                    <ul class="list-disc list-inside space-y-0.5">
                                        <li>Kolom <strong>NO</strong> â€” Nomor urut</li>
                                        <li>Kolom <strong>SATKER</strong> â€” Nama satuan kerja</li>
                                        <li>Kolom <strong>JENIS KENDARAAN</strong> â€” Tipe kendaraan</li>
                                        <li>Kolom <strong>NOPOL</strong> â€” Nomor polisi kendaraan</li>
                                        <li>Kolom <strong>JENIS BBM</strong> â€” Pertamax / Pertamina Dex</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Download Template -->
                            <a href="{{ route('admin.kendaraans.download-import-template') }}"
                                class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-violet-600 hover:text-violet-800 transition mb-4">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Download Template Excel
                            </a>

                            <!-- Drag & Drop Area -->
                            <div class="relative border-2 border-dashed rounded-xl p-6 sm:p-8 text-center transition-all"
                                :class="isDragging ? 'border-violet-400 bg-violet-50 scale-[1.02]' : (selectedFile ? 'border-emerald-300 bg-emerald-50' : 'border-slate-300 hover:border-violet-400 hover:bg-violet-50/50')"
                                @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false"
                                @drop.prevent="handleDrop($event)">
                                <input type="file" x-ref="fileInput" accept=".xlsx,.xls,.csv" class="hidden"
                                    @change="handleFileSelect($event)">

                                <template x-if="!selectedFile">
                                    <div>
                                        <div
                                            class="mx-auto w-12 h-12 sm:w-14 sm:h-14 bg-violet-100 rounded-2xl flex items-center justify-center mb-3">
                                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-violet-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-slate-700 mb-1">Drag & drop file Excel di sini
                                        </p>
                                        <p class="text-xs text-slate-400 mb-3">atau</p>
                                        <button @click="$refs.fileInput.click()" type="button"
                                            class="px-4 py-2 bg-violet-600 text-white text-xs sm:text-sm font-semibold rounded-lg hover:bg-violet-700 transition shadow-md shadow-violet-500/20">
                                            Pilih File
                                        </button>
                                        <p class="mt-3 text-[10px] sm:text-xs text-slate-400">Maksimal 2MB. Format: .xlsx,
                                            .xls, .csv</p>
                                    </div>
                                </template>

                                <template x-if="selectedFile">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="text-left flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-slate-800 truncate"
                                                x-text="selectedFile.name"></p>
                                            <p class="text-xs text-slate-400" x-text="formatFileSize(selectedFile.size)">
                                            </p>
                                        </div>
                                        <button @click="clearFile()" type="button"
                                            class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>

                            <!-- Error Message -->
                            <div x-show="uploadError" x-transition
                                class="mt-3 p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-600"
                                x-text="uploadError"></div>
                        </div>

                        <!-- STEP 2: Preview Result -->
                        <div x-show="step === 2" x-transition>
                            <!-- Loading -->
                            <div x-show="isLoading" class="flex flex-col items-center justify-center py-8">
                                <div
                                    class="w-10 h-10 border-4 border-violet-200 border-t-violet-600 rounded-full animate-spin mb-3">
                                </div>
                                <p class="text-sm text-slate-500">Menganalisis file...</p>
                            </div>

                            <div x-show="!isLoading && previewData">
                                <!-- Summary Cards -->
                                <div class="grid grid-cols-3 gap-2 sm:gap-3 mb-4">
                                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 text-center">
                                        <p class="text-lg sm:text-2xl font-black text-emerald-600"
                                            x-text="previewData?.new_count || 0"></p>
                                        <p
                                            class="text-[10px] sm:text-xs font-semibold text-emerald-500 uppercase tracking-wider">
                                            Data Baru</p>
                                    </div>
                                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-center">
                                        <p class="text-lg sm:text-2xl font-black text-amber-600"
                                            x-text="previewData?.duplicate_count || 0"></p>
                                        <p
                                            class="text-[10px] sm:text-xs font-semibold text-amber-500 uppercase tracking-wider">
                                            Duplikat</p>
                                    </div>
                                    <div class="bg-red-50 border border-red-200 rounded-xl p-3 text-center">
                                        <p class="text-lg sm:text-2xl font-black text-red-600"
                                            x-text="previewData?.error_count || 0"></p>
                                        <p
                                            class="text-[10px] sm:text-xs font-semibold text-red-500 uppercase tracking-wider">
                                            Error</p>
                                    </div>
                                </div>

                                <!-- New Entries Table -->
                                <template x-if="previewData?.new_entries?.length > 0">
                                    <div class="mb-4">
                                        <h4
                                            class="text-xs sm:text-sm font-bold text-emerald-700 mb-2 flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Data Baru
                                        </h4>
                                        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                                            <div class="overflow-x-auto">
                                                <table class="w-full text-xs">
                                                    <thead class="bg-slate-50">
                                                        <tr>
                                                            <th class="px-3 py-2 text-left font-semibold text-slate-600">
                                                                Nopol</th>
                                                            <th class="px-3 py-2 text-left font-semibold text-slate-600">
                                                                Jenis Kendaraan</th>
                                                            <th class="px-3 py-2 text-left font-semibold text-slate-600">
                                                                Jenis BBM</th>
                                                            <th class="px-3 py-2 text-left font-semibold text-slate-600">
                                                                Satker</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-slate-100">
                                                        <template x-for="entry in previewData.new_entries.slice(0, 10)"
                                                            :key="entry.row">
                                                            <tr class="hover:bg-emerald-50/50">
                                                                <td class="px-3 py-2 font-mono font-semibold"
                                                                    x-text="entry.no_polisi"></td>
                                                                <td class="px-3 py-2" x-text="entry.jenis_kendaraan"></td>
                                                                <td class="px-3 py-2" x-text="entry.jenis_bbm"></td>
                                                                <td class="px-3 py-2" x-text="entry.satker"></td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <template x-if="previewData.new_entries.length > 10">
                                                <div class="px-3 py-2 bg-slate-50 text-xs text-slate-500 text-center">
                                                    <span
                                                        x-text="'... dan ' + (previewData.new_entries.length - 10) + ' data lainnya'"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <!-- Duplicates Table -->
                                <template x-if="previewData?.duplicates?.length > 0">
                                    <div class="mb-4">
                                        <h4
                                            class="text-xs sm:text-sm font-bold text-amber-700 mb-2 flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z">
                                                </path>
                                            </svg>
                                            Data Duplikat
                                        </h4>
                                        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                                            <div class="overflow-x-auto">
                                                <table class="w-full text-xs">
                                                    <thead class="bg-slate-50">
                                                        <tr>
                                                            <th class="px-3 py-2 text-left font-semibold text-slate-600">
                                                                Nopol</th>
                                                            <th class="px-3 py-2 text-left font-semibold text-slate-600">
                                                                Field</th>
                                                            <th class="px-3 py-2 text-left font-semibold text-slate-600">
                                                                Data Lama</th>
                                                            <th class="px-3 py-2 text-left font-semibold text-slate-600">
                                                                Data Baru</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-slate-100">
                                                        <template x-for="dup in previewData.duplicates.slice(0, 10)"
                                                            :key="dup.row">
                                                            <template x-if="dup.has_changes">
                                                                <template x-for="(change, ci) in dup.changes" :key="ci">
                                                                    <tr class="hover:bg-amber-50/50">
                                                                        <td class="px-3 py-2 font-mono font-semibold"
                                                                            x-text="ci === 0 ? dup.no_polisi : ''"></td>
                                                                        <td class="px-3 py-2 font-semibold"
                                                                            x-text="change.field"></td>
                                                                        <td class="px-3 py-2 text-red-500 line-through"
                                                                            x-text="change.old"></td>
                                                                        <td class="px-3 py-2 text-emerald-600 font-semibold"
                                                                            x-text="change.new"></td>
                                                                    </tr>
                                                                </template>
                                                            </template>
                                                        </template>
                                                        <template
                                                            x-for="dup in previewData.duplicates.filter(d => !d.has_changes).slice(0, 5)"
                                                            :key="'nochange-'+dup.row">
                                                            <tr class="hover:bg-slate-50">
                                                                <td class="px-3 py-2 font-mono font-semibold"
                                                                    x-text="dup.no_polisi"></td>
                                                                <td colspan="3" class="px-3 py-2 text-slate-400 italic">
                                                                    Tidak ada perubahan</td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <template x-if="previewData.duplicates.length > 10">
                                                <div class="px-3 py-2 bg-slate-50 text-xs text-slate-500 text-center">
                                                    <span
                                                        x-text="'... dan ' + (previewData.duplicates.length - 10) + ' duplikat lainnya'"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <!-- Errors List -->
                                <template x-if="previewData?.errors?.length > 0">
                                    <div class="mb-4">
                                        <h4
                                            class="text-xs sm:text-sm font-bold text-red-700 mb-2 flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                            Error Validasi
                                        </h4>
                                        <div
                                            class="bg-red-50 border border-red-200 rounded-xl p-3 space-y-1 max-h-32 overflow-y-auto">
                                            <template x-for="(err, i) in previewData.errors.slice(0, 10)" :key="i">
                                                <p class="text-xs text-red-600" x-text="err"></p>
                                            </template>
                                            <template x-if="previewData.errors.length > 10">
                                                <p class="text-xs text-red-400 italic"
                                                    x-text="'... dan ' + (previewData.errors.length - 10) + ' error lainnya'">
                                                </p>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- STEP 3: Confirm & Select Duplicate Action -->
                        <div x-show="step === 3" x-transition>
                            <div class="space-y-4">
                                <!-- Summary -->
                                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                                    <p class="text-sm font-semibold text-slate-700 mb-2">Ringkasan Import:</p>
                                    <ul class="text-xs text-slate-600 space-y-1">
                                        <li class="flex items-center gap-2">
                                            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                            <span
                                                x-text="(previewData?.new_count || 0) + ' kendaraan baru akan ditambahkan'"></span>
                                        </li>
                                        <li class="flex items-center gap-2" x-show="previewData?.duplicate_count > 0">
                                            <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                                            <span
                                                x-text="(previewData?.duplicate_count || 0) + ' kendaraan duplikat ditemukan'"></span>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Duplicate Action Selection -->
                                <template x-if="previewData?.duplicate_count > 0">
                                    <div>
                                        <p class="text-xs sm:text-sm font-bold text-slate-700 mb-3">Apa yang ingin dilakukan
                                            dengan data duplikat?</p>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <button @click="duplicateAction = 'skip'" type="button"
                                                :class="duplicateAction === 'skip' ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-500/20' : 'border-slate-200 hover:border-slate-300'"
                                                class="p-3 sm:p-4 border-2 rounded-xl text-left transition-all">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                                                    </svg>
                                                    <span class="text-sm font-bold text-slate-800">Lewati (Skip)</span>
                                                </div>
                                                <p class="text-xs text-slate-500">Data duplikat tidak diubah, hanya data
                                                    baru yang ditambahkan.</p>
                                            </button>
                                            <button @click="duplicateAction = 'update'" type="button"
                                                :class="duplicateAction === 'update' ? 'border-amber-500 bg-amber-50 ring-2 ring-amber-500/20' : 'border-slate-200 hover:border-slate-300'"
                                                class="p-3 sm:p-4 border-2 rounded-xl text-left transition-all">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                        </path>
                                                    </svg>
                                                    <span class="text-sm font-bold text-slate-800">Perbarui (Update)</span>
                                                </div>
                                                <p class="text-xs text-slate-500">Data duplikat akan diperbarui dengan data
                                                    dari file Excel.</p>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-4 sm:px-6 py-3 sm:py-4 bg-slate-50 border-t border-slate-200 shrink-0">
                        <div class="flex gap-2 sm:gap-3">
                            <button @click="step > 1 ? step-- : closeModal()" type="button"
                                class="flex-1 px-3 sm:px-4 py-2.5 sm:py-3 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors"
                                x-text="step > 1 ? 'Kembali' : 'Batal'"></button>

                            <!-- Step 1: Preview Button -->
                            <button x-show="step === 1" @click="previewImport()" type="button"
                                :disabled="!selectedFile || isLoading"
                                class="flex-1 px-3 sm:px-4 py-2.5 sm:py-3 bg-gradient-to-r from-violet-500 to-indigo-600 text-white text-sm font-bold rounded-xl hover:from-violet-600 hover:to-indigo-700 shadow-lg shadow-violet-500/30 hover:shadow-violet-500/40 transition-all hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                                <span x-show="!isLoading"><svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg> Preview Import</span>
                                <span x-show="isLoading"><svg class="w-4 h-4 inline mr-1 animate-spin" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg> Menganalisis...</span>
                            </button>

                            <!-- Step 2: Next Button -->
                            <button x-show="step === 2" @click="step = 3" type="button"
                                :disabled="!previewData || (previewData.new_count === 0 && previewData.duplicate_count === 0)"
                                class="flex-1 px-3 sm:px-4 py-2.5 sm:py-3 bg-gradient-to-r from-violet-500 to-indigo-600 text-white text-sm font-bold rounded-xl hover:from-violet-600 hover:to-indigo-700 shadow-lg shadow-violet-500/30 hover:shadow-violet-500/40 transition-all hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                                Lanjutkan <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </button>

                            <!-- Step 3: Confirm Import -->
                            <button x-show="step === 3" @click="confirmImport()" type="button"
                                :disabled="isImporting || (previewData?.duplicate_count > 0 && !duplicateAction)"
                                class="flex-1 px-3 sm:px-4 py-2.5 sm:py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-sm font-bold rounded-xl hover:from-emerald-600 hover:to-teal-700 shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/40 transition-all hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                                <span x-show="!isImporting"><svg class="w-4 h-4 inline mr-1" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg> Import Sekarang</span>
                                <span x-show="isImporting"><svg class="w-4 h-4 inline mr-1 animate-spin" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg> Memproses...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function importKendaraanModal() {
                return {
                    showModal: false,
                    step: 1,
                    selectedFile: null,
                    isDragging: false,
                    isLoading: false,
                    isImporting: false,
                    uploadError: '',
                    previewData: null,
                    duplicateAction: 'skip',

                    openModal() {
                        this.showModal = true;
                        this.resetState();
                    },

                    closeModal() {
                        this.showModal = false;
                        this.resetState();
                    },

                    resetState() {
                        this.step = 1;
                        this.selectedFile = null;
                        this.isDragging = false;
                        this.isLoading = false;
                        this.isImporting = false;
                        this.uploadError = '';
                        this.previewData = null;
                        this.duplicateAction = 'skip';
                    },

                    handleDrop(e) {
                        this.isDragging = false;
                        const files = e.dataTransfer.files;
                        if (files.length > 0) {
                            this.validateAndSetFile(files[0]);
                        }
                    },

                    handleFileSelect(e) {
                        if (e.target.files.length > 0) {
                            this.validateAndSetFile(e.target.files[0]);
                        }
                    },

                    validateAndSetFile(file) {
                        this.uploadError = '';
                        const allowed = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'text/csv'];
                        const allowedExt = ['.xlsx', '.xls', '.csv'];
                        const ext = '.' + file.name.split('.').pop().toLowerCase();

                        if (!allowedExt.includes(ext)) {
                            this.uploadError = 'Format file tidak didukung. Gunakan .xlsx, .xls, atau .csv';
                            return;
                        }
                        if (file.size > 2 * 1024 * 1024) {
                            this.uploadError = 'Ukuran file melebihi batas 2MB.';
                            return;
                        }
                        this.selectedFile = file;
                    },

                    clearFile() {
                        this.selectedFile = null;
                        this.uploadError = '';
                        if (this.$refs.fileInput) this.$refs.fileInput.value = '';
                    },

                    formatFileSize(bytes) {
                        if (bytes < 1024) return bytes + ' B';
                        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
                        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
                    },

                    async previewImport() {
                        if (!this.selectedFile) return;
                        this.isLoading = true;
                        this.uploadError = '';
                        this.step = 2;

                        const formData = new FormData();
                        formData.append('file', this.selectedFile);

                        try {
                            const resp = await fetch('{{ route("admin.kendaraans.preview-import-kendaraan") }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                },
                                body: formData
                            });

                            if (!resp.ok) {
                                const errData = await resp.json().catch(() => null);
                                throw new Error(errData?.message || 'Gagal menganalisis file. Periksa format dan coba lagi.');
                            }

                            this.previewData = await resp.json();
                        } catch (err) {
                            this.uploadError = err.message;
                            this.step = 1;
                        } finally {
                            this.isLoading = false;
                        }
                    },

                    confirmImport() {
                        if (!this.selectedFile) return;
                        this.isImporting = true;

                        const formData = new FormData();
                        formData.append('file', this.selectedFile);
                        formData.append('duplicate_action', this.previewData?.duplicate_count > 0 ? this.duplicateAction : 'skip');
                        formData.append('_token', '{{ csrf_token() }}');

                        // Create a hidden form and submit traditionally (for redirect)
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route("admin.kendaraans.import-kendaraan") }}';
                        form.enctype = 'multipart/form-data';
                        form.style.display = 'none';
                        form.setAttribute('data-turbo', 'false');

                        // CSRF
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';
                        form.appendChild(csrfInput);

                        // Duplicate action
                        const actionInput = document.createElement('input');
                        actionInput.type = 'hidden';
                        actionInput.name = 'duplicate_action';
                        actionInput.value = this.previewData?.duplicate_count > 0 ? this.duplicateAction : 'skip';
                        form.appendChild(actionInput);

                        // File - use DataTransfer to attach file
                        const fileInput = document.createElement('input');
                        fileInput.type = 'file';
                        fileInput.name = 'file';
                        const dt = new DataTransfer();
                        dt.items.add(this.selectedFile);
                        fileInput.files = dt.files;
                        form.appendChild(fileInput);

                        document.body.appendChild(form);
                        form.submit();
                    }
                }
            }
        </script>
    @endif

    <!-- Monthly Report Modal -->
    <div x-cloak
        x-data="{ showMonthlyReport: false, satkerId: '', bulan: '{{ now()->month }}', tahun: '{{ now()->year }}' }"
        @open-monthly-report.window="showMonthlyReport = true" @turbo:before-cache.window="showMonthlyReport = false">
        <!-- Backdrop -->
        <div x-show="showMonthlyReport" style="display: none;" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50"
            @click="showMonthlyReport = false"></div>

        <!-- Modal -->
        <div x-show="showMonthlyReport" style="display: none;" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="showMonthlyReport = false">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden" @click.stop>
                <!-- Modal Header -->
                <div class="px-6 py-5 bg-gradient-to-r from-rose-500 to-rose-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-white/20 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Laporan Bulanan Satker</h3>
                                <p class="text-sm text-rose-100">Pilih Satker dan Periode Laporan</p>
                            </div>
                        </div>
                        <button @click="showMonthlyReport = false"
                            class="p-1 text-white/70 hover:text-white rounded-lg hover:bg-white/10 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <form action="{{ route('admin.kendaraans.laporan-bulanan') }}" method="GET" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">1. Pilih Satuan Kerja</label>
                        <select name="satker_id" id="report_satker_id" x-model="satkerId" required
                            class="tom-select w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl text-sm font-semibold text-slate-800 transition-all">
                            <option value="">-- Pilih Satker --</option>
                            @foreach($satkers as $s)
                                <option value="{{ $s->id }}">{{ $s->nama_satker }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">2. Pilih Bulan</label>
                            <select name="bulan" x-model="bulan"
                                class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}">
                                        {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">3. Pilih Tahun</label>
                            <select name="tahun" x-model="tahun"
                                class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all">
                                @for($y = now()->year; $y >= now()->year - 5; $y--)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="showMonthlyReport = false"
                            class="flex-1 px-4 py-3 bg-slate-100 text-slate-600 font-semibold rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-rose-500 to-rose-600 text-white font-bold rounded-xl hover:from-rose-600 hover:to-rose-700 shadow-lg shadow-rose-500/30 hover:shadow-rose-500/40 transition-all hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="!satkerId">
                            Buka Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const checkAll = document.getElementById('checkAll');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            const bulkActions = document.getElementById('bulkActions');
            const selectedCountLabel = document.getElementById('selectedCount');

            function updateBulkUI() {
                const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
                selectedCountLabel.innerText = checkedCount;
                if (checkedCount > 0) {
                    bulkActions.classList.remove('hidden');
                    bulkActions.classList.add('flex');
                } else {
                    bulkActions.classList.add('hidden');
                    bulkActions.classList.remove('flex');
                }
            }

            checkAll.addEventListener('change', () => {
                itemCheckboxes.forEach(cb => { cb.checked = checkAll.checked; });
                updateBulkUI();
            });

            itemCheckboxes.forEach(cb => { cb.addEventListener('change', updateBulkUI); });

            document.getElementById('bulkDeleteBtn').addEventListener('click', function () {
                const selected = document.querySelectorAll('.item-checkbox:checked');
                if (selected.length === 0) return;

                Swal.fire({
                    title: 'Hapus Data Massal',
                    text: `Apakah Anda yakin ingin menghapus ${selected.length} kendaraan yang terpilih? Tindakan ini tidak dapat dibatalkan.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const container = document.getElementById('bulkIdsContainer');
                        container.innerHTML = '';
                        selected.forEach(cb => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'ids[]';
                            input.value = cb.value;
                            container.appendChild(input);
                        });
                        document.getElementById('bulkDeleteForm').submit();
                    }
                });
            });
        </script>
    @endpush
    @if(auth()->user()->role === 'super_admin')
        <!-- Transfer Satker Modal -->
        <div x-cloak x-data="{
                                                                                                                    showTransfer: false,
                                                                                                                    transferId: null,
                                                                                                                    transferNopol: '',
                                                                                                                    currentSatker: '',
                                                                                                                    selectedSatkerId: '',
                                                                                                                    satkerSearch: '',
                                                                                                                    satkerOpen: false,
                                                                                                                    satkerLabel: '',
                                                                                                                    satkers: {{ json_encode($satkers->map(fn($s) => ['id' => $s->id, 'nama' => $s->nama_satker])) }},
                                                                                                                    get filteredSatkers() {
                                                                                                                        if (!this.satkerSearch) return this.satkers;
                                                                                                                        return this.satkers.filter(s => s.nama.toLowerCase().includes(this.satkerSearch.toLowerCase()));
                                                                                                                    },
                                                                                                                    selectSatker(s) {
                                                                                                                        this.selectedSatkerId = s.id;
                                                                                                                        this.satkerLabel = s.nama;
                                                                                                                        this.satkerOpen = false;
                                                                                                                        this.satkerSearch = '';
                                                                                                                    },
                                                                                                                    reset() {
                                                                                                                        this.showTransfer = false;
                                                                                                                        setTimeout(() => {
                                                                                                                            this.transferId = null;
                                                                                                                            this.transferNopol = '';
                                                                                                                            this.currentSatker = '';
                                                                                                                            this.selectedSatkerId = '';
                                                                                                                            this.satkerLabel = '';
                                                                                                                        }, 300);
                                                                                                                    }
                                                                                                                }"
            @open-transfer.window="transferId = $event.detail.id; transferNopol = $event.detail.nopol; currentSatker = $event.detail.current_satker; showTransfer = true"
            @turbo:before-cache.window="showTransfer = false">

            <div x-show="showTransfer" style="display: none;" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50" @click="reset()"></div>

            <div x-show="showTransfer" style="display: none;" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md flex flex-col max-h-[90vh]" @click.stop>
                    <div class="px-6 py-5 bg-gradient-to-r from-violet-600 to-indigo-600 shrink-0 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-white/20 rounded-xl text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">Pindah Satker</h3>
                                    <p class="text-sm text-violet-100" x-text="transferNopol"></p>
                                </div>
                            </div>
                            <button @click="reset()" class="text-white/70 hover:text-white transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <form action="{{ route('admin.kendaraans.transfer') }}" method="POST"
                        class="flex flex-col flex-1 overflow-hidden">
                        @csrf
                        <input type="hidden" name="kendaraan_id" :value="transferId">

                        <div class="p-6 space-y-6 overflow-y-auto flex-1">
                            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl space-y-2">
                                <div
                                    class="flex items-center gap-2 text-amber-700 font-bold text-sm uppercase tracking-wider">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                    Penting
                                </div>
                                <p class="text-xs text-amber-600 leading-relaxed font-medium">
                                    Memindahkan kendaraan akan secara otomatis:
                                <ul class="list-disc list-inside mt-1 ml-1">
                                    <li>Mereset PIN kendaraan</li>
                                    <li>Memindahkan seluruh saldo ke Satker baru</li>
                                    <li>Mencatat riwayat keluar di Satker asal</li>
                                    <li>Mencatat riwayat masuk di Satker tujuan</li>
                                </ul>
                                </p>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Satker
                                        Saat Ini</label>
                                    <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 font-semibold italic"
                                        x-text="currentSatker"></div>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Pilih
                                        Satker Tujuan</label>
                                    <div class="relative" @click.outside="satkerOpen = false">
                                        <div @click="satkerOpen = !satkerOpen; $nextTick(() => { if(satkerOpen) $refs.transferSatkerInput.focus() })"
                                            class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl text-sm font-semibold text-slate-800 flex items-center justify-between cursor-pointer transition-all"
                                            :class="satkerOpen ? 'border-violet-500 ring-4 ring-violet-500/10' : ''">
                                            <span x-text="satkerLabel || '-- Pilih Satker Tujuan --'"
                                                :class="satkerLabel ? 'text-slate-800' : 'text-slate-400'"></span>
                                            <input type="hidden" name="satker_id" :value="selectedSatkerId">
                                            <svg class="w-5 h-5 text-slate-400 transition-transform"
                                                :class="satkerOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>

                                        <div x-show="satkerOpen" style="display:none;" x-transition
                                            class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden">
                                            <div class="p-2 border-b border-slate-100">
                                                <input x-ref="transferSatkerInput" x-model="satkerSearch" type="text"
                                                    placeholder="Cari Satker..."
                                                    class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500 border-none bg-slate-50">
                                            </div>
                                            <div class="max-h-48 overflow-y-auto py-1">
                                                <template x-for="s in filteredSatkers" :key="s.id">
                                                    <div @click="selectSatker(s)"
                                                        class="px-4 py-2.5 text-sm text-slate-700 hover:bg-violet-50 cursor-pointer flex items-center justify-between transition-colors"
                                                        :class="selectedSatkerId == s.id ? 'bg-violet-50 text-violet-700 font-bold' : ''">
                                                        <span x-text="s.nama"></span>
                                                        <svg x-show="selectedSatkerId == s.id"
                                                            class="w-4 h-4 text-violet-500" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center gap-3 shrink-0 rounded-b-2xl">
                            <button type="button" @click="reset()"
                                class="flex-1 px-4 py-2.5 bg-white text-slate-700 font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition">
                                Batal
                            </button>
                            <button type="submit" :disabled="!selectedSatkerId"
                                class="flex-[2] px-4 py-2.5 bg-violet-600 text-white font-bold rounded-xl hover:bg-violet-700 transition shadow-lg shadow-violet-500/30 disabled:opacity-50 disabled:shadow-none">
                                Pindahkan Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>