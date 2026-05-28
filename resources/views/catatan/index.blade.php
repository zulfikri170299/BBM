<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8 bg-gray-50/30 min-h-screen" x-data="{ 
        showAddModal: false,
        showEditModal: false,
        showDeleteModal: false,
        currentNote: { id: '', judul: '', isi: '', warna: 'indigo' },
        noteToDelete: null,
        colors: [
            { name: 'Indigo', class: 'bg-indigo-50 border-indigo-200 text-indigo-700', value: 'indigo' },
            { name: 'Emerald', class: 'bg-emerald-50 border-emerald-200 text-emerald-700', value: 'emerald' },
            { name: 'Amber', class: 'bg-amber-50 border-amber-200 text-amber-700', value: 'amber' },
            { name: 'Rose', class: 'bg-rose-50 border-rose-200 text-rose-700', value: 'rose' },
            { name: 'Sky', class: 'bg-sky-50 border-sky-200 text-sky-700', value: 'sky' },
            { name: 'Slate', class: 'bg-slate-50 border-slate-200 text-slate-700', value: 'slate' }
        ],
        editNote(note) {
            this.currentNote = { ...note };
            this.showEditModal = true;
        },
        confirmDelete(id) {
            this.noteToDelete = id;
            this.showDeleteModal = true;
        }
    }">
        <!-- Header Section -->
        <div class="max-w-7xl mx-auto mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Catatan & Memo</h1>
                    <p class="mt-1 text-sm text-gray-500 font-medium italic">Simpan ide, pengingat, atau catatan penting Anda di sini.</p>
                </div>
                
                <button @click="showAddModal = true" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl transition-all duration-200 shadow-lg shadow-indigo-500/25 font-bold text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Catatan
                </button>
            </div>
        </div>

        <!-- Notes Grid -->
        <div class="max-w-7xl mx-auto">
            @if($catatans->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border-2 border-dashed border-gray-200">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Belum Ada Catatan</h3>
                    <p class="text-gray-500 text-sm mt-1">Mulai buat catatan pertama Anda hari ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($catatans as $catatan)
                        <div class="group relative flex flex-col p-6 rounded-2xl border-2 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 {{ 
                            $catatan->warna == 'emerald' ? 'bg-emerald-50 border-emerald-100 text-emerald-900' : (
                            $catatan->warna == 'amber' ? 'bg-amber-50 border-amber-100 text-amber-900' : (
                            $catatan->warna == 'rose' ? 'bg-rose-50 border-rose-100 text-rose-900' : (
                            $catatan->warna == 'sky' ? 'bg-sky-50 border-sky-100 text-sky-900' : (
                            $catatan->warna == 'slate' ? 'bg-slate-50 border-slate-100 text-slate-900' : 'bg-indigo-50 border-indigo-100 text-indigo-900')))) 
                        }}">
                            <!-- Note Actions -->
                            <div class="absolute top-4 right-4 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="editNote({
                                    id: '{{ $catatan->id }}',
                                    judul: @js($catatan->judul),
                                    isi: @js($catatan->isi),
                                    warna: '{{ $catatan->warna }}'
                                })" class="p-1.5 rounded-lg hover:bg-white/50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </button>
                                <button @click="confirmDelete('{{ $catatan->id }}')" class="p-1.5 rounded-lg hover:bg-white/50 text-rose-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="flex-1">
                                <h4 class="text-lg font-bold mb-3 pr-12">{{ $catatan->judul }}</h4>
                                <div class="text-sm opacity-80 leading-relaxed line-clamp-6 whitespace-pre-wrap">{{ $catatan->isi }}</div>
                            </div>

                            <div class="mt-6 pt-4 border-t border-black/5 flex items-center justify-between text-[10px] font-bold uppercase tracking-wider opacity-50">
                                <span>{{ $catatan->created_at->translatedFormat('d M Y') }}</span>
                                <span>{{ $catatan->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Add Modal -->
        <div x-cloak x-show="showAddModal" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showAddModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showAddModal = false"></div>

                <div x-show="showAddModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('catatan.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-8 pt-8 pb-6">
                            <div class="mb-6 flex items-center justify-between">
                                <h3 class="text-xl font-bold text-gray-900">Buat Catatan Baru</h3>
                                <button type="button" @click="showAddModal = false" class="text-gray-400 hover:text-gray-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>

                            <div class="space-y-5">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Judul</label>
                                    <input type="text" name="judul" required placeholder="Judul catatan..." class="w-full px-4 py-3 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-medium">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Isi Catatan</label>
                                    <textarea name="isi" required rows="6" placeholder="Tulis sesuatu di sini..." class="w-full px-4 py-3 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-medium"></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Warna</label>
                                    <div class="flex flex-wrap gap-3" x-data="{ selectedWarna: 'indigo' }">
                                        <input type="hidden" name="warna" :value="selectedWarna">
                                        <template x-for="color in colors" :key="color.value">
                                            <button type="button" @click="selectedWarna = color.value" 
                                                class="w-10 h-10 rounded-full border-2 transition-all transform hover:scale-110 active:scale-95"
                                                :class="[
                                                    color.class.split(' ')[0], 
                                                    selectedWarna === color.value ? 'border-gray-900 ring-2 ring-offset-2 ring-gray-200' : 'border-transparent'
                                                ]"
                                                :title="color.name">
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-8 py-6 flex flex-col sm:flex-row-reverse gap-3">
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-xl border border-transparent shadow-lg shadow-indigo-500/25 px-8 py-3 bg-indigo-600 text-sm font-bold text-white hover:bg-indigo-700 transition-all active:scale-95">
                                Simpan Catatan
                            </button>
                            <button type="button" @click="showAddModal = false" class="w-full sm:w-auto inline-flex justify-center rounded-xl px-8 py-3 text-sm font-bold text-gray-700 hover:bg-gray-200 transition-all active:scale-95">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-cloak x-show="showEditModal" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = false"></div>

                <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form :action="`/catatan/${currentNote.id}`" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white px-8 pt-8 pb-6">
                            <div class="mb-6 flex items-center justify-between">
                                <h3 class="text-xl font-bold text-gray-900">Edit Catatan</h3>
                                <button type="button" @click="showEditModal = false" class="text-gray-400 hover:text-gray-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>

                            <div class="space-y-5">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Judul</label>
                                    <input type="text" name="judul" required x-model="currentNote.judul" class="w-full px-4 py-3 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-medium">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Isi Catatan</label>
                                    <textarea name="isi" required rows="6" x-model="currentNote.isi" class="w-full px-4 py-3 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-medium"></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Warna</label>
                                    <div class="flex flex-wrap gap-3">
                                        <input type="hidden" name="warna" :value="currentNote.warna">
                                        <template x-for="color in colors" :key="color.value">
                                            <button type="button" @click="currentNote.warna = color.value" 
                                                class="w-10 h-10 rounded-full border-2 transition-all transform hover:scale-110 active:scale-95"
                                                :class="[
                                                    color.class.split(' ')[0], 
                                                    currentNote.warna === color.value ? 'border-gray-900 ring-2 ring-offset-2 ring-gray-200' : 'border-transparent'
                                                ]"
                                                :title="color.name">
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-8 py-6 flex flex-col sm:flex-row-reverse gap-3">
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-xl border border-transparent shadow-lg shadow-indigo-500/25 px-8 py-3 bg-indigo-600 text-sm font-bold text-white hover:bg-indigo-700 transition-all active:scale-95">
                                Perbarui Catatan
                            </button>
                            <button type="button" @click="showEditModal = false" class="w-full sm:w-auto inline-flex justify-center rounded-xl px-8 py-3 text-sm font-bold text-gray-700 hover:bg-gray-200 transition-all active:scale-95">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-cloak x-show="showDeleteModal" class="fixed inset-0 z-[110] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showDeleteModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                    <div class="bg-white px-8 pt-8 pb-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Hapus Catatan?</h3>
                                <p class="text-sm text-gray-500 font-medium">Tindakan ini tidak dapat dibatalkan.</p>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm leading-relaxed">Apakah Anda yakin ingin menghapus catatan ini secara permanen?</p>
                    </div>
                    <div class="bg-gray-50 px-8 py-6 flex flex-col sm:flex-row-reverse gap-3">
                        <form :action="`/catatan/${noteToDelete}`" method="POST" class="w-full sm:w-auto">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-lg shadow-rose-500/25 px-8 py-3 bg-rose-600 text-sm font-bold text-white hover:bg-rose-700 transition-all active:scale-95">
                                Hapus Sekarang
                            </button>
                        </form>
                        <button type="button" @click="showDeleteModal = false" class="w-full sm:w-auto inline-flex justify-center rounded-xl px-8 py-3 text-sm font-bold text-gray-700 hover:bg-gray-200 transition-all active:scale-95">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
