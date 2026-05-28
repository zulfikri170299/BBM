<x-app-layout>
    <div class="p-4 sm:p-6 lg:p-8 space-y-4 sm:space-y-6 lg:space-y-8">
        <!-- Page Title -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-0">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900">Dashboard</h1>
                <p class="text-xs sm:text-sm text-slate-500 font-medium">Selamat datang, {{ auth()->user()->name }}</p>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-xl border border-slate-200 shadow-sm w-fit">
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-xs sm:text-sm font-bold text-slate-700">
                    {{ \Carbon\Carbon::now('Asia/Makassar')->translatedFormat('l, d F Y') }}
                    <span class="text-indigo-600 ml-1">{{ \Carbon\Carbon::now('Asia/Makassar')->format('H:i') }}
                        WITA</span>
                </span>
            </div>
        </div>

        <!-- Tank Stock Section -->
        @if($tankStock)
        <div class="grid grid-cols-2 gap-3 sm:gap-6">
            <!-- Pertamax Tank Card -->
            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm p-3 sm:p-5 flex items-center gap-3 sm:gap-4 hover:border-blue-300 transition-all group overflow-hidden relative">
                <div class="absolute -right-4 -top-4 w-16 sm:w-24 h-16 sm:h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 relative z-10">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
                <div class="relative z-10 min-w-0">
                    <p class="text-[8px] sm:text-[10px] font-black text-blue-600 uppercase tracking-widest mb-0.5 sm:mb-1 opacity-80 whitespace-normal leading-tight">Stok BBM di Tangki (Pertamax)</p>
                    <p class="text-lg sm:text-2xl font-black text-slate-900 leading-none truncate">
                        {{ number_format($tankStock->sisa_pertamax, 0, ',', '.') }} <span class="text-[10px] sm:text-sm font-bold text-slate-400">L</span>
                    </p>
                </div>
            </div>

            <!-- Dex Tank Card -->
            <div class="bg-white rounded-2xl border border-rose-100 shadow-sm p-3 sm:p-5 flex items-center gap-3 sm:gap-4 hover:border-rose-300 transition-all group overflow-hidden relative">
                <div class="absolute -right-4 -top-4 w-16 sm:w-24 h-16 sm:h-24 bg-rose-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0 relative z-10">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
                <div class="relative z-10 min-w-0">
                    <p class="text-[8px] sm:text-[10px] font-black text-rose-600 uppercase tracking-widest mb-0.5 sm:mb-1 opacity-80 whitespace-normal leading-tight">Stok BBM di Tangki (Dex)</p>
                    <p class="text-lg sm:text-2xl font-black text-slate-900 leading-none truncate">
                        {{ number_format($tankStock->sisa_dex, 0, ',', '.') }} <span class="text-[10px] sm:text-sm font-bold text-slate-400">L</span>
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
            <!-- Transaksi Hari Ini -->
            <div
                class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-cyan-500 to-sky-700 p-4 sm:p-5 lg:p-6 text-white shadow-xl group hover:shadow-cyan-500/40 transition-all duration-300 hover:-translate-y-1">
                <div
                    class="absolute -top-4 -right-4 w-16 sm:w-24 h-16 sm:h-24 bg-white/10 rounded-full blur-lg group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10">
                    <div class="p-2 sm:p-3 bg-white/20 rounded-lg sm:rounded-xl backdrop-blur-sm w-fit mb-3 sm:mb-4">
                        <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                            </path>
                        </svg>
                    </div>
                    <p class="text-2xl sm:text-3xl lg:text-4xl font-extrabold">
                        {{ number_format($todayTransactions, 0, ',', '.') }}
                    </p>
                    <p class="text-xs sm:text-sm lg:text-base text-cyan-100 mt-1 font-medium">Transaksi Hari Ini</p>
                </div>
            </div>

            <!-- Total Penyaluran per Jenis BBM -->
            @forelse($breakdownBbm as $jenis => $total)
                @php
                    $colors = [
                        'Pertamax' => 'from-blue-500 to-indigo-700 shadow-blue-500/40',
                        'Pertamina Dex' => 'from-emerald-500 to-teal-700 shadow-emerald-500/40',
                        'Pertalite' => 'from-green-500 to-emerald-700 shadow-green-500/40',
                        'Solar' => 'from-amber-500 to-orange-700 shadow-amber-500/40',
                        'Dexlite' => 'from-rose-500 to-pink-700 shadow-rose-500/40',
                    ];
                    $colorClass = $colors[$jenis] ?? 'from-slate-500 to-slate-700 shadow-slate-500/40';
                @endphp
                <div
                    class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br {{ $colorClass }} p-4 sm:p-5 lg:p-6 text-white shadow-xl group transition-all duration-300 hover:-translate-y-1">
                    <div
                        class="absolute -top-4 -right-4 w-16 sm:w-24 h-16 sm:h-24 bg-white/10 rounded-full blur-lg group-hover:scale-150 transition-transform duration-500">
                    </div>
                    <div class="relative z-10">
                        <div class="p-2 sm:p-3 bg-white/20 rounded-lg sm:rounded-xl backdrop-blur-sm w-fit mb-3 sm:mb-4">
                            <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                </path>
                            </svg>
                        </div>
                        <div class="flex items-baseline gap-1 sm:gap-2">
                            <p class="text-xl sm:text-3xl lg:text-4xl font-extrabold truncate">
                                {{ number_format($total, 0, ',', '.') }}
                            </p>
                            <p class="text-xs sm:text-sm lg:text-lg font-semibold">L</p>
                        </div>
                        <p
                            class="text-[10px] sm:text-xs lg:text-sm font-bold uppercase tracking-wider mt-1 opacity-90 truncate">
                            {{ $jenis }} Hari Ini
                        </p>
                    </div>
                </div>
            @empty
                <!-- Tampilan jika belum ada penyaluran -->
                <div
                    class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-slate-400 to-slate-500 p-4 sm:p-5 lg:p-6 text-white shadow-xl opacity-60">
                    <div class="relative z-10">
                        <div class="p-2 sm:p-3 bg-white/20 rounded-lg sm:rounded-xl backdrop-blur-sm w-fit mb-3 sm:mb-4">
                            <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                </path>
                            </svg>
                        </div>
                        <p class="text-sm sm:text-lg lg:text-xl font-bold italic">Belum ada penyaluran</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Transaksi Admin / Super Admin -->
        @if($adminTransactions->isNotEmpty())
        <div class="pt-4 sm:pt-6">
            <h2 class="text-lg sm:text-xl font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Penyaluran dari Stok Pusat (Super Admin) Hari Ini
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
                @foreach($adminTransactions as $at)
                    <div class="bg-gradient-to-br from-purple-500 to-fuchsia-700 rounded-xl sm:rounded-2xl border border-purple-100 shadow-xl p-4 sm:p-5 flex items-center gap-4 group hover:shadow-purple-500/40 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute -top-4 -right-4 w-16 sm:w-24 h-16 sm:h-24 bg-white/10 rounded-full blur-lg group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm text-white flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform relative z-10">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div class="relative z-10 text-white">
                            <p class="text-xs font-bold text-purple-100 uppercase tracking-widest mb-0.5">{{ $at->jenis_bbm }}</p>
                            <div class="flex items-baseline gap-1">
                                <p class="text-xl sm:text-2xl font-black leading-none">
                                    {{ number_format($at->total_liter, 0, ',', '.') }}
                                </p>
                                <span class="text-sm font-bold text-purple-200">L</span>
                            </div>
                            <p class="text-[10px] sm:text-xs text-purple-100 mt-1">{{ $at->total_transaksi }} Transaksi</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Hutang Belum Dibayar Stats -->
        <div class="pt-4 sm:pt-6">
            <h2 class="text-lg sm:text-xl font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Total Bon Belum Dibayar
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
                @forelse($hutangPerBbm as $jenis => $total)
                    <div
                        class="bg-white rounded-xl sm:rounded-2xl border border-rose-100 shadow-sm p-4 sm:p-5 flex items-center gap-4 hover:border-rose-300 transition-colors group">
                        <div
                            class="w-12 h-12 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-0.5">{{ $jenis }}</p>
                            <p class="text-xl sm:text-2xl font-black text-rose-600 leading-none">
                                {{ number_format($total, 0, ',', '.') }} <span
                                    class="text-sm font-bold text-slate-300">L</span>
                            </p>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full bg-emerald-50 rounded-xl sm:rounded-2xl border border-emerald-100 p-4 sm:p-5 flex items-center justify-center gap-3">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p class="font-bold text-emerald-600">Tidak ada bon yang belum dibayar saat ini</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Floating CTA Button -->
        <a href="{{ route('petugas.transaksi.index') }}" title="Mulai Transaksi Baru"
            class="fixed bottom-6 right-6 sm:bottom-8 sm:right-8 z-50 flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-full shadow-2xl shadow-indigo-500/50 hover:shadow-indigo-500/80 hover:-translate-y-1 hover:scale-105 transition-all duration-300 group">
            <svg class="w-6 h-6 sm:w-8 sm:h-8 group-hover:rotate-12 transition-transform duration-300" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z">
                </path>
            </svg>
            <span class="absolute -top-1 -right-1 flex h-4 w-4">
                <span
                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-indigo-500 border border-white"></span>
            </span>
        </a>
    </div>

</x-app-layout>