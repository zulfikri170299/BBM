<x-guest-layout maxWidth="max-w-5xl">
    <div
        class="bg-slate-900/80 backdrop-blur-2xl border border-white/10 rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row w-full">

        <!-- Left Side: Branding & Visuals -->
        <div class="hidden md:flex md:w-1/2 bg-slate-800 relative items-center justify-center overflow-hidden">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 bg-[url('/polda.jpg')] bg-cover bg-center opacity-70"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-red-900/60 to-slate-900/60 mix-blend-multiply"></div>

            <!-- Content -->
            <div class="relative z-10 p-12 flex flex-col items-center text-center">
                <div class="mb-8 relative group">
                    <div
                        class="absolute inset-0 bg-amber-500/30 blur-2xl rounded-full group-hover:bg-amber-500/40 transition-all duration-500">
                    </div>
                    <img src="{{ asset('rolog.png') }}" alt="Logo"
                        class="w-32 h-32 object-contain relative drop-shadow-2xl transform group-hover:scale-105 transition-transform duration-500">
                </div>

                <h2 class="text-3xl font-bold text-white mb-2 tracking-tight">SIM-BBM</h2>
                <h3 class="text-sm font-semibold text-amber-500 mb-6 tracking-wider uppercase">(SISTEM INFORMASI MANAJEMEN BBM)</h3>

                <div class="w-16 h-1 bg-gradient-to-r from-red-500 to-amber-500 rounded-full mb-8"></div>

                <p class="text-slate-300 text-sm leading-relaxed max-w-xs font-light">
                    Platform digital terintegrasi untuk pengelolaan distribusi dan monitoring bahan bakar minyak kendaraan
                    dinas secara realtime dan transparan.
                </p>

                <div class="mt-12 flex gap-4">
                    <div class="flex flex-col items-center gap-1">
                        <div
                            class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center backdrop-blur-sm border border-white/10">
                            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Efisien</span>
                    </div>
                    <div class="flex flex-col items-center gap-1">
                        <div
                            class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center backdrop-blur-sm border border-white/10">
                            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Aman</span>
                    </div>
                    <div class="flex flex-col items-center gap-1">
                        <div
                            class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center backdrop-blur-sm border border-white/10">
                            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Terukur</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full md:w-1/2 p-8 md:p-12 relative">
            <div class="absolute top-0 right-0 p-4">
                <div
                    class="px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-500 text-[10px] font-bold tracking-wider uppercase">
                    v2.0 Enterprise
                </div>
            </div>

            <div class="mb-10 mt-4 text-center md:text-left">
                <div class="flex justify-center mb-6 md:hidden">
                    <img src="{{ asset('rolog.png') }}" alt="Logo" class="w-16 h-16 object-contain drop-shadow-lg">
                </div>
                <div class="md:hidden">
                    <h4 class="text-2xl font-bold text-white mb-1">SIM-BBM</h4>
                    <p class="text-amber-500 text-xs font-semibold tracking-wider uppercase mb-2">(SISTEM INFORMASI MANAJEMEN BBM)</p>
                </div>
                <div class="hidden md:block">
                    <h4 class="text-2xl font-bold text-white mb-2">Selamat Datang</h4>
                    <p class="text-slate-400 text-sm">Silakan masuk menggunakan akun kredensial Anda.</p>
                </div>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            @if(session('error'))
                <div
                    class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 flex items-start gap-3 backdrop-blur-sm">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    <div>
                        <p class="text-sm font-bold text-red-400">Akses Ditolak</p>
                        <p class="text-xs font-medium text-red-200/80 leading-relaxed mt-1">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email / NRP Address -->
                <div class="space-y-2">
                    <label for="email" class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Email /
                        NRP</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-500 group-focus-within:text-amber-500 transition-colors duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input id="email" type="text" name="email" value="{{ old('email') }}" required autofocus
                            autocomplete="username"
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-950/30 border border-slate-700/50 rounded-xl text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all duration-300 placeholder-slate-600 text-sm"
                            placeholder="Masukkan ID Pengguna">
                    </div>
                    @error('email')
                        <p class="text-xs text-red-400 font-medium ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label for="password"
                            class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Password</label>
                        @if (Route::has('password.request'))
                            <a class="text-[10px] font-medium text-amber-500 hover:text-amber-400 transition-colors hover:underline decoration-amber-500/50 underline-offset-4"
                                href="{{ route('password.request') }}">
                                Lupa Password?
                            </a>
                        @endif
                    </div>
                    <div class="relative group" x-data="{ show: false }">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-500 group-focus-within:text-amber-500 transition-colors duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" :type="show ? 'text' : 'password'" type="password" name="password" required
                            autocomplete="current-password"
                            class="w-full pl-11 pr-12 py-3.5 bg-slate-950/30 border border-slate-700/50 rounded-xl text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all duration-300 placeholder-slate-600 text-sm"
                            placeholder="••••••••">
                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-amber-500 transition-colors focus:outline-none">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path x-show="!show" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path x-show="!show" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                <path x-show="show" x-cloak stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-2.428 3.015" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-red-400 font-medium ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer select-none group">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-slate-600 bg-slate-800 text-amber-500 focus:ring-amber-500/50 focus:ring-offset-0 focus:ring-offset-transparent shadow-sm transition-colors cursor-pointer w-4 h-4"
                            name="remember">
                        <span
                            class="ml-2.5 text-xs text-slate-400 group-hover:text-amber-500 transition-colors font-medium">Ingat
                            saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                        class="w-full py-4 px-6 bg-gradient-to-r from-red-700 to-amber-700 hover:from-red-600 hover:to-amber-600 text-white font-bold rounded-xl shadow-lg shadow-red-900/30 transform hover:-translate-y-0.5 active:scale-[0.98] transition-all duration-200 text-sm uppercase tracking-widest border-t border-white/10 relative overflow-hidden group">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            LOGIN
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </span>
                    </button>

                    <!-- Public Check Balance Link -->
                    <div class="mt-6 flex flex-col gap-3">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-slate-700/50"></div>
                            </div>
                            <div class="relative flex justify-center text-xs uppercase">
                                <span class="bg-slate-900 border border-slate-700/50 px-3 py-1 rounded-full text-slate-500 font-bold tracking-widest">Atau</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('cek-saldo.index') }}"
                            class="w-full py-3 px-6 bg-slate-800/50 hover:bg-slate-700/50 text-amber-500 border border-amber-500/20 hover:border-amber-500/50 font-bold rounded-xl transition-all duration-200 text-xs uppercase tracking-widest flex items-center justify-center gap-2 group">
                            <svg class="w-4 h-4 text-amber-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Cek Saldo Tanpa Login
                        </a>

                        <button type="button" x-data @click="$dispatch('open-face-login')"
                            class="w-full py-3 px-6 bg-emerald-600/10 hover:bg-emerald-600/20 text-emerald-500 border border-emerald-500/30 hover:border-emerald-500/60 font-bold rounded-xl transition-all duration-200 text-xs uppercase tracking-widest flex items-center justify-center gap-2 group mt-2">
                            <svg class="w-4 h-4 text-emerald-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Login dengan Scan Wajah
                        </button>
                    </div>
                    <!-- Mobile only secure badge -->
                    <div class="md:hidden text-center mt-6">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-800/50 border border-slate-700/50">
                            <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-[10px] text-slate-400 font-medium">Koneksi Aman & Terenkripsi</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Face Login Modal -->
    <div x-data="faceLoginModal()"
        x-show="isOpen"
        @open-face-login.window="openModal()"
        style="display: none;"
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <!-- Backdrop -->
            <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/80 backdrop-blur-md transition-opacity" aria-hidden="true" @click="closeModal()"></div>

            <!-- Modal Card -->
            <div x-show="isOpen" x-transition:enter="ease-out duration-400" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90" 
                class="relative bg-gradient-to-b from-slate-800/95 to-slate-900/95 backdrop-blur-2xl rounded-3xl shadow-2xl w-full max-w-md border border-white/10 overflow-hidden">
                
                <!-- Glow ring effect -->
                <div class="absolute -inset-[1px] rounded-3xl bg-gradient-to-b from-emerald-500/20 via-transparent to-cyan-500/20 pointer-events-none"></div>
                
                <!-- Header -->
                <div class="relative px-6 pt-6 pb-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center shadow-lg shadow-emerald-500/25">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-white tracking-wide">Face ID Login</h3>
                                <p class="text-[11px] text-slate-400 mt-0.5">Verifikasi identitas biometrik</p>
                            </div>
                        </div>
                        <button @click="closeModal()" class="w-8 h-8 rounded-full bg-white/5 hover:bg-white/10 border border-white/10 flex items-center justify-center transition-all hover:rotate-90 duration-300">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    
                    <!-- Step Progress -->
                    <div class="flex items-center gap-2 mt-5">
                        <div class="flex-1 flex items-center gap-2">
                            <div :class="currentStep >= 1 ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'bg-slate-700 text-slate-500'" class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold transition-all duration-500">
                                <template x-if="currentStep > 1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></template>
                                <template x-if="currentStep <= 1"><span>1</span></template>
                            </div>
                            <span :class="currentStep >= 1 ? 'text-emerald-400' : 'text-slate-500'" class="text-[10px] font-semibold uppercase tracking-wider transition-colors">Memuat</span>
                        </div>
                        <div :class="currentStep >= 2 ? 'bg-emerald-500/50' : 'bg-slate-700'" class="h-[2px] w-8 rounded transition-colors duration-500"></div>
                        <div class="flex-1 flex items-center gap-2">
                            <div :class="currentStep >= 2 ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'bg-slate-700 text-slate-500'" class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold transition-all duration-500">
                                <template x-if="currentStep > 2"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></template>
                                <template x-if="currentStep <= 2"><span>2</span></template>
                            </div>
                            <span :class="currentStep >= 2 ? 'text-emerald-400' : 'text-slate-500'" class="text-[10px] font-semibold uppercase tracking-wider transition-colors">Scan</span>
                        </div>
                        <div :class="currentStep >= 3 ? 'bg-emerald-500/50' : 'bg-slate-700'" class="h-[2px] w-8 rounded transition-colors duration-500"></div>
                        <div class="flex-1 flex items-center gap-2">
                            <div :class="currentStep >= 3 ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'bg-slate-700 text-slate-500'" class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold transition-all duration-500">
                                <span>3</span>
                            </div>
                            <span :class="currentStep >= 3 ? 'text-emerald-400' : 'text-slate-500'" class="text-[10px] font-semibold uppercase tracking-wider transition-colors">Cocok</span>
                        </div>
                    </div>
                </div>
                
                <!-- Camera Area -->
                <div class="relative px-6 pb-4">
                    <div class="relative w-full aspect-square bg-black rounded-2xl overflow-hidden border border-white/5 shadow-inner">
                        <video id="video-login" autoplay muted playsinline class="w-full h-full object-cover hidden" x-ref="video"></video>
                        <canvas id="overlay-login" class="absolute inset-0 w-full h-full pointer-events-none" x-ref="canvas"></canvas>

                        <!-- Loading State -->
                        <div x-show="isLoadingModels" class="absolute inset-0 flex flex-col items-center justify-center bg-black z-10">
                            <div class="relative">
                                <div class="w-16 h-16 rounded-full border-2 border-emerald-500/20 flex items-center justify-center">
                                    <div class="w-14 h-14 rounded-full border-2 border-transparent border-t-emerald-500 animate-spin"></div>
                                </div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <span class="text-xs text-emerald-400/80 font-semibold mt-4 tracking-wider">Memuat Model AI...</span>
                            <div class="w-32 h-1 bg-slate-800 rounded-full mt-3 overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-emerald-500 to-cyan-500 rounded-full animate-pulse" style="width: 60%"></div>
                            </div>
                        </div>

                        <!-- Face Guide - Corner Brackets -->
                        <div x-show="isActive && !isProcessing" class="absolute inset-0 z-10 pointer-events-none flex items-center justify-center">
                            <div class="relative w-[65%] aspect-[3/4]">
                                <!-- Top-Left -->
                                <div class="absolute top-0 left-0 w-8 h-8 border-t-2 border-l-2 rounded-tl-xl" :class="isScanning ? 'border-emerald-400' : 'border-emerald-500'"></div>
                                <!-- Top-Right -->
                                <div class="absolute top-0 right-0 w-8 h-8 border-t-2 border-r-2 rounded-tr-xl" :class="isScanning ? 'border-emerald-400' : 'border-emerald-500'"></div>
                                <!-- Bottom-Left -->
                                <div class="absolute bottom-0 left-0 w-8 h-8 border-b-2 border-l-2 rounded-bl-xl" :class="isScanning ? 'border-emerald-400' : 'border-emerald-500'"></div>
                                <!-- Bottom-Right -->
                                <div class="absolute bottom-0 right-0 w-8 h-8 border-b-2 border-r-2 rounded-br-xl" :class="isScanning ? 'border-emerald-400' : 'border-emerald-500'"></div>
                                <!-- Center Crosshair -->
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-5 h-[1px] bg-emerald-500/30"></div>
                                </div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="h-5 w-[1px] bg-emerald-500/30"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Scanning Line -->
                        <style>
                            @keyframes face-scan-sweep { 0% { top: 15%; opacity: 0; } 10% { opacity: 1; } 50% { opacity: 0.8; } 90% { opacity: 1; } 100% { top: 85%; opacity: 0; } }
                            .face-scan-line { animation: face-scan-sweep 2.5s ease-in-out infinite; }
                            @keyframes face-glow-pulse { 0%, 100% { box-shadow: 0 0 20px rgba(16,185,129,0.3), inset 0 0 20px rgba(16,185,129,0.05); border-color: rgba(16,185,129,0.3); } 50% { box-shadow: 0 0 40px rgba(16,185,129,0.6), inset 0 0 40px rgba(16,185,129,0.1); border-color: rgba(16,185,129,0.6); } }
                            .face-glow-success { animation: face-glow-pulse 1s ease-in-out infinite; }
                        </style>
                        <div x-show="isScanning" class="absolute left-[17%] right-[17%] h-[2px] bg-gradient-to-r from-transparent via-emerald-400 to-transparent z-20 face-scan-line shadow-[0_0_15px_rgba(16,185,129,0.6)]"></div>

                        <!-- Success / Processing State -->
                        <div x-show="isProcessing" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="absolute inset-0 flex flex-col items-center justify-center bg-black/60 backdrop-blur-sm z-30">
                            <div class="w-20 h-20 rounded-full border-2 border-emerald-400 flex items-center justify-center face-glow-success mb-4">
                                <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span class="text-sm text-white font-bold tracking-widest uppercase">Wajah Dikenali</span>
                            <span class="text-[10px] text-emerald-400/70 mt-1 font-medium">Sedang mengautentikasi...</span>
                        </div>

                        <!-- Bottom label -->
                        <div x-show="isActive && !isProcessing" class="absolute bottom-3 left-0 right-0 z-20 flex justify-center pointer-events-none">
                            <div class="px-3 py-1.5 rounded-full bg-black/60 backdrop-blur-md border border-white/10">
                                <span class="text-[10px] text-emerald-400 font-semibold uppercase tracking-widest animate-pulse">Mendeteksi wajah...</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Error message -->
                    <div x-show="errorMsg" x-transition class="mt-3 p-3 rounded-xl bg-red-500/10 border border-red-500/20">
                        <p x-text="errorMsg" class="text-xs text-red-400 font-semibold text-center"></p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="relative px-6 py-4 border-t border-white/5 bg-slate-900/50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full" :class="isActive ? 'bg-emerald-500 shadow-lg shadow-emerald-500/50' : 'bg-slate-600'"></div>
                        <span class="text-[10px] font-medium" :class="isActive ? 'text-emerald-400' : 'text-slate-500'" x-text="isActive ? 'Kamera aktif' : 'Kamera nonaktif'"></span>
                    </div>
                    <button type="button" @click="closeModal()" class="px-5 py-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm font-semibold text-slate-300 hover:text-white transition-all duration-200">
                        Batal
                    </button>
                </div>
            </div>
        </div>

        <form id="face-login-form" method="POST" action="{{ route('login.face') }}" class="hidden">
            @csrf
            <input type="hidden" name="face_descriptor" id="face_descriptor_login">
        </form>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <script>
        function faceLoginModal() {
            return {
                isOpen: false,
                isLoadingModels: true,
                isActive: false,
                isScanning: false,
                isProcessing: false,
                faceDescriptor: '',
                errorMsg: '',
                stream: null,
                detectionInterval: null,
                currentStep: 0,

                async openModal() {
                    this.isOpen = true;
                    this.errorMsg = '';
                    this.isProcessing = false;
                    this.currentStep = 1;
                    
                    if (this.isLoadingModels) {
                        try {
                            const modelsUrl = '/models';
                            console.log('[FaceLogin] Loading models from:', modelsUrl);
                            await faceapi.nets.tinyFaceDetector.loadFromUri(modelsUrl);
                            console.log('[FaceLogin] tinyFaceDetector loaded');
                            await faceapi.nets.faceLandmark68TinyNet.loadFromUri(modelsUrl);
                            console.log('[FaceLogin] faceLandmark68TinyNet loaded');
                            await faceapi.nets.faceRecognitionNet.loadFromUri(modelsUrl);
                            console.log('[FaceLogin] faceRecognitionNet loaded - All models ready!');
                            this.isLoadingModels = false;
                            this.currentStep = 2;
                        } catch (e) {
                            console.error("[FaceLogin] Error loading models:", e);
                            this.errorMsg = "Gagal memuat sistem AI (" + e.message + "). Coba refresh halaman.";
                        }
                    } else {
                        this.currentStep = 2;
                    }
                    
                    this.startCamera();
                },

                async startCamera() {
                    if (this.isLoadingModels) return;
                    
                    try {
                        this.stream = await navigator.mediaDevices.getUserMedia({ 
                            video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } }, 
                            audio: false 
                        });
                        this.$refs.video.srcObject = this.stream;
                        this.$refs.video.classList.remove('hidden');
                        this.isActive = true;
                        this.isScanning = true;

                        this.$refs.video.onloadedmetadata = () => {
                            this.$refs.video.play();
                            
                            const canvas = this.$refs.canvas;
                            const displaySize = { width: this.$refs.video.videoWidth, height: this.$refs.video.videoHeight };
                            canvas.width = displaySize.width;
                            canvas.height = displaySize.height;
                            faceapi.matchDimensions(canvas, displaySize);

                            this.detectionInterval = setInterval(async () => {
                                if (!this.isActive || this.isProcessing) return;
                                if (this.$refs.video.videoWidth === 0) return;
                                
                                try {
                                    const options = new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.1 });
                                    
                                    // Stage 1: Fast face detection only
                                    const faceDetection = await faceapi.detectSingleFace(this.$refs.video, options);
                                    
                                    const context = canvas.getContext('2d');
                                    context.clearRect(0, 0, canvas.width, canvas.height);

                                    if (!faceDetection) return;

                                    // Draw detected face box
                                    const resized = faceapi.resizeResults(faceDetection, displaySize);
                                    const box = resized.box;
                                    context.strokeStyle = '#10b981';
                                    context.lineWidth = 3;
                                    context.strokeRect(box.x, box.y, box.width, box.height);
                                    
                                    console.log('[FaceLogin] Face detected, score:', faceDetection.score.toFixed(3), '- computing descriptor...');

                                    // Stage 2: Compute full descriptor
                                    const fullResult = await faceapi
                                        .detectSingleFace(this.$refs.video, options)
                                        .withFaceLandmarks(true)
                                        .withFaceDescriptor();

                                    if (fullResult && fullResult.descriptor) {
                                        const descriptorJson = JSON.stringify(Array.from(fullResult.descriptor));
                                        
                                        // Explicitly set hidden input value
                                        document.getElementById('face_descriptor_login').value = descriptorJson;
                                        
                                        this.isProcessing = true;
                                        this.isScanning = false;
                                        this.currentStep = 3;
                                        this.errorMsg = "";
                                        
                                        console.log('[FaceLogin] Descriptor ready! Submitting...');
                                        setTimeout(() => {
                                            document.getElementById('face-login-form').submit();
                                        }, 800);
                                    } else {
                                        console.log('[FaceLogin] Descriptor computation failed, retrying...');
                                    }
                                } catch (error) {
                                    console.error("[FaceLogin] Detection error:", error);
                                }
                            }, 500);
                        };
                    } catch (err) {
                        console.error(err);
                        this.errorMsg = "Kamera tidak dapat diakses. Pastikan izin kamera telah diberikan.";
                    }
                },

                closeModal() {
                    this.isOpen = false;
                    if (this.stream) {
                        this.stream.getTracks().forEach(track => track.stop());
                    }
                    if (this.detectionInterval) {
                        clearInterval(this.detectionInterval);
                    }
                    this.$refs.video.classList.add('hidden');
                    this.isActive = false;
                    this.isScanning = false;
                    this.currentStep = 0;
                }
            };
        }
    </script>
    @endpush
</x-guest-layout>