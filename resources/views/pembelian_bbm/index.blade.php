<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-2xl font-bold text-white leading-tight">Pembelian BBM</h1>
                <p class="mt-1 text-xs text-slate-400">Catat dan pantau riwayat pembelian BBM Pertamax & Pertamina Dex.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-100 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="font-medium text-sm">{{ session('success') }}</p>
            </div>
        @endif
        
        @if ($errors->any())
            <div class="p-4 bg-rose-50 text-rose-700 rounded-xl border border-rose-100">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <p class="font-bold text-sm">Terjadi Kesalahan</p>
                </div>
                <ul class="list-disc list-inside text-sm pl-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Add Purchase Form -->
            <div class="lg:col-span-1">
                <div class="bg-slate-900 border border-white/5 rounded-2xl border border-white/10 shadow-sm sticky top-8">
                    <div class="p-4 sm:p-6 border-b border-white/5 bg-slate-800/50">
                        <h3 class="text-base sm:text-lg font-bold text-slate-200 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Form Pembelian BBM
                        </h3>
                    </div>
                    <form action="{{ route('pembelian-bbm.store') }}" method="POST" class="p-4 sm:p-6 space-y-4" autocomplete="off">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2">1. Pilih Tanggal</label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', $latestDate ?? date('Y-m-d')) }}" class="w-full px-4 py-2.5 bg-slate-800/50 border border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none font-semibold text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2">2. Jenis BBM</label>
                            <select name="jenis_bbm" required class="tom-select w-full">
                                <option value="">Pilih Jenis BBM</option>
                                <option value="Pertamax" {{ old('jenis_bbm') == 'Pertamax' ? 'selected' : '' }}>Pertamax</option>
                                <option value="Pertamina Dex" {{ old('jenis_bbm') == 'Pertamina Dex' ? 'selected' : '' }}>Pertamina Dex</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2">3. Jumlah BBM (Liter)</label>
                            <input type="number" name="jumlah" value="{{ old('jumlah') }}" step="1" min="1" class="w-full px-4 py-2.5 bg-slate-800/50 border border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none" placeholder="Masukkan jumlah liter..." required>
                        </div>
                        
                        <button type="submit" class="w-full py-3 mt-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/30 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Data Pembelian
                        </button>
                    </form>
                </div>
            </div>

            <!-- History Table -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-slate-900 border border-white/5 rounded-2xl border border-white/10 shadow-sm overflow-hidden">
                    <div class="p-4 sm:p-5 border-b border-white/5 bg-slate-800/50 flex flex-col gap-4">
                        <div class="flex items-center gap-3">
                            <h3 class="text-base font-bold text-slate-200">Riwayat Pembelian BBM</h3>
                        </div>

                        <!-- Compact Date Filter -->
                        <form action="{{ route('pembelian-bbm.index') }}" method="GET" class="flex flex-wrap xl:flex-nowrap items-center gap-2 w-full">
                            <input type="hidden" name="per_page" value="{{ request('per_page', 15) }}">
                            
                            <!-- Date Inputs -->
                            <div class="flex flex-nowrap items-center gap-2 flex-grow min-w-0">
                                <div class="relative group/input w-1/3">
                                    <select name="jenis_bbm" class="px-2 py-1.5 bg-slate-900 border border-white/5 border-white/10 rounded-lg text-[10px] sm:text-[11px] font-bold text-slate-300 focus:ring-4 focus:ring-indigo-500/5 transition-all w-full">
                                        <option value="">Semua BBM</option>
                                        <option value="Pertamax" {{ request('jenis_bbm') == 'Pertamax' ? 'selected' : '' }}>Pertamax</option>
                                        <option value="Pertamina Dex" {{ request('jenis_bbm') == 'Pertamina Dex' ? 'selected' : '' }}>Pertamina Dex</option>
                                    </select>
                                </div>
                                <div class="relative group/input w-1/3">
                                    <input type="date" name="start_date" value="{{ request('start_date') }}" 
                                        class="flatpickr px-2 py-1.5 bg-slate-900 border border-white/5 border-white/10 rounded-lg text-[10px] sm:text-[11px] font-bold text-slate-300 focus:ring-4 focus:ring-indigo-500/5 transition-all w-full" placeholder="Tgl Mulai">
                                </div>
                                <div class="relative group/input w-1/3">
                                    <input type="date" name="end_date" value="{{ request('end_date') }}" 
                                        class="flatpickr px-2 py-1.5 bg-slate-900 border border-white/5 border-white/10 rounded-lg text-[10px] sm:text-[11px] font-bold text-slate-300 focus:ring-4 focus:ring-indigo-500/5 transition-all w-full" placeholder="Tgl Selesai">
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex flex-nowrap items-center gap-1 sm:gap-2 flex-shrink-0 mt-2 xl:mt-0 w-full xl:w-auto justify-end">
                                <button type="submit" class="flex-1 xl:flex-none px-3 py-1.5 bg-indigo-600 text-white font-black rounded-lg hover:bg-indigo-700 transition-all text-[10px] uppercase tracking-widest shadow-sm">
                                    Filter
                                </button>
                                @if(request()->hasAny(['start_date', 'end_date', 'jenis_bbm']))
                                    <a href="{{ route('pembelian-bbm.index', request()->only('per_page')) }}" class="flex-1 xl:flex-none px-3 py-1.5 bg-slate-800 text-slate-400 font-bold rounded-lg hover:bg-slate-200 transition-all text-[10px] uppercase tracking-widest leading-normal text-center">
                                        Reset
                                    </a>
                                @endif
                                <div class="h-6 w-px bg-slate-200 mx-1 hidden sm:block"></div>
                                <a href="{{ route('pembelian-bbm.print', request()->all()) }}" target="_blank" class="flex-1 xl:flex-none px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-[10px] font-black rounded-lg shadow-sm transition-all flex items-center justify-center gap-1.5 uppercase tracking-widest">
                                    <svg class="w-3.5 h-3.5 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Cetak PDF
                                </a>
                            </div>
                        </form>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-800/50 border-b border-white/5">
                                    <th colspan="5" class="px-4 py-3">
                                        <div class="flex items-center justify-between">
                                            <form action="{{ route('pembelian-bbm.index') }}" method="GET" class="flex items-center">
                                                @foreach(request()->except('per_page') as $k => $v)
                                                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                                @endforeach
                                                <x-per-page :current="request('per_page', 15)" />
                                            </form>
                                            <div class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">
                                                Menampilkan {{ $pembelians->firstItem() ?? 0 }}-{{ $pembelians->lastItem() ?? 0 }} dari {{ $pembelians->total() }} data
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                                <tr class="bg-slate-800/50 border-b border-white/5">
                                    <th class="px-4 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">No</th>
                                    <th class="px-4 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</th>
                                    <th class="px-4 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Jenis BBM</th>
                                    <th class="px-4 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jumlah (Liter)</th>
                                    <th class="px-4 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($pembelians as $index => $item)
                                    @php
                                        $bbmStyle = [
                                            'Pertamax' => 'from-blue-500 to-indigo-600',
                                            'Pertamina Dex' => 'from-rose-500 to-red-600',
                                        ];
                                    @endphp
                                    <tr class="hover:bg-slate-800/50 transition-colors">
                                        <td class="px-4 py-3 text-center">
                                            <p class="text-xs sm:text-sm font-medium text-slate-400">{{ ($pembelians->currentPage() - 1) * $pembelians->perPage() + $index + 1 }}</p>
                                        </td>
                                        <td class="px-4 py-3">
                                            <p class="text-xs sm:text-sm font-semibold text-slate-300">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</p>
                                            <p class="text-[10px] text-slate-400">Dibuat: {{ $item->created_at->format('d/m/Y H:i') }}</p>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-[10px] font-bold text-white bg-gradient-to-r {{ $bbmStyle[$item->jenis_bbm] ?? 'from-slate-500 to-slate-600' }} whitespace-nowrap">
                                                {{ $item->jenis_bbm }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <p class="text-xs sm:text-sm font-bold text-indigo-600">
                                                {{ number_format($item->jumlah, 0, ',', '.') }} L
                                            </p>
                                        </td>
                                         <td class="px-4 py-3 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('pembelian-bbm.edit', $item) }}" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <form action="{{ route('pembelian-bbm.destroy', $item->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" 
                                                        data-confirm="Apakah Anda yakin ingin menghapus data pembelian ini?"
                                                        data-confirm-type="danger"
                                                        data-confirm-title="Hapus Data!"
                                                        data-confirm-text="Ya, Hapus!"
                                                        class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Belum ada riwayat pembelian BBM.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($pembelians->hasPages())
                        <div class="px-3 sm:px-6 py-3 sm:py-4 bg-slate-800/50 border-t border-white/5">
                            {{ $pembelians->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
