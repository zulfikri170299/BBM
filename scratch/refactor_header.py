import sys

with open(r'd:\PROJEK\BBM\BBM\resources\views\layouts\header.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

import re

# We will completely replace the header tag but keep the AlpineJS logic inside the dropdowns.
new_header = """<header class="navbar bg-base-100 border-b border-base-300 sticky top-0 z-20 px-4">
    <div class="flex-none lg:hidden">
        <label for="main-drawer" aria-label="open sidebar" class="btn btn-square btn-ghost">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </label>
    </div>
    <div class="flex-1 lg:hidden">
        <h1 class="text-sm font-bold truncate ml-2">
            {{ auth()->user()->satker->nama_satker ?? 'BIRO LOGISTIK' }}
        </h1>
    </div>
    <div class="flex-1 hidden lg:flex">
        <!-- Empty flex-1 for desktop to push right items -->
    </div>
    <div class="flex-none gap-2">
        <!-- Theme Toggle -->
        <button type="button" @click="isDarkMode = !isDarkMode" class="btn btn-ghost btn-circle">
            <!-- Sun icon for light mode -->
            <svg x-show="!isDarkMode" class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
            </svg>
            <!-- Moon icon for dark mode -->
            <svg x-show="isDarkMode" x-cloak class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
            </svg>
        </button>

        <!-- Notifications -->
        <div class="dropdown dropdown-end" x-data="{ 
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
        }">
            <label tabindex="0" class="btn btn-ghost btn-circle">
                <div class="indicator">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    <span class="badge badge-primary badge-xs indicator-item" x-show="unreadCount > 0" x-text="unreadCount"></span>
                </div>
            </label>
            <div tabindex="0" class="mt-3 z-[1] card card-compact dropdown-content w-80 bg-base-100 shadow">
                <div class="card-body">
                    <div class="flex justify-between items-center border-b border-base-200 pb-2">
                        <span class="font-bold text-lg">Notifikasi</span>
                        <span class="badge badge-primary" x-text="unreadCount + ' Baru'"></span>
                    </div>
                    <div class="max-h-96 overflow-y-auto custom-scrollbar">
                        @forelse(auth()->user()->unreadNotifications as $notification)
                            <div id="notification-{{ $notification->id }}" class="flex gap-3 py-3 border-b border-base-200">
                                <div class="shrink-0 w-10 h-10 rounded-xl bg-warning/20 text-warning flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold truncate">{{ $notification->data['title'] }}</p>
                                    <p class="text-xs opacity-70 mt-1 line-clamp-2">{{ $notification->data['message'] }}</p>
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="text-[10px] opacity-50">{{ $notification->created_at->diffForHumans() }}</span>
                                        <button @click="markAsRead('{{ $notification->id }}')" class="btn btn-xs btn-primary btn-outline">Dibaca</button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 opacity-50 text-sm">Tidak ada notifikasi baru</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Year Filter -->
        <div class="dropdown dropdown-end">
            <label tabindex="0" class="btn btn-ghost">
                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
                {{ session('filter_tahun', date('Y')) }}
            </label>
            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-32 mt-3 overflow-y-auto max-h-60">
                <li class="menu-title"><span>Pilih Tahun</span></li>
                @php
                    $currentYear = date('Y');
                    $startYear = 2020;
                @endphp
                @for($y = $currentYear; $y >= $startYear; $y--)
                    <li>
                        <form method="POST" action="{{ route('set-filter-tahun') }}" class="w-full">
                            @csrf
                            <input type="hidden" name="tahun" value="{{ $y }}">
                            <button type="submit" class="w-full text-left {{ session('filter_tahun', $currentYear) == $y ? 'active' : '' }}">
                                {{ $y }}
                            </button>
                        </form>
                    </li>
                @endfor
            </ul>
        </div>

        <!-- Profile Dropdown -->
        <div class="dropdown dropdown-end">
            <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                <div class="w-9 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile" />
                </div>
            </label>
            <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">
                <li class="menu-title"><span>{{ Auth::user()->name }}</span></li>
                <li><a href="{{ route('profile.edit') }}">Profil Anda</a></li>
                @if(auth()->user()->is_developer)
                    <div class="divider my-0"></div>
                    <li>
                        <form method="POST" action="{{ route('dev.lockdown.toggle') }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full text-left {{ \App\Models\Setting::isSystemLocked() ? 'text-success' : 'text-error' }}">
                                {{ \App\Models\Setting::isSystemLocked() ? __('Aktifkan Sistem') : __('Lockdown Sistem') }}
                            </button>
                        </form>
                    </li>
                @endif
                <div class="divider my-0"></div>
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full text-left text-error">Log out</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
"""

with open(r'd:\PROJEK\BBM\BBM\resources\views\layouts\header.blade.php', 'w', encoding='utf-8') as f:
    f.write(new_header)

print("Replaced header.blade.php layout successfully.")
