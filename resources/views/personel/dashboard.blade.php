<x-app-layout>
    <div class="p-6 lg:p-8 space-y-8">
        <!-- Page Title -->
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Personel Overview</h1>
            <p class="mt-1 text-slate-500">Halo, {{ Auth::user()->name }}! Berikut informasi akun Anda.</p>
        </div>

        @if(isset($error))
            <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 p-2 bg-amber-100 rounded-full">
                        <svg class="h-5 w-5 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="text-sm text-amber-700 font-medium">{{ $error }}</p>
                </div>
            </div>
        @endif

        <!-- Top Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Saldo Card -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-500 to-emerald-700 p-8 text-white shadow-xl group">
                <div class="absolute -top-6 -right-6 w-32 h-32 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-black/10 to-transparent"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-green-100 uppercase tracking-wider mb-2">Saldo Personal Anda</p>
                    <p class="text-5xl font-extrabold tracking-tight">{{ number_format($saldo, 1) }} <span class="text-2xl">Liter</span></p>
                    <div class="mt-5 flex items-center gap-2 text-sm text-green-200">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>Status Aktif</span>
                    </div>
                </div>
            </div>

            <!-- Satker Info -->
            <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-8 flex flex-col justify-center">
                <div class="flex items-center gap-4 mb-4">
                    <div class="p-3 bg-indigo-100 text-indigo-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 uppercase tracking-wider">Unit Kerja</p>
                        <p class="text-xl font-bold text-slate-900">{{ auth()->user()->satker->nama_satker ?? '-' }}</p>
                    </div>
                </div>
                <div class="pl-16 space-y-1 text-sm text-slate-600">
                    <p><span class="font-medium text-slate-800">Nama:</span> {{ auth()->user()->name }}</p>
                    <p><span class="font-medium text-slate-800">NRP:</span> {{ auth()->user()->personel->nrp ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Transaction History & Vehicles -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Transaction History Table -->
            <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200/70 shadow-sm">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-800">Riwayat Transaksi</h3>
                </div>
                @if($transactions->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-slate-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <p class="text-slate-500 font-medium">Belum ada transaksi</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-slate-50/70">
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kendaraan</th>
                                    <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Liter</th>
                                    <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($transactions as $trx)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-semibold text-slate-800">{{ $trx->kendaraan->no_polisi }}</span>
                                        <span class="text-xs text-slate-400 ml-1">({{ $trx->kendaraan->jenis_bbm }})</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 text-right">{{ $trx->liter }} L</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-800 text-right">Rp {{ number_format($trx->total) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-slate-100">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>

            <!-- Vehicles List -->
            <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">Kendaraan Satker</h3>
                </div>
                <div class="p-4 space-y-3 max-h-[500px] overflow-y-auto">
                    @foreach($kendaraans as $kendaraan)
                    <div class="p-4 rounded-xl border {{ $kendaraan->saldo < 50000 ? 'border-red-200 bg-red-50/50' : 'border-slate-200 bg-slate-50/50' }} transition-all hover:shadow-md hover:-translate-y-0.5 duration-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-bold text-slate-800">{{ $kendaraan->no_polisi }}</p>
                                <p class="text-xs text-slate-500">{{ $kendaraan->jenis_kendaraan }} &bull; {{ $kendaraan->jenis_bbm }}</p>
                            </div>
                            @if($kendaraan->saldo < 50000)
                                <span class="px-2.5 py-1 text-xs font-bold text-red-700 bg-red-100 rounded-full animate-pulse">Low</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full">OK</span>
                            @endif
                        </div>
                        <div class="mt-2 text-right">
                            <span class="text-xs text-slate-500">Saldo:</span>
                            <span class="font-bold text-sm {{ $kendaraan->saldo < 10 ? 'text-red-600' : 'text-slate-700' }}">{{ number_format($kendaraan->saldo, 1) }} Liter</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
