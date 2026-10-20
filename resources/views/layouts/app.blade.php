<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full" x-data="{ isDarkMode: $persist(true) }" :class="{ 'dark': isDarkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <script>
        if (localStorage.getItem('_x_isDarkMode') === 'true' || !('_x_isDarkMode' in localStorage)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
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
            darkMode: 'class',
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
        .content-ready {
            opacity: 1;
            transform: none !important;
            transition: opacity 0.25s ease;
        }
        .content-loading {
            opacity: 0;
            transform: none !important;
        }
        
        body {
            -webkit-tap-highlight-color: transparent;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(0, 0, 0, 0.2); border-radius: 10px; }
        .dark ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); }
        ::-webkit-scrollbar-thumb:hover { background: rgba(0, 0, 0, 0.3); }
        .dark ::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.2); }

        /* Submenu styling */
        .submenu-dot {
            width: 6px;
            height: 6px;
            border-radius: 9999px;
            margin-right: 0.75rem;
            transition: all 0.3s ease;
            background-color: #94a3b8; /* slate-400 */
        }
        .dark .submenu-dot {
            background-color: #475569; /* slate-600 */
        }
        .active-dot {
            background-color: #0062ff !important;
            box-shadow: 0 0 8px rgba(0, 98, 255, 0.4);
        }
        .dark .active-dot {
            background-color: #38bdf8 !important;
            box-shadow: 0 0 8px rgba(56, 189, 248, 0.5);
        }
        .submenu-line {
            border-left: 1px solid #e2e8f0; /* slate-200 */
        }
        .dark .submenu-line {
            border-left: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Dark Theme overrides for TomSelect */
        .dark .ts-wrapper .ts-control {
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

        .dark .ts-wrapper.focus .ts-control {
            border-color: #0062ff !important;
            background-color: rgba(15, 23, 42, 0.9) !important;
            box-shadow: 0 0 0 2px rgba(0, 98, 255, 0.2) !important;
        }

        .dark .ts-dropdown, .dark .ts-wrapper .ts-dropdown {
            position: absolute;
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
            background-color: #0f172a;
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

        .dark .ts-dropdown .option, .dark .ts-wrapper .ts-dropdown .option {
            padding: 0.625rem 0.75rem;
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .dark .ts-dropdown .active, .dark .ts-wrapper .ts-dropdown .active {
            background-color: rgba(255, 255, 255, 0.05);
            color: #38bdf8;
        }
        
        .dark .ts-wrapper .ts-control .item {
            font-weight: 600;
            color: #f8fafc;
        }

        .dark .ts-wrapper .ts-control input {
            color: #f8fafc !important;
        }

        /* Light Theme overrides for TomSelect */
        .ts-wrapper .ts-control {
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            border-radius: 0.75rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: #1e293b;
            transition: all 0.2s;
            min-height: 38px;
            display: flex;
            align-items: center;
        }

        .ts-wrapper.focus .ts-control {
            border-color: #0062ff !important;
            box-shadow: 0 0 0 2px rgba(0, 98, 255, 0.2) !important;
        }

        .ts-dropdown, .ts-wrapper .ts-dropdown {
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            font-size: 0.75rem;
            padding: 0.5rem;
            z-index: 99999;
            animation: dropdownFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        .ts-dropdown .option, .ts-wrapper .ts-dropdown .option {
            padding: 0.625rem 0.75rem;
            border-radius: 0.5rem;
            font-weight: 500;
        }
        
        .ts-dropdown .active, .ts-wrapper .ts-dropdown .active {
            background-color: #f1f5f9;
            color: #0f172a;
        }

        /* Dark Theme overrides for Flatpickr */
        .dark .flatpickr-calendar {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
            border-radius: 1.5rem !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            padding: 0.5rem !important;
            font-family: 'Inter', sans-serif !important;
            background: #0f172a !important;
            color: #f8fafc !important;
            z-index: 99999 !important;
        }
        .dark .flatpickr-day {
            border-radius: 0.5rem !important;
            color: #cbd5e1 !important;
        }
        .dark .flatpickr-day:hover, .dark .flatpickr-day.prevMonthDay:hover, .dark .flatpickr-day.nextMonthDay:hover, .dark .flatpickr-day:focus, .dark .flatpickr-day.prevMonthDay:focus, .dark .flatpickr-day.nextMonthDay:focus {
            background: rgba(255,255,255,0.1) !important;
            border-color: transparent !important;
            color: #fff !important;
        }
        .dark .flatpickr-day.today {
            border-color: rgba(255,255,255,0.2) !important;
            color: #38bdf8 !important;
        }
        .dark .flatpickr-day.selected, .dark .flatpickr-day.startRange, .dark .flatpickr-day.endRange {
            background: #0062ff !important;
            border-color: #0062ff !important;
            color: #fff !important;
        }
        .dark .flatpickr-months .flatpickr-month {
            color: #f8fafc !important;
            fill: #f8fafc !important;
        }
        .dark .flatpickr-current-month .flatpickr-monthDropdown-months {
            background: #0f172a !important;
            color: #f8fafc !important;
        }
        .dark .flatpickr-current-month .flatpickr-monthDropdown-months .flatpickr-monthDropdown-month {
            background-color: #0f172a !important;
        }
        .dark .flatpickr-months .flatpickr-prev-month,
        .dark .flatpickr-months .flatpickr-next-month {
            color: #94a3b8 !important;
            fill: #94a3b8 !important;
            transition: all 0.2s ease;
        }
        .dark .flatpickr-months .flatpickr-prev-month:hover,
        .dark .flatpickr-months .flatpickr-next-month:hover {
            color: #fff !important;
            fill: #fff !important;
        }
        .dark span.flatpickr-weekday {
            color: #94a3b8 !important;
        }
        
        @media (max-width: 768px) {
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
                scrollbar-width: thin;
            }
        }
        
        /* Pastikan pop-up SweetAlert selalu di atas elemen modal apapun */
        .swal2-container {
            z-index: 999999 !important;
        }
    </style>
    @stack('styles')
</head>

<body class="h-full font-sans antialiased text-slate-800 dark:text-slate-200 overflow-hidden bg-white dark:bg-slate-900 relative selection:bg-brand-primary/30 selection:text-slate-900 dark:text-white transition-colors duration-300" x-data="{ sidebarOpen: false, desktopSidebarOpen: $persist(true) }" @sidebar-close.window="sidebarOpen = false" @sidebar-open.window="sidebarOpen = true">
    <!-- Solid clean background instead of blur -->
    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none bg-white dark:bg-slate-900 transition-colors duration-300">
    </div>

    <div class="flex h-full min-h-full">
        @include('layouts.sidebar')

        <!-- Main Content Wrapper -->
        <div class="flex flex-1 flex-col overflow-hidden relative z-0">
            @include('layouts.header')

            <!-- Global Notification Pop-Up (SweetAlert2) -->
            @if(session('success') || session('error') || session('warning') || session('status') || $errors->any())
                <script>
                    (function() {
                        let triggered = false;
                        function triggerFlashPopup() {
                            if (triggered) return;
                            if (typeof Swal === 'undefined') {
                                setTimeout(triggerFlashPopup, 60);
                                return;
                            }
                            triggered = true;

                            const isDark = document.documentElement.classList.contains('dark');
                            const baseConfig = {
                                background: isDark ? '#0f172a' : '#ffffff',
                                color: isDark ? '#f8fafc' : '#1e293b',
                                confirmButtonColor: '#0062ff',
                                buttonsStyling: true,
                                customClass: {
                                    popup: 'border border-slate-200 dark:border-white/10 rounded-3xl shadow-2xl p-6 sm:p-8',
                                    title: 'text-xl sm:text-2xl font-black ' + (isDark ? 'text-white' : 'text-slate-800') + ' mb-2',
                                    htmlContainer: 'text-slate-600 dark:text-slate-300 font-medium text-sm sm:text-base mt-2',
                                    confirmButton: 'rounded-xl px-8 py-3 font-bold uppercase tracking-wider text-xs shadow-lg'
                                }
                            };

                            @if(session('success'))
                                Swal.fire({
                                    ...baseConfig,
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    html: `{!! addslashes(session('success')) !!}`,
                                    confirmButtonText: 'OK, Mengerti',
                                    timer: 3500,
                                    timerProgressBar: true
                                });
                            @elseif(session('error'))
                                Swal.fire({
                                    ...baseConfig,
                                    icon: 'error',
                                    title: 'Oops, Terjadi Kesalahan!',
                                    html: `{!! addslashes(session('error')) !!}`,
                                    confirmButtonColor: '#e11d48',
                                    confirmButtonText: 'Tutup'
                                });
                            @elseif(session('warning'))
                                Swal.fire({
                                    ...baseConfig,
                                    icon: 'warning',
                                    title: 'Perhatian!',
                                    html: `{!! addslashes(session('warning')) !!}`,
                                    confirmButtonColor: '#f59e0b',
                                    confirmButtonText: 'OK'
                                });
                            @elseif(session('status'))
                                Swal.fire({
                                    ...baseConfig,
                                    icon: 'info',
                                    title: 'Informasi',
                                    html: `{!! addslashes(session('status')) !!}`,
                                    confirmButtonText: 'OK',
                                    timer: 3500,
                                    timerProgressBar: true
                                });
                            @elseif($errors->any())
                                Swal.fire({
                                    ...baseConfig,
                                    icon: 'error',
                                    title: 'Periksa Inputan Anda',
                                    html: `<ul class="text-left text-sm space-y-1 list-disc list-inside mt-2">@foreach($errors->all() as $error)<li>{!! addslashes($error) !!}</li>@endforeach</ul>`,
                                    confirmButtonColor: '#e11d48',
                                    confirmButtonText: 'Tutup & Perbaiki'
                                });
                            @endif
                        }

                        if (document.readyState === 'loading') {
                            document.addEventListener('DOMContentLoaded', triggerFlashPopup, { once: true });
                        } else {
                            triggerFlashPopup();
                        }
                        document.addEventListener('turbo:load', triggerFlashPopup, { once: true });
                    })();
                </script>
            @endif

            <!-- Main Content Area -->
            <main class="flex-1 min-w-0 w-full overflow-y-auto overflow-x-hidden custom-scrollbar" id="main-content">
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

            const isDark = document.documentElement.classList.contains('dark');
            Swal.fire({
                title, text, icon,
                background: isDark ? '#0f172a' : '#ffffff',
                color: isDark ? '#f8fafc' : '#1e293b',
                confirmButtonColor: '#0062ff',
                confirmButtonText: 'Tutup',
                customClass: {
                    popup: 'border border-slate-300 dark:border-white/10 rounded-[2rem] shadow-2xl',
                    title: 'text-2xl font-bold ' + (isDark ? 'text-white' : 'text-slate-800'),
                    confirmButton: 'rounded-xl px-10 py-3 font-bold uppercase tracking-wider text-sm shadow-[0_0_15px_rgba(0,98,255,0.4)]'
                }
            });
        });

        window.confirmDialog = ((options, callback) => {
            const type = options.type || 'question';
            let confirmColor = '#0062ff';
            let swalIcon = type;
            if (type === 'danger' || type === 'error') {
                confirmColor = '#e11d48';
                swalIcon = (type === 'danger') ? 'warning' : 'error';
            } else if (type === 'warning') {
                confirmColor = '#f59e0b';
                swalIcon = 'warning';
            } else if (type === 'success') {
                confirmColor = '#10b981';
                swalIcon = 'success';
            }

            const isDark = document.documentElement.classList.contains('dark');
            return Swal.fire({
                title: options.title || 'Konfirmasi',
                text: options.message,
                icon: swalIcon,
                background: isDark ? '#0f172a' : '#ffffff',
                color: isDark ? '#f8fafc' : '#1e293b',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: isDark ? '#334155' : '#94a3b8',
                confirmButtonText: options.confirmText || 'Ya, Lanjutkan!',
                cancelButtonText: options.cancelText || 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'border border-slate-200 dark:border-white/10 rounded-3xl shadow-2xl p-6 sm:p-8',
                    title: 'text-xl sm:text-2xl font-black ' + (isDark ? 'text-white' : 'text-slate-800') + ' mb-2',
                    htmlContainer: 'text-slate-600 dark:text-slate-300 font-medium mb-6 text-sm',
                    confirmButton: 'rounded-xl px-6 py-2.5 font-black uppercase tracking-wider text-xs shadow-lg ml-2',
                    cancelButton: 'rounded-xl px-6 py-2.5 font-bold uppercase tracking-wider text-xs text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-white/10 hover:bg-slate-100 dark:hover:bg-white/5'
                },
                buttonsStyling: true
            }).then((result) => {
                if (result.isConfirmed && typeof callback === 'function') {
                    callback();
                }
            });
        });

        if (!window.chatPollingActive) {
            window.chatPollingActive = true;
            let lastUnreadCount = 0, isFirstCheck = true, userInteracted = false;
            let lastNotifiedChatId = null;
            window.activeIncomingChat = null;

            const notificationSound = new Audio('https://assets.mixkit.co/sfx/preview/mixkit-software-interface-start-2574.mp3');
            document.addEventListener('click', () => { userInteracted = true; notificationSound.load(); }, { once: true });

            window.hideIncomingChatToast = function() {
                const toastEl = document.getElementById('incoming-chat-toast');
                if (!toastEl) return;
                toastEl.classList.remove('translate-y-0', 'opacity-100');
                toastEl.classList.add('translate-y-[-24px]', 'opacity-0');
                setTimeout(() => {
                    toastEl.classList.add('hidden');
                }, 300);
            };

            window.skipIncomingChat = function() {
                if (window.activeIncomingChat && window.activeIncomingChat.id) {
                    try {
                        const skipped = JSON.parse(sessionStorage.getItem('skipped_chat_ids') || '[]');
                        if (!skipped.includes(window.activeIncomingChat.id)) {
                            skipped.push(window.activeIncomingChat.id);
                            sessionStorage.setItem('skipped_chat_ids', JSON.stringify(skipped));
                        }
                    } catch (e) {}
                }
                window.hideIncomingChatToast();
            };

            window.replyIncomingChat = function() {
                if (!window.activeIncomingChat) return;
                const url = window.activeIncomingChat.chat_url + '?focus=1';
                
                // Tambahkan ID pesan ini ke skipped agar toast tidak muncul kembali saat halaman chat dibuka
                try {
                    const skipped = JSON.parse(sessionStorage.getItem('skipped_chat_ids') || '[]');
                    if (!skipped.includes(window.activeIncomingChat.id)) {
                        skipped.push(window.activeIncomingChat.id);
                        sessionStorage.setItem('skipped_chat_ids', JSON.stringify(skipped));
                    }
                } catch (e) {}

                window.hideIncomingChatToast();
                if (typeof Turbo !== 'undefined') {
                    Turbo.visit(url);
                } else {
                    window.location.href = url;
                }
            };

            function showIncomingChatToast(msg) {
                const toastEl = document.getElementById('incoming-chat-toast');
                const avatarEl = document.getElementById('toast-chat-avatar-wrapper');
                const nameEl = document.getElementById('toast-chat-sender-name');
                const roleEl = document.getElementById('toast-chat-sender-role');
                const snippetEl = document.getElementById('toast-chat-snippet');
                const timeEl = document.getElementById('toast-chat-time');

                if (!toastEl || !avatarEl || !nameEl || !roleEl || !snippetEl || !timeEl) return;

                window.activeIncomingChat = msg;

                if (msg.has_photo && msg.avatar_url) {
                    avatarEl.innerHTML = `<img src="${msg.avatar_url}" alt="${msg.sender_name}" class="w-11 h-11 rounded-full object-cover border-2 border-indigo-500/50 shadow-md shadow-indigo-500/20">`;
                } else {
                    avatarEl.innerHTML = `<div class="w-11 h-11 rounded-full bg-gradient-to-tr from-indigo-500 via-indigo-600 to-purple-600 flex items-center justify-center text-white font-extrabold text-base shadow-md shadow-indigo-500/30">${msg.sender_initial}</div>`;
                }

                nameEl.textContent = msg.sender_name;
                roleEl.textContent = msg.sender_role + (msg.sender_satker ? ' • ' + msg.sender_satker : '');
                snippetEl.textContent = msg.text_snippet;
                timeEl.textContent = msg.created_at_time || msg.created_at_human;

                toastEl.classList.remove('hidden');
                // Trigger smooth enter animation
                requestAnimationFrame(() => {
                    toastEl.classList.remove('translate-y-[-24px]', 'opacity-0');
                    toastEl.classList.add('translate-y-0', 'opacity-100');
                });

                if (userInteracted && lastNotifiedChatId !== msg.id) {
                    notificationSound.currentTime = 0;
                    notificationSound.play().catch(e => console.log('Audio play failed:', e));
                }
                lastNotifiedChatId = msg.id;
            }

            function checkIncomingChat() {
                @auth
                    // Cek jika sedang berada di room chat aktif dengan pengirim tertentu
                    const currentPath = window.location.pathname;

                    // 1. Cek unread badge count
                    fetch("{{ route('chat.unread.count') }}")
                        .then(r => r.json())
                        .then(data => {
                            const sidebarBadge = document.getElementById('sidebar-chat-badge');
                            if (sidebarBadge) {
                                const count = data.count;
                                if (count > 0) {
                                    sidebarBadge.textContent = count > 99 ? '99+' : count;
                                    sidebarBadge.classList.remove('hidden');
                                    sidebarBadge.classList.add('flex');
                                } else {
                                    sidebarBadge.classList.add('hidden');
                                    sidebarBadge.classList.remove('flex');
                                }
                            }
                        })
                        .catch(err => console.error('Error count:', err));

                    // 2. Cek unread latest message untuk toast popup
                    fetch("{{ route('chat.latest-unread') }}")
                        .then(r => r.json())
                        .then(data => {
                            if (!data || !data.has_unread || !data.message) {
                                window.hideIncomingChatToast();
                                return;
                            }

                            const msg = data.message;

                            // Jika user sedang aktif chatting dengan sender ini, jangan tampilkan toast pop up
                            if (currentPath === `/chat/${msg.sender_id}` || currentPath.startsWith(`/chat/${msg.sender_id}/`)) {
                                window.hideIncomingChatToast();
                                return;
                            }

                            // Cek apakah pesan ini sudah di-skip oleh user
                            try {
                                const skipped = JSON.parse(sessionStorage.getItem('skipped_chat_ids') || '[]');
                                if (skipped.includes(msg.id)) {
                                    return;
                                }
                            } catch (e) {}

                            showIncomingChatToast(msg);
                        })
                        .catch(err => console.error('Error incoming chat:', err));
                @endauth
            }

            // Polling setiap 3.5 detik untuk respon instan
            setInterval(checkIncomingChat, 3500);
            checkIncomingChat();
        }

        if (!window.confirmHandlerBound) {
            window.confirmHandlerBound = true;
            document.addEventListener('click', function (e) {
                const target = e.target.closest('[data-confirm]');
                if (target) {
                    e.preventDefault();
                    e.stopPropagation();
                    window.confirmDialog({
                        message: target.getAttribute('data-confirm'),
                        type: target.getAttribute('data-confirm-type') || 'warning',
                        confirmText: target.getAttribute('data-confirm-text') || 'Ya, Lanjutkan!',
                        title: target.getAttribute('data-confirm-title') || 'Konfirmasi'
                    }, () => {
                        const form = target.closest('form');
                        if (form) {
                            form.dataset.confirmed = 'true';
                            form.submit();
                        }
                        else if (target.tagName === 'A') typeof Turbo !== 'undefined' ? Turbo.visit(target.href) : window.location.href = target.href;
                    });
                }
            }, true);

            // Cegah bypass submit di mobile browser (misal Safari iOS)
            document.addEventListener('submit', function (e) {
                if (e.target.dataset.confirmed === 'true') {
                    delete e.target.dataset.confirmed;
                    return;
                }
                const submitter = e.submitter || document.activeElement;
                const target = submitter ? submitter.closest('[data-confirm]') : e.target.querySelector('[data-confirm]');
                if (target) {
                    e.preventDefault();
                    e.stopPropagation();
                    window.confirmDialog({
                        message: target.getAttribute('data-confirm'),
                        type: target.getAttribute('data-confirm-type') || 'warning',
                        confirmText: target.getAttribute('data-confirm-text') || 'Ya, Lanjutkan!',
                        title: target.getAttribute('data-confirm-title') || 'Konfirmasi'
                    }, () => {
                        e.target.dataset.confirmed = 'true';
                        e.target.submit();
                    });
                }
            }, true);
        }

        if (!window.sidebarLogicBound) {
            window.sidebarLogicBound = true;
            document.addEventListener('turbo:load', function () {
                // Animate main content in
                const main = document.getElementById('main-content');
                if (main) {
                    main.classList.remove('content-ready');
                    main.classList.add('content-loading');
                    // Force reflow
                    main.offsetHeight;
                    main.classList.remove('content-loading');
                    main.classList.add('content-ready');
                }

                if (window.innerWidth < 1024) window.dispatchEvent(new CustomEvent('sidebar-close'));
                window.dispatchEvent(new CustomEvent('close-reports'));

                const currentPath = window.location.pathname;
                const sidebar = document.getElementById('sidebar-nav') || document;
                const allLinks = sidebar.querySelectorAll('nav a[href]');
                
                const mainActive = ['bg-brand-primary', 'text-white', 'shadow-lg', 'shadow-brand-primary/20'];
                const mainInactive = ['text-white', 'dark:text-slate-400', 'hover:text-white', 'dark:hover:text-white dark:text-white', 'hover:bg-slate-200/50', 'dark:hover:bg-white/5', 'transition-colors'];
                const subActive = ['text-brand-primary', 'dark:text-white', 'bg-brand-primary/10', 'dark:bg-white/10', 'font-semibold'];
                const subInactive = ['text-slate-600', 'dark:text-slate-400', 'hover:text-white', 'dark:hover:text-white dark:text-white', 'hover:bg-slate-100', 'dark:hover:bg-white/5', 'transition-colors'];

                let activeSubmenuDropdown = null, bestMatchLink = null, bestMatchLength = 0;

                allLinks.forEach(link => {
                    const isInsideSubmenu = !!link.closest('.submenu-line');
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

    <!-- Floating Pop Up Notification: Incoming Chat Message -->
    <div id="incoming-chat-toast-container" class="fixed top-5 right-5 z-[99999] max-w-sm sm:max-w-md w-[calc(100%-2.5rem)] pointer-events-none">
        <div id="incoming-chat-toast" class="hidden pointer-events-auto transform translate-y-[-24px] opacity-0 transition-all duration-300 ease-out bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border border-indigo-500/30 rounded-2xl shadow-2xl shadow-indigo-500/20 p-4 ring-1 ring-black/5 dark:ring-white/10">
            <!-- Toast Top Header -->
            <div class="flex items-center justify-between pb-2 mb-2.5 border-b border-slate-100 dark:border-white/10">
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-indigo-600"></span>
                    </span>
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Pesan Masuk Baru</span>
                </div>
                <div class="flex items-center gap-2">
                    <span id="toast-chat-time" class="text-[11px] font-medium text-slate-600 dark:text-slate-400">Baru saja</span>
                    <button type="button" onclick="window.skipIncomingChat()" class="text-slate-600 dark:text-slate-400 hover:text-white dark:hover:text-slate-200 p-1 rounded-lg transition-colors" title="Lewati / Tutup">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Toast Body: Sender & Message Snippet -->
            <div class="flex items-start gap-3">
                <div id="toast-chat-avatar-wrapper" class="shrink-0 relative">
                    <!-- Avatar injected via JS -->
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <h4 id="toast-chat-sender-name" class="text-sm font-bold text-white truncate max-w-[190px]"></h4>
                        <span id="toast-chat-sender-role" class="text-[10px] font-semibold text-indigo-600 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/70 border border-indigo-200/50 dark:border-indigo-800/50 px-2 py-0.5 rounded-full"></span>
                    </div>
                    <div class="mt-1.5 p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/70 border border-slate-200/60 dark:border-white/5">
                        <p id="toast-chat-snippet" class="text-xs sm:text-[13px] text-slate-800 dark:text-slate-100 leading-relaxed break-words line-clamp-2 font-normal"></p>
                    </div>
                </div>
            </div>

            <!-- Toast Actions: Skip & Balas -->
            <div class="mt-3.5 pt-2.5 flex items-center justify-end gap-2 border-t border-slate-100 dark:border-white/10">
                <button type="button" id="toast-chat-btn-skip" onclick="window.skipIncomingChat()" class="px-3.5 py-1.5 text-xs font-semibold rounded-xl text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 active:scale-95 transition-all flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-600 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    </svg>
                    Skip
                </button>
                <button type="button" id="toast-chat-btn-reply" onclick="window.replyIncomingChat()" class="px-4 py-1.5 text-xs font-bold rounded-xl text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 shadow-md shadow-indigo-500/25 active:scale-95 transition-all flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                    </svg>
                    Balas
                </button>
            </div>
        </div>
    </div>

    @auth
        @if(auth()->user()->role === 'admin_satker')
            @php
                $hasFilledSatisfactionThisMonth = \App\Models\SatisfactionIndex::where('user_id', auth()->id())
                    ->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month)
                    ->exists();
            @endphp

            @if(!$hasFilledSatisfactionThisMonth)
                <!-- Mandatory Monthly Satisfaction Survey Modal for Admin Satker -->
                <div id="mandatory-satisfaction-modal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md select-none overflow-y-auto" x-data="{ selectedRating: null, note: '', isSubmitting: false }" @keydown.escape.window.prevent="">
                    <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-3xl shadow-2xl p-6 sm:p-8 overflow-hidden my-auto pointer-events-auto">
                        <!-- Ambient Glow -->
                        <div class="absolute -top-16 -right-16 w-48 h-48 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="relative z-10">
                            <!-- Badge & Period -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 uppercase tracking-wider">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                                    Survei Wajib Bulanan
                                </span>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                                    Periode {{ now()->translatedFormat('F Y') }}
                                </span>
                            </div>

                            <!-- Title & Description -->
                            <div class="text-left mb-6">
                                <h3 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                                    Indeks Kepuasan Layanan BBM
                                </h3>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 mt-2 leading-relaxed">
                                    Yth. <strong>{{ auth()->user()->name }}</strong> @if(auth()->user()->satker) ({{ auth()->user()->satker->nama_satker }}) @endif, mohon berikan penilaian atas kualitas pelayanan dan penyaluran BBM Biro Logistik Polda NTB untuk bulan <strong>{{ now()->translatedFormat('F Y') }}</strong>.
                                </p>
                                <p class="text-[11px] text-amber-600 dark:text-amber-400 font-semibold mt-1.5 flex items-center gap-1">
                                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span>Wajib diisi setiap awal/pergantian bulan baru sebelum melanjutkan aktivitas di aplikasi.</span>
                                </p>
                            </div>

                            <!-- Form -->
                            <form action="{{ route('satisfaction.store') }}" method="POST" class="space-y-5" @submit="isSubmitting = true">
                                @csrf
                                
                                <!-- 3 Rating Options -->
                                <div class="grid grid-cols-3 gap-3">
                                    <!-- Sangat Puas -->
                                    <label class="cursor-pointer group relative">
                                        <input type="radio" name="rating" value="3" x-model="selectedRating" class="peer sr-only" required>
                                        <div class="flex flex-col items-center justify-center p-3 sm:p-4 rounded-2xl border-2 border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-800/40 hover:border-emerald-500 hover:bg-emerald-500/5 peer-checked:border-emerald-500 peer-checked:bg-emerald-500/10 peer-checked:shadow-lg peer-checked:shadow-emerald-500/15 transition-all text-center">
                                            <span class="text-3xl sm:text-4xl transition-transform group-hover:scale-110 peer-checked:scale-125 duration-200">🤩</span>
                                            <span class="text-[11px] sm:text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mt-2 group-hover:text-emerald-600 peer-checked:text-emerald-600 dark:peer-checked:text-emerald-400">
                                                Sangat Puas
                                            </span>
                                        </div>
                                    </label>

                                    <!-- Puas -->
                                    <label class="cursor-pointer group relative">
                                        <input type="radio" name="rating" value="2" x-model="selectedRating" class="peer sr-only" required>
                                        <div class="flex flex-col items-center justify-center p-3 sm:p-4 rounded-2xl border-2 border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-800/40 hover:border-amber-500 hover:bg-amber-500/5 peer-checked:border-amber-500 peer-checked:bg-amber-500/10 peer-checked:shadow-lg peer-checked:shadow-amber-500/15 transition-all text-center">
                                            <span class="text-3xl sm:text-4xl transition-transform group-hover:scale-110 peer-checked:scale-125 duration-200">🙂</span>
                                            <span class="text-[11px] sm:text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mt-2 group-hover:text-amber-600 peer-checked:text-amber-600 dark:peer-checked:text-amber-400">
                                                Puas
                                            </span>
                                        </div>
                                    </label>

                                    <!-- Tidak Puas -->
                                    <label class="cursor-pointer group relative">
                                        <input type="radio" name="rating" value="1" x-model="selectedRating" class="peer sr-only" required>
                                        <div class="flex flex-col items-center justify-center p-3 sm:p-4 rounded-2xl border-2 border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-800/40 hover:border-rose-500 hover:bg-rose-500/5 peer-checked:border-rose-500 peer-checked:bg-rose-500/10 peer-checked:shadow-lg peer-checked:shadow-rose-500/15 transition-all text-center">
                                            <span class="text-3xl sm:text-4xl transition-transform group-hover:scale-110 peer-checked:scale-125 duration-200">😡</span>
                                            <span class="text-[11px] sm:text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mt-2 group-hover:text-rose-600 peer-checked:text-rose-600 dark:peer-checked:text-rose-400">
                                                Tidak Puas
                                            </span>
                                        </div>
                                    </label>
                                </div>

                                <!-- Catatan / Saran -->
                                <div>
                                    <label for="satisfaction-note" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                        Catatan / Saran Layanan (Opsional)
                                    </label>
                                    <textarea 
                                        id="satisfaction-note" 
                                        name="note" 
                                        rows="2" 
                                        x-model="note"
                                        maxlength="255"
                                        placeholder="Tuliskan masukan atau saran Anda terkait pelayanan BBM..."
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-800/60 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 text-xs focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all resize-none"
                                    ></textarea>
                                </div>

                                <!-- Submit Button (Disabled jika belum pilih rating) -->
                                <button 
                                    type="submit" 
                                    :disabled="!selectedRating || isSubmitting"
                                    :class="selectedRating && !isSubmitting ? 'bg-gradient-to-r from-amber-500 via-amber-600 to-amber-700 hover:from-amber-600 hover:to-amber-800 text-white shadow-lg shadow-amber-500/30 cursor-pointer hover:scale-[1.01] active:scale-[0.99]' : 'bg-slate-200 dark:bg-slate-800 text-slate-400 cursor-not-allowed'"
                                    class="w-full py-3.5 px-4 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 transition-all duration-200"
                                >
                                    <template x-if="!isSubmitting">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>Kirim Penilaian Kepuasan</span>
                                        </div>
                                    </template>
                                    <template x-if="isSubmitting">
                                        <div class="flex items-center gap-2">
                                            <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                            <span>Menyimpan Penilaian...</span>
                                        </div>
                                    </template>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    @endauth

    @stack('scripts')
</body>
</html>