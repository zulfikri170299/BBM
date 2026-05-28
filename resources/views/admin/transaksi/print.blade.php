<x-app-layout>
    <div class="p-6 lg:p-8">
        <div class="max-w-md mx-auto space-y-6">
            <!-- Success Icon -->
            <div class="text-center">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full shadow-lg shadow-emerald-500/30 mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-slate-900">Pengisian Berhasil!</h1>
                <p class="text-sm text-slate-500 mt-1">Berikut adalah bukti transaksi pengisian BBM</p>
            </div>

            <!-- Struk Card -->
            <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden" id="struk-area">
                <!-- Header Struk -->
                <div class="px-6 py-5 bg-gradient-to-r from-slate-800 to-slate-900 text-center">
                    <h2 class="text-white font-bold text-lg tracking-wide">SPBP</h2>
                    <p class="text-slate-300 text-xs mt-0.5">Bukti Transaksi Pengisian BBM</p>
                </div>

                <div class="p-6 space-y-4">
                    <!-- Info Transaksi -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Tanggal</p>
                            <p class="text-sm font-semibold text-slate-800">
                                {{ \Carbon\Carbon::parse($transaksi->tanggal)->setTimezone('Asia/Makassar')->format('d M Y') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Waktu</p>
                            <p class="text-sm font-semibold text-slate-800">
                                {{ \Carbon\Carbon::parse($transaksi->tanggal)->setTimezone('Asia/Makassar')->format('H:i') }}
                                WITA</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Petugas</p>
                            <p class="text-sm font-semibold text-slate-700">{{ $transaksi->petugas->name ?? '-' }} (Admin)</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">ID Transaksi</p>
                            <p class="text-sm font-mono font-bold text-slate-800">
                                #{{ str_pad($transaksi->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-slate-200"></div>

                    <!-- Info Unit (Kendaraan/Personel) -->
                    @php
                        $target = $transaksi->kendaraan ?? $transaksi->personel;
                        $isKendaraan = isset($transaksi->kendaraan);
                    @endphp
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">
                                {{ $isKendaraan ? 'No Polisi' : 'NRP' }}</p>
                            <p class="text-sm font-bold text-slate-800">
                                {{ $isKendaraan ? $target->no_polisi : $target->nrp }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Satker</p>
                            <p class="text-sm font-semibold text-slate-700">{{ $target->satker->nama_satker ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">
                                {{ $isKendaraan ? 'Jenis Kendaraan' : 'Nama' }}</p>
                            <p class="text-sm font-semibold text-slate-700">
                                {{ $isKendaraan ? $target->jenis_kendaraan : $target->nama }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Jenis BBM</p>
                            <p class="text-sm font-bold text-slate-800">{{ $target->jenis_bbm }}</p>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-slate-200"></div>

                    <!-- Detail Pengisian -->
                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-500">Jumlah Pengisian</span>
                            <span
                                class="text-sm font-bold text-slate-800">{{ number_format($transaksi->liter, 0, ',', '.') }}
                                Liter</span>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-slate-200"></div>

                    <!-- Sisa Saldo -->
                    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-center">
                        <p class="text-xs text-emerald-600 font-medium">Sisa Saldo
                            {{ $isKendaraan ? 'Kendaraan' : 'Personel' }}</p>
                        <p class="text-xl font-bold text-emerald-700 mt-0.5">
                            {{ number_format($target->saldo, 0, ',', '.') }} Liter</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-3 bg-slate-50 text-center border-t border-slate-100">
                    <p class="text-xs text-slate-400">Terima Kasih — SIMAK BBM</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.transaksi.index') }}"
                    class="flex-1 text-center px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-semibold text-sm hover:bg-slate-200 transition-colors">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                        </path>
                    </svg>
                    Scan Lagi
                </a>
                <button onclick="window.print()"
                    class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-xl transition-all text-sm">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Cetak Struk
                </button>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #struk-area,
            #struk-area * {
                visibility: visible;
            }

            #struk-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 80mm;
            }
        }
    </style>
</x-app-layout>
