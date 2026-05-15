<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Personel') }}
        </h2>
    </x-slot>

    @push('head')
        <script>
            // Fungsi ini harus ada SEBELUM Alpine.js dimuat (defer)
            // sehingga saat Alpine init dan menemukan x-data="personelImportModal()",
            // fungsi ini sudah tersedia di scope global.
            function personelImportModal() {
                return {
                    show: false,
                    step: 1,
                    uploading: false,
                    uploadProgress: 0,
                    importing: false,
                    importProgress: 0,
                    selectedFile: null,
                    duplicateAction: 'skip',
                    previewData: {
                        new_count: 0,
                        duplicate_count: 0,
                        error_count: 0,
                        new_entries: [],
                        duplicates: [],
                        errors: []
                    },

                    openModal() {
                        this.show = true;
                        this.step = 1;
                        this.resetData();
                    },

                    closeModal() {
                        if (this.importing) return;
                        this.show = false;
                        setTimeout(() => this.resetData(), 300);
                    },

                    resetData() {
                        this.step = 1;
                        this.uploading = false;
                        this.importing = false;
                        this.selectedFile = null;
                        this.previewData = {
                            new_count: 0,
                            duplicate_count: 0,
                            error_count: 0,
                            new_entries: [],
                            duplicates: [],
                            errors: []
                        };
                    },

                    handleFileUpload(e) {
                        const file = e.target.files[0];
                        if (!file) return;

                        this.selectedFile = file;
                        this.uploading = true;
                        this.uploadProgress = 0;

                        const formData = new FormData();
                        formData.append('file', file);
                        formData.append('_token', '{{ csrf_token() }}');

                        const interval = setInterval(() => {
                            if (this.uploadProgress < 90) this.uploadProgress += 10;
                        }, 100);

                        fetch('{{ route('satker.personels.preview-import') }}', {
                            method: 'POST',
                            body: formData,
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        })
                            .then(res => res.json())
                            .then(data => {
                                clearInterval(interval);
                                this.uploadProgress = 100;
                                if (data.success) {
                                    setTimeout(() => {
                                        this.previewData = data;
                                        this.step = 2;
                                        this.uploading = false;
                                    }, 500);
                                } else {
                                    throw new Error(data.message || 'Gagal memproses file');
                                }
                            })
                                clearInterval(interval);
                                this.uploading = false;
                                window.showAlert('Gagal Unggah', err.message, 'error');
                            });
                    },

                    backToStep1() {
                        this.step = 1;
                        this.selectedFile = null;
                    },

                    confirmImport() {
                        this.importing = true;
                        this.importProgress = 0;

                        const formData = new FormData();
                        formData.append('file', this.selectedFile);
                        formData.append('duplicate_action', this.duplicateAction);
                        formData.append('_token', '{{ csrf_token() }}');

                        const interval = setInterval(() => {
                            if (this.importProgress < 95) this.importProgress += 5;
                        }, 200);

                        fetch('{{ route('satker.personels.import') }}', {
                            method: 'POST',
                            body: formData,
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        })
                            .then(res => {
                                if (res.redirected) {
                                    clearInterval(interval);
                                    this.importProgress = 100;
                                    window.location.href = res.url;
                                } else {
                                    return res.json().then(data => {
                                        throw new Error(data.message || 'Gagal import')
                                    });
                                }
                            })
                                clearInterval(interval);
                                this.importing = false;
                                window.showAlert('Gagal Import', err.message, 'error');
                            });
                    }
                };
            }
        </script>
    @endpush

    <div class="py-12">
        <div class="max-w-[95rem] mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">



                    <!-- Action Bar -->
                    <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
                        <div class="flex items-center justify-center md:justify-start gap-2">

                            <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-import-modal'))"
                                class="inline-flex items-center justify-center w-10 h-10 bg-emerald-600 text-white rounded-xl shadow-lg shadow-emerald-500/30 hover:bg-emerald-700 transition-all duration-200 hover:-translate-y-0.5 group relative"
                                title="Import Excel">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                <span
                                    class="absolute -bottom-10 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-[10px] rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">Import
                                    Excel</span>
                            </button>

                            <a href="{{ route('satker.personels.export') }}"
                                class="inline-flex items-center justify-center w-10 h-10 bg-indigo-600 text-white rounded-xl shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 transition-all duration-200 hover:-translate-y-0.5 group relative"
                                title="Export Excel">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <span
                                    class="absolute -bottom-10 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-[10px] rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">Export
                                    Excel</span>
                            </a>

                            <a href="{{ route('satker.personels.create') }}"
                                class="inline-flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-all duration-200 hover:-translate-y-0.5 group relative"
                                title="Tambah Personel">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span
                                    class="absolute -bottom-10 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-[10px] rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">Tambah
                                    Personel</span>
                            </a>
                        </div>

                        <!-- Search Form -->
                        <form action="{{ route('satker.personels.index') }}" method="GET"
                            class="flex items-center gap-2 w-full md:w-auto">
                            <div class="relative flex-1 md:w-64">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Cari Nama / NRP..."
                                    class="pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full transition-shadow bg-slate-50">
                            </div>
                            <button type="submit"
                                class="inline-flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-all duration-200 hover:-translate-y-0.5 group relative">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                                <span
                                    class="absolute -bottom-10 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-[10px] rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">Cari
                                    Data</span>
                            </button>
                        </form>
                    </div>

                    @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" x-transition
                            class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-start gap-3 shadow-sm relative">
                            <div class="flex-shrink-0 text-emerald-500 mt-0.5">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-emerald-900 text-sm">Berhasil</h3>
                                <p class="text-emerald-700 text-sm mt-1 leading-relaxed">{{ session('success') }}</p>
                            </div>
                            <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endif



                    @if(session('error'))
                        <div x-data="{ show: true }" x-show="show" x-transition
                            class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-100 flex items-start gap-3 shadow-sm relative">
                            <div class="flex-shrink-0 text-rose-500 mt-0.5">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-rose-900 text-sm">Gagal</h3>
                                <p class="text-rose-700 text-sm mt-1 leading-relaxed">{{ session('error') }}</p>
                            </div>
                            <button @click="show = false" class="text-rose-400 hover:text-rose-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endif

                    <div class="overflow-x-auto">

                        <!-- Bulk Actions Bar -->
                        <div id="bulkActionsBar"
                            class="hidden px-4 sm:px-6 py-3 bg-indigo-50 border-b border-indigo-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-semibold text-indigo-800"><span id="selectedCount">0</span>
                                    data dipilih</span>
                            </div>
                            <button type="button" onclick="bulkDeletePersonel()"
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
                        <form id="bulkDeleteForm" action="{{ route('satker.personels.bulk-delete') }}" method="POST"
                            class="hidden">
                            @csrf
                            <div id="bulkDeleteInputs"></div>
                        </form>

                        <table class="min-w-full divide-y divide-slate-200">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th colspan="6" class="px-6 py-3">
                                        <div class="flex items-center justify-between">
                                            <form action="{{ route('satker.personels.index') }}" method="GET"
                                                class="flex items-center">
                                                <x-per-page :current="request('per_page', 10)" />
                                            </form>
                                            <div
                                                class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">
                                                Menampilkan
                                                {{ $personels->firstItem() ?? 0 }}-{{ $personels->lastItem() ?? 0 }}
                                                dari {{ $personels->total() }} data
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                                <tr class="bg-slate-50/50">
                                    <th class="px-4 py-4 text-center w-10">
                                        <input type="checkbox" id="checkAll"
                                            class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 cursor-pointer">
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-[10px] font-bold text-slate-500 uppercase tracking-widest w-12">
                                        No</th>
                                    <th
                                        class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                        Identitas Personel</th>
                                    <th
                                        class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                        Jenis BBM</th>
                                    <th
                                        class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                        Saldo</th>
                                    <th
                                        class="px-6 py-4 text-right text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100">
                                @foreach($personels as $personel)
                                    <tr class="hover:bg-slate-50/80 transition-colors group">
                                        <td class="px-4 py-4 text-center">
                                            <input type="checkbox" name="item_ids[]" value="{{ $personel->id }}"
                                                class="item-checkbox w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 cursor-pointer">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span
                                                class="text-xs font-bold text-slate-400">{{ $loop->iteration + ($personels->currentPage() - 1) * $personels->perPage() }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-sm ring-2 ring-white">
                                                    {{ strtoupper(substr($personel->nama, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">
                                                        {{ $personel->nama }}
                                                    </p>
                                                    <p class="text-[11px] font-medium text-slate-500 mt-0.5">NRP:
                                                        {{ $personel->nrp }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $bbmColors = [
                                                    'Pertalite' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                    'Pertamax' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                    'Solar' => 'bg-orange-50 text-orange-600 border-orange-100',
                                                    'Pertamina Dex' => 'bg-rose-50 text-rose-600 border-rose-100',
                                                ];
                                                $colorClass = $bbmColors[$personel->jenis_bbm] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                                            @endphp
                                            <span
                                                class="inline-flex items-center w-fit px-2.5 py-1 rounded-md text-[10px] font-bold border {{ $colorClass }}">
                                                {{ strtoupper($personel->jenis_bbm ?? '-') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-baseline gap-1">
                                                <span
                                                    class="text-sm font-black text-slate-900">{{ number_format($personel->saldo, 0, ',', '.') }}</span>
                                                <span class="text-[10px] font-bold text-slate-400 uppercase">Liter</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('satker.personels.print', $personel) }}"

                                                    class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all"
                                                    title="Cetak Kartu">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                @if(auth()->user()->role !== 'super_admin' && $personel->saldo > 0)
                                                    <span class="p-2 text-slate-200 cursor-not-allowed group/edit"
                                                        title="Saldo masih {{ number_format($personel->saldo, 0, ',', '.') }} L">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                            </path>
                                                        </svg>
                                                    </span>
                                                @else
                                                    <a href="{{ route('satker.personels.edit', $personel) }}"
                                                        class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                                        title="Edit">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                @endif
                                                @if(auth()->user()->role !== 'super_admin' && $personel->saldo > 0)
                                                    <span class="p-2 text-slate-200 cursor-not-allowed"
                                                        title="Saldo masih {{ number_format($personel->saldo, 0, ',', '.') }} L">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </span>
                                                @else
                                                    <form action="{{ route('satker.personels.destroy', $personel) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" data-confirm="Yakin ingin menghapus personel ini?"
                                                            data-confirm-type="error"
                                                            class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all"
                                                            title="Hapus Personel">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Reset buttons removed for Satker Admin as per request --}}
                                             </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $personels->links() }}
                    </div>


                </div>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div x-data="personelImportModal()" @open-import-modal.window="openModal()" x-show="show" x-cloak
        class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition.opacity class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle w-full"
                :class="step === 2 ? 'max-w-5xl' : 'max-w-xl'">

                <!-- Modal Content -->
                <div class="relative bg-white font-sans">
                    <!-- Header -->
                    <div
                        class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between bg-white relative overflow-hidden">
                        <div
                            class="absolute top-0 left-0 w-20 h-20 bg-indigo-50/50 rounded-full -translate-x-10 -translate-y-10 blur-2xl">
                        </div>
                        <div class="relative flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 to-violet-700 flex items-center justify-center text-white shadow-lg shadow-indigo-100 shrink-0 transform hover:rotate-6 transition-transform duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354l1.1 3.383h3.558l-2.877 2.09 1.1 3.383-2.877-2.09-2.877 2.09 1.1-3.383-2.877-2.09h3.558l1.1-3.383z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m16-10a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900 tracking-tight">Import Personel</h3>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="w-1 h-1 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Sistem
                                        Unggah
                                        Pintar</p>
                                </div>
                            </div>
                        </div>
                        <button @click="closeModal()"
                            class="relative w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition-all duration-300 group">
                            <svg class="w-5 h-5 transform group-hover:rotate-90 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Stepper -->
                    <div class="px-6 py-4 bg-slate-50/30 border-b border-slate-50 relative">
                        <div class="flex items-center justify-between max-w-md mx-auto">
                            <template x-for="(s, i) in ['Upload', 'Preview', 'Selesai']">
                                <div class="flex items-center flex-1 last:flex-none">
                                    <div class="flex flex-col items-center gap-1.5 group cursor-default">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-[10px] font-black transition-all duration-500 relative"
                                            :class="step > i+1 ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-100' : (step === i+1 ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-100 ring-4 ring-indigo-50 scale-110' : 'bg-white border border-slate-200 text-slate-400')">
                                            <template x-if="step > i+1">
                                                <svg class="w-4 h-4 animate-bounce-subtle" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </template>
                                            <template x-if="step <= i+1">
                                                <span x-text="i+1" class="relative z-10"></span>
                                            </template>
                                            <template x-if="step === i+1">
                                                <div
                                                    class="absolute inset-0 rounded-xl bg-indigo-600 animate-ping opacity-20">
                                                </div>
                                            </template>
                                        </div>
                                        <span
                                            class="text-[9px] font-black uppercase tracking-[0.15em] transition-colors duration-300"
                                            :class="step >= i+1 ? 'text-slate-900' : 'text-slate-400'"
                                            x-text="s"></span>
                                    </div>
                                    <template x-if="i < 2">
                                        <div
                                            class="flex-1 h-0.5 mx-3 rounded-full transition-colors duration-500 overflow-hidden bg-slate-100">
                                            <div class="h-full bg-emerald-500 transition-all duration-700"
                                                :style="`width: ${step > i+1 ? '100' : '0'}%`"></div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Step 1: Upload -->
                    <div x-show="step === 1" x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 translate-y-8"
                        x-transition:enter-end="opacity-100 translate-y-0" class="p-6">
                        <div class="mb-6 group/dropzone">
                            <label
                                class="relative flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-slate-200 rounded-[2rem] bg-slate-50/50 hover:bg-white hover:border-indigo-400 hover:shadow-xl hover:shadow-indigo-100/50 transition-all duration-700 cursor-pointer overflow-hidden group">
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-purple-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                                </div>
                                <div
                                    class="relative flex flex-col items-center justify-center py-6 transition-all duration-700 group-hover:scale-105">
                                    <div
                                        class="w-14 h-14 mb-4 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 shadow-xl shadow-indigo-100 flex items-center justify-center text-white ring-4 ring-indigo-50 group-hover:rotate-[8deg] transition-all duration-500">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                            </path>
                                        </svg>
                                    </div>
                                    <h4 class="text-base font-black text-slate-900 mb-1">Lepaskan berkas di sini</h4>
                                    <p
                                        class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] group-hover:text-indigo-500 transition-colors">
                                        Atau klik untuk memilih file Excel/CSV</p>
                                </div>
                                <input type="file" class="hidden" @change="handleFileUpload" accept=".xlsx,.xls,.csv" />

                                <div x-show="uploading"
                                    class="absolute inset-0 bg-white/98 backdrop-blur-xl flex flex-col items-center justify-center p-6 z-20">
                                    <div
                                        class="w-48 h-1.5 bg-slate-100 rounded-full overflow-hidden mb-4 ring-2 ring-indigo-50">
                                        <div class="h-full bg-gradient-to-r from-indigo-600 to-violet-600 transition-all duration-500"
                                            :style="`width: ${uploadProgress}%`"></div>
                                    </div>
                                    <p
                                        class="text-[9px] font-black text-indigo-600 tracking-[0.3em] animate-bounce-subtle uppercase">
                                        MEMPROSES DATA...</p>
                                </div>
                            </label>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <div
                                class="flex-1 p-5 rounded-[1.5rem] bg-gradient-to-br from-white to-slate-50 border border-slate-100 shadow-sm transition-all hover:shadow-md">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <h4
                                            class="text-[11px] font-black text-slate-800 uppercase tracking-widest mb-1">
                                            Butuh
                                            Bantuan?</h4>
                                        <p class="text-[10px] font-medium text-slate-500 leading-relaxed mb-3">Gunakan
                                            template
                                            resmi untuk menghindari kesalahan format.</p>
                                        <a href="{{ route('satker.personels.download-template') }}"
                                            class="inline-flex items-center text-[10px] font-bold text-amber-600 hover:text-amber-700 transition-colors uppercase tracking-widest gap-1.5 border-b border-amber-200">
                                            Unduh Template
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flex-1 p-5 rounded-[1.5rem] bg-gradient-to-br from-white to-slate-50 border border-slate-100 shadow-sm transition-all hover:shadow-md">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4
                                            class="text-[11px] font-black text-slate-800 uppercase tracking-widest mb-2">
                                            Kolom
                                            Wajib</h4>
                                        <div class="flex flex-wrap gap-x-3 gap-y-1">
                                            <template x-for="col in ['NAMA', 'NRP']">
                                                <div class="flex items-center gap-1">
                                                    <div class="w-1 h-1 rounded-full bg-indigo-500"></div>
                                                    <span class="text-[9px] font-black text-slate-500"
                                                        x-text="col"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Preview -->
                    <div x-show="step === 2" x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        class="p-0 overflow-hidden">
                        <div class="p-6 pb-3 grid grid-cols-3 gap-4 bg-white shrink-0">
                            <div
                                class="group p-4 rounded-2xl bg-gradient-to-br from-indigo-600 to-indigo-800 text-white shadow-lg shadow-indigo-100 relative overflow-hidden transform hover:-translate-y-1 transition-all duration-300">
                                <div
                                    class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity">
                                </div>
                                <div class="relative z-10">
                                    <p
                                        class="text-[9px] font-black text-indigo-100 uppercase tracking-[0.2em] mb-1 opacity-80">
                                        TOTAL DATA</p>
                                    <h4 class="text-2xl font-black tracking-tight"
                                        x-text="previewData.new_count + previewData.duplicate_count"></h4>
                                </div>
                                <svg class="absolute -right-2 -bottom-2 w-16 h-16 text-white/5 rotate-12 transition-transform group-hover:scale-125 duration-700"
                                    fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                                </svg>
                            </div>
                            <div
                                class="group p-4 rounded-2xl bg-amber-50 border border-amber-100 shadow-sm relative overflow-hidden transform hover:-translate-y-1 transition-all duration-300">
                                <div class="relative z-10">
                                    <p class="text-[9px] font-black text-amber-600 uppercase tracking-[0.2em] mb-1">
                                        DUPLIKAT</p>
                                    <h4 class="text-2xl font-black text-amber-900" x-text="previewData.duplicate_count">
                                    </h4>
                                </div>
                                <svg class="absolute -right-2 -bottom-2 w-14 h-14 text-amber-200/50 rotate-12 transition-transform group-hover:scale-125 duration-700"
                                    fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                            <div
                                class="group p-4 rounded-2xl bg-rose-50 border border-rose-100 shadow-sm relative overflow-hidden transform hover:-translate-y-1 transition-all duration-300">
                                <div class="relative z-10">
                                    <p class="text-[9px] font-black text-rose-600 uppercase tracking-[0.2em] mb-1">ERROR
                                    </p>
                                    <h4 class="text-2xl font-black text-rose-900" x-text="previewData.error_count"></h4>
                                </div>
                                <svg class="absolute -right-2 -bottom-2 w-14 h-14 text-rose-200/50 rotate-12 transition-transform group-hover:scale-125 duration-700"
                                    fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Progress Bar for Final Confirmation -->
                        <div x-show="importing" class="px-8 mb-4">
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 transition-all duration-300"
                                    :style="`width: ${importProgress}%`"></div>
                            </div>
                            <p
                                class="text-[10px] font-black text-emerald-600 mt-2 uppercase tracking-widest animate-pulse">
                                Memproses Database...</p>
                        </div>

                        <div
                            class="px-7 py-3 bg-slate-50/50 border-y border-slate-100 flex items-center justify-between">
                            <h4 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em]">Analisis Data
                            </h4>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                    <span class="text-[9px] font-black text-slate-500 uppercase">Baru</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                                    <span class="text-[9px] font-black text-slate-500 uppercase">Update</span>
                                </div>
                            </div>
                        </div>

                        <div class="max-h-[320px] overflow-auto border-b border-slate-100 scrollbar-thin">
                            <table class="w-full border-separate border-spacing-0">
                                <thead class="bg-white/95 backdrop-blur-sm sticky top-0 z-20 shadow-sm text-center">
                                    <tr>
                                        <th
                                            class="pl-7 pr-3 py-3 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">
                                            Row</th>
                                        <th
                                            class="px-4 py-3 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">
                                            Nama Personel</th>
                                        <th
                                            class="pr-7 pl-3 py-3 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">
                                            JENIS BBM</th>
                                        <th
                                            class="pr-7 pl-3 py-3 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">
                                            NRP/NIP</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-50">
                                    <!-- Duplicates -->
                                    <template x-for="item in previewData.duplicates">
                                        <tr class="bg-amber-50/30">
                                            <td class="px-4 py-3 text-[10px] font-black text-amber-600"
                                                x-text="item.row"></td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="flex flex-col items-center">
                                                    <span class="text-[10px] font-bold text-slate-700"
                                                        x-text="item.nama"></span>
                                                    <template x-if="item.changes.find(c => c.field === 'Nama')">
                                                        <span class="text-[8px] font-bold text-amber-600 italic mt-0.5"
                                                            x-text="'Lama: ' + item.changes.find(c => c.field === 'Nama').old"></span>
                                                    </template>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                <span
                                                    class="px-2 py-0.5 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 text-[9px] font-black"
                                                    x-text="item.jenis_bbm"></span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                <span
                                                    class="px-2 py-0.5 rounded-lg bg-white border border-amber-200 text-amber-700 text-[9px] font-black"
                                                    x-text="item.nrp"></span>
                                            </td>
                                        </tr>
                                    </template>

                                    <!-- New Entries -->
                                    <template x-for="item in previewData.new_entries">
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="px-4 py-3 text-[10px] font-black text-slate-400"
                                                x-text="item.row"></td>
                                            <td class="px-4 py-3 text-[10px] font-bold text-slate-900 text-center"
                                                x-text="item.nama"></td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                <span
                                                    class="px-2 py-0.5 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 text-[9px] font-black"
                                                    x-text="item.jenis_bbm"></span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                <span
                                                    class="px-2 py-0.5 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-600 text-[9px] font-black"
                                                    x-text="item.nrp"></span>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Warnings & Options -->
                        <template x-if="previewData.error_count > 0">
                            <div class="px-8 py-5 bg-rose-50 border-b border-rose-100">
                                <h5
                                    class="text-[10px] font-black text-rose-600 uppercase tracking-widest mb-3 flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Terdapat Error di Beberapa Baris
                                </h5>
                                <div class="grid grid-cols-2 gap-x-8 gap-y-1">
                                    <template x-for="error in previewData.errors">
                                        <p class="text-[10px] font-bold text-rose-800 leading-relaxed truncate"
                                            x-text="'• ' + error"></p>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <div class="px-6 py-5 bg-slate-50/30">
                            <div
                                class="p-4 rounded-2xl bg-white border border-slate-100 shadow-lg shadow-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p
                                            class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-0.5">
                                            Opsi
                                            Duplikat</p>
                                        <p class="text-[11px] font-bold text-slate-900">Perlakuan jika NRP terdaftar</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-1 bg-slate-50 rounded-xl border border-slate-100">
                                    <label class="relative flex items-center cursor-pointer group">
                                        <input type="radio" value="skip" x-model="duplicateAction" class="sr-only peer">
                                        <div
                                            class="px-5 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all peer-checked:bg-white peer-checked:text-indigo-600 peer-checked:shadow-sm text-slate-400">
                                            Lewati</div>
                                    </label>
                                    <label class="relative flex items-center cursor-pointer group">
                                        <input type="radio" value="update" x-model="duplicateAction"
                                            class="sr-only peer">
                                        <div
                                            class="px-5 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all peer-checked:bg-white peer-checked:text-indigo-600 peer-checked:shadow-sm text-slate-400">
                                            Update</div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div
                        class="px-4 sm:px-6 py-4 bg-white border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <template x-if="step === 1">
                            <div class="flex items-center gap-2 group">
                                <div
                                    class="w-6 h-6 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 transition-colors duration-500">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="text-[9px] font-black uppercase tracking-[0.15em] text-slate-400">Siapkan
                                    Berkas
                                    Anda</span>
                            </div>
                        </template>
                        <template x-if="step === 2">
                            <button @click="backToStep1()" :disabled="importing"
                                class="w-full sm:w-auto px-5 py-2.5 text-[10px] font-black text-slate-500 hover:text-slate-900 transition-all uppercase tracking-[0.2em] disabled:opacity-50 flex items-center justify-center sm:justify-start gap-2 group">
                                <svg class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-1" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali
                            </button>
                        </template>

                        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                            <button @click="closeModal()" :disabled="importing"
                                class="w-full sm:w-auto px-6 py-2.5 text-[10px] font-black text-slate-400 hover:text-slate-600 transition-all uppercase tracking-[0.2em] disabled:opacity-50">Batal</button>
                            <template x-if="step === 2">
                                <button @click="confirmImport()"
                                    :disabled="importing || (previewData.new_count === 0 && previewData.duplicate_count === 0)"
                                    class="relative w-full sm:w-auto px-8 py-2.5 bg-indigo-600 text-white rounded-xl text-[10px] font-black shadow-lg shadow-indigo-100 hover:bg-indigo-700 hover:scale-105 transition-all uppercase tracking-[0.2em] disabled:opacity-50 disabled:scale-100 group overflow-hidden">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                    </div>
                                    <span class="relative flex items-center justify-center gap-2">
                                        <svg class="w-3.5 h-3.5 animate-pulse" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Import Sekarang
                                    </span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

