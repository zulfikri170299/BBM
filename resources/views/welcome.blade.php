<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'SIMAK BBM') }}</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        },
                        colors: {
                            primary: '#2563EB',
                            secondary: '#1E293B',
                        }
                    }
                }
            }
        </script>
        <style>
            body { font-family: 'Inter', sans-serif; }
            .bg-grid-pattern {
                background-image: radial-gradient(#E2E8F0 1px, transparent 1px);
                background-size: 40px 40px;
            }
        </style>
    </head>
    <body class="antialiased text-slate-800 bg-white">
        <div class="relative min-h-screen flex flex-col">
            <!-- Background Decoration -->
            <div class="absolute inset-0 z-0 bg-grid-pattern opacity-50 pointer-events-none"></div>
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-blue-100 opacity-50 blur-3xl z-0"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 rounded-full bg-indigo-100 opacity-50 blur-3xl z-0"></div>

            <!-- Navbar -->
            <nav class="relative z-10 w-full max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        S
                    </div>
                    <span class="font-bold text-xl tracking-tight text-slate-900">SIMAK BBM</span>
                </div>
                <div class="hidden md:flex gap-8">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-medium text-slate-600 hover:text-blue-600 transition">Panel Utama</a>
                    @else
                        <a href="{{ route('login') }}" class="font-medium text-slate-600 hover:text-blue-600 transition">Log in</a>
                        <!-- Register is disabled for public, usually admin creates users -->
                        {{-- <a href="{{ route('register') }}" class="font-medium text-slate-600 hover:text-blue-600 transition">Register</a> --}}
                    @endauth
                </div>
                <!-- Mobile Menu Button (Placeholder) -->
                <div class="md:hidden">
                    <button class="text-slate-600 hover:text-slate-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </nav>

            <!-- Hero Section -->
            <main class="relative z-10 flex-grow flex items-center justify-center px-6">
                <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div class="space-y-8">
                        <div class="space-y-4">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-sm font-semibold border border-blue-100">
                                <span class="relative flex h-2 w-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                </span>
                                Sistem Manajemen Bahan Bakar Digital
                            </div>
                            <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight text-slate-900 leading-tight">
                                Kelola BBM <br>
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Lebih Efisien</span>
                            </h1>
                            <p class="text-lg text-slate-600 max-w-lg leading-relaxed">
                                Platform terintegrasi untuk manajemen distribusi bahan bakar kendaraan dinas. Logistik akurat, monitoring realtime, dan transparansi penuh.
                            </p>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-8 py-4 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:bg-blue-700 hover:shadow-blue-500/30 transition transform hover:-translate-y-1 text-center">
                                    Masuk ke Sistem
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="px-8 py-4 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:bg-blue-700 hover:shadow-blue-500/30 transition transform hover:-translate-y-1 text-center">
                                    Login Sekarang
                                </a>
                            @endauth
                        </div>

                        <div class="pt-8 grid grid-cols-3 gap-6 border-t border-slate-200">
                            <div>
                                <p class="text-3xl font-bold text-slate-900">100%</p>
                                <p class="text-sm text-slate-500 font-medium">Digital</p>
                            </div>
                            <div>
                                <p class="text-3xl font-bold text-slate-900">QR</p>
                                <p class="text-sm text-slate-500 font-medium">Code System</p>
                            </div>
                            <div>
                                <p class="text-3xl font-bold text-slate-900">24/7</p>
                                <p class="text-sm text-slate-500 font-medium">Monitoring</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Illustration / Image -->
                    <div class="relative hidden md:block">
                        <div class="absolute inset-0 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-2xl transform rotate-3 opacity-10"></div>
                        <div class="relative bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden p-2">
                            <div class="bg-slate-50 rounded-xl overflow-hidden aspect-[4/3] flex items-center justify-center relative">
                                <!-- Mockup UI -->
                                <div class="absolute inset-0 bg-slate-100 opacity-50"></div>
                                <div class="relative z-10 w-3/4 p-4 bg-white rounded-lg shadow-md border border-slate-200 space-y-3">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                        </div>
                                        <div>
                                            <div class="h-2 w-20 bg-slate-200 rounded"></div>
                                            <div class="h-1.5 w-12 bg-slate-100 rounded mt-1"></div>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="h-2 w-full bg-slate-100 rounded"></div>
                                        <div class="h-2 w-5/6 bg-slate-100 rounded"></div>
                                        <div class="h-2 w-4/6 bg-slate-100 rounded"></div>
                                    </div>
                                    <div class="mt-4 flex justify-between items-center">
                                        <div class="h-8 w-24 bg-blue-600 rounded"></div>
                                        <div class="h-8 w-8 bg-slate-100 rounded-full"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="relative z-10 w-full max-w-7xl mx-auto px-6 py-8 text-center text-slate-400 text-sm">
                &copy; {{ date('Y') }} SIMAK BBM. All rights reserved.
            </footer>
        </div>
    </body>
</html>
