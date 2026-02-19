<x-app-layout>
    <div class="p-4 sm:p-6 lg:p-8 space-y-4 sm:space-y-6 lg:space-y-8">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900">Petugas BBM</h1>
            <p class="mt-0.5 sm:mt-1 text-xs sm:text-sm text-slate-500">Station pengisian bahan bakar —
                {{ Auth::user()->satker->nama_satker ?? '' }}
            </p>
        </div>

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
                    <p class="text-2xl sm:text-3xl lg:text-4xl font-extrabold">{{ $todayTransactions }}</p>
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

        <!-- CTA Button -->
        <div
            class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/70 shadow-sm p-5 sm:p-6 lg:p-8 text-center">
            <div class="max-w-md mx-auto">
                <div
                    class="w-12 h-12 sm:w-16 sm:h-16 lg:w-20 lg:h-20 bg-indigo-100 rounded-full mx-auto mb-3 sm:mb-4 lg:mb-6 flex items-center justify-center">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 lg:w-10 lg:h-10 text-indigo-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                        </path>
                    </svg>
                </div>
                <h2 class="text-base sm:text-xl lg:text-2xl font-bold text-slate-900 mb-1 sm:mb-2">Mulai Transaksi Baru
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mb-4 sm:mb-6 px-4">Scan barcode kendaraan atau masukkan ID
                    secara
                    manual untuk memulai pengisian BBM.</p>
                <a href="{{ route('petugas.transaksi.index') }}"
                    class="inline-flex items-center px-5 py-3 sm:px-6 sm:py-3 lg:px-8 lg:py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold rounded-xl sm:rounded-2xl text-sm sm:text-base lg:text-lg shadow-xl shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all duration-300 hover:-translate-y-1 hover:scale-105">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 mr-2 sm:mr-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Mulai Transaksi
                </a>
            </div>
        </div>
    </div>
</x-app-layout>