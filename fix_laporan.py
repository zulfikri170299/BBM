import os

def process_tahunan(file_path):
    with open(file_path, "r") as f:
        content = f.read()

    # Admin's index loop
    search_block1 = """            $pendapatanPertamax = RiwayatTopup::where('satker_id', $satker->id)
                ->where('tipe', 'masuk')->where($isPertamaxTopup)->whereYear('created_at', $year)->sum('jumlah');

            $pendapatanDex = RiwayatTopup::where('satker_id', $satker->id)
                ->where('tipe', 'masuk')->where($isDexTopup)->whereYear('created_at', $year)->sum('jumlah');

            $pemakaianPertamax = TransaksiBbm::where('satker_id', $satker->id)
                ->where($isPertamaxTrans)->whereYear('tanggal', $year)->sum('liter');

            $pemakaianDex = TransaksiBbm::where('satker_id', $satker->id)
                ->where($isDexTrans)->whereYear('tanggal', $year)->sum('liter');
            
            $potongSaldoPertamax = RiwayatTopup::where('satker_id', $satker->id)
                ->where('tipe', 'keluar')->where($isPertamaxTopup)->whereYear('created_at', $year)->sum('jumlah');
            
            $potongSaldoDex = RiwayatTopup::where('satker_id', $satker->id)
                ->where('tipe', 'keluar')->where($isDexTopup)->whereYear('created_at', $year)->sum('jumlah');
                
            $hutangPertamax = \App\Models\Hutang::where('satker_id', $satker->id)
                ->where($isPertamaxTrans)->whereYear('tanggal_bon', $year)->sum('jumlah_bon');
            $hutangDex = \App\Models\Hutang::where('satker_id', $satker->id)
                ->where($isDexTrans)->whereYear('tanggal_bon', $year)->sum('jumlah_bon');
            
            $pemakaianPertamax += $potongSaldoPertamax + $hutangPertamax;
            $pemakaianDex += $potongSaldoDex + $hutangDex;"""

    # Admin's print loop
    search_block2 = """            $pendapatanPertamax = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'masuk')->where($isPertamaxTopup)->whereYear('created_at', $year)->sum('jumlah');
            $pendapatanDex = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'masuk')->where($isDexTopup)->whereYear('created_at', $year)->sum('jumlah');
            $pemakaianPertamax = TransaksiBbm::where('satker_id', $satker->id)->where($isPertamaxTrans)->whereYear('tanggal', $year)->sum('liter');
            $pemakaianDex = TransaksiBbm::where('satker_id', $satker->id)->where($isDexTrans)->whereYear('tanggal', $year)->sum('liter');
            
            $psP = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'keluar')->where($isPertamaxTopup)->whereYear('created_at', $year)->sum('jumlah');
            $psD = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'keluar')->where($isDexTopup)->whereYear('created_at', $year)->sum('jumlah');
            
            $hutangP = \App\Models\Hutang::where('satker_id', $satker->id)->where($isPertamaxTrans)->whereYear('tanggal_bon', $year)->sum('jumlah_bon');
            $hutangD = \App\Models\Hutang::where('satker_id', $satker->id)->where($isDexTrans)->whereYear('tanggal_bon', $year)->sum('jumlah_bon');
            
            $pemakaianPertamax += $psP + $hutangP;
            $pemakaianDex += $psD + $hutangD;"""

    # Satker's index loop
    search_block3 = """            $pendapatanPertamax = RiwayatTopup::where('satker_id', $satker->id)
                ->where('tipe', 'masuk')->where($isPertamaxTopup)->whereYear('created_at', $year)->sum('jumlah');

            $pendapatanDex = RiwayatTopup::where('satker_id', $satker->id)
                ->where('tipe', 'masuk')->where($isDexTopup)->whereYear('created_at', $year)->sum('jumlah');

            $pemakaianPertamax = TransaksiBbm::where('satker_id', $satker->id)
                ->where($isPertamaxTrans)->whereYear('tanggal', $year)->sum('liter');

            $pemakaianDex = TransaksiBbm::where('satker_id', $satker->id)
                ->where($isDexTrans)->whereYear('tanggal', $year)->sum('liter');
            
            $psP = RiwayatTopup::where('satker_id', $satker->id)
                ->where('tipe', 'keluar')->where($isPertamaxTopup)->whereYear('created_at', $year)->sum('jumlah');
            
            $psD = RiwayatTopup::where('satker_id', $satker->id)
                ->where('tipe', 'keluar')->where($isDexTopup)->whereYear('created_at', $year)->sum('jumlah');
            
            $hutangP = \App\Models\Hutang::where('satker_id', $satker->id)
                ->where($isPertamaxTrans)->whereYear('tanggal_bon', $year)->sum('jumlah_bon');
            $hutangD = \App\Models\Hutang::where('satker_id', $satker->id)
                ->where($isDexTrans)->whereYear('tanggal_bon', $year)->sum('jumlah_bon');
            
            $pemakaianPertamax += $psP + $hutangP;
            $pemakaianDex += $psD + $hutangD;"""

    # Satker's print loop
    search_block4 = """            $pendapatanPertamax = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'masuk')->where($isPertamaxTopup)->whereYear('created_at', $year)->sum('jumlah');
            $pendapatanDex = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'masuk')->where($isDexTopup)->whereYear('created_at', $year)->sum('jumlah');
            $pemakaianPertamax = TransaksiBbm::where('satker_id', $satker->id)->where($isPertamaxTrans)->whereYear('tanggal', $year)->sum('liter');
            $pemakaianDex = TransaksiBbm::where('satker_id', $satker->id)->where($isDexTrans)->whereYear('tanggal', $year)->sum('liter');
            
            $psP = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'keluar')->where($isPertamaxTopup)->whereYear('created_at', $year)->sum('jumlah');
            $psD = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'keluar')->where($isDexTopup)->whereYear('created_at', $year)->sum('jumlah');
            
            $hutang_p = \App\Models\Hutang::where('satker_id', $satker->id)->where($isPertamaxTrans)->whereYear('tanggal_bon', $year)->sum('jumlah_bon');
            $hutang_d = \App\Models\Hutang::where('satker_id', $satker->id)->where($isDexTrans)->whereYear('tanggal_bon', $year)->sum('jumlah_bon');

            $pemakaianPertamax += $psP + $hutang_p;
            $pemakaianDex += $psD + $hutang_d;"""

    replace_block = """            // --- PENDAPATAN ---
            $pendapatanPertamax = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'masuk')->where($isPertamaxTopup)->whereYear('created_at', $year)->sum('jumlah');
            $pendapatanPertamax += \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satker->id)->whereHas('tujuanKendaraan', function($k) { $k->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })->whereYear('created_at', $year)->sum('jumlah')
                + \App\Models\RiwayatTransferAntarPersonel::where('satker_id', $satker->id)->whereHas('targetKendaraan', function($k) { $k->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })->whereYear('created_at', $year)->sum('jumlah');

            $pendapatanDex = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'masuk')->where($isDexTopup)->whereYear('created_at', $year)->sum('jumlah');
            $pendapatanDex += \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satker->id)->whereHas('tujuanKendaraan', function($k) { $k->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })->whereYear('created_at', $year)->sum('jumlah')
                + \App\Models\RiwayatTransferAntarPersonel::where('satker_id', $satker->id)->whereHas('targetKendaraan', function($k) { $k->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })->whereYear('created_at', $year)->sum('jumlah');

            // --- PEMAKAIAN ---
            $pemakaianPertamax = TransaksiBbm::where('satker_id', $satker->id)->where($isPertamaxTrans)->whereYear('tanggal', $year)->sum('liter');
            $pemakaianDex = TransaksiBbm::where('satker_id', $satker->id)->where($isDexTrans)->whereYear('tanggal', $year)->sum('liter');
            
            // Hutang
            $hutangP = \App\Models\Hutang::where('satker_id', $satker->id)->where($isPertamaxTrans)->whereYear('tanggal_bon', $year)->sum('jumlah_bon');
            $hutangD = \App\Models\Hutang::where('satker_id', $satker->id)->where($isDexTrans)->whereYear('tanggal_bon', $year)->sum('jumlah_bon');

            // Potong Saldo / Keluar
            $psP = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'keluar')->where($isPertamaxTopup)->whereYear('created_at', $year)->sum('jumlah');
            $psD = RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'keluar')->where($isDexTopup)->whereYear('created_at', $year)->sum('jumlah');

            // Transfer Keluar (Dari Kendaraan)
            $tkP = \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satker->id)->whereHas('kendaraan', function($k) { $k->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })->whereYear('created_at', $year)->sum('jumlah')
                + \App\Models\RiwayatTransferAntarPersonel::where('satker_id', $satker->id)->whereHas('asalKendaraan', function($k) { $k->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })->whereYear('created_at', $year)->sum('jumlah');
            
            $tkD = \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satker->id)->whereHas('kendaraan', function($k) { $k->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })->whereYear('created_at', $year)->sum('jumlah')
                + \App\Models\RiwayatTransferAntarPersonel::where('satker_id', $satker->id)->whereHas('asalKendaraan', function($k) { $k->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })->whereYear('created_at', $year)->sum('jumlah');
            
            $pemakaianPertamax += $psP + $hutangP + $tkP;
            $pemakaianDex += $psD + $hutangD + $tkD;"""

    content = content.replace(search_block1, replace_block)
    content = content.replace(search_block2, replace_block)
    content = content.replace(search_block3, replace_block)
    content = content.replace(search_block4, replace_block)

    with open(file_path, "w") as f:
        f.write(content)
        
process_tahunan("d:\\PROJEK\\BBM\\app\\Http\\Controllers\\Admin\\LaporanTahunanController.php")
process_tahunan("d:\\PROJEK\\BBM\\app\\Http\\Controllers\\Satker\\LaporanTahunanController.php")
