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
                background: linear-gradient(135deg, rgba(66, 0, 0, 0.95) 0%, rgba(128, 0, 0, 0.9) 100%), url('/bg-login.jpg');
                background-size: cover;
                background-position: center;
                position: relative;
            }
            .login-gradient::before {
                content: '';
                position: absolute;
                inset: 0;
                background: radial-gradient(circle at center, transparent 0%, rgba(0,0,0,0.4) 100%);
                pointer-events: none;
            }
            /* Custom Scrollbar */
            ::-webkit-scrollbar { width: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-800 bg-slate-50">
        <div class="min-h-screen flex login-gradient">
            <!-- Left Panel: Branding -->
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden items-center justify-center">
                <!-- Content -->
                <div class="relative z-10 flex flex-col justify-between p-16 w-full h-full">
                    <!-- Logo -->
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('rolog.png') }}" alt="Logo" class="w-14 h-14 object-contain drop-shadow-md">
                        <span class="text-3xl font-extrabold text-white tracking-tight">BIRO LOGISTIK</span>
                    </div>

                    <!-- Main Text -->
                    <div class="space-y-8">
                        <h1 class="text-5xl lg:text-7xl font-extrabold text-white leading-tight drop-shadow-lg">
                            Sistem Manajemen<br>
                            <span class="text-red-200">Logistik & BBM</span>
                        </h1>
                        <p class="text-xl text-red-100/80 max-w-lg leading-relaxed font-light drop-shadow-md">
                            Platform terintegrasi untuk monitoring dan distribusi BBM kendaraan dinas secara digital, transparan, dan akuntabel.
                        </p>

                        <!-- Feature Badges -->
                        <div class="flex flex-wrap gap-3">
                            <div class="flex items-center gap-2 px-4 py-2 bg-black/20 backdrop-blur-md rounded-full border border-white/10 text-sm font-medium text-white shadow-sm">
                                <svg class="w-5 h-5 text-red-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                QR Code System
                            </div>
                            <div class="flex items-center gap-2 px-4 py-2 bg-black/20 backdrop-blur-md rounded-full border border-white/10 text-sm font-medium text-white shadow-sm">
                                <svg class="w-5 h-5 text-red-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                Real-time Monitoring
                            </div>
                            <div class="flex items-center gap-2 px-4 py-2 bg-black/20 backdrop-blur-md rounded-full border border-white/10 text-sm font-medium text-white shadow-sm">
                                <svg class="w-5 h-5 text-red-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                Export PDF & Excel
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="text-sm text-red-100/60 font-medium">
                        &copy; {{ date('Y') }} BIRO LOGISTIK. Polda Nusa Tenggara Barat.
                    </div>
                </div>
            </div>

            <!-- Right Panel: Login Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-16 bg-white/60 backdrop-blur-md lg:rounded-l-[60px] shadow-[-20px_0_40px_rgba(0,0,0,0.1)] relative z-20">
                <div class="w-full max-w-[420px]">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden text-center mb-10">
                        <img src="{{ asset('rolog.png') }}" alt="Logo" class="w-20 h-20 mx-auto object-contain drop-shadow-sm mb-4">
                        <h2 class="text-2xl font-bold text-slate-900">BIRO LOGISTIK</h2>
                    </div>

                    <div class="mb-10 text-center lg:text-left">
                        <h2 class="text-4xl font-bold text-slate-900 tracking-tight mb-3">Selamat Datang</h2>
                        <p class="text-slate-500 text-lg">Silakan masuk ke akun anda.</p>
                    </div>

                    {{ $slot }}

                    <p class="text-center text-xs text-slate-400 mt-12 font-medium">
                        Butuh bantuan teknis? <a href="#" class="text-red-700 hover:text-red-800 hover:underline font-semibold">Hubungi Administrator</a>.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
