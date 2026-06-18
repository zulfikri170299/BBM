<x-app-layout>
    <div class="p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Pengaturan Sistem</h1>
            <p class="mt-1 text-slate-500">Kelola konfigurasi global fitur aplikasi.</p>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-6">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div class="flex items-center justify-between p-4 border border-slate-200 rounded-xl bg-slate-50">
                        <div>
                            <h3 class="font-semibold text-slate-800">Tambah Data Kendaraan Satker</h3>
                            <p class="text-sm text-slate-500">Izinkan akun Satker untuk menambah data kendaraan secara
                                manual.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="satker_can_create_kendaraan" value="1" class="sr-only peer" {{ ($settings['satker_can_create_kendaraan'] ?? '1') == '1' ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600">
                            </div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-slate-200 rounded-xl bg-slate-50">
                        <div>
                            <h3 class="font-semibold text-slate-800">Edit Data Kendaraan Satker</h3>
                            <p class="text-sm text-slate-500">Izinkan akun Satker untuk mengubah data kendaraan.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="satker_can_edit_kendaraan" value="1" class="sr-only peer" {{ ($settings['satker_can_edit_kendaraan'] ?? '1') == '1' ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600">
                            </div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-slate-200 rounded-xl bg-slate-50">
                        <div>
                            <h3 class="font-semibold text-slate-800">Import Data Kendaraan Satker</h3>
                            <p class="text-sm text-slate-500">Izinkan akun Satker untuk import data kendaraan via Excel.
                            </p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="satker_can_import_kendaraan" value="1" class="sr-only peer" {{ ($settings['satker_can_import_kendaraan'] ?? '1') == '1' ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600">
                            </div>
                        </label>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Aktivasi Global
                            Akun</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div
                                class="flex items-center justify-between p-4 border border-slate-200 rounded-xl bg-white shadow-sm">
                                <div>
                                    <h3 class="font-semibold text-slate-800">Akses Akun Satker</h3>
                                    <p class="text-xs text-slate-500">Kontrol login seluruh Admin Satker.</p>
                                    <a href="{{ route('admin.users.index', ['role' => 'admin_satker']) }}"
                                        class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800 mt-2 inline-block uppercase tracking-wider">
                                        &rarr; Pilih Akun per Individu
                                    </a>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_satker_enabled" value="1" class="sr-only peer" {{ ($settings['is_satker_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-600">
                                    </div>
                                </label>
                            </div>

                            <div
                                class="flex items-center justify-between p-4 border border-slate-200 rounded-xl bg-white shadow-sm">
                                <div>
                                    <h3 class="font-semibold text-slate-800">Akses User</h3>
                                    <p class="text-xs text-slate-500">Kontrol login per individu.</p>
                                    <a href="{{ route('admin.users.index', ['role' => 'personel']) }}"
                                        class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800 mt-2 inline-block uppercase tracking-wider">
                                        &rarr; Pilih Akun per Individu
                                    </a>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_personel_enabled" value="1" class="sr-only peer" {{ ($settings['is_personel_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-600">
                                    </div>
                                </label>
                            </div>

                            <div
                                class="flex items-center justify-between p-3 border border-slate-100 rounded-lg bg-slate-50/50">
                                <div>
                                    <h3 class="text-sm font-medium text-slate-600">Kontrol User</h3>
                                    <p class="text-[10px] text-slate-400 mt-0.5">Matikan semua menu dan data user.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer scale-90 origin-right">
                                    <input type="checkbox" name="personel_access_control" value="1" class="sr-only peer" {{ ($settings['personel_access_control'] ?? '1') == '1' ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-600">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>




                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit"
                        class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 hover:shadow-indigo-500/50 transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>