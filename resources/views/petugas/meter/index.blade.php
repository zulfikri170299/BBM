<x-app-layout>
    <div class="container-fluid py-3 px-3 sm:py-8 sm:px-6 bg-slate-50 min-h-screen">
        <!-- Header Section -->
        <div class="mb-3 sm:mb-10 flex flex-col md:flex-row md:items-center md:justify-between gap-2 sm:gap-6">
            <div>
                <h1 class="text-lg sm:text-3xl font-extrabold text-slate-900 tracking-tight">Input Meter Pompa</h1>
                <p class="text-slate-500 font-medium mt-0.5 text-xs sm:text-base">Pencatatan harian meteran awal dan
                    akhir
                    untuk audit BBM.</p>
            </div>
            <div
                class="flex items-center gap-2 bg-white px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl sm:rounded-2xl shadow-sm border border-slate-200 w-fit">
                <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5 text-indigo-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <span
                    class="text-[10px] sm:text-sm font-black text-slate-700 uppercase tracking-wider">{{ Carbon\Carbon::parse($today)->translatedFormat('d F Y') }}</span>
            </div>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                class="mb-3 sm:mb-8 p-2 sm:p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-lg sm:rounded-2xl flex items-center shadow-sm animate-fade-in-down">
                <div class="bg-emerald-500 p-1 sm:p-1.5 rounded-full mr-2 text-white">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <span class="font-bold text-[10px] sm:text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-8">
            @php
                $fuelTypes = [
                    ['name' => 'PERTAMAX', 'color' => 'emerald'],
                    ['name' => 'PERTAMINA DEX', 'color' => 'indigo']
                ];
            @endphp

            @foreach($fuelTypes as $type)
                @php
                    $reading = $readings->get($type['name']);
                    $colorClass = $type['color'];
                @endphp
                <div
                    class="bg-white rounded-xl sm:rounded-[2.5rem] shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden relative group transition-all duration-300 hover:shadow-xl hover:shadow-{{ $colorClass }}-100/50">
                    <div
                        class="absolute top-0 right-0 w-12 h-12 sm:w-32 sm:h-32 bg-{{ $colorClass }}-50/50 rounded-bl-3xl sm:rounded-bl-[10rem] transition-all group-hover:w-16 group-hover:h-16 sm:group-hover:w-36 sm:group-hover:h-36">
                    </div>

                    <form action="{{ route('petugas.meter.store') }}" method="POST" class="p-4 sm:p-10 relative z-10">
                        @csrf
                        <input type="hidden" name="tanggal" value="{{ $today }}">
                        <input type="hidden" name="jenis_bbm" value="{{ $type['name'] }}">

                        <div class="flex items-center gap-3 sm:gap-4 mb-3 sm:mb-8">
                            <div
                                class="p-2 sm:p-3 bg-{{ $colorClass }}-100 rounded-lg sm:rounded-2xl text-{{ $colorClass }}-600 transition-transform group-hover:scale-110">
                                <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3
                                    class="text-[8px] sm:text-xs font-black text-{{ $colorClass }}-500 uppercase tracking-[0.2em] mb-0 sm:mb-1">
                                    Fuel Category</h3>
                                <h2 class="text-sm sm:text-2xl font-black text-slate-900 tracking-tight">{{ $type['name'] }}
                                </h2>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 sm:gap-6 mb-3 sm:mb-8">
                            <div class="space-y-1 sm:space-y-2">
                                <label
                                    class="block text-[8px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Meter
                                    Awal</label>
                                <div class="relative group/input">
                                    <input type="number" step="0.01" name="meter_awal" id="meter_awal_{{ $loop->index }}"
                                        value="{{ old('meter_awal') }}" oninput="calculateTotal({{ $loop->index }})"
                                        class="w-full text-sm sm:text-lg font-black text-slate-700 bg-slate-50 border-2 border-transparent group-hover/input:border-{{ $colorClass }}-200 rounded-lg sm:rounded-2xl focus:ring-4 focus:ring-{{ $colorClass }}-100 focus:border-{{ $colorClass }}-500 p-2 sm:p-4 transition-all outline-none"
                                        placeholder="0.00">
                                </div>
                            </div>
                            <div class="space-y-1 sm:space-y-2">
                                <label
                                    class="block text-[8px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Meter
                                    Akhir</label>
                                <div class="relative group/input">
                                    <input type="number" step="0.01" name="meter_akhir" id="meter_akhir_{{ $loop->index }}"
                                        value="{{ old('meter_akhir') }}" oninput="calculateTotal({{ $loop->index }})"
                                        class="w-full text-sm sm:text-lg font-black text-slate-700 bg-slate-50 border-2 border-transparent group-hover/input:border-{{ $colorClass }}-200 rounded-lg sm:rounded-2xl focus:ring-4 focus:ring-{{ $colorClass }}-100 focus:border-{{ $colorClass }}-500 p-2 sm:p-4 transition-all outline-none"
                                        placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <!-- Calculation Result Display -->
                        <div
                            class="mb-3 sm:mb-8 bg-slate-50 rounded-lg sm:rounded-2xl p-2.5 sm:p-5 border border-slate-100 flex items-center justify-between">
                            <div>
                                <span
                                    class="block text-[8px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest">Total
                                    Pengisian</span>
                            </div>
                            <div class="text-right flex items-baseline gap-1">
                                <span id="total_liter_{{ $loop->index }}"
                                    class="text-lg sm:text-2xl font-black text-{{ $colorClass }}-600">0</span>
                                <span class="text-[10px] sm:text-sm font-bold text-slate-400">L</span>
                            </div>
                        </div>

                        <div class="mb-3 sm:mb-8">
                            <label
                                class="block text-[8px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-1 sm:mb-2">Keterangan
                                (Opsional)</label>
                            <div class="relative group/input">
                                <textarea name="keterangan" rows="1"
                                    class="w-full text-[10px] sm:text-sm font-bold text-slate-700 bg-slate-50 border-2 border-transparent group-hover/input:border-{{ $colorClass }}-200 rounded-lg sm:rounded-2xl focus:ring-4 focus:ring-{{ $colorClass }}-100 focus:border-{{ $colorClass }}-500 p-2 sm:p-4 transition-all outline-none resize-none"
                                    placeholder="Catatan...">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-slate-900 hover:bg-slate-800 text-white p-2.5 sm:p-5 rounded-lg sm:rounded-2xl transition-all duration-300 shadow-lg shadow-slate-200 font-black text-[10px] sm:text-sm uppercase tracking-[0.2em] flex items-center justify-center gap-2 sm:gap-3 group/btn">
                            <span>Simpan</span>
                            <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5 transition-transform group-hover/btn:translate-x-1"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7-7 7">
                                </path>
                            </svg>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        <!-- Instructions -->
        <div
            class="mt-4 sm:mt-12 bg-white p-4 sm:p-10 rounded-xl sm:rounded-[3rem] shadow-xl shadow-slate-200/40 border border-slate-100 flex flex-col md:flex-row items-center gap-4 sm:gap-8">
            <div
                class="w-10 h-10 sm:w-20 sm:h-20 shrink-0 bg-indigo-50 rounded-xl sm:rounded-[2rem] flex items-center justify-center text-indigo-600">
                <svg class="w-5 h-5 sm:w-10 sm:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="flex-1 text-center md:text-left">
                <h3 class="text-sm sm:text-xl font-black text-slate-900 tracking-tight mb-1 sm:mb-2">Panduan Penginputan
                </h3>
                <p class="text-slate-500 font-medium text-[10px] sm:text-sm leading-relaxed">Pastikan angka input
                    sesuai display mekanik pompa. Data digunakan untuk audit.</p>
            </div>
        </div>
    </div>

</x-app-layout>

<script>
    function calculateTotal(index) {
        const awal = parseFloat(document.getElementById(`meter_awal_${index}`).value) || 0;
        const akhir = parseFloat(document.getElementById(`meter_akhir_${index}`).value) || 0;
        const totalSpan = document.getElementById(`total_liter_${index}`);

        // Calculate difference
        let total = akhir - awal;

        if (total < 0) total = 0;

        // Format number with decimals if needed, or integer
        // Using toLocaleString for thousand separators
        totalSpan.textContent = total.toLocaleString('id-ID', { maximumFractionDigits: 2 });
    }

    // Initialize calculation on load if values exist
    document.addEventListener('DOMContentLoaded', function () {
        @foreach($fuelTypes as $loop)
            calculateTotal({{ $loop->index }});
        @endforeach
    });
</script>

<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }

    @keyframes fade-in-down {
        0% {
            opacity: 0;
            transform: translateY(-10px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-down {
        animation: fade-in-down 0.5s ease-out;
    }
</style>