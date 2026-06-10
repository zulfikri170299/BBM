import os

def fix_tahunan(file_path):
    with open(file_path, "r") as f:
        content = f.read()

    s = """            // Transfer Keluar (Dari Kendaraan)
            $tkP = \\App\\Models\\RiwayatTransferSaldoPersonel::where('satker_id', $satker->id)->whereHas('kendaraan', function($k) { $k->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })->whereYear('created_at', $year)->sum('jumlah')
                + \\App\\Models\\RiwayatTransferAntarPersonel::where('satker_id', $satker->id)->whereHas('asalKendaraan', function($k) { $k->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })->whereYear('created_at', $year)->sum('jumlah');
            
            $tkD = \\App\\Models\\RiwayatTransferSaldoPersonel::where('satker_id', $satker->id)->whereHas('kendaraan', function($k) { $k->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })->whereYear('created_at', $year)->sum('jumlah')
                + \\App\\Models\\RiwayatTransferAntarPersonel::where('satker_id', $satker->id)->whereHas('asalKendaraan', function($k) { $k->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })->whereYear('created_at', $year)->sum('jumlah');"""

    r = """            // Transfer Keluar (Dari Kendaraan)
            $tkP = \\App\\Models\\RiwayatTransferSaldoPersonel::where('satker_id', $satker->id)->whereHas('kendaraan', function($k) { $k->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX'); })->whereYear('created_at', $year)->sum('jumlah');
            
            $tkD = \\App\\Models\\RiwayatTransferSaldoPersonel::where('satker_id', $satker->id)->whereHas('kendaraan', function($k) { $k->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX'); })->whereYear('created_at', $year)->sum('jumlah');"""
            
    content = content.replace(s, r)
    with open(file_path, "w") as f:
        f.write(content)

def fix_admin_triwulan(file_path):
    with open(file_path, "r") as f:
        content = f.read()

    s = """        $tkPersonelRaw = \\App\\Models\\RiwayatTransferSaldoPersonel::join('kendaraans', 'riwayat_transfer_saldo_personels.kendaraan_id', '=', 'kendaraans.id')
            ->select('riwayat_transfer_saldo_personels.satker_id', \\Illuminate\\Support\\Facades\\DB::raw("COALESCE(NULLIF(kendaraans.jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"), \\Illuminate\\Support\\Facades\\DB::raw('SUM(riwayat_transfer_saldo_personels.jumlah) as total'))
            ->whereBetween('riwayat_transfer_saldo_personels.created_at', [$startUtc, $endUtc])
            ->groupBy('riwayat_transfer_saldo_personels.satker_id', 'kendaraans.jenis_bbm')
            ->get();
            
        $tkAntarRaw = \\App\\Models\\RiwayatTransferAntarPersonel::join('kendaraans', 'riwayat_transfer_antar_personels.asal_kendaraan_id', '=', 'kendaraans.id')
            ->select('riwayat_transfer_antar_personels.satker_id', \\Illuminate\\Support\\Facades\\DB::raw("COALESCE(NULLIF(kendaraans.jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"), \\Illuminate\\Support\\Facades\\DB::raw('SUM(riwayat_transfer_antar_personels.jumlah) as total'))
            ->whereBetween('riwayat_transfer_antar_personels.created_at', [$startUtc, $endUtc])
            ->groupBy('riwayat_transfer_antar_personels.satker_id', 'kendaraans.jenis_bbm')
            ->get();

        foreach($tkPersonelRaw as $item) {
            $pemakaian[$item->satker_id][$item->jenis_bbm] = ($pemakaian[$item->satker_id][$item->jenis_bbm] ?? 0) + $item->total;
        }
        foreach($tkAntarRaw as $item) {
            $pemakaian[$item->satker_id][$item->jenis_bbm] = ($pemakaian[$item->satker_id][$item->jenis_bbm] ?? 0) + $item->total;
        }"""
        
    r = """        $tkPersonelRaw = \\App\\Models\\RiwayatTransferSaldoPersonel::join('kendaraans', 'riwayat_transfer_saldo_personels.kendaraan_id', '=', 'kendaraans.id')
            ->select('riwayat_transfer_saldo_personels.satker_id', \\Illuminate\\Support\\Facades\\DB::raw("COALESCE(NULLIF(kendaraans.jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"), \\Illuminate\\Support\\Facades\\DB::raw('SUM(riwayat_transfer_saldo_personels.jumlah) as total'))
            ->whereBetween('riwayat_transfer_saldo_personels.created_at', [$startUtc, $endUtc])
            ->groupBy('riwayat_transfer_saldo_personels.satker_id', 'kendaraans.jenis_bbm')
            ->get();

        foreach($tkPersonelRaw as $item) {
            $pemakaian[$item->satker_id][$item->jenis_bbm] = ($pemakaian[$item->satker_id][$item->jenis_bbm] ?? 0) + $item->total;
        }"""

    content = content.replace(s, r)
    with open(file_path, "w") as f:
        f.write(content)

