<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex items-center gap-3.5">
            <a href="{{ route('admin.petugas-spbp.index') }}" 
                class="w-10 h-10 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 shadow-sm transition-all hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-white tracking-tight">Edit Petugas SPBP</h1>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-0.5">Perbarui data petugas penanda tangan untuk laporan dokumen PDF.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="max-w-2xl bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-slate-50/70 dark:bg-slate-800/40 border-b border-slate-200 dark:border-white/10">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white">Edit Data Petugas</h3>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Perbarui informasi nama lengkap, pangkat/NRP, atau urutan tanda tangan</p>
            </div>

            <form action="{{ route('admin.petugas-spbp.update', $petugas_spbp) }}" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <!-- Nama -->
                <div>
                    <label for="nama" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $petugas_spbp->nama) }}" required
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800/60 border {{ $errors->has('nama') ? 'border-red-500' : 'border-slate-200 dark:border-white/10' }} rounded-xl text-sm font-semibold text-slate-800 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition"
                            placeholder="Contoh: I KOMANG DARMAJA, S.Sos.">
                    </div>
                    @error('nama')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pangkat/NRP -->
                <div>
                    <label for="pangkat_nrp" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">
                        Pangkat / NRP / NIP <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <input type="text" name="pangkat_nrp" id="pangkat_nrp" value="{{ old('pangkat_nrp', $petugas_spbp->pangkat_nrp) }}" required
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800/60 border {{ $errors->has('pangkat_nrp') ? 'border-red-500' : 'border-slate-200 dark:border-white/10' }} rounded-xl text-sm font-semibold text-slate-800 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition"
                            placeholder="Contoh: AKP NRP 79070976">
                    </div>
                    @error('pangkat_nrp')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Urutan -->
                <div>
                    <label for="urutan" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">
                        Urutan Tampilan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                            </svg>
                        </div>
                        <input type="number" name="urutan" id="urutan" value="{{ old('urutan', $petugas_spbp->urutan) }}" required min="1"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800/60 border {{ $errors->has('urutan') ? 'border-red-500' : 'border-slate-200 dark:border-white/10' }} rounded-xl text-sm font-semibold text-slate-800 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                        Menentukan nomor urut kolom penanda tangan dari kiri ke kanan pada cetak laporan PDF.
                    </p>
                    @error('urutan')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-white/10">
                    <a href="{{ route('admin.petugas-spbp.index') }}" 
                        class="px-4 py-2.5 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">
                        Batal
                    </a>
                    <button type="submit" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs font-bold shadow-sm shadow-indigo-600/20 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
