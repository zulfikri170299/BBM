<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Personel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[95rem] mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">



                    <!-- Action Bar -->
                    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div class="flex gap-2">
                            <a href="{{ route('satker.personels.create') }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition-colors">
                                + Add Personel
                            </a>
                            <a href="{{ route('satker.personels.download-template') }}"
                                class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg shadow transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Template Excel
                            </a>
                        </div>

                        <!-- Search & Import -->
                        <div class="flex flex-col md:flex-row gap-4 items-end md:items-center">
                            <!-- Search Form -->
                            <form action="{{ route('satker.personels.index') }}" method="GET" class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Cari Nama / NRP..."
                                    class="pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-64 transition-shadow">
                            </form>

                            <form action="{{ route('satker.personels.import') }}" method="POST"
                                enctype="multipart/form-data" class="flex items-center gap-2">
                                @csrf
                                <input type="file" name="file" class="block w-full text-sm text-slate-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-violet-50 file:text-violet-700
                                    hover:file:bg-violet-100 transform transition-all" required>
                                <button type="submit"
                                    class="bg-violet-600 hover:bg-violet-700 text-white font-bold py-2 px-4 rounded-lg shadow transition-colors">
                                    Import
                                </button>
                            </form>
                        </div>
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

                    @if(session('warning'))
                        <div x-data="{ show: true }" x-show="show" x-transition
                            class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-100 flex items-start gap-3 shadow-sm relative">
                            <div class="flex-shrink-0 text-amber-500 mt-0.5">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-amber-900 text-sm">Import Selesai dengan Catatan</h3>
                                <p class="text-amber-800 text-sm mt-1 leading-relaxed">{{ session('warning') }}</p>
                            </div>
                            <button @click="show = false" class="text-amber-400 hover:text-amber-600 transition-colors">
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
                                                <a href="{{ route('satker.personels.print', $personel) }}" target="_blank"
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
                                                @if($personel->saldo > 0)
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
</x-app-layout>

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

    function bulkDeletePersonel() {
        const checked = document.querySelectorAll('.item-checkbox:checked');
        if (checked.length === 0) return;

        Swal.fire({
            title: 'Hapus ' + checked.length + ' Personel?',
            text: 'Data yang dihapus tidak dapat dikembalikan! Personel dengan saldo tersisa akan dilewati.',
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