<x-guest-layout maxWidth="max-w-md">
    <div class="bg-slate-900/80 border border-white/10 rounded-3xl shadow-2xl overflow-hidden p-6 md:p-8 relative">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 p-4">
            <div class="px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-500 text-[10px] font-bold tracking-wider uppercase">
                Fitur Publik
            </div>
        </div>

        <div class="mb-8 text-center">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('rolog.png') }}" alt="Logo" class="w-16 h-16 object-contain drop-shadow-lg">
            </div>
            <h1 class="text-xl font-bold text-white mb-1">Cek Saldo BBM</h1>
            @if($personelAccessControl == '1')
                <p class="text-slate-400 text-xs text-balance">Scan barcode atau masukkan Nopol/NRP untuk melihat sisa saldo.</p>
            @else
                <p class="text-slate-400 text-xs text-balance">Scan barcode atau masukkan Nopol untuk melihat sisa saldo.</p>
            @endif
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <p class="text-xs font-medium text-red-200/80 leading-relaxed">{{ session('error') }}</p>
            </div>
        @endif

        <div x-data="{ mode: 'manual' }" class="space-y-6">
            <!-- Tabs -->
            <div class="flex bg-slate-950/50 p-1 rounded-xl border border-white/5">
                <button @click="mode = 'manual'" 
                    :class="mode === 'manual' ? 'bg-amber-600 text-white shadow-lg' : 'text-slate-400 hover:text-white'"
                    class="flex-1 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all duration-300 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Input Manual
                </button>
                <button @click="mode = 'scan'; startScanner()" 
                    :class="mode === 'scan' ? 'bg-amber-600 text-white shadow-lg' : 'text-slate-400 hover:text-white'"
                    class="flex-1 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all duration-300 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    Scan Barcode
                </button>
            </div>

            <!-- Manual Input Form -->
            <div x-show="mode === 'manual'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                <form action="{{ route('cek-saldo.check') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-2">
                        @if($personelAccessControl == '1')
                            <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Nopol / NRP</label>
                        @else
                            <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Nopol</label>
                        @endif
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400 group-focus-within:text-amber-500 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="identifier" required
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-950/30 border border-slate-700/50 rounded-xl text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all duration-300 placeholder-slate-600 text-sm uppercase tracking-widest"
                                placeholder="{{ $personelAccessControl == '1' ? 'Masukkan Nopol atau NRP' : 'Masukkan Nopol' }}">
                        </div>
                    </div>
                    <button type="submit" class="w-full py-4 px-6 bg-gradient-to-r from-amber-700 to-amber-600 hover:from-amber-600 hover:to-amber-500 text-white font-bold rounded-xl shadow-lg shadow-amber-900/30 transform hover:-translate-y-0.5 active:scale-[0.98] transition-all duration-200 text-sm uppercase tracking-widest border-t border-white/10">
                        Periksa Saldo
                    </button>
                </form>
            </div>

            <!-- Scanner View -->
            <div x-show="mode === 'scan'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="space-y-4">
                <div class="relative rounded-2xl overflow-hidden bg-slate-950/50 border border-white/5 min-h-[300px] flex flex-col items-center justify-center">
                    <div id="reader" class="w-full"></div>
                    <div id="scanner-placeholder" class="py-12 flex flex-col items-center justify-center text-slate-400">
                        <svg class="w-16 h-16 mb-4 opacity-20 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-sm font-medium">Memulai Kamera...</span>
                    </div>
                </div>
                
                <form id="barcode-form" action="{{ route('cek-saldo.check') }}" method="POST" class="hidden">
                    @csrf
                    <input type="hidden" name="identifier" id="identifier-input">
                </form>

                @if($personelAccessControl == '1')
                    <p class="text-[10px] text-slate-400 text-center italic uppercase tracking-widest">Arahkan kamera ke barcode kartu kendaraan atau personel</p>
                @else
                    <p class="text-[10px] text-slate-400 text-center italic uppercase tracking-widest">Arahkan kamera ke barcode kartu kendaraan</p>
                @endif
            </div>

            <!-- Back to Login -->
            <div class="pt-6 text-center border-t border-white/5">
                <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-400 hover:text-amber-500 transition-colors uppercase tracking-widest flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Halaman Login
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
                const config = { fps: 10, qrbox: { width: 250, height: 250 } };

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
                        <div class="p-6 text-center">
                            <svg class="w-12 h-12 text-red-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <p class="text-xs text-red-400 font-bold uppercase tracking-widest">Gagal Mengakses Kamera</p>
                            <p class="text-[10px] text-slate-400 mt-1">Pastikan Anda telah memberikan izin kamera pada browser.</p>
                        </div>
                    `;
                });
            }, 500);
        }

        window.addEventListener('beforeunload', () => {
            if (html5QrCode && html5QrCode.getState() === 2) {
                html5QrCode.stop();
            }
        });
    </script>
    @endpush
</x-guest-layout>
