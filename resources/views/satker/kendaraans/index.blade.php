<x-app-layout>
    <div class="p-6 lg:p-8 space-y-6" x-data="{ 
        showTransferModal: false, 
        showMonthlyReportModal: false,
        selectedBbm: '',
        personels: {{ json_encode($personels) }}
    }">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Kendaraan</h1>
                <p class="mt-1 text-slate-500">Kelola armada kendaraan {{ Auth::user()->satker->nama_satker ?? '' }}</p>
            </div>
            <div class="flex gap-2">
                <button @click="showMonthlyReportModal = true" class="inline-flex items-center px-5 py-2.5 bg-rose-600 text-white rounded-xl font-semibold text-sm hover:bg-rose-700 shadow-lg shadow-rose-500/30 transition-all duration-200 hover:-translate-y-0.5 group">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Laporan Bulanan
                </button>
                <a href="{{ route('satker.kendaraans.laporan-transfer') }}" class="inline-flex items-center px-5 py-2.5 bg-amber-500 text-white rounded-xl font-semibold text-sm hover:bg-amber-600 shadow-lg shadow-amber-500/30 transition-all duration-200 hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Laporan Transfer
                </a>
                <button @click="showTransferModal = true" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 text-white rounded-xl font-semibold text-sm hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 transition-all duration-200 hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    Transfer Saldo
                </button>
                @if(\App\Models\Setting::where('key', 'satker_can_create_kendaraan')->value('value') ?? 1)
                <a href="{{ route('satker.kendaraans.create') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold text-sm hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Kendaraan
                </a>
                @endif
            </div>
        </div>

        <!-- Error Alert -->
        @if(session('error'))
            <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl mb-4" x-data="{ show: true }" x-show="show" x-transition>
                <div class="flex-shrink-0 p-1.5 bg-red-100 rounded-full mt-0.5">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-sm font-medium text-red-700 flex-1">{{ session('error') }}</p>
                <button @click="show = false" class="text-red-400 hover:text-red-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl mb-4">
                <div class="flex-shrink-0 p-1.5 bg-red-100 rounded-full mt-0.5">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-red-800">Terdapat beberapa kesalahan:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Success Alert -->
        @if(session('success'))
            <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl" x-data="{ show: true }" x-show="show" x-transition>
                <div class="flex-shrink-0 p-1.5 bg-emerald-100 rounded-full mt-0.5">
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
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">Daftar Kendaraan</h3>
                        <p class="text-xs text-slate-400">{{ $kendaraans->total() }} kendaraan terdaftar</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-slate-50/70">
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kode</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis Kendaraan</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nopol</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis BBM</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Saldo</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">PIN</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kendaraans as $kendaraan)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-semibold text-slate-500">{{ $loop->iteration + ($kendaraans->currentPage() - 1) * $kendaraans->perPage() }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <code class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-mono font-bold">{{ $kendaraan->kode_kendaraan ?? '-' }}</code>
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
                                        'Pertamax' => 'bg-blue-100 text-blue-700',
                                        'Pertamina Dex' => 'bg-emerald-100 text-emerald-700',
                                    ];
                                    $color = $bbmColors[$kendaraan->jenis_bbm] ?? 'bg-slate-100 text-slate-700';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $color }}">
                                    {{ $kendaraan->jenis_bbm }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-sm font-bold {{ $kendaraan->saldo < 10 ? 'text-red-600' : 'text-slate-800' }}">
                                    {{ number_format($kendaraan->saldo, 0, ',', '.') }} Liter
                                </span>
                                @if($kendaraan->saldo < 10)
                                    <span class="block text-xs text-red-500 font-medium mt-0.5">Saldo rendah</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <code class="px-2.5 py-1 bg-amber-50 text-amber-700 rounded-lg text-xs font-mono font-bold tracking-widest">{{ $kendaraan->pin }}</code>
                            </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('satker.kendaraans.print', $kendaraan) }}" target="_blank" class="p-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-600 hover:text-indigo-700 rounded-lg transition-colors group" title="Print Barcode">
                                            <img src="{{ asset('assets/images/qr-icon.png') }}" class="w-5 h-5 opacity-80 group-hover:opacity-100 transition-opacity" alt="QR Icon">
                                        </a>
                                        @if(\App\Models\Setting::where('key', 'satker_can_edit_kendaraan')->value('value') ?? 1)
                                        <a href="{{ route('satker.kendaraans.edit', $kendaraan) }}" class="p-2 bg-amber-100 hover:bg-amber-200 text-amber-600 hover:text-amber-700 rounded-lg transition-colors group" title="Edit Kendaraan">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <p class="text-slate-500 font-medium">Belum ada kendaraan terdaftar</p>
                                    <a href="{{ route('satker.kendaraans.create') }}" class="mt-3 text-sm font-semibold text-indigo-600 hover:text-indigo-500">Tambah kendaraan pertama →</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($kendaraans->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $kendaraans->links() }}
            </div>
            @endif
        </div>

        <!-- Transfer Modal -->
        <div x-show="showTransferModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 py-6">
                <!-- Backdrop -->
                <div x-show="showTransferModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showTransferModal = false"></div>

                <!-- Modal Panel -->
                <div x-show="showTransferModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto overflow-hidden">
                    <form action="{{ route('satker.kendaraans.transfer') }}" method="POST">
                        @csrf

                        <!-- Header with Gradient -->
                        <div class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 px-6 py-5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-white" id="modal-title">Transfer Saldo</h3>
                                        <p class="text-emerald-100 text-xs">Kendaraan → Personel</p>
                                    </div>
                                </div>
                                <button type="button" @click="showTransferModal = false" class="p-1.5 hover:bg-white/20 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Form Body -->
                        <div class="px-6 py-5 space-y-5">

                            <!-- Sumber Kendaraan -->
                            <div>
                                <label for="kendaraan_id" class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                                    <span class="flex items-center justify-center w-6 h-6 rounded-lg bg-blue-100 text-blue-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1"></path></svg>
                                    </span>
                                    Sumber Dana
                                </label>
                                <select name="kendaraan_id" id="kendaraan_id" required 
                                    @change="selectedBbm = $event.target.selectedOptions[0].dataset.bbm"
                                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all appearance-none cursor-pointer">
                                    <option value="" data-bbm="">— Pilih Kendaraan —</option>
                                    @foreach($availableKendaraans as $k)
                                        <option value="{{ $k->id }}" data-bbm="{{ $k->jenis_bbm }}">{{ $k->no_polisi }} • {{ $k->jenis_bbm }} • {{ number_format($k->saldo, 0, ',', '.') }} L</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Arrow Divider -->
                            <div class="flex items-center justify-center">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-b from-emerald-100 to-teal-100 border-2 border-emerald-200 shadow-sm">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                </div>
                            </div>

                            <!-- Tujuan Personel -->
                            <div>
                                <label for="personel_id" class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                                    <span class="flex items-center justify-center w-6 h-6 rounded-lg bg-purple-100 text-purple-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </span>
                                    Tujuan Personel
                                </label>
                                <select name="personel_id" id="personel_id" required class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all appearance-none cursor-pointer">
                                    <option value="">— Pilih Personel —</option>
                                    <template x-for="personel in personels.filter(p => !selectedBbm || !p.jenis_bbm || p.jenis_bbm === selectedBbm)">
                                        <option :value="personel.id" x-text="personel.nama + ' • ' + (personel.jenis_bbm ? personel.jenis_bbm : 'Belum set BBM')"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="h-px bg-slate-100"></div>

                            <!-- Jumlah Transfer -->
                            <div>
                                <label for="jumlah" class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                                    <span class="flex items-center justify-center w-6 h-6 rounded-lg bg-amber-100 text-amber-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    </span>
                                    Jumlah Transfer
                                </label>
                                <div class="relative">
                                    <input type="number" name="jumlah" id="jumlah" required step="0.01" min="0.1" class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all pr-16" placeholder="Masukkan jumlah">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                        <span class="text-xs font-bold text-slate-400 bg-slate-200/60 px-2 py-0.5 rounded-md">LITER</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Keterangan -->
                            <div>
                                <label for="keterangan" class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                                    <span class="flex items-center justify-center w-6 h-6 rounded-lg bg-slate-100 text-slate-500">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                    </span>
                                    Keterangan
                                    <span class="text-xs font-normal text-slate-400">(opsional)</span>
                                </label>
                                <input type="text" name="keterangan" id="keterangan" class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" placeholder="Catatan tambahan...">
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-6 py-4 bg-slate-50/80 border-t border-slate-100 flex flex-row-reverse gap-3">
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-sm font-bold rounded-xl hover:from-emerald-600 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-lg shadow-emerald-500/25 transition-all duration-200 hover:-translate-y-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Konfirmasi Transfer
                            </button>
                            <button type="button" @click="showTransferModal = false" class="inline-flex items-center px-5 py-2.5 bg-white text-slate-600 text-sm font-semibold rounded-xl border border-slate-200 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-300 transition-all duration-200">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Monthly Report Modal -->
        <div x-show="showMonthlyReportModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 py-6">
                <!-- Backdrop -->
                <div x-show="showMonthlyReportModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showMonthlyReportModal = false"></div>

                <!-- Modal Panel -->
                <div x-show="showMonthlyReportModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-auto overflow-hidden">
                    <form action="{{ route('satker.kendaraans.laporan-bulanan') }}" method="GET">
                        <!-- Header with Gradient -->
                        <div class="bg-gradient-to-r from-rose-500 via-rose-600 to-rose-700 px-6 py-5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-white">Laporan Bulanan</h3>
                                        <p class="text-rose-100 text-xs">Pilih Periode Laporan</p>
                                    </div>
                                </div>
                                <button type="button" @click="showMonthlyReportModal = false" class="p-1.5 hover:bg-white/20 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Form Body -->
                        <div class="px-6 py-5 space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Bulan</label>
                                <select name="bulan" class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500 transition-all appearance-none cursor-pointer">
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ now()->month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Tahun</label>
                                <select name="tahun" class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500 transition-all appearance-none cursor-pointer">
                                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                                        <option value="{{ $y }}" {{ now()->year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-6 py-4 bg-slate-50/80 border-t border-slate-100 flex flex-row-reverse gap-3">
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-rose-500 to-rose-600 text-white text-sm font-bold rounded-xl hover:from-rose-600 hover:to-rose-700 shadow-lg shadow-rose-500/25 transition-all duration-200 hover:-translate-y-0.5">
                                Buka Laporan
                            </button>
                            <button type="button" @click="showMonthlyReportModal = false" class="inline-flex items-center px-5 py-2.5 bg-white text-slate-600 text-sm font-semibold rounded-xl border border-slate-200 hover:bg-slate-50 transition-all duration-200">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
