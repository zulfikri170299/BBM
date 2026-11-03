<x-guest-layout maxWidth="max-w-md">
    <div class="relative backdrop-blur-2xl bg-slate-900/95 border border-slate-700/80 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.7)] p-5 sm:p-7 overflow-hidden text-slate-100 my-auto">
        <!-- Ambient Decorative Lighting Glows -->
        <div class="absolute -top-12 -right-12 w-40 h-40 bg-amber-500/15 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-12 -left-12 w-40 h-40 bg-blue-600/15 rounded-full blur-2xl pointer-events-none"></div>

        <!-- Top Header & Badges -->
        <div class="flex items-center justify-between mb-3 relative z-10">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full bg-slate-800/80 hover:bg-slate-800 border border-slate-700/60 text-slate-300 hover:text-white text-xs font-semibold transition-all duration-200 group">
                <svg class="w-3.5 h-3.5 text-slate-400 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Login</span>
            </a>
            
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-[10px] font-extrabold tracking-wider uppercase shadow-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span>Fitur Publik</span>
            </div>
        </div>

        <!-- Hero Section: Logo, Title, Subtitle -->
        <div class="text-center mb-5 relative z-10">
            <div class="relative inline-flex items-center justify-center mb-2">
                <div class="absolute inset-0 bg-amber-500/25 rounded-2xl blur-lg"></div>
                <div class="relative rounded-2xl p-1 flex items-center justify-center">
                    <img src="{{ asset('rolog.png') }}" 
                         onerror="this.onerror=null; this.src='/TRIBRATA.png';" 
                         alt="Logo Biro Logistik" 
                         class="w-14 h-14 sm:w-16 sm:h-16 object-contain drop-shadow-md mx-auto"
                         style="width: 58px; height: 58px; max-width: 58px; max-height: 58px;">
                </div>
            </div>
            
            <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight leading-tight">
                Cek Saldo BBM
            </h1>
            <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto leading-relaxed">
                Scan barcode atau masukkan Nopol untuk melihat sisa saldo.
            </p>
        </div>

        <!-- Flash / Error Alert -->
        @if(session('error'))
            <div class="mb-4 p-3 rounded-xl bg-rose-500/15 border border-rose-500/30 flex items-start gap-2.5 relative z-10 animate-shake">
                <div class="w-6 h-6 rounded-lg bg-rose-500/20 text-rose-400 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-rose-300">Data Tidak Ditemukan</p>
                    <p class="text-[11px] text-rose-200/80 leading-snug mt-0.5">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <div x-data="{ mode: 'manual', nopolVal: '' }" class="space-y-4 relative z-10">
            <!-- Modern Segmented Tabs -->
            <div class="flex bg-slate-950/80 p-1 rounded-xl border border-slate-800 shadow-inner gap-1">
                <button type="button" @click="mode = 'manual'" 
                    :class="mode === 'manual' ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 font-black shadow-md shadow-amber-500/25' : 'text-slate-400 hover:text-white font-semibold hover:bg-slate-800/40'"
                    class="flex-1 py-2.5 rounded-lg text-xs uppercase tracking-wider transition-all duration-200 flex items-center justify-center gap-1.5 cursor-pointer select-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Input Manual</span>
                </button>
                <button type="button" @click="mode = 'scan'; startScanner()" 
                    :class="mode === 'scan' ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 font-black shadow-md shadow-amber-500/25' : 'text-slate-400 hover:text-white font-semibold hover:bg-slate-800/40'"
                    class="flex-1 py-2.5 rounded-lg text-xs uppercase tracking-wider transition-all duration-200 flex items-center justify-center gap-1.5 cursor-pointer select-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    <span>Scan Barcode</span>
                </button>
            </div>

            <!-- Mode 1: Manual Input Form -->
            <div x-show="mode === 'manual'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                <form action="{{ route('cek-saldo.check') }}" method="POST" class="space-y-3.5">
                    @csrf
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="text-[10.5px] font-bold text-slate-300 uppercase tracking-widest flex items-center gap-1.5">
                                <span>Nomor Polisi</span>
                            </label>
                            <span class="text-[10px] text-slate-500 font-medium">Contoh: POL 83-XXI</span>
                        </div>
                        
                        <div class="relative group">
                            <!-- Left Search Icon -->
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-amber-500 transition-colors">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            
                            <!-- Input -->
                            <input type="text" 
                                   name="identifier" 
                                   x-model="nopolVal"
                                   required 
                                   autofocus
                                   autocomplete="off"
                                   class="w-full pl-11 pr-10 py-3 bg-slate-950/70 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 text-sm font-bold tracking-wider focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:bg-slate-950 transition-all uppercase"
                                   placeholder="MASUKKAN NOMOR POLISI">
                            
                            <!-- Clear Button ('X') -->
                            <button type="button" 
                                    x-show="nopolVal && nopolVal.length > 0" 
                                    @click="nopolVal = ''"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-white transition-colors cursor-pointer">
                                <div class="w-5 h-5 rounded-full bg-slate-800 hover:bg-slate-700 flex items-center justify-center text-xs">
                                    &times;
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full py-3.5 px-5 bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 hover:from-amber-400 hover:to-orange-500 text-slate-950 font-black rounded-xl shadow-lg shadow-amber-500/25 active:scale-[0.98] transition-all flex items-center justify-center gap-2 text-xs uppercase tracking-wider border-t border-white/30 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Periksa Saldo</span>
                    </button>
                </form>
            </div>

            <!-- Mode 2: Scanner View -->
            <div x-show="mode === 'scan'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-3">
                <div class="relative rounded-xl overflow-hidden bg-slate-950 border border-slate-800 min-h-[260px] flex flex-col items-center justify-center shadow-inner">
                    <!-- Scanner Viewfinder Reticle Overlay -->
                    <div class="absolute inset-5 pointer-events-none z-10 flex flex-col justify-between">
                        <div class="flex justify-between">
                            <div class="w-6 h-6 border-t-2 border-l-2 border-amber-500 rounded-tl-md"></div>
                            <div class="w-6 h-6 border-t-2 border-r-2 border-amber-500 rounded-tr-md"></div>
                        </div>
                        <div class="flex justify-between">
                            <div class="w-6 h-6 border-b-2 border-l-2 border-amber-500 rounded-bl-md"></div>
                            <div class="w-6 h-6 border-b-2 border-r-2 border-amber-500 rounded-br-md"></div>
                        </div>
                    </div>

                    <!-- Scanner Video Mount -->
                    <div id="reader" class="w-full relative z-0"></div>

                    <!-- Placeholder / Loading Animation -->
                    <div id="scanner-placeholder" class="py-8 flex flex-col items-center justify-center text-slate-400 relative z-20">
                        <div class="w-14 h-14 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center mb-2.5 animate-pulse">
                            <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-slate-200">Mengaktifkan Kamera...</span>
                        <span class="text-[10px] text-slate-500 mt-0.5">Izinkan browser mengakses kamera</span>
                    </div>
                </div>
                
                <form id="barcode-form" action="{{ route('cek-saldo.check') }}" method="POST" class="hidden">
                    @csrf
                    <input type="hidden" name="identifier" id="identifier-input">
                </form>

                <p class="text-[10.5px] text-slate-400 text-center font-medium">Arahkan kamera ke barcode pada kartu BBM kendaraan</p>
            </div>

            <!-- Footer: Kembali ke Login -->
            <div class="pt-3 text-center border-t border-slate-800">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg text-xs font-bold text-slate-400 hover:text-amber-400 hover:bg-slate-800/50 transition-all duration-200 uppercase tracking-widest group">
                    <svg class="w-3.5 h-3.5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali ke Halaman Login</span>
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        let html5QrCode;
        
        function startScanner() {
            setTimeout(() => {
                const readerElement = document.getElementById('reader');
                const placeholder = document.getElementById('scanner-placeholder');
                
                if (!readerElement) return;

                html5QrCode = new Html5Qrcode("reader");
                const config = { fps: 15, qrbox: { width: 200, height: 200 } };

                html5QrCode.start(
                    { facingMode: "environment" },
                    config,
                    (decodedText, decodedResult) => {
                        document.getElementById('identifier-input').value = decodedText;
                        html5QrCode.stop().then(() => {
                            document.getElementById('barcode-form').submit();
                        });
                    },
                    (errorMessage) => {}
                ).then(() => {
                    placeholder.style.display = 'none';
                }).catch((err) => {
                    console.error(err);
                    placeholder.innerHTML = `
                        <div class="p-5 text-center">
                            <div class="w-10 h-10 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <p class="text-xs text-rose-400 font-bold uppercase tracking-wider">Izin Kamera Dibutuhkan</p>
                            <p class="text-[10px] text-slate-400 mt-1 max-w-xs mx-auto">Pastikan Anda telah memberikan izin kamera pada browser untuk menggunakan fitur scanner barcode.</p>
                        </div>
                    `;
                });
            }, 300);
        }

        window.addEventListener('beforeunload', () => {
            if (html5QrCode && html5QrCode.getState() === 2) {
                html5QrCode.stop();
            }
        });
    </script>
    @endpush
</x-guest-layout>
