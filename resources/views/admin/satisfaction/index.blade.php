<x-app-layout>
    <div class="p-6 lg:p-8 space-y-8">
        <!-- Page Title -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Indeks Kepuasan</h1>
                <p class="mt-1 text-slate-500">Laporan kepuasan pelayanan dari Satker dan Personel.</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200/70 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Total Responden</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-2xl border border-slate-200/70 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-yellow-50 text-yellow-600 rounded-xl">
                        <span class="text-2xl">🤩</span>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Sangat Puas</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $stats['sangat_puas'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/70 shadow-sm">
                 <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                        <span class="text-2xl">🙂</span>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Puas</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $stats['puas'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/70 shadow-sm">
                 <div class="flex items-center gap-4">
                    <div class="p-3 bg-red-50 text-red-600 rounded-xl">
                        <span class="text-2xl">😡</span>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Tidak Puas</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $stats['tidak_puas'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">Riwayat Penilaian</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Rating</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($ratings as $rating)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $rating->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-xs mr-3">
                                        {{ substr($rating->user->name, 0, 1) }}
                                    </div>
                                    <div class="text-sm font-medium text-slate-900">{{ $rating->user->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-100 text-slate-800">
                                    {{ $rating->user->role_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($rating->rating == '3')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        🤩 Sangat Puas
                                    </span>
                                @elseif($rating->rating == '2')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        🙂 Puas
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        😡 Tidak Puas
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500 max-w-xs truncate" title="{{ $rating->note }}">
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
            <div class="p-4 border-t border-slate-100">
                {{ $ratings->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
