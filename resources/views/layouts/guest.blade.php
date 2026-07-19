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
    <style>
        /* body { font-family: 'Outfit', sans-serif; } - Handled by font-sans class */

        .login-gradient {
            background-color: #0f172a;
            position: relative;
        }

        @media (min-width: 768px) {
            .login-gradient {
                background-image: url('/polda.jpg');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
            }
        }

        .login-gradient::before {
            display: none;
        }

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

<body class="font-sans antialiased text-slate-200 bg-slate-900">
    <div class="min-h-screen flex flex-col items-center justify-center p-0 md:p-4 login-gradient">
        <div class="w-full {{ $maxWidth }} relative z-20 flex-1 flex flex-col justify-center">
            {{ $slot }}

            <p class="text-center text-xs text-slate-400 mt-8 mb-8 md:mb-0 font-medium">
                &copy; {{ date('Y') }} BIRO LOGISTIK. Polda Nusa Tenggara Barat.
            </p>
        </div>
    </div>

    @stack('scripts')
</body>

</html>