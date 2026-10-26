<x-app-layout>
    <div class="p-3 sm:p-5 lg:p-6 space-y-4 sm:space-y-5 max-w-7xl mx-auto">
        
        <!-- Executive Header Banner (Compact & Sleek) -->
        <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-xl sm:rounded-2xl border border-indigo-500/30 p-3.5 sm:p-4 shadow-lg shadow-indigo-950/20">
            <div class="absolute -top-16 -right-16 w-60 h-60 bg-indigo-500/20 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-cyan-500/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="min-w-0">
                    <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-white/10 text-cyan-300 border border-white/10 mb-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        SPBP POLDA NTB • KONTROL SALDO PUSAT
                    </div>
                    <h1 class="text-lg sm:text-xl lg:text-2xl font-black text-white tracking-tight">
                        Manajemen Saldo BBM Pusat
                    </h1>
                    <p class="text-[11px] sm:text-xs text-slate-300 max-w-2xl">
                        Pantau saldo bahan bakar induk dan riwayat mutasi sebelum didistribusikan ke kendaraan dinas Satker.
                    </p>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('admin.stok.print', request()->all()) }}" target="_blank"
                       class="px-3.5 py-2 bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-500 hover:to-red-500 text-white text-xs font-black rounded-xl shadow-md shadow-rose-600/30 transition-all duration-200 flex items-center gap-2 active:scale-95 border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        <span>Cetak Laporan PDF</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Master Stock Cards (Compact, Sleek & Streamlined) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
            @foreach(['Pertamax', 'Pertamina Dex'] as $bbmName)
                @php
                    $stockItem = $stocks->firstWhere('jenis_bbm', $bbmName);
                    $saldo = $stockItem ? $stockItem->saldo : 0;
                    $masuk = $summary[$bbmName]['masuk'] ?? 0;
                    $keluar = $summary[$bbmName]['keluar'] ?? 0;
                    $isDex = stripos($bbmName, 'dex') !== false;
                @endphp

                <div class="relative overflow-hidden rounded-xl sm:rounded-2xl {{ $isDex ? 'bg-gradient-to-br from-rose-600 via-rose-700 to-red-800 border-rose-400/40 shadow-rose-900/20' : 'bg-gradient-to-br from-blue-600 via-indigo-600 to-indigo-800 border-blue-400/40 shadow-indigo-900/20' }} text-white p-3.5 sm:p-4 shadow-lg border group">
                    <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-white/10 rounded-full blur-xl group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>

                    <div class="relative z-10 flex flex-col justify-between gap-2.5">
                        <!-- Top Header: Label & Icon -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $isDex ? 'bg-amber-300' : 'bg-cyan-300' }} animate-pulse"></span>
                                <h3 class="text-[11px] sm:text-xs font-black uppercase tracking-wider text-white/90">
                                    Saldo Induk {{ $bbmName }}
                                </h3>
                            </div>
                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-white/15 backdrop-blur-md border border-white/20 flex items-center justify-center shrink-0 shadow-inner">
                                @if($isDex)
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                @endif
                            </div>
                        </div>

                        <!-- Bottom Row: Big Saldo Value & Mini Mutasi Stats -->
                        <div class="flex flex-wrap sm:flex-nowrap items-end justify-between gap-2 pt-1 border-t border-white/15">
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-2xl sm:text-3xl font-black text-white tabular-nums tracking-tight">
                                    {{ number_format($saldo, 0, ',', '.') }}
                                </span>
                                <span class="text-xs font-bold text-white/80">Liter</span>
                            </div>

                            <!-- Mini Mutasi Indicators -->
                            <div class="flex items-center gap-1.5 text-[10px] sm:text-[11px] font-bold">
                                <div class="px-2 py-1 rounded-lg bg-black/25 backdrop-blur-sm border border-white/10 flex items-center gap-1" title="Total BBM Masuk">
                                    <span class="text-[9px] uppercase tracking-wider text-emerald-300/80 font-black">Msk</span>
                                    <span class="text-emerald-300 font-black tabular-nums">+{{ number_format($masuk, 0, ',', '.') }} L</span>
                                </div>
                                <div class="px-2 py-1 rounded-lg bg-black/25 backdrop-blur-sm border border-white/10 flex items-center gap-1" title="Total BBM Keluar">
                                    <span class="text-[9px] uppercase tracking-wider text-rose-300/80 font-black">Klr</span>
                                    <span class="text-rose-300 font-black tabular-nums">-{{ number_format($keluar, 0, ',', '.') }} L</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
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
        
        @if(session('error'))
            <div class="p-3.5 sm:p-4 bg-rose-500/10 dark:bg-rose-950/40 border border-rose-500/30 rounded-2xl flex items-center gap-3 shadow-md shadow-rose-950/10">
                <div class="p-1.5 bg-rose-500 text-white rounded-xl shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <p class="font-bold text-xs sm:text-sm text-rose-800 dark:text-rose-200">{{ session('error') }}</p>
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

        <!-- Main Layout: Form (Left) & Mutation Table (Right) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left Column: Form Top-Up Saldo Baru (Col Span 4) -->
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
                                Tambah Saldo Baru
                            </h2>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                Top-up kuota saldo bahan bakar pusat
                            </p>
                        </div>
                    </div>

                    <!-- Form Body -->
                    <form action="{{ route('admin.stok.store') }}" method="POST" class="p-4 sm:p-5 space-y-4" autocomplete="off" x-data="{ jenis: '{{ old('jenis_bbm', 'Pertamax') }}' }">
                        @csrf
                        <!-- Browser Auto-fill Bypass -->
                        <input type="text" style="display:none" autocomplete="username">
                        <input type="password" style="display:none" autocomplete="current-password">

                        <!-- 1. Jenis BBM (Interactive Card Chips) -->
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                                <span>1. Jenis Bahan Bakar (BBM)</span>
                            </label>

                            <input type="hidden" name="jenis_bbm" :value="jenis">

                            <div class="grid grid-cols-2 gap-2.5">
                                <button type="button" @click="jenis = 'Pertamax'"
                                    :class="jenis === 'Pertamax' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/30 border-blue-500' : 'bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-white/10 hover:border-blue-400'"
                                    class="p-2.5 rounded-xl border text-center transition-all duration-200 flex flex-col items-center justify-center gap-1">
                                    <span class="w-2 h-2 rounded-full" :class="jenis === 'Pertamax' ? 'bg-cyan-300 animate-pulse' : 'bg-blue-500'"></span>
                                    <span class="text-xs font-black uppercase tracking-wider">Pertamax</span>
                                </button>

                                <button type="button" @click="jenis = 'Pertamina Dex'"
                                    :class="jenis === 'Pertamina Dex' ? 'bg-rose-600 text-white shadow-md shadow-rose-500/30 border-rose-500' : 'bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-white/10 hover:border-rose-400'"
                                    class="p-2.5 rounded-xl border text-center transition-all duration-200 flex flex-col items-center justify-center gap-1">
                                    <span class="w-2 h-2 rounded-full" :class="jenis === 'Pertamina Dex' ? 'bg-amber-300 animate-pulse' : 'bg-rose-500'"></span>
                                    <span class="text-xs font-black uppercase tracking-wider">P. Dex</span>
                                </button>
                            </div>
                        </div>

                        <!-- 2. Jumlah (Liter) -->
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                </svg>
                                <span>2. Jumlah Saldo (Liter)</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="jumlah" step="1" min="1" 
                                    class="w-full pl-3.5 pr-14 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono font-black text-sm text-slate-900 dark:text-white transition-all outline-none" 
                                    placeholder="Contoh: 10000" required>
                                <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-xs font-black text-indigo-500 uppercase">
                                    Liter
                                </span>
                            </div>
                        </div>

                        <!-- 3. Keterangan -->
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>3. Keterangan (Opsional)</span>
                            </label>
                            <textarea name="keterangan" rows="2" 
                                class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-xs text-slate-800 dark:text-slate-200 transition-all outline-none" 
                                placeholder="Contoh: Penerimaan Alokasi TW IV 2026"></textarea>
                        </div>

                        <!-- 4. Password Top Up -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    <span>4. Password Top-Up</span>
                                </label>
                                <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400">Otorisasi</span>
                            </div>
                            <input type="password" name="topup_password" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-xs sm:text-sm text-slate-900 dark:text-white transition-all outline-none" 
                                placeholder="Ketik PIN / Password Top-up" required autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                            
                            @if(!auth()->user()->topup_password)
                                <p class="text-[11px] text-rose-500 font-bold mt-1.5 flex items-center gap-1">
                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    <span>Anda belum mengatur password top-up. <a href="{{ route('profile.edit') }}" class="underline hover:text-rose-700">Atur di Profil</a></span>
                                </p>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" 
                                class="w-full py-3 bg-gradient-to-r from-indigo-600 via-indigo-700 to-cyan-600 hover:from-indigo-500 hover:to-cyan-500 text-white rounded-xl font-black text-xs uppercase tracking-wider shadow-lg shadow-indigo-600/30 transition-all active:scale-[0.98] flex items-center justify-center gap-2 border border-white/15">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Simpan Data Saldo</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: Mutation History Table (Col Span 8) -->
            <div class="lg:col-span-8 space-y-4">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl sm:rounded-3xl shadow-xl shadow-slate-900/5 dark:shadow-indigo-950/20 overflow-hidden">
                    
                    <!-- Table Header & Filter Bar -->
                    <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-white/10 bg-slate-50/80 dark:bg-slate-800/60 space-y-3.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></div>
                                <h2 class="text-sm sm:text-base font-black text-slate-800 dark:text-white uppercase tracking-wider">
                                    Riwayat Perubahan Saldo
                                </h2>
                            </div>
                        </div>

                        <!-- Filter Form -->
                        <form action="{{ route('admin.stok.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                            <input type="hidden" name="per_page" value="{{ request('per_page', 20) }}">
                            
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
                                    <a href="{{ route('admin.stok.index', request()->only('per_page')) }}" 
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
                            <form action="{{ route('admin.stok.index') }}" method="GET" class="inline-block">
                                @foreach(request()->except('per_page') as $k => $v)
                                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                @endforeach
                                <select name="per_page" onchange="this.form.submit()" 
                                    class="px-2 py-1 bg-white dark:bg-slate-900 border border-slate-300 dark:border-white/10 rounded-lg text-xs font-bold text-slate-700 dark:text-slate-300 outline-none">
                                    @foreach([10, 20, 50, 100] as $size)
                                        <option value="{{ $size }}" {{ request('per_page', 20) == $size ? 'selected' : '' }}>{{ $size }} Baris</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                            Menampilkan <span class="text-slate-900 dark:text-white font-black">{{ $history->firstItem() ?? 0 }}-{{ $history->lastItem() ?? 0 }}</span> dari <span class="text-slate-900 dark:text-white font-black">{{ $history->total() }}</span> data
                        </div>
                    </div>

                    <!-- Table Responsive -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 dark:bg-slate-800/40 border-b border-slate-200 dark:border-white/10 text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                    <th class="px-4 py-3.5">Tanggal</th>
                                    <th class="px-4 py-3.5 text-center">Jenis BBM</th>
                                    <th class="px-4 py-3.5 text-right">Jumlah</th>
                                    <th class="px-4 py-3.5 text-center">Tipe Mutasi</th>
                                    <th class="px-4 py-3.5">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-white/5">
                                @forelse($history as $item)
                                    <tr class="hover:bg-indigo-50/30 dark:hover:bg-slate-800/60 transition-colors">
                                        <!-- Tanggal & Jam -->
                                        <td class="px-4 py-3.5 whitespace-nowrap">
                                            <p class="text-xs sm:text-sm font-black text-slate-800 dark:text-white">
                                                {{ $item->created_at->translatedFormat('d/m/Y') }}
                                            </p>
                                            <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">
                                                {{ $item->created_at->timezone('Asia/Makassar')->format('H:i') }} WITA
                                            </p>
                                        </td>

                                        <!-- Jenis BBM -->
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

                                        <!-- Jumlah Mutasi -->
                                        <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                            <div class="inline-flex items-baseline gap-1 {{ $item->tipe === 'masuk' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                                <span class="text-xs sm:text-sm font-black tabular-nums">
                                                    {{ $item->tipe === 'masuk' ? '+' : '-' }} {{ number_format($item->jumlah, 0, ',', '.') }}
                                                </span>
                                                <span class="text-[10px] font-bold">L</span>
                                            </div>
                                        </td>

                                        <!-- Tipe Badge -->
                                        <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                            @if($item->tipe === 'masuk')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    Masuk
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-500/15 text-rose-600 dark:text-rose-400 border border-rose-500/30">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    Keluar
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Keterangan -->
                                        <td class="px-4 py-3.5">
                                            <p class="text-xs text-slate-700 dark:text-slate-300 font-medium leading-relaxed max-w-md line-clamp-2" title="{{ $item->keterangan }}">
                                                {{ $item->keterangan }}
                                            </p>
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
                                                    Belum ada riwayat mutasi saldo yang sesuai filter.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    @if($history->hasPages())
                        <div class="px-4 py-3 sm:px-6 sm:py-4 bg-slate-50/80 dark:bg-slate-800/40 border-t border-slate-200 dark:border-white/10">
                            {{ $history->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
