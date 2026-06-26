<x-app-layout>
    <div class="py-4 sm:py-12 px-2 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900 border border-white/5 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold">Daftar Nominatif Berita Acara</h2>
                            <p class="text-slate-400">Laporan nominatif penerimaan BBM per Satker.</p>
                        </div>
                    </div>

                    <!-- Pihak Kesatu Configuration Form -->
                    <div class="mb-8 p-6 bg-slate-800/50 rounded-2xl border border-white/10 shadow-sm">
                        <h3 class="font-bold text-slate-200 flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                </path>
                            </svg>
                            Konfigurasi Penandatangan Nominatif
                        </h3>
                        <form action="{{ route('admin.nominatif.update-settings') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                @php $settingsData = \App\Models\Setting::all()->pluck('value', 'key'); @endphp
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-slate-400 uppercase">Nama</label>
                                    <input type="text" name="nominatif_nama"
                                        value="{{ $settingsData['nominatif_nama'] ?? '' }}"
                                        class="w-full px-4 py-2 rounded-xl border-white/10 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                        placeholder="Tulis Nama Lengkap">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-slate-400 uppercase">Pangkat</label>
                                    <input type="text" name="nominatif_pangkat"
                                        value="{{ $settingsData['nominatif_pangkat'] ?? '' }}"
                                        class="w-full px-4 py-2 rounded-xl border-white/10 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                        placeholder="Ketik Pangkat">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-slate-400 uppercase">NRP</label>
                                    <input type="text" name="nominatif_nrp"
                                        value="{{ $settingsData['nominatif_nrp'] ?? '' }}"
                                        class="w-full px-4 py-2 rounded-xl border-white/10 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                        placeholder="No NRP">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-slate-400 uppercase">Jabatan</label>
                                    <input type="text" name="nominatif_jabatan"
                                        value="{{ $settingsData['nominatif_jabatan'] ?? '' }}"
                                        class="w-full px-4 py-2 rounded-xl border-white/10 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                        placeholder="Sebutkan Jabatan">
                                </div>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <button type="submit"
                                    class="px-5 py-2 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
                                    Simpan Konfigurasi
                                </button>
                            </div>
                        </form>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-6 p-4 bg-slate-800/50 rounded-2xl border border-white/10 shadow-sm overflow-x-auto">
                        <form action="{{ route('admin.nominatif.index') }}" method="GET" class="flex items-end gap-3 min-w-max">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase ml-1">Tahun</label>
                                <select name="tahun" class="tom-select w-28 px-3 py-1.5 rounded-xl border-white/10 text-xs font-bold">
                                    @php $currentYear = date('Y'); @endphp
                                    @for($i = $currentYear; $i >= $currentYear - 5; $i--)
                                        <option value="{{ $i }}" {{ request('tahun', $tahun) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase ml-1">Bulan</label>
                                <select name="bulan" class="tom-select w-40 px-3 py-1.5 rounded-xl border-white/10 text-xs font-bold">
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ request('bulan', $bulan) == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="h-8 w-[1px] bg-slate-200 mx-1 self-end mb-1.5"></div>

                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase ml-1">Mulai</label>
                                <input type="date" name="start_date" value="{{ request('start_date', $startDate) }}" 
                                    class="w-36 px-3 py-1.5 rounded-xl border-white/10 text-xs font-bold">
                            </div>

                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase ml-1">Sampai</label>
                                <input type="date" name="end_date" value="{{ request('end_date', $endDate) }}" 
                                    class="w-36 px-3 py-1.5 rounded-xl border-white/10 text-xs font-bold">
                            </div>

                            <div class="flex gap-1.5 ml-2">
                                <button type="submit" class="px-4 py-1.5 bg-slate-800 text-white text-xs font-bold rounded-xl hover:bg-slate-900 transition-all active:scale-95">Tampilkan</button>
                                
                                @if(request('start_date') || request('end_date'))
                                    <a href="{{ route('admin.nominatif.index') }}" class="px-4 py-1.5 bg-slate-200 text-slate-400 text-xs font-bold rounded-xl hover:bg-slate-300 transition-all active:scale-95">Reset</a>
                                @endif

                                <a href="{{ route('admin.nominatif.export', ['tahun' => $tahun, 'bulan' => $bulan, 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                                   class="px-4 py-1.5 bg-green-600 text-white text-xs font-bold rounded-xl hover:bg-green-700 flex items-center gap-1.5 transition-all active:scale-95">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Excel
                                </a>
                                <a href="{{ route('admin.nominatif.pdf', ['tahun' => $tahun, 'bulan' => $bulan, 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                                   class="px-4 py-1.5 bg-rose-600 text-white text-xs font-bold rounded-xl hover:bg-rose-700 flex items-center gap-1.5 transition-all active:scale-95">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    PDF
                                </a>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto bg-slate-900 border border-white/5 rounded-xl shadow-sm border border-white/5">
                        <table class="min-w-full divide-y divide-white/10">
                            <thead class="bg-slate-800/50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-400 uppercase w-12">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-400 uppercase w-32">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-400 uppercase">Satker (Uraian)</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-400 uppercase">Pertamax (L)</th>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Pertamina Dex (L)</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Satuan</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-slate-900 border border-white/5 divide-y divide-white/10">
                                @php $total_p = 0; $total_d = 0; @endphp
                                @forelse($logs as $index => $log)
                                    @php 
                                        $total_p += $log->total_pertamax; 
                                        $total_d += $log->total_dex; 
                                    @endphp
                                    <tr class="hover:bg-slate-800/50 transition-colors">
                                        <td class="px-4 py-3 text-gray-400 font-bold text-xs">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 text-xs font-bold text-slate-400">
                                            {{ isset($log->tanggal) ? \Carbon\Carbon::parse($log->tanggal)->format('d-m-Y') : '-' }}
                                        </td>
                                        <td class="px-4 py-3 font-bold text-slate-200">{{ ucwords(strtolower($log->satker->nama_satker)) }}</td>
                                        <td class="px-4 py-3 text-right font-black text-indigo-600">{{ number_format($log->total_pertamax, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right font-black text-rose-600">{{ number_format($log->total_dex, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-center text-xs text-gray-400">Liter</td>
                                        <td class="px-4 py-3 text-center">
                                            <form action="{{ route('admin.nominatif.destroy-group') }}" method="POST" class="inline-block delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="satker_id" value="{{ $log->satker_id }}">
                                                <input type="hidden" name="tanggal" value="{{ $log->tanggal }}">
                                                <button type="button" onclick="confirmDelete(event)"
                                                    class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors group"
                                                    title="Hapus Data">
                                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-10 text-center text-slate-400">Tidak ada data untuk periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if(count($logs) > 0)
                            <tfoot class="bg-slate-800/50 font-bold border-t-2 border-white/10">
                                <tr>
                                    <td class="px-4 py-3 text-center" colspan="3">JUMLAH</td>
                                    <td class="px-4 py-3 text-right font-black text-indigo-700 leading-none">{{ number_format($total_p, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right font-black text-rose-700 leading-none">{{ number_format($total_d, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3"></td>
                                    <td class="px-4 py-3"></td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(e) {
            const form = e.target.closest('form');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Seluruh data transaksi Satker ini pada tanggal tersebut akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    container: 'font-sans',
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl font-bold px-6 py-3',
                    cancelButton: 'rounded-xl font-bold px-6 py-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        }
    </script>
    @endpush
</x-app-layout>
