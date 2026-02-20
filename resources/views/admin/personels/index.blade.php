<x-app-layout>
    <div class="py-12">
        <div class="max-w-[95rem] mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Header & Filter -->
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div>
                                <h2 class="text-2xl font-bold text-slate-800 leading-tight">Data Personel</h2>
                                <p class="text-slate-500 text-sm mt-1">Kelola data personel dan saldo BBM.</p>
                            </div>
                        </div>

                        <form action="{{ route('admin.personels.index') }}" method="GET"
                            class="flex flex-col sm:flex-row gap-3">
                            <!-- Filter Satker Custom Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <input type="hidden" name="satker_id" id="satker_id" value="{{ request('satker_id') }}">

                                <button type="button" @click="open = !open" @click.away="open = false"
                                    class="flex items-center justify-between w-full sm:w-48 bg-slate-50 border border-slate-300 text-slate-700 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2.5 hover:bg-white transition-colors">
                                    <span class="truncate block mr-2">
                                        @php
                                            $selectedSatker = $satkers->firstWhere('id', request('satker_id'));
                                            echo $selectedSatker ? $selectedSatker->nama_satker : 'Semua Satker';
                                        @endphp
                                    </span>
                                    <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute z-10 w-full sm:w-64 mt-1 bg-white rounded-xl shadow-lg border border-slate-100 py-1 max-h-60 overflow-auto focus:outline-none"
                                    style="display: none;">

                                    <div
                                        class="px-3 py-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                        Filter Satuan Kerja
                                    </div>

                                    <button type="button"
                                        @click="document.getElementById('satker_id').value = ''; $el.closest('form').submit();"
                                        class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-indigo-600 flex items-center justify-between group transition-colors">
                                        <span>Semua Satker</span>
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
                                            class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-indigo-600 flex items-center justify-between group transition-colors">
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
                            </div>

                            <!-- Search -->
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-500" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="block w-full sm:w-64 p-2.5 pl-10 text-sm text-slate-900 border border-slate-300 rounded-lg bg-slate-50 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Cari Nama atau NRP...">
                            </div>

                            <button type="submit"
                                class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-4 py-2.5 transition-colors">
                                Cari
                            </button>

                            @if(request('search') || request('satker_id'))
                                <a href="{{ route('admin.personels.index') }}"
                                    class="text-slate-700 bg-slate-100 hover:bg-slate-200 focus:ring-4 focus:outline-none focus:ring-slate-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center transition-colors">
                                    Reset
                                </a>
                            @endif

                            <div class="flex items-center gap-2 ml-auto lg:ml-0 border-l lg:border-l-0 pl-3 lg:pl-0 border-slate-200">
                                <a href="{{ route('admin.personels.create') }}" 
                                   class="inline-flex items-center p-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-sm"
                                   title="Tambah Personel">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                </a>
                                <button type="button" @click="$dispatch('open-import-modal')" 
                                        class="inline-flex items-center p-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition shadow-sm"
                                        title="Import Excel">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                </button>
                                <a href="{{ route('admin.personels.export', request()->all()) }}" 
                                   class="inline-flex items-center p-2.5 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition shadow-sm"
                                   title="Export Excel">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </a>
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
                    <div id="bulkActions" class="hidden items-center gap-3 mb-4 p-3 bg-indigo-50 border border-indigo-100 rounded-xl">
                        <span class="text-xs font-bold text-indigo-600 bg-white px-3 py-1.5 rounded-lg border border-indigo-100">
                            <span id="selectedCount">0</span> DIPILIH
                        </span>
                        <button type="button" id="bulkDeleteBtn"
                            class="px-3 py-1.5 bg-rose-600 text-white rounded-lg text-xs font-bold hover:bg-rose-700 transition shadow-sm">
                            <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus Terpilih
                        </button>
                    </div>

                    <form id="bulkDeleteForm" action="{{ route('admin.personels.bulk-delete') }}" method="POST" class="hidden">
                        @csrf
                        <div id="bulkIdsContainer"></div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="w-10 px-6 py-4">
                                        <input type="checkbox" id="checkAll"
                                            class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 shadow-sm cursor-pointer">
                                    </th>
                                    <th
                                        class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                        Data Personel</th>
                                    <th
                                        class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                        Satker & BBM</th>
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
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" value="{{ $personel->id }}"
                                                class="item-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 shadow-sm cursor-pointer">
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
                                                        {{ $personel->nama }}</p>
                                                    <p class="text-[11px] font-medium text-slate-500 mt-0.5">NRP:
                                                        {{ $personel->nrp }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-xs font-bold text-slate-700 capitalize mb-1.5">
                                                {{ strtolower($personel->satker->nama_satker ?? '-') }}</p>
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
                                                class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $colorClass }}">
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
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex justify-end items-center gap-2">
                                                <a href="{{ route('admin.personels.edit', $personel) }}"
                                                    class="inline-flex items-center p-2 bg-slate-100 hover:bg-indigo-100 text-slate-500 hover:text-indigo-600 rounded-lg transition-colors"
                                                    title="Edit Personel">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.personels.print', $personel) }}" target="_blank"
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
                                                <form action="{{ route('admin.personels.reset-pin', $personel) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" data-confirm="Reset PIN personel {{ $personel->nama }}? PIN baru akan di-generate secara acak."
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
    <div x-data="{ show: false }" 
         @open-import-modal.window="show = true"
         x-show="show" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <div class="bg-white p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Import Data Personel</h3>
                                <p class="text-xs text-slate-500">Unggah file Excel untuk menambah personel massal</p>
                            </div>
                        </div>
                        <button @click="show = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form action="{{ route('admin.personels.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="p-4 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50/50 hover:bg-slate-50 transition-colors group">
                            <input type="file" name="file" class="hidden" id="fileInput" @change="fileName = $event.target.files[0].name" x-data="{ fileName: '' }">
                            <label for="fileInput" class="cursor-pointer flex flex-col items-center justify-center gap-2">
                                <svg class="w-8 h-8 text-slate-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="text-sm font-medium text-slate-600" x-text="fileName || 'Pilih file Excel (.xlsx, .xls, .csv)'"></span>
                            </label>
                        </div>

                        <div class="bg-indigo-50 rounded-xl p-4 flex items-start gap-3">
                            <svg class="w-5 h-5 text-indigo-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <h4 class="text-xs font-bold text-indigo-900">Petunjuk Import</h4>
                                <ul class="text-[10px] text-indigo-700 mt-1 list-disc list-inside space-y-0.5">
                                    <li>Gunakan template yang disediakan</li>
                                    <li>Data satker akan dicari berdasarkan kolom SATKER</li>
                                    <li>NRP akan digunakan sebagai username & password</li>
                                </ul>
                                <a href="{{ route('admin.personels.download-template') }}" class="inline-flex items-center text-[10px] font-bold text-indigo-600 mt-2 hover:text-indigo-800 transition-colors">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    Unduh Template CSV
                                </a>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" @click="show = false" class="px-4 py-2 text-sm font-bold text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-sm font-bold rounded-lg hover:bg-emerald-700 transition shadow-sm">Mulai Import</button>
                        </div>
                    </form>
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

        document.getElementById('bulkDeleteBtn').addEventListener('click', function() {
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
    </script>
    @endpush
</x-app-layout>