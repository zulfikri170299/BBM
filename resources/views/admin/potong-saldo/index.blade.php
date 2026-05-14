<x-app-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gray-50/30 min-h-screen" x-data="{ 
        tab: 'kendaraan',
        selectedKendaraan: [],
        selectedPersonel: [],
        searchKendaraan: '',
        searchPersonel: '',
        allSelectedKendaraan: false,
        allSelectedPersonel: false,
        
        kendaraanList: @js($kendaraans->map(fn($k) => ['id' => $k->id, 'bbm' => $k->jenis_bbm, 'saldo' => $k->saldo])),
        personelList: @js($personels->map(fn($p) => ['id' => $p->id, 'bbm' => $p->jenis_bbm, 'saldo' => $p->saldo])),
        
        get countPertamax() {
            let data = this.tab === 'kendaraan' ? this.kendaraanList : this.personelList;
            let selected = this.tab === 'kendaraan' ? this.selectedKendaraan : this.selectedPersonel;
            return data.filter(item => selected.includes(item.id.toString()) && item.bbm === 'Pertamax').length;
        },
        get countDex() {
            let data = this.tab === 'kendaraan' ? this.kendaraanList : this.personelList;
            let selected = this.tab === 'kendaraan' ? this.selectedKendaraan : this.selectedPersonel;
            return data.filter(item => selected.includes(item.id.toString()) && item.bbm === 'Pertamina Dex').length;
        },
        get literPertamax() {
            let data = this.tab === 'kendaraan' ? this.kendaraanList : this.personelList;
            let selected = this.tab === 'kendaraan' ? this.selectedKendaraan : this.selectedPersonel;
            return data.filter(item => selected.includes(item.id.toString()) && item.bbm === 'Pertamax').reduce((a, b) => a + parseFloat(b.saldo), 0);
        },
        get literDex() {
            let data = this.tab === 'kendaraan' ? this.kendaraanList : this.personelList;
            let selected = this.tab === 'kendaraan' ? this.selectedKendaraan : this.selectedPersonel;
            return data.filter(item => selected.includes(item.id.toString()) && item.bbm === 'Pertamina Dex').reduce((a, b) => a + parseFloat(b.saldo), 0);
        },
        
        toggleAllKendaraan() {
            if (this.allSelectedKendaraan) {
                this.selectedKendaraan = Array.from(document.querySelectorAll('.kendaraan-checkbox')).map(el => el.value);
            } else {
                this.selectedKendaraan = [];
            }
        },
        toggleAllPersonel() {
            if (this.allSelectedPersonel) {
                this.selectedPersonel = Array.from(document.querySelectorAll('.personel-checkbox')).map(el => el.value);
            } else {
                this.selectedPersonel = [];
            }
        }
    }">
        <div class="max-w-7xl mx-auto">
            <!-- Header Compact -->
            <div class="mb-6 flex items-center justify-between border-b border-gray-200 pb-4">
                <div>
                    <h1 class="text-xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                        <span class="p-1.5 bg-indigo-600 text-white rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </span>
                        Potong Saldo Masal
                    </h1>
                </div>
                <div class="flex items-center gap-1 p-1 bg-gray-200/50 rounded-xl">
                    <button @click="tab = 'kendaraan'" :class="tab === 'kendaraan' ? 'bg-white shadow-sm text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">
                        Kendaraan
                    </button>
                    <button @click="tab = 'personel'" :class="tab === 'personel' ? 'bg-white shadow-sm text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">
                        Personel
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <!-- Selection Panel -->
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <!-- Toolbar Compact -->
                        <div class="p-3 border-b border-gray-100 flex items-center justify-between gap-4 bg-gray-50/30">
                            <div class="relative max-w-xs w-full">
                                <input type="text" 
                                    x-model="tab === 'kendaraan' ? searchKendaraan : searchPersonel"
                                    :placeholder="tab === 'kendaraan' ? 'Cari nopol...' : 'Cari nama/nrp...'" 
                                    class="w-full pl-8 pr-4 py-1.5 bg-white border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 text-xs font-medium">
                                <div class="absolute left-2.5 top-2 text-gray-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 bg-indigo-600 text-white text-[10px] font-black rounded-full" x-text="tab === 'kendaraan' ? selectedKendaraan.length : selectedPersonel.length">0</span>
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Item Terpilih</span>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="max-h-[500px] overflow-y-auto custom-scrollbar">
                            <!-- Kendaraan Table -->
                            <div x-show="tab === 'kendaraan'">
                                <table class="w-full text-left border-collapse">
                                    <thead class="sticky top-0 bg-white shadow-sm z-10 text-[9px] font-black text-gray-400 uppercase tracking-[0.1em]">
                                        <tr>
                                            <th class="px-4 py-3 w-10 text-center">
                                                <input type="checkbox" x-model="allSelectedKendaraan" @change="toggleAllKendaraan" class="rounded-sm border-gray-300 text-indigo-600 focus:ring-indigo-500 scale-90">
                                            </th>
                                            <th class="px-2 py-3">Satker</th>
                                            <th class="px-2 py-3">Kendaraan</th>
                                            <th class="px-4 py-3 text-right">Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @foreach($kendaraans as $k)
                                            <tr x-show="searchKendaraan === '' || '{{ $k->no_polisi }}'.toLowerCase().includes(searchKendaraan.toLowerCase())" 
                                                class="hover:bg-indigo-50/20 transition-colors">
                                                <td class="px-4 py-2.5 text-center">
                                                    <input type="checkbox" value="{{ $k->id }}" x-model="selectedKendaraan" class="kendaraan-checkbox rounded-sm border-gray-300 text-indigo-600 focus:ring-indigo-500 scale-90">
                                                </td>
                                                <td class="px-2 py-2.5">
                                                    <div class="text-[10px] font-bold text-gray-500 truncate max-w-[150px]">{{ $k->satker->nama_satker }}</div>
                                                </td>
                                                <td class="px-2 py-2.5">
                                                    <div class="flex flex-col">
                                                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ $k->jenis_kendaraan }}</span>
                                                        <div class="flex items-center gap-1.5">
                                                            <span class="text-xs font-black text-gray-900">{{ $k->no_polisi }}</span>
                                                            @if($k->jenis_bbm === 'Pertamax')
                                                                <span class="text-[7px] font-black text-indigo-600 px-1.5 py-0.5 bg-indigo-50 rounded uppercase border border-indigo-100">Pertamax</span>
                                                            @else
                                                                <span class="text-[7px] font-black text-amber-600 px-1.5 py-0.5 bg-amber-50 rounded uppercase border border-amber-100">P. Dex</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-2.5 text-right">
                                                    <span class="text-xs font-black {{ $k->saldo > 0 ? 'text-indigo-600' : 'text-gray-300' }}">{{ number_format($k->saldo, 0) }} L</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Personel Table -->
                            <div x-show="tab === 'personel'">
                                <table class="w-full text-left border-collapse">
                                    <thead class="sticky top-0 bg-white shadow-sm z-10 text-[9px] font-black text-gray-400 uppercase tracking-[0.1em]">
                                        <tr>
                                            <th class="px-4 py-3 w-10 text-center">
                                                <input type="checkbox" x-model="allSelectedPersonel" @change="toggleAllPersonel" class="rounded-sm border-gray-300 text-indigo-600 focus:ring-indigo-500 scale-90">
                                            </th>
                                            <th class="px-2 py-3">Satker</th>
                                            <th class="px-2 py-3">Personel</th>
                                            <th class="px-4 py-3 text-right">Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @foreach($personels as $p)
                                            <tr x-show="searchPersonel === '' || '{{ $p->nama }} {{ $p->nrp }}'.toLowerCase().includes(searchPersonel.toLowerCase())" 
                                                class="hover:bg-indigo-50/20 transition-colors">
                                                <td class="px-4 py-2.5 text-center">
                                                    <input type="checkbox" value="{{ $p->id }}" x-model="selectedPersonel" class="personel-checkbox rounded-sm border-gray-300 text-indigo-600 focus:ring-indigo-500 scale-90">
                                                </td>
                                                <td class="px-2 py-2.5">
                                                    <div class="text-[10px] font-bold text-gray-500 truncate max-w-[150px]">{{ $p->satker->nama_satker }}</div>
                                                </td>
                                                <td class="px-2 py-2.5">
                                                    <div class="flex items-center justify-between gap-4">
                                                        <div class="flex flex-col">
                                                            <span class="text-xs font-black text-gray-900 leading-tight">{{ $p->nama }}</span>
                                                            <span class="text-[8px] font-bold text-gray-400">NRP: {{ $p->nrp }}</span>
                                                        </div>
                                                        @if($p->jenis_bbm === 'Pertamax')
                                                            <span class="text-[7px] font-black text-indigo-600 px-1.5 py-0.5 bg-indigo-50 rounded uppercase border border-indigo-100">Pertamax</span>
                                                        @else
                                                            <span class="text-[7px] font-black text-amber-600 px-1.5 py-0.5 bg-amber-50 rounded uppercase border border-amber-100">P. Dex</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-4 py-2.5 text-right">
                                                    <span class="text-xs font-black {{ $p->saldo > 0 ? 'text-indigo-600' : 'text-gray-300' }}">{{ number_format($p->saldo, 0) }} L</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Panel (Ultra Compact) -->
                <div class="lg:col-span-4 sticky top-6">
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                        <!-- Summary Header Compact -->
                        <div class="p-4 bg-slate-900 text-white">
                            <h3 class="text-[10px] font-black uppercase tracking-widest mb-3 text-indigo-400">Ringkasan Pemotongan</h3>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-white/5 rounded-xl p-3 border border-white/5">
                                    <div class="text-[8px] font-black uppercase text-indigo-300 mb-2 tracking-tighter">Pertamax</div>
                                    <div class="space-y-1">
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-base font-black leading-none" x-text="countPertamax">0</span>
                                            <span class="text-[7px] font-bold opacity-30 uppercase tracking-tighter">Unit</span>
                                        </div>
                                        <div class="flex items-baseline gap-1 border-t border-white/5 pt-1">
                                            <span class="text-sm font-black leading-none text-indigo-400" x-text="literPertamax">0</span>
                                            <span class="text-[7px] font-bold text-indigo-400/40 uppercase tracking-tighter">Liter</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white/5 rounded-xl p-3 border border-white/5">
                                    <div class="text-[8px] font-black uppercase text-amber-300 mb-2 tracking-tighter">Pertamina Dex</div>
                                    <div class="space-y-1">
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-base font-black leading-none" x-text="countDex">0</span>
                                            <span class="text-[7px] font-bold opacity-30 uppercase tracking-tighter">Unit</span>
                                        </div>
                                        <div class="flex items-baseline gap-1 border-t border-white/5 pt-1">
                                            <span class="text-sm font-black leading-none text-amber-400" x-text="literDex">0</span>
                                            <span class="text-[7px] font-bold text-amber-400/40 uppercase tracking-tighter">Liter</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 py-1.5 px-3 bg-indigo-500/10 rounded-lg border border-indigo-500/20 flex items-center gap-2">
                                <svg class="w-3 h-3 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-[9px] font-bold text-indigo-200 italic">Seluruh sisa saldo akan dikosongkan.</p>
                            </div>
                        </div>

                        <!-- Form Content Compact -->
                        <div class="p-4">
                            <form action="{{ route('admin.bulk-potong.process') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="type" :value="tab">
                                <input type="hidden" name="is_kosongkan" value="1">
                                <template x-for="id in (tab === 'kendaraan' ? selectedKendaraan : selectedPersonel)" :key="id">
                                    <input type="hidden" name="ids[]" :value="id">
                                </template>

                                <!-- Option Group Compact -->
                                <div class="space-y-3">
                                    <div class="space-y-1.5">
                                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Kembalikan ke Stok?</label>
                                        <div class="flex p-1 bg-gray-100 rounded-lg">
                                            <label class="flex-1 cursor-pointer">
                                                <input type="radio" name="kembalikan_ke_stok" value="ya" checked class="sr-only peer">
                                                <div class="py-1 text-center text-[9px] font-black uppercase rounded-md transition-all peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:shadow-sm text-gray-400">YA</div>
                                            </label>
                                            <label class="flex-1 cursor-pointer">
                                                <input type="radio" name="kembalikan_ke_stok" value="tidak" class="sr-only peer">
                                                <div class="py-1 text-center text-[9px] font-black uppercase rounded-md transition-all peer-checked:bg-rose-600 peer-checked:text-white peer-checked:shadow-sm text-gray-400">TIDAK</div>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-1.5">
                                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Password Top Up</label>
                                        <input type="password" name="topup_password" required autocomplete="new-password" placeholder="••••••••" 
                                            class="w-full px-3 py-2 bg-gray-50 border-gray-100 rounded-lg focus:ring-2 focus:ring-indigo-500 font-bold text-xs transition-all">
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Keterangan</label>
                                        <textarea name="keterangan" required rows="2" placeholder="Alasan..." class="w-full px-3 py-2 bg-gray-50 border-gray-100 rounded-lg focus:ring-2 focus:ring-indigo-500 text-xs font-medium resize-none transition-all"></textarea>
                                    </div>
                                </div>

                                <!-- Submit Compact -->
                                <div class="pt-1">
                                    <button type="submit" 
                                        :disabled="(tab === 'kendaraan' ? selectedKendaraan.length : selectedPersonel.length) === 0"
                                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-100 disabled:text-gray-300 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-indigo-100 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                                        <span>Konfirmasi Potong</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>
</x-app-layout>
