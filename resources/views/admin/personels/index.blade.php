<x-app-layout>
    <div class="py-12">
        <div class="max-w-[95rem] mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Header & Filter -->
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-800 leading-tight">Data Personel</h2>
                            <p class="text-slate-500 text-sm mt-1">Kelola data personel dan saldo BBM.</p>
                        </div>
                        
                        <form action="{{ route('admin.personels.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                            <!-- Filter Satker Custom Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <input type="hidden" name="satker_id" id="satker_id" value="{{ request('satker_id') }}">
                                
                                <button type="button" @click="open = !open" @click.away="open = false" class="flex items-center justify-between w-full sm:w-48 bg-slate-50 border border-slate-300 text-slate-700 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2.5 hover:bg-white transition-colors">
                                    <span class="truncate block mr-2">
                                        @php
                                            $selectedSatker = $satkers->firstWhere('id', request('satker_id'));
                                            echo $selectedSatker ? $selectedSatker->nama_satker : 'Semua Satker';
                                        @endphp
                                    </span>
                                    <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>

                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute z-10 w-full sm:w-64 mt-1 bg-white rounded-xl shadow-lg border border-slate-100 py-1 max-h-60 overflow-auto focus:outline-none"
                                     style="display: none;">
                                    
                                    <div class="px-3 py-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                        Filter Satuan Kerja
                                    </div>
                                    
                                    <button type="button" 
                                            @click="document.getElementById('satker_id').value = ''; $el.closest('form').submit();"
                                            class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-indigo-600 flex items-center justify-between group transition-colors">
                                        <span>Semua Satker</span>
                                        @if(!request('satker_id'))
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        @endif
                                    </button>

                                    @foreach($satkers as $satker)
                                        <button type="button" 
                                                @click="document.getElementById('satker_id').value = '{{ $satker->id }}'; $el.closest('form').submit();"
                                                class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-indigo-600 flex items-center justify-between group transition-colors">
                                            <span class="truncate">{{ $satker->nama_satker }}</span>
                                            @if(request('satker_id') == $satker->id)
                                                <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Search -->
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" class="block w-full sm:w-64 p-2.5 pl-10 text-sm text-slate-900 border border-slate-300 rounded-lg bg-slate-50 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Cari Nama atau NRP...">
                            </div>

                            <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-4 py-2.5 transition-colors">
                                Cari
                            </button>
                            
                            @if(request('search') || request('satker_id'))
                                <a href="{{ route('admin.personels.index') }}" class="text-slate-700 bg-slate-100 hover:bg-slate-200 focus:ring-4 focus:outline-none focus:ring-slate-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center transition-colors">
                                    Reset
                                </a>
                            @endif
                        </form>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif



                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-6 py-4 text-center text-[10px] font-bold text-slate-500 uppercase tracking-widest w-12">No</th>
                                    <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">Data Personel</th>
                                    <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">Satker & BBM</th>
                                    <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">Saldo</th>
                                    <th class="px-6 py-4 text-right text-[10px] font-bold text-slate-500 uppercase tracking-widest">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100">
                                @foreach($personels as $personel)
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-xs font-bold text-slate-400">{{ $loop->iteration + ($personels->currentPage() - 1) * $personels->perPage() }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-sm ring-2 ring-white">
                                                {{ strtoupper(substr($personel->nama, 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $personel->nama }}</p>
                                                <p class="text-[11px] font-medium text-slate-500 mt-0.5">NRP: {{ $personel->nrp }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p class="text-xs font-bold text-slate-700 capitalize mb-1.5">{{ strtolower($personel->satker->nama_satker ?? '-') }}</p>
                                        @php
                                            $bbmColors = [
                                                'Pertalite' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                'Pertamax' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                'Solar' => 'bg-orange-50 text-orange-600 border-orange-100',
                                                'Pertamina Dex' => 'bg-rose-50 text-rose-600 border-rose-100',
                                            ];
                                            $colorClass = $bbmColors[$personel->jenis_bbm] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $colorClass }}">
                                            {{ strtoupper($personel->jenis_bbm ?? '-') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-sm font-black text-slate-900">{{ number_format($personel->saldo, 0, ',', '.') }}</span>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase">Liter</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex justify-end items-center gap-2">
                                            <a href="{{ route('admin.personels.print', $personel) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-lg text-xs font-bold transition-all duration-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                                Print
                                            </a>
                                            <form action="{{ route('admin.personels.destroy', $personel) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-lg text-xs font-bold transition-all duration-200" onclick="return confirm('Hapus data personel {{ $personel->nama }}?')">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $personels->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
