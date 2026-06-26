<x-app-layout>
    <div class="py-6 sm:py-10 bg-slate-800/50 min-h-screen px-2 sm:px-6 lg:px-8">
        <div class="max-w-lg mx-auto px-4 sm:px-6">
            <!-- Breadcrumb / Back Link -->
            <div class="mb-4">
                <a href="{{ route('satker.personels.index') }}"
                    class="inline-flex items-center gap-2 text-[11px] font-bold text-slate-400 hover:text-indigo-600 transition-all uppercase tracking-widest">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>

            <div
                class="bg-slate-900 border border-white/5 rounded-[1.5rem] shadow-xl shadow-slate-200/50 border border-white/5 overflow-hidden">
                <!-- Header Section -->
                <div class="p-5 sm:p-6 bg-slate-800/50 border-b border-white/5">
                    <h2 class="text-xl font-black text-slate-200 tracking-tight">Edit Personel</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">Perbarui data personel
                        scra ringkas</p>
                </div>

                <div class="p-5 sm:p-6">
                    <form action="{{ route('satker.personels.update', $personel) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <!-- Nama -->
                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nama
                                Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama', $personel->nama) }}"
                                class="w-full h-11 px-4 rounded-xl border-white/10 bg-slate-800/50 text-xs font-bold text-slate-300 placeholder:text-slate-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all"
                                placeholder="Contoh: Budi Santoso" required>
                            @error('nama') <p class="mt-1 text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NRP -->
                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">NRP
                                / NIP</label>
                            <input type="text" name="nrp" value="{{ old('nrp', $personel->nrp) }}"
                                class="w-full h-11 px-4 rounded-xl border-white/10 bg-slate-800/50 text-xs font-bold text-slate-300 placeholder:text-slate-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all"
                                placeholder="Masukkan NRP/NIP" 
                                inputmode="numeric"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                required>
                            @error('nrp') <p class="mt-1 text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenis BBM -->
                        <div class="relative group">
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Jenis
                                BBM</label>
                            <select name="jenis_bbm"
                                class="tom-select w-full {{ auth()->user()->role !== 'super_admin' && $personel->saldo > ? 'bg-slate-800 cursor-not-allowed' : '' }}"
                                {{ auth()->user()->role !== 'super_admin' && $personel->saldo > 0 ? 'disabled' : 'required' }}>
                                <option value="">Pilih Jenis BBM...</option>
                                <option value="Pertamax" {{ old('jenis_bbm', $personel->jenis_bbm) == 'Pertamax' ? 'selected' : '' }}>Pertamax</option>
                                <option value="Pertamina Dex" {{ old('jenis_bbm', $personel->jenis_bbm) == 'Pertamina Dex' ? 'selected' : '' }}>Pertamina Dex</option>
                            </select>
                            @if (auth()->user()->role !== 'super_admin' && $personel->saldo > 0)
                                <input type="hidden" name="jenis_bbm" value="{{ $personel->jenis_bbm }}">
                                <!-- Tooltip -->
                                <div class="absolute -top-7 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-all bg-slate-800 text-white text-[9px] py-1 px-2 rounded shadow-lg whitespace-nowrap z-50 font-bold uppercase tracking-wider">
                                    Jenis BBM tidak dapat diubah selama masih ada Saldo
                                </div>
                            @endif
                            @error('jenis_bbm') <p class="mt-1 text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Action Button -->
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full h-11 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-indigo-500/30 flex items-center justify-center gap-2 group">
                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Perbarui Personel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <p class="mt-6 text-center text-slate-400 text-[9px] font-black uppercase tracking-widest">Biro Logistik
                &copy; 2026</p>
        </div>
    </div>
</x-app-layout>