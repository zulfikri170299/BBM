<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-8">
        <!-- Header Section -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.laporan-stok-bbm.index') }}" class="p-2 bg-slate-900 border border-white/5 border border-white/10 rounded-xl hover:bg-slate-800/50 transition-all">
                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white">Edit Data Sinkronisasi</h1>
                <p class="text-slate-400 text-sm mt-1">Ubah data stok awal atau waktu sinkronisasi.</p>
            </div>
        </div>

        <div class="max-w-2xl">
            <div class="bg-slate-900 border border-white/5 rounded-2xl shadow-sm border border-white/10 overflow-hidden">
                <form action="{{ route('admin.laporan-stok-bbm.update', $sinkronisasi->id) }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2">Stok Awal Pertamax (Liter)</label>
                            <div class="relative">
                                <input type="number" step="1" name="stok_awal_pertamax" value="{{ $sinkronisasi->stok_awal_pertamax }}" required
                                    class="w-full pl-4 pr-12 py-3 bg-slate-800/50 border border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium text-white">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">LITER</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2">Stok Awal Pertamina Dex (Liter)</label>
                            <div class="relative">
                                <input type="number" step="1" name="stok_awal_dex" value="{{ $sinkronisasi->stok_awal_dex }}" required
                                    class="w-full pl-4 pr-12 py-3 bg-slate-800/50 border border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium text-white">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">LITER</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2">Waktu Sinkronisasi</label>
                            <input type="datetime-local" name="created_at" value="{{ $sinkronisasi->created_at->format('Y-m-d\TH:i') }}" required
                                class="w-full px-4 py-3 bg-slate-800/50 border border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium text-white">
                            <p class="text-[10px] text-slate-400 mt-1 italic">* Mengubah waktu akan mempengaruhi perhitungan pemakaian di riwayat sekitarnya.</p>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="submit" 
                            class="flex-1 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/30 transition-all active:scale-[0.98]">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.laporan-stok-bbm.index') }}" 
                            class="px-8 py-4 bg-slate-800 hover:bg-slate-200 text-slate-400 rounded-xl font-bold transition-all active:scale-[0.98]">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
