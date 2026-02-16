<x-app-layout>
    <div class="p-6 lg:p-8 space-y-8">
        <!-- Page Title -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Super Admin Overview</h1>
                <p class="mt-1 text-slate-500">Selamat datang, {{ Auth::user()->name }}. Berikut ringkasan sistem.</p>
            </div>
            <div class="flex items-center gap-3">
            </div>
        </div>

        <!-- Main Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <!-- Card: Total Satker -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 p-5 text-white shadow-lg group hover:shadow-indigo-500/40 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-lg group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2.5 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <a href="{{ route('admin.satkers.index') }}" class="text-[10px] font-semibold bg-white/20 hover:bg-white/30 px-2 py-1 rounded-lg transition uppercase tracking-wider">Kelola</a>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <p class="text-2xl font-black">{{ $stats['totalSatker'] }}</p>
                        <p class="text-[11px] text-indigo-100 opacity-80 font-medium">Total Satker</p>
                    </div>
                </div>
            </div>

            <!-- Card: Total Users -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 p-5 text-white shadow-lg group hover:shadow-emerald-500/40 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-lg group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2.5 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="text-[10px] font-semibold bg-white/20 hover:bg-white/30 px-2 py-1 rounded-lg transition uppercase tracking-wider">Kelola</a>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <p class="text-2xl font-black">{{ $stats['totalUsers'] }}</p>
                        <p class="text-[11px] text-emerald-100 opacity-80 font-medium">Total Users</p>
                    </div>
                </div>
            </div>

            <!-- Card: Total Kendaraan -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 p-5 text-white shadow-lg group hover:shadow-amber-500/40 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-lg group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2.5 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1"></path></svg>
                        </div>
                        <a href="{{ route('admin.kendaraans.index') }}" class="text-[10px] font-semibold bg-white/20 hover:bg-white/30 px-2 py-1 rounded-lg transition uppercase tracking-wider">Kelola</a>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <p class="text-2xl font-black">{{ $stats['totalKendaraan'] }}</p>
                        <p class="text-[11px] text-amber-100 opacity-80 font-medium">Total Kendaraan</p>
                    </div>
                </div>
            </div>

            <!-- Card: Total Transaksi -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-500 to-pink-600 p-5 text-white shadow-lg group hover:shadow-rose-500/40 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-lg group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2.5 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                        <a href="{{ route('admin.riwayat.index') }}" class="text-[10px] font-semibold bg-white/20 hover:bg-white/30 px-2 py-1 rounded-lg transition uppercase tracking-wider">Riwayat</a>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <p class="text-2xl font-black">{{ $stats['totalTransaksi'] }}</p>
                        <p class="text-[11px] text-rose-100 opacity-80 font-medium">Total Transaksi</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-4">
            <!-- Left Column: Unit & Personel Balance -->
            <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-6 overflow-hidden relative">
                <div class="absolute top-0 right-0 p-4 opacity-5">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08s5.97 1.09 6 3.08c-1.29 1.94-3.5 3.22-6 3.22zM18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.85 7h10.29l1.04 3H5.81l1.04-3zM19 17H5v-5h14v5z"/></svg>
                </div>
                
                <div class="grid grid-cols-1 gap-8 relative z-10">
                    <!-- Unit Section -->
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <div class="w-1.5 h-4 bg-amber-500 rounded-full"></div>
                            Total Saldo BBM Kendaraan
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            @php
                                $bbmStyle = [
                                    'Pertamax' => 'from-blue-500 to-indigo-600',
                                    'Pertamina Dex' => 'from-rose-500 to-red-600',
                                ];
                            @endphp
                            @foreach($kendaraanFuel as $kFuel)
                                @if(isset($bbmStyle[$kFuel->jenis_bbm]))
                                <div class="bg-gradient-to-br {{ $bbmStyle[$kFuel->jenis_bbm] }} p-4 rounded-xl text-white shadow-md">
                                    <p class="text-[9px] font-bold uppercase tracking-widest opacity-80">{{ $kFuel->jenis_bbm }}</p>
                                    <div class="flex items-baseline gap-1 mt-1">
                                        <span class="text-xl font-black">{{ number_format($kFuel->total, 0, ',', '.') }}</span>
                                        <span class="text-[10px] font-bold opacity-70">L</span>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                            @if(count($kendaraanFuel) == 0)
                                <p class="col-span-2 text-center text-slate-400 py-4 italic">Belum ada data saldo kendaraan.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Separator -->
                    <div class="border-t border-slate-100"></div>

                    <!-- Personel Section -->
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <div class="w-1.5 h-4 bg-indigo-500 rounded-full"></div>
                            Total Saldo BBM Personel
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($personelFuel as $pFuel)
                                <div class="bg-gradient-to-br {{ $bbmStyle[$pFuel->jenis_bbm] ?? 'from-slate-500 to-slate-600' }} p-4 rounded-xl text-white shadow-md">
                                    <p class="text-[9px] font-bold uppercase tracking-widest opacity-80">{{ $pFuel->jenis_bbm }}</p>
                                    <div class="flex items-baseline gap-1 mt-1">
                                        <span class="text-xl font-black">{{ number_format($pFuel->total, 0, ',', '.') }}</span>
                                        <span class="text-[10px] font-bold opacity-70">L</span>
                                    </div>
                                </div>
                            @endforeach
                            @if(count($personelFuel) == 0)
                                <p class="col-span-2 text-center text-slate-400 py-4 italic text-sm">Belum ada data saldo personel.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Stok BBM Pusat (Super Admin) -->
            <div class="bg-indigo-600 rounded-2xl border border-indigo-400 shadow-xl p-6 overflow-hidden relative">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
                <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2 relative z-10">
                    <div class="w-2 h-6 bg-white rounded-full"></div>
                    Stok BBM Pusat (Super Admin)
                </h3>
                <div class="grid grid-cols-1 gap-4 relative z-10">
                    @foreach($adminStocks as $aStock)
                        <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20">
                            <p class="text-[10px] font-bold text-white/70 uppercase tracking-widest leading-tight mb-1">{{ $aStock->jenis_bbm }}</p>
                            <div class="flex items-baseline justify-between mt-1">
                                <span class="text-3xl font-black text-white">{{ number_format($aStock->saldo, 0, ',', '.') }} <span class="text-sm font-bold text-white/50">L</span></span>
                                <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8 text-right relative z-10">
                    <a href="{{ route('admin.stok.index') }}" class="text-sm font-bold text-white bg-indigo-500 hover:bg-white hover:text-indigo-600 px-6 py-3 rounded-xl transition-all shadow-lg active:scale-95 flex items-center gap-2 inline-flex">
                        Kelola Stok Pusat
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mt-4">
            <!-- Left Column: Charts -->
            <div class="xl:col-span-2 space-y-6">
                <!-- Satisfaction Index Chart -->
                <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-6 overflow-hidden relative">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-slate-800">Indeks Kepuasan Petugas</h3>
                        <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Sangat Puas
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span> Puas
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> Tidak Puas
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                        <div class="space-y-3 order-2 md:order-1">
                            <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-xl">🤩</span>
                                    <div>
                                        <p class="text-[10px] font-bold text-emerald-800 uppercase leading-none mb-1">Sangat Puas</p>
                                        <p class="text-xs font-medium text-emerald-600 leading-none">{{ $satisfactionStats['p_sangat_puas'] }}% dari total</p>
                                    </div>
                                </div>
                                <span class="text-lg font-black text-emerald-700">{{ $satisfactionStats['sangat_puas'] }}</span>
                            </div>
                            <div class="p-3 bg-amber-50 rounded-xl border border-amber-100 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-xl">🙂</span>
                                    <div>
                                        <p class="text-[10px] font-bold text-amber-800 uppercase leading-none mb-1">Puas</p>
                                        <p class="text-xs font-medium text-amber-600 leading-none">{{ $satisfactionStats['p_puas'] }}% dari total</p>
                                    </div>
                                </div>
                                <span class="text-lg font-black text-amber-700">{{ $satisfactionStats['puas'] }}</span>
                            </div>
                            <div class="p-3 bg-rose-50 rounded-xl border border-rose-100 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-xl">😡</span>
                                    <div>
                                        <p class="text-[10px] font-bold text-rose-800 uppercase leading-none mb-1">Tidak Puas</p>
                                        <p class="text-xs font-medium text-rose-600 leading-none">{{ $satisfactionStats['p_tidak_puas'] }}% dari total</p>
                                    </div>
                                </div>
                                <span class="text-lg font-black text-rose-700">{{ $satisfactionStats['tidak_puas'] }}</span>
                            </div>
                        </div>
                        <div id="satisfactionDonutChart" class="min-h-[220px] order-1 md:order-2"></div>
                    </div>
                </div>

                <!-- Transaction Chart Area -->
                <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Transaksi 7 Hari Terakhir</h3>
                    <div id="transactionChart"></div>
                </div>
            </div>

            <!-- Right Column: Recent Transactions & Stats -->
            <div class="space-y-6">
                <!-- Secondary Stats Grid -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white rounded-2xl border border-slate-200/70 p-4 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-violet-100 text-violet-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Personel</p>
                                <p class="text-lg font-black text-slate-900">{{ $stats['totalPersonel'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl border border-slate-200/70 p-4 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-sky-100 text-sky-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Total Liter</p>
                                <p class="text-lg font-black text-slate-900">{{ number_format($stats['totalLiter'], 0, ',', '.') }}L</p>
                            </div>
                        </div>
                    </div>
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
                                    {{ substr($trx->kendaraan->no_polisi ?? ($trx->personel->nrp ?? '?'), 0, 2) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-800 truncate">{{ $trx->kendaraan->no_polisi ?? ($trx->personel->nama ?? 'Personel') }}</p>
                                    <p class="text-xs text-slate-400">{{ $trx->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-slate-700">{{ number_format($trx->liter, 0, ',', '.') }} L</p>
                                    <p class="text-xs text-slate-400">Total: {{ number_format($trx->liter, 0, ',', '.') }} L</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div id="userMap" class="w-full h-[400px] rounded-xl z-0 bg-slate-100 flex items-center justify-center text-slate-400" style="height: 400px; min-height: 400px;">
            Memuat Peta...
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

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        .leaflet-popup-content-wrapper { border-radius: 12px; padding: 0.5rem; }
        .leaflet-popup-tip { background: white; }
    </style>
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <!-- Map Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var mapContainer = document.getElementById('userMap');
            if(mapContainer) {
                // Map Initialization
                var map = L.map('userMap', {
                    maxBounds: [[-11.0, 95.0], [6.0, 141.0]],
                    minZoom: 5,
                    maxBoundsViscosity: 1.0
                }).setView([-2.5489, 118.0149], 5); // Center of Indonesia

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                var users = {!! json_encode($usersWithLocation) !!};
                var markers = [];

                function getRoleColor(role) {
                    switch(role) {
                        case 'super_admin': return 'bg-rose-500';
                        case 'admin_satker': return 'bg-indigo-600';
                        case 'petugas_bbm': return 'bg-amber-500';
                        case 'personel': return 'bg-emerald-500';
                        default: return 'bg-slate-500';
                    }
                }

                users.forEach(function(user) {
                    if(user.last_latitude && user.last_longitude) {
                        var roleColor = getRoleColor(user.role);
                        var initials = user.name.substring(0, 2).toUpperCase();
                        
                        var iconHtml = `
                            <div class="relative flex items-center justify-center w-8 h-8 rounded-full shadow-lg border-2 border-white ${roleColor} text-white font-bold text-xs transform hover:scale-110 transition-transform">
                                ${initials}
                                <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-[4px] border-l-transparent border-r-[4px] border-r-transparent border-t-[6px] border-t-white opacity-50"></div>
                            </div>
                        `;

                        var customIcon = L.divIcon({
                            html: iconHtml,
                            className: '', // Remove default Leaflet class styles
                            iconSize: [32, 32],
                            iconAnchor: [16, 32],
                            popupAnchor: [0, -32]
                        });

                        var marker = L.marker([user.last_latitude, user.last_longitude], {icon: customIcon}).addTo(map);
                        
                        var lastActive = new Date(user.last_activity_at).toLocaleString('id-ID');
                        var roleLabel = user.role.replace('_', ' ').toUpperCase();

                        var popupContent = `
                            <div class="p-2 min-w-[200px]">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="w-8 h-8 rounded-full ${roleColor} flex items-center justify-center text-white text-xs font-bold shadow-sm">${initials}</span>
                                    <div>
                                        <h4 class="font-bold text-slate-800 text-sm leading-tight">${user.name}</h4>
                                        <p class="text-[10px] text-indigo-600 font-bold tracking-wide">${roleLabel}</p>
                                    </div>
                                </div>
                                <div class="text-xs text-slate-500 border-t border-slate-100 pt-2 space-y-1">
                                    <p class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span class="font-medium">Aktif:</span> ${lastActive}
                                    </p>
                                    <p class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        <span class="font-medium">Lokasi:</span> ${user.last_latitude.toFixed(4)}, ${user.last_longitude.toFixed(4)}
                                    </p>
                                </div>
                            </div>
                        `;
                        
                        marker.bindPopup(popupContent);
                        markers.push(marker);
                    }
                });

                if (markers.length > 0) {
                    var group = new L.featureGroup(markers);
                    map.fitBounds(group.getBounds().pad(0.1));
                }
                
                // Force map resize check
                setTimeout(function(){ map.invalidateSize(); }, 500);
            }
        });
    </script>

    <!-- Chart Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Satisfaction Donut Chart
            var satisfactionOptions = {
                chart: {
                    type: 'donut',
                    height: 220,
                    fontFamily: 'Outfit, sans-serif',
                },
                series: [
                    {{ $satisfactionStats['sangat_puas'] }}, 
                    {{ $satisfactionStats['puas'] }}, 
                    {{ $satisfactionStats['tidak_puas'] }}
                ],
                labels: ['Sangat Puas', 'Puas', 'Tidak Puas'],
                colors: ['#10b981', '#fbbf24', '#f43f5e'], // Emerald-500, Amber-400, Rose-500
                legend: { show: false },
                dataLabels: { enabled: false },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    formatter: function (w) {
                                        return {{ $satisfactionStats['total'] }}
                                    }
                                }
                            }
                        }
                    }
                },
                tooltip: { theme: 'dark' }
            };
            var satisfactionChart = new ApexCharts(document.querySelector("#satisfactionDonutChart"), satisfactionOptions);
            satisfactionChart.render();

            // Transaction Chart Initialization (Existing)
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
            var markers = [];

            users.forEach(function(user) {
                if(user.last_latitude && user.last_longitude) {
                    var marker = L.marker([user.last_latitude, user.last_longitude]).addTo(map);
                    
                    var lastActive = new Date(user.last_activity_at).toLocaleString('id-ID');
                    var roleLabel = user.role.replace('_', ' ').toUpperCase();

                    var popupContent = `
                        <div class="p-2 min-w-[150px]">
                            <h4 class="font-bold text-slate-800 text-sm mb-1">${user.name}</h4>
                            <p class="text-xs text-indigo-600 font-bold mb-1">${roleLabel}</p>
                            <p class="text-[10px] text-slate-500 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                ${lastActive}
                            </p>
                        </div>
                    `;
                    
                    marker.bindPopup(popupContent);
                    markers.push(marker);
                }
            });

            if (markers.length > 0) {
                var group = new L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.1));
            }
        });
    </script>
    @endpush
</x-app-layout>
