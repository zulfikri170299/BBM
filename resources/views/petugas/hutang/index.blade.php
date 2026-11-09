<x-app-layout>
    <div class="max-w-7xl mx-auto p-2 sm:p-6 lg:p-8 space-y-6 px-2 sm:px-6 lg:px-8">
        <!-- Page Title -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h1 class="text-2xl sm:text-2xl font-bold text-slate-800 dark:text-white border-b-4 border-indigo-600 pb-2 inline-block">
                Rekapan Hutang BBM</h1>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Input Baru -->
            <div
                class="lg:col-span-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/5 rounded-3xl border border-slate-200 dark:border-white/10 shadow-xl shadow-indigo-500/10 p-6 sm:p-8 relative overflow-hidden group">
                <!-- Decorative background -->
                <div
                    class="absolute -top-12 -right-12 w-32 h-32 bg-indigo-500/20 rounded-full blur-3xl group-hover:bg-indigo-500/40 transition-colors duration-500">
                </div>

                <div class="relative">
                    <div class="flex items-center gap-4 mb-8">
                        <div
                            class="p-3 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl text-white shadow-lg shadow-indigo-500/30">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-800 dark:text-slate-200 tracking-tight">Catat Hutang Baru</h2>
                            <p class="text-xs font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wider">Input Data Hutang
                                Satker</p>
                        </div>
                    </div>

                    <form action="{{ route('petugas.hutang.store') }}" method="POST" class="space-y-6" id="debtForm" x-data="petugasHutangForm()">
                        @csrf

                        <!-- Satker Selection -->
                        <div class="space-y-2">
                            <x-input-label for="satker_id" value="Pilih Satker Unit"
                                class="text-slate-700 dark:text-slate-300 font-bold ml-1" />
                            <div class="relative">
                                <select id="satker_id" name="satker_id"
                                    x-model="satkerId"
                                    @change="onSatkerChange($event.target.value)"
                                    class="tom-select block w-full py-3 px-3.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 focus:bg-white dark:focus:bg-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl transition-all shadow-sm font-bold text-xs sm:text-sm text-slate-800 dark:text-white cursor-pointer"
                                    required>
                                    <option class="bg-white text-slate-900 dark:bg-slate-900 dark:text-white" value="">-- Pilih Satker --</option>
                                    @foreach($satkers as $sat)
                                        <option class="bg-white text-slate-900 dark:bg-slate-900 dark:text-white" value="{{ $sat->id }}">{{ $sat->nama_satker }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Vehicle Selection -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <x-input-label for="kendaraan_select" value="Pilih Kendaraan"
                                    class="text-slate-700 dark:text-slate-300 font-bold ml-1" />
                                <span x-show="loadingKendaraan" x-cloak class="text-[10px] text-amber-500 flex items-center gap-1 font-semibold">
                                    <svg class="animate-spin h-3.5 w-3.5 text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                    Memuat kendaraan...
                                </span>
                            </div>
                            <div class="relative">
                                <select id="kendaraan_select"
                                    x-model="kendaraanId"
                                    @change="onKendaraanChange($event.target.value)"
                                    :disabled="loadingKendaraan || !satkerId || kendaraans.length === 0"
                                    class="tom-select block w-full py-3 px-3.5 pr-10 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 focus:bg-white dark:focus:bg-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl transition-all shadow-sm font-bold text-xs sm:text-sm text-slate-800 dark:text-white disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                                    required>
                                    <option value="" disabled selected x-text="!satkerId ? '-- Pilih Satker Dahulu --' : (loadingKendaraan ? 'Sedang memuat data kendaraan...' : (kendaraans.length ? '-- Pilih Kendaraan --' : 'Tidak ada kendaraan tersedia'))"></option>
                                    <template x-for="kend in kendaraans" :key="kend.id">
                                        <option :value="kend.id" x-text="`${kend.no_polisi} - ${kend.jenis_kendaraan} (${kend.jenis_bbm}) ${kend.bebas_bon ? '[✨ BEBAS LIMIT BON]' : ''}`" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-white py-1.5"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        {{-- Card Info Kendaraan --}}
                        <div x-show="selectedKendaraan" x-cloak
                            class="bg-gradient-to-br from-slate-100 dark:from-slate-900 to-indigo-900 rounded-2xl p-4 text-slate-800 dark:text-white shadow-lg space-y-3 transition-all duration-300">
                            <div class="flex justify-between items-start">
                                <span
                                    class="px-2 py-0.5 bg-white/20 rounded-md text-[10px] font-bold uppercase tracking-widest text-slate-800 dark:text-white/80">Info
                                    Kendaraan</span>
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                    <path
                                        d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7h-3v3h3V7z" />
                                </svg>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[10px] text-indigo-600 dark:text-indigo-300 font-bold uppercase">No. Polisi</p>
                                    <p class="text-sm font-black tracking-tight" x-text="nopol || '-'"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-indigo-600 dark:text-indigo-300 font-bold uppercase">Jenis BBM</p>
                                    <p class="text-sm font-bold" x-text="jenisBbm || '-'"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Panel Stok Tangki --}}
                        <div x-show="selectedKendaraan && currentStok !== null" x-cloak
                            :class="{
                                'border-rose-200 bg-rose-50 text-rose-600': currentStok <= 0,
                                'border-amber-200 bg-amber-50 text-amber-600': currentStok > 0 && currentStok < 100,
                                'border-emerald-200 bg-emerald-50 text-emerald-600': currentStok >= 100
                            }"
                            class="rounded-2xl border p-4 space-y-1 transition-all duration-300">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400">Stok BBM di Tangki</p>
                            <div class="flex items-end justify-between">
                                <div>
                                    <span class="text-xs font-bold text-slate-600 dark:text-slate-400" x-text="jenisBbm || '-'"></span>
                                    <p class="text-2xl font-black">
                                        <span x-text="Number(currentStok || 0).toLocaleString('id-ID')"></span>
                                        <span class="text-sm font-bold">L</span>
                                    </p>
                                </div>
                                <svg class="w-8 h-8 text-slate-700 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Hidden fields -->
                        <input type="hidden" id="jenis_kendaraan" name="jenis_kendaraan" :value="jenisKendaraan" required>
                        <input type="hidden" id="nopol" name="nopol" :value="nopol" required>
                        <input type="hidden" id="jenis_bbm" name="jenis_bbm" :value="jenisBbm" required>

                        <!-- Tanggal Bon Input -->
                        <div class="space-y-2">
                            <x-input-label for="tanggal_bon" value="Tanggal Bon"
                                class="text-slate-700 dark:text-slate-300 font-bold ml-1" />
                            <div class="relative group/input">
                                <x-text-input id="tanggal_bon" name="tanggal_bon" type="date"
                                    class="flatpickr block w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-white/10 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-2xl py-3.5 transition-all duration-300 font-bold text-slate-700 dark:text-slate-300"
                                    value="{{ date('Y-m-d') }}" required />
                            </div>
                        </div>

                        <!-- Nama Driver (Manual Input) -->
                        <div class="space-y-2">
                            <x-input-label for="nama_driver" value="Nama Driver (Manual)"
                                class="text-slate-700 dark:text-slate-300 font-bold ml-1" />
                            <div class="relative group/input">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600 dark:text-slate-400 group-focus-within/input:text-indigo-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <x-text-input id="nama_driver" name="nama_driver" type="text"
                                    class="block w-full pl-11 bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-white/10 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-2xl py-3.5 transition-all duration-300 font-bold text-slate-700 dark:text-slate-300"
                                    placeholder="Masukkan Nama Driver..." required />
                            </div>
                        </div>

                        <!-- Amount Input -->
                        <div class="space-y-2">
                            <x-input-label for="jumlah_bon" value="Jumlah Bon (Liter)"
                                class="text-slate-700 dark:text-slate-300 font-bold ml-1" />
                            <div class="relative group/input">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-600 dark:text-slate-400 group-focus-within/input:text-indigo-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                </div>
                                <x-text-input id="jumlah_bon" type="number" step="1" min="1" name="jumlah_bon"
                                    x-model="jumlahBon"
                                    @input="validateJumlah"
                                    class="block w-full pl-11 bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-white/10 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-2xl py-3.5 transition-all duration-300 font-bold text-slate-700 dark:text-slate-300 text-lg"
                                    placeholder="0.0" required />
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <span class="text-slate-600 dark:text-slate-400 font-bold text-sm">LTR</span>
                                </div>
                            </div>
                            {{-- Pesan error stok --}}
                            <div x-show="stokError" x-cloak class="flex items-center gap-2 mt-1 px-3 py-2 bg-rose-50 border border-rose-200 rounded-xl">
                                <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                </svg>
                                <span class="text-xs font-bold text-rose-600" x-text="stokErrorText"></span>
                            </div>
                            @error('jumlah_bon')
                                <p class="text-xs text-rose-600 font-bold mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                :disabled="isSubmitDisabled"
                                :class="{'opacity-50 cursor-not-allowed': isSubmitDisabled}"
                                class="w-full inline-flex justify-center items-center px-6 py-4 bg-gradient-to-r from-indigo-600 to-blue-600 border-none rounded-2xl font-black text-white uppercase tracking-widest hover:from-indigo-700 hover:to-blue-700 active:scale-[0.98] transition-all duration-200 shadow-xl shadow-indigo-500/30 group/btn">
                                <span>Simpan Data Hutang</span>
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Riwayat -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/5 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm p-6 relative z-10">
                <div class="space-y-6 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-slate-50 dark:bg-slate-800 rounded-lg text-slate-600 dark:text-slate-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-200">Riwayat Hutang BBM</h2>
                        </div>

                        <!-- Summary Outstanding per BBM -->
                        <div class="flex flex-wrap gap-2">
                            @foreach($summaryHutang as $bbm => $total)
                                <div
                                    class="px-3 py-1.5 bg-rose-50 border border-rose-100 rounded-xl flex items-center gap-2">
                                    <span
                                        class="text-[10px] font-black text-rose-600 dark:text-rose-400 uppercase tracking-widest">{{ $bbm }}</span>
                                    <span class="text-xs font-bold text-rose-500">{{ $total }} L</span>
                                </div>
                            @endforeach
                            @if($summaryHutang->isEmpty())
                                <div
                                    class="px-3 py-1.5 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center gap-2">
                                    <span class="text-xs font-semibold text-emerald-500 italic">Data Kosong</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Filter Form -->
                    <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 rounded-2xl p-4">
                        <form action="{{ route('petugas.hutang.index') }}" method="GET"
                            class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="filter_satker" value="Filter Satker"
                                    class="text-xs font-bold text-slate-600 dark:text-slate-400 mb-1" />
                                <x-filter-select 
                                    name="satker_id" 
                                    :value="request('satker_id')" 
                                    placeholder="Semua Satker" 
                                    search-placeholder="Cari nama satker..." 
                                    icon="building" 
                                    :options="$satkers" 
                                    :auto-submit="true"
                                />
                            </div>
                            <div>
                                <x-input-label for="filter_status" value="Filter Status"
                                    class="text-xs font-bold text-slate-600 dark:text-slate-400 mb-1" />
                                <x-filter-select 
                                    name="status" 
                                    :value="request('status')" 
                                    placeholder="Semua Status" 
                                    icon="shield" 
                                    :searchable="false" 
                                    :options="['belum_dibayar' => 'BELUM BAYAR', 'sudah_dibayar' => 'LUNAS']" 
                                    :auto-submit="true"
                                />
                            </div>
                            <div class="flex items-end gap-2 text-xs">
                                <button type="submit"
                                    class="flex-1 px-4 py-2 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/20 active:scale-95">
                                    Terapkan
                                </button>
                                @if(request()->hasAny(['satker_id', 'status']))
                                    <a href="{{ route('petugas.hutang.index') }}"
                                        class="px-4 py-2 bg-slate-200 text-slate-700 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-300 transition active:scale-95">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 dark:text-white overflow-x-auto rounded-xl border border-slate-200 dark:border-white/10/60">
                    <div class="px-4 py-3 border-b border-slate-200 dark:border-white/5 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50">
                        <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Daftar Rekapan Hutang</h3>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] uppercase font-bold text-slate-600 dark:text-slate-400">Tampilkan</span>
                            <form action="{{ route('petugas.hutang.index') }}" method="GET" class="inline">
                                @foreach(request()->except('per_page') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <select name="per_page" onchange="this.form.submit()"
                                    class="bg-white dark:bg-slate-900 dark:text-white block border-slate-200 dark:border-white/10 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-xs py-1.5 font-bold text-slate-700 dark:text-slate-300">
                                    <option class="bg-white text-slate-900 dark:bg-slate-900 dark:text-white" value="15" {{ request('per_page') == 15 || !request('per_page') ? 'selected' : '' }}>15</option>
                                    <option class="bg-white text-slate-900 dark:bg-slate-900 dark:text-white" value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option class="bg-white text-slate-900 dark:bg-slate-900 dark:text-white" value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option class="bg-white text-slate-900 dark:bg-slate-900 dark:text-white" value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </form>
                        </div>
                    </div>
                    <table class="w-full text-sm text-left whitespace-nowrap">
                        <thead class="text-xs text-slate-600 dark:text-slate-400 uppercase bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-white/10">
                            <tr>
                                <th class="px-4 py-4 font-bold tracking-wider">Tanggal</th>
                                <th class="px-4 py-4 font-bold tracking-wider">Satker</th>
                                <th class="px-4 py-4 font-bold tracking-wider">Kendaraan</th>
                                <th class="px-4 py-4 font-bold tracking-wider">Driver</th>
                                <th class="px-4 py-4 font-bold tracking-wider">Jumlah Bon</th>
                                <th class="px-4 py-4 font-bold tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-white/5">
                            @forelse ($hutangs as $hutang)
                                <tr class="hover:bg-slate-50 dark:bg-slate-800/50 transition-colors">
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-slate-800 dark:text-white">
                                            @if($hutang->tanggal_bon)
                                                {{ \Carbon\Carbon::parse($hutang->tanggal_bon)->translatedFormat('d F Y') }}
                                            @else
                                                {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->translatedFormat('d F Y') }}
                                            @endif
                                        </p>
                                        <p class="text-xs text-slate-600 dark:text-slate-400">
                                            @if($hutang->tanggal_bon)
                                                {{-- For manual date, time might not be relevant or default to midnight --}}
                                                Catatan Manual
                                            @else
                                                {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->format('H:i') }}
                                                WITA
                                            @endif
                                        </p>
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-slate-800 dark:text-slate-200">{{ $hutang->satker->nama_satker }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-bold text-slate-800 dark:text-white">{{ $hutang->nopol }}</p>
                                        <p class="text-xs text-slate-600 dark:text-slate-400">{{ $hutang->jenis_kendaraan }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-slate-800 dark:text-slate-200">{{ $hutang->nama_driver ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="inline-flex items-center px-2 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-xs font-bold">
                                            {{ $hutang->jumlah_bon }} L {{ $hutang->jenis_bbm }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($hutang->status === 'belum_dibayar')
                                            <span
                                                class="px-2.5 py-1 bg-rose-100 text-rose-700 border border-rose-200 rounded-lg text-xs font-bold tracking-wide uppercase">BELUM
                                                BAYAR</span>
                                        @else
                                            <span
                                                class="px-2.5 py-1 bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-bold tracking-wide uppercase">LUNAS</span>
                                            <div class="mt-1 text-xs">
                                                <span class="text-[10px] text-slate-600 dark:text-slate-400">Oleh:
                                                    {{ $hutang->adminBayar->name ?? '-' }}</span>
                                                <span
                                                    class="text-[10px] text-slate-600 dark:text-slate-400">{{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->translatedFormat('d M Y H:i') }}
                                                    WITA</span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-600 dark:text-slate-400 italic">Belum ada data
                                        hutang BBM.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $hutangs->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function petugasHutangForm() {
            return {
                satkerId: '',
                kendaraanId: '',
                kendaraans: [],
                loadingKendaraan: false,
                selectedKendaraan: null,
                nopol: '',
                jenisKendaraan: '',
                jenisBbm: '',
                jumlahBon: '',
                currentStok: null,
                stokError: false,
                stokErrorText: '',
                stokTangkiData: @json($stokTangki),

                normalizeBbmKey(bbm) {
                    if (!bbm) return '';
                    const upper = bbm.toUpperCase();
                    if (upper.includes('DEX')) return 'Pertamina Dex';
                    if (upper.includes('PERTAMAX')) return 'Pertamax';
                    return bbm;
                },

                async onSatkerChange(val) {
                    this.satkerId = val;
                    this.kendaraanId = '';
                    this.selectedKendaraan = null;
                    this.nopol = '';
                    this.jenisKendaraan = '';
                    this.jenisBbm = '';
                    this.currentStok = null;
                    this.stokError = false;
                    this.kendaraans = [];

                    if (!val) return;

                    this.loadingKendaraan = true;
                    try {
                        const res = await fetch('{{ route("petugas.hutang.get-kendaraan") }}?satker_id=' + val);
                        const data = await res.json();
                        this.kendaraans = Array.isArray(data) ? data : [];
                    } catch (err) {
                        console.error('Gagal mengambil kendaraan:', err);
                        this.kendaraans = [];
                    } finally {
                        this.loadingKendaraan = false;
                        this.$nextTick(() => {
                            const kSelect = document.getElementById('kendaraan_select');
                            if (kSelect) kSelect.dispatchEvent(new Event('custom-select:refresh'));
                        });
                    }
                },

                onKendaraanChange(val) {
                    this.kendaraanId = val;
                    const kend = this.kendaraans.find(k => k.id == val);
                    if (kend) {
                        this.selectedKendaraan = kend;
                        this.nopol = kend.no_polisi;
                        this.jenisKendaraan = kend.jenis_kendaraan;
                        this.jenisBbm = kend.jenis_bbm;

                        const normBbm = this.normalizeBbmKey(kend.jenis_bbm);
                        if (normBbm && this.stokTangkiData.hasOwnProperty(normBbm)) {
                            this.currentStok = parseFloat(this.stokTangkiData[normBbm]) || 0;
                        } else {
                            this.currentStok = 0;
                        }
                    } else {
                        this.selectedKendaraan = null;
                        this.nopol = '';
                        this.jenisKendaraan = '';
                        this.jenisBbm = '';
                        this.currentStok = null;
                    }
                    this.validateJumlah();
                    this.$nextTick(() => {
                        const kSelect = document.getElementById('kendaraan_select');
                        if (kSelect) kSelect.dispatchEvent(new Event('custom-select:refresh'));
                    });
                },

                validateJumlah() {
                    const val = parseFloat(this.jumlahBon) || 0;
                    if (this.currentStok !== null && val > this.currentStok) {
                        this.stokError = true;
                        this.stokErrorText = 'Melebihi stok tangki! Maks: ' + Math.floor(this.currentStok) + ' L';
                    } else {
                        this.stokError = false;
                        this.stokErrorText = '';
                    }
                },

                get isSubmitDisabled() {
                    return !this.satkerId || !this.kendaraanId || this.stokError || (parseFloat(this.jumlahBon) || 0) <= 0;
                }
            };
        }
    </script>
</x-app-layout>
