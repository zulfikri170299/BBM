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
                    <button @click="$dispatch('open-import')"
                        class="inline-flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all duration-200 hover:-translate-y-0.5 group relative"
                        title="Import Excel">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                            </path>
                        </svg>
                        <!-- Tooltip -->
                        <span
                            class="absolute -bottom-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">Import
                            Excel</span>
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

                <!-- Filter Satker Dropdown (Icon Only) -->
                <div class="relative" x-data="{ open: false }">
                    <form action="{{ route('admin.kendaraans.index') }}" method="GET">
                        <input type="hidden" name="satker_id" id="satker_id" value="{{ request('satker_id') }}">
                        <button type="button" @click="open = !open" @click.away="open = false"
                            class="inline-flex items-center justify-center w-10 h-10 bg-white text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-200 shadow-sm transition-all duration-200 group relative"
                            :class="{'bg-indigo-50 border-indigo-200 text-indigo-600': '{{ request('satker_id') }}' }">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                </path>
                            </svg>
                            <!-- Tooltip -->
                            <span
                                class="absolute -bottom-8 right-0 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">
                                @php
                                    $selectedSatker = $satkers->firstWhere('id', request('satker_id'));
                                    echo $selectedSatker ? 'Filter: ' . $selectedSatker->nama_satker : 'Filter Satker';
                                @endphp
                            </span>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 z-20 w-64 mt-2 bg-white rounded-xl shadow-xl border border-slate-100 py-1 max-h-60 overflow-auto focus:outline-none"
                            style="display: none;">

                            <div
                                class="px-3 py-2 text-xs font-semibold text-slate-400 uppercase tracking-wider bg-slate-50/50">
                                Pilih Satuan Kerja
                            </div>

                            <button type="button"
                                @click="document.getElementById('satker_id').value = ''; $el.closest('form').submit();"
                                class="w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-indigo-600 flex items-center justify-between group transition-colors border-b border-slate-50">
                                <span class="font-medium">Semua Satker</span>
                                @if(!request('satker_id'))
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @endif
                            </button>

                            @foreach($satkers as $satker)
                                <button type="button"
                                    @click="document.getElementById('satker_id').value = '{{ $satker->id }}'; $el.closest('form').submit();"
                                    class="w-full text-left px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600 flex items-center justify-between group transition-colors">
                                    <span class="truncate">{{ $satker->nama_satker }}</span>
                                    @if(request('satker_id') == $satker->id)
                                        <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Success Alert -->
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl"
                x-data="{ show: true }" x-show="show" x-transition>
                <div class="flex-shrink-0 p-1.5 bg-emerald-100 rounded-full">
                    <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p class="text-sm font-medium text-emerald-700 flex-1">{{ session('success') }}</p>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 rounded-xl" x-data="{ show: true }"
                x-show="show" x-transition>
                <div class="flex-shrink-0 p-1.5 bg-red-100 rounded-full">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p class="text-sm font-medium text-red-700 flex-1">{{ session('error') }}</p>
                <button @click="show = false" class="text-red-400 hover:text-red-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex-shrink-0 p-1.5 bg-red-100 rounded-full">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    @foreach($errors->all() as $error)
                        <p class="text-sm font-medium text-red-700">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

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
            </div>

            <form id="bulkDeleteForm" action="{{ route('admin.kendaraans.bulk-delete') }}" method="POST" class="hidden">
                @csrf
                <div id="bulkIdsContainer"></div>
            </form>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-slate-50/70">
                            <th class="w-10 px-6 py-3.5">
                                <input type="checkbox" id="checkAll"
                                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 shadow-sm cursor-pointer">
                            </th>
                            <th
                                class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Satker</th>
                            <th
                                class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Kode</th>
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
                                <td class="px-6 py-4">
                                    <input type="checkbox" value="{{ $kendaraan->id }}"
                                        class="item-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 shadow-sm cursor-pointer">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex-shrink-0 w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                            {{ strtoupper(substr($kendaraan->satker->nama_satker ?? '-', 0, 2)) }}
                                        </div>
                                        <span
                                            class="text-sm font-medium text-slate-700">{{ $kendaraan->satker->nama_satker ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <code
                                        class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-mono font-bold">{{ $kendaraan->kode_kendaraan ?? '-' }}</code>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm font-semibold text-slate-800">{{ $kendaraan->jenis_kendaraan }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-slate-800">{{ $kendaraan->no_polisi }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $bbmColors = [
                                            'Pertamax' => 'bg-blue-100 text-blue-700',
                                            'Pertamina Dex' => 'bg-emerald-100 text-emerald-700',
                                        ];
                                        $color = $bbmColors[$kendaraan->jenis_bbm] ?? 'bg-slate-100 text-slate-700';
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $color }}">
                                        {{ $kendaraan->jenis_bbm }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span
                                        class="text-sm font-bold {{ $kendaraan->saldo < 10 ? 'text-red-600' : 'text-slate-800' }}">
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
        <div x-cloak x-data="{
                                showTopup: false,
                                topupId: null,
                                topupNopol: '',
                                topupSaldo: '',
                                jumlah: '',
                                topupPassword: '',
                                selectMode: false,
                                // Unified Vehicle Search
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
                                    this.topupNopol = k.nopol;
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
                                get filteredList() {
                                    if (!this.kendaraanSearch) return this.allKendaraans;
                                    let s = this.kendaraanSearch.toLowerCase();
                                    return this.allKendaraans.filter(k => 
                                        k.nopol.toLowerCase().includes(s) || 
                                        k.satker_nama.toLowerCase().includes(s) ||
                                        k.jenis_bbm.toLowerCase().includes(s)
                                    );
                                },
                                get currentAdminStock() {
                                    if (!this.topupId) return 0;
                                    const k = this.allKendaraans.find(x => x.id == this.topupId);
                                    if (!k) return 0;
                                    const s = this.adminStocks.find(x => x.jenis_bbm == k.jenis_bbm);
                                    return s ? s.saldo : 0;
                                },
                                get canSubmitManual() {
                                    return this.topupId && this.jumlah && this.jumlah > 0 && this.jumlah <= this.currentAdminStock && this.topupPassword;
                                },
                                selectKendaraan(id) {
                                    const k = this.allKendaraans.find(x => x.id == id);
                                    if (k) {
                                        this.topupId = k.id;
                                        this.topupNopol = k.nopol;
                                        this.topupSaldo = k.saldo;
                                    }
                                },
                                reset() {
                                    this.showTopup = false;
                                    setTimeout(() => {
                                        this.jumlah = '';
                                        this.topupPassword = '';
                                        this.topupId = null;
                                        this.kendaraanLabel = '';
                                        this.kendaraanSearch = '';
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
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl max-h-[95vh] flex flex-col overflow-hidden"
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
                                    <h3 class="text-base sm:text-lg font-bold text-white uppercase tracking-tight">Top Up
                                        Saldo — SIM BBM</h3>
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
                        <div class="p-4 sm:p-6 space-y-6 overflow-y-auto flex-1 bg-slate-50/30">
                            <!-- Hidden Inputs -->
                            <input type="hidden" name="kendaraan_id" :value="topupId">

                            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-4">
                                <div x-show="selectMode" class="space-y-2">
                                    <label
                                        class="block text-xs sm:text-sm font-bold text-slate-700 uppercase tracking-wider">Cari
                                        Kendaraan (Nopol/Satker)</label>
                                    <div class="relative" @click.outside="kendaraanOpen = false">
                                        <div @click="kendaraanOpen = !kendaraanOpen; $nextTick(() => { if(kendaraanOpen) $refs.kendaraanInputManual.focus() })"
                                            class="w-full px-3 sm:px-4 py-3 bg-white border-2 border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 flex items-center justify-between cursor-pointer transition-all"
                                            :class="kendaraanOpen ? 'border-emerald-500 ring-4 ring-emerald-500/10' : ''">
                                            <span x-text="kendaraanLabel || '-- Pilih Kendaraan --'"
                                                :class="kendaraanLabel ? 'text-slate-800' : 'text-slate-400'"></span>
                                            <svg class="w-4 h-4 text-slate-400 transition-transform"
                                                :class="kendaraanOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        <div x-show="kendaraanOpen" x-transition.opacity.duration.150ms
                                            class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden"
                                            style="display:none;">
                                            <div class="p-2 border-b border-slate-100 bg-slate-50">
                                                <div class="relative">
                                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                    </svg>
                                                    <input x-ref="kendaraanInputManual" x-model="kendaraanSearch"
                                                        type="text" placeholder="Ketik Nopol atau Nama Satker..."
                                                        class="w-full pl-9 pr-3 py-2 text-sm border-2 border-slate-200 rounded-lg focus:outline-none focus:border-emerald-500 transition-colors">
                                                </div>
                                            </div>
                                            <div class="max-h-60 overflow-y-auto">
                                                <template x-for="k in filteredList" :key="k.id">
                                                    <div @click="selectKendaraanManual(k)"
                                                        class="px-4 py-2.5 hover:bg-emerald-50 cursor-pointer border-b border-slate-50 last:border-0 transition-colors"
                                                        :class="topupId == k.id ? 'bg-emerald-50/50' : ''">
                                                        <div class="flex items-center justify-between gap-3">
                                                            <div>
                                                                <p class="text-xs sm:text-sm font-bold text-slate-800"
                                                                    x-text="k.nopol"></p>
                                                                <p class="text-[10px] text-slate-500"
                                                                    x-text="k.satker_nama"></p>
                                                            </div>
                                                            <div class="text-right">
                                                                <span
                                                                    class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full"
                                                                    x-text="k.saldo + ' L'"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                                <div x-show="filteredList.length === 0"
                                                    class="px-4 py-6 text-sm text-slate-400 text-center flex flex-col items-center gap-2">
                                                    <svg class="w-8 h-8 opacity-20" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                        </path>
                                                    </svg>
                                                    <span>Data tidak ditemukan</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Informasi Saldo & Stok -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" x-show="topupId" x-transition>
                                <!-- Current Saldo -->
                                <div class="flex items-center gap-3 p-4 bg-blue-50/50 rounded-xl border border-blue-100">
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
                                <div class="flex items-center gap-3 p-4 bg-amber-50 rounded-xl border border-amber-200">
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
                            <div class="bg-white p-4 sm:p-6 rounded-xl border border-slate-200 shadow-sm space-y-4"
                                x-show="topupId" x-transition>
                                <div>
                                    <label for="jumlah"
                                        class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wider text-center">Jumlah
                                        Top Up</label>
                                    <div class="relative max-w-[200px] mx-auto">
                                        <input type="number" name="jumlah" id="jumlah" x-model="jumlah" step="0.1" min="0.1"
                                            max="10000" required placeholder="0.0"
                                            class="w-full px-4 py-4 pr-16 bg-slate-50 border-2 border-slate-200 rounded-2xl text-2xl font-black text-slate-800 text-center focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all placeholder:text-slate-200">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                            <span class="text-xs font-bold text-slate-400">LITER</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Amount Buttons -->
                                <div class="grid grid-cols-4 gap-2">
                                    <template x-for="q in [5, 10, 15, 20, 25, 30, 40, 50]">
                                        <button type="button" @click="jumlah = q"
                                            class="py-2 text-xs font-bold rounded-xl border-2 transition-all hover:scale-105 active:scale-95"
                                            :class="jumlah == q ? 'border-emerald-500 bg-emerald-50 text-emerald-700 ring-2 ring-emerald-200' : 'border-slate-100 bg-white text-slate-500 hover:border-emerald-200 hover:bg-emerald-50/50'"
                                            x-text="q + ' L'"></button>
                                    </template>
                                </div>

                                <div class="pt-2">
                                    <label for="topupPassword"
                                        class="block text-xs font-bold text-slate-400 mb-2 uppercase tracking-widest text-center">PIN
                                        Keamanan Admin</label>
                                    <input type="password" id="topupPassword" name="topupPassword" x-model="topupPassword"
                                        required placeholder="••••••"
                                        class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-center text-lg font-bold tracking-[0.5em] focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all placeholder:tracking-normal placeholder:font-normal placeholder:text-slate-200"
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
                        <div class="px-4 sm:px-6 py-4 bg-white border-t border-slate-100 flex gap-3 shrink-0">
                            <button type="button" @click="reset()"
                                class="flex-1 px-4 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all active:scale-95 text-xs sm:text-sm uppercase tracking-wider">Batal</button>
                            <button type="submit"
                                class="flex-[2] px-4 py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-black rounded-xl hover:from-emerald-600 hover:to-green-700 shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/40 transition-all hover:-translate-y-0.5 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:grayscale text-xs sm:text-sm uppercase tracking-widest flex items-center justify-center gap-2"
                                :disabled="!canSubmitManual">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span>Simpan Top Up</span>
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
                                    <li>Kolom <strong>NO</strong> — Nomor urut</li>
                                    <li>Kolom <strong>SATKER</strong> — Nama satuan kerja</li>
                                    <li>Kolom <strong>KODE KENDARAAN</strong> — Kode unik kendaraan</li>
                                    <li>Kolom <strong>JENIS KENDARAAN</strong> — Tipe kendaraan</li>
                                    <li>Kolom <strong>NOPOL</strong> — Nomor polisi kendaraan</li>
                                    <li>Kolom <strong>JENIS BBM</strong> — Tipe BBM</li>
                                    <li>Kolom <strong>JUMLAH LITER</strong> — Jumlah liter top up</li>
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
                                📥 Import Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Monthly Report Modal -->
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
                        <select name="satker_id" x-model="satkerId" required
                            class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all">
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
</x-app-layout>