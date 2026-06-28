<x-app-layout>
    <div class="p-2 sm:p-6 lg:p-8 space-y-8 max-w-4xl mx-auto">
        <div class="flex items-center gap-4">
            <a href="{{ route($rolePrefix.'.sounding.create') }}" class="p-2 bg-slate-800 hover:bg-slate-700 rounded-xl transition-colors">
                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-3xl font-black text-white tracking-tight">Tambah Data Sounding Akhir</h1>
        </div>

        <div class="bg-slate-900 border border-white/10 rounded-3xl p-8">
            <form action="{{ route($rolePrefix.'.sounding.store-akhir') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="sounding_id" id="sounding_id" value="">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase mb-2">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" required value="{{ old('tanggal', date('Y-m-d')) }}"
                               class="w-full bg-slate-800 border border-white/10 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase mb-2">Jenis BBM</label>
                        <select name="jenis_bbm" id="jenis_bbm" required class="tom-select w-full" data-placeholder="Pilih BBM">
                            <option value="">Pilih BBM</option>
                            <option value="PERTAMAX" {{ old('jenis_bbm') == 'PERTAMAX' ? 'selected' : '' }}>PERTAMAX</option>
                            <option value="PERTAMINA DEX" {{ old('jenis_bbm') == 'PERTAMINA DEX' ? 'selected' : '' }}>PERTAMINA DEX</option>
                        </select>
                    </div>
                </div>
                
                <!-- Status Message -->
                <div id="status_message" class="hidden p-4 rounded-xl text-sm font-bold border-l-4"></div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="calc_section" style="opacity: 0.5; pointer-events: none;">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase mb-2">Stok Awal</label>
                        <div class="relative">
                            <input type="number" step="1" id="stok_awal" readonly
                                   class="w-full bg-slate-800/50 border border-white/5 rounded-xl text-slate-400 pl-4 pr-12 py-3 focus:ring-0 cursor-not-allowed" placeholder="0">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 text-xs font-bold">Ltr</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase mb-2">Pengeluaran Aplikasi</label>
                        <div class="relative">
                            <input type="number" step="1" name="pengeluaran_aplikasi" id="pengeluaran_aplikasi" required readonly
                                   class="w-full bg-rose-900/30 border border-rose-500/30 rounded-xl text-rose-300 pl-4 pr-12 py-3 focus:ring-0 cursor-not-allowed font-bold" placeholder="0">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-rose-400 text-xs font-bold">Ltr</span>
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1 italic" id="pengeluaran_status">Menunggu data...</p>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase mb-2">Stok Akhir</label>
                        <div class="relative">
                            <input type="number" step="1" name="stok_akhir" id="stok_akhir" required
                                   class="w-full bg-slate-800 border border-white/10 rounded-xl text-white pl-4 pr-12 py-3 focus:ring-2 focus:ring-rose-500" placeholder="0">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold">Ltr</span>
                        </div>
                    </div>
                </div>

                <!-- Preview Susut -->
                <div class="p-4 bg-slate-800 rounded-xl flex items-center justify-between border border-white/5">
                    <span class="text-slate-400 font-bold uppercase text-sm">Estimasi Susut:</span>
                    <span id="preview_susut" class="text-2xl font-black text-white">0 <span class="text-sm">Ltr</span></span>
                </div>

                <div id="doc_section" style="opacity: 0.5; pointer-events: none;">
                    <label class="block text-xs font-black text-slate-400 uppercase mb-2">Dokumentasi (Foto) - Opsional</label>
                    <input type="file" name="dokumentasi" accept="image/*"
                           class="w-full bg-slate-800 border border-white/10 rounded-xl text-white file:mr-4 file:py-3 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-rose-600 file:text-white hover:file:bg-rose-700 cursor-pointer">
                    <p class="text-[10px] text-slate-500 mt-2">Pilih gambar baru jika ingin mengganti/menambahkan dokumentasi.</p>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" id="submit_btn" disabled class="px-8 py-3 bg-slate-700 text-slate-400 font-bold rounded-xl transition-colors cursor-not-allowed">
                        SIMPAN DATA AKHIR
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const tanggalInput = document.getElementById('tanggal');
        const jenisBbmInput = document.getElementById('jenis_bbm');
        
        const soundingIdInput = document.getElementById('sounding_id');
        const stokAwalInput = document.getElementById('stok_awal');
        const pengeluaranInput = document.getElementById('pengeluaran_aplikasi');
        const stokAkhirInput = document.getElementById('stok_akhir');
        
        const previewSusut = document.getElementById('preview_susut');
        const pengeluaranStatus = document.getElementById('pengeluaran_status');
        const statusMessage = document.getElementById('status_message');
        const calcSection = document.getElementById('calc_section');
        const docSection = document.getElementById('doc_section');
        const submitBtn = document.getElementById('submit_btn');

        function toggleForm(enabled) {
            if (enabled) {
                calcSection.style.opacity = '1';
                calcSection.style.pointerEvents = 'auto';
                docSection.style.opacity = '1';
                docSection.style.pointerEvents = 'auto';
                submitBtn.disabled = false;
                submitBtn.className = "px-8 py-3 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-xl transition-colors shadow-lg shadow-rose-500/30";
            } else {
                calcSection.style.opacity = '0.5';
                calcSection.style.pointerEvents = 'none';
                docSection.style.opacity = '0.5';
                docSection.style.pointerEvents = 'none';
                submitBtn.disabled = true;
                submitBtn.className = "px-8 py-3 bg-slate-700 text-slate-400 font-bold rounded-xl transition-colors cursor-not-allowed";
                soundingIdInput.value = "";
                stokAwalInput.value = "";
                pengeluaranInput.value = "";
            }
        }

        function showMessage(type, message) {
            statusMessage.style.display = 'block';
            statusMessage.textContent = message;
            if (type === 'error') {
                statusMessage.className = "p-4 rounded-xl text-sm font-bold border-l-4 border-rose-500 bg-rose-500/10 text-rose-400 block mb-6";
            } else if (type === 'success') {
                statusMessage.className = "p-4 rounded-xl text-sm font-bold border-l-4 border-emerald-500 bg-emerald-500/10 text-emerald-400 block mb-6";
            } else {
                statusMessage.className = "p-4 rounded-xl text-sm font-bold border-l-4 border-indigo-500 bg-indigo-500/10 text-indigo-400 block mb-6";
            }
        }

        async function fetchData() {
            const tanggal = tanggalInput.value;
            const jenisBbm = jenisBbmInput.value;

            if (tanggal && jenisBbm) {
                showMessage('info', 'Sedang memeriksa data sounding awal...');
                toggleForm(false);
                
                try {
                    // Fetch Sounding Awal
                    const resAwal = await fetch(`{{ route($rolePrefix.'.sounding.get-awal') }}?tanggal=${tanggal}&jenis_bbm=${jenisBbm}`);
                    const dataAwal = await resAwal.json();
                    
                    if (!dataAwal.found) {
                        showMessage('error', 'Data Sounding Awal belum ada untuk tanggal & Jenis BBM ini. Silakan input Sounding Awal terlebih dahulu.');
                        return;
                    }
                    
                    showMessage('success', 'Data Sounding Awal ditemukan. Silakan input stok akhir.');
                    soundingIdInput.value = dataAwal.id;
                    stokAwalInput.value = dataAwal.stok_awal;
                    
                    toggleForm(true);

                    // Fetch Pengeluaran
                    pengeluaranStatus.textContent = "Mengambil data...";
                    pengeluaranStatus.className = "text-[10px] mt-1 italic text-indigo-400 animate-pulse";
                    
                    const resPengeluaran = await fetch(`{{ route($rolePrefix.'.sounding.get-pengeluaran') }}?tanggal=${tanggal}&jenis_bbm=${jenisBbm}`);
                    const dataPengeluaran = await resPengeluaran.json();
                    
                    pengeluaranInput.value = dataPengeluaran.pengeluaran;
                    pengeluaranStatus.textContent = "Data pengeluaran berhasil ditarik.";
                    pengeluaranStatus.className = "text-[10px] mt-1 italic text-emerald-400";
                    
                    calculateSusut();
                    
                } catch (err) {
                    console.error(err);
                    showMessage('error', 'Terjadi kesalahan saat mengambil data.');
                }
            } else {
                statusMessage.style.display = 'none';
                toggleForm(false);
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

        tanggalInput.addEventListener('change', fetchData);
        jenisBbmInput.addEventListener('change', fetchData);
        stokAkhirInput.addEventListener('input', calculateSusut);

        if (tanggalInput.value && jenisBbmInput.value) {
            fetchData();
        }
    </script>
    @endpush
</x-app-layout>
