<section>
    <header class="flex items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
        <div class="p-2 sm:p-3 bg-amber-100 text-amber-600 rounded-2xl shadow-sm">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z">
                </path>
            </svg>
        </div>
        <div>
            <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight"> Keamanan Akun </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5"> Perbarui kata sandi Anda untuk menjaga keamanan akun.
            </p>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4 sm:space-y-6 max-w-xl">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password"
                class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1 sm:mb-2">Password Saat Ini</label>
            <div class="relative group">
                <span
                    class="absolute inset-y-0 left-0 flex items-center pl-3 sm:pl-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                        </path>
                    </svg>
                </span>
                <input id="update_password_current_password" name="current_password" type="password" required
                    autocomplete="current-password"
                    class="w-full pl-10 sm:pl-12 pr-4 py-2.5 sm:py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all placeholder-slate-400"
                    placeholder="Masukkan password saat ini">
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="h-px bg-slate-100"></div>

        <div>
            <label for="update_password_password"
                class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1 sm:mb-2">Password Baru</label>
            <div class="relative group">
                <span
                    class="absolute inset-y-0 left-0 flex items-center pl-3 sm:pl-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4">
                        </path>
                    </svg>
                </span>
                <input id="update_password_password" name="password" type="password" required
                    autocomplete="new-password"
                    class="w-full pl-10 sm:pl-12 pr-4 py-2.5 sm:py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all placeholder-slate-400"
                    placeholder="Buat password baru">
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password_confirmation"
                class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1 sm:mb-2">Konfirmasi Password
                Baru</label>
            <div class="relative group">
                <span
                    class="absolute inset-y-0 left-0 flex items-center pl-3 sm:pl-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                </span>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" required
                    autocomplete="new-password"
                    class="w-full pl-10 sm:pl-12 pr-4 py-2.5 sm:py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all placeholder-slate-400"
                    placeholder="Ulangi password baru">
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold text-xs sm:text-sm rounded-xl shadow-lg shadow-amber-500/30 hover:shadow-xl hover:shadow-amber-500/40 transition-all transform hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                    </path>
                </svg>
                Simpan Password
            </button>

            @if (session('status') === 'password-updated')
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                    class="flex items-center gap-2 text-emerald-600 font-semibold text-xs sm:text-sm">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Password berhasil diperbarui!
                </div>
            @endif
        </div>
    </form>
</section>