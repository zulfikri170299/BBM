{{-- sidebar.blade.php --}}
@php
    $personelAccessControl = \App\Models\Setting::where('key', 'personel_access_control')->value('value') ?? '1';
@endphp

<!-- Mobile Sidebar Overlay -->
<div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 lg:hidden" role="dialog" aria-modal="true">
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-linear duration-300" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" @click="sidebarOpen = false"></div>
         
    <div x-show="sidebarOpen" 
         x-transition:enter="transition ease-in-out duration-300 transform" 
         x-transition:enter-start="-translate-x-full" 
         x-transition:enter-end="translate-x-0" 
         x-transition:leave="transition ease-in-out duration-300 transform" 
         x-transition:leave-start="translate-x-0" 
         x-transition:leave-end="-translate-x-full" 
         class="relative flex h-full w-full max-w-[240px] flex-1 flex-col bg-slate-900 pb-4 pt-5">
         
        <div class="flex items-center justify-between px-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('rolog.png') }}" class="h-8 w-auto desktop-float" alt="Logo">
                <div class="flex flex-col">
                    <span class="text-lg font-black italic leading-none font-outfit uppercase tracking-[0.2em] desktop-shimmer">SPBP</span>
                    <span class="text-[7px] font-black text-slate-400 uppercase tracking-[0.1em] mt-1">SIM BBM - Polda NTB</span>
                </div>
            </div>
            <button type="button" class="-mr-2 flex h-10 w-10 items-center justify-center rounded-md bg-white/5 text-slate-400 hover:text-white" @click="sidebarOpen = false">
                <span class="sr-only">Tutup sidebar</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <nav class="mt-4 flex-1 px-4 space-y-1 overflow-y-auto custom-scrollbar pb-24"
            x-data="{ 
                reportsOpen: {{ (request()->routeIs('admin.laporan-slog.*') || request()->routeIs('admin.nominatif.*') || request()->routeIs('admin.laporan-topup.*') || request()->routeIs('admin.laporan-triwulan.*') || request()->routeIs('admin.laporan-stok-bbm.*') || request()->routeIs('admin.laporan-harian.*') || request()->routeIs('admin.riwayat.*') || request()->routeIs('admin.ba.*') || request()->routeIs('admin.laporan-sisa.*') || request()->routeIs('admin.laporan-hutang.*') || request()->routeIs('admin.laporan-potong.*') || request()->routeIs('admin.saldo-dialihkan.*')) ? 'true' : 'false' }},
                transactionsOpen: {{ (request()->routeIs('admin.transaksi.*') || request()->routeIs('admin.transfer-saldo.*') || request()->routeIs('admin.bulk-potong.*') || request()->routeIs('admin.meter.*') || request()->routeIs('admin.hutang.*') || request()->routeIs('petugas.transaksi.*') || request()->routeIs('petugas.hutang.*')) ? 'true' : 'false' }},
                masterBbmOpen: {{ (request()->routeIs('admin.stok.*') || request()->routeIs('pembelian-bbm.*')) ? 'true' : 'false' }},
                settingsOpen: {{ (request()->routeIs('admin.satkers.*') || request()->routeIs('admin.penanda-tangan.*') || request()->routeIs('admin.petugas-spbp.*') || request()->routeIs('admin.settings.*')) ? 'true' : 'false' }},
                userManagementOpen: {{ (request()->routeIs('admin.users.index') || request()->routeIs('admin.users.monitoring') || request()->routeIs('admin.pin-management.index')) ? 'true' : 'false' }},
                satkerReportsOpen: {{ (request()->routeIs('satker.riwayat.*') || request()->routeIs('satker.kendaraans.laporan-bulanan.*') || request()->routeIs('satker.kendaraans.laporan-transfer.*') || request()->routeIs('satker.laporan-hutang.*') || request()->routeIs('satker.laporan-triwulan.*') || request()->routeIs('satker.saldo-dialihkan.*')) ? 'true' : 'false' }}
            }">
            
            @include('layouts.sidebar-links')
            
        </nav>
    </div>
</div>

<!-- Desktop Sidebar -->
<div class="hidden lg:flex lg:w-72 lg:flex-col bg-slate-900/40 backdrop-blur-xl border-r border-white/5 relative z-10 transition-all duration-300" id="sidebar-nav">
    <div class="flex h-24 shrink-0 items-center px-8 border-b border-white/5">
        <div class="flex items-center gap-4">
            <div class="relative">
                <img src="{{ asset('rolog.png') }}" class="h-10 w-auto desktop-float relative z-10" alt="Logo">
                <div class="absolute inset-0 bg-brand-primary/20 blur-xl rounded-full"></div>
            </div>
            <div class="flex flex-col justify-center">
                <span class="text-2xl font-black italic leading-none font-outfit uppercase tracking-[0.2em] desktop-shimmer">SPBP</span>
                <span class="text-[8px] font-black text-slate-400 uppercase tracking-[0.1em] mt-1.5">SIM BBM - Polda NTB</span>
            </div>
        </div>
    </div>
    
    <nav class="mt-4 flex-1 px-4 space-y-1.5 overflow-y-auto custom-scrollbar"
        x-data="{ 
            reportsOpen: {{ (request()->routeIs('admin.laporan-slog.*') || request()->routeIs('admin.nominatif.*') || request()->routeIs('admin.laporan-topup.*') || request()->routeIs('admin.laporan-triwulan.*') || request()->routeIs('admin.laporan-stok-bbm.*') || request()->routeIs('admin.laporan-harian.*') || request()->routeIs('admin.riwayat.*') || request()->routeIs('admin.ba.*') || request()->routeIs('admin.laporan-sisa.*') || request()->routeIs('admin.laporan-hutang.*') || request()->routeIs('admin.laporan-potong.*') || request()->routeIs('admin.saldo-dialihkan.*')) ? 'true' : 'false' }},
            transactionsOpen: {{ (request()->routeIs('admin.transaksi.*') || request()->routeIs('admin.transfer-saldo.*') || request()->routeIs('admin.bulk-potong.*') || request()->routeIs('admin.meter.*') || request()->routeIs('admin.hutang.*') || request()->routeIs('petugas.transaksi.*') || request()->routeIs('petugas.hutang.*')) ? 'true' : 'false' }},
            masterBbmOpen: {{ (request()->routeIs('admin.stok.*') || request()->routeIs('pembelian-bbm.*')) ? 'true' : 'false' }},
            settingsOpen: {{ (request()->routeIs('admin.satkers.*') || request()->routeIs('admin.penanda-tangan.*') || request()->routeIs('admin.petugas-spbp.*') || request()->routeIs('admin.settings.*')) ? 'true' : 'false' }},
            userManagementOpen: {{ (request()->routeIs('admin.users.index') || request()->routeIs('admin.users.monitoring') || request()->routeIs('admin.pin-management.index')) ? 'true' : 'false' }},
            satkerReportsOpen: {{ (request()->routeIs('satker.riwayat.*') || request()->routeIs('satker.kendaraans.laporan-bulanan.*') || request()->routeIs('satker.kendaraans.laporan-transfer.*') || request()->routeIs('satker.laporan-hutang.*') || request()->routeIs('satker.laporan-triwulan.*') || request()->routeIs('satker.saldo-dialihkan.*')) ? 'true' : 'false' }}
        }">
        
        @include('layouts.sidebar-links')

    </nav>
    

</div>