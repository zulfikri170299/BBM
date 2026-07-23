<section>
    <header class="flex items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
        <div class="p-2 sm:p-3 bg-red-100 text-red-600 rounded-2xl shadow-sm">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z">
                </path>
            </svg>
        </div>
        <div>
            @if(auth()->user()->topup_password)
                <h2 class="text-lg sm:text-xl font-bold text-white tracking-tight"> Ubah Password Top Up </h2>
                <p class="text-xs sm:text-sm text-slate-400 mt-0.5"> Masukkan password Top Up lama Anda, lalu buat
                    password baru. </p>
            @else
                <h2 class="text-lg sm:text-xl font-bold text-white tracking-tight"> Buat Password Top Up </h2>
                <p class="text-xs sm:text-sm text-slate-400 mt-0.5"> Atur password khusus untuk otorisasi transaksi Top Up.
                    (Cukup sekali) </p>
            @endif
        </div>
    </header>

    <form method="post" action="{{ route('profile.topup-password.update') }}" class="space-y-4 sm:space-y-6 max-w-xl">
        @csrf
        @method('put')

        <!-- Password Verification -->
        <div>
            <label for="current_password_topup"
                class="block text-xs sm:text-sm font-semibold text-slate-300 mb-1 sm:mb-2">
                @if(auth()->user()->topup_password)
                    Password Top Up Lama
                @else
                    Password Login (Verifikasi Pertama)
                @endif
            </label>
            <div class="relative group">
                <span
                    class="absolute inset-y-0 left-0 flex items-center pl-3 sm:pl-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </span>
                <input id="current_password_topup" name="password" type="password" required
                    autocomplete="current-password"
                    class="w-full pl-10 sm:pl-12 pr-4 py-2.5 sm:py-3 text-sm bg-slate-800/50 border border-white/10 rounded-xl text-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all placeholder-slate-400"
                    placeholder="{{ auth()->user()->topup_password ? 'Masukkan password Top Up lama' : 'Masukkan password login akun ini' }}">
            </div>
            <x-input-error :messages="$errors->updateTopupPassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="topup_password" class="block text-xs sm:text-sm font-semibold text-slate-300 mb-1 sm:mb-2">
                {{ auth()->user()->topup_password ? 'Password Top Up Baru' : 'Buat Password Top Up' }}
            </label>
            <div class="relative group">
                <span
                    class="absolute inset-y-0 left-0 flex items-center pl-3 sm:pl-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4">
                        </path>
                    </svg>
                </span>
                <input id="topup_password" name="topup_password" type="password" required autocomplete="new-password"
                    class="w-full pl-10 sm:pl-12 pr-4 py-2.5 sm:py-3 text-sm bg-slate-800/50 border border-white/10 rounded-xl text-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all placeholder-slate-400"
                    placeholder="Minimal 6 karakter">
            </div>
            <x-input-error :messages="$errors->updateTopupPassword->get('topup_password')" class="mt-2" />
        </div>

        <div>
            <label for="topup_password_confirmation"
                class="block text-xs sm:text-sm font-semibold text-slate-300 mb-1 sm:mb-2">Konfirmasi Password</label>
            <div class="relative group">
                <span
                    class="absolute inset-y-0 left-0 flex items-center pl-3 sm:pl-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                </span>
                <input id="topup_password_confirmation" name="topup_password_confirmation" type="password" required
                    autocomplete="new-password"
                    class="w-full pl-10 sm:pl-12 pr-4 py-2.5 sm:py-3 text-sm bg-slate-800/50 border border-white/10 rounded-xl text-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all placeholder-slate-400"
                    placeholder="Ulangi password di atas">
            </div>
            <x-input-error :messages="$errors->updateTopupPassword->get('topup_password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-2.5 bg-gradient-to-r from-rose-500 to-rose-600 text-white font-bold text-xs sm:text-sm rounded-xl shadow-lg shadow-rose-500/30 hover:shadow-xl hover:shadow-rose-500/40 transition-all transform hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                    </path>
                </svg>
                Simpan Password Top Up
            </button>

            @if (session('status') === 'topup-password-updated')
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                    class="flex items-center gap-2 text-emerald-600 font-semibold text-xs sm:text-sm">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Password Top Up berhasil diperbarui!
                </div>
            @endif
        </div>
    </form>
</section>

{{-- Reset Password Top Up Section --}}
<div class="mt-6 pt-6 border-t border-white/10 max-w-xl" x-data="{ showResetModal: false }">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div>
            <h3 class="text-sm font-bold text-slate-300">Reset Password Top Up</h3>
            <p class="text-xs text-slate-400 mt-0.5">Reset ke password default yang sudah ditentukan</p>
        </div>
        <button type="button" @click="showResetModal = true"
            class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-lg shadow-amber-500/30 hover:shadow-xl transition-all transform hover:-translate-y-0.5">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                </path>
            </svg>
            Reset ke Default
        </button>
    </div>

    @if (session('status') === 'topup-password-reset')
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
            class="mt-3 flex items-center gap-2 text-amber-600 font-semibold text-xs sm:text-sm bg-amber-50 px-4 py-2 rounded-xl border border-amber-200">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"></path>
            </svg>
            Password Top Up berhasil direset ke default!
        </div>
    @endif

    {{-- Custom Confirmation Modal --}}
    <div x-show="showResetModal" x-cloak class="fixed inset-0 z-[999] flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-slate-900/60" @click="showResetModal = false"></div>

        {{-- Modal Card --}}
        <div class="relative w-full max-w-sm bg-slate-900 border border-white/5 rounded-3xl shadow-2xl overflow-hidden" x-show="showResetModal"
            x-transition:enter="transition ease-out duration-300 delay-100"
            x-transition:enter-start="opacity-0 scale-90 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90">

            {{-- Top Gradient Bar --}}
            <div class="h-1.5 bg-gradient-to-r from-amber-400 via-orange-500 to-rose-500"></div>

            {{-- Content --}}
            <div class="p-6 sm:p-8 text-center">
                {{-- Icon --}}
                <div
                    class="mx-auto w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center mb-5 shadow-inner">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>

                {{-- Title --}}
                <h3 class="text-lg font-bold text-white mb-2">Reset Password Top Up?</h3>

                {{-- Description --}}
                <p class="text-sm text-slate-400 mb-6">Password Top Up Anda akan direset ke password default yang sudah
                    ditentukan. Apakah Anda yakin?</p>
            </div>

            {{-- Actions --}}
            <div class="flex border-t border-white/5">
                <button type="button" @click="showResetModal = false"
                    class="flex-1 py-4 text-sm font-bold text-slate-400 hover:bg-slate-800/50 hover:text-slate-300 transition-colors border-r border-white/5">
                    Batal
                </button>
                <form method="POST" action="{{ route('profile.topup-password.reset') }}" class="flex-1">
                    @csrf
                    <button type="submit"
                        class="w-full py-4 text-sm font-bold text-amber-600 hover:bg-amber-50 hover:text-amber-700 transition-colors">
                        Ya, Reset Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>