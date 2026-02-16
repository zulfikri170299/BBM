<x-app-layout>
    <div class="p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Edit Kendaraan</h1>
                <p class="mt-1 text-slate-500">Perbarui data kendaraan <strong>{{ $kendaraan->no_polisi }}</strong></p>
            </div>
            <a href="{{ route('admin.kendaraans.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-semibold text-sm hover:bg-slate-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        @if($errors->any())
            <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex-1">
                    @foreach($errors->all() as $error)
                        <p class="text-sm font-medium text-red-700">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-amber-500 to-orange-600">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/20 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Edit Data Kendaraan</h3>
                        <p class="text-sm text-amber-100">Kode: {{ $kendaraan->kode_kendaraan ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.kendaraans.update', $kendaraan) }}" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <!-- Kode Kendaraan (Read Only) -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Kode Kendaraan</label>
                    <input type="text" value="{{ $kendaraan->kode_kendaraan ?? '-' }}" disabled class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-mono font-bold text-indigo-600 cursor-not-allowed">
                </div>

                <!-- No Polisi -->
                <div>
                    <label for="no_polisi" class="block text-sm font-semibold text-slate-700 mb-2">Nomor Polisi <span class="text-red-500">*</span></label>
                    <input type="text" name="no_polisi" id="no_polisi" value="{{ old('no_polisi', $kendaraan->no_polisi) }}" required class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all">
                </div>

                <!-- Jenis Kendaraan -->
                <div>
                    <label for="jenis_kendaraan" class="block text-sm font-semibold text-slate-700 mb-2">Jenis Kendaraan <span class="text-red-500">*</span></label>
                    <input type="text" name="jenis_kendaraan" id="jenis_kendaraan" value="{{ old('jenis_kendaraan', $kendaraan->jenis_kendaraan) }}" required class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all">
                </div>

                <!-- Jenis BBM -->
                <div>
                    <label for="jenis_bbm" class="block text-sm font-semibold text-slate-700 mb-2">Jenis BBM <span class="text-red-500">*</span></label>
                    <select name="jenis_bbm" id="jenis_bbm" required class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all">
                        <option value="Pertamax" {{ old('jenis_bbm', $kendaraan->jenis_bbm) == 'Pertamax' ? 'selected' : '' }}>Pertamax</option>
                        <option value="Pertamina Dex" {{ old('jenis_bbm', $kendaraan->jenis_bbm) == 'Pertamina Dex' ? 'selected' : '' }}>Pertamina Dex</option>
                    </select>
                </div>

                <!-- PIN -->
                <div>
                    <label for="pin" class="block text-sm font-semibold text-slate-700 mb-2">PIN <span class="text-slate-400 font-normal">(Isi jika ingin mengubah)</span></label>
                    <input type="number" name="pin" id="pin" class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl text-sm font-mono text-slate-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all" min="0" maxlength="6" oninput="if(this.value.length > 6) this.value = this.value.slice(0, 6);" placeholder="Kosongkan jika tidak ingin mengubah PIN">
                </div>

                <!-- Submit -->
                <div class="flex gap-3 pt-2">
                    <a href="{{ route('admin.kendaraans.index') }}" class="flex-1 px-4 py-3 bg-slate-100 text-slate-600 font-semibold rounded-xl hover:bg-slate-200 transition-colors text-center">Batal</a>
                    <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold rounded-xl hover:from-amber-600 hover:to-orange-700 shadow-lg shadow-amber-500/30 transition-all hover:-translate-y-0.5">
                        ✏️ Update Kendaraan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
