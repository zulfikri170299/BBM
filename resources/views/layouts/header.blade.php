<header class="flex h-16 shrink-0 items-center justify-between gap-x-4 border-b border-white/5 bg-slate-900/40 backdrop-blur-xl px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8 sticky top-0 z-20">
    <div class="flex items-center gap-3">
        {{-- Hamburger Button (Mobile) --}}
        <button type="button" class="-m-2.5 p-2.5 text-slate-400 hover:text-white transition-colors lg:hidden" @click="sidebarOpen = true">
            <span class="sr-only">Buka sidebar</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        {{-- Page Title (Mobile) --}}
        <div class="lg:hidden flex items-center">
            <h1 class="text-sm font-bold text-slate-200">
                {{ auth()->user()->satker->nama_satker ?? 'BIRO LOGISTIK' }}
            </h1>
        </div>
    </div>

    <div class="flex items-center gap-x-4 lg:gap-x-6">
        <!-- Notifications -->
        <div x-data="{ 
            notificationsOpen: false, 
            unreadCount: {{ auth()->user()->unreadNotifications->count() }},
            markAsRead(id) {
                fetch(`/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                }).then(response => {
                    if (response.ok) {
                        this.unreadCount = Math.max(0, this.unreadCount - 1);
                        const el = document.getElementById(`notification-${id}`);
                        if (el) el.remove();
                    }
                });
            }
        }" class="relative">
            <button @click="notificationsOpen = !notificationsOpen" type="button" class="-m-2.5 p-2.5 text-slate-400 hover:text-white transition-colors relative focus:outline-none">
                <span class="sr-only">View notifications</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
                <template x-if="unreadCount > 0">
                    <span class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-brand-primary text-[9px] font-bold text-white ring-2 ring-slate-900 shadow-[0_0_10px_rgba(0,98,255,0.8)]" x-text="unreadCount"></span>
                </template>
            </button>

            <!-- Notifications Dropdown -->
            <div x-show="notificationsOpen" @click.away="notificationsOpen = false"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="absolute right-0 mt-2 w-80 max-w-[calc(100vw-2rem)] bg-slate-900 border border-white/10 rounded-2xl shadow-2xl z-[100] ring-1 ring-black ring-opacity-5 overflow-hidden"
                style="display: none;">

                <div class="p-4 border-b border-white/5 flex justify-between items-center bg-slate-800/50">
                    <h3 class="font-bold text-white">Notifikasi</h3>
                    <span class="px-2 py-0.5 bg-brand-primary/20 text-brand-primary border border-brand-primary/30 text-[10px] font-bold rounded-full"
                        x-text="unreadCount + ' Baru'"></span>
                </div>

                <div class="max-h-96 overflow-y-auto custom-scrollbar">
                    @forelse(auth()->user()->unreadNotifications as $notification)
                        <div id="notification-{{ $notification->id }}"
                            class="p-4 border-b border-white/5 hover:bg-white/5 transition-colors group relative">
                            <div class="flex gap-3">
                                <div class="shrink-0 w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-slate-200 truncate">
                                        {{ $notification->data['title'] }}
                                    </p>
                                    <p class="text-xs text-slate-400 mt-1 leading-relaxed line-clamp-2">
                                        {{ $notification->data['message'] }}
                                    </p>
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="text-[10px] font-medium text-slate-500">{{ $notification->created_at->diffForHumans() }}</span>
                                        <button @click="markAsRead('{{ $notification->id }}')"
                                            class="text-[10px] font-bold text-brand-primary hover:text-white bg-brand-primary/10 hover:bg-brand-primary border border-brand-primary/20 px-2 py-1 rounded-lg transition-colors">
                                            Tandai Dibaca
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 bg-slate-800 border border-white/5 text-slate-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-slate-400">Tidak ada notifikasi baru</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Separator -->
        <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-white/10" aria-hidden="true"></div>

        <!-- Profile dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" type="button" class="-m-1.5 flex items-center p-1.5 focus:outline-none" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                <span class="sr-only">Buka user menu</span>
                <img class="h-8 w-8 sm:h-9 sm:w-9 rounded-full bg-slate-800 ring-2 ring-brand-primary/50 object-cover shadow-[0_0_10px_rgba(0,98,255,0.3)]" src="{{ Auth::user()->profile_photo_url }}" alt="">
                <span class="hidden lg:flex lg:items-center">
                    <span class="ml-4 text-sm font-semibold leading-6 text-slate-200" aria-hidden="true">{{ Auth::user()->name }}</span>
                    <svg class="ml-2 h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </span>
            </button>

            <!-- Dropdown menu -->
            <div x-show="open" @click.away="open = false" 
                x-transition:enter="transition ease-out duration-100" 
                x-transition:enter-start="transform opacity-0 scale-95" 
                x-transition:enter-end="transform opacity-100 scale-100" 
                x-transition:leave="transition ease-in duration-75" 
                x-transition:leave-start="transform opacity-100 scale-100" 
                x-transition:leave-end="transform opacity-0 scale-95" 
                class="absolute right-0 z-10 mt-2.5 w-48 origin-top-right rounded-xl bg-slate-900 border border-white/10 py-2 shadow-2xl ring-1 ring-white/5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" style="display: none;">
                
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm leading-6 text-slate-300 hover:text-white hover:bg-white/5" role="menuitem" tabindex="-1">Profil Anda</a>
                
                @if(auth()->user()->is_developer)
                    <div class="h-px bg-white/5 my-1"></div>
                    <form method="POST" action="{{ route('dev.lockdown.toggle') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-4 py-2 text-sm leading-6 {{ \App\Models\Setting::isSystemLocked() ? 'text-emerald-400 hover:bg-emerald-400/10' : 'text-rose-400 hover:bg-rose-400/10' }}">
                            {{ \App\Models\Setting::isSystemLocked() ? __('Aktifkan Sistem') : __('Lockdown Sistem') }}
                        </button>
                    </form>
                @endif
                
                <div class="h-px bg-white/5 my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm leading-6 text-red-400 hover:text-red-300 hover:bg-red-400/10" role="menuitem" tabindex="-1">Log out</a>
                </form>
            </div>
        </div>
    </div>
</header>