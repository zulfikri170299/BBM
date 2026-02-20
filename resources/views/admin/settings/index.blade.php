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
                                    <h3 class="font-semibold text-slate-800">Akses Akun Personel</h3>
                                    <p class="text-xs text-slate-500">Kontrol login seluruh Personel.</p>
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
                        </div>
                    </div>

                    {{-- WhatsApp Configuration Section --}}
                    <div class="pt-6 border-t border-slate-100" x-data="{ 
                        waToken: '{{ $settings['whatsapp_token'] ?? '' }}',
                        waGroup: '{{ $settings['whatsapp_group_target'] ?? '' }}',
                        groups: [],
                        loading: false,
                        error: '',
                        async fetchGroups() {
                            if (!this.waToken) {
                                this.error = 'Masukkan Token API terlebih dahulu.';
                                return;
                            }
                            this.loading = true;
                            this.error = '';
                            try {
                                const response = await fetch('{{ route('admin.settings.whatsapp.fetch-groups') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ token: this.waToken })
                                });
                                const result = await response.json();
                                if (result.status) {
                                    this.groups = result.data.data; // Fonnte returns {status: true, data: [...]}
                                    if (this.groups.length === 0) {
                                        this.error = 'Tidak ada grup ditemukan pada akun ini.';
                                    }
                                } else {
                                    this.error = 'Gagal mengambil grup: ' + (result.reason || 'Cek Token Anda');
                                }
                            } catch (e) {
                                this.error = 'Terjadi kesalahan jaringan.';
                            } finally {
                                this.loading = false;
                            }
                        }
                    }">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Konfigurasi
                                WhatsApp Broadcast</h4>
                        </div>

                        <div class="bg-emerald-50/50 rounded-2xl border border-emerald-100 p-5 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-tighter">Fonnte
                                        API Token</label>
                                    <div class="flex gap-2">
                                        <input type="password" name="whatsapp_token" x-model="waToken"
                                            placeholder="Masukkan Token API Fonnte"
                                            class="flex-1 px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                                        <button type="button" @click="fetchGroups()" :disabled="loading"
                                            class="px-4 py-2.5 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 disabled:opacity-50 transition-all flex items-center gap-2">
                                            <svg x-show="loading" class="animate-spin h-3 w-3 text-white"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                            <span x-text="loading ? 'Loading...' : 'Ambil Daftar Grup'"></span>
                                        </button>
                                    </div>
                                    <p class="mt-2 text-[10px] text-slate-500 italic">*Dapatkan token di
                                        dashboard.fonnte.com</p>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-tighter">Target
                                        WhatsApp Group</label>
                                    <select name="whatsapp_group_target" x-model="waGroup"
                                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                                        <option value="">-- Pilih Grup Tujuan --</option>
                                        <template x-if="groups.length === 0 && waGroup">
                                            <option :value="waGroup" selected x-text="waGroup"></option>
                                        </template>
                                        <template x-for="group in groups" :key="group.id">
                                            <option :value="group.id" x-text="group.name"></option>
                                        </template>
                                    </select>
                                    <p class="mt-2 text-xs text-rose-500 font-medium" x-show="error" x-text="error"
                                        x-cloak></p>
                                    <p class="mt-2 text-[10px] text-slate-500" x-show="!error && waGroup">Current ID:
                                        <span class="font-mono bg-slate-100 px-1 rounded" x-text="waGroup"></span></p>
                                </div>
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