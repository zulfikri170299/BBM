

        @if(in_array(auth()->user()->role, ['super_admin', 'kasubbag']))
            <div class="mt-2 mb-2 px-3 text-[9px] font-black uppercase tracking-widest text-slate-500">Administration</div>

            <a href="{{ route('admin.dashboard') }}"
                class="group flex items-center gap-x-2.5 rounded-xl px-3 py-1.5 text-xs font-bold leading-6 transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('admin.kendaraans.index') }}"
                class="group flex items-center gap-x-2.5 rounded-xl px-3 py-1.5 text-xs font-bold leading-6 transition-all {{ (request()->routeIs('admin.kendaraans.*') && !request()->routeIs('admin.kendaraans.laporan-*')) ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1">
                    </path>
                </svg>
                Kendaraan
            </a>

            @if($personelAccessControl == '1')
            <a href="{{ route('admin.personels.index') }}"
                class="group flex items-center gap-x-2.5 rounded-xl px-3 py-1.5 text-xs font-bold leading-6 transition-all {{ request()->routeIs('admin.personels.*') ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                Personel
            </a>
            @endif

            <div class="space-y-1">
                <button @click="masterBbmOpen = !masterBbmOpen"
                    class="w-full flex items-center justify-between px-3 py-1.5 text-sm text-gray-100 rounded-xl transition-all duration-200 text-slate-400 hover:text-white hover:bg-white/5 focus:outline-none active:scale-[0.98]">
                    <div class="flex items-center gap-x-2.5">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-1.207 9.658A2 2 0 0116.8 18.5H7.2a2 2 0 01-1.993-1.842L4 7m16 0a2 2 0 00-2-2H6a2 2 0 00-2 2m16 0l-2.4-2.4a1 1 0 00-.707-.293H9.107a1 1 0 00-.707.293L6 7m4 4h4">
                            </path>
                        </svg>
                        Master BBM
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200 shrink-0" :class="masterBbmOpen ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="masterBbmOpen" x-collapse style="display: none;" class="ml-9 submenu-line space-y-1 my-1">
                    @if(auth()->user()->role === 'super_admin')
                        <a href="{{ route('admin.stok.index') }}"
                            class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.stok.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.stok.*') ? 'active-dot' : '' }}"></div>
                            Saldo BBM
                        </a>
                        <a href="{{ route('pembelian-bbm.index') }}"
                            class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('pembelian-bbm.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('pembelian-bbm.*') ? 'active-dot' : '' }}"></div>
                            Pembelian BBM
                        </a>
                        <a href="{{ route('admin.rendis.index') }}"
                            class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.rendis.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.rendis.*') ? 'active-dot' : '' }}"></div>
                            Rendis BBM
                        </a>
                        <a href="{{ route('admin.sounding.index') }}"
                            class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.sounding.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.sounding.*') ? 'active-dot' : '' }}"></div>
                            Data Sounding
                        </a>
                    @endif
                </div>
            </div>

            @if(auth()->user()->role === 'super_admin')
                <div class="space-y-1">
                    <button @click="transactionsOpen = !transactionsOpen"
                        class="w-full flex items-center justify-between px-3 py-1.5 text-sm text-gray-100 rounded-xl transition-all duration-200 text-slate-400 hover:text-white hover:bg-white/5 focus:outline-none active:scale-[0.98]">
                        <div class="flex items-center gap-x-2.5">
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            Transaksi
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200 shrink-0" :class="transactionsOpen ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="transactionsOpen" x-collapse style="display: none;" class="ml-9 submenu-line space-y-1 my-1">
                        <a href="{{ route('admin.transaksi.index') }}"
                            class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.transaksi.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.transaksi.*') ? 'active-dot' : '' }}"></div>
                            Transaksi BBM
                        </a>
                        <a href="{{ route('admin.hutang.index') }}"
                            class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.hutang.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.hutang.*') ? 'active-dot' : '' }}"></div>
                            Hutang BBM
                        </a>
                        @if($personelAccessControl == '1')
                        <a href="{{ route('admin.transfer-saldo.index') }}"
                            class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.transfer-saldo.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.transfer-saldo.*') ? 'active-dot' : '' }}"></div>
                            Transfer Saldo
                        </a>
                        @endif
                        <a href="{{ route('admin.bulk-potong.index') }}"
                            class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.bulk-potong.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.bulk-potong.*') ? 'active-dot' : '' }}"></div>
                            Potong Saldo Masal
                        </a>
                        <a href="{{ route('admin.meter.index') }}"
                            class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.meter.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.meter.*') ? 'active-dot' : '' }}"></div>
                            Input Meter Pompa
                        </a>
                    </div>
                </div>
            @endif



            {{-- Reports Dropdown --}}
            <div class="space-y-1">
                <button @click="reportsOpen = !reportsOpen"
                    class="w-full flex items-center justify-between px-3 py-1.5 text-sm text-gray-100 rounded-xl transition-all duration-200 text-slate-400 hover:text-white hover:bg-white/5 focus:outline-none active:scale-[0.98]">
                    <div class="flex items-center gap-x-2.5">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Laporan
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200 shrink-0" :class="reportsOpen ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="reportsOpen" x-collapse style="display: none;" class="ml-9 submenu-line space-y-1 my-1">
                    <a href="{{ route('admin.laporan-slog.index') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-slog.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.laporan-slog.*') ? 'active-dot' : '' }}"></div>
                        Laporan Rutin
                    </a>
                    <a href="{{ route('admin.laporan-topup.index') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-topup.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.laporan-topup.*') ? 'active-dot' : '' }}"></div>
                        Laporan Top Up
                    </a>
                    <a href="{{ route('admin.laporan-hutang.index') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-hutang.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.laporan-hutang.*') ? 'active-dot' : '' }}"></div>
                        Laporan Bayar Hutang
                    </a>
                    <a href="{{ route('admin.laporan-potong.index') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-potong.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.laporan-potong.*') ? 'active-dot' : '' }}"></div>
                        Laporan Potong Saldo
                    </a>
                    <a href="{{ route('admin.laporan-transfer-saldo.index') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-transfer-saldo.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.laporan-transfer-saldo.*') ? 'active-dot' : '' }}"></div>
                        Laporan Transfer Saldo
                    </a>
                    <a href="{{ route('admin.saldo-dialihkan.index') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.saldo-dialihkan.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.saldo-dialihkan.*') ? 'active-dot' : '' }}"></div>
                        Saldo Yang di Alihkan
                    </a>
                    <a href="{{ route('admin.laporan-harian.index') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-harian.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.laporan-harian.*') ? 'active-dot' : '' }}"></div>
                        Laporan Harian
                    </a>
                    <a href="{{ route('admin.laporan-triwulan.index') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-triwulan.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.laporan-triwulan.*') ? 'active-dot' : '' }}"></div>
                        Laporan Per 3 Bulan
                    </a>
                    <a href="{{ route('admin.laporan-tahunan.index') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-tahunan.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.laporan-tahunan.*') ? 'active-dot' : '' }}"></div>
                        Laporan Tahunan
                    </a>
                    <a href="{{ route('admin.laporan-stok-bbm.index') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-stok-bbm.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.laporan-stok-bbm.*') ? 'active-dot' : '' }}"></div>
                        Data BBM Pada Tangki
                    </a>
                    <a href="{{ route('admin.riwayat.index') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.riwayat.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.riwayat.*') ? 'active-dot' : '' }}"></div>
                        Riwayat BBM
                    </a>

                    <a href="{{ route('admin.ba.index') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.ba.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.ba.*') ? 'active-dot' : '' }}"></div>
                        Berita Acara
                    </a>
                    <a href="{{ route('admin.nominatif.index') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.nominatif.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.nominatif.*') ? 'active-dot' : '' }}"></div>
                        Nominatif
                    </a>
                    <a href="{{ route('admin.laporan-sisa.kendaraan') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-sisa.kendaraan') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.laporan-sisa.kendaraan') ? 'active-dot' : '' }}"></div>
                        Sisa BBM Kendaraan
                    </a>
                    @if($personelAccessControl == '1')
                    <a href="{{ route('admin.laporan-sisa.personel') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.laporan-sisa.personel') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('admin.laporan-sisa.personel') ? 'active-dot' : '' }}"></div>
                        Sisa BBM Personel
                    </a>
                    @endif
                </div>
            </div>

            @if(auth()->user()->role === 'super_admin')
                <div class="space-y-1">
                    <button @click="userManagementOpen = !userManagementOpen"
                        class="w-full flex items-center justify-between px-3 py-1.5 text-sm text-gray-100 rounded-xl transition-all duration-200 text-slate-400 hover:text-white hover:bg-white/5 focus:outline-none active:scale-[0.98]">
                        <div class="flex items-center gap-x-2.5">
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            Manajemen User
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200 shrink-0" :class="userManagementOpen ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="userManagementOpen" x-collapse style="display: none;" class="ml-9 submenu-line space-y-1 my-1">
                        <a href="{{ route('admin.users.index') }}"
                            class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.users.index') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.users.index') ? 'active-dot' : '' }}"></div>
                            Users
                        </a>
                        <a href="{{ route('admin.users.monitoring') }}"
                            class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.users.monitoring') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.users.monitoring') ? 'active-dot' : '' }}"></div>
                            Monitoring User
                        </a>
                        <a href="{{ route('admin.pin-management.index') }}"
                            class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.pin-management.index') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.pin-management.index') ? 'active-dot' : '' }}"></div>
                            Manajemen PIN
                        </a>
                    </div>
                </div>
            @endif

            @if(auth()->user()->role === 'super_admin')
                <div class="space-y-1">
                    <button @click="settingsOpen = !settingsOpen"
                        class="w-full flex items-center justify-between px-3 py-1.5 text-sm text-gray-100 rounded-xl transition-all duration-200 text-slate-400 hover:text-white hover:bg-white/5 focus:outline-none active:scale-[0.98]">
                        <div class="flex items-center gap-x-2.5">
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Pengaturan
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200 shrink-0" :class="settingsOpen ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="settingsOpen" x-collapse style="display: none;" class="ml-9 submenu-line space-y-1 my-1">
                        <a href="{{ route('admin.satkers.index') }}"
                            class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.satkers.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.satkers.*') ? 'active-dot' : '' }}"></div>
                            Satkers
                        </a>
                        <a href="{{ route('admin.penanda-tangan.index') }}"
                            class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.penanda-tangan.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.penanda-tangan.*') ? 'active-dot' : '' }}"></div>
                            Penanda Tangan
                        </a>
                        <a href="{{ route('admin.petugas-spbp.index') }}"
                            class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.petugas-spbp.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.petugas-spbp.*') ? 'active-dot' : '' }}"></div>
                            Petugas SPBP
                        </a>
                        <a href="{{ route('admin.settings.index') }}"
                            class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.settings.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.settings.*') ? 'active-dot' : '' }}"></div>
                            Sistem
                        </a>
                        <a href="{{ route('admin.backup.index') }}"
                            class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('admin.backup.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                            <div class="submenu-dot {{ request()->routeIs('admin.backup.*') ? 'active-dot' : '' }}"></div>
                            Backup Database
                        </a>
                    </div>
                </div>
            @endif




        @endif

        @if(auth()->user()->role === 'admin_satker')
            <p class="px-3 py-1.5 mt-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Satker Management</p>

            <a href="{{ route('satker.dashboard') }}"
                class="group flex items-center gap-x-2.5 rounded-xl px-3 py-1.5 text-xs font-bold leading-6 transition-all {{ request()->routeIs('satker.dashboard') ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                Dashboard
            </a>



            <a href="{{ route('satker.kendaraans.index') }}"
                class="group flex items-center gap-x-2.5 rounded-xl px-3 py-1.5 text-xs font-bold leading-6 transition-all {{ (request()->routeIs('satker.kendaraans.*') && !request()->routeIs('satker.kendaraans.laporan-*')) ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1">
                    </path>
                </svg>
                Kendaraan
            </a>

            @if($personelAccessControl == '1')
            <a href="{{ route('satker.personels.index') }}"
                class="group flex items-center gap-x-2.5 rounded-xl px-3 py-1.5 text-xs font-bold leading-6 transition-all {{ request()->routeIs('satker.personels.*') ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                Personel
            </a>
            @endif

            <a href="{{ route('satker.hutang.index') }}"
                class="group flex items-center gap-x-2.5 rounded-xl px-3 py-1.5 text-xs font-bold leading-6 transition-all {{ request()->routeIs('satker.hutang.*') ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Hutang BBM
            </a>

            <a href="{{ route('satker.penanda-tangan.index') }}"
                class="group flex items-center gap-x-2.5 rounded-xl px-3 py-1.5 text-xs font-bold leading-6 transition-all {{ request()->routeIs('satker.penanda-tangan.*') ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                </svg>
                Penanda Tangan
            </a>

            {{-- Reports Dropdown (Satker) --}}
            <div class="space-y-1">
                <button @click="satkerReportsOpen = !satkerReportsOpen"
                    class="w-full flex items-center justify-between px-3 py-1.5 text-sm text-gray-100 rounded-xl transition-all duration-200 text-slate-400 hover:text-white hover:bg-white/5 focus:outline-none active:scale-[0.98]">
                    <div class="flex items-center gap-x-2.5">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Laporan
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200 shrink-0"
                        :class="satkerReportsOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="satkerReportsOpen" x-collapse style="display: none;" class="ml-9 submenu-line space-y-1 my-1">
                    <a href="{{ route('satker.riwayat.index') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('satker.riwayat.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('satker.riwayat.*') ? 'active-dot' : '' }}"></div>
                        Riwayat BBM
                    </a>
                    <a href="{{ route('satker.laporan-hutang.index') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('satker.laporan-hutang.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('satker.laporan-hutang.*') ? 'active-dot' : '' }}"></div>
                        Laporan Bayar Hutang
                    </a>
                    <a href="{{ route('satker.laporan-triwulan.index') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('satker.laporan-triwulan.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('satker.laporan-triwulan.*') ? 'active-dot' : '' }}"></div>
                        Laporan Per 3 Bulan
                    </a>
                    <a href="{{ route('satker.laporan-tahunan.index') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('satker.laporan-tahunan.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('satker.laporan-tahunan.*') ? 'active-dot' : '' }}"></div>
                        Laporan Tahunan
                    </a>
                    <a href="{{ route('satker.kendaraans.laporan-bulanan') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('satker.kendaraans.laporan-bulanan.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('satker.kendaraans.laporan-bulanan.*') ? 'active-dot' : '' }}"></div>
                        Laporan Bulanan
                    </a>
                    @if($personelAccessControl == '1')
                    <a href="{{ route('satker.kendaraans.laporan-transfer') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('satker.kendaraans.laporan-transfer.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('satker.kendaraans.laporan-transfer.*') ? 'active-dot' : '' }}"></div>
                        Laporan Transfer
                    </a>
                    @endif
                    <a href="{{ route('satker.saldo-dialihkan.index') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('satker.saldo-dialihkan.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('satker.saldo-dialihkan.*') ? 'active-dot' : '' }}"></div>
                        Saldo Yang di Alihkan
                    </a>
                </div>
            </div>

        @endif

        @if(auth()->user()->role === 'petugas_bbm')
            <div class="mt-2 mb-2 px-3 text-[9px] font-black uppercase tracking-widest text-slate-500">Petugas Area</div>

            <a href="{{ route('petugas.dashboard') }}"
                class="group flex items-center gap-x-2.5 rounded-xl px-3 py-1.5 text-xs font-bold leading-6 transition-all {{ request()->routeIs('petugas.dashboard') ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                Dashboard
            </a>

            <div class="space-y-1">
                <button @click="transactionsOpen = !transactionsOpen"
                    class="w-full flex items-center justify-between px-3 py-1.5 text-sm text-gray-100 rounded-xl transition-all duration-200 text-slate-400 hover:text-white hover:bg-white/5 focus:outline-none active:scale-[0.98]">
                    <div class="flex items-center gap-x-2.5">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        Transaksi
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200 shrink-0" :class="transactionsOpen ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="transactionsOpen" x-collapse style="display: none;" class="ml-9 submenu-line space-y-1 my-1">
                    <a href="{{ route('petugas.transaksi.index') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('petugas.transaksi.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('petugas.transaksi.*') ? 'active-dot' : '' }}"></div>
                        Transaksi BBM
                    </a>
                    <a href="{{ route('petugas.hutang.index') }}"
                        class="group  flex items-center py-2 px-4 text-xs font-medium rounded-lg transition-all active:scale-[0.98] {{ request()->routeIs('petugas.hutang.*') ? 'text-white active-' : 'text-slate-400 hover:text-white text-slate-400 hover:text-white hover:bg-white/5/50' }}">
                        <div class="submenu-dot {{ request()->routeIs('petugas.hutang.*') ? 'active-dot' : '' }}"></div>
                        Catat Hutang BBM
                    </a>
                </div>
            </div>

            <a href="{{ route('petugas.meter.index') }}"
                class="group flex items-center gap-x-2.5 rounded-xl px-3 py-1.5 text-xs font-bold leading-6 transition-all {{ request()->routeIs('petugas.meter.*') ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                    </path>
                </svg>
                Input Meter Pompa
            </a>

            <a href="{{ route('petugas.sinkronisasi.index') }}"
                class="group flex items-center gap-x-2.5 rounded-xl px-3 py-1.5 text-xs font-bold leading-6 transition-all {{ request()->routeIs('petugas.sinkronisasi.*') ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                Input Stok Tangki
            </a>

            <a href="{{ route('pembelian-bbm.index') }}"
                class="group flex items-center gap-x-2.5 rounded-xl px-3 py-1.5 text-xs font-bold leading-6 transition-all {{ request()->routeIs('pembelian-bbm.*') ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                Pembelian BBM
            </a>

            <a href="{{ route('petugas.rekapan.index') }}"
                class="group flex items-center gap-x-2.5 rounded-xl px-3 py-1.5 text-xs font-bold leading-6 transition-all {{ request()->routeIs('petugas.rekapan.*') ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Rekapan Pengisian
            </a>

            <a href="{{ route('petugas.riwayat.index') }}"
                class="group flex items-center gap-x-2.5 rounded-xl px-3 py-1.5 text-xs font-bold leading-6 transition-all {{ request()->routeIs('petugas.riwayat.*') ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Riwayat BBM
            </a>

            <a href="{{ route('petugas.sounding.index') }}"
                class="group flex items-center gap-x-2.5 rounded-xl px-3 py-1.5 text-xs font-bold leading-6 transition-all {{ request()->routeIs('petugas.sounding.*') ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                Data Sounding
            </a>
        @endif

        @if(auth()->user()->role === 'personel')
            <a href="{{ route('personel.dashboard') }}"
                class="group flex items-center gap-x-2.5 rounded-xl px-3 py-1.5 text-xs font-bold leading-6 transition-all {{ request()->routeIs('personel.dashboard') ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('personel.transfer.index') }}"
                class="group flex items-center gap-x-2.5 rounded-xl px-3 py-1.5 text-xs font-bold leading-6 transition-all {{ request()->routeIs('personel.transfer.*') ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
                Transfer Saldo
            </a>


        @endif

        <p class="px-4 py-1 mt-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Komunikasi</p>
        @if(in_array(auth()->user()->role, ['super_admin', 'kasubbag']))
            <a href="{{ route('admin.broadcast.index') }}"
                class="group flex items-center gap-x-2.5 rounded-xl px-3 py-1.5 text-xs font-bold leading-6 transition-all {{ request()->routeIs('admin.broadcast.*') ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                    </path>
                </svg>
                Pesan Siaran
            </a>
        @endif
        <a href="{{ route('chat.index') }}"
            class="relative group flex items-center gap-x-2.5 rounded-xl px-3 py-1.5 text-xs font-bold leading-6 transition-all {{ request()->routeIs('chat.*') ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                </path>
            </svg>
            Chat / Konsultasi

            {{-- Notification Badge --}}
            <span id="sidebar-chat-badge"
                class="hidden absolute right-3 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full items-center justify-center border-2 border-slate-900">
            </span>
        </a>

        @if(in_array(auth()->user()->role, ['super_admin', 'kasubbag']))
            <a href="{{ route('admin.satisfaction.index') }}"
                class="group flex items-center gap-x-2.5 rounded-xl px-3 py-1.5 text-xs font-bold leading-6 transition-all {{ request()->routeIs('admin.satisfaction.*') ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Indeks Kepuasan
            </a>
        @else
            <a href="{{ route('satisfaction.create') }}"
                class="group flex items-center gap-x-2.5 rounded-xl px-3 py-1.5 text-xs font-bold leading-6 transition-all {{ request()->routeIs('satisfaction.create') ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Indeks Kepuasan
            </a>
        @endif

        <a href="{{ route('catatan.index') }}"
            class="group flex items-center gap-x-2.5 rounded-xl px-3 py-1.5 text-xs font-bold leading-6 transition-all {{ request()->routeIs('catatan.*') ? 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                </path>
            </svg>
            Catatan
        </a>

        {{-- Logout --}}
        <div class="mt-3 pt-3 border-t border-slate-800/50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-x-2.5 px-3 py-1.5 text-sm text-red-400 hover:bg-red-500/10 rounded-xl transition-all duration-200 active:scale-[0.98]">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    