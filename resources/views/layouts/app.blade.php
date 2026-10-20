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
    <meta name="turbo-prefetch" content="true">
    <script type="module" data-turbo-track="reload">
        import * as Turbo from 'https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.12/+esm';
        window.Turbo = Turbo;
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
    <link rel="stylesheet" href="{{ asset('vendor/flatpickr/flatpickr.min.css') }}">
    <script src="{{ asset('vendor/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('vendor/flatpickr/id.js') }}"></script>
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
        /* Sembunyikan progress bar atas bawaan Turbo sepenuhnya */
        .turbo-progress-bar {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            height: 0 !important;
            pointer-events: none !important;
        }

        .content-ready {
            opacity: 1;
            transform: none !important;
            transition: opacity 0.2s ease;
        }
        .content-loading {
            opacity: 0.65;
            filter: blur(0.3px);
            transform: none !important;
            transition: opacity 0.15s ease, filter 0.15s ease;
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
            background-color: rgba(15, 23, 42, 0.8) !important;
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
            background-color: rgba(15, 23, 42, 0.95) !important;
            box-shadow: 0 0 0 2px rgba(0, 98, 255, 0.2) !important;
        }

        .dark .ts-dropdown, .dark .ts-wrapper .ts-dropdown {
            position: absolute;
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
            background-color: #0f172a !important;
            color: #f1f5f9 !important;
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
            color: #f1f5f9 !important;
        }

        .dark .ts-dropdown .option:hover, .dark .ts-wrapper .ts-dropdown .option:hover {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: #38bdf8 !important;
        }

        .dark .ts-dropdown .active, .dark .ts-wrapper .ts-dropdown .active {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: #38bdf8 !important;
            font-weight: 700;
        }
        
        .dark .ts-wrapper .ts-control .item {
            font-weight: 600;
            color: #f8fafc !important;
        }

        .dark .ts-wrapper .ts-control input {
            color: #f8fafc !important;
        }

        /* Light Theme overrides for TomSelect */
        .ts-wrapper .ts-control {
            border: 1px solid #cbd5e1 !important;
            background-color: #ffffff !important;
            border-radius: 0.75rem !important;
            padding: 0.5rem 2.25rem 0.5rem 0.875rem !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            color: #0f172a !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 40px;
            display: flex;
            align-items: center;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 0.75rem center !important;
            background-size: 1rem 1rem !important;
        }

        .ts-wrapper.dropdown-active .ts-control {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%230062ff' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M5 15l7-7 7 7'/%3E%3C/svg%3E") !important;
            border-color: #0062ff !important;
            box-shadow: 0 0 0 3px rgba(0, 98, 255, 0.15) !important;
        }

        .dark .ts-wrapper .ts-control {
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            background-color: #0f172a !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E") !important;
        }

        .dark .ts-wrapper.dropdown-active .ts-control {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2338bdf8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M5 15l7-7 7 7'/%3E%3C/svg%3E") !important;
            border-color: #38bdf8 !important;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2) !important;
        }

        .ts-dropdown, .ts-wrapper .ts-dropdown {
            background-color: #ffffff !important;
            border-radius: 1rem !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05) !important;
            font-size: 0.75rem !important;
            padding: 0.375rem !important;
            z-index: 99999 !important;
            color: #0f172a !important;
            animation: dropdownFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        .ts-dropdown .option, .ts-wrapper .ts-dropdown .option {
            padding: 0.5rem 0.75rem !important;
            border-radius: 0.5rem !important;
        /* Custom Modern Filter Dropdown Styles (100% Native-Free, Zero-CDN) */
        .custom-select-wrapper {
            position: relative;
            width: 100%;
        }

        .custom-select-trigger {
            width: 100%;
            min-height: 40px;
            padding: 0.5rem 0.875rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            border-radius: 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            user-select: none;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
            color: #0f172a;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .custom-select-trigger:hover {
            border-color: #94a3b8;
            background-color: #f8fafc;
        }

        .custom-select-trigger.active {
            border-color: #0062ff !important;
            box-shadow: 0 0 0 3px rgba(0, 98, 255, 0.15) !important;
            background-color: #ffffff;
        }

        .dark .custom-select-trigger {
            border: 1px solid rgba(255, 255, 255, 0.1);
            background-color: #0f172a;
            color: #f8fafc;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.3);
        }

        .dark .custom-select-trigger:hover {
            border-color: rgba(255, 255, 255, 0.2);
            background-color: #1e293b;
        }

        .dark .custom-select-trigger.active {
            border-color: #38bdf8 !important;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2) !important;
            background-color: #0f172a;
        }

        .custom-select-menu {
            position: fixed;
            z-index: 99999;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            max-height: 320px;
            animation: customDropdownPop 0.15s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .dark .custom-select-menu {
            border: 1px solid rgba(255, 255, 255, 0.1);
            background-color: #0f172a;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
        }

        @keyframes customDropdownPop {
            from { opacity: 0; transform: scale(0.97) translateY(-4px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .custom-select-search {
            padding: 0.5rem;
            border-bottom: 1px solid #f1f5f9;
            background-color: #f8fafc;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .dark .custom-select-search {
            border-color: rgba(255, 255, 255, 0.08);
            background-color: #1e293b;
        }

        .custom-select-options {
            overflow-y: auto;
            padding: 0.375rem;
            flex: 1;
        }

        .custom-select-option {
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: #1e293b;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.15s ease;
            margin-bottom: 2px;
            user-select: none;
        }

        .custom-select-option:hover {
            background-color: #f1f5f9;
            color: #0062ff;
        }

        .dark .custom-select-option {
            color: #cbd5e1;
        }

        .dark .custom-select-option:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: #38bdf8;
        }

        .custom-select-option.selected {
            background-color: #eff6ff;
            color: #0062ff;
            font-weight: 700;
        }

        .dark .custom-select-option.selected {
            background-color: rgba(56, 189, 248, 0.15);
            color: #38bdf8;
            font-weight: 700;
        }

        /* Global Native Select & Option Colors in Light/Dark Mode with Modern Styling */
        select {
            background-color: #ffffff !important;
            color: #0f172a !important;
            border-color: #cbd5e1 !important;
            border-radius: 0.75rem !important;
            padding: 0.5rem 2.25rem 0.5rem 0.875rem !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            min-height: 40px !important;
            border-width: 1px !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") !important;
            background-position: right 0.75rem center !important;
            background-repeat: no-repeat !important;
            background-size: 1.15em 1.15em !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            cursor: pointer !important;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        select:focus {
            outline: none !important;
            border-color: #0062ff !important;
            box-shadow: 0 0 0 3px rgba(0, 98, 255, 0.15) !important;
        }

        .dark select {
            background-color: #0f172a !important;
            color: #f8fafc !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") !important;
        }

        .dark select:focus {
            border-color: #38bdf8 !important;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2) !important;
        }

        select option {
            background-color: #ffffff !important;
            color: #0f172a !important;
            padding: 8px 12px !important;
        }

        .dark select option {
            background-color: #0f172a !important;
            color: #f8fafc !important;
            padding: 8px 12px !important;
        }

        /* Flatpickr Calendar Base & Light Theme */
        .flatpickr-calendar {
            z-index: 99999 !important;
            font-family: 'Inter', sans-serif !important;
            border-radius: 1.5rem !important;
            box-shadow: 0 20px 40px -8px rgba(0, 0, 0, 0.12), 0 8px 16px -4px rgba(0, 0, 0, 0.08) !important;
            border: 1px solid #e2e8f0 !important;
            padding: 0.5rem !important;
            background: #ffffff !important;
            animation: fpSlideIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
            transition: background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease !important;
        }
        @keyframes fpSlideIn {
            from { opacity: 0; transform: scale(0.97) translateY(-6px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        /* Light mode day styling */
        .flatpickr-day {
            border-radius: 0.5rem !important;
            transition: background 0.15s ease, color 0.15s ease !important;
        }
        .flatpickr-day:hover {
            background: #f1f5f9 !important;
            border-color: transparent !important;
            color: #0f172a !important;
        }
        .flatpickr-day.today {
            border-color: #0062ff !important;
            color: #0062ff !important;
            font-weight: 700 !important;
        }
        .flatpickr-day.today:hover {
            background: #eff6ff !important;
            color: #0062ff !important;
        }
        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange {
            background: #0062ff !important;
            border-color: #0062ff !important;
            color: #fff !important;
            box-shadow: 0 2px 8px rgba(0, 98, 255, 0.35) !important;
        }
        .flatpickr-day.prevMonthDay, .flatpickr-day.nextMonthDay {
            color: #cbd5e1 !important;
        }
        span.flatpickr-weekday {
            color: #64748b !important;
            font-weight: 600 !important;
        }
        .flatpickr-months .flatpickr-prev-month svg,
        .flatpickr-months .flatpickr-next-month svg {
            fill: #64748b !important;
            transition: fill 0.15s ease !important;
        }
        .flatpickr-months .flatpickr-prev-month:hover svg,
        .flatpickr-months .flatpickr-next-month:hover svg {
            fill: #0f172a !important;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months {
            font-weight: 700 !important;
        }
        /* Dark theme via html.dark (class dark ada di <html>, flatpickr calendar adalah descendant <body>) */
        html.dark .flatpickr-calendar,
        .flatpickr-calendar.fp-dark {
            background: #0f172a !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255,255,255,0.04) !important;
            color: #f8fafc !important;
        }
        html.dark .flatpickr-calendar .flatpickr-day,
        .flatpickr-calendar.fp-dark .flatpickr-day {
            color: #cbd5e1 !important;
        }
        html.dark .flatpickr-calendar .flatpickr-day:hover,
        html.dark .flatpickr-calendar .flatpickr-day.prevMonthDay:hover,
        html.dark .flatpickr-calendar .flatpickr-day.nextMonthDay:hover,
        .flatpickr-calendar.fp-dark .flatpickr-day:hover,
        .flatpickr-calendar.fp-dark .flatpickr-day.prevMonthDay:hover,
        .flatpickr-calendar.fp-dark .flatpickr-day.nextMonthDay:hover {
            background: rgba(255,255,255,0.08) !important;
            border-color: transparent !important;
            color: #fff !important;
        }
        html.dark .flatpickr-calendar .flatpickr-day.today,
        .flatpickr-calendar.fp-dark .flatpickr-day.today {
            border-color: rgba(56,189,248,0.5) !important;
            color: #38bdf8 !important;
            font-weight: 700 !important;
        }
        html.dark .flatpickr-calendar .flatpickr-day.today:hover,
        .flatpickr-calendar.fp-dark .flatpickr-day.today:hover {
            background: rgba(56,189,248,0.1) !important;
            color: #38bdf8 !important;
        }
        html.dark .flatpickr-calendar .flatpickr-day.selected,
        html.dark .flatpickr-calendar .flatpickr-day.startRange,
        html.dark .flatpickr-calendar .flatpickr-day.endRange,
        .flatpickr-calendar.fp-dark .flatpickr-day.selected,
        .flatpickr-calendar.fp-dark .flatpickr-day.startRange,
        .flatpickr-calendar.fp-dark .flatpickr-day.endRange {
            background: #0062ff !important;
            border-color: #0062ff !important;
            color: #fff !important;
            box-shadow: 0 2px 10px rgba(0, 98, 255, 0.4) !important;
        }
        html.dark .flatpickr-calendar .flatpickr-day.prevMonthDay,
        html.dark .flatpickr-calendar .flatpickr-day.nextMonthDay,
        .flatpickr-calendar.fp-dark .flatpickr-day.prevMonthDay,
        .flatpickr-calendar.fp-dark .flatpickr-day.nextMonthDay {
            color: #475569 !important;
        }
        html.dark .flatpickr-calendar .flatpickr-months .flatpickr-month,
        .flatpickr-calendar.fp-dark .flatpickr-months .flatpickr-month {
            color: #f8fafc !important;
            fill: #f8fafc !important;
        }
        html.dark .flatpickr-calendar .flatpickr-current-month .flatpickr-monthDropdown-months,
        .flatpickr-calendar.fp-dark .flatpickr-current-month .flatpickr-monthDropdown-months {
            background: #0f172a !important;
            color: #f8fafc !important;
            font-weight: 700 !important;
        }
        html.dark .flatpickr-calendar .flatpickr-current-month .flatpickr-monthDropdown-months .flatpickr-monthDropdown-month,
        .flatpickr-calendar.fp-dark .flatpickr-current-month .flatpickr-monthDropdown-months .flatpickr-monthDropdown-month {
            background-color: #0f172a !important;
            color: #f8fafc !important;
        }
        html.dark .flatpickr-calendar .flatpickr-months .flatpickr-prev-month svg,
        html.dark .flatpickr-calendar .flatpickr-months .flatpickr-next-month svg,
        .flatpickr-calendar.fp-dark .flatpickr-months .flatpickr-prev-month svg,
        .flatpickr-calendar.fp-dark .flatpickr-months .flatpickr-next-month svg {
            fill: #94a3b8 !important;
            transition: fill 0.2s ease !important;
        }
        html.dark .flatpickr-calendar .flatpickr-months .flatpickr-prev-month:hover svg,
        html.dark .flatpickr-calendar .flatpickr-months .flatpickr-next-month:hover svg,
        .flatpickr-calendar.fp-dark .flatpickr-months .flatpickr-prev-month:hover svg,
        .flatpickr-calendar.fp-dark .flatpickr-months .flatpickr-next-month:hover svg {
            fill: #fff !important;
        }
        html.dark .flatpickr-calendar span.flatpickr-weekday,
        .flatpickr-calendar.fp-dark span.flatpickr-weekday {
            color: #64748b !important;
            font-weight: 600 !important;
        }
        html.dark .flatpickr-calendar .flatpickr-current-month input.cur-year,
        .flatpickr-calendar.fp-dark .flatpickr-current-month input.cur-year {
            color: #f8fafc !important;
        }
        html.dark .flatpickr-calendar .numInputWrapper span.arrowUp:after,
        .flatpickr-calendar.fp-dark .numInputWrapper span.arrowUp:after {
            border-bottom-color: #94a3b8 !important;
        }
        html.dark .flatpickr-calendar .numInputWrapper span.arrowDown:after,
        .flatpickr-calendar.fp-dark .numInputWrapper span.arrowDown:after {
            border-top-color: #94a3b8 !important;
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
    <!-- Flatpickr Dark Mode Override - MUST be last CSS to guarantee specificity win -->
    <style id="fp-dark-override">
        /* Nuclear option: multi-layer specificity untuk memastikan override flatpickr vendor CSS */
        html.dark body .flatpickr-calendar.open,
        html.dark body .flatpickr-calendar.inline {
            background-color: #0f172a !important;
            background: #0f172a !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            box-shadow: 0 25px 60px rgba(0,0,0,0.7) !important;
            color: #f8fafc !important;
        }
        html.dark body .flatpickr-calendar.arrowTop:after {
            border-bottom-color: #0f172a !important;
        }
        html.dark body .flatpickr-calendar.arrowBottom:after {
            border-top-color: #0f172a !important;
        }
        html.dark body .flatpickr-calendar .flatpickr-day { color: #cbd5e1 !important; }
        html.dark body .flatpickr-calendar .flatpickr-day:hover,
        html.dark body .flatpickr-calendar .flatpickr-day:focus {
            background: rgba(255,255,255,0.08) !important;
            border-color: transparent !important;
            color: #fff !important;
        }
        html.dark body .flatpickr-calendar .flatpickr-day.today {
            border-color: rgba(56,189,248,0.6) !important;
            color: #38bdf8 !important;
        }
        html.dark body .flatpickr-calendar .flatpickr-day.selected,
        html.dark body .flatpickr-calendar .flatpickr-day.startRange,
        html.dark body .flatpickr-calendar .flatpickr-day.endRange {
            background: #0062ff !important;
            border-color: #0062ff !important;
            color: #fff !important;
        }
        html.dark body .flatpickr-calendar .flatpickr-day.prevMonthDay,
        html.dark body .flatpickr-calendar .flatpickr-day.nextMonthDay { color: #334155 !important; }
        html.dark body .flatpickr-calendar .flatpickr-months .flatpickr-month { color: #f8fafc !important; fill: #f8fafc !important; }
        html.dark body .flatpickr-calendar .flatpickr-current-month .flatpickr-monthDropdown-months {
            background: #0f172a !important; color: #f8fafc !important;
        }
        html.dark body .flatpickr-calendar .flatpickr-current-month .flatpickr-monthDropdown-months .flatpickr-monthDropdown-month {
            background-color: #0f172a !important; color: #f8fafc !important;
        }
        html.dark body .flatpickr-calendar .flatpickr-months .flatpickr-prev-month svg,
        html.dark body .flatpickr-calendar .flatpickr-months .flatpickr-next-month svg { fill: #94a3b8 !important; }
        html.dark body .flatpickr-calendar .flatpickr-months .flatpickr-prev-month:hover svg,
        html.dark body .flatpickr-calendar .flatpickr-months .flatpickr-next-month:hover svg { fill: #fff !important; }
        html.dark body .flatpickr-calendar span.flatpickr-weekday { color: #475569 !important; }
        html.dark body .flatpickr-calendar .flatpickr-current-month input.cur-year { color: #f8fafc !important; }
        html.dark body .flatpickr-calendar .dayContainer + .dayContainer { box-shadow: -1px 0 0 rgba(255,255,255,0.08) !important; }
    </style>
</head>

<body class="h-full font-sans antialiased text-slate-800 dark:text-slate-200 overflow-hidden bg-white dark:bg-slate-900 relative selection:bg-brand-primary/30 selection:text-slate-900 dark:text-white transition-colors duration-300" x-data="{ sidebarOpen: false, desktopSidebarOpen: $persist(true) }" @sidebar-close.window="sidebarOpen = false" @sidebar-open.window="sidebarOpen = true">
    <!-- Global Centered Loading Screen / Spinner for Seamless Turbo Navigation -->
    <div id="turbo-loading-indicator"
         class="fixed inset-0 z-[999999] pointer-events-none transition-all duration-300 opacity-0 flex items-center justify-center bg-slate-950/40 dark:bg-black/60 backdrop-blur-[2px]">
        <div id="turbo-loading-box"
             class="relative flex flex-col items-center justify-center gap-3.5 px-8 py-7 rounded-3xl bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-white/10 shadow-2xl shadow-brand-primary/10 dark:shadow-black/70 backdrop-blur-xl transform transition-all duration-300 scale-95">
            <!-- Modern Dual Spinner Ring -->
            <div class="relative flex items-center justify-center w-12 h-12">
                <div class="w-12 h-12 rounded-full border-[3px] border-slate-200 dark:border-slate-800"></div>
                <div class="absolute w-12 h-12 rounded-full border-[3px] border-transparent border-t-brand-primary border-r-cyan-400 animate-spin"></div>
                <div class="absolute w-3 h-3 rounded-full bg-brand-primary/80 animate-ping"></div>
                <div class="w-2 h-2 rounded-full bg-brand-primary"></div>
            </div>
            <div class="flex flex-col items-center text-center">
                <span class="text-xs font-bold tracking-wider uppercase text-slate-800 dark:text-slate-100">Memuat Data...</span>
                <span class="text-[10px] font-medium text-slate-600 dark:text-slate-300 mt-0.5">Mohon tunggu sebentar</span>
            </div>
        </div>
    </div>

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
    <script>
        // Universal Custom Select Enhancer (100% Identik dengan Desain Pilihan User)
        window.initCustomSelects = window.initCustomSelects || (() => {
            const getContextIcon = (select) => {
                const name = (select.name || select.id || '').toLowerCase();
                if (name.includes('satker')) {
                    return `<svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>`;
                } else if (name.includes('bbm') || name.includes('jenis')) {
                    return `<svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>`;
                } else if (name.includes('kendaraan') || name.includes('nopol')) {
                    return `<svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>`;
                } else if (name.includes('status') || name.includes('aksi')) {
                    return `<svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>`;
                } else if (name.includes('tahun') || name.includes('bulan') || name.includes('tw') || name.includes('year')) {
                    return `<svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>`;
                }
                return `<svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>`;
            };

            const getSearchPlaceholder = (select) => {
                const name = (select.name || select.id || '').toLowerCase();
                if (name.includes('satker')) return 'Cari nama satker...';
                if (name.includes('kendaraan')) return 'Cari nopol / kendaraan...';
                if (name.includes('bbm')) return 'Cari jenis BBM...';
                if (name.includes('status')) return 'Cari status...';
                return 'Cari...';
            };

            const targets = document.querySelectorAll('select.tom-select:not([data-custom-select-init]), form[method="GET"] select:not([data-custom-select-init]):not([data-no-custom-select]), .filter-card select:not([data-custom-select-init])');
            
            targets.forEach(select => {
                if (select.dataset.customSelectInit) return;
                select.dataset.customSelectInit = 'true';
                select.style.display = 'none';

                const wrapper = document.createElement('div');
                wrapper.className = 'custom-select-wrapper ' + (select.className.replace('tom-select', '').trim());

                const trigger = document.createElement('button');
                trigger.type = 'button';
                trigger.className = 'w-full py-2.5 px-3 bg-slate-50 dark:bg-slate-800/70 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-medium text-left flex items-center justify-between transition-all hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none';

                const iconHtml = getContextIcon(select);
                const placeholderText = getSearchPlaceholder(select);

                const updateTrigger = () => {
                    const selectedOpt = select.options[select.selectedIndex] || select.options[0];
                    const labelText = selectedOpt ? selectedOpt.textContent.trim() : 'Pilih...';
                    const hasSelected = select.value !== '';

                    trigger.innerHTML = `
                        <div class="flex items-center gap-2 truncate pr-2">
                            ${iconHtml}
                            <span class="truncate font-semibold ${hasSelected ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400'}">${labelText}</span>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0 text-slate-400">
                            ${hasSelected ? `
                                <span class="custom-select-clear p-0.5 rounded-full hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-400 hover:text-rose-500 transition-colors" title="Reset">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </span>
                            ` : ''}
                            <svg class="custom-select-chevron w-4 h-4 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    `;

                    const clearBtn = trigger.querySelector('.custom-select-clear');
                    if (clearBtn) {
                        clearBtn.addEventListener('click', (e) => {
                            e.stopPropagation();
                            select.value = '';
                            updateTrigger();
                            select.dispatchEvent(new Event('change', { bubbles: true }));
                            if (select.getAttribute('onchange')) {
                                try { eval(select.getAttribute('onchange')); } catch(err) {}
                            }
                        });
                    }
                };
                updateTrigger();

                wrapper.appendChild(trigger);
                select.parentNode.insertBefore(wrapper, select.nextSibling);

                let menu = null;
                let backdrop = null;

                const closeMenu = () => {
                    if (menu && menu.parentNode) menu.remove();
                    if (backdrop && backdrop.parentNode) backdrop.remove();
                    trigger.classList.remove('ring-2', 'ring-blue-500', 'border-transparent', 'active');
                    const chev = trigger.querySelector('.custom-select-chevron');
                    if (chev) chev.style.transform = '';
                    menu = null;
                    backdrop = null;
                };

                const openMenu = () => {
                    document.querySelectorAll('.custom-select-menu').forEach(m => m.remove());
                    document.querySelectorAll('.custom-select-backdrop').forEach(b => b.remove());
                    document.querySelectorAll('.custom-select-trigger.active, button.active').forEach(t => {
                        t.classList.remove('ring-2', 'ring-blue-500', 'border-transparent', 'active');
                        const c = t.querySelector('.custom-select-chevron');
                        if (c) c.style.transform = '';
                    });

                    trigger.classList.add('ring-2', 'ring-blue-500', 'border-transparent', 'active');
                    const chev = trigger.querySelector('.custom-select-chevron');
                    if (chev) chev.style.transform = 'rotate(180deg)';

                    menu = document.createElement('div');
                    menu.className = 'custom-select-menu bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl z-50 overflow-hidden flex flex-col max-h-80';

                    const rect = trigger.getBoundingClientRect();
                    const spaceBelow = window.innerHeight - rect.bottom;
                    const menuHeight = Math.min(320, select.options.length * 42 + 65);

                    let left = rect.left;
                    let width = Math.max(rect.width, 240);
                    if (left + width > window.innerWidth - 12) {
                        left = Math.max(12, window.innerWidth - width - 12);
                    }
                    menu.style.position = 'fixed';
                    menu.style.width = width + 'px';
                    menu.style.left = left + 'px';
                    menu.style.zIndex = '99999';

                    if (spaceBelow < menuHeight && rect.top > menuHeight) {
                        menu.style.bottom = (window.innerHeight - rect.top + 6) + 'px';
                        menu.style.top = 'auto';
                    } else {
                        menu.style.top = (rect.bottom + 6) + 'px';
                        menu.style.bottom = 'auto';
                    }

                    let searchInput = null;
                    if (select.options.length > 5) {
                        const searchContainer = document.createElement('div');
                        searchContainer.className = 'p-2.5 border-b border-slate-200 dark:border-white/10 bg-slate-50/70 dark:bg-slate-800/40 sticky top-0 z-10';
                        searchContainer.innerHTML = `
                            <div class="relative">
                                <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input type="text" placeholder="${placeholderText}" class="w-full pl-8 pr-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-medium text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                        `;
                        menu.appendChild(searchContainer);
                        searchInput = searchContainer.querySelector('input');
                    }

                    const optionsContainer = document.createElement('div');
                    optionsContainer.className = 'overflow-y-auto divide-y divide-slate-100 dark:divide-white/5 custom-scrollbar p-1 flex-1';

                    const renderOptions = (filterText = '') => {
                        optionsContainer.innerHTML = '';
                        const query = filterText.toLowerCase().trim();
                        let count = 0;

                        Array.from(select.options).forEach(opt => {
                            const text = opt.textContent.trim();
                            if (query && !text.toLowerCase().includes(query)) return;
                            count++;

                            const isSelected = opt.value === select.value;
                            const item = document.createElement('button');
                            item.type = 'button';
                            item.className = 'w-full text-left px-3 py-2 rounded-lg text-xs flex items-center justify-between transition-colors ' + 
                                (isSelected 
                                    ? 'bg-blue-600/10 text-blue-600 dark:bg-blue-500/20 dark:text-white font-bold' 
                                    : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800');

                            item.innerHTML = `
                                <span class="flex items-center gap-2 truncate pr-2">
                                    ${isSelected ? '<span class="w-2 h-2 rounded-full bg-blue-500 shrink-0"></span>' : ''}
                                    <span class="truncate">${text}</span>
                                </span>
                                ${isSelected ? '<svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>' : ''}
                            `;

                            item.addEventListener('click', () => {
                                select.value = opt.value;
                                updateTrigger();
                                closeMenu();
                                select.dispatchEvent(new Event('change', { bubbles: true }));
                                if (select.getAttribute('onchange')) {
                                    try {
                                        if (typeof select.onchange === 'function') {
                                            select.onchange();
                                        } else {
                                            eval(select.getAttribute('onchange'));
                                        }
                                    } catch (e) {
                                        console.warn('onchange eval error:', e);
                                    }
                                }
                            });
                            optionsContainer.appendChild(item);
                        });

                        if (count === 0) {
                            optionsContainer.innerHTML = `<div class="px-3 py-4 text-center text-xs text-slate-400">Tidak ditemukan "${filterText}"</div>`;
                        }
                    };

                    renderOptions();
                    menu.appendChild(optionsContainer);

                    backdrop = document.createElement('div');
                    backdrop.className = 'custom-select-backdrop';
                    backdrop.style.position = 'fixed';
                    backdrop.style.inset = '0';
                    backdrop.style.zIndex = '99998';
                    backdrop.addEventListener('click', closeMenu);

                    document.body.appendChild(backdrop);
                    document.body.appendChild(menu);

                    if (searchInput) {
                        setTimeout(() => searchInput.focus(), 25);
                        searchInput.addEventListener('input', (e) => renderOptions(e.target.value));
                    }
                };

                trigger.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (menu) closeMenu(); else openMenu();
                });

                trigger.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        if (menu) closeMenu(); else openMenu();
                    } else if (e.key === 'Escape') {
                        closeMenu();
                    }
                });
            });
        });

        if (!window.customSelectBound) {
            window.customSelectBound = true;
            document.addEventListener('DOMContentLoaded', window.initCustomSelects);
            document.addEventListener('turbo:load', window.initCustomSelects);
            document.addEventListener('turbo:render', window.initCustomSelects);
            window.addEventListener('resize', () => {
                document.querySelectorAll('.custom-select-menu').forEach(m => m.remove());
                document.querySelectorAll('.custom-select-backdrop').forEach(b => b.remove());
            }, { passive: true });
            window.addEventListener('scroll', () => {
                document.querySelectorAll('.custom-select-menu').forEach(m => m.remove());
                document.querySelectorAll('.custom-select-backdrop').forEach(b => b.remove());
            }, { passive: true, capture: true });
        }
        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            setTimeout(window.initCustomSelects, 30);
        }

        // Global Flatpickr Initializer
        window.initFlatpickr = () => {
            if (typeof flatpickr === 'undefined') {
                setTimeout(window.initFlatpickr, 80);
                return;
            }
            try {
                const inputs = document.querySelectorAll('input[type="date"]:not(.flatpickr-input), .flatpickr:not(.flatpickr-input)');
                inputs.forEach(el => {
                    const isStart = el.name && (el.name.includes('start') || el.name.includes('dari'));
                    const isEnd = el.name && (el.name.includes('end') || el.name.includes('sampai'));
                    
                    const idLocale = (flatpickr.l10ns && flatpickr.l10ns.id) ? flatpickr.l10ns.id : 'default';

                    const config = {
                        locale: idLocale,
                        dateFormat: "Y-m-d",
                        altInput: true,
                        altFormat: "d F Y",
                        placeholder: el.getAttribute('placeholder') || "Pilih Tanggal",
                        disableMobile: true,
                        monthSelectorType: "dropdown",
                        animate: true,
                        static: false,
                        defaultDate: el.getAttribute('data-default-date') || el.value || null,
                        prevArrow: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>',
                        nextArrow: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>',
                        onReady: function(selectedDates, dateStr, instance) {
                            // Apply dark mode class directly to calendar element
                            const applyDarkMode = () => {
                                const isDark = document.documentElement.classList.contains('dark');
                                if (instance.calendarContainer) {
                                    instance.calendarContainer.classList.toggle('fp-dark', isDark);
                                }
                            };
                            applyDarkMode();
                            // Watch for dark mode changes via MutationObserver
                            if (!instance._darkObserver) {
                                instance._darkObserver = new MutationObserver(applyDarkMode);
                                instance._darkObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
                            }
                            if (instance.altInput) {
                                instance.altInput.setAttribute('placeholder', el.getAttribute('placeholder') || 'Pilih Tanggal');
                                instance.altInput.className = el.className;
                                if (el.getAttribute('style')) {
                                    instance.altInput.setAttribute('style', el.getAttribute('style'));
                                }
                                if (!instance.altInput.classList.contains('flatpickr-input')) {
                                    instance.altInput.classList.add('flatpickr-input');
                                }
                                instance.altInput.style.cursor = 'pointer';
                                instance.altInput.addEventListener('click', (e) => {
                                    e.stopPropagation();
                                    instance.open();
                                });
                                instance.altInput.addEventListener('focus', () => {
                                    instance.open();
                                });
                            }
                        },
                        onOpen: function(selectedDates, dateStr, instance) {
                            // Re-apply dark mode every time calendar opens
                            const isDark = document.documentElement.classList.contains('dark');
                            if (instance.calendarContainer) {
                                instance.calendarContainer.classList.toggle('fp-dark', isDark);
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
                    const fp = flatpickr(el, config);

                    // Buat icon kalender di sebelah input (jika ada) bisa diklik untuk membuka kalender
                    const parent = el.closest('.relative');
                    if (parent && fp) {
                        const iconWrapper = parent.querySelector('.absolute.inset-y-0.left-0');
                        if (iconWrapper) {
                            iconWrapper.style.cursor = 'pointer';
                            iconWrapper.classList.remove('pointer-events-none');
                            iconWrapper.addEventListener('click', (e) => {
                                e.stopPropagation();
                                fp.open();
                            });
                        }
                    }
                });
            } catch (e) {
                console.warn('Flatpickr init error:', e);
            }
        };

        if (!window.flatpickrBound) {
            window.flatpickrBound = true;
            document.addEventListener('DOMContentLoaded', window.initFlatpickr);
            document.addEventListener('turbo:load', window.initFlatpickr);
            document.addEventListener('turbo:render', window.initFlatpickr);
            document.addEventListener('alpine:initialized', window.initFlatpickr);
            window.addEventListener('load', window.initFlatpickr);

            // Fallback click listener jika input belum terinisialisasi saat pertama kali diklik
            document.addEventListener('click', (e) => {
                const target = e.target.closest('.flatpickr:not(.flatpickr-input), input[type="date"]:not(.flatpickr-input)');
                if (target) {
                    window.initFlatpickr();
                    if (target._flatpickr) {
                        target._flatpickr.open();
                    }
                }
            });
        }
        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            setTimeout(window.initFlatpickr, 30);
            setTimeout(window.initFlatpickr, 200);
        }
            
            // Clean up instances before Turbo caches to prevent duplicates and sluggishness on mobile
            document.addEventListener('turbo:before-cache', () => {
                // Destroy TomSelect
                document.querySelectorAll('.tomselected').forEach(el => {
                    if (el.tomselect) el.tomselect.destroy();
                });
                
                // Destroy Flatpickr (including MutationObserver for dark mode)
                document.querySelectorAll('.flatpickr-input').forEach(el => {
                    if (el._flatpickr) {
                        if (el._flatpickr._darkObserver) {
                            el._flatpickr._darkObserver.disconnect();
                            el._flatpickr._darkObserver = null;
                        }
                        el._flatpickr.destroy();
                    }
                });
            });
        
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
                    if (document.hidden) return;
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

        if (!window.turboFastNavBound) {
            window.turboFastNavBound = true;

            // Prefetch on mouse hover
            document.addEventListener('pointerenter', function (e) {
                const link = e.target.closest('a[href]');
                if (!link) return;
                const href = link.getAttribute('href');
                if (!href || href === '#' || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) return;
                try {
                    const url = new URL(href, window.location.origin);
                    if (url.origin === window.location.origin && url.pathname !== window.location.pathname) {
                        link.setAttribute('data-turbo-prefetch', 'true');
                    }
                } catch (err) {}
            }, { passive: true, capture: true });

            // Prefetch on touchstart (mobile)
            document.addEventListener('touchstart', function (e) {
                const link = e.target.closest('a[href]');
                if (!link) return;
                const href = link.getAttribute('href');
                if (!href || href === '#' || href.startsWith('javascript:')) return;
                try {
                    const url = new URL(href, window.location.origin);
                    if (url.origin === window.location.origin && url.pathname !== window.location.pathname) {
                        link.setAttribute('data-turbo-prefetch', 'true');
                    }
                } catch (err) {}
            }, { passive: true, capture: true });

            const showLoading = () => {
                const indicator = document.getElementById('turbo-loading-indicator');
                if (indicator) {
                    indicator.classList.remove('opacity-0', 'pointer-events-none');
                    indicator.classList.add('opacity-100');
                    const box = document.getElementById('turbo-loading-box');
                    if (box) {
                        box.classList.remove('scale-95');
                        box.classList.add('scale-100');
                    }
                }
                const main = document.getElementById('main-content');
                if (main) {
                    main.classList.remove('content-ready');
                    main.classList.add('content-loading');
                }
            };

            const hideLoading = () => {
                const indicator = document.getElementById('turbo-loading-indicator');
                if (indicator) {
                    indicator.classList.remove('opacity-100');
                    indicator.classList.add('opacity-0', 'pointer-events-none');
                    const box = document.getElementById('turbo-loading-box');
                    if (box) {
                        box.classList.remove('scale-100');
                        box.classList.add('scale-95');
                    }
                }
                const main = document.getElementById('main-content');
                if (main) {
                    main.classList.remove('content-loading');
                    main.classList.add('content-ready');
                }
            };

            // Global indicator hooks
            window.showPageLoading = showLoading;
            window.hidePageLoading = hideLoading;

            document.addEventListener('turbo:click', showLoading);
            document.addEventListener('turbo:before-visit', showLoading);
            document.addEventListener('turbo:request-start', showLoading);
            document.addEventListener('turbo:load', hideLoading);
            document.addEventListener('turbo:render', hideLoading);
            document.addEventListener('turbo:request-end', hideLoading);

            // Tampilkan loading saat form submit (misal filter tanggal / pencarian)
            document.addEventListener('submit', function (e) {
                // Abaikan jika form memiliki attribute data-no-loading
                if (e.target && !e.target.hasAttribute('data-no-loading')) {
                    showLoading();
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
                        <h4 id="toast-chat-sender-name" class="text-sm font-bold text-slate-900 dark:text-white truncate max-w-[190px]"></h4>
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
                $userSatId = auth()->id();
                $cacheKey = 'user_sat_' . $userSatId . '_' . now()->format('Ym');
                $hasFilledSatisfactionThisMonth = \Illuminate\Support\Facades\Cache::get($cacheKey);

                if ($hasFilledSatisfactionThisMonth === null) {
                    $hasFilledSatisfactionThisMonth = \App\Models\SatisfactionIndex::where('user_id', $userSatId)
                        ->whereYear('created_at', now()->year)
                        ->whereMonth('created_at', now()->month)
                        ->exists();

                    if ($hasFilledSatisfactionThisMonth) {
                        \Illuminate\Support\Facades\Cache::put($cacheKey, true, now()->endOfMonth()->addDay());
                    }
                }
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
                                <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
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