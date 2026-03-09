<x-app-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6" x-data="{ 
        showModal: false, 
        showEditModal: false,
        selectedHutang: null, 
        selectedSatker: null,
        hutangData: { nopol: '', bbm: '', jumlah: 0 },
        editData: { id: '', satker_id: '', nopol: '', nama_driver: '', jenis_bbm: '', jumlah_bon: '', tanggal_bon: '' },
        selectedKendaraan: '', 
        selectedBbm: '',
        kendaraans: [],
        loadingKendaraan: false,
        get filteredKendaraans() {
            return this.kendaraans.filter(k => k.jenis_bbm === this.selectedBbm);
        },
        async openModal(id, satkerId, bbm, nopol, jumlah) {
            this.selectedHutang = id;
            this.selectedSatker = satkerId;
            this.selectedBbm = bbm;
            this.hutangData = { nopol, bbm, jumlah };
            this.selectedKendaraan = '';
            this.kendaraans = [];
            this.showModal = true;
            this.loadingKendaraan = true;
            
            try {
                const response = await fetch(`/admin/hutang/get-kendaraan?satker_id=${satkerId}`);
                this.kendaraans = await response.json();
            } catch (error) {
                console.error('Gagal mengambil data kendaraan:', error);
            } finally {
                this.loadingKendaraan = false;
            }
        },
        openEditModal(data) {
            this.editData = { ...data };
            this.showEditModal = true;
        }
    }">
        <!-- Page Title -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 border-b-4 border-indigo-600 pb-2 inline-block">
                Monitoring Hutang BBM</h1>

            <!-- Summary Outstanding per BBM (Dynamic) -->
            <div class="flex flex-wrap gap-3">
                @forelse($summaryHutang as $bbm => $total)
                    <div
                        class="bg-white px-4 py-2 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3 animate-in fade-in slide-in-from-right-4 duration-500">
                        <div class="w-2 h-8 bg-rose-500 rounded-full"></div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">
                                {{ $bbm }}
                            </p>
                            <p class="text-lg font-black text-rose-600 leading-none">
                                {{ number_format($total, 0, ',', '.') }} <span
                                    class="text-xs font-bold text-slate-400">L</span>
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="bg-emerald-50 px-4 py-2 rounded-2xl border border-emerald-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-sm font-bold text-emerald-600">Semua Terbayar</span>
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

        <!-- Filter & Search -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6 mb-6">
            <form action="{{ route('admin.hutang.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <x-input-label for="satker_id" value="Filter Satker" />
                    <select name="satker_id" id="satker_id"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Semua Satker</option>
                        @foreach($satkers as $satker)
                            <option value="{{ $satker->id }}" {{ request('satker_id') == $satker->id ? 'selected' : '' }}>
                                {{ $satker->nama_satker }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1">
                    <x-input-label for="status" value="Status Pembayaran" />
                    <select name="status" id="status"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Semua Status</option>
                        <option value="belum_dibayar" {{ request('status') === 'belum_dibayar' ? 'selected' : '' }}>BELUM
                            BAYAR
                        </option>
                        <option value="sudah_dibayar" {{ request('status') === 'sudah_dibayar' ? 'selected' : '' }}>LUNAS
                        </option>
                    </select>
                </div>

                <div class="flex-1">
                    <x-input-label for="start_date" value="Tanggal Awal" />
                    <x-text-input type="date" name="start_date" id="start_date"
                        value="{{ request('start_date') }}"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                </div>

                <div class="flex-1">
                    <x-input-label for="end_date" value="Tanggal Akhir" />
                    <x-text-input type="date" name="end_date" id="end_date"
                        value="{{ request('end_date') }}"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="px-4 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['satker_id', 'status', 'per_page']))
                        <a href="{{ route('admin.hutang.index') }}"
                            class="px-4 py-2.5 bg-slate-100 text-slate-700 font-semibold rounded-lg hover:bg-slate-200 transition">
                            Reset
                        </a>
                    @endif
                    <a href="{{ route('admin.hutang.pdf', request()->all()) }}"
                        class="px-4 py-2.5 bg-rose-600 text-white font-semibold rounded-lg shadow hover:bg-rose-700 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak PDF
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Data -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Data Hutang BBM</h3>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Tampilkan</span>
                    <form action="{{ route('admin.hutang.index') }}" method="GET" class="inline">
                        @foreach(request()->except('per_page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <select name="per_page" onchange="this.form.submit()"
                            class="block border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-xs py-1.5 font-bold text-slate-700">
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
                    <thead class="text-[10px] text-slate-500 uppercase bg-slate-50/80 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3 font-bold">Tanggal</th>
                            <th class="px-4 py-3 font-bold">Satker</th>
                            <th class="px-4 py-3 font-bold">Kendaraan</th>
                            <th class="px-4 py-3 font-bold">Driver</th>
                            <th class="px-4 py-3 font-bold">Jumlah Bon</th>
                            <th class="px-4 py-3 font-bold">Petugas Pencatat</th>
                            <th class="px-4 py-3 font-bold">Status</th>
                            @if(auth()->user()->role !== 'kasubbag')
                                <th class="px-4 py-3 font-bold text-center border-l border-slate-200 bg-slate-100/30">
                                    Pembayaran</th>
                                <th class="px-4 py-3 font-bold text-center border-l border-slate-200">Kelola</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($hutangs as $hutang)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-4 py-2">
                                    <div class="font-medium text-slate-900">
                                        @if($hutang->tanggal_bon)
                                            {{ \Carbon\Carbon::parse($hutang->tanggal_bon)->format('d-m-Y') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->format('d-m-Y') }}
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-slate-500">
                                        @if($hutang->tanggal_bon)
                                            CATATAN MANUAL
                                        @else
                                            {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->format('H:i') }}
                                            WITA
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-2 font-semibold text-slate-800">{{ $hutang->satker->nama_satker }}</td>
                                <td class="px-4 py-2">
                                    <div class="font-bold text-slate-900 leading-tight">{{ $hutang->nopol }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $hutang->jenis_kendaraan }}</div>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="font-bold text-slate-800">{{ $hutang->nama_driver ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-2">
                                    <span
                                        class="inline-flex items-center px-2 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded text-xs font-bold">
                                        {{ number_format($hutang->jumlah_bon, 0, ',', '.') }} L {{ $hutang->jenis_bbm }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-slate-700 text-xs">{{ $hutang->petugas->name ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    @if($hutang->status === 'belum_dibayar')
                                        <span
                                            class="px-2 py-0.5 bg-rose-100 text-rose-700 border border-rose-200 rounded-lg text-[10px] font-bold uppercase">BELUM
                                            BAYAR</span>
                                    @else
                                        <div class="flex flex-col gap-0.5">
                                            <span
                                                class="px-2 py-0.5 bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-lg text-[10px] font-bold uppercase w-fit">LUNAS</span>
                                            <span class="text-[9px] text-slate-500">Oleh:
                                                {{ $hutang->adminBayar->name ?? '-' }}</span>
                                            <span
                                                class="text-[9px] text-slate-500">{{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->format('d-m-Y') }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                @if(auth()->user()->role !== 'kasubbag')
                                    <td class="px-4 py-2 border-l border-slate-100 bg-slate-50/20">
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
                                    <td class="px-4 py-2 border-l border-slate-100">
                                        <div class="flex items-center justify-center gap-2">
                                            @php
                                                $editData = json_encode([
                                                    'id' => $hutang->id,
                                                    'satker_id' => $hutang->satker_id,
                                                    'nopol' => $hutang->nopol,
                                                    'nama_driver' => $hutang->nama_driver,
                                                    'jenis_bbm' => $hutang->jenis_bbm,
                                                    'jumlah_bon' => $hutang->jumlah_bon,
                                                    'tanggal_bon' => $hutang->tanggal_bon ?? $hutang->created_at->format('Y-m-d')
                                                ]);
                                            @endphp
                                            <button @click="openEditModal({{ htmlspecialchars($editData) }})"
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
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role === 'kasubbag' ? 7 : 9 }}"
                                    class="px-6 py-8 text-center text-slate-500 italic">Tidak ada data hutang
                                    ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($hutangs->hasPages())
                <div class="p-4 border-t border-slate-200">
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
                    class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full">

                    <!-- Modal Header -->
                    <div
                        class="bg-indigo-600 px-6 py-4 text-white flex justify-between items-center bg-gradient-to-r from-indigo-600 to-indigo-700">
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 p-2 rounded-xl">
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

                    <div class="bg-white px-6 pt-6 pb-6 border-b border-slate-100">
                        <div class="mb-6">
                            <!-- Summary Card -->
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex flex-col gap-3">
                                <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Detail
                                        Hutang</span>
                                    <span
                                        class="text-indigo-600 font-bold text-xs bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100 uppercase"
                                        x-text="hutangData.bbm"></span>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase">No. Polisi</p>
                                        <p class="text-lg font-black text-slate-800" x-text="hutangData.nopol"></p>
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
                                <label for="kendaraan_id"
                                    class="block text-sm font-black text-slate-700 mb-2 uppercase tracking-wide">PILIH
                                    KENDARAAN PEMBAYAR</label>

                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                        </svg>
                                    </div>
                                    <select name="kendaraan_id" id="kendaraan_id" x-model="selectedKendaraan"
                                        class="block w-full pl-11 pr-10 py-3.5 bg-slate-50 border-slate-200 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-2xl transition-all shadow-sm font-bold text-slate-700 appearance-none"
                                        :disabled="loadingKendaraan" required>
                                        <option value="" disabled x-show="!loadingKendaraan">-- Pilih Kendaraan --
                                        </option>
                                        <option value="" disabled x-show="loadingKendaraan">Sedang mengambil data...
                                        </option>
                                        <template x-for="kend in filteredKendaraans" :key="kend.id">
                                            <option :value="kend.id"
                                                x-text="`${kend.no_polisi} (Saldo: ${kend.saldo} L)`"></option>
                                        </template>
                                    </select>

                                    <div
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                        <template x-if="loadingKendaraan">
                                            <svg class="animate-spin h-5 w-5 text-indigo-600" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </template>
                                        <template x-if="!loadingKendaraan">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </template>
                                    </div>
                                </div>

                                <p x-show="!loadingKendaraan && filteredKendaraans.length === 0"
                                    class="text-sm text-rose-600 mt-4 p-3 bg-rose-50 border border-rose-100 rounded-xl font-bold flex items-center gap-2"
                                    style="display: none;">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Peringatan: Tidak ada kendaraan dengan jenis BBM <span x-text="selectedBbm"></span>
                                    di Satker ini.
                                </p>
                            </div>
                        </form>
                    </div>

                    <div class="bg-white px-6 py-5 flex flex-col sm:flex-row-reverse gap-3 rounded-b-3xl">
                        <button type="submit" form="paymentForm" :disabled="!selectedKendaraan || loadingKendaraan"
                            class="w-full sm:w-auto px-8 py-3.5 bg-indigo-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 active:scale-95 transition-all text-sm uppercase tracking-widest disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none">
                            KONFIRMASI PEMBAYARAN
                        </button>
                        <button type="button" @click="showModal = false"
                            class="w-full sm:w-auto px-8 py-3.5 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition-all text-sm uppercase tracking-widest">
                            BATAL
                        </button>
                    </div>
                </div>
            </div>
        <!-- Edit Modal -->
        <div x-show="showEditModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEditModal" x-transition.opacity
                    class="fixed inset-0 bg-slate-900/60 transition-opacity backdrop-blur-sm" aria-hidden="true"
                    @click="showEditModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showEditModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full border border-slate-200">

                    <!-- Modal Header -->
                    <div
                        class="bg-blue-600 px-6 py-4 text-white flex justify-between items-center bg-gradient-to-r from-blue-600 to-blue-700">
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 p-2 rounded-xl">
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

                    <form :action="'/admin/hutang/' + editData.id" method="POST" class="p-6 space-y-4">
                        @csrf @method('PUT')

                        <div>
                            <x-input-label for="edit_satker_id" value="Satker" />
                            <select name="satker_id" id="edit_satker_id" x-model="editData.satker_id"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm font-bold text-slate-700">
                                @foreach($satkers as $satker)
                                    <option value="{{ $satker->id }}">{{ $satker->nama_satker }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="edit_nopol" value="No. Polisi" />
                                <x-text-input id="edit_nopol" name="nopol" type="text" x-model="editData.nopol"
                                    class="mt-1 block w-full font-bold uppercase" />
                            </div>
                            <div>
                                <x-input-label for="edit_jenis_bbm" value="Jenis BBM" />
                                <select name="jenis_bbm" id="edit_jenis_bbm" x-model="editData.jenis_bbm"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm font-bold">
                                    <option value="PERTAMAX">PERTAMAX</option>
                                    <option value="PERTAMINA DEX">PERTAMINA DEX</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="edit_nama_driver" value="Nama Driver" />
                            <x-text-input id="edit_nama_driver" name="nama_driver" type="text"
                                x-model="editData.nama_driver" class="mt-1 block w-full font-bold" />
                        </div>

                        <div>
                            <x-input-label for="edit_tanggal_bon" value="Tanggal Bon" />
                            <x-text-input id="edit_tanggal_bon" name="tanggal_bon" type="date"
                                x-model="editData.tanggal_bon" class="mt-1 block w-full font-bold" required />
                        </div>

                        <div>
                            <x-input-label for="edit_jumlah_bon" value="Jumlah Bon (Liter)" />
                            <x-text-input id="edit_jumlah_bon" name="jumlah_bon" type="number" step="0.1"
                                x-model="editData.jumlah_bon" class="mt-1 block w-full font-bold text-rose-600" />
                        </div>

                        <div class="pt-4 flex flex-col sm:flex-row-reverse gap-3">
                            <button type="submit"
                                class="w-full sm:w-auto px-8 py-3.5 bg-blue-600 text-white font-black rounded-2xl shadow-xl shadow-blue-100 hover:bg-blue-700 active:scale-95 transition-all text-sm uppercase tracking-widest">
                                SIMPAN PERUBAHAN
                            </button>
                            <button type="button" @click="showEditModal = false"
                                class="w-full sm:w-auto px-8 py-3.5 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition-all text-sm uppercase tracking-widest">
                                BATAL
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
</x-app-layout>