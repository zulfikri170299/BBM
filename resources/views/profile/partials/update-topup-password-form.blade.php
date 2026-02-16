    <header class="flex items-center gap-4 mb-8">
        <div class="p-3 bg-red-100 text-red-600 rounded-2xl shadow-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        <div>
            @if(auth()->user()->topup_password)
                <h2 class="text-xl font-bold text-slate-900 tracking-tight"> Ubah Password Top Up </h2>
                <p class="text-sm text-slate-500 mt-0.5"> Masukkan password login Anda untuk keamanan, lalu buat password baru. </p>
            @else
                <h2 class="text-xl font-bold text-slate-900 tracking-tight"> Buat Password Top Up </h2>
                <p class="text-sm text-slate-500 mt-0.5"> Atur password khusus untuk otorisasi transaksi Top Up. (Cukup sekali) </p>
            @endif
        </div>
    </header>

    <form method="post" action="{{ route('profile.topup-password.update') }}" class="space-y-6 max-w-xl">
        @csrf
        @method('put')

        <!-- Login Password Validation -->
        <div>
            <label for="current_password_topup" class="block text-sm font-semibold text-slate-700 mb-2">Password Login (Verifikasi)</label>
            <div class="relative group">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </span>
                <input id="current_password_topup" name="password" type="password" required autocomplete="current-password"
                    class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all placeholder-slate-400"
                    placeholder="Masukkan password login akun ini">
            </div>
            <x-input-error :messages="$errors->updateTopupPassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="topup_password" class="block text-sm font-semibold text-slate-700 mb-2">
                {{ auth()->user()->topup_password ? 'Password Top Up Baru' : 'Buat Password Top Up' }}
            </label>
            <div class="relative group">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path></svg>
                </span>
                <input id="topup_password" name="topup_password" type="password" required autocomplete="new-password"
                    class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all placeholder-slate-400"
                    placeholder="Minimal 6 karakter">
            </div>
            <x-input-error :messages="$errors->updateTopupPassword->get('topup_password')" class="mt-2" />
        </div>

        <div>
            <label for="topup_password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password</label>
            <div class="relative group">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </span>
                <input id="topup_password_confirmation" name="topup_password_confirmation" type="password" required autocomplete="new-password"
                    class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all placeholder-slate-400"
                    placeholder="Ulangi password di atas">
            </div>
            <x-input-error :messages="$errors->updateTopupPassword->get('topup_password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-rose-500 to-rose-600 text-white font-bold rounded-xl shadow-lg shadow-rose-500/30 hover:shadow-xl hover:shadow-rose-500/40 transition-all transform hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                Simpan Password Top Up
            </button>

            @if (session('status') === 'topup-password-updated')
                <div 
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="flex items-center gap-2 text-emerald-600 font-semibold text-sm"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    Password Top Up berhasil diperbarui!
                </div>
            @endif
        </div>
    </form>
</section>
