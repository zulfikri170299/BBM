<section>
    <header>
        <div class="flex items-center gap-3 mb-2">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-bold text-gray-900">Face ID Registration</h2>
                <p class="text-[11px] sm:text-xs text-gray-500">Daftarkan wajah untuk login biometrik</p>
            </div>
        </div>
    </header>

    {{-- Status: Wajah Sudah Terdaftar --}}
    @if(Auth::user()->face_descriptor)
        <div class="mt-4 sm:mt-6" x-data="{ showDelete: false }">
            <div class="p-4 sm:p-6 rounded-2xl bg-gradient-to-br from-emerald-50 to-cyan-50 border border-emerald-200/60">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center shadow-md shadow-emerald-500/30">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-emerald-800">Wajah Anda Sudah Terdaftar</p>
                        <p class="text-xs text-emerald-600/80">Login menggunakan scan wajah sudah aktif</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 mt-4 pt-4 border-t border-emerald-200/50">
                    <button type="button" @click="showDelete = false"
                        onclick="document.getElementById('face-scan-camera-section').style.display = document.getElementById('face-scan-camera-section').style.display === 'none' ? 'block' : 'none'"
                        class="px-4 py-2 bg-white hover:bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-xl border border-emerald-200 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                        Daftarkan Ulang
                    </button>
                    <button type="button" @click="showDelete = !showDelete"
                        class="px-4 py-2 bg-white hover:bg-red-50 text-red-600 text-xs font-semibold rounded-xl border border-red-200 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        Hapus Scan Wajah
                    </button>
                </div>
                <div x-show="showDelete" x-transition class="mt-4 p-4 rounded-xl bg-red-50 border border-red-200">
                    <p class="text-xs text-red-700 font-semibold mb-3">Apakah Anda yakin ingin menghapus data wajah?</p>
                    <div class="flex items-center gap-2">
                        <form method="POST" action="{{ route('profile.face.delete') }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition-colors">Ya, Hapus</button>
                        </form>
                        <button type="button" @click="showDelete = false" class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-600 text-xs font-semibold rounded-xl border border-slate-200 transition-colors">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <p class="mt-2 text-xs sm:text-sm text-gray-600 leading-relaxed">Belum ada wajah terdaftar. Aktifkan kamera untuk mendaftarkan wajah Anda.</p>
    @endif

    {{-- Camera Section --}}
    <div id="face-scan-camera-section" class="mt-4 sm:mt-6 space-y-4" style="{{ Auth::user()->face_descriptor ? 'display:none' : '' }}">
        <div x-data="faceScanApp()" x-init="loadModels()">
            <!-- Camera Container -->
            <div class="relative w-full max-w-sm mx-auto bg-slate-900 rounded-2xl overflow-hidden shadow-xl border border-slate-200">
                <div class="relative aspect-[3/4]">
                    <video x-ref="video" autoplay muted playsinline class="w-full h-full object-cover hidden"></video>
                    <canvas x-ref="canvas" class="absolute inset-0 w-full h-full pointer-events-none"></canvas>

                    <!-- Loading -->
                    <div x-show="status === 'loading'" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900 z-10">
                        <div class="w-14 h-14 rounded-full border-2 border-transparent border-t-emerald-500 animate-spin"></div>
                        <span class="text-xs text-emerald-400/80 font-semibold mt-4 tracking-wider">Memuat Model AI...</span>
                    </div>

                    <!-- Standby -->
                    <div x-show="status === 'ready'" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-50 z-10">
                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-3 border border-slate-200">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Kamera Nonaktif</span>
                    </div>

                    <!-- Error loading -->
                    <div x-show="status === 'error'" class="absolute inset-0 flex flex-col items-center justify-center bg-red-50 z-10 p-4">
                        <svg class="w-10 h-10 text-red-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.072 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                        <p x-text="errorMsg" class="text-xs text-red-600 font-semibold text-center"></p>
                    </div>

                    <!-- Corner brackets -->
                    <div x-show="status === 'scanning' || status === 'detected'" class="absolute inset-0 z-10 pointer-events-none flex items-center justify-center">
                        <div class="relative w-[65%] aspect-[3/4]">
                            <div class="absolute top-0 left-0 w-8 h-8 border-t-2 border-l-2 rounded-tl-xl transition-colors duration-300" :class="status === 'detected' ? 'border-emerald-400' : 'border-white/40'"></div>
                            <div class="absolute top-0 right-0 w-8 h-8 border-t-2 border-r-2 rounded-tr-xl transition-colors duration-300" :class="status === 'detected' ? 'border-emerald-400' : 'border-white/40'"></div>
                            <div class="absolute bottom-0 left-0 w-8 h-8 border-b-2 border-l-2 rounded-bl-xl transition-colors duration-300" :class="status === 'detected' ? 'border-emerald-400' : 'border-white/40'"></div>
                            <div class="absolute bottom-0 right-0 w-8 h-8 border-b-2 border-r-2 rounded-br-xl transition-colors duration-300" :class="status === 'detected' ? 'border-emerald-400' : 'border-white/40'"></div>
                        </div>
                    </div>

                    <!-- Scan line -->
                    <style>@keyframes fsweep{0%{top:15%;opacity:0}10%{opacity:1}50%{opacity:.8}90%{opacity:1}100%{top:85%;opacity:0}}.fsweep{animation:fsweep 2.5s ease-in-out infinite}</style>
                    <div x-show="status === 'scanning'" class="absolute left-[17%] right-[17%] h-[2px] bg-gradient-to-r from-transparent via-emerald-400 to-transparent z-20 fsweep shadow-[0_0_15px_rgba(16,185,129,0.6)]"></div>

                    <!-- Bottom label -->
                    <div x-show="status === 'scanning' || status === 'detected'" class="absolute bottom-3 left-0 right-0 z-20 flex justify-center pointer-events-none">
                        <div class="px-3 py-1.5 rounded-full bg-black/60 backdrop-blur-md border border-white/10">
                            <span x-show="status === 'scanning'" class="text-[10px] text-white/70 font-semibold uppercase tracking-widest animate-pulse">Mencari wajah...</span>
                            <span x-show="status === 'detected'" class="text-[10px] text-emerald-400 font-semibold uppercase tracking-widest">✓ Wajah Terdeteksi — Menyimpan...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center gap-3 pt-4">
                <button type="button" @click="startCamera()" x-show="status === 'ready'" 
                    class="w-full sm:w-auto px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600 text-white text-xs sm:text-sm font-bold rounded-xl shadow-lg shadow-emerald-500/20 transition-all duration-200 flex items-center justify-center gap-2 uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Aktifkan Kamera
                </button>

                <button type="button" @click="stopCamera()" x-show="status === 'scanning' || status === 'detected'" 
                    class="w-full sm:w-auto px-5 py-2.5 border border-slate-300 hover:bg-slate-50 text-slate-600 text-xs sm:text-sm font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                    Matikan Kamera
                </button>

            </div>

            <!-- Debug info -->
            <div x-show="debugMsg" x-transition class="mt-3 p-3 rounded-xl bg-slate-50 border border-slate-200">
                <p x-text="debugMsg" class="text-[11px] text-slate-500 font-mono text-center"></p>
            </div>
            
            <div x-show="errorMsg && status !== 'error'" x-transition class="mt-3 p-3 rounded-xl bg-red-50 border border-red-200">
                <p x-text="errorMsg" class="text-xs text-red-500 font-semibold text-center"></p>
            </div>

            <!-- Hidden form -->
            <form id="face-register-form" method="post" action="{{ route('profile.face.update') }}">
                @csrf
                <input type="hidden" name="face_descriptor" id="face_descriptor_input">
            </form>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
