@php
    $target = $kendaraan ?? $personel;
    $isKendaraan = isset($kendaraan);
@endphp

<x-app-layout>
    <div class="p-6 lg:p-8">
        <div class="max-w-xl mx-auto space-y-6">
            <!-- Header -->
            <div class="text-center">
                <h1 class="text-2xl font-bold text-slate-900">Proses Pengisian BBM</h1>
                <p class="text-sm text-slate-500 mt-1">Verifikasi data kendaraan dan masukkan jumlah pengisian</p>
            </div>

            <!-- Error -->
            @if(session('error'))
                <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
                    <div class="flex items-center gap-2 text-sm text-red-700">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Detail Target (Kendaraan/Personel) Card -->
            <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r {{ $isKendaraan ? 'from-indigo-500 to-indigo-600' : 'from-rose-500 to-rose-600' }}">
                    <h3 class="text-white font-bold text-sm flex items-center gap-2">
                        @if($isKendaraan)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1"></path></svg>
                            Detail Kendaraan
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Detail Personel
                        @endif
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        @if($isKendaraan)
                            <div>
                                <p class="text-xs text-slate-400 font-medium">No Polisi</p>
                                <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $kendaraan->no_polisi }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-medium">Satker</p>
                                <p class="text-sm font-semibold text-slate-700 mt-0.5">{{ $kendaraan->satker->nama_satker }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-medium">Jenis Kendaraan</p>
                                <p class="text-sm font-semibold text-slate-700 mt-0.5">{{ $kendaraan->jenis_kendaraan }}</p>
                            </div>
                        @else
                            <div>
                                <p class="text-xs text-slate-400 font-medium">NRP</p>
                                <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $personel->nrp }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-medium">Satker</p>
                                <p class="text-sm font-semibold text-slate-700 mt-0.5">{{ $personel->satker->nama_satker }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-xs text-slate-400 font-medium">Nama</p>
                                <p class="text-sm font-semibold text-slate-700 mt-0.5">{{ $personel->nama }}</p>
                            </div>
                        @endif

                        <div>
                            <p class="text-xs text-slate-400 font-medium">Jenis BBM</p>
                            @php
                                $bbmColors = [
                                    'Pertamax' => 'bg-blue-100 text-blue-700',
                                    'Pertamina Dex' => 'bg-rose-100 text-rose-700',
                                ];
                                $color = $bbmColors[$target->jenis_bbm] ?? 'bg-slate-100 text-slate-700';
                            @endphp
                            <span class="inline-flex items-center mt-1 px-2.5 py-1 rounded-full text-xs font-bold {{ $color }}">{{ $target->jenis_bbm }}</span>
                        </div>
                    </div>

                    <!-- Saldo -->
                    <div class="mt-5 p-4 {{ $isKendaraan ? 'bg-emerald-50 border-emerald-200' : 'bg-rose-50 border-rose-200' }} border rounded-xl">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium {{ $isKendaraan ? 'text-emerald-700' : 'text-rose-700' }}">Sisa Saldo</span>
                            <span class="text-xl font-bold {{ $isKendaraan ? 'text-emerald-600' : 'text-rose-600' }}">{{ number_format($target->saldo, 0, ',', '.') }} <span class="text-sm font-medium">Liter</span></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Pengisian -->
            <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Form Pengisian BBM
                    </h3>
                </div>
                <form action="{{ route('petugas.transaksi.process') }}" method="POST" class="p-6 space-y-5">
                    @csrf
                    @if($isKendaraan)
                        <input type="hidden" name="kendaraan_id" value="{{ $kendaraan->id }}">
                    @else
                        <input type="hidden" name="personel_id" value="{{ $personel->id }}">
                    @endif

                    <div>
                        <label for="liter" class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Liter</label>
                        <input type="number" name="liter" id="liter" step="0.1" min="0.1" max="{{ $target->saldo }}" value="{{ old('liter') }}" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-lg font-bold text-slate-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all" placeholder="0.0" required>
                        <p class="mt-1.5 text-xs text-slate-400">Maksimal: {{ number_format($target->saldo, 0, ',', '.') }} Liter</p>
                        @error('liter')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nama_driver" class="block text-sm font-semibold text-slate-700 mb-2">Nama Driver</label>
                        <input type="text" name="nama_driver" id="nama_driver" value="{{ old('nama_driver') }}" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all" placeholder="Masukkan nama driver secara manual..." required>
                        @error('nama_driver')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pin" class="block text-sm font-semibold text-slate-700 mb-2">PIN {{ $isKendaraan ? 'Kendaraan' : 'Personel' }}</label>
                        <input type="tel" name="pin" id="pin" maxlength="6" inputmode="numeric" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-lg font-mono tracking-[0.5em] text-center text-slate-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all bg-white" placeholder="000000" required>
                        @error('pin')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <a href="{{ route('petugas.transaksi.index') }}" class="flex-1 text-center px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-semibold text-sm hover:bg-slate-200 transition-colors">Batal</a>
                        <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 transition-all text-sm">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Proses Pengisian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
