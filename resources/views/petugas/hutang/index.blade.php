<x-app-layout>
    <div class="max-w-7xl mx-auto p-2 sm:p-6 lg:p-8 space-y-6 px-2 sm:px-6 lg:px-8">
        <!-- Page Title -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h1 class="text-2xl sm:text-2xl font-bold text-white border-b-4 border-indigo-600 pb-2 inline-block">
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
                class="lg:col-span-1 bg-slate-900 border border-white/5 rounded-3xl border border-white/10 shadow-xl shadow-indigo-500/10 p-6 sm:p-8 relative overflow-hidden group">
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
                            <h2 class="text-xl font-extrabold text-slate-200 tracking-tight">Catat Hutang Baru</h2>
                            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Input Data Hutang
                                Satker</p>
                        </div>
                    </div>

                    <form action="{{ route('petugas.hutang.store') }}" method="POST" class="space-y-6" id="debtForm">
                        @csrf

                        <!-- Satker Selection -->
                        <div class="space-y-2">
                            <x-input-label for="satker_id" value="Pilih Satker Unit"
                                class="text-slate-300 font-bold ml-1" />
                            <div class="relative">
                                <select id="satker_id" name="satker_id"
                                    class="tom-select w-full duration-300"
                                    required>
                                    <option value="">-- Pilih Satker --</option>
                                    @foreach($satkers as $sat)
                                        <option value="{{ $sat->id }}">{{ $sat->nama_satker }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Vehicle Selection -->
                        <div class="space-y-2">
                            <x-input-label for="kendaraan_select" value="Pilih Kendaraan"
                                class="text-slate-300 font-bold ml-1" />
                            <div class="relative">
                                <select id="kendaraan_select"
                                    class="tom-select w-full duration-300 disabled:bg-slate-800 disabled:cursor-not-allowed"
                                    required disabled>
                                    <option value="">-- Pilih Satker Dahulu --</option>
                                </select>
                                <div id="vehicle_loader"
                                    class="absolute inset-y-0 right-0 pr-10 flex items-center pointer-events-none hidden z-10">
                                    <svg class="animate-spin h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Card Info Kendaraan --}}
                        <div id="vehicle_info_card"
                            class="bg-gradient-to-br from-slate-900 to-indigo-900 rounded-2xl p-4 text-white shadow-lg space-y-3 hidden animate-in fade-in slide-in-from-top-4 duration-300">
                            <div class="flex justify-between items-start">
                                <span
                                    class="px-2 py-0.5 bg-slate-900 border border-white/5/20 rounded-md text-[10px] font-bold uppercase tracking-widest text-white/80">Info
                                    Kendaraan</span>
                                <svg class="w-5 h-5 text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                    <path
                                        d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7h-3v3h3V7z" />
                                </svg>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[10px] text-indigo-300 font-bold uppercase">No. Polisi</p>
                                    <p id="card_nopol" class="text-sm font-black tracking-tight">-</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-indigo-300 font-bold uppercase">Jenis BBM</p>
                                    <p id="card_bbm" class="text-sm font-bold">-</p>
                                </div>
                            </div>
                        </div>

                        {{-- Panel Stok Tangki --}}
                        <div id="stok_tangki_card" class="hidden rounded-2xl border p-4 space-y-1 transition-all duration-300">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Stok BBM di Tangki</p>
                            <div class="flex items-end justify-between">
                                <div>
                                    <span id="stok_tangki_jenis" class="text-xs font-bold text-slate-400">-</span>
                                    <p id="stok_tangki_nilai" class="text-2xl font-black text-slate-200">0 <span class="text-sm font-bold">L</span></p>
                                </div>
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Hidden fields -->
                        <input type="hidden" id="jenis_kendaraan" name="jenis_kendaraan" required>
                        <input type="hidden" id="nopol" name="nopol" required>
                        <input type="hidden" id="jenis_bbm" name="jenis_bbm" required>

                        <!-- Tanggal Bon Input -->
                        <div class="space-y-2">
                            <x-input-label for="tanggal_bon" value="Tanggal Bon"
                                class="text-slate-300 font-bold ml-1" />
                            <div class="relative group/input">
                                <x-text-input id="tanggal_bon" name="tanggal_bon" type="date"
                                    class="flatpickr block w-full bg-slate-800/50 border-white/10 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-2xl py-3.5 transition-all duration-300 font-bold text-slate-300"
                                    value="{{ date('Y-m-d') }}" required />
                            </div>
                        </div>

                        <!-- Nama Driver (Manual Input) -->
                        <div class="space-y-2">
                            <x-input-label for="nama_driver" value="Nama Driver (Manual)"
                                class="text-slate-300 font-bold ml-1" />
                            <div class="relative group/input">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within/input:text-indigo-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <x-text-input id="nama_driver" name="nama_driver" type="text"
                                    class="block w-full pl-11 bg-slate-800/50 border-white/10 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-2xl py-3.5 transition-all duration-300 font-bold text-slate-300"
                                    placeholder="Masukkan Nama Driver..." required />
                            </div>
                        </div>

                        <!-- Amount Input -->
                        <div class="space-y-2">
                            <x-input-label for="jumlah_bon" value="Jumlah Bon (Liter)"
                                class="text-slate-300 font-bold ml-1" />
                            <div class="relative group/input">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within/input:text-indigo-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                </div>
                                <x-text-input id="jumlah_bon" type="number" step="1" min="1" name="jumlah_bon"
                                    class="block w-full pl-11 bg-slate-800/50 border-white/10 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-2xl py-3.5 transition-all duration-300 font-bold text-slate-300 text-lg"
                                    placeholder="0.0" required />
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <span class="text-slate-400 font-bold text-sm">LTR</span>
                                </div>
                            </div>
                            {{-- Pesan error stok --}}
                            <div id="stok_error_msg" class="hidden flex items-center gap-2 mt-1 px-3 py-2 bg-rose-50 border border-rose-200 rounded-xl">
                                <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                </svg>
                                <span id="stok_error_text" class="text-xs font-bold text-rose-600"></span>
                            </div>
                            @error('jumlah_bon')
                                <p class="text-xs text-rose-600 font-bold mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2">
                            <button type="submit"
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
            <div class="lg:col-span-2 bg-slate-900 border border-white/5 rounded-2xl border border-white/10 shadow-sm p-6 overflow-hidden">
                <div class="space-y-6 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-slate-800 rounded-lg text-slate-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-slate-200">Riwayat Hutang BBM</h2>
                        </div>

                        <!-- Summary Outstanding per BBM -->
                        <div class="flex flex-wrap gap-2">
                            @foreach($summaryHutang as $bbm => $total)
                                <div
                                    class="px-3 py-1.5 bg-rose-50 border border-rose-100 rounded-xl flex items-center gap-2">
                                    <span
                                        class="text-[10px] font-black text-rose-400 uppercase tracking-widest">{{ $bbm }}</span>
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
                    <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-4">
                        <form action="{{ route('petugas.hutang.index') }}" method="GET"
                            class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="filter_satker" value="Filter Satker"
                                    class="text-xs font-bold text-slate-400 mb-1" />
                                <select name="satker_id" id="filter_satker"
                                    class="tom-select w-full">
                                    <option value="">Semua Satker</option>
                                    @foreach($satkers as $sat)
                                        <option value="{{ $sat->id }}" {{ request('satker_id') == $sat->id ? 'selected' : '' }}>
                                            {{ $sat->nama_satker }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="filter_status" value="Filter Status"
                                    class="text-xs font-bold text-slate-400 mb-1" />
                                <select name="status" id="filter_status"
                                    class="tom-select w-full">
                                    <option value="">Semua Status</option>
                                    <option value="belum_dibayar" {{ request('status') === 'belum_dibayar' ? 'selected' : '' }}>
                                        BELUM BAYAR</option>
                                    <option value="sudah_dibayar" {{ request('status') === 'sudah_dibayar' ? 'selected' : '' }}>
                                        LUNAS</option>
                                </select>
                            </div>
                            <div class="flex items-end gap-2 text-xs">
                                <button type="submit"
                                    class="flex-1 px-4 py-2 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/20 active:scale-95">
                                    Terapkan
                                </button>
                                @if(request()->hasAny(['satker_id', 'status']))
                                    <a href="{{ route('petugas.hutang.index') }}"
                                        class="px-4 py-2 bg-slate-200 text-slate-300 font-bold rounded-xl hover:bg-slate-300 transition active:scale-95">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-xl border border-white/10/60">
                    <div class="px-4 py-3 border-b border-white/5 flex justify-between items-center bg-slate-800/50">
                        <h3 class="text-sm font-bold text-slate-300 uppercase tracking-wider">Daftar Rekapan Hutang</h3>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] uppercase font-bold text-slate-400">Tampilkan</span>
                            <form action="{{ route('petugas.hutang.index') }}" method="GET" class="inline">
                                @foreach(request()->except('per_page') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <select name="per_page" onchange="this.form.submit()"
                                    class="block border-white/10 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-xs py-1.5 font-bold text-slate-300">
                                    <option value="15" {{ request('per_page') == 15 || !request('per_page') ? 'selected' : '' }}>15</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </form>
                        </div>
                    </div>
                    <table class="w-full text-sm text-left whitespace-nowrap">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-800/50/80 border-b border-white/10">
                            <tr>
                                <th class="px-4 py-4 font-bold tracking-wider">Tanggal</th>
                                <th class="px-4 py-4 font-bold tracking-wider">Satker</th>
                                <th class="px-4 py-4 font-bold tracking-wider">Kendaraan</th>
                                <th class="px-4 py-4 font-bold tracking-wider">Driver</th>
                                <th class="px-4 py-4 font-bold tracking-wider">Jumlah Bon</th>
                                <th class="px-4 py-4 font-bold tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse ($hutangs as $hutang)
                                <tr class="hover:bg-slate-800/50 transition-colors">
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-white">
                                            @if($hutang->tanggal_bon)
                                                {{ \Carbon\Carbon::parse($hutang->tanggal_bon)->translatedFormat('d F Y') }}
                                            @else
                                                {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->translatedFormat('d F Y') }}
                                            @endif
                                        </p>
                                        <p class="text-xs text-slate-400">
                                            @if($hutang->tanggal_bon)
                                                {{-- For manual date, time might not be relevant or default to midnight --}}
                                                Catatan Manual
                                            @else
                                                {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->format('H:i') }}
                                                WITA
                                            @endif
                                        </p>
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-slate-200">{{ $hutang->satker->nama_satker }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-bold text-white">{{ $hutang->nopol }}</p>
                                        <p class="text-xs text-slate-400">{{ $hutang->jenis_kendaraan }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-slate-200">{{ $hutang->nama_driver ?? '-' }}</div>
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
                                                <span class="text-[10px] text-slate-400">Oleh:
                                                    {{ $hutang->adminBayar->name ?? '-' }}</span>
                                                <span
                                                    class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->translatedFormat('d M Y H:i') }}
                                                    WITA</span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-400 italic">Belum ada data
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
        (function() {
            const stokTangkiData = @json($stokTangki);

            function initHutangForm() {
                const satkerSelect = document.getElementById('satker_id');
                const kendaraanSelect = document.getElementById('kendaraan_select');
                const inputJenisKendaraan = document.getElementById('jenis_kendaraan');
                const inputNopol = document.getElementById('nopol');
                const inputJenisBbm = document.getElementById('jenis_bbm');
                const jumlahBonInput = document.getElementById('jumlah_bon');
                const submitBtn = document.querySelector('#debtForm button[type="submit"]');

                if (!satkerSelect || !kendaraanSelect) return;

                const vehicleLoader = document.getElementById('vehicle_loader');
                const vehicleInfoCard = document.getElementById('vehicle_info_card');
                const cardNopol = document.getElementById('card_nopol');
                const cardBbm = document.getElementById('card_bbm');
                const stokTangkiCard = document.getElementById('stok_tangki_card');
                const stokTangkiJenis = document.getElementById('stok_tangki_jenis');
                const stokTangkiNilai = document.getElementById('stok_tangki_nilai');
                const stokErrorMsg = document.getElementById('stok_error_msg');
                const stokErrorText = document.getElementById('stok_error_text');

                let currentStok = null;

                function normalizeBbmKey(bbm) {
                    if (!bbm) return '';
                    const upper = bbm.toUpperCase();
                    if (upper.includes('DEX')) return 'Pertamina Dex';
                    if (upper.includes('PERTAMAX')) return 'Pertamax';
                    return bbm;
                }

                function updateStokDisplay(jenisBbm) {
                    const normBbm = normalizeBbmKey(jenisBbm);
                    if (normBbm && stokTangkiData.hasOwnProperty(normBbm)) {
                        currentStok = parseFloat(stokTangkiData[normBbm]);
                    } else {
                        currentStok = 0;
                    }
                    if (stokTangkiJenis) stokTangkiJenis.textContent = jenisBbm;
                    if (stokTangkiNilai) stokTangkiNilai.innerHTML = '<span class="text-2xl font-black">' + currentStok.toLocaleString('id-ID', {minimumFractionDigits:0, maximumFractionDigits:1}) + '</span> <span class="text-sm font-bold">L</span>';

                    // Warna card berdasarkan stok
                    if (stokTangkiCard) {
                        stokTangkiCard.classList.remove('hidden', 'border-rose-200', 'bg-rose-50', 'border-amber-200', 'bg-amber-50', 'border-emerald-200', 'bg-emerald-50');
                        if (stokTangkiNilai) stokTangkiNilai.classList.remove('text-rose-600', 'text-amber-600', 'text-emerald-600', 'text-slate-200');
                        if (currentStok <= 0) {
                            stokTangkiCard.classList.add('border-rose-200', 'bg-rose-50');
                            if (stokTangkiNilai) stokTangkiNilai.classList.add('text-rose-600');
                        } else if (currentStok < 100) {
                            stokTangkiCard.classList.add('border-amber-200', 'bg-amber-50');
                            if (stokTangkiNilai) stokTangkiNilai.classList.add('text-amber-600');
                        } else {
                            stokTangkiCard.classList.add('border-emerald-200', 'bg-emerald-50');
                            if (stokTangkiNilai) stokTangkiNilai.classList.add('text-emerald-600');
                        }
                    }
                    validateJumlah();
                }

                function validateJumlah() {
                    const jumlah = parseFloat(jumlahBonInput ? jumlahBonInput.value : 0) || 0;
                    if (currentStok !== null && jumlah > currentStok) {
                        if (stokErrorMsg) stokErrorMsg.classList.remove('hidden');
                        if (stokErrorText) stokErrorText.textContent = 'Melebihi stok tangki! Maks: ' + currentStok.toFixed(0) + ' L';
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        }
                    } else {
                        if (stokErrorMsg) stokErrorMsg.classList.add('hidden');
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    }
                }

                if (jumlahBonInput) {
                    jumlahBonInput.addEventListener('input', validateJumlah);
                }

                // === Fetch kendaraan saat satker berubah ===
                function fetchKendaraanData(satkerId) {
                    let ts = kendaraanSelect.tomselect;
                    if (!ts && window.initTomSelect) {
                        window.initTomSelect();
                        ts = kendaraanSelect.tomselect;
                    }

                    // Reset semua field
                    if (inputJenisKendaraan) inputJenisKendaraan.value = '';
                    if (inputNopol) inputNopol.value = '';
                    if (inputJenisBbm) inputJenisBbm.value = '';
                    if (vehicleInfoCard) vehicleInfoCard.classList.add('hidden');
                    if (stokTangkiCard) stokTangkiCard.classList.add('hidden');
                    currentStok = null;
                    if (stokErrorMsg) stokErrorMsg.classList.add('hidden');

                    if (ts) {
                        ts.clear();
                        ts.clearOptions();
                        ts.disable();
                        if (vehicleLoader) vehicleLoader.classList.remove('hidden');
                        ts.addOption({value: '', text: 'Memuat kendaraan...'});
                        ts.refreshOptions(false);
                    }

                    if (satkerId) {
                        fetch('{{ route("petugas.hutang.get-kendaraan") }}?satker_id=' + satkerId)
                            .then(function(response) { return response.json(); })
                            .then(function(data) {
                                if (vehicleLoader) vehicleLoader.classList.add('hidden');
                                if (ts) {
                                    ts.clearOptions();
                                    ts.addOption({value: '', text: '-- Pilih Kendaraan --'});
                                    if (data.length > 0) {
                                        data.forEach(function(kendaraan) {
                                            ts.addOption({
                                                value: kendaraan.id,
                                                text: kendaraan.no_polisi + ' - ' + kendaraan.jenis_kendaraan,
                                                nopol: kendaraan.no_polisi,
                                                jenis: kendaraan.jenis_kendaraan,
                                                bbm: kendaraan.jenis_bbm
                                            });
                                        });
                                        ts.enable();
                                    } else {
                                        ts.addOption({value: '', text: 'Tidak ada kendaraan tersedia'});
                                        ts.disable();
                                    }
                                    ts.refreshOptions(false);
                                }
                            })
                            .catch(function(error) {
                                console.error('Error fetching kendaraan:', error);
                                if (vehicleLoader) vehicleLoader.classList.add('hidden');
                                if (ts) {
                                    ts.clearOptions();
                                    ts.addOption({value: '', text: 'Gagal memuat data'});
                                    ts.disable();
                                    ts.refreshOptions(false);
                                }
                            });
                    } else {
                        if (vehicleLoader) vehicleLoader.classList.add('hidden');
                        if (ts) {
                            ts.clearOptions();
                            ts.addOption({value: '', text: '-- Pilih Satker Terlebih Dahulu --'});
                            ts.disable();
                            ts.refreshOptions(false);
                        }
                    }
                }

                // === Proses saat kendaraan dipilih ===
                function processSelectedKendaraan(selectedValue) {
                    if (stokErrorMsg) stokErrorMsg.classList.add('hidden');
                    const ts = kendaraanSelect.tomselect;
                    if (!ts) return;
                    const optionData = ts.options[selectedValue];
                    if (selectedValue && optionData) {
                        if (inputNopol) inputNopol.value = optionData.nopol;
                        if (inputJenisKendaraan) inputJenisKendaraan.value = optionData.jenis;
                        if (inputJenisBbm) inputJenisBbm.value = optionData.bbm;
                        if (cardNopol) cardNopol.textContent = optionData.nopol;
                        if (cardBbm) cardBbm.textContent = optionData.bbm;
                        if (vehicleInfoCard) vehicleInfoCard.classList.remove('hidden');
                        updateStokDisplay(optionData.bbm);
                    } else {
                        if (inputNopol) inputNopol.value = '';
                        if (inputJenisKendaraan) inputJenisKendaraan.value = '';
                        if (inputJenisBbm) inputJenisBbm.value = '';
                        if (vehicleInfoCard) vehicleInfoCard.classList.add('hidden');
                        if (stokTangkiCard) stokTangkiCard.classList.add('hidden');
                        currentStok = null;
                    }
                }

                // === Bind events ===
                // Pastikan TomSelect sudah terinisialisasi
                if (!satkerSelect.tomselect && window.initTomSelect) {
                    window.initTomSelect();
                }

                // Bind satker change
                if (satkerSelect.tomselect) {
                    satkerSelect.tomselect.on('change', function(value) {
                        fetchKendaraanData(value);
                    });
                } else {
                    satkerSelect.addEventListener('change', function() {
                        fetchKendaraanData(this.value);
                    });
                }

                // Bind kendaraan change
                if (!kendaraanSelect.tomselect && window.initTomSelect) {
                    window.initTomSelect();
                }
                if (kendaraanSelect.tomselect) {
                    kendaraanSelect.tomselect.on('change', function(value) {
                        processSelectedKendaraan(value);
                    });
                } else {
                    kendaraanSelect.addEventListener('change', function() {
                        processSelectedKendaraan(this.value);
                    });
                }
            }

            // Polling untuk memastikan TomSelect sudah diinisialisasi oleh app.blade.php
            // Karena ini adalah SPA Turbo Drive, timing sangat krusial.
            const maxWaitMs = 5000;
            const intervalMs = 50;
            let elapsed = 0;

            const waitForTomSelect = setInterval(function() {
                const satker = document.getElementById('satker_id');
                elapsed += intervalMs;

                // Jika elemen satker sudah memiliki properti .tomselect, artinya library
                // sudah dimuat dan di-bind oleh global handler di app.blade.php
                if (satker && satker.tomselect) {
                    clearInterval(waitForTomSelect);
                    initHutangForm();
                } else if (elapsed > maxWaitMs) {
                    // Timeout fallback - coba bind secara paksa atau beri fallback
                    clearInterval(waitForTomSelect);
                    if (window.initTomSelect) window.initTomSelect();
                    initHutangForm();
                    console.warn("Hutang BBM Form: TomSelect initialization timeout, forcing init.");
                }
            }, intervalMs);
        })();
    </script>
</x-app-layout>
