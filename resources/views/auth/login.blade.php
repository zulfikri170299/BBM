<x-guest-layout>
    <div
        class="bg-slate-900/40 backdrop-blur-xl border border-white/10 rounded-3xl p-8 lg:p-10 shadow-2xl relative overflow-hidden">
        <!-- Header: Logo & Title -->
        <div class="text-center mb-10">
            <div class="flex items-center justify-center gap-3 mb-2">
                <img src="{{ asset('rolog.png') }}" alt="Logo" class="w-12 h-12 object-contain drop-shadow-md">
                <h1 class="text-4xl font-black tracking-tighter text-[#F89D1C] flex items-center">
                    BIRO LOGISTIK
                </h1>
            </div>
            <p class="text-slate-400 text-sm font-medium tracking-wide">Polda NTB</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                <p class="text-sm font-medium text-red-200">{{ session('error') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email / NRP Address -->
            <div class="space-y-1">
                <div class="relative group">
                    <input id="email" type="text" name="email" value="{{ old('email') }}" required autofocus
                        autocomplete="username"
                        class="w-full px-5 py-4 bg-slate-800/50 border border-white/5 rounded-2xl text-white focus:outline-none focus:border-[#0EA5E9] focus:ring-1 focus:ring-[#0EA5E9] transition-all duration-300 placeholder-slate-500 text-sm"
                        placeholder="Email Address">
                </div>
                @error('email')
                    <p class="text-xs text-red-400 font-medium mt-1 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="space-y-1">
                <div class="relative group">
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full px-5 py-4 bg-slate-800/50 border border-white/5 rounded-2xl text-white focus:outline-none focus:border-[#0EA5E9] focus:ring-1 focus:ring-[#0EA5E9] transition-all duration-300 placeholder-slate-500 text-sm"
                        placeholder="Password">
                </div>
                @error('password')
                    <p class="text-xs text-red-400 font-medium mt-1 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between px-1">
                <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-white/10 bg-slate-800 text-[#0EA5E9] focus:ring-[#0EA5E9] shadow-sm"
                        name="remember">
                    <span class="ml-2 text-xs text-slate-300 font-medium tracking-tight">Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-xs font-medium text-slate-400 hover:text-white transition-colors underline decoration-slate-600 underline-offset-4"
                        href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit"
                    class="w-full py-4 px-6 bg-[#0EA5E9] hover:bg-[#0284C7] text-white font-bold rounded-2xl shadow-[0_10px_30px_-10px_rgba(14,165,233,0.5)] active:scale-[0.98] transition-all duration-200 text-sm uppercase tracking-wider">
                    Log in
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>