<x-app-layout>
    <div class="p-6 lg:p-8 space-y-8">
        <!-- Page Title -->
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Petugas BBM</h1>
            <p class="mt-1 text-slate-500">Station pengisian bahan bakar — {{ Auth::user()->satker->nama_satker ?? '' }}</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-cyan-500 to-sky-700 p-6 text-white shadow-xl group hover:shadow-cyan-500/40 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-lg group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm w-fit mb-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <p class="text-4xl font-extrabold">{{ $todayTransactions }}</p>
                    <p class="text-sm text-cyan-100 mt-1">Transaksi Hari Ini</p>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 p-6 text-white shadow-xl group hover:shadow-amber-500/40 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-lg group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm w-fit mb-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <p class="text-4xl font-extrabold">{{ number_format($todayLiter, 0, ',', '.') }}</p>
                        <p class="text-lg font-semibold">Liter</p>
                    </div>
                    <p class="text-xs text-amber-100/80 font-medium uppercase tracking-wider mt-1">Total Penyaluran Hari Ini</p>

                    @if(count($breakdownBbm) > 0)
                    <div class="mt-4 pt-4 border-t border-white/20 grid grid-cols-2 gap-y-2 gap-x-4">
                        @foreach($breakdownBbm as $jenis => $total)
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold uppercase tracking-tight opacity-80">{{ $jenis }}</span>
                                <span class="text-xs font-bold">{{ number_format($total, 0, ',', '.') }} L</span>
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- CTA Button -->
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-8 text-center">
            <div class="max-w-md mx-auto">
                <div class="w-20 h-20 bg-indigo-100 rounded-full mx-auto mb-6 flex items-center justify-center">
                    <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 mb-2">Mulai Transaksi Baru</h2>
                <p class="text-slate-500 mb-6">Scan barcode kendaraan atau masukkan ID secara manual untuk memulai proses pengisian BBM.</p>
                <a href="{{ route('petugas.transaksi.index') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold rounded-2xl text-lg shadow-xl shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all duration-300 hover:-translate-y-1 hover:scale-105">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Mulai Transaksi
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
