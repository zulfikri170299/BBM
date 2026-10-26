<x-app-layout>
    <div class="p-3 sm:p-6 lg:p-7 space-y-6 max-w-2xl mx-auto">
        
        <!-- Header Nav Back -->
        <div class="flex items-center gap-3">
            <a href="{{ route('pembelian-bbm.index') }}" 
               class="p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 shadow-sm hover:shadow transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white leading-tight">
                    Edit Data Pembelian BBM
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Perbarui rincian tanggal, jenis, atau kuantum BBM yang masuk.
                </p>
            </div>
        </div>

        @if ($errors->any())
            <div class="p-4 bg-rose-500/10 dark:bg-rose-950/40 border border-rose-500/30 rounded-2xl">
                <div class="flex items-center gap-2 mb-1.5 text-rose-600 dark:text-rose-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <p class="font-black text-xs uppercase tracking-wider">Terdapat Kesalahan</p>
                </div>
                <ul class="list-disc list-inside text-xs font-semibold text-rose-800 dark:text-rose-300 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Card Form Edit -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl sm:rounded-3xl shadow-xl shadow-slate-900/5 dark:shadow-indigo-950/20 overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-white/10 bg-slate-50/80 dark:bg-slate-800/60 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-cyan-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-indigo-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm sm:text-base font-black text-slate-900 dark:text-white">
                        Formulir Perubahan Data
                    </h2>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">
                        ID Pembelian: #{{ $pembelianBbm->id }}
                    </p>
                </div>
            </div>

            <form action="{{ route('pembelian-bbm.update', $pembelianBbm->id) }}" method="POST" class="p-5 sm:p-6 space-y-5" autocomplete="off" x-data="{ jenis: '{{ old('jenis_bbm', $pembelianBbm->jenis_bbm) }}' }">
                @csrf
                @method('PUT')
                
                <!-- Tanggal -->
                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>1. Tanggal Penerimaan / DO</span>
                    </label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', $pembelianBbm->tanggal->format('Y-m-d')) }}" 
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-bold text-xs sm:text-sm text-slate-800 dark:text-slate-200 transition-all outline-none" required>
                </div>
                
                <!-- Jenis BBM -->
                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                        <span>2. Jenis Bahan Bakar (BBM)</span>
                    </label>

                    <input type="hidden" name="jenis_bbm" :value="jenis">

                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" @click="jenis = 'Pertamax'"
                            :class="jenis === 'Pertamax' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/30 border-blue-500' : 'bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-white/10 hover:border-blue-400'"
                            class="p-3 rounded-xl border text-center transition-all duration-200 flex flex-col items-center justify-center gap-1">
                            <span class="w-2 h-2 rounded-full" :class="jenis === 'Pertamax' ? 'bg-cyan-300 animate-pulse' : 'bg-blue-500'"></span>
                            <span class="text-xs font-black uppercase tracking-wider">Pertamax</span>
                        </button>

                        <button type="button" @click="jenis = 'Pertamina Dex'"
                            :class="jenis === 'Pertamina Dex' ? 'bg-rose-600 text-white shadow-md shadow-rose-500/30 border-rose-500' : 'bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-white/10 hover:border-rose-400'"
                            class="p-3 rounded-xl border text-center transition-all duration-200 flex flex-col items-center justify-center gap-1">
                            <span class="w-2 h-2 rounded-full" :class="jenis === 'Pertamina Dex' ? 'bg-amber-300 animate-pulse' : 'bg-rose-500'"></span>
                            <span class="text-xs font-black uppercase tracking-wider">Pertamina Dex</span>
                        </button>
                    </div>
                </div>
                
                <!-- Kuantum Jumlah -->
                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                        </svg>
                        <span>3. Jumlah BBM (Liter)</span>
                    </label>
                    <div class="relative">
                        <input type="number" name="jumlah" value="{{ old('jumlah', $pembelianBbm->jumlah) }}" step="1" min="1" 
                            class="w-full pl-3.5 pr-14 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono font-black text-sm text-slate-900 dark:text-white transition-all outline-none" 
                            placeholder="Masukkan kuantum liter..." required>
                        <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-xs font-black text-indigo-500 uppercase">
                            Liter
                        </span>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="pt-4 border-t border-slate-200 dark:border-white/10 flex items-center gap-3">
                    <a href="{{ route('pembelian-bbm.index') }}" 
                       class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl font-black text-xs uppercase tracking-wider transition-all flex items-center justify-center border border-slate-200 dark:border-white/5">
                        Batal
                    </a>
                    <button type="submit" 
                            class="flex-1 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white rounded-xl font-black text-xs uppercase tracking-wider shadow-lg shadow-indigo-600/30 transition-all active:scale-[0.98] flex items-center justify-center gap-2 border border-white/15">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
