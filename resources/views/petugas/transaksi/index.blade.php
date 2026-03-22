<x-app-layout>
    <div class="relative min-h-[calc(100vh-64px)] p-2 sm:p-4 overflow-hidden">
        <!-- Decorative background elements -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-4xl h-48 bg-gradient-to-b from-indigo-500/10 to-transparent blur-3xl -z-10"></div>
        <div class="absolute bottom-0 right-0 w-48 h-48 bg-rose-500/5 blur-3xl -z-10 animate-pulse"></div>

        <div class="max-w-md mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-4 relative">
                <div class="relative inline-block group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                    <div class="relative flex items-center justify-center w-12 h-12 bg-white rounded-xl shadow-xl shadow-indigo-500/10 mb-2 mx-auto border border-white/50 bg-clip-padding backdrop-blur-xl">
                        <svg class="w-6 h-6 text-indigo-600 animate-bounce-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                            </path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-xl font-black text-slate-800 tracking-tight leading-none">PENGISIAN BBM</h1>
                <p class="text-[10px] font-bold text-slate-500/80 uppercase tracking-widest mt-1">Identifikasi Kendaraan</p>
            </div>

            <div x-data="{ 
                tab: 'barcode',
                init() {
                    this.$watch('tab', value => {
                        if (value !== 'barcode') {
                            if (typeof window.stopScanner === 'function') window.stopScanner();
                        }
                    });
                }
            }" class="space-y-4">
                
                <!-- Premium Tab Switcher - More Compact -->
                <div class="glass p-1 rounded-xl shadow-xl flex relative overflow-hidden">
                    <button @click="tab = 'barcode'"
                        class="flex-1 relative z-10 flex flex-col items-center justify-center gap-1 py-2 rounded-lg transition-all duration-300 group"
                        :class="tab === 'barcode' ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600'">
                        <svg class="w-4 h-4 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" :class="tab === 'barcode' && 'animate-pulse'">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                        <span class="text-[9px] font-black uppercase tracking-tighter">Barcode</span>
                        <div x-show="tab === 'barcode'" x-transition:enter="transition-all duration-300 transform" x-transition:enter-start="opacity-0 scale-x-0" x-transition:enter-end="opacity-100 scale-x-100" class="absolute inset-0 bg-white rounded-lg shadow-md -z-10"></div>
                    </button>

                    <button @click="tab = 'nopol'"
                        class="flex-1 relative z-10 flex flex-col items-center justify-center gap-1 py-2 rounded-lg transition-all duration-300 group"
                        :class="tab === 'nopol' ? 'text-amber-600' : 'text-slate-400 hover:text-slate-600'">
                        <svg class="w-4 h-4 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" :class="tab === 'nopol' && 'animate-pulse'">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1"></path>
                        </svg>
                        <span class="text-[9px] font-black uppercase tracking-tighter">Nopol</span>
                        <div x-show="tab === 'nopol'" x-transition:enter="transition-all duration-300 transform" x-transition:enter-start="opacity-0 scale-x-0" x-transition:enter-end="opacity-100 scale-x-100" class="absolute inset-0 bg-white rounded-lg shadow-md -z-10"></div>
                    </button>

                    <button @click="tab = 'nrp'"
                        class="flex-1 relative z-10 flex flex-col items-center justify-center gap-1 py-2 rounded-lg transition-all duration-300 group"
                        :class="tab === 'nrp' ? 'text-rose-600' : 'text-slate-400 hover:text-slate-600'">
                        <svg class="w-4 h-4 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" :class="tab === 'nrp' && 'animate-pulse'">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-[9px] font-black uppercase tracking-tighter">NRP</span>
                        <div x-show="tab === 'nrp'" x-transition:enter="transition-all duration-300 transform" x-transition:enter-start="opacity-0 scale-x-0" x-transition:enter-end="opacity-100 scale-x-100" class="absolute inset-0 bg-white rounded-lg shadow-md -z-10"></div>
                    </button>
                </div>

                <!-- Tab Contents Container - Reduced height -->
                <div class="relative min-h-[350px]">
                    <!-- Tab Barcode -->
                    <div x-show="tab === 'barcode'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                        class="glass rounded-3xl shadow-2xl overflow-hidden border border-white/40">
                        <div class="px-4 py-2 bg-gradient-to-r from-indigo-50/50 to-white/50 border-b border-indigo-100/30">
                            <h2 class="text-[9px] font-black text-indigo-900/40 text-center uppercase tracking-widest leading-none">Scanning Interface</h2>
                        </div>
                        
                        <div class="p-4">
                            <div class="aspect-[4/3] max-h-[220px] mx-auto rounded-xl bg-slate-900 relative overflow-hidden shadow-inner border-4 border-slate-800">
                                <div id="reader" class="w-full h-full"></div>
                                
                                <!-- Scanner Overlay -->
                                <div id="scanner-placeholder" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900/60 backdrop-blur-sm z-20">
                                    <div class="relative w-20 h-20 flex items-center justify-center">
                                        <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-indigo-500 rounded-tl-lg animate-pulse"></div>
                                        <div class="absolute top-0 right-0 w-6 h-6 border-t-2 border-r-2 border-indigo-500 rounded-tr-lg animate-pulse"></div>
                                        <div class="absolute bottom-0 left-0 w-6 h-6 border-b-2 border-l-2 border-indigo-500 rounded-bl-lg animate-pulse"></div>
                                        <div class="absolute bottom-0 right-0 w-6 h-6 border-b-2 border-r-2 border-indigo-500 rounded-br-lg animate-pulse"></div>
                                        
                                        <svg class="w-10 h-10 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="mt-4 text-[10px] font-black text-white/50 uppercase tracking-[0.2em]">Kamera Standby</span>
                                </div>

                                <!-- Scanning Line Animation -->
                                <div id="scanning-line" class="hidden absolute left-0 right-0 h-1 bg-indigo-500 shadow-[0_0_15px_rgba(99,102,241,0.8)] z-30 animate-scan"></div>
                            </div>

                            <form id="barcode-form" action="{{ route('petugas.transaksi.check') }}" method="POST">
                                @csrf
                                <input type="hidden" name="mode" value="barcode">
                                <input type="hidden" name="barcode" id="barcode">
                            </form>

                            <div class="mt-4 space-y-3">
                                <button type="button" id="start-scanner"
                                    class="w-full px-4 py-3.5 bg-indigo-600 hover:bg-black text-white font-black rounded-xl shadow-xl shadow-indigo-200 transition-all duration-300 flex items-center justify-center gap-3 group active:scale-[0.98]">
                                    <span class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center group-hover:rotate-12 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        </svg>
                                    </span>
                                    <span id="button-text" class="uppercase tracking-widest text-[11px]">AKTIFKAN KAMERA</span>
                                </button>
                                
                                <p class="text-[9px] text-center text-slate-400 font-bold uppercase tracking-widest">Posisikan barcode kendaraan di tengah</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Input Nopol -->
                    <div x-show="tab === 'nopol'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                        class="glass rounded-3xl shadow-2xl overflow-hidden border border-white/40">
                        <div class="px-4 py-2 bg-gradient-to-r from-amber-50/50 to-white/50 border-b border-amber-100/30">
                            <h2 class="text-[9px] font-black text-amber-900/40 text-center uppercase tracking-widest leading-none">Manual Input Nopol</h2>
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <div class="w-14 h-14 bg-amber-100 rounded-2xl flex items-center justify-center mb-3 mx-auto shadow-inner">
                                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-black text-slate-800 text-center mb-0.5 uppercase">Cari Kendaraan</h3>
                            </div>

                            <form action="{{ route('petugas.transaksi.check') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="mode" value="nopol">
                                <div>
                                    <input type="text" name="nopol" id="nopol" required
                                        class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl text-lg text-center text-slate-800 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all font-black uppercase tracking-[0.2em] shadow-inner placeholder-slate-300"
                                        placeholder="AB 1234 CD">
                                </div>
                                <button type="submit"
                                    class="w-full px-4 py-3.5 bg-amber-500 hover:bg-black text-white font-black rounded-xl shadow-xl shadow-amber-100 transition-all duration-300 flex items-center justify-center gap-3 active:scale-[0.98]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <span class="uppercase tracking-widest text-[11px]">CARI KENDARAAN</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Tab Input NRP -->
                    <div x-show="tab === 'nrp'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                        class="glass rounded-3xl shadow-2xl overflow-hidden border border-white/40">
                        <div class="px-4 py-2 bg-gradient-to-r from-rose-50/50 to-white/50 border-b border-rose-100/30">
                            <h2 class="text-[9px] font-black text-rose-900/40 text-center uppercase tracking-widest leading-none">Personel Identification</h2>
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <div class="w-14 h-14 bg-rose-100 rounded-2xl flex items-center justify-center mb-3 mx-auto shadow-inner">
                                    <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-black text-slate-800 text-center mb-0.5 uppercase">Cari Personel</h3>
                            </div>

                            <form action="{{ route('petugas.transaksi.check') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="mode" value="nrp">
                                <div>
                                    <input type="text" name="nrp" id="nrp" required
                                        class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl text-lg text-center text-slate-800 focus:bg-white focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all font-black uppercase tracking-[0.2em] shadow-inner placeholder-slate-300"
                                        placeholder="MASUKKAN NRP">
                                </div>
                                <button type="submit"
                                    class="w-full px-4 py-3.5 bg-rose-500 hover:bg-black text-white font-black rounded-xl shadow-xl shadow-rose-100 transition-all duration-300 flex items-center justify-center gap-3 active:scale-[0.98]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <span class="uppercase tracking-widest text-[11px]">PROSES PERSONEL</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Styles for Animations -->
    <style>
        @keyframes scan {
            0% { top: 0; opacity: 0; }
            5% { opacity: 1; }
            95% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
        .animate-scan {
            animation: scan 3s linear infinite;
        }
        .animate-bounce-slow {
            animation: bounce 3s infinite;
        }
    </style>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        let html5QrCode;
        const scannerButton = document.getElementById('start-scanner');
        const buttonText = document.getElementById('button-text');
        const placeholder = document.getElementById('scanner-placeholder');
        const scanLine = document.getElementById('scanning-line');
        const barcodeInput = document.getElementById('barcode');
        const barcodeForm = document.getElementById('barcode-form');

        scannerButton.addEventListener('click', () => {
            if (html5QrCode && html5QrCode.getState() === 2) {
                stopScanner();
            } else {
                startScanner();
            }
        });

        function startScanner() {
            placeholder.classList.add('opacity-0');
            setTimeout(() => placeholder.classList.add('hidden'), 300);
            scanLine.classList.remove('hidden');
            buttonText.textContent = 'MATIKAN KAMERA';
            
            scannerButton.classList.replace('bg-indigo-600', 'bg-rose-600');
            scannerButton.classList.replace('shadow-indigo-200', 'shadow-rose-100');

            html5QrCode = new Html5Qrcode("reader");
            const config = { fps: 20, qrbox: { width: 250, height: 250 } };

            html5QrCode.start(
                { facingMode: "environment" },
                config,
                (decodedText, decodedResult) => {
                    barcodeInput.value = decodedText;
                    stopScanner();
                    
                    // Simple sound or vibration if available
                    if (window.navigator.vibrate) window.navigator.vibrate(200);
                    
                    barcodeForm.submit();
                },
                (errorMessage) => {}
            ).catch((err) => {
                console.error(err);
                window.showAlert("Akses Kamera Gagal", "Gagal mengakses kamera. Pastikan izin kamera diberikan.", "error");
                stopScanner();
            });
        }

        function stopScanner() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    placeholder.classList.remove('hidden');
                    setTimeout(() => placeholder.classList.remove('opacity-0'), 10);
                    scanLine.classList.add('hidden');
                    buttonText.textContent = 'AKTIFKAN KAMERA';
                    
                    scannerButton.classList.replace('bg-rose-600', 'bg-indigo-600');
                    scannerButton.classList.replace('shadow-rose-100', 'shadow-indigo-200');
                }).catch(err => console.error(err));
            }
        }

        window.startScanner = startScanner;
        window.stopScanner = stopScanner;

        // Visibility and page events
        window.addEventListener('beforeunload', stopScanner);
        window.addEventListener('pagehide', stopScanner);
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'hidden') stopScanner();
        });
    </script>
</x-app-layout>