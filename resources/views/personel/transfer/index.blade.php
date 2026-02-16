<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transfer Saldo') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Saldo Card with Gradient -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-700 p-8 shadow-xl text-white">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center sm:items-start md:items-center gap-4">
                    <div>
                        <p class="text-indigo-100 font-medium text-lg">Saldo Anda Saat Ini</p>
                        <h3 class="text-4xl sm:text-5xl font-bold mt-2">{{ number_format($personel->saldo, 0, ',', '.') }} Liter</h3>
                        <p class="mt-2 text-indigo-200 text-sm flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Jenis BBM: <span class="font-semibold ml-1">{{ $personel->jenis_bbm }}</span>
                        </p>
                    </div>
                    <div class="p-3 bg-white/10 rounded-full backdrop-blur-sm border border-white/20">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                </div>
                <!-- Decorative Circles -->
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-indigo-500/30 rounded-full blur-3xl"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Form Transfer -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden sticky top-8">
                        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-lg font-bold text-slate-800 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                Form Transfer
                            </h3>
                            <p class="text-sm text-slate-500 mt-1">Kirim saldo ke rekan satu Satker.</p>
                        </div>

                        <div class="p-6">
                            <form method="post" action="{{ route('personel.transfer.store') }}" class="space-y-5">
                                @csrf

                                <div>
                                    <x-input-label for="receiver_id" :value="__('Penerima')" class="text-slate-700" />
                                    <div class="relative mt-1">
                                        <select id="receiver_id" name="receiver_id" class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm transition-all duration-200">
                                            <option value="">-- Pilih Rekan --</option>
                                            @foreach($personels as $p)
                                                <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->nrp }})</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                    </div>
                                    <p class="mt-1.5 text-xs text-slate-500 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Hanya rekan dengan BBM: {{ $personel->jenis_bbm }}
                                    </p>
                                    <x-input-error :messages="$errors->get('receiver_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="jumlah" :value="__('Jumlah Transfer')" class="text-slate-700 font-medium" />
                                    <div class="relative mt-2">
                                        <input id="jumlah" name="jumlah" type="number" class="w-full pl-4 pr-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-slate-800 font-bold text-lg shadow-sm transition-all duration-200" value="{{ old('jumlah') }}" required min="1" placeholder="0" autocomplete="off">
                                    </div>
                                    <x-input-error :messages="$errors->get('jumlah')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="pin" :value="__('PIN Keamanan')" class="text-slate-700 font-medium" />
                                    <div class="relative mt-2">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        </div>
                                        <input id="pin" name="pin" type="password" class="w-full pl-12 pr-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-slate-800 font-bold text-lg shadow-sm transition-all duration-200 tracking-[0.5em]" required autocomplete="new-password" value="" placeholder="••••••">
                                    </div>
                                    <x-input-error :messages="$errors->get('pin')" class="mt-2" />
                                </div>

                                <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-indigo-500/30 transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                    {{ __('Kirim Saldo Sekarang') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Transfer -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden h-full">
                        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-slate-800 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Riwayat Transaksi
                            </h3>
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-medium">Terbaru</span>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-slate-200">
                                        <th class="p-4">Waktu</th>
                                        <th class="p-4">Jenis</th>
                                        <th class="p-4">Lawan Transaksi</th>
                                        <th class="p-4 text-right">Jumlah</th>
                                        <th class="p-4">Ket</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($riwayat as $item)
                                        <tr class="hover:bg-slate-50/80 transition-colors duration-150">
                                            <td class="p-4 whitespace-nowrap">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-semibold text-slate-700">{{ $item->created_at->format('d M Y') }}</span>
                                                    <span class="text-xs text-slate-400">{{ $item->created_at->format('H:i') }} WIB</span>
                                                </div>
                                            </td>
                                            <td class="p-4 whitespace-nowrap">
                                                @if($item->sender_id == $personel->id)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                                        Keluar
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                         <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                                        Masuk
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="p-4 whitespace-nowrap">
                                                 @if($item->sender_id == $personel->id)
                                                    <div class="text-sm font-medium text-slate-900">{{ $item->receiver->nama ?? 'Tidak Diketahui' }}</div>
                                                    <div class="text-xs text-slate-500">Penerima</div>
                                                @else
                                                     <div class="text-sm font-medium text-slate-900">{{ $item->sender->nama ?? 'Tidak Diketahui' }}</div>
                                                     <div class="text-xs text-slate-500">Pengirim</div>
                                                @endif
                                            </td>
                                            <td class="p-4 whitespace-nowrap text-right">
                                                @if($item->sender_id == $personel->id)
                                                    <span class="text-sm font-bold text-rose-600">- {{ number_format($item->jumlah, 0, ',', '.') }} Liter</span>
                                                @else
                                                    <span class="text-sm font-bold text-emerald-600">+ {{ number_format($item->jumlah, 0, ',', '.') }} Liter</span>
                                                @endif
                                            </td>
                                            <td class="p-4 text-sm text-slate-500 truncate max-w-xs">
                                                {{ $item->keterangan ?? '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="p-8 text-center">
                                                <div class="flex flex-col items-center justify-center text-slate-400">
                                                    <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    <span class="text-base font-medium">Belum ada riwayat transaksi</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($riwayat->hasPages())
                            <div class="p-4 border-t border-slate-100 bg-slate-50">
                                {{ $riwayat->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
