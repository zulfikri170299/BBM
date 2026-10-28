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
                        <h1 class="text-2xl font-bold text-white tracking-tight">Pengaturan Sistem</h1>
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

        <!-- Form Pengaturan -->
        <form id="settings-form" action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- ========================================================
                 BAGIAN 1: HAK AKSES DATA & OTORISASI AKUN (2 KOLOM SEIMBANG)
                 ======================================================== -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Hak Akses Kendaraan Satker (7 cols) -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 bg-slate-50/70 dark:bg-slate-800/40 border-b border-slate-200 dark:border-white/10 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center border border-blue-100 dark:border-blue-500/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">Hak Akses Kendaraan Satker</h3>
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
                                        <h4 class="text-xs font-bold text-slate-900 dark:text-white">Tambah Data Kendaraan</h4>
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
                                        <h4 class="text-xs font-bold text-slate-900 dark:text-white">Edit Data Kendaraan</h4>
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
                                        <h4 class="text-xs font-bold text-slate-900 dark:text-white">Import Data via Excel</h4>
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
                </div>

                <!-- Otorisasi Login Satker & Kebijakan (5 cols) -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 bg-slate-50/70 dark:bg-slate-800/40 border-b border-slate-200 dark:border-white/10 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-xl flex items-center justify-center border border-amber-100 dark:border-amber-500/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-white">Otorisasi Login Satker</h3>
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
                                        <h4 class="text-xs font-bold text-white">Akses Akun Seluruh Satker</h4>
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

                </div>
            </div>

            <!-- ========================================================
                 BAGIAN 2: KUSTOMISASI VISUAL HALAMAN LOGIN (SEJAJAR KIRI & KANAN)
                 ======================================================== -->
            <div class="pt-4 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center border border-indigo-100 dark:border-indigo-500/20 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-white tracking-tight">Kustomisasi Visual Halaman Login</h2>
                        <p class="text-xs text-slate-400">Atur gambar hero lingkaran di tengah serta foto background gedung desktop secara presisi dan sejajar berdampingan.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                    
                    <!-- ========================================================
                         KOLOM KIRI: GAMBAR HERO LINGKARAN LOGIN
                         ======================================================== -->
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden"
                         x-data="{ 
                            hasPreview: false, 
                            previewSrc: '{{ !empty($settings['mobile_hero_image']) && file_exists(public_path($settings['mobile_hero_image'])) ? asset($settings['mobile_hero_image']) : '' }}', 
                            removeCustomImage: false,
                            croppedBase64: '',
                            shapeMode: '{{ ($settings['mobile_hero_shape'] ?? 'circle') === 'rectangle' ? 'auto' : ($settings['mobile_hero_shape'] ?? 'circle') }}',
                            fitMode: '{{ $settings['mobile_hero_fit'] ?? 'cover' }}',
                            zoomLevel: {{ $settings['mobile_hero_zoom'] ?? 100 }},
                            bgColor: '{{ $settings['mobile_hero_bg'] ?? 'transparent' }}',
                            showCropperModal: false,
                            cropperInstance: null,
                            rawImageSrc: '',

                            onShapeChange() {
                                if (this.cropperInstance) {
                                    this.cropperInstance.setAspectRatio(this.shapeMode === 'circle' ? 1 : NaN);
                                }
                            },

                            fileChosen(event) {
                                const file = event.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        this.rawImageSrc = e.target.result;
                                        this.initCropper();
                                    };
                                    reader.readAsDataURL(file);
                                }
                            },

                            initCropper() {
                                this.showCropperModal = true;
                                this.$nextTick(() => {
                                    const img = document.getElementById('cropper-target-image');
                                    if (!img) return;
                                    if (this.cropperInstance) {
                                        this.cropperInstance.destroy();
                                    }
                                    img.src = this.rawImageSrc;
                                    const isCircle = this.shapeMode === 'circle';
                                    this.cropperInstance = new Cropper(img, {
                                        aspectRatio: isCircle ? 1 : NaN,
                                        viewMode: 1,
                                        dragMode: 'move',
                                        autoCropArea: 0.98,
                                        restore: false,
                                        guides: !isCircle,
                                        center: true,
                                        highlight: false,
                                        cropBoxMovable: !isCircle,
                                        cropBoxResizable: !isCircle,
                                        toggleDragModeOnDblclick: false,
                                        background: false,
                                    });
                                });
                            },

                            cropZoom(delta) {
                                if (this.cropperInstance) {
                                    this.cropperInstance.zoom(delta);
                                }
                            },

                            cropRotate(degree) {
                                if (this.cropperInstance) {
                                    this.cropperInstance.rotate(degree);
                                }
                            },

                            cropReset() {
                                if (this.cropperInstance) {
                                    this.cropperInstance.reset();
                                }
                            },

                            applyCroppedImage() {
                                if (!this.cropperInstance) return;
                                const croppedCanvas = this.cropperInstance.getCroppedCanvas({
                                    width: 800,
                                    height: 800,
                                    imageSmoothingEnabled: true,
                                    imageSmoothingQuality: 'high',
                                });
                                if (!croppedCanvas) return;

                                // Mask bulat transparan sempurna (1:1 circular mask)
                                const circleCanvas = document.createElement('canvas');
                                circleCanvas.width = 800;
                                circleCanvas.height = 800;
                                const ctx = circleCanvas.getContext('2d');
                                
                                ctx.beginPath();
                                ctx.arc(400, 400, 400, 0, 2 * Math.PI);
                                ctx.closePath();
                                ctx.clip();
                                ctx.drawImage(croppedCanvas, 0, 0, 800, 800);

                                this.croppedBase64 = circleCanvas.toDataURL('image/png');
                                this.previewSrc = this.croppedBase64;
                                this.hasPreview = true;
                                this.removeCustomImage = false;
                                this.closeCropper();
                            },

                            openReCrop() {
                                if (this.previewSrc) {
                                    this.rawImageSrc = this.previewSrc;
                                    this.initCropper();
                                }
                            },

                            closeCropper() {
                                if (this.cropperInstance) {
                                    this.cropperInstance.destroy();
                                    this.cropperInstance = null;
                                }
                                this.showCropperModal = false;
                            },

                            resetImage() {
                                if (this.$refs.fileInput) this.$refs.fileInput.value = '';
                                this.hasPreview = false;
                                this.previewSrc = '';
                                this.croppedBase64 = '';
                                this.removeCustomImage = true;
                            }
                         }">
                        
                        <!-- Header Card -->
                        <div class="px-4 py-3 bg-slate-50/70 dark:bg-slate-800/40 border-b border-slate-200 dark:border-white/10 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 bg-sky-50 dark:bg-sky-500/10 text-sky-600 dark:text-sky-400 rounded-lg flex items-center justify-center border border-sky-100 dark:border-sky-500/20">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white">Gambar Hero Login (Mobile & Desktop)</h3>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">Gambar bulat atau persegi panjang di tengah portal login.</p>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 text-[9px] font-bold rounded-lg border"
                                  :class="(hasPreview || previewSrc) && !removeCustomImage ? 'bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 border-sky-200 dark:border-sky-800/50' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-white/10'">
                                <span x-text="(hasPreview || previewSrc) && !removeCustomImage ? 'Gambar Khusus' : 'Model Bawaan'"></span>
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="p-3.5 sm:p-4 space-y-3">
                            
                            <!-- Input Teks Slogan Login Mobile -->
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 space-y-1.5">
                                <label for="mobile_hero_slogan" class="flex items-center justify-between text-xs font-bold text-slate-800 dark:text-slate-200">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <span>Teks Slogan / Judul Login Mobile</span>
                                    </span>
                                    <span class="text-[9px] font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/40 px-2 py-0.5 rounded-full border border-indigo-200 dark:border-indigo-700/50">Dapat Diubah</span>
                                </label>
                                <input type="text" 
                                       id="mobile_hero_slogan"
                                       name="mobile_hero_slogan" 
                                       value="{{ $settings['mobile_hero_slogan'] ?? 'Ayok Mulai Transaksi dengan SIM-BBM Mobile' }}" 
                                       placeholder="Contoh: Ayok Mulai Transaksi dengan SIM-BBM Mobile"
                                       class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition shadow-sm font-semibold">
                                <p class="text-[10px] text-slate-500 dark:text-slate-400">
                                    Kalimat ajakan yang muncul di bagian atas halaman login HP (tepat di atas kubah gambar media).
                                </p>
                            </div>

                            <!-- Visual Preview Hero WYSIWYG & Tombol Aksi -->
                            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-3 p-3 rounded-xl bg-gradient-to-b from-[#14081c]/70 via-[#07183d]/80 to-[#022b7a]/85 border border-white/15 text-white shadow-inner relative overflow-hidden">
                                
                                <!-- Lingkaran Hero Miniatur Preview Persis Mobile -->
                                <div class="relative shrink-0 flex items-center justify-center p-1.5">
                                    <!-- Soft Outer Halo -->
                                    <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-blue-600/35 via-sky-500/25 to-indigo-500/30 blur-lg pointer-events-none"></div>

                                    <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-full overflow-hidden flex items-center justify-center relative transition-all duration-300"
                                         style="box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.14), 0 0 20px rgba(56, 189, 248, 0.22), 0 12px 30px rgba(0, 0, 0, 0.7);"
                                         :style="bgColor !== 'transparent' ? 'background-color: ' + bgColor + ';' : 'background-color: rgba(7,24,61,0.85);'">
                                        
                                        <!-- Template Gambar Kustom -->
                                        <template x-if="(hasPreview || previewSrc) && !removeCustomImage">
                                            <img :src="previewSrc" alt="Preview Hero Mobile" 
                                                 class="w-full h-full object-cover object-center transition-all duration-200"
                                                 :style="'transform: scale(' + (zoomLevel / 100) + ');'">
                                        </template>

                                        <!-- Template Model Bawaan Logo Polda -->
                                        <template x-if="(!hasPreview && !previewSrc) || removeCustomImage">
                                            <div class="w-full h-full flex items-center justify-center p-3 bg-white/10">
                                                <img src="/rolog.png" onerror="this.onerror=null; this.src='/TRIBRATA.png';" alt="Default Logo" class="max-w-full max-h-full object-contain drop-shadow">
                                            </div>
                                        </template>

                                        <!-- Soft Edge Inner Vignette -->
                                        <div class="absolute inset-0 rounded-full pointer-events-none z-10"
                                             style="box-shadow: inset 0 0 18px 5px rgba(7, 24, 61, 0.75), inset 0 0 0 1px rgba(255, 255, 255, 0.14);"></div>
                                    </div>
                                </div>

                                <!-- Keterangan & Tombol Aksi Upload/Crop -->
                                <div class="flex-1 space-y-2.5 text-center sm:text-left">
                                    <div>
                                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-1.5">
                                            <h4 class="text-xs font-bold text-white tracking-wide">Status Tampilan:
                                                <span class="text-cyan-300 font-black" x-text="(hasPreview || previewSrc) && !removeCustomImage ? 'Gambar Khusus Aktif' : 'Model Bawaan (Logo Polda)'"></span>
                                            </h4>
                                            <span class="px-2 py-0.5 rounded-md bg-indigo-500/30 border border-indigo-400/40 text-[9.5px] font-black text-cyan-200 flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                                                Lingkaran Mobile (1:1)
                                            </span>
                                        </div>
                                        <p class="text-[10px] text-slate-300 mt-1 leading-relaxed">
                                            Format: <strong>PNG/WEBP/JPG</strong>. Media krop otomatis berbentuk <strong>Lingkaran Bulat Penuh (1:1)</strong> persis seperti ukuran dan tampilan media di halaman login mobile.
                                        </p>
                                    </div>

                                    <!-- Indikator Bentuk Media Sesuai Mobile -->
                                    <div class="flex items-center justify-center sm:justify-start gap-2 pt-0.5">
                                        <span class="text-[10px] font-bold text-slate-300 shrink-0">Bentuk Media:</span>
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-600/25 border border-indigo-400/30 text-indigo-200 text-xs font-bold shadow-sm">
                                            <span class="w-2.5 h-2.5 rounded-full border-2 border-cyan-400 bg-cyan-400/40"></span>
                                            <span>Lingkaran Bulat Mobile (1:1)</span>
                                        </div>
                                        <input type="hidden" name="mobile_hero_shape" value="circle">
                                    </div>

                                    <!-- Tombol Aksi Upload / Crop / Reset -->
                                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-1.5 pt-0.5">
                                        <input type="file" 
                                               name="mobile_hero_image" 
                                               x-ref="fileInput"
                                               @change="fileChosen($event)" 
                                               accept="image/*" 
                                               class="hidden" 
                                               id="hero-file-input">
                                        
                                        <label for="hero-file-input" 
                                               class="cursor-pointer inline-flex items-center gap-1 px-2.5 py-1.5 bg-white hover:bg-slate-100 text-slate-900 rounded-lg text-[11px] font-bold shadow-sm active:scale-95 transition">
                                            <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                            </svg>
                                            <span>Pilih Gambar</span>
                                        </label>

                                        <button type="button" 
                                                x-show="(hasPreview || previewSrc) && !removeCustomImage"
                                                @click="openReCrop()"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-sky-500/25 hover:bg-sky-500/40 text-cyan-200 border border-cyan-400/30 rounded-lg text-[11px] font-bold transition">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                            </svg>
                                            <span x-text="shapeMode === 'circle' ? 'Crop Bulat' : 'Atur Crop Otomatis'"></span>
                                        </button>

                                        <button type="button" 
                                                x-show="(hasPreview || previewSrc) && !removeCustomImage"
                                                @click="resetImage()"
                                                class="inline-flex items-center gap-1 px-2 py-1.5 bg-rose-500/20 hover:bg-rose-500/35 text-rose-200 border border-rose-400/30 rounded-lg text-[11px] font-bold transition">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span>Reset</span>
                                        </button>

                                        <input type="hidden" name="mobile_hero_image_base64" :value="croppedBase64">
                                        <input type="hidden" name="remove_mobile_hero_image" :value="removeCustomImage ? '1' : '0'">
                                    </div>
                                </div>
                            </div>

                            <!-- Panel 3 Pengaturan Tampilan Lingkaran / Persegi Panjang (Fit, Zoom & Warna Background) -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-0.5">
                                
                                <!-- 1. Mode Pas (Fit Mode - Bebas Focus Scroll Bug) -->
                                <div class="p-2.5 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50/50 dark:bg-slate-800/30 space-y-1">
                                    <label class="block text-[10px] font-bold text-slate-800 dark:text-white uppercase tracking-wider">
                                        1. Mode Pas
                                    </label>
                                    <div class="grid grid-cols-2 gap-1">
                                        <button type="button" 
                                                @click="fitMode = 'cover'"
                                                class="flex flex-col items-center justify-center p-1 rounded-lg border text-center transition select-none"
                                                :class="fitMode === 'cover' ? 'border-indigo-600 bg-indigo-50/80 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 font-bold' : 'border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'">
                                            <span class="text-xs">Penuh</span>
                                            <span class="text-[8px] text-slate-400 font-normal">Cover</span>
                                        </button>

                                        <button type="button" 
                                                @click="fitMode = 'contain'"
                                                class="flex flex-col items-center justify-center p-1 rounded-lg border text-center transition select-none"
                                                :class="fitMode === 'contain' ? 'border-indigo-600 bg-indigo-50/80 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 font-bold' : 'border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'">
                                            <span class="text-xs">Utuh</span>
                                            <span class="text-[8px] text-slate-400 font-normal">Contain</span>
                                        </button>
                                        <input type="hidden" name="mobile_hero_fit" :value="fitMode">
                                    </div>
                                </div>

                                <!-- 2. Skala Perbesaran (Zoom Level Slider) -->
                                <div class="p-2.5 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50/50 dark:bg-slate-800/30 space-y-1">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-[10px] font-bold text-slate-800 dark:text-white uppercase tracking-wider">
                                            2. Skala Zoom
                                        </label>
                                        <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400" x-text="zoomLevel + '%'"></span>
                                    </div>
                                    <input type="range" 
                                           name="mobile_hero_zoom" 
                                           min="50" 
                                           max="150" 
                                           step="5" 
                                           x-model="zoomLevel" 
                                           class="w-full accent-indigo-600 cursor-pointer h-1.5 pt-1">
                                    <div class="flex justify-between text-[8px] text-slate-400 pt-0.5">
                                        <span>50%</span>
                                        <button type="button" @click="zoomLevel = 100" class="text-indigo-500 hover:underline">100%</button>
                                        <span>150%</span>
                                    </div>
                                </div>

                                <!-- 3. Warna Latar (Background - Bebas Focus Scroll Bug) -->
                                <div class="p-2.5 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50/50 dark:bg-slate-800/30 space-y-1">
                                    <label class="block text-[10px] font-bold text-slate-800 dark:text-white uppercase tracking-wider">
                                        3. Warna Latar
                                    </label>
                                    <div class="flex items-center gap-1.5 pt-0.5">
                                        <!-- Transparan -->
                                        <button type="button" 
                                                @click="bgColor = 'transparent'"
                                                class="relative focus:outline-none" 
                                                title="Transparan">
                                            <div class="w-5 h-5 rounded-full border-2 transition-all flex items-center justify-center"
                                                 :class="bgColor === 'transparent' ? 'ring-2 ring-indigo-500 ring-offset-1 border-indigo-600 bg-white' : 'border-slate-300 bg-slate-200'">
                                                <svg class="w-2.5 h-2.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                            </div>
                                        </button>

                                        <!-- Hitam Gelap -->
                                        <button type="button" 
                                                @click="bgColor = '#0a0a0c'"
                                                class="relative focus:outline-none" 
                                                title="Hitam Gelap">
                                            <div class="w-5 h-5 rounded-full bg-[#0a0a0c] border-2 transition-all"
                                                 :class="bgColor === '#0a0a0c' ? 'ring-2 ring-indigo-500 ring-offset-1 border-white' : 'border-transparent'"></div>
                                        </button>

                                        <!-- Biru Polri -->
                                        <button type="button" 
                                                @click="bgColor = '#0e2c7a'"
                                                class="relative focus:outline-none" 
                                                title="Biru Polri">
                                            <div class="w-5 h-5 rounded-full bg-[#0e2c7a] border-2 transition-all"
                                                 :class="bgColor === '#0e2c7a' ? 'ring-2 ring-indigo-500 ring-offset-1 border-white' : 'border-transparent'"></div>
                                        </button>

                                        <!-- Biru SPBP Cerah -->
                                        <button type="button" 
                                                @click="bgColor = '#004fcf'"
                                                class="relative focus:outline-none" 
                                                title="Biru SPBP">
                                            <div class="w-5 h-5 rounded-full bg-[#004fcf] border-2 transition-all"
                                                 :class="bgColor === '#004fcf' ? 'ring-2 ring-indigo-500 ring-offset-1 border-white' : 'border-transparent'"></div>
                                        </button>
                                        <input type="hidden" name="mobile_hero_bg" :value="bgColor">
                                    </div>
                                    <p class="text-[8px] text-slate-400">Efektif saat mode Utuh / transparan.</p>
                                </div>

                            </div>
                        </div>

                        <!-- MODAL CIRCULAR CROPPER -->
                        <div x-show="showCropperModal" 
                             x-cloak 
                             class="fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-5"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0">
                            
                            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-md" @click="closeCropper()"></div>

                            <div class="relative z-10 w-full max-w-xl bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-white/10 overflow-hidden flex flex-col text-slate-800 dark:text-slate-200"
                                 x-transition:enter="transition ease-out duration-300 transform"
                                 x-transition:enter-start="scale-95 translate-y-4"
                                 x-transition:enter-end="scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-200 transform"
                                 x-transition:leave-start="scale-100 translate-y-0"
                                 x-transition:leave-end="scale-95 translate-y-4">
                                
                                <div class="px-5 py-4 border-b border-slate-200 dark:border-white/10 flex items-center justify-between bg-slate-50 dark:bg-slate-800/50">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Sesuaikan Potongan Lingkaran Mobile (1:1)</h3>
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Geser & zoom gambar agar pas di dalam lingkaran mobile. Hasil potongan akan tampil bulat sempurna di login HP.</p>
                                        </div>
                                    </div>
                                    <button type="button" @click="closeCropper()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-white transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>

                                <div class="p-4 bg-slate-950 flex items-center justify-center relative overflow-hidden" style="min-height: 340px; max-height: 420px;">
                                    <div class="circular-cropper w-full h-full max-h-[380px] flex items-center justify-center">
                                        <img id="cropper-target-image" src="" alt="Crop Source" class="max-w-full max-h-full block">
                                    </div>
                                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 px-3 py-1 bg-black/60 backdrop-blur-md rounded-full text-[10px] font-bold text-cyan-200 border border-white/20 pointer-events-none">
                                        Bagian di dalam lingkaran akan tampil di Login Mobile
                                    </div>
                                </div>

                                <div class="px-5 py-3 border-t border-slate-200 dark:border-white/10 flex flex-wrap items-center justify-between gap-2 bg-slate-50/70 dark:bg-slate-800/40">
                                    <div class="flex items-center gap-1.5">
                                        <button type="button" @click="cropZoom(0.1)" title="Perbesar" class="p-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 hover:bg-slate-100 transition shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        </button>
                                        <button type="button" @click="cropZoom(-0.1)" title="Perkecil" class="p-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 hover:bg-slate-100 transition shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                        </button>
                                        <button type="button" @click="cropRotate(-90)" title="Putar Kiri 90°" class="p-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 hover:bg-slate-100 transition shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a5 5 0 015 5v2m0 0l-3-3m3 3l3-3M3 10l3-3m-3 3l3 3"/></svg>
                                        </button>
                                        <button type="button" @click="cropRotate(90)" title="Putar Kanan 90°" class="p-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 hover:bg-slate-100 transition shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a5 5 0 00-5 5v2m0 0l3-3m-3 3l-3-3m17-7l-3-3m3 3l-3 3"/></svg>
                                        </button>
                                        <button type="button" @click="cropReset()" title="Reset Posisi" class="px-2.5 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 hover:bg-slate-100 text-xs font-bold transition shadow-sm">
                                            Reset
                                        </button>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button type="button" @click="closeCropper()" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-slate-100 text-xs font-bold transition">
                                            Batal
                                        </button>
                                        <button type="button" @click="applyCroppedImage()" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-600/30 active:scale-95 transition flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            <span x-text="shapeMode === 'circle' ? 'Terapkan ke Lingkaran' : 'Terapkan Gambar Otomatis'"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- ========================================================
                         KOLOM KANAN: GAMBAR BACKGROUND GEDUNG (LOGIN DESKTOP)
                         (Sejajar di samping form gambar hero lingkaran login)
                         ======================================================== -->
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden"
                         x-data="{ 
                            hasCustomBg: {{ !empty($settings['login_background_image']) && file_exists(public_path($settings['login_background_image'])) ? 'true' : 'false' }},
                            previewSrc: '{{ !empty($settings['login_background_image']) && file_exists(public_path($settings['login_background_image'])) ? asset($settings['login_background_image']) : asset('polda.jpg') }}',
                            defaultSrc: '{{ asset('polda.jpg') }}',
                            removeCustomBg: false,
                            posY: {{ $settings['login_bg_pos_y'] ?? 50 }},
                            zoomLevel: {{ $settings['login_bg_zoom'] ?? 100 }},
                            overlayLevel: {{ $settings['login_bg_overlay'] ?? 25 }},
                            
                            fileChosen(event) {
                                const file = event.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        this.previewSrc = e.target.result;
                                        this.hasCustomBg = true;
                                        this.removeCustomBg = false;
                                    };
                                    reader.readAsDataURL(file);
                                }
                            },

                            resetToDefault() {
                                if (this.$refs.bgFileInput) this.$refs.bgFileInput.value = '';
                                this.previewSrc = this.defaultSrc;
                                this.hasCustomBg = false;
                                this.removeCustomBg = true;
                                this.posY = 50;
                                this.zoomLevel = 100;
                            }
                         }">
                        
                        <!-- Header Card -->
                        <div class="px-4 py-3 bg-slate-50/70 dark:bg-slate-800/40 border-b border-slate-200 dark:border-white/10 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-lg flex items-center justify-center border border-blue-100 dark:border-blue-500/20">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white">Gambar Background Gedung (Desktop)</h3>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">Latar belakang gedung halaman login komputer.</p>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 text-[9px] font-bold rounded-lg border"
                                  :class="hasCustomBg && !removeCustomBg ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/50' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-white/10'">
                                <span x-text="hasCustomBg && !removeCustomBg ? 'Gedung Kustom' : 'Gedung Bawaan'"></span>
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="p-3.5 sm:p-4 space-y-3">
                            
                            <!-- Visual Preview Miniatur Layar Desktop 16:9 Live Interaktif -->
                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-700 dark:text-slate-300 text-[11px]">Live Preview Background Desktop</span>
                                    <span class="text-[10px] text-slate-400 font-medium">Simulasi Layar 16:9</span>
                                </div>

                                <!-- Desktop Screen Mockup Frame -->
                                <div class="relative w-full h-32 sm:h-36 rounded-xl overflow-hidden border-2 border-slate-300 dark:border-slate-700 shadow-md group bg-slate-950">
                                    <!-- Background Image with dynamic position and zoom -->
                                    <img :src="previewSrc" alt="Preview Background Gedung" 
                                         class="w-full h-full object-cover transition-all duration-300 origin-center"
                                         :style="'object-position: center ' + posY + '%; transform: scale(' + (zoomLevel / 100) + ');'">
                                    
                                    <!-- Dynamic Overlay -->
                                    <div class="absolute inset-0 bg-slate-950 transition-opacity duration-200 pointer-events-none"
                                         :style="'opacity: ' + (overlayLevel / 100) + ';'"></div>

                                    <!-- Miniatur Kartu Login di Depan (Proporsi Akurat 1:1 dengan Halaman Login Asli) -->
                                    <div class="absolute inset-0 flex items-center justify-center p-2 pointer-events-none">
                                        <div class="w-48 h-24 rounded-xl bg-white/30 backdrop-blur-md border border-white/40 shadow-xl flex items-center justify-between p-1.5 gap-1.5">
                                            <!-- Sisi Kiri: Branding Miniatur -->
                                            <div class="w-1/2 h-full rounded-lg bg-gradient-to-b from-[#4a0613]/90 via-[#081e54]/90 to-[#004fcf]/90 flex flex-col items-center justify-between text-center p-1 border-r border-white/20">
                                                <div class="px-1 py-0.5 rounded-full bg-white/10 border border-white/20 text-[5px] text-cyan-200 font-bold uppercase tracking-widest leading-none">
                                                    PORTAL RESMI
                                                </div>
                                                <div class="w-6 h-6 rounded-full bg-white/20 border border-white/40 flex items-center justify-center shadow-sm">
                                                    <div class="w-4 h-4 rounded-full bg-amber-400/30 border border-amber-300/40"></div>
                                                </div>
                                                <div>
                                                    <div class="text-[6.5px] font-black text-white leading-none">SIM-BBM</div>
                                                    <div class="text-[4.5px] text-blue-200 leading-none">POLDA NTB</div>
                                                </div>
                                            </div>
                                            <!-- Sisi Kanan: Action Panel Miniatur -->
                                            <div class="w-1/2 h-full rounded-lg bg-white/95 p-1 flex flex-col justify-between">
                                                <div class="flex items-center gap-1">
                                                    <div class="w-2 h-2 rounded bg-blue-600 shrink-0"></div>
                                                    <div class="h-1 w-8 bg-slate-300 rounded"></div>
                                                </div>
                                                <div class="grid grid-cols-5 gap-0.5 my-0.5">
                                                    <div class="h-1.5 rounded bg-blue-100"></div>
                                                    <div class="h-1.5 rounded bg-amber-100"></div>
                                                    <div class="h-1.5 rounded bg-emerald-100"></div>
                                                    <div class="h-1.5 rounded bg-cyan-100"></div>
                                                    <div class="h-1.5 rounded bg-purple-100"></div>
                                                </div>
                                                <div class="h-2.5 w-full bg-blue-600 rounded flex items-center justify-center text-[5px] font-black text-white">LOGIN KE SISTEM</div>
                                                <div class="h-2 w-full bg-slate-100 rounded border border-slate-200 flex items-center justify-center text-[4px] text-blue-600 font-bold">CEK SALDO</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Badge Info Posisi Aktif -->
                                    <div class="absolute bottom-1.5 right-1.5 px-1.5 py-0.5 rounded bg-black/60 backdrop-blur-md text-[8px] text-white/90 font-medium border border-white/20 flex items-center gap-1 pointer-events-none">
                                        <span>Posisi Y: <span x-text="posY + '%'"></span> | Zoom: <span x-text="zoomLevel + '%'"></span></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Aksi Upload & Reset -->
                            <div class="flex flex-wrap items-center justify-between gap-1.5 pt-0.5">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <input type="file" 
                                           name="login_background_image" 
                                           x-ref="bgFileInput"
                                           @change="fileChosen($event)" 
                                           accept="image/jpeg,image/png,image/jpg,image/webp" 
                                           class="hidden" 
                                           id="login-bg-file-input">
                                    
                                    <label for="login-bg-file-input" 
                                           class="cursor-pointer inline-flex items-center gap-1 px-2.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:hover:bg-indigo-900/60 dark:text-indigo-300 rounded-lg text-[11px] font-bold border border-indigo-200 dark:border-indigo-700/50 shadow-sm active:scale-95 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>Ganti Foto Gedung</span>
                                    </label>

                                    <button type="button" 
                                            x-show="hasCustomBg && !removeCustomBg"
                                            @click="resetToDefault()"
                                            class="inline-flex items-center gap-1 px-2 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:hover:bg-rose-900/50 dark:text-rose-300 rounded-lg text-[11px] font-bold border border-rose-200 dark:border-rose-700/50 transition">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span>Bawaan</span>
                                    </button>

                                    <input type="hidden" name="remove_login_background_image" :value="removeCustomBg ? '1' : '0'">
                                </div>

                                <span class="text-[9px] text-slate-400">
                                    JPG/PNG/WEBP (Maks 8MB)
                                </span>
                            </div>

                            <!-- Panel 3 Pengaturan Tampilan Gedung (Posisi, Zoom, dan Kegelapan) -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-0.5">
                                
                                <!-- 1. Posisi Vertikal Y (Geser Atas / Tengah / Bawah) -->
                                <div class="p-2.5 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50/50 dark:bg-slate-800/30 space-y-1">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-[10px] font-bold text-slate-800 dark:text-white uppercase tracking-wider">
                                            1. Posisi Vertikal
                                        </label>
                                        <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400" x-text="posY + '%'"></span>
                                    </div>
                                    <div class="grid grid-cols-3 gap-1">
                                        <button type="button" @click="posY = 15" class="py-0.5 px-1 text-[9px] font-bold rounded-md border text-center transition"
                                                :class="posY <= 25 ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-white/10'">
                                            Atas
                                        </button>
                                        <button type="button" @click="posY = 50" class="py-0.5 px-1 text-[9px] font-bold rounded-md border text-center transition"
                                                :class="posY > 25 && posY < 75 ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-white/10'">
                                            Tengah
                                        </button>
                                        <button type="button" @click="posY = 85" class="py-0.5 px-1 text-[9px] font-bold rounded-md border text-center transition"
                                                :class="posY >= 75 ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-white/10'">
                                            Bawah
                                        </button>
                                    </div>
                                    <input type="range" 
                                           name="login_bg_pos_y" 
                                           min="0" 
                                           max="100" 
                                           step="5" 
                                           x-model="posY" 
                                           class="w-full accent-indigo-600 cursor-pointer h-1.5 pt-0.5">
                                </div>

                                <!-- 2. Skala Perbesaran (Zoom Level Gedung) -->
                                <div class="p-2.5 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50/50 dark:bg-slate-800/30 space-y-1">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-[10px] font-bold text-slate-800 dark:text-white uppercase tracking-wider">
                                            2. Skala Zoom
                                        </label>
                                        <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400" x-text="zoomLevel + '%'"></span>
                                    </div>
                                    <input type="range" 
                                           name="login_bg_zoom" 
                                           min="100" 
                                           max="180" 
                                           step="5" 
                                           x-model="zoomLevel" 
                                           class="w-full accent-indigo-600 cursor-pointer h-1.5 pt-1">
                                    <div class="flex justify-between text-[8px] text-slate-400 pt-0.5">
                                        <span>100%</span>
                                        <button type="button" @click="zoomLevel = 100" class="text-indigo-500 hover:underline">Reset</button>
                                        <span>180%</span>
                                    </div>
                                </div>

                                <!-- 3. Tingkat Kegelapan Lapisan (Overlay) -->
                                <div class="p-2.5 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50/50 dark:bg-slate-800/30 space-y-1">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-[10px] font-bold text-slate-800 dark:text-white uppercase tracking-wider">
                                            3. Kegelapan
                                        </label>
                                        <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400" x-text="overlayLevel + '%'"></span>
                                    </div>
                                    <input type="range" 
                                           name="login_bg_overlay" 
                                           min="10" 
                                           max="70" 
                                           step="5" 
                                           x-model="overlayLevel" 
                                           class="w-full accent-indigo-600 cursor-pointer h-1.5 pt-1">
                                    <div class="flex justify-between text-[8px] text-slate-400 pt-0.5">
                                        <span>10%</span>
                                        <button type="button" @click="overlayLevel = 25" class="text-indigo-500 hover:underline">25%</button>
                                        <span>70%</span>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>

    @push('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
    <style>
        /* Lingkaran cropper visual bulat */
        .circular-cropper .cropper-view-box,
        .circular-cropper .cropper-face {
            border-radius: 50% !important;
        }
        .circular-cropper .cropper-view-box {
            outline: 2px solid #38bdf8 !important;
            outline-color: rgba(56, 189, 248, 0.9) !important;
            box-shadow: 0 0 0 1000px rgba(15, 23, 42, 0.75);
        }

        /* Persegi panjang cropper visual rounded rectangle */
        .rectangular-cropper .cropper-view-box,
        .rectangular-cropper .cropper-face {
            border-radius: 1rem !important;
        }
        .rectangular-cropper .cropper-view-box {
            outline: 2px solid #38bdf8 !important;
            outline-color: rgba(56, 189, 248, 0.9) !important;
            box-shadow: 0 0 0 1000px rgba(15, 23, 42, 0.75);
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script>
        // Mencegah window desync scroll bug saat interaksi form di layout overflow-hidden
        window.addEventListener('scroll', function() {
            if (window.scrollY !== 0 || window.scrollX !== 0) {
                window.scrollTo(0, 0);
            }
        }, { passive: true });
    </script>
    @endpush
</x-app-layout>