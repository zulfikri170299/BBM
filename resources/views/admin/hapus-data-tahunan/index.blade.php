<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-6">
        {{-- Page Header --}}
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white flex items-center gap-3">
                <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Hapus Data Tahunan
            </h1>
            <p class="mt-1 text-slate-600 dark:text-slate-400">Hapus data lama untuk mengoptimalkan ruang penyimpanan. <span class="font-semibold text-amber-500">Hanya data tahun {{ $currentYear - 2 }} ke bawah yang dapat dihapus.</span></p>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 8000)"
                class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-700 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-xl relative flex items-start gap-3" role="alert">
                <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-sm">{!! session('success') !!}</span>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-xl relative flex items-start gap-3" role="alert">
                <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-sm">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Warning Banner --}}
        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700/50 rounded-2xl p-4 flex items-start gap-3">
            <svg class="w-6 h-6 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div>
                <h3 class="font-semibold text-amber-800 dark:text-amber-300">Peringatan Penting</h3>
                <ul class="mt-1 text-sm text-amber-700 dark:text-amber-400 space-y-1 list-disc list-inside">
                    <li>Data yang dihapus <strong>tidak dapat dikembalikan</strong>.</li>
                    <li>Pastikan Anda sudah melakukan <strong>Backup Database</strong> sebelum menghapus data.</li>
                    <li>Hanya data dari tahun <strong>{{ $currentYear - 2 }}</strong> ke bawah yang diizinkan untuk dihapus.</li>
                </ul>
            </div>
        </div>

        {{-- Yearly Data Cards --}}
        @if(count($yearStats) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($yearStats as $year => $stats)
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden" x-data="{ expanded: false }">
                        {{-- Card Header --}}
                        <div class="p-5 flex items-center justify-between cursor-pointer" @click="expanded = !expanded">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-900/30 flex items-center justify-center">
                                    <span class="text-lg font-black text-red-600 dark:text-red-400">{{ $year }}</span>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800 dark:text-white">Data Tahun {{ $year }}</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        <span class="font-semibold text-red-500">{{ number_format($stats['total']) }}</span> total data
                                    </p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-slate-400 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>

                        {{-- Expanded Details --}}
                        <div x-show="expanded" x-collapse style="display: none;">
                            <div class="px-5 pb-4 border-t border-slate-100 dark:border-white/5 pt-3">
                                <div class="space-y-2 text-xs">
                                    @php
                                        $items = [
                                            ['Transaksi BBM', $stats['transaksi_bbm']],
                                            ['Riwayat Top Up', $stats['riwayat_topup']],
                                            ['Riwayat Stok Admin', $stats['riwayat_stok_admin']],
                                            ['Pembelian BBM', $stats['pembelian_bbm']],
                                            ['Berita Acara', $stats['ba_logs']],
                                            ['Rendis BBM', $stats['rendis_bbm']],
                                            ['Rendis Kendaraan', $stats['rendis_kendaraan']],
                                            ['Hutang BBM', $stats['hutang']],
                                            ['Sinkronisasi', $stats['sinkronisasi']],
                                            ['Sounding', $stats['sounding']],
                                            ['Meter Harian', $stats['daily_meter']],
                                            ['Transfer Saldo', $stats['transfer_saldo']],
                                            ['Transfer Antar Personel', $stats['transfer_antar_personel']],
                                            ['Log Aktivitas', $stats['log_aktivitas']],
                                            ['Indeks Kepuasan', $stats['satisfaction_index']],
                                            ['Catatan', $stats['catatan']],
                                            ['Chat / Konsultasi', $stats['chat']],
                                            ['Notifikasi', $stats['notifikasi']],
                                        ];
                                    @endphp
                                    @foreach($items as $item)
                                        @if($item[1] > 0)
                                        <div class="flex items-center justify-between py-1.5 px-3 rounded-lg {{ $loop->even ? 'bg-slate-50 dark:bg-slate-800/50' : '' }}">
                                            <span class="text-slate-600 dark:text-slate-400">{{ $item[0] }}</span>
                                            <span class="font-bold text-slate-800 dark:text-white">{{ number_format($item[1]) }}</span>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>

                                {{-- Delete Button --}}
                                <button type="button"
                                    onclick="openDeleteModal({{ $year }}, {{ $stats['total'] }})"
                                    class="mt-4 w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-all duration-200 active:scale-[0.98] shadow-sm shadow-red-500/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus Data Tahun {{ $year }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm p-12 text-center">
                <svg class="w-16 h-16 text-slate-300 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Tidak Ada Data Lama</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Tidak ditemukan data dari tahun {{ $currentYear - 2 }} ke bawah yang bisa dihapus.</p>
            </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <div class="relative bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-2xl w-full max-w-md p-6 space-y-5 transform transition-all">
            {{-- Header --}}
            <div class="text-center">
                <div class="w-16 h-16 mx-auto bg-red-100 dark:bg-red-900/40 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Konfirmasi Hapus Data</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Anda akan menghapus <span id="modalTotalData" class="font-bold text-red-500"></span> data dari tahun <span id="modalYear" class="font-bold text-red-500"></span>.
                </p>
                <p class="mt-1 text-xs text-red-500 font-semibold">Data yang sudah dihapus tidak dapat dikembalikan!</p>
            </div>

            {{-- Form --}}
            <form id="deleteForm" method="POST" action="{{ route('admin.hapus-data-tahunan.destroy') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="tahun" id="modalTahunInput">

                <div class="space-y-4">
                    {{-- PIN Input --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">PIN Top Up</label>
                        <input type="password" name="pin" required placeholder="Masukkan PIN Top Up Anda"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-white/10 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm">
                    </div>

                    {{-- Confirmation Input --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Ketik <span class="font-bold text-red-500">HAPUS</span> untuk konfirmasi</label>
                        <input type="text" name="konfirmasi" required placeholder="Ketik HAPUS" autocomplete="off"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-white/10 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm uppercase tracking-widest font-bold">
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-5">
                    <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 px-4 py-2.5 border border-slate-300 dark:border-white/10 text-slate-700 dark:text-slate-300 text-sm font-semibold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm shadow-red-500/20 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus Permanen
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openDeleteModal(year, totalData) {
            document.getElementById('modalYear').textContent = year;
            document.getElementById('modalTotalData').textContent = new Intl.NumberFormat('id-ID').format(totalData);
            document.getElementById('modalTahunInput').value = year;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.style.overflow = '';
            // Reset form
            document.getElementById('deleteForm').reset();
        }

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeDeleteModal();
        });
    </script>
    @endpush
</x-app-layout>
