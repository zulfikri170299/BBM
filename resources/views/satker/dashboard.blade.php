<x-app-layout>
    <div class="p-6 lg:p-8 space-y-8">
        <!-- Page Title -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Satker Overview</h1>
                <p class="mt-1 text-slate-500">{{ Auth::user()->satker->nama_satker ?? 'Satker Panel' }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('satker.kendaraans.create') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold text-sm hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Kendaraan
                </a>
                <a href="{{ route('satker.personels.create') }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl font-semibold text-sm hover:bg-slate-50 shadow-sm transition-all duration-200 hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Tambah Personel
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <!-- Kendaraan -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 p-6 text-white shadow-xl group hover:shadow-blue-500/40 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-lg group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 012-2h0m8 0a2 2 0 00-2 2h0"></path></svg>
                        </div>
                        <a href="{{ route('satker.kendaraans.index') }}" class="text-xs font-semibold bg-white/20 hover:bg-white/30 px-3 py-1 rounded-lg transition">Kelola →</a>
                    </div>
                    <p class="text-3xl font-extrabold">{{ $totalKendaraan }}</p>
                    <p class="text-sm text-blue-100 mt-1">Total Kendaraan</p>
                </div>
            </div>

            <!-- Personel -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 p-6 text-white shadow-xl group hover:shadow-emerald-500/40 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-lg group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <a href="{{ route('satker.personels.index') }}" class="text-xs font-semibold bg-white/20 hover:bg-white/30 px-3 py-1 rounded-lg transition">Kelola →</a>
                    </div>
                    <p class="text-3xl font-extrabold">{{ $totalPersonel }}</p>
                    <p class="text-sm text-emerald-100 mt-1">Total Personel</p>
                </div>
            </div>

            <!-- Transaksi -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-violet-500 to-purple-700 p-6 text-white shadow-xl group hover:shadow-violet-500/40 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-lg group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                    </div>
                    <p class="text-3xl font-extrabold">{{ $totalTransaksi }}</p>
                    <p class="text-sm text-violet-100 mt-1">Total Transaksi</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