@push('scripts')
    <script>
        // Bulk Delete Checkboxes
        const checkAll = document.getElementById('checkAll');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const bulkActionsBar = document.getElementById('bulkActionsBar');
        const selectedCountEl = document.getElementById('selectedCount');

        function updateBulkUI() {
            if (!selectedCountEl) return;
            const checked = document.querySelectorAll('.item-checkbox:checked');
            selectedCountEl.textContent = checked.length;
            if (bulkActionsBar) bulkActionsBar.classList.toggle('hidden', checked.length === 0);
            if (checkAll) {
                checkAll.checked = checked.length === itemCheckboxes.length && itemCheckboxes.length > 0;
                checkAll.indeterminate = checked.length > 0 && checked.length < itemCheckboxes.length;
            }
        }

        if (checkAll && itemCheckboxes.length > 0) {
            checkAll.addEventListener('change', function () {
                itemCheckboxes.forEach(cb => cb.checked = this.checked);
                updateBulkUI();
            });
        }

        itemCheckboxes.forEach(cb => cb.addEventListener('change', updateBulkUI));

        function bulkDeletePersonel() {
            const checked = document.querySelectorAll('.item-checkbox:checked');
            if (checked.length === 0) return;
            Swal.fire({
                title: 'Hapus Massal?',
                text: 'Hapus ' + checked.length + ' data terpilih?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                confirmButtonText: 'Ya, Hapus!'
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
@endpush