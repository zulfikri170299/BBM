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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

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
        body {
            font-family: 'Outfit', sans-serif;
        }

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
            background: radial-gradient(circle at center, transparent 0%, rgba(0, 0, 0, 0.4) 100%);
            pointer-events: none;
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

<body class="font-sans antialiased text-slate-200">
    <div class="min-h-screen flex items-center justify-center p-4 login-gradient">
        <div class="w-full max-w-[440px] relative z-20">
            {{ $slot }}

            <p class="text-center text-xs text-slate-500 mt-8 font-medium">
                &copy; {{ date('Y') }} BIRO LOGISTIK. Polda Nusa Tenggara Barat.
            </p>
        </div>
    </div>
</body>

</html>