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

                <h2 class="text-3xl font-bold text-white mb-2 tracking-tight">SISTEM INFORMASI</h2>
                <h3 class="text-xl font-semibold text-amber-500 mb-6 tracking-widest uppercase">Manajemen Bahan Bakar
                </h3>

                <div class="w-16 h-1 bg-gradient-to-r from-red-500 to-amber-500 rounded-full mb-8"></div>

                <p class="text-slate-300 text-sm leading-relaxed max-w-xs font-light">
                    Platform digital terintegrasi untuk pengelolaan distribusi dan monitoring bahan bakar kendaraan
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

            <div class="mb-10 mt-4">
                <h4 class="text-2xl font-bold text-white mb-2">Selamat Datang</h4>
                <p class="text-slate-400 text-sm">Silakan masuk menggunakan akun kredensial Anda.</p>
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
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-500 group-focus-within:text-amber-500 transition-colors duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-950/30 border border-slate-700/50 rounded-xl text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all duration-300 placeholder-slate-600 text-sm"
                            placeholder="••••••••">
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
                            Masuk ke Dashboard
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </span>
                    </button>
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
</x-guest-layout>