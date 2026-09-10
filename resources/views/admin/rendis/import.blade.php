<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Import Rendis BBM</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Buat Rendis BBM massal menggunakan file Excel</p>
            </div>
            <a href="{{ route('admin.rendis.index') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">&larr; Kembali ke Daftar</a>
        </div>

        @if(session('error'))
            <div class="mb-6 bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 text-rose-600 dark:text-rose-400 px-4 py-3 rounded-xl shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-8">
            
            <div class="mb-8 p-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-2">Langkah 1: Download Template</h3>
                <p class="text-blue-700 dark:text-blue-300 text-sm mb-4">
                    Unduh template Excel yang sudah berisi seluruh data kendaraan aktif. Anda hanya perlu mengisi <strong>Triwulan, Tahun, Susut, Hari Operasional</strong> di bagian atas, serta angka <strong>Liter per Hari</strong> untuk masing-masing kendaraan. (Harap <strong>jangan mengubah ID Kendaraan</strong>).
                </p>
                <a href="{{ route('admin.rendis.import.template') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download Template Excel
                </a>
            </div>

            <div class="p-6 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-xl">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Langkah 2: Upload File Excel</h3>
                <form id="importForm" action="{{ route('admin.rendis.import.process') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih File Excel (.xlsx)</label>
                        <input type="file" name="file_excel" accept=".xlsx, .xls" required class="block w-full text-sm text-gray-500 dark:text-gray-400
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-lg file:border-0
                            file:text-sm file:font-semibold
                            file:bg-brand-primary file:text-white
                            hover:file:bg-brand-secondary
                            border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                        @error('file_excel')
                            <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end mt-6" x-data="{ showPinModal: false, pinValue: '' }">
                        <button type="button" @click="showPinModal = true" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Upload dan Proses Rendis
                        </button>

                        {{-- MODAL PIN VERIFICATION --}}
                        <div x-show="showPinModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center" x-transition>
                            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showPinModal = false"></div>
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-sm mx-4 z-10 overflow-hidden" @click.stop>
                                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Verifikasi PIN</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Masukkan PIN Top Up untuk Proses Import</p>
                                </div>
                                <div class="p-6">
                                    <div class="mb-5">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">PIN Top Up</label>
                                        <input type="password" name="pin" x-model="pinValue" placeholder="Masukkan PIN..." class="w-full px-4 py-3 text-center text-lg tracking-widest rounded-xl border-gray-300 dark:border-gray-600 bg-white text-slate-900 dark:bg-gray-700 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                                    </div>
                                    <div class="flex gap-3">
                                        <button type="button" @click="showPinModal = false" class="flex-1 px-4 py-2.5 text-sm font-medium rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Batal</button>
                                        <button type="submit" :disabled="!pinValue" class="flex-1 px-4 py-2.5 text-sm font-medium rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed">Proses</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

    @if(session('import_conflict'))
        <form id="conflictForm" action="{{ route('admin.rendis.import.resolve') }}" method="POST" class="hidden" style="display: none;">
            @csrf
            <input type="hidden" name="file_path" value="{{ session('import_conflict')['file_path'] }}">
            <input type="hidden" name="action_type" id="action_type_input" value="">
        </form>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Data Sudah Ada!',
                        text: '{{ session("import_conflict")["message"] }}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Update Data Saja',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#3b82f6',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('action_type_input').value = 'update';
                            document.getElementById('conflictForm').submit();
                        }
                    });
                } else {
                    alert('Data Sudah Ada! Pilihan fitur timpa/update membutuhkan SweetAlert.');
                }
            });
        </script>
    @endif
</x-app-layout>
