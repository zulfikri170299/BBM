<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-4 sm:space-y-8">
        <!-- Page Title -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-white">Dashboard</h1>

            </div>
            <div class="flex items-center gap-3">
            </div>
        </div>

        <!-- Main Stats Grid -->
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-2 sm:gap-4">
            <!-- Card: Total Satker -->
            <div
                class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-indigo-600 to-indigo-900 border border-indigo-400 shadow-xl p-3 sm:p-5 text-white shadow-lg shadow-indigo-500/5 group hover:shadow-indigo-500/20 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-indigo-500/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10 h-full flex flex-col">
                    <div class="flex items-center justify-between mb-2 sm:mb-3">
                        <div class="p-2 sm:p-2.5 bg-white/20 border border-white/30 rounded-lg sm:rounded-xl">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                        <a href="{{ route('admin.satkers.index') }}"
                            class="text-[9px] sm:text-[10px] font-semibold bg-white/20 border border-white/30 hover:bg-slate-900 border border-white/5/30 px-2 py-1 rounded-lg transition uppercase tracking-wider text-white/90">Kelola</a>
                    </div>
                    <div class="flex flex-col gap-0.5 sm:gap-1 mt-2 sm:mt-1">
                        <p class="text-2xl sm:text-4xl font-black text-white leading-none">{{ number_format($stats['totalSatker'], 0, ',', '.') }}</p>
                        <p class="text-[10px] sm:text-sm text-white/90 font-medium leading-tight">Total Satker</p>
                    </div>
                    <div class="mt-auto pt-3 sm:pt-5 grid grid-cols-1 sm:grid-cols-2 gap-1.5 sm:gap-2">
                        <div class="bg-white/10 backdrop-blur-sm border border-white/20 shadow-sm rounded-xl px-3 py-2 text-center flex flex-col justify-center transition-colors hover:bg-white/20">
                            <p class="text-[10px] sm:text-xs font-bold text-white/80 uppercase tracking-wider mb-1">Terdaftar</p>
                            <p class="text-sm sm:text-base font-black text-white leading-tight">{{ number_format($stats['totalSatker'], 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm border border-white/20 shadow-sm rounded-xl px-3 py-2 flex flex-col items-center justify-center transition-colors hover:bg-white/20">
                            <p class="text-[10px] sm:text-xs font-bold text-white/80 uppercase tracking-wider mb-1">Status</p>
                            <p class="text-xs sm:text-sm font-black text-emerald-300 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_8px_rgba(52,211,153,0.8)]"></span>
                                AKTIF
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Total Users -->
            <div
                class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-emerald-600 to-emerald-900 border border-emerald-400 shadow-xl p-3 sm:p-5 text-white shadow-lg shadow-emerald-500/5 group hover:shadow-emerald-500/20 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-emerald-500/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10 h-full flex flex-col">
                    <div class="flex items-center justify-between mb-2 sm:mb-3">
                        <div class="p-2 sm:p-2.5 bg-white/20 border border-white/30 rounded-lg sm:rounded-xl">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                        <a href="{{ route('admin.users.index') }}"
                            class="text-[9px] sm:text-[10px] font-semibold bg-white/20 border border-white/30 hover:bg-slate-900 border border-white/5/30 px-2 py-1 rounded-lg transition uppercase tracking-wider text-white/90">Kelola</a>
                    </div>
                    <div class="flex flex-col gap-0.5 sm:gap-1 mt-2 sm:mt-1">
                        <p class="text-2xl sm:text-4xl font-black text-white leading-none">{{ number_format($stats['totalUsers'], 0, ',', '.') }}</p>
                        <p class="text-[10px] sm:text-sm text-white/90 font-medium leading-tight">Total Users</p>
                    </div>
                    <div class="mt-auto pt-3 sm:pt-5 grid grid-cols-1 sm:grid-cols-2 gap-1.5 sm:gap-2">
                        <div class="bg-white/10 backdrop-blur-sm border border-white/20 shadow-sm rounded-xl px-3 py-2 text-center flex flex-col justify-center transition-colors hover:bg-white/20">
                            <p class="text-[10px] sm:text-xs font-bold text-white/80 uppercase tracking-wider mb-1">Hak Akses</p>
                            <p class="text-xs sm:text-sm font-black text-white truncate">Terverifikasi</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm border border-white/20 shadow-sm rounded-xl px-3 py-2 text-center flex flex-col justify-center transition-colors hover:bg-white/20">
                            <p class="text-[10px] sm:text-xs font-bold text-white/80 uppercase tracking-wider mb-1">Sistem</p>
                            <p class="text-xs sm:text-sm font-black text-white">Online</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Total Kendaraan -->
            <div
                class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-amber-500 to-amber-800 border border-amber-400 shadow-xl p-3 sm:p-5 text-white shadow-lg shadow-amber-500/5 group hover:shadow-amber-500/20 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-amber-500/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10 h-full flex flex-col">
                    <div class="flex items-center justify-between mb-2 sm:mb-3">
                        <div class="p-2 sm:p-2.5 bg-white/20 border border-white/30 rounded-lg sm:rounded-xl">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1">
                                </path>
                            </svg>
                        </div>
                        <a href="{{ route('admin.kendaraans.index') }}"
                            class="text-[9px] sm:text-[10px] font-semibold bg-white/20 border border-white/30 hover:bg-slate-900 border border-white/5/30 px-2 py-1 rounded-lg transition uppercase tracking-wider text-white/90">Kelola</a>
                    </div>
                    <div class="flex flex-col gap-0.5 sm:gap-1 mt-2 sm:mt-1">
                        <p class="text-2xl sm:text-4xl font-black text-white leading-none">{{ number_format($stats['totalKendaraan'], 0, ',', '.') }}</p>
                        <p class="text-[10px] sm:text-sm text-white/90 font-medium leading-tight">Total Kendaraan</p>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-1.5 sm:gap-2 mt-auto pt-3 sm:pt-5">
                        <div class="bg-white/10 backdrop-blur-sm border border-white/20 shadow-sm rounded-xl px-2 py-2 text-center transition-colors hover:bg-white/20">
                            <p class="text-[10px] sm:text-xs font-bold text-white/80 uppercase tracking-wider mb-1">R2</p>
                            <p class="text-sm sm:text-base font-black text-white">{{ $stats['rodaR2'] }}</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm border border-white/20 shadow-sm rounded-xl px-2 py-2 text-center transition-colors hover:bg-white/20">
                            <p class="text-[10px] sm:text-xs font-bold text-white/80 uppercase tracking-wider mb-1">R4</p>
                            <p class="text-sm sm:text-base font-black text-white">{{ $stats['rodaR4'] }}</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm border border-white/20 shadow-sm rounded-xl px-2 py-2 text-center transition-colors hover:bg-white/20">
                            <p class="text-[10px] sm:text-xs font-bold text-white/80 uppercase tracking-wider mb-1">R6</p>
                            <p class="text-sm sm:text-base font-black text-white">{{ $stats['rodaR6'] }}</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm border border-white/20 shadow-sm rounded-xl px-2 py-2 text-center transition-colors hover:bg-white/20">
                            <p class="text-[10px] sm:text-xs font-bold text-white/80 uppercase tracking-wider mb-1">Non</p>
                            <p class="text-sm sm:text-base font-black text-white">{{ $stats['rodaNon'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Total Transaksi -->
            <div
                class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-rose-600 to-rose-900 border border-rose-400 shadow-xl p-3 sm:p-5 text-white shadow-lg shadow-rose-500/5 group hover:shadow-rose-500/20 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-rose-500/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10 h-full flex flex-col">
                    <div class="flex items-center justify-between mb-2 sm:mb-3">
                        <div class="p-2 sm:p-2.5 bg-white/20 border border-white/30 rounded-lg sm:rounded-xl">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <a href="{{ route('admin.riwayat.index') }}"
                            class="text-[9px] sm:text-[10px] font-semibold bg-white/20 border border-white/30 hover:bg-slate-900 border border-white/5/30 px-2 py-1 rounded-lg transition uppercase tracking-wider text-white/90">Riwayat</a>
                    </div>
                    <div class="flex flex-col gap-0.5 sm:gap-1 mt-2 sm:mt-1">
                        <p class="text-2xl sm:text-4xl font-black text-white leading-none">{{ number_format($stats['totalTransaksi'], 0, ',', '.') }}</p>
                        <p class="text-[10px] sm:text-sm text-white/90 font-medium leading-tight">Total Transaksi</p>
                    </div>
                    <div class="mt-auto pt-3 sm:pt-5 grid grid-cols-1 sm:grid-cols-2 gap-1.5 sm:gap-2">
                        <div class="bg-white/10 backdrop-blur-sm border border-white/20 shadow-sm rounded-xl px-3 py-2 text-center flex flex-col justify-center transition-colors hover:bg-white/20">
                            <p class="text-[10px] sm:text-xs font-bold text-white/80 uppercase tracking-wider mb-1">Volume Disalurkan</p>
                            <p class="text-sm sm:text-base font-black text-white">{{ number_format($stats['totalLiter'] ?? 0, 0, ',', '.') }} <span class="text-[10px] font-bold opacity-80">L</span></p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm border border-white/20 shadow-sm rounded-xl px-3 py-2 text-center flex flex-col justify-center transition-colors hover:bg-white/20">
                            <p class="text-[10px] sm:text-xs font-bold text-white/80 uppercase tracking-wider mb-1">Total Bon</p>
                            <p class="text-sm sm:text-base font-black text-white">{{ number_format(($stats['totalHutangPertamax'] ?? 0) + ($stats['totalHutangDex'] ?? 0), 0, ',', '.') }} <span class="text-[10px] font-bold opacity-80">L</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(in_array(auth()->user()->role, ['super_admin', 'kasubbag']))
        <!-- Tank Stock Section (Sinkronisasi) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
            <!-- Card: Pertamax -->
            <div class="bg-slate-900/60 border border-white/10 rounded-2xl shadow-lg p-5 text-white flex items-center gap-4 group hover:shadow-indigo-500/20 transition-all duration-300 hover:-translate-y-1 relative overflow-hidden">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-indigo-500/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="p-3 bg-white/20 border border-white/30 rounded-xl group-hover:scale-110 transition-transform shrink-0">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
                <div class="relative z-10 flex-1 min-w-0 pr-12">
                    <p class="text-[10px] sm:text-[11px] font-black text-white/90 uppercase tracking-widest mb-1 opacity-80 leading-tight">Stok BBM di Tangki (Pertamax)</p>
                    <h3 class="text-xl sm:text-2xl font-black truncate text-white">{{ number_format($tankStock['pertamax'], 0, ',', '.') }} <span class="text-xs font-bold text-white/90">L</span></h3>
                </div>
                <div class="absolute bottom-4 right-4 relative z-20">
                    <a href="{{ route('admin.laporan-stok-bbm.index') }}" class="text-[10px] font-bold bg-white/20 border border-white/30 hover:bg-slate-900 hover:border-white/5/40 px-3 py-1.5 rounded-lg transition-all uppercase tracking-wider text-white/90">Laporan</a>
                </div>
            </div>

            <!-- Card: Dex -->
            <div class="{{ $tankStock['dex'] < 0 ? 'bg-slate-900/60 border-2 border-rose-500/50 shadow-2xl scale-[1.02]' : 'bg-slate-900/60 border border-white/10' }} rounded-2xl shadow-lg p-5 text-white flex items-center gap-4 group hover:shadow-rose-500/20 transition-all duration-300 hover:-translate-y-1 relative overflow-hidden">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-rose-500/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="p-3 bg-white/20 border border-white/30 rounded-xl group-hover:scale-110 transition-transform shrink-0">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
                <div class="relative z-10 flex-1 min-w-0 pr-12">
                    <p class="text-[10px] sm:text-[11px] font-black text-white/90 uppercase tracking-widest mb-1 opacity-80 leading-tight">Stok BBM di Tangki (Dex)</p>
                    <h3 class="text-xl sm:text-2xl font-black truncate text-white">{{ number_format($tankStock['dex'], 0, ',', '.') }} <span class="text-xs font-bold text-white/90">L</span></h3>
                </div>
                <div class="absolute bottom-4 right-4 relative z-20">
                    <a href="{{ route('admin.laporan-stok-bbm.index') }}" class="text-[10px] font-bold bg-white/20 border border-white/30 hover:bg-slate-900 hover:border-white/5/40 px-3 py-1.5 rounded-lg transition-all uppercase tracking-wider text-white/90">Laporan</a>
                </div>
            </div>
        </div>
        @endif

        <!-- Hutang Stats Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-gradient-to-r from-rose-900/30 to-slate-900 rounded-2xl border border-rose-500/20 shadow-sm p-5 flex items-center gap-4 group hover:shadow-md transition-all">
                <div class="p-3 bg-rose-50 rounded-xl text-rose-500 group-hover:bg-rose-100 transition-colors">
                    <svg class="w-8 h-8 font-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Hutang Pertamax (Belum Bayar)</p>
                    <p class="text-2xl font-black text-white leading-none">{{ number_format($stats['totalHutangPertamax'], 0, ',', '.') }} <span class="text-sm font-bold text-slate-400">Liter</span></p>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-rose-900/30 to-slate-900 rounded-2xl border border-rose-500/20 shadow-sm p-5 flex items-center gap-4 group hover:shadow-md transition-all">
                <div class="p-3 bg-rose-50 rounded-xl text-rose-500 group-hover:bg-rose-100 transition-colors">
                    <svg class="w-8 h-8 font-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Hutang Dex (Belum Bayar)</p>
                    <p class="text-2xl font-black text-white leading-none">{{ number_format($stats['totalHutangDex'], 0, ',', '.') }} <span class="text-sm font-bold text-slate-400">Liter</span></p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-8 mt-2 sm:mt-4">
            <!-- Left Column: Unit Balance & Satisfaction Index -->
            <div class="space-y-4 sm:space-y-8">
                <div
                    class="bg-slate-900 border border-white/5 rounded-xl sm:rounded-2xl border border-white/10/70 shadow-sm p-4 sm:p-6 overflow-hidden relative">
                <div class="absolute top-0 right-0 p-4 opacity-5">
                    <svg class="w-20 h-20 sm:w-32 sm:h-32" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08s5.97 1.09 6 3.08c-1.29 1.94-3.5 3.22-6 3.22zM18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.85 7h10.29l1.04 3H5.81l1.04-3zM19 17H5v-5h14v5z" />
                    </svg>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:gap-8 relative z-10">
                    <!-- Unit Section -->
                    <div>
                        <h3 class="text-xs sm:text-xs font-semibold text-slate-200 mb-2 sm:mb-4 flex items-center gap-2">
                            <div class="w-1 h-3 sm:w-1.5 sm:h-4 bg-amber-500 rounded-full"></div>
                            Total Saldo Kendaraan
                        </h3>
                        <div class="grid grid-cols-2 gap-2 sm:gap-4">
                            @php
                                $bbmStyle = [
                                    'Pertamax' => 'from-blue-500 to-indigo-600',
                                    'Pertamina Dex' => 'from-rose-500 to-red-600',
                                ];
                            @endphp
                            @foreach($kendaraanFuel as $kFuel)
                                @if(isset($bbmStyle[$kFuel->jenis_bbm]))
                                    <div
                                        class="bg-gradient-to-br {{ $bbmStyle[$kFuel->jenis_bbm] }} p-3 sm:p-4 rounded-lg sm:rounded-xl text-white shadow-md">
                                        <p
                                            class="text-[8px] sm:text-[9px] font-bold uppercase tracking-widest opacity-80 truncate">
                                            {{ $kFuel->jenis_bbm }}</p>
                                        <div class="flex items-baseline gap-1 mt-0.5 sm:mt-1">
                                            <span
                                                class="text-base sm:text-xl font-black">{{ number_format($kFuel->total, 0, ',', '.') }}</span>
                                            <span class="text-[9px] sm:text-[10px] font-bold opacity-70">L</span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            @if(count($kendaraanFuel) == 0)
                                <p class="col-span-2 text-center text-slate-400 py-4 italic">Belum ada data saldo kendaraan.
                                </p>
                            @endif
                        </div>
                    </div>


                </div>
                </div>
                
                <!-- Satisfaction Index Chart -->
                <div
                    class="bg-slate-900 border border-white/5 rounded-xl sm:rounded-2xl border border-white/10/70 shadow-sm p-4 sm:p-6 overflow-hidden relative">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-2">
                        <h3 class="text-base sm:text-lg font-bold text-slate-200">Indeks Kepuasan Petugas</h3>
                        <div
                            class="flex items-center gap-2 text-[8px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Sangat
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span> Puas
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> Tidak
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 items-center">
                        <div class="space-y-2 sm:space-y-3 order-2 md:order-1">
                            <div
                                class="p-2 sm:p-3 bg-emerald-50 rounded-lg sm:rounded-xl border border-emerald-100 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-lg sm:text-xl">🤩</span>
                                    <div>
                                        <p
                                            class="text-[9px] sm:text-[10px] font-bold text-emerald-800 uppercase leading-none mb-0.5 sm:mb-1">
                                            Sangat Puas</p>
                                        <p class="text-[10px] sm:text-xs font-medium text-emerald-600 leading-none">
                                            {{ $satisfactionStats['p_sangat_puas'] }}% dari total</p>
                                    </div>
                                </div>
                                <span
                                    class="text-base sm:text-lg font-black text-emerald-700">{{ $satisfactionStats['sangat_puas'] }}</span>
                            </div>
                            <div
                                class="p-2 sm:p-3 bg-amber-50 rounded-lg sm:rounded-xl border border-amber-100 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-lg sm:text-xl">🙂</span>
                                    <div>
                                        <p
                                            class="text-[9px] sm:text-[10px] font-bold text-amber-800 uppercase leading-none mb-0.5 sm:mb-1">
                                            Puas</p>
                                        <p class="text-[10px] sm:text-xs font-medium text-amber-600 leading-none">
                                            {{ $satisfactionStats['p_puas'] }}% dari total</p>
                                    </div>
                                </div>
                                <span
                                    class="text-base sm:text-lg font-black text-amber-700">{{ $satisfactionStats['puas'] }}</span>
                            </div>
                            <div
                                class="p-2 sm:p-3 bg-rose-50 rounded-lg sm:rounded-xl border border-rose-100 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-lg sm:text-xl">😡</span>
                                    <div>
                                        <p
                                            class="text-[9px] sm:text-[10px] font-bold text-rose-800 uppercase leading-none mb-0.5 sm:mb-1">
                                            Tidak Puas</p>
                                        <p class="text-[10px] sm:text-xs font-medium text-rose-600 leading-none">
                                            {{ $satisfactionStats['p_tidak_puas'] }}% dari total</p>
                                    </div>
                                </div>
                                <span
                                    class="text-base sm:text-lg font-black text-rose-700">{{ $satisfactionStats['tidak_puas'] }}</span>
                            </div>
                        </div>
                        <div id="satisfactionDonutChart" class="min-h-[200px] sm:min-h-[220px] order-1 md:order-2">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item 2: Stok Pembelian BBM (Belum Distribusi) (Super Admin) -->
            <div class="space-y-4 sm:space-y-8">
                <div class="bg-slate-900/60 border border-white/10 rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6 overflow-hidden relative group h-full">
                    <div class="absolute -top-12 -right-12 w-32 h-32 sm:w-48 sm:h-48 bg-indigo-500/20 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-500">
                    </div>
                    <h3 class="text-sm sm:text-lg font-bold text-white mb-4 sm:mb-6 flex items-center gap-2 relative z-10">
                        <div class="w-1.5 h-4 sm:w-2 sm:h-6 bg-indigo-500 rounded-full"></div>
                        Saldo BBM (Belum Distribusi)
                    </h3>
                    <div class="grid grid-cols-1 gap-2 sm:gap-4 relative z-10">
                        @foreach($adminStocks as $aStock)
                            <div class="bg-white/20 border border-white/30 rounded-lg sm:rounded-xl p-3 sm:p-4 hover:border-white/20 transition-all">
                                <p class="text-[8px] sm:text-[10px] font-bold text-white/90 uppercase tracking-widest leading-tight mb-0.5 sm:mb-1">
                                    {{ $aStock->jenis_bbm }}</p>
                                <div class="flex items-baseline justify-between mt-0.5 sm:mt-1">
                                    <span class="text-xl sm:text-2xl font-bold text-white">{{ rtrim(rtrim(number_format($aStock->saldo, 2, ',', '.'), '0'), ',') }}
                                        <span class="text-xs sm:text-sm font-bold text-white/90 opacity-70">L</span></span>
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-slate-800 border border-white/5/20 flex items-center justify-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 sm:mt-8 text-right relative z-10">
                        <a href="{{ route('admin.stok.index') }}" class="text-xs sm:text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 border border-transparent px-4 py-2 sm:px-6 sm:py-3 rounded-xl transition-all shadow-lg active:scale-95 flex items-center gap-2 inline-flex">
                            Kelola Saldo
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Item 3: Transaction Chart Area -->
            <div class="space-y-4 sm:space-y-8">
                <div class="bg-slate-900 border border-white/5 rounded-xl sm:rounded-2xl border border-white/10/70 shadow-sm p-4 sm:p-6 h-full">
                    <h3 class="text-base sm:text-lg font-bold text-slate-200 mb-2 sm:mb-4">Transaksi 7 Hari Terakhir</h3>
                    <div id="transactionChart"></div>
                </div>
            </div>

            <!-- Item 4: Recent Transactions -->
            <div class="space-y-4 sm:space-y-8">
                <div class="bg-slate-900 border border-white/5 rounded-xl sm:rounded-2xl border border-white/10/70 shadow-sm h-full flex flex-col">
                    <div class="p-4 sm:p-6 border-b border-white/5 shrink-0">
                        <h3 class="text-base sm:text-lg font-bold text-slate-200">Aktivitas Terbaru</h3>
                    </div>
                    <div class="divide-y divide-white/5 flex-1 overflow-y-auto" style="max-height: 400px;">
                        @foreach($recentTransactions as $trx)
                            <div class="px-4 py-3 sm:px-6 sm:py-4 hover:bg-slate-800/50 transition-colors">
                                <div class="flex items-center gap-2 sm:gap-3">
                                    <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs sm:text-sm">
                                        {{ substr($trx->kendaraan->no_polisi ?? ($trx->personel->nrp ?? '?'), 0, 2) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs sm:text-xs font-medium text-slate-200 truncate">
                                            {{ $trx->kendaraan->no_polisi ?? ($trx->personel->nama ?? 'Personel') }}</p>
                                        <p class="text-[10px] sm:text-xs text-slate-400">
                                            {{ $trx->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs sm:text-sm font-bold text-slate-300">
                                            {{ number_format($trx->liter, 0, ',', '.') }} L</p>
                                        <p class="text-[10px] sm:text-xs text-slate-400">Total:
                                            {{ number_format($trx->liter, 0, ',', '.') }} L</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div id="userMap"
            class="w-full h-[300px] sm:h-[400px] rounded-xl z-0 bg-slate-800 flex items-center justify-center text-slate-400"
            style="height: 300px; min-height: 300px;">
            Memuat Peta...
        </div>
    </div>

    <!-- Chart Script -->
    @php
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = \App\Models\TransaksiBbm::whereDate('created_at', $date)->count();
            $chartData[] = ['date' => $date->format('d M'), 'count' => $count];
        }
    @endphp

    @push('styles')
        <style>
            .leaflet-popup-content-wrapper {
                border-radius: 12px;
                padding: 0.5rem;
            }

            .leaflet-popup-tip {
                background: white;
            }
        </style>
    @endpush

    @push('scripts')
        <!-- Map Script -->
        <script>
            document.addEventListener('turbo:load', function () {
                var mapContainer = document.getElementById('userMap');
                if (mapContainer && typeof L !== 'undefined') {
                    // Force height for standard Leaflet initialization
                    mapContainer.style.height = '300px';
                    
                    // Double-initialization prevention for Turbo
                    if (window.myUserMap) {
                        window.myUserMap.off();
                        window.myUserMap.remove();
                        window.myUserMap = null;
                    }

                    // Map Initialization
                    window.myUserMap = L.map('userMap', {
                        maxBounds: [[-11.0, 95.0], [6.0, 141.0]],
                        minZoom: 5,
                        maxBoundsViscosity: 1.0
                    }).setView([-2.5489, 118.0149], 5); // Center of Indonesia

                    var map = window.myUserMap;

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    var users = {!! json_encode($usersWithLocation) !!};
                    var markers = [];

                    function getRoleColor(role) {
                        switch (role) {
                            case 'super_admin': return 'bg-rose-500';
                            case 'admin_satker': return 'bg-indigo-600';
                            case 'petugas_bbm': return 'bg-amber-500';
                            case 'personel': return 'bg-emerald-500';
                            default: return 'bg-slate-500';
                        }
                    }

                    users.forEach(function (user) {
                        if (user.last_latitude && user.last_longitude) {
                            var roleColor = getRoleColor(user.role);
                            var initials = user.name.substring(0, 2).toUpperCase();

                            var iconHtml = `
                                <div class="relative flex items-center justify-center w-8 h-8 rounded-full shadow-lg border-2 border-white ${roleColor} text-white font-bold text-xs transform hover:scale-110 transition-transform">
                                    ${initials}
                                    <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-[4px] border-l-transparent border-r-[4px] border-r-transparent border-t-[6px] border-t-white opacity-50"></div>
                                </div>
                            `;

                            var customIcon = L.divIcon({
                                html: iconHtml,
                                className: '', // Remove default Leaflet class styles
                                iconSize: [32, 32],
                                iconAnchor: [16, 32],
                                popupAnchor: [0, -32]
                            });

                            var marker = L.marker([user.last_latitude, user.last_longitude], { icon: customIcon }).addTo(map);

                            var lastActive = new Date(user.last_activity_at).toLocaleString('id-ID');
                            var roleLabel = user.role.replace('_', ' ').toUpperCase();

                            var popupContent = `
                                <div class="p-2 min-w-[200px]">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="w-8 h-8 rounded-full ${roleColor} flex items-center justify-center text-white text-xs font-bold shadow-sm">${initials}</span>
                                        <div>
                                            <h4 class="font-bold text-slate-200 text-sm leading-tight">${user.name}</h4>
                                            <p class="text-[10px] text-indigo-600 font-bold tracking-wide">${roleLabel}</p>
                                        </div>
                                    </div>
                                    <div class="text-xs text-slate-400 border-t border-white/5 pt-2 space-y-1">
                                        <p class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span class="font-medium">Aktif:</span> ${lastActive}
                                        </p>
                                        <p class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            <span class="font-medium">Lokasi:</span> ${user.last_latitude.toFixed(4)}, ${user.last_longitude.toFixed(4)}
                                        </p>
                                    </div>
                                </div>
                            `;

                            marker.bindPopup(popupContent);
                            markers.push(marker);
                        }
                    });

                    if (markers.length > 0) {
                        var group = new L.featureGroup(markers);
                        map.fitBounds(group.getBounds().pad(0.1));
                    }

                    // Force map resize check
                    setTimeout(function () { map.invalidateSize(); }, 500);
                }
            });
        </script>

        <!-- Chart Script -->
        <script>
            document.addEventListener('turbo:load', function () {
                // Satisfaction Donut Chart
                var satisfactionOptions = {
                    chart: {
                        type: 'donut',
                        height: 220,
                        fontFamily: 'Outfit, sans-serif',
                    },
                    series: [
                        {{ $satisfactionStats['sangat_puas'] }},
                        {{ $satisfactionStats['puas'] }},
                        {{ $satisfactionStats['tidak_puas'] }}
                    ],
                    labels: ['Sangat Puas', 'Puas', 'Tidak Puas'],
                    colors: ['#10b981', '#fbbf24', '#f43f5e'], // Emerald-500, Amber-400, Rose-500
                    legend: { show: false },
                    dataLabels: { enabled: false },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '75%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        formatter: function (w) {
                                            return {{ $satisfactionStats['total'] }}
                                        }
                                    }
                                }
                            }
                        }
                    },
                    tooltip: { theme: 'dark' }
                };
                var satisfactionChart = new ApexCharts(document.querySelector("#satisfactionDonutChart"), satisfactionOptions);
                satisfactionChart.render();

                // Transaction Chart Initialization (Existing)
                var options = {
                    chart: {
                        type: 'area',
                        height: 320,
                        fontFamily: 'Outfit, sans-serif',
                        toolbar: { show: false },
                        zoom: { enabled: false },
                    },
                    series: [{
                        name: 'Transaksi',
                        data: {!! json_encode(array_column($chartData, 'count')) !!}
                    }],
                    xaxis: {
                        categories: {!! json_encode(array_column($chartData, 'date')) !!},
                        labels: { style: { colors: '#94a3b8', fontSize: '10px' } }, // Small font for mobile axis
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                    },
                    yaxis: {
                        labels: { style: { colors: '#94a3b8', fontSize: '10px' } }, // Small font for mobile axis
                    },
                    colors: ['#4338ca'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.45,
                            opacityTo: 0.05,
                            stops: [0, 100]
                        }
                    },
                    stroke: { curve: 'smooth', width: 3 },
                    dataLabels: { enabled: false },
                    grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                    tooltip: {
                        theme: 'dark',
                        y: { formatter: function (val) { return val + ' transaksi'; } }
                    }
                };
                var chart = new ApexCharts(document.querySelector("#transactionChart"), options);
                chart.render();

            });
        </script>
    @endpush
</x-app-layout>