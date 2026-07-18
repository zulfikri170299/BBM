<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SPBP Rolog Polda NTB') }}</title>
    <link rel="icon" href="{{ asset('rolog.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Inter:wght@100..900&display=swap" rel="stylesheet">

    <!-- Hotwire Turbo Drive (SPA Navigation) -->
    <script type="module" data-turbo-track="reload">
        import * as Turbo from 'https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.12/+esm';
        Turbo.config.forms.mode = "optin";
    </script>

    <!-- Styles & Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-primary': '#0062ff',
                        'brand-dark': '#0a0a0b',
                        'slate': { 
                            800: '#1e293b',
                            900: '#0f172a', 
                            950: '#020617' 
                        }
                    },
                    fontFamily: { 
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif']
                    }
                }
            }
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    @stack('head')

    <style>
        .swal2-container { z-index: 99999 !important; }
        [x-cloak] { display: none !important; }
        @keyframes shimmerText {
            0% { background-position: -200% center; }
            100% { background-position: 200% center; }
        }
        .desktop-shimmer {
            background: linear-gradient(to right, #dc2626 0%, #eab308 40%, #ffffff 50%, #eab308 60%, #dc2626 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            color: transparent;
            animation: shimmerText 4s linear infinite;
        }
        @keyframes desktopFloat {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-3px) rotate(3deg) scale(1.05); }
        }
        .desktop-float {
            animation: desktopFloat 3s ease-in-out infinite;
        }
        @keyframes contentFadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .content-animate {
            animation: contentFadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        
        body {
            -webkit-tap-highlight-color: transparent;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.2); }

        /* Dark Theme overrides for TomSelect */
        .ts-wrapper .ts-control {
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            background-color: rgba(15, 23, 42, 0.6) !important;
            border-radius: 0.75rem !important;
            padding: 0.5rem 0.75rem !important;
            font-size: 0.75rem !important;
            font-weight: 500 !important;
            color: #f1f5f9 !important;
            box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.05) !important;
            transition: all 0.2s;
            min-height: 38px;
            display: flex;
            align-items: center;
        }

        .ts-wrapper.focus .ts-control {
            border-color: #0062ff !important;
            background-color: rgba(15, 23, 42, 0.9) !important;
            box-shadow: 0 0 0 2px rgba(0, 98, 255, 0.2) !important;
        }

        .ts-dropdown, .ts-wrapper .ts-dropdown {
            position: absolute;
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
            background-color: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            color: #f1f5f9;
            font-size: 0.75rem;
            overflow: hidden;
            padding: 0.5rem;
            z-index: 99999;
            transform-origin: top;
            animation: dropdownFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes dropdownFadeIn {
            from { opacity: 0; transform: scaleY(0.95) translateY(-5px); }
            to { opacity: 1; transform: scaleY(1) translateY(0); }
        }

        .ts-dropdown .option, .ts-wrapper .ts-dropdown .option {
            padding: 0.625rem 0.75rem;
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .ts-dropdown .active, .ts-wrapper .ts-dropdown .active {
            background-color: rgba(255, 255, 255, 0.05);
            color: #38bdf8;
        }
        
        .ts-wrapper .ts-control .item {
            font-weight: 600;
            color: #f8fafc;
        }

        .ts-wrapper .ts-control input {
            color: #f8fafc !important;
        }

        /* Dark Theme overrides for Flatpickr */
        .flatpickr-calendar {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
            border-radius: 1.5rem !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            padding: 0.5rem !important;
            font-family: 'Inter', sans-serif !important;
            background: #0f172a !important;
            color: #f8fafc !important;
            z-index: 99999 !important;
        }
        .flatpickr-day {
            border-radius: 0.5rem !important;
            color: #cbd5e1 !important;
        }
        .flatpickr-day:hover, .flatpickr-day.prevMonthDay:hover, .flatpickr-day.nextMonthDay:hover, .flatpickr-day:focus, .flatpickr-day.prevMonthDay:focus, .flatpickr-day.nextMonthDay:focus {
            background: rgba(255,255,255,0.1) !important;
            border-color: transparent !important;
            color: #fff !important;
        }
        .flatpickr-day.today {
            border-color: rgba(255,255,255,0.2) !important;
            color: #38bdf8 !important;
        }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange {
            background: #0062ff !important;
            border-color: #0062ff !important;
            color: #fff !important;
        }
        .flatpickr-months .flatpickr-month {
            color: #f8fafc !important;
            fill: #f8fafc !important;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months {
            background: #0f172a !important;
            color: #f8fafc !important;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months .flatpickr-monthDropdown-month {
            background-color: #0f172a !important;
        }
        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            color: #94a3b8 !important;
            fill: #94a3b8 !important;
            transition: all 0.2s ease;
        }
        .flatpickr-months .flatpickr-prev-month:hover,
        .flatpickr-months .flatpickr-next-month:hover {
            color: #fff !important;
            fill: #fff !important;
        }
        span.flatpickr-weekday {
            color: #94a3b8 !important;
        }
        
        @media (max-width: 768px) {
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
                scrollbar-width: thin;
            }
        }
    </style>
    @stack('styles')
