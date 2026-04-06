<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold">Daftar Nominatif Berita Acara</h2>
                            <p class="text-gray-600">Laporan nominatif penerimaan BBM per Satker.</p>
                        </div>
                    </div>

                    <!-- Pihak Kesatu Configuration Form -->
                    <div class="mb-8 p-6 bg-slate-50 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="font-bold text-slate-800 flex items-center gap-2 mb-4">
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
                                    <label class="text-xs font-bold text-slate-500 uppercase">Nama</label>
                                    <input type="text" name="nominatif_nama"
                                        value="{{ $settingsData['nominatif_nama'] ?? '' }}"
                                        class="w-full px-4 py-2 rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                        placeholder="Tulis Nama Lengkap">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-slate-500 uppercase">Pangkat</label>
                                    <input type="text" name="nominatif_pangkat"
                                        value="{{ $settingsData['nominatif_pangkat'] ?? '' }}"
                                        class="w-full px-4 py-2 rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                        placeholder="Ketik Pangkat">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-slate-500 uppercase">NRP</label>
                                    <input type="text" name="nominatif_nrp"
                                        value="{{ $settingsData['nominatif_nrp'] ?? '' }}"
                                        class="w-full px-4 py-2 rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                        placeholder="No NRP">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-slate-500 uppercase">Jabatan</label>
                                    <input type="text" name="nominatif_jabatan"
                                        value="{{ $settingsData['nominatif_jabatan'] ?? '' }}"
                                        class="w-full px-4 py-2 rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
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

                    <div class="mb-8 p-6 bg-slate-50 rounded-2xl border border-slate-200 shadow-sm">
                        <form action="{{ route('admin.nominatif.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-slate-500 uppercase">Tahun</label>
                                <select name="tahun" class="w-40 px-4 py-2 rounded-xl border-slate-200">
                                    @php $currentYear = date('Y'); @endphp
                                    @for($i = $currentYear; $i >= $currentYear - 5; $i--)
                                        <option value="{{ $i }}" {{ request('tahun', $tahun) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-slate-500 uppercase">Bulan</label>
                                <select name="bulan" class="w-48 px-4 py-2 rounded-xl border-slate-200">
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ request('bulan', $bulan) == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="px-5 py-2 bg-slate-600 text-white text-sm font-bold rounded-xl hover:bg-slate-700">Tampilkan</button>
                                <a href="{{ route('admin.nominatif.export', ['tahun' => $tahun, 'bulan' => $bulan]) }}" 
                                   class="px-5 py-2 bg-green-600 text-white text-sm font-bold rounded-xl hover:bg-green-700 flex items-center gap-2">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Export Excel
                                </a>
                                <a href="{{ route('admin.nominatif.pdf', ['tahun' => $tahun, 'bulan' => $bulan]) }}" 
                                   class="px-5 py-2 bg-rose-600 text-white text-sm font-bold rounded-xl hover:bg-rose-700 flex items-center gap-2">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Export PDF
                                </a>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-100">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Satker (Uraian)</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Pertamax (L)</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Pertamina Dex (L)</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Satuan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php $total_p = 0; $total_d = 0; @endphp
                                @forelse($logs as $index => $log)
                                    @php 
                                        $total_p += $log->total_pertamax; 
                                        $total_d += $log->total_dex; 
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 font-medium">{{ ucwords(strtolower($log->satker->nama_satker)) }}</td>
                                        <td class="px-6 py-4 text-right">{{ number_format($log->total_pertamax, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-right">{{ number_format($log->total_dex, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-center">Liter</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">Tidak ada data untuk periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if(count($logs) > 0)
                            <tfoot class="bg-slate-50 font-bold border-t-2 border-slate-200">
                                <tr>
                                    <td class="px-6 py-4 text-center" colspan="2">JUMLAH</td>
                                    <td class="px-6 py-4 text-right">{{ number_format($total_p, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right">{{ number_format($total_d, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4"></td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
