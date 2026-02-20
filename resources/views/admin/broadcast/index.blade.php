<x-app-layout>
    <div class="p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Pesan Siaran (Broadcast)</h1>
                <p class="mt-1 text-slate-500">Kirimkan notifikasi penting ke seluruh pengguna aplikasi.</p>
            </div>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl relative flex items-center gap-3"
                role="alert">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"></path>
                </svg>
                <span class="block sm:inline font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="max-w-3xl">
            <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                            </path>
                        </svg>
                        Buat Pesan Baru
                    </h3>
                </div>

                <form action="{{ route('admin.broadcast.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Judul Informasi</label>
                        <input type="text" name="title" required
                            placeholder="Contoh: Pemeliharaan Sistem / Update Harga BBM"
                            class="w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm">
                        @error('title') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Isi Pesan</label>
                        <textarea name="message" rows="5" required
                            placeholder="Tuliskan informasi detail yang ingin disampaikan..."
                            class="w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm"></textarea>
                        @error('message') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        <p class="mt-2 text-xs text-slate-400">Pesan ini akan muncul di menu notifikasi (lonceng) pada
                            semua akun pengguna.</p>
                    </div>



                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all active:scale-95">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Kirim ke Semua Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-8 bg-amber-50 border border-amber-200 p-4 rounded-xl flex gap-3">
            <svg class="w-6 h-6 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-sm text-amber-800">
                <p class="font-bold">Informasi Penting:</p>
                <p class="mt-1">Pesan siaran yang dikirim akan langsung tersimpan di database dan muncul sebagai
                    notifikasi baru bagi setiap pengguna. Gunakan fitur ini secara bijak untuk informasi yang bersifat
                    darurat atau instruksi resmi.</p>
            </div>
        </div>
    </div>
</x-app-layout>