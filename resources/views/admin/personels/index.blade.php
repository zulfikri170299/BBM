<x-app-layout>
    <div class="p-1 sm:p-6 lg:p-8 space-y-4 sm:space-y-6">
        <div class="max-w-[95rem] mx-auto">
            <div class="bg-slate-900 border border-white/5 overflow-hidden sm:shadow-sm sm:rounded-xl">
                <div class="p-2 sm:p-6 text-white">


                    <!-- Header & Filter -->
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div>
                                <h2 class="text-2xl font-bold text-slate-200 leading-tight">Data Personel</h2>
                                <p class="text-slate-400 text-sm mt-1">Kelola data personel dan saldo BBM.</p>
                            </div>
                        </div>

                        <form action="{{ route('admin.personels.index') }}" method="GET"
                            class="w-full flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            
                            <!-- Search & Action Controls Container -->
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full shrink-0">
                                
                                <!-- Filter Satker TomSelect -->
                                <div class="w-full sm:w-64">
                                    <select name="satker_id" id="filter_satker_id"
                                        onchange="this.form.submit()"
                                        class="tom-select w-full">
                                        <option value="">Semua Satker</option>
                                        @foreach($satkers as $satker)
                                            <option value="{{ $satker->id }}" {{ request('satker_id') == $satker->id ? 'selected' : '' }}>
                                                {{ $satker->nama_satker }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Input Pencarian & Tombol Cari -->
                                <div class="flex items-center gap-2 w-full sm:flex-1">
                                    <div class="relative flex-1">
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-slate-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                            </svg>
                                        </div>
                                        <input type="text" name="search" value="{{ request('search') }}"
                                            class="block w-full p-2.5 pl-10 text-sm text-white border-2 border-white/10 rounded-xl bg-slate-900 focus:ring-indigo-500 focus:border-indigo-500 transition-colors placeholder-slate-500"
                                            placeholder="Cari Nama atau NRP...">
                                    </div>
                                    <button type="submit"
                                        class="inline-flex items-center justify-center shrink-0 w-[42px] h-[42px] bg-indigo-600 border border-indigo-500 text-white rounded-xl shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 transition-all duration-200"
                                        title="Cari Data">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Row Action Buttons -->
                                <div class="flex items-center justify-between sm:justify-end gap-2 shrink-0 pt-1 sm:pt-0">
                                    <div class="flex items-center gap-2">
                                        @if(request('search') || request('satker_id'))
                                            <a href="{{ route('admin.personels.index') }}"
                                                class="inline-flex items-center justify-center shrink-0 w-10 h-10 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-xl hover:bg-rose-500/20 hover:text-rose-300 transition-all duration-200"
                                                title="Reset Filter">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                            </a>
                                        @endif
                                        
                                        <!-- Actions grouped immediately to the right -->
                                        @if(auth()->user()->role !== 'kasubbag')
                                            <button type="button" @click="$dispatch('open-import-modal')"
                                                class="inline-flex items-center justify-center shrink-0 w-10 h-10 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl hover:bg-emerald-500/20 hover:text-emerald-300 transition-all duration-200"
                                                title="Import Excel">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                                </svg>
                                            </button>
                                        @endif
                                        
                                        <a href="{{ route('admin.personels.export', request()->all()) }}" target="_blank"
                                            class="inline-flex items-center justify-center shrink-0 w-10 h-10 bg-amber-500/10 border border-amber-500/20 text-amber-500 rounded-xl hover:bg-amber-500/20 hover:text-amber-400 transition-all duration-200"
                                            title="Export Excel">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                        </a>
                                        
                                        @if(auth()->user()->role !== 'kasubbag')
                                            <a href="{{ route('admin.personels.create') }}"
                                                class="inline-flex items-center justify-center shrink-0 w-10 h-10 bg-indigo-500/20 border border-indigo-500/30 text-indigo-400 rounded-xl hover:bg-indigo-500/30 hover:text-indigo-300 transition-all duration-200"
                                                title="Tambah Personel">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif



                    <!-- Bulk Actions -->
                    @if(auth()->user()->role !== 'kasubbag')
                        <div id="bulkActions"
                            class="hidden items-center gap-3 mb-4 p-3 bg-indigo-50 border border-indigo-100 rounded-xl">
                            <span
                                class="text-xs font-bold text-indigo-600 bg-slate-900 border border-white/5 px-3 py-1.5 rounded-lg border border-indigo-100">
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

                        <form id="bulkDeleteForm" action="{{ route('admin.personels.bulk-delete') }}" method="POST"
                            class="hidden">
                            @csrf
                            <div id="bulkIdsContainer"></div>
                        </form>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/10">
                            <thead>
                                <tr class="bg-slate-800/50 border-b border-white/5">
                                    <th colspan="5" class="px-4 py-3">
                                        <div class="flex items-center justify-between">
                                            <form action="{{ route('admin.personels.index') }}" method="GET"
                                                class="flex items-center">
                                                @if(request('satker_id'))
                                                    <input type="hidden" name="satker_id"
                                                        value="{{ request('satker_id') }}">
                                                @endif
                                                <x-per-page :current="request('per_page', 15)" />
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
                                <tr class="bg-slate-800/50">
                                    @if(auth()->user()->role !== 'kasubbag')
                                        <th class="w-10 px-6 py-4">
                                            <input type="checkbox" id="checkAll"
                                                class="rounded border-white/20 text-indigo-600 focus:ring-indigo-500 shadow-sm cursor-pointer">
                                        </th>
                                    @endif
                                    <th
                                        class="px-4 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        Data Personel</th>
                                    <th
                                        class="px-4 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest hidden sm:table-cell">
                                        Satker & BBM</th>
                                    <th
                                        class="px-4 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest hidden lg:table-cell">
                                        Saldo</th>
                                    @if(auth()->user()->role === 'super_admin')
                                    <th
                                        class="px-4 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest hidden lg:table-cell">
                                        PIN</th>
                                    @endif
                                    <th
                                        class="px-4 py-3 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-slate-900 border border-white/5 divide-y divide-white/5">
                                @foreach($personels as $personel)
                                    <tr class="hover:bg-slate-800/50/80 transition-colors group">
                                        @if(auth()->user()->role !== 'kasubbag')
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <input type="checkbox" value="{{ $personel->id }}"
                                                    class="item-checkbox rounded border-white/20 text-indigo-600 focus:ring-indigo-500 shadow-sm cursor-pointer">
                                            </td>
                                        @endif
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-sm ring-2 ring-white">
                                                    {{ strtoupper(substr($personel->nama, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-sm font-bold text-white group-hover:text-indigo-600 transition-colors">
                                                        {{ $personel->nama }}
                                                    </p>
                                                    <p class="text-[11px] font-medium text-slate-400 mt-0.5">NRP:
                                                        {{ $personel->nrp }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap hidden sm:table-cell">
                                            <p class="text-xs font-bold text-slate-300 capitalize mb-1.5">
                                                {{ strtolower($personel->satker->nama_satker ?? '-') }}
                                            </p>
                                            @php
                                                $bbmColors = [
                                                    'Pertalite' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                    'Pertamax' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                    'Solar' => 'bg-orange-50 text-orange-600 border-orange-100',
                                                    'Pertamina Dex' => 'bg-rose-50 text-rose-600 border-rose-100',
                                                ];
                                                $colorClass = $bbmColors[$personel->jenis_bbm] ?? 'bg-slate-800/50 text-slate-400 border-white/5';
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold border whitespace-nowrap {{ $colorClass }}">
                                                {{ strtoupper($personel->jenis_bbm ?? '-') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap hidden lg:table-cell">
                                            <div class="flex items-baseline gap-1">
                                                <span
                                                    class="text-sm font-black text-white">{{ rtrim(rtrim(number_format($personel->saldo, 2, ',', '.'), '0'), ',') }}</span>
                                                <span class="text-[10px] font-bold text-slate-400 uppercase">Liter</span>
                                            </div>
                                        </td>
                                        @if(auth()->user()->role === 'super_admin')
                                        <td class="px-4 py-3 whitespace-nowrap hidden lg:table-cell">
                                            <span class="text-sm font-bold text-slate-300 tracking-wider bg-slate-800 rounded px-2 py-1 select-all">{{ $personel->pin }}</span>
                                        </td>
                                        @endif
                                         <td class="px-4 py-3 whitespace-nowrap text-right">
                                            <div class="hidden lg:flex justify-end items-center gap-2">
                                                @if(auth()->user()->role !== 'kasubbag')
                                                    @if(auth()->user()->role !== 'super_admin' && $personel->saldo > 0)
                                                        <span
                                                            class="inline-flex items-center p-2 bg-slate-800/50 text-slate-200 rounded-lg cursor-not-allowed group/edit"
                                                            title="Saldo masih {{ rtrim(rtrim(number_format($personel->saldo, 2, ',', '.'), '0'), ',') }} L">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                                </path>
                                                            </svg>
                                                        </span>
                                                    @else
                                                        <a href="{{ route('admin.personels.edit', $personel) }}"
                                                            class="inline-flex items-center p-2 bg-slate-800 hover:bg-indigo-100 text-slate-400 hover:text-indigo-600 rounded-lg transition-colors"
                                                            title="Edit Personel">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                                </path>
                                                            </svg>
                                                        </a>
                                                    @endif
                                                @endif

                                                <a href="{{ route('admin.personels.print', $personel) }}"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-lg text-xs font-bold transition-all duration-200">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                        </path>
                                                    </svg>
                                                    Print
                                                </a>

                                                @if(auth()->user()->role !== 'kasubbag')
                                                    <form action="{{ route('admin.personels.reset-password', $personel) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            data-confirm="Reset password akun login {{ $personel->nama }} menjadi NRP ({{ $personel->nrp }})?"
                                                            data-confirm-title="Reset Password Akun"
                                                            data-confirm-text="Ya, Reset Password"
                                                            data-confirm-type="warning"
                                                            class="inline-flex items-center p-2 bg-slate-800 hover:bg-amber-100 text-slate-400 hover:text-amber-600 rounded-lg transition-colors"
                                                            title="Reset Password Akun">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('admin.personels.reset-pin', $personel) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            data-confirm="Reset PIN personel {{ $personel->nama }}? PIN baru akan di-generate secara acak."
                                                            data-confirm-title="Reset PIN"
                                                            data-confirm-text="Ya, Reset PIN"
                                                            data-confirm-type="warning"
                                                            class="inline-flex items-center p-2 bg-slate-800 hover:bg-amber-100 text-slate-400 hover:text-amber-600 rounded-lg transition-colors"
                                                            title="Reset PIN (Kartu)">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </form>

                                                    @if(auth()->user()->role !== 'super_admin' && $personel->saldo > 0)
                                                        <span class="p-2 text-slate-200 cursor-not-allowed"
                                                            title="Saldo masih {{ rtrim(rtrim(number_format($personel->saldo, 2, ',', '.'), '0'), ',') }} L">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                        </span>
                                                    @else
                                                        <form action="{{ route('admin.personels.destroy', $personel) }}"
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
                                                @endif
                                            </div>

                                            <!-- Mobile Actions Modal Trigger -->
                                            <div x-data="{ showDetail: false }" class="lg:hidden flex justify-end">
                                                <button type="button" @click="showDetail = true" class="inline-flex items-center p-2 bg-indigo-600/10 text-indigo-400 rounded-lg border border-indigo-600/20 shadow-sm" title="Lihat Detail">
                                                    <svg class="w-5 h-5 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </button>
                                                
                                                <template x-teleport="body">
                                                    <div x-show="showDetail" class="fixed inset-0 z-[9999] flex items-end sm:items-center justify-center p-4" style="display: none;">
                                                        <div x-show="showDetail" x-transition.opacity @click="showDetail = false" class="fixed inset-0 bg-slate-950/80"></div>
                                                        <div x-show="showDetail" x-transition.translate.y @click.outside="showDetail = false" class="relative w-full max-w-sm bg-slate-900 border border-white/10 rounded-2xl shadow-2xl flex flex-col max-h-[85vh] overflow-hidden">
                                                            <div class="p-4 border-b border-white/10 flex justify-between items-center bg-slate-800/50">
                                                                <div class="text-left">
                                                                    <h3 class="text-white font-bold text-lg leading-tight">{{ $personel->nama }}</h3>
                                                                    <p class="text-slate-400 text-xs">{{ $personel->nrp }}</p>
                                                                </div>
                                                                <button type="button" @click="showDetail = false" class="p-2 bg-slate-800 text-slate-400 hover:text-white rounded-xl border border-white/5 shadow-sm">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                                </button>
                                                            </div>
                                                            <div class="p-4 overflow-y-auto w-full custom-scrollbar text-left text-sm space-y-4">
                                                                <div class="grid grid-cols-2 gap-4">
                                                                    <div>
                                                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">Satker</span>
                                                                        <span class="font-bold text-white">{{ $personel->satker->nama_satker ?? '-' }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">Jenis BBM</span>
                                                                        <span class="font-bold text-white">{{ strtoupper($personel->jenis_bbm ?? '-') }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">Saldo</span>
                                                                        <span class="font-bold text-emerald-400">{{ rtrim(rtrim(number_format($personel->saldo, 2, ',', '.'), '0'), ',') }} L</span>
                                                                    </div>
                                                                    @if(auth()->user()->role === 'super_admin')
                                                                    <div>
                                                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">PIN</span>
                                                                        <code class="bg-amber-400/20 text-amber-500 px-2 py-0.5 rounded font-bold">{{ $personel->pin }}</code>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                                
                                                                <div class="pt-4 border-t border-white/10 flex flex-col gap-2">
                                                                    <a href="{{ route('admin.personels.print', $personel) }}" class="flex items-center gap-3 w-full p-3 bg-slate-800 rounded-xl text-slate-300 hover:text-white hover:bg-slate-700 transition">
                                                                        <div class="p-1.5 bg-blue-500/20 text-blue-400 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg></div>
                                                                        <span class="font-semibold text-xs">Print Kartu</span>
                                                                    </a>
                                                                    
                                                                    @if(auth()->user()->role !== 'kasubbag')
                                                                        @if(auth()->user()->role !== 'super_admin' && $personel->saldo > 0)
                                                                            <span class="flex items-center gap-3 w-full p-3 bg-slate-800/50 rounded-xl text-slate-500 cursor-not-allowed">
                                                                                <div class="p-1.5 bg-slate-800 rounded-lg text-slate-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></div>
                                                                                <span class="font-semibold text-xs flex-1 text-left">Edit Personel</span>
                                                                                <span class="text-[9px] bg-slate-800 px-2 py-0.5 rounded text-amber-500/70 border border-white/5">Saldo Tersisa</span>
                                                                            </span>
                                                                        @else
                                                                            <a href="{{ route('admin.personels.edit', $personel) }}" class="flex items-center gap-3 w-full p-3 bg-slate-800 rounded-xl text-slate-300 hover:text-white hover:bg-slate-700 transition">
                                                                                <div class="p-1.5 bg-indigo-500/20 text-indigo-400 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></div>
                                                                                <span class="font-semibold text-xs">Edit Personel</span>
                                                                            </a>
                                                                        @endif

                                                                        <form action="{{ route('admin.personels.reset-password', $personel) }}" method="POST" class="w-full">
                                                                            @csrf
                                                                            <button type="submit" data-confirm="Reset password akun login {{ $personel->nama }} menjadi NRP ({{ $personel->nrp }})?" data-confirm-type="warning" class="flex items-center w-full gap-3 p-3 bg-slate-800 rounded-xl text-slate-300 hover:text-white hover:bg-slate-700 transition text-left">
                                                                                <div class="p-1.5 bg-emerald-500/20 text-emerald-400 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg></div>
                                                                                <span class="font-semibold text-xs">Reset Password Login</span>
                                                                            </button>
                                                                        </form>

                                                                        <form action="{{ route('admin.personels.reset-pin', $personel) }}" method="POST" class="w-full">
                                                                            @csrf
                                                                            <button type="submit" data-confirm="Reset PIN personel {{ $personel->nama }}?" data-confirm-type="warning" class="flex items-center w-full gap-3 p-3 bg-slate-800 rounded-xl text-slate-300 hover:text-white hover:bg-slate-700 transition text-left">
                                                                                <div class="p-1.5 bg-amber-500/20 text-amber-400 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg></div>
                                                                                <span class="font-semibold text-xs">Reset PIN (Kartu)</span>
                                                                            </button>
                                                                        </form>

                                                                        @if(auth()->user()->role !== 'super_admin' && $personel->saldo > 0)
                                                                            <span class="flex items-center gap-3 w-full p-3 bg-slate-800/50 rounded-xl text-slate-500 cursor-not-allowed">
                                                                                <div class="p-1.5 bg-slate-800 rounded-lg text-slate-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></div>
                                                                                <span class="font-bold text-xs flex-1 text-left">Hapus Personel</span>
                                                                                <span class="text-[9px] text-rose-500 font-bold bg-rose-500/10 px-2 py-0.5 rounded border border-rose-500/20">Saldo Tersisa</span>
                                                                            </span>
                                                                        @else
                                                                            <form action="{{ route('admin.personels.destroy', $personel) }}" method="POST" class="w-full">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" data-confirm="Yakin ingin menghapus personel ini?" data-confirm-type="error" class="flex items-center gap-3 w-full p-3 bg-rose-900/20 text-rose-400 border border-rose-900/50 rounded-xl hover:text-white hover:bg-rose-600 transition text-left">
                                                                                    <div class="p-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></div>
                                                                                    <span class="font-bold text-xs">Hapus Personel</span>
                                                                                </button>
                                                                            </form>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
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
    <div x-data="personelImportModal()" @open-import-modal.window="openModal()" x-show="show"
        class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition.opacity class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-slate-900/60"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-slate-900 border border-white/5 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle w-full"
                :class="step === 2 ? 'max-w-5xl' : 'max-w-xl'">

                <!-- Modal Content -->
                <div class="relative bg-slate-900 border border-white/5 font-sans">
                    <!-- Header -->
                    <div
                        class="px-5 py-3.5 border-b border-white/5 flex items-center justify-between bg-slate-900 border border-white/5 relative overflow-hidden">
                        <div
                            class="absolute top-0 left-0 w-20 h-20 bg-indigo-50/50 rounded-full -translate-x-10 -translate-y-10 blur-2xl">
                        </div>
                        <div class="relative flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 to-violet-700 flex items-center justify-center text-white shadow-lg shadow-indigo-500/20 shrink-0 transform hover:rotate-6 transition-transform duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354l1.1 3.383h3.558l-2.877 2.09 1.1 3.383-2.877-2.09-2.877 2.09 1.1-3.383-2.877-2.09h3.558l1.1-3.383z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m16-10a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-white tracking-tight">Import Personel</h3>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="w-1 h-1 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Sistem
                                        Unggah Pintar</p>
                                </div>
                            </div>
                        </div>
                        <button @click="closeModal()"
                            class="relative w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-all duration-300 group">
                            <svg class="w-5 h-5 transform group-hover:rotate-90 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Stepper -->
                    <div class="px-4 py-3 bg-slate-800/50/30 border-b border-slate-50 relative">
                        <div class="flex items-center justify-between max-w-md mx-auto">
                            <template x-for="(s, i) in ['Upload', 'Preview', 'Selesai']">
                                <div class="flex items-center flex-1 last:flex-none">
                                    <div class="flex flex-col items-center gap-1.5 group cursor-default">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-[10px] font-black transition-all duration-500 relative"
                                            :class="step > i+1 ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : (step === i+1 ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-500/20 ring-4 ring-indigo-50 scale-110' : 'bg-slate-900 border border-white/5 border border-white/10 text-slate-400')">
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
                                            :class="step >= i+1 ? 'text-white' : 'text-slate-400'"
                                            x-text="s"></span>
                                    </div>
                                    <template x-if="i < 2">
                                        <div
                                            class="flex-1 h-0.5 mx-3 rounded-full transition-colors duration-500 overflow-hidden bg-slate-800">
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
                                class="relative flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-white/10 rounded-[2rem] bg-slate-800/50 hover:bg-slate-900 border border-white/5 hover:border-indigo-400 hover:shadow-xl hover:shadow-indigo-500/20 transition-all duration-700 cursor-pointer overflow-hidden group">
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-purple-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                                </div>
                                <div
                                    class="relative flex flex-col items-center justify-center py-6 transition-all duration-700 group-hover:scale-105">
                                    <div
                                        class="w-14 h-14 mb-4 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 shadow-xl shadow-indigo-500/20 flex items-center justify-center text-white ring-4 ring-indigo-50 group-hover:rotate-[8deg] transition-all duration-500">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                            </path>
                                        </svg>
                                    </div>
                                    <h4 class="text-base font-black text-white mb-1">Lepaskan berkas di sini</h4>
                                    <p
                                        class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] group-hover:text-indigo-500 transition-colors">
                                        Atau klik untuk memilih file Excel/CSV</p>
                                </div>
                                <input type="file" class="hidden" @change="handleFileUpload" accept=".xlsx,.xls,.csv" />

                                <div x-show="uploading"
                                    class="absolute inset-0 bg-slate-900 border border-white/5/98 flex flex-col items-center justify-center p-6 z-20">
                                    <div
                                        class="w-48 h-1.5 bg-slate-800 rounded-full overflow-hidden mb-4 ring-2 ring-indigo-50">
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
                                class="flex-1 p-5 rounded-[1.5rem] bg-gradient-to-br from-white to-slate-50 border border-white/5 shadow-sm transition-all hover:shadow-md">
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
                                            class="text-[11px] font-black text-slate-200 uppercase tracking-widest mb-1">
                                            Butuh Bantuan?</h4>
                                        <p class="text-[10px] font-medium text-slate-400 leading-relaxed mb-3">Gunakan
                                            template resmi untuk menghindari kesalahan format.</p>
                                        <a href="{{ route('admin.personels.download-template') }}"
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
                                class="flex-1 p-5 rounded-[1.5rem] bg-gradient-to-br from-white to-slate-50 border border-white/5 shadow-sm transition-all hover:shadow-md">
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
                                            class="text-[11px] font-black text-slate-200 uppercase tracking-widest mb-2">
                                            Kolom Wajib</h4>
                                        <div class="flex flex-wrap gap-x-3 gap-y-1">
                                            <template x-for="col in ['SATKER', 'NAMA', 'NRP']">
                                                <div class="flex items-center gap-1">
                                                    <div class="w-1 h-1 rounded-full bg-indigo-500"></div>
                                                    <span class="text-[9px] font-black text-slate-400"
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
                        <div class="p-6 pb-3 grid grid-cols-3 gap-4 bg-slate-900 border border-white/5 shrink-0">
                            <div
                                class="group p-4 rounded-2xl bg-gradient-to-br from-indigo-600 to-indigo-800 text-white shadow-lg shadow-indigo-500/20 relative overflow-hidden transform hover:-translate-y-1 transition-all duration-300">
                                <div
                                    class="absolute inset-0 bg-slate-900 border border-white/5/10 opacity-0 group-hover:opacity-100 transition-opacity">
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
                            <div class="w-full h-2 bg-slate-800 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 transition-all duration-300"
                                    :style="`width: ${importProgress}%`"></div>
                            </div>
                            <p
                                class="text-[10px] font-black text-emerald-600 mt-2 uppercase tracking-widest animate-pulse">
                                Memproses Database...</p>
                        </div>

                        <div
                            class="px-7 py-3 bg-slate-800/50 border-y border-white/5 flex items-center justify-between">
                            <h4 class="text-[10px] font-black text-white uppercase tracking-[0.2em]">Analisis Data
                            </h4>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                    <span class="text-[9px] font-black text-slate-400 uppercase">Baru</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                                    <span class="text-[9px] font-black text-slate-400 uppercase">Update</span>
                                </div>
                            </div>
                        </div>

                        <div class="max-h-[320px] overflow-auto border-b border-white/5 scrollbar-thin">
                            <table class="w-full border-separate border-spacing-0">
                                <thead class="bg-slate-900 border border-white/5/95 sticky top-0 z-20 shadow-sm text-center">
                                    <tr>
                                        <th
                                            class="pl-7 pr-3 py-3 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-white/5">
                                            Row</th>
                                        <th
                                            class="px-4 py-3 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-white/5">
                                            Satker Target</th>
                                        <th
                                            class="px-4 py-3 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-white/5">
                                            Nama Personel</th>
                                        <th
                                            class="pr-7 pl-3 py-3 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-white/5">
                                            JENIS BBM</th>
                                        <th
                                            class="pr-7 pl-3 py-3 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-white/5">
                                            NRP/NIP</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-slate-900 border border-white/5 divide-y divide-slate-50">
                                    <!-- Duplicates -->
                                    <template x-for="item in previewData.duplicates">
                                        <tr class="bg-amber-50/30">
                                            <td class="px-4 py-3 text-[10px] font-black text-amber-600"
                                                x-text="item.row"></td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[10px] font-bold text-slate-400"
                                                        x-text="item.satker_name || '-'"></span>
                                                    <template x-if="item.changes.find(c => c.field === 'Satker')">
                                                        <svg class="w-3 h-3 text-amber-500" fill="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path
                                                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                                                        </svg>
                                                    </template>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex flex-col">
                                                    <span class="text-[10px] font-bold text-slate-300"
                                                        x-text="item.nama"></span>
                                                    <template x-if="item.changes.find(c => c.field === 'Nama')">
                                                        <span class="text-[8px] font-bold text-amber-600 italic mt-0.5"
                                                            x-text="'Lama: ' + item.changes.find(c => c.field === 'Nama').old"></span>
                                                    </template>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span
                                                    class="px-2 py-0.5 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 text-[9px] font-black"
                                                    x-text="item.jenis_bbm"></span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span
                                                    class="px-2 py-0.5 rounded-lg bg-slate-900 border border-white/5 border border-amber-200 text-amber-700 text-[9px] font-black"
                                                    x-text="item.nrp"></span>
                                            </td>
                                        </tr>
                                    </template>

                                    <!-- New Entries -->
                                    <template x-for="item in previewData.new_entries">
                                        <tr class="hover:bg-slate-800/50 transition-colors">
                                            <td class="px-4 py-3 text-[10px] font-black text-slate-400"
                                                x-text="item.row"></td>
                                            <td class="px-4 py-3 text-[10px] font-bold text-slate-400"
                                                x-text="item.satker_name || '-'"></td>
                                            <td class="px-4 py-3 text-[10px] font-bold text-white"
                                                x-text="item.nama"></td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span
                                                    class="px-2 py-0.5 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 text-[9px] font-black"
                                                    x-text="item.jenis_bbm"></span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
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

                        <div class="px-6 py-5 bg-slate-800/50/30">
                            <div
                                class="p-4 rounded-2xl bg-slate-900 border border-white/5 shadow-lg shadow-black/20 flex flex-col sm:flex-row items-center justify-between gap-4">
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
                                            Opsi Duplikat</p>
                                        <p class="text-[11px] font-bold text-white">Perlakuan jika NRP terdaftar</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-1 bg-slate-800/50 rounded-xl border border-white/5">
                                    <label class="relative flex items-center cursor-pointer group">
                                        <input type="radio" value="skip" x-model="duplicateAction" class="sr-only peer">
                                        <div
                                            class="px-5 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all peer-checked:bg-slate-900 border border-white/5 peer-checked:text-indigo-600 peer-checked:shadow-sm text-slate-400">
                                            Lewati</div>
                                    </label>
                                    <label class="relative flex items-center cursor-pointer group">
                                        <input type="radio" value="update" x-model="duplicateAction"
                                            class="sr-only peer">
                                        <div
                                            class="px-5 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all peer-checked:bg-slate-900 border border-white/5 peer-checked:text-indigo-600 peer-checked:shadow-sm text-slate-400">
                                            Update</div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div
                        class="px-4 sm:px-6 py-4 bg-slate-900 border border-white/5 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <template x-if="step === 1">
                            <div class="flex items-center gap-2 group">
                                <div
                                    class="w-6 h-6 rounded-lg bg-slate-800/50 flex items-center justify-center text-slate-400 transition-colors duration-500">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="text-[9px] font-black uppercase tracking-[0.15em] text-slate-400">Siapkan
                                    Berkas Anda</span>
                            </div>
                        </template>
                        <template x-if="step === 2">
                            <button @click="backToStep1()" :disabled="importing"
                                class="w-full sm:w-auto px-5 py-2.5 text-[10px] font-black text-slate-400 hover:text-white transition-all uppercase tracking-[0.2em] disabled:opacity-50 flex items-center justify-center sm:justify-start gap-2 group">
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
                                class="w-full sm:w-auto px-6 py-2.5 text-[10px] font-black text-slate-400 hover:text-slate-400 transition-all uppercase tracking-[0.2em] disabled:opacity-50">Batal</button>
                            <template x-if="step === 2">
                                <button @click="confirmImport()"
                                    :disabled="importing || (previewData.new_count === 0 && previewData.duplicate_count === 0)"
                                    class="relative w-full sm:w-auto px-8 py-2.5 bg-indigo-600 text-white rounded-xl text-[10px] font-black shadow-lg shadow-indigo-500/20 hover:bg-indigo-700 hover:scale-105 transition-all uppercase tracking-[0.2em] disabled:opacity-50 disabled:scale-100 group overflow-hidden">
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
                    text: `Apakah Anda yakin ingin menghapus ${selected.length} personel yang terpilih? Tindakan ini tidak dapat dibatalkan.`,
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
                            new_count: 0, duplicate_count: 0, error_count: 0,
                            new_entries: [], duplicates: [], errors: []
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

                        // Simulate progress
                        const interval = setInterval(() => {
                            if (this.uploadProgress < 90) this.uploadProgress += 10;
                        }, 100);

                        fetch('{{ route("admin.personels.preview-import") }}', {
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
                            .catch(err => {
                                clearInterval(interval);
                                this.uploading = false;
                                Swal.fire('Error', err.message, 'error');
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

                        fetch('{{ route("admin.personels.import") }}', {
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
                                    return res.json().then(data => { throw new Error(data.message || 'Gagal import') });
                                }
                            })
                            .catch(err => {
                                clearInterval(interval);
                                this.importing = false;
                                Swal.fire('Error', err.message, 'error');
                            });
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>