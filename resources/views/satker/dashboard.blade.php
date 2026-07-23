<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-4 sm:space-y-8">
        <!-- Page Title -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-white">Dashboard</h1>

            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-6">
            <!-- Card: Total Kendaraan -->
            <div
                class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-amber-500 to-amber-800 border border-amber-400 shadow-xl p-4 sm:p-6 text-white shadow-lg shadow-amber-500/5 group hover:shadow-amber-500/20 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-slate-500/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2 sm:mb-4">
                        <div class="p-2 sm:p-3 bg-white/20 border border-white/30 rounded-lg sm:rounded-xl">
                            <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1">
                                </path>
                            </svg>
                        </div>
                        <a href="{{ route('satker.kendaraans.index') }}"
                            class="text-[10px] sm:text-xs font-semibold bg-white/20 hover:bg-white/30 border border-white/30 px-3 py-1.5 shadow-sm rounded-lg transition">Kelola
                            →</a>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-baseline gap-0 sm:gap-2 mt-1 sm:mt-0">
                        <p class="text-xl sm:text-2xl font-black text-white">{{ $totalKendaraan }}</p>
                        <p class="text-[9px] sm:text-[11px] text-white/90 font-medium truncate">Total Kendaraan</p>
                    </div>
                    <div class="grid grid-cols-4 gap-1 mt-2 sm:mt-3">
                        <div class="bg-white/20 border border-white/30 shadow-sm rounded-lg px-1.5 py-1 text-center">
                            <p class="text-[8px] sm:text-[9px] font-bold opacity-80">R2</p>
                            <p class="text-xs sm:text-sm font-black">{{ $rodaR2 }}</p>
                        </div>
                        <div class="bg-white/20 border border-white/30 shadow-sm rounded-lg px-1.5 py-1 text-center">
                            <p class="text-[8px] sm:text-[9px] font-bold opacity-80">R4</p>
                            <p class="text-xs sm:text-sm font-black">{{ $rodaR4 }}</p>
                        </div>
                        <div class="bg-white/20 border border-white/30 shadow-sm rounded-lg px-1.5 py-1 text-center">
                            <p class="text-[8px] sm:text-[9px] font-bold opacity-80">R6</p>
                            <p class="text-xs sm:text-sm font-black">{{ $rodaR6 }}</p>
                        </div>
                        <div class="bg-white/20 border border-white/30 shadow-sm rounded-lg px-1.5 py-1 text-center">
                            <p class="text-[8px] sm:text-[9px] font-bold opacity-80 leading-tight">Non</p>
                            <p class="text-xs sm:text-sm font-black">{{ $rodaNon }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Total Transaksi BBM -->
            <div
                class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-rose-600 to-rose-900 border border-rose-400 shadow-xl p-4 sm:p-6 text-white shadow-lg shadow-rose-500/5 group hover:shadow-rose-500/20 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-slate-500/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2 sm:mb-4">
                        <div class="p-2 sm:p-3 bg-white/20 border border-white/30 rounded-lg sm:rounded-xl">
                            <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-baseline gap-0 sm:gap-2 mt-1 sm:mt-0">
                        <p class="text-xl sm:text-2xl font-black text-white">{{ $totalTransaksi }}</p>
                        <p class="text-[9px] sm:text-[11px] text-white/90 font-medium truncate">Total Transaksi BBM</p>
                    </div>
                    <div class="mt-2 sm:mt-3 flex gap-2">
                        <div class="flex-1 bg-white/20 border border-white/30 shadow-sm rounded-lg px-2 py-1 text-center flex flex-col justify-center">
                            <p class="text-[8px] sm:text-[9px] font-bold opacity-80 text-white/90 uppercase">Sistem Pencatatan</p>
                            <p class="text-[10px] sm:text-xs font-black text-emerald-300 mt-0.5 truncate flex items-center justify-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_8px_rgba(52,211,153,0.8)]"></span> Realtime
                            </p>
                        </div>
                        <div class="flex-1 bg-white/20 border border-white/30 shadow-sm rounded-lg px-2 py-1 text-center flex flex-col justify-center">
                            <p class="text-[8px] sm:text-[9px] font-bold opacity-80 text-white/90 uppercase">Validasi</p>
                            <p class="text-[10px] sm:text-xs font-black text-white mt-0.5">Sistem</p>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Card: Saldo Kendaraan -->
            <div
                class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-sky-500 to-sky-800 border border-sky-400 shadow-xl p-4 sm:p-6 text-white shadow-lg shadow-sky-500/5 group hover:shadow-sky-500/20 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-slate-500/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2 sm:mb-4">
                        <div class="p-2 sm:p-3 bg-white/20 border border-white/30 rounded-lg sm:rounded-xl">
                            <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-baseline gap-0 sm:gap-2 mt-1 sm:mt-0">
                        <p class="text-xl sm:text-2xl font-black text-white">{{ rtrim(rtrim(number_format($totalSaldoKendaraan, 2, ',', '.'), '0'), ',') }} <span class="text-xs sm:text-sm font-medium opacity-80">L</span></p>
                        <p class="text-[9px] sm:text-[11px] text-white/90 font-medium truncate">Saldo Kendaraan</p>
                    </div>
                    <div class="mt-2 sm:mt-3 flex gap-2">
                        @foreach($saldoKendaraanPerBbm as $bbm => $total)
                            <div class="flex-1 bg-white/20 border border-white/30 shadow-sm rounded-lg px-2 py-1 text-center flex flex-col justify-center">
                                <p class="text-[8px] sm:text-[9px] font-bold opacity-80 text-white/90 uppercase truncate">{{ $bbm }}</p>
                                <p class="text-[10px] sm:text-xs font-black text-white mt-0.5">{{ rtrim(rtrim(number_format($total, 2, ',', '.'), '0'), ',') }} <span class="text-[8px] font-bold opacity-80">L</span></p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Card: Total Hutang (Bon) -->
            <div
                class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-800 border border-indigo-400 shadow-xl p-4 sm:p-6 text-white shadow-lg shadow-indigo-500/5 group hover:shadow-indigo-500/20 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-slate-500/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2 sm:mb-4">
                        <div class="p-2 sm:p-3 bg-white/20 border border-white/30 rounded-lg sm:rounded-xl">
                            <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-baseline gap-0 sm:gap-2 mt-1 sm:mt-0">
                        <p class="text-xl sm:text-2xl font-black text-white">{{ rtrim(rtrim(number_format($totalHutang, 2, ',', '.'), '0'), ',') }} <span class="text-xs sm:text-sm font-medium opacity-80">L</span></p>
                        <p class="text-[9px] sm:text-[11px] text-white/90 font-medium truncate">Total Hutang (Bon)</p>
                    </div>
                    <div class="mt-2 sm:mt-3 flex gap-2">
                        @foreach($hutangPerBbm as $bbm => $total)
                            <div class="flex-1 bg-white/20 border border-white/30 shadow-sm rounded-lg px-2 py-1 text-center flex flex-col justify-center">
                                <p class="text-[8px] sm:text-[9px] font-bold opacity-80 text-white/90 uppercase truncate">{{ $bbm }}</p>
                                <p class="text-[10px] sm:text-xs font-black text-white mt-0.5">{{ rtrim(rtrim(number_format($total, 2, ',', '.'), '0'), ',') }} <span class="text-[8px] font-bold opacity-80">L</span></p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart & Recent Activity -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-3 sm:gap-6 mt-2 sm:mt-4">
            <!-- Chart Area -->
            <div
                class="xl:col-span-2 bg-slate-900 border border-white/5 rounded-xl sm:rounded-2xl border border-white/10/70 shadow-sm p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold text-slate-200 mb-2 sm:mb-4">Transaksi 7 Hari Terakhir
                </h3>
                <div id="transactionChart"></div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-slate-900 border border-white/5 rounded-xl sm:rounded-2xl border border-white/10/70 shadow-sm">
                <div class="p-4 sm:p-6 border-b border-white/5">
                    <h3 class="text-base sm:text-lg font-bold text-slate-200">Aktivitas Terbaru</h3>
                </div>
                <div class="divide-y divide-white/5 max-h-[300px] sm:max-h-[400px] overflow-y-auto">
                    @forelse($recentTransactions as $trx)
                        <div class="px-4 py-3 sm:px-6 sm:py-4 hover:bg-slate-800/50 transition-colors">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div
                                    class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold text-xs sm:text-sm">
                                    {{ substr($trx->kendaraan->no_polisi ?? '?', 0, 2) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs sm:text-xs font-medium text-slate-200 truncate">
                                        {{ $trx->kendaraan->no_polisi ?? '-' }}
                                    </p>
                                    <p class="text-[10px] sm:text-xs text-slate-400">
                                        {{ $trx->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs sm:text-sm font-bold text-slate-300">
                                        {{ number_format($trx->liter, 0, ',', '.') }} L</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <p class="text-xs text-slate-400">Belum ada aktivitas transaksi</p>
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
                    name: 'Liter Transaksi',
                    data: {!! json_encode(array_column($chartData, 'liter')) !!}
                }, {
                    name: 'Jumlah Transaksi',
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
            var chart = new ApexCharts(document.querySelector("#transactionChart"), options);
            chart.render();
        });
    </script>
</x-app-layout>