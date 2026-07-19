<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-8">
        <!-- Page Title -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl sm:text-2xl font-bold text-white leading-tight">Indeks Kepuasan</h1>
                <p class="mt-1 text-xs text-slate-400">Laporan kepuasan pelayanan dari Satker dan Personel.</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <div class="bg-slate-900 border border-white/5 p-4 sm:p-6 rounded-2xl border border-white/10/70 shadow-sm">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="p-2 sm:p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] sm:text-xs text-slate-400">Total Responden</p>
                        <p class="text-xl sm:text-2xl font-bold text-white">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 border border-white/5 p-4 sm:p-6 rounded-2xl border border-white/10/70 shadow-sm">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="p-2 sm:p-3 bg-yellow-50 text-yellow-600 rounded-xl">
                        <span class="text-xl sm:text-2xl">🤩</span>
                    </div>
                    <div>
                        <p class="text-[10px] sm:text-xs text-slate-400">Sangat Puas</p>
                        <p class="text-xl sm:text-2xl font-bold text-white">{{ $stats['sangat_puas'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 border border-white/5 p-4 sm:p-6 rounded-2xl border border-white/10/70 shadow-sm">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="p-2 sm:p-3 bg-green-50 text-green-600 rounded-xl">
                        <span class="text-xl sm:text-2xl">🙂</span>
                    </div>
                    <div>
                        <p class="text-[10px] sm:text-xs text-slate-400">Puas</p>
                        <p class="text-xl sm:text-2xl font-bold text-white">{{ $stats['puas'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 border border-white/5 p-4 sm:p-6 rounded-2xl border border-white/10/70 shadow-sm">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="p-2 sm:p-3 bg-red-50 text-red-600 rounded-xl">
                        <span class="text-xl sm:text-2xl">😡</span>
                    </div>
                    <div>
                        <p class="text-[10px] sm:text-xs text-slate-400">Tidak Puas</p>
                        <p class="text-xl sm:text-2xl font-bold text-white">{{ $stats['tidak_puas'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-slate-900 rounded-2xl border border-white/10 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-white/5 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-200">Riwayat Penilaian</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/5">
                    <thead class="bg-slate-800/50">
                        <tr class="border-b border-white/5">
                            <th colspan="5" class="px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <form action="{{ route('admin.satisfaction.index') }}" method="GET"
                                        class="flex items-center">
                                        <x-per-page :current="request('per_page', 15)" />
                                    </form>
                                    <div class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">
                                        Menampilkan {{ $ratings->firstItem() ?? 0 }}-{{ $ratings->lastItem() ?? 0 }}
                                        dari {{ $ratings->total() }} data
                                    </div>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                Tanggal</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                User</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                Role</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                Rating</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 bg-slate-900 border border-white/5">
                        @forelse($ratings as $rating)
                            <tr class="hover:bg-slate-800/50 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-slate-400">
                                    {{ $rating->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="h-8 w-8 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 font-bold text-xs mr-3">
                                            {{ substr($rating->user->name, 0, 1) }}
                                        </div>
                                        <div class="text-sm font-medium text-white">{{ $rating->user->name }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-800 text-slate-200">
                                        {{ $rating->user->role_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    @if($rating->rating == '3')
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-yellow-100 text-yellow-800">
                                            🤩 Sangat Puas
                                        </span>
                                    @elseif($rating->rating == '2')
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-green-100 text-green-800">
                                            🙂 Puas
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-red-100 text-red-800">
                                            😡 Tidak Puas
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-400 max-w-xs truncate" title="{{ $rating->note }}">
                                    {{ $rating->note ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                    Belum ada data penilaian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-white/5">
                {{ $ratings->links() }}
            </div>
        </div>
    </div>
</x-app-layout>