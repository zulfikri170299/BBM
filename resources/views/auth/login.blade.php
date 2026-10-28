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

        <!-- Inject Global Modern CSS -->
        <style>
            @keyframes gradientMove {
                0%,100%{background-position:0% 50%} 50%{background-position:100% 50%}
            }
            @keyframes floatBadge {
                0%,100%{transform:translateY(0)} 50%{transform:translateY(-5px)}
            }
            @keyframes glowPulse {
                0%,100%{box-shadow:0 0 20px rgba(0,120,255,0.4),0 0 50px rgba(0,120,255,0.15)}
                50%{box-shadow:0 0 35px rgba(0,120,255,0.65),0 0 80px rgba(0,120,255,0.25)}
            }
            @keyframes slideUp {
                from{transform:translateY(30px);opacity:0} to{transform:translateY(0);opacity:1}
            }
            @keyframes shimmer2 {
                0%{background-position:-200% center} 100%{background-position:200% center}
            }
            @keyframes rotateSlow {
                from{transform:rotate(0deg)} to{transform:rotate(360deg)}
            }
            @keyframes blink {
                0%,100%{opacity:1} 50%{opacity:0.4}
            }
            .mobile-glass-card {
                background: linear-gradient(145deg, rgba(15,23,42,0.95) 0%, rgba(15,23,42,0.88) 100%);
                backdrop-filter: blur(30px) saturate(180%);
                -webkit-backdrop-filter: blur(30px) saturate(180%);
                border-top: 1px solid rgba(255,255,255,0.12);
            }
            .mobile-glass-card::before {
                content: '';
                position: absolute;
                inset-x: 0;
                top: 0;
                height: 1px;
                background: linear-gradient(90deg, transparent, rgba(99,179,237,0.6), rgba(139,92,246,0.4), transparent);
            }
            .icon-btn-glow-blue { box-shadow: 0 6px 18px rgba(59,130,246,0.5); }
            .icon-btn-glow-amber { box-shadow: 0 6px 18px rgba(245,158,11,0.5); }
            .icon-btn-glow-emerald { box-shadow: 0 6px 18px rgba(16,185,129,0.5); }
            .icon-btn-glow-cyan { box-shadow: 0 6px 18px rgba(6,182,212,0.5); }
            .icon-btn-glow-violet { box-shadow: 0 6px 18px rgba(139,92,246,0.5); }
            .login-btn-mobile {
                background: linear-gradient(135deg, #0055ff, #7c3aed, #0055ff);
                background-size: 200% 200%;
                animation: gradientMove 3s ease infinite;
                box-shadow: 0 8px 25px rgba(0,98,255,0.45), 0 2px 8px rgba(124,58,237,0.3);
            }
            .login-btn-mobile:active { transform: scale(0.97); }
            .slide-up-anim { animation: slideUp 0.4s ease both; }
            .desktop-left-panel {
                background: linear-gradient(-45deg, #030712, #0f172a, #071a38, #0c1a3a);
                background-size: 400% 400%;
                animation: gradientMove 10s ease infinite;
            }
            .desktop-right-panel {
                background: rgba(255,255,255,0.96);
                backdrop-filter: blur(20px);
            }
            .desktop-card-wrap {
                box-shadow: 0 30px 80px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.08);
            }
            .fast-icon-desktop {
                transition: all 0.2s cubic-bezier(0.34,1.56,0.64,1);
            }
            .fast-icon-desktop:hover { transform: translateY(-5px) scale(1.1); }
            .fast-icon-desktop:active { transform: scale(0.93); }
            .desktop-login-btn {
                background: linear-gradient(135deg, #0047ff, #0062ff, #3b82f6);
                box-shadow: 0 12px 35px rgba(0,98,255,0.45), inset 0 1px 0 rgba(255,255,255,0.2);
                transition: all 0.3s ease;
            }
            .desktop-login-btn:hover {
                box-shadow: 0 16px 45px rgba(0,98,255,0.6), inset 0 1px 0 rgba(255,255,255,0.25);
                transform: translateY(-2px);
            }
            .desktop-login-btn:active { transform: scale(0.98); }
            .neon-border {
                position: relative;
            }
            .neon-border::after {
                content: '';
                position: absolute;
                inset: -1px;
                border-radius: inherit;
                background: linear-gradient(135deg, rgba(0,98,255,0.5), rgba(139,92,246,0.3), rgba(6,182,212,0.4), rgba(0,98,255,0.5));
                background-size: 300% 300%;
                animation: gradientMove 4s ease infinite;
                z-index: -1;
                filter: blur(4px);
            }
            .shimmer-title {
                background: linear-gradient(90deg, #fff 0%, #93c5fd 30%, #fff 50%, #c4b5fd 70%, #fff 100%);
                background-size: 300% auto;
                -webkit-background-clip: text;
                background-clip: text;
                -webkit-text-fill-color: transparent;
                animation: shimmer2 4s linear infinite;
            }
            .dot-float {
                animation: floatBadge 3s ease-in-out infinite;
            }
            .ping-dot::before {
                content: '';
                position: absolute;
                inset: 0;
                border-radius: 50%;
                background: currentColor;
                animation: glowPulse 2s ease infinite;
                opacity: 0.5;
            }
        </style>

        <!-- ========================================================
             1. MOBILE VIEW (Full Hero Background & Dark Glass Fast Menu)
             ======================================================== -->
        <div class="md:hidden h-[100dvh] max-h-[100dvh] flex flex-col justify-between bg-slate-950 text-white relative overflow-hidden select-none"
             style="height: 100dvh; max-height: 100dvh; overflow: hidden;">
            
            @php
                $customHero = !empty($mobileHeroImage) ? $mobileHeroImage : \App\Models\Setting::where('key', 'mobile_hero_image')->value('value');
                $heroFit = !empty($heroFit) ? $heroFit : (\App\Models\Setting::where('key', 'mobile_hero_fit')->value('value') ?? 'cover');
                $heroZoom = !empty($heroZoom) ? $heroZoom : (\App\Models\Setting::where('key', 'mobile_hero_zoom')->value('value') ?? '100');
                $heroBg = !empty($heroBg) ? $heroBg : (\App\Models\Setting::where('key', 'mobile_hero_bg')->value('value') ?? 'transparent');
                $heroShape = !empty($heroShape) ? $heroShape : (\App\Models\Setting::where('key', 'mobile_hero_shape')->value('value') ?? 'circle');
                $isAutoShape = in_array($heroShape, ['auto', 'rectangle']);
                $mobileHeroSlogan = !empty($mobileHeroSlogan) ? $mobileHeroSlogan : (\App\Models\Setting::where('key', 'mobile_hero_slogan')->value('value') ?? 'Ayok Mulai Transaksi dengan SIM-BBM Mobile');
            @endphp

            <!-- Ambient Background Navy dengan Dot Grid & Bintik-Bintik Berjalan Modern -->
            <div class="absolute inset-0 animated-bg pointer-events-none overflow-hidden">
                <!-- Dot Grid Texture Berjalan / Pulse -->
                <div class="absolute inset-0 bg-[radial-gradient(rgba(99,179,237,0.18)_1.2px,transparent_1.2px)] [background-size:20px_20px] pointer-events-none"></div>
                <!-- Ambient Glow Orbs -->
                <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-80 h-80 bg-blue-600/20 rounded-full blur-[80px]"></div>
                <div class="absolute bottom-1/3 right-4 w-60 h-60 bg-indigo-500/15 rounded-full blur-[70px]"></div>

                <!-- Bintik-Bintik Berjalan / Floating Moving Particles (Persis Seperti Desktop) -->
                <div class="absolute top-[10%] left-[10%] w-2 h-2 bg-cyan-300 rounded-full float-dot shadow-[0_0_10px_#67e8f9]" style="animation-delay:0s; animation-duration:3.8s;"></div>
                <div class="absolute top-[18%] right-[14%] w-1.5 h-1.5 bg-blue-400 rounded-full float-dot shadow-[0_0_8px_#60a5fa]" style="animation-delay:1.5s; animation-duration:4.5s;"></div>
                <div class="absolute top-[26%] left-[24%] w-1 h-1 bg-teal-300 rounded-full float-dot shadow-[0_0_6px_#5eead4]" style="animation-delay:0.7s; animation-duration:5s;"></div>
                <div class="absolute top-[34%] right-[22%] w-2.5 h-2.5 bg-indigo-400/90 rounded-full float-dot shadow-[0_0_12px_#818cf8]" style="animation-delay:2.2s; animation-duration:4.2s;"></div>
                <div class="absolute top-[42%] left-[8%] w-1.5 h-1.5 bg-sky-300 rounded-full float-dot shadow-[0_0_8px_#7dd3fc]" style="animation-delay:3.1s; animation-duration:3.5s;"></div>
                <div class="absolute top-[50%] right-[8%] w-2 h-2 bg-cyan-400 rounded-full float-dot shadow-[0_0_10px_#22d3ee]" style="animation-delay:1.0s; animation-duration:4.8s;"></div>
                <div class="absolute top-[15%] left-[65%] w-1.5 h-1.5 bg-blue-200 rounded-full float-dot shadow-[0_0_8px_#bfdbfe]" style="animation-delay:2.7s; animation-duration:5.2s;"></div>
                <div class="absolute top-[65%] left-[12%] w-2 h-2 bg-blue-500/80 rounded-full float-dot shadow-[0_0_10px_#3b82f6]" style="animation-delay:0.3s; animation-duration:4.6s;"></div>
                <div class="absolute top-[58%] right-[18%] w-1.5 h-1.5 bg-teal-200 rounded-full float-dot shadow-[0_0_8px_#99f6e4]" style="animation-delay:1.8s; animation-duration:4.0s;"></div>
            </div>

            <!-- Top Header Mobile -->
            <div class="relative z-10 px-5 pt-3 pb-1 shrink-0 flex items-center justify-between">
                <!-- ID / Country Badge -->
                <div class="inline-flex items-center gap-1.5 bg-black/40 backdrop-blur-md px-3 py-1 rounded-full border border-white/20 text-xs font-black text-white shadow-sm">
                    <span class="w-3.5 h-3.5 rounded-full overflow-hidden flex flex-col shadow-inner shrink-0 border border-white/40">
                        <span class="bg-red-600 h-1/2 w-full"></span>
                        <span class="bg-white h-1/2 w-full"></span>
                    </span>
                    <span class="tracking-wide text-[11px]">SPBP</span>
                </div>

                <!-- Center Logo Brand -->
                <div class="flex items-center gap-2 bg-black/40 backdrop-blur-md px-3 py-1 rounded-full border border-white/20 shadow-sm">
                    <img src="/rolog.png" onerror="this.onerror=null; this.src='/TRIBRATA.png';" alt="Logo" class="w-6 h-6 object-contain drop-shadow-md">
                    <div class="flex flex-col text-left">
                        <span class="font-black text-xs leading-none tracking-tight text-white uppercase drop-shadow-sm">SIM-BBM</span>
                        <span class="text-[8px] font-bold text-cyan-200 tracking-wider">POLDA NTB</span>
                    </div>
                </div>

                <!-- Kontak Kami / Bantuan Button -->
                <a href="{{ route('cek-saldo.index') }}" 
                   title="Bantuan / Cek Saldo"
                   class="inline-flex items-center gap-1.5 text-[11px] font-black text-white bg-black/40 hover:bg-black/60 px-3 py-1 rounded-full border border-white/20 backdrop-blur-md transition-all shadow-sm">
                    <svg class="w-3.5 h-3.5 text-cyan-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z" />
                    </svg>
                    <span>Bantuan</span>
                </a>
            </div>

            <!-- Slogan Title (Dapat Diubah oleh Super Admin di Pengaturan Sistem) -->
            <div class="relative z-10 px-6 pt-16 sm:pt-20 pb-2 shrink-0 text-center">
                <h1 class="text-[19px] sm:text-2xl font-black text-white leading-tight tracking-tight drop-shadow-[0_2px_12px_rgba(0,0,0,0.8)] max-w-xs mx-auto">
                    {{ $mobileHeroSlogan }}
                </h1>
            </div>

            <!-- Middle Container: Media Lingkaran (Circle) Sesuai Contoh Gambar -->
            <div class="relative z-10 flex-1 min-h-0 flex items-end justify-center w-full px-4 overflow-visible">
                <!-- Wrapper Lingkaran dengan Ambient Diffuse Glow di Belakang -->
                <div class="relative flex items-center justify-center -mb-8 sm:-mb-9">
                    <!-- Soft Outer Halo / Ambient Aura yang Menyatu ke Background -->
                    <div class="absolute -inset-3 sm:-inset-4 rounded-full bg-gradient-to-tr from-blue-600/35 via-sky-500/25 to-indigo-500/30 blur-xl pointer-events-none"></div>

                    <!-- Lingkaran Bulat Utama dengan Border Lembut Halus & Shadow Terintegrasi -->
                    <div class="relative w-[75vw] h-[75vw] max-w-[295px] max-h-[295px] sm:max-w-[315px] sm:max-h-[315px] aspect-square rounded-full overflow-hidden flex items-center justify-center bg-slate-900/80 backdrop-blur-md group"
                         style="box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.14), 0 0 25px rgba(56, 189, 248, 0.22), 0 20px 45px rgba(0, 0, 0, 0.85);">
                        
                        @if(!empty($customHero) && file_exists(public_path($customHero)))
                            <img src="/{{ ltrim($customHero, '/') }}" alt="SPBP Hero" 
                                 class="w-full h-full object-cover object-center transition-all duration-300"
                                 style="{{ !empty($heroZoom) && $heroZoom != 100 ? 'transform: scale('.($heroZoom/100).');' : '' }}">
                        @else
                            <!-- Fallback jika belum upload gambar: Logo Default dalam Lingkaran -->
                            <div class="w-full h-full flex flex-col items-center justify-center p-4 text-center animate-float">
                                <div class="w-20 h-20 rounded-full bg-white/10 border border-white/15 flex items-center justify-center p-2.5 shadow-lg mb-1.5">
                                    <img src="/rolog.png" onerror="this.onerror=null; this.src='/TRIBRATA.png';" alt="SPBP Logo" class="max-w-full max-h-full object-contain drop-shadow-md">
                                </div>
                                <span class="text-xs font-black tracking-wide text-white drop-shadow">BIRO LOGISTIK</span>
                                <span class="text-[9.5px] font-bold text-cyan-200">POLDA NTB</span>
                            </div>
                        @endif

                        <!-- Soft Edge Inner Vignette & Ambient Glow: Melembutkan Tepian Agar Menyatu Sempurna -->
                        <div class="absolute inset-0 rounded-full pointer-events-none z-10"
                             style="box-shadow: inset 0 0 22px 6px rgba(7, 24, 61, 0.75), inset 0 0 0 1px rgba(255, 255, 255, 0.14);"></div>
                        <div class="absolute inset-0 rounded-full bg-gradient-to-t from-[#07183d]/50 via-transparent to-cyan-400/10 pointer-events-none z-10"></div>
                    </div>
                </div>
            </div>

            <!-- ========================================================
                 BOTTOM FAST MENU CONTAINER (Dark Glassmorphism Modern Card)
                 ======================================================== -->
            <div class="mobile-glass-card relative z-20 shrink-0 rounded-t-[2rem] pt-3 pb-4 px-4 sm:px-5 slide-up-anim">
                
                <!-- Header Label Modern -->
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-1 h-5 rounded-full bg-gradient-to-b from-blue-400 to-violet-500"></div>
                        <span class="text-[12px] font-black tracking-widest text-white uppercase">Layanan Cepat</span>
                    </div>
                    <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full" style="background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.12);">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400" style="animation:blink 2s ease infinite;"></span>
                        <span class="text-[9px] font-black text-emerald-400 tracking-widest uppercase">Online</span>
                    </div>
                </div>

                <!-- 5 Fast Menu Icons - Dark Glass Style -->
                <div class="grid grid-cols-5 gap-2 mb-3">
                    <!-- 1. KENDARAAN -->
                    <button type="button" @click="openMenu('kendaraan', 'Menu Kendaraan Dinas')"
                            class="flex flex-col items-center gap-1.5 fast-icon-desktop">
                        <div class="w-11 h-11 rounded-2xl icon-btn-glow-blue flex items-center justify-center"
                             style="background:linear-gradient(135deg,#2563eb,#1d4ed8);">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                            </svg>
                        </div>
                        <span class="text-[8.5px] font-black uppercase tracking-wide text-slate-300">Kendaraan</span>
                    </button>

                    <!-- 2. HUTANG -->
                    <button type="button" @click="openMenu('hutang', 'Pencatatan Bon / Hutang BBM')"
                            class="flex flex-col items-center gap-1.5 fast-icon-desktop">
                        <div class="w-11 h-11 rounded-2xl icon-btn-glow-amber flex items-center justify-center"
                             style="background:linear-gradient(135deg,#d97706,#b45309);">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8h4"/>
                            </svg>
                        </div>
                        <span class="text-[8.5px] font-black uppercase tracking-wide text-slate-300">Hutang</span>
                    </button>

                    <!-- 3. RIWAYAT -->
                    <button type="button" @click="openMenu('riwayat', 'Riwayat Distribusi BBM')"
                            class="flex flex-col items-center gap-1.5 fast-icon-desktop">
                        <div class="w-11 h-11 rounded-2xl icon-btn-glow-emerald flex items-center justify-center"
                             style="background:linear-gradient(135deg,#059669,#047857);">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-[8.5px] font-black uppercase tracking-wide text-slate-300">Riwayat</span>
                    </button>

                    <!-- 4. TRANSFER -->
                    <button type="button" @click="openMenu('transfer', 'Transfer Saldo Antar Kendaraan')"
                            class="flex flex-col items-center gap-1.5 fast-icon-desktop">
                        <div class="w-11 h-11 rounded-2xl icon-btn-glow-cyan flex items-center justify-center"
                             style="background:linear-gradient(135deg,#0891b2,#0e7490);">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <span class="text-[8.5px] font-black uppercase tracking-wide text-slate-300">Transfer</span>
                    </button>

                    <!-- 5. PENGAJUAN -->
                    <button type="button" @click="openMenu('pengajuan', 'Pengajuan Kendaraan Baru')"
                            class="flex flex-col items-center gap-1.5 fast-icon-desktop">
                        <div class="w-11 h-11 rounded-2xl icon-btn-glow-violet flex items-center justify-center"
                             style="background:linear-gradient(135deg,#7c3aed,#6d28d9);">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <span class="text-[8.5px] font-black uppercase tracking-wide text-slate-300">Pengajuan</span>
                    </button>
                </div>

                <!-- CTA Buttons Row -->
                <div class="flex items-center gap-2.5">
                    <!-- Tombol LOGIN - Gradient Animated -->
                    <button type="button" @click="openMenu('', 'Masuk Akun SIM-BBM')"
                            class="login-btn-mobile flex-1 py-3 px-4 rounded-2xl text-white font-black text-[12px] uppercase tracking-widest flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        <span>Login</span>
                    </button>

                    <!-- Tombol Cek Saldo -->
                    <a href="{{ route('cek-saldo.index') }}" title="Cek Saldo"
                       class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 active:scale-90 transition-all"
                       style="background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.15);">
                        <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- ========================================================
             2. DESKTOP VIEW (Ultra-Modern Premium Login Card)
             ======================================================== -->
        <!-- Animated Glow Orbs Background (behind card) -->
        <div class="hidden md:block pointer-events-none absolute inset-0 overflow-hidden z-0" aria-hidden="true">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-600/20 rounded-full blur-[100px] animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/3 w-80 h-80 bg-indigo-500/15 rounded-full blur-[90px] animate-pulse" style="animation-delay: 1s"></div>
            <div class="absolute top-1/3 right-1/4 w-64 h-64 bg-cyan-400/10 rounded-full blur-[80px] animate-pulse" style="animation-delay: 2s"></div>
        </div>

        <style>
            @keyframes shimmer {
                0% { background-position: -200% center; }
                100% { background-position: 200% center; }
            }
            @keyframes borderGlow {
                0%, 100% { box-shadow: 0 0 20px rgba(0, 98, 255, 0.3), 0 0 60px rgba(0, 98, 255, 0.1), inset 0 1px 0 rgba(255,255,255,0.15); }
                50% { box-shadow: 0 0 30px rgba(0, 120, 255, 0.45), 0 0 80px rgba(0, 98, 255, 0.2), inset 0 1px 0 rgba(255,255,255,0.2); }
            }
            @keyframes scanLine {
                0% { transform: translateY(-100%); opacity: 0; }
                10% { opacity: 1; }
                90% { opacity: 1; }
                100% { transform: translateY(100%); opacity: 0; }
            }
            @keyframes floatUp {
                0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.6; }
                50% { transform: translateY(-20px) rotate(180deg); opacity: 1; }
            }
            @keyframes gradientShift {
                0%, 100% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
            }
            .login-card-glow {
                animation: borderGlow 3s ease-in-out infinite;
            }
            .shimmer-text {
                background: linear-gradient(90deg, #ffffff, #93c5fd, #ffffff, #60a5fa, #ffffff);
                background-size: 200% auto;
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                animation: shimmer 4s linear infinite;
            }
            .gradient-border {
                position: relative;
            }
            .gradient-border::before {
                content: '';
                position: absolute;
                inset: 0;
                border-radius: inherit;
                padding: 1px;
                background: linear-gradient(135deg, rgba(255,255,255,0.4), rgba(99,179,237,0.3), rgba(255,255,255,0.1), rgba(139,92,246,0.2));
                -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                -webkit-mask-composite: xor;
                mask-composite: exclude;
                pointer-events: none;
            }
            .float-dot {
                animation: floatUp 4s ease-in-out infinite;
            }
            .scan-effect {
                position: relative;
                overflow: hidden;
            }
            .scan-effect::after {
                content: '';
                position: absolute;
                inset-x: 0;
                height: 2px;
                background: linear-gradient(90deg, transparent, rgba(99,179,237,0.6), transparent);
                animation: scanLine 4s linear infinite;
            }
            .card-hover-lift:hover {
                transform: translateY(-2px);
                transition: transform 0.3s ease;
            }
            .neon-input:focus {
                box-shadow: 0 0 0 2px rgba(0, 98, 255, 0.4), 0 0 15px rgba(0, 98, 255, 0.1);
                border-color: rgba(0, 98, 255, 0.6);
            }
            .btn-glow {
                position: relative;
                overflow: hidden;
            }
            .btn-glow::before {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.15) 50%, transparent 100%);
                transform: translateX(-100%);
                transition: transform 0.5s ease;
            }
            .btn-glow:hover::before {
                transform: translateX(100%);
            }
            .animated-bg {
                background: linear-gradient(-45deg, #0a1628, #0c1e40, #071530, #0d2050);
                background-size: 400% 400%;
                animation: gradientShift 8s ease infinite;
            }
            .menu-icon-hover {
                transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            }
            .menu-icon-hover:hover {
                transform: translateY(-4px) scale(1.08);
            }
        </style>

        <div class="hidden md:flex login-card-glow gradient-border bg-white/[0.07] backdrop-blur-[28px] rounded-[2rem] overflow-hidden flex-row w-full max-w-4xl mx-auto transition-all duration-500 relative z-10 md:min-h-[520px] lg:min-h-[560px]" style="background: rgba(255,255,255,0.06);">
            
            <!-- Left Side: Branding Premium dengan Animated Dark Panel -->
            <div class="w-1/2 animated-bg text-white relative items-center justify-between overflow-hidden flex flex-col py-6 px-6 lg:py-7 lg:px-7 text-center select-none border-r border-white/10 scan-effect">
                <!-- Layered Ambient Glow Effects -->
                <div class="absolute -top-20 left-1/2 -translate-x-1/2 w-96 h-56 bg-gradient-to-b from-blue-500/35 via-blue-700/15 to-transparent blur-[60px] pointer-events-none"></div>
                <div class="absolute top-1/4 -left-16 w-52 h-52 bg-cyan-400/20 rounded-full blur-[50px] pointer-events-none"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-indigo-600/12 rounded-full blur-[70px] pointer-events-none"></div>
                <div class="absolute -bottom-16 right-0 w-56 h-56 bg-violet-500/15 rounded-full blur-[55px] pointer-events-none"></div>
                <!-- Dot Grid Texture -->
                <div class="absolute inset-0 bg-[radial-gradient(rgba(99,179,237,0.08)_1px,transparent_1px)] [background-size:18px_18px] pointer-events-none"></div>
                <!-- Floating Decorative Dots -->
                <div class="absolute top-[15%] left-[8%] w-1.5 h-1.5 bg-cyan-400/60 rounded-full float-dot" style="animation-delay:0s"></div>
                <div class="absolute top-[30%] right-[12%] w-1 h-1 bg-blue-300/50 rounded-full float-dot" style="animation-delay:1.5s"></div>
                <div class="absolute bottom-[25%] left-[15%] w-2 h-2 bg-indigo-400/40 rounded-full float-dot" style="animation-delay:0.8s"></div>
                <div class="absolute bottom-[40%] right-[8%] w-1 h-1 bg-cyan-300/50 rounded-full float-dot" style="animation-delay:2.5s"></div>

                <!-- Top Badge: Status Portal Resmi - Premium Style -->
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gradient-to-r from-white/10 to-white/5 backdrop-blur-xl border border-cyan-400/30 hover:border-cyan-300/50 shadow-[0_0_20px_rgba(6,182,212,0.15),0_4px_16px_rgba(0,0,0,0.3)] text-[9.5px] font-black tracking-widest text-white transition-all duration-300 cursor-default">
                        <span class="relative flex items-center">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span class="absolute w-2 h-2 rounded-full bg-emerald-400 animate-ping opacity-75"></span>
                        </span>
                        <span class="tracking-[0.2em] uppercase text-cyan-100/95">PORTAL RESMI BIRO LOGISTIK</span>
                    </div>
                </div>

                <!-- Center Content: Premium Branding Showcase -->
                <div class="relative z-10 flex flex-col items-center max-w-sm my-auto py-1">
                    <!-- Stage Visual Container (Showcase Panggung Bersih & Mewah dengan Logo Default) -->
                    <div class="relative w-48 h-48 lg:w-52 lg:h-52 flex items-center justify-center my-2 transition-all duration-300">
                        <!-- Glowing Shadow Stage (Pendaran Mewah di Bawah Logo) -->
                        <div class="absolute -bottom-2 w-44 h-8 bg-gradient-to-r from-blue-600/50 via-cyan-400/40 to-blue-600/50 rounded-full blur-xl pointer-events-none"></div>
                        <div class="absolute inset-0 bg-gradient-to-tr from-blue-500/25 via-cyan-400/20 to-transparent rounded-full blur-2xl pointer-events-none"></div>

                        <!-- Modern Glass Ring Orb for Default Logo -->
                        <div class="relative z-10 w-36 h-36 lg:w-40 lg:h-40 rounded-3xl bg-white/[0.08] backdrop-blur-xl border border-white/25 shadow-[0_20px_50px_rgba(0,0,0,0.45),inset_0_1px_0_rgba(255,255,255,0.3)] flex items-center justify-center p-4 transform hover:scale-105 transition-all duration-500 group">
                            <!-- Subtle Glow Layer -->
                            <div class="absolute inset-0 rounded-3xl bg-gradient-to-br from-cyan-400/20 via-transparent to-blue-600/30 opacity-75 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                            
                            <!-- Default Official Logo -->
                            <img src="/rolog.png" onerror="this.onerror=null; this.src='/TRIBRATA.png';" alt="Logo Biro Logistik Polda NTB" class="relative z-10 max-w-full max-h-full object-contain drop-shadow-[0_12px_24px_rgba(0,0,0,0.6)] group-hover:scale-110 transition-transform duration-500">
                        </div>
                    </div>

                    <h2 class="text-xl lg:text-2xl font-black tracking-tight drop-shadow-lg mt-2 shimmer-text">
                        SIM-BBM POLDA NTB
                    </h2>
                    
                    <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-gradient-to-r from-blue-500/25 to-cyan-500/15 border border-cyan-400/30 text-cyan-200 text-[9.5px] font-extrabold uppercase tracking-widest mt-1 mb-1.5 shadow-[0_0_15px_rgba(6,182,212,0.25)]">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-300 shadow-[0_0_6px_#67e8f9]"></span>
                        <span>Sistem Informasi Manajemen BBM</span>
                    </div>

                    <p class="text-blue-100/75 text-[11px] lg:text-[11.5px] leading-relaxed max-w-xs font-normal mb-1">
                        Platform logistik terpadu untuk monitoring dan alokasi BBM kendaraan dinas secara akuntabel, transparan, dan presisi.
                    </p>
                </div>

                <!-- Bottom Feature Chips: 3 Premium Glass Badges -->
                <div class="relative z-10 grid grid-cols-3 gap-2 w-full max-w-xs pt-2">
                    <!-- EFISIEN -->
                    <div class="flex flex-col items-center justify-center py-3 px-2 rounded-2xl bg-gradient-to-b from-cyan-500/15 to-blue-600/10 border border-cyan-400/25 hover:border-cyan-300/50 hover:bg-cyan-500/20 backdrop-blur-sm transition-all duration-300 group card-hover-lift shadow-[0_4px_12px_rgba(6,182,212,0.1)] hover:shadow-[0_6px_20px_rgba(6,182,212,0.25)] cursor-default">
                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-cyan-400/30 to-blue-500/30 border border-cyan-300/40 flex items-center justify-center text-cyan-300 mb-1.5 group-hover:scale-110 group-hover:shadow-[0_0_12px_rgba(6,182,212,0.5)] transition-all duration-300">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <span class="text-[9px] text-cyan-100 font-extrabold tracking-widest uppercase">Efisien</span>
                    </div>

                    <!-- AMAN -->
                    <div class="flex flex-col items-center justify-center py-3 px-2 rounded-2xl bg-gradient-to-b from-emerald-500/15 to-teal-600/10 border border-emerald-400/25 hover:border-emerald-300/50 hover:bg-emerald-500/20 backdrop-blur-sm transition-all duration-300 group card-hover-lift shadow-[0_4px_12px_rgba(16,185,129,0.1)] hover:shadow-[0_6px_20px_rgba(16,185,129,0.25)] cursor-default">
                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-emerald-400/30 to-teal-500/30 border border-emerald-300/40 flex items-center justify-center text-emerald-300 mb-1.5 group-hover:scale-110 group-hover:shadow-[0_0_12px_rgba(16,185,129,0.5)] transition-all duration-300">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <span class="text-[9px] text-emerald-100 font-extrabold tracking-widest uppercase">Aman</span>
                    </div>

                    <!-- TERUKUR -->
                    <div class="flex flex-col items-center justify-center py-3 px-2 rounded-2xl bg-gradient-to-b from-amber-500/15 to-orange-600/10 border border-amber-400/25 hover:border-amber-300/50 hover:bg-amber-500/20 backdrop-blur-sm transition-all duration-300 group card-hover-lift shadow-[0_4px_12px_rgba(245,158,11,0.1)] hover:shadow-[0_6px_20px_rgba(245,158,11,0.25)] cursor-default">
                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-amber-400/30 to-orange-500/30 border border-amber-300/40 flex items-center justify-center text-amber-300 mb-1.5 group-hover:scale-110 group-hover:shadow-[0_0_12px_rgba(245,158,11,0.5)] transition-all duration-300">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <span class="text-[9px] text-amber-100 font-extrabold tracking-widest uppercase">Terukur</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Ultra-Modern Glass Action Panel -->
            <div class="w-1/2 py-7 px-6 lg:py-8 lg:px-7 relative backdrop-blur-2xl text-slate-800 flex flex-col justify-between" style="background: rgba(255,255,255,0.92);">
                
                <!-- Header Atas: Logo & Badge Premium -->
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-100/80">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <div class="absolute inset-0 bg-blue-500/20 rounded-full blur-md"></div>
                            <img src="/rolog.png" onerror="this.onerror=null; this.src='/TRIBRATA.png';" alt="Logo Biro Logistik Polda NTB" class="relative h-12 w-auto object-contain drop-shadow-lg hover:scale-110 transition-transform duration-300 shrink-0">
                        </div>
                        <div>
                            <h3 class="text-lg lg:text-[20px] font-black text-slate-900 leading-tight tracking-tight">Portal SIM-BBM</h3>
                            <p class="text-[10.5px] text-slate-500 font-semibold leading-tight mt-0.5">Biro Logistik Polda Nusa Tenggara Barat</p>
                        </div>
                    </div>

                    <!-- Badge SPBP NTB Modern -->
                    <div class="inline-flex items-center gap-1.5 bg-gradient-to-r from-red-50 via-white to-blue-50 border border-slate-200 px-2.5 py-1.5 rounded-full text-xs font-black shadow-sm hover:shadow-md transition-shadow">
                        <span class="w-3.5 h-3.5 rounded-full overflow-hidden flex flex-col shadow border border-slate-300 shrink-0">
                            <span class="bg-red-600 h-1/2 w-full"></span>
                            <span class="bg-white h-1/2 w-full"></span>
                        </span>
                        <span class="tracking-wider text-[9px] font-black uppercase text-blue-900">SPBP NTB</span>
                    </div>
                </div>

                <!-- Bagian Tengah: Fast Menu Modern & Navigasi -->
                <div class="my-auto py-3 space-y-3">
                    
                    <!-- Fast Menu Card: Ultra Modern Premium -->
                    <div class="rounded-2xl p-4 border border-slate-100 shadow-[0_4px_24px_rgba(0,60,180,0.06)]" style="background: linear-gradient(135deg, rgba(248,250,255,0.98) 0%, rgba(239,246,255,0.95) 100%);">
                        <div class="flex items-center justify-between mb-3 px-0.5">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-gradient-to-br from-blue-600 to-blue-700 text-white flex items-center justify-center shadow-[0_4px_10px_rgba(0,98,255,0.35)]">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                                <span class="text-[11px] font-black tracking-tight text-slate-900 uppercase">Fast Menu Layanan</span>
                            </div>
                            <span class="text-[8.5px] font-black text-blue-700 bg-blue-100 border border-blue-200 px-2.5 py-1 rounded-full uppercase tracking-widest shadow-sm">Akses Langsung</span>
                        </div>

                        <!-- 5 Fast Menu Buttons - Premium Icon Style -->
                        <div class="grid grid-cols-5 gap-1.5 text-center">
                            <!-- 1. KENDARAAN -->
                            <button type="button" @click="openMenu('kendaraan', 'Menu Kendaraan Dinas')"
                                    class="flex flex-col items-center group active:scale-90 transition-all menu-icon-hover">
                                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 text-white flex items-center justify-center shadow-[0_4px_12px_rgba(59,130,246,0.35)] group-hover:shadow-[0_6px_18px_rgba(59,130,246,0.5)] group-hover:from-blue-400 group-hover:to-blue-600 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                    </svg>
                                </div>
                                <span class="mt-1.5 text-[8.5px] font-black uppercase text-slate-700 tracking-tight leading-tight line-clamp-1 group-hover:text-blue-700 transition-colors">Kendaraan</span>
                            </button>

                            <!-- 2. HUTANG -->
                            <button type="button" @click="openMenu('hutang', 'Pencatatan Bon / Hutang BBM')"
                                    class="flex flex-col items-center group active:scale-90 transition-all menu-icon-hover">
                                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white flex items-center justify-center shadow-[0_4px_12px_rgba(245,158,11,0.35)] group-hover:shadow-[0_6px_18px_rgba(245,158,11,0.5)] group-hover:from-amber-400 group-hover:to-orange-500 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8h4" />
                                    </svg>
                                </div>
                                <span class="mt-1.5 text-[8.5px] font-black uppercase text-slate-700 tracking-tight leading-tight line-clamp-1 group-hover:text-amber-700 transition-colors">Hutang</span>
                            </button>

                            <!-- 3. RIWAYAT BBM -->
                            <button type="button" @click="openMenu('riwayat', 'Riwayat Distribusi BBM')"
                                    class="flex flex-col items-center group active:scale-90 transition-all menu-icon-hover">
                                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center shadow-[0_4px_12px_rgba(16,185,129,0.35)] group-hover:shadow-[0_6px_18px_rgba(16,185,129,0.5)] group-hover:from-emerald-400 group-hover:to-teal-500 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="mt-1.5 text-[8.5px] font-black uppercase text-slate-700 tracking-tight leading-tight line-clamp-1 group-hover:text-emerald-700 transition-colors">Riwayat</span>
                            </button>

                            <!-- 4. TRANSFER -->
                            <button type="button" @click="openMenu('transfer', 'Transfer Saldo Antar Kendaraan')"
                                    class="flex flex-col items-center group active:scale-90 transition-all menu-icon-hover">
                                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-cyan-500 to-sky-600 text-white flex items-center justify-center shadow-[0_4px_12px_rgba(6,182,212,0.35)] group-hover:shadow-[0_6px_18px_rgba(6,182,212,0.5)] group-hover:from-cyan-400 group-hover:to-sky-500 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </div>
                                <span class="mt-1.5 text-[8.5px] font-black uppercase text-slate-700 tracking-tight leading-tight line-clamp-1 group-hover:text-cyan-700 transition-colors">Transfer</span>
                            </button>

                            <!-- 5. PENGAJUAN -->
                            <button type="button" @click="openMenu('pengajuan', 'Pengajuan Kendaraan Baru')"
                                    class="flex flex-col items-center group active:scale-90 transition-all menu-icon-hover">
                                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-700 text-white flex items-center justify-center shadow-[0_4px_12px_rgba(139,92,246,0.35)] group-hover:shadow-[0_6px_18px_rgba(139,92,246,0.5)] group-hover:from-violet-400 group-hover:to-purple-600 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <span class="mt-1.5 text-[8.5px] font-black uppercase text-slate-700 tracking-tight leading-tight line-clamp-1 group-hover:text-violet-700 transition-colors">Pengajuan</span>
                            </button>
                        </div>

                        <!-- Progress Dots Indicator -->
                        <div class="flex items-center justify-center gap-1.5 mt-3">
                            <span class="w-7 h-1 rounded-full bg-gradient-to-r from-blue-600 to-blue-400 shadow-[0_0_6px_rgba(0,98,255,0.4)]"></span>
                            <span class="w-1.5 h-1 rounded-full bg-slate-200"></span>
                        </div>
                    </div>

                    <!-- Info Strip Modern -->
                    <div class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50/80 border border-blue-100 text-slate-600 text-[10.5px] leading-relaxed">
                        <div class="w-6 h-6 rounded-lg bg-blue-100 border border-blue-200 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-slate-600">Pilih menu layanan cepat di atas atau klik <strong class="text-blue-700">Login</strong> untuk masuk ke sistem.</span>
                    </div>
                </div>

                <!-- Bagian Bawah: Premium CTA Buttons -->
                <div class="space-y-2.5 pt-1">
                    <!-- Tombol Utama Login - Premium Gradient Glow -->
                    <button type="button" 
                            @click="openMenu('', 'Masuk Akun SIM-BBM')"
                            class="btn-glow group w-full py-3.5 px-5 rounded-2xl font-black text-[12.5px] uppercase tracking-widest text-white flex items-center justify-center gap-2.5 cursor-pointer active:scale-[0.98] transition-all duration-300"
                            style="background: linear-gradient(135deg, #0055ff 0%, #0062ff 40%, #3b82f6 100%); box-shadow: 0 10px 30px rgba(0,98,255,0.35), 0 4px 10px rgba(0,98,255,0.2), inset 0 1px 0 rgba(255,255,255,0.15);"
                            onmouseover="this.style.boxShadow='0 14px_40px rgba(0,98,255,0.5), 0 6px_15px rgba(0,98,255,0.3), inset 0 1px 0 rgba(255,255,255,0.2)'; this.style.transform='translateY(-1px)';"
                            onmouseout="this.style.boxShadow='0 10px 30px rgba(0,98,255,0.35), 0 4px 10px rgba(0,98,255,0.2), inset 0 1px 0 rgba(255,255,255,0.15)'; this.style.transform='';"
                            >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        <span>LOGIN KE SISTEM</span>
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1.5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>

                    <!-- Tombol Cek Saldo - Modern Outline Style -->
                    <a href="{{ route('cek-saldo.index') }}"
                       class="group w-full py-3 px-4 rounded-2xl transition-all duration-300 text-[11px] uppercase tracking-wider font-black flex items-center justify-center gap-2 active:scale-[0.99] border-2 border-slate-200 hover:border-blue-300 text-slate-600 hover:text-blue-700 bg-white hover:bg-blue-50/60 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-600 group-hover:scale-110 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                        <span>Cek Saldo Tanpa Login</span>
                    </a>

                    <!-- Security Badge Premium -->
                    <div class="flex items-center justify-center gap-2 pt-1">
                        <div class="flex items-center gap-1.5 text-[8.5px] font-bold text-slate-400 tracking-widest uppercase">
                            <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/>
                            </svg>
                            <span>SSL 256-Bit Encrypted</span>
                        </div>
                        <span class="text-slate-200 text-[10px]">|</span>
                        <span class="text-[8.5px] font-bold text-slate-400 tracking-widest uppercase">SIM-BBM Polda NTB</span>
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
            
            <!-- Backdrop Premium dengan Blur Kuat -->
            <div class="fixed inset-0 bg-slate-950/75 backdrop-blur-[6px]" @click="showLoginModal = false"></div>

            <!-- Modal Content Card - Ultra Premium -->
            <div class="relative z-10 w-full max-w-md rounded-t-[2rem] sm:rounded-[1.75rem] p-6 sm:p-7 shadow-[0_30px_80px_rgba(0,0,0,0.5)] max-h-[90vh] overflow-y-auto text-slate-800 border border-white/20 backdrop-blur-sm"
                 style="background: rgba(255,255,255,0.97);"
                 x-transition:enter="transition ease-out duration-350 transform"
                 x-transition:enter-start="translate-y-full sm:translate-y-6 sm:scale-90 opacity-0"
                 x-transition:enter-end="translate-y-0 sm:scale-100 opacity-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="translate-y-0 sm:scale-100 opacity-100"
                 x-transition:leave-end="translate-y-full sm:translate-y-6 sm:scale-90 opacity-0">
                
                <!-- Drag Handle Pill (Mobile Only) -->
                <div class="sm:hidden w-12 h-1 bg-slate-200 rounded-full mx-auto mb-5"></div>

                <!-- Header Modal Premium -->
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <div class="absolute inset-0 bg-blue-500/20 rounded-full blur-md"></div>
                            <img src="/rolog.png" onerror="this.onerror=null; this.src='/TRIBRATA.png';" alt="Logo Biro Logistik" class="relative h-11 w-auto object-contain drop-shadow-md shrink-0">
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-black text-slate-900 leading-tight tracking-tight">Masuk SIM-BBM</h3>
                            <p class="text-[11px] text-slate-500 font-medium mt-0.5" x-text="menuTitle ? 'Akses › ' + menuTitle : 'Silakan isi kredensial akun Anda'"></p>
                        </div>
                    </div>
                    <button type="button" @click="showLoginModal = false"
                            class="w-9 h-9 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 flex items-center justify-center transition-all duration-200 hover:scale-105 active:scale-95">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
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
                    
                    <!-- ID Pengguna / Email / NRP - Premium Input -->
                    <div>
                        <label for="login_modal_email" class="block text-[10.5px] font-black text-slate-600 uppercase tracking-widest mb-1.5">
                            {{ isset($personelAccessControl) && $personelAccessControl == '1' ? 'Email / NRP' : 'ID Pengguna / Email' }}
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input id="login_modal_email" type="text" name="email" value="{{ old('email') }}" required autofocus
                                class="neon-input w-full pl-12 pr-4 py-3 bg-slate-50/80 border-2 border-slate-200 rounded-2xl text-slate-900 text-sm outline-none focus:border-blue-400 focus:bg-white transition-all duration-200 placeholder-slate-400 font-medium"
                                placeholder="Masukkan NRP atau Email">
                        </div>
                        @error('email')
                            <p class="text-[10.5px] text-rose-500 font-bold mt-1.5 ml-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password - Premium Input -->
                    <div x-data="{ showPass: false }">
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="login_modal_password" class="block text-[10.5px] font-black text-slate-600 uppercase tracking-widest">
                                Password
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[11px] font-bold text-blue-600 hover:text-blue-700 hover:underline underline-offset-2 transition-colors">
                                    Lupa Password?
                                </a>
                            @endif
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="login_modal_password" :type="showPass ? 'text' : 'password'" name="password" required
                                class="neon-input w-full pl-12 pr-12 py-3 bg-slate-50/80 border-2 border-slate-200 rounded-2xl text-slate-900 text-sm outline-none focus:border-blue-400 focus:bg-white transition-all duration-200 placeholder-slate-400 font-medium"
                                placeholder="••••••••">
                            <button type="button" @click="showPass = !showPass"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-blue-500 transition-colors">
                                <svg x-show="!showPass" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPass" x-cloak class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-2.428 3.015" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-[10.5px] text-rose-500 font-bold mt-1.5 ml-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember Me + Lupa Password Row -->
                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center gap-2.5 cursor-pointer select-none group">
                            <div class="relative">
                                <input type="checkbox" name="remember" class="sr-only peer">
                                <div class="w-4 h-4 rounded-md border-2 border-slate-300 peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-all duration-200 flex items-center justify-center">
                                    <svg class="w-2.5 h-2.5 text-white hidden peer-checked:block" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <span class="text-[11.5px] text-slate-600 font-medium group-hover:text-slate-800 transition-colors">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <!-- Tombol Submit Masuk - Ultra Premium -->
                    <div class="pt-1">
                        <button type="submit" 
                            class="btn-glow w-full py-3.5 rounded-2xl text-white font-black text-[12.5px] uppercase tracking-widest flex items-center justify-center gap-2.5 active:scale-[0.99] transition-all duration-300 shadow-[0_10px_30px_rgba(0,98,255,0.35)]"
                            style="background: linear-gradient(135deg, #0055ff 0%, #0062ff 40%, #3b82f6 100%);">
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