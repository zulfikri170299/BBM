<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIMAK BBM') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Outfit', 'sans-serif'],
                        },
                        colors: {
                            primary: '#4338ca', // Indigo 700
                            secondary: '#64748B', // Slate 500
                            dark: '#0f172a',
                        }
                    }
                }
            }
        </script>
        <style>
            body { font-family: 'Outfit', sans-serif; }
            .glass {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.5);
            }
            .sidebar-active {
                background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%);
                color: white;
                box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.4);
            }
        </style>
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900">
        <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-slate-50 overflow-hidden">
            @include('layouts.sidebar')

            <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
                @include('layouts.header')

                <!-- Global Notification (Moved outside main for better visibility) -->
                @if(session('success'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-xl flex items-center shadow-sm relative z-50 transition-all duration-300 ease-in-out transform hover:scale-[1.01]" role="alert">
                            <div class="shrink-0">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="block sm:inline font-bold">{{ session('success') }}</span>
                            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-900 focus:outline-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                        <div class="bg-rose-50 border border-rose-100 text-rose-700 px-4 py-3 rounded-xl flex items-center shadow-sm relative z-50 transition-all duration-300 ease-in-out transform hover:scale-[1.01]" role="alert">
                             <div class="shrink-0">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="block sm:inline font-bold">{{ session('error') }}</span>
                            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-rose-400 hover:text-rose-900 focus:outline-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                        <div class="bg-rose-50 border border-rose-100 text-rose-700 px-4 py-3 rounded-xl shadow-sm relative z-50">
                            <ul class="list-disc list-inside text-sm font-bold">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
        @stack('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // GeoLocation Logic
                if ("geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        fetch("{{ route('profile.location.update') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                latitude: position.coords.latitude,
                                longitude: position.coords.longitude
                            })
                        });
                    });
                }

                // Chat Notification Logic
                const sidebarBadge = document.getElementById('sidebar-chat-badge');
                // Gunakan URL yang lebih stabil atau asset lokal jika ada
                const notificationSound = new Audio('https://assets.mixkit.co/sfx/preview/mixkit-software-interface-start-2574.mp3'); 
                
                let lastUnreadCount = 0;
                let isFirstCheck = true;
                let userInteracted = false;

                // Unlock audio on first interaction
                document.addEventListener('click', function() {
                    userInteracted = true;
                    notificationSound.load(); // Preload on interaction
                }, { once: true });

                function checkUnreadMessages() {
                    // Only check if user is logged in
                    @auth
                    fetch("{{ route('chat.unread.count') }}")
                        .then(response => response.json())
                        .then(data => {
                            const count = data.count;
                            
                            if (count > 0) {
                                sidebarBadge.textContent = count > 99 ? '99+' : count;
                                sidebarBadge.classList.remove('hidden');
                                sidebarBadge.classList.add('flex');
                                
                                // Play sound if new unread messages arrive
                                if (count > lastUnreadCount && !isFirstCheck) {
                                    if (userInteracted) {
                                        notificationSound.currentTime = 0;
                                        notificationSound.play().catch(e => console.log('Audio play failed:', e));
                                    } else {
                                        console.log('User has not interacted yet, sound blocked.');
                                    }
                                }
                            } else {
                                sidebarBadge.classList.add('hidden');
                                sidebarBadge.classList.remove('flex');
                            }
                            
                            lastUnreadCount = count;
                            isFirstCheck = false;
                        })
                        .catch(err => console.error('Error checking unread messages:', err));
                    @endauth
                }

                // Check every 3 seconds for faster feedback
                setInterval(checkUnreadMessages, 3000);
                
                // Initial check
                checkUnreadMessages();
            });
        </script>
    </body>
</html>
