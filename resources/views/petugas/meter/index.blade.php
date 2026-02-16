<x-app-layout>
<div class="container-fluid py-8 px-6 bg-slate-50 min-h-screen">
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Input Meter Pompa</h1>
            <p class="text-slate-500 font-medium mt-1">Pencatatan harian meteran awal dan akhir untuk audit BBM.</p>
        </div>
        <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-2xl shadow-sm border border-slate-200">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span class="text-sm font-black text-slate-700 uppercase tracking-wider">{{ Carbon\Carbon::parse($today)->translatedFormat('d F Y') }}</span>
        </div>
    </div>

    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
         class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center shadow-sm animate-fade-in-down">
        <div class="bg-emerald-500 p-1.5 rounded-full mr-3 text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <span class="font-bold text-sm">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
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
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative group transition-all duration-300 hover:shadow-2xl hover:shadow-{{ $colorClass }}-100/50">
            <div class="absolute top-0 right-0 w-32 h-32 bg-{{ $colorClass }}-50/50 rounded-bl-[10rem] transition-all group-hover:w-36 group-hover:h-36"></div>
            
            <form action="{{ route('petugas.meter.store') }}" method="POST" class="p-10 relative z-10">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $today }}">
                <input type="hidden" name="jenis_bbm" value="{{ $type['name'] }}">

                <div class="flex items-center gap-4 mb-8">
                    <div class="p-3 bg-{{ $colorClass }}-100 rounded-2xl text-{{ $colorClass }}-600 transition-transform group-hover:scale-110">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-{{ $colorClass }}-500 uppercase tracking-[0.2em] mb-1">Fuel Category</h3>
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ $type['name'] }}</h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Meter Awal (Physical)</label>
                        <div class="relative group/input">
                            <input type="number" step="0.01" name="meter_awal" id="meter_awal_{{ $loop->index }}" 
                                value="{{ old('meter_awal') }}" 
                                oninput="calculateTotal({{ $loop->index }})"
                                class="w-full text-lg font-black text-slate-700 bg-slate-50 border-2 border-transparent group-hover/input:border-{{ $colorClass }}-200 rounded-2xl focus:ring-4 focus:ring-{{ $colorClass }}-100 focus:border-{{ $colorClass }}-500 p-4 transition-all outline-none">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Meter Akhir (Physical)</label>
                        <div class="relative group/input">
                            <input type="number" step="0.01" name="meter_akhir" id="meter_akhir_{{ $loop->index }}" 
                                value="{{ old('meter_akhir') }}" 
                                oninput="calculateTotal({{ $loop->index }})"
                                class="w-full text-lg font-black text-slate-700 bg-slate-50 border-2 border-transparent group-hover/input:border-{{ $colorClass }}-200 rounded-2xl focus:ring-4 focus:ring-{{ $colorClass }}-100 focus:border-{{ $colorClass }}-500 p-4 transition-all outline-none">
                        </div>
                    </div>
                </div>

                <!-- Calculation Result Display -->
                <div class="mb-8 bg-slate-50 rounded-2xl p-5 border border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Pengisian Hari Ini</span>
                        <span class="text-xs font-semibold text-slate-500">({{ $type['name'] }})</span>
                    </div>
                    <div class="text-right">
                        <span id="total_liter_{{ $loop->index }}" class="text-2xl font-black text-{{ $colorClass }}-600">0</span>
                        <span class="text-sm font-bold text-slate-400">Liter</span>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2">Keterangan / Catatan (Opsional)</label>
                    <div class="relative group/input">
                        <textarea name="keterangan" rows="2" 
                            class="w-full text-sm font-bold text-slate-700 bg-slate-50 border-2 border-transparent group-hover/input:border-{{ $colorClass }}-200 rounded-2xl focus:ring-4 focus:ring-{{ $colorClass }}-100 focus:border-{{ $colorClass }}-500 p-4 transition-all outline-none resize-none"
                            placeholder="Tambahkan catatan jika ada...">{{ old('keterangan') }}</textarea>
                    </div>
                </div>

                <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white p-5 rounded-2xl transition-all duration-300 shadow-lg shadow-slate-200 font-black text-sm uppercase tracking-[0.2em] flex items-center justify-center gap-3 group/btn">
                    <span>Simpan Data {{ $type['name'] }}</span>
                    <svg class="w-5 h-5 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7-7 7"></path></svg>
                </button>
            </form>
        </div>
        @endforeach
    </div>

    <!-- Instructions -->
    <div class="mt-12 bg-white p-10 rounded-[3rem] shadow-xl shadow-slate-200/40 border border-slate-100 flex flex-col md:flex-row items-center gap-8">
        <div class="w-20 h-20 shrink-0 bg-indigo-50 rounded-[2rem] flex items-center justify-center text-indigo-600">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div class="flex-1">
            <h3 class="text-xl font-black text-slate-900 tracking-tight mb-2">Panduan Penginputan</h3>
            <p class="text-slate-500 font-medium text-sm leading-relaxed">Pastikan angka yang diinput sesuai dengan angka yang tertera pada display mekanik pompa. Data ini akan digunakan oleh Super Admin untuk melakukan audit rekonsiliasi dengan transaksi di aplikasi.</p>
        </div>
        <div class="shrink-0 flex items-center gap-4 bg-slate-50 p-4 rounded-3xl border border-slate-100">
            <div class="flex flex-col items-center">
                <span class="text-2xl font-black text-slate-900 leading-tight">100%</span>
                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Accuracy</span>
            </div>
            <div class="w-px h-8 bg-slate-200"></div>
            <div class="flex flex-col items-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                <span class="text-[8px] font-black uppercase tracking-widest mt-1">Verified</span>
            </div>
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
        
        // Ensure total is not negative for display (logic: meter akhir >= meter awal)
        // You might want to allow negative for correction, but usually for dispensing it's positive.
        // If total is negative, it usually means input error or meter rollover (not handled here yet)
        
        if (total < 0) total = 0;

        // Format number with decimals if needed, or integer
        // Using toLocaleString for thousand separators
        totalSpan.textContent = total.toLocaleString('id-ID', { maximumFractionDigits: 2 });
    }

    // Initialize calculation on load if values exist
    document.addEventListener('DOMContentLoaded', function() {
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
    input[type=number] { -moz-appearance: textfield; }
    
    @keyframes fade-in-down {
        0% { opacity: 0; transform: translateY(-10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down { animation: fade-in-down 0.5s ease-out; }
</style>
