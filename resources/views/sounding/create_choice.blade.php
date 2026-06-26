<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-6 max-w-4xl mx-auto">
        <div class="flex items-center gap-3">
            <a href="{{ route($rolePrefix.'.sounding.index') }}" class="p-1.5 bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-xl font-black text-white tracking-tight">Pilih Jenis Sounding</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
            <a href="{{ route($rolePrefix.'.sounding.create', ['type' => 'awal']) }}" 
               class="group relative bg-slate-900 border border-white/10 rounded-2xl p-5 hover:bg-slate-800 hover:border-indigo-500/50 transition-all overflow-hidden flex flex-col items-center text-center shadow-lg shadow-indigo-500/10">
                <div class="w-14 h-14 bg-indigo-500/20 text-indigo-400 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-black text-white mb-1.5">Sounding Awal</h3>
                <p class="text-slate-400 text-xs leading-relaxed">
                    Dilakukan pada pagi hari atau awal pergantian shift.<br>
                    Anda hanya perlu menginputkan <strong class="text-indigo-300">Stok Awal</strong> BBM.
                </p>
                
                <div class="absolute inset-0 border-2 border-indigo-500 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </a>

            <a href="{{ route($rolePrefix.'.sounding.create', ['type' => 'akhir']) }}" 
               class="group relative bg-slate-900 border border-white/10 rounded-2xl p-5 hover:bg-slate-800 hover:border-rose-500/50 transition-all overflow-hidden flex flex-col items-center text-center shadow-lg shadow-rose-500/10">
                <div class="w-14 h-14 bg-rose-500/20 text-rose-400 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </div>
                <h3 class="text-lg font-black text-white mb-1.5">Sounding Akhir</h3>
                <p class="text-slate-400 text-xs leading-relaxed">
                    Dilakukan pada sore/malam hari.<br>
                    Menginputkan <strong class="text-rose-300">Stok Akhir</strong>, serta perhitungan pengeluaran & susut secara otomatis.
                </p>

                <div class="absolute inset-0 border-2 border-rose-500 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </a>
        </div>
    </div>
</x-app-layout>
