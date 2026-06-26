<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-8 max-w-3xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('pembelian-bbm.index') }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white leading-tight">Edit Data Pembelian BBM</h1>
                <p class="text-xs text-slate-400 mt-1">Ubah data riwayat pembelian BBM Pertamax & Pertamina Dex.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="p-4 bg-rose-50 text-rose-700 rounded-xl border border-rose-100">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <p class="font-bold text-sm">Terjadi Kesalahan</p>
                </div>
                <ul class="list-disc list-inside text-sm pl-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-slate-900 border border-white/5 rounded-2xl border border-white/10 shadow-sm overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-white/5 bg-slate-800/50">
                <h3 class="text-base sm:text-lg font-bold text-slate-200 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Form Edit Pembelian
                </h3>
            </div>
            <form action="{{ route('pembelian-bbm.update', $pembelianBbm->id) }}" method="POST" class="p-4 sm:p-6 space-y-5" autocomplete="off">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">1. Pilih Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', $pembelianBbm->tanggal->format('Y-m-d')) }}" data-default-date="{{ old('tanggal', $pembelianBbm->tanggal->format('Y-m-d')) }}" class="w-full px-4 py-2.5 bg-slate-800/50 border border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none font-semibold text-sm" required>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">2. Jenis BBM</label>
                    <select name="jenis_bbm" required class="tom-select w-full bg-slate-800/50 border-white/10 rounded-xl transition-all font-semibold text-sm">
                        <option value="">Pilih Jenis BBM</option>
                        <option value="Pertamax" {{ old('jenis_bbm', $pembelianBbm->jenis_bbm) == 'Pertamax' ? 'selected' : '' }}>Pertamax</option>
                        <option value="Pertamina Dex" {{ old('jenis_bbm', $pembelianBbm->jenis_bbm) == 'Pertamina Dex' ? 'selected' : '' }}>Pertamina Dex</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">3. Jumlah BBM (Liter)</label>
                    <input type="number" name="jumlah" value="{{ old('jumlah', $pembelianBbm->jumlah) }}" step="1" min="1" class="w-full px-4 py-2.5 bg-slate-800/50 border border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none" placeholder="Masukkan jumlah liter..." required>
                </div>
                
                <div class="pt-4 mt-6 border-t border-white/5 flex gap-3">
                    <a href="{{ route('pembelian-bbm.index') }}" class="flex-1 py-3 bg-slate-800 hover:bg-slate-200 text-slate-300 rounded-xl font-bold transition-all active:scale-95 flex items-center justify-center">
                        Batal
                    </a>
                    <button type="submit" class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/30 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
