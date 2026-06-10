import os

def process_admin_triwulan(file_path):
    with open(file_path, "r") as f:
        content = f.read()

    # Block 1 - Admin TM
    s1 = """        $pendapatan = [];
        foreach($pendapatanRaw as $item) {
            $pendapatan[$item->satker_id][$item->jenis_bbm] = $item->total;
        }"""
        
    r1 = """        $pendapatan = [];
        foreach($pendapatanRaw as $item) {
            $pendapatan[$item->satker_id][$item->jenis_bbm] = $item->total;
        }

        $tmPersonelRaw = \\App\\Models\\RiwayatTransferSaldoPersonel::join('kendaraans', 'riwayat_transfer_saldo_personels.tujuan_kendaraan_id', '=', 'kendaraans.id')
            ->select('riwayat_transfer_saldo_personels.satker_id', \\Illuminate\\Support\\Facades\\DB::raw("COALESCE(NULLIF(kendaraans.jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"), \\Illuminate\\Support\\Facades\\DB::raw('SUM(riwayat_transfer_saldo_personels.jumlah) as total'))
            ->whereBetween('riwayat_transfer_saldo_personels.created_at', [$startUtc, $endUtc])
            ->groupBy('riwayat_transfer_saldo_personels.satker_id', 'kendaraans.jenis_bbm')
            ->get();

        $tmAntarRaw = \\App\\Models\\RiwayatTransferAntarPersonel::join('kendaraans', 'riwayat_transfer_antar_personels.target_kendaraan_id', '=', 'kendaraans.id')
            ->select('riwayat_transfer_antar_personels.satker_id', \\Illuminate\\Support\\Facades\\DB::raw("COALESCE(NULLIF(kendaraans.jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"), \\Illuminate\\Support\\Facades\\DB::raw('SUM(riwayat_transfer_antar_personels.jumlah) as total'))
            ->whereBetween('riwayat_transfer_antar_personels.created_at', [$startUtc, $endUtc])
            ->groupBy('riwayat_transfer_antar_personels.satker_id', 'kendaraans.jenis_bbm')
            ->get();

        foreach($tmPersonelRaw as $item) {
            $pendapatan[$item->satker_id][$item->jenis_bbm] = ($pendapatan[$item->satker_id][$item->jenis_bbm] ?? 0) + $item->total;
        }
        foreach($tmAntarRaw as $item) {
            $pendapatan[$item->satker_id][$item->jenis_bbm] = ($pendapatan[$item->satker_id][$item->jenis_bbm] ?? 0) + $item->total;
        }"""

    # Block 2 - Admin TK
    s2 = """        foreach($potongSaldoRaw as $item) {
            $pemakaian[$item->satker_id][$item->jenis_bbm] = ($pemakaian[$item->satker_id][$item->jenis_bbm] ?? 0) + $item->total;
        }
        foreach($hutangRaw as $item) {
            $pemakaian[$item->satker_id][$item->jenis_bbm] = ($pemakaian[$item->satker_id][$item->jenis_bbm] ?? 0) + $item->total;
        }"""
        
    r2 = """        foreach($potongSaldoRaw as $item) {
            $pemakaian[$item->satker_id][$item->jenis_bbm] = ($pemakaian[$item->satker_id][$item->jenis_bbm] ?? 0) + $item->total;
        }
        foreach($hutangRaw as $item) {
            $pemakaian[$item->satker_id][$item->jenis_bbm] = ($pemakaian[$item->satker_id][$item->jenis_bbm] ?? 0) + $item->total;
        }

        $tkPersonelRaw = \\App\\Models\\RiwayatTransferSaldoPersonel::join('kendaraans', 'riwayat_transfer_saldo_personels.kendaraan_id', '=', 'kendaraans.id')
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

    if s1 in content: content = content.replace(s1, r1)
    if s2 in content: content = content.replace(s2, r2)
    with open(file_path, "w") as f: f.write(content)

def process_satker_triwulan(file_path):
    with open(file_path, "r") as f:
        content = f.read()

    # Block 1 - Satker TM
    s1 = """        $pendapatan = [];
        foreach($pendapatanRaw as $item) {
            $pendapatan[$item->jenis_bbm] = $item->total;
        }"""
    r1 = """        $pendapatan = [];
        foreach($pendapatanRaw as $item) {
            $pendapatan[$item->jenis_bbm] = $item->total;
        }
        $queryTmP1 = \\App\\Models\\RiwayatTransferSaldoPersonel::join('kendaraans', 'riwayat_transfer_saldo_personels.tujuan_kendaraan_id', '=', 'kendaraans.id')
            ->select(\\Illuminate\\Support\\Facades\\DB::raw("COALESCE(NULLIF(kendaraans.jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"), \\Illuminate\\Support\\Facades\\DB::raw('SUM(riwayat_transfer_saldo_personels.jumlah) as total'))
            ->whereBetween('riwayat_transfer_saldo_personels.created_at', [$startUtc, $endUtc]);
        if (!$isSuperAdmin) { $queryTmP1->where('riwayat_transfer_saldo_personels.satker_id', $satker->id); }
        $tmP1 = $queryTmP1->groupBy('kendaraans.jenis_bbm')->get();

        $queryTmP2 = \\App\\Models\\RiwayatTransferAntarPersonel::join('kendaraans', 'riwayat_transfer_antar_personels.target_kendaraan_id', '=', 'kendaraans.id')
            ->select(\\Illuminate\\Support\\Facades\\DB::raw("COALESCE(NULLIF(kendaraans.jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"), \\Illuminate\\Support\\Facades\\DB::raw('SUM(riwayat_transfer_antar_personels.jumlah) as total'))
            ->whereBetween('riwayat_transfer_antar_personels.created_at', [$startUtc, $endUtc]);
        if (!$isSuperAdmin) { $queryTmP2->where('riwayat_transfer_antar_personels.satker_id', $satker->id); }
        $tmP2 = $queryTmP2->groupBy('kendaraans.jenis_bbm')->get();

        foreach($tmP1 as $item) { $pendapatan[$item->jenis_bbm] = ($pendapatan[$item->jenis_bbm] ?? 0) + $item->total; }
        foreach($tmP2 as $item) { $pendapatan[$item->jenis_bbm] = ($pendapatan[$item->jenis_bbm] ?? 0) + $item->total; }"""

    # Block 2 - Satker TK
    s2 = """        foreach($potongSaldoRaw as $item) {
            $pemakaian[$item->jenis_bbm] = ($pemakaian[$item->jenis_bbm] ?? 0) + $item->total;
        }
        foreach($hutangRaw as $item) {
            $pemakaian[$item->jenis_bbm] = ($pemakaian[$item->jenis_bbm] ?? 0) + $item->total;
        }"""
    r2 = """        foreach($potongSaldoRaw as $item) {
            $pemakaian[$item->jenis_bbm] = ($pemakaian[$item->jenis_bbm] ?? 0) + $item->total;
        }
        foreach($hutangRaw as $item) {
            $pemakaian[$item->jenis_bbm] = ($pemakaian[$item->jenis_bbm] ?? 0) + $item->total;
        }

        $queryTkP1 = \\App\\Models\\RiwayatTransferSaldoPersonel::join('kendaraans', 'riwayat_transfer_saldo_personels.kendaraan_id', '=', 'kendaraans.id')
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

    if s1 in content: content = content.replace(s1, r1)
    if s2 in content: content = content.replace(s2, r2)
    with open(file_path, "w") as f: f.write(content)

process_admin_triwulan("d:\\PROJEK\\BBM\\app\\Http\\Controllers\\Admin\\LaporanTriwulanController.php")
process_satker_triwulan("d:\\PROJEK\\BBM\\app\\Http\\Controllers\\Satker\\LaporanTriwulanController.php")
