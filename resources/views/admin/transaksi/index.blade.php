<x-app-layout>
    <div x-data="transaksi()" @@start-scan.window="startScanner()" @@stop-scan.window="stopScanner()" class="max-w-sm mx-auto p-2 sm:p-4 pb-12">
        <!-- Header: High Contrast -->
        <div class="text-center mb-3">
            <h1 class="text-xl font-bold text-white tracking-wide">TRANSAKSI BBM</h1>
            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-0.5" x-text="step === 'search' ? 'IDENTIFIKASI' : 'KONFIRMASI'"></p>
        </div>

        <!-- Step 1: Search -->
        <div x-show="step === 'search'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="space-y-2.5">
            <!-- Tabs -->
            <div class="flex bg-slate-900/50 p-1 rounded-xl border border-white/5 backdrop-blur-sm">
                <button @@click="tab = 'barcode'; stopScanner();" 
                    :class="tab === 'barcode' ? 'bg-slate-800 text-white shadow-sm' : 'text-slate-400 hover:text-slate-300'"
                    class="flex-1 py-1.5 text-[9px] font-black uppercase rounded-lg transition-all">Barcode</button>
                <button @@click="tab = 'manual'; stopScanner();" 
                    :class="tab === 'manual' ? 'bg-slate-900 border border-white/5 text-indigo-600 shadow-sm' : 'text-slate-400 hover:text-slate-300'"
                    class="flex-1 py-1.5 text-[9px] font-black uppercase rounded-lg transition-all">{{ $personelAccessControl == '1' ? 'NOPOL / NRP' : 'NOPOL' }}</button>
            </div>

            <!-- Card Container -->
            <div class="bg-slate-900 border border-white/5 rounded-2xl p-4 shadow-xl shadow-indigo-500/10 relative">
                <!-- Barcode -->
                <div x-show="tab === 'barcode'" class="space-y-3 flex flex-col items-center">
                    <div class="w-full max-w-[260px] aspect-square bg-slate-800/50 rounded-xl relative overflow-hidden border-2 border-white/5">
                        <div id="reader" class="w-full h-full"></div>
                        <div id="scanner-placeholder" x-show="!isScannerActive || isLoadingScanner" 
                             class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900 border border-white/5/90 backdrop-blur-sm">
                            <div x-show="isLoadingScanner" class="mb-2">
                                <svg class="animate-spin h-7 w-7 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            <div x-show="!isLoadingScanner" class="relative group">
                                <div class="relative w-36 h-36 bg-slate-900 border border-white/5 rounded-3xl border border-white/10 shadow-sm flex items-center justify-center overflow-hidden">
                                    <svg class="w-20 h-20 text-indigo-300/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                    </svg>
                                    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-indigo-400/10 to-transparent h-1/2 w-full -translate-y-full animate-[scan_3s_ease-in-out_infinite]"></div>
                                </div>
                            </div>
                            <p class="text-slate-400 text-[8px] font-black uppercase tracking-[0.2em] mt-3" x-text="isLoadingScanner ? 'INIT...' : 'SIAP SCAN'"></p>
                        </div>
                        <!-- Square Scan Overlay -->
                        <div x-show="isScannerActive" class="absolute inset-0 pointer-events-none flex items-center justify-center p-6">
                             <div class="w-full aspect-square border-2 border-indigo-500/40 relative transform scale-90">
                                  <div class="absolute -top-1 -left-1 w-6 h-6 border-t-4 border-l-4 border-indigo-600 rounded-tl-sm"></div>
                                  <div class="absolute -top-1 -right-1 w-6 h-6 border-t-4 border-r-4 border-indigo-600 rounded-tr-sm"></div>
                                  <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-4 border-l-4 border-indigo-600 rounded-bl-sm"></div>
                                  <div class="absolute -bottom-1 -right-1 w-6 h-6 border-b-4 border-r-4 border-indigo-600 rounded-br-sm"></div>
                                  <div class="absolute left-0 right-0 h-[1.5px] bg-red-500/60 animate-scan"></div>
                             </div>
                        </div>
                    </div>
                    
                    <button type="button" @@click="isScannerActive ? stopScanner() : startScanner()" 
                        :disabled="isLoadingScanner"
                        :class="isScannerActive ? 'bg-slate-900 text-white' : 'bg-indigo-600 hover:bg-indigo-700 text-white' "
                        class="w-full px-4 py-3.5 font-black rounded-xl shadow-xl shadow-indigo-500/30 transition-all duration-300 flex items-center justify-center gap-2 active:scale-[0.98]">
                        <svg x-show="!isScannerActive" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <svg x-show="isScannerActive" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="uppercase tracking-widest text-[11px]" x-text="isScannerActive ? 'MATIKAN KAMERA' : 'AKTIFKAN KAMERA'"></span>
                    </button>
                </div>

                <!-- Manual -->
                <div x-show="tab !== 'barcode'" class="space-y-3 py-1" x-cloak>
                    <div x-show="error" class="p-2 bg-red-50 border border-red-200 rounded-lg text-red-600 text-[10px] font-black uppercase text-center" x-text="error"></div>
                    
                    <input type="text" x-model="manualValue" @@keyup.enter="checkData(tab, manualValue)"
                        class="w-full px-4 py-4 bg-slate-800/50 border-2 border-white/5 rounded-xl text-2xl text-center font-black text-white uppercase tracking-widest outline-none"
                        autocomplete="off" spellcheck="false" :placeholder="'CARI ' + (tab === 'manual' ? ('{{ $personelAccessControl == '1' ? 'NOPOL / NRP' : 'NOPOL' }}') : tab.toUpperCase())">
                    <button @@click="checkData(tab, manualValue)" :disabled="isLoading"
                        class="w-full px-4 py-3.5 bg-indigo-600 hover:bg-black text-white font-black rounded-xl shadow-xl shadow-indigo-500/30 transition-all duration-300 flex items-center justify-center gap-3 active:scale-[0.98] disabled:opacity-50">
                        <svg x-show="!isLoading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span x-show="!isLoading" class="uppercase tracking-widest text-[11px]">PROSES DATA</span>
                        <span x-show="isLoading" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            MENCARI...
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 2: Form (Extreme Anti-Autofill) -->
        <div x-show="step === 'form'" x-transition:enter="transition duration-300" class="space-y-2.5" x-cloak>
            <div class="bg-slate-900 border border-white/5 rounded-2xl overflow-hidden shadow-lg">
                <div :class="type === 'kendaraan' ? 'bg-indigo-600' : 'bg-rose-600'" class="px-4 py-3.5 text-white flex justify-between items-end">
                    <div>
                        <p class="text-[7px] font-black uppercase tracking-widest opacity-70" x-text="type === 'kendaraan' ? 'NO POLISI' : 'NRP'"></p>
                        <h2 class="text-xl font-black leading-none mt-0.5 tracking-tight" x-text="type === 'kendaraan' ? target.no_polisi : target.nrp"></h2>
                    </div>
                    <div class="text-right text-xl font-black" x-text="target.saldo + ' L'"></div>
                </div>
                <div class="px-4 py-2.5 grid grid-cols-2 gap-3 bg-slate-800/50">
                    <span class="text-[11px] font-bold text-slate-300 truncate" x-text="type === 'kendaraan' ? target.jenis_kendaraan : target.nama"></span>
                    <span class="text-[11px] font-black text-indigo-600 uppercase text-right" x-text="target.jenis_bbm"></span>
                </div>
            </div>

            <form action="{{ route('admin.transaksi.process') }}" method="POST" class="space-y-2.5" autocomplete="off">
                @csrf
                <!-- Dummy Hidden Fields to Bait Autofill Away -->
                <input type="text" name="prevent_autofill_username" style="display:none" autocomplete="username">
                <input type="password" name="prevent_autofill_password" style="display:none" autocomplete="current-password">

                <input type="hidden" name="kendaraan_id" :value="type === 'kendaraan' ? target.id : ''">
                <input type="hidden" name="personel_id" :value="type === 'personel' ? target.id : ''">

                <div class="bg-slate-900 border border-white/5 rounded-2xl p-4 space-y-3.5 shadow-lg shadow-black/20">
                    <div>
                        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">JUMLAH LITER</label>
                        <div class="relative">
                            <input type="number" name="liter" step="1" min="1" :max="target.saldo" required x-model="liter"
                                class="w-full px-4 py-3 bg-slate-800/50 border-2 border-white/5 rounded-xl text-3xl font-black text-indigo-600 outline-none"
                                autocomplete="off" placeholder="0.0">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">NAMA DRIVER</label>
                        <input type="text" name="nama_driver" required
                            class="w-full px-4 py-3.5 bg-slate-800/50 border-2 border-white/5 rounded-xl text-xs font-bold text-slate-200 outline-none"
                            autocomplete="off" spellcheck="false" placeholder="Siapa pembawa?">
                    </div>
                    <div>
                        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">PIN OTORISASI</label>
                        <!-- Using type="text" with custom masking to hide characters and avoid password manager popups -->
                        <input type="text" name="pin" maxlength="6" inputmode="numeric" required
                            class="w-full px-4 py-4 bg-slate-800/50 border-2 border-white/5 rounded-xl text-2xl text-center font-mono tracking-[0.4em] text-emerald-600 outline-none"
                            style="-webkit-text-security: disc;"
                            autocomplete="off"
                            spellcheck="false"
                            placeholder="••••••">
                    </div>
                </div>

                <div class="flex gap-2.5 pt-1">
                    <button type="button" @@click="reset()" 
                        class="flex-1 py-4 bg-slate-800 text-slate-400 font-black rounded-xl text-[10px] uppercase transition-all">BATAL</button>
                    <button type="submit" :disabled="liter <= 0 || (target && liter > target.saldo)"
                        class="flex-[2] py-4 bg-emerald-500 text-white font-black rounded-xl text-[10px] uppercase shadow-xl disabled:opacity-50">PROSES</button>
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

                async checkData(mode, value) {
                    if (!value) return;
                    console.log('Searching for:', mode, value);
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
                        console.log('Response:', res);
                        
                        if (res.success) {
                            this.target = res.data; 
                            this.type = res.type; 
                            this.step = 'form'; 
                            this.stopScanner();
                        } else { 
                            this.error = res.message; 
                        }
                    } catch (e) { 
                        console.error('Error:', e);
                        this.error = 'Koneksi bermasalah.'; 
                    } finally { 
                        this.isLoading = false; 
                    }
                },

                reset() {
                    this.step = 'search'; this.target = null; this.type = null; this.error = null; this.liter = '';
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
                    } catch (e) { this.error = "Gagal buka kamera."; } finally { this.isLoadingScanner = false; }
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
        @keyframes scan { 0% { top:0; } 100% { top:100%; } }
        .animate-scan { animation: scan 2s linear infinite; position: absolute; }
    </style>
</x-app-layout>
