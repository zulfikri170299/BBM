<x-app-layout>
    <div class="p-4 sm:p-6 lg:p-8 space-y-4 sm:space-y-8">
        <!-- Page Title -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-4">
            <div>
                <h1 class="text-xl sm:text-3xl font-bold text-slate-900">Dashboard</h1>

            </div>
        </div>

        <!-- Stats Cards Row 1 -->
        <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-6">
            <!-- Card: Total Kendaraan -->
            <div
                class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 p-4 sm:p-6 text-white shadow-xl group hover:shadow-blue-500/40 transition-all duration-300 hover:-translate-y-1">
                <div
                    class="absolute -top-4 -right-4 w-16 sm:w-24 h-16 sm:h-24 bg-white/10 rounded-full blur-lg group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2 sm:mb-4">
                        <div class="p-2 sm:p-3 bg-white/20 rounded-lg sm:rounded-xl backdrop-blur-sm">
                            <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1">
                                </path>
                            </svg>
                        </div>
                        <a href="{{ route('satker.kendaraans.index') }}"
                            class="text-[10px] sm:text-xs font-semibold bg-white/20 hover:bg-white/30 px-2 sm:px-3 py-1 rounded-lg transition">Kelola
                            →</a>
                    </div>
                    <p class="text-2xl sm:text-3xl font-extrabold">{{ $totalKendaraan }}</p>
                    <p class="text-xs sm:text-sm text-blue-100 mt-0.5 sm:mt-1">Total Kendaraan</p>
                </div>
            </div>

            <!-- Card: Total Personel -->
            <div
                class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 p-4 sm:p-6 text-white shadow-xl group hover:shadow-emerald-500/40 transition-all duration-300 hover:-translate-y-1">
                <div
                    class="absolute -top-4 -right-4 w-16 sm:w-24 h-16 sm:h-24 bg-white/10 rounded-full blur-lg group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2 sm:mb-4">
                        <div class="p-2 sm:p-3 bg-white/20 rounded-lg sm:rounded-xl backdrop-blur-sm">
                            <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <a href="{{ route('satker.personels.index') }}"
                            class="text-[10px] sm:text-xs font-semibold bg-white/20 hover:bg-white/30 px-2 sm:px-3 py-1 rounded-lg transition">Kelola
                            →</a>
                    </div>
                    <p class="text-2xl sm:text-3xl font-extrabold">{{ $totalPersonel }}</p>
                    <p class="text-xs sm:text-sm text-emerald-100 mt-0.5 sm:mt-1">Total Personel</p>
                </div>
            </div>

            <!-- Card: Total Transaksi BBM -->
            <div
                class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-violet-500 to-purple-700 p-4 sm:p-6 text-white shadow-xl group hover:shadow-violet-500/40 transition-all duration-300 hover:-translate-y-1">
                <div
                    class="absolute -top-4 -right-4 w-16 sm:w-24 h-16 sm:h-24 bg-white/10 rounded-full blur-lg group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2 sm:mb-4">
                        <div class="p-2 sm:p-3 bg-white/20 rounded-lg sm:rounded-xl backdrop-blur-sm">
                            <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl sm:text-3xl font-extrabold">{{ $totalTransaksi }}</p>
                    <p class="text-xs sm:text-sm text-violet-100 mt-0.5 sm:mt-1">Total Transaksi BBM</p>
                </div>
            </div>

            <!-- Card: Total Transfer -->
            <div
                class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 p-4 sm:p-6 text-white shadow-xl group hover:shadow-amber-500/40 transition-all duration-300 hover:-translate-y-1">
                <div
                    class="absolute -top-4 -right-4 w-16 sm:w-24 h-16 sm:h-24 bg-white/10 rounded-full blur-lg group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2 sm:mb-4">
                        <div class="p-2 sm:p-3 bg-white/20 rounded-lg sm:rounded-xl backdrop-blur-sm">
                            <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <a href="{{ route('satker.kendaraans.laporan-transfer') }}"
                            class="text-[10px] sm:text-xs font-semibold bg-white/20 hover:bg-white/30 px-2 sm:px-3 py-1 rounded-lg transition">Laporan
                            →</a>
                    </div>
                    <p class="text-2xl sm:text-3xl font-extrabold">{{ $totalTransfer }}</p>
                    <p class="text-xs sm:text-sm text-amber-100 mt-0.5 sm:mt-1">Total Transfer</p>
                </div>
            </div>
        </div>

        <!-- Secondary Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <div
                class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/70 p-4 sm:p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="p-2 sm:p-3 bg-sky-100 text-sky-600 rounded-lg sm:rounded-xl">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                            </path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm text-slate-500">Saldo Kendaraan</p>
                        <p class="text-lg sm:text-2xl font-bold text-slate-900 truncate">
                            {{ number_format($totalSaldoKendaraan, 0, ',', '.') }} <span
                                class="text-xs sm:text-sm font-medium text-slate-400">L</span>
                        </p>
                        <div class="mt-1 sm:mt-2 flex flex-wrap gap-1">
                            @foreach($saldoKendaraanPerBbm as $bbm => $total)
                                <span
                                    class="text-[8px] sm:text-[9px] font-bold bg-sky-50 text-sky-700 px-1.5 py-0.5 rounded border border-sky-100 uppercase">{{ $bbm }}:
                                    {{ number_format($total, 0, ',', '.') }} L</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/70 p-4 sm:p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="p-2 sm:p-3 bg-pink-100 text-pink-600 rounded-lg sm:rounded-xl">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm text-slate-500">Saldo Personel</p>
                        <p class="text-lg sm:text-2xl font-bold text-slate-900 truncate">
                            {{ number_format($totalSaldoPersonel, 0, ',', '.') }} <span
                                class="text-xs sm:text-sm font-medium text-slate-400">L</span>
                        </p>
                        <div class="mt-1 sm:mt-2 flex flex-wrap gap-1">
                            @foreach($saldoPersonelPerBbm as $bbm => $total)
                                <span
                                    class="text-[8px] sm:text-[9px] font-bold bg-pink-50 text-pink-700 px-1.5 py-0.5 rounded border border-pink-100 uppercase">{{ $bbm }}:
                                    {{ number_format($total, 0, ',', '.') }} L</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/70 p-4 sm:p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="p-2 sm:p-3 bg-teal-100 text-teal-600 rounded-lg sm:rounded-xl">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm text-slate-500">Transfer</p>
                        <p class="text-lg sm:text-2xl font-bold text-slate-900 truncate">
                            {{ number_format($totalLiterTransfer, 0, ',', '.') }} <span
                                class="text-xs sm:text-sm font-medium text-slate-400">L</span>
                        </p>
                        <div class="mt-1 sm:mt-2 flex flex-wrap gap-1">
                            @foreach($literTransferPerBbm as $bbm => $total)
                                <span
                                    class="text-[8px] sm:text-[9px] font-bold bg-teal-50 text-teal-700 px-1.5 py-0.5 rounded border border-teal-100 uppercase">{{ $bbm }}:
                                    {{ number_format($total, 0, ',', '.') }} L</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/70 p-4 sm:p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="p-2 sm:p-3 bg-rose-100 text-rose-600 rounded-lg sm:rounded-xl">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm text-slate-500">Total Hutang (Bon)</p>
                        <p class="text-lg sm:text-2xl font-bold text-slate-900 truncate">
                            {{ number_format($totalHutang, 0, ',', '.') }} <span
                                class="text-xs sm:text-sm font-medium text-slate-400">L</span>
                        </p>
                        <div class="mt-1 sm:mt-2 flex flex-wrap gap-1">
                            @foreach($hutangPerBbm as $bbm => $total)
                                <span
                                    class="text-[8px] sm:text-[9px] font-bold bg-rose-50 text-rose-700 px-1.5 py-0.5 rounded border border-rose-100 uppercase">{{ $bbm }}:
                                    {{ number_format($total, 0, ',', '.') }} L</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart & Recent Activity -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-3 sm:gap-6">
            <!-- Chart Area -->
            <div
                class="xl:col-span-2 bg-white rounded-xl sm:rounded-2xl border border-slate-200/70 shadow-sm p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold text-slate-800 mb-2 sm:mb-4">Transfer 7 Hari Terakhir</h3>
                <div id="transferChart"></div>
            </div>

            <!-- Recent Transfers -->
            <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/70 shadow-sm">
                <div class="p-4 sm:p-6 border-b border-slate-100">
                    <h3 class="text-base sm:text-lg font-bold text-slate-800">Aktivitas Terbaru</h3>
                </div>
                <div class="divide-y divide-slate-100 max-h-[300px] sm:max-h-[400px] overflow-y-auto">
                    @forelse($recentTransfers as $trx)
                        <div class="px-4 py-3 sm:px-6 sm:py-4 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div
                                    class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold text-xs sm:text-sm">
                                    {{ substr($trx->kendaraan->no_polisi ?? '?', 0, 2) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs sm:text-sm font-semibold text-slate-800 truncate">
                                        {{ $trx->kendaraan->no_polisi ?? '-' }} → {{ $trx->personel->nama ?? '-' }}
                                    </p>
                                    <p class="text-[10px] sm:text-xs text-slate-400">{{ $trx->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs sm:text-sm font-bold text-emerald-600">
                                        {{ number_format($trx->jumlah, 0, ',', '.') }} L
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <p class="text-sm text-slate-400">Belum ada aktivitas transfer</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Script -->
    <script>
        document.addEventListener('turbo:load', function () {
            var options = {
                chart: {
                    type: 'area',
                    height: 320,
                    fontFamily: 'Outfit, sans-serif',
                    toolbar: { show: false },
                    zoom: { enabled: false },
                },
                series: [{
                    name: 'Liter Transfer',
                    data: {!! json_encode(array_column($chartData, 'liter')) !!}
                }, {
                    name: 'Jumlah Transfer',
                    data: {!! json_encode(array_column($chartData, 'count')) !!}
                }],
                xaxis: {
                    categories: {!! json_encode(array_column($chartData, 'date')) !!},
                    labels: { style: { colors: '#94a3b8', fontSize: '12px' } },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                },
                yaxis: {
                    labels: { style: { colors: '#94a3b8', fontSize: '10px' } }, // Smaller font for Y axis on mobile
                },
                colors: ['#059669', '#6366f1'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.05,
                        stops: [0, 100]
                    }
                },
                stroke: { curve: 'smooth', width: 3 },
                dataLabels: { enabled: false },
                grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                tooltip: {
                    theme: 'dark',
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                }
            };
            var chart = new ApexCharts(document.querySelector("#transferChart"), options);
            chart.render();
        });
    </script>
</x-app-layout>