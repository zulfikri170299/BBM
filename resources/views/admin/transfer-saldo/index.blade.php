<x-app-layout>
    <div class="p-4 lg:p-6 space-y-4 sm:space-y-6">
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-wide border-b-2 border-indigo-500/200/50 pb-2 inline-block">Transfer Saldo</h1>
                <p class="text-xs sm:text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-1">Manajemen Perpindahan BBM antar Unit</p>
            </div>
        </div>

        @php
            $personelAccessControl = \App\Models\Setting::where('key', 'personel_access_control')->value('value') ?? '1';
        @endphp

        <div class="bg-slate-900 border border-white/5 rounded-3xl shadow-lg shadow-black/20" x-data="{ tipeTujuan: 'kendaraan', selectedKendaraan: '' }">
            <div class="lg:grid lg:grid-cols-12 lg:divide-x lg:divide-white/5">
                {{-- Transfer Form --}}
                <div class="lg:col-span-4">
                    <div class="p-3 sm:p-4 border-b border-white/5 bg-slate-800/50 rounded-t-3xl lg:rounded-tl-3xl lg:rounded-tr-none">
                        <h3 class="text-xs sm:text-sm font-black text-slate-200 flex items-center gap-2 uppercase tracking-widest">
                            <i class="fas fa-exchange-alt text-indigo-500"></i>
                            Kustomisasi Transfer
                        </h3>
                    </div>
                    <form action="{{ route('admin.transfer-saldo.store') }}" method="POST" class="p-3 sm:p-4 space-y-3">
                        @csrf

                        {{-- Pilih Satker --}}
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">1. Satuan Kerja</label>
                            <select name="satker_id" id="transfer_satker_id"
                                onchange="if(this.value) { typeof Turbo !== 'undefined' ? Turbo.visit('{{ route('admin.transfer-saldo.index') }}?satker_id=' + this.value) : window.location.href='{{ route('admin.transfer-saldo.index') }}?satker_id=' + this.value }"
                                class="tom-select w-full">
                                <option value="">-- Pilih Satker --</option>
                                @foreach($satkers as $satker)
                                    <option value="{{ $satker->id }}" {{ $selectedSatkerId == $satker->id ? 'selected' : '' }}>{{ $satker->nama_satker }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if($selectedSatkerId)
                            {{-- Tipe Tujuan --}}
                            <div class="pt-1">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">2. Tipe Transfer</label>
                                <div class="grid grid-cols-1 gap-2">
                                    <label class="relative flex items-center justify-center p-2 rounded-xl border-2 cursor-pointer transition-all"
                                        :class="tipeTujuan === 'kendaraan' ? 'border-indigo-500/50 bg-indigo-500/10 text-indigo-400' : 'border-white/5 bg-slate-800/50 text-slate-400'">
                                        <input type="radio" name="tipe_tujuan" value="kendaraan" x-model="tipeTujuan" class="sr-only">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-gas-pump"></i>
                                            <span class="text-xs font-bold uppercase tracking-wider">Pusat ke Kendaraan (TM)</span>
                                        </div>
                                    </label>
                                    @if($personelAccessControl == '1')
                                    <label class="relative flex items-center justify-center p-2 rounded-xl border-2 cursor-pointer transition-all"
                                        :class="tipeTujuan === 'personel' ? 'border-indigo-500/50 bg-indigo-500/10 text-indigo-400' : 'border-white/5 bg-slate-800/50 text-slate-400'">
                                        <input type="radio" name="tipe_tujuan" value="personel" x-model="tipeTujuan" class="sr-only">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-user-tag"></i>
                                            <span class="text-xs font-bold uppercase tracking-wider">Kendaraan ke Anggota</span>
                                        </div>
                                    </label>
                                    @endif
                                </div>
                            </div>

                            @if($personelAccessControl == '1')
                            {{-- Pilih Kendaraan Sumber (Jika Kendaraan -> Personel) --}}
                            <div x-show="tipeTujuan === 'personel'" x-transition>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Kendaraan Sumber</label>
                                <select name="kendaraan_id" id="transfer_kendaraan_id" :required="tipeTujuan === 'personel'" class="w-full" x-model="selectedKendaraan" x-ref="kendaraanSelect">
                                    <option value="">-- Pilih Kendaraan --</option>
                                    @foreach($kendaraans as $k)
                                        <option value="{{ $k->id }}">{{ $k->no_polisi }} ({{ $k->jenis_bbm }} - {{ rtrim(rtrim(number_format($k->saldo, 2, ',', '.'), '0'), ',') }} L)</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tujuan Personel (Jika Kendaraan -> Personel) --}}
                            <div x-show="tipeTujuan === 'personel'" x-transition>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Anggota Tujuan</label>
                                <select name="personel_id" id="transfer_personel_id" :required="tipeTujuan === 'personel'" class="w-full">
                                    <option value="">-- Pilih Anggota --</option>
                                </select>
                            </div>
                            @endif

                            {{-- Tujuan Kendaraan (Jika Pusat -> Kendaraan) --}}
                            <div x-show="tipeTujuan === 'kendaraan'" x-transition class="space-y-3">
                                {{-- Info Stok Pusat --}}
                                @if(isset($adminStocks) && $adminStocks->count() > 0)
                                <div class="bg-indigo-500/10 border border-indigo-500/20 rounded-xl p-3">
                                    <label class="block text-[9px] font-black text-indigo-400 uppercase tracking-widest mb-2 text-center items-center flex justify-center gap-1.5"><i class="fas fa-database"></i> Sisa Stok Pusat</label>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                        @foreach($adminStocks as $stock)
                                        <div class="bg-slate-900 border border-indigo-500/20 rounded-lg py-1.5 px-2 text-center shadow-sm hover:border-indigo-400/50 transition-colors">
                                            <div class="text-[9px] font-bold text-slate-400 uppercase">{{ $stock->jenis_bbm }}</div>
                                            <div class="text-xs font-bold text-indigo-400">{{ rtrim(rtrim(number_format($stock->saldo, 2, ',', '.'), '0'), ',') }} L</div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Kendaraan Tujuan</label>
                                    <select name="tujuan_kendaraan_id" id="transfer_tujuan_kendaraan_id" :required="tipeTujuan === 'kendaraan'" class="w-full" x-ref="tujuanKendaraanSelect">
                                        <option value="">-- Pilih Kendaraan Tujuan --</option>
                                        @foreach($kendaraans as $k)
                                            <option value="{{ $k->id }}">{{ $k->no_polisi }} ({{ $k->jenis_bbm }} - {{ rtrim(rtrim(number_format($k->saldo, 2, ',', '.'), '0'), ',') }} L)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 pt-1">
                                {{-- Jumlah --}}
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Jumlah (L)</label>
                                    <input type="number" name="jumlah" step="1" min="1" required placeholder="0"
                                        class="w-full px-3 py-2 bg-slate-800/50 border-2 border-white/5 rounded-xl text-xs font-semibold text-slate-200 focus:border-indigo-500 transition-all">
                                </div>
                                {{-- Password Top Up --}}
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">PIN / Password Topup</label>
                                    <input type="password" name="topup_password" required placeholder="***"
                                        class="w-full px-3 py-2 bg-slate-800/50 border-2 border-white/5 rounded-xl text-xs font-semibold text-slate-200 focus:border-indigo-500 transition-all">
                                </div>
                            </div>
                            
                            {{-- Keterangan --}}
                            <div class="pt-1">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Keterangan</label>
                                <input name="keterangan" placeholder="Keterangan opsional..."
                                    class="w-full px-3 py-2 bg-slate-800/50 border-2 border-white/5 rounded-xl text-xs font-semibold text-slate-200 focus:border-indigo-500 transition-all">
                            </div>

                            <button type="submit"
                                class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-black shadow-lg shadow-indigo-500/20 transition-all active:scale-95 flex items-center justify-center gap-2 text-xs uppercase tracking-widest mt-2"
                                {{ $kendaraans->isEmpty() ? 'disabled' : '' }}>
                                <i class="fas fa-paper-plane"></i>
                                Konfirmasi Transfer
                            </button>
                        @endif
                    </form>
                </div>

                {{-- Riwayat Transfer --}}
                <div class="lg:col-span-8 border-t lg:border-t-0 border-white/5">
                    <div class="p-4 border-b border-white/5 bg-slate-800/50 flex items-center justify-between lg:rounded-tr-3xl">
                        <h3 class="text-xs sm:text-sm font-black text-slate-200 uppercase tracking-widest">Riwayat Transfer BBM</h3>
                        <div class="text-[9px] font-bold text-slate-400 uppercase">
                            {{ $riwayat->total() }} Data Ditemukan
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-800/50 border-b border-white/5">
                                    <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tgl</th>
                                    <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Sumber</th>
                                    <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tujuan</th>
                                    <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Jumlah</th>
                                    <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Ket</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($riwayat as $item)
                                    <tr class="hover:bg-slate-800/50 transition-colors">
                                        <td class="px-4 py-3 align-top">
                                            <p class="text-xs font-bold text-slate-200 whitespace-nowrap mb-0.5">{{ $item->created_at->format('d M Y') }}</p>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase">{{ $item->created_at->format('H:i') }}</p>
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            @if($item->kendaraan_id)
                                                <div class="flex items-center gap-2">
                                                    <div class="w-9 h-9 bg-indigo-500/20 border border-indigo-500/30 rounded-xl flex items-center justify-center text-indigo-400">
                                                        <i class="fas fa-car text-xs"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs font-bold text-slate-200 whitespace-nowrap mb-0.5">{{ $item->kendaraan->no_polisi ?? '-' }}</p>
                                                        <p class="text-[10px] font-bold text-slate-400 uppercase">KENDARAAN</p>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="flex items-center gap-2">
                                                    <div class="w-9 h-9 bg-rose-500/20 border border-rose-500/30 rounded-xl flex items-center justify-center text-rose-400">
                                                        <i class="fas fa-gas-pump text-xs"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs font-bold text-rose-500 uppercase mb-0.5">Stok Pusat</p>
                                                        <p class="text-[10px] font-bold text-slate-400 uppercase">SYSTEM</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            <div class="flex items-center gap-2">
                                                @if($item->personel_id)
                                                    <div class="w-9 h-9 bg-amber-500/20 border border-amber-500/30 rounded-xl flex items-center justify-center text-amber-400">
                                                        <i class="fas fa-user-tag text-xs"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs font-bold text-slate-200 whitespace-nowrap mb-0.5">{{ $item->personel->nama ?? '-' }}</p>
                                                        <p class="text-[10px] font-bold text-slate-400 uppercase">PERSONEL</p>
                                                    </div>
                                                @elseif(isset($item->tujuan_kendaraan_id))
                                                    <div class="w-9 h-9 bg-emerald-500/20 border border-emerald-500/30 rounded-xl flex items-center justify-center text-emerald-400">
                                                        <i class="fas fa-car-side text-xs"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs font-bold text-slate-200 whitespace-nowrap mb-0.5">{{ $item->tujuanKendaraan->no_polisi ?? '-' }}</p>
                                                        <p class="text-[10px] font-bold text-slate-400 uppercase">KENDARAAN</p>
                                                    </div>
                                                @else
                                                    -
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center align-top">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-indigo-500/20 border border-indigo-500/30 text-indigo-300">
                                                {{ number_format($item->jumlah, 0, ',', '.') }} L
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            <p class="text-xs font-bold text-slate-400 line-clamp-1 italic">{{ $item->keterangan ?: '-' }}</p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                            <i class="fas fa-folder-open mb-2 block text-xl"></i>
                                            <span class="text-[11px] font-bold uppercase tracking-widest">Belum ada riwayat transfer.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="p-3 border-t border-white/5">
                            {{ $riwayat->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('turbo:load', function () {
                const satkerSelect = document.getElementById('transfer_satker_id');
                const kendaraanSelect = document.getElementById('transfer_kendaraan_id');
                const tujuanKendaraanSelect = document.getElementById('transfer_tujuan_kendaraan_id');
                const personelSelect = document.getElementById('transfer_personel_id');
                
                function getTsInstance(el, config = {}) {
                    if (!el) return null;
                    if (el.tomselect) return el.tomselect;
                    const defaults = {
                        create: false,
                        dropdownParent: 'body',
                        onDropdownOpen: (dropdown) => {
                            dropdown.style.zIndex = '99999';
                        }
                    };
                    return new TomSelect(el, { ...defaults, ...config });
                }

                if (satkerSelect) {
                    getTsInstance(satkerSelect);
                }

                const kendaraanTs = getTsInstance(kendaraanSelect);

                if (tujuanKendaraanSelect) {
                    getTsInstance(tujuanKendaraanSelect, {
                        create: false,
                        sortField: {
                            field: 'text',
                            direction: 'asc'
                        }
                    });
                }

                const personelTs = getTsInstance(personelSelect, {
                    create: false,
                    valueField: 'id',
                    labelField: 'text',
                    searchField: 'text',
                    options: [],
                    render: {
                        option: function(data, escape) {
                            return '<div class="px-3 py-2 border-b border-slate-50">' +
                                        '<div class="font-bold text-slate-200 text-xs">' + escape(data.nama) + '</div>' +
                                        '<div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">' + escape(data.nrp) + ' • ' + escape(data.bbm_label) + ' • ' + escape(data.saldo_label) + '</div>' +
                                   '</div>';
                        },
                        item: function(data, escape) {
                            return '<div class="text-xs font-bold">' + escape(data.nama) + ' (' + escape(data.nrp) + ')</div>';
                        }
                    }
                });

                const kendaraans = @json($kendaraans ?? []);
                const personels = @json($personels ?? []);

                function updateOptions(value) {
                    if(!personelTs) return;
                    personelTs.clear();
                    personelTs.clearOptions();

                    if (!value) return;

                    const selectedKendaraan = kendaraans.find(k => k.id == value);
                    const requiredBbm = selectedKendaraan ? (selectedKendaraan.jenis_bbm || '').toUpperCase() : null;

                    if (requiredBbm) {
                        // Filter & Add Personels
                        const filteredPersonels = personels.filter(p => !p.jenis_bbm || p.jenis_bbm.toUpperCase() === requiredBbm);
                        personelTs.addOptions(filteredPersonels.map(p => ({
                            id: p.id,
                            nama: p.nama,
                            nrp: p.nrp,
                            bbm_label: p.jenis_bbm || 'BELUM SET BBM',
                            saldo_label: Number(p.saldo).toLocaleString('id-ID') + ' L',
                            text: `${p.nama} (${p.nrp})`
                        })));
                    }
                }

                if (kendaraanTs) {
                    kendaraanTs.on('change', function (value) {
                        updateOptions(value);
                    });

                    if (kendaraanTs.getValue()) {
                        updateOptions(kendaraanTs.getValue());
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>