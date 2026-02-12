<x-app-layout>
    <div class="p-6 lg:p-8">
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-8">
            <div class="flex items-center gap-5 mb-6">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Selamat Datang, {{ Auth::user()->name }}!</h1>
                    <p class="text-slate-500">Anda login sebagai <span class="font-semibold text-indigo-600 capitalize">{{ str_replace('_', ' ', Auth::user()->role) }}</span></p>
                </div>
            </div>
            <p class="text-slate-600 mb-4">Silakan pilih menu di sidebar untuk mengakses fitur yang tersedia.</p>
            
            @if(Auth::user()->role === 'super_admin')
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold text-sm hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all duration-200">
                    Buka Panel Admin →
                </a>
            @elseif(Auth::user()->role === 'admin_satker')
                <a href="{{ route('satker.dashboard') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold text-sm hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all duration-200">
                    Buka Panel Satker →
                </a>
            @elseif(Auth::user()->role === 'petugas_bbm')
                <a href="{{ route('petugas.dashboard') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold text-sm hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all duration-200">
                    Buka Panel Petugas →
                </a>
            @elseif(Auth::user()->role === 'personel')
                <a href="{{ route('personel.dashboard') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold text-sm hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all duration-200">
                    Buka Panel Personel →
                </a>
            @endif
        </div>
    </div>
</x-app-layout>
