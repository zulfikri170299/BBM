<x-app-layout>
    <div class="py-6 sm:py-10 bg-slate-800/50 min-h-screen px-2 sm:px-6 lg:px-8" x-data="{ 
        tab: '{{ $tab }}',
        showModal: false,
        editingItem: null,
        newPin: '',
        isPersonel: true,
        loading: false,
        
        openEdit(item, type) {
            this.editingItem = item;
            this.isPersonel = type === 'personel';
            this.newPin = '';
            this.showModal = true;
        },
        
        async savePin() {
            if (this.newPin.length !== 6) {
                alert('PIN harus 6 digit angka.');
                return;
            }
            
            this.loading = true;
            const url = this.isPersonel 
                ? `/admin/pin-management/personel/${this.editingItem.id}`
                : `/admin/pin-management/kendaraan/${this.editingItem.id}`;
                
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ pin: this.newPin })
                });
                
                const result = await response.json();
                if (result.success) {
                    window.location.reload();
                } else {
                    alert(result.message || 'Terjadi kesalahan.');
                }
            } catch (error) {
                alert('Gagal menghubungi server.');
            } finally {
                this.loading = false;
            }
        }
    }">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-3xl font-black text-slate-200 tracking-tight">Manajemen PIN</h2>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Kelola PIN Personel dan Kendaraan secara mandiri</p>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="bg-slate-900 border border-white/5 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-white/5 overflow-hidden">
                <!-- Tabs & Filters -->
                <div class="p-6 border-b border-slate-50 bg-slate-800/50/30">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <!-- Custom Tabs -->
                        <div class="flex p-1.5 bg-slate-200/50 rounded-2xl w-fit">
                            <a href="{{ request()->fullUrlWithQuery(['tab' => 'personel']) }}"
                               class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $tab === 'personel' ? 'bg-slate-900 border border-white/5 text-indigo-600 shadow-sm' : 'text-slate-400 hover:text-slate-300' }}">
                                Personel
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['tab' => 'kendaraan']) }}"
                               class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $tab === 'kendaraan' ? 'bg-slate-900 border border-white/5 text-indigo-600 shadow-sm' : 'text-slate-400 hover:text-slate-300' }}">
                                Kendaraan
                            </a>
                        </div>

                        <!-- Filters -->
                        <form action="{{ route('admin.pin-management.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                            <input type="hidden" name="tab" value="{{ $tab }}">
                            
                            <div class="relative min-w-[200px]">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="w-full h-11 pl-10 pr-4 rounded-xl border-white/10 bg-slate-900 border border-white/5 text-xs font-bold text-slate-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all"
                                    placeholder="Cari {{ $tab === 'personel' ? 'Nama/NRP' : 'No Polisi' }}...">
                                <div class="absolute left-3.5 top-3.5 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                            </div>

                            <select name="satker_id" onchange="this.form.submit()"
                                class="tom-select h-11 min-w-[200px]">
                                <option value="">Semua Satker</option>
                                @foreach($satkers as $satker)
                                    <option value="{{ $satker->id }}" {{ request('satker_id') == $satker->id ? 'selected' : '' }}>
                                        {{ $satker->nama_satker }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit" class="h-11 px-6 bg-slate-800 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-900 transition-all">
                                Filter
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Table Area -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-800/50 border-b border-white/5">
                                <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Data {{ $tab === 'personel' ? 'Personel' : 'Kendaraan' }}</th>
                                <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Satuan Kerja</th>
                                <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">PIN Saat Ini</th>
                                <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($data as $item)
                                <tr class="hover:bg-slate-800/50 transition-all group">
                                    <td class="px-4 py-3">
                                        @if($tab === 'personel')
                                            <div>
                                                <p class="text-sm font-black text-slate-200">{{ $item->nama }}</p>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase mt-0.5">NRP: {{ $item->nrp }}</p>
                                            </div>
                                        @else
                                            <div>
                                                <p class="text-sm font-black text-slate-200">{{ $item->no_polisi }}</p>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase mt-0.5">{{ $item->jenis_kendaraan }}</p>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs font-bold text-slate-400">
                                        {{ $item->satker->nama_satker ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="inline-flex items-center px-3 py-1 bg-slate-800 rounded-lg text-xs font-mono font-black text-slate-300 tracking-widest">
                                            {{ $item->pin }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <button @click="openEdit({{ json_encode($item) }}, '{{ $tab }}')"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Ubah PIN
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="p-4 bg-slate-800/50 rounded-full mb-4">
                                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Data tidak ditemukan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($data->hasPages())
                    <div class="p-6 border-t border-slate-50 bg-slate-800/50/30">
                        {{ $data->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Modal Edit PIN -->
        <div x-show="showModal" 
             class="fixed inset-0 z-[100] overflow-y-auto" 
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-slate-900 border border-white/5 rounded-[2rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-md sm:w-full"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
                    
                    <div class="px-6 pt-8 pb-6">
                        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-6 bg-indigo-50 rounded-2xl text-indigo-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        
                        <div class="text-center mb-8">
                            <h3 class="text-xl font-black text-slate-200 leading-tight">Ubah PIN Keamanan</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1" x-text="isPersonel ? editingItem?.nama : editingItem?.no_polisi"></p>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">PIN Baru (6 Digit)</label>
                                <input type="text" x-model="newPin" 
                                       maxlength="6"
                                       inputmode="numeric"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)"
                                       class="w-full h-14 px-6 text-2xl font-mono font-black text-center tracking-[0.5em] border-2 border-white/5 bg-slate-800/50 rounded-2xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder:text-slate-200"
                                       placeholder="••••••">
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <button @click="showModal = false"
                                        class="h-12 px-6 bg-slate-800 text-slate-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 transition-all">
                                    Batal
                                </button>
                                <button @click="savePin()"
                                        :disabled="loading || newPin.length !== 6"
                                        class="h-12 px-6 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/20 disabled:opacity-50 flex items-center justify-center gap-2">
                                    <template x-if="loading">
                                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    </template>
                                    <span x-text="loading ? 'Menyimpan...' : 'Simpan PIN'"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
