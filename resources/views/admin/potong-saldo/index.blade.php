<x-app-layout>
    <div class="py-6 px-2 sm:px-6 lg:px-8  min-h-screen" x-data="{ 
        tab: 'kendaraan',
        selectedKendaraan: [],
        selectedPersonel: [],
        searchKendaraan: '',
        searchPersonel: '',
        filterSatker: '',
        filterBbm: '',
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
                // Hanya pilih yang terlihat (tidak kena filter)
                this.selectedKendaraan = Array.from(document.querySelectorAll('.kendaraan-checkbox'))
                    .filter(el => el.closest('tr').style.display !== 'none')
                    .map(el => el.value);
            } else {
                this.selectedKendaraan = [];
            }
        },
        toggleAllPersonel() {
            if (this.allSelectedPersonel) {
                // Hanya pilih yang terlihat (tidak kena filter)
                this.selectedPersonel = Array.from(document.querySelectorAll('.personel-checkbox'))
                    .filter(el => el.closest('tr').style.display !== 'none')
                    .map(el => el.value);
            } else {
                this.selectedPersonel = [];
            }
        }
    }">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <!-- Header Compact -->
            <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-wide border-b-2 border-indigo-500/50 pb-2 inline-block">Potong Saldo Masal</h1>
                    <p class="text-xs sm:text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-1">Pengosongan Saldo Serentak</p>
                </div>
                <div class="flex items-center gap-1 p-1 bg-slate-900 border border-white/5 rounded-xl shadow-lg">
                    <button @click="tab = 'kendaraan'" :class="tab === 'kendaraan' ? 'bg-indigo-600 shadow-sm text-white' : 'text-slate-400 hover:text-slate-300 hover:bg-white/5'" class="px-4 py-1.5 rounded-lg text-xs font-black uppercase tracking-widest transition-all flex items-center gap-2">
                        <i class="fas fa-car"></i> Kendaraan
                    </button>
                    <button @click="tab = 'personel'" :class="tab === 'personel' ? 'bg-amber-500 shadow-sm text-white' : 'text-slate-400 hover:text-slate-300 hover:bg-white/5'" class="px-4 py-1.5 rounded-lg text-xs font-black uppercase tracking-widest transition-all flex items-center gap-2">
                        <i class="fas fa-user-tag"></i> Personel
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <!-- Selection Panel -->
                <div class="lg:col-span-8">
                    <div class="bg-slate-900 border border-white/5 rounded-3xl shadow-lg shadow-black/20">
                        <!-- Toolbar Compact -->
                        <div class="p-3 border-b border-white/5 flex items-center justify-between gap-4 ">
                            <div class="relative max-w-xs w-full">
                                <input type="text" 
                                    x-model="tab === 'kendaraan' ? searchKendaraan : searchPersonel"
                                    :placeholder="tab === 'kendaraan' ? 'Cari nopol...' : 'Cari nama/nrp...'" 
                                    class="w-full pl-8 pr-4 py-1.5 bg-slate-800/50 border border-white/5 rounded-lg focus:ring-2 focus:ring-indigo-500 text-xs font-medium">
                                <div class="absolute left-2.5 top-2 text-slate-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2 flex-1 justify-end">
                                <!-- Custom Dropdown Satker -->
                                <div class="relative" x-data="{ 
                                    open: false,
                                    search: '',
                                    get selectedLabel() {
                                        if (filterSatker === '') return 'Semua Satker';
                                        let item = @js($satkers).find(s => s.id == filterSatker);
                                        return item ? item.nama_satker : 'Semua Satker';
                                    }
                                }" @click.away="open = false">
                                    <button @click="open = !open" type="button" 
                                        class="flex items-center justify-between gap-2 px-3 py-1.5 bg-slate-900 border border-white/5 border border-white/10 rounded-lg text-[10px] font-black text-slate-300 hover:border-indigo-300 hover:bg-indigo-500/10 text-slate-300 transition-all min-w-[160px]">
                                        <span x-text="selectedLabel" class="truncate"></span>
                                        <svg class="w-3 h-3 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>

                                    <div x-show="open" 
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        class="absolute right-0 mt-2 w-64 bg-slate-900 border border-white/5 rounded-xl shadow-xl border border-white/5 z-[100] overflow-hidden">
                                        
                                        <!-- Search Satker inside dropdown -->
                                        <div class="p-2 border-b border-gray-50 bg-slate-800/50">
                                            <div class="relative">
                                                <input type="text" x-model="search" placeholder="Cari satker..." 
                                                    class="w-full pl-7 pr-3 py-1.5 text-[10px] font-bold border-white/10 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                                <div class="absolute left-2.5 top-2 text-slate-400">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="max-h-60 overflow-y-auto custom-scrollbar p-1">
                                            <button @click="filterSatker = ''; open = false; search = ''" type="button"
                                                class="w-full text-left px-3 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all"
                                                :class="filterSatker === '' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800'">
                                                Semua Satker
                                            </button>
                                            @foreach($satkers as $satker)
                                                <button x-show="'{{ strtolower($satker->nama_satker) }}'.includes(search.toLowerCase())"
                                                    @click="filterSatker = '{{ $satker->id }}'; open = false; search = ''" type="button"
                                                    class="w-full text-left px-3 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all"
                                                    :class="filterSatker == '{{ $satker->id }}' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800'">
                                                    {{ $satker->nama_satker }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!-- Custom Dropdown BBM -->
                                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                    <button @click="open = !open" type="button" 
                                        class="flex items-center justify-between gap-2 px-3 py-1.5 bg-slate-900 border border-white/5 border border-white/10 rounded-lg text-[10px] font-black text-slate-300 hover:border-indigo-300 hover:bg-indigo-500/10 text-slate-300 transition-all min-w-[120px]">
                                        <span x-text="filterBbm === '' ? 'Semua BBM' : filterBbm"></span>
                                        <svg class="w-3 h-3 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>

                                    <div x-show="open" 
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        class="absolute right-0 mt-2 w-40 bg-slate-900 border border-white/5 rounded-xl shadow-xl border border-white/5 z-[100] p-1">
                                        <button @click="filterBbm = ''; open = false" type="button"
                                            class="w-full text-left px-3 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all"
                                            :class="filterBbm === '' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800'">
                                            Semua BBM
                                        </button>
                                        <button @click="filterBbm = 'Pertamax'; open = false" type="button"
                                            class="w-full text-left px-3 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all"
                                            :class="filterBbm === 'Pertamax' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800'">
                                            Pertamax
                                        </button>
                                        <button @click="filterBbm = 'Pertamina Dex'; open = false" type="button"
                                            class="w-full text-left px-3 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all"
                                            :class="filterBbm === 'Pertamina Dex' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800'">
                                            P. Dex
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <span class="px-2.5 py-0.5 bg-indigo-600 text-white text-[10px] font-black rounded-full" x-text="tab === 'kendaraan' ? selectedKendaraan.length : selectedPersonel.length">0</span>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Item Terpilih</span>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="max-h-[500px] overflow-y-auto custom-scrollbar rounded-b-3xl">
                            <!-- Kendaraan Table -->
                            <div x-show="tab === 'kendaraan'">
                                <table class="w-full text-left border-collapse">
                                    <thead class="sticky top-0 bg-slate-800/50 border-b border-white/5 shadow-sm z-10 text-[9px] font-black text-slate-400 uppercase tracking-[0.1em]">
                                        <tr>
                                            <th class="px-4 py-3 w-10 text-center">
                                                <input type="checkbox" x-model="allSelectedKendaraan" @change="toggleAllKendaraan" class="rounded-sm border-white/20 text-indigo-600 focus:ring-indigo-500 scale-90">
                                            </th>
                                            <th class="px-2 py-3">Satker</th>
                                            <th class="px-2 py-3">Kendaraan</th>
                                            <th class="px-4 py-3 text-right">Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/5">
                                        @foreach($kendaraans as $k)
                                            <tr x-show="(searchKendaraan === '' || '{{ $k->no_polisi }}'.toLowerCase().includes(searchKendaraan.toLowerCase())) && 
                                                        (filterSatker === '' || '{{ $k->satker_id }}' === filterSatker) &&
                                                        (filterBbm === '' || '{{ $k->jenis_bbm }}' === filterBbm)" 
                                                class="hover:bg-indigo-50/20 transition-colors">
                                                <td class="px-4 py-2.5 text-center">
                                                    <input type="checkbox" value="{{ $k->id }}" x-model="selectedKendaraan" class="kendaraan-checkbox rounded-sm border-white/20 text-indigo-600 focus:ring-indigo-500 scale-90">
                                                </td>
                                                <td class="px-2 py-2.5">
                                                    <div class="text-[10px] font-bold text-slate-400 truncate max-w-[150px]">{{ $k->satker->nama_satker }}</div>
                                                </td>
                                                <td class="px-2 py-2.5">
                                                    <div class="flex flex-col">
                                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $k->jenis_kendaraan }}</span>
                                                        <div class="flex items-center gap-1.5">
                                                            <span class="text-xs font-black text-white">{{ $k->no_polisi }}</span>
                                                            @if($k->jenis_bbm === 'Pertamax')
                                                                <span class="text-[7px] font-black text-indigo-600 px-1.5 py-0.5 bg-indigo-50 rounded uppercase border border-indigo-100">Pertamax</span>
                                                            @else
                                                                <span class="text-[7px] font-black text-amber-600 px-1.5 py-0.5 bg-amber-50 rounded uppercase border border-amber-100">P. Dex</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-2.5 text-right">
                                                    <span class="text-xs font-black {{ $k->saldo > 0 ? 'text-indigo-600' : 'text-slate-300' }}">{{ number_format($k->saldo, 0) }} L</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Personel Table -->
                            <div x-show="tab === 'personel'">
                                <table class="w-full text-left border-collapse">
                                    <thead class="sticky top-0 bg-slate-800/50 border-b border-white/5 shadow-sm z-10 text-[9px] font-black text-slate-400 uppercase tracking-[0.1em]">
                                        <tr>
                                            <th class="px-4 py-3 w-10 text-center">
                                                <input type="checkbox" x-model="allSelectedPersonel" @change="toggleAllPersonel" class="rounded-sm border-white/20 text-indigo-600 focus:ring-indigo-500 scale-90">
                                            </th>
                                            <th class="px-2 py-3">Satker</th>
                                            <th class="px-2 py-3">Personel</th>
                                            <th class="px-4 py-3 text-right">Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/5">
                                        @foreach($personels as $p)
                                            <tr x-show="(searchPersonel === '' || '{{ $p->nama }} {{ $p->nrp }}'.toLowerCase().includes(searchPersonel.toLowerCase())) &&
                                                        (filterSatker === '' || '{{ $p->satker_id }}' === filterSatker) &&
                                                        (filterBbm === '' || '{{ $p->jenis_bbm }}' === filterBbm)" 
                                                class="hover:bg-indigo-50/20 transition-colors">
                                                <td class="px-4 py-2.5 text-center">
                                                    <input type="checkbox" value="{{ $p->id }}" x-model="selectedPersonel" class="personel-checkbox rounded-sm border-white/20 text-indigo-600 focus:ring-indigo-500 scale-90">
                                                </td>
                                                <td class="px-2 py-2.5">
                                                    <div class="text-[10px] font-bold text-slate-400 truncate max-w-[150px]">{{ $p->satker->nama_satker }}</div>
                                                </td>
                                                <td class="px-2 py-2.5">
                                                    <div class="flex items-center justify-between gap-4">
                                                        <div class="flex flex-col">
                                                            <span class="text-xs font-black text-white leading-tight">{{ $p->nama }}</span>
                                                            <span class="text-[8px] font-bold text-slate-400">NRP: {{ $p->nrp }}</span>
                                                        </div>
                                                        @if($p->jenis_bbm === 'Pertamax')
                                                            <span class="text-[7px] font-black text-indigo-600 px-1.5 py-0.5 bg-indigo-50 rounded uppercase border border-indigo-100">Pertamax</span>
                                                        @else
                                                            <span class="text-[7px] font-black text-amber-600 px-1.5 py-0.5 bg-amber-50 rounded uppercase border border-amber-100">P. Dex</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-4 py-2.5 text-right">
                                                    <span class="text-xs font-black {{ $p->saldo > 0 ? 'text-indigo-600' : 'text-slate-300' }}">{{ number_format($p->saldo, 0) }} L</span>
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
                    <div class="bg-slate-900 border border-white/5 rounded-3xl shadow-lg shadow-black/20 overflow-hidden">
                        <!-- Summary Header Compact -->
                        <div class="p-4 bg-slate-900 text-white">
                            <h3 class="text-[10px] font-black uppercase tracking-widest mb-3 text-indigo-400">Ringkasan Pemotongan</h3>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-slate-900 border border-white/5/5 rounded-xl p-3 border border-white/5">
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
                                <div class="bg-slate-900 border border-white/5/5 rounded-xl p-3 border border-white/5">
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
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kembalikan ke Stok?</label>
                                        <div class="flex p-1 bg-slate-800 rounded-lg">
                                            <label class="flex-1 cursor-pointer">
                                                <input type="radio" name="kembalikan_ke_stok" value="ya" checked class="sr-only peer">
                                                <div class="py-1 text-center text-[9px] font-black uppercase rounded-md transition-all peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:shadow-sm text-slate-400">YA</div>
                                            </label>
                                            <label class="flex-1 cursor-pointer">
                                                <input type="radio" name="kembalikan_ke_stok" value="tidak" class="sr-only peer">
                                                <div class="py-1 text-center text-[9px] font-black uppercase rounded-md transition-all peer-checked:bg-rose-600 peer-checked:text-white peer-checked:shadow-sm text-slate-400">TIDAK</div>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-1.5">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Password Top Up</label>
                                        <input type="password" name="topup_password" required autocomplete="new-password" placeholder="••••••••" 
                                            class="w-full px-3 py-2 bg-slate-800/50 border-white/5 rounded-lg focus:ring-2 focus:ring-indigo-500 font-bold text-xs transition-all">
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Keterangan</label>
                                        <textarea name="keterangan" required rows="2" placeholder="Alasan..." class="w-full px-3 py-2 bg-slate-800/50 border-white/5 rounded-lg focus:ring-2 focus:ring-indigo-500 text-xs font-medium resize-none transition-all"></textarea>
                                    </div>
                                </div>

                                <!-- Submit Compact -->
                                <div class="pt-1">
                                    <button type="submit" 
                                        :disabled="(tab === 'kendaraan' ? selectedKendaraan.length : selectedPersonel.length) === 0"
                                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 disabled:bg-slate-800 disabled:text-slate-300 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-indigo-500/20 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
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

    
</x-app-layout>
