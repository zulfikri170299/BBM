<x-app-layout>
    <div class="p-3 sm:p-6 lg:p-8 space-y-4 sm:space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-xl sm:text-3xl font-bold text-slate-900">Pengaturan Profil</h1>
                <p class="mt-1 text-xs sm:text-base text-slate-500">Kelola informasi akun, keamanan, dan preferensi
                    Anda.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Left Side: Nav/Info -->
            <div class="lg:col-span-1 space-y-4 sm:space-y-6">
                <div
                    class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden p-4 sm:p-6 text-center">
                    <div
                        class="w-20 h-20 sm:w-24 sm:h-24 mx-auto rounded-2xl overflow-hidden flex items-center justify-center shadow-lg ring-4 ring-slate-50 mb-3 sm:mb-4">
                        <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
                            class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">{{ Auth::user()->name }}</h3>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium break-all">{{ Auth::user()->email }}</p>
                    <div class="mt-3 sm:mt-4 flex justify-center">
                        <span
                            class="px-3 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-indigo-100">
                            {{ str_replace('_', ' ', Auth::user()->role) }}
                        </span>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden p-2">
                    <nav class="space-y-1">
                        <a href="#profile-info"
                            class="flex items-center gap-3 px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 rounded-xl transition-colors">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Informasi Profil
                        </a>
                        <a href="#update-photo"
                            class="flex items-center gap-3 px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 rounded-xl transition-colors">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-pink-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            Foto Profil
                        </a>
                        <a href="#update-password"
                            class="flex items-center gap-3 px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 rounded-xl transition-colors">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-amber-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                            Keamanan Password
                        </a>
                        @if(!in_array(Auth::user()->role, ['admin_satker', 'personel']))
                            <a href="#update-topup-password"
                                class="flex items-center gap-3 px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 rounded-xl transition-colors">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                                Password Top Up
                            </a>
                        @endif
                    </nav>
                </div>
            </div>

            <!-- Right Side: Forms -->
            <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                <!-- Profile Information -->
                <div id="profile-info"
                    class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden scroll-mt-6">
                    <div class="p-4 sm:p-6 lg:p-8">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Update Profile Photo -->
                <div id="update-photo"
                    class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden scroll-mt-6">
                    <div class="p-4 sm:p-6 lg:p-8">
                        <header>
                            <h2 class="text-base sm:text-lg font-medium text-gray-900">
                                {{ __('Foto Profil') }}
                            </h2>

                            <p class="mt-1 text-xs sm:text-sm text-gray-600">
                                {{ __("Update foto profil akun anda.") }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('profile.photo.update') }}"
                            class="mt-4 sm:mt-6 space-y-4 sm:space-y-6" enctype="multipart/form-data">
                            @csrf

                            <div class="flex items-center gap-4">
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
                                    class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover shadow-sm">
                                <div class="w-full">
                                    <input id="photo" name="photo" type="file" class="block w-full text-xs sm:text-sm text-slate-500
                                        file:mr-2 sm:file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-xs sm:file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100
                                    " accept="image/*" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('photo')" />
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button class="text-xs sm:text-sm">{{ __('Save') }}</x-primary-button>

                                @if (session('status') === 'photo-updated')
                                    <p x-data="{ show: true }" x-show="show" x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-xs sm:text-sm text-gray-600">{{ __('Saved.') }}</p>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Update Password -->
                <div id="update-password"
                    class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden scroll-mt-6">
                    <div class="p-4 sm:p-6 lg:p-8">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Update Top Up Password -->
                @if(!in_array(Auth::user()->role, ['admin_satker', 'personel']))
                    <div id="update-topup-password"
                        class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden scroll-mt-6">
                        <div class="p-4 sm:p-6 lg:p-8">
                            @include('profile.partials.update-topup-password-form')
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('status') === 'password-updated')
                Swal.fire({
                    icon: 'success',
                    title: 'Password Berhasil Diubah!',
                    text: 'Password akun Anda telah diperbarui. Gunakan password baru saat login berikutnya.',
                    confirmButtonColor: '#4338ca',
                    confirmButtonText: 'OK, Mengerti',
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-3xl border border-slate-100 shadow-2xl',
                        title: 'text-xl font-bold text-slate-800',
                        htmlContainer: 'text-slate-500 font-medium text-sm',
                        confirmButton: 'rounded-xl px-8 py-2.5 font-bold'
                    }
                });
            @endif

            @if(session('status') === 'topup-password-updated')
                Swal.fire({
                    icon: 'success',
                    title: 'Password Top Up Diperbarui!',
                    text: 'Password Top Up baru Anda sudah aktif dan siap digunakan untuk otorisasi transaksi.',
                    confirmButtonColor: '#e11d48',
                    confirmButtonText: 'OK, Mengerti',
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-3xl border border-slate-100 shadow-2xl',
                        title: 'text-xl font-bold text-slate-800',
                        htmlContainer: 'text-slate-500 font-medium text-sm',
                        confirmButton: 'rounded-xl px-8 py-2.5 font-bold'
                    }
                });
            @endif

            @if(session('status') === 'topup-password-reset')
                Swal.fire({
                    icon: 'info',
                    title: 'Password Top Up Direset!',
                    text: 'Password Top Up berhasil direset ke password default. Silakan hubungi admin jika Anda lupa.',
                    confirmButtonColor: '#f59e0b',
                    confirmButtonText: 'OK, Mengerti',
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-3xl border border-slate-100 shadow-2xl',
                        title: 'text-xl font-bold text-slate-800',
                        htmlContainer: 'text-slate-500 font-medium text-sm',
                        confirmButton: 'rounded-xl px-8 py-2.5 font-bold'
                    }
                });
            @endif

            @if(session('status') === 'profile-updated')
                Swal.fire({
                    icon: 'success',
                    title: 'Profil Diperbarui!',
                    text: 'Informasi profil Anda berhasil disimpan.',
                    confirmButtonColor: '#4338ca',
                    confirmButtonText: 'OK',
                    timer: 3000,
                    timerProgressBar: true,
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-3xl border border-slate-100 shadow-2xl',
                        title: 'text-xl font-bold text-slate-800',
                        htmlContainer: 'text-slate-500 font-medium text-sm',
                        confirmButton: 'rounded-xl px-8 py-2.5 font-bold'
                    }
                });
            @endif
            });
    </script>
@endpush