{{-- Mobile Overlay --}}
<div x-cloak x-show="sidebarOpen" style="display: none;" @click="sidebarOpen = false"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-40 bg-black/60 lg:hidden"></div>

{{-- Sidebar --}}
    <style>
        .submenu-line {
            position: relative;
        }
        .submenu-line::before {
            content: '';
            position: absolute;
            left: -1px;
            top: 0;
            bottom: 12px;
            width: 1px;
            background: linear-gradient(to bottom, #6366f1 0%, rgba(71, 85, 105, 0.2) 100%);
        }
        .submenu-dot {
            position: absolute;
            left: -14px;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 5px;
            border-radius: 9999px;
            background: #475569;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .group:hover .submenu-dot {
            background: #818cf8;
            box-shadow: 0 0 8px rgba(99, 102, 241, 0.6);
            transform: translateY(-50%) scale(1.5);
        }
        .active-dot {
            background: #fff !important;
            box-shadow: 0 0 12px rgba(99, 102, 241, 1);
            transform: translateY(-50%) scale(1.6) !important;
            border: 1px solid #6366f1;
        }
        .submenu-item {
            transition: all 0.2s ease-in-out;
        }
        .submenu-item:hover {
            padding-left: 1.25rem !important;
        }
        .active-submenu-item {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, rgba(99, 102, 241, 0) 100%);
            border-left: 2px solid #6366f1;
            margin-left: -2px;
        }
    </style>

