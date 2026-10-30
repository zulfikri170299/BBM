<x-app-layout>
    <div class="p-4 lg:p-6 space-y-5 sm:space-y-6">

        {{-- Top Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-5 rounded-2xl sm:rounded-3xl shadow-sm">
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-cyan-500 text-white flex items-center justify-center shadow-lg shadow-indigo-500/25 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white tracking-tight">Transfer Saldo BBM</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20">
                            Unit Transfer
                        </span>
                    </div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-0.5">
                        Alokasi dan perpindahan kuota BBM antar armada dinas, stok pusat, dan personel
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.laporan-transfer-saldo.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700/80 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition-all border border-slate-200 dark:border-white/10 active:scale-95 shadow-sm">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Laporan Transfer</span>
                </a>
            </div>
        </div>

        {{-- KPI Summary Stats Cards --}}
        @php
            $stokPertamax = $adminStocks->where('jenis_bbm', 'Pertamax')->first()->saldo ?? 0;
            $stokDex = $adminStocks->where('jenis_bbm', 'Pertamina Dex')->first()->saldo ?? 0;
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-4">
            {{-- Card 1: Stok Pusat Pertamax --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-900 text-white rounded-2xl p-4 sm:p-5 shadow-lg shadow-blue-500/15 border border-blue-400/20 group hover:shadow-xl transition-all duration-300">
                <div class="absolute -right-4 -bottom-4 w-28 h-28 bg-white/5 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500"></div>
                <div class="flex items-center justify-between">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-white/15 text-[10px] font-extrabold uppercase tracking-widest text-blue-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-300 animate-pulse"></span>
                            Stok Pusat
                        </span>
                        <h4 class="text-xs font-bold text-blue-100/80 mt-1">Pertamax</h4>
                        <div class="flex items-baseline gap-1 mt-1.5">
                            <span class="text-2xl sm:text-3xl font-black tracking-tight">{{ number_format($stokPertamax, 0, ',', '.') }}</span>
                            <span class="text-xs font-bold text-blue-200">Liter</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center shrink-0 shadow-inner">
                        <svg class="w-6 h-6 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Card 2: Stok Pusat Pertamina Dex --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-emerald-600 via-teal-700 to-slate-900 text-white rounded-2xl p-4 sm:p-5 shadow-lg shadow-emerald-500/15 border border-emerald-400/20 group hover:shadow-xl transition-all duration-300">
                <div class="absolute -right-4 -bottom-4 w-28 h-28 bg-white/5 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500"></div>
                <div class="flex items-center justify-between">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-white/15 text-[10px] font-extrabold uppercase tracking-widest text-emerald-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                            Stok Pusat
                        </span>
                        <h4 class="text-xs font-bold text-emerald-100/80 mt-1">Pertamina Dex</h4>
                        <div class="flex items-baseline gap-1 mt-1.5">
                            <span class="text-2xl sm:text-3xl font-black tracking-tight">{{ number_format($stokDex, 0, ',', '.') }}</span>
                            <span class="text-xs font-bold text-emerald-200">Liter</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center shrink-0 shadow-inner">
                        <svg class="w-6 h-6 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Card 3: Total Aktivitas Transfer --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-indigo-700 via-purple-800 to-slate-950 text-white rounded-2xl p-4 sm:p-5 shadow-lg shadow-purple-500/15 border border-purple-400/20 group hover:shadow-xl transition-all duration-300 sm:col-span-2 lg:col-span-1">
                <div class="absolute -right-4 -bottom-4 w-28 h-28 bg-white/5 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500"></div>
                <div class="flex items-center justify-between">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-white/15 text-[10px] font-extrabold uppercase tracking-widest text-purple-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-purple-300"></span>
                            Log Transaksi
                        </span>
                        <h4 class="text-xs font-bold text-purple-100/80 mt-1">Total Riwayat Transfer</h4>
                        <div class="flex items-baseline gap-1 mt-1.5">
                            <span class="text-2xl sm:text-3xl font-black tracking-tight">{{ number_format($riwayat->total(), 0, ',', '.') }}</span>
                            <span class="text-xs font-bold text-purple-200">Log Tercatat</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center shrink-0 shadow-inner">
                        <svg class="w-6 h-6 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        @php
            $personelAccessControl = \App\Models\Setting::where('key', 'personel_access_control')->value('value') ?? '1';
        @endphp

        {{-- Main Two-Column Container --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6 items-start" x-data="{ tipeTujuan: 'kendaraan', selectedKendaraan: '', quickLiter: '' }">
            
            {{-- LEFT COLUMN: Transfer Action Form (5 cols) --}}
            <div class="lg:col-span-5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl sm:rounded-3xl shadow-sm overflow-hidden flex flex-col">
                {{-- Form Header --}}
                <div class="p-4 sm:px-5 sm:py-4 bg-gradient-to-r from-slate-50 via-indigo-50/30 to-slate-50 dark:from-slate-800/80 dark:via-indigo-950/20 dark:to-slate-800/80 border-b border-slate-200 dark:border-white/10 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-indigo-600/10 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider">Formulir Transfer</h3>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">Input alokasi & pemindahan saldo BBM</p>
                        </div>
                    </div>
                    @if($selectedSatkerId)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Satker Terpilih
                        </span>
                    @endif
                </div>

                {{-- Form Body --}}
                <div class="p-4 sm:p-5">
                    <form action="{{ route('admin.transfer-saldo.store') }}" method="POST" class="space-y-4">
                        @csrf

                        {{-- Step 1: Pilih Satker --}}
                        <div class="space-y-1.5">
                            <label class="flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-300">
                                <span class="flex items-center gap-1.5">
                                    <span class="w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-600 dark:text-indigo-400 text-[10px] font-black flex items-center justify-center">1</span>
                                    <span>Pilih Satuan Kerja</span>
                                </span>
                                @if($selectedSatkerId)
                                    <a href="{{ route('admin.transfer-saldo.index') }}" class="text-[10px] text-rose-500 hover:text-rose-600 font-bold hover:underline">Ganti Satker</a>
                                @endif
                            </label>
                            
                            <select name="satker_id" id="transfer_satker_id"
                                onchange="if(this.value) { typeof Turbo !== 'undefined' ? Turbo.visit('{{ route('admin.transfer-saldo.index') }}?satker_id=' + this.value) : window.location.href='{{ route('admin.transfer-saldo.index') }}?satker_id=' + this.value }"
                                class="tom-select w-full">
                                <option value="">-- Pilih Satuan Kerja --</option>
                                @foreach($satkers as $satker)
                                    <option value="{{ $satker->id }}" {{ $selectedSatkerId == $satker->id ? 'selected' : '' }}>
                                        {{ $satker->nama_satker }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Jika Satker Belum Dipilih --}}
                        @if(!$selectedSatkerId)
                            <div class="my-4 p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-dashed border-slate-200 dark:border-white/10 text-center space-y-2.5">
                                <div class="w-12 h-12 mx-auto rounded-2xl bg-indigo-500/10 dark:bg-indigo-500/20 text-indigo-500 dark:text-indigo-400 flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300">Pilih Satker Terlebih Dahulu</h4>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 max-w-xs mx-auto leading-relaxed">
                                    Silakan tentukan Satker di atas untuk memuat daftar armada kendaraan dan mulai memindahkan saldo kuota BBM.
                                </p>
                            </div>
                        @else
                            {{-- Step 2: Tipe Transfer --}}
                            <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-white/5">
                                <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300">
                                    <span class="w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-600 dark:text-indigo-400 text-[10px] font-black flex items-center justify-center">2</span>
                                    <span>Tipe / Skema Transfer</span>
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                    {{-- Option 1: Pusat -> Kendaraan --}}
                                    <label class="relative flex flex-col p-3 rounded-xl border-2 cursor-pointer transition-all duration-200"
                                        :class="tipeTujuan === 'kendaraan' 
                                            ? 'border-indigo-600 dark:border-indigo-500 bg-indigo-50/50 dark:bg-indigo-500/10 shadow-sm' 
                                            : 'border-slate-200 dark:border-white/10 hover:border-slate-300 dark:hover:border-white/20 bg-white dark:bg-slate-800/40'">
                                        <input type="radio" name="tipe_tujuan" value="kendaraan" x-model="tipeTujuan" class="sr-only">
                                        <div class="flex items-center justify-between mb-1.5">
                                            <div class="w-7 h-7 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                </svg>
                                            </div>
                                            <span class="w-4 h-4 rounded-full border-2 flex items-center justify-center"
                                                :class="tipeTujuan === 'kendaraan' ? 'border-indigo-600 dark:border-indigo-500 bg-indigo-600 dark:bg-indigo-500' : 'border-slate-300 dark:border-white/20'">
                                                <span class="w-1.5 h-1.5 rounded-full bg-white" x-show="tipeTujuan === 'kendaraan'"></span>
                                            </span>
                                        </div>
                                        <span class="text-xs font-black text-slate-800 dark:text-white">Pusat ke Mobil (TM)</span>
                                        <span class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">Top up transfer dari stok induk pusat</span>
                                    </label>

                                    @if($personelAccessControl == '1')
                                    {{-- Option 2: Kendaraan -> Personel --}}
                                    <label class="relative flex flex-col p-3 rounded-xl border-2 cursor-pointer transition-all duration-200"
                                        :class="tipeTujuan === 'personel' 
                                            ? 'border-indigo-600 dark:border-indigo-500 bg-indigo-50/50 dark:bg-indigo-500/10 shadow-sm' 
                                            : 'border-slate-200 dark:border-white/10 hover:border-slate-300 dark:hover:border-white/20 bg-white dark:bg-slate-800/40'">
                                        <input type="radio" name="tipe_tujuan" value="personel" x-model="tipeTujuan" class="sr-only">
                                        <div class="flex items-center justify-between mb-1.5">
                                            <div class="w-7 h-7 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <span class="w-4 h-4 rounded-full border-2 flex items-center justify-center"
                                                :class="tipeTujuan === 'personel' ? 'border-indigo-600 dark:border-indigo-500 bg-indigo-600 dark:bg-indigo-500' : 'border-slate-300 dark:border-white/20'">
                                                <span class="w-1.5 h-1.5 rounded-full bg-white" x-show="tipeTujuan === 'personel'"></span>
                                            </span>
                                        </div>
                                        <span class="text-xs font-black text-slate-800 dark:text-white">Mobil ke Personel</span>
                                        <span class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">Pindahkan kuota dari mobil ke anggota</span>
                                    </label>
                                    @endif
                                </div>
                            </div>

                            {{-- Form Fields: Jika Skema Pusat -> Kendaraan --}}
                            <div x-show="tipeTujuan === 'kendaraan'" x-transition class="space-y-3.5 pt-2 border-t border-slate-100 dark:border-white/5">
                                <div>
                                    <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                        <span class="w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-600 dark:text-indigo-400 text-[10px] font-black flex items-center justify-center">3</span>
                                        <span>Kendaraan Tujuan (Penerima)</span>
                                    </label>
                                    <select name="tujuan_kendaraan_id" id="transfer_tujuan_kendaraan_id" :required="tipeTujuan === 'kendaraan'" class="w-full" x-ref="tujuanKendaraanSelect">
                                        <option value="">-- Pilih Kendaraan Penerima --</option>
                                        @foreach($kendaraans as $k)
                                            <option value="{{ $k->id }}">
                                                {{ $k->no_polisi }} • {{ $k->jenis_bbm }} (Saldo: {{ rtrim(rtrim(number_format($k->saldo, 2, ',', '.'), '0'), ',') }} L)
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($kendaraans->isEmpty())
                                        <p class="text-[11px] text-amber-500 mt-1 font-semibold">Tidak ada kendaraan terdaftar pada satker ini.</p>
                                    @endif
                                </div>
                            </div>

                            @if($personelAccessControl == '1')
                            {{-- Form Fields: Jika Skema Kendaraan -> Personel --}}
                            <div x-show="tipeTujuan === 'personel'" x-transition class="space-y-3.5 pt-2 border-t border-slate-100 dark:border-white/5">
                                <div>
                                    <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                        <span class="w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-600 dark:text-indigo-400 text-[10px] font-black flex items-center justify-center">3</span>
                                        <span>Kendaraan Sumber (Asal Saldo)</span>
                                    </label>
                                    <select name="kendaraan_id" id="transfer_kendaraan_id" :required="tipeTujuan === 'personel'" class="w-full" x-model="selectedKendaraan" x-ref="kendaraanSelect">
                                        <option value="">-- Pilih Kendaraan Asal --</option>
                                        @foreach($kendaraans as $k)
                                            <option value="{{ $k->id }}">
                                                {{ $k->no_polisi }} • {{ $k->jenis_bbm }} (Saldo: {{ rtrim(rtrim(number_format($k->saldo, 2, ',', '.'), '0'), ',') }} L)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                        <span class="w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-600 dark:text-indigo-400 text-[10px] font-black flex items-center justify-center">4</span>
                                        <span>Anggota / Personel Penerima</span>
                                    </label>
                                    <select name="personel_id" id="transfer_personel_id" :required="tipeTujuan === 'personel'" class="w-full">
                                        <option value="">-- Pilih Personel Tujuan --</option>
                                    </select>
                                    <p class="text-[10px] text-slate-400 mt-1">Daftar anggota disesuaikan otomatis dengan jenis BBM kendaraan sumber.</p>
                                </div>
                            </div>
                            @endif

                            {{-- Volume Transfer & PIN Otorisasi --}}
                            <div class="space-y-3.5 pt-2 border-t border-slate-100 dark:border-white/5">
                                {{-- Jumlah Liter Input --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                        Volume Transfer (Liter)
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="jumlah" id="input_jumlah_liter" step="1" min="1" required placeholder="0"
                                            x-model="quickLiter"
                                            class="w-full pl-3.5 pr-14 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 rounded-xl text-sm font-bold text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                        <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                            LITER
                                        </span>
                                    </div>
                                    {{-- Quick Add Buttons --}}
                                    <div class="flex items-center gap-1.5 mt-2">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mr-1">Cepat:</span>
                                        @foreach([5, 10, 20, 50] as $preset)
                                            <button type="button" @click="quickLiter = {{ $preset }}"
                                                class="px-2 py-0.5 rounded-lg text-[10px] font-extrabold bg-slate-100 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 border border-slate-200 dark:border-white/5 transition-all active:scale-95">
                                                +{{ $preset }}L
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- PIN Keamanan Top Up --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                        PIN Otorisasi Top Up
                                    </label>
                                    <div class="relative">
                                        <input type="password" name="topup_password" required placeholder="Masukkan PIN keamanan"
                                            class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 rounded-xl text-sm font-bold text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all tracking-widest">
                                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                </div>

                                {{-- Keterangan Opsional --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                        Keterangan (Opsional)
                                    </label>
                                    <input type="text" name="keterangan" placeholder="Alasan perpindahan saldo..."
                                        class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-medium text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <div class="pt-2">
                                <button type="submit"
                                    class="w-full py-3 bg-gradient-to-r from-blue-600 via-indigo-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white rounded-xl font-black shadow-lg shadow-indigo-500/25 transition-all duration-200 active:scale-[0.98] flex items-center justify-center gap-2 text-xs uppercase tracking-wider disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                                    {{ $kendaraans->isEmpty() ? 'disabled' : '' }}>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                    <span>Konfirmasi & Eksekusi Transfer</span>
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            {{-- RIGHT COLUMN: Riwayat Transfer Log (7 cols) --}}
            <div class="lg:col-span-7 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl sm:rounded-3xl shadow-sm overflow-hidden flex flex-col">
                {{-- Table Header --}}
                <div class="p-4 sm:px-5 sm:py-4 bg-gradient-to-r from-slate-50 via-slate-50 to-indigo-50/20 dark:from-slate-800/80 dark:via-slate-800/80 dark:to-indigo-950/20 border-b border-slate-200 dark:border-white/10 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider">Riwayat Log Transfer</h3>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">Histori lengkap perpindahan BBM unit</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        @if($selectedSatkerId)
                            <a href="{{ route('admin.transfer-saldo.index') }}" 
                               class="text-[11px] font-bold text-rose-500 hover:text-rose-600 px-2.5 py-1 rounded-lg bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 flex items-center gap-1 transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span>Reset Filter Satker</span>
                            </a>
                        @endif
                        <span class="px-3 py-1 rounded-full text-[11px] font-black bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-white/5">
                            {{ $riwayat->total() }} Data
                        </span>
                    </div>
                </div>

                {{-- Table Content --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 dark:bg-slate-800/40 border-b border-slate-200 dark:border-white/10">
                                <th class="px-4 py-3 text-[10px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 py-3 text-[10px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Sumber (Asal)</th>
                                <th class="px-2 py-3 text-center"></th>
                                <th class="px-4 py-3 text-[10px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tujuan</th>
                                <th class="px-4 py-3 text-[10px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">Volume</th>
                                <th class="px-4 py-3 text-[10px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden sm:table-cell">Ket</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($riwayat as $item)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors group">
                                    {{-- Tanggal & Jam --}}
                                    <td class="px-4 py-3.5 align-middle whitespace-nowrap">
                                        <p class="text-xs font-black text-slate-800 dark:text-slate-200">
                                            {{ $item->created_at->format('d M Y') }}
                                        </p>
                                        <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500">
                                            {{ $item->created_at->format('H:i') }} WITA
                                        </p>
                                    </td>

                                    {{-- Sumber --}}
                                    <td class="px-4 py-3.5 align-middle">
                                        @if($item->kendaraan_id)
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20 flex items-center justify-center shrink-0">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1" />
                                                    </svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-xs font-black text-slate-800 dark:text-slate-100 truncate" title="{{ $item->kendaraan->no_polisi ?? '-' }}">
                                                        {{ $item->kendaraan->no_polisi ?? '-' }}
                                                    </p>
                                                    <span class="inline-block px-1.5 py-0.2 rounded text-[9px] font-bold uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">
                                                        KENDARAAN
                                                    </span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-xl bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 flex items-center justify-center shrink-0">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                                    </svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-xs font-black text-rose-600 dark:text-rose-400 truncate">
                                                        STOK PUSAT
                                                    </p>
                                                    <span class="inline-block px-1.5 py-0.2 rounded text-[9px] font-bold uppercase tracking-wider bg-rose-50 dark:bg-rose-500/10 text-rose-500">
                                                        PUSAT INDUK
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Panah Transfer --}}
                                    <td class="px-1 py-3.5 text-center align-middle">
                                        <div class="w-6 h-6 mx-auto rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                        </div>
                                    </td>

                                    {{-- Tujuan --}}
                                    <td class="px-4 py-3.5 align-middle">
                                        @if($item->personel_id)
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 flex items-center justify-center shrink-0">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-xs font-black text-slate-800 dark:text-slate-100 truncate" title="{{ $item->personel->nama ?? '-' }}">
                                                        {{ $item->personel->nama ?? '-' }}
                                                    </p>
                                                    <span class="inline-block px-1.5 py-0.2 rounded text-[9px] font-bold uppercase tracking-wider bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400">
                                                        PERSONEL
                                                    </span>
                                                </div>
                                            </div>
                                        @elseif(isset($item->tujuan_kendaraan_id))
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 flex items-center justify-center shrink-0">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1" />
                                                    </svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-xs font-black text-slate-800 dark:text-slate-100 truncate" title="{{ $item->tujuanKendaraan->no_polisi ?? '-' }}">
                                                        {{ $item->tujuanKendaraan->no_polisi ?? '-' }}
                                                    </p>
                                                    <span class="inline-block px-1.5 py-0.2 rounded text-[9px] font-bold uppercase tracking-wider bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                                        KENDARAAN
                                                    </span>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </td>

                                    {{-- Volume Badge --}}
                                    <td class="px-4 py-3.5 text-center align-middle whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-black bg-gradient-to-r from-indigo-500/15 to-blue-500/15 border border-indigo-500/30 text-indigo-600 dark:text-indigo-400 shadow-sm">
                                            <span>{{ number_format($item->jumlah, 0, ',', '.') }}</span>
                                            <span class="text-[10px] font-bold">L</span>
                                        </span>
                                    </td>

                                    {{-- Keterangan --}}
                                    <td class="px-4 py-3.5 align-middle hidden sm:table-cell max-w-[150px] truncate">
                                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium truncate" title="{{ $item->keterangan ?: '-' }}">
                                            {{ $item->keterangan ?: '-' }}
                                        </p>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-14 text-center">
                                        <div class="w-16 h-16 mx-auto rounded-3xl bg-slate-100 dark:bg-slate-800/60 text-slate-400 dark:text-slate-500 flex items-center justify-center mb-3">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                        <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300">Belum Ada Riwayat Transfer</h4>
                                        <p class="text-[11px] text-slate-400 mt-1 max-w-sm mx-auto">
                                            Seluruh riwayat perpindahan kuota BBM antar armada atau dari stok pusat akan tercatat otomatis di sini.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Footer --}}
                <div class="p-4 bg-slate-50/60 dark:bg-slate-800/30 border-t border-slate-200 dark:border-white/10">
                    {{ $riwayat->links() }}
                </div>
            </div>

        </div>

    </div>

    @push('scripts')
        <script>
            document.addEventListener('turbo:load', function () {
                const satkerSelect = document.getElementById('transfer_satker_id');
                const kendaraanSelect = document.getElementById('transfer_kendaraan_id');
                const tujuanKendaraanSelect = document.getElementById('transfer_tujuan_kendaraan_id');
                const personelSelect = document.getElementById('transfer_personel_id');
                
                function getTsInstance(el, config = {}) {
                    if (!el) return null;
                    if (el.tomselect) return el.tomselect;
                    const defaults = {
                        create: false,
                        dropdownParent: 'body',
                        onDropdownOpen: (dropdown) => {
                            dropdown.style.zIndex = '99999';
                        }
                    };
                    return new TomSelect(el, { ...defaults, ...config });
                }

                if (satkerSelect) {
                    getTsInstance(satkerSelect);
                }

                const kendaraanTs = getTsInstance(kendaraanSelect);

                if (tujuanKendaraanSelect) {
                    getTsInstance(tujuanKendaraanSelect, {
                        create: false,
                        sortField: {
                            field: 'text',
                            direction: 'asc'
                        }
                    });
                }

                const personelTs = getTsInstance(personelSelect, {
                    create: false,
                    valueField: 'id',
                    labelField: 'text',
                    searchField: 'text',
                    options: [],
                    render: {
                        option: function(data, escape) {
                            return '<div class="px-3 py-2 border-b border-slate-100 dark:border-white/5">' +
                                        '<div class="font-bold text-slate-800 dark:text-slate-200 text-xs">' + escape(data.nama) + '</div>' +
                                        '<div class="text-[9px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider">' + escape(data.nrp) + ' • ' + escape(data.bbm_label) + ' • ' + escape(data.saldo_label) + '</div>' +
                                   '</div>';
                        },
                        item: function(data, escape) {
                            return '<div class="text-xs font-bold">' + escape(data.nama) + ' (' + escape(data.nrp) + ')</div>';
                        }
                    }
                });

                const kendaraans = @json($kendaraans ?? []);
                const personels = @json($personels ?? []);

                function updateOptions(value) {
                    if(!personelTs) return;
                    personelTs.clear();
                    personelTs.clearOptions();

                    if (!value) return;

                    const selectedKendaraan = kendaraans.find(k => k.id == value);
                    const requiredBbm = selectedKendaraan ? (selectedKendaraan.jenis_bbm || '').toUpperCase() : null;

                    if (requiredBbm) {
                        const filteredPersonels = personels.filter(p => !p.jenis_bbm || p.jenis_bbm.toUpperCase() === requiredBbm);
                        personelTs.addOptions(filteredPersonels.map(p => ({
                            id: p.id,
                            nama: p.nama,
                            nrp: p.nrp,
                            bbm_label: p.jenis_bbm || 'BELUM SET BBM',
                            saldo_label: Number(p.saldo).toLocaleString('id-ID') + ' L',
                            text: `${p.nama} (${p.nrp})`
                        })));
                    }
                }

                if (kendaraanTs) {
                    kendaraanTs.on('change', function (value) {
                        updateOptions(value);
                    });

                    if (kendaraanTs.getValue()) {
                        updateOptions(kendaraanTs.getValue());
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
