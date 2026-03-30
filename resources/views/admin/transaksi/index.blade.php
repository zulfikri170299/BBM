<x-app-layout>
    <div x-data="transaksi()" @@start-scan.window="startScanner()" @@stop-scan.window="stopScanner()" class="max-w-xl mx-auto p-2 sm:p-4 pb-12">
        <!-- Header: High Contrast -->
        <div class="text-center mb-3">
            <h1 class="text-lg font-black text-slate-900 tracking-tight">TRANSAKSI BBM</h1>
            <p class="text-[8px] font-bold text-slate-500 uppercase tracking-widest leading-none mt-0.5" x-text="step === 'search' ? 'IDENTIFIKASI' : 'KONFIRMASI'"></p>
        </div>

        <!-- Step 1: Search -->
        <div x-show="step === 'search'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="space-y-2.5">
            <!-- Tabs -->
            <div class="flex bg-slate-100 p-1 rounded-xl border border-slate-200 shadow-inner">
                <button @@click="tab = 'barcode'; stopScanner();" 
                    :class="tab === 'barcode' ? 'bg-white text-indigo-700 shadow-sm border border-slate-200' : 'text-slate-500'"
                    class="flex-1 py-1.5 text-[9px] font-black uppercase rounded-lg transition-all">Barcode</button>
                <button @@click="tab = 'manual'; stopScanner();" 
                    :class="tab === 'manual' ? 'bg-white text-indigo-700 shadow-sm border border-slate-200' : 'text-slate-500'"
                    class="flex-1 py-1.5 text-[9px] font-black uppercase rounded-lg transition-all">NOPOL / NRP</button>
            </div>

            <!-- Card Container -->
            <div class="bg-white border-2 border-slate-100 rounded-2xl p-4 shadow-xl shadow-slate-200/50 relative">
                <!-- Barcode -->
                <div x-show="tab === 'barcode'" class="space-y-3 flex flex-col items-center">
                    <div class="w-full max-w-[260px] aspect-square bg-slate-50 rounded-xl relative overflow-hidden border-2 border-slate-100">
                        <div id="reader" class="w-full h-full"></div>
                        <div id="scanner-placeholder" x-show="!isScannerActive || isLoadingScanner" 
                             class="absolute inset-0 flex flex-col items-center justify-center bg-white/90 backdrop-blur-sm">
                            <div x-show="isLoadingScanner" class="mb-2">
                                <svg class="animate-spin h-7 w-7 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            <div x-show="!isLoadingScanner" class="relative group">
                                <div class="relative w-36 h-36 bg-white rounded-3xl border border-slate-200 shadow-sm flex items-center justify-center overflow-hidden">
                                    <img src="{{ asset('images/qr-placeholder.png') }}" class="w-28 h-28 object-contain opacity-100" alt="QR">
                                    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-slate-400/5 to-transparent h-1/2 w-full -translate-y-full animate-[scan_3s_ease-in-out_infinite]"></div>
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
                        :class="isScannerActive ? 'bg-slate-900 text-white' : 'bg-indigo-600' "
                        class="w-full py-3.5 font-black rounded-xl text-[10px] tracking-widest uppercase transition-all duration-300">
                        <span x-text="isScannerActive ? 'MATIKAN' : 'AKTIFKAN KAMERA'"></span>
                    </button>
                </div>

                <!-- Manual -->
                <div x-show="tab !== 'barcode'" class="space-y-3 py-1" x-cloak>
                    <div x-show="error" class="p-2 bg-red-50 border border-red-200 rounded-lg text-red-600 text-[10px] font-black uppercase text-center" x-text="error"></div>
                    
                    <input type="text" x-model="manualValue" @@keyup.enter="checkData(tab, manualValue)"
                        class="w-full px-4 py-4 bg-slate-50 border-2 border-slate-100 rounded-xl text-2xl text-center font-black text-slate-900 uppercase tracking-widest outline-none"
                        autocomplete="off" spellcheck="false" :placeholder="'CARI ' + (tab === 'manual' ? 'NOPOL / NRP' : tab.toUpperCase())">
                    <button @@click="checkData(tab, manualValue)" :disabled="isLoading"
                        class="w-full py-4 bg-indigo-600 text-white font-black rounded-xl text-[10px] tracking-widest uppercase transition-all active:scale-[0.98]">
                        <span x-show="!isLoading" x-text="'PROSES ' + (tab === 'manual' ? 'NOPOL / NRP' : tab.toUpperCase())"></span>
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
            <div class="bg-white border-2 border-slate-100 rounded-2xl overflow-hidden shadow-lg">
                <div :class="type === 'kendaraan' ? 'bg-indigo-600' : 'bg-rose-600'" class="px-4 py-3.5 text-white flex justify-between items-end">
                    <div>
                        <p class="text-[7px] font-black uppercase tracking-widest opacity-70" x-text="type === 'kendaraan' ? 'NO POLISI' : 'NRP'"></p>
                        <h2 class="text-xl font-black leading-none mt-0.5 tracking-tight" x-text="type === 'kendaraan' ? target.no_polisi : target.nrp"></h2>
                    </div>
                    <div class="text-right text-xl font-black" x-text="target.saldo + ' L'"></div>
                </div>
                <div class="px-4 py-2.5 grid grid-cols-2 gap-3 bg-slate-50/50">
                    <span class="text-[11px] font-bold text-slate-700 truncate" x-text="type === 'kendaraan' ? target.jenis_kendaraan : target.nama"></span>
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

                <div class="bg-white border-2 border-slate-100 rounded-2xl p-4 space-y-3.5 shadow-xl">
                    <div>
                        <label class="block text-[8px] font-black text-slate-500 uppercase tracking-widest mb-1">JUMLAH LITER</label>
                        <div class="relative">
                            <input type="number" name="liter" step="0.1" min="0.1" :max="target.saldo" required x-model="liter"
                                class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl text-3xl font-black text-indigo-600 outline-none"
                                autocomplete="off" placeholder="0.0">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[8px] font-black text-slate-500 uppercase tracking-widest mb-1">NAMA DRIVER</label>
                        <input type="text" name="nama_driver" required
                            class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-xl text-xs font-bold text-slate-800 outline-none"
                            autocomplete="off" spellcheck="false" placeholder="Siapa pembawa?">
                    </div>
                    <div>
                        <label class="block text-[8px] font-black text-slate-500 uppercase tracking-widest mb-1">PIN OTORISASI</label>
                        <!-- Using type="text" with custom masking to hide characters and avoid password manager popups -->
                        <input type="text" name="pin" maxlength="6" inputmode="numeric" required
                            class="w-full px-4 py-4 bg-slate-50 border-2 border-slate-100 rounded-xl text-2xl text-center font-mono tracking-[0.4em] text-emerald-600 outline-none"
                            style="-webkit-text-security: disc;"
                            autocomplete="off"
                            spellcheck="false"
                            placeholder="••••••">
                    </div>
                </div>

                <div class="flex gap-2.5 pt-1">
                    <button type="button" @@click="reset()" 
                        class="flex-1 py-4 bg-slate-100 text-slate-500 font-black rounded-xl text-[10px] uppercase transition-all">BATAL</button>
                    <button type="submit" :disabled="liter <= 0 || (target && liter > target.saldo)"
                        class="flex-[2] py-4 bg-emerald-500 text-white font-black rounded-xl text-[10px] uppercase shadow-xl disabled:opacity-50">PROSES</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scanner & Alpine Script -->
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        function transaksi() {
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
