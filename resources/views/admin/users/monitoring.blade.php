<x-app-layout>
    <div class="p-6 lg:p-8 space-y-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Monitoring Aktivitas User</h1>
                <p class="mt-1 text-slate-500">Pantau status aktif, lokasi terakhir, dan riwayat perubahan akun
                    pengguna.</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
            <form action="{{ route('admin.users.monitoring') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Cari
                        User</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Nama atau email..."
                            class="w-full pl-9 pr-4 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Satker -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Filter
                        Satker</label>
                    <select name="satker_id"
                        class="w-full py-2 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <option value="">Semua Satker</option>
                        @foreach($satkers as $satker)
                            <option value="{{ $satker->id }}" {{ request('satker_id') == $satker->id ? 'selected' : '' }}>
                                {{ $satker->nama_satker }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Filter
                        Tanggal Aktif</label>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                        class="w-full py-2 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                </div>

                <!-- Actions -->
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-indigo-700 transition-all shadow-sm shadow-indigo-100">
                        Terapkan
                    </button>
                    @if(request()->anyFilled(['search', 'satker_id', 'tanggal']))
                        <a href="{{ route('admin.users.monitoring') }}"
                            class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-200 transition-all text-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <!-- Bulk Actions -->
            <div id="bulkActions"
                class="hidden px-6 py-3 border-b border-slate-100 flex items-center justify-between bg-indigo-50/50 animate-in fade-in duration-300">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-indigo-600"><span id="selectedCount">0</span> DIPILIH</span>
                    <div class="h-4 w-px bg-indigo-200"></div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="submitBulk('active')"
                            class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 transition shadow-sm">Aktifkan
                            Terpilih</button>
                        <button type="button" onclick="submitBulk('inactive')"
                            class="px-3 py-1.5 bg-rose-600 text-white rounded-lg text-xs font-bold hover:bg-rose-700 transition shadow-sm">Nonaktifkan
                            Terpilih</button>
                    </div>
                </div>
            </div>

            <form id="bulkForm" action="{{ route('admin.users.bulk-status') }}" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="status" id="bulkStatusInput">
                <div id="bulkIdsContainer"></div>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="w-10 px-6 py-4">
                                <input type="checkbox" id="checkAll"
                                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 shadow-sm cursor-pointer">
                            </th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">User
                            </th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Satker
                                & Role</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status
                                Terakhir</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Lokasi
                                Terakhir</th>
                            <th
                                class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    @if(auth()->id() !== $user->id)
                                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                            class="user-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 shadow-sm cursor-pointer">
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-600">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800">{{ $user->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-semibold text-slate-700">
                                        {{ $user->satker->nama_satker ?? 'Pusat' }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span
                                            class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-600">
                                            {{ str_replace('_', ' ', $user->role) }}
                                        </span>
                                        @if($user->is_active)
                                            <span class="flex h-2 w-2 rounded-full bg-emerald-500" title="Akun Aktif"></span>
                                        @else
                                            <span class="flex h-2 w-2 rounded-full bg-rose-500" title="Akun Nonaktif"></span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->isOnline())
                                        <div class="flex items-center gap-1.5 mb-1">
                                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                            <span class="text-[10px] font-bold text-green-600 uppercase">Aktif Sekarang</span>
                                        </div>
                                    @endif
                                    @if($user->last_activity_at)
                                        <p class="text-xs font-medium text-slate-700">
                                            {{ $user->last_activity_at->diffForHumans() }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $user->last_activity_at->format('d/m H:i') }}
                                            WIB</p>
                                    @else
                                        <span class="text-xs italic text-slate-400">Belum pernah aktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->last_latitude && $user->last_longitude)
                                        <a href="https://www.google.com/maps?q={{ $user->last_latitude }},{{ $user->last_longitude }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-sky-50 text-sky-600 rounded-lg text-xs font-bold hover:bg-sky-100 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Buka Maps
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="p-1.5 {{ $user->is_active ? 'text-rose-400 hover:text-rose-600' : 'text-emerald-400 hover:text-emerald-600' }} transition-colors"
                                                    title="{{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                                    @if($user->is_active)
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                                            </path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    @endif
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" data-confirm="Hapus log user ini?"
                                                    data-confirm-type="error"
                                                    class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all"
                                                    title="Hapus">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        <button onclick="showLogs({{ $user->id }}, '{{ $user->name }}')"
                                            class="p-1.5 text-slate-400 hover:text-indigo-600 transition-colors"
                                            title="Lihat Detail Aktivitas">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Tidak ada data user.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Activity Log Modal -->
    <div id="logModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" aria-hidden="true"
                onclick="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-800" id="modalTitle">Riwayat Aktivitas</h3>
                    <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l18 18"></path>
                        </svg>
                    </button>
                </div>
                <div class="px-6 py-6 max-h-[60vh] overflow-y-auto" id="logContent">
                    <!-- Logs will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Activity Log Modal Logic
            function showLogs(userId, userName) {
                document.getElementById('modalTitle').innerText = 'Aktivitas: ' + userName;
                const content = document.getElementById('logContent');
                content.innerHTML = '<div class="flex justify-center py-8"><svg class="animate-spin h-8 w-8 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>';
                document.getElementById('logModal').classList.remove('hidden');

                fetch(`/admin/users/${userId}/logs`)
                    .then(response => response.json())
                    .then(logs => {
                        if (logs.length === 0) {
                            content.innerHTML = '<p class="text-center text-slate-400 italic py-4">Belum ada riwayat aktivitas tercatat.</p>';
                            return;
                        }

                        let html = '<div class="space-y-4">';
                        logs.forEach(log => {
                            const date = new Date(log.created_at).toLocaleString('id-ID', {
                                day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
                            });
                            html += `
                                <div class="flex gap-4 p-3 rounded-xl bg-slate-50 border border-slate-100">
                                    <div class="flex-shrink-0 w-2 h-2 mt-1.5 rounded-full bg-indigo-500"></div>
                                    <div>
                                        <p class="text-sm text-slate-800 font-medium">${log.aktivitas}</p>
                                        <p class="text-[10px] text-slate-400 mt-1">${date} WIB</p>
                                    </div>
                                </div>
                            `;
                        });
                        html += '</div>';
                        content.innerHTML = html;
                    })
                    .catch(error => {
                        content.innerHTML = '<p class="text-center text-rose-500 py-4">Gagal memuat log aktivitas.</p>';
                    });
            }

            function closeModal() {
                document.getElementById('logModal').classList.add('hidden');
            }

            // Bulk Actions Logic
            const checkAll = document.getElementById('checkAll');
            const userCheckboxes = document.querySelectorAll('.user-checkbox');
            const bulkActions = document.getElementById('bulkActions');
            const selectedCountLabel = document.getElementById('selectedCount');
            const bulkStatusInput = document.getElementById('bulkStatusInput');
            const bulkForm = document.getElementById('bulkForm');

            function updateBulkUI() {
                const checkedCount = document.querySelectorAll('.user-checkbox:checked').length;
                if (selectedCountLabel) selectedCountLabel.innerText = checkedCount;
                if (bulkActions) {
                    if (checkedCount > 0) {
                        bulkActions.classList.remove('hidden');
                    } else {
                        bulkActions.classList.add('hidden');
                    }
                }
            }

            if (checkAll) {
                checkAll.addEventListener('change', () => {
                    userCheckboxes.forEach(cb => {
                        cb.checked = checkAll.checked;
                    });
                    updateBulkUI();
                });
            }

            userCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateBulkUI);
            });

            function submitBulk(status) {
                const actionText = status === 'active' ? 'mengaktifkan' : 'menonaktifkan';
                const selected = document.querySelectorAll('.user-checkbox:checked');

                if (selected.length === 0) return window.showAlert('Peringatan', 'Pilih setidaknya satu user.', 'warning');

                Swal.fire({
                    title: 'Konfirmasi',
                    text: `Apakah Anda yakin ingin ${actionText} ${selected.length} akun yang terpilih?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4338ca',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: 'Batal',
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-[2rem] border-none shadow-2xl p-8',
                        title: 'text-2xl font-black text-slate-800 mb-2',
                        htmlContainer: 'text-slate-500 font-medium mb-6',
                        confirmButton: 'rounded-2xl px-8 py-3.5 font-black uppercase tracking-widest text-xs shadow-lg shadow-indigo-200 ml-3',
                        cancelButton: 'rounded-2xl px-8 py-3.5 font-bold uppercase tracking-widest text-xs text-slate-600 hover:bg-slate-100'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Clear previous IDs
                        const container = document.getElementById('bulkIdsContainer');
                        container.innerHTML = '';

                        // Add new IDs
                        selected.forEach(cb => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'user_ids[]';
                            input.value = cb.value;
                            container.appendChild(input);
                        });

                        bulkStatusInput.value = status;
                        bulkForm.submit();
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>