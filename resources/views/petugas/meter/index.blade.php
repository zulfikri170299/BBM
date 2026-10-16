<x-app-layout>
    <div class="py-6 px-3 sm:px-6 lg:px-8 min-h-screen">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- Flash Message -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-between shadow-lg shadow-emerald-500/5 animate-fade-in-down">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-xl bg-emerald-500/20 text-emerald-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400/60 hover:text-emerald-400 p-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            <!-- Header Section -->
            @php
                $carbonToday = \Carbon\Carbon::parse($today)->locale('id');
                $yesterday = \Carbon\Carbon::parse($today)->subDay()->toDateString();
                $tomorrow = \Carbon\Carbon::parse($today)->addDay()->toDateString();
                $actualToday = \Carbon\Carbon::now('Asia/Makassar')->toDateString();
            @endphp
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 sm:gap-4 bg-white/70 dark:bg-slate-900/60 backdrop-blur-xl p-3.5 sm:p-6 rounded-2xl sm:rounded-3xl border border-slate-200/80 dark:border-white/10 shadow-sm">
                <div>
                    <div class="hidden sm:inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20 text-[10px] font-black uppercase tracking-widest mb-2">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Audit Fisik Nozzle Pompa</span>
                    </div>
                    <h1 class="text-xl sm:text-3xl font-black text-white tracking-tight">
                        Input Meter Pompa BBM
                    </h1>
                    <p class="hidden sm:block text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium mt-1">
                        Pencatatan angka meteran mekanik awal dan akhir dispenser untuk verifikasi audit BBM harian.
                    </p>
                </div>

                <!-- Date Navigation Controls -->
                <div class="w-full sm:w-auto flex items-center justify-between gap-1.5 sm:gap-2 bg-slate-100 dark:bg-slate-800/80 p-1.5 rounded-2xl border border-slate-200 dark:border-white/10 shadow-inner">
                    <!-- Prev Day -->
                    <a href="?tanggal={{ $yesterday }}" title="Hari Sebelumnya"
                        class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-700 hover:text-indigo-600 transition-all shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>

                    <!-- Current Date Picker -->
                    <form method="GET" class="flex-1 sm:flex-initial flex items-center justify-center m-0 px-2 gap-2">
                        <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <input type="date" name="tanggal" value="{{ $today }}" onchange="this.form.submit()"
                            class="text-xs font-black text-slate-800 dark:text-slate-200 uppercase tracking-wider bg-transparent border-none p-0 focus:ring-0 cursor-pointer outline-none text-center">
                    </form>

                    <!-- Next Day -->
                    <a href="?tanggal={{ $tomorrow }}" title="Hari Berikutnya"
                        class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-700 hover:text-indigo-600 transition-all shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    @if($today !== $actualToday)
                        <a href="?tanggal={{ $actualToday }}"
                            class="px-2.5 sm:px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-[10px] sm:text-[11px] font-bold uppercase tracking-wider transition-all shadow-sm shrink-0">
                            Hari Ini
                        </a>
                    @endif
                </div>
            </div>

            <!-- Two Fuel Category Cards -->
            @php
                $fuelConfigs = [
                    [
                        'name' => 'PERTAMAX',
                        'accent' => 'emerald',
                        'theme_from' => 'from-emerald-500',
                        'theme_to' => 'to-teal-600',
                        'badge_bg' => 'bg-emerald-500/10',
                        'badge_text' => 'text-emerald-600 dark:text-emerald-400',
                        'border' => 'border-emerald-500/20',
                        'ring' => 'focus:ring-emerald-500/20 focus:border-emerald-500'
                    ],
                    [
                        'name' => 'PERTAMINA DEX',
                        'accent' => 'indigo',
                        'theme_from' => 'from-blue-600',
                        'theme_to' => 'to-indigo-600',
                        'badge_bg' => 'bg-blue-500/10',
                        'badge_text' => 'text-blue-600 dark:text-blue-400',
                        'border' => 'border-blue-500/20',
                        'ring' => 'focus:ring-blue-500/20 focus:border-blue-500'
                    ]
                ];
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($fuelConfigs as $config)
                    @php
                        $reading = $readings->get($config['name']);
                        $lastReading = $lastReadings[$config['name']] ?? null;
                        $initAwal = old('meter_awal', $reading?->meter_awal ?? '');
                        $initAkhir = old('meter_akhir', $reading?->meter_akhir ?? '');
                        $initKet = old('keterangan', $reading?->keterangan ?? '');
                        $hasReadingToday = !is_null($reading);
                    @endphp

                    <div x-data="{
                        meterAwal: '{{ $initAwal }}',
                        meterAkhir: '{{ $initAkhir }}',
                        showNote: {{ $initKet ? 'true' : 'false' }},
                        get totalPengisian() {
                            const awal = parseInt(this.meterAwal);
                            const akhir = parseInt(this.meterAkhir);
                            if (isNaN(awal) || isNaN(akhir)) return 0;
                            const diff = akhir - awal;
                            return diff > 0 ? diff : 0;
                        },
                        get isNegative() {
                            const awal = parseInt(this.meterAwal);
                            const akhir = parseInt(this.meterAkhir);
                            if (isNaN(awal) || isNaN(akhir)) return false;
                            return akhir < awal && akhir > 0;
                        },
                        formatNumber(num) {
                            return new Intl.NumberFormat('id-ID').format(num);
                        },
                        setMeterAwal(val) {
                            this.meterAwal = val;
                        }
                    }"
                    class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl rounded-2xl sm:rounded-3xl border border-slate-200/80 dark:border-white/10 shadow-xl overflow-hidden flex flex-col justify-between transition-all hover:border-slate-300 dark:hover:border-white/20">
                        
                        <!-- Card Header -->
                        <div class="p-3.5 sm:p-5 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-slate-800/40">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5 sm:gap-3">
                                    <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl {{ $config['badge_bg'] }} {{ $config['border'] }} border {{ $config['badge_text'] }} flex items-center justify-center shadow-sm shrink-0">
                                        <!-- Gas Pump Dispenser Icon -->
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L7.05 15.12a2 2 0 00-1.022.547l-2.387 2.387a2 2 0 000 2.828l.141.141a2 2 0 002.828 0l2.628-2.628a2 2 0 012.33-.213l.317.158a2 2 0 002.33-.213l2.628-2.628a2 2 0 000-2.828l-.141-.141z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="hidden sm:block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-0.5">
                                            Kategori BBM
                                        </span>
                                        <h2 class="text-base sm:text-xl font-black text-slate-900 dark:text-white tracking-tight">
                                            {{ $config['name'] }}
                                        </h2>
                                    </div>
                                </div>

                                <!-- Status Badge -->
                                <div class="text-right shrink-0">
                                    @if($hasReadingToday)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-[10px] sm:text-xs font-bold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Sudah Tercatat
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 text-[10px] sm:text-xs font-bold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            Belum Diisi
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Card Form -->
                        <form action="{{ route('petugas.meter.store') }}" method="POST" class="p-3.5 sm:p-5 space-y-3 sm:space-y-4 flex-1 flex flex-col justify-between">
                            @csrf
                            <input type="hidden" name="tanggal" value="{{ $today }}">
                            <input type="hidden" name="jenis_bbm" value="{{ $config['name'] }}">

                            <div class="space-y-3 sm:space-y-4">
                                <!-- Odometer Inputs Row -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                    <!-- Meter Awal -->
                                    <div>
                                        <label class="flex items-center gap-1 text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.933 12.8a1 1 0 000-1.6L6.6 7.2A1 1 0 005 8v8a1 1 0 001.6.8l5.333-4zM19.933 12.8a1 1 0 000-1.6l-5.333-4A1 1 0 0013 8v8a1 1 0 001.6.8l5.333-4z" />
                                            </svg>
                                            <span>Meter Awal</span>
                                            <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" step="1" name="meter_awal" x-model="meterAwal"
                                                class="w-full px-3.5 py-2.5 sm:py-3 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 rounded-xl sm:rounded-2xl text-base sm:text-lg font-black font-mono tracking-wider text-slate-900 dark:text-white {{ $config['ring'] }} transition-all"
                                                placeholder="0">
                                            <span class="absolute right-3.5 top-2.5 sm:top-3.5 text-xs font-bold text-slate-400">PPU</span>
                                        </div>
                                    </div>

                                    <!-- Meter Akhir -->
                                    <div>
                                        <label class="flex items-center gap-1 text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                                            </svg>
                                            <span>Meter Akhir</span>
                                            <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" step="1" name="meter_akhir" x-model="meterAkhir"
                                                class="w-full px-3.5 py-2.5 sm:py-3 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 rounded-xl sm:rounded-2xl text-base sm:text-lg font-black font-mono tracking-wider text-slate-900 dark:text-white {{ $config['ring'] }} transition-all"
                                                placeholder="0">
                                            <span class="absolute right-3.5 top-2.5 sm:top-3.5 text-xs font-bold text-slate-400">PPU</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Negative Warning Banner -->
                                <div x-show="isNegative" x-cloak class="p-2.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-500 text-xs font-bold flex items-center gap-2">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    <span>Meter akhir tidak boleh lebih kecil dari meter awal!</span>
                                </div>

                                <!-- Calculation Result Display Box -->
                                <div class="p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-gradient-to-br from-slate-100 to-slate-50 dark:from-slate-800/80 dark:to-slate-850 border border-slate-200 dark:border-white/10 flex items-center justify-between gap-3">
                                    <div>
                                        <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 block">
                                            Total Pengisian Fisik
                                        </span>
                                        <span class="hidden sm:block text-[11px] text-slate-400 mt-0.5 font-medium">
                                            Selisih meter akhir &amp; awal dispenser
                                        </span>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <div class="flex items-baseline justify-end gap-1">
                                            <span class="text-xl sm:text-3xl font-black font-mono tracking-tight {{ $config['badge_text'] }}"
                                                x-text="formatNumber(totalPengisian)">0</span>
                                            <span class="text-[11px] sm:text-xs font-black uppercase text-slate-500 dark:text-slate-400">Liter</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Notes Field (Collapsible) -->
                                <div>
                                    <button type="button" @click="showNote = !showNote" class="flex items-center gap-1.5 text-[11px] font-bold text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                        <svg class="w-3.5 h-3.5 transition-transform" :class="showNote ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        <span>Keterangan / Catatan <span class="font-normal opacity-70">(opsional)</span></span>
                                    </button>
                                    <div x-show="showNote" x-cloak class="mt-2">
                                        <textarea name="keterangan" rows="2"
                                            class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-medium text-slate-800 dark:text-white {{ $config['ring'] }} resize-none transition-all placeholder-slate-400"
                                            placeholder="Catatan kendala, pengujian nozzle, bon darurat...">{{ $initKet }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-3">
                                <button type="submit"
                                    :disabled="isNegative"
                                    class="w-full py-3 sm:py-3.5 bg-gradient-to-r {{ $config['theme_from'] }} {{ $config['theme_to'] }} hover:opacity-95 disabled:opacity-40 disabled:cursor-not-allowed text-white font-black text-xs uppercase tracking-widest rounded-xl sm:rounded-2xl shadow-md active:scale-[0.99] transition-all flex items-center justify-center gap-2">
                                    <span>Simpan Meter {{ $config['name'] }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>

            <!-- Audit History Table -->
            @if(isset($recentReadings) && $recentReadings->count() > 0)
                <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl rounded-3xl border border-slate-200/80 dark:border-white/10 shadow-xl overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-black uppercase tracking-wider text-white">
                                    Riwayat Pencatatan Meteran Terakhir
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                                    10 catatan log meter pompa dispenser terbaru
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-white/10 text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                                <tr>
                                    <th class="px-6 py-3.5">Tanggal</th>
                                    <th class="px-4 py-3.5">Jenis BBM</th>
                                    <th class="px-4 py-3.5 font-mono text-right">Meter Awal</th>
                                    <th class="px-4 py-3.5 font-mono text-right">Meter Akhir</th>
                                    <th class="px-6 py-3.5 font-mono text-right">Total Fisik (L)</th>
                                    <th class="px-6 py-3.5">Catatan / Keterangan</th>
                                    <th class="px-4 py-3.5 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5 text-xs font-semibold">
                                @foreach($recentReadings as $item)
                                    @php
                                        $diff = max(0, $item->meter_akhir - $item->meter_awal);
                                        $isPertamax = stripos($item->jenis_bbm, 'PERTAMAX') !== false;
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors {{ $item->tanggal === $today ? 'bg-indigo-50/30 dark:bg-indigo-950/20' : '' }}">
                                        <td class="px-6 py-4">
                                            <span class="text-white font-bold block">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                            </span>
                                            <span class="text-[10px] text-slate-400">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->isoFormat('dddd') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase {{ $isPertamax ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20' }}">
                                                {{ $item->jenis_bbm }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-right font-mono text-slate-600 dark:text-slate-300">
                                            {{ number_format($item->meter_awal, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-4 text-right font-mono text-slate-600 dark:text-slate-300">
                                            {{ number_format($item->meter_akhir, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="font-mono font-black text-sm {{ $isPertamax ? 'text-emerald-600 dark:text-emerald-400' : 'text-blue-600 dark:text-blue-400' }}">
                                                {{ number_format($diff, 0, ',', '.') }} L
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-slate-500 dark:text-slate-400 max-w-xs truncate">
                                            {{ $item->keterangan ?: '-' }}
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <a href="?tanggal={{ $item->tanggal }}"
                                                class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-[11px] font-bold transition-all">
                                                Buka
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Panduan Section (Desktop Only) -->
            <div class="hidden sm:flex bg-white/60 dark:bg-slate-900/40 backdrop-blur-md p-4 sm:p-5 rounded-2xl sm:rounded-3xl border border-slate-200/60 dark:border-white/5 items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                    <strong class="text-slate-800 dark:text-slate-200 block text-xs font-bold mb-0.5">Panduan Rekonsiliasi Audit Meteran:</strong>
                    Pastikan angka yang diinput sesuai dengan display counter mekanik pada nozzle dispenser untuk mencocokkan transaksi kupon harian dengan keluaran fisik pompa.
                </div>
            </div>

        </div>
    </div>
</x-app-layout>