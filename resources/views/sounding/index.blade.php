<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-500/30 text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-white tracking-tight">Data Sounding BBM</h1>
                    <p class="text-slate-400 text-sm font-medium mt-1 uppercase tracking-widest flex items-center gap-2">
                        Monitoring Stok Fisik dan Pengeluaran
                    </p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route($rolePrefix.'.sounding.pdf', request()->all()) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-xl shadow-lg shadow-rose-500/30 hover:shadow-rose-500/50 transition-all active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    CETAK PDF
                </a>
                <a href="{{ route($rolePrefix.'.sounding.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    TAMBAH DATA
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm flex items-center gap-3">
                <div class="p-2 bg-emerald-500 rounded-lg text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="text-emerald-800 font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Filter Card -->
        <div class="bg-slate-900 border border-white/5 rounded-3xl shadow-sm overflow-hidden mb-6">
            <div class="p-5 border-b border-white/5 bg-slate-800/30">
                <form action="{{ route($rolePrefix.'.sounding.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 items-end gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Bulan</label>
                        <select name="bulan" class="tom-select w-full" data-placeholder="Pilih Bulan">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ $bulan == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Tahun</label>
                        <select name="tahun" class="tom-select w-full" data-placeholder="Pilih Tahun">
                            @foreach(range(date('Y') - 2, date('Y')) as $y)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Triwulan</label>
                        <select name="tw" class="tom-select w-full" data-placeholder="Semua (Opsional)">
                            <option value="">Semua (Opsional)</option>
                            <option value="1" {{ $tw == '1' ? 'selected' : '' }}>Triwulan 1</option>
                            <option value="2" {{ $tw == '2' ? 'selected' : '' }}>Triwulan 2</option>
                            <option value="3" {{ $tw == '3' ? 'selected' : '' }}>Triwulan 3</option>
                            <option value="4" {{ $tw == '4' ? 'selected' : '' }}>Triwulan 4</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Jenis BBM</label>
                        <select name="jenis_bbm" class="tom-select w-full" data-placeholder="Semua BBM">
                            <option value="">Semua BBM</option>
                            <option value="PERTAMAX" {{ $jenis_bbm == 'PERTAMAX' ? 'selected' : '' }}>PERTAMAX</option>
                            <option value="PERTAMINA DEX" {{ $jenis_bbm == 'PERTAMINA DEX' ? 'selected' : '' }}>PERTAMINA DEX</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            TERAPKAN
                        </button>
                    </div>
                </form>
            </div>
        <div class="bg-slate-900 border border-white/5 rounded-3xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-white/5">
                            <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Jenis BBM</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider text-right">Stok Awal</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider text-right">Stok Akhir</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider text-right">Pemakaian</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider text-right">Susut</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider text-center">Dokumentasi</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-white">
                        @php
                            $totals = [];
                        @endphp
                        @forelse($soundings as $item)
                            @php
                                $jenis = $item->jenis_bbm;
                                if(!isset($totals[$jenis])) {
                                    $totals[$jenis] = ['awal' => 0, 'akhir' => 0, 'pemakaian' => 0, 'susut' => 0];
                                }
                                $totals[$jenis]['awal'] += $item->stok_awal;
                                $totals[$jenis]['akhir'] += $item->stok_akhir;
                                $totals[$jenis]['pemakaian'] += $item->pengeluaran_aplikasi;
                                $totals[$jenis]['susut'] += $item->susut;
                            @endphp
                            <tr class="hover:bg-white/5 border-b border-white/5 last:border-0 text-sm text-slate-300">
                                <td class="px-6 py-3 whitespace-nowrap">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d/m/Y') }}</td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $item->jenis_bbm == 'PERTAMAX' ? 'bg-indigo-500/10 text-indigo-400' : 'bg-rose-500/10 text-rose-400' }}">
                                        {{ ucwords(strtolower($item->jenis_bbm)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-right whitespace-nowrap">{{ number_format($item->stok_awal, 0, ',', '.') }} L</td>
                                <td class="px-6 py-3 text-right whitespace-nowrap">{{ number_format($item->stok_akhir, 0, ',', '.') }} L</td>
                                <td class="px-6 py-3 text-right whitespace-nowrap">{{ number_format($item->pengeluaran_aplikasi, 0, ',', '.') }} L</td>
                                <td class="px-6 py-3 text-right whitespace-nowrap font-medium {{ $item->susut > 0 ? 'text-emerald-400' : ($item->susut < 0 ? 'text-rose-400' : 'text-slate-400') }}">
                                    {{ number_format($item->susut, 0, ',', '.') }} L
                                </td>
                                <td class="px-6 py-3 text-center whitespace-nowrap">
                                    <div class="flex justify-center">
                                        @if($item->dokumentasi)
                                            <a href="{{ Storage::url($item->dokumentasi) }}" target="_blank" class="p-1.5 bg-slate-800 text-indigo-400 hover:bg-indigo-500 hover:text-white rounded-lg transition-colors" title="Lihat Dokumentasi">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        @else
                                            <span class="p-1.5 text-slate-500">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route($rolePrefix.'.sounding.edit', $item->id) }}" class="p-1.5 bg-slate-800 text-amber-400 hover:bg-amber-500 hover:text-white rounded-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route($rolePrefix.'.sounding.destroy', $item->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-slate-800 text-rose-400 hover:bg-rose-500 hover:text-white rounded-lg transition-colors" 
                                                    title="Hapus" 
                                                    data-confirm="Apakah Anda yakin ingin menghapus data sounding ini? Data yang dihapus tidak dapat dikembalikan." 
                                                    data-confirm-type="error" 
                                                    data-confirm-title="Hapus Data Sounding?" 
                                                    data-confirm-text="Ya, Hapus!">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-800/50 rounded-full flex items-center justify-center mb-4 text-slate-500">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <p class="text-slate-400 font-bold">Belum ada data sounding.</p>
                                    <p class="text-slate-500 text-xs mt-1">Ganti filter atau tambahkan data baru.</p>
                                </div>
                            </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if(count($soundings) > 0)
                    <tfoot class="bg-white/5 border-t border-white/10 text-white">
                        @foreach($totals as $jenis => $total)
                        <tr class="border-b border-white/5 last:border-0 text-sm text-slate-300">
                            <td colspan="2" class="px-6 py-4 text-right font-medium text-slate-400 uppercase">Total {{ ucwords(strtolower($jenis)) }}</td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">{{ number_format($total['awal'], 0, ',', '.') }} L</td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">{{ number_format($total['akhir'], 0, ',', '.') }} L</td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">{{ number_format($total['pemakaian'], 0, ',', '.') }} L</td>
                            <td class="px-6 py-4 text-right whitespace-nowrap font-medium {{ $total['susut'] > 0 ? 'text-emerald-400' : ($total['susut'] < 0 ? 'text-rose-400' : 'text-slate-400') }}">
                                {{ number_format($total['susut'], 0, ',', '.') }} L
                            </td>
                            <td colspan="2"></td>
                        </tr>
                        @endforeach
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
