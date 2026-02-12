<x-app-layout>
    <div class="p-6 lg:p-8 space-y-8">
        <!-- Page Title -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Super Admin Overview</h1>
                <p class="mt-1 text-slate-500">Selamat datang, {{ Auth::user()->name }}. Berikut ringkasan sistem.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold text-sm hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Laporan
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        @php
            $totalSatker = \App\Models\Satker::count();
            $totalUsers = \App\Models\User::count();
            $totalKendaraan = \App\Models\Kendaraan::count();
            $totalTransaksi = \App\Models\TransaksiBbm::count();
            $totalPersonel = \App\Models\Personel::count();
            $totalLiter = \App\Models\TransaksiBbm::sum('liter');
            $recentTransactions = \App\Models\TransaksiBbm::with(['kendaraan', 'petugas'])->orderBy('created_at', 'desc')->take(7)->get();
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
            <!-- Card: Total Satker -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 p-6 text-white shadow-xl group hover:shadow-indigo-500/40 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-lg group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <a href="{{ route('admin.satkers.index') }}" class="text-xs font-semibold bg-white/20 hover:bg-white/30 px-3 py-1 rounded-lg transition">Kelola →</a>
                    </div>
                    <p class="text-3xl font-extrabold">{{ $totalSatker }}</p>
                    <p class="text-sm text-indigo-100 mt-1">Total Satker</p>
                </div>
            </div>

            <!-- Card: Total Users -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 p-6 text-white shadow-xl group hover:shadow-emerald-500/40 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-lg group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold bg-white/20 hover:bg-white/30 px-3 py-1 rounded-lg transition">Kelola →</a>
                    </div>
                    <p class="text-3xl font-extrabold">{{ $totalUsers }}</p>
                    <p class="text-sm text-emerald-100 mt-1">Total Users</p>
                </div>
            </div>

            <!-- Card: Total Kendaraan -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 p-6 text-white shadow-xl group hover:shadow-amber-500/40 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-lg group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 012-2h0m8 0a2 2 0 00-2 2h0m8 0a2 2 0 00-2-2h0"></path></svg>
                        </div>
                    </div>
                    <p class="text-3xl font-extrabold">{{ $totalKendaraan }}</p>
                    <p class="text-sm text-amber-100 mt-1">Total Kendaraan</p>
                </div>
            </div>

            <!-- Card: Total Transaksi -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-500 to-pink-600 p-6 text-white shadow-xl group hover:shadow-rose-500/40 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-lg group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                    </div>
                    <p class="text-3xl font-extrabold">{{ $totalTransaksi }}</p>
                    <p class="text-sm text-rose-100 mt-1">Total Transaksi</p>
                </div>
            </div>
        </div>

        <!-- Secondary Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl border border-slate-200/70 p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-violet-100 text-violet-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Total Personel</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $totalPersonel }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/70 p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-sky-100 text-sky-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Total Liter Disalurkan</p>
                        <p class="text-2xl font-bold text-slate-900">{{ number_format($totalLiter, 0) }} L</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart & Recent Transactions -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Chart Area -->
            <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200/70 shadow-sm p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Transaksi 7 Hari Terakhir</h3>
                <div id="transactionChart"></div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">Aktivitas Terbaru</h3>
                </div>
                <div class="divide-y divide-slate-100 max-h-[400px] overflow-y-auto">
                    @foreach($recentTransactions as $trx)
                    <div class="px-6 py-4 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm">
                                {{ substr($trx->kendaraan->no_polisi ?? '?', 0, 2) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-800 truncate">{{ $trx->kendaraan->no_polisi ?? '-' }}</p>
                                <p class="text-xs text-slate-400">{{ $trx->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-slate-700">{{ number_format($trx->liter, 1) }} L</p>
                                <p class="text-xs text-slate-400">Rp {{ number_format($trx->total, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Script -->
    @php
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = \App\Models\TransaksiBbm::whereDate('created_at', $date)->count();
            $chartData[] = ['date' => $date->format('d M'), 'count' => $count];
        }
    @endphp
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                chart: {
                    type: 'area',
                    height: 320,
                    fontFamily: 'Outfit, sans-serif',
                    toolbar: { show: false },
                    zoom: { enabled: false },
                },
                series: [{
                    name: 'Transaksi',
                    data: {!! json_encode(array_column($chartData, 'count')) !!}
                }],
                xaxis: {
                    categories: {!! json_encode(array_column($chartData, 'date')) !!},
                    labels: { style: { colors: '#94a3b8', fontSize: '12px' } },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                },
                yaxis: {
                    labels: { style: { colors: '#94a3b8', fontSize: '12px' } },
                },
                colors: ['#4338ca'],
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
                    y: { formatter: function(val) { return val + ' transaksi'; } }
                }
            };
            var chart = new ApexCharts(document.querySelector("#transactionChart"), options);
            chart.render();
        });
    </script>
</x-app-layout>
