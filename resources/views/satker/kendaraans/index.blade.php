<x-app-layout>
    <div class="p-6 lg:p-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Kendaraan</h1>
                <p class="mt-1 text-slate-500">Kelola armada kendaraan {{ Auth::user()->satker->nama_satker ?? '' }}</p>
            </div>
            <a href="{{ route('satker.kendaraans.create') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold text-sm hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Kendaraan
            </a>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
            <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl" x-data="{ show: true }" x-show="show" x-transition>
                <div class="flex-shrink-0 p-1.5 bg-emerald-100 rounded-full mt-0.5">
                    <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                </div>
                <p class="text-sm font-medium text-emerald-700 flex-1">{{ session('success') }}</p>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        <!-- Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">Daftar Kendaraan</h3>
                        <p class="text-xs text-slate-400">{{ $kendaraans->total() }} kendaraan terdaftar</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-slate-50/70">
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis Kendaraan</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nopol</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis BBM</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Saldo</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">PIN</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Print</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kendaraans as $kendaraan)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-semibold text-slate-500">{{ $loop->iteration + ($kendaraans->currentPage() - 1) * $kendaraans->perPage() }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-slate-800">{{ $kendaraan->jenis_kendaraan }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-slate-800">{{ $kendaraan->no_polisi }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $bbmColors = [
                                        'Pertalite' => 'bg-green-100 text-green-700',
                                        'Pertamax' => 'bg-blue-100 text-blue-700',
                                        'Solar' => 'bg-amber-100 text-amber-700',
                                        'Dexlite' => 'bg-purple-100 text-purple-700',
                                    ];
                                    $color = $bbmColors[$kendaraan->jenis_bbm] ?? 'bg-slate-100 text-slate-700';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $color }}">
                                    {{ $kendaraan->jenis_bbm }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-sm font-bold {{ $kendaraan->saldo < 10 ? 'text-red-600' : 'text-slate-800' }}">
                                    {{ number_format($kendaraan->saldo, 1, ',', '.') }} Liter
                                </span>
                                @if($kendaraan->saldo < 10)
                                    <span class="block text-xs text-red-500 font-medium mt-0.5">Saldo rendah</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <code class="px-2.5 py-1 bg-amber-50 text-amber-700 rounded-lg text-xs font-mono font-bold tracking-widest">{{ $kendaraan->pin }}</code>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('satker.kendaraans.print', $kendaraan) }}" target="_blank" class="inline-flex items-center p-2 bg-slate-100 hover:bg-indigo-100 text-slate-500 hover:text-indigo-600 rounded-lg transition-colors" title="Print Barcode">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <p class="text-slate-500 font-medium">Belum ada kendaraan terdaftar</p>
                                    <a href="{{ route('satker.kendaraans.create') }}" class="mt-3 text-sm font-semibold text-indigo-600 hover:text-indigo-500">Tambah kendaraan pertama →</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($kendaraans->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $kendaraans->links() }}
            </div>
            @endif
        </div>
    </div>

</x-app-layout>
