@props(['maxWidth' => 'max-w-[440px]'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SPBP Rolog Polda NTB') }} - Login</title>
    <link rel="icon" href="{{ asset('rolog.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 3s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                },
            },
        }
    </script>
    <!-- Tailwind config moved to tailwind.config.js -->
    @php
        $bgSetting = \App\Models\Setting::where('key', 'login_background_image')->value('value');
        $bgUrl = (!empty($bgSetting) && file_exists(public_path($bgSetting))) ? asset($bgSetting) : asset('polda.jpg');
        $bgOverlay = (int) (\App\Models\Setting::where('key', 'login_bg_overlay')->value('value') ?? 25);
        $bgPosY = (int) (\App\Models\Setting::where('key', 'login_bg_pos_y')->value('value') ?? 50);
        $bgZoom = (int) (\App\Models\Setting::where('key', 'login_bg_zoom')->value('value') ?? 100);
        $overlayAlpha1 = number_format(($bgOverlay * 0.72) / 100, 2);
        $overlayAlpha2 = number_format(min(1.0, ($bgOverlay * 1.4) / 100), 2);
    @endphp
    <style>
        /* body { font-family: 'Outfit', sans-serif; } - Handled by font-sans class */

    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
    </style>
</head>

<body class="font-sans antialiased text-slate-800 dark:text-slate-200 bg-slate-950">
    <!-- Dedicated Desktop Background Layer: Identik 1:1 dengan Live Preview Admin Settings -->
    <div class="hidden md:block fixed inset-0 pointer-events-none -z-10 overflow-hidden">
        <img src="{{ $bgUrl }}" 
             alt="Background Gedung Biro Logistik Polda NTB" 
             class="w-full h-full object-cover origin-center transition-all duration-300"
             style="object-position: center {{ $bgPosY }}%; transform: scale({{ $bgZoom / 100 }});">
        
        <!-- Dark Overlay Gradient -->
        <div class="absolute inset-0 bg-slate-950 transition-opacity duration-300"
             style="opacity: {{ $bgOverlay / 100 }};"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-slate-950/40 pointer-events-none"></div>
    </div>

    <!-- Mobile Background Container -->
    <div class="h-[100dvh] md:min-h-screen flex flex-col items-center justify-center p-0 md:p-4 relative z-10 md:bg-transparent bg-slate-900 overflow-hidden md:overflow-visible">
        <div class="w-full {{ $maxWidth }} relative z-20 flex-1 flex flex-col justify-center py-0 md:py-4 h-full md:h-auto overflow-hidden md:overflow-visible">
            {{ $slot }}

            <p class="hidden md:block text-center text-xs text-white/80 drop-shadow mt-4 mb-2 font-medium">
                &copy; {{ date('Y') }} BIRO LOGISTIK. Polda Nusa Tenggara Barat.
            </p>
        </div>
    </div>

    @stack('scripts')
</body>

</html>