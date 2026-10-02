<x-app-layout>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ showPinModal: false, pinValue: '', topupAction: '', topupBulan: '' }">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Rencana Distribusi (Rendis) BBM</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola rencana distribusi BBM per Triwulan</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.rendis.import') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                Import Excel
            </a>
            <a href="{{ route('admin.rendis.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary hover:bg-brand-primary/90 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Rendis Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 rounded-lg bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800">
            <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase w-12">No</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Triwulan / Tahun</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden sm:table-cell">Jumlah Pembelian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden sm:table-cell">Status Top Up</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                            <span class="hidden sm:inline">Aksi</span>
                            <span class="sm:hidden">Detail</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($rendisList as $rendis)
                    @php
                        $currentYear = (int) date('Y');
                        $currentMonth = (int) date('n');
                        $rendisYear = (int) $rendis->tahun;
                        
                        $twMonths = [
                            'TW I' => [1, 2, 3],
                            'TW II' => [4, 5, 6],
                            'TW III' => [7, 8, 9],
                            'TW IV' => [10, 11, 12],
                        ];
                        $months = $twMonths[$rendis->triwulan] ?? [1, 2, 3];
                        
                        $isAllowed = function($m) use ($currentYear, $currentMonth, $rendisYear) {
                            if ($currentYear > $rendisYear) return true;
                            if ($currentYear < $rendisYear) return false;
                            return $currentMonth >= $m;
                        };
                        
                        $canTopupB1 = $isAllowed($months[0]);
                        $canTopupB2 = $isAllowed($months[1]);
                        $canTopupB3 = $isAllowed($months[2]);
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors" x-data="{ showDetail: false }">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $rendis->triwulan }} - {{ $rendis->tahun }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                            <div class="flex flex-col gap-1.5 w-48">
                                <div class="flex items-center justify-between text-xs bg-blue-50 dark:bg-blue-900/20 px-2 py-1 rounded border border-blue-100 dark:border-blue-800/50">
                                    <span class="text-blue-600 dark:text-blue-400 font-medium">Pertamax</span>
                                    <span class="font-bold text-blue-700 dark:text-blue-300">{{ number_format($rendis->pembelian_pertamax, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between text-xs bg-emerald-50 dark:bg-emerald-900/20 px-2 py-1 rounded border border-emerald-100 dark:border-emerald-800/50">
                                    <span class="text-emerald-600 dark:text-emerald-400 font-medium">Pertamina Dex</span>
                                    <span class="font-bold text-emerald-700 dark:text-emerald-300">{{ number_format($rendis->pembelian_pertamina_dex, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm hidden sm:table-cell">
                            @if($rendis->is_topup_b1) <span class="px-2 py-1 text-[10px] bg-green-100 text-green-800 rounded">B1 ✓</span> @else <span class="px-2 py-1 text-[10px] bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded">B1 -</span> @endif
                            @if($rendis->is_topup_b2) <span class="px-2 py-1 text-[10px] bg-green-100 text-green-800 rounded">B2 ✓</span> @else <span class="px-2 py-1 text-[10px] bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded">B2 -</span> @endif
                            @if($rendis->is_topup_b3) <span class="px-2 py-1 text-[10px] bg-green-100 text-green-800 rounded">B3 ✓</span> @else <span class="px-2 py-1 text-[10px] bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded">B3 -</span> @endif
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="hidden sm:flex items-center justify-end gap-2">
                                {{-- Aksi Dasar --}}
                                <div class="flex items-center gap-1 border-r border-gray-300 dark:border-gray-600 pr-2 mr-1">
                                    <a href="{{ route('admin.rendis.show', $rendis->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 transition-colors" title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.rendis.print-pdf', $rendis->id) }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-900/30 dark:text-rose-400 dark:hover:bg-rose-900/50 transition-colors" title="Cetak PDF">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.rendis.print-excel', $rendis->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50 transition-colors" title="Export Excel">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </a>
                                    @if(!$rendis->is_topup_b1 || !$rendis->is_topup_b2 || !$rendis->is_topup_b3)
                                        <button type="button" @click="topupAction = '{{ route('admin.rendis.verify-edit', $rendis->id) }}'; topupBulan = 'Edit Rendis'; pinValue = ''; showPinModal = true" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 dark:bg-amber-900/30 dark:text-amber-400 dark:hover:bg-amber-900/50 transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                    @endif
                                    @if(!$rendis->is_topup_b1 && !$rendis->is_topup_b2 && !$rendis->is_topup_b3)
                                        <form action="{{ route('admin.rendis.destroy', $rendis->id) }}" method="POST" class="inline-block" onsubmit="event.preventDefault(); Swal.fire({title: 'Hapus Rendis ini?', text: 'Seluruh data kendaraan terkait Rendis ini juga akan terhapus secara permanen.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'}).then((result) => { if (result.isConfirmed) this.submit(); });">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-red-50 dark:bg-red-900/50 transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                {{-- Aksi Top Up --}}
                                <div class="flex items-center gap-1 bg-white text-slate-900 dark:bg-gray-700/50 rounded-lg p-1">
                                    {{-- Top Up B1 --}}
                                    @if($rendis->is_topup_b1)
                                        <button type="button" disabled class="px-2 py-1 text-xs font-semibold rounded bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed" title="B1 Sudah Di-Topup">B1 Selesai</button>
                                    @elseif($canTopupB1)
                                        <button type="button" @click="topupAction = '{{ route('admin.rendis.execute-topup', $rendis->id) }}?bulan=1'; topupBulan = 'Bulan 1'; pinValue = ''; showPinModal = true" class="px-2 py-1 text-xs font-semibold rounded bg-emerald-500 hover:bg-emerald-600 text-white shadow-sm transition-colors" title="Eksekusi Top Up B1">Top Up B1</button>
                                    @else
                                        <button type="button" disabled class="px-2 py-1 text-xs font-semibold rounded bg-gray-200 bg-white text-slate-900 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed border border-gray-300 dark:border-gray-600" title="Belum Waktunya">Top Up B1</button>
                                    @endif

                                    {{-- Top Up B2 --}}
                                    @if($rendis->is_topup_b2)
                                        <button type="button" disabled class="px-2 py-1 text-xs font-semibold rounded bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed" title="B2 Sudah Di-Topup">B2 Selesai</button>
                                    @elseif($canTopupB2)
                                        <button type="button" @click="topupAction = '{{ route('admin.rendis.execute-topup', $rendis->id) }}?bulan=2'; topupBulan = 'Bulan 2'; pinValue = ''; showPinModal = true" class="px-2 py-1 text-xs font-semibold rounded bg-emerald-500 hover:bg-emerald-600 text-white shadow-sm transition-colors" title="Eksekusi Top Up B2">Top Up B2</button>
                                    @else
                                        <button type="button" disabled class="px-2 py-1 text-xs font-semibold rounded bg-gray-200 bg-white text-slate-900 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed border border-gray-300 dark:border-gray-600" title="Belum Waktunya">Top Up B2</button>
                                    @endif

                                    {{-- Top Up B3 --}}
                                    @if($rendis->is_topup_b3)
                                        <button type="button" disabled class="px-2 py-1 text-xs font-semibold rounded bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed" title="B3 Sudah Di-Topup">B3 Selesai</button>
                                    @elseif($canTopupB3)
                                        <button type="button" @click="topupAction = '{{ route('admin.rendis.execute-topup', $rendis->id) }}?bulan=3'; topupBulan = 'Bulan 3'; pinValue = ''; showPinModal = true" class="px-2 py-1 text-xs font-semibold rounded bg-emerald-500 hover:bg-emerald-600 text-white shadow-sm transition-colors" title="Eksekusi Top Up B3">Top Up B3</button>
                                    @else
                                        <button type="button" disabled class="px-2 py-1 text-xs font-semibold rounded bg-gray-200 bg-white text-slate-900 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed border border-gray-300 dark:border-gray-600" title="Belum Waktunya">Top Up B3</button>
                                    @endif
                                </div>
                            </div>

                            <!-- Mobile detail button -->
                            <div class="sm:hidden flex justify-end">
                                <button type="button" @click="showDetail = true" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 transition-colors" title="Lihat Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                            </div>
                            
                            <!-- Mobile Detail Modal -->
                            <template x-teleport="body">
                                <div x-show="showDetail" class="fixed inset-0 z-[9000] flex items-end sm:items-center justify-center p-4" style="display: none;">
                                    <div x-show="showDetail" x-transition.opacity @click="showDetail = false" class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
                                    <div x-show="showDetail" x-transition.translate.y @click.outside="showDetail = false" class="relative w-full max-w-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-gray-700 rounded-2xl shadow-2xl flex flex-col max-h-[85vh] overflow-hidden">
                                        <div class="p-4 border-b border-slate-200 dark:border-gray-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50">
                                            <div class="text-left">
                                                <h3 class="text-slate-800 dark:text-white font-bold text-lg leading-tight">{{ $rendis->triwulan }}</h3>
                                                <p class="text-slate-600 dark:text-slate-400 text-xs">Tahun {{ $rendis->tahun }}</p>
                                            </div>
                                            <button type="button" @click="showDetail = false" class="p-2 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-400 hover:text-slate-900 dark:text-white rounded-xl border border-slate-200 dark:border-gray-600 shadow-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                        <div class="p-4 overflow-y-auto w-full custom-scrollbar text-left text-sm space-y-4">
                                            <div class="grid grid-cols-2 gap-4">
                                                <div class="col-span-2">
                                                    <span class="text-[10px] text-slate-600 dark:text-slate-400 font-bold uppercase tracking-wider block mb-1">Jumlah Pembelian</span>
                                                    <div class="flex flex-col gap-1.5 mt-2">
                                                        <div class="flex items-center justify-between text-xs bg-blue-50 dark:bg-blue-900/20 px-3 py-2 rounded-lg border border-blue-100 dark:border-blue-800/50">
                                                            <span class="text-blue-600 dark:text-blue-400 font-medium">Pertamax</span>
                                                            <span class="font-bold text-blue-700 dark:text-blue-300">{{ number_format($rendis->pembelian_pertamax, 0, ',', '.') }}</span>
                                                        </div>
                                                        <div class="flex items-center justify-between text-xs bg-emerald-50 dark:bg-emerald-900/20 px-3 py-2 rounded-lg border border-emerald-100 dark:border-emerald-800/50">
                                                            <span class="text-emerald-600 dark:text-emerald-400 font-medium">Pertamina Dex</span>
                                                            <span class="font-bold text-emerald-700 dark:text-emerald-300">{{ number_format($rendis->pembelian_pertamina_dex, 0, ',', '.') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-span-2">
                                                    <span class="text-[10px] text-slate-600 dark:text-slate-400 font-bold uppercase tracking-wider block mb-1">Status Top Up</span>
                                                    <div class="flex gap-2 mt-2">
                                                        @if($rendis->is_topup_b1) <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded font-bold">B1 ✓</span> @else <span class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded">B1 -</span> @endif
                                                        @if($rendis->is_topup_b2) <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded font-bold">B2 ✓</span> @else <span class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded">B2 -</span> @endif
                                                        @if($rendis->is_topup_b3) <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded font-bold">B3 ✓</span> @else <span class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded">B3 -</span> @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="bg-white dark:bg-slate-900 dark:text-white pt-4 border-t border-slate-200 dark:border-gray-700 flex flex-col gap-2">
                                                <a href="{{ route('admin.rendis.show', $rendis->id) }}" class="flex items-center gap-3 w-full p-3 bg-white rounded-xl text-slate-700 dark:text-slate-300 hover:text-slate-800 dark:text-white hover:bg-slate-50 dark:bg-slate-800 transition">
                                                    <div class="p-1.5 bg-blue-500/20 text-blue-600 dark:text-blue-400 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></div>
                                                    <span class="font-semibold text-xs">Detail Kendaraan</span>
                                                </a>
                                                <a href="{{ route('admin.rendis.print-pdf', $rendis->id) }}" target="_blank" class="flex items-center gap-3 w-full p-3 bg-white rounded-xl text-slate-700 dark:text-slate-300 hover:text-slate-800 dark:text-white hover:bg-slate-50 dark:bg-slate-800 transition">
                                                    <div class="p-1.5 bg-rose-500/20 text-rose-600 dark:text-rose-400 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div>
                                                    <span class="font-semibold text-xs">Cetak PDF</span>
                                                </a>
                                                <a href="{{ route('admin.rendis.print-excel', $rendis->id) }}" class="flex items-center gap-3 w-full p-3 bg-white rounded-xl text-slate-700 dark:text-slate-300 hover:text-slate-800 dark:text-white hover:bg-slate-50 dark:bg-slate-800 transition">
                                                    <div class="p-1.5 bg-green-500/20 text-green-600 dark:text-green-400 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></div>
                                                    <span class="font-semibold text-xs">Export Excel</span>
                                                </a>
                                                
                                                <div class="my-2 border-t border-slate-100 dark:border-gray-700"></div>
                                                <span class="text-[10px] text-slate-600 dark:text-slate-400 font-bold uppercase tracking-wider block mb-1">Aksi Eksekusi Top Up</span>
                                                
                                                @if($rendis->is_topup_b1)
                                                    <div class="flex items-center gap-3 w-full p-3 bg-gray-50 dark:bg-slate-800/50 rounded-xl text-gray-500 transition">
                                                        <div class="p-1.5 bg-gray-200 dark:bg-slate-700 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                                                        <span class="font-semibold text-xs">B1 Sudah Di-Topup</span>
                                                    </div>
                                                @elseif($canTopupB1)
                                                    <button type="button" @click="showDetail = false; topupAction = '{{ route('admin.rendis.execute-topup', $rendis->id) }}?bulan=1'; topupBulan = 'Bulan 1'; pinValue = ''; showPinModal = true" class="flex items-center gap-3 w-full p-3 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 rounded-xl hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition text-left">
                                                        <div class="p-1.5 bg-emerald-500 text-white rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></div>
                                                        <span class="font-bold text-xs">Top Up B1 Sekarang</span>
                                                    </button>
                                                @else
                                                    <div class="flex items-center gap-3 w-full p-3 bg-gray-50 dark:bg-slate-800/50 rounded-xl text-gray-500 transition">
                                                        <div class="p-1.5 bg-gray-200 dark:bg-slate-700 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                                                        <span class="font-semibold text-xs">Top Up B1 (Belum Waktunya)</span>
                                                    </div>
                                                @endif
                                                
                                                @if($rendis->is_topup_b2)
                                                    <div class="flex items-center gap-3 w-full p-3 bg-gray-50 dark:bg-slate-800/50 rounded-xl text-gray-500 transition">
                                                        <div class="p-1.5 bg-gray-200 dark:bg-slate-700 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                                                        <span class="font-semibold text-xs">B2 Sudah Di-Topup</span>
                                                    </div>
                                                @elseif($canTopupB2)
                                                    <button type="button" @click="showDetail = false; topupAction = '{{ route('admin.rendis.execute-topup', $rendis->id) }}?bulan=2'; topupBulan = 'Bulan 2'; pinValue = ''; showPinModal = true" class="flex items-center gap-3 w-full p-3 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 rounded-xl hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition text-left">
                                                        <div class="p-1.5 bg-emerald-500 text-white rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></div>
                                                        <span class="font-bold text-xs">Top Up B2 Sekarang</span>
                                                    </button>
                                                @else
                                                    <div class="flex items-center gap-3 w-full p-3 bg-gray-50 dark:bg-slate-800/50 rounded-xl text-gray-500 transition">
                                                        <div class="p-1.5 bg-gray-200 dark:bg-slate-700 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                                                        <span class="font-semibold text-xs">Top Up B2 (Belum Waktunya)</span>
                                                    </div>
                                                @endif
                                                
                                                @if($rendis->is_topup_b3)
                                                    <div class="flex items-center gap-3 w-full p-3 bg-gray-50 dark:bg-slate-800/50 rounded-xl text-gray-500 transition">
                                                        <div class="p-1.5 bg-gray-200 dark:bg-slate-700 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                                                        <span class="font-semibold text-xs">B3 Sudah Di-Topup</span>
                                                    </div>
                                                @elseif($canTopupB3)
                                                    <button type="button" @click="showDetail = false; topupAction = '{{ route('admin.rendis.execute-topup', $rendis->id) }}?bulan=3'; topupBulan = 'Bulan 3'; pinValue = ''; showPinModal = true" class="flex items-center gap-3 w-full p-3 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 rounded-xl hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition text-left">
                                                        <div class="p-1.5 bg-emerald-500 text-white rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></div>
                                                        <span class="font-bold text-xs">Top Up B3 Sekarang</span>
                                                    </button>
                                                @else
                                                    <div class="flex items-center gap-3 w-full p-3 bg-gray-50 dark:bg-slate-800/50 rounded-xl text-gray-500 transition">
                                                        <div class="p-1.5 bg-gray-200 dark:bg-slate-700 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                                                        <span class="font-semibold text-xs">Top Up B3 (Belum Waktunya)</span>
                                                    </div>
                                                @endif

                                                @if(!$rendis->is_topup_b1 || !$rendis->is_topup_b2 || !$rendis->is_topup_b3)
                                                    <div class="my-2 border-t border-slate-100 dark:border-gray-700"></div>
                                                    <button type="button" @click="showDetail = false; topupAction = '{{ route('admin.rendis.verify-edit', $rendis->id) }}'; topupBulan = 'Edit Rendis'; pinValue = ''; showPinModal = true" class="flex items-center gap-3 w-full p-3 bg-white rounded-xl text-slate-700 dark:text-slate-300 hover:text-slate-800 dark:text-white hover:bg-slate-50 dark:bg-slate-800 transition text-left">
                                                        <div class="p-1.5 bg-amber-500/20 text-amber-600 dark:text-amber-400 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></div>
                                                        <span class="font-semibold text-xs">Edit Rendis</span>
                                                    </button>
                                                @endif
                                                @if(!$rendis->is_topup_b1 && !$rendis->is_topup_b2 && !$rendis->is_topup_b3)
                                                    <form action="{{ route('admin.rendis.destroy', $rendis->id) }}" method="POST" class="w-full" onsubmit="event.preventDefault(); Swal.fire({title: 'Hapus Rendis ini?', text: 'Seluruh data kendaraan terkait Rendis ini juga akan terhapus secara permanen.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'}).then((result) => { if (result.isConfirmed) this.submit(); });">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="flex items-center gap-3 w-full p-3 bg-rose-50/50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 border border-rose-900/50 rounded-xl hover:text-white hover:bg-rose-600 transition text-left">
                                                            <div class="p-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></div>
                                                            <span class="font-bold text-xs">Hapus Rendis</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada data Rendis.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL PIN VERIFICATION --}}
    <div x-show="showPinModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center" x-transition>
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showPinModal = false"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6 w-full max-w-sm mx-4 z-10" @click.stop>
            <div class="text-center mb-5">
                <div class="mx-auto w-14 h-14 flex items-center justify-center rounded-full bg-amber-100 dark:bg-amber-900/40 mb-3">
                    <svg class="w-7 h-7 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Verifikasi PIN</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Masukkan PIN Top Up untuk <span class="font-semibold text-emerald-600" x-text="topupBulan"></span></p>
            </div>
            <form :action="topupAction" method="POST">
                @csrf
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">PIN Top Up</label>
                    <input type="password" name="topup_password" x-model="pinValue" required autofocus placeholder="Masukkan PIN..." class="w-full px-4 py-3 text-center text-lg tracking-widest rounded-xl border-gray-300 dark:border-gray-600 bg-white text-slate-900 dark:bg-gray-700 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                </div>
                <div class="flex gap-3">
                    <button type="button" @click="showPinModal = false" class="flex-1 px-4 py-2.5 text-sm font-medium rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-bold rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white shadow-sm transition-colors">Konfirmasi</button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