def fix_satker_triwulan(file_path):
    with open(file_path, "r") as f:
        content = f.read()

    s = """        $queryTkP1 = \\App\\Models\\RiwayatTransferSaldoPersonel::join('kendaraans', 'riwayat_transfer_saldo_personels.kendaraan_id', '=', 'kendaraans.id')
            ->select(\\Illuminate\\Support\\Facades\\DB::raw("COALESCE(NULLIF(kendaraans.jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"), \\Illuminate\\Support\\Facades\\DB::raw('SUM(riwayat_transfer_saldo_personels.jumlah) as total'))
            ->whereBetween('riwayat_transfer_saldo_personels.created_at', [$startUtc, $endUtc]);
        if (!$isSuperAdmin) { $queryTkP1->where('riwayat_transfer_saldo_personels.satker_id', $satker->id); }
        $tkP1 = $queryTkP1->groupBy('kendaraans.jenis_bbm')->get();

        $queryTkP2 = \\App\\Models\\RiwayatTransferAntarPersonel::join('kendaraans', 'riwayat_transfer_antar_personels.asal_kendaraan_id', '=', 'kendaraans.id')
            ->select(\\Illuminate\\Support\\Facades\\DB::raw("COALESCE(NULLIF(kendaraans.jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"), \\Illuminate\\Support\\Facades\\DB::raw('SUM(riwayat_transfer_antar_personels.jumlah) as total'))
            ->whereBetween('riwayat_transfer_antar_personels.created_at', [$startUtc, $endUtc]);
        if (!$isSuperAdmin) { $queryTkP2->where('riwayat_transfer_antar_personels.satker_id', $satker->id); }
        $tkP2 = $queryTkP2->groupBy('kendaraans.jenis_bbm')->get();

        foreach($tkP1 as $item) { $pemakaian[$item->jenis_bbm] = ($pemakaian[$item->jenis_bbm] ?? 0) + $item->total; }
        foreach($tkP2 as $item) { $pemakaian[$item->jenis_bbm] = ($pemakaian[$item->jenis_bbm] ?? 0) + $item->total; }"""
        
    r = """        $queryTkP1 = \\App\\Models\\RiwayatTransferSaldoPersonel::join('kendaraans', 'riwayat_transfer_saldo_personels.kendaraan_id', '=', 'kendaraans.id')
            ->select(\\Illuminate\\Support\\Facades\\DB::raw("COALESCE(NULLIF(kendaraans.jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"), \\Illuminate\\Support\\Facades\\DB::raw('SUM(riwayat_transfer_saldo_personels.jumlah) as total'))
            ->whereBetween('riwayat_transfer_saldo_personels.created_at', [$startUtc, $endUtc]);
        if (!$isSuperAdmin) { $queryTkP1->where('riwayat_transfer_saldo_personels.satker_id', $satker->id); }
        $tkP1 = $queryTkP1->groupBy('kendaraans.jenis_bbm')->get();

        foreach($tkP1 as $item) { $pemakaian[$item->jenis_bbm] = ($pemakaian[$item->jenis_bbm] ?? 0) + $item->total; }"""

    content = content.replace(s, r)
    with open(file_path, "w") as f:
        f.write(content)

fix_tahunan("d:\\PROJEK\\BBM\\app\\Http\\Controllers\\Admin\\LaporanTahunanController.php")
fix_tahunan("d:\\PROJEK\\BBM\\app\\Http\\Controllers\\Satker\\LaporanTahunanController.php")
fix_admin_triwulan("d:\\PROJEK\\BBM\\app\\Http\\Controllers\\Admin\\LaporanTriwulanController.php")
fix_satker_triwulan("d:\\PROJEK\\BBM\\app\\Http\\Controllers\\Satker\\LaporanTriwulanController.php")
