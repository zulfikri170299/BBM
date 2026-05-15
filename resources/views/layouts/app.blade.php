<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SPBP Rolog Polda NTB') }}</title>
    <link rel="icon" href="{{ asset('rolog.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Hotwire Turbo Drive (SPA Navigation) -->
    <script type="module" data-turbo-track="reload">
        import * as Turbo from 'https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.12/+esm';
        // Only intercept link clicks for SPA navigation, NOT form submissions
        Turbo.config.forms.mode = "optin";
    </script>

    <!-- Styles & Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: '#4338ca',
                        secondary: '#64748B',
                        dark: '#0f172a',
                    },
                },
            },
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts" data-turbo-track="reload"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" data-turbo-track="reload"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    @stack('head')

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            /* body { font-family: 'Outfit', sans-serif; } - Handled by font-sans class */
        }

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

        /* TomSelect Premium Styling */
        .ts-wrapper .ts-control {
            border: 2px solid #f1f5f9;
            background-color: #f8fafc;
            border-radius: 0.75rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 700;
            color: #334155;
            box-shadow: none;
            transition: all 0.2s;
            min-height: 38px;
            display: flex;
            align-items: center;
        }

        .ts-wrapper.focus .ts-control {
            border-color: #6366f1;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .ts-wrapper .ts-dropdown {
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            font-size: 0.75rem;
            overflow: hidden;
            padding: 0.5rem;
            z-index: 100;
        }

        .ts-wrapper .ts-dropdown .option {
            padding: 0.625rem 0.75rem;
            border-radius: 0.5rem;
            font-weight: 600;
        }

        .ts-wrapper .ts-dropdown .active {
            background-color: #f1f5f9;
            color: #4338ca;
        }
        
        .ts-wrapper .ts-control .item {
            font-weight: 700;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        /* Flatpickr Premium Indigo Styling */
        .flatpickr-calendar {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border-radius: 2rem;
            border: 1px solid #e2e8f0;
            padding: 1rem;
            font-family: 'Outfit', sans-serif;
            background: #ffffff;
            width: 315px; /* Slightly wider for better readability */
        }
        .flatpickr-months {
            margin-bottom: 0.5rem;
        }
        .flatpickr-month {
            height: 40px;
        }
        .flatpickr-day {
            border-radius: 12px;
            font-weight: 600;
            height: 38px;
            line-height: 38px;
            margin: 2px;
        }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, 
        .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, 
        .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, 
        .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, 
        .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, 
        .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, 
        .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay, 
        .flatpickr-day.selected.nextMonthDay, .flatpickr-day.startRange.nextMonthDay, 
        .flatpickr-day.endRange.nextMonthDay {
            background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%) !important;
            border-color: transparent !important;
            box-shadow: 0 4px 12px rgba(67, 56, 202, 0.3);
        }
        .flatpickr-day.today {
            border-color: #4338ca;
            color: #4338ca;
        }
        .flatpickr-day.today:hover {
            background: #f1f5f9;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months {
            font-weight: 800 !important;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .flatpickr-weekday {
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            font-size: 10px;
        }
        /* Style for the input itself - add a nice icon */
        .flatpickr-input-container {
            position: relative;
        }
        .flatpickr-input-container::after {
            content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5' /%3E%3C/svg%3E");
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1.25rem;
            height: 1.25rem;
            pointer-events: none;
            transition: all 0.2s;
        }
        .flatpickr-input-container:focus-within::after {
            filter: invert(24%) sepia(86%) saturate(3015%) hue-rotate(240deg) brightness(85%) contrast(92%); /* indigo-600 */
        }
        .flatpickr-months .flatpickr-month {
            color: #1e293b;
            fill: #1e293b;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months {
            font-weight: 700;
        }
        .flatpickr-calendar .flatpickr-innerContainer {
            margin-top: 0.5rem;
        }
        .flatpickr-day {
            border-radius: 0.75rem;
            font-weight: 600;
            color: #475569;
        }
        .flatpickr-day.today {
            border-color: #4338ca;
            color: #4338ca;
        }
        .flatpickr-day:hover {
            background: #f1f5f9;
        }
    </style>
    @stack('styles')
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-900">
    <div x-data="{ sidebarOpen: false }" @sidebar-close.window="sidebarOpen = false"
        class="flex h-[100dvh] bg-slate-50 overflow-hidden">
        @include('layouts.sidebar')

        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden pt-1">
            @include('layouts.header')

            <!-- Global Notification Modals -->
            @if(session('success') || session('error') || $errors->any())
                <div x-data="{ show: true }" x-show="show"
                    class="fixed inset-0 z-[9999] flex flex-col items-center justify-center p-4 sm:p-0 gap-4">
                    <!-- Backdrop -->
                    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="show = false"
                        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

                    @if(session('success'))
                        <div class="relative w-full max-w-sm bg-white rounded-[2rem] shadow-2xl overflow-hidden flex flex-col items-center p-8 text-center"
                            @click.stop x-transition:enter="transition ease-out duration-300 transform"
                            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            x-transition:leave="transition ease-in duration-200 transform"
                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                            x-transition:leave-end="opacity-0 translate-y-4 scale-95">

                            <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-10 h-10 text-emerald-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                    </path>
                                </svg>
                            </div>

                            <h3 class="text-[22px] font-bold text-slate-800 mb-2">Berhasil!</h3>
                            <div class="text-[15px] font-medium text-slate-500 mb-8">{!! session('success') !!}</div>

                            <button @click="show = false"
                                class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white rounded-[1rem] font-semibold transition-colors">OK, Mengerti</button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="relative w-full max-w-sm bg-white rounded-[2rem] shadow-2xl overflow-hidden flex flex-col items-center p-8 text-center"
                            @click.stop x-transition:enter="transition ease-out duration-300 transform"
                            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            x-transition:leave="transition ease-in duration-200 transform"
                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                            x-transition:leave-end="opacity-0 translate-y-4 scale-95">

                            <div class="w-20 h-20 bg-rose-50 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-10 h-10 text-rose-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>

                            <h3 class="text-[22px] font-bold text-slate-800 mb-2">Oops, Terjadi Kesalahan!</h3>
                            <div class="text-[15px] font-medium text-slate-500 mb-8">{!! session('error') !!}</div>

                            <button @click="show = false"
                                class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white rounded-[1rem] font-semibold transition-colors">OK, Mengerti</button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="relative w-full max-w-sm bg-white rounded-[2rem] shadow-2xl overflow-hidden flex flex-col items-center p-8 text-center"
                            @click.stop x-transition:enter="transition ease-out duration-300 transform"
                            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            x-transition:leave="transition ease-in duration-200 transform"
                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                            x-transition:leave-end="opacity-0 translate-y-4 scale-95">

                            <div class="w-20 h-20 bg-amber-50 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-10 h-10 text-amber-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>

                            <h3 class="text-[22px] font-bold text-slate-800 mb-3">Periksa Inputan Anda</h3>
                            <div class="text-[14px] font-medium text-slate-500 w-full mb-8">
                                <ul class="space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            <button @click="show = false"
                                class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white rounded-[1rem] font-semibold transition-colors">Tutup & Perbaiki</button>
                        </div>
                    @endif
                </div>
            @endif

            <main>
                {{ $slot }}
            </main>
        </div>
    </div>
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Global TomSelect Initializer
        window.initTomSelect = window.initTomSelect || (() => {
            document.querySelectorAll('.tom-select:not(.ts-wrapper)').forEach(el => {
                new TomSelect(el, {
                    create: false,
                    dropdownParent: 'body',
                    onDropdownOpen: (dropdown) => {
                        dropdown.style.zIndex = "9999";
                    }
                });
            });
        });

        if (!window.tomSelectBound) {
            window.tomSelectBound = true;
            document.addEventListener('turbo:load', window.initTomSelect);
            document.addEventListener('turbo:render', window.initTomSelect);
        }

        // Global Flatpickr Initializer
        window.initFlatpickr = window.initFlatpickr || (() => {
            const inputs = document.querySelectorAll('input[type="date"]:not(.flatpickr-input), .flatpickr:not(.flatpickr-input)');
            
            inputs.forEach(el => {
                // Pre-wrap the element for the icon container
                // This must be done BEFORE flatpickr initialization to prevent event listener issues
                if (!el.parentNode.classList.contains('flatpickr-input-container')) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'flatpickr-input-container w-full';
                    el.parentNode.insertBefore(wrapper, el);
                    wrapper.appendChild(el);
                }

                // Determine if this is a "Dari" (Start) or "Sampai" (End) field
                const isStart = el.name && (el.name.includes('start') || el.name.includes('dari'));
                const isEnd = el.name && (el.name.includes('end') || el.name.includes('sampai'));
                
                const config = {
                    locale: "id",
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d F Y",
                    placeholder: "Tgl",
                    disableMobile: "true",
                    prevArrow: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>',
                    nextArrow: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>',
                    onReady: function(selectedDates, dateStr, instance) {
                        // Set placeholder explicitly on the altInput
                        if (instance.altInput) {
                            instance.altInput.setAttribute('placeholder', 'Tgl');
                        }
                    },
                    onChange: function(selectedDates, dateStr, instance) {
                        // Find potential paired inputs in the same form
                        if (isStart || isEnd) {
                            const form = el.closest('form');
                            if (form) {
                                if (isStart) {
                                    const endInput = form.querySelector('[name*="end"], [name*="sampai"]');
                                    if (endInput && endInput._flatpickr) {
                                        endInput._flatpickr.set('minDate', dateStr);
                                    }
                                } else if (isEnd) {
                                    const startInput = form.querySelector('[name*="start"], [name*="dari"]');
                                    if (startInput && startInput._flatpickr) {
                                        startInput._flatpickr.set('maxDate', dateStr);
                                    }
                                }
                            }
                        }
                    }
                };

                flatpickr(el, config);
            });
        });

        if (!window.flatpickrBound) {
            window.flatpickrBound = true;
            document.addEventListener('turbo:load', window.initFlatpickr);
            document.addEventListener('turbo:render', window.initFlatpickr);
        }
        
        // Global helper definitions (Only define once)
        window.showAlert = window.showAlert || ((title, text, icon = 'info') => {
            Swal.fire({
                title, text, icon,
                confirmButtonColor: '#4338ca',
                confirmButtonText: 'Tutup',
                customClass: {
                    popup: 'rounded-[2rem] border-none shadow-2xl',
                    title: 'text-2xl font-bold text-slate-800',
                    confirmButton: 'rounded-xl px-10 py-3 font-bold uppercase tracking-wider text-sm'
                }
            });
        });

        window.confirmDialog = window.confirmDialog || ((options, callback) => {
            const type = options.type || 'question';
            let confirmColor = '#4338ca';
            if (type === 'error' || type === 'danger') confirmColor = '#e11d48';
            if (type === 'warning') confirmColor = '#f59e0b';

            Swal.fire({
                title: options.title || 'Konfirmasi',
                text: options.message,
                icon: type,
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#94a3b8',
                confirmButtonText: options.confirmText || 'Ya, Lanjutkan!',
                cancelButtonText: options.cancelText || 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] border-none shadow-2xl p-8',
                    title: 'text-2xl font-black text-slate-800 mb-2',
                    htmlContainer: 'text-slate-500 font-medium mb-6',
                    confirmButton: 'rounded-2xl px-8 py-3.5 font-black uppercase tracking-widest text-xs shadow-lg shadow-indigo-200 ml-3',
                    cancelButton: 'rounded-2xl px-8 py-3.5 font-bold uppercase tracking-widest text-xs text-slate-600 hover:bg-slate-100'
                },
                buttonsStyling: true
            }).then((result) => {
                if (result.isConfirmed && callback) callback();
            });
        });

        // Chat Polling (Initialize once)
        if (!window.chatPollingActive) {
            window.chatPollingActive = true;
            let lastUnreadCount = 0;
            let isFirstCheck = true;
            let userInteracted = false;
            const notificationSound = new Audio('https://assets.mixkit.co/sfx/preview/mixkit-software-interface-start-2574.mp3');

            document.addEventListener('click', () => { userInteracted = true; notificationSound.load(); }, { once: true });

            function checkUnreadMessages() {
                const sidebarBadge = document.getElementById('sidebar-chat-badge');
                if (!sidebarBadge) return;
                @auth
                    fetch("{{ route('chat.unread.count') }}")
                        .then(r => r.json()).then(data => {
                            const count = data.count;
                            if (count > 0) {
                                sidebarBadge.textContent = count > 99 ? '99+' : count;
                                sidebarBadge.classList.remove('hidden');
                                sidebarBadge.classList.add('flex');
                                if (count > lastUnreadCount && !isFirstCheck && userInteracted) {
                                    notificationSound.currentTime = 0;
                                    notificationSound.play().catch(e => console.log('Audio play failed:', e));
                                }
                            } else {
                                sidebarBadge.classList.add('hidden'); sidebarBadge.classList.remove('flex');
                            }
                            lastUnreadCount = count; isFirstCheck = false;
                        }).catch(err => console.error('Error checking unread messages:', err));
                @endauth
            }
            setInterval(checkUnreadMessages, 30000);
            checkUnreadMessages();
        }

        // Global Confirmation Handler (Bind once on document)
        if (!window.confirmHandlerBound) {
            window.confirmHandlerBound = true;
            document.addEventListener('click', function (e) {
                const target = e.target.closest('[data-confirm]');
                if (target) {
                    e.preventDefault();
                    window.confirmDialog({
                        message: target.getAttribute('data-confirm'),
                        type: target.getAttribute('data-confirm-type') || 'warning',
                        confirmText: target.getAttribute('data-confirm-text') || 'Ya, Lanjutkan!',
                        title: target.getAttribute('data-confirm-title') || 'Konfirmasi'
                    }, () => {
                        const form = target.closest('form');
                        if (form) form.submit();
                        else if (target.tagName === 'A') window.location.href = target.href;
                    });
                }
            });
        }

        // Sidebar logic to run on EVERY Turbo visit
        if (!window.sidebarLogicBound) {
            window.sidebarLogicBound = true;
            document.addEventListener('turbo:load', function () {
                // 1. Snappily close sidebar on mobile navigation
                if (window.innerWidth < 1024) {
                    window.dispatchEvent(new CustomEvent('sidebar-close'));
                }
                window.dispatchEvent(new CustomEvent('close-reports'));

                const currentPath = window.location.pathname;
                const sidebar = document.getElementById('sidebar');
                if (!sidebar) return;

            const allLinks = sidebar.querySelectorAll('a[href]');
            const mainActive = ['bg-indigo-600', 'shadow-lg', 'shadow-indigo-500/30'], mainInactive = ['hover:bg-slate-800'];
            const subActive = ['text-white', 'bg-indigo-600/50'], subInactive = ['text-slate-400', 'hover:text-white', 'hover:bg-slate-800'];

            let activeSubmenuDropdown = null, bestMatchLink = null, bestMatchLength = 0;

            allLinks.forEach(link => {
                const isInsideSubmenu = !!link.closest('div[x-show]');
                if (isInsideSubmenu) { link.classList.remove(...subActive); link.classList.add(...subInactive); }
                else { link.classList.remove(...mainActive); if (!link.classList.contains('hover:bg-slate-800')) link.classList.add(...mainInactive); }

                const href = link.getAttribute('href');
                if (!href || href === '#' || href.startsWith('javascript:')) return;
                try {
                    const linkPath = new URL(href, window.location.origin).pathname;
                    if (currentPath.startsWith(linkPath)) {
                        if (linkPath.length > bestMatchLength) { bestMatchLength = linkPath.length; bestMatchLink = link; }
                    }
                } catch (e) {}
            });

            if (bestMatchLink) {
                const isInsideSubmenu = !!bestMatchLink.closest('div[x-show]');
                if (isInsideSubmenu) {
                    bestMatchLink.classList.remove(...subInactive); bestMatchLink.classList.add(...subActive);
                    activeSubmenuDropdown = bestMatchLink.closest('div[x-show]');
                } else {
                    bestMatchLink.classList.remove(...mainInactive); bestMatchLink.classList.add(...mainActive);
                }
            }

                if (activeSubmenuDropdown) {
                    const dropdownContainer = activeSubmenuDropdown.closest('.space-y-1');
                    if (dropdownContainer) {
                        const button = dropdownContainer.querySelector('button');
                        if (button) {
                            const evt = button.getAttribute('@click').includes('satkerReportsOpen') ? 'open-satker-reports' : 'open-admin-reports';
                            window.dispatchEvent(new CustomEvent(evt));
                        }
                    }
                }
            });
        }

        // GeoLocation Logic (Run once per session or day)
        if (!window.geoLocChecked) {
            window.geoLocChecked = true;
            const lastLocationUpdate = localStorage.getItem('lastLocationUpdate');
            if ("geolocation" in navigator && (!lastLocationUpdate || (Date.now() - parseInt(lastLocationUpdate)) > 86400000)) {
                navigator.geolocation.getCurrentPosition(function (p) {
                    fetch("{{ route('profile.location.update') }}", {
                        method: "POST", headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                        body: JSON.stringify({ latitude: p.coords.latitude, longitude: p.coords.longitude })
                    }).then(() => localStorage.setItem('lastLocationUpdate', Date.now().toString()));
                });
            }
        }
    </script>
</body>

</html>