<x-app-layout>
    <div x-data="transaksi()" x-on:start-scan.window="startScanner()" x-on:stop-scan.window="stopScanner()" class="max-w-md sm:max-w-xl mx-auto p-2 sm:p-6 pb-6">
        
        <!-- Header Ringkas (Bebas Scroll di Layar HP) -->
        <div class="mb-2.5 sm:mb-4 text-center">
            <h1 class="text-base sm:text-2xl font-black text-slate-800 dark:text-white tracking-tight flex items-center justify-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Transaksi Pelayanan BBM</span>
            </h1>
        </div>

        <!-- Step 1: Search / Identifikasi -->
        <div x-show="step === 'search'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="space-y-2.5 sm:space-y-3">
            
            <!-- Segmented Control Tabs (Barcode vs Nopol/NRP) -->
            <div class="flex p-1 bg-slate-100 dark:bg-slate-900/80 rounded-xl border border-slate-200 dark:border-white/10 shadow-inner">
                <button type="button" @click="tab = 'barcode'; stopScanner();" 
                    :class="tab === 'barcode' ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white shadow-md shadow-indigo-500/30 font-black' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-bold'"
                    class="flex-1 py-1.5 px-2 text-[11px] sm:text-xs uppercase tracking-wider rounded-lg transition-all duration-200 flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    <span>Scan Barcode</span>
                </button>
                <button type="button" @click="tab = 'manual'; stopScanner();" 
                    :class="tab === 'manual' ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white shadow-md shadow-indigo-500/30 font-black' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-bold'"
                    class="flex-1 py-1.5 px-2 text-[11px] sm:text-xs uppercase tracking-wider rounded-lg transition-all duration-200 flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1" />
                    </svg>
                    <span>{{ $personelAccessControl == '1' ? 'Cari Nopol / NRP' : 'Cari Plat Nopol' }}</span>
                </button>
            </div>

            <!-- Error Alert -->
            <div x-show="error" x-transition class="p-2.5 bg-rose-500/10 dark:bg-rose-950/40 border border-rose-500/30 rounded-xl flex items-start gap-2 shadow-sm" x-cloak>
                <div class="p-1 bg-rose-500 text-white rounded-lg shrink-0 mt-0.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[9px] font-black uppercase tracking-wider text-rose-600 dark:text-rose-400">Pemberitahuan</p>
                    <p class="text-xs font-bold text-rose-800 dark:text-rose-200 mt-0.5 leading-snug" x-text="error"></p>
                </div>
                <button type="button" @click="error = null" class="text-rose-400 hover:text-rose-600 dark:hover:text-rose-200 p-0.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Card Container Utama -->
            <div class="bg-white dark:bg-slate-900/90 border border-slate-200/90 dark:border-white/10 rounded-2xl p-4 sm:p-5 shadow-lg shadow-indigo-950/20 relative overflow-hidden backdrop-blur-xl">
                
                <!-- TAB 1: SCANNER KAMERA -->
                <div x-show="tab === 'barcode'" class="space-y-3.5 flex flex-col items-center">
                    
                    <!-- Scanner Screen Viewport (Proporsional & Seimbang) -->
                    <div class="w-56 sm:w-64 aspect-square bg-slate-950 rounded-2xl relative overflow-hidden border-2 border-indigo-500/30 shadow-xl shadow-indigo-950/40">
                        <div id="reader" class="w-full h-full"></div>

                        <!-- Placeholder Saat Kamera Mati -->
                        <div id="scanner-placeholder" x-show="!isScannerActive || isLoadingScanner" 
                             class="absolute inset-0 flex flex-col items-center justify-center p-3 bg-gradient-to-b from-slate-900 via-indigo-950 to-slate-950 text-white z-10">
                            
                            <!-- Loading Spinner -->
                            <div x-show="isLoadingScanner" class="flex flex-col items-center gap-2">
                                <div class="relative w-10 h-10">
                                    <div class="absolute inset-0 rounded-full border-3 border-indigo-500/20 border-t-indigo-500 animate-spin"></div>
                                    <div class="absolute inset-1.5 rounded-full border-3 border-cyan-500/20 border-b-cyan-400 animate-spin" style="animation-direction: reverse; animation-duration: 1.5s;"></div>
                                </div>
                                <p class="text-[10px] font-bold text-cyan-300 animate-pulse tracking-wider uppercase">Membuka Kamera...</p>
                            </div>

                            <!-- Standby Visual -->
                            <div x-show="!isLoadingScanner" class="flex flex-col items-center text-center">
                                <div class="relative mb-2.5 group">
                                    <div class="absolute -inset-1.5 bg-gradient-to-r from-indigo-500 to-cyan-500 rounded-xl blur-md opacity-30"></div>
                                    <div class="relative w-16 h-16 bg-slate-900/90 rounded-2xl border border-indigo-400/40 shadow-md flex items-center justify-center">
                                        <svg class="w-8 h-8 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                        </svg>
                                    </div>
                                </div>

                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    Kamera Standby
                                </span>
                            </div>
                        </div>

                        <!-- Holographic Target Overlay Saat Kamera Aktif -->
                        <div x-show="isScannerActive" class="absolute inset-0 pointer-events-none flex items-center justify-center p-4 z-10">
                            <div class="w-full aspect-square relative border border-indigo-400/20 rounded-xl overflow-hidden">
                                <!-- 4 Glowing Corners -->
                                <div class="absolute top-0 left-0 w-5 h-5 border-t-2 border-l-2 border-cyan-400 rounded-tl"></div>
                                <div class="absolute top-0 right-0 w-5 h-5 border-t-2 border-r-2 border-cyan-400 rounded-tr"></div>
                                <div class="absolute bottom-0 left-0 w-5 h-5 border-b-2 border-l-2 border-cyan-400 rounded-bl"></div>
                                <div class="absolute bottom-0 right-0 w-5 h-5 border-b-2 border-r-2 border-cyan-400 rounded-br"></div>
                                
                                <!-- Animated Laser Beam -->
                                <div class="absolute left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-cyan-400 to-transparent shadow-[0_0_8px_#22d3ee] animate-scan"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tombol Buka / Matikan Kamera (1 Baris Rapi) -->
                    <button type="button" @click="isScannerActive ? stopScanner() : startScanner()" 
                        :disabled="isLoadingScanner"
                        :class="isScannerActive ? 'bg-gradient-to-r from-rose-600 to-red-700 hover:from-rose-500 hover:to-red-600 shadow-rose-500/30' : 'bg-gradient-to-r from-indigo-600 via-indigo-500 to-cyan-600 hover:from-indigo-500 hover:to-cyan-500 shadow-indigo-500/30'"
                        class="w-56 sm:w-64 px-4 py-3 text-white font-black text-xs uppercase tracking-wider rounded-xl shadow-lg transition-all duration-300 flex items-center justify-center gap-2 active:scale-[0.98] border border-white/15 whitespace-nowrap">
                        
                        <svg x-show="!isScannerActive" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        
                        <svg x-show="isScannerActive" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        
                        <span class="whitespace-nowrap" x-text="isScannerActive ? 'Matikan Kamera' : 'Buka Kamera'"></span>
                    </button>
                </div>

                <!-- TAB 2: INPUT MANUAL NOPOL / NRP -->
                <div x-show="tab !== 'barcode'" class="space-y-3 py-1" x-cloak>
                    <div class="text-center">
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                            Input {{ $personelAccessControl == '1' ? 'Nomor Polisi atau NRP' : 'Nomor Polisi Kendaraan' }}
                        </label>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">
                            {{ $personelAccessControl == '1' ? 'Ketik Nopol kendaraan atau NRP personel tanpa spasi' : 'Ketik nomor plat dinas atau pribadi tanpa spasi (contoh: 1234-XVI / DR1234AB)' }}
                        </p>
                    </div>

                    <!-- Input Box Bergaya Plat Nomor Polisi -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" x-model="manualValue" @keyup.enter="checkData(tab, manualValue)"
                            class="w-full pl-12 pr-10 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-indigo-500/30 focus:border-indigo-500 dark:focus:border-indigo-400 rounded-2xl text-xl sm:text-2xl text-center font-mono font-black text-slate-900 dark:text-white uppercase tracking-widest outline-none transition-all shadow-inner"
                            autocomplete="off" spellcheck="false" :placeholder="'MASUKKAN ' + (tab === 'manual' ? ('{{ $personelAccessControl == '1' ? 'NOPOL / NRP' : 'NOPOL' }}') : tab.toUpperCase())">
                        
                        <button type="button" x-show="manualValue" @click="manualValue = ''" 
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Submit Button -->
                    <button type="button" @click="checkData(tab, manualValue)" :disabled="isLoading || !manualValue"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white font-black text-xs uppercase tracking-wider rounded-2xl shadow-xl shadow-indigo-600/30 transition-all duration-300 flex items-center justify-center gap-2.5 active:scale-[0.98] disabled:opacity-50 border border-white/15">
                        
                        <svg x-show="!isLoading" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                        
                        <span x-show="!isLoading">Cari & Periksa Data</span>
                        
                        <span x-show="isLoading" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Memeriksa Database...</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 2: Form Konfirmasi & Pengisian BBM -->
        <div x-show="step === 'form'" x-transition:enter="transition duration-300" class="space-y-4" x-cloak>
            
            <!-- Executive Vehicle / Personnel Identity Card -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white p-5 border border-indigo-500/30 shadow-2xl shadow-indigo-950/30">
                <div class="absolute -top-12 -right-12 w-40 h-40 bg-indigo-500/20 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute -bottom-12 -left-12 w-40 h-40 bg-cyan-500/20 rounded-full blur-2xl pointer-events-none"></div>

                <div class="relative z-10 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-white/10 text-cyan-300 border border-white/10"
                            x-text="target ? (target.satker ? target.satker.nama_satker : 'SATKER') : '-'"></span>
                        
                        <div class="flex items-center gap-1.5">
                            <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider bg-purple-500/30 text-purple-200 border border-purple-500/40"
                                x-text="type === 'kendaraan' ? 'KENDARAAN' : 'PERSONEL'"></span>
                            
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border"
                                :class="target && target.jenis_bbm && target.jenis_bbm.toLowerCase().includes('dex') ? 'bg-rose-500/20 text-rose-300 border-rose-500/30' : 'bg-cyan-500/20 text-cyan-300 border-cyan-500/30'"
                                x-text="target ? target.jenis_bbm : '-'"></span>
                        </div>
                    </div>

                    <div class="flex items-baseline justify-between pt-1 border-t border-white/10">
                        <div>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block"
                                x-text="type === 'kendaraan' ? 'NOMOR POLISI' : 'NRP PERSONEL'"></span>
                            <h2 class="text-2xl sm:text-3xl font-mono font-black text-white tracking-wider mt-0.5" 
                                x-text="target ? (type === 'kendaraan' ? target.no_polisi : target.nrp) : '-'"></h2>
                            <p class="text-xs text-slate-300 font-medium" 
                                x-text="target ? (type === 'kendaraan' ? target.jenis_kendaraan : target.nama) : ''"></p>
                        </div>
                        
                        <div class="text-right">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">SISA SALDO</span>
                            <div class="flex items-baseline justify-end gap-1 mt-0.5">
                                <span class="text-2xl sm:text-3xl font-black text-emerald-400 tabular-nums" 
                                    x-text="target ? target.saldo : '0'"></span>
                                <span class="text-xs font-black text-emerald-300">Liter</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Proses Pengisian -->
            <form action="{{ route('admin.transaksi.process') }}" method="POST" class="space-y-4" autocomplete="off">
                @csrf
                <!-- Dummy Hidden Fields to Bait Autofill Away -->
                <input type="text" name="prevent_autofill_username" style="display:none" autocomplete="username">
                <input type="password" name="prevent_autofill_password" style="display:none" autocomplete="current-password">

                <input type="hidden" name="kendaraan_id" :value="type === 'kendaraan' ? (target ? target.id : '') : ''">
                <input type="hidden" name="personel_id" :value="type === 'personel' ? (target ? target.id : '') : ''">

                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-3xl p-5 sm:p-6 space-y-4 shadow-xl shadow-black/10">
                    
                    <!-- Input Jumlah Liter & Preset Buttons -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                Volume Penyaluran
                            </label>
                            <span class="text-[10px] font-bold text-slate-400" x-text="'Maksimal ' + (target ? target.saldo : 0) + ' L'"></span>
                        </div>
                        
                        <div class="relative">
                            <input type="number" name="liter" step="1" min="1" :max="target ? target.saldo : 9999" required x-model="liter"
                                class="w-full px-4 py-3.5 pr-20 bg-slate-50 dark:bg-slate-950 border-2 border-slate-200 dark:border-white/10 focus:border-indigo-500 dark:focus:border-indigo-400 rounded-2xl text-2xl sm:text-3xl font-black text-indigo-600 dark:text-indigo-400 outline-none transition-all shadow-inner"
                                autocomplete="off" placeholder="0">
                            
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <span class="text-xs font-black uppercase tracking-wider text-slate-400 dark:text-slate-500 bg-slate-200 dark:bg-slate-800 px-2 py-1 rounded-lg">LITER</span>
                            </div>
                        </div>

                        <!-- Quick Preset Buttons -->
                        <div class="grid grid-cols-4 gap-2 mt-2.5">
                            <button type="button" @click="setLiter(5)" 
                                :disabled="target && target.saldo < 5"
                                class="py-1.5 px-2 rounded-xl text-xs font-extrabold bg-slate-100 hover:bg-indigo-50 dark:bg-slate-800 dark:hover:bg-indigo-950/50 text-slate-700 dark:text-slate-300 hover:text-indigo-600 border border-slate-200 dark:border-white/5 transition-all disabled:opacity-40">
                                5 L
                            </button>
                            <button type="button" @click="setLiter(10)" 
                                :disabled="target && target.saldo < 10"
                                class="py-1.5 px-2 rounded-xl text-xs font-extrabold bg-slate-100 hover:bg-indigo-50 dark:bg-slate-800 dark:hover:bg-indigo-950/50 text-slate-700 dark:text-slate-300 hover:text-indigo-600 border border-slate-200 dark:border-white/5 transition-all disabled:opacity-40">
                                10 L
                            </button>
                            <button type="button" @click="setLiter(20)" 
                                :disabled="target && target.saldo < 20"
                                class="py-1.5 px-2 rounded-xl text-xs font-extrabold bg-slate-100 hover:bg-indigo-50 dark:bg-slate-800 dark:hover:bg-indigo-950/50 text-slate-700 dark:text-slate-300 hover:text-indigo-600 border border-slate-200 dark:border-white/5 transition-all disabled:opacity-40">
                                20 L
                            </button>
                            <button type="button" @click="setLiter(target ? target.saldo : 0)" 
                                class="py-1.5 px-2 rounded-xl text-xs font-extrabold bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/60 dark:hover:bg-indigo-900/80 text-indigo-600 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-500/30 transition-all">
                                Max
                            </button>
                        </div>
                    </div>

                    <!-- Input Nama Driver -->
                    <div>
                        <label class="block text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Nama Pengemudi / Driver
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input type="text" name="nama_driver" required
                                class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-950 border-2 border-slate-200 dark:border-white/10 focus:border-indigo-500 dark:focus:border-indigo-400 rounded-2xl text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 outline-none transition-all"
                                autocomplete="off" spellcheck="false" placeholder="Masukkan nama personel pembawa kendaraan">
                        </div>
                    </div>

                    <!-- Input PIN Otorisasi -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                PIN Keamanan Otorisasi (6 Digit)
                            </label>
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 dark:text-emerald-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                Terenkripsi
                            </span>
                        </div>
                        <input type="text" name="pin" maxlength="6" inputmode="numeric" required
                            class="w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-950 border-2 border-slate-200 dark:border-white/10 focus:border-emerald-500 dark:focus:border-emerald-400 rounded-2xl text-xl sm:text-2xl text-center font-mono tracking-[0.4em] text-emerald-600 dark:text-emerald-400 outline-none transition-all shadow-inner"
                            style="-webkit-text-security: disc;"
                            autocomplete="off"
                            spellcheck="false"
                            placeholder="••••••">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3 pt-1">
                    <button type="button" @click="reset()" 
                        class="flex-1 py-4 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-black rounded-2xl text-xs uppercase tracking-wider transition-all border border-slate-200 dark:border-white/5">
                        Batal
                    </button>
                    <button type="submit" :disabled="liter <= 0 || (target && liter > target.saldo)"
                        class="flex-[2] py-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-black rounded-2xl text-xs uppercase tracking-wider shadow-xl shadow-emerald-500/30 transition-all duration-300 active:scale-[0.98] disabled:opacity-50 border border-white/15">
                        Selesaikan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scanner & Alpine Script -->
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        window.transaksi = function() {
            return {
                step: 'search',
                tab: 'barcode',
                isLoading: false,
                isScannerActive: false,
                isLoadingScanner: false,
                error: null,
                target: null,
                type: null,
                manualValue: '',
                liter: '',

                setLiter(val) {
                    if (!this.target) return;
                    this.liter = Math.min(val, this.target.saldo);
                },

                async checkData(mode, value) {
                    if (!value) return;
                    this.isLoading = true;
                    this.error = null;
                    try {
                        const response = await fetch('{{ route('admin.transaksi.check') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ mode, [mode === 'manual' ? 'value' : mode]: value })
                        });
                        
                        const res = await response.json();
                        
                        if (res.success) {
                            this.target = res.data; 
                            this.type = res.type; 
                            this.step = 'form'; 
                            this.stopScanner();
                        } else { 
                            this.error = res.message; 
                            if (mode === 'barcode') {
                                this.stopScanner();
                            }
                        }
                    } catch (e) { 
                        console.error('Error:', e);
                        this.error = 'Koneksi ke server bermasalah.'; 
                    } finally { 
                        this.isLoading = false; 
                    }
                },

                reset() {
                    this.step = 'search'; this.target = null; this.type = null; this.error = null; this.liter = ''; this.manualValue = '';
                    if (this.tab === 'barcode') setTimeout(() => this.startScanner(), 300);
                },

                async startScanner() {
                    if (this.isLoadingScanner) return;
                    this.isLoadingScanner = true; this.error = null;
                    try {
                        if (window.html5QrCode) try { await window.html5QrCode.stop(); } catch(e) {}
                        window.html5QrCode = new Html5Qrcode("reader");
                        await window.html5QrCode.start({ facingMode: "environment" }, { 
                            fps: 15, qrbox: (vw, vh) => { const s = Math.min(vw, vh) * 0.8; return { width: s, height: s }; }, aspectRatio: 1.0 
                        }, (txt) => this.checkData('barcode', txt));
                        this.isScannerActive = true;
                    } catch (e) { this.error = "Gagal mengakses kamera. Pastikan izin kamera telah diberikan."; } finally { this.isLoadingScanner = false; }
                },

                async stopScanner() {
                    this.isScannerActive = false; this.isLoadingScanner = false;
                    if (window.html5QrCode && window.html5QrCode.getState() === 2) try { await window.html5QrCode.stop(); } catch (e) {}
                }
            }
        }
    </script>
    <style>
        #reader__dashboard_section_csr button { display: none !important; }
        #reader__scan_region video { object-fit: cover !important; }
        [x-cloak] { display: none !important; }
        @keyframes scan { 
            0% { top: 0%; opacity: 0.8; }
            50% { top: 96%; opacity: 1; }
            100% { top: 0%; opacity: 0.8; }
        }
        .animate-scan { animation: scan 2.5s ease-in-out infinite; position: absolute; }
    </style>
</x-app-layout>
