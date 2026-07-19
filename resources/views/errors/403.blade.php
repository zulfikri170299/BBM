<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak</title>
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
            <div class="absolute inset-0 bg-indigo-500 blur-3xl opacity-20 -z-10"></div>
            <div
                class="bg-indigo-500/10 border border-indigo-500/20 p-6 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m0 0v2m0-2h2m-2 0H10m11-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <h1 class="text-2xl font-bold tracking-tight">403 | Forbidden</h1>
        <p class="text-slate-400 text-lg">
            Maaf Anda Tidak Ada Akses Untuk Halaman Tersebut
        </p>

        <div class="pt-8">
            <a href="{{ url('/') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/30 transition-all active:scale-95 gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>

</html>
