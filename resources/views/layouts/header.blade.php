<header class="bg-white shadow-sm border-b border-slate-200 z-10">
    <div class="flex items-center justify-between px-6 py-4">
        <div class="flex items-center">
            <button @click="sidebarOpen = true" class="text-slate-500 focus:outline-none lg:hidden">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <div class="relative ml-4 lg:ml-0">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-5 h-5 text-slate-400" viewBox="0 0 24 24" fill="none">
                        <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <input class="w-32 sm:w-64 pl-10 pr-4 py-2 text-sm text-slate-700 bg-slate-100 border border-transparent rounded-lg focus:outline-none focus:bg-white focus:border-indigo-500 transition duration-200" type="text" placeholder="Search...">
            </div>
        </div>

        <div class="flex items-center space-x-4">
            <!-- Notifications (Placeholder) -->
             <button class="relative p-2 text-slate-400 hover:text-slate-600 transition duration-150">
                <span class="absolute top-2 right-2 h-2 w-2 rounded-full bg-red-500 border border-white"></span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </button>

            <!-- Profile Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                     <div class="flex flex-col items-end mr-2 hidden md:block">
                        <span class="text-sm font-semibold text-slate-700">{{ Auth::user()->name }}</span>
                        <span class="text-xs text-slate-500 uppercase">{{ str_replace('_', ' ', Auth::user()->role) }}</span>
                    </div>
                    <img class="object-cover w-10 h-10 rounded-full border-2 border-indigo-100 shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4338ca&color=fff" alt="Avatar">
                </button>

                <div x-show="open" @click.away="open = false" class="absolute right-0 w-48 mt-2 py-2 bg-white rounded-md shadow-xl z-50 ring-1 ring-black ring-opacity-5" style="display: none;">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Log out</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
