<header class="bg-white shadow-sm border-b border-slate-200 z-40 relative">
    <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4">
        <div class="flex items-center gap-3">
            {{-- Hamburger Button (Mobile) --}}
            <button @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden inline-flex items-center justify-center w-10 h-10 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 active:scale-95 focus:outline-none">
                <svg x-show="!sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6H20M4 12H20M4 18H11" />
                </svg>
                <svg x-show="sidebarOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            {{-- Page Title (Mobile) --}}
            <div class="lg:hidden">
                <h1 class="text-sm font-bold text-slate-800">
                    {{ auth()->user()->satker->nama_satker ?? 'BIRO LOGISTIK' }}
                </h1>
            </div>
        </div>

        <div class="flex items-center space-x-2 sm:space-x-4">
            <!-- Notifications (Functional) -->
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
                <button @click="notificationsOpen = !notificationsOpen"
                    class="relative p-2 text-slate-400 hover:text-indigo-600 transition duration-150 focus:outline-none rounded-xl hover:bg-indigo-50 active:scale-95">
                    <template x-if="unreadCount > 0">
                        <span
                            class="absolute top-1 right-1 h-4 w-4 rounded-full bg-red-500 border-2 border-white text-[10px] text-white flex items-center justify-center font-bold"
                            x-text="unreadCount"></span>
                    </template>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>
                </button>

                <div x-show="notificationsOpen" @click.away="notificationsOpen = false"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="absolute right-0 mt-2 w-80 max-w-[calc(100vw-2rem)] bg-white rounded-2xl shadow-2xl z-[100] ring-1 ring-black ring-opacity-5 overflow-hidden"
                    style="display: none;">

                    <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                        <h3 class="font-bold text-slate-800">Notifikasi</h3>
                        <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 text-[10px] font-bold rounded-full"
                            x-text="unreadCount + ' Baru'"></span>
                    </div>

                    <div class="max-h-96 overflow-y-auto">
                        @forelse(auth()->user()->unreadNotifications as $notification)
                            <div id="notification-{{ $notification->id }}"
                                class="p-4 border-b border-slate-50 hover:bg-slate-50 transition-colors group relative">
                                <div class="flex gap-3">
                                    <div
                                        class="shrink-0 w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-slate-800 truncate">
                                            {{ $notification->data['title'] }}
                                        </p>
                                        <p class="text-xs text-slate-500 mt-1 leading-relaxed line-clamp-2">
                                            {{ $notification->data['message'] }}
                                        </p>
                                        <div class="flex justify-between items-center mt-2">
                                            <span
                                                class="text-[10px] font-medium text-slate-400">{{ $notification->created_at->diffForHumans() }}</span>
                                            <button @click="markAsRead('{{ $notification->id }}')"
                                                class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-2 py-1 rounded-lg">
                                                Tandai Dibaca
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center">
                                <div
                                    class="w-16 h-16 bg-slate-100 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                        </path>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-slate-500">Tidak ada notifikasi baru</p>
                            </div>
                        @endforelse
                    </div>

                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <div class="p-3 bg-slate-50 border-t border-slate-100 text-center">
                            <a href="#" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Lihat Semua
                                Riwayat</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="flex items-center space-x-2 focus:outline-none active:scale-95 transition-transform">
                    <div class="flex flex-col items-end mr-2 hidden md:block">
                        <span class="text-sm font-semibold text-slate-700">{{ Auth::user()->name }}</span>
                        <span
                            class="text-xs text-slate-500 uppercase">{{ str_replace('_', ' ', Auth::user()->role) }}</span>
                    </div>
                    <img class="object-cover w-9 h-9 sm:w-10 sm:h-10 rounded-full border-2 border-indigo-100 shadow-sm"
                        src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                </button>

                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="absolute right-0 w-48 mt-2 py-2 bg-white rounded-xl shadow-2xl z-50 ring-1 ring-black ring-opacity-5"
                    style="display: none;">
                    <a href="{{ route('profile.edit') }}"
                        class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 font-medium">Profile</a>

                    @if(auth()->user()->is_developer)
                        <div class="border-t border-slate-100 italic"></div>
                        <form method="POST" action="{{ route('dev.lockdown.toggle') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2.5 text-sm {{ \App\Models\Setting::isSystemLocked() ? 'text-emerald-600' : 'text-rose-600' }} hover:bg-slate-50 font-bold">
                                {{ \App\Models\Setting::isSystemLocked() ? __('Aktifkan Sistem') : __('Lockdown Sistem') }}
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                            class="block px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-medium">Log out</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>