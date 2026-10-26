<x-app-layout>
    <div class="p-3 sm:p-6 lg:p-7 space-y-6 max-w-7xl mx-auto">
        
        <!-- Executive Header Banner -->
        <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-2xl sm:rounded-3xl border border-indigo-500/30 p-4 sm:p-6 shadow-xl shadow-indigo-950/20">
            <div class="absolute -top-20 -right-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="min-w-0">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-white/10 text-cyan-300 border border-white/10 mb-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        SPBP POLDA NTB • LOGISTIK & PENERIMAAN BBM
                    </div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-black text-white tracking-tight">
                        Penerimaan & Pembelian BBM
                    </h1>
                    <p class="mt-1 text-xs sm:text-sm text-slate-300 max-w-2xl">
                        Catat faktur DO dan pasokan BBM Pertamax & Pertamina Dex dari Pertamina. Data ini secara otomatis tersinkronisasi sebagai <span class="text-cyan-300 font-bold">Penerimaan pada Laporan Rutin Slog</span>.
                    </p>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('pembelian-bbm.print', request()->all()) }}" target="_blank"
                       class="px-4 py-2.5 bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-500 hover:to-red-500 text-white text-xs font-black rounded-xl shadow-lg shadow-rose-600/30 transition-all duration-200 flex items-center gap-2 active:scale-95 border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        <span>Cetak Laporan PDF</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Summary Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
            <!-- Stat 1: Total Pertamax -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 via-indigo-600 to-indigo-800 text-white p-4 shadow-lg shadow-indigo-500/20 border border-blue-400/30 flex items-center justify-between">
                <div class="relative z-10">
                    <div class="flex items-center gap-1.5 mb-1">
                        <span class="w-2 h-2 rounded-full bg-cyan-300"></span>
                        <p class="text-[10px] font-black uppercase tracking-wider text-blue-100">Total Pasokan Pertamax</p>
                    </div>
                    <div class="flex items-baseline gap-1.5 mt-0.5">
                        <h3 class="text-2xl sm:text-3xl font-black text-white tabular-nums">
                            {{ number_format($totalPertamax ?? 0, 0, ',', '.') }}
                        </h3>
                        <span class="text-xs font-bold text-blue-200">Liter</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-white/15 backdrop-blur-md border border-white/20 flex items-center justify-center shrink-0 shadow-inner">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>

            <!-- Stat 2: Total Pertamina Dex -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-600 via-red-600 to-rose-800 text-white p-4 shadow-lg shadow-rose-500/20 border border-rose-400/30 flex items-center justify-between">
                <div class="relative z-10">
                    <div class="flex items-center gap-1.5 mb-1">
                        <span class="w-2 h-2 rounded-full bg-amber-300"></span>
                        <p class="text-[10px] font-black uppercase tracking-wider text-rose-100">Total Pasokan Pertamina Dex</p>
                    </div>
                    <div class="flex items-baseline gap-1.5 mt-0.5">
                        <h3 class="text-2xl sm:text-3xl font-black text-white tabular-nums">
                            {{ number_format($totalDex ?? 0, 0, ',', '.') }}
                        </h3>
                        <span class="text-xs font-bold text-rose-200">Liter</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-white/15 backdrop-blur-md border border-white/20 flex items-center justify-center shrink-0 shadow-inner">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
            </div>

            <!-- Stat 3: Total Transaksi Pembelian -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800 via-slate-900 to-indigo-950 text-white p-4 shadow-lg shadow-slate-950/20 border border-slate-700/40 flex items-center justify-between">
                <div class="relative z-10">
                    <div class="flex items-center gap-1.5 mb-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <p class="text-[10px] font-black uppercase tracking-wider text-slate-300">Total Faktur / DO Masuk</p>
                    </div>
                    <div class="flex items-baseline gap-1.5 mt-0.5">
                        <h3 class="text-2xl sm:text-3xl font-black text-white tabular-nums">
                            {{ number_format($totalTransaksi ?? 0, 0, ',', '.') }}
                        </h3>
                        <span class="text-xs font-bold text-slate-400">Transaksi</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 flex items-center justify-center shrink-0 shadow-inner">
                    <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Feedback Alert Messages -->
        @if(session('success'))
            <div class="p-3.5 sm:p-4 bg-emerald-500/10 dark:bg-emerald-950/40 border border-emerald-500/30 rounded-2xl flex items-center gap-3 shadow-md shadow-emerald-950/10">
                <div class="p-1.5 bg-emerald-500 text-white rounded-xl shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <p class="font-bold text-xs sm:text-sm text-emerald-800 dark:text-emerald-200">{{ session('success') }}</p>
            </div>
        @endif
        
        @if ($errors->any())
            <div class="p-3.5 sm:p-4 bg-rose-500/10 dark:bg-rose-950/40 border border-rose-500/30 rounded-2xl">
                <div class="flex items-center gap-2 mb-1.5 text-rose-600 dark:text-rose-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <p class="font-black text-xs uppercase tracking-wider">Terdapat Kesalahan Pengisian</p>
                </div>
                <ul class="list-disc list-inside text-xs font-semibold text-rose-800 dark:text-rose-300 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Layout: Form (Left) & History Table (Right) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left Column: Form Pembelian BBM (Col Span 4) -->
            <div class="lg:col-span-4 lg:sticky lg:top-6">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl sm:rounded-3xl shadow-xl shadow-slate-900/5 dark:shadow-indigo-950/20 overflow-hidden">
                    
                    <!-- Form Header -->
                    <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-white/10 bg-slate-50/80 dark:bg-slate-800/60 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-cyan-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-indigo-500/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm sm:text-base font-black text-slate-900 dark:text-white">
                                Form Pembelian BBM
                            </h2>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                Input penerimaan pasokan DO baru
                            </p>
                        </div>
                    </div>

                    <!-- Form Body -->
                    <form action="{{ route('pembelian-bbm.store') }}" method="POST" class="p-4 sm:p-5 space-y-4" autocomplete="off" x-data="{ jenis: '{{ old('jenis_bbm', 'Pertamax') }}' }">
                        @csrf
                        
                        <!-- 1. Tanggal Penerimaan -->
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>1. Tanggal Penerimaan / DO</span>
                            </label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', $latestDate ?? date('Y-m-d')) }}" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-bold text-xs sm:text-sm text-slate-800 dark:text-slate-200 transition-all outline-none" required>
                        </div>

                        <!-- 2. Jenis BBM (Interactive Card Chips) -->
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                                <span>2. Jenis Bahan Bakar (BBM)</span>
                            </label>

                            <input type="hidden" name="jenis_bbm" :value="jenis">

                            <div class="grid grid-cols-2 gap-2.5">
                                <!-- Option 1: Pertamax -->
                                <button type="button" @click="jenis = 'Pertamax'"
                                    :class="jenis === 'Pertamax' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/30 border-blue-500' : 'bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-white/10 hover:border-blue-400'"
                                    class="p-2.5 rounded-xl border text-center transition-all duration-200 flex flex-col items-center justify-center gap-1">
                                    <span class="w-2 h-2 rounded-full" :class="jenis === 'Pertamax' ? 'bg-cyan-300 animate-pulse' : 'bg-blue-500'"></span>
                                    <span class="text-xs font-black uppercase tracking-wider">Pertamax</span>
                                </button>

                                <!-- Option 2: Pertamina Dex -->
                                <button type="button" @click="jenis = 'Pertamina Dex'"
                                    :class="jenis === 'Pertamina Dex' ? 'bg-rose-600 text-white shadow-md shadow-rose-500/30 border-rose-500' : 'bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-white/10 hover:border-rose-400'"
                                    class="p-2.5 rounded-xl border text-center transition-all duration-200 flex flex-col items-center justify-center gap-1">
                                    <span class="w-2 h-2 rounded-full" :class="jenis === 'Pertamina Dex' ? 'bg-amber-300 animate-pulse' : 'bg-rose-500'"></span>
                                    <span class="text-xs font-black uppercase tracking-wider">P. Dex</span>
                                </button>
                            </div>
                        </div>

                        <!-- 3. Jumlah BBM (Liter) -->
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                </svg>
                                <span>3. Jumlah BBM (Liter)</span>
                            </label>
                            
                            <div class="relative">
                                <input type="number" name="jumlah" value="{{ old('jumlah') }}" step="1" min="1" 
                                    class="w-full pl-3.5 pr-14 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono font-black text-sm text-slate-900 dark:text-white transition-all outline-none" 
                                    placeholder="Contoh: 8000" required>
                                <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-xs font-black text-indigo-500 uppercase">
                                    Liter
                                </span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" 
                                class="w-full py-3 bg-gradient-to-r from-indigo-600 via-indigo-700 to-cyan-600 hover:from-indigo-500 hover:to-cyan-500 text-white rounded-xl font-black text-xs uppercase tracking-wider shadow-lg shadow-indigo-600/30 transition-all active:scale-[0.98] flex items-center justify-center gap-2 border border-white/15">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Simpan Data Pembelian</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: History Table (Col Span 8) -->
            <div class="lg:col-span-8 space-y-4">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl sm:rounded-3xl shadow-xl shadow-slate-900/5 dark:shadow-indigo-950/20 overflow-hidden">
                    
                    <!-- Table Header & Filter Bar -->
                    <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-white/10 bg-slate-50/80 dark:bg-slate-800/60 space-y-3.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></div>
                                <h2 class="text-sm sm:text-base font-black text-slate-800 dark:text-white uppercase tracking-wider">
                                    Riwayat Pembelian BBM
                                </h2>
                            </div>
                        </div>

                        <!-- Filter Form -->
                        <form action="{{ route('pembelian-bbm.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                            <input type="hidden" name="per_page" value="{{ request('per_page', 15) }}">
                            
                            <!-- Filter BBM Dropdown -->
                            <div class="min-w-[130px] flex-1 sm:flex-none">
                                <select name="jenis_bbm" class="w-full px-3 py-1.5 bg-white dark:bg-slate-950 border border-slate-300 dark:border-white/10 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                                    <option value="">Semua Jenis BBM</option>
                                    <option value="Pertamax" {{ request('jenis_bbm') == 'Pertamax' ? 'selected' : '' }}>Pertamax</option>
                                    <option value="Pertamina Dex" {{ request('jenis_bbm') == 'Pertamina Dex' ? 'selected' : '' }}>Pertamina Dex</option>
                                </select>
                            </div>

                            <!-- Start Date -->
                            <div class="flex-1 sm:flex-none">
                                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                                    class="w-full px-3 py-1.5 bg-white dark:bg-slate-950 border border-slate-300 dark:border-white/10 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none" 
                                    placeholder="Tgl Mulai">
                            </div>

                            <!-- End Date -->
                            <div class="flex-1 sm:flex-none">
                                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                                    class="w-full px-3 py-1.5 bg-white dark:bg-slate-950 border border-slate-300 dark:border-white/10 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none" 
                                    placeholder="Tgl Selesai">
                            </div>

                            <!-- Filter & Reset Buttons -->
                            <div class="flex items-center gap-1.5 ml-auto">
                                <button type="submit" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-xl text-xs uppercase tracking-wider transition-all shadow-sm">
                                    Filter
                                </button>
                                @if(request()->hasAny(['start_date', 'end_date', 'jenis_bbm']))
                                    <a href="{{ route('pembelian-bbm.index', request()->only('per_page')) }}" 
                                       class="px-3 py-1.5 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-300 font-bold rounded-xl text-xs uppercase tracking-wider transition-all">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Per Page Control & Showing Info Bar (Rapi di Luar Thead) -->
                    <div class="px-4 py-2.5 bg-slate-100/70 dark:bg-slate-800/30 border-b border-slate-200 dark:border-white/5 flex items-center justify-between text-xs text-slate-600 dark:text-slate-400">
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold uppercase tracking-wider">Tampilkan:</span>
                            <form action="{{ route('pembelian-bbm.index') }}" method="GET" class="inline-block">
                                @foreach(request()->except('per_page') as $k => $v)
                                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                @endforeach
                                <select name="per_page" onchange="this.form.submit()" 
                                    class="px-2 py-1 bg-white dark:bg-slate-900 border border-slate-300 dark:border-white/10 rounded-lg text-xs font-bold text-slate-700 dark:text-slate-300 outline-none">
                                    @foreach([10, 15, 25, 50, 100] as $size)
                                        <option value="{{ $size }}" {{ request('per_page', 15) == $size ? 'selected' : '' }}>{{ $size }} Baris</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                            Menampilkan <span class="text-slate-900 dark:text-white font-black">{{ $pembelians->firstItem() ?? 0 }}-{{ $pembelians->lastItem() ?? 0 }}</span> dari <span class="text-slate-900 dark:text-white font-black">{{ $pembelians->total() }}</span> data
                        </div>
                    </div>

                    <!-- Table Responsive -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 dark:bg-slate-800/40 border-b border-slate-200 dark:border-white/10 text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                    <th class="px-4 py-3.5 text-center w-12">No</th>
                                    <th class="px-4 py-3.5">Tanggal Penerimaan</th>
                                    <th class="px-4 py-3.5 text-center">Jenis BBM</th>
                                    <th class="px-4 py-3.5 text-right">Kuantum (Liter)</th>
                                    <th class="px-4 py-3.5 text-center w-24">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-white/5">
                                @forelse($pembelians as $index => $item)
                                    <tr class="hover:bg-indigo-50/30 dark:hover:bg-slate-800/60 transition-colors">
                                        <!-- No -->
                                        <td class="px-4 py-3.5 text-center text-xs font-bold text-slate-500 dark:text-slate-400">
                                            {{ ($pembelians->currentPage() - 1) * $pembelians->perPage() + $index + 1 }}
                                        </td>

                                        <!-- Tanggal -->
                                        <td class="px-4 py-3.5">
                                            <p class="text-xs sm:text-sm font-black text-slate-800 dark:text-white">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                                            </p>
                                            <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">
                                                Dibuat: {{ $item->created_at->format('d/m/Y H:i') }}
                                            </p>
                                        </td>

                                        <!-- Jenis BBM Badge -->
                                        <td class="px-4 py-3.5 text-center">
                                            @if(stripos($item->jenis_bbm, 'dex') !== false)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-500/15 text-rose-600 dark:text-rose-400 border border-rose-500/30 shadow-sm whitespace-nowrap">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    Pertamina Dex
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-500/15 text-blue-600 dark:text-blue-400 border border-blue-500/30 shadow-sm whitespace-nowrap">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                                    Pertamax
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Jumlah Liter -->
                                        <td class="px-4 py-3.5 text-right">
                                            <div class="inline-flex items-baseline gap-1">
                                                <span class="text-sm sm:text-base font-black tabular-nums text-slate-900 dark:text-white">
                                                    {{ number_format($item->jumlah, 0, ',', '.') }}
                                                </span>
                                                <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400">L</span>
                                            </div>
                                        </td>

                                        <!-- Action Buttons -->
                                        <td class="px-4 py-3.5 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <!-- Edit Button -->
                                                <a href="{{ route('pembelian-bbm.edit', $item) }}" 
                                                   class="p-2 text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 rounded-xl transition-all" 
                                                   title="Edit Data">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>

                                                <!-- Delete Form Button -->
                                                <form action="{{ route('pembelian-bbm.destroy', $item->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" 
                                                        data-confirm="Apakah Anda yakin ingin menghapus data pembelian sebesar {{ number_format($item->jumlah, 0, ',', '.') }} L ini?"
                                                        data-confirm-type="danger"
                                                        data-confirm-title="Hapus Pembelian BBM"
                                                        data-confirm-text="Ya, Hapus!"
                                                        class="p-2 text-slate-600 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/50 rounded-xl transition-all" 
                                                        title="Hapus Data">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center gap-2">
                                                <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                    </svg>
                                                </div>
                                                <p class="text-xs font-bold text-slate-600 dark:text-slate-400">
                                                    Tidak ada data riwayat pembelian yang sesuai filter.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    @if($pembelians->hasPages())
                        <div class="px-4 py-3 sm:px-6 sm:py-4 bg-slate-50/80 dark:bg-slate-800/40 border-t border-slate-200 dark:border-white/10">
                            {{ $pembelians->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
