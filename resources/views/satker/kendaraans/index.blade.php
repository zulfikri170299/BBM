<x-app-layout>
    <div class="p-3 sm:p-6 lg:p-8 space-y-4 sm:space-y-6" x-data="{ 
        showTransferModal: false, 
        showMonthlyReportModal: false,
        showImportModal: false,
        selectedBbm: '',
        personels: {{ json_encode($personels) }}
    }">
        <!-- Page Header -->
        <div class="flex flex-col gap-3 sm:gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900">Kendaraan</h1>
                <p class="mt-0.5 sm:mt-1 text-xs sm:text-sm text-slate-500">Kelola armada kendaraan
                    {{ Auth::user()->satker->nama_satker ?? '' }}
                </p>
            </div>
            <div class="flex gap-2 sm:gap-3">
                <button @click="showMonthlyReportModal = true"
                    class="inline-flex items-center justify-center w-10 h-10 bg-rose-600 text-white rounded-xl hover:bg-rose-700 shadow-lg shadow-rose-500/30 transition-all duration-200 hover:-translate-y-0.5 group relative"
                    title="Laporan Bulanan">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span
                        class="absolute -bottom-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">Laporan
                        Bulanan</span>
                </button>
                <a href="{{ route('satker.kendaraans.laporan-transfer') }}"
                    class="inline-flex items-center justify-center w-10 h-10 bg-amber-500 text-white rounded-xl hover:bg-amber-600 shadow-lg shadow-amber-500/30 transition-all duration-200 hover:-translate-y-0.5 group relative"
                    title="Laporan Transfer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span
                        class="absolute -bottom-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">Laporan
                        Transfer</span>
                </a>
                <button @click="showTransferModal = true"
                    class="inline-flex items-center justify-center w-10 h-10 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 transition-all duration-200 hover:-translate-y-0.5 group relative"
                    title="Transfer Saldo">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                    <span
                        class="absolute -bottom-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">Transfer
                        Saldo</span>
                </button>
                @if(\App\Models\Setting::where('key', 'satker_can_create_kendaraan')->value('value') ?? 1)
                    <a href="{{ route('satker.kendaraans.create') }}"
                        class="inline-flex items-center justify-center w-10 h-10 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 group relative"
                        title="Tambah Kendaraan">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span
                            class="absolute -bottom-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">Tambah
                            Kendaraan</span>
                    </a>
                @endif
                @if((\App\Models\Setting::where('key', 'satker_can_import_kendaraan')->value('value') ?? '1') == '1')
                    <button @click="showImportModal = true"
                        class="inline-flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all duration-200 hover:-translate-y-0.5 group relative"
                        title="Import Excel">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                            </path>
                        </svg>
                        <span
                            class="absolute -bottom-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">Import
                            Excel</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Error Alert -->
        @if(session('error'))
            <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl mb-4" x-data="{ show: true }"
                x-show="show" x-transition>
                <div class="flex-shrink-0 p-1.5 bg-red-100 rounded-full mt-0.5">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl mb-4">
                <div class="flex-shrink-0 p-1.5 bg-red-100 rounded-full mt-0.5">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-red-800">Terdapat beberapa kesalahan:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Success Alert -->
        @if(session('success'))
            <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl"
                x-data="{ show: true }" x-show="show" x-transition>
                <div class="flex-shrink-0 p-1.5 bg-emerald-100 rounded-full mt-0.5">
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

        <!-- Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="p-1.5 sm:p-2 bg-blue-100 text-blue-600 rounded-lg">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm sm:text-base font-semibold text-slate-800">Daftar Kendaraan</h3>
                        <p class="text-[10px] sm:text-xs text-slate-400">{{ $kendaraans->total() }} kendaraan terdaftar
                        </p>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions Bar -->
            <div id="bulkActionsBar"
                class="hidden px-4 sm:px-6 py-3 bg-indigo-50 border-b border-indigo-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-semibold text-indigo-800"><span id="selectedCount">0</span> data
                        dipilih</span>
                </div>
                <button type="button" onclick="bulkDeleteKendaraan()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-lg shadow transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    Hapus Terpilih
                </button>
            </div>

            <!-- Hidden form for bulk delete -->
            <form id="bulkDeleteForm" action="{{ route('satker.kendaraans.bulk-delete') }}" method="POST"
                class="hidden">
                @csrf
                <div id="bulkDeleteInputs"></div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-slate-50/70">
                            <th class="px-4 py-3.5 text-center w-10">
                                <input type="checkbox" id="checkAll"
                                    class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 cursor-pointer">
                            </th>
                            <th
                                class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-12">
                                No</th>
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
                                class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kendaraans as $kendaraan)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-4 text-center">
                                    <input type="checkbox" name="item_ids[]" value="{{ $kendaraan->id }}"
                                        class="item-checkbox w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 cursor-pointer">
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="text-sm font-semibold text-slate-500">{{ $loop->iteration + ($kendaraans->currentPage() - 1) * $kendaraans->perPage() }}</span>
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
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('satker.kendaraans.print', $kendaraan) }}" target="_blank"
                                            rel="nofollow"
                                            class="p-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-600 hover:text-indigo-700 rounded-lg transition-colors group"
                                            title="Print Barcode">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                </path>
                                            </svg>
                                        </a>
                                        @if(\App\Models\Setting::where('key', 'satker_can_edit_kendaraan')->value('value') ?? 1)
                                            <a href="{{ route('satker.kendaraans.edit', $kendaraan) }}"
                                                class="p-2 bg-amber-100 hover:bg-amber-200 text-amber-600 hover:text-amber-700 rounded-lg transition-colors group"
                                                title="Edit Kendaraan">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
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
                                                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="text-slate-500 font-medium">Belum ada kendaraan terdaftar</p>
                                        <a href="{{ route('satker.kendaraans.create') }}"
                                            class="mt-3 text-sm font-semibold text-indigo-600 hover:text-indigo-500">Tambah
                                            kendaraan pertama →</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($kendaraans->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $kendaraans->links() }}
                </div>
            @endif
        </div>

        <!-- Transfer Modal -->
        <div x-show="showTransferModal" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 py-6">
                <!-- Backdrop -->
                <div x-show="showTransferModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                    @click="showTransferModal = false"></div>

                <!-- Modal Panel -->
                <div x-show="showTransferModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto max-h-[90vh] flex flex-col overflow-hidden">
                    <form action="{{ route('satker.kendaraans.transfer') }}" method="POST">
                        @csrf

                        <!-- Header with Gradient -->
                        <div
                            class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 px-4 sm:px-6 py-4 sm:py-5 shrink-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-white" id="modal-title">Transfer Saldo</h3>
                                        <p class="text-emerald-100 text-xs">Kendaraan → Personel</p>
                                    </div>
                                </div>
                                <button type="button" @click="showTransferModal = false"
                                    class="p-1.5 hover:bg-white/20 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Form Body -->
                        <div class="px-4 sm:px-6 py-4 sm:py-5 space-y-4 sm:space-y-5 overflow-y-auto flex-1">

                            <!-- Sumber Kendaraan -->
                            <div>
                                <label for="kendaraan_id"
                                    class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                                    <span
                                        class="flex items-center justify-center w-6 h-6 rounded-lg bg-blue-100 text-blue-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1">
                                            </path>
                                        </svg>
                                    </span>
                                    Sumber Dana
                                </label>
                                <select name="kendaraan_id" id="kendaraan_id" required
                                    @change="selectedBbm = $event.target.selectedOptions[0].dataset.bbm"
                                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all appearance-none cursor-pointer">
                                    <option value="" data-bbm="">— Pilih Kendaraan —</option>
                                    @foreach($availableKendaraans as $k)
                                        <option value="{{ $k->id }}" data-bbm="{{ $k->jenis_bbm }}">{{ $k->no_polisi }} •
                                            {{ $k->jenis_bbm }} • {{ number_format($k->saldo, 0, ',', '.') }} L
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Arrow Divider -->
                            <div class="flex items-center justify-center">
                                <div
                                    class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-b from-emerald-100 to-teal-100 border-2 border-emerald-200 shadow-sm">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Tujuan Personel -->
                            <div>
                                <label for="personel_id"
                                    class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                                    <span
                                        class="flex items-center justify-center w-6 h-6 rounded-lg bg-purple-100 text-purple-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </span>
                                    Tujuan Personel
                                </label>
                                <select name="personel_id" id="personel_id" required
                                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all appearance-none cursor-pointer">
                                    <option value="">— Pilih Personel —</option>
                                    <template
                                        x-for="personel in personels.filter(p => !selectedBbm || !p.jenis_bbm || p.jenis_bbm === selectedBbm)">
                                        <option :value="personel.id"
                                            x-text="personel.nama + ' • ' + (personel.jenis_bbm ? personel.jenis_bbm : 'Belum set BBM')">
                                        </option>
                                    </template>
                                </select>
                            </div>

                            <div class="h-px bg-slate-100"></div>

                            <!-- Jumlah Transfer -->
                            <div>
                                <label for="jumlah"
                                    class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                                    <span
                                        class="flex items-center justify-center w-6 h-6 rounded-lg bg-amber-100 text-amber-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                            </path>
                                        </svg>
                                    </span>
                                    Jumlah Transfer
                                </label>
                                <div class="relative">
                                    <input type="number" name="jumlah" id="jumlah" required step="0.01" min="0.1"
                                        class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all pr-16"
                                        placeholder="Masukkan jumlah">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                        <span
                                            class="text-xs font-bold text-slate-400 bg-slate-200/60 px-2 py-0.5 rounded-md">LITER</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Keterangan -->
                            <div>
                                <label for="keterangan"
                                    class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                                    <span
                                        class="flex items-center justify-center w-6 h-6 rounded-lg bg-slate-100 text-slate-500">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                                            </path>
                                        </svg>
                                    </span>
                                    Keterangan
                                    <span class="text-xs font-normal text-slate-400">(opsional)</span>
                                </label>
                                <input type="text" name="keterangan" id="keterangan"
                                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                                    placeholder="Catatan tambahan...">
                            </div>
                        </div>

                        <!-- Footer -->
                        <div
                            class="px-4 sm:px-6 py-3 sm:py-4 bg-slate-50/80 border-t border-slate-100 flex flex-row-reverse gap-2 sm:gap-3 shrink-0">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-sm font-bold rounded-xl hover:from-emerald-600 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-lg shadow-emerald-500/25 transition-all duration-200 hover:-translate-y-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Konfirmasi Transfer
                            </button>
                            <button type="button" @click="showTransferModal = false"
                                class="inline-flex items-center px-5 py-2.5 bg-white text-slate-600 text-sm font-semibold rounded-xl border border-slate-200 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-300 transition-all duration-200">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Monthly Report Modal -->
        <div x-show="showMonthlyReportModal" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 py-6">
                <!-- Backdrop -->
                <div x-show="showMonthlyReportModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                    @click="showMonthlyReportModal = false"></div>

                <!-- Modal Panel -->
                <div x-show="showMonthlyReportModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-auto max-h-[90vh] flex flex-col overflow-hidden">
                    <form action="{{ route('satker.kendaraans.laporan-bulanan') }}" method="GET">
                        <!-- Header with Gradient -->
                        <div
                            class="bg-gradient-to-r from-rose-500 via-rose-600 to-rose-700 px-4 sm:px-6 py-4 sm:py-5 shrink-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-white">Laporan Bulanan</h3>
                                        <p class="text-rose-100 text-xs">Pilih Periode Laporan</p>
                                    </div>
                                </div>
                                <button type="button" @click="showMonthlyReportModal = false"
                                    class="p-1.5 hover:bg-white/20 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Form Body -->
                        <div class="px-4 sm:px-6 py-4 sm:py-5 space-y-4 overflow-y-auto flex-1">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Bulan</label>
                                <select name="bulan"
                                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500 transition-all appearance-none cursor-pointer">
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ now()->month == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Tahun</label>
                                <select name="tahun"
                                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500 transition-all appearance-none cursor-pointer">
                                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                                        <option value="{{ $y }}" {{ now()->year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div
                            class="px-4 sm:px-6 py-3 sm:py-4 bg-slate-50/80 border-t border-slate-100 flex flex-row-reverse gap-2 sm:gap-3 shrink-0">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-rose-500 to-rose-600 text-white text-sm font-bold rounded-xl hover:from-rose-600 hover:to-rose-700 shadow-lg shadow-rose-500/25 transition-all duration-200 hover:-translate-y-0.5">
                                Buka Laporan
                            </button>
                            <button type="button" @click="showMonthlyReportModal = false"
                                class="inline-flex items-center px-5 py-2.5 bg-white text-slate-600 text-sm font-semibold rounded-xl border border-slate-200 hover:bg-slate-50 transition-all duration-200">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Import Kendaraan Modal -->
        @if((\App\Models\Setting::where('key', 'satker_can_import_kendaraan')->value('value') ?? '1') == '1')
            <div x-show="showImportModal" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
                aria-labelledby="import-modal-title" role="dialog" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen px-4 py-6">
                    <!-- Backdrop -->
                    <div x-show="showImportModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                        @click="showImportModal = false"></div>

                    <!-- Modal Panel -->
                    <div x-show="showImportModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto max-h-[90vh] flex flex-col overflow-hidden">
                        <form id="importKendaraanForm" action="{{ route('satker.kendaraans.import') }}" method="POST"
                            enctype="multipart/form-data" data-turbo="false">
                            @csrf
                            <input type="hidden" name="duplicate_action" id="duplicateActionInput" value="skip">

                            <!-- Header with Gradient -->
                            <div
                                class="bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 px-4 sm:px-6 py-4 sm:py-5 shrink-0">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-base sm:text-lg font-bold text-white" id="import-modal-title">
                                                Import Data Kendaraan</h3>
                                            <p class="text-blue-100 text-[10px] sm:text-xs">Upload file Excel (.xlsx, .xls)
                                            </p>
                                        </div>
                                    </div>
                                    <button type="button" @click="showImportModal = false"
                                        class="p-1.5 hover:bg-white/20 rounded-lg transition-colors">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Form Body -->
                            <div class="px-4 sm:px-6 py-4 sm:py-5 space-y-4 sm:space-y-5 overflow-y-auto flex-1">

                                <!-- Download Template -->
                                <div
                                    class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-3 sm:p-4">
                                    <div class="flex items-start gap-3">
                                        <div class="p-1.5 bg-blue-100 rounded-lg shrink-0 mt-0.5">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs sm:text-sm font-semibold text-blue-800">Download Template</p>
                                            <p class="text-[10px] sm:text-xs text-blue-600 mt-0.5">Unduh format template
                                                Excel sebelum melakukan import data</p>
                                            <a href="{{ route('satker.kendaraans.download-template') }}"
                                                class="inline-flex items-center gap-1.5 mt-2 px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                    </path>
                                                </svg>
                                                Download Template
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- File Upload -->
                                <div>
                                    <label
                                        class="flex items-center gap-2 text-xs sm:text-sm font-semibold text-slate-700 mb-2">
                                        <span
                                            class="flex items-center justify-center w-5 h-5 sm:w-6 sm:h-6 rounded-lg bg-indigo-100 text-indigo-600">
                                            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                        </span>
                                        Pilih File Excel
                                    </label>
                                    <div class="relative">
                                        <input type="file" name="file" id="importFileInput" accept=".xlsx,.xls,.csv"
                                            required
                                            class="block w-full text-xs sm:text-sm text-slate-500 file:mr-3 sm:file:mr-4 file:py-2 file:px-3 sm:file:px-4 file:rounded-lg file:border-0 file:text-xs sm:file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-slate-200 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <p class="mt-1.5 text-[10px] sm:text-xs text-slate-400">Format: .xlsx, .xls, .csv |
                                        Maks: 2MB</p>
                                </div>

                                <!-- Instructions -->
                                <div class="bg-amber-50 border border-amber-200 rounded-xl p-3">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="text-[10px] sm:text-xs text-amber-700">
                                            <p class="font-semibold mb-1">Petunjuk:</p>
                                            <ul class="space-y-0.5 list-disc list-inside">
                                                <li>Kolom wajib: <b>NO POLISI</b>, <b>JENIS KENDARAAN</b>, <b>JENIS BBM</b>
                                                </li>
                                                <li>Jenis BBM: <b>Pertamax</b> atau <b>Pertamina Dex</b></li>
                                                <li>Kode, Barcode, PIN diisi otomatis</li>
                                                <li>Data duplikat akan dikonfirmasi terlebih dahulu</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div
                                class="px-4 sm:px-6 py-3 sm:py-4 bg-slate-50/80 border-t border-slate-100 flex flex-row-reverse gap-2 sm:gap-3 shrink-0">
                                <button type="submit" id="importSubmitBtn"
                                    class="inline-flex items-center gap-1.5 sm:gap-2 px-4 sm:px-6 py-2 sm:py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-xs sm:text-sm font-bold rounded-xl hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg shadow-blue-500/25 transition-all duration-200 hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                    Import Sekarang
                                </button>
                                <button type="button" @click="showImportModal = false"
                                    class="inline-flex items-center px-4 sm:px-5 py-2 sm:py-2.5 bg-white text-slate-600 text-xs sm:text-sm font-semibold rounded-xl border border-slate-200 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-300 transition-all duration-200">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('importKendaraanForm');
            if (!form) return;

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const fileInput = document.getElementById('importFileInput');
                if (!fileInput.files.length) {
                    Swal.fire({ icon: 'warning', title: 'File Kosong', text: 'Silakan pilih file Excel terlebih dahulu.' });
                    return;
                }

                const submitBtn = document.getElementById('importSubmitBtn');
                const originalBtnHTML = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Memeriksa...';

                // Step 1: Preview for duplicates
                const formData = new FormData();
                formData.append('file', fileInput.files[0]);
                formData.append('_token', '{{ csrf_token() }}');

                fetch('{{ route("satker.kendaraans.preview-import") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                    .then(res => {
                        if (res.status === 403) return res.json().then(d => { throw new Error(d.error || 'Fitur dinonaktifkan'); });
                        if (!res.ok) return res.json().then(d => { throw new Error(d.error || d.message || 'Gagal memproses file'); });
                        return res.json();
                    })
                    .then(data => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnHTML;

                        if (data.duplicates && data.duplicates.length > 0) {
                            // Show duplicate confirmation popup
                            let tableHTML = `
                        <div style="max-height:300px;overflow-y:auto;margin-top:10px;">
                            <table style="width:100%;border-collapse:collapse;font-size:12px;">
                                <thead>
                                    <tr style="background:#f1f5f9;">
                                        <th style="padding:8px;border:1px solid #e2e8f0;text-align:left;">No Polisi</th>
                                        <th style="padding:8px;border:1px solid #e2e8f0;text-align:left;">Data Lama</th>
                                        <th style="padding:8px;border:1px solid #e2e8f0;text-align:left;">Data Baru</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                            data.duplicates.forEach(d => {
                                tableHTML += `
                            <tr>
                                <td style="padding:6px 8px;border:1px solid #e2e8f0;font-weight:600;">${d.no_polisi}</td>
                                <td style="padding:6px 8px;border:1px solid #e2e8f0;">
                                    <span style="color:#64748b;">${d.old_jenis_kendaraan}</span><br>
                                    <span style="font-size:10px;color:#94a3b8;">${d.old_jenis_bbm}</span>
                                </td>
                                <td style="padding:6px 8px;border:1px solid #e2e8f0;">
                                    <span style="color:#2563eb;font-weight:500;">${d.new_jenis_kendaraan}</span><br>
                                    <span style="font-size:10px;color:#3b82f6;">${d.new_jenis_bbm}</span>
                                </td>
                            </tr>`;
                            });

                            tableHTML += '</tbody></table></div>';

                            let errInfo = '';
                            if (data.errors && data.errors.length > 0) {
                                errInfo = `<div style="margin-top:10px;padding:8px;background:#fef2f2;border-radius:8px;font-size:11px;color:#dc2626;text-align:left;">⚠️ ${data.errors.length} baris juga ada masalah validasi</div>`;
                            }

                            Swal.fire({
                                icon: 'question',
                                title: 'Data Duplikat Ditemukan!',
                                html: `<p style="font-size:13px;color:#64748b;margin-bottom:8px;">Ditemukan <b>${data.duplicates.length}</b> data kendaraan yang sudah ada di database:</p>` + tableHTML + errInfo,
                                showDenyButton: true,
                                showCancelButton: true,
                                confirmButtonText: '🔄 Gunakan Data Baru',
                                denyButtonText: '📋 Gunakan Data Lama',
                                cancelButtonText: 'Batal',
                                confirmButtonColor: '#2563eb',
                                denyButtonColor: '#64748b',
                                width: '520px',
                                customClass: { popup: 'swal-import-popup' }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.getElementById('duplicateActionInput').value = 'update';
                                    form.submit();
                                } else if (result.isDenied) {
                                    document.getElementById('duplicateActionInput').value = 'skip';
                                    form.submit();
                                }
                            });
                        } else {
                            // No duplicates, submit directly
                            document.getElementById('duplicateActionInput').value = 'skip';
                            form.submit();
                        }
                    })
                    .catch(err => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnHTML;
                        Swal.fire({ icon: 'error', title: 'Error', text: err.message });
                    });
            });
        });
    </script>

    <script>
        // Bulk Delete Checkboxes
        const checkAll = document.getElementById('checkAll');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const bulkActionsBar = document.getElementById('bulkActionsBar');
        const selectedCountEl = document.getElementById('selectedCount');

        function updateBulkUI() {
            const checked = document.querySelectorAll('.item-checkbox:checked');
            selectedCountEl.textContent = checked.length;
            bulkActionsBar.classList.toggle('hidden', checked.length === 0);
            checkAll.checked = checked.length === itemCheckboxes.length && itemCheckboxes.length > 0;
            checkAll.indeterminate = checked.length > 0 && checked.length < itemCheckboxes.length;
        }

        if (checkAll) {
            checkAll.addEventListener('change', function () {
                itemCheckboxes.forEach(cb => cb.checked = this.checked);
                updateBulkUI();
            });
        }

        itemCheckboxes.forEach(cb => cb.addEventListener('change', updateBulkUI));

        function bulkDeleteKendaraan() {
            const checked = document.querySelectorAll('.item-checkbox:checked');
            if (checked.length === 0) return;

            Swal.fire({
                title: 'Hapus ' + checked.length + ' Kendaraan?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const inputsDiv = document.getElementById('bulkDeleteInputs');
                    inputsDiv.innerHTML = '';
                    checked.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = cb.value;
                        inputsDiv.appendChild(input);
                    });
                    document.getElementById('bulkDeleteForm').submit();
                }
            });
        }
    </script>

</x-app-layout>