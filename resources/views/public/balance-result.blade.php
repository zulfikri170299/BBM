<x-guest-layout maxWidth="max-w-md">
    <div class="relative backdrop-blur-2xl bg-slate-900/95 border border-slate-700/80 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.7)] p-5 sm:p-7 overflow-hidden text-slate-100 text-center my-auto">
        <!-- Ambient Decorative Lighting Glows -->
        <div class="absolute -top-12 -right-12 w-40 h-40 bg-emerald-500/15 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-12 -left-12 w-40 h-40 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <!-- Top Header & Badges -->
        <div class="flex items-center justify-between mb-4 relative z-10">
            <a href="{{ route('cek-saldo.index') }}" class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full bg-slate-800/80 hover:bg-slate-800 border border-slate-700/60 text-slate-300 hover:text-white text-xs font-semibold transition-all duration-200 group">
                <svg class="w-3.5 h-3.5 text-slate-400 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Kembali</span>
            </a>
            
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-[10px] font-extrabold tracking-wider uppercase shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Informasi Saldo</span>
            </div>
        </div>

        <!-- Vehicle Emblem / Header -->
        <div class="mb-4 relative z-10">
            <div class="flex justify-center mb-2">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-500 to-amber-600 p-0.5 shadow-md shadow-amber-500/20" style="width: 56px; height: 56px;">
                    <div class="w-full h-full bg-slate-950 rounded-[14px] flex items-center justify-center">
                        @if($type === 'kendaraan')
                            <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1" />
                            </svg>
                        @else
                            <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        @endif
                    </div>
                </div>
            </div>
            <h1 class="text-lg sm:text-xl font-black text-white tracking-tight uppercase">{{ $title }}</h1>
            <p class="text-xs text-slate-400 uppercase tracking-widest font-bold mt-0.5">{{ $name }}</p>
        </div>

        <!-- Vehicle Details Card -->
        <div class="bg-slate-950/80 rounded-xl border border-slate-800 p-3.5 mb-4 text-left space-y-2 relative z-10 shadow-inner">
            <div class="flex items-center justify-between">
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $id_label }}</span>
                <span class="px-2 py-0.5 rounded-lg bg-slate-800 text-amber-400 font-mono text-xs font-black tracking-widest uppercase border border-slate-700">
                    {{ $id_value }}
                </span>
            </div>
            <div class="w-full h-px bg-slate-800/80"></div>
            <div class="flex items-center justify-between">
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Jenis BBM</span>
                <span class="px-2 py-0.5 rounded-md bg-amber-500/20 text-amber-300 border border-amber-500/30 text-[10px] font-black uppercase tracking-wider">
                    {{ $jenis_bbm ?? 'BELUM SET' }}
                </span>
            </div>
            <div class="w-full h-px bg-slate-800/80"></div>
            <div class="flex items-center justify-between">
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Waktu Pengecekan</span>
                <span class="text-slate-300 text-[10.5px] font-semibold">{{ now()->isoFormat('HH:mm - D MMMM Y') }}</span>
            </div>
        </div>

        <!-- Main Saldo Highlight Card -->
        <div class="relative group mb-5 z-10">
            <div class="absolute inset-0 bg-emerald-500/20 blur-xl rounded-2xl opacity-60 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative bg-gradient-to-br from-emerald-600 via-emerald-700 to-teal-800 p-5 rounded-2xl border border-emerald-400/30 shadow-xl overflow-hidden">
                <div class="absolute top-0 right-0 p-3 opacity-15">
                    <svg class="w-14 h-14 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                        <path fill-rule="evenodd"
                            d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p class="text-[10px] text-emerald-100/90 font-extrabold uppercase tracking-[0.2em] mb-1">Sisa Kuota Saldo Realtime</p>
                <div class="flex items-baseline justify-center gap-1.5 my-1">
                    <span class="text-white text-4xl sm:text-5xl font-black tracking-tight drop-shadow-sm">
                        {{ rtrim(rtrim(number_format($saldo, 2, ',', '.'), '0'), ',') }}
                    </span>
                    <span class="text-emerald-200 text-base font-extrabold tracking-wider">LITER</span>
                </div>
                <p class="text-[9.5px] text-emerald-100/70 mt-0.5 italic font-medium">Kondisi Saldo Berlaku Saat Ini</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="space-y-2 relative z-10">
            <a href="{{ route('cek-saldo.index') }}" class="w-full py-3 px-4 bg-slate-800/90 hover:bg-slate-800 text-white font-bold rounded-xl transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center gap-2 border border-slate-700 shadow-md">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>Periksa Kendaraan Lain</span>
            </a>
            
            <a href="{{ route('login') }}" class="w-full py-3 px-4 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-orange-500 text-slate-950 font-black rounded-xl shadow-lg shadow-amber-500/25 active:scale-[0.98] transition-all text-xs uppercase tracking-wider flex items-center justify-center gap-2 border-t border-white/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                <span>Masuk ke Akun SIM-BBM</span>
            </a>
        </div>
    </div>
</x-guest-layout>