<x-app-layout>
    <div class="p-2 sm:p-4 lg:p-6 space-y-4">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-white leading-tight">Backup & Restore Database</h1>
                <p class="mt-1 text-xs text-slate-400">Ekspor data untuk pencadangan atau impor dari file SQL untuk pemulihan (restore).</p>
            </div>
        </div>

        @if(session('success'))
            <div class="p-3 bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-100 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="font-medium text-xs">{{ session('success') }}</p>
            </div>
        @endif
        
        @if ($errors->any())
            <div class="p-3 bg-rose-50 text-rose-700 rounded-lg border border-rose-100">
                <div class="flex items-center gap-2 mb-1">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <p class="font-bold text-xs">Gagal memproses permintaan</p>
                </div>
                <ul class="list-disc list-inside text-[11px] pl-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Backup / Export Section -->
            <div class="bg-slate-900 border border-white/10 rounded-xl shadow-2xl overflow-hidden flex flex-col">
                <div class="px-4 sm:px-5 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 shrink-0">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <div class="p-1.5 bg-slate-900/20 rounded-lg">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </div>
                        Export Database
                    </h3>
                </div>
                <div class="p-3 sm:p-5 space-y-3 flex flex-col flex-1 justify-between">
                    <div>
                        <p class="text-xs text-slate-400 leading-relaxed mb-4">
                            Fitur ini akan mengekspor seluruh data yang ada dalam database sistem Pospolmas menjadi file dengan format <strong>.sql</strong>. 
                            Gunakan secara berkala untuk keperluan pencadangan (backup) data.
                        </p>
                        
                        <div class="flex items-start gap-2 sm:gap-3 p-3 bg-blue-50 rounded-xl border border-blue-200 mb-3">
                            <div class="p-1 bg-blue-100 text-blue-600 rounded-lg mt-0.5 shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="text-[11px] text-blue-700">
                                <p class="font-bold mb-0.5 flex flex-col sm:flex-row gap-1">Note:</p>
                                <p class="leading-relaxed">
                                    Fitur otomatis menyesuaikan mesin database. File yang diunduh dapat berupa Format SQL (untuk tipe server) atau Berkas <strong>.sqlite</strong> langsung.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.backup.export') }}" method="POST" class="space-y-3 mt-auto">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">PIN Keamanan Verifikasi</label>
                            <input type="password" name="topup_password" class="w-full px-3 py-2 bg-slate-900 border-2 border-white/10 rounded-xl text-xs sm:text-xs font-medium text-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-400" placeholder="Masukkan PIN" required autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                            @if(!auth()->user()->topup_password)
                                <p class="text-[10px] text-red-500 mt-1">
                                    <svg class="w-2.5 h-2.5 inline mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    Anda belum mengatur PIN keamanan. <a href="{{ route('profile.edit') }}" class="underline hover:text-blue-400">Atur di Profil</a>.
                                </p>
                            @endif
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/40 transition-all hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2 text-sm uppercase tracking-wider mt-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Export Sekarang
                        </button>
                    </form>
                </div>
            </div>

            <!-- Import / Restore Section -->
            <div class="bg-slate-900 border border-white/10 rounded-xl shadow-2xl overflow-hidden flex flex-col">
                <div class="px-4 sm:px-5 py-4 bg-gradient-to-r from-rose-500 to-red-600 shrink-0">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <div class="p-1.5 bg-slate-900/20 rounded-lg">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </div>
                        Import / Restore
                    </h3>
                </div>
                <div class="p-3 sm:p-5 flex flex-col flex-1 justify-between space-y-3">
                    <div>
                        <p class="text-xs text-slate-400 leading-relaxed mb-4">
                            Gunakan fitur ini untuk memulihkan seluruh data operasional dari file backup <strong>.sql / .sqlite</strong>.
                        </p>

                        <div class="flex items-start gap-2 sm:gap-3 p-3 bg-red-50 rounded-xl border border-red-200 mb-3">
                            <div class="p-1 bg-red-100 text-red-600 rounded-lg mt-0.5 shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div class="text-[11px] text-red-700">
                                <p class="font-bold mb-0.5">Peringatan Keamanan Data!</p>
                                <p class="leading-relaxed">
                                    Fitur ini akan mengeksekusi file sepenuhnya. <strong class="text-red-600">Semua data saat ini bisa dihapus/ditimpa secara permanen.</strong> Pastikan menggunakan sumber file asli!
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.backup.import') }}" method="POST" enctype="multipart/form-data" class="space-y-3 mt-auto">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Pilih File Backup (.sql / .sqlite)</label>
                            <input type="file" name="backup_file" accept=".sql,.sqlite,.db" 
                                class="w-full px-3 py-2 bg-slate-900 border-2 border-white/10 rounded-xl text-xs sm:text-xs text-slate-200 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">PIN Keamanan Verifikasi</label>
                            <input type="password" name="topup_password" class="w-full px-3 py-2 bg-slate-900 border-2 border-white/10 rounded-xl text-xs sm:text-xs font-medium text-slate-200 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all placeholder:text-slate-400" placeholder="Masukkan PIN" required autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                            @if(!auth()->user()->topup_password)
                                <p class="text-[10px] text-red-500 mt-1">
                                    <svg class="w-2.5 h-2.5 inline mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    Anda belum mengatur PIN keamanan. <a href="{{ route('profile.edit') }}" class="underline hover:text-rose-400">Atur di Profil</a>.
                                </p>
                            @endif
                        </div>
                        <button type="submit" 
                            data-confirm="Tindakan ini mungkin menghapus semua data Anda saat ini dan menggantinya dengan data dari file. Anda yakin ingin melanjutkan import?"
                            data-confirm-type="danger"
                            data-confirm-title="Peringatan Restore Data!"
                            data-confirm-text="Ya, Restore Sekarang!"
                            class="w-full py-2.5 mt-2 bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white rounded-xl font-bold shadow-lg shadow-red-500/30 hover:shadow-red-500/40 transition-all hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2 text-sm uppercase tracking-wider">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Restore Data
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
