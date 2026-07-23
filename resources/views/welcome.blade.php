<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SPBP Rolog Polda NTB') }}</title>
    <link rel="icon" href="{{ asset('rolog.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        inter: ['Inter', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    <style>
        /* body { font-family: 'Inter', sans-serif; } - Moved to Tailwind config */
        .bg-grid-pattern {
            background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 40px 40px;
        }
    </style>
</head>

<body class="font-inter antialiased text-white bg-gradient-to-br from-red-900 via-red-950 to-slate-950 min-h-screen">
    <div class="relative min-h-screen flex flex-col overflow-hidden">
        <!-- Background Decoration -->
        <div class="absolute inset-0 z-0 bg-grid-pattern opacity-30 pointer-events-none"></div>
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-red-600 opacity-20 blur-3xl z-0">
        </div>
        <div
            class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 rounded-full bg-orange-600 opacity-20 blur-3xl z-0">
        </div>

        <!-- Navbar -->
        <nav class="relative z-10 w-full max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="{{ asset('rolog.png') }}" alt="Logo Biro Logistik"
                    class="w-12 h-12 object-contain drop-shadow-md">
                <span class="font-bold text-2xl tracking-tight text-white drop-shadow-sm">BIRO LOGISTIK</span>
            </div>
            <div class="hidden md:flex gap-8">
                @auth
                    <a href="{{ url('/dashboard') }}" class="font-medium text-red-100 hover:text-white transition">Panel
                        Utama</a>
                @else
                    <a href="{{ route('login') }}" class="font-medium text-red-100 hover:text-white transition">Log in</a>
                @endauth
            </div>
            <!-- Mobile Menu Button (Placeholder) -->
            <div class="md:hidden">
                <button class="text-red-100 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="relative z-10 flex-grow flex items-center justify-center px-6 py-12">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center px-2 sm:px-6 lg:px-8">
                <div class="space-y-4 sm:space-y-8">
                    <div class="space-y-4">
                        <div
                            class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-red-900/50 text-red-200 text-sm font-semibold border border-red-800">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                            </span>
                            Sistem Manajemen Bahan Bakar Digital
                        </div>
                        <h1
                            class="text-5xl md:text-6xl font-extrabold tracking-tight text-white leading-tight drop-shadow-lg">
                            Kelola Logistik <br>
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-orange-400">Lebih
                                Efisien</span>
                        </h1>
                        <p class="text-lg text-red-100/90 max-w-lg leading-relaxed">
                            Platform terintegrasi untuk manajemen distribusi bahan bakar kendaraan dinas. Pemantauan
                            akurat, monitoring realtime, dan transparansi penuh.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="px-8 py-4 bg-gradient-to-r from-red-600 to-red-700 text-white font-bold rounded-xl shadow-lg shadow-red-900/40 hover:from-red-500 hover:to-red-600 hover:shadow-red-900/60 transition transform hover:-translate-y-1 text-center border border-red-500/30">
                                Masuk ke Sistem
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="px-8 py-4 bg-gradient-to-r from-red-600 to-red-700 text-white font-bold rounded-xl shadow-lg shadow-red-900/40 hover:from-red-500 hover:to-red-600 hover:shadow-red-900/60 transition transform hover:-translate-y-1 text-center border border-red-500/30">
                                Login Sekarang
                            </a>
                        @endauth
                    </div>

                    <div class="pt-8 grid grid-cols-3 gap-6 border-t border-white/10">
                        <div>
                            <p class="text-3xl font-bold text-white">100%</p>
                            <p class="text-sm text-red-200 font-medium">Digital</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-white">QR</p>
                            <p class="text-sm text-red-200 font-medium">Code System</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-white">24/7</p>
                            <p class="text-sm text-red-200 font-medium">Monitoring</p>
                        </div>
                    </div>
                </div>

                <!-- Right Illustration / Image -->
                <div class="relative hidden md:block">
                    <div
                        class="absolute inset-0 bg-gradient-to-tr from-red-500 to-orange-500 rounded-2xl transform rotate-3 opacity-20 blur-lg">
                    </div>
                    <div
                        class="relative bg-slate-900 border border-white/5/5 border border-white/10 rounded-2xl shadow-2xl overflow-hidden p-2">
                        <div
                            class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl overflow-hidden aspect-[4/3] flex items-center justify-center relative">
                            <!-- Mockup UI -->
                            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
                            <div
                                class="relative z-10 w-3/4 p-4 bg-slate-800 rounded-lg shadow-xl border border-slate-700 space-y-3">
                                <div class="flex items-center gap-3 mb-2">
                                    <div
                                        class="w-8 h-8 bg-red-900/50 rounded-full flex items-center justify-center text-red-500 border border-red-500/20">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="h-2 w-20 bg-slate-700 rounded"></div>
                                        <div class="h-1.5 w-12 bg-slate-700/50 rounded mt-1"></div>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="h-2 w-full bg-slate-700 rounded"></div>
                                    <div class="h-2 w-5/6 bg-slate-700 rounded"></div>
                                    <div class="h-2 w-4/6 bg-slate-700 rounded"></div>
                                </div>
                                <div class="mt-4 flex justify-between items-center">
                                    <div class="h-8 w-24 bg-red-600 rounded"></div>
                                    <div class="h-8 w-8 bg-slate-700 rounded-full"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="relative z-10 w-full max-w-7xl mx-auto px-6 py-8 text-center text-red-200/40 text-sm">
            &copy; {{ date('Y') }} BIRO LOGISTIK. All rights reserved.
        </footer>
    </div>
</body>

</html>