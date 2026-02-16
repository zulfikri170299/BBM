<section class="space-y-6">
    <header class="flex items-center gap-4 mb-4">
        <div class="p-3 bg-rose-100 text-rose-600 rounded-2xl shadow-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight"> Hapus Akun </h2>
            <p class="text-sm text-slate-500 mt-0.5"> Menghapus akun Anda akan menghapus semua data secara permanen. </p>
        </div>
    </header>

    <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl">
        <p class="text-sm text-rose-700 leading-relaxed mb-4 font-medium italic">
            "Setelah akun Anda dihapus, semua data akan hilang selamanya. Harap berhati-hati sebelum mengambil keputusan ini."
        </p>
        <button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex items-center px-4 py-2 bg-rose-600 text-white text-xs font-bold rounded-lg hover:bg-rose-700 transition"
        >
            Hapus Akun Sekarang
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
            @csrf
            @method('delete')

            <div class="text-center">
                <div class="w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 mb-2 font-display">Apakah Anda yakin?</h2>
                <p class="text-slate-500 text-sm max-w-sm mx-auto mb-8">
                    Data Anda akan dihapus permanen. Silakan masukkan password Anda untuk mengkonfirmasi tindakan ini.
                </p>
            </div>

            <div class="space-y-4 max-w-sm mx-auto">
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-rose-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </span>
                    <input id="password" name="password" type="password" required
                        class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all placeholder-slate-400"
                        placeholder="Masukkan password konfirmasi">
                </div>
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-10 flex flex-col sm:flex-row gap-3 justify-center">
                <button type="button" x-on:click="$dispatch('close')" 
                    class="px-6 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition">
                    Batal
                </button>
                <button type="submit" 
                    class="px-6 py-2.5 bg-rose-600 text-white font-bold rounded-xl shadow-lg shadow-rose-500/30 hover:shadow-xl hover:shadow-rose-500/40 transition-all">
                    Ya, Hapus Akun Saya
                </button>
            </div>
        </form>
    </x-modal>
</section>
