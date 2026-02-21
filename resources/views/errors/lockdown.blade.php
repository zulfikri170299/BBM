<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Gangguan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>

<body class="bg-[#0f172a] text-white flex items-center justify-center min-h-screen p-6">
    <div class="max-w-md w-full text-center space-y-8 animate-in fade-in zoom-in duration-700">
        <div class="relative">
            <div class="absolute inset-0 bg-red-500 blur-3xl opacity-20 -z-10"></div>
            <div
                class="bg-red-500/10 border border-red-500/20 p-6 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m0 0v2m0-2h2m-2 0H10m11-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <h1 class="text-4xl font-bold tracking-tight">Sistem Sedang Gangguan</h1>
        <p class="text-slate-400 text-lg">
            Silahkan Coba Beberapa Saat Lagi
        </p>

        <div class="pt-8">
            <a href="/login" class="text-slate-500 hover:text-white transition-colors text-sm">
                Login Administrator
            </a>
        </div>
    </div>
</body>

</html>