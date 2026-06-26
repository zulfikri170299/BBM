@props(['todaySatisfaction' => false])

<div class="bg-slate-900 rounded-2xl border border-white/10 p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
    <!-- Decorative background -->
    <div class="absolute -top-10 -right-10 w-32 h-32 bg-yellow-50 rounded-full blur-2xl opacity-60"></div>
    <div class="absolute top-1/2 -left-10 w-24 h-24 bg-indigo-50 rounded-full blur-2xl opacity-60"></div>
    
    <div class="relative z-10">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-200 flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Indeks Kepuasan
                </h3>
                <p class="text-sm text-slate-400 mt-1">Bagaimana pelayanan petugas kami hari ini?</p>
            </div>
        </div>

        @if($todaySatisfaction)
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center animate-fade-in-up">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 rounded-full text-green-600 mb-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h4 class="font-bold text-green-800">Terima Kasih!</h4>
                <p class="text-xs text-green-600">Masukan Anda sangat berharga bagi kami.</p>
            </div>
        @else
            <form action="{{ route('satisfaction.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-3 gap-3">
                    <!-- Sangat Puas -->
                    <label class="cursor-pointer group">
                        <input type="radio" name="rating" value="3" class="peer sr-only" required>
                        <div class="flex flex-col items-center p-3 rounded-xl border border-white/10 hover:border-yellow-400 hover:bg-yellow-50 peer-checked:border-yellow-500 peer-checked:bg-yellow-100 peer-checked:ring-1 peer-checked:ring-yellow-500 transition-all duration-200">
                            <span class="text-3xl filter grayscale group-hover:grayscale-0 peer-checked:grayscale-0 transition-all transform group-hover:scale-110 peer-checked:scale-110">🤩</span>
                            <span class="text-[10px] font-bold text-slate-400 mt-2 group-hover:text-yellow-700 peer-checked:text-yellow-700">SANGA PUAS</span>
                        </div>
                    </label>

                    <!-- Puas -->
                    <label class="cursor-pointer group">
                        <input type="radio" name="rating" value="2" class="peer sr-only">
                        <div class="flex flex-col items-center p-3 rounded-xl border border-white/10 hover:border-green-400 hover:bg-green-50 peer-checked:border-green-500 peer-checked:bg-green-100 peer-checked:ring-1 peer-checked:ring-green-500 transition-all duration-200">
                            <span class="text-3xl filter grayscale group-hover:grayscale-0 peer-checked:grayscale-0 transition-all transform group-hover:scale-110 peer-checked:scale-110">🙂</span>
                            <span class="text-[10px] font-bold text-slate-400 mt-2 group-hover:text-green-700 peer-checked:text-green-700">PUAS</span>
                        </div>
                    </label>

                    <!-- Tidak Puas -->
                    <label class="cursor-pointer group">
                        <input type="radio" name="rating" value="1" class="peer sr-only">
                        <div class="flex flex-col items-center p-3 rounded-xl border border-white/10 hover:border-red-400 hover:bg-red-50 peer-checked:border-red-500 peer-checked:bg-red-100 peer-checked:ring-1 peer-checked:ring-red-500 transition-all duration-200">
                            <span class="text-3xl filter grayscale group-hover:grayscale-0 peer-checked:grayscale-0 transition-all transform group-hover:scale-110 peer-checked:scale-110">😡</span>
                            <span class="text-[10px] font-bold text-slate-400 mt-2 group-hover:text-red-700 peer-checked:text-red-700">TIDAK PUAS</span>
                        </div>
                    </label>
                </div>

                <div class="relative">
                    <input type="text" name="note" placeholder="Catatan (opsional)..." class="w-full text-sm border-white/10 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 placeholder:text-slate-400 py-2 px-3 bg-slate-800/50">
                </div>

                <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-medium py-2 px-4 rounded-lg shadow-sm hover:shadow-md transition-all text-sm flex items-center justify-center gap-2">
                    <span>Kirim Penilaian</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </form>
        @endif
    </div>

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
    </style>
</div>
