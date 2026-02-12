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
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900">
        <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-slate-50 overflow-hidden">
            @include('layouts.sidebar')

            <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
                @include('layouts.header')

                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
