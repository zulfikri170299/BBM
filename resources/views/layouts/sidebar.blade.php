{{-- Mobile Overlay --}}
<div x-cloak x-show="sidebarOpen" style="display: none;" @click="sidebarOpen = false"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-40 bg-black/60 lg:hidden"></div>

{{-- Sidebar --}}
<div x-data="{ 
        reportsOpen: {{ (request()->routeIs('admin.laporan-slog.*') || request()->routeIs('admin.nominatif.*') || request()->routeIs('admin.laporan-topup.*') || request()->routeIs('admin.laporan-triwulan.*') || request()->routeIs('admin.laporan-stok-bbm.*') || request()->routeIs('admin.laporan-harian.*') || request()->routeIs('admin.riwayat.*') || request()->routeIs('admin.ba.*') || request()->routeIs('admin.laporan-sisa.*') || request()->routeIs('admin.laporan-hutang.*') || request()->routeIs('admin.laporan-potong.*')) ? 'true' : 'false' }},
        satkerReportsOpen: {{ (request()->routeIs('satker.riwayat.*') || request()->routeIs('satker.kendaraans.laporan-bulanan.*') || request()->routeIs('satker.kendaraans.laporan-transfer.*') || request()->routeIs('satker.laporan-hutang.*') || request()->routeIs('satker.laporan-triwulan.*')) ? 'true' : 'false' }},
        init() {
            // Force sidebar closed on mobile every page load
            if (window.innerWidth < 1024) {
                this.$dispatch('sidebar-close');
            }
            this.$el.scrollTop = localStorage.getItem('sidebarScroll') || 0;
            this.$el.addEventListener('scroll', () => {
                localStorage.setItem('sidebarScroll', this.$el.scrollTop);
            });
        }
    }" id="sidebar" 
    @close-reports.window="reportsOpen = false; satkerReportsOpen = false"
    @open-admin-reports.window="reportsOpen = true"
    @open-satker-reports.window="satkerReportsOpen = true"
    class="fixed inset-y-0 left-0 z-50 w-72 lg:w-64 overflow-y-auto transform bg-slate-900 text-white -translate-x-full lg:translate-x-0 lg:static lg:inset-0 transition-transform duration-300 ease-in-out"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    style="@media (min-width: 1024px) { display: block !important; }">

    {{-- Sidebar Header --}}
    <div class="flex items-center justify-between px-5 py-3 border-b border-slate-800/50">
        <div class="flex items-center gap-3">
            <img src="{{ asset('rolog.png') }}" alt="Logo" class="w-9 h-9 object-contain">
            <div>
                <span
                    class="text-base font-bold text-white leading-tight block">{{ auth()->user()->satker->nama_satker ?? 'BIRO LOGISTIK' }}</span>
                <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">SIM BBM - Polda NTB</span>
            </div>
        </div>
        {{-- Close Button (Mobile Only) --}}
        <button @click="sidebarOpen = false"
            class="lg:hidden p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <nav class="mt-2 px-3 space-y-0.5 pb-24 lg:pb-4">

        @if(in_array(auth()->user()->role, ['super_admin', 'kasubbag']))
            <p class="px-4 py-1 mt-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Administration</p>

            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                <span class="ml-3 font-medium">Dashboard</span>
            </a>

            @if(auth()->user()->role === 'super_admin')
                <a href="{{ route('admin.transaksi.index') }}"
                    class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('admin.transaksi.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                    <span class="ml-3 font-medium">Transaksi BBM</span>
                </a>
            @endif

            @if(auth()->user()->role === 'super_admin')
                <a href="{{ route('admin.stok.index') }}"
                    class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('admin.stok.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    <span class="ml-3 font-medium">Stok BBM</span>
                </a>

                <a href="{{ route('pembelian-bbm.index') }}"
                    class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('pembelian-bbm.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    <span class="ml-3 font-medium">Pembelian BBM</span>
                </a>
            @endif

            @if(auth()->user()->role === 'super_admin')
                <a href="{{ route('admin.meter.index') }}"
                    class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('admin.meter.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    <span class="ml-3 font-medium">Input Meter Pompa</span>
                </a>
            @endif

            <a href="{{ route('admin.hutang.index') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('admin.hutang.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span class="ml-3 font-medium">Hutang BBM</span>
            </a>

            <a href="{{ route('admin.kendaraans.index') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ (request()->routeIs('admin.kendaraans.*') && !request()->routeIs('admin.kendaraans.laporan-*')) ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1">
                    </path>
                </svg>
                <span class="ml-3 font-medium">Kendaraan</span>
            </a>

            <a href="{{ route('admin.personels.index') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('admin.personels.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <span class="ml-3 font-medium">Personel</span>
            </a>



            @if(auth()->user()->role === 'super_admin')
                <a href="{{ route('admin.transfer-saldo.index') }}"
                    class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('admin.transfer-saldo.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4">
                        </path>
                    </svg>
                    <span class="ml-3 font-medium">Transfer Saldo</span>
                </a>

                <a href="{{ route('admin.bulk-potong.index') }}"
                    class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('admin.bulk-potong.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <span class="ml-3 font-medium">Potong Saldo Masal</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('admin.users.index') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    <span class="ml-3 font-medium">Users</span>
                </a>

                <a href="{{ route('admin.users.monitoring') }}"
                    class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('admin.users.monitoring') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                    <span class="ml-3 font-medium">Monitoring User</span>
                </a>
            @endif

            @if(auth()->user()->role === 'super_admin')
                <a href="{{ route('admin.satkers.index') }}"
                    class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('admin.satkers.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    <span class="ml-3 font-medium">Satkers</span>
                </a>
            @endif

            {{-- Reports Dropdown --}}
            <div class="space-y-1">
                <button @click="reportsOpen = !reportsOpen"
                    class="w-full flex items-center justify-between px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 hover:bg-slate-800 focus:outline-none active:scale-[0.98]">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span class="ml-3 font-medium">Laporan</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200 shrink-0" :class="reportsOpen ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="reportsOpen" x-collapse style="display: none;" class="pl-10 pr-2 space-y-1">
                    <a href="{{ route('admin.laporan-slog.index') }}"
                        class="block py-1.5 px-4 text-sm font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-slog.*') ? 'text-white bg-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        Laporan Rutin
                    </a>
                    <a href="{{ route('admin.laporan-topup.index') }}"
                        class="block py-1.5 px-4 text-sm font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-topup.*') ? 'text-white bg-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        Laporan Top Up
                    </a>
                    <a href="{{ route('admin.laporan-hutang.index') }}"
                        class="block py-2.5 px-4 text-sm font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-hutang.*') ? 'text-white bg-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        Laporan Bayar Hutang
                    </a>
                    <a href="{{ route('admin.laporan-potong.index') }}"
                        class="block py-2.5 px-4 text-sm font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-potong.*') ? 'text-white bg-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        Laporan Potong Saldo
                    </a>
                    <a href="{{ route('admin.laporan-harian.index') }}"
                        class="block py-2.5 px-4 text-sm font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-harian.*') ? 'text-white bg-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        Laporan Harian
                    </a>
                    <a href="{{ route('admin.laporan-triwulan.index') }}"
                        class="block py-2.5 px-4 text-sm font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-triwulan.*') ? 'text-white bg-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        Laporan Per 3 Bulan
                    </a>
                    <a href="{{ route('admin.laporan-tahunan.index') }}"
                        class="block py-2.5 px-4 text-sm font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-tahunan.*') ? 'text-white bg-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        Laporan Tahunan
                    </a>
                    <a href="{{ route('admin.laporan-stok-bbm.index') }}"
                        class="block py-2.5 px-4 text-sm font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-stok-bbm.*') ? 'text-white bg-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        Data BBM Pada Tangki
                    </a>
                    <a href="{{ route('admin.riwayat.index') }}"
                        class="block py-2.5 px-4 text-sm font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.riwayat.*') ? 'text-white bg-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        Riwayat BBM
                    </a>
                    <a href="{{ route('admin.ba.index') }}"
                        class="block py-2.5 px-4 text-sm font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.ba.*') ? 'text-white bg-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        Berita Acara
                    </a>
                    <a href="{{ route('admin.nominatif.index') }}"
                        class="block py-2.5 px-4 text-sm font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.nominatif.*') ? 'text-white bg-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        Nominatif
                    </a>
                    <a href="{{ route('admin.laporan-sisa.kendaraan') }}"
                        class="block py-2.5 px-4 text-sm font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-sisa.kendaraan') ? 'text-white bg-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        Sisa BBM Kendaraan
                    </a>
                    <a href="{{ route('admin.laporan-sisa.personel') }}"
                        class="block py-2.5 px-4 text-sm font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-sisa.personel') ? 'text-white bg-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        Sisa BBM Personel
                    </a>
                </div>
            </div>

            @if(auth()->user()->role === 'super_admin')
                <a href="{{ route('admin.penanda-tangan.index') }}"
                    class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('admin.penanda-tangan.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    <span class="ml-3 font-medium">Penanda Tangan</span>
                </a>

                <a href="{{ route('admin.petugas-spbp.index') }}"
                    class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('admin.petugas-spbp.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                        </path>
                    </svg>
                    <span class="ml-3 font-medium">Petugas SPBP</span>
                </a>

                <a href="{{ route('admin.settings.index') }}"
                    class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="ml-3 font-medium">Pengaturan</span>
                </a>
            @endif

            <a href="{{ route('admin.broadcast.index') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('admin.broadcast.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                    </path>
                </svg>
                <span class="ml-3 font-medium">Pesan Siaran</span>
            </a>
        @endif

        @if(auth()->user()->role === 'admin_satker')
            <p class="px-4 py-2 mt-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Satker Management</p>

            <a href="{{ route('satker.dashboard') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('satker.dashboard') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                <span class="ml-3 font-medium">Dashboard</span>
            </a>



            <a href="{{ route('satker.kendaraans.index') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ (request()->routeIs('satker.kendaraans.*') && !request()->routeIs('satker.kendaraans.laporan-*')) ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1">
                    </path>
                </svg>
                <span class="ml-3 font-medium">Kendaraan</span>
            </a>

            <a href="{{ route('satker.personels.index') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('satker.personels.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <span class="ml-3 font-medium">Personel</span>
            </a>

            <a href="{{ route('satker.hutang.index') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('satker.hutang.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span class="ml-3 font-medium">Hutang BBM</span>
            </a>

            <a href="{{ route('satker.penanda-tangan.index') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('satker.penanda-tangan.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                </svg>
                <span class="ml-3 font-medium">Penanda Tangan</span>
            </a>

            {{-- Reports Dropdown (Satker) --}}
            <div class="space-y-1">
                <button @click="satkerReportsOpen = !satkerReportsOpen"
                    class="w-full flex items-center justify-between px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 hover:bg-slate-800 focus:outline-none active:scale-[0.98]">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span class="ml-3 font-medium">Laporan</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200 shrink-0"
                        :class="satkerReportsOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="satkerReportsOpen" x-collapse style="display: none;" class="pl-10 pr-2 space-y-1">
                    <a href="{{ route('satker.riwayat.index') }}"
                        class="block py-2.5 px-4 text-sm font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('satker.riwayat.*') ? 'text-white bg-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        Riwayat BBM
                    </a>
                    <a href="{{ route('satker.laporan-hutang.index') }}"
                        class="block py-2.5 px-4 text-sm font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('satker.laporan-hutang.*') ? 'text-white bg-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        Laporan Bayar Hutang
                    </a>
                    <a href="{{ route('satker.laporan-triwulan.index') }}"
                        class="block py-2.5 px-4 text-sm font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('satker.laporan-triwulan.*') ? 'text-white bg-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        Laporan Per 3 Bulan
                    </a>
                    <a href="{{ route('satker.laporan-tahunan.index') }}"
                        class="block py-2.5 px-4 text-sm font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('satker.laporan-tahunan.*') ? 'text-white bg-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        Laporan Tahunan
                    </a>
                    <a href="{{ route('satker.kendaraans.laporan-bulanan') }}"
                        class="block py-2.5 px-4 text-sm font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('satker.kendaraans.laporan-bulanan.*') ? 'text-white bg-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        Laporan Bulanan
                    </a>
                    <a href="{{ route('satker.kendaraans.laporan-transfer') }}"
                        class="block py-2.5 px-4 text-sm font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('satker.kendaraans.laporan-transfer.*') ? 'text-white bg-indigo-600/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        Laporan Transfer
                    </a>
                </div>
            </div>

        @endif

        @if(auth()->user()->role === 'petugas_bbm')
            <p class="px-4 py-1 mt-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Petugas Area</p>

            <a href="{{ route('petugas.dashboard') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('petugas.dashboard') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                <span class="ml-3 font-medium">Dashboard</span>
            </a>

            <a href="{{ route('petugas.transaksi.index') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('petugas.transaksi.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                </svg>
                <span class="ml-3 font-medium">Transaksi BBM</span>
            </a>

            <a href="{{ route('petugas.meter.index') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('petugas.meter.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                <span class="ml-3 font-medium">Input Meter Pompa</span>
            </a>

            <a href="{{ route('petugas.sinkronisasi.index') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('petugas.sinkronisasi.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <span class="ml-3 font-medium">Input Stok Tangki</span>
            </a>

            <a href="{{ route('pembelian-bbm.index') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('pembelian-bbm.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <span class="ml-3 font-medium">Pembelian BBM</span>
            </a>

            <a href="{{ route('petugas.rekapan.index') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('petugas.rekapan.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span class="ml-3 font-medium">Rekapan Pengisian</span>
            </a>

            <a href="{{ route('petugas.hutang.index') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('petugas.hutang.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span class="ml-3 font-medium">Catat Hutang BBM</span>
            </a>
        @endif

        @if(auth()->user()->role === 'personel')
            <a href="{{ route('personel.dashboard') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('personel.dashboard') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                <span class="ml-3 font-medium">Dashboard</span>
            </a>

            <a href="{{ route('personel.transfer.index') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('personel.transfer.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
                <span class="ml-3 font-medium">Transfer Saldo</span>
            </a>


        @endif

        <p class="px-4 py-1 mt-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Komunikasi</p>
        <a href="{{ route('chat.index') }}"
            class="relative flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('chat.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                </path>
            </svg>
            <span class="ml-3 font-medium">Chat / Konsultasi</span>

            {{-- Notification Badge --}}
            <span id="sidebar-chat-badge"
                class="hidden absolute right-3 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full items-center justify-center border-2 border-slate-900">
            </span>
        </a>

        @if(in_array(auth()->user()->role, ['super_admin', 'kasubbag']))
            <a href="{{ route('admin.satisfaction.index') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('admin.satisfaction.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="ml-3 font-medium">Indeks Kepuasan</span>
            </a>
        @else
            <a href="{{ route('satisfaction.create') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('satisfaction.create') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="ml-3 font-medium">Indeks Kepuasan</span>
            </a>
        @endif

        <a href="{{ route('catatan.index') }}"
            class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('catatan.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                </path>
            </svg>
            <span class="ml-3 font-medium">Catatan</span>
        </a>

        {{-- Logout --}}
        <div class="mt-3 pt-3 border-t border-slate-800/50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                    class="flex items-center px-4 py-2 text-sm text-red-400 hover:bg-red-500/10 rounded-xl transition-all duration-200 active:scale-[0.98]">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    <span class="ml-3 font-medium">Logout</span>
                </a>
            </form>
        </div>
    </nav>
</div>