<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SatisfactionIndex;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Traits\PaginatesTables;

class SatisfactionController extends Controller
{
    use PaginatesTables;

    private function getDaftarBulan(): array
    {
        return [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
    }

    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);
        $bulan = $request->get('bulan');

        $query = SatisfactionIndex::with('user.satker', 'user.personel');
        if ($bulan && $bulan !== 'all') {
            $query->whereMonth('created_at', $bulan);
        }

        $ratings = $query->latest()
            ->paginate($perPage)
            ->withQueryString();

        $statsQuery = SatisfactionIndex::query();
        if ($bulan && $bulan !== 'all') {
            $statsQuery->whereMonth('created_at', $bulan);
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'avg' => (clone $statsQuery)->avg('rating'),
            'sangat_puas' => (clone $statsQuery)->where('rating', '=', '3')->count(),
            'puas' => (clone $statsQuery)->where('rating', '=', '2')->count(),
            'tidak_puas' => (clone $statsQuery)->where('rating', '=', '1')->count(),
        ];

        $daftarBulan = $this->getDaftarBulan();

        return view('admin.satisfaction.index', compact('ratings', 'stats', 'bulan', 'daftarBulan'));
    }

    public function print(Request $request)
    {
        $bulan = $request->get('bulan');
        $tahun = session('filter_tahun', date('Y'));

        $query = SatisfactionIndex::with('user.satker', 'user.personel');
        if ($bulan && $bulan !== 'all') {
            $query->whereMonth('created_at', $bulan);
        }

        $ratings = $query->latest()->get();

        $statsQuery = SatisfactionIndex::query();
        if ($bulan && $bulan !== 'all') {
            $statsQuery->whereMonth('created_at', $bulan);
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'avg' => (clone $statsQuery)->avg('rating'),
            'sangat_puas' => (clone $statsQuery)->where('rating', '=', '3')->count(),
            'puas' => (clone $statsQuery)->where('rating', '=', '2')->count(),
            'tidak_puas' => (clone $statsQuery)->where('rating', '=', '1')->count(),
        ];

        $daftarBulan = $this->getDaftarBulan();
        $namaBulan = ($bulan && isset($daftarBulan[(int)$bulan])) ? $daftarBulan[(int)$bulan] : 'Semua Bulan';
        $periode = ($bulan && isset($daftarBulan[(int)$bulan])) 
            ? "Bulan {$daftarBulan[(int)$bulan]} {$tahun}" 
            : "Tahun {$tahun}";

        $pdf = Pdf::loadView('admin.satisfaction.print', compact('ratings', 'stats', 'bulan', 'tahun', 'namaBulan', 'periode'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-indeks-kepuasan-' . ($bulan && $bulan !== 'all' ? 'bulan-' . $bulan . '-' : '') . date('Y-m-d_H-i') . '.pdf');
    }
}

