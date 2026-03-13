<x-app-layout>
    <div class="p-6 lg:p-8 space-y-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 leading-tight">Manajemen Stok BBM</h1>
                <p class="mt-1 text-sm text-slate-500">Kelola stok bahan bakar pusat sebelum dibagikan ke Satker.</p>
            </div>
        </div>

        <!-- Current Stock Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-2 gap-3 sm:gap-4">
            @foreach($stocks as $stock)
                @php
                    $bbmStyle = [
                        'Pertamax' => 'from-blue-500 to-indigo-600',
                        'Pertamina Dex' => 'from-rose-500 to-red-600',
                    ];
                @endphp
                <div class="bg-gradient-to-br {{ $bbmStyle[$stock->jenis_bbm] ?? 'from-slate-500 to-slate-600' }} p-4 sm:p-5 rounded-2xl text-white shadow-lg relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative z-10">
                        <p class="text-[9px] sm:text-[10px] font-bold uppercase tracking-widest opacity-80">{{ $stock->jenis_bbm }}</p>
                        <div class="flex items-baseline gap-1 mt-1 sm:mt-2">
                            <span class="text-2xl sm:text-3xl font-black">{{ number_format($stock->saldo, 0, ',', '.') }}</span>
                            <span class="text-[10px] sm:text-sm font-bold opacity-70">Liter</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Add Stock Form -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden sticky top-8">
                    <div class="p-4 sm:p-6 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Tambah Stok Baru
                        </h3>
                    </div>
                    <form action="{{ route('admin.stok.store') }}" method="POST" class="p-4 sm:p-6 space-y-4" autocomplete="off">
                        @csrf
                        <!-- Browser Auto-fill Hack -->
                        <input type="text" style="display:none" autocomplete="username">
                        <input type="password" style="display:none" autocomplete="current-password">

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jenis BBM</label>
                            <select name="jenis_bbm" required class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm font-semibold text-slate-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all">
                                <option value="">Pilih Jenis BBM</option>
                                @foreach(['Pertamax', 'Pertamina Dex'] as $bbm)
                                    <option value="{{ $bbm }}">{{ $bbm }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah (Liter)</label>
                            <input type="number" name="jumlah" step="0.01" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none" placeholder="Masukkan jumlah liter..." required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Keterangan (Opsional)</label>
                            <textarea name="keterangan" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none" placeholder="Contoh: Penerimaan Alokasi TW I 2024"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Password Top Up</label>
                            <input type="password" name="topup_password" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none" placeholder="Masukkan password top-up..." required autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                            @if(!auth()->user()->topup_password)
                                <p class="text-xs text-rose-500 mt-1">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    Anda belum mengatur password top-up. <a href="{{ route('profile.edit') }}" class="underline hover:text-rose-700">Atur di sini</a>.
                                </p>
                            @endif
                        </div>
                        <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/30 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Stok
                        </button>
                    </form>
                </div>
            </div>

            <!-- History Table -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex items-center gap-3">
                            <h3 class="text-lg font-bold text-slate-800">Riwayat Perubahan Stok</h3>
                        </div>
                        <a href="{{ route('admin.stok.print') }}" target="_blank" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-rose-500/30 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak PDF
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100">
                                    <th colspan="5" class="px-6 py-3">
                                        <div class="flex items-center justify-between">
                                            <form action="{{ route('admin.stok.index') }}" method="GET" class="flex items-center">
                                                <x-per-page :current="request('per_page', 20)" />
                                            </form>
                                            <div class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">
                                                Menampilkan {{ $history->firstItem() ?? 0 }}-{{ $history->lastItem() ?? 0 }} dari {{ $history->total() }} data
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                                <tr class="bg-slate-50 border-b border-slate-100">
                                    <th class="px-2 py-4 sm:px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</th>
                                    <th class="px-2 py-4 sm:px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jenis BBM</th>
                                    <th class="px-2 py-4 sm:px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jumlah</th>
                                    <th class="px-2 py-4 sm:px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tipe</th>
                                    <th class="px-2 py-4 sm:px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($history as $item)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-2 py-3 sm:px-6 sm:py-4">
                                            <p class="text-xs sm:text-sm font-semibold text-slate-700">{{ $item->created_at->format('d/m/Y') }}</p>
                                            <p class="text-[9px] sm:text-[10px] text-slate-400">{{ $item->created_at->format('H:i') }} WIB</p>
                                        </td>
                                        <td class="px-2 py-3 sm:px-6 sm:py-4 text-center">
                                            <span class="px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-[8px] sm:text-[10px] font-bold text-white bg-gradient-to-r {{ $bbmStyle[$item->jenis_bbm] ?? 'from-slate-500 to-slate-600' }} whitespace-nowrap">
                                                {{ $item->jenis_bbm }}
                                            </span>
                                        </td>
                                        <td class="px-2 py-3 sm:px-6 sm:py-4">
                                            <p class="text-xs sm:text-sm font-bold {{ $item->tipe === 'masuk' ? 'text-emerald-600' : 'text-rose-600' }}">
                                                {{ $item->tipe === 'masuk' ? '+' : '-' }} {{ number_format($item->jumlah, 0, ',', '.') }} L
                                            </p>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($item->tipe === 'masuk')
                                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-600">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                                                    Masuk
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2 py-1 text-xs font-semibold text-rose-600">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-rose-600"></span>
                                                    Keluar
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-xs text-slate-600 line-clamp-2">{{ $item->keterangan }}</p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Belum ada riwayat stok.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($history->hasPages())
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                            {{ $history->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
