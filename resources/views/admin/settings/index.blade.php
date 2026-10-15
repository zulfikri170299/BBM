<x-app-layout>
    <div class="p-3 sm:p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-sm text-white shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-2xl font-bold text-slate-800 dark:text-white tracking-tight">Pengaturan Sistem</h1>
                        <span class="inline-flex items-center px-2 py-0.5 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-500/20 rounded-md text-[10px] font-bold">
                            Super Admin
                        </span>
                    </div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-0.5">Kelola hak akses modul operasional dan konfigurasi keamanan akun secara terpusat.</p>
                </div>
            </div>

            <!-- Header Quick Action Button -->
            <button type="submit" form="settings-form"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs font-bold shadow-sm shadow-indigo-600/25 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                </svg>
                <span>Simpan Perubahan</span>
            </button>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/40 rounded-xl shadow-sm"
                x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)">
                <div class="flex-shrink-0 p-1.5 bg-emerald-500 text-white rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="text-xs font-semibold text-emerald-800 dark:text-emerald-300 flex-1">{{ session('success') }}</p>
                <button @click="show = false" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Form 2-Column Responsive Grid -->
        <form id="settings-form" action="{{ route('admin.settings.update') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <!-- Column 1: Hak Akses Data Kendaraan (7 cols) -->
                <div class="lg:col-span-7 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 bg-slate-50/70 dark:bg-slate-800/40 border-b border-slate-200 dark:border-white/10 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center border border-blue-100 dark:border-blue-500/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 dark:text-white">Hak Akses Kendaraan Satker</h3>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Kontrol kewenangan input, edit, dan import data kendaraan.</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[10px] font-bold rounded-lg border border-slate-200 dark:border-white/5">
                            3 Modul
                        </span>
                    </div>

                    <div class="p-5 space-y-3.5">
                        <!-- Option 1: Tambah Kendaraan -->
                        <div class="flex items-center justify-between p-3.5 rounded-xl border border-slate-200/80 dark:border-white/5 bg-slate-50/50 dark:bg-slate-800/40 hover:bg-slate-50 dark:hover:bg-slate-800/70 transition">
                            <div class="flex items-center gap-3 pr-3">
                                <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-500/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-800 dark:text-white">Tambah Data Kendaraan</h4>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Izinkan akun Satker menambah kendaraan manual.</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0 select-none">
                                <input type="checkbox" name="satker_can_create_kendaraan" value="1" class="sr-only peer" {{ ($settings['satker_can_create_kendaraan'] ?? '1') == '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all after:shadow-sm peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>

                        <!-- Option 2: Edit Kendaraan -->
                        <div class="flex items-center justify-between p-3.5 rounded-xl border border-slate-200/80 dark:border-white/5 bg-slate-50/50 dark:bg-slate-800/40 hover:bg-slate-50 dark:hover:bg-slate-800/70 transition">
                            <div class="flex items-center gap-3 pr-3">
                                <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 border border-indigo-100 dark:border-indigo-500/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-800 dark:text-white">Edit Data Kendaraan</h4>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Izinkan akun Satker mengubah rincian kendaraan.</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0 select-none">
                                <input type="checkbox" name="satker_can_edit_kendaraan" value="1" class="sr-only peer" {{ ($settings['satker_can_edit_kendaraan'] ?? '1') == '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all after:shadow-sm peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>

                        <!-- Option 3: Import Excel -->
                        <div class="flex items-center justify-between p-3.5 rounded-xl border border-slate-200/80 dark:border-white/5 bg-slate-50/50 dark:bg-slate-800/40 hover:bg-slate-50 dark:hover:bg-slate-800/70 transition">
                            <div class="flex items-center gap-3 pr-3">
                                <div class="w-8 h-8 rounded-xl bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0 border border-purple-100 dark:border-purple-500/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-800 dark:text-white">Import Data via Excel</h4>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Izinkan akun Satker import kendaraan dari spreadsheet.</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0 select-none">
                                <input type="checkbox" name="satker_can_import_kendaraan" value="1" class="sr-only peer" {{ ($settings['satker_can_import_kendaraan'] ?? '1') == '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all after:shadow-sm peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Column 2: Kontrol Akun & Panduan (5 cols) -->
                <div class="lg:col-span-5 space-y-6">
                    <!-- Global Satker Login Control Card -->
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 bg-slate-50/70 dark:bg-slate-800/40 border-b border-slate-200 dark:border-white/10 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-xl flex items-center justify-center border border-amber-100 dark:border-amber-500/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800 dark:text-white">Otorisasi Login Satker</h3>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Penguncian autentikasi seluruh Satker.</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 space-y-4">
                            <div class="flex items-start justify-between gap-3 p-3.5 rounded-xl border border-slate-200/80 dark:border-white/5 bg-slate-50/50 dark:bg-slate-800/40 hover:bg-slate-50 dark:hover:bg-slate-800/70 transition">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 border border-rose-100 dark:border-rose-500/20 mt-0.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-800 dark:text-white">Akses Akun Seluruh Satker</h4>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed">
                                            Jika dimatikan, seluruh Admin Satker tidak dapat login ke sistem.
                                        </p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer shrink-0 select-none">
                                    <input type="checkbox" name="is_satker_enabled" value="1" class="sr-only peer" {{ ($settings['is_satker_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all after:shadow-sm peer-checked:bg-rose-600"></div>
                                </label>
                            </div>

                            <!-- Link to Individual User Control -->
                            <a href="{{ route('admin.users.index', ['role' => 'admin_satker']) }}"
                                class="flex items-center justify-between p-3 rounded-xl bg-indigo-50/70 hover:bg-indigo-100/70 dark:bg-indigo-950/30 dark:hover:bg-indigo-950/60 border border-indigo-200/80 dark:border-indigo-800/40 text-indigo-700 dark:text-indigo-400 transition group">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <span class="text-xs font-bold">Kelola Akses per Akun Satker</span>
                                </div>
                                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Information & Bottom Save Card -->
                    <div class="bg-gradient-to-br from-slate-900 to-indigo-950 text-white rounded-2xl border border-indigo-500/20 shadow-sm p-5 relative overflow-hidden">
                        <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-indigo-500/10 rounded-full blur-xl pointer-events-none"></div>
                        <div class="flex items-center gap-2.5 mb-2.5">
                            <svg class="w-4 h-4 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h4 class="text-xs font-bold text-white tracking-wide uppercase">Informasi Kebijakan</h4>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed font-normal mb-4">
                            Setiap perubahan pengaturan sistem akan diterapkan secara instan ke seluruh sesi aktif pengguna Satker di Polda NTB.
                        </p>
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 active:scale-95 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-600/30 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            <span>Simpan Semua Pengaturan</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>