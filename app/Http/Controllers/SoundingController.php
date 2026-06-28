<?php

namespace App\Http\Controllers;

use App\Models\Sounding;
use App\Models\TransaksiBbm;
use App\Models\Hutang;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class SoundingController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now('Asia/Makassar')->format('m'));
        $tahun = $request->get('tahun', Carbon::now('Asia/Makassar')->format('Y'));
        $tw = $request->get('tw'); // Triwulan
        $jenis_bbm = $request->get('jenis_bbm');

        $query = Sounding::with('petugas')->orderBy('tanggal', 'asc');

        if ($tw) {
            $months = [];
            if ($tw == 1) $months = [1, 2, 3];
            elseif ($tw == 2) $months = [4, 5, 6];
            elseif ($tw == 3) $months = [7, 8, 9];
            elseif ($tw == 4) $months = [10, 11, 12];
            $query->where(function($q) use ($months) {
                foreach($months as $m) {
                    $q->orWhereMonth('tanggal', $m);
                }
            })->whereYear('tanggal', $tahun);
        } else {
            $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        }

        if ($jenis_bbm) {
            $query->where('jenis_bbm', $jenis_bbm);
        }

        $soundings = $query->get();
        
        $rolePrefix = auth()->user()->role === 'super_admin' ? 'admin' : 'petugas';

        return view('sounding.index', compact('soundings', 'bulan', 'tahun', 'tw', 'jenis_bbm', 'rolePrefix'));
    }

    public function create(Request $request)
    {
        $rolePrefix = auth()->user()->role === 'super_admin' ? 'admin' : 'petugas';
        $type = $request->query('type');
        
        if ($type === 'awal') {
            return view('sounding.create_awal', compact('rolePrefix'));
        } elseif ($type === 'akhir') {
            return view('sounding.create_akhir', compact('rolePrefix'));
        }

        return view('sounding.create_choice', compact('rolePrefix'));
    }

    public function getAwal(Request $request)
    {
        $tanggal = $request->get('tanggal');
        $jenis_bbm = $request->get('jenis_bbm');

        if (!$tanggal || !$jenis_bbm) {
            return response()->json(['found' => false]);
        }

        $sounding = Sounding::where('tanggal', $tanggal)->where('jenis_bbm', $jenis_bbm)->first();

        if ($sounding) {
            return response()->json([
                'found' => true,
                'id' => $sounding->id,
                'stok_awal' => $sounding->stok_awal
            ]);
        }

        return response()->json(['found' => false]);
    }

    // Ajax endpoint for getting pengeluaran
    public function getPengeluaran(Request $request)
    {
        $tanggal = $request->get('tanggal');
        $jenis_bbm = $request->get('jenis_bbm');

        if (!$tanggal || !$jenis_bbm) {
            return response()->json(['pengeluaran' => 0]);
        }

        // Transaksi BBM
        $transaksi = TransaksiBbm::leftJoin('kendaraans', 'transaksi_bbms.kendaraan_id', '=', 'kendaraans.id')
            ->leftJoin('personels', 'transaksi_bbms.personel_id', '=', 'personels.id')
            ->whereDate('transaksi_bbms.tanggal', $tanggal)
            ->where(function($q) use ($jenis_bbm) {
                $q->where('kendaraans.jenis_bbm', $jenis_bbm)
                  ->orWhere('personels.jenis_bbm', $jenis_bbm);
            })
            ->sum('liter');

        // Hutang BBM
        $hutang = Hutang::whereDate('tanggal_bon', $tanggal)
            ->where('jenis_bbm', $jenis_bbm)
            ->sum('jumlah_bon');

        $totalPengeluaran = $transaksi + $hutang;

        return response()->json(['pengeluaran' => $totalPengeluaran]);
    }

    public function store(Request $request)
    {
        // This is now used exclusively for Sounding Awal
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_bbm' => 'required|string',
            'stok_awal' => 'required|integer',
            'dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg|max:10240', // max 10MB
        ]);

        // Check if exists
        $exists = Sounding::where('tanggal', $request->tanggal)->where('jenis_bbm', $request->jenis_bbm)->exists();
        if ($exists) {
            return back()->withInput()->with('error', 'Data Sounding Awal untuk Tanggal dan Jenis BBM tersebut sudah ada.');
        }

        $data = $request->except('dokumentasi');
        $data['petugas_id'] = auth()->id();

        if ($request->hasFile('dokumentasi')) {
            $file = $request->file('dokumentasi');
            $filename = time() . '_' . uniqid() . '.jpg';
            
            // Compress Image
            $destinationPath = storage_path('app/public/sounding');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $this->compressImage($file->getRealPath(), $destinationPath . '/' . $filename, 30); // 30% quality for high compression
            
            $data['dokumentasi'] = 'sounding/' . $filename;
        }

        $data['stok_akhir'] = 0;
        $data['pengeluaran_aplikasi'] = 0;

        Sounding::create($data);

        $rolePrefix = auth()->user()->role === 'super_admin' ? 'admin' : 'petugas';
        return redirect()->route($rolePrefix . '.sounding.index')->with('success', 'Data Sounding Awal berhasil ditambahkan.');
    }

    public function storeAkhir(Request $request)
    {
        $request->validate([
            'sounding_id' => 'required|exists:soundings,id',
            'stok_akhir' => 'required|integer',
            'pengeluaran_aplikasi' => 'required|integer',
            'dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        $sounding = Sounding::findOrFail($request->sounding_id);

        $data = [
            'stok_akhir' => $request->stok_akhir,
            'pengeluaran_aplikasi' => $request->pengeluaran_aplikasi,
        ];

        if ($request->hasFile('dokumentasi')) {
            // Delete old file if exists
            if ($sounding->dokumentasi && Storage::disk('public')->exists($sounding->dokumentasi)) {
                Storage::disk('public')->delete($sounding->dokumentasi);
            }

            $file = $request->file('dokumentasi');
            $filename = time() . '_' . uniqid() . '.jpg';
            
            $destinationPath = storage_path('app/public/sounding');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $this->compressImage($file->getRealPath(), $destinationPath . '/' . $filename, 30);
            
            $data['dokumentasi'] = 'sounding/' . $filename;
        }

        $sounding->update($data);

        $rolePrefix = auth()->user()->role === 'super_admin' ? 'admin' : 'petugas';
        return redirect()->route($rolePrefix . '.sounding.index')->with('success', 'Data Sounding Akhir berhasil ditambahkan.');
    }

    public function edit(Sounding $sounding)
    {
        $rolePrefix = auth()->user()->role === 'super_admin' ? 'admin' : 'petugas';
        return view('sounding.edit', compact('sounding', 'rolePrefix'));
    }

    public function update(Request $request, Sounding $sounding)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_bbm' => 'required|string',
            'stok_awal' => 'required|integer',
            'stok_akhir' => 'required|integer',
            'pengeluaran_aplikasi' => 'required|integer',
            'dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        $data = $request->except('dokumentasi');

        if ($request->hasFile('dokumentasi')) {
            // Delete old file if exists
            if ($sounding->dokumentasi && Storage::disk('public')->exists($sounding->dokumentasi)) {
                Storage::disk('public')->delete($sounding->dokumentasi);
            }

            $file = $request->file('dokumentasi');
            $filename = time() . '_' . uniqid() . '.jpg';
            
            $destinationPath = storage_path('app/public/sounding');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $this->compressImage($file->getRealPath(), $destinationPath . '/' . $filename, 30);
            
            $data['dokumentasi'] = 'sounding/' . $filename;
        }

        $sounding->update($data);

        $rolePrefix = auth()->user()->role === 'super_admin' ? 'admin' : 'petugas';
        return redirect()->route($rolePrefix . '.sounding.index')->with('success', 'Data Sounding berhasil diperbarui.');
    }

    public function destroy(Sounding $sounding)
    {
        if ($sounding->dokumentasi && Storage::disk('public')->exists($sounding->dokumentasi)) {
            Storage::disk('public')->delete($sounding->dokumentasi);
        }
        $sounding->delete();
        return back()->with('success', 'Data Sounding berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now('Asia/Makassar')->format('m'));
        $tahun = $request->get('tahun', Carbon::now('Asia/Makassar')->format('Y'));
        $tw = $request->get('tw'); 
        $jenis_bbm = $request->get('jenis_bbm');

        $query = Sounding::with('petugas')->orderBy('tanggal', 'asc');

        $judulBulan = '';
        if ($tw) {
            $months = [];
            if ($tw == 1) $months = [1, 2, 3];
            elseif ($tw == 2) $months = [4, 5, 6];
            elseif ($tw == 3) $months = [7, 8, 9];
            elseif ($tw == 4) $months = [10, 11, 12];
            $query->where(function($q) use ($months) {
                foreach($months as $m) {
                    $q->orWhereMonth('tanggal', $m);
                }
            })->whereYear('tanggal', $tahun);
            $judulBulan = "TRIWULAN $tw TAHUN $tahun";
        } else {
            $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            $namaBulan = Carbon::createFromFormat('m', $bulan)->translatedFormat('F');
            $judulBulan = "BULAN " . strtoupper($namaBulan) . " TAHUN $tahun";
        }

        if ($jenis_bbm) {
            $query->where('jenis_bbm', $jenis_bbm);
        }

        $soundings = $query->get();

        $pdf = Pdf::loadView('sounding.pdf', compact('soundings', 'judulBulan'))
            ->setPaper('a4', 'landscape');

        return $pdf->download("Laporan_Sounding_BBM.pdf");
    }

    private function compressImage($source, $destination, $quality) {
        $info = getimagesize($source);
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source);
            // Convert to JPEG for better compression
            $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
            imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
            imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
            $image = $bg;
        } else {
            return false;
        }
        
        // Resize if it's too large to save space
        $width = imagesx($image);
        $height = imagesy($image);
        if ($width > 1200) {
            $newWidth = 1200;
            $newHeight = floor($height * ($newWidth / $width));
            $tmp = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($tmp, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            $image = $tmp;
        }

        imagejpeg($image, $destination, $quality);
        return true;
    }
}
