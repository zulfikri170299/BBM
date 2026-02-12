<x-app-layout>
    <div class="p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Data Kendaraan</h1>
                <p class="mt-1 text-slate-500">Semua kendaraan dari seluruh Satuan Kerja.</p>
            </div>
            <button @click="$dispatch('open-topup-select')" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 text-white rounded-xl font-semibold text-sm hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 transition-all duration-200 hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Top Up Saldo
            </button>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl" x-data="{ show: true }" x-show="show" x-transition>
                <div class="flex-shrink-0 p-1.5 bg-emerald-100 rounded-full">
                    <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                </div>
                <p class="text-sm font-medium text-emerald-700 flex-1">{{ session('success') }}</p>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        <!-- Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
            <!-- Table Header Info -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">Daftar Kendaraan</h3>
                        <p class="text-xs text-slate-400">{{ $kendaraans->total() }} kendaraan dari seluruh satker</p>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-slate-50/70">
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Satker</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis Kendaraan</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nopol</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis BBM</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Saldo</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">PIN</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kendaraans as $kendaraan)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-semibold text-slate-500">{{ $loop->iteration + ($kendaraans->currentPage() - 1) * $kendaraans->perPage() }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                        {{ strtoupper(substr($kendaraan->satker->nama_satker ?? '-', 0, 2)) }}
                                    </div>
                                    <span class="text-sm font-medium text-slate-700">{{ $kendaraan->satker->nama_satker ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-slate-800">{{ $kendaraan->jenis_kendaraan }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-slate-800">{{ $kendaraan->no_polisi }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $bbmColors = [
                                        'Pertalite' => 'bg-green-100 text-green-700',
                                        'Pertamax' => 'bg-blue-100 text-blue-700',
                                        'Solar' => 'bg-amber-100 text-amber-700',
                                        'Dexlite' => 'bg-purple-100 text-purple-700',
                                    ];
                                    $color = $bbmColors[$kendaraan->jenis_bbm] ?? 'bg-slate-100 text-slate-700';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $color }}">
                                    {{ $kendaraan->jenis_bbm }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-sm font-bold {{ $kendaraan->saldo < 10 ? 'text-red-600' : 'text-slate-800' }}">
                                    {{ number_format($kendaraan->saldo, 1, ',', '.') }} Liter
                                </span>
                                @if($kendaraan->saldo < 10)
                                    <span class="block text-xs text-red-500 font-medium mt-0.5">Saldo rendah</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <code class="px-2.5 py-1 bg-amber-50 text-amber-700 rounded-lg text-xs font-mono font-bold tracking-widest">{{ $kendaraan->pin }}</code>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button @click="$dispatch('open-topup', { id: {{ $kendaraan->id }}, nopol: '{{ $kendaraan->no_polisi }}', saldo: '{{ number_format($kendaraan->saldo, 1, ',', '.') }}' })" class="inline-flex items-center p-2 bg-slate-100 hover:bg-emerald-100 text-slate-500 hover:text-emerald-600 rounded-lg transition-colors" title="Top Up Saldo">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    </button>
                                    <a href="{{ route('admin.kendaraans.edit', $kendaraan) }}" class="inline-flex items-center p-2 bg-slate-100 hover:bg-amber-100 text-slate-500 hover:text-amber-600 rounded-lg transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.kendaraans.destroy', $kendaraan) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Yakin ingin menghapus kendaraan ini?')" class="inline-flex items-center p-2 bg-slate-100 hover:bg-red-100 text-slate-500 hover:text-red-600 rounded-lg transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <p class="text-slate-500 font-medium">Belum ada kendaraan terdaftar</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($kendaraans->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $kendaraans->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Top Up Modal -->
    <div x-data="{
            showTopup: false,
            topupId: null,
            topupNopol: '',
            topupSaldo: '',
            jumlah: '',
            selectMode: false,
            kendaraans: [
                @foreach($kendaraans as $k)
                { id: {{ $k->id }}, nopol: '{{ $k->no_polisi }}', saldo: '{{ number_format($k->saldo, 1, ',', '.') }}', saldoRaw: {{ $k->saldo }} },
                @endforeach
            ],
            selectKendaraan(id) {
                const k = this.kendaraans.find(x => x.id == id);
                if (k) {
                    this.topupId = k.id;
                    this.topupNopol = k.nopol;
                    this.topupSaldo = k.saldo;
                }
            }
        }"
        @open-topup.window="topupId = $event.detail.id; topupNopol = $event.detail.nopol; topupSaldo = $event.detail.saldo; jumlah = ''; selectMode = false; showTopup = true"
        @open-topup-select.window="topupId = null; topupNopol = ''; topupSaldo = ''; jumlah = ''; selectMode = true; showTopup = true"
    >
        <!-- Backdrop -->
        <div x-show="showTopup" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50" @click="showTopup = false"></div>

        <!-- Modal -->
        <div x-show="showTopup" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="showTopup = false">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden" @click.stop>
                <!-- Modal Header -->
                <div class="px-6 py-5 bg-gradient-to-r from-emerald-500 to-green-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-white/20 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Top Up Saldo</h3>
                                <p class="text-sm text-emerald-100" x-text="topupNopol || 'Pilih kendaraan'"></p>
                            </div>
                        </div>
                        <button @click="showTopup = false" class="p-1 text-white/70 hover:text-white rounded-lg hover:bg-white/10 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <form :action="'/admin/kendaraans/' + topupId + '/topup'" method="POST" class="p-6 space-y-5">
                    @csrf

                    <!-- Select Kendaraan (only when opened from header button) -->
                    <div x-show="selectMode">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Kendaraan</label>
                        <select @change="selectKendaraan($event.target.value)" class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all">
                            <option value="">-- Pilih Kendaraan --</option>
                            @foreach($kendaraans as $k)
                                <option value="{{ $k->id }}">{{ $k->no_polisi }} — {{ $k->jenis_kendaraan }} ({{ number_format($k->saldo, 1, ',', '.') }} L)</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Current Saldo -->
                    <div x-show="topupId" class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-medium">Saldo Saat Ini</p>
                            <p class="text-lg font-bold text-slate-800"><span x-text="topupSaldo"></span> Liter</p>
                        </div>
                    </div>

                    <!-- Jumlah Input -->
                    <div x-show="topupId">
                        <label for="jumlah" class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Top Up (Liter)</label>
                        <div class="relative">
                            <input type="number" name="jumlah" id="jumlah" x-model="jumlah" step="0.1" min="0.1" max="10000" required placeholder="Masukkan jumlah liter" class="w-full px-4 py-3 pr-16 bg-white border-2 border-slate-200 rounded-xl text-lg font-semibold text-slate-800 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all placeholder:text-slate-300 placeholder:font-normal">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <span class="text-sm font-bold text-slate-400">Liter</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Amount Buttons -->
                    <div x-show="topupId">
                        <p class="text-xs text-slate-400 font-medium mb-2">Pilihan Cepat</p>
                        <div class="grid grid-cols-4 gap-2">
                            <button type="button" @click="jumlah = 5" class="py-2 text-sm font-semibold rounded-lg border-2 transition-all" :class="jumlah == 5 ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-slate-200 text-slate-600 hover:border-emerald-300 hover:bg-emerald-50'">5 L</button>
                            <button type="button" @click="jumlah = 10" class="py-2 text-sm font-semibold rounded-lg border-2 transition-all" :class="jumlah == 10 ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-slate-200 text-slate-600 hover:border-emerald-300 hover:bg-emerald-50'">10 L</button>
                            <button type="button" @click="jumlah = 20" class="py-2 text-sm font-semibold rounded-lg border-2 transition-all" :class="jumlah == 20 ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-slate-200 text-slate-600 hover:border-emerald-300 hover:bg-emerald-50'">20 L</button>
                            <button type="button" @click="jumlah = 50" class="py-2 text-sm font-semibold rounded-lg border-2 transition-all" :class="jumlah == 50 ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-slate-200 text-slate-600 hover:border-emerald-300 hover:bg-emerald-50'">50 L</button>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="showTopup = false" class="flex-1 px-4 py-3 bg-slate-100 text-slate-600 font-semibold rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                        <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold rounded-xl hover:from-emerald-600 hover:to-green-700 shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/40 transition-all hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed" :disabled="!topupId || !jumlah || jumlah <= 0">
                            ⛽ Top Up Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
