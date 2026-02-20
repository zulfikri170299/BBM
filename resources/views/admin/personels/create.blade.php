<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-slate-800">Tambah Personel</h2>
                        <a href="{{ route('admin.personels.index') }}"
                            class="text-sm text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>
                    </div>

                    <form action="{{ route('admin.personels.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Satuan Kerja</label>
                                <select name="satker_id"
                                    class="w-full rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                                    required>
                                    <option value="">Pilih Satker</option>
                                    @foreach($satkers as $satker)
                                        <option value="{{ $satker->id }}" {{ old('satker_id') == $satker->id ? 'selected' : '' }}>{{ $satker->nama_satker }}</option>
                                    @endforeach
                                </select>
                                @error('satker_id') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="nama" value="{{ old('nama') }}"
                                    class="w-full rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                                    placeholder="Masukkan nama..." required>
                                @error('nama') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">NRP/NIP</label>
                                <input type="text" name="nrp" value="{{ old('nrp') }}"
                                    class="w-full rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                                    placeholder="Masukkan NRP/NIP..." required>
                                @error('nrp') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Jenis BBM</label>
                                <select name="jenis_bbm"
                                    class="w-full rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                                    required>
                                    @foreach(['Pertalite', 'Pertamax', 'Solar', 'Pertamina Dex'] as $bbm)
                                        <option value="{{ $bbm }}" {{ old('jenis_bbm') == $bbm ? 'selected' : '' }}>{{ $bbm }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_bbm') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Saldo Awal (Liter)</label>
                                <input type="number" step="0.1" name="saldo" value="{{ old('saldo', 0) }}"
                                    class="w-full rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                                    required>
                                @error('saldo') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <button type="reset"
                                class="px-6 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-lg hover:bg-slate-200 transition-colors">Reset</button>
                            <button type="submit"
                                class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">Simpan
                                Personel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>