</head>

<body class="h-full font-sans antialiased text-slate-200 overflow-hidden bg-slate-900 relative selection:bg-brand-primary/30 selection:text-white" x-data="{ sidebarOpen: false }" @sidebar-close.window="sidebarOpen = false" @sidebar-open.window="sidebarOpen = true">
    <!-- Solid clean background instead of blur -->
    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none bg-slate-900">
    </div>

    <div class="flex h-full min-h-full">
        @include('layouts.sidebar')

        <!-- Main Content Wrapper -->
        <div class="flex flex-1 flex-col overflow-hidden relative z-0">
            @include('layouts.header')

            <!-- Global Notification Modals -->
            @if(session('success') || session('error') || $errors->any())
                <div x-data="{ show: true }" x-show="show" x-cloak
                    class="fixed inset-0 z-[9999] flex flex-col items-center justify-center p-4 sm:p-0 gap-4 pointer-events-none">
                    <!-- Backdrop -->
                    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity pointer-events-auto" @click="show = false"
                        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

                    @if(session('success'))
                        <div class="relative w-full max-w-sm bg-slate-900 border border-white/10 rounded-[2rem] shadow-2xl overflow-hidden flex flex-col items-center p-8 text-center pointer-events-auto"
                            @click.stop x-transition:enter="transition ease-out duration-300 transform"
                            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            x-transition:leave="transition ease-in duration-200 transform"
                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                            x-transition:leave-end="opacity-0 translate-y-4 scale-95">

                            <div class="w-20 h-20 bg-emerald-500/20 rounded-full flex items-center justify-center mb-6 border border-emerald-500/30">
                                <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>

                            <h3 class="text-[22px] font-bold text-white mb-2">Berhasil!</h3>
                            <div class="text-[15px] font-medium text-slate-400 mb-8">{!! session('success') !!}</div>

                            <button @click="show = false"
                                class="w-full py-3.5 bg-brand-primary hover:bg-blue-600 text-white rounded-[1rem] font-bold transition-colors shadow-[0_0_15px_rgba(0,98,255,0.4)]">OK, Mengerti</button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="relative w-full max-w-sm bg-slate-900 border border-white/10 rounded-[2rem] shadow-2xl overflow-hidden flex flex-col items-center p-8 text-center pointer-events-auto"
                            @click.stop x-transition:enter="transition ease-out duration-300 transform"
                            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            x-transition:leave="transition ease-in duration-200 transform"
                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                            x-transition:leave-end="opacity-0 translate-y-4 scale-95">

                            <div class="w-20 h-20 bg-rose-500/20 rounded-full flex items-center justify-center mb-6 border border-rose-500/30">
                                <svg class="w-10 h-10 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>

                            <h3 class="text-[22px] font-bold text-white mb-2">Oops, Terjadi Kesalahan!</h3>
                            <div class="text-[15px] font-medium text-slate-400 mb-8">{!! session('error') !!}</div>

                            <button @click="show = false"
                                class="w-full py-3.5 bg-slate-800 hover:bg-slate-700 border border-white/5 text-white rounded-[1rem] font-bold transition-colors">OK, Mengerti</button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="relative w-full max-w-sm bg-slate-900 border border-white/10 rounded-[2rem] shadow-2xl overflow-hidden flex flex-col items-center p-8 text-center pointer-events-auto"
                            @click.stop x-transition:enter="transition ease-out duration-300 transform"
                            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            x-transition:leave="transition ease-in duration-200 transform"
                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                            x-transition:leave-end="opacity-0 translate-y-4 scale-95">

                            <div class="w-20 h-20 bg-amber-500/20 rounded-full flex items-center justify-center mb-6 border border-amber-500/30">
                                <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>

                            <h3 class="text-[22px] font-bold text-white mb-3">Periksa Inputan Anda</h3>
                            <div class="text-[14px] font-medium text-slate-400 w-full mb-8">
                                <ul class="space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            <button @click="show = false"
                                class="w-full py-3.5 bg-slate-800 hover:bg-slate-700 border border-white/5 text-white rounded-[1rem] font-bold transition-colors">Tutup & Perbaiki</button>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto content-animate custom-scrollbar">
                {{ $slot }}
            </main>
        </div>
    </div>
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
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
            
            const scrollContainer = document.querySelector('main');
            if (scrollContainer && !scrollContainer.dataset.tomSelectScrollBound) {
                scrollContainer.dataset.tomSelectScrollBound = 'true';
                scrollContainer.addEventListener('scroll', () => {
                    document.querySelectorAll('.tom-select.tomselected').forEach(el => {
                        if (el.tomselect && el.tomselect.isOpen) el.tomselect.blur();
                    });
                }, { passive: true });
            }
        });

        if (!window.tomSelectBound) {
            window.tomSelectBound = true;
            document.addEventListener('turbo:load', window.initTomSelect);
            document.addEventListener('turbo:render', window.initTomSelect);
        }

        // Global Flatpickr Initializer
        window.initFlatpickr = window.initFlatpickr || (() => {
            try {
                const inputs = document.querySelectorAll('input[type="date"]:not(.flatpickr-input), .flatpickr:not(.flatpickr-input)');
                inputs.forEach(el => {
                    const isStart = el.name && (el.name.includes('start') || el.name.includes('dari'));
                    const isEnd = el.name && (el.name.includes('end') || el.name.includes('sampai'));
                    
                    const config = {
                        locale: typeof flatpickr !== 'undefined' && flatpickr.l10ns && flatpickr.l10ns.id ? 'id' : 'default',
                        dateFormat: "Y-m-d",
                        altInput: true,
                        altFormat: "d F Y",
                        placeholder: "Tgl",
                        disableMobile: true,
                        monthSelectorType: "dropdown",
                        animate: true,
                        appendTo: document.body,
                        static: false,
                        position: "auto",
                        defaultDate: el.getAttribute('data-default-date') || el.value,
                        prevArrow: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>',
                        nextArrow: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>',
                        onReady: function(selectedDates, dateStr, instance) {
                            if (instance.altInput) {
                                instance.altInput.setAttribute('placeholder', 'Tgl');
                            }
                        },
                        onChange: function(selectedDates, dateStr, instance) {
                            if (isStart || isEnd) {
                                const form = el.closest('form');
                                if (form) {
                                    if (isStart) {
                                        const endInput = form.querySelector('[name*="end"], [name*="sampai"]');
                                        if (endInput && endInput._flatpickr) endInput._flatpickr.set('minDate', dateStr);
                                    } else if (isEnd) {
                                        const startInput = form.querySelector('[name*="start"], [name*="dari"]');
                                        if (startInput && startInput._flatpickr) startInput._flatpickr.set('maxDate', dateStr);
                                    }
                                }
                            }
                        }
                    };
                    flatpickr(el, config);
                });
            } catch (e) {
                console.warn('Flatpickr init error:', e.message);
            }
        });

        if (!window.flatpickrBound) {
            window.flatpickrBound = true;
            document.addEventListener('turbo:load', window.initFlatpickr);
            document.addEventListener('turbo:render', window.initFlatpickr);
            
            // Clean up instances before Turbo caches to prevent duplicates and sluggishness on mobile
            document.addEventListener('turbo:before-cache', () => {
                // Destroy TomSelect
                document.querySelectorAll('.tomselected').forEach(el => {
                    if (el.tomselect) el.tomselect.destroy();
                });
                
                // Destroy Flatpickr
                document.querySelectorAll('.flatpickr-input').forEach(el => {
                    if (el._flatpickr) el._flatpickr.destroy();
                });
            });
        }
        
        window.showAlert = window.showAlert || ((title, text, icon = 'info') => {
            let iconColor = '#38bdf8';
            if (icon === 'error') iconColor = '#f43f5e';
            if (icon === 'success') iconColor = '#10b981';
            if (icon === 'warning') iconColor = '#f59e0b';

            Swal.fire({
                title, text, icon,
                background: '#0f172a',
                color: '#f8fafc',
                confirmButtonColor: '#0062ff',
                confirmButtonText: 'Tutup',
                customClass: {
                    popup: 'border border-white/10 rounded-[2rem] shadow-2xl',
                    title: 'text-2xl font-bold text-white',
                    confirmButton: 'rounded-xl px-10 py-3 font-bold uppercase tracking-wider text-sm shadow-[0_0_15px_rgba(0,98,255,0.4)]'
                }
            });
        });

        window.confirmDialog = window.confirmDialog || ((options, callback) => {
            const type = options.type || 'question';
            let confirmColor = '#0062ff';
            if (type === 'error' || type === 'danger') confirmColor = '#e11d48';
            if (type === 'warning') confirmColor = '#f59e0b';

            Swal.fire({
                title: options.title || 'Konfirmasi',
                text: options.message,
                icon: type,
                background: '#0f172a',
                color: '#cbd5e1',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#1e293b',
                confirmButtonText: options.confirmText || 'Ya, Lanjutkan!',
                cancelButtonText: options.cancelText || 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'border border-white/10 rounded-[2rem] shadow-2xl p-8',
                    title: 'text-2xl font-black text-white mb-2',
                    htmlContainer: 'text-slate-400 font-medium mb-6',
                    confirmButton: 'rounded-2xl px-8 py-3.5 font-black uppercase tracking-widest text-xs shadow-lg ml-3',
                    cancelButton: 'rounded-2xl px-8 py-3.5 font-bold uppercase tracking-widest text-xs text-slate-300 border border-white/10 hover:bg-white/5'
                },
                buttonsStyling: true
            }).then((result) => {
                if (result.isConfirmed && callback) callback();
            });
        });

        if (!window.chatPollingActive) {
            window.chatPollingActive = true;
            let lastUnreadCount = 0, isFirstCheck = true, userInteracted = false;
            const notificationSound = new Audio('https://assets.mixkit.co/sfx/preview/mixkit-software-interface-start-2574.mp3');
            document.addEventListener('click', () => { userInteracted = true; notificationSound.load(); }, { once: true });
            function checkUnreadMessages() {
                const sidebarBadge = document.getElementById('sidebar-chat-badge');
                if (!sidebarBadge) return;
                @auth
                    fetch("{{ route('chat.unread.count') }}").then(r => r.json()).then(data => {
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
            setInterval(checkUnreadMessages, 30000); checkUnreadMessages();
        }

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
                        else if (target.tagName === 'A') typeof Turbo !== 'undefined' ? Turbo.visit(target.href) : window.location.href = target.href;
                    });
                }
            });
        }

        if (!window.sidebarLogicBound) {
            window.sidebarLogicBound = true;
            document.addEventListener('turbo:load', function () {
                if (window.innerWidth < 1024) window.dispatchEvent(new CustomEvent('sidebar-close'));
                window.dispatchEvent(new CustomEvent('close-reports'));

                const currentPath = window.location.pathname;
                const sidebar = document.getElementById('sidebar-nav') || document;
                const allLinks = sidebar.querySelectorAll('nav a[href]');
                
                const mainActive = ['bg-brand-primary', 'text-white', 'shadow-lg', 'shadow-brand-primary/20'];
                const mainInactive = ['text-slate-400', 'hover:text-white', 'hover:bg-white/5'];
                const subActive = ['text-white', 'bg-white/10'];
                const subInactive = ['text-slate-400', 'hover:text-white', 'hover:bg-white/5'];

                let activeSubmenuDropdown = null, bestMatchLink = null, bestMatchLength = 0;

                allLinks.forEach(link => {
                    const isInsideSubmenu = !!link.closest('div[x-show]');
                    if (isInsideSubmenu) { link.classList.remove(...subActive); link.classList.add(...subInactive); }
                    else { link.classList.remove(...mainActive); link.classList.add(...mainInactive); }

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
                    // Open the dropdown if Alpine hasn't already.
                    // Instead of managing Alpine state externally, we usually bind it or click the button.
                    const button = activeSubmenuDropdown.previousElementSibling;
                    if (button && activeSubmenuDropdown.style.display === 'none') {
                        // Alpine handles clicks, so we can dispatch click
                        // button.click(); 
                    }
                }
            });
        }

        if (!window.turboCacheBound) {
            window.turboCacheBound = true;
            document.addEventListener("turbo:before-cache", function() {
                document.querySelectorAll('.tomselected').forEach(el => { if (el.tomselect) el.tomselect.destroy(); });
                document.querySelectorAll('.flatpickr-input').forEach(el => { if (el._flatpickr) el._flatpickr.destroy(); });
                window.dispatchEvent(new CustomEvent('sidebar-close'));
                if (Swal.isVisible()) Swal.close();
            });
        }

        // Custom validation message for number inputs to prevent long overflow messages on mobile
        document.addEventListener('invalid', function (e) {
            if (e.target.tagName === 'INPUT' && e.target.type === 'number' && e.target.step === '1') {
                if (e.target.validity.stepMismatch || e.target.validity.badInput) {
                    e.target.setCustomValidity('Hanya angka bulat');
                }
            }
        }, true);
        
        document.addEventListener('input', function (e) {
            if (e.target.tagName === 'INPUT' && e.target.type === 'number' && e.target.step === '1') {
                e.target.setCustomValidity('');
            }
        });
    </script>
</body>
</html>