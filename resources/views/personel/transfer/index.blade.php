<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
            {{ __('Transfer Saldo') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-8">

            <!-- Saldo Card with Gradient -->
            <div
                class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-700 p-5 sm:p-8 shadow-xl text-white">
                <div
                    class="relative z-10 flex flex-col md:flex-row justify-between items-center sm:items-start md:items-center gap-4">
                    <div class="text-center sm:text-left">
                        <p class="text-indigo-100 font-medium text-sm sm:text-lg">Saldo Anda Saat Ini</p>
                        <h3 class="text-3xl sm:text-5xl font-bold mt-1 sm:mt-2">
                            {{ number_format($personel->saldo, 0, ',', '.') }} Liter
                        </h3>
                        <p
                            class="mt-2 text-indigo-200 text-xs sm:text-sm flex items-center justify-center sm:justify-start">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Jenis BBM: <span class="font-semibold ml-1">{{ $personel->jenis_bbm }}</span>
                        </p>
                    </div>
                    <div
                        class="p-2 sm:p-3 bg-white/10 rounded-full backdrop-blur-sm border border-white/20 hidden sm:block">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                    </div>
                </div>
                <!-- Decorative Circles -->
                <div
                    class="absolute top-0 right-0 -mt-10 -mr-10 w-32 h-32 sm:w-40 sm:h-40 bg-white/10 rounded-full blur-3xl">
                </div>
                <div
                    class="absolute bottom-0 left-0 -mb-10 -ml-10 w-32 h-32 sm:w-40 sm:h-40 bg-indigo-500/30 rounded-full blur-3xl">
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-8">
                <!-- Form Transfer -->
                <div class="lg:col-span-1">
                    <div
                        class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden sticky top-4 sm:top-8">
                        <div class="p-4 sm:p-6 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-base sm:text-lg font-bold text-slate-800 flex items-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-indigo-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                                Form Transfer
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1">Kirim saldo ke rekan satu Satker.</p>
                        </div>

                        <div class="p-4 sm:p-6" x-data="{
                            tipeTujuan: '{{ old('tipe_tujuan', 'personel') }}',
                            jumlah: {{ old('jumlah', 0) }},
                            userSaldo: {{ $personel->saldo }},
                            receiverSearch: '',
                            receiverOpen: false,
                            receiverSelected: '{{ old('receiver_id', '') }}',
                            receiverLabel: '{{ $personels->where('id', old('receiver_id'))->first()->nama ?? '' }}',
                            receivers: [
                                @foreach($personels as $p)
                                    { id: {{ $p->id }}, nama: '{{ addslashes($p->nama) }}', nrp: '{{ addslashes($p->nrp) }}', bbm: '{{ $p->jenis_bbm }}' },
                                @endforeach
                            ],
                            // Searchable Kendaraan
                            kendaraanSearch: '',
                            kendaraanOpen: false,
                            kendaraanSelected: '{{ old('target_kendaraan_id', '') }}',
                            kendaraanLabel: '{{ $availableKendaraans->where('id', old('target_kendaraan_id'))->first()->no_polisi ?? '' }}',
                            kendaraans: [
                                @foreach($availableKendaraans as $k)
                                    { id: {{ $k->id }}, no_polisi: '{{ $k->no_polisi }}', jenis_bbm: '{{ $k->jenis_bbm }}' },
                                @endforeach
                            ],
                            get filteredReceivers() {
                                if (!this.receiverSearch) return this.receivers;
                                const q = this.receiverSearch.toLowerCase();
                                return this.receivers.filter(r => r.nama.toLowerCase().includes(q) || r.nrp.toLowerCase().includes(q));
                            },
                            get filteredKendaraans() {
                                if (!this.kendaraanSearch) return this.kendaraans;
                                const q = this.kendaraanSearch.toLowerCase();
                                return this.kendaraans.filter(k => k.no_polisi.toLowerCase().includes(q));
                            },
                            selectReceiver(r) {
                                this.receiverSelected = r.id;
                                this.receiverLabel = r.nama + ' (' + r.nrp + ')';
                                this.receiverOpen = false;
                                this.receiverSearch = '';
                            },
                            selectKendaraan(k) {
                                this.kendaraanSelected = k.id;
                                this.kendaraanLabel = k.no_polisi;
                                this.kendaraanOpen = false;
                                this.kendaraanSearch = '';
                            },
                            get isInvalid() {
                                return this.jumlah > this.userSaldo || this.jumlah <= 0 || (!this.receiverSelected && this.tipeTujuan === 'personel') || (!this.kendaraanSelected && this.tipeTujuan === 'kendaraan');
                            }
                        }">
                            <form method="post" action="{{ route('personel.transfer.store') }}"
                                class="space-y-4 sm:space-y-5">
                                @csrf

                                <!-- Tipe Tujuan Selector -->
                                <div class="p-1 bg-slate-100 rounded-xl flex mb-4">
                                    <button type="button" @click="tipeTujuan = 'personel'"
                                        class="flex-1 px-4 py-2 text-xs font-bold rounded-lg transition-all"
                                        :class="tipeTujuan === 'personel' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'">
                                        Ke Rekan
                                    </button>
                                    <button type="button" @click="tipeTujuan = 'kendaraan'"
                                        class="flex-1 px-4 py-2 text-xs font-bold rounded-lg transition-all"
                                        :class="tipeTujuan === 'kendaraan' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'">
                                        Ke Kendaraan
                                    </button>
                                </div>

                                <input type="hidden" name="tipe_tujuan" :value="tipeTujuan">

                                <div x-show="tipeTujuan === 'personel'">
                                    <x-input-label for="receiver_id" :value="__('Penerima')"
                                        class="text-xs sm:text-sm text-slate-700" />
                                    <input type="hidden" name="receiver_id" :value="receiverSelected" :required="tipeTujuan === 'personel'">
                                    <div class="relative mt-1" @click.outside="receiverOpen = false">
                                        <div @click="receiverOpen = !receiverOpen; $nextTick(() => { if(receiverOpen) $refs.receiverInput.focus() })"
                                            class="w-full pl-9 sm:pl-10 pr-4 py-2 sm:py-2.5 rounded-lg sm:rounded-xl border border-slate-300 text-xs sm:text-sm shadow-sm transition-all duration-200 cursor-pointer flex items-center justify-between bg-white"
                                            :class="receiverOpen ? 'ring-2 ring-indigo-500 border-indigo-500' : ''">
                                            <span x-text="receiverLabel || '-- Pilih Rekan --'"
                                                :class="receiverLabel ? 'text-slate-800' : 'text-slate-400'"
                                                class="truncate"></span>
                                            <svg class="w-4 h-4 text-slate-400 transition-transform shrink-0"
                                                :class="receiverOpen ? 'rotate-180' : ''" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400"
                                            x-show="!receiverOpen">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div x-show="receiverOpen" x-transition.opacity.duration.150ms
                                            class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden"
                                            style="display:none;">
                                            <div class="p-2 border-b border-slate-100">
                                                <div class="relative">
                                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                    </svg>
                                                    <input x-ref="receiverInput" x-model="receiverSearch" type="text"
                                                        placeholder="Cari nama / NRP..."
                                                        class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                </div>
                                            </div>
                                            <div class="max-h-48 overflow-y-auto">
                                                <template x-for="r in filteredReceivers" :key="r.id">
                                                    <div @click="selectReceiver(r)"
                                                        class="px-4 py-2.5 text-xs sm:text-sm text-slate-700 hover:bg-indigo-50 cursor-pointer flex items-center justify-between transition-colors"
                                                        :class="receiverSelected === r.id ? 'bg-indigo-50 text-indigo-700 font-semibold' : ''">
                                                        <span
                                                            x-text="r.nama + ' (' + r.nrp + ')' + (r.bbm ? ' - ' + r.bbm : ' - Belum set BBM')"></span>
                                                        <svg x-show="receiverSelected === r.id"
                                                            class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                </template>
                                                <div x-show="filteredReceivers.length === 0"
                                                    class="px-4 py-3 text-sm text-slate-400 text-center">Tidak ditemukan
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-1.5 text-[10px] sm:text-xs text-slate-500 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Menampilkan rekan dengan BBM {{ $personel->jenis_bbm }} atau yang belum diatur.
                                    </p>
                                    <x-input-error :messages="$errors->get('receiver_id')" class="mt-2" />
                                </div>

                                <div x-show="tipeTujuan === 'kendaraan'" style="display:none;">
                                    <x-input-label for="target_kendaraan_id" :value="__('Kendaraan Tujuan')"
                                        class="text-xs sm:text-sm text-slate-700" />
                                    <input type="hidden" name="target_kendaraan_id" :value="kendaraanSelected" :required="tipeTujuan === 'kendaraan'">
                                    <div class="relative mt-1" @click.outside="kendaraanOpen = false">
                                        <div @click="kendaraanOpen = !kendaraanOpen; $nextTick(() => { if(kendaraanOpen) $refs.kendaraanInput.focus() })"
                                            class="w-full pl-9 sm:pl-10 pr-4 py-2 sm:py-2.5 rounded-lg sm:rounded-xl border border-slate-300 text-xs sm:text-sm shadow-sm transition-all duration-200 cursor-pointer flex items-center justify-between bg-white"
                                            :class="kendaraanOpen ? 'ring-2 ring-indigo-500 border-indigo-500' : ''">
                                            <span x-text="kendaraanLabel || '-- Pilih Kendaraan --'"
                                                :class="kendaraanLabel ? 'text-slate-800' : 'text-slate-400'"
                                                class="truncate"></span>
                                            <svg class="w-4 h-4 text-slate-400 transition-transform shrink-0"
                                                :class="kendaraanOpen ? 'rotate-180' : ''" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400"
                                            x-show="!kendaraanOpen">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1"></path>
                                            </svg>
                                        </div>
                                        <div x-show="kendaraanOpen" x-transition.opacity.duration.150ms
                                            class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden"
                                            style="display:none;">
                                            <div class="p-2 border-b border-slate-100">
                                                <div class="relative">
                                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                    </svg>
                                                    <input x-ref="kendaraanInput" x-model="kendaraanSearch" type="text"
                                                        placeholder="Cari nopol kendaraan..."
                                                        class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                </div>
                                            </div>
                                            <div class="max-h-48 overflow-y-auto">
                                                <template x-for="k in filteredKendaraans" :key="k.id">
                                                    <div @click="selectKendaraan(k)"
                                                        class="px-4 py-2.5 text-xs sm:text-sm text-slate-700 hover:bg-indigo-50 cursor-pointer flex items-center justify-between transition-colors"
                                                        :class="kendaraanSelected === k.id ? 'bg-indigo-50 text-indigo-700 font-semibold' : ''">
                                                        <span x-text="k.no_polisi + ' - ' + k.jenis_bbm"></span>
                                                        <svg x-show="kendaraanSelected === k.id"
                                                            class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                </template>
                                                <div x-show="filteredKendaraans.length === 0"
                                                    class="px-4 py-3 text-sm text-slate-400 text-center">Tidak ditemukan atau BBM tidak cocok
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-1.5 text-[10px] sm:text-xs text-slate-500 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Hanya menampilkan kendaraan dengan BBM {{ $personel->jenis_bbm }}.
                                    </p>
                                    <x-input-error :messages="$errors->get('target_kendaraan_id')" class="mt-2" />
                                </div>

                                <x-input-error :messages="$errors->get('tipe_tujuan')" class="mt-2" />

                                <div>
                                    <x-input-label for="jumlah" :value="__('Jumlah Transfer')"
                                        class="text-xs sm:text-sm text-slate-700 font-medium" />
                                    <div class="relative mt-1 sm:mt-2">
                                        <input id="jumlah" name="jumlah" type="number" x-model.number="jumlah"
                                            class="w-full pl-4 pr-4 py-2.5 sm:py-3 rounded-lg sm:rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-slate-800 font-bold text-base sm:text-lg shadow-sm transition-all duration-200"
                                            :class="jumlah > userSaldo ? 'border-rose-500 ring-rose-500/10' : ''"
                                            required min="1" placeholder="0"
                                            autocomplete="off">
                                    </div>
                                    <div x-show="jumlah > userSaldo" x-transition.opacity
                                        class="mt-2 text-xs text-rose-600 flex items-center bg-rose-50 p-2 rounded-lg border border-rose-100">
                                        <svg class="w-4 h-4 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Saldo tidak mencukupi (Maks: <span x-text="userSaldo"></span> L)
                                    </div>
                                    <x-input-error :messages="$errors->get('jumlah')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="pin" :value="__('PIN Keamanan')"
                                        class="text-xs sm:text-sm text-slate-700 font-medium" />
                                    <div class="relative mt-1 sm:mt-2">
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 sm:pl-4 pointer-events-none text-slate-400">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                </path>
                                            </svg>
                                        </div>
                                        <input id="pin" name="pin" type="password"
                                            class="w-full pl-10 sm:pl-12 pr-4 py-2.5 sm:py-3 rounded-lg sm:rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-slate-800 font-bold text-base sm:text-lg shadow-sm transition-all duration-200 tracking-[0.5em]"
                                            required autocomplete="new-password" value="" placeholder="••••••">
                                    </div>
                                    <x-input-error :messages="$errors->get('pin')" class="mt-2" />
                                </div>

                                <button type="submit" :disabled="isInvalid"
                                    class="w-full flex items-center justify-center px-4 py-2.5 sm:py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg sm:rounded-xl transition-all duration-200 shadow-lg shadow-indigo-500/30 transform hover:-translate-y-0.5 text-xs sm:text-base disabled:opacity-50 disabled:cursor-not-allowed disabled:grayscale disabled:hover:transform-none">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    {{ __('Kirim Saldo Sekarang') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Transfer -->
                <div class="lg:col-span-2">
                    <div
                        class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden h-full">
                        <div
                            class="p-4 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <h3 class="text-base sm:text-lg font-bold text-slate-800 flex items-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-indigo-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Riwayat Transaksi
                            </h3>
                            <span
                                class="px-2 py-0.5 sm:px-3 sm:py-1 bg-indigo-50 text-indigo-700 rounded-full text-[10px] sm:text-xs font-medium">Terbaru</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr
                                        class="bg-slate-50 text-slate-500 text-[10px] sm:text-xs font-bold uppercase tracking-wider border-b border-slate-200">
                                        <th class="p-3 sm:p-4">Waktu</th>
                                        <th class="p-3 sm:p-4">Jenis</th>
                                        <th class="p-3 sm:p-4">Lawan Transaksi</th>
                                        <th class="p-3 sm:p-4 text-right">Jumlah</th>
                                        <th class="p-3 sm:p-4 hidden sm:table-cell">Ket</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($riwayat as $item)
                                        <tr class="hover:bg-slate-50/80 transition-colors duration-150">
                                            <td class="p-3 sm:p-4 whitespace-nowrap">
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-xs sm:text-sm font-semibold text-slate-700">{{ $item->created_at->timezone('Asia/Makassar')->format('d M Y') }}</span>
                                                    <span
                                                        class="text-[10px] sm:text-xs text-slate-400">{{ $item->created_at->timezone('Asia/Makassar')->format('H:i') }}
                                                        WITA</span>
                                                </div>
                                            </td>
                                            <td class="p-3 sm:p-4 whitespace-nowrap">
                                                @if($item->sender_id == $personel->id)
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-medium bg-rose-100 text-rose-800">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                                        </svg>
                                                        Keluar
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-medium bg-emerald-100 text-emerald-800">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                                        </svg>
                                                        Masuk
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="p-3 sm:p-4 whitespace-nowrap">
                                                @if($item->sender_id == $personel->id)
                                                    <div class="text-xs sm:text-sm font-medium text-slate-900">
                                                        @if($item->target_kendaraan_id)
                                                            <span class="text-indigo-600">Kendaraan: {{ $item->targetKendaraan->no_polisi ?? 'N/A' }}</span>
                                                        @else
                                                            {{ $item->receiver->nama ?? 'Tidak Diketahui' }}
                                                        @endif
                                                    </div>
                                                    <div class="text-[10px] sm:text-xs text-slate-500">Penerima</div>
                                                @else
                                                    <div class="text-xs sm:text-sm font-medium text-slate-900">
                                                        {{ $item->sender->nama ?? 'Tidak Diketahui' }}
                                                    </div>
                                                    <div class="text-[10px] sm:text-xs text-slate-500">Pengirim</div>
                                                @endif
                                            </td>
                                            <td class="p-3 sm:p-4 whitespace-nowrap text-right">
                                                @if($item->sender_id == $personel->id)
                                                    <span class="text-xs sm:text-sm font-bold text-rose-600">-
                                                        {{ number_format($item->jumlah, 0, ',', '.') }} L</span>
                                                @else
                                                    <span class="text-xs sm:text-sm font-bold text-emerald-600">+
                                                        {{ number_format($item->jumlah, 0, ',', '.') }} L</span>
                                                @endif
                                            </td>
                                            <td
                                                class="p-3 sm:p-4 text-xs sm:text-sm text-slate-500 truncate max-w-xs hidden sm:table-cell">
                                                {{ $item->keterangan ?? '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="p-6 sm:p-8 text-center">
                                                <div class="flex flex-col items-center justify-center text-slate-400">
                                                    <svg class="w-10 h-10 sm:w-12 sm:h-12 mb-2 sm:mb-3 opacity-50"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
                                                    </svg>
                                                    <span class="text-sm sm:text-base font-medium">Belum ada riwayat
                                                        transaksi</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($riwayat->hasPages())
                            <div class="p-3 sm:p-4 border-t border-slate-100 bg-slate-50">
                                {{ $riwayat->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>