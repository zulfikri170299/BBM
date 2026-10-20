<x-app-layout>
    <div class="p-2 sm:p-4 lg:p-6 space-y-4 sm:space-y-6">
        <!-- Header Halaman -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-1.5 sm:gap-2">
            <div>
                <h1 class="text-lg sm:text-2xl font-bold text-slate-800 dark:text-white leading-tight">Backup & Restore Database</h1>
                <p class="mt-0.5 text-xs text-slate-600 dark:text-slate-400">Pencadangan manual, restore SQL, dan auto-backup ke Google Drive.</p>
            </div>
        </div>

        <!-- Flash Notifications -->
        @if(session('success'))
            <div class="p-3.5 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 rounded-xl border border-emerald-200 dark:border-emerald-800/50 flex items-center gap-2.5 shadow-sm">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="font-semibold text-xs sm:text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('warning'))
            <div class="p-3.5 bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 rounded-xl border border-amber-200 dark:border-amber-800/50 flex items-center gap-2.5 shadow-sm">
                <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <p class="font-semibold text-xs sm:text-sm">{{ session('warning') }}</p>
            </div>
        @endif
        
        @if ($errors->any())
            <div class="p-3.5 bg-rose-50 dark:bg-rose-950/40 text-rose-800 dark:text-rose-300 rounded-xl border border-rose-200 dark:border-rose-800/50 shadow-sm">
                <div class="flex items-center gap-2 mb-1">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="font-bold text-xs sm:text-sm">Gagal memproses permintaan</p>
                </div>
                <ul class="list-disc list-inside text-xs pl-2 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- SECTION UTAMA: AUTO-BACKUP KE GOOGLE DRIVE -->
        <div x-data="{ showDriveSettings: false, tz: '{{ old('backup_gdrive_timezone', $gdriveTimezone) }}' }" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl shadow-xl overflow-hidden">
            <!-- Header Card Gradient (Compact & Bersih di Mobile) -->
            <div class="p-3.5 sm:px-6 sm:py-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-emerald-600 text-white">
                <div class="flex items-center justify-between gap-2.5 sm:gap-3">
                    <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                        <div class="p-2 sm:p-2.5 bg-white/15 backdrop-blur-md rounded-xl shrink-0 border border-white/20">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap">
                                <h2 class="text-sm sm:text-lg font-bold truncate">Auto-Backup Google Drive</h2>
                                <span class="px-2 py-0.5 rounded-full text-[10px] sm:text-[11px] font-extrabold uppercase tracking-wider bg-white/20 backdrop-blur-sm text-white border border-white/30 shrink-0 shadow-sm">
                                    {{ $gdriveTime }} {{ $gdriveTimezone }}
                                </span>
                            </div>
                            <p class="hidden sm:block text-xs text-blue-100/90 mt-0.5">Sistem akan mengekspor database secara otomatis setiap jam {{ $gdriveTime }} {{ $gdriveTimezone }} dan menyimpannya ke folder Google Drive Anda.</p>
                        </div>
                    </div>

                    <!-- Actions: Status Badge & Tombol Pengaturan -->
                    <div class="shrink-0 flex items-center gap-1.5 sm:gap-2">
                        @if($gdriveEnabled)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 sm:px-3 sm:py-1.5 rounded-xl bg-emerald-500 text-white text-[11px] sm:text-xs font-bold shadow-sm shadow-emerald-500/20">
                                <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-white animate-pulse"></span>
                                <span class="hidden sm:inline">Aktif ({{ $gdriveTime }})</span>
                                <span class="sm:hidden">Aktif</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 sm:px-3 sm:py-1.5 rounded-xl bg-slate-800/60 text-slate-300 text-[11px] sm:text-xs font-semibold border border-white/10">
                                <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-slate-400"></span>
                                Nonaktif
                            </span>
                        @endif

                        <!-- Tombol Icon Pengaturan -->
                        <button type="button" 
                            @click="showDriveSettings = !showDriveSettings" 
                            class="inline-flex items-center gap-1 sm:gap-1.5 px-2.5 py-1 sm:px-3 sm:py-1.5 rounded-xl bg-white/20 hover:bg-white/30 backdrop-blur-md text-white text-[11px] sm:text-xs font-bold transition shadow-sm border border-white/20 active:scale-95 cursor-pointer"
                            :title="showDriveSettings ? 'Sembunyikan Pengaturan' : 'Buka Pengaturan Link Drive'">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 transition-transform duration-300" :class="showDriveSettings ? 'rotate-90 text-amber-300' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span x-text="showDriveSettings ? 'Tutup' : 'Pengaturan'"></span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-3.5 sm:p-6 space-y-3 sm:space-y-4">
                <!-- Info Status Terakhir Backup (Grid 2 Kolom Ringkas di Mobile) -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 sm:gap-3 p-3 sm:p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-xs">
                    <div>
                        <span class="text-slate-400 font-medium block text-[10px] sm:text-xs mb-0.5">Terakhir Dijalankan:</span>
                        <span class="font-bold text-slate-700 dark:text-slate-200 text-[11px] sm:text-xs">
                            {{ $lastRun ? \Carbon\Carbon::parse($lastRun)->format('d M Y, H:i') : 'Belum pernah' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium block text-[10px] sm:text-xs mb-0.5">Status Eksekusi:</span>
                        @if(!$lastStatus)
                            <span class="text-slate-500 italic text-[11px]">Belum ada</span>
                        @elseif($lastStatus === 'success')
                            <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-bold text-[11px] sm:text-xs">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span>Berhasil</span>
                                <span class="hidden sm:inline">di Drive</span>
                            </span>
                        @elseif(str_starts_with($lastStatus, 'warning:'))
                            <span class="text-amber-600 dark:text-amber-400 font-semibold text-[11px] truncate block" title="{{ $lastStatus }}">
                                {{ \Illuminate\Support\Str::limit(substr($lastStatus, 8), 25) }}
                            </span>
                        @else
                            <span class="text-rose-600 dark:text-rose-400 font-semibold text-[11px] truncate block" title="{{ $lastStatus }}">
                                {{ \Illuminate\Support\Str::limit($lastStatus, 25) }}
                            </span>
                        @endif
                    </div>
                    <div class="col-span-2 sm:col-span-1 pt-1.5 border-t border-slate-200/60 dark:border-white/5 sm:border-0 sm:pt-0">
                        <span class="text-slate-400 font-medium block text-[10px] sm:text-xs mb-0.5">Berkas Terakhir:</span>
                        <span class="font-mono text-slate-700 dark:text-slate-200 font-semibold truncate block text-[11px] sm:text-xs" title="{{ $lastFileName }}">
                            {{ $lastFileName ?: '-' }}
                        </span>
                    </div>
                </div>

                <!-- Ringkasan Tautan Folder Aktif (Responsif & Rapi di Mobile) -->
                @if($gdriveFolderUrl)
                    <div class="p-3 sm:p-3.5 bg-slate-50/70 dark:bg-slate-800/40 rounded-xl border border-slate-200 dark:border-white/10 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="p-2 bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-xl shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                            </span>
                            <div class="min-w-0">
                                <span class="text-slate-400 text-[10px] font-semibold uppercase tracking-wider block">Folder Tujuan Google Drive:</span>
                                <a href="{{ $gdriveFolderUrl }}" target="_blank" class="font-bold text-blue-600 dark:text-blue-400 hover:underline truncate block text-xs" title="{{ $gdriveFolderUrl }}">
                                    {{ $gdriveFolderUrl }}
                                </a>
                            </div>
                        </div>
                        <div class="flex items-center justify-between sm:justify-end gap-2 pt-2 border-t border-slate-200/60 dark:border-white/5 sm:border-0 sm:pt-0 shrink-0">
                            @if($webhookUrl)
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/40 text-[10px] sm:text-[11px] font-bold inline-flex items-center gap-1">
                                    <svg class="w-3 h-3 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Web App Terhubung
                                </span>
                            @endif
                            <!-- Tombol Blokan Ubah Link -->
                            <button type="button" 
                                @click="showDriveSettings = true" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold text-xs rounded-xl shadow-sm shadow-blue-600/25 transition cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                <span>Ubah Link</span>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- FORM & MENU PENGATURAN (DISEMBUNYIKAN SECARA DEFAULT - BISA DIBUKA DENGAN ICON PENGATURAN / UBAH LINK) -->
                <div x-show="showDriveSettings" x-collapse x-cloak class="pt-4 border-t border-slate-200 dark:border-white/10 space-y-4">
                    <form action="{{ route('admin.backup.gdrive.settings') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <!-- Header Form Pengaturan -->
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-white/5">
                            <h3 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Form Pengaturan Jadwal, Link & Web App Google Drive
                            </h3>
                            <button type="button" @click="showDriveSettings = false" class="text-xs text-slate-400 hover:text-slate-600 font-semibold cursor-pointer">
                                ✕ Tutup
                            </button>
                        </div>

                        <!-- Toggle Aktifkan Auto-Backup -->
                        <div class="flex items-center justify-between p-3.5 sm:p-4 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50/50 dark:bg-slate-800/30">
                            <div class="pr-3">
                                <label for="backup_gdrive_enabled" class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white cursor-pointer">
                                    Aktifkan Pencadangan Otomatis (Setiap Hari)
                                </label>
                                <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    Server akan mengekspor database secara otomatis setiap jam yang ditentukan dan mengunggahnya langsung ke folder Google Drive Anda.
                                </p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="checkbox" id="backup_gdrive_enabled" name="backup_gdrive_enabled" value="1" {{ $gdriveEnabled ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-600"></div>
                            </label>
                        </div>

                        <!-- MENU EDIT JAM BACKUP & PILIHAN ZONA WAKTU (WITA / WIB) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50/50 dark:bg-slate-800/30">
                            <!-- Input Jam Backup -->
                            <div>
                                <label for="backup_gdrive_time" class="block text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 mb-1.5">
                                    Jam Eksekusi Backup <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </span>
                                    <input type="time" 
                                        id="backup_gdrive_time"
                                        name="backup_gdrive_time" 
                                        value="{{ old('backup_gdrive_time', $gdriveTime) }}" 
                                        class="w-full pl-10 pr-3 py-2.5 bg-white dark:bg-slate-900 border-2 border-slate-200 dark:border-white/10 rounded-xl text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all cursor-pointer"
                                        required>
                                </div>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                                    Format 24 jam (misal <strong>23:00</strong>). Ekspor otomatis berjalan setiap hari di jam ini.
                                </p>
                            </div>

                            <!-- Pilihan Zona Waktu (WITA / WIB) -->
                            <div>
                                <label class="block text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 mb-1.5">
                                    Pilihan Zona Waktu <span class="text-rose-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <!-- WITA -->
                                    <label class="relative flex items-center justify-between p-2.5 rounded-xl border-2 cursor-pointer transition-all"
                                        :class="tz === 'WITA' ? 'border-blue-500 bg-blue-50/70 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 shadow-sm font-bold' : 'border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold'">
                                        <div class="flex items-center gap-2">
                                            <input type="radio" name="backup_gdrive_timezone" value="WITA" x-model="tz" class="sr-only">
                                            <div>
                                                <div class="text-xs font-bold leading-tight">WITA</div>
                                                <div class="text-[10px] text-slate-400 dark:text-slate-400 font-normal">UTC+8 (Makassar/Bali)</div>
                                            </div>
                                        </div>
                                        <template x-if="tz === 'WITA'">
                                            <span class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center shrink-0">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            </span>
                                        </template>
                                    </label>

                                    <!-- WIB -->
                                    <label class="relative flex items-center justify-between p-2.5 rounded-xl border-2 cursor-pointer transition-all"
                                        :class="tz === 'WIB' ? 'border-blue-500 bg-blue-50/70 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 shadow-sm font-bold' : 'border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold'">
                                        <div class="flex items-center gap-2">
                                            <input type="radio" name="backup_gdrive_timezone" value="WIB" x-model="tz" class="sr-only">
                                            <div>
                                                <div class="text-xs font-bold leading-tight">WIB</div>
                                                <div class="text-[10px] text-slate-400 dark:text-slate-400 font-normal">UTC+7 (Jakarta)</div>
                                            </div>
                                        </div>
                                        <template x-if="tz === 'WIB'">
                                            <span class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center shrink-0">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            </span>
                                        </template>
                                    </label>
                                </div>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                                    Jadwal cron akan dieksekusi tepat sesuai zona waktu yang dipilih.
                                </p>
                            </div>
                        </div>

                        <!-- Input Link Folder Google Drive -->
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 mb-1.5">
                                Link Folder Google Drive <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                    </svg>
                                </span>
                                <input type="text" 
                                    name="backup_gdrive_folder_url" 
                                    value="{{ old('backup_gdrive_folder_url', $gdriveFolderUrl) }}" 
                                    placeholder="https://drive.google.com/drive/folders/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs" 
                                    class="w-full pl-10 pr-3 py-2.5 bg-white dark:bg-slate-900 border-2 border-slate-200 dark:border-white/10 rounded-xl text-xs sm:text-sm font-medium text-slate-800 dark:text-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-400"
                                    required>
                            </div>
                            <div class="flex items-center justify-between gap-2 mt-1.5 flex-wrap">
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                    Masukkan link folder Google Drive Anda. Sistem akan otomatis mendeteksi ID folder dari link tersebut.
                                </p>
                                @if($gdriveFolderId)
                                    <span class="text-[10px] font-mono px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 dark:bg-blue-950/50 dark:text-blue-300 font-semibold">
                                        ID Folder: {{ $gdriveFolderId }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Input URL Web App Google Drive (Penghubung) -->
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-1.5 flex-wrap">
                                <label class="block text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200">
                                    URL Web App Google Drive (Penghubung) <span class="text-rose-500">*</span>
                                </label>
                                @if($webhookUrl)
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/40 text-[10px] font-bold">
                                        ✓ Web App Terhubung
                                    </span>
                                @endif
                            </div>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                    </svg>
                                </span>
                                <input type="url" 
                                    name="backup_gdrive_webhook_url" 
                                    value="{{ old('backup_gdrive_webhook_url', $webhookUrl) }}" 
                                    placeholder="https://script.google.com/macros/s/AKfycb.../exec" 
                                    class="w-full pl-10 pr-3 py-2.5 bg-white dark:bg-slate-900 border-2 border-slate-200 dark:border-white/10 rounded-xl text-xs sm:text-sm font-medium text-slate-800 dark:text-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-400">
                            </div>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                                Tempelkan URL Web App Google Drive Anda di sini.
                            </p>
                        </div>

                        <!-- Panduan 1 Menit Tanpa Ribet -->
                        <div x-data="{ copied: false }" class="p-4 rounded-xl border border-blue-200 dark:border-blue-800/50 bg-blue-50/60 dark:bg-blue-950/20 space-y-3">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <h4 class="text-xs sm:text-sm font-bold text-blue-900 dark:text-blue-200 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Cara Mendapatkan URL Web App:
                                </h4>
                                <a href="https://script.google.com/home/start" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-600 text-white font-bold text-[11px] hover:bg-blue-700 transition shadow-sm">
                                    Buka script.google.com 
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </div>

                            <ol class="list-decimal list-inside text-xs text-blue-800 dark:text-blue-300 space-y-1.5 leading-relaxed">
                                <li>Klik tombol biru <strong>"Buka script.google.com"</strong> &gt; Klik <strong>Proyek Baru</strong>.</li>
                                <li>Hapus seluruh teks bawaan, lalu salin dan tempel kode di bawah ini:</li>
                            </ol>

                            <!-- Box Kode Script Siap Salin -->
                            <div class="relative mt-2">
                                <textarea id="scriptCode" readonly rows="5" class="w-full p-3 font-mono text-[11px] bg-slate-900 text-slate-100 rounded-xl border border-slate-700 focus:outline-none select-all leading-normal">function doPost(e) {
  try {
    var data = JSON.parse(e.postData.contents);
    var folder = DriveApp.getFolderById(data.folder_id);
    var decoded = Utilities.base64Decode(data.file_data);
    var blob = Utilities.newBlob(decoded, data.mimetype, data.filename);
    var file = folder.createFile(blob);
    return ContentService.createTextOutput(JSON.stringify({ status: 'success', id: file.getId() })).setMimeType(ContentService.MimeType.JSON);
  } catch (err) {
    return ContentService.createTextOutput(JSON.stringify({ status: 'error', message: err.toString() })).setMimeType(ContentService.MimeType.JSON);
  }
}</textarea>
                                <button type="button" 
                                    @click="
                                        navigator.clipboard.writeText(document.getElementById('scriptCode').value);
                                        copied = true;
                                        setTimeout(() => copied = false, 3000);
                                    "
                                    class="absolute top-2 right-2 px-3 py-1.5 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-lg text-xs font-bold shadow transition flex items-center gap-1.5">
                                    <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    <svg x-show="copied" class="w-3.5 h-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span x-text="copied ? 'Tersalin!' : 'Salin Kode Script'"></span>
                                </button>
                            </div>

                            <p class="text-xs text-blue-800 dark:text-blue-300 leading-relaxed pt-1">
                                3. Klik tombol biru <strong>Terapkan (Deploy) &gt; Penerapan baru (New deployment)</strong> &gt; Pilih jenis <strong>Aplikasi web (Web app)</strong> &gt; Pada kolom <em>"Yang memiliki akses (Who has access)"</em> pilih <strong>Siapa saja (Anyone)</strong> &gt; Klik <strong>Terapkan</strong>.<br>
                                4. Salin <strong>URL Aplikasi Web</strong> yang diberikan Google, lalu tempelkan ke kolom di atas. Selesai!
                            </p>
                        </div>

                        <!-- Tombol Aksi Simpan & Batal -->
                        <div class="flex items-center justify-end gap-2.5 pt-2">
                            <button type="button" @click="showDriveSettings = false" class="px-4 py-2.5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-slate-100 rounded-xl text-xs font-bold transition">
                                Sembunyikan
                            </button>
                            <button type="submit" class="px-5 py-2.5 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-xl text-xs sm:text-sm font-bold shadow-lg shadow-brand-primary/25 transition-all hover:-translate-y-0.5 active:scale-95 flex items-center gap-2 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Simpan Pengaturan
                            </button>
                        </div>
                    </form>

                    <!-- Tombol Uji Coba Langsung (Test Backup Sekarang) - DILETAKKAN DI DALAM PENGATURAN SUPAYA IKUT TERSEMBUNYI -->
                    <div class="pt-2 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-slate-50/80 dark:bg-slate-800/60 p-4 rounded-xl border border-slate-200 dark:border-white/10">
                        <div>
                            <h4 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Uji Coba Ekspor & Upload ke Google Drive
                            </h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Ingin mengetes koneksi tanpa menunggu jam {{ $gdriveTime }} {{ $gdriveTimezone }}? Klik tombol di samping untuk mengekspor database dan mengunggahnya sekarang juga.</p>
                        </div>
                        <form action="{{ route('admin.backup.gdrive.test') }}" method="POST" class="shrink-0 w-full sm:w-auto">
                            @csrf
                            <button type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl text-xs sm:text-sm font-bold shadow-md shadow-emerald-600/20 transition-all hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Tes Backup & Upload Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION MANUAL: EXPORT & IMPORT RESTORE -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Backup / Export Manual Section -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl shadow-xl overflow-hidden flex flex-col">
                <div class="px-4 sm:px-5 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 shrink-0">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <div class="p-1.5 bg-white/20 rounded-lg">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </div>
                        Export Database Manual (Download Langsung)
                    </h3>
                </div>
                <div class="p-4 sm:p-5 space-y-3 flex flex-col flex-1 justify-between">
                    <div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-4">
                            Fitur ini akan mengekspor seluruh data yang ada dalam database saat ini dan langsung mengunduh file backup ke perangkat Anda.
                        </p>
                        
                        <div class="flex items-start gap-2.5 p-3 bg-blue-50 dark:bg-blue-950/30 rounded-xl border border-blue-200 dark:border-blue-800/40 mb-3 text-xs text-blue-700 dark:text-blue-300">
                            <div class="p-1 bg-blue-100 dark:bg-blue-900/50 text-blue-600 rounded-lg shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <p class="leading-relaxed">
                                Format file otomatis menyesuaikan engine database Anda (format <strong>.sqlite</strong> untuk berkas lokal atau <strong>.sql</strong> untuk database server).
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('admin.backup.export') }}" method="POST" class="space-y-3 mt-auto">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">PIN Keamanan Verifikasi</label>
                            <input type="password" name="topup_password" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border-2 border-slate-200 dark:border-white/10 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-400" placeholder="Masukkan PIN" required autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                            @if(!auth()->user()->topup_password)
                                <p class="text-[10px] text-red-500 mt-1">
                                    Anda belum mengatur PIN keamanan. <a href="{{ route('profile.edit') }}" class="underline hover:text-blue-600">Atur di Profil</a>.
                                </p>
                            @endif
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/40 transition-all hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2 text-xs uppercase tracking-wider mt-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Export Sekarang
                        </button>
                    </form>
                </div>
            </div>

            <!-- Import / Restore Section -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl shadow-xl overflow-hidden flex flex-col">
                <div class="px-4 sm:px-5 py-4 bg-gradient-to-r from-rose-500 to-red-600 shrink-0">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <div class="p-1.5 bg-white/20 rounded-lg">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </div>
                        Import / Restore Data
                    </h3>
                </div>
                <div class="p-4 sm:p-5 flex flex-col flex-1 justify-between space-y-3">
                    <div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-4">
                            Gunakan fitur ini untuk memulihkan seluruh data operasional sistem dari file backup <strong>.sql / .sqlite</strong>.
                        </p>

                        <div class="flex items-start gap-2.5 p-3 bg-red-50 dark:bg-red-950/30 rounded-xl border border-red-200 dark:border-red-800/40 mb-3 text-xs text-red-700 dark:text-red-300">
                            <div class="p-1 bg-red-100 dark:bg-red-900/50 text-red-600 rounded-lg shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <p class="leading-relaxed">
                                <strong class="text-red-600 dark:text-red-400">Peringatan:</strong> Seluruh data yang ada saat ini akan ditimpa sesuai isi file backup.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('admin.backup.import') }}" method="POST" enctype="multipart/form-data" class="space-y-3 mt-auto">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Pilih File Backup (.sql / .sqlite)</label>
                            <input type="file" name="backup_file" accept=".sql,.sqlite,.db" 
                                class="w-full px-3 py-2 bg-white dark:bg-slate-900 border-2 border-slate-200 dark:border-white/10 rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">PIN Keamanan Verifikasi</label>
                            <input type="password" name="topup_password" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border-2 border-slate-200 dark:border-white/10 rounded-xl text-xs font-medium text-slate-800 dark:text-slate-200 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all placeholder:text-slate-400" placeholder="Masukkan PIN" required autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                            @if(!auth()->user()->topup_password)
                                <p class="text-[10px] text-red-500 mt-1">
                                    Anda belum mengatur PIN keamanan. <a href="{{ route('profile.edit') }}" class="underline hover:text-rose-600">Atur di Profil</a>.
                                </p>
                            @endif
                        </div>
                        <button type="submit" 
                            data-confirm="Tindakan ini mungkin menghapus semua data Anda saat ini dan menggantinya dengan data dari file. Anda yakin ingin melanjutkan import?"
                            data-confirm-type="danger"
                            data-confirm-title="Peringatan Restore Data!"
                            data-confirm-text="Ya, Restore Sekarang!"
                            class="w-full py-2.5 mt-2 bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white rounded-xl font-bold shadow-lg shadow-red-500/30 hover:shadow-red-500/40 transition-all hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2 text-xs uppercase tracking-wider">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Restore Data
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- SECTION: ARSIP SALINAN BACKUP LOKAL DI SERVER -->
        @if(count($localBackups) > 0)
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl shadow-sm p-4 sm:p-5">
                <div class="flex items-center justify-between gap-2 mb-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                        <h3 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white">Arsip Backup Lokal Terbaru (Server)</h3>
                    </div>
                    <span class="text-[11px] text-slate-400">Tersimpan otomatis di server</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-white/10 text-slate-400 font-semibold">
                                <th class="py-2 px-3">Nama Berkas</th>
                                <th class="py-2 px-3">Ukuran</th>
                                <th class="py-2 px-3">Waktu Dibuat</th>
                                <th class="py-2 px-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @foreach($localBackups as $backup)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                    <td class="py-2.5 px-3 font-mono font-medium text-slate-800 dark:text-slate-200">{{ $backup['name'] }}</td>
                                    <td class="py-2.5 px-3 text-slate-500">{{ $backup['size'] }}</td>
                                    <td class="py-2.5 px-3 text-slate-500">{{ $backup['time'] }}</td>
                                    <td class="py-2.5 px-3 text-right">
                                        <a href="{{ route('admin.backup.download', ['fileName' => $backup['name']]) }}" 
                                            class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-700 dark:text-slate-300 font-bold rounded-lg text-[11px] transition">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            Unduh
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
