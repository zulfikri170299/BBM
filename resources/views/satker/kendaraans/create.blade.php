<x-app-layout>
    <div class="p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('satker.kendaraans.index') }}" class="p-2 bg-slate-100 hover:bg-slate-200 rounded-xl text-slate-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Tambah Kendaraan</h1>
                <p class="mt-1 text-slate-500">Barcode & PIN akan di-generate secara otomatis.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="max-w-2xl">
            <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-8">
                <!-- Auto-generate Info -->
                <div class="mb-6 p-4 bg-indigo-50 border border-indigo-100 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-100 rounded-lg">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-indigo-800">Barcode & PIN Otomatis</p>
                            <p class="text-xs text-indigo-600">Barcode dan PIN 6 digit akan di-generate secara otomatis saat kendaraan dibuat.</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('satker.kendaraans.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- No Polisi -->
                    <div>
                        <label for="no_polisi" class="block text-sm font-semibold text-slate-700 mb-2">Nomor Polisi</label>
                        <input type="text" name="no_polisi" id="no_polisi" value="{{ old('no_polisi') }}"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all placeholder-slate-400 text-slate-800"
                            placeholder="contoh: B 1234 XYZ" required>
                        @error('no_polisi')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Kendaraan -->
                    <div>
                        <label for="jenis_kendaraan" class="block text-sm font-semibold text-slate-700 mb-2">Jenis Kendaraan</label>
                        <input type="text" name="jenis_kendaraan" id="jenis_kendaraan" value="{{ old('jenis_kendaraan') }}"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all placeholder-slate-400 text-slate-800"
                            placeholder="contoh: Toyota Fortuner, Isuzu Panther" required>
                        @error('jenis_kendaraan')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis BBM -->
                    <div>
                        <label for="jenis_bbm" class="block text-sm font-semibold text-slate-700 mb-2">Jenis BBM</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @foreach(['Pertamax', 'Pertamina Dex'] as $bbm)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="jenis_bbm" value="{{ $bbm }}" class="peer sr-only" {{ old('jenis_bbm', 'Pertamax') == $bbm ? 'checked' : '' }} required>
                                <div class="flex items-center justify-center px-4 py-3 border-2 border-slate-200 rounded-xl text-sm font-medium text-slate-600 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 hover:bg-slate-50 transition-all">
                                    {{ $bbm }}
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('jenis_bbm')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="flex items-center gap-3 pt-4">
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all duration-300 hover:-translate-y-0.5">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Kendaraan
                            </span>
                        </button>
                        <a href="{{ route('satker.kendaraans.index') }}" class="px-6 py-3 bg-slate-100 text-slate-600 font-semibold rounded-xl hover:bg-slate-200 transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
