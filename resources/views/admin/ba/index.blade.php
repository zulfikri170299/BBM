<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold">Riwayat Berita Acara</h2>
                            <p class="text-gray-600">Daftar dokumen yang dibuat otomatis setelah Import Saldo.</p>
                        </div>
                    </div>

                    <!-- Pihak Kesatu Configuration Form -->
                    <div class="mb-8 p-6 bg-slate-50 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="font-bold text-slate-800 flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Konfigurasi Pihak Kesatu (Penandatangan)
                        </h3>
                        <form action="{{ route('admin.ba.update-settings') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-slate-500 uppercase">Nama</label>
                                    <input type="text" name="ba_pihak_1_nama"
                                        value="{{ $settings['ba_pihak_1_nama'] ?? '' }}"
                                        class="w-full px-4 py-2 rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                        placeholder="Nama Lengkap">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-slate-500 uppercase">Pangkat</label>
                                    <input type="text" name="ba_pihak_1_pangkat"
                                        value="{{ $settings['ba_pihak_1_pangkat'] ?? '' }}"
                                        class="w-full px-4 py-2 rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                        placeholder="Pangkat">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-slate-500 uppercase">NRP</label>
                                    <input type="text" name="ba_pihak_1_nrp"
                                        value="{{ $settings['ba_pihak_1_nrp'] ?? '' }}"
                                        class="w-full px-4 py-2 rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                        placeholder="NRP">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-slate-500 uppercase">Jabatan</label>
                                    <input type="text" name="ba_pihak_1_jabatan"
                                        value="{{ $settings['ba_pihak_1_jabatan'] ?? '' }}"
                                        class="w-full px-4 py-2 rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                        placeholder="Jabatan">
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

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-100">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr class="border-b border-slate-100">
                                    <th colspan="7" class="px-6 py-3">
                                        <div class="flex items-center justify-between">
                                            <form action="{{ route('admin.ba.index') }}" method="GET"
                                                class="flex items-center space-x-3">
                                                <x-per-page :current="request('per_page', 15)" />
                                                <select name="tahun" onchange="this.form.submit()" class="text-xs border-slate-300 rounded-lg shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-1.5 pl-3 pr-8">
                                                    @foreach($tahunList as $t)
                                                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>Tahun {{ $t }}</option>
                                                    @endforeach
                                                </select>
                                            </form>
                                            <div
                                                class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">
                                                Menampilkan {{ $logs->firstItem() ?? 0 }}-{{ $logs->lastItem() ?? 0 }}
                                                dari {{ $logs->total() }} data
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Satker</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Periode</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pertamax (L)</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Dex (L)</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Terakhir Diperbarui</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($logs as $index => $log)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $logs->firstItem() + $index }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">{{ $log->satker->nama_satker }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ \Carbon\Carbon::create()->month($log->bulan)->translatedFormat('F') }}
                                                {{ $log->tahun }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ number_format($log->total_pertamax, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ number_format($log->total_dex, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $log->updated_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <a href="{{ route('admin.ba.download-log', $log) }}"
                                                class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-sm" title="Download Word (DOCX)">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                    </path>
                                                </svg>
                                                DOCX
                                            </a>

                                            <a href="{{ route('admin.ba.download-pdf', $log) }}" target="_blank"
                                                class="inline-flex items-center px-3 py-1.5 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition-colors shadow-sm" title="Download PDF">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                PDF
                                            </a>

                                            <!-- Delete -->
                                            <form action="{{ route('admin.ba.destroy', $log) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    data-confirm="Apakah Anda yakin ingin menghapus data Berita Acara ini?"
                                                    data-confirm-type="error"
                                                    class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all"
                                                    title="Hapus Berita Acara">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                                <p>Belum ada riwayat Berita Acara.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>