<div x-cloak :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"></div>

<div x-cloak :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-dark text-white lg:translate-x-0 lg:static lg:inset-0">
    <div class="flex items-center justify-center mt-8">
        <div class="flex items-center">
            <span class="text-2xl font-bold text-white ml-2">SIMAK BBM</span>
        </div>
    </div>

    <nav class="mt-10 px-4 space-y-2">
        <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 mt-4 text-gray-100 rounded-xl transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            <span class="mx-3 font-medium">Beranda</span>
        </a>

        @if(auth()->user()->role === 'super_admin')
            <p class="px-6 py-2 mt-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Administration</p>
            
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 text-gray-100 rounded-xl transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span class="mx-3 font-medium">Admin Overview</span>
            </a>
            
            <a href="{{ route('admin.satkers.index') }}" class="flex items-center px-6 py-3 text-gray-100 rounded-xl transition-colors duration-200 {{ request()->routeIs('admin.satkers.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span class="mx-3 font-medium">Satkers</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="flex items-center px-6 py-3 text-gray-100 rounded-xl transition-colors duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="mx-3 font-medium">Users</span>
            </a>

            <a href="{{ route('admin.kendaraans.index') }}" class="flex items-center px-6 py-3 text-gray-100 rounded-xl transition-colors duration-200 {{ request()->routeIs('admin.kendaraans.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1"></path></svg>
                <span class="mx-3 font-medium">Kendaraan</span>
            </a>
        @endif

        @if(auth()->user()->role === 'admin_satker')
            <p class="px-6 py-2 mt-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Satker Management</p>

            <a href="{{ route('satker.dashboard') }}" class="flex items-center px-6 py-3 text-gray-100 rounded-xl transition-colors duration-200 {{ request()->routeIs('satker.dashboard') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                <span class="mx-3 font-medium">Overview</span>
            </a>
            
            <a href="{{ route('satker.kendaraans.index') }}" class="flex items-center px-6 py-3 text-gray-100 rounded-xl transition-colors duration-200 {{ request()->routeIs('satker.kendaraans.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                <span class="mx-3 font-medium">Kendaraan</span>
            </a>
             <a href="{{ route('satker.personels.index') }}" class="flex items-center px-6 py-3 text-gray-100 rounded-xl transition-colors duration-200 {{ request()->routeIs('satker.personels.*') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span class="mx-3 font-medium">Personel</span>
            </a>
        @endif

        @if(auth()->user()->role === 'petugas_bbm')
            <p class="px-6 py-2 mt-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Petugas Area</p>
             <a href="{{ route('petugas.dashboard') }}" class="flex items-center px-6 py-3 text-gray-100 rounded-xl transition-colors duration-200 {{ request()->routeIs('petugas.dashboard') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30' : 'hover:bg-slate-800' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                <span class="mx-3 font-medium">Scan Barcode</span>
            </a>
        @endif

        <div class="mt-auto pt-6 border-t border-slate-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center px-6 py-3 text-red-400 hover:bg-slate-800 rounded-xl transition-colors duration-200">
                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span class="mx-3 font-medium">Logout</span>
                </a>
            </form>
        </div>
    </nav>
</div>
