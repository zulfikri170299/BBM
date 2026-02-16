<x-app-layout>
    <div class="p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Pengaturan Profil</h1>
                <p class="mt-1 text-slate-500">Kelola informasi akun, keamanan, dan preferensi Anda.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Side: Nav/Info -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden p-6 text-center">
                    <div class="w-24 h-24 mx-auto rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg ring-4 ring-slate-50 mb-4">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">{{ Auth::user()->name }}</h3>
                    <p class="text-sm text-slate-500 font-medium">{{ Auth::user()->email }}</p>
                    <div class="mt-4 flex justify-center">
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-indigo-100">
                            {{ str_replace('_', ' ', Auth::user()->role) }}
                        </span>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden p-2">
                    <nav class="space-y-1">
                        <a href="#profile-info" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 rounded-xl transition-colors">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Informasi Profil
                        </a>
                        <a href="#update-password" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 rounded-xl transition-colors">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Keamanan Password
                        </a>
                        @if(Auth::user()->role !== 'admin_satker')
                        <a href="#update-topup-password" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 rounded-xl transition-colors">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Password Top Up
                        </a>
                        @endif
                    </nav>
                </div>
            </div>

            <!-- Right Side: Forms -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Profile Information -->
                <div id="profile-info" class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden scroll-mt-6">
                    <div class="p-6 lg:p-8">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Update Password -->
                <div id="update-password" class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden scroll-mt-6">
                    <div class="p-6 lg:p-8">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Update Top Up Password -->
                @if(Auth::user()->role !== 'admin_satker')
                <div id="update-topup-password" class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden scroll-mt-6">
                    <div class="p-6 lg:p-8">
                        @include('profile.partials.update-topup-password-form')
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
