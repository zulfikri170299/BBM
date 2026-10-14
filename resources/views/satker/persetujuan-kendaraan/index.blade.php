<x-app-layout>
    <div class="py-6 px-2 sm:px-6 lg:px-8 min-h-screen" x-data="{
        showAlasan: false,
        alasanModal: '',
        nopolModal: '',
        openAlasanModal(nopol, alasan) {
            this.nopolModal = nopol;
            this.alasanModal = alasan;
            this.showAlasan = true;
        }
    }">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Header Card -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-5 rounded-2xl shadow-sm">
                <div>
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-brand-primary/10 text-brand-primary dark:bg-white/10 dark:text-white rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white">Persetujuan Kendaraan</h1>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Monitoring status pengajuan kendaraan (Tambah, Edit, Import) dari {{ auth()->user()->satker->nama_satker ?? 'Satker Anda' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Stats Quick Cards -->
                <div class="flex items-center gap-2 self-stretch sm:self-auto overflow-x-auto pb-1 sm:pb-0">
                    <div class="px-3.5 py-2 bg-amber-500/10 border border-amber-500/20 rounded-xl text-center flex-1 sm:flex-initial min-w-[90px]">
                        <p class="text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider">Menunggu</p>
                        <p class="text-lg font-black text-amber-600 dark:text-amber-400 leading-none mt-0.5">{{ number_format($counts['pending'] ?? 0) }}</p>
                    </div>
                    <div class="px-3.5 py-2 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-center flex-1 sm:flex-initial min-w-[90px]">
                        <p class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Disetujui</p>
                        <p class="text-lg font-black text-emerald-600 dark:text-emerald-400 leading-none mt-0.5">{{ number_format($counts['disetujui'] ?? 0) }}</p>
                    </div>
                    <div class="px-3.5 py-2 bg-rose-500/10 border border-rose-500/20 rounded-xl text-center flex-1 sm:flex-initial min-w-[90px]">
                        <p class="text-[10px] font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider">Ditolak</p>
                        <p class="text-lg font-black text-rose-600 dark:text-rose-400 leading-none mt-0.5">{{ number_format($counts['ditolak'] ?? 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- Flash Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Tab Status Navigation -->
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 dark:border-white/10 pb-3">
                <div class="flex items-center gap-1.5 p-1 bg-slate-100 dark:bg-slate-800/60 rounded-xl overflow-x-auto text-xs">
                    <a href="{{ route('satker.persetujuan-kendaraan.index', array_merge(request()->except(['page']), ['status' => 'pending'])) }}"
                        class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap {{ $status === 'pending' ? 'bg-white dark:bg-slate-900 text-amber-600 dark:text-amber-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                        <span>Menunggu Persetujuan</span>
                        @if(($counts['pending'] ?? 0) > 0)
                            <span class="px-1.5 py-0.2 bg-amber-500 text-white text-[10px] rounded-full font-black animate-pulse">{{ $counts['pending'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('satker.persetujuan-kendaraan.index', array_merge(request()->except(['page']), ['status' => 'disetujui'])) }}"
                        class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap {{ $status === 'disetujui' ? 'bg-white dark:bg-slate-900 text-emerald-600 dark:text-emerald-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                        <span>Disetujui</span>
                        <span class="text-[10px] opacity-75">({{ $counts['disetujui'] ?? 0 }})</span>
                    </a>
                    <a href="{{ route('satker.persetujuan-kendaraan.index', array_merge(request()->except(['page']), ['status' => 'ditolak'])) }}"
                        class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap {{ $status === 'ditolak' ? 'bg-white dark:bg-slate-900 text-rose-600 dark:text-rose-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                        <span>Ditolak</span>
                        @if(($counts['ditolak'] ?? 0) > 0)
                            <span class="px-1.5 py-0.2 bg-rose-600 text-white text-[10px] rounded-full font-black">({{ $counts['ditolak'] }})</span>
                        @else
                            <span class="text-[10px] opacity-75">(0)</span>
                        @endif
                    </a>
                    <a href="{{ route('satker.persetujuan-kendaraan.index', array_merge(request()->except(['page']), ['status' => 'all'])) }}"
                        class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $status === 'all' ? 'bg-white dark:bg-slate-900 text-brand-primary dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                        Semua Pengajuan
                    </a>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('satker.kendaraans.index') }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-lg transition inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Ke Tabel Kendaraan
                    </a>
                </div>
            </div>

            <!-- Filter Toolbar -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-3 sm:p-4 rounded-2xl shadow-sm"
                x-data="{
                    aksiOpen: false,
                    selectedAksi: '{{ $aksi ?? '' }}',
                    selectedAksiLabel: '{{ empty($aksi) ? 'Semua Aksi' : ($aksi === 'tambah' ? 'Tambah Baru' : ($aksi === 'edit' ? 'Edit Data' : 'Import Excel')) }}'
                }">
                <form id="filterForm" action="{{ route('satker.persetujuan-kendaraan.index') }}" method="GET" class="flex flex-col lg:flex-row items-stretch lg:items-center gap-2.5">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="hidden" name="aksi" :value="selectedAksi">

                    <!-- Custom Dropdown: Filter Aksi -->
                    <div class="relative w-full lg:w-48 flex-shrink-0" @click.outside="aksiOpen = false">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1 lg:hidden">Aksi</label>
                        <button type="button" @click="aksiOpen = !aksiOpen"
                            class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-white/10 rounded-xl text-left text-xs font-semibold text-slate-800 dark:text-slate-200 flex items-center justify-between gap-2 transition focus:outline-none focus:ring-2 focus:ring-brand-primary">
                            <span class="truncate" x-text="selectedAksiLabel"></span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 flex-shrink-0" :class="aksiOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="aksiOpen" x-transition.origin.top.duration.150ms
                            class="absolute z-50 left-0 right-0 mt-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl shadow-xl py-1 text-xs" style="display: none;">
                            <button type="button" @click="selectedAksi = ''; selectedAksiLabel = 'Semua Aksi'; aksiOpen = false; $nextTick(() => $el.closest('form').submit());"
                                class="w-full px-3 py-2 text-left hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold" :class="selectedAksi === '' ? 'text-brand-primary font-bold bg-brand-primary/5' : 'text-slate-700 dark:text-slate-300'">
                                Semua Aksi
                            </button>
                            <button type="button" @click="selectedAksi = 'tambah'; selectedAksiLabel = 'Tambah Baru'; aksiOpen = false; $nextTick(() => $el.closest('form').submit());"
                                class="w-full px-3 py-2 text-left hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold" :class="selectedAksi === 'tambah' ? 'text-brand-primary font-bold bg-brand-primary/5' : 'text-slate-700 dark:text-slate-300'">
                                Tambah Baru
                            </button>
                            <button type="button" @click="selectedAksi = 'edit'; selectedAksiLabel = 'Edit Data'; aksiOpen = false; $nextTick(() => $el.closest('form').submit());"
                                class="w-full px-3 py-2 text-left hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold" :class="selectedAksi === 'edit' ? 'text-brand-primary font-bold bg-brand-primary/5' : 'text-slate-700 dark:text-slate-300'">
                                Edit Data
                            </button>
                            <button type="button" @click="selectedAksi = 'import'; selectedAksiLabel = 'Import Excel'; aksiOpen = false; $nextTick(() => $el.closest('form').submit());"
                                class="w-full px-3 py-2 text-left hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold" :class="selectedAksi === 'import' ? 'text-brand-primary font-bold bg-brand-primary/5' : 'text-slate-700 dark:text-slate-300'">
                                Import Excel
                            </button>
                        </div>
                    </div>

                    <!-- Search Input -->
                    <div class="relative flex-1">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1 lg:hidden">Pencarian</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nopol, jenis kendaraan..."
                                class="w-full h-10 pl-9 pr-3 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-medium text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-primary">
                        </div>
                    </div>

                    <!-- Submit & Reset Buttons -->
                    <div class="flex items-center gap-2 self-end lg:self-auto flex-shrink-0">
                        <button type="submit" class="h-10 px-4 bg-brand-primary text-white rounded-xl text-xs font-bold hover:bg-brand-primary/90 transition shadow-sm flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            <span>Filter</span>
                        </button>
                        @if($search || $aksi)
                            <a href="{{ route('satker.persetujuan-kendaraan.index', ['status' => $status]) }}" class="h-10 px-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-bold transition flex items-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-white/5 text-left text-xs whitespace-nowrap">
                        <thead class="bg-slate-50 dark:bg-slate-800/70 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                            <tr>
                                <th class="px-4 py-3.5 text-center w-12 whitespace-nowrap">No</th>
                                <th class="px-4 py-3.5 whitespace-nowrap">Kendaraan</th>
                                <th class="px-4 py-3.5 whitespace-nowrap">Nopol</th>
                                <th class="px-4 py-3.5 text-center whitespace-nowrap">BBM / Roda</th>
                                <th class="px-4 py-3.5 text-center whitespace-nowrap">Aksi Pengajuan</th>
                                <th class="px-4 py-3.5">Perubahan / Catatan</th>
                                <th class="px-4 py-3.5 text-center whitespace-nowrap">Status</th>
                                <th class="px-4 py-3.5 text-center whitespace-nowrap">Tanggal</th>
                                <th class="px-4 py-3.5 text-center w-28 whitespace-nowrap">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-white/5 text-slate-700 dark:text-slate-300">
                            @forelse($kendaraans as $kendaraan)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                    <td class="px-4 py-3 text-center font-bold text-slate-400 whitespace-nowrap">
                                        {{ $loop->iteration + ($kendaraans->currentPage() - 1) * $kendaraans->perPage() }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $kendaraan->jenis_kendaraan }}</div>
                                        <div class="text-[10px] text-slate-400">Kode: {{ $kendaraan->kode_kendaraan ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="inline-block font-mono font-bold text-xs px-2.5 py-1 rounded bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-white/10 text-slate-800 dark:text-slate-200 whitespace-nowrap">
                                            {{ $kendaraan->no_polisi }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        <div class="font-semibold">{{ $kendaraan->jenis_bbm }}</div>
                                        <div class="text-[10px] text-slate-400">Roda {{ $kendaraan->roda ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($kendaraan->aksi_approval === 'edit')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                                Edit Data
                                            </span>
                                        @elseif($kendaraan->aksi_approval === 'import')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                                                Import Excel
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300">
                                                Tambah Baru
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 max-w-sm">
                                        @if($kendaraan->aksi_approval === 'edit' && !empty($kendaraan->pending_changes))
                                            @php
                                                $changes = is_array($kendaraan->pending_changes) 
                                                    ? $kendaraan->pending_changes 
                                                    : json_decode($kendaraan->pending_changes, true);
                                                
                                                $diffs = [];
                                                if (is_array($changes)) {
                                                    if (isset($changes['no_polisi']) && trim((string)$changes['no_polisi']) !== trim((string)$kendaraan->no_polisi)) {
                                                        $diffs[] = [
                                                            'label' => 'Nopol',
                                                            'old' => $kendaraan->no_polisi,
                                                            'new' => $changes['no_polisi']
                                                        ];
                                                    }
                                                    if (isset($changes['jenis_kendaraan']) && trim((string)$changes['jenis_kendaraan']) !== trim((string)$kendaraan->jenis_kendaraan)) {
                                                        $diffs[] = [
                                                            'label' => 'Jenis',
                                                            'old' => $kendaraan->jenis_kendaraan,
                                                            'new' => $changes['jenis_kendaraan']
                                                        ];
                                                    }
                                                    if (isset($changes['jenis_bbm']) && trim((string)$changes['jenis_bbm']) !== trim((string)$kendaraan->jenis_bbm)) {
                                                        $diffs[] = [
                                                            'label' => 'BBM',
                                                            'old' => $kendaraan->jenis_bbm,
                                                            'new' => $changes['jenis_bbm']
                                                        ];
                                                    }
                                                    if (isset($changes['roda']) && trim((string)$changes['roda']) !== trim((string)$kendaraan->roda)) {
                                                        $diffs[] = [
                                                            'label' => 'Roda',
                                                            'old' => 'Roda ' . ($kendaraan->roda ?: '-'),
                                                            'new' => 'Roda ' . $changes['roda']
                                                        ];
                                                    }
                                                    if (isset($changes['cc']) && trim((string)$changes['cc']) !== trim((string)$kendaraan->cc)) {
                                                        $diffs[] = [
                                                            'label' => 'CC',
                                                            'old' => ($kendaraan->cc ?: '-') . ' cc',
                                                            'new' => $changes['cc'] . ' cc'
                                                        ];
                                                    }
                                                }
                                            @endphp
                                            <div class="space-y-1">
                                                <div class="text-[9px] font-black uppercase tracking-wider text-amber-600 dark:text-amber-400 flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    Perubahan Diajukan:
                                                </div>
                                                @if(!empty($diffs))
                                                    <div class="flex flex-col gap-1 text-[11px]">
                                                        @foreach($diffs as $diff)
                                                            <div class="inline-flex items-center gap-1.5 px-2 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-white/10 w-fit">
                                                                <span class="font-bold text-slate-500 dark:text-slate-400">{{ $diff['label'] }}:</span>
                                                                <span class="line-through text-rose-500 font-medium">{{ $diff['old'] }}</span>
                                                                <span class="text-slate-400 font-bold">➔</span>
                                                                <span class="text-emerald-600 dark:text-emerald-400 font-black">{{ $diff['new'] }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-slate-400 italic text-[11px]">Pengajuan pembaruan data</span>
                                                @endif
                                            </div>
                                        @elseif($kendaraan->aksi_approval === 'import')
                                            <span class="text-slate-400 italic text-[11px]">Penambahan unit via Import Excel</span>
                                        @else
                                            <span class="text-slate-400 italic text-[11px]">Pengajuan unit armada kendaraan baru</span>
                                        @endif

                                        @if($kendaraan->status_approval === 'ditolak' && $kendaraan->alasan_penolakan)
                                            <div class="mt-1.5 p-2 bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900/50 rounded-lg text-rose-700 dark:text-rose-300 text-[11px]">
                                                <strong>Alasan Ditolak:</strong> {{ $kendaraan->alasan_penolakan }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($kendaraan->status_approval === 'pending')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 border border-amber-200 dark:border-amber-800/50">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                Menunggu
                                            </span>
                                        @elseif($kendaraan->status_approval === 'disetujui')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/50">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Disetujui
                                            </span>
                                        @elseif($kendaraan->status_approval === 'ditolak')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300 border border-rose-200 dark:border-rose-800/50">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center text-[11px] text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                        {{ ($kendaraan->updated_at ?: $kendaraan->created_at) ? ($kendaraan->updated_at ?: $kendaraan->created_at)->timezone('Asia/Makassar')->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            @if($kendaraan->status_approval === 'ditolak')
                                                @if(\App\Models\Setting::where('key', 'satker_can_edit_kendaraan')->value('value') ?? 1)
                                                    <a href="{{ route('satker.kendaraans.edit', $kendaraan) }}"
                                                        class="p-1.5 bg-amber-100 hover:bg-amber-200 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 rounded-lg transition"
                                                        title="Perbaiki & Ajukan Ulang">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    </a>
                                                @endif
                                                <form action="{{ route('satker.persetujuan-kendaraan.destroy', $kendaraan) }}" method="POST" onsubmit="event.preventDefault(); window.confirmDialog({
    title: 'Hapus Pengajuan?',
    message: 'Apakah Anda yakin ingin membatalkan dan menghapus pengajuan kendaraan ini?',
    type: 'danger',
    confirmText: 'Ya, Hapus!',
    cancelText: 'Batal'
}, () => this.submit());">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 bg-rose-100 hover:bg-rose-200 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300 rounded-lg transition" title="Hapus Pengajuan">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            @elseif($kendaraan->status_approval === 'pending')
                                                <form action="{{ route('satker.persetujuan-kendaraan.destroy', $kendaraan) }}" method="POST" onsubmit="event.preventDefault(); window.confirmDialog({
    title: 'Batalkan Pengajuan?',
    message: 'Apakah Anda yakin ingin membatalkan pengajuan kendaraan ini?',
    type: 'danger',
    confirmText: 'Ya, Batalkan!',
    cancelText: 'Kembali'
}, () => this.submit());">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 bg-rose-100 hover:bg-rose-200 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300 rounded-lg transition" title="Batalkan Pengajuan">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </form>
                                            @elseif($kendaraan->status_approval === 'disetujui')
                                                <a href="{{ route('satker.kendaraans.index', ['search' => $kendaraan->no_polisi]) }}"
                                                    class="p-1.5 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 rounded-lg transition"
                                                    title="Lihat di Tabel Kendaraan">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center text-slate-400 mb-3">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <p class="text-sm font-bold text-slate-600 dark:text-slate-300">Tidak ada data pengajuan kendaraan</p>
                                            <p class="text-xs text-slate-400 mt-1">
                                                @if($status === 'pending')
                                                    Saat ini tidak ada pengajuan kendaraan yang sedang menunggu persetujuan.
                                                @elseif($status === 'ditolak')
                                                    Tidak ada pengajuan kendaraan yang ditolak.
                                                @else
                                                    Belum ada riwayat pengajuan kendaraan yang ditemukan.
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($kendaraans->hasPages())
                    <div class="px-4 py-3 border-t border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-800/40">
                        {{ $kendaraans->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
