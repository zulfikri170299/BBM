<x-app-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6">
        <!-- Page Title -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 border-b-4 border-indigo-600 pb-2 inline-block">
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
                class="lg:col-span-1 bg-white rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50 p-6 sm:p-8 relative overflow-hidden group">
                <!-- Decorative background -->
                <div
                    class="absolute -top-12 -right-12 w-32 h-32 bg-indigo-50 rounded-full blur-3xl group-hover:bg-indigo-100 transition-colors duration-500">
                </div>

                <div class="relative">
                    <div class="flex items-center gap-4 mb-8">
                        <div
                            class="p-3 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl text-white shadow-lg shadow-indigo-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Catat Hutang Baru</h2>
                            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Input Data Hutang
                                Satker</p>
                        </div>
                    </div>

                    <form action="{{ route('petugas.hutang.store') }}" method="POST" class="space-y-6" id="debtForm">
                        @csrf

                        <!-- Satker Selection -->
                        <div class="space-y-2">
                            <x-input-label for="satker_id" value="Pilih Satker Unit"
                                class="text-slate-700 font-bold ml-1" />
                            <div class="relative group/input">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within/input:text-indigo-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <select id="satker_id" name="satker_id"
                                    class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-2xl shadow-sm transition-all duration-300 font-medium text-slate-700"
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
                                class="text-slate-700 font-bold ml-1" />
                            <div class="relative group/input">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within/input:text-indigo-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </div>
                                <select id="kendaraan_select"
                                    class="block w-full pl-11 pr-10 py-3.5 bg-slate-50 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-2xl shadow-sm transition-all duration-300 disabled:bg-slate-100 disabled:cursor-not-allowed font-medium text-slate-700 appearance-none"
                                    required disabled>
                                    <option value="">-- Pilih Satker Dahulu --</option>
                                </select>
                                <div id="vehicle_loader"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none hidden">
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

                        <!-- Selected Vehicle Card (Dynamic) -->
                        <div id="vehicle_info_card"
                            class="bg-gradient-to-br from-slate-900 to-indigo-900 rounded-2xl p-4 text-white shadow-lg space-y-3 hidden animate-in fade-in slide-in-from-top-4 duration-300">
                            <div class="flex justify-between items-start">
                                <span
                                    class="px-2 py-0.5 bg-white/20 rounded-md text-[10px] font-bold uppercase tracking-widest text-white/80">Info
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

                        <!-- Hidden fields -->
                        <input type="hidden" id="jenis_kendaraan" name="jenis_kendaraan" required>
                        <input type="hidden" id="nopol" name="nopol" required>
                        <input type="hidden" id="jenis_bbm" name="jenis_bbm" required>

                        <!-- Tanggal Bon Input -->
                        <div class="space-y-2">
                            <x-input-label for="tanggal_bon" value="Tanggal Bon"
                                class="text-slate-700 font-bold ml-1" />
                            <div class="relative group/input">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within/input:text-indigo-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <x-text-input id="tanggal_bon" name="tanggal_bon" type="date"
                                    class="block w-full pl-11 bg-slate-50 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-2xl py-3.5 transition-all duration-300 font-bold text-slate-700"
                                    value="{{ date('Y-m-d') }}" required />
                            </div>
                        </div>

                        <!-- Nama Driver (Manual Input) -->
                        <div class="space-y-2">
                            <x-input-label for="nama_driver" value="Nama Driver (Manual)"
                                class="text-slate-700 font-bold ml-1" />
                            <div class="relative group/input">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within/input:text-indigo-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <x-text-input id="nama_driver" name="nama_driver" type="text"
                                    class="block w-full pl-11 bg-slate-50 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-2xl py-3.5 transition-all duration-300 font-bold text-slate-700"
                                    placeholder="Masukkan Nama Driver..." required />
                            </div>
                        </div>

                        <!-- Amount Input -->
                        <div class="space-y-2">
                            <x-input-label for="jumlah_bon" value="Jumlah Bon (Liter)"
                                class="text-slate-700 font-bold ml-1" />
                            <div class="relative group/input">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within/input:text-indigo-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                </div>
                                <x-text-input id="jumlah_bon" type="number" step="0.1" min="0.1" name="jumlah_bon"
                                    class="block w-full pl-11 bg-slate-50 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-2xl py-3.5 transition-all duration-300 font-bold text-slate-700 text-lg"
                                    placeholder="0.0" required />
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <span class="text-slate-400 font-bold text-sm">LTR</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                class="w-full inline-flex justify-center items-center px-6 py-4 bg-gradient-to-r from-indigo-600 to-blue-600 border-none rounded-2xl font-black text-white uppercase tracking-widest hover:from-indigo-700 hover:to-blue-700 active:scale-[0.98] transition-all duration-200 shadow-xl shadow-indigo-200 group/btn">
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
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 overflow-hidden">
                <div class="space-y-6 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-slate-100 rounded-lg text-slate-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-slate-800">Riwayat Hutang BBM</h2>
                        </div>

                        <!-- Summary Outstanding per BBM -->
                        <div class="flex flex-wrap gap-2">
                            @foreach($summaryHutang as $bbm => $total)
                                <div
                                    class="px-3 py-1.5 bg-rose-50 border border-rose-100 rounded-xl flex items-center gap-2">
                                    <span
                                        class="text-[10px] font-black text-rose-400 uppercase tracking-widest">{{ $bbm }}</span>
                                    <span class="text-sm font-black text-rose-600">{{ $total }} L</span>
                                </div>
                            @endforeach
                            @if($summaryHutang->isEmpty())
                                <div
                                    class="px-3 py-1.5 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center gap-2">
                                    <span class="text-sm font-bold text-emerald-600 italic">Data Kosong</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Filter Form -->
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                        <form action="{{ route('petugas.hutang.index') }}" method="GET"
                            class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="filter_satker" value="Filter Satker"
                                    class="text-xs font-bold text-slate-600 mb-1" />
                                <select name="satker_id" id="filter_satker"
                                    class="block w-full text-xs border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl bg-white shadow-sm transition-all">
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
                                    class="text-xs font-bold text-slate-600 mb-1" />
                                <select name="status" id="filter_status"
                                    class="block w-full text-xs border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl bg-white shadow-sm transition-all">
                                    <option value="">Semua Status</option>
                                    <option value="belum_dibayar" {{ request('status') === 'belum_dibayar' ? 'selected' : '' }}>
                                        BELUM BAYAR</option>
                                    <option value="sudah_dibayar" {{ request('status') === 'sudah_dibayar' ? 'selected' : '' }}>
                                        LUNAS</option>
                                </select>
                            </div>
                            <div class="flex items-end gap-2 text-xs">
                                <button type="submit"
                                    class="flex-1 px-4 py-2 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 active:scale-95">
                                    Terapkan
                                </button>
                                @if(request()->hasAny(['satker_id', 'status']))
                                    <a href="{{ route('petugas.hutang.index') }}"
                                        class="px-4 py-2 bg-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-300 transition active:scale-95">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-xl border border-slate-200/60">
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Daftar Rekapan Hutang</h3>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] uppercase font-bold text-slate-400">Tampilkan</span>
                            <form action="{{ route('petugas.hutang.index') }}" method="GET" class="inline">
                                @foreach(request()->except('per_page') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <select name="per_page" onchange="this.form.submit()"
                                    class="block border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-xs py-1.5 font-bold text-slate-700">
                                    <option value="15" {{ request('per_page') == 15 || !request('per_page') ? 'selected' : '' }}>15</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </form>
                        </div>
                    </div>
                    <table class="w-full text-sm text-left whitespace-nowrap">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-50/80 border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-4 font-bold tracking-wider">Tanggal</th>
                                <th class="px-4 py-4 font-bold tracking-wider">Satker</th>
                                <th class="px-4 py-4 font-bold tracking-wider">Kendaraan</th>
                                <th class="px-4 py-4 font-bold tracking-wider">Driver</th>
                                <th class="px-4 py-4 font-bold tracking-wider">Jumlah Bon</th>
                                <th class="px-4 py-4 font-bold tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($hutangs as $hutang)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-slate-900">
                                            @if($hutang->tanggal_bon)
                                                {{ \Carbon\Carbon::parse($hutang->tanggal_bon)->translatedFormat('d F Y') }}
                                            @else
                                                {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->translatedFormat('d F Y') }}
                                            @endif
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            @if($hutang->tanggal_bon)
                                                {{-- For manual date, time might not be relevant or default to midnight --}}
                                                Catatan Manual
                                            @else
                                                {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->format('H:i') }}
                                                WITA
                                            @endif
                                        </p>
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-slate-800">{{ $hutang->satker->nama_satker }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-bold text-slate-900">{{ $hutang->nopol }}</p>
                                        <p class="text-xs text-slate-500">{{ $hutang->jenis_kendaraan }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-slate-800">{{ $hutang->nama_driver ?? '-' }}</div>
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
                                                <span class="text-[10px] text-slate-500">Oleh:
                                                    {{ $hutang->adminBayar->name ?? '-' }}</span>
                                                <span
                                                    class="text-[10px] text-slate-500">{{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->translatedFormat('d M Y H:i') }}
                                                    WITA</span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-500 italic">Belum ada data
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
        document.addEventListener('DOMContentLoaded', function () {
            const satkerSelect = document.getElementById('satker_id');
            const kendaraanSelect = document.getElementById('kendaraan_select');
            const inputJenisKendaraan = document.getElementById('jenis_kendaraan');
            const inputNopol = document.getElementById('nopol');
            const inputJenisBbm = document.getElementById('jenis_bbm');

            // New UI Elements
            const vehicleLoader = document.getElementById('vehicle_loader');
            const vehicleInfoCard = document.getElementById('vehicle_info_card');
            const cardNopol = document.getElementById('card_nopol');
            const cardBbm = document.getElementById('card_bbm');

            if (satkerSelect) {
                satkerSelect.addEventListener('change', function () {
                    const satkerId = this.value;

                    // Reset and show loader
                    kendaraanSelect.innerHTML = '<option value="">Memuat kendaraan...</option>';
                    kendaraanSelect.disabled = true;
                    vehicleLoader.classList.remove('hidden');

                    // Hide info card
                    vehicleInfoCard.classList.add('hidden');

                    inputJenisKendaraan.value = '';
                    inputNopol.value = '';
                    inputJenisBbm.value = '';

                    if (satkerId) {
                        fetch(`{{ route('petugas.hutang.get-kendaraan') }}?satker_id=${satkerId}`)
                            .then(response => response.json())
                            .then(data => {
                                vehicleLoader.classList.add('hidden');
                                kendaraanSelect.innerHTML = '<option value="">-- Pilih Kendaraan --</option>';

                                if (data.length > 0) {
                                    data.forEach(kendaraan => {
                                        const option = document.createElement('option');
                                        option.value = kendaraan.id;
                                        option.dataset.nopol = kendaraan.no_polisi;
                                        option.dataset.jenis = kendaraan.jenis_kendaraan;
                                        option.dataset.bbm = kendaraan.jenis_bbm;
                                        option.textContent = `${kendaraan.no_polisi} - ${kendaraan.jenis_kendaraan}`;
                                        kendaraanSelect.appendChild(option);
                                    });
                                    kendaraanSelect.disabled = false;
                                } else {
                                    kendaraanSelect.innerHTML = '<option value="">Tidak ada kendaraan terdaftar</option>';
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching kendaraan:', error);
                                vehicleLoader.classList.add('hidden');
                                kendaraanSelect.innerHTML = '<option value="">Gagal memuat data</option>';
                            });
                    } else {
                        vehicleLoader.classList.add('hidden');
                        kendaraanSelect.innerHTML = '<option value="">-- Pilih Satker Terlebih Dahulu --</option>';
                    }
                });
            }

            if (kendaraanSelect) {
                kendaraanSelect.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption.value) {
                        const nopol = selectedOption.dataset.nopol;
                        const bbm = selectedOption.dataset.bbm;
                        const jenis = selectedOption.dataset.jenis;

                        inputNopol.value = nopol;
                        inputJenisKendaraan.value = jenis;
                        inputJenisBbm.value = bbm;

                        // Show Info Card
                        cardNopol.textContent = nopol;
                        cardBbm.textContent = bbm;
                        vehicleInfoCard.classList.remove('hidden');
                    } else {
                        inputNopol.value = '';
                        inputJenisKendaraan.value = '';
                        inputJenisBbm.value = '';
                        vehicleInfoCard.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</x-app-layout>
