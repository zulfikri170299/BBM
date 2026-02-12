<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIMAK BBM') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Outfit', 'sans-serif'],
                        },
                    }
                }
            }
        </script>
        <style>
            body { font-family: 'Outfit', sans-serif; }
            .login-gradient {
                background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 40%, #312e81 70%, #4338ca 100%);
            }
            .glass-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }
            .input-focus:focus {
                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
            }
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
            .float-animation {
                animation: float 6s ease-in-out infinite;
            }
            @keyframes float-delay {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-15px); }
            }
            .float-animation-delay {
                animation: float-delay 8s ease-in-out infinite;
                animation-delay: 2s;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex">
            <!-- Left Panel: Branding -->
            <div class="hidden lg:flex lg:w-1/2 login-gradient relative overflow-hidden">
                <!-- Decorative Elements -->
                <div class="absolute top-20 left-20 w-72 h-72 bg-indigo-500/20 rounded-full blur-3xl float-animation"></div>
                <div class="absolute bottom-20 right-20 w-96 h-96 bg-violet-500/10 rounded-full blur-3xl float-animation-delay"></div>
                <div class="absolute top-1/3 right-1/4 w-40 h-40 bg-blue-400/10 rounded-full blur-2xl float-animation"></div>

                <!-- Grid Pattern -->
                <div class="absolute inset-0" style="background-image: radial-gradient(rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 40px 40px;"></div>

                <!-- Content -->
                <div class="relative z-10 flex flex-col justify-between p-12 w-full">
                    <!-- Logo -->
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/20">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <span class="text-2xl font-bold text-white tracking-tight">SIMAK BBM</span>
                    </div>

                    <!-- Main Text -->
                    <div class="space-y-6">
                        <h1 class="text-5xl font-extrabold text-white leading-tight">
                            Sistem Manajemen<br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 to-violet-300">Bahan Bakar</span>
                        </h1>
                        <p class="text-lg text-indigo-200 max-w-md leading-relaxed">
                            Platform terintegrasi untuk monitoring dan distribusi BBM kendaraan dinas secara digital, transparan, dan akuntabel.
                        </p>

                        <!-- Feature Badges -->
                        <div class="flex flex-wrap gap-3 pt-4">
                            <div class="flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/10 text-sm text-indigo-200">
                                <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                QR Code System
                            </div>
                            <div class="flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/10 text-sm text-indigo-200">
                                <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                Real-time Monitoring
                            </div>
                            <div class="flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/10 text-sm text-indigo-200">
                                <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                Export PDF & Excel
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="text-sm text-indigo-300/50">
                        &copy; {{ date('Y') }} SIMAK BBM. All rights reserved.
                    </div>
                </div>
            </div>

            <!-- Right Panel: Login Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-slate-50">
                <div class="w-full max-w-md">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden text-center mb-8">
                        <div class="inline-flex items-center gap-3">
                            <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <span class="text-2xl font-bold text-slate-900">SIMAK BBM</span>
                        </div>
                    </div>

                    <!-- Form Card -->
                    <div class="glass-card rounded-3xl shadow-xl shadow-slate-200/50 p-8 sm:p-10">
                        <div class="mb-8">
                            <h2 class="text-3xl font-bold text-slate-900">Selamat Datang</h2>
                            <p class="text-slate-500 mt-2">Masuk ke akun Anda untuk melanjutkan</p>
                        </div>

                        {{ $slot }}
                    </div>

                    <p class="text-center text-xs text-slate-400 mt-6">
                        Hubungi administrator jika Anda kesulitan mengakses akun.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
