<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-6">
        {{-- Page Header --}}
        <div>
            <h1 class="text-2xl font-bold text-white">Penanda Tangan Laporan</h1>
            <p class="mt-1 text-slate-400">Kelola data penanda tangan yang akan ditampilkan di semua laporan PDF.</p>
        </div>

        {{-- Alert --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form Tambah --}}
        <div class="bg-slate-900 rounded-2xl border border-white/10 shadow-sm overflow-hidden p-6">
            <h3 class="text-lg font-bold text-slate-200 mb-4">Tambah Penanda Tangan</h3>
            <form action="{{ route('admin.penanda-tangan.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-1">Nama <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" required value="{{ old('nama') }}"
                        class="w-full px-4 py-2.5 bg-slate-800/50 border border-white/10 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-300"
                        placeholder="Nama lengkap">
                    @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-1">Jabatan <span class="text-red-500">*</span></label>
                    <input type="text" name="jabatan" required value="{{ old('jabatan') }}"
                        class="w-full px-4 py-2.5 bg-slate-800/50 border border-white/10 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-300"
                        placeholder="Contoh: Kasubbagrenmin Biro Logistik">
                    @error('jabatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-1">Jabatan 2 (Opsional)</label>
                    <input type="text" name="jabatan2" value="{{ old('jabatan2') }}"
                        class="w-full px-4 py-2.5 bg-slate-800/50 border border-white/10 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-300"
                        placeholder="Contoh: Polda NTB">
                    @error('jabatan2') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-1">Pangkat</label>
                    <input type="text" name="pangkat" value="{{ old('pangkat') }}"
                        class="w-full px-4 py-2.5 bg-slate-800/50 border border-white/10 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-300"
                        placeholder="Contoh: KOMISARIS POLISI">
                    @error('pangkat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-1">NRP</label>
                    <input type="text" name="nrp" value="{{ old('nrp') }}"
                        class="w-full px-4 py-2.5 bg-slate-800/50 border border-white/10 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-300"
                        placeholder="Contoh: 99101131">
                    @error('nrp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Simpan
                    </button>
                </div>
            </form>
        </div>

        {{-- Tabel Data --}}
        <div class="bg-slate-900 rounded-2xl border border-white/10 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-white/5">
                <h3 class="text-lg font-bold text-slate-200">Daftar Penanda Tangan</h3>
                <p class="text-xs text-slate-400">Data terakhir yang aktif akan digunakan di semua laporan PDF.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/10">
                    <thead class="bg-slate-800/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-400 uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-400 uppercase">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-400 uppercase">Jabatan</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-400 uppercase">Jabatan 2</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-400 uppercase">Pangkat</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-400 uppercase">NRP</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-slate-400 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-slate-900 border border-white/5 divide-y divide-white/5">
                        @forelse($penandaTangans as $index => $pt)
                            <tr class="hover:bg-slate-800/50 transition {{ $index === 0 ? 'bg-emerald-50/50' : '' }}">
                                <td class="px-4 py-3 text-xs text-slate-400">
                                    {{ $index + 1 }}
                                    @if($index === 0)
                                        <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">AKTIF</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs font-medium text-slate-200">{{ $pt->nama }}</td>
                                <td class="px-4 py-3 text-xs text-slate-400">{{ $pt->jabatan }}</td>
                                <td class="px-4 py-3 text-xs text-slate-400">{{ $pt->jabatan2 ?? '-' }}</td>
                                <td class="px-4 py-3 text-xs text-slate-400">{{ $pt->pangkat ?? '-' }}</td>
                                <td class="px-4 py-3 text-xs text-slate-400">{{ $pt->nrp ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Edit Button --}}
                                        <button onclick="editPenandaTangan({{ json_encode($pt) }}, '{{ route('admin.penanda-tangan.update', $pt) }}')"
                                            class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        {{-- Delete --}}
                                        <form action="{{ route('admin.penanda-tangan.destroy', $pt) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="button" 
                                                data-confirm="Apakah Anda yakin ingin menghapus data penanda tangan ini?"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                 <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    Belum ada data penanda tangan. Silakan tambahkan di form di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Preview --}}
        @if($penandaTangans->count() > 0)
            @php $active = $penandaTangans->first(); @endphp
            <div class="bg-slate-900 rounded-2xl border border-white/10 shadow-sm overflow-hidden p-6">
                <h3 class="text-lg font-bold text-slate-200 mb-4">Preview Tanda Tangan di PDF</h3>
                <div class="border border-dashed border-white/20 rounded-2xl p-8 max-w-sm mx-auto text-center bg-slate-800/50/30">
                    <p class="text-xs text-slate-300">Mataram, {{ \Carbon\Carbon::now()->setTimezone('Asia/Makassar')->translatedFormat('d F Y') }}</p>
                    <p class="text-xs text-slate-200 mt-2 uppercase">{{ $active->jabatan }}</p>
                    @if($active->jabatan2)
                        <p class="text-xs text-slate-200 mt-0 uppercase">{{ $active->jabatan2 }}</p>
                    @endif
                    
                    <div class="mt-12 inline-block">
                        <table class="border-collapse mx-auto w-auto">
                            <tr>
                                <td class="border-b border-slate-900 text-center px-4 pb-0">
                                    <span class="text-sm text-white">{{ $active->nama }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center pt-0 px-1">
                                    <span class="text-xs text-slate-300 font-medium">{{ $active->pangkat }} NRP {{ $active->nrp }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Modal Edit --}}
    <div id="editModal" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900/40 backdrop-blur-sm" onclick="closeEditModal()"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-slate-900 border border-white/5 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/10">
                <div class="bg-slate-900 border border-white/5 px-6 py-6 pb-4">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-slate-200">Edit Penanda Tangan</h3>
                        <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-400 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <form id="editForm" method="POST">
                        @csrf @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-300 mb-1">Nama</label>
                                <input type="text" name="nama" id="edit_nama" required
                                    class="w-full px-4 py-2.5 bg-slate-800/50 border border-white/10 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-300">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-300 mb-1">Jabatan</label>
                                <input type="text" name="jabatan" id="edit_jabatan" required
                                    class="w-full px-4 py-2.5 bg-slate-800/50 border border-white/10 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-300">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-300 mb-1">Jabatan 2 (Opsional)</label>
                                <input type="text" name="jabatan2" id="edit_jabatan2"
                                    class="w-full px-4 py-2.5 bg-slate-800/50 border border-white/10 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-300">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-300 mb-1">Pangkat</label>
                                    <input type="text" name="pangkat" id="edit_pangkat"
                                        class="w-full px-4 py-2.5 bg-slate-800/50 border border-white/10 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-300">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-300 mb-1">NRP</label>
                                    <input type="text" name="nrp" id="edit_nrp"
                                        class="w-full px-4 py-2.5 bg-slate-800/50 border border-white/10 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-300">
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 flex justify-end gap-3">
                            <button type="button" onclick="closeEditModal()" 
                                class="px-6 py-2.5 bg-slate-800 text-slate-300 rounded-xl font-bold hover:bg-slate-200 transition-all">Batal</button>
                            <button type="submit" 
                                class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editPenandaTangan(data, actionUrl) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            
            form.action = actionUrl;
            document.getElementById('edit_nama').value = data.nama;
            document.getElementById('edit_jabatan').value = data.jabatan;
            document.getElementById('edit_jabatan2').value = data.jabatan2 || '';
            document.getElementById('edit_pangkat').value = data.pangkat || '';
            document.getElementById('edit_nrp').value = data.nrp || '';
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
</x-app-layout>
