<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-8 max-w-4xl mx-auto">
        <div class="flex items-center gap-4">
            <a href="{{ route($rolePrefix.'.sounding.index') }}" class="p-2 bg-slate-800 hover:bg-slate-700 rounded-xl transition-colors">
                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-2xl font-black text-white tracking-tight">Edit Data Sounding</h1>
        </div>

        <div class="bg-slate-900 border border-white/10 rounded-3xl p-8">
            <form action="{{ route($rolePrefix.'.sounding.update', $sounding->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase mb-2">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" required value="{{ old('tanggal', $sounding->tanggal) }}"
                               class="w-full bg-slate-800 border border-white/10 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase mb-2">Jenis BBM</label>
                        <select name="jenis_bbm" id="jenis_bbm" required class="tom-select w-full" data-placeholder="Pilih BBM">
                            <option value="">Pilih BBM</option>
                            <option value="PERTAMAX" {{ (old('jenis_bbm') ?? $sounding->jenis_bbm) == 'PERTAMAX' ? 'selected' : '' }}>PERTAMAX</option>
                            <option value="PERTAMINA DEX" {{ (old('jenis_bbm') ?? $sounding->jenis_bbm) == 'PERTAMINA DEX' ? 'selected' : '' }}>PERTAMINA DEX</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase mb-2">Stok Awal</label>
                        <div class="relative">
                            <input type="number" step="1" name="stok_awal" id="stok_awal" required value="{{ old('stok_awal', $sounding->stok_awal) }}"
                                   class="w-full bg-slate-800 border border-white/10 rounded-xl text-white pl-4 pr-12 py-3 focus:ring-2 focus:ring-indigo-500" placeholder="0">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold">Ltr</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase mb-2">Pengeluaran Aplikasi</label>
                        <div class="relative">
                            <input type="number" step="1" name="pengeluaran_aplikasi" id="pengeluaran_aplikasi" required value="{{ old('pengeluaran_aplikasi', $sounding->pengeluaran_aplikasi) }}" readonly
                                   class="w-full bg-indigo-900/30 border border-indigo-500/30 rounded-xl text-indigo-300 pl-4 pr-12 py-3 focus:ring-0 cursor-not-allowed font-bold" placeholder="0">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-indigo-400 text-xs font-bold">Ltr</span>
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1 italic" id="pengeluaran_status">Otomatis ditarik dari data transaksi</p>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase mb-2">Stok Akhir</label>
                        <div class="relative">
                            <input type="number" step="1" name="stok_akhir" id="stok_akhir" required value="{{ old('stok_akhir', $sounding->stok_akhir) }}"
                                   class="w-full bg-slate-800 border border-white/10 rounded-xl text-white pl-4 pr-12 py-3 focus:ring-2 focus:ring-indigo-500" placeholder="0">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold">Ltr</span>
                        </div>
                    </div>
                </div>

                <!-- Preview Susut -->
                <div class="p-4 bg-slate-800 rounded-xl flex items-center justify-between border border-white/5">
                    <span class="text-slate-400 font-bold uppercase text-sm">Estimasi Susut:</span>
                    <span id="preview_susut" class="text-2xl font-black text-white">0 <span class="text-sm">Ltr</span></span>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase mb-2">Dokumentasi (Foto) - Opsional</label>
                    <input type="file" name="dokumentasi" accept="image/*"
                           class="w-full bg-slate-800 border border-white/10 rounded-xl text-white file:mr-4 file:py-3 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">
                    <p class="text-[10px] text-slate-500 mt-2">Biarkan kosong jika tidak ingin mengubah foto.</p>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-8 py-3 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-xl transition-colors shadow-lg shadow-amber-500/30">
                        PERBARUI DATA
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const tanggalInput = document.getElementById('tanggal');
        const jenisBbmInput = document.getElementById('jenis_bbm');
        const pengeluaranInput = document.getElementById('pengeluaran_aplikasi');
        const stokAwalInput = document.getElementById('stok_awal');
        const stokAkhirInput = document.getElementById('stok_akhir');
        const previewSusut = document.getElementById('preview_susut');
        const pengeluaranStatus = document.getElementById('pengeluaran_status');

        function fetchPengeluaran() {
            const tanggal = tanggalInput.value;
            const jenisBbm = jenisBbmInput.value;

            if (tanggal && jenisBbm) {
                pengeluaranStatus.textContent = "Mengambil data...";
                pengeluaranStatus.classList.add('animate-pulse');

                fetch(`{{ route($rolePrefix.'.sounding.get-pengeluaran') }}?tanggal=${tanggal}&jenis_bbm=${jenisBbm}`)
                    .then(response => response.json())
                    .then(data => {
                        pengeluaranInput.value = data.pengeluaran;
                        pengeluaranStatus.textContent = "Berhasil ditarik dari database.";
                        pengeluaranStatus.classList.remove('animate-pulse');
                        pengeluaranStatus.classList.add('text-emerald-400');
                        calculateSusut();
                    })
                    .catch(error => {
                        console.error('Error fetching pengeluaran:', error);
                        pengeluaranStatus.textContent = "Gagal mengambil data.";
                        pengeluaranStatus.classList.remove('animate-pulse');
                        pengeluaranStatus.classList.add('text-rose-400');
                    });
            }
        }

        function calculateSusut() {
            const awal = parseFloat(stokAwalInput.value) || 0;
            const pengeluaran = parseFloat(pengeluaranInput.value) || 0;
            const akhir = parseFloat(stokAkhirInput.value) || 0;

            const susut = awal - pengeluaran - akhir;
            
            previewSusut.innerHTML = `${susut.toLocaleString('id-ID', { maximumFractionDigits: 0 })} <span class="text-sm">Ltr</span>`;
            if(susut > 0) previewSusut.className = "text-2xl font-black text-emerald-400";
            else if(susut < 0) previewSusut.className = "text-2xl font-black text-rose-400";
            else previewSusut.className = "text-2xl font-black text-slate-300";
        }

        tanggalInput.addEventListener('change', fetchPengeluaran);
        jenisBbmInput.addEventListener('change', fetchPengeluaran);

        stokAwalInput.addEventListener('input', calculateSusut);
        pengeluaranInput.addEventListener('input', calculateSusut);
        stokAkhirInput.addEventListener('input', calculateSusut);

        // Fetch on load if both are filled
        if (tanggalInput.value && jenisBbmInput.value) {
            fetchPengeluaran();
        }
    </script>
    @endpush
</x-app-layout>
