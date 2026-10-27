<x-guest-layout maxWidth="max-w-5xl">
    <div x-data="{ 
            showLoginModal: {{ $errors->any() || session('error') ? 'true' : 'false' }}, 
            activeMenuTarget: '{{ old('redirect_to', request('redirect_to', '')) }}',
            menuTitle: '{{ old('redirect_to') ? strtoupper(old('redirect_to')) : (request('redirect_to') ? strtoupper(request('redirect_to')) : '') }}',
            openMenu(name, title) {
                this.activeMenuTarget = name;
                this.menuTitle = title;
                this.showLoginModal = true;
                
                // 1. Tulis langsung ke Cookie agar backend SELALU menerima via HTTP header
                if (name) {
                    document.cookie = 'simbbm_redirect_to=' + encodeURIComponent(name) + '; path=/; max-age=3600; SameSite=Lax';
                } else {
                    document.cookie = 'simbbm_redirect_to=; path=/; max-age=0; SameSite=Lax';
                }
                
                // 2. Tulis langsung ke DOM input & action Modal Login
                const redirectInput = document.getElementById('login_modal_redirect_input');
                if (redirectInput) {
                    redirectInput.value = name;
                    redirectInput.setAttribute('value', name);
                }
                const formModal = document.getElementById('login_modal_form');
                if (formModal) {
                    formModal.action = '{{ route('login') }}' + (name ? '?redirect_to=' + encodeURIComponent(name) : '');
                }

                // Fokus ke input email setelah modal terbuka
                setTimeout(() => {
                    const emailInput = document.getElementById('login_modal_email');
                    if (emailInput) {
                        emailInput.focus();
                    }
                }, 100);
            },
            prepareSubmit() {
                const target = this.activeMenuTarget;
                if (target) {
                    document.cookie = 'simbbm_redirect_to=' + encodeURIComponent(target) + '; path=/; max-age=3600; SameSite=Lax';
                    const redirectInput = document.getElementById('login_modal_redirect_input');
                    if (redirectInput) {
                        redirectInput.value = target;
                        redirectInput.setAttribute('value', target);
                    }
                    const formModal = document.getElementById('login_modal_form');
                    if (formModal) {
                        formModal.action = '{{ route('login') }}?redirect_to=' + encodeURIComponent(target);
                    }
                }
            }
         }"
         class="w-full">

        <!-- ========================================================
             1. MOBILE VIEW (Gradasi Vertikal: Nuansa Merah Marun di Atas, Biru SPBP di Tengah & Bawah)
             ======================================================== -->
        <div class="md:hidden min-h-screen flex flex-col justify-between bg-gradient-to-b from-[#590714] via-[#0e2c7a] to-[#004fcf] text-white relative overflow-hidden select-none">
            
            <!-- Dynamic Background Lighting: Merah Marun Khusus di Bagian Atas Saja (Tanpa Marun di Samping) -->
            <!-- 1. Ambient Glow Merah Marun Membentang di Bagian Atas Header -->
            <div class="absolute -top-14 left-1/2 -translate-x-1/2 w-full h-56 bg-gradient-to-b from-[#730a17]/80 via-[#590714]/50 to-transparent blur-2xl pointer-events-none"></div>
            
            <!-- 2. Kilau Lembut di Atas Kiri -->
            <div class="absolute -top-10 -left-10 w-60 h-60 bg-rose-900/35 rounded-full blur-3xl pointer-events-none"></div>
            
            <!-- 3. Ambient Biru Cerah di Tengah & Belakang Panggung (Sisi Samping Murni Biru) -->
            <div class="absolute top-[45%] left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-blue-500/25 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Top Header Mobile (Status, Logo & Bantuan ala BRImo) -->
            <div class="relative z-10 px-5 pt-5 pb-1 flex items-center justify-between">
                <!-- ID / Country Badge -->
                <div class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full border border-white/25 text-xs font-black text-white shadow-sm">
                    <span class="w-3.5 h-3.5 rounded-full overflow-hidden flex flex-col shadow-inner shrink-0 border border-white/40">
                        <span class="bg-red-600 h-1/2 w-full"></span>
                        <span class="bg-white h-1/2 w-full"></span>
                    </span>
                    <span class="tracking-wide text-[11px]">SPBP</span>
                </div>

                <!-- Center Logo Brand -->
                <div class="flex items-center gap-2">
                    <img src="/rolog.png" onerror="this.onerror=null; this.src='/TRIBRATA.png';" alt="Logo" class="w-8 h-8 object-contain drop-shadow-md">
                    <div class="flex flex-col text-left">
                        <span class="font-black text-xs leading-none tracking-tight text-white uppercase drop-shadow-sm">SIM-BBM</span>
                        <span class="text-[8px] font-bold text-cyan-200 tracking-wider">POLDA NTB</span>
                    </div>
                </div>

                <!-- Kontak Kami / Bantuan Button (Headset Icon ala BRImo) -->
                <a href="{{ route('cek-saldo.index') }}" 
                   title="Bantuan / Cek Saldo"
                   class="inline-flex items-center gap-1.5 text-[11px] font-black text-white bg-white/15 hover:bg-white/25 px-3 py-1 rounded-full border border-white/20 backdrop-blur-md transition-all shadow-sm">
                    <svg class="w-3.5 h-3.5 text-cyan-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z" />
                    </svg>
                    <span>Bantuan</span>
                </a>
            </div>

            <!-- Hero Section (Headline & Stage Visual) -->
            <div class="relative z-10 px-5 pt-1 pb-3 flex-1 flex flex-col items-center justify-center text-center">
                <!-- Slogan Headline -->
                <h1 class="text-xl sm:text-2xl font-black text-white leading-tight tracking-tight drop-shadow-md max-w-xs mb-3">
                    Yuk! mulai transaksimu bersama SPBP Mobile!
                </h1>

                <!-- Stage Visual Container -->
                <div class="relative w-72 h-64 flex items-center justify-center">
                    
                    @php
                        $customHero = !empty($mobileHeroImage) ? $mobileHeroImage : \App\Models\Setting::where('key', 'mobile_hero_image')->value('value');
                        $heroFit = !empty($heroFit) ? $heroFit : (\App\Models\Setting::where('key', 'mobile_hero_fit')->value('value') ?? 'cover');
                        $heroZoom = !empty($heroZoom) ? $heroZoom : (\App\Models\Setting::where('key', 'mobile_hero_zoom')->value('value') ?? '100');
                        $heroBg = !empty($heroBg) ? $heroBg : (\App\Models\Setting::where('key', 'mobile_hero_bg')->value('value') ?? 'transparent');
                        $heroShape = !empty($heroShape) ? $heroShape : (\App\Models\Setting::where('key', 'mobile_hero_shape')->value('value') ?? 'circle');
                    @endphp

                    <!-- Glowing Shadow Stage (Menyesuaikan Bentuk Hero) -->
                    <div class="absolute -bottom-2 {{ $heroShape === 'rectangle' ? 'w-72 h-10' : 'w-56 h-12' }} bg-blue-950/45 rounded-full blur-xl pointer-events-none"></div>

                    <!-- Container Gambar Hero Mobile (Hasil Upload Pengaturan Super Admin) -->
                    @if(!empty($customHero) && file_exists(public_path($customHero)))
                        <div class="relative z-10 {{ $heroShape === 'rectangle' ? 'w-72 h-44 sm:w-80 sm:h-48 rounded-2xl' : 'w-64 h-64 rounded-full' }} overflow-hidden flex items-center justify-center transform hover:scale-105 transition-all duration-500 animate-float"
                             style="{{ !empty($heroBg) && $heroBg !== 'transparent' ? 'background-color: '.$heroBg.';' : '' }} -webkit-mask-image: radial-gradient({{ $heroShape === 'rectangle' ? 'ellipse' : 'circle' }} at center, rgba(0,0,0,1) 70%, rgba(0,0,0,0) 99%); mask-image: radial-gradient({{ $heroShape === 'rectangle' ? 'ellipse' : 'circle' }} at center, rgba(0,0,0,1) 70%, rgba(0,0,0,0) 99%);">
                            <img src="/{{ ltrim($customHero, '/') }}" alt="SPBP Hero Banner" 
                                 class="w-full h-full {{ $heroFit === 'contain' ? 'object-contain p-1' : 'object-cover' }} transition-all duration-300"
                                 style="{{ !empty($heroZoom) && $heroZoom != 100 ? 'transform: scale('.($heroZoom/100).');' : '' }}">
                        </div>
                    @else
                        <div class="relative z-10 max-w-[260px] max-h-[220px] flex items-center justify-center p-2 transform hover:scale-105 transition-transform duration-500 animate-float">
                            <!-- Logo Resmi SIM-BBM Biro Logistik Polda NTB Jika Belum Upload Gambar Kustom -->
                            <img src="/rolog.png" onerror="this.onerror=null; this.src='/TRIBRATA.png';" alt="SPBP Logo" class="w-36 h-36 object-contain drop-shadow-[0_15px_35px_rgba(0,0,0,0.45)]">
                        </div>
                    @endif
                </div>
            </div>

            <!-- ========================================================
                 BOTTOM FAST MENU CONTAINER (Pure White Solid ala BRImo)
                 ======================================================== -->
            <div class="relative z-20 bg-white rounded-t-[2.5rem] pt-4 pb-6 px-4 sm:px-5 shadow-[0_-12px_40px_rgba(0,0,0,0.22)] border-t border-slate-100 text-slate-800">
                
                <!-- Fast Menu Title & Info Icon -->
                <div class="flex items-center justify-center gap-1.5 mb-3.5">
                    <span class="text-xs sm:text-sm font-black tracking-tight text-slate-800">Fast Menu</span>
                    <button type="button" @click="openMenu('INFO', 'Informasi Fitur Cepat')" class="text-blue-600 hover:text-blue-700 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <!-- 5 Fast Menu Icons (Warna-Warni Pastel Cerah, Teks Kecil Presisi) -->
                <div class="grid grid-cols-5 gap-1 text-center pb-2">
                    
                    <!-- 1. KENDARAAN (Soft Blue) -->
                    <button type="button" 
                            @click="openMenu('kendaraan', 'Menu Kendaraan Dinas')"
                            class="flex flex-col items-center group active:scale-90 transition-transform">
                        <div class="w-12 h-12 sm:w-13 sm:h-13 rounded-2xl bg-blue-50 border border-blue-200/80 text-blue-600 flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:bg-blue-100/70 transition-all">
                            <!-- Car Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                            </svg>
                        </div>
                        <span class="mt-1.5 text-[9px] font-black uppercase text-slate-800 tracking-tight leading-tight line-clamp-1">
                            Kendaraan
                        </span>
                    </button>

                    <!-- 2. HUTANG (Soft Amber / Orange) -->
                    <button type="button" 
                            @click="openMenu('hutang', 'Pencatatan Bon / Hutang BBM')"
                            class="flex flex-col items-center group active:scale-90 transition-transform">
                        <div class="w-12 h-12 sm:w-13 sm:h-13 rounded-2xl bg-amber-50 border border-amber-200/80 text-amber-600 flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:bg-amber-100/70 transition-all">
                            <!-- Invoice / Receipt Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8h4" />
                            </svg>
                        </div>
                        <span class="mt-1.5 text-[9px] font-black uppercase text-slate-800 tracking-tight leading-tight line-clamp-1">
                            Hutang
                        </span>
                    </button>

                    <!-- 3. RIWAYAT BBM (Soft Emerald Green) -->
                    <button type="button" 
                            @click="openMenu('riwayat', 'Riwayat Distribusi BBM')"
                            class="flex flex-col items-center group active:scale-90 transition-transform">
                        <div class="w-12 h-12 sm:w-13 sm:h-13 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-600 flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:bg-emerald-100/70 transition-all">
                            <!-- Clock / History Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="mt-1.5 text-[9px] font-black uppercase text-slate-800 tracking-tight leading-tight line-clamp-1">
                            Riwayat BBM
                        </span>
                    </button>

                    <!-- 4. TRANSFER (Soft Cyan / Sky) -->
                    <button type="button" 
                            @click="openMenu('transfer', 'Transfer Saldo Antar Kendaraan')"
                            class="flex flex-col items-center group active:scale-90 transition-transform">
                        <div class="w-12 h-12 sm:w-13 sm:h-13 rounded-2xl bg-cyan-50 border border-cyan-200/80 text-cyan-600 flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:bg-cyan-100/70 transition-all">
                            <!-- Transfer Arrows Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <span class="mt-1.5 text-[9px] font-black uppercase text-slate-800 tracking-tight leading-tight line-clamp-1">
                            Transfer
                        </span>
                    </button>

                    <!-- 5. PERSETUJUAN KENDARAAN (Soft Purple / Violet) -->
                    <button type="button" 
                            @click="openMenu('persetujuan', 'Persetujuan Kendaraan Baru')"
                            class="flex flex-col items-center group active:scale-90 transition-transform">
                        <div class="w-12 h-12 sm:w-13 sm:h-13 rounded-2xl bg-purple-50 border border-purple-200/80 text-purple-600 flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:bg-purple-100/70 transition-all">
                            <!-- Shield Check / Approval Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <span class="mt-1.5 text-[9px] font-black uppercase text-slate-800 tracking-tight leading-tight line-clamp-1">
                            Persetujuan
                        </span>
                    </button>
                </div>

                <!-- Slider Dots Indicator (Gaya BRImo: Aktif Biru + Abu-abu) -->
                <div class="flex items-center justify-center gap-1.5 my-3">
                    <span class="w-8 h-1.5 rounded-full bg-[#0062ff]"></span>
                    <span class="w-2 h-1.5 rounded-full bg-slate-200"></span>
                </div>

                <!-- Action Button Row: Login & QR Shortcut ala BRImo -->
                <div class="flex items-center gap-2.5 pt-0.5">
                    <!-- Tombol Login Biru Cerah Bergradasi -->
                    <button type="button" 
                            @click="openMenu('', 'Masuk Akun SIM-BBM')"
                            class="flex-1 py-3.5 px-6 rounded-2xl bg-gradient-to-r from-[#0062ff] to-[#0050d4] hover:from-[#0055e0] hover:to-[#0047bf] text-white font-black text-sm uppercase tracking-wider shadow-lg shadow-blue-500/35 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                        <span>Login</span>
                    </button>

                    <!-- Tombol Cek Saldo Tanpa Login (Icon Scanner / Barcode ala BRImo) -->
                    <a href="{{ route('cek-saldo.index') }}"
                       title="Cek Saldo Tanpa Login"
                       class="w-12 h-12 rounded-2xl border-2 border-blue-200 bg-blue-50/90 hover:bg-blue-100 text-[#0062ff] flex items-center justify-center shadow-sm active:scale-90 transition-all shrink-0">
                        <!-- Barcode Scanner Icon -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- ========================================================
             2. DESKTOP VIEW (Modern Glass Card yang Elegan & Proporsional)
             ======================================================== -->
        <div class="hidden md:flex bg-white/30 backdrop-blur-2xl rounded-[2rem] shadow-[0_25px_70px_rgba(2,10,35,0.45)] ring-1 ring-white/70 overflow-hidden flex-row w-full max-w-4xl mx-auto border border-white/40 transition-all duration-300 md:min-h-[520px] lg:min-h-[560px]">
            
            <!-- Left Side: Branding, Panggung Hero & Identitas Institusi Presisi -->
            <div class="w-1/2 bg-gradient-to-b from-[#4a0613]/85 via-[#081e54]/90 to-[#004fcf]/90 backdrop-blur-2xl text-white relative items-center justify-between overflow-hidden flex flex-col py-7 px-6 lg:py-8 lg:px-7 text-center select-none border-r border-white/20">
                <!-- Ambient Dynamic Lights -->
                <div class="absolute -top-14 left-1/2 -translate-x-1/2 w-full h-40 bg-gradient-to-b from-[#8a0a1e]/60 via-[#540815]/30 to-transparent blur-3xl pointer-events-none"></div>
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-rose-950/30 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute top-[42%] left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Top Badge: Status Portal Resmi -->
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/25 shadow-sm text-[10px] font-black tracking-wide text-cyan-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_8px_#34d399]"></span>
                        <span class="tracking-widest uppercase">PORTAL RESMI BIRO LOGISTIK</span>
                    </div>
                </div>

                <!-- Center Content: Panggung Dome & Banner Hero -->
                <div class="relative z-10 flex flex-col items-center max-w-sm my-auto py-2">
                    <!-- Stage Visual Container -->
                    <div class="relative {{ $heroShape === 'rectangle' ? 'w-64 h-48' : 'w-52 h-48' }} flex items-center justify-center my-1.5 transition-all duration-300">
                        <!-- Glowing Shadow Stage (Menyesuaikan Bentuk Hero) -->
                        <div class="absolute -bottom-1 {{ $heroShape === 'rectangle' ? 'w-60' : 'w-44' }} h-8 bg-blue-950/60 rounded-full blur-lg pointer-events-none"></div>

                        @if(!empty($customHero) && file_exists(public_path($customHero)))
                            <div class="relative z-10 {{ $heroShape === 'rectangle' ? 'w-56 h-36 lg:w-60 lg:h-38 rounded-2xl' : 'w-44 h-44 rounded-full' }} overflow-hidden flex items-center justify-center transform hover:scale-105 transition-all duration-500 animate-float"
                                 style="{{ !empty($heroBg) && $heroBg !== 'transparent' ? 'background-color: '.$heroBg.';' : '' }} -webkit-mask-image: radial-gradient({{ $heroShape === 'rectangle' ? 'ellipse' : 'circle' }} at center, rgba(0,0,0,1) 70%, rgba(0,0,0,0) 99%); mask-image: radial-gradient({{ $heroShape === 'rectangle' ? 'ellipse' : 'circle' }} at center, rgba(0,0,0,1) 70%, rgba(0,0,0,0) 99%);">
                                <img src="/{{ ltrim($customHero, '/') }}" alt="SPBP Hero Banner" 
                                     class="w-full h-full {{ $heroFit === 'contain' ? 'object-contain p-1' : 'object-cover' }} transition-all duration-300"
                                     style="{{ !empty($heroZoom) && $heroZoom != 100 ? 'transform: scale('.($heroZoom/100).');' : '' }}">
                            </div>
                        @else
                            <div class="relative z-10 max-w-[200px] max-h-[180px] flex items-center justify-center p-1.5 transform hover:scale-105 transition-transform duration-500 animate-float">
                                <img src="/rolog.png" onerror="this.onerror=null; this.src='/TRIBRATA.png';" alt="SPBP Logo" class="w-28 h-28 object-contain drop-shadow-[0_14px_30px_rgba(0,0,0,0.55)]">
                            </div>
                        @endif
                    </div>

                    <h2 class="text-xl lg:text-2xl font-black text-white tracking-tight drop-shadow-md mt-2">SIM-BBM POLDA NTB</h2>
                    
                    <div class="inline-flex items-center gap-1 px-3 py-0.5 rounded-full bg-blue-500/25 border border-blue-400/35 text-cyan-200 text-[9px] font-extrabold uppercase tracking-widest mt-1.5 mb-2 shadow-sm">
                        <span>Sistem Informasi Manajemen BBM</span>
                    </div>

                    <p class="text-blue-100/90 text-[11.5px] leading-relaxed max-w-xs font-normal mb-2 drop-shadow">
                        Platform logistik terpadu untuk monitoring dan alokasi BBM kendaraan dinas secara akuntabel, transparan, dan presisi.
                    </p>
                </div>

                <!-- Bottom Features / Core Values: 3 Glass Pill Cards -->
                <div class="relative z-10 grid grid-cols-3 gap-2.5 w-full max-w-xs pt-2">
                    <div class="flex flex-col items-center justify-center py-2 px-1.5 rounded-xl bg-white/10 hover:bg-white/15 border border-white/20 backdrop-blur-md transition-all group shadow-sm">
                        <div class="w-7 h-7 rounded-lg bg-blue-500/30 border border-blue-400/40 flex items-center justify-center text-cyan-300 mb-1 group-hover:scale-110 transition-transform">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-[9px] text-white font-extrabold tracking-wide uppercase">Efisien</span>
                    </div>
                    <div class="flex flex-col items-center justify-center py-2 px-1.5 rounded-xl bg-white/10 hover:bg-white/15 border border-white/20 backdrop-blur-md transition-all group shadow-sm">
                        <div class="w-7 h-7 rounded-lg bg-emerald-500/30 border border-emerald-400/40 flex items-center justify-center text-emerald-300 mb-1 group-hover:scale-110 transition-transform">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <span class="text-[9px] text-white font-extrabold tracking-wide uppercase">Aman</span>
                    </div>
                    <div class="flex flex-col items-center justify-center py-2 px-1.5 rounded-xl bg-white/10 hover:bg-white/15 border border-white/20 backdrop-blur-md transition-all group shadow-sm">
                        <div class="w-7 h-7 rounded-lg bg-purple-500/30 border border-purple-400/40 flex items-center justify-center text-purple-300 mb-1 group-hover:scale-110 transition-transform">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <span class="text-[9px] text-white font-extrabold tracking-wide uppercase">Terukur</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Desktop Fast Menu & Action Panel (Modern Frosted Glass Panel) -->
            <div class="w-1/2 py-7 px-6 lg:py-8 lg:px-7 relative bg-white/92 backdrop-blur-2xl text-slate-800 flex flex-col justify-between">
                
                <!-- Header Atas: Logo & Badge Identitas SPBP NTB -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <img src="/rolog.png" onerror="this.onerror=null; this.src='/TRIBRATA.png';" alt="Logo Biro Logistik Polda NTB" class="h-11 w-auto object-contain drop-shadow-[0_4px_10px_rgba(0,0,0,0.15)] hover:scale-105 transition-transform duration-300 shrink-0">
                        <div>
                            <h3 class="text-lg lg:text-xl font-black text-slate-900 leading-tight tracking-tight">Portal SIM-BBM</h3>
                            <p class="text-[11px] text-slate-500 font-semibold">Biro Logistik Polda Nusa Tenggara Barat</p>
                        </div>
                    </div>

                    <!-- Badge Negara / SPBP NTB -->
                    <div class="inline-flex items-center gap-1 bg-gradient-to-r from-red-50/80 via-white to-blue-50/80 border border-slate-200/90 px-2.5 py-1 rounded-full text-xs font-black shadow-sm">
                        <span class="w-3 h-3 rounded-full overflow-hidden flex flex-col shadow-inner shrink-0 border border-slate-300">
                            <span class="bg-red-600 h-1/2 w-full"></span>
                            <span class="bg-white h-1/2 w-full"></span>
                        </span>
                        <span class="tracking-wider text-[9px] font-black uppercase text-blue-900">SPBP NTB</span>
                    </div>
                </div>

                <!-- Bagian Tengah: Fast Menu & Keterangan Navigasi -->
                <div class="my-auto py-3.5 space-y-3.5">
                    
                    <!-- Fast Menu Card Container Modern -->
                    <div class="bg-gradient-to-b from-slate-50/95 to-blue-50/50 rounded-2xl p-4 border border-blue-100/90 shadow-[0_6px_20px_rgba(0,40,120,0.04)]">
                        <div class="flex items-center justify-between mb-2.5 px-0.5">
                            <div class="flex items-center gap-1.5">
                                <span class="w-5 h-5 rounded-md bg-blue-600 text-white flex items-center justify-center shadow-sm">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                                <span class="text-[11px] font-black tracking-tight text-slate-900 uppercase">Fast Menu Layanan</span>
                            </div>
                            <span class="text-[9px] font-black text-blue-700 bg-blue-100/80 border border-blue-200 px-2 py-0.5 rounded-full uppercase tracking-wider">Akses Langsung</span>
                        </div>

                        <!-- 5 Fast Menu Buttons -->
                        <div class="grid grid-cols-5 gap-1.5 text-center pb-0.5">
                            <!-- 1. KENDARAAN -->
                            <button type="button" 
                                     @click="openMenu('kendaraan', 'Menu Kendaraan Dinas')"
                                    class="flex flex-col items-center group active:scale-95 transition-all">
                                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-b from-blue-50 to-blue-100/80 border border-blue-200 text-blue-600 flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:bg-blue-100 group-hover:-translate-y-0.5 transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                    </svg>
                                </div>
                                <span class="mt-1.5 text-[9px] font-black uppercase text-slate-800 tracking-tight leading-tight line-clamp-1 group-hover:text-blue-600 transition-colors">Kendaraan</span>
                            </button>

                            <!-- 2. HUTANG -->
                            <button type="button" 
                                    @click="openMenu('hutang', 'Pencatatan Bon / Hutang BBM')"
                                    class="flex flex-col items-center group active:scale-95 transition-all">
                                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-b from-amber-50 to-amber-100/80 border border-amber-200 text-amber-600 flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:bg-amber-100 group-hover:-translate-y-0.5 transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8h4" />
                                    </svg>
                                </div>
                                <span class="mt-1.5 text-[9px] font-black uppercase text-slate-800 tracking-tight leading-tight line-clamp-1 group-hover:text-amber-600 transition-colors">Hutang</span>
                            </button>

                            <!-- 3. RIWAYAT BBM -->
                            <button type="button" 
                                    @click="openMenu('riwayat', 'Riwayat Distribusi BBM')"
                                    class="flex flex-col items-center group active:scale-95 transition-all">
                                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-b from-emerald-50 to-emerald-100/80 border border-emerald-200 text-emerald-600 flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:bg-emerald-100 group-hover:-translate-y-0.5 transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="mt-1.5 text-[9px] font-black uppercase text-slate-800 tracking-tight leading-tight line-clamp-1 group-hover:text-emerald-600 transition-colors">Riwayat</span>
                            </button>

                            <!-- 4. TRANSFER -->
                            <button type="button" 
                                    @click="openMenu('transfer', 'Transfer Saldo Antar Kendaraan')"
                                    class="flex flex-col items-center group active:scale-95 transition-all">
                                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-b from-cyan-50 to-cyan-100/80 border border-cyan-200 text-cyan-600 flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:bg-cyan-100 group-hover:-translate-y-0.5 transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </div>
                                <span class="mt-1.5 text-[9px] font-black uppercase text-slate-800 tracking-tight leading-tight line-clamp-1 group-hover:text-cyan-600 transition-colors">Transfer</span>
                            </button>

                            <!-- 5. PERSETUJUAN -->
                            <button type="button" 
                                    @click="openMenu('persetujuan', 'Persetujuan Kendaraan Baru')"
                                    class="flex flex-col items-center group active:scale-95 transition-all">
                                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-b from-purple-50 to-purple-100/80 border border-purple-200 text-purple-600 flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:bg-purple-100 group-hover:-translate-y-0.5 transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <span class="mt-1.5 text-[9px] font-black uppercase text-slate-800 tracking-tight leading-tight line-clamp-1 group-hover:text-purple-600 transition-colors">Setuju</span>
                            </button>
                        </div>

                        <!-- Slider Dots Indicator (Gaya Modern) -->
                        <div class="flex items-center justify-center gap-1.5 mt-2.5">
                            <span class="w-6 h-1 rounded-full bg-[#0062ff] shadow-sm shadow-blue-500/50"></span>
                            <span class="w-1.5 h-1 rounded-full bg-slate-300"></span>
                        </div>
                    </div>

                    <!-- Keterangan Petunjuk Masuk Berbentuk Info Strip -->
                    <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl bg-blue-50/70 border border-blue-100/90 text-slate-600 text-[11px] leading-relaxed">
                        <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Pilih menu layanan cepat di atas atau klik <strong>Login</strong> untuk masuk.</span>
                    </div>
                </div>

                <!-- Bagian Bawah: Tombol Login, Shortcut Cek Saldo & Security Badge -->
                <div class="space-y-2.5 pt-1">
                    <!-- Tombol Utama Login -->
                    <button type="button" 
                            @click="openMenu('', 'Masuk Akun SIM-BBM')"
                            class="group w-full py-3.5 px-5 rounded-xl bg-gradient-to-r from-[#0055ff] via-[#0062ff] to-[#0047d4] hover:from-[#0047d4] hover:to-[#003cb0] text-white font-black text-xs uppercase tracking-wider shadow-[0_10px_24px_rgba(0,98,255,0.32)] hover:shadow-[0_14px_30px_rgba(0,98,255,0.45)] active:scale-[0.98] transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <span>LOGIN KE SISTEM</span>
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>

                    <!-- Tombol Cek Saldo Tanpa Login -->
                    <a href="{{ route('cek-saldo.index') }}"
                       class="group w-full py-3 px-4 bg-white hover:bg-blue-50/90 text-[#0055ff] border-2 border-blue-200/90 hover:border-blue-400 font-black rounded-xl transition-all duration-200 text-[11px] uppercase tracking-wider flex items-center justify-center gap-2 shadow-sm active:scale-[0.99]">
                        <svg class="w-3.5 h-3.5 text-[#0055ff] group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                        <span>Cek Saldo Tanpa Login</span>
                    </a>

                    <!-- Security & Verifikasi Footer -->
                    <div class="flex items-center justify-center gap-1.5 text-[9px] font-bold text-slate-500 tracking-wider uppercase pt-1.5">
                        <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/>
                        </svg>
                        <span>Enkripsi 256-Bit SSL &bull; SIM-BBM Biro Logistik Polda NTB</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- ========================================================
             3. UNIVERSAL LOGIN MODAL (Mobile: Slide-Up Bottom Sheet | Desktop: Centered Dialog Popup)
             ======================================================== -->
        <div x-show="showLoginModal" 
             x-cloak
             class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <!-- Backdrop Gelap dengan Blur -->
            <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-md" @click="showLoginModal = false"></div>

            <!-- Modal Content Card -->
            <div class="relative z-10 w-full max-w-md bg-white rounded-t-[2.4rem] sm:rounded-3xl p-6 sm:p-7 shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto text-slate-800"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="translate-y-full sm:translate-y-4 sm:scale-95"
                 x-transition:enter-end="translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="translate-y-0 sm:scale-100"
                 x-transition:leave-end="translate-y-full sm:translate-y-4 sm:scale-95">
                
                <!-- Drag Handle Pill (Mobile Only) -->
                <div class="sm:hidden w-12 h-1.5 bg-slate-300 rounded-full mx-auto mb-4"></div>

                <!-- Header Modal & Close Button -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <img src="/rolog.png" onerror="this.onerror=null; this.src='/TRIBRATA.png';" alt="Logo Biro Logistik" class="h-10 w-auto object-contain drop-shadow-md shrink-0">
                        <div>
                            <h3 class="text-base sm:text-lg font-black text-slate-900 leading-tight">Masuk SIM-BBM</h3>
                            <p class="text-[11px] text-slate-500 font-medium" x-text="menuTitle ? 'Akses ' + menuTitle : 'Silakan isi kredensial akun Anda'"></p>
                        </div>
                    </div>
                    <button type="button" @click="showLoginModal = false" class="p-2 rounded-full text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Flash / Error Alert -->
                @if(session('error'))
                    <div class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 text-xs font-bold flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Form Login Modal (Tunggal untuk Mobile & Desktop) -->
                <form id="login_modal_form" method="POST" @submit="prepareSubmit()" :action="'{{ route('login') }}' + (activeMenuTarget ? '?redirect_to=' + encodeURIComponent(activeMenuTarget) : '')" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="redirect_to" id="login_modal_redirect_input" value="{{ old('redirect_to', request('redirect_to', request()->cookie('simbbm_redirect_to', ''))) }}" :value="activeMenuTarget" x-model="activeMenuTarget">
                    
                    <!-- ID Pengguna / Email / NRP -->
                    <div>
                        <label for="login_modal_email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            {{ isset($personelAccessControl) && $personelAccessControl == '1' ? 'Email / NRP' : 'ID Pengguna / Email' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input id="login_modal_email" type="text" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full pl-11 pr-4 py-2.5 sm:py-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all placeholder-slate-400"
                                placeholder="Masukkan NRP atau Email">
                        </div>
                        @error('email')
                            <p class="text-[11px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div x-data="{ showPass: false }">
                        <div class="flex items-center justify-between mb-1">
                            <label for="login_modal_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Password
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[11px] font-bold text-blue-600 hover:underline">
                                    Lupa Password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="login_modal_password" :type="showPass ? 'text' : 'password'" name="password" required
                                class="w-full pl-11 pr-11 py-2.5 sm:py-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all placeholder-slate-400"
                                placeholder="••••••••">
                            <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                                <svg x-show="!showPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPass" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-2.428 3.015" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-[11px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300 bg-white">
                            <span class="text-xs text-slate-600 font-medium">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <!-- Tombol Masuk Submit -->
                    <div class="pt-2">
                        <button type="submit" 
                            class="w-full py-3.5 bg-gradient-to-r from-[#0062ff] to-[#0050d4] hover:from-[#0055e0] hover:to-[#0047bf] text-white font-black text-sm uppercase tracking-wider rounded-xl shadow-lg shadow-blue-500/35 active:scale-[0.99] transition-all flex items-center justify-center gap-2">
                            <span>MASUK KE SISTEM</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-guest-layout>