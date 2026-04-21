<x-app-layout>
    <div class="p-4 lg:p-6 space-y-4 sm:space-y-6">
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 leading-tight tracking-tight uppercase">Transfer Saldo</h1>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Manajemen Perpindahan BBM antar Unit</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6" x-data="{ tipeTujuan: 'kendaraan', selectedKendaraan: '' }">
            {{-- Transfer Form --}}
            <div class="lg:col-span-4">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm relative z-10 overflow-hidden">
                    <div class="p-3 sm:p-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-xs sm:text-sm font-black text-slate-800 flex items-center gap-2 uppercase tracking-widest">
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
                                onchange="if(this.value) { window.location.href='{{ route('admin.transfer-saldo.index') }}?satker_id=' + this.value }"
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
                                        :class="tipeTujuan === 'kendaraan' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-slate-100 bg-white text-slate-400'">
                                        <input type="radio" name="tipe_tujuan" value="kendaraan" x-model="tipeTujuan" class="sr-only">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-gas-pump"></i>
                                            <span class="text-[11px] font-black uppercase tracking-wider">Pusat ke Kendaraan (TM)</span>
                                        </div>
                                    </label>
                                    <label class="relative flex items-center justify-center p-2 rounded-xl border-2 cursor-pointer transition-all"
                                        :class="tipeTujuan === 'personel' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-slate-100 bg-white text-slate-400'">
                                        <input type="radio" name="tipe_tujuan" value="personel" x-model="tipeTujuan" class="sr-only">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-user-tag"></i>
                                            <span class="text-[11px] font-black uppercase tracking-wider">Kendaraan ke Anggota</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            {{-- Pilih Kendaraan Sumber (Jika Kendaraan -> Personel) --}}
                            <div x-show="tipeTujuan === 'personel'" x-transition>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Kendaraan Sumber</label>
                                <select name="kendaraan_id" id="transfer_kendaraan_id" :required="tipeTujuan === 'personel'" class="w-full" x-model="selectedKendaraan" x-ref="kendaraanSelect">
                                    <option value="">-- Pilih Kendaraan --</option>
                                    @foreach($kendaraans as $k)
                                        <option value="{{ $k->id }}">{{ $k->no_polisi }} ({{ $k->jenis_bbm }} - {{ number_format($k->saldo, 0, ',', '.') }} L)</option>
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

                            {{-- Tujuan Kendaraan (Jika Pusat -> Kendaraan) --}}
                            <div x-show="tipeTujuan === 'kendaraan'" x-transition class="space-y-3">
                                {{-- Info Stok Pusat --}}
                                @if(isset($adminStocks) && $adminStocks->count() > 0)
                                <div class="bg-indigo-50/50 border border-indigo-100 rounded-xl p-3">
                                    <label class="block text-[9px] font-black text-indigo-400 uppercase tracking-widest mb-2 text-center items-center flex justify-center gap-1.5"><i class="fas fa-database"></i> Sisa Stok Pusat</label>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                        @foreach($adminStocks as $stock)
                                        <div class="bg-white border border-indigo-50 rounded-lg py-1.5 px-2 text-center shadow-sm hover:border-indigo-200 transition-colors">
                                            <div class="text-[9px] font-bold text-slate-500 uppercase">{{ $stock->jenis_bbm }}</div>
                                            <div class="text-[11px] font-black text-indigo-600">{{ number_format($stock->saldo, 0, ',', '.') }} L</div>
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
                                            <option value="{{ $k->id }}">{{ $k->no_polisi }} ({{ $k->jenis_bbm }} - {{ number_format($k->saldo, 0, ',', '.') }} L)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 pt-1">
                                {{-- Jumlah --}}
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Jumlah (L)</label>
                                    <input type="number" name="jumlah" step="0.1" min="0.1" required placeholder="0"
                                        class="w-full px-3 py-2 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm font-bold text-slate-800 focus:border-indigo-500 transition-all">
                                </div>
                                {{-- Password Top Up --}}
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">PIN / Password Topup</label>
                                    <input type="password" name="topup_password" required placeholder="***"
                                        class="w-full px-3 py-2 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm font-bold text-slate-800 focus:border-indigo-500 transition-all">
                                </div>
                            </div>
                            
                            {{-- Keterangan --}}
                            <div class="pt-1">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Keterangan</label>
                                <input name="keterangan" placeholder="Keterangan opsional..."
                                    class="w-full px-3 py-2 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm font-bold text-slate-800 focus:border-indigo-500 transition-all">
                            </div>

                            <button type="submit"
                                class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-black shadow-lg shadow-indigo-500/30 transition-all active:scale-95 flex items-center justify-center gap-2 text-xs uppercase tracking-widest mt-2"
                                {{ $kendaraans->isEmpty() ? 'disabled' : '' }}>
                                <i class="fas fa-paper-plane"></i>
                                Konfirmasi Transfer
                            </button>
                        @endif
                    </form>
                </div>
            </div>

            {{-- Riwayat Transfer --}}
            <div class="lg:col-span-8">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <h3 class="text-xs sm:text-sm font-black text-slate-800 uppercase tracking-widest">Riwayat Transfer BBM</h3>
                        <div class="text-[9px] font-bold text-slate-400 uppercase">
                            {{ $riwayat->total() }} Data Ditemukan
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100">
                                    <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tgl</th>
                                    <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Sumber</th>
                                    <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tujuan</th>
                                    <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Jumlah</th>
                                    <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Ket</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($riwayat as $item)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-4 py-2.5">
                                            <p class="text-[11px] font-black text-slate-700">{{ $item->created_at->format('d M Y') }}</p>
                                            <p class="text-[9px] font-bold text-slate-400 uppercase">{{ $item->created_at->format('H:i') }}</p>
                                        </td>
                                        <td class="px-4 py-2.5">
                                            @if($item->kendaraan_id)
                                                <div class="flex items-center gap-2">
                                                    <div class="w-7 h-7 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-500">
                                                        <i class="fas fa-car text-[10px]"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-[11px] font-black text-slate-700">{{ $item->kendaraan->no_polisi ?? '-' }}</p>
                                                        <p class="text-[9px] font-bold text-slate-400 uppercase">KENDARAAN</p>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="flex items-center gap-2">
                                                    <div class="w-7 h-7 bg-rose-50 rounded-lg flex items-center justify-center text-rose-500">
                                                        <i class="fas fa-gas-pump text-[10px]"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-[11px] font-black text-rose-600 uppercase">Stok Pusat</p>
                                                        <p class="text-[9px] font-bold text-slate-400 uppercase">SYSTEM</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2.5">
                                            <div class="flex items-center gap-2">
                                                @if($item->personel_id)
                                                    <div class="w-7 h-7 bg-amber-50 rounded-lg flex items-center justify-center text-amber-500">
                                                        <i class="fas fa-user-tag text-[10px]"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-[11px] font-black text-slate-700">{{ $item->personel->nama ?? '-' }}</p>
                                                        <p class="text-[9px] font-bold text-slate-400 uppercase">PERSONEL</p>
                                                    </div>
                                                @elseif(isset($item->tujuan_kendaraan_id))
                                                    <div class="w-7 h-7 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-500">
                                                        <i class="fas fa-car-side text-[10px]"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-[11px] font-black text-slate-700">{{ $item->tujuanKendaraan->no_polisi ?? '-' }}</p>
                                                        <p class="text-[9px] font-bold text-slate-400 uppercase">KENDARAAN</p>
                                                    </div>
                                                @else
                                                    -
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-2.5 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-black bg-indigo-100 text-indigo-700">
                                                {{ number_format($item->jumlah, 1, ',', '.') }} L
                                            </span>
                                        </td>
                                        <td class="px-4 py-2.5">
                                            <p class="text-[10px] font-bold text-slate-500 line-clamp-1 italic">{{ $item->keterangan ?: '-' }}</p>
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
                        <div class="p-3 border-t border-slate-100">
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
                    return new TomSelect(el, config);
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
                                        '<div class="font-bold text-slate-800 text-xs">' + escape(data.nama) + '</div>' +
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