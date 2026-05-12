<x-app-layout>
<div class="py-8 px-4 sm:px-6 lg:px-8 bg-gray-50/30 min-h-screen">
    <!-- Clean & Professional Header -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Laporan Rutin</h1>
                <p class="mt-1 text-sm text-gray-500 font-medium italic">Rekapitulasi persediaan, penerimaan, dan pengeluaran BBM {{ $jenisLaporan == 'harian' ? 'Per Hari (Harian)' : 'Per Minggu (Bulanan)' }}.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <!-- PDF Export Button -->
                <a href="{{ route('admin.laporan-slog.print', ['bulan' => $bulan, 'tahun' => $tahun, 'jenis_laporan' => $jenisLaporan]) }}" target="_blank" class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-xl transition-all duration-200 shadow-sm font-bold text-xs uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Cetak PDF
                </a>

                <form action="{{ route('admin.laporan-slog.index') }}" method="GET" 
                    x-data="{ 
                        jenis: '{{ $jenisLaporan }}', 
                        bulan: '{{ $bulan }}', 
                        tahun: '{{ $tahun }}',
                        jenisLabel: '{{ $jenisLaporan == 'harian' ? 'Harian' : 'Bulanan' }}',
                        bulanLabel: '{{ Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F') }}'
                    }" 
                    class="flex items-center gap-2 bg-white p-1 rounded-2xl shadow-sm border border-gray-200 ring-1 ring-black/5">
                    
                    <input type="hidden" name="jenis_laporan" x-model="jenis">
                    <input type="hidden" name="bulan" x-model="bulan">
                    <input type="hidden" name="tahun" x-model="tahun">

                    <!-- Jenis Laporan Dropdown -->
                    <div class="relative px-2 border-r border-gray-100" x-data="{ open: false }">
                        <button type="button" @click="open = !open" @click.away="open = false" 
                            class="flex items-center gap-2 px-3 py-2 text-xs font-bold text-indigo-700 hover:bg-indigo-50 rounded-xl transition-all">
                            <span x-text="jenisLabel"></span>
                            <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            class="absolute left-0 mt-2 w-32 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden py-1">
                            <button type="button" @click="jenis = 'harian'; jenisLabel = 'Harian'; open = false" class="w-full text-left px-4 py-2 text-xs font-medium hover:bg-indigo-50 text-gray-700 transition-colors">Harian</button>
                            <button type="button" @click="jenis = 'bulanan'; jenisLabel = 'Bulanan'; open = false" class="w-full text-left px-4 py-2 text-xs font-medium hover:bg-indigo-50 text-gray-700 transition-colors">Bulanan</button>
                        </div>
                    </div>

                    <!-- Bulan Dropdown -->
                    <div class="relative px-2 border-r border-gray-100" x-data="{ open: false }">
                        <button type="button" @click="open = !open" @click.away="open = false" 
                            class="flex items-center gap-2 px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-50 rounded-xl transition-all">
                            <span x-text="bulanLabel"></span>
                            <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            class="absolute left-0 mt-2 w-40 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden max-h-60 overflow-y-auto py-1 custom-scrollbar">
                            @foreach(range(1, 12) as $m)
                                @php $mStr = sprintf('%02d', $m); $mLabel = Carbon\Carbon::create()->month((int)$m)->translatedFormat('F'); @endphp
                                <button type="button" @click="bulan = '{{ $mStr }}'; bulanLabel = '{{ $mLabel }}'; open = false" 
                                    class="w-full text-left px-4 py-2 text-xs font-medium hover:bg-gray-50 transition-colors"
                                    :class="bulan === '{{ $mStr }}' ? 'text-indigo-600 bg-indigo-50/50' : 'text-gray-700'">
                                    {{ $mLabel }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tahun Dropdown -->
                    <div class="relative px-2" x-data="{ open: false }">
                        <button type="button" @click="open = !open" @click.away="open = false" 
                            class="flex items-center gap-2 px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-50 rounded-xl transition-all">
                            <span x-text="tahun"></span>
                            <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            class="absolute left-0 mt-2 w-24 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden py-1">
                            @foreach(range(date('Y')-2, date('Y')) as $y)
                                <button type="button" @click="tahun = '{{ $y }}'; open = false" 
                                    class="w-full text-left px-4 py-2 text-xs font-medium hover:bg-gray-50 transition-colors"
                                    :class="tahun === '{{ $y }}' ? 'text-indigo-600 bg-indigo-50/50' : 'text-gray-700'">
                                    {{ $y }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl transition-all duration-200 font-bold text-xs uppercase tracking-wider ml-2 shadow-sm active:scale-95">
                        Filter
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modern Data Grid Table -->
    <div class="max-w-7xl mx-auto space-y-8">
        @if($jenisLaporan == 'bulanan')
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden ring-1 ring-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gray-50/80">
                                <th rowspan="2" class="px-4 py-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest border-r border-gray-100 w-12">No</th>
                                <th rowspan="2" class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest border-r border-gray-100">Tanggal</th>
                                <th rowspan="2" class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest border-r border-gray-100">Uraian</th>
                                <th colspan="2" class="px-4 py-4 text-center text-[10px] font-bold text-indigo-600 uppercase tracking-widest border-r border-gray-100 bg-indigo-50/30">Jenis dan Kuantum BMP</th>
                                <th rowspan="2" class="px-4 py-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest border-r border-gray-100">Satuan</th>
                            </tr>
                            <tr class="bg-gray-50/80">
                                <th class="px-4 py-2 text-center text-[10px] font-bold text-indigo-600 uppercase tracking-widest border-r border-gray-100 bg-indigo-50/30">Pertamax</th>
                                <th class="px-4 py-2 text-center text-[10px] font-bold text-indigo-600 uppercase tracking-widest border-r border-gray-100 bg-indigo-50/30">Pertamina Dex</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @php $i = 1; @endphp
                            @foreach($data['weeks'] as $weekName => $weekData)
                                <!-- Persediaan Awal -->
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td rowspan="5" class="px-4 py-4 text-[11px] font-bold text-center text-gray-400 border-r border-gray-100 align-top">{{ $i++ }}.</td>
                                    <td rowspan="5" class="px-6 py-4 text-sm font-bold text-gray-900 border-r border-gray-100 align-top whitespace-nowrap">{{ $weekName }}</td>
                                    <td class="px-6 py-2 text-xs font-semibold text-gray-600 border-r border-gray-100">Persediaan awal</td>
                                    <td class="px-4 py-2 text-xs text-center text-gray-700 border-r border-gray-100">{{ number_format($weekData['awal_pertamax'], 0, ',', '.') ?: '-' }}</td>
                                    <td class="px-4 py-2 text-xs text-center text-gray-700 border-r border-gray-100">{{ number_format($weekData['awal_dex'], 0, ',', '.') ?: '-' }}</td>
                                    <td class="px-4 py-2 text-xs text-center text-gray-500">Liter</td>
                                </tr>
                                <!-- Penerimaan -->
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-2 text-xs font-semibold text-emerald-600 border-r border-gray-100">Penerimaan</td>
                                    <td class="px-4 py-2 text-xs text-center text-emerald-700 font-bold border-r border-gray-100">{{ number_format($weekData['terima_pertamax'], 0, ',', '.') ?: '-' }}</td>
                                    <td class="px-4 py-2 text-xs text-center text-emerald-700 font-bold border-r border-gray-100">{{ number_format($weekData['terima_dex'], 0, ',', '.') ?: '-' }}</td>
                                    <td class="px-4 py-2 text-xs text-center text-gray-500">Liter</td>
                                </tr>
                                <!-- Jumlah -->
                                <tr class="hover:bg-gray-50/50 transition-colors bg-indigo-50/10">
                                    <td class="px-6 py-2 text-xs font-bold text-indigo-700 border-r border-gray-100">Jumlah</td>
                                    <td class="px-4 py-2 text-xs text-center text-indigo-700 font-bold border-r border-gray-100">{{ number_format($weekData['jumlah_pertamax'], 0, ',', '.') ?: '-' }}</td>
                                    <td class="px-4 py-2 text-xs text-center text-indigo-700 font-bold border-r border-gray-100">{{ number_format($weekData['jumlah_dex'], 0, ',', '.') ?: '-' }}</td>
                                    <td class="px-4 py-2 text-xs text-center text-gray-500">Liter</td>
                                </tr>
                                <!-- Pengeluaran -->
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-2 text-xs font-semibold text-rose-600 border-r border-gray-100">Pengeluaran</td>
                                    <td class="px-4 py-2 text-xs text-center text-rose-700 font-bold border-r border-gray-100">{{ number_format($weekData['keluar_pertamax'], 0, ',', '.') ?: '-' }}</td>
                                    <td class="px-4 py-2 text-xs text-center text-rose-700 font-bold border-r border-gray-100">{{ number_format($weekData['keluar_dex'], 0, ',', '.') ?: '-' }}</td>
                                    <td class="px-4 py-2 text-xs text-center text-gray-500">Liter</td>
                                </tr>
                                <!-- Persediaan Akhir -->
                                <tr class="hover:bg-gray-50/50 transition-colors bg-gray-50">
                                    <td class="px-6 py-2 text-xs font-bold text-gray-800 border-r border-gray-100">Persediaan akhir</td>
                                    <td class="px-4 py-2 text-xs text-center text-gray-800 font-black border-r border-gray-100">{{ number_format($weekData['akhir_pertamax'], 0, ',', '.') ?: '-' }}</td>
                                    <td class="px-4 py-2 text-xs text-center text-gray-800 font-black border-r border-gray-100">{{ number_format($weekData['akhir_dex'], 0, ',', '.') ?: '-' }}</td>
                                    <td class="px-4 py-2 text-xs text-center text-gray-500">Liter</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-indigo-50/30 uppercase tracking-widest border-t-2 border-indigo-100">
                            <!-- Rekapitulasi -->
                            <tr class="font-bold">
                                <td rowspan="5" colspan="2" class="px-6 py-4 text-right text-[11px] text-indigo-700 border-r border-indigo-100 align-top">Rekapitulasi :</td>
                                <td class="px-6 py-3 text-xs text-gray-600 border-r border-indigo-100">Persediaan awal</td>
                                <td class="px-4 py-3 text-center text-xs text-gray-700 border-r border-indigo-100">{{ number_format($data['rekap']['awal_pertamax'], 0, ',', '.') ?: '-' }}</td>
                                <td class="px-4 py-3 text-center text-xs text-gray-700 border-r border-indigo-100">{{ number_format($data['rekap']['awal_dex'], 0, ',', '.') ?: '-' }}</td>
                                <td class="px-4 py-3 text-center text-xs text-gray-500">Liter</td>
                            </tr>
                            <tr class="font-bold">
                                <td class="px-6 py-3 text-xs text-emerald-600 border-r border-indigo-100">Penerimaan</td>
                                <td class="px-4 py-3 text-center text-xs text-emerald-700 border-r border-indigo-100">{{ number_format($data['rekap']['terima_pertamax'], 0, ',', '.') ?: '-' }}</td>
                                <td class="px-4 py-3 text-center text-xs text-emerald-700 border-r border-indigo-100">{{ number_format($data['rekap']['terima_dex'], 0, ',', '.') ?: '-' }}</td>
                                <td class="px-4 py-3 text-center text-xs text-gray-500">Liter</td>
                            </tr>
                            <tr class="font-bold bg-indigo-100/50">
                                <td class="px-6 py-3 text-xs text-indigo-800 border-r border-indigo-100">Jumlah</td>
                                <td class="px-4 py-3 text-center text-xs text-indigo-800 border-r border-indigo-100">{{ number_format($data['rekap']['jumlah_pertamax'], 0, ',', '.') ?: '-' }}</td>
                                <td class="px-4 py-3 text-center text-xs text-indigo-800 border-r border-indigo-100">{{ number_format($data['rekap']['jumlah_dex'], 0, ',', '.') ?: '-' }}</td>
                                <td class="px-4 py-3 text-center text-xs text-gray-500">Liter</td>
                            </tr>
                            <tr class="font-bold">
                                <td class="px-6 py-3 text-xs text-rose-600 border-r border-indigo-100">Pengeluaran</td>
                                <td class="px-4 py-3 text-center text-xs text-rose-700 border-r border-indigo-100">{{ number_format($data['rekap']['keluar_pertamax'], 0, ',', '.') ?: '-' }}</td>
                                <td class="px-4 py-3 text-center text-xs text-rose-700 border-r border-indigo-100">{{ number_format($data['rekap']['keluar_dex'], 0, ',', '.') ?: '-' }}</td>
                                <td class="px-4 py-3 text-center text-xs text-gray-500">Liter</td>
                            </tr>
                            <tr class="font-black bg-gray-100">
                                <td class="px-6 py-3 text-xs text-gray-900 border-r border-indigo-100">Persediaan akhir</td>
                                <td class="px-4 py-3 text-center text-xs text-gray-900 border-r border-indigo-100">{{ number_format($data['rekap']['akhir_pertamax'], 0, ',', '.') ?: '-' }}</td>
                                <td class="px-4 py-3 text-center text-xs text-gray-900 border-r border-indigo-100">{{ number_format($data['rekap']['akhir_dex'], 0, ',', '.') ?: '-' }}</td>
                                <td class="px-4 py-3 text-center text-xs text-gray-500">Liter</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @else
            @foreach($data as $weekName => $weekData)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden ring-1 ring-gray-100 mb-6">
                    <div class="bg-gray-50/80 px-6 py-3 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-gray-800 uppercase tracking-widest">{{ $weekName }}</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead>
                                <tr class="bg-gray-50/80">
                                    <th rowspan="2" class="px-4 py-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest border-r border-gray-100 w-12">No</th>
                                    <th rowspan="2" class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest border-r border-gray-100">Tanggal</th>
                                    <th rowspan="2" class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest border-r border-gray-100">Uraian</th>
                                    <th colspan="2" class="px-4 py-4 text-center text-[10px] font-bold text-indigo-600 uppercase tracking-widest border-r border-gray-100 bg-indigo-50/30">Jenis dan Kuantum BMP</th>
                                    <th rowspan="2" class="px-4 py-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest border-r border-gray-100">Satuan</th>
                                </tr>
                                <tr class="bg-gray-50/80">
                                    <th class="px-4 py-2 text-center text-[10px] font-bold text-indigo-600 uppercase tracking-widest border-r border-gray-100 bg-indigo-50/30">Pertamax</th>
                                    <th class="px-4 py-2 text-center text-[10px] font-bold text-indigo-600 uppercase tracking-widest border-r border-gray-100 bg-indigo-50/30">Pertamina Dex</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @php $i = 1; @endphp
                                @foreach($weekData['days'] as $day)
                                    <!-- Persediaan Awal -->
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td rowspan="5" class="px-4 py-4 text-[11px] font-bold text-center text-gray-400 border-r border-gray-100 align-top">{{ $i++ }}.</td>
                                        <td rowspan="5" class="px-6 py-4 text-sm font-bold text-gray-900 border-r border-gray-100 align-top whitespace-nowrap">{{ $day['nama_hari'] }}</td>
                                        <td class="px-6 py-2 text-xs font-semibold text-gray-600 border-r border-gray-100">Persediaan awal</td>
                                        <td class="px-4 py-2 text-xs text-center text-gray-700 border-r border-gray-100">{{ number_format($day['awal_pertamax'], 0, ',', '.') ?: '-' }}</td>
                                        <td class="px-4 py-2 text-xs text-center text-gray-700 border-r border-gray-100">{{ number_format($day['awal_dex'], 0, ',', '.') ?: '-' }}</td>
                                        <td class="px-4 py-2 text-xs text-center text-gray-500">Liter</td>
                                    </tr>
                                    <!-- Penerimaan -->
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-2 text-xs font-semibold text-emerald-600 border-r border-gray-100">Penerimaan</td>
                                        <td class="px-4 py-2 text-xs text-center text-emerald-700 font-bold border-r border-gray-100">{{ number_format($day['terima_pertamax'], 0, ',', '.') ?: '-' }}</td>
                                        <td class="px-4 py-2 text-xs text-center text-emerald-700 font-bold border-r border-gray-100">{{ number_format($day['terima_dex'], 0, ',', '.') ?: '-' }}</td>
                                        <td class="px-4 py-2 text-xs text-center text-gray-500">Liter</td>
                                    </tr>
                                    <!-- Jumlah -->
                                    <tr class="hover:bg-gray-50/50 transition-colors bg-indigo-50/10">
                                        <td class="px-6 py-2 text-xs font-bold text-indigo-700 border-r border-gray-100">Jumlah</td>
                                        <td class="px-4 py-2 text-xs text-center text-indigo-700 font-bold border-r border-gray-100">{{ number_format($day['jumlah_pertamax'], 0, ',', '.') ?: '-' }}</td>
                                        <td class="px-4 py-2 text-xs text-center text-indigo-700 font-bold border-r border-gray-100">{{ number_format($day['jumlah_dex'], 0, ',', '.') ?: '-' }}</td>
                                        <td class="px-4 py-2 text-xs text-center text-gray-500">Liter</td>
                                    </tr>
                                    <!-- Pengeluaran -->
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-2 text-xs font-semibold text-rose-600 border-r border-gray-100">Pengeluaran</td>
                                        <td class="px-4 py-2 text-xs text-center text-rose-700 font-bold border-r border-gray-100">{{ number_format($day['keluar_pertamax'], 0, ',', '.') ?: '-' }}</td>
                                        <td class="px-4 py-2 text-xs text-center text-rose-700 font-bold border-r border-gray-100">{{ number_format($day['keluar_dex'], 0, ',', '.') ?: '-' }}</td>
                                        <td class="px-4 py-2 text-xs text-center text-gray-500">Liter</td>
                                    </tr>
                                    <!-- Persediaan Akhir -->
                                    <tr class="hover:bg-gray-50/50 transition-colors bg-gray-50">
                                        <td class="px-6 py-2 text-xs font-bold text-gray-800 border-r border-gray-100">Persediaan akhir</td>
                                        <td class="px-4 py-2 text-xs text-center text-gray-800 font-black border-r border-gray-100">{{ number_format($day['akhir_pertamax'], 0, ',', '.') ?: '-' }}</td>
                                        <td class="px-4 py-2 text-xs text-center text-gray-800 font-black border-r border-gray-100">{{ number_format($day['akhir_dex'], 0, ',', '.') ?: '-' }}</td>
                                        <td class="px-4 py-2 text-xs text-center text-gray-500">Liter</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-indigo-50/30 uppercase tracking-widest border-t-2 border-indigo-100">
                                <!-- Rekapitulasi -->
                                <tr class="font-bold">
                                    <td rowspan="5" colspan="2" class="px-6 py-4 text-right text-[11px] text-indigo-700 border-r border-indigo-100 align-top">Rekapitulasi :</td>
                                    <td class="px-6 py-3 text-xs text-gray-600 border-r border-indigo-100">Persediaan awal</td>
                                    <td class="px-4 py-3 text-center text-xs text-gray-700 border-r border-indigo-100">{{ number_format($weekData['rekap']['awal_pertamax'], 0, ',', '.') ?: '-' }}</td>
                                    <td class="px-4 py-3 text-center text-xs text-gray-700 border-r border-indigo-100">{{ number_format($weekData['rekap']['awal_dex'], 0, ',', '.') ?: '-' }}</td>
                                    <td class="px-4 py-3 text-center text-xs text-gray-500">Liter</td>
                                </tr>
                                <tr class="font-bold">
                                    <td class="px-6 py-3 text-xs text-emerald-600 border-r border-indigo-100">Penerimaan</td>
                                    <td class="px-4 py-3 text-center text-xs text-emerald-700 border-r border-indigo-100">{{ number_format($weekData['rekap']['terima_pertamax'], 0, ',', '.') ?: '-' }}</td>
                                    <td class="px-4 py-3 text-center text-xs text-emerald-700 border-r border-indigo-100">{{ number_format($weekData['rekap']['terima_dex'], 0, ',', '.') ?: '-' }}</td>
                                    <td class="px-4 py-3 text-center text-xs text-gray-500">Liter</td>
                                </tr>
                                <tr class="font-bold bg-indigo-100/50">
                                    <td class="px-6 py-3 text-xs text-indigo-800 border-r border-indigo-100">Jumlah</td>
                                    <td class="px-4 py-3 text-center text-xs text-indigo-800 border-r border-indigo-100">{{ number_format($weekData['rekap']['jumlah_pertamax'], 0, ',', '.') ?: '-' }}</td>
                                    <td class="px-4 py-3 text-center text-xs text-indigo-800 border-r border-indigo-100">{{ number_format($weekData['rekap']['jumlah_dex'], 0, ',', '.') ?: '-' }}</td>
                                    <td class="px-4 py-3 text-center text-xs text-gray-500">Liter</td>
                                </tr>
                                <tr class="font-bold">
                                    <td class="px-6 py-3 text-xs text-rose-600 border-r border-indigo-100">Pengeluaran</td>
                                    <td class="px-4 py-3 text-center text-xs text-rose-700 border-r border-indigo-100">{{ number_format($weekData['rekap']['keluar_pertamax'], 0, ',', '.') ?: '-' }}</td>
                                    <td class="px-4 py-3 text-center text-xs text-rose-700 border-r border-indigo-100">{{ number_format($weekData['rekap']['keluar_dex'], 0, ',', '.') ?: '-' }}</td>
                                    <td class="px-4 py-3 text-center text-xs text-gray-500">Liter</td>
                                </tr>
                                <tr class="font-black bg-gray-100">
                                    <td class="px-6 py-3 text-xs text-gray-900 border-r border-indigo-100">Persediaan akhir</td>
                                    <td class="px-4 py-3 text-center text-xs text-gray-900 border-r border-indigo-100">{{ number_format($weekData['rekap']['akhir_pertamax'], 0, ',', '.') ?: '-' }}</td>
                                    <td class="px-4 py-3 text-center text-xs text-gray-900 border-r border-indigo-100">{{ number_format($weekData['rekap']['akhir_dex'], 0, ',', '.') ?: '-' }}</td>
                                    <td class="px-4 py-3 text-center text-xs text-gray-500">Liter</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<style>
    /* Clean scrollbar for the table */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>
</x-app-layout>
