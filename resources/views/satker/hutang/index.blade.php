<x-app-layout>
    <div class="max-w-7xl mx-auto p-2 sm:p-6 lg:p-8 space-y-6 px-2 sm:px-6 lg:px-8" x-data="{ 
            showModal: false, 
            selectedHutang: null,
            hutangData: { nopol: '', bbm: '', jumlah: 0 },
            selectedKendaraan: '', 
            selectedBbm: '',
            kendaraans: {{ $kendaraans->toJson() }},
            get filteredKendaraans() {
                return this.kendaraans.filter(k => k.jenis_bbm === this.selectedBbm);
            },
            openModal(id, nopol, bbm, jumlah) {
                this.selectedHutang = id;
                this.hutangData = { nopol, bbm, jumlah };
                this.showModal = true;
            }
        }">
        <!-- Page Title & Summary Cards -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="space-y-1">
                <h1 class="text-3xl font-black text-white tracking-tight">Daftar Hutang BBM Satker</h1>
                <div class="h-1.5 w-20 bg-indigo-600 rounded-full"></div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <!-- Hutang Stats Pill Style -->
                @foreach($hutangPerBbm as $jenis => $total)
                    <div
                        class="bg-slate-900 border border-white/5 px-5 py-3 rounded-[1.5rem] border border-slate-50 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-2 h-10 bg-rose-500 rounded-full shadow-sm shadow-rose-200"></div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] mb-1.5">
                                {{ $jenis }}
                            </p>
                            <p class="text-2xl font-black text-rose-600 leading-none tracking-tight">
                                {{ number_format($total, 0, ',', '.') }}
                                <span class="text-sm font-bold text-slate-300 ml-0.5">L</span>
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-slate-900 border border-white/5 p-4 lg:p-6 rounded-[2rem] lg:rounded-[2.5rem] border border-white/5 shadow-xl shadow-slate-200/50">
            <form action="{{ route('satker.hutang.index') }}" method="GET"
                class="flex flex-col lg:flex-row lg:items-end gap-5 lg:gap-3">
                <div class="grid grid-cols-2 gap-3 lg:flex lg:flex-nowrap lg:gap-3 flex-1">
                    <div class="space-y-1.5 lg:flex-1">
                        <label
                            class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">
                            BBM
                        </label>
                        <select name="jenis_bbm"
                            class="tom-select w-full bg-slate-800/50 border-white/5 rounded-2xl focus:bg-slate-900 border border-white/5 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-300 text-xs">
                            <option value="">Semua BBM</option>
                            <option value="Pertamax" {{ request('jenis_bbm') == 'Pertamax' ? 'selected' : '' }}>Pertamax
                            </option>
                            <option value="Pertamina Dex" {{ request('jenis_bbm') == 'Pertamina Dex' ? 'selected' : '' }}>
                                Pertamina Dex</option>
                        </select>
                    </div>
                    <div class="space-y-1.5 lg:flex-1">
                        <label
                            class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">
                            Status
                        </label>
                        <select name="status"
                            class="tom-select w-full bg-slate-800/50 border-white/5 rounded-2xl focus:bg-slate-900 border border-white/5 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-300 text-xs">
                            <option value="">Semua Status</option>
                            <option value="belum_dibayar" {{ request('status') == 'belum_dibayar' ? 'selected' : '' }}>BELUM
                            </option>
                            <option value="sudah_dibayar" {{ request('status') == 'sudah_dibayar' ? 'selected' : '' }}>LUNAS
                            </option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-between lg:justify-start gap-2 lg:mb-0.5 pt-1 lg:pt-0">
                    <button type="submit"
                        class="flex-1 lg:flex-none px-4 py-3.5 lg:px-5 lg:py-3.5 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/20 active:scale-95 text-center">
                        Terapkan
                    </button>
                    <a href="{{ route('satker.hutang.index') }}"
                        class="px-4 py-3 text-slate-400 hover:text-slate-400 font-black text-[10px] uppercase tracking-widest transition-colors">
                        Reset
                    </a>

                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error Form!</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Table Data -->
        <div class="bg-slate-900 border border-white/5 rounded-[2.5rem] border border-white/5 shadow-xl shadow-slate-200/50 overflow-hidden">
            <div class="px-8 py-5 border-b border-slate-50 flex justify-between items-center bg-slate-800/50/30">
                <div class="flex items-center gap-4">
                    <form action="{{ route('satker.hutang.index') }}" method="GET" class="flex items-center gap-3">
                        @foreach(request()->except('per_page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <label
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Limit:</label>
                        <select name="per_page" onchange="this.form.submit()"
                            class="text-xs font-bold border-white/5 bg-slate-900 border border-white/5 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 py-1.5 pl-3 pr-8 transition-all">
                            <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </form>
                </div>
                <div class="text-[10px] text-slate-400 font-black uppercase tracking-widest">
                    {{ $hutangs->firstItem() ?? 0 }}-{{ $hutangs->lastItem() ?? 0 }} / {{ $hutangs->total() }} Data
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="text-[11px] text-slate-400 uppercase tracking-[0.15em] bg-slate-800/50">
                            <th class="px-8 py-4 font-black">Tanggal Bon</th>
                            <th class="px-8 py-4 font-black">Kendaraan</th>
                            <th class="px-8 py-4 font-black text-center">Driver</th>
                            <th class="px-8 py-4 font-black text-center">Jumlah Bon</th>
                            <th class="px-8 py-4 font-black">Status</th>
                            <th class="px-8 py-4 font-black text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($hutangs as $hutang)
                            <tr class="hover:bg-indigo-50/30 transition-colors group">
                                <td class="px-8 py-2 whitespace-nowrap">
                                    <div class="font-bold text-slate-200">
                                        @if($hutang->tanggal_bon)
                                            {{ \Carbon\Carbon::parse($hutang->tanggal_bon)->format('d M Y') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->format('d M Y') }}
                                        @endif
                                    </div>
                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-wider mt-0.5">
                                        @if($hutang->tanggal_bon)
                                            CATATAN MANUAL
                                        @else
                                            {{ \Carbon\Carbon::parse($hutang->created_at)->timezone('Asia/Makassar')->format('H:i') }}
                                            WITA
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-2">
                                    <div>
                                        <div class="font-black text-white tracking-tight">{{ $hutang->nopol }}</div>
                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                            {{ $hutang->jenis_kendaraan }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-2 text-center">
                                    <div class="font-bold text-slate-300">{{ $hutang->nama_driver ?? '-' }}</div>
                                </td>
                                <td class="px-8 py-2 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <span
                                            class="text-sm font-black text-white">{{ number_format($hutang->jumlah_bon, 0, ',', '.') }}
                                            L</span>
                                        <span
                                            class="text-[9px] font-black text-indigo-500 uppercase tracking-widest bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100/50 mt-1">
                                            {{ $hutang->jenis_bbm }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-2">
                                    @if($hutang->status === 'belum_dibayar')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-600 border border-rose-100/50 rounded-full text-[10px] font-black uppercase tracking-widest">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                            BELUM BAYAR
                                        </span>
                                    @else
                                        <div class="flex flex-col">
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100/50 rounded-full text-[10px] font-black uppercase tracking-widest w-fit">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                                LUNAS
                                            </span>
                                            <span class="text-[9px] font-bold text-slate-400 mt-1 pl-1">
                                                Paid:
                                                {{ \Carbon\Carbon::parse($hutang->tanggal_bayar)->timezone('Asia/Makassar')->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-8 py-2 text-right">
                                    @if($hutang->status === 'belum_dibayar')
                                        <button
                                            @click="openModal({{ $hutang->id }}, '{{ $hutang->nopol }}', '{{ $hutang->jenis_bbm }}', {{ $hutang->jumlah_bon }})"
                                            class="inline-flex h-9 items-center justify-center px-5 bg-indigo-600 text-white font-black text-[11px] uppercase tracking-widest rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/20 active:scale-95 mb-0.5">
                                            Bayar Hutang
                                        </button>
                                    @else
                                        <span
                                            class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic">Success</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-16 h-16 bg-slate-800/50 rounded-[2rem] flex items-center justify-center text-slate-200 mb-4">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-bold text-slate-400 italic">Tidak ada data hutang ditemukan.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($hutangs->hasPages())
                <div class="px-8 py-6 border-t border-slate-50 bg-slate-800/50/20">
                    {{ $hutangs->links() }}
                </div>
            @endif
        </div>

        <!-- Payment Modal -->
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
            <!-- Backdrop -->
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-md" aria-hidden="true" @click="showModal = false">
            </div>

            <!-- Modal Panel -->
            <div x-show="showModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="relative bg-slate-900 border border-white/5 rounded-[3rem] shadow-2xl overflow-hidden max-w-xl w-full border border-white/20">

                <!-- Header -->
                <div
                    class="bg-gradient-to-br from-indigo-600 to-indigo-800 px-10 py-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-slate-900 border border-white/5/10 rounded-full blur-2xl"></div>
                    <div
                        class="absolute bottom-0 left-0 -mb-12 -ml-12 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl">
                    </div>

                    <div class="relative flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-slate-900 border border-white/5/20 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/30 shadow-inner">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black tracking-tight leading-none">Pembayaran</h3>
                                <p
                                    class="text-indigo-100 text-[10px] font-black uppercase tracking-[0.2em] mt-1.5 opacity-80">
                                    Konfirmasi Transaksi</p>
                            </div>
                        </div>
                        <button @click="showModal = false" class="p-2 hover:bg-slate-900 border border-white/5/10 rounded-xl transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-10 space-y-8">
                    <!-- Summary Card -->
                    <div
                        class="bg-slate-800/50 rounded-[2rem] p-8 border border-white/5 relative group overflow-hidden">
                        <div
                            class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-transparent via-indigo-200 to-transparent opacity-30 group-hover:opacity-100 transition-opacity">
                        </div>

                        <div class="flex items-center justify-between mb-6">
                            <span
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Detail
                                Tagihan</span>
                            <span
                                class="px-3 py-1 bg-slate-900 border border-white/5 text-indigo-600 font-black text-[10px] rounded-full border border-indigo-50 shadow-sm"
                                x-text="hutangData.bbm"></span>
                        </div>

                        <div class="grid grid-cols-2 gap-8">
                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">No.
                                    Polisi</label>
                                <p class="text-2xl font-black text-white leading-tight tracking-tight"
                                    x-text="hutangData.nopol"></p>
                            </div>
                            <div class="space-y-1 text-right">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Jumlah
                                    Bon</label>
                                <p class="text-2xl font-black text-rose-600 leading-tight tracking-tight"
                                    x-text="`${hutangData.jumlah} L`"></p>
                            </div>
                        </div>
                    </div>

                    <form :action="`/satker/hutang/${selectedHutang}/bayar`" method="POST" id="paymentForm"
                        class="space-y-6">
                        @csrf
                        <div class="space-y-3">
                            <input type="hidden" name="nopol" :value="hutangData.nopol">
                            <p class="text-sm font-bold text-slate-400 mb-4 bg-indigo-50 p-4 rounded-2xl border border-indigo-100/50 flex gap-3">
                                <svg class="h-6 w-6 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                </svg>
                                <span>
                                    Pembayaran ini akan memotong saldo kendaraan <span class="font-black text-indigo-700" x-text="hutangData.nopol"></span>.
                                </span>
                            </p>
                        </div>
                    </form>

                    <div class="flex flex-col gap-3">
                        <button type="submit" form="paymentForm"
                            class="w-full py-5 bg-indigo-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-500/20 hover:bg-indigo-700 hover:shadow-indigo-500/30 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98] transition-all text-xs uppercase tracking-[0.2em] disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none">
                            Konfirmasi Pembayaran
                        </button>
                        <button type="button" @click="showModal = false"
                            class="w-full py-4 text-slate-400 hover:text-slate-400 hover:bg-slate-800/50 font-black rounded-2xl transition-all text-[10px] uppercase tracking-[0.2em]">
                            Batalkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>