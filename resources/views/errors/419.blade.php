<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="2;url={{ route('login') }}">
    <title>Sesi Kedaluwarsa - SPBP Polda NTB</title>
    <link rel="icon" type="image/png" href="{{ asset('rolog.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-b from-[#0066eb] via-[#0055d4] to-[#003cb0] text-white min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-7 text-center shadow-2xl relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-cyan-400/20 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-white/15 border border-white/30 flex items-center justify-center shadow-lg">
            <svg class="w-8 h-8 text-cyan-200 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
        </div>

        <h2 class="text-xl font-extrabold text-white mb-2">Sesi Halaman Berakhir</h2>
        <p class="text-xs text-blue-100 font-medium leading-relaxed mb-6">
            Sesi keamanan form Anda telah kedaluwarsa karena tidak ada aktivitas. Sistem sedang menyegarkan halaman login Anda...
        </p>

        <a href="{{ route('login') }}" 
           class="inline-flex items-center justify-center gap-2 w-full py-3 px-5 rounded-2xl bg-white text-blue-700 hover:bg-blue-50 font-black text-xs uppercase tracking-wider shadow-lg transition-all active:scale-95">
            <span>Kembali ke Halaman Login</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </a>
    </div>
</body>
</html>
