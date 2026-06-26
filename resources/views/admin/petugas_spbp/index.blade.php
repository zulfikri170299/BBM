<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-200 leading-tight">
            {{ __('Daftar Petugas SPBP') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-800/50 min-h-screen px-2 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 flex items-center p-4 text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-100 shadow-sm animate-fade-in-down" role="alert">
                    <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="ml-3 text-sm font-bold">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <div class="bg-slate-900 border border-white/5 overflow-hidden shadow-sm sm:rounded-2xl border border-white/10">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                        <div>
                            <h3 class="text-xl font-bold text-slate-200">Petugas SPBP Polda NTB</h3>
                            <p class="text-xs text-slate-400 mt-1">Daftar petugas yang akan muncul di tanda tangan laporan PDF.</p>
                        </div>
                        <a href="{{ route('admin.petugas-spbp.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-indigo-500/30 transition-all active:scale-95 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Petugas
                        </a>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-white/5">
                        <table class="min-w-full divide-y divide-white/5">
                            <thead class="bg-slate-800/50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Urutan</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Nama Petugas</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Pangkat / NRP</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-slate-400 uppercase tracking-widest">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-slate-900 border border-white/5 divide-y divide-slate-50">
                                @forelse($petugas as $p)
                                    <tr class="hover:bg-slate-800/50/30 transition-colors">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-800 text-slate-400 text-xs font-bold">
                                                {{ $p->urutan }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-xs font-semibold text-slate-200">{{ $p->nama }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-xs text-slate-400 font-medium">{{ $p->pangkat_nrp }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                            <div class="flex justify-center items-center gap-2">
                                                <a href="{{ route('admin.petugas-spbp.edit', $p) }}" 
                                                    class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group" 
                                                    title="Edit Petugas">
                                                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                    </svg>
                                                </a>
                                                <form action="{{ route('admin.petugas-spbp.destroy', $p) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus petugas ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                        class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition-all duration-200 group" 
                                                        title="Hapus Petugas">
                                                        <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center gap-2">
                                                <div class="p-3 rounded-full bg-slate-800/50 text-slate-300">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                    </svg>
                                                </div>
                                                <p class="text-xs text-slate-400 font-medium">Belum ada data petugas SPBP.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
