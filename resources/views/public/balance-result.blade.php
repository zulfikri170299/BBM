<x-guest-layout maxWidth="max-w-md">
    <div class="bg-slate-900/80 backdrop-blur-2xl border border-white/10 rounded-3xl shadow-2xl overflow-hidden p-6 md:p-8 relative text-center">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 p-4">
            <div class="px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-[10px] font-bold tracking-wider uppercase">
                Informasi Saldo
            </div>
        </div>

        <div class="mb-6">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/30">
                    @if($type === 'kendaraan')
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1" />
                        </svg>
                    @else
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    @endif
                </div>
            </div>
            <h1 class="text-xl font-bold text-white mb-0.5 uppercase tracking-tight">{{ $title }}</h1>
            <p class="text-slate-400 text-xs uppercase tracking-widest font-semibold">{{ $name }}</p>
        </div>

        <!-- Details Card -->
        <div class="bg-slate-950/50 rounded-2xl border border-white/5 p-4 mb-6 text-left space-y-3">
            <div class="flex items-center justify-between group">
                <span class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">{{ $id_label }}</span>
                <span class="text-white text-xs font-bold tracking-widest group-hover:text-amber-500 transition-colors uppercase">{{ $id_value }}</span>
            </div>
            <div class="w-full h-px bg-white/5"></div>
            <div class="flex items-center justify-between group">
                <span class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Jenis BBM</span>
                <span class="px-2 py-0.5 bg-amber-500 text-slate-900 text-[9px] font-black rounded-lg uppercase tracking-wider group-hover:scale-110 transition-transform">{{ $jenis_bbm ?? 'BELUM SET' }}</span>
            </div>
            <div class="w-full h-px bg-white/5"></div>
            <div class="flex items-center justify-between group">
                <span class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Waktu Pengecekan</span>
                <span class="text-slate-300 text-[10px] font-semibold uppercase">{{ now()->isoFormat('HH:mm - D MMMM Y') }}</span>
            </div>
        </div>

        <!-- Saldo Display -->
        <div class="relative group">
            <div class="absolute inset-0 bg-emerald-500/20 blur-xl rounded-full opacity-40 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative bg-gradient-to-br from-emerald-600 to-emerald-700 p-6 rounded-2xl border border-white/10 shadow-lg overflow-hidden">
                <div class="absolute top-0 right-0 p-3 opacity-10">
                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p class="text-[9px] text-emerald-100/70 font-bold uppercase tracking-[0.2em] mb-1">Sisa Saldo Anda</p>
                <div class="flex items-baseline justify-center gap-1">
                    <span class="text-white text-4xl font-black tracking-tight">{{ number_format($saldo, 0, ',', '.') }}</span>
                    <span class="text-emerald-100 text-base font-bold">LITER</span>
                </div>
            </div>
        </div>

        <!-- Disclaimer -->
        <p class="mt-6 text-[9px] text-slate-500 leading-relaxed uppercase tracking-wider italic">
            * Data ini diambil secara realtime. Untuk rincian transaksi lengkap, silakan login ke dashboard.
        </p>

        <!-- Actions -->
        <div class="mt-6 flex flex-col gap-2">
            <a href="{{ route('cek-saldo.index') }}" class="w-full py-3 px-4 bg-slate-800/50 hover:bg-slate-700/50 text-white font-bold rounded-xl transition-all duration-200 text-[10px] uppercase tracking-widest flex items-center justify-center gap-2 border border-white/10 group">
                <svg class="w-3.5 h-3.5 text-amber-500 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Cek Lainnya
            </a>
            <a href="{{ route('login') }}" class="w-full py-3 px-4 bg-gradient-to-r from-red-700 to-amber-700 hover:from-red-600 hover:to-amber-600 text-white font-bold rounded-xl shadow-md shadow-red-900/20 transform hover:-translate-y-0.5 active:scale-[0.98] transition-all duration-200 text-[11px] uppercase tracking-widest border-t border-white/10">
                KEMBALI KE LOGIN
            </a>
        </div>
    </div>
</x-guest-layout>
