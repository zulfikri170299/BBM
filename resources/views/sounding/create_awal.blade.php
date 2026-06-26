<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-8 max-w-4xl mx-auto">
        <div class="flex items-center gap-4">
            <a href="{{ route($rolePrefix.'.sounding.create') }}" class="p-2 bg-slate-800 hover:bg-slate-700 rounded-xl transition-colors">
                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-3xl font-black text-white tracking-tight">Tambah Data Sounding Awal</h1>
        </div>

        @if(session('error'))
            <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl shadow-sm flex items-center gap-3">
                <div class="p-2 bg-rose-500 rounded-lg text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-rose-800 font-bold text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-slate-900 border border-white/10 rounded-3xl p-8">
            <form action="{{ route($rolePrefix.'.sounding.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase mb-2">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" required value="{{ old('tanggal', date('Y-m-d')) }}"
                               class="w-full bg-slate-800 border border-white/10 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase mb-2">Jenis BBM</label>
                        <select name="jenis_bbm" id="jenis_bbm" required class="tom-select w-full" data-placeholder="Pilih BBM">
                            <option value="">Pilih BBM</option>
                            <option value="PERTAMAX" {{ old('jenis_bbm') == 'PERTAMAX' ? 'selected' : '' }}>PERTAMAX</option>
                            <option value="PERTAMINA DEX" {{ old('jenis_bbm') == 'PERTAMINA DEX' ? 'selected' : '' }}>PERTAMINA DEX</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase mb-2">Stok Awal</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="stok_awal" id="stok_awal" required value="{{ old('stok_awal') }}"
                                   class="w-full bg-slate-800 border border-white/10 rounded-xl text-white pl-4 pr-12 py-3 focus:ring-2 focus:ring-indigo-500" placeholder="0">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold">Ltr</span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase mb-2">Dokumentasi (Foto)</label>
                        <input type="file" name="dokumentasi" accept="image/*"
                               class="w-full bg-slate-800 border border-white/10 rounded-xl text-white file:mr-4 file:py-3 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">
                        <p class="text-[10px] text-slate-500 mt-2">Gambar akan dikompres secara otomatis.</p>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl transition-colors shadow-lg shadow-indigo-500/30">
                        SIMPAN DATA AWAL
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
