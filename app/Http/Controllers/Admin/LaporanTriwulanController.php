<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Satker;
use App\Models\RiwayatTopup;
use App\Models\TransaksiBbm;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanTriwulanController extends Controller
{
    private function getLaporanData(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $triwulan = $request->triwulan ?? 1;

        if ($triwulan == 1) {
            $startDateStr = "$tahun-01-01";
            $endDateStr = "$tahun-03-31";
            $periodeLabel = "Januari-Maret $tahun";
        } elseif ($triwulan == 2) {
            $startDateStr = "$tahun-04-01";
            $endDateStr = "$tahun-06-30";
            $periodeLabel = "April-Juni $tahun";
        } elseif ($triwulan == 3) {
            $startDateStr = "$tahun-07-01";
            $endDateStr = "$tahun-09-30";
            $periodeLabel = "Juli-September $tahun";
        } else {
            $startDateStr = "$tahun-10-01";
            $endDateStr = "$tahun-12-31";
            $periodeLabel = "Oktober-Desember $tahun";
        }

        $startDate = Carbon::createFromFormat('Y-m-d', $startDateStr, 'Asia/Makassar')->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $endDateStr, 'Asia/Makassar')->endOfDay();

        $startUtc = $startDate->copy()->setTimezone('UTC')->format('Y-m-d H:i:s');
        $endUtc = $endDate->copy()->setTimezone('UTC')->format('Y-m-d H:i:s');

        // RiwayatTopup uses created_at (timestamp)
        // TransaksiBbm uses tanggal (custom column)

        $bbmTypesTopup = RiwayatTopup::join('kendaraans', 'riwayat_topups.kendaraan_id', '=', 'kendaraans.id')
            ->whereBetween('riwayat_topups.created_at', [$startUtc, $endUtc])
            ->whereIn('riwayat_topups.metode', ['manual', 'IMPORT', 'massal'])
            ->where('riwayat_topups.tipe', 'masuk')
            ->selectRaw("COALESCE(NULLIF(kendaraans.jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm")
            ->distinct()
            ->pluck('jenis_bbm')
            ->toArray();

        $bbmTypesTransaksi = TransaksiBbm::whereBetween('tanggal', [$startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s')])
            ->selectRaw("COALESCE(NULLIF(jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm")
            ->distinct()
            ->pluck('jenis_bbm')
            ->toArray();
            
        $bbmTypesHutang = \App\Models\Hutang::whereBetween('tanggal_bon', [$startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s')])
            ->selectRaw("COALESCE(NULLIF(jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm")
            ->distinct()
            ->pluck('jenis_bbm')
            ->toArray();

        $allBbmTypes = array_unique(array_merge($bbmTypesTopup, $bbmTypesTransaksi, $bbmTypesHutang));
        sort($allBbmTypes);
        
        if (empty($allBbmTypes)) {
            $allBbmTypes = ['Pertamax', 'Pertamina Dex'];
        }

        $satkers = Satker::orderBy('nama_satker')->get();
        
        $pendapatanRaw = RiwayatTopup::join('kendaraans', 'riwayat_topups.kendaraan_id', '=', 'kendaraans.id')
            ->select(
                'riwayat_topups.satker_id',
                DB::raw("COALESCE(NULLIF(kendaraans.jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"),
                DB::raw("SUM(riwayat_topups.jumlah) as total")
            )
            ->whereBetween('riwayat_topups.created_at', [$startUtc, $endUtc])
            ->where('riwayat_topups.tipe', 'masuk')
            ->groupBy('riwayat_topups.satker_id', 'kendaraans.jenis_bbm')
            ->get();
        
        $pendapatan = [];
        foreach($pendapatanRaw as $item) {
            $pendapatan[$item->satker_id][$item->jenis_bbm] = $item->total;
        }

        $tmPersonelRaw = \App\Models\RiwayatTransferSaldoPersonel::join('kendaraans', 'riwayat_transfer_saldo_personels.tujuan_kendaraan_id', '=', 'kendaraans.id')
            ->select('riwayat_transfer_saldo_personels.satker_id', \Illuminate\Support\Facades\DB::raw("COALESCE(NULLIF(kendaraans.jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"), \Illuminate\Support\Facades\DB::raw('SUM(riwayat_transfer_saldo_personels.jumlah) as total'))
            ->whereBetween('riwayat_transfer_saldo_personels.created_at', [$startUtc, $endUtc])
            ->groupBy('riwayat_transfer_saldo_personels.satker_id', 'kendaraans.jenis_bbm')
            ->get();

        $tmAntarRaw = \App\Models\RiwayatTransferAntarPersonel::join('kendaraans', 'riwayat_transfer_antar_personels.target_kendaraan_id', '=', 'kendaraans.id')
            ->select('riwayat_transfer_antar_personels.satker_id', \Illuminate\Support\Facades\DB::raw("COALESCE(NULLIF(kendaraans.jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"), \Illuminate\Support\Facades\DB::raw('SUM(riwayat_transfer_antar_personels.jumlah) as total'))
            ->whereBetween('riwayat_transfer_antar_personels.created_at', [$startUtc, $endUtc])
            ->groupBy('riwayat_transfer_antar_personels.satker_id', 'kendaraans.jenis_bbm')
            ->get();

        foreach($tmPersonelRaw as $item) {
            $pendapatan[$item->satker_id][$item->jenis_bbm] = ($pendapatan[$item->satker_id][$item->jenis_bbm] ?? 0) + $item->total;
        }
        foreach($tmAntarRaw as $item) {
            $pendapatan[$item->satker_id][$item->jenis_bbm] = ($pendapatan[$item->satker_id][$item->jenis_bbm] ?? 0) + $item->total;
        }

        $pemakaianRaw = TransaksiBbm::select(
                'satker_id',
                DB::raw("COALESCE(NULLIF(jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"),
                DB::raw('SUM(liter) as total')
            )
            ->whereBetween('tanggal', [$startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s')])
            ->groupBy('satker_id', 'jenis_bbm')
            ->get();

        $pemakaian = [];
        foreach($pemakaianRaw as $item) {
            $pemakaian[$item->satker_id][$item->jenis_bbm] = ($pemakaian[$item->satker_id][$item->jenis_bbm] ?? 0) + $item->total;
        }

        // Tambahan: Hitung SEMUA RiwayatTopup 'keluar' (Potong Saldo, POTONG_HUTANG, TRANSFER, dll) sebagai pemakaian
        $potongSaldoRaw = RiwayatTopup::select(
                'satker_id',
                DB::raw("COALESCE(NULLIF(jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"),
                DB::raw('SUM(jumlah) as total')
            )
            ->whereBetween('created_at', [$startUtc, $endUtc])
            ->where('tipe', 'keluar')
            ->groupBy('satker_id', 'jenis_bbm')
            ->get();

        // Tambahan: Hutang bulan ini dihitung sebagai pemakaian
        $hutangRaw = \App\Models\Hutang::select(
                'satker_id',
                DB::raw("COALESCE(NULLIF(jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"),
                DB::raw('SUM(jumlah_bon) as total')
            )
            ->whereBetween('tanggal_bon', [$startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s')])
            ->groupBy('satker_id', 'jenis_bbm')
            ->get();

        foreach($potongSaldoRaw as $item) {
            $pemakaian[$item->satker_id][$item->jenis_bbm] = ($pemakaian[$item->satker_id][$item->jenis_bbm] ?? 0) + $item->total;
        }
        foreach($hutangRaw as $item) {
            $pemakaian[$item->satker_id][$item->jenis_bbm] = ($pemakaian[$item->satker_id][$item->jenis_bbm] ?? 0) + $item->total;
        }

        $tkPersonelRaw = \App\Models\RiwayatTransferSaldoPersonel::join('kendaraans', 'riwayat_transfer_saldo_personels.kendaraan_id', '=', 'kendaraans.id')
            ->select('riwayat_transfer_saldo_personels.satker_id', \Illuminate\Support\Facades\DB::raw("COALESCE(NULLIF(kendaraans.jenis_bbm, ''), 'TANPA JENIS') as jenis_bbm"), \Illuminate\Support\Facades\DB::raw('SUM(riwayat_transfer_saldo_personels.jumlah) as total'))
            ->whereBetween('riwayat_transfer_saldo_personels.created_at', [$startUtc, $endUtc])
            ->groupBy('riwayat_transfer_saldo_personels.satker_id', 'kendaraans.jenis_bbm')
            ->get();

        foreach($tkPersonelRaw as $item) {
            $pemakaian[$item->satker_id][$item->jenis_bbm] = ($pemakaian[$item->satker_id][$item->jenis_bbm] ?? 0) + $item->total;
        }

        $sisaBbm = [];
        foreach($satkers as $satker) {
            foreach($allBbmTypes as $jenis) {
                $p = $pendapatan[$satker->id][$jenis] ?? 0;
                $m = $pemakaian[$satker->id][$jenis] ?? 0;
                $sisaBbm[$satker->id][$jenis] = $p - $m;
            }
        }

        return compact('tahun', 'triwulan', 'periodeLabel', 'allBbmTypes', 'satkers', 'pendapatan', 'pemakaian', 'sisaBbm');
    }

    public function index(Request $request)
    {
        // If year and triwulan not set, don't show data implicitly, wait for user selection
        // Or show for current setting
        $tahun = $request->tahun ?? date('Y');
        $triwulan = $request->triwulan ?? 1;

        $request->merge(['tahun' => $tahun, 'triwulan' => $triwulan]);
        $data = $this->getLaporanData($request);
        
        return view('admin.laporan_triwulan.index', $data);
    }

    public function export(Request $request)
    {
        $request->validate([
            'tahun' => 'required|numeric',
            'triwulan' => 'required|numeric|in:1,2,3,4',
        ]);

        $data = $this->getLaporanData($request);
        extract($data);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $jumlahBbm = count($allBbmTypes);
        $startColData = 3;

        $sheet->setCellValue('A1', 'REKAPAN PER 3 BULAN');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        
        $sheet->setCellValue('A2', "Periode $periodeLabel");
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);

        $sheet->setCellValue('A4', 'NO');
        $sheet->setCellValue('B4', 'SATKER');
        
        $colIndex = 3;
        
        $pendapatanColStart = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
        $sheet->setCellValue($pendapatanColStart . '4', 'JUMLAH PENDAPATAN');
        foreach($allBbmTypes as $jenis) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue($colLetter . '5', strtoupper($jenis));
            $colIndex++;
        }
        $pendapatanColEnd = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex - 1);
        if ($pendapatanColStart !== $pendapatanColEnd) {
            $sheet->mergeCells($pendapatanColStart . '4:' . $pendapatanColEnd . '4');
        }

        $pemakaianColStart = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
        $sheet->setCellValue($pemakaianColStart . '4', 'PEMAKAIAN');
        foreach($allBbmTypes as $jenis) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue($colLetter . '5', strtoupper($jenis));
            $colIndex++;
        }
        $pemakaianColEnd = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex - 1);
        if ($pemakaianColStart !== $pemakaianColEnd) {
            $sheet->mergeCells($pemakaianColStart . '4:' . $pemakaianColEnd . '4');
        }

        $sisaColStart = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
        $sheet->setCellValue($sisaColStart . '4', 'SISA BBM');
        foreach($allBbmTypes as $jenis) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue($colLetter . '5', strtoupper($jenis));
            $colIndex++;
        }
        $sisaColEnd = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex - 1);
        if ($sisaColStart !== $sisaColEnd) {
            $sheet->mergeCells($sisaColStart . '4:' . $sisaColEnd . '4');
        }

        $sheet->mergeCells('A4:A5');
        $sheet->mergeCells('B4:B5');

        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex - 1);
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => 'FFE0E0E0'],
            ]
        ];
        $sheet->getStyle("A4:{$lastCol}5")->applyFromArray($headerStyle);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('A')->setWidth(5);
        for ($i = 3; $i < $colIndex; $i++) {
            $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i))->setWidth(15);
        }

        $row = 6;
        $no = 1;
        
        foreach ($satkers as $satker) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, strtoupper($satker->nama_satker));
            
            $currColIndex = 3;
            $pendapatanCols = [];
            foreach($allBbmTypes as $jenis) {
                $val = $pendapatan[$satker->id][$jenis] ?? 0;
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($currColIndex);
                $sheet->setCellValue($colLetter . $row, $val);
                $pendapatanCols[$jenis] = $colLetter;
                $currColIndex++;
            }
            
            $pemakaianCols = [];
            foreach($allBbmTypes as $jenis) {
                $val = $pemakaian[$satker->id][$jenis] ?? 0;
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($currColIndex);
                $sheet->setCellValue($colLetter . $row, $val);
                $pemakaianCols[$jenis] = $colLetter;
                $currColIndex++;
            }
            
            foreach($allBbmTypes as $jenis) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($currColIndex);
                $valSisa = $sisaBbm[$satker->id][$jenis] ?? 0;
                $sheet->setCellValue($colLetter . $row, $valSisa);
                $currColIndex++;
            }
            
            $no++;
            $row++;
        }

        $sheet->setCellValue('B' . $row, 'TOTAL');
        $sheet->getStyle("A{$row}:B{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $lastDataRow = $row - 1;
        if ($lastDataRow >= 6) {
            for ($i = 3; $i < $colIndex; $i++) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
                $sheet->setCellValue("{$colLetter}{$row}", "=SUM({$colLetter}6:{$colLetter}{$lastDataRow})");
                $sheet->getStyle("{$colLetter}{$row}")->getFont()->setBold(true);
            }
        }

        $dataStyle = [
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $sheet->getStyle("A6:{$lastCol}{$row}")->applyFromArray($dataStyle);
        $sheet->getStyle("B6:B" . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Laporan_Triwulan_' . $tahun . '_T' . $triwulan . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function print(Request $request)
    {
        $request->validate([
            'tahun' => 'required|numeric',
            'triwulan' => 'required|numeric|in:1,2,3,4',
        ]);

        $data = $this->getLaporanData($request);

        $pdf = Pdf::loadView('admin.laporan_triwulan.print', $data)
            ->setPaper([0, 0, 609.45, 935.43], 'landscape');

        return $pdf->stream('laporan-triwulan-' . $data['tahun'] . '-T' . $data['triwulan'] . '-' . date('Y-m-d_H-i') . '.pdf');
    }
}
