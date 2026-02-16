<x-app-layout>
    <div class="p-6 lg:p-8">
        <div class="max-w-lg mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl shadow-lg shadow-indigo-500/30 mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                </div>
                <h1 class="text-2xl font-bold text-slate-900">Pengisian BBM</h1>
                <p class="text-sm text-slate-500 mt-1">Scan barcode atau input manual nopol untuk memulai pengisian</p>
            </div>

            @if($errors->any())
                <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <div class="flex items-center gap-2 text-sm text-red-700">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-medium">{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif

            <!-- Tabs -->
            <div x-data="{ tab: 'barcode' }" class="space-y-4">
                <div class="flex bg-slate-100 p-1 rounded-xl">
                    <button @click="tab = 'barcode'" :class="tab === 'barcode' ? 'bg-white shadow-sm text-indigo-600' : 'text-slate-500 hover:text-slate-700'" class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        Scan Barcode
                    </button>
                    <button @click="tab = 'nopol'" :class="tab === 'nopol' ? 'bg-white shadow-sm text-indigo-600' : 'text-slate-500 hover:text-slate-700'" class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1"></path></svg>
                        Input Nopol
                    </button>
                    <button @click="tab = 'nrp'" :class="tab === 'nrp' ? 'bg-white shadow-sm text-indigo-600' : 'text-slate-500 hover:text-slate-700'" class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Input NRP
                    </button>
                </div>

                <!-- Tab Barcode -->
                <div x-show="tab === 'barcode'" x-transition class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
                    <div class="px-6 py-3 bg-indigo-50 border-b border-indigo-100">
                        <p class="text-xs text-indigo-600 font-medium text-center">Arahkan scanner ke barcode kartu kendaraan</p>
                    </div>
                    <div class="p-0 bg-slate-50 relative">
                        <div id="reader" class="w-full"></div>
                        <div id="scanner-placeholder" class="py-12 flex flex-col items-center justify-center text-slate-400">
                            <svg class="w-16 h-16 mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="text-sm font-medium">Kamera tidak aktif</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <form id="barcode-form" action="{{ route('petugas.transaksi.check') }}" method="POST">
                            @csrf
                            <input type="hidden" name="mode" value="barcode">
                            <input type="hidden" name="barcode" id="barcode">
                        </form>
                        
                        <button type="button" id="start-scanner" class="w-full px-6 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-xl hover:shadow-indigo-500/40 transition-all text-sm flex items-center justify-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span id="button-text">Aktifkan Kamera Scanner</span>
                        </button>
                        
                        <div id="scanner-info-text" class="text-center mt-4">
                            <p class="text-xs text-slate-500 italic">Klik tombol di atas untuk mulai memindai barcode kartu kendaraan</p>
                        </div>
                    </div>
                </div>

                <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
                <script>
                    let html5QrCode;
                    const scannerButton = document.getElementById('start-scanner');
                    const buttonText = document.getElementById('button-text');
                    const placeholder = document.getElementById('scanner-placeholder');
                    const infoText = document.getElementById('scanner-info-text');
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
                        placeholder.style.display = 'none';
                        infoText.style.display = 'none';
                        buttonText.textContent = 'Matikan Kamera Scanner';
                        scannerButton.classList.replace('from-indigo-600', 'from-red-600');
                        scannerButton.classList.replace('to-indigo-700', 'to-red-700');
                        scannerButton.classList.replace('shadow-indigo-500/30', 'shadow-red-500/30');

                        html5QrCode = new Html5Qrcode("reader");
                        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

                        html5QrCode.start(
                            { facingMode: "environment" }, 
                            config,
                            (decodedText, decodedResult) => {
                                barcodeInput.value = decodedText;
                                stopScanner();
                                barcodeForm.submit();
                            },
                            (errorMessage) => {
                                // silent fails for scanning
                            }
                        ).catch((err) => {
                            console.error(err);
                            alert("Gagal mengakses kamera. Pastikan izin kamera diberikan.");
                            stopScanner();
                        });
                    }

                    function stopScanner() {
                        if (html5QrCode) {
                            html5QrCode.stop().then(() => {
                                placeholder.style.display = 'flex';
                                infoText.style.display = 'block';
                                buttonText.textContent = 'Aktifkan Kamera Scanner';
                                scannerButton.classList.replace('from-red-600', 'from-indigo-600');
                                scannerButton.classList.replace('to-red-700', 'to-indigo-700');
                                scannerButton.classList.replace('shadow-red-500/30', 'shadow-indigo-500/30');
                            }).catch(err => console.error(err));
                        }
                    }
                </script>

                <!-- Tab Input Nopol -->
                <div x-show="tab === 'nopol'" x-transition class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
                    <div class="px-6 py-3 bg-amber-50 border-b border-amber-100">
                        <p class="text-xs text-amber-600 font-medium">⚡ Cadangan: Masukkan nopol langsung jika barcode tidak terbaca</p>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('petugas.transaksi.check') }}" method="POST">
                            @csrf
                            <input type="hidden" name="mode" value="nopol">
                            <div class="mb-5">
                                <label for="nopol" class="block text-sm font-semibold text-slate-700 mb-2">Nomor Polisi</label>
                                <input type="text" name="nopol" id="nopol" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-sm text-slate-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-lg uppercase tracking-wider" placeholder="Contoh: AB 1234 CD">
                            </div>
                            <button type="submit" class="w-full px-6 py-3.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold rounded-xl shadow-lg shadow-amber-500/30 hover:shadow-xl hover:shadow-amber-500/40 transition-all text-sm">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Cari Kendaraan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Tab Input NRP -->
                <div x-show="tab === 'nrp'" x-transition class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
                    <div class="px-6 py-3 bg-rose-50 border-b border-rose-100">
                        <p class="text-xs text-rose-600 font-medium">👤 Personel: Masukkan NRP untuk menggunakan saldo pribadi</p>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('petugas.transaksi.check') }}" method="POST">
                            @csrf
                            <input type="hidden" name="mode" value="nrp">
                            <div class="mb-5">
                                <label for="nrp" class="block text-sm font-semibold text-slate-700 mb-2">NRP Personel</label>
                                <input type="text" name="nrp" id="nrp" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-sm text-slate-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-lg uppercase tracking-wider" placeholder="Masukkan NRP...">
                            </div>
                            <button type="submit" class="w-full px-6 py-3.5 bg-gradient-to-r from-rose-500 to-rose-600 text-white font-bold rounded-xl shadow-lg shadow-rose-500/30 hover:shadow-xl hover:shadow-rose-500/40 transition-all text-sm">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Cari Personel
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
