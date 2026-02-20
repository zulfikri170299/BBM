<x-app-layout>
    <div class="py-6 sm:py-10 bg-slate-50 min-h-screen">
        <div class="max-w-lg mx-auto px-4 sm:px-6">
            <!-- Breadcrumb / Back Link -->
            <div class="mb-4">
                <a href="{{ route('admin.personels.index') }}"
                    class="inline-flex items-center gap-2 text-[11px] font-bold text-slate-400 hover:text-indigo-600 transition-all uppercase tracking-widest">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>

            <div class="bg-white rounded-[1.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <!-- Header Section -->
                <div class="p-5 sm:p-6 bg-slate-50/50 border-b border-slate-100">
                    <h2 class="text-xl font-black text-slate-800 tracking-tight">Tambah Personel</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">Input data personel secara ringkas</p>
                </div>

                <div class="p-5 sm:p-6">
                    <form action="{{ route('admin.personels.store') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <!-- Hidden Defaults -->
                        <input type="hidden" name="jenis_bbm" value="Pertalite">
                        <input type="hidden" name="saldo" value="0">

                        <!-- Satker -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Satuan Kerja</label>
                            <div class="relative group">
                                <select name="satker_id"
                                    class="w-full h-11 pl-4 pr-10 rounded-xl border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all appearance-none"
                                    required>
                                    <option value="">Pilih Satuan Kerja...</option>
                                    @foreach($satkers as $satker)
                                        <option value="{{ $satker->id }}" {{ old('satker_id') == $satker->id ? 'selected' : '' }}>
                                            {{ $satker->nama_satker }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                            @error('satker_id') <p class="mt-1 text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Nama -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama') }}"
                                class="w-full h-11 px-4 rounded-xl border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-700 placeholder:text-slate-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all"
                                placeholder="Contoh: Budi Santoso" required>
                            @error('nama') <p class="mt-1 text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- NRP -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">NRP / NIP</label>
                            <input type="text" name="nrp" value="{{ old('nrp') }}"
                                class="w-full h-11 px-4 rounded-xl border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-700 placeholder:text-slate-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all"
                                placeholder="Masukkan NRP/NIP" required>
                            @error('nrp') <p class="mt-1 text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Action Button -->
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full h-11 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-indigo-200 flex items-center justify-center gap-2 group">
                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Personel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <p class="mt-6 text-center text-slate-400 text-[9px] font-black uppercase tracking-widest">Biro Logistik &copy; 2026</p>
        </div>
    </div>
</x-app-layout>