<x-app-layout>
    <div class="p-6 lg:p-8 space-y-8">
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 leading-tight">Transfer Saldo ke Personel</h1>
                <p class="mt-1 text-sm text-slate-500">Transfer saldo BBM dari kendaraan ke personel di seluruh satker.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Transfer Form --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm sticky top-8 relative z-10">
                    <div class="p-4 sm:p-6 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                        <h3 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            Form Transfer
                        </h3>
                    </div>
                    <form action="{{ route('admin.transfer-saldo.store') }}" method="POST" class="p-4 sm:p-6 space-y-4">
                        @csrf

                        {{-- Pilih Satker --}}
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Satker</label>
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
                            {{-- Pilih Kendaraan --}}
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kendaraan
                                    Sumber</label>
                                <select name="kendaraan_id" id="transfer_kendaraan_id" required class="tom-select w-full">
                                    <option value="">-- Pilih Kendaraan --</option>
                                    @foreach($kendaraans as $k)
                                        <option value="{{ $k->id }}">{{ $k->no_polisi }} ({{ $k->jenis_bbm }} -
                                            {{ number_format($k->saldo, 0, ',', '.') }} L)
                                        </option>
                                    @endforeach
                                </select>
                                @if($kendaraans->isEmpty())
                                    <p class="text-xs text-amber-500 mt-1 font-medium">Tidak ada kendaraan di satker ini.</p>
                                @endif
                            </div>

                            {{-- Pilih Personel --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Personel
                                    Tujuan</label>
                                <select name="personel_id" id="transfer_personel_id" required class="w-full">
                                    <option value="">-- Pilih Personel --</option>
                                    <!-- Options will be populated by TomSelect -->
                                </select>
                                @if($personels->isEmpty())
                                    <p class="text-xs text-amber-500 mt-1 font-medium">Tidak ada personel di satker ini.</p>
                                @endif
                                <p id="filter_info" class="text-[10px] text-indigo-500 mt-1 font-bold hidden">
                                    Menampilkan personel dengan BBM <span id="filter_bbm_label"></span> atau belum set BBM.
                                </p>
                            </div>

                            {{-- Jumlah --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jumlah
                                    (Liter)</label>
                                <input type="number" name="jumlah" step="0.1" min="0.1" required placeholder="0"
                                    class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm font-semibold text-slate-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all">
                            </div>

                            {{-- Keterangan --}}
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Keterangan
                                    (Opsional)</label>
                                <textarea name="keterangan" rows="2"
                                    class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm text-slate-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all"
                                    placeholder="Keterangan transfer..."></textarea>
                            </div>

                            <button type="submit"
                                class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/30 transition-all active:scale-95 flex items-center justify-center gap-2"
                                {{ $kendaraans->isEmpty() || $personels->isEmpty() ? 'disabled' : '' }}>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                                Transfer Saldo
                            </button>
                        @endif
                    </form>
                </div>
            </div>

            {{-- Riwayat Transfer --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-lg font-bold text-slate-800">Riwayat Transfer</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100">
                                    <th colspan="6" class="px-6 py-3">
                                        <div class="flex items-center justify-between">
                                            <form action="{{ route('admin.transfer-saldo.index') }}" method="GET"
                                                class="flex items-center">
                                                @if($selectedSatkerId)
                                                    <input type="hidden" name="satker_id" value="{{ $selectedSatkerId }}">
                                                @endif
                                                <x-per-page :current="request('per_page', 20)" />
                                            </form>
                                            <div
                                                class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">
                                                Menampilkan
                                                {{ $riwayat->firstItem() ?? 0 }}-{{ $riwayat->lastItem() ?? 0 }} dari
                                                {{ $riwayat->total() }} data
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                                <tr class="bg-slate-50 border-b border-slate-100">
                                    <th
                                        class="px-4 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        Tanggal</th>
                                    <th
                                        class="px-4 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        Satker</th>
                                    <th
                                        class="px-4 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        Kendaraan</th>
                                    <th
                                        class="px-4 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        Personel</th>
                                    <th
                                        class="px-4 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        Jumlah</th>
                                    <th
                                        class="px-4 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($riwayat as $item)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-4 py-3">
                                            <p class="text-xs font-semibold text-slate-700">
                                                {{ $item->created_at->format('d/m/Y') }}
                                            </p>
                                            <p class="text-[9px] text-slate-400">{{ $item->created_at->format('H:i') }} WIB
                                            </p>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="text-xs font-semibold text-slate-700">{{ $item->satker->nama_satker ?? '-' }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="text-xs font-semibold text-slate-700">{{ $item->kendaraan->no_polisi ?? '-' }}</span>
                                            <p class="text-[9px] text-slate-400">{{ $item->kendaraan->jenis_bbm ?? '' }}</p>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($item->personel_id)
                                                <span
                                                    class="text-xs font-semibold text-slate-700">{{ $item->personel->nama ?? '-' }}</span>
                                                <p class="text-[9px] text-slate-400">{{ $item->personel->nrp ?? '' }}</p>
                                            @else
                                                <span class="text-xs font-bold text-rose-600">PUSAT (POTONGAN)</span>
                                                <p class="text-[9px] text-slate-400">Pengurangan Saldo</p>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="text-xs font-bold text-indigo-600">{{ number_format($item->jumlah, 0, ',', '.') }}
                                                L</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <p class="text-xs text-slate-600 line-clamp-2">{{ $item->keterangan ?: '-' }}
                                            </p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">Belum ada
                                            riwayat transfer.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($riwayat->hasPages())
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                            {{ $riwayat->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('turbo:load', function () {
                const kendaraanSelect = document.getElementById('transfer_kendaraan_id');
                const personelSelect = document.getElementById('transfer_personel_id');
                
                if (!kendaraanSelect || !personelSelect) return;

                // Function to get or create TomSelect instance
                function getTsInstance(el, config = {}) {
                    if (el.tomselect) return el.tomselect;
                    return new TomSelect(el, config);
                }

                const kendaraanTs = getTsInstance(kendaraanSelect);
                const personelTs = getTsInstance(personelSelect, {
                    create: false,
                    valueField: 'id',
                    labelField: 'text',
                    searchField: 'text',
                    options: [],
                    render: {
                        option: function(data, escape) {
                            return '<div class="px-3 py-2 border-b border-slate-50">' +
                                        '<div class="font-bold text-slate-800 text-sm">' + escape(data.nama) + '</div>' +
                                        '<div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">' + escape(data.nrp) + ' • ' + escape(data.bbm_label) + ' • ' + escape(data.saldo_label) + '</div>' +
                                   '</div>';
                        },
                        item: function(data, escape) {
                            return '<div>' + escape(data.nama) + ' (' + escape(data.nrp) + ')</div>';
                        }
                    }
                });

                const kendaraans = @json($kendaraans ?? []);
                const personels = @json($personels ?? []);

                function updatePersonels(value) {
                    personelTs.clear();
                    personelTs.clearOptions();

                    const infoLabel = document.getElementById('filter_info');
                    const bbmLabel = document.getElementById('filter_bbm_label');

                    if (!value) {
                        if (infoLabel) infoLabel.classList.add('hidden');
                        return;
                    }

                    const selectedKendaraan = kendaraans.find(k => k.id == value);
                    const requiredBbm = selectedKendaraan ? (selectedKendaraan.jenis_bbm || '').toUpperCase() : null;

                    if (requiredBbm) {
                        if (infoLabel) {
                            infoLabel.classList.remove('hidden');
                            bbmLabel.textContent = selectedKendaraan.jenis_bbm;
                        }

                        // Case-insensitive filtering
                        const filteredPersonels = personels.filter(p => {
                            if (!p.jenis_bbm) return true; // Show those with no BBM set
                            return p.jenis_bbm.toUpperCase() === requiredBbm;
                        });

                        const options = filteredPersonels.map(p => ({
                            id: p.id,
                            nama: p.nama,
                            nrp: p.nrp,
                            bbm_label: p.jenis_bbm || 'BELUM SET BBM',
                            saldo_label: Number(p.saldo).toLocaleString('id-ID') + ' L',
                            text: `${p.nama} (${p.nrp}) ${p.jenis_bbm ? ' - ' + p.jenis_bbm : ' - Belum set BBM'} - ${Number(p.saldo).toLocaleString('id-ID')} L`
                        }));

                        personelTs.addOptions(options);
                        personelTs.refreshOptions(false);
                    }
                }

                if (kendaraanTs && personelTs) {
                    kendaraanTs.on('change', function (value) {
                        updatePersonels(value);
                    });

                    // Initial trigger
                    if (kendaraanTs.getValue()) {
                        updatePersonels(kendaraanTs.getValue());
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>