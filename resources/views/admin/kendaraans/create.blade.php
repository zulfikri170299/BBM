<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white">Tambah Kendaraan</h1>
                <p class="mt-1 text-slate-400">Tambah kendaraan baru ke satuan kerja.</p>
            </div>
            <a href="{{ route('admin.kendaraans.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 text-slate-400 rounded-xl font-semibold text-sm hover:bg-slate-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex-shrink-0 p-1.5 bg-red-100 rounded-full mt-0.5">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="flex-1">
                    @foreach($errors->all() as $error)
                        <p class="text-sm font-medium text-red-700">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-slate-900 rounded-2xl border border-white/10 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-white/5 bg-gradient-to-r from-indigo-500 to-purple-600">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-slate-900 border border-white/5/20 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Data Kendaraan Baru</h3>
                        <p class="text-sm text-indigo-100">Lengkapi form berikut untuk menambah kendaraan</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.kendaraans.store') }}" method="POST" class="p-6 space-y-5">
                @csrf

                <!-- Pilih Satker -->
                <div>
                    <label for="satker_id" class="block text-sm font-semibold text-slate-300 mb-2">Satuan Kerja <span class="text-red-500">*</span></label>
                    <select name="satker_id" id="satker_id" required class="tom-select w-full">
                        <option value="">-- Pilih Satuan Kerja --</option>
                        @foreach($satkers as $satker)
                            <option value="{{ $satker->id }}" {{ old('satker_id') == $satker->id ? 'selected' : '' }}>{{ $satker->nama_satker }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- No Polisi -->
                <div>
                    <label for="no_polisi" class="block text-sm font-semibold text-slate-300 mb-2">Nomor Polisi <span class="text-red-500">*</span></label>
                    <input type="text" name="no_polisi" id="no_polisi" value="{{ old('no_polisi') }}" required placeholder="Contoh: AB 1234 CD" class="w-full px-4 py-3 bg-slate-900 border-2 border-white/10 rounded-xl text-xs font-medium text-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder:text-slate-300 placeholder:font-normal">
                </div>

                <!-- Jenis Kendaraan -->
                <div>
                    <label for="jenis_kendaraan" class="block text-sm font-semibold text-slate-300 mb-2">Jenis Kendaraan <span class="text-red-500">*</span></label>
                    <input type="text" name="jenis_kendaraan" id="jenis_kendaraan" value="{{ old('jenis_kendaraan') }}" required placeholder="Contoh: Mobil Dinas, Motor, Truk" class="w-full px-4 py-3 bg-slate-900 border-2 border-white/10 rounded-xl text-xs font-medium text-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder:text-slate-300 placeholder:font-normal">
                </div>

                <!-- Roda -->
                <div>
                    <label for="roda" class="block text-sm font-semibold text-slate-300 mb-2">Roda Kendaraan</label>
                    <select name="roda" id="roda" class="tom-select w-full">
                        <option value="">-- Pilih Jenis Roda --</option>
                        <option value="R2" {{ old('roda') == 'R2' ? 'selected' : '' }}>R2</option>
                        <option value="R4" {{ old('roda') == 'R4' ? 'selected' : '' }}>R4</option>
                        <option value="R6" {{ old('roda') == 'R6' ? 'selected' : '' }}>R6</option>
                        <option value="Non Kendaraan" {{ old('roda') == 'Non Kendaraan' ? 'selected' : '' }}>Non Kendaraan</option>
                    </select>
                </div>

                <!-- CC Kendaraan -->
                <div>
                    <label for="cc" class="block text-sm font-semibold text-slate-300 mb-2">CC Kendaraan</label>
                    <input type="text" name="cc" id="cc" value="{{ old('cc') }}" placeholder="Contoh: 1500, 150 CC" class="w-full px-4 py-3 bg-slate-900 border-2 border-white/10 rounded-xl text-xs font-medium text-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder:text-slate-300 placeholder:font-normal">
                </div>

                <!-- Jenis BBM -->
                <div>
                    <label for="jenis_bbm" class="block text-sm font-semibold text-slate-300 mb-2">Jenis BBM <span class="text-red-500">*</span></label>
                    <select name="jenis_bbm" id="jenis_bbm" required class="tom-select w-full">
                        <option value="">-- Pilih Jenis BBM --</option>
                        <option value="Pertamax" {{ old('jenis_bbm') == 'Pertamax' ? 'selected' : '' }}>Pertamax</option>
                        <option value="Pertamina Dex" {{ old('jenis_bbm') == 'Pertamina Dex' ? 'selected' : '' }}>Pertamina Dex</option>
                    </select>
                </div>

                <!-- Info -->
                <div class="flex items-start gap-3 p-4 bg-amber-50 rounded-xl border border-amber-200">
                    <div class="p-1.5 bg-amber-100 text-amber-600 rounded-lg mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="text-xs text-amber-700">
                        <p class="font-semibold mb-1">Informasi:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            <li><strong>Kode Kendaraan</strong>, <strong>Barcode</strong>, dan <strong>PIN</strong> akan dibuat otomatis oleh sistem</li>
                            <li>Saldo awal kendaraan adalah <strong>0 Liter</strong></li>
                            <li>Simpan PIN yang ditampilkan setelah kendaraan berhasil ditambahkan</li>
                        </ul>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex gap-3 pt-2">
                    <a href="{{ route('admin.kendaraans.index') }}" class="flex-1 px-4 py-3 bg-slate-800 text-slate-400 font-semibold rounded-xl hover:bg-slate-200 transition-colors text-center">Batal</a>
                    <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold rounded-xl hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/40 transition-all hover:-translate-y-0.5">
                        🚗 Simpan Kendaraan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