<div x-data="{ 
        reportsOpen: {{ (request()->routeIs('admin.laporan-slog.*') || request()->routeIs('admin.nominatif.*') || request()->routeIs('admin.laporan-topup.*') || request()->routeIs('admin.laporan-triwulan.*') || request()->routeIs('admin.laporan-stok-bbm.*') || request()->routeIs('admin.laporan-harian.*') || request()->routeIs('admin.riwayat.*') || request()->routeIs('admin.ba.*') || request()->routeIs('admin.laporan-sisa.*') || request()->routeIs('admin.laporan-hutang.*') || request()->routeIs('admin.laporan-potong.*')) ? 'true' : 'false' }},
        transactionsOpen: {{ (request()->routeIs('admin.transaksi.*') || request()->routeIs('admin.transfer-saldo.*') || request()->routeIs('admin.bulk-potong.*') || request()->routeIs('admin.meter.*')) ? 'true' : 'false' }},
        masterBbmOpen: {{ (request()->routeIs('admin.stok.*') || request()->routeIs('pembelian-bbm.*') || request()->routeIs('admin.hutang.*')) ? 'true' : 'false' }},
        settingsOpen: {{ (request()->routeIs('admin.satkers.*') || request()->routeIs('admin.penanda-tangan.*') || request()->routeIs('admin.petugas-spbp.*') || request()->routeIs('admin.settings.*')) ? 'true' : 'false' }},
        userManagementOpen: {{ (request()->routeIs('admin.users.index') || request()->routeIs('admin.users.monitoring')) ? 'true' : 'false' }},
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
    @close-reports.window="reportsOpen = false; satkerReportsOpen = false; transactionsOpen = false; masterBbmOpen = false; settingsOpen = false; userManagementOpen = false"
    @open-admin-reports.window="reportsOpen = true"
    @open-satker-reports.window="satkerReportsOpen = true"
    class="fixed inset-y-0 left-0 z-50 w-72 lg:w-64 overflow-y-auto transform bg-slate-900 text-white -translate-x-full lg:translate-x-0 lg:static lg:inset-0 transition-transform duration-300 ease-in-out"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

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

            <div class="space-y-1">
                <button @click="masterBbmOpen = !masterBbmOpen"
                    class="w-full flex items-center justify-between px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 hover:bg-slate-800 focus:outline-none active:scale-[0.98]">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-1.207 9.658A2 2 0 0116.8 18.5H7.2a2 2 0 01-1.993-1.842L4 7m16 0a2 2 0 00-2-2H6a2 2 0 00-2 2m16 0l-2.4-2.4a1 1 0 00-.707-.293H9.107a1 1 0 00-.707.293L6 7m4 4h4">
                            </path>
                        </svg>
                        <span class="ml-3 font-medium">Master BBM</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200 shrink-0" :class="masterBbmOpen ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="masterBbmOpen" x-collapse style="display: none;" class="ml-9 submenu-line space-y-1 my-1">
                    @if(auth()->user()->role === 'super_admin')
                        <a href="{{ route('admin.stok.index') }}"
                            class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.stok.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.stok.*') ? 'active-dot' : '' }}"></div>
                            Stok BBM
                        </a>
                        <a href="{{ route('pembelian-bbm.index') }}"
                            class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('pembelian-bbm.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('pembelian-bbm.*') ? 'active-dot' : '' }}"></div>
                            Pembelian BBM
                        </a>
                    @endif
                    <a href="{{ route('admin.hutang.index') }}"
                        class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.hutang.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.hutang.*') ? 'active-dot' : '' }}"></div>
                        Hutang BBM
                    </a>
                </div>
            </div>

            @if(auth()->user()->role === 'super_admin')
                <div class="space-y-1">
                    <button @click="transactionsOpen = !transactionsOpen"
                        class="w-full flex items-center justify-between px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 hover:bg-slate-800 focus:outline-none active:scale-[0.98]">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span class="ml-3 font-medium">Transaksi</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200 shrink-0" :class="transactionsOpen ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="transactionsOpen" x-collapse style="display: none;" class="ml-9 submenu-line space-y-1 my-1">
                        <a href="{{ route('admin.transaksi.index') }}"
                            class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.transaksi.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.transaksi.*') ? 'active-dot' : '' }}"></div>
                            Transaksi BBM
                        </a>
                        <a href="{{ route('admin.transfer-saldo.index') }}"
                            class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.transfer-saldo.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.transfer-saldo.*') ? 'active-dot' : '' }}"></div>
                            Transfer Saldo
                        </a>
                        <a href="{{ route('admin.bulk-potong.index') }}"
                            class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.bulk-potong.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.bulk-potong.*') ? 'active-dot' : '' }}"></div>
                            Potong Saldo Masal
                        </a>
                        <a href="{{ route('admin.meter.index') }}"
                            class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.meter.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.meter.*') ? 'active-dot' : '' }}"></div>
                            Input Meter Pompa
                        </a>
                    </div>
                </div>
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

                <div x-show="reportsOpen" x-collapse style="display: none;" class="ml-9 submenu-line space-y-1 my-1">
                    <a href="{{ route('admin.laporan-slog.index') }}"
                        class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-slog.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.laporan-slog.*') ? 'active-dot' : '' }}"></div>
                        Laporan Rutin
                    </a>
                    <a href="{{ route('admin.laporan-topup.index') }}"
                        class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-topup.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.laporan-topup.*') ? 'active-dot' : '' }}"></div>
                        Laporan Top Up
                    </a>
                    <a href="{{ route('admin.laporan-hutang.index') }}"
                        class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-hutang.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.laporan-hutang.*') ? 'active-dot' : '' }}"></div>
                        Laporan Bayar Hutang
                    </a>
                    <a href="{{ route('admin.laporan-potong.index') }}"
                        class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-potong.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.laporan-potong.*') ? 'active-dot' : '' }}"></div>
                        Laporan Potong Saldo
                    </a>
                    <a href="{{ route('admin.laporan-harian.index') }}"
                        class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-harian.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.laporan-harian.*') ? 'active-dot' : '' }}"></div>
                        Laporan Harian
                    </a>
                    <a href="{{ route('admin.laporan-triwulan.index') }}"
                        class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-triwulan.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.laporan-triwulan.*') ? 'active-dot' : '' }}"></div>
                        Laporan Per 3 Bulan
                    </a>
                    <a href="{{ route('admin.laporan-tahunan.index') }}"
                        class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-tahunan.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.laporan-tahunan.*') ? 'active-dot' : '' }}"></div>
                        Laporan Tahunan
                    </a>
                    <a href="{{ route('admin.laporan-stok-bbm.index') }}"
                        class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-stok-bbm.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.laporan-stok-bbm.*') ? 'active-dot' : '' }}"></div>
                        Data BBM Pada Tangki
                    </a>
                    <a href="{{ route('admin.riwayat.index') }}"
                        class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.riwayat.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.riwayat.*') ? 'active-dot' : '' }}"></div>
                        Riwayat BBM
                    </a>
                    <a href="{{ route('admin.ba.index') }}"
                        class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.ba.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.ba.*') ? 'active-dot' : '' }}"></div>
                        Berita Acara
                    </a>
                    <a href="{{ route('admin.nominatif.index') }}"
                        class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.nominatif.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.nominatif.*') ? 'active-dot' : '' }}"></div>
                        Nominatif
                    </a>
                    <a href="{{ route('admin.laporan-sisa.kendaraan') }}"
                        class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-sisa.kendaraan') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.laporan-sisa.kendaraan') ? 'active-dot' : '' }}"></div>
                        Sisa BBM Kendaraan
                    </a>
                    <a href="{{ route('admin.laporan-sisa.personel') }}"
                        class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-sisa.personel') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.laporan-sisa.personel') ? 'active-dot' : '' }}"></div>
                        Sisa BBM Personel
                    </a>
                </div>
            </div>

            @if(auth()->user()->role === 'super_admin')
                <div class="space-y-1">
                    <button @click="userManagementOpen = !userManagementOpen"
                        class="w-full flex items-center justify-between px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 hover:bg-slate-800 focus:outline-none active:scale-[0.98]">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            <span class="ml-3 font-medium">Manajemen User</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200 shrink-0" :class="userManagementOpen ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="userManagementOpen" x-collapse style="display: none;" class="ml-9 submenu-line space-y-1 my-1">
                        <a href="{{ route('admin.users.index') }}"
                            class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.users.index') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.users.index') ? 'active-dot' : '' }}"></div>
                            Users
                        </a>
                        <a href="{{ route('admin.users.monitoring') }}"
                            class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.users.monitoring') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.users.monitoring') ? 'active-dot' : '' }}"></div>
                            Monitoring User
                        </a>
                        <a href="{{ route('admin.pin-management.index') }}"
                            class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.pin-management.index') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.pin-management.index') ? 'active-dot' : '' }}"></div>
                            Manajemen PIN
                        </a>
                    </div>
                </div>
            @endif

            @if(auth()->user()->role === 'super_admin')
                <div class="space-y-1">
                    <button @click="settingsOpen = !settingsOpen"
                        class="w-full flex items-center justify-between px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 hover:bg-slate-800 focus:outline-none active:scale-[0.98]">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="ml-3 font-medium">Pengaturan</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200 shrink-0" :class="settingsOpen ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="settingsOpen" x-collapse style="display: none;" class="ml-9 submenu-line space-y-1 my-1">
                        <a href="{{ route('admin.satkers.index') }}"
                            class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.satkers.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.satkers.*') ? 'active-dot' : '' }}"></div>
                            Satkers
                        </a>
                        <a href="{{ route('admin.penanda-tangan.index') }}"
                            class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.penanda-tangan.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.penanda-tangan.*') ? 'active-dot' : '' }}"></div>
                            Penanda Tangan
                        </a>
                        <a href="{{ route('admin.petugas-spbp.index') }}"
                            class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.petugas-spbp.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.petugas-spbp.*') ? 'active-dot' : '' }}"></div>
                            Petugas SPBP
                        </a>
                        <a href="{{ route('admin.settings.index') }}"
                            class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.settings.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.settings.*') ? 'active-dot' : '' }}"></div>
                            Sistem
                        </a>
                    </div>
                </div>
            @endif




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

                <div x-show="satkerReportsOpen" x-collapse style="display: none;" class="ml-9 submenu-line space-y-1 my-1">
                    <a href="{{ route('satker.riwayat.index') }}"
                        class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('satker.riwayat.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('satker.riwayat.*') ? 'active-dot' : '' }}"></div>
                        Riwayat BBM
                    </a>
                    <a href="{{ route('satker.laporan-hutang.index') }}"
                        class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('satker.laporan-hutang.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('satker.laporan-hutang.*') ? 'active-dot' : '' }}"></div>
                        Laporan Bayar Hutang
                    </a>
                    <a href="{{ route('satker.laporan-triwulan.index') }}"
                        class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('satker.laporan-triwulan.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('satker.laporan-triwulan.*') ? 'active-dot' : '' }}"></div>
                        Laporan Per 3 Bulan
                    </a>
                    <a href="{{ route('satker.laporan-tahunan.index') }}"
                        class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('satker.laporan-tahunan.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('satker.laporan-tahunan.*') ? 'active-dot' : '' }}"></div>
                        Laporan Tahunan
                    </a>
                    <a href="{{ route('satker.kendaraans.laporan-bulanan') }}"
                        class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('satker.kendaraans.laporan-bulanan.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('satker.kendaraans.laporan-bulanan.*') ? 'active-dot' : '' }}"></div>
                        Laporan Bulanan
                    </a>
                    <a href="{{ route('satker.kendaraans.laporan-transfer') }}"
                        class="group submenu-item flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('satker.kendaraans.laporan-transfer.*') ? 'text-white active-submenu-item' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('satker.kendaraans.laporan-transfer.*') ? 'active-dot' : '' }}"></div>
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
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z">
                    </path>
                </svg>
                <span class="ml-3 font-medium">Transaksi BBM</span>
            </a>

            <a href="{{ route('petugas.meter.index') }}"
                class="flex items-center px-4 py-2 text-sm text-gray-100 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->routeIs('petugas.meter.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
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
        @if(in_array(auth()->user()->role, ['super_admin', 'kasubbag']))
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