function faceScanApp() {
    return {
        status: 'loading', // loading, ready, scanning, detected, error
        errorMsg: '',
        debugMsg: '',
        stream: null,
        detectionInterval: null,
        descriptorData: null,

        async loadModels() {
            if (typeof faceapi === 'undefined') {
                this.status = 'error';
                this.errorMsg = 'Library face-api.js gagal dimuat. Refresh halaman.';
                return;
            }
            try {
                await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
                console.log('[Face] tinyFaceDetector OK');
                await faceapi.nets.faceLandmark68TinyNet.loadFromUri('/models');
                console.log('[Face] faceLandmark68TinyNet OK');
                await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
                console.log('[Face] faceRecognitionNet OK');
                this.status = 'ready';
                console.log('[Face] All models loaded!');
            } catch (e) {
                console.error('[Face] Model load error:', e);
                this.status = 'error';
                this.errorMsg = 'Gagal memuat model: ' + e.message;
            }
        },

        async startCamera() {
            this.errorMsg = '';
            this.debugMsg = '';
            this.descriptorData = null;
            try {
                this.stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } },
                    audio: false
                });
                const video = this.$refs.video;
                video.srcObject = this.stream;
                video.classList.remove('hidden');
                this.status = 'scanning';

                video.onloadedmetadata = () => {
                    video.play();
                    const canvas = this.$refs.canvas;
                    const displaySize = { width: video.videoWidth, height: video.videoHeight };
                    canvas.width = displaySize.width;
                    canvas.height = displaySize.height;
                    faceapi.matchDimensions(canvas, displaySize);
                    this.runDetection(video, canvas, displaySize);
                };
            } catch (err) {
                console.error('[Face] Camera error:', err);
                this.status = 'error';
                this.errorMsg = 'Kamera tidak dapat diakses: ' + err.message;
            }
        },

        async runDetection(video, canvas, displaySize) {
            let attempts = 0;
            const ctx = canvas.getContext('2d');
            const options = new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.1 });

            this.detectionInterval = setInterval(async () => {
                if (this.status !== 'scanning' && this.status !== 'detected') return;
                if (video.videoWidth === 0) return;

                attempts++;
                try {
                    // STEP 1: Detect face only (fast)
                    const faceDetection = await faceapi.detectSingleFace(video, options);
                    
                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    if (!faceDetection) {
                        if (attempts % 15 === 0) {
                            this.debugMsg = 'Scanning... (percobaan ke-' + attempts + ')';
                            console.log('[Face] No face detected, attempt #' + attempts);
                        }
                        if (this.status === 'detected') this.status = 'scanning';
                        return;
                    }

                    // Face found! Draw box
                    const resized = faceapi.resizeResults(faceDetection, displaySize);
                    const box = resized.box;
                    ctx.strokeStyle = '#10b981';
                    ctx.lineWidth = 3;
                    ctx.strokeRect(box.x, box.y, box.width, box.height);

                    console.log('[Face] Face detected! Score:', faceDetection.score.toFixed(3));
                    this.debugMsg = 'Wajah terdeteksi (skor: ' + faceDetection.score.toFixed(2) + '). Menghitung deskriptor...';

                    // STEP 2: Compute full descriptor (slower, separate step)
                    const fullResult = await faceapi
                        .detectSingleFace(video, options)
                        .withFaceLandmarks(true)
                        .withFaceDescriptor();

                    if (fullResult && fullResult.descriptor) {
                        this.descriptorData = JSON.stringify(Array.from(fullResult.descriptor));
                        this.status = 'detected';
                        this.debugMsg = 'Wajah terdeteksi! Menyimpan otomatis...';
                        console.log('[Face] Descriptor computed! Auto-saving...');
                        
                        // Auto-save langsung
                        setTimeout(() => {
                            this.saveFace();
                        }, 500);
                    } else {
                        console.log('[Face] Face found but descriptor computation failed, retrying...');
                        this.debugMsg = 'Wajah terdeteksi, menghitung ulang deskriptor...';
                    }

                } catch (error) {
                    console.error('[Face] Detection error:', error);
                    if (attempts % 5 === 0) {
                        this.debugMsg = 'Error deteksi: ' + error.message + ' (mencoba ulang...)';
                    }
                }
            }, 500);
        },

        stopCamera() {
            if (this.stream) {
                this.stream.getTracks().forEach(t => t.stop());
            }
            if (this.detectionInterval) {
                clearInterval(this.detectionInterval);
            }
            if (this.$refs.video) {
                this.$refs.video.classList.add('hidden');
            }
            if (this.$refs.canvas) {
                const c = this.$refs.canvas.getContext('2d');
                c.clearRect(0, 0, this.$refs.canvas.width, this.$refs.canvas.height);
            }
            this.status = 'ready';
            this.descriptorData = null;
            this.debugMsg = '';
        },

        saveFace() {
            if (!this.descriptorData) {
                this.errorMsg = 'Wajah belum siap. Tunggu hingga deskriptor dihitung.';
                return;
            }
            // Set the hidden input value explicitly
            const input = document.getElementById('face_descriptor_input');
            input.value = this.descriptorData;
            console.log('[Face] Submitting descriptor. Length:', this.descriptorData.length);
            console.log('[Face] Input value preview:', input.value.substring(0, 80));
            
            this.stopCamera();
            document.getElementById('face-register-form').submit();
        }
    };
}
</script>
