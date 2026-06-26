<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'super_admin' || $user->role === 'kasubbag') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'admin_satker') {
        return redirect()->route('satker.dashboard');
    } elseif ($user->role === 'petugas_bbm') {
        return redirect()->route('petugas.dashboard');
    } elseif ($user->role === 'personel') {
        return redirect()->route('personel.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/topup-password', [ProfileController::class, 'updateTopupPassword'])->name('profile.topup-password.update');
    Route::post('/profile/topup-password/reset', [ProfileController::class, 'resetTopupPassword'])->name('profile.topup-password.reset');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/location', [ProfileController::class, 'updateLocation'])->name('profile.location.update');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');

    Route::post('/notifications/{id}/read', function ($id) {
        auth()->user()->notifications()->findOrFail($id)->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.read');

    // Chat Routes
    Route::get('/chat/unread/count', [\App\Http\Controllers\ChatController::class, 'unreadCount'])->name('chat.unread.count');
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{receiver}', [\App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    Route::get('/chat/{receiver}/messages', [\App\Http\Controllers\ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/{receiver}', [\App\Http\Controllers\ChatController::class, 'store'])->name('chat.store');
    Route::delete('/chat/{chat}', [\App\Http\Controllers\ChatController::class, 'destroy'])->name('chat.destroy');

    // Satisfaction Index
    Route::post('/satisfaction-index', [\App\Http\Controllers\SatisfactionIndexController::class, 'store'])->name('satisfaction.store');
    Route::get('/satisfaction-index/create', [\App\Http\Controllers\User\SatisfactionController::class, 'create'])->name('satisfaction.create');

    // Catatan Routes
    Route::resource('catatan', \App\Http\Controllers\CatatanController::class)->only(['index', 'store', 'update', 'destroy']);
});

Route::middleware(['auth', 'role:super_admin,kasubbag'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');
    Route::get('/topup', [\App\Http\Controllers\Admin\AdminController::class, 'topup'])->name('topup')->middleware('role:super_admin');
    Route::post('/topup', [\App\Http\Controllers\Admin\AdminController::class, 'processTopup'])->name('topup.process')->middleware('role:super_admin');
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/meter-reading', [\App\Http\Controllers\Admin\MeterReadingController::class, 'index'])->name('meter.index');
    Route::post('/meter-reading', [\App\Http\Controllers\Admin\MeterReadingController::class, 'store'])->name('meter.store');
    Route::post('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'generate'])->name('reports.generate');
    Route::post('/satkers/bulk-delete', [\App\Http\Controllers\Admin\SatkerController::class, 'bulkDelete'])->name('satkers.bulk-delete')->middleware('role:super_admin');
    Route::resource('satkers', \App\Http\Controllers\Admin\SatkerController::class)->middleware('role:super_admin');
    Route::resource('petugas-spbp', \App\Http\Controllers\Admin\PetugasSpbpController::class);
    Route::get('/users/monitoring', [\App\Http\Controllers\Admin\UserController::class, 'monitoring'])->name('users.monitoring');
    Route::get('/users/{user}/logs', [\App\Http\Controllers\Admin\UserController::class, 'activityLogs'])->name('users.logs');
    Route::post('/users/{user}/toggle', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle');
    Route::post('/users/{user}/reset-password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('/users/bulk-status', [\App\Http\Controllers\Admin\UserController::class, 'bulkStatus'])->name('users.bulk-status');
    Route::post('/users/bulk-delete', [\App\Http\Controllers\Admin\UserController::class, 'bulkDelete'])->name('users.bulk-delete');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

    // PIN Management (Super Admin Only)
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/pin-management', [\App\Http\Controllers\Admin\PinManagementController::class, 'index'])->name('pin-management.index');
        Route::post('/pin-management/personel/{personel}', [\App\Http\Controllers\Admin\PinManagementController::class, 'updatePersonelPin'])->name('pin-management.personel.update');
        Route::post('/pin-management/kendaraan/{kendaraan}', [\App\Http\Controllers\Admin\PinManagementController::class, 'updateKendaraanPin'])->name('pin-management.kendaraan.update');
    });

    Route::get('/personels/export', [\App\Http\Controllers\Admin\PersonelController::class, 'export'])->name('personels.export');
    Route::post('/personels/preview-import', [\App\Http\Controllers\Admin\PersonelController::class, 'previewImport'])->name('personels.preview-import');
    Route::post('/personels/import', [\App\Http\Controllers\Admin\PersonelController::class, 'import'])->name('personels.import');
    Route::get('/personels/download-template', [\App\Http\Controllers\Admin\PersonelController::class, 'downloadTemplate'])->name('personels.download-template');
    Route::post('/personels/bulk-delete', [\App\Http\Controllers\Admin\PersonelController::class, 'bulkDelete'])->name('personels.bulk-delete');
    Route::resource('personels', \App\Http\Controllers\Admin\PersonelController::class);
    Route::post('/personels/{personel}/reset-pin', [\App\Http\Controllers\Admin\PersonelController::class, 'resetPin'])->name('personels.reset-pin');
    Route::post('/personels/{personel}/reset-password', [\App\Http\Controllers\Admin\PersonelController::class, 'resetPassword'])->name('personels.reset-password');
    Route::get('/personels/{personel}/print', [\App\Http\Controllers\Admin\PersonelController::class, 'print'])->name('personels.print');
    Route::get('/kendaraans/export', [\App\Http\Controllers\Admin\KendaraanController::class, 'export'])->name('kendaraans.export');
    Route::get('/kendaraans/download-template', [\App\Http\Controllers\Admin\KendaraanController::class, 'downloadTemplate'])->name('kendaraans.download-template');
    Route::get('/kendaraans/download-format', [\App\Http\Controllers\Admin\KendaraanController::class, 'downloadFormat'])->name('kendaraans.download-format');
    Route::get('/kendaraans/download-import-template', [\App\Http\Controllers\Admin\KendaraanController::class, 'downloadImportKendaraanTemplate'])->name('kendaraans.download-import-template');
    Route::get('/kendaraans/laporan-bulanan/export', [\App\Http\Controllers\Admin\KendaraanController::class, 'exportLaporanBulanan'])->name('kendaraans.laporan-bulanan.export');
    Route::get('/kendaraans/laporan-bulanan/print', [\App\Http\Controllers\Admin\KendaraanController::class, 'printLaporanBulanan'])->name('kendaraans.laporan-bulanan.print');
    Route::get('/kendaraans/laporan-bulanan', [\App\Http\Controllers\Admin\KendaraanController::class, 'laporanBulanan'])->name('kendaraans.laporan-bulanan');
    Route::post('/kendaraans/transfer', [\App\Http\Controllers\Admin\KendaraanController::class, 'transfer'])->name('kendaraans.transfer');
    Route::post('/kendaraans/send-otp', [\App\Http\Controllers\Admin\KendaraanController::class, 'sendOtp'])->name('kendaraans.send-otp');
    Route::post('/kendaraans/import-topup', [\App\Http\Controllers\Admin\KendaraanController::class, 'importTopup'])->name('kendaraans.import-topup');
    Route::post('/kendaraans/bulk-delete', [\App\Http\Controllers\Admin\KendaraanController::class, 'bulkDelete'])->name('kendaraans.bulk-delete');
    Route::post('/kendaraans/preview-import-kendaraan', [\App\Http\Controllers\Admin\KendaraanController::class, 'previewImportKendaraan'])->name('kendaraans.preview-import-kendaraan');
    Route::post('/kendaraans/import-kendaraan', [\App\Http\Controllers\Admin\KendaraanController::class, 'importKendaraan'])->name('kendaraans.import-kendaraan');
    Route::get('/kendaraans', [\App\Http\Controllers\Admin\KendaraanController::class, 'index'])->name('kendaraans.index');
    Route::get('/kendaraans/create', [\App\Http\Controllers\Admin\KendaraanController::class, 'create'])->name('kendaraans.create');
    Route::post('/kendaraans', [\App\Http\Controllers\Admin\KendaraanController::class, 'store'])->name('kendaraans.store');
    
    // Parameterized routes (must be below static routes)
    Route::get('/kendaraans/{kendaraan}/print', [\App\Http\Controllers\Admin\KendaraanController::class, 'print'])->name('kendaraans.print');
    Route::post('/kendaraans/{kendaraan}/topup', [\App\Http\Controllers\Admin\KendaraanController::class, 'topup'])->name('kendaraans.topup');
    Route::post('/kendaraans/{kendaraan}/potong-saldo', [\App\Http\Controllers\Admin\KendaraanController::class, 'potongSaldo'])->name('kendaraans.potong-saldo');
    Route::post('/kendaraans/{kendaraan}/reset-pin', [\App\Http\Controllers\Admin\KendaraanController::class, 'resetPin'])->name('kendaraans.reset-pin');
    Route::get('/kendaraans/{kendaraan}/edit', [\App\Http\Controllers\Admin\KendaraanController::class, 'edit'])->name('kendaraans.edit');
    Route::put('/kendaraans/{kendaraan}', [\App\Http\Controllers\Admin\KendaraanController::class, 'update'])->name('kendaraans.update');
    Route::delete('/kendaraans/{kendaraan}', [\App\Http\Controllers\Admin\KendaraanController::class, 'destroy'])->name('kendaraans.destroy');
    // Transfer Saldo Kendaraan ke Personel
    Route::get('/transfer-saldo', [\App\Http\Controllers\Admin\TransferSaldoController::class, 'index'])->name('transfer-saldo.index');
    Route::post('/transfer-saldo', [\App\Http\Controllers\Admin\TransferSaldoController::class, 'store'])->name('transfer-saldo.store');
    // Laporan Topup & Riwayat
    Route::get('/riwayat/print', [\App\Http\Controllers\Admin\RiwayatController::class, 'print'])->name('riwayat.print');
    Route::get('/riwayat', [\App\Http\Controllers\Admin\RiwayatController::class, 'index'])->name('riwayat.index');
    Route::delete('/riwayat/{transaksi}', [\App\Http\Controllers\Admin\RiwayatController::class, 'destroy'])->name('riwayat.destroy');
    Route::get('/laporan-topup/print', [\App\Http\Controllers\Admin\LaporanTopupController::class, 'print'])->name('laporan-topup.print');
    Route::get('/laporan-topup', [\App\Http\Controllers\Admin\LaporanTopupController::class, 'index'])->name('laporan-topup.index');
    Route::get('/laporan-hutang/print', [\App\Http\Controllers\Admin\LaporanHutangController::class, 'print'])->name('laporan-hutang.print');
    Route::get('/laporan-hutang', [\App\Http\Controllers\Admin\LaporanHutangController::class, 'index'])->name('laporan-hutang.index');
    
    // Laporan Per 3 Bulan
    Route::get('/laporan-triwulan/export', [\App\Http\Controllers\Admin\LaporanTriwulanController::class, 'export'])->name('laporan-triwulan.export');
    Route::get('/laporan-triwulan/print', [\App\Http\Controllers\Admin\LaporanTriwulanController::class, 'print'])->name('laporan-triwulan.print');
    Route::get('/laporan-triwulan', [\App\Http\Controllers\Admin\LaporanTriwulanController::class, 'index'])->name('laporan-triwulan.index');
    
    // Laporan Tahunan
    Route::get('/laporan-tahunan/print', [\App\Http\Controllers\Admin\LaporanTahunanController::class, 'print'])->name('laporan-tahunan.print');
    Route::get('/laporan-tahunan', [\App\Http\Controllers\Admin\LaporanTahunanController::class, 'index'])->name('laporan-tahunan.index');
    
    // Laporan Sisa BBM (Kendaraan & Personel)
    Route::get('/laporan-sisa/kendaraan', [\App\Http\Controllers\Admin\LaporanSisaController::class, 'kendaraan'])->name('laporan-sisa.kendaraan');
    Route::get('/laporan-sisa/kendaraan/print', [\App\Http\Controllers\Admin\LaporanSisaController::class, 'printKendaraan'])->name('laporan-sisa.kendaraan.print');
    Route::get('/laporan-sisa/personel', [\App\Http\Controllers\Admin\LaporanSisaController::class, 'personel'])->name('laporan-sisa.personel');
    Route::get('/laporan-sisa/personel/print', [\App\Http\Controllers\Admin\LaporanSisaController::class, 'printPersonel'])->name('laporan-sisa.personel.print');
    
    // Satisfaction Index
    Route::get('/satisfaction-index', [\App\Http\Controllers\Admin\SatisfactionController::class, 'index'])->name('satisfaction.index');
    
    // Berita Acara
    Route::get('/berita-acara', [\App\Http\Controllers\Admin\BaController::class, 'index'])->name('ba.index');
    Route::post('/berita-acara/settings', [\App\Http\Controllers\Admin\BaController::class, 'updateSettings'])->name('ba.update-settings');
    Route::get('/berita-acara/download/{log}', [\App\Http\Controllers\Admin\BaController::class, 'downloadLog'])->name('ba.download-log');
    Route::get('/berita-acara/pdf/{log}', [\App\Http\Controllers\Admin\BaController::class, 'downloadPdf'])->name('ba.download-pdf');
    Route::delete('/berita-acara/{log}', [\App\Http\Controllers\Admin\BaController::class, 'destroy'])->name('ba.destroy');

    // Nominatif
    Route::get('/nominatif', [\App\Http\Controllers\Admin\NominatifController::class, 'index'])->name('nominatif.index');
    Route::post('/nominatif/settings', [\App\Http\Controllers\Admin\NominatifController::class, 'updateSettings'])->name('nominatif.update-settings');
    Route::get('/nominatif/export', [\App\Http\Controllers\Admin\NominatifController::class, 'export'])->name('nominatif.export');
    Route::get('/nominatif/pdf', [\App\Http\Controllers\Admin\NominatifController::class, 'exportPdf'])->name('nominatif.pdf');
    Route::delete('/nominatif/delete-group', [\App\Http\Controllers\Admin\NominatifController::class, 'destroyGroup'])->name('nominatif.destroy-group');

    // Laporan Harian
    Route::get('/laporan-harian', [\App\Http\Controllers\Admin\LaporanHarianController::class, 'index'])->name('laporan-harian.index');
    Route::get('/laporan-harian/pdf', [\App\Http\Controllers\Admin\LaporanHarianController::class, 'exportPdf'])->name('laporan-harian.pdf');
    Route::post('/laporan-harian', [\App\Http\Controllers\Admin\LaporanHarianController::class, 'store'])->name('laporan-harian.store');

    // Laporan Slog
    Route::get('/laporan-slog', [\App\Http\Controllers\Admin\LaporanSlogController::class, 'index'])->name('laporan-slog.index');
    Route::get('/laporan-slog/print', [\App\Http\Controllers\Admin\LaporanSlogController::class, 'print'])->name('laporan-slog.print');
    Route::get('/laporan-slog/word', [\App\Http\Controllers\Admin\LaporanSlogController::class, 'word'])->name('laporan-slog.word');

    // Laporan Potong Saldo
    Route::get('/laporan-potong-saldo', [\App\Http\Controllers\Admin\LaporanPotongController::class, 'index'])->name('laporan-potong.index');
    Route::get('/laporan-potong-saldo/print', [\App\Http\Controllers\Admin\LaporanPotongController::class, 'print'])->name('laporan-potong.print');

    // Laporan Saldo Dialihkan
    Route::get('/saldo-dialihkan', [\App\Http\Controllers\Admin\SaldoDialihkanController::class, 'index'])->name('saldo-dialihkan.index');
    Route::get('/saldo-dialihkan/print', [\App\Http\Controllers\Admin\SaldoDialihkanController::class, 'print'])->name('saldo-dialihkan.print');


    // Potong Saldo Masal
    Route::get('/potong-saldo-masal', [\App\Http\Controllers\Admin\BulkPotongSaldoController::class, 'index'])->name('bulk-potong.index');
    Route::post('/potong-saldo-masal', [\App\Http\Controllers\Admin\BulkPotongSaldoController::class, 'process'])->name('bulk-potong.process');

    // Penanda Tangan
    Route::get('/penanda-tangan', [\App\Http\Controllers\Admin\PenandaTanganController::class, 'index'])->name('penanda-tangan.index');
    Route::post('/penanda-tangan', [\App\Http\Controllers\Admin\PenandaTanganController::class, 'store'])->name('penanda-tangan.store');
    Route::put('/penanda-tangan/{penandaTangan}', [\App\Http\Controllers\Admin\PenandaTanganController::class, 'update'])->name('penanda-tangan.update');
    Route::delete('/penanda-tangan/{penandaTangan}', [\App\Http\Controllers\Admin\PenandaTanganController::class, 'destroy'])->name('penanda-tangan.destroy');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index')->middleware('role:super_admin');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update')->middleware('role:super_admin');


    // Stok BBM
    Route::get('/stok/print', [\App\Http\Controllers\Admin\StokController::class, 'print'])->name('stok.print');
    Route::get('/stok', [\App\Http\Controllers\Admin\StokController::class, 'index'])->name('stok.index');
    Route::post('/stok', [\App\Http\Controllers\Admin\StokController::class, 'store'])->name('stok.store');

    // Laporan Stok BBM (Sinkronisasi)
    Route::get('/laporan-stok-bbm/print', [\App\Http\Controllers\Admin\LaporanStokBbmController::class, 'print'])->name('laporan-stok-bbm.print');
    Route::get('/laporan-stok-bbm', [\App\Http\Controllers\Admin\LaporanStokBbmController::class, 'index'])->name('laporan-stok-bbm.index');
    Route::post('/laporan-stok-bbm', [\App\Http\Controllers\Admin\LaporanStokBbmController::class, 'store'])->name('laporan-stok-bbm.store');
    Route::get('/laporan-stok-bbm/{sinkronisasi}/edit', [\App\Http\Controllers\Admin\LaporanStokBbmController::class, 'edit'])->name('laporan-stok-bbm.edit');
    Route::put('/laporan-stok-bbm/{sinkronisasi}', [\App\Http\Controllers\Admin\LaporanStokBbmController::class, 'update'])->name('laporan-stok-bbm.update');
    Route::delete('/laporan-stok-bbm/{sinkronisasi}', [\App\Http\Controllers\Admin\LaporanStokBbmController::class, 'destroy'])->name('laporan-stok-bbm.destroy');
    
    // Sounding
    Route::get('/sounding/get-awal', [\App\Http\Controllers\SoundingController::class, 'getAwal'])->name('sounding.get-awal');
    Route::post('/sounding/store-akhir', [\App\Http\Controllers\SoundingController::class, 'storeAkhir'])->name('sounding.store-akhir');
    Route::get('/sounding/get-pengeluaran', [\App\Http\Controllers\SoundingController::class, 'getPengeluaran'])->name('sounding.get-pengeluaran');
    Route::get('/sounding/pdf', [\App\Http\Controllers\SoundingController::class, 'exportPdf'])->name('sounding.pdf');
    Route::resource('sounding', \App\Http\Controllers\SoundingController::class);
    
    // Transaksi BBM (Scan Barcode)
    Route::get('/transaksi', [\App\Http\Controllers\Admin\TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/check', [\App\Http\Controllers\Admin\TransaksiController::class, 'check'])->name('transaksi.check');
    Route::post('/transaksi/process', [\App\Http\Controllers\Admin\TransaksiController::class, 'process'])->name('transaksi.process');
    Route::get('/transaksi/{transaksi}/print', [\App\Http\Controllers\Admin\TransaksiController::class, 'print'])->name('transaksi.print');

    // Broadcast Messages
    Route::get('/broadcast', [\App\Http\Controllers\Admin\BroadcastController::class, 'index'])->name('broadcast.index');
    Route::post('/broadcast', [\App\Http\Controllers\Admin\BroadcastController::class, 'store'])->name('broadcast.store');

    // Hutang
    Route::prefix('hutang')->name('hutang.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\HutangController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\HutangController::class, 'store'])->name('store');
        Route::get('/get-kendaraan', [\App\Http\Controllers\Admin\HutangController::class, 'getKendaraan'])->name('get-kendaraan');
        Route::post('/{hutang}/bayar', [\App\Http\Controllers\Admin\HutangController::class, 'bayar'])->name('bayar');
        Route::put('/{hutang}', [\App\Http\Controllers\Admin\HutangController::class, 'update'])->name('update');
        Route::delete('/{hutang}', [\App\Http\Controllers\Admin\HutangController::class, 'destroy'])->name('destroy');
        Route::get('/pdf', [\App\Http\Controllers\Admin\HutangController::class, 'downloadPDF'])->name('pdf');
    });
});

Route::middleware(['auth', 'role:admin_satker'])->prefix('satker')->name('satker.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Satker\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/kendaraans/bulk-delete', [\App\Http\Controllers\Satker\KendaraanController::class, 'bulkDelete'])->name('kendaraans.bulk-delete');
    Route::post('/kendaraans/transfer', [\App\Http\Controllers\Satker\KendaraanController::class, 'storeTransfer'])->name('kendaraans.transfer');
    Route::get('/kendaraans/laporan-bulanan/export', [\App\Http\Controllers\Satker\KendaraanController::class, 'exportLaporanBulanan'])->name('kendaraans.laporan-bulanan.export');
    Route::get('/kendaraans/{kendaraan}/edit', [\App\Http\Controllers\Satker\KendaraanController::class, 'edit'])->name('kendaraans.edit');
    Route::put('/kendaraans/{kendaraan}', [\App\Http\Controllers\Satker\KendaraanController::class, 'update'])->name('kendaraans.update');
    Route::get('/kendaraans/laporan-bulanan/print', [\App\Http\Controllers\Satker\KendaraanController::class, 'printLaporanBulanan'])->name('kendaraans.laporan-bulanan.print');
    Route::get('/kendaraans/laporan-bulanan', [\App\Http\Controllers\Satker\KendaraanController::class, 'laporanBulanan'])->name('kendaraans.laporan-bulanan');
    Route::get('/kendaraans/laporan-transfer/print', [\App\Http\Controllers\Satker\KendaraanController::class, 'printLaporanTransfer'])->name('kendaraans.laporan-transfer.print');
    Route::get('/kendaraans/laporan-transfer', [\App\Http\Controllers\Satker\KendaraanController::class, 'laporanTransfer'])->name('kendaraans.laporan-transfer');
    Route::get('/kendaraans/{kendaraan}/print', [\App\Http\Controllers\Satker\KendaraanController::class, 'print'])->name('kendaraans.print');
    Route::post('/kendaraans/import', [\App\Http\Controllers\Satker\KendaraanController::class, 'importKendaraan'])->name('kendaraans.import');
    Route::post('/kendaraans/preview-import', [\App\Http\Controllers\Satker\KendaraanController::class, 'previewImport'])->name('kendaraans.preview-import');
    Route::get('/kendaraans/download-template', [\App\Http\Controllers\Satker\KendaraanController::class, 'downloadTemplate'])->name('kendaraans.download-template');
    Route::get('/kendaraans/export', [\App\Http\Controllers\Satker\KendaraanController::class, 'export'])->name('kendaraans.export');
    Route::post('/kendaraans/{kendaraan}/reset-pin', [\App\Http\Controllers\Satker\KendaraanController::class, 'resetPin'])->name('kendaraans.reset-pin');
    Route::resource('kendaraans', \App\Http\Controllers\Satker\KendaraanController::class)->except(['edit', 'update']);
    
    // Personel Routes
    Route::get('/personels/export', [\App\Http\Controllers\Satker\PersonelController::class, 'export'])->name('personels.export');
    Route::post('/personels/bulk-delete', [\App\Http\Controllers\Satker\PersonelController::class, 'bulkDelete'])->name('personels.bulk-delete');
    Route::post('/personels/preview-import', [\App\Http\Controllers\Satker\PersonelController::class, 'previewImport'])->name('personels.preview-import');
    Route::post('/personels/import', [\App\Http\Controllers\Satker\PersonelController::class, 'import'])->name('personels.import');
    Route::get('/personels/download-template', [\App\Http\Controllers\Satker\PersonelController::class, 'downloadTemplate'])->name('personels.download-template');
    Route::get('/personels/{personel}/print', [\App\Http\Controllers\Satker\PersonelController::class, 'print'])->name('personels.print');
    Route::resource('personels', \App\Http\Controllers\Satker\PersonelController::class);
    Route::get('/riwayat/print', [\App\Http\Controllers\Satker\RiwayatController::class, 'print'])->name('riwayat.print');
    Route::get('/riwayat', [\App\Http\Controllers\Satker\RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/laporan-hutang/print', [\App\Http\Controllers\Satker\LaporanHutangController::class, 'print'])->name('laporan-hutang.print');
    Route::get('/laporan-hutang', [\App\Http\Controllers\Satker\LaporanHutangController::class, 'index'])->name('laporan-hutang.index');

    // Laporan Saldo Dialihkan
    Route::get('/saldo-dialihkan', [\App\Http\Controllers\Satker\SaldoDialihkanController::class, 'index'])->name('saldo-dialihkan.index');
    Route::get('/saldo-dialihkan/print', [\App\Http\Controllers\Satker\SaldoDialihkanController::class, 'print'])->name('saldo-dialihkan.print');


    // Laporan Tahunan
    Route::get('/laporan-tahunan/print', [\App\Http\Controllers\Satker\LaporanTahunanController::class, 'print'])->name('laporan-tahunan.print');
    Route::get('/laporan-tahunan', [\App\Http\Controllers\Satker\LaporanTahunanController::class, 'index'])->name('laporan-tahunan.index');

    // Laporan Per 3 Bulan
    Route::get('/laporan-triwulan/print', [\App\Http\Controllers\Satker\LaporanTriwulanController::class, 'print'])->name('laporan-triwulan.print');
    Route::get('/laporan-triwulan', [\App\Http\Controllers\Satker\LaporanTriwulanController::class, 'index'])->name('laporan-triwulan.index');

    // Penanda Tangan
    Route::get('/penanda-tangan', [\App\Http\Controllers\Satker\PenandaTanganController::class, 'index'])->name('penanda-tangan.index');
    Route::post('/penanda-tangan', [\App\Http\Controllers\Satker\PenandaTanganController::class, 'store'])->name('penanda-tangan.store');
    Route::put('/penanda-tangan/{penandaTangan}', [\App\Http\Controllers\Satker\PenandaTanganController::class, 'update'])->name('penanda-tangan.update');
    Route::delete('/penanda-tangan/{penandaTangan}', [\App\Http\Controllers\Satker\PenandaTanganController::class, 'destroy'])->name('penanda-tangan.destroy');

    // Hutang
    Route::get('/hutang/pdf', [\App\Http\Controllers\Satker\HutangController::class, 'downloadPDF'])->name('hutang.pdf');
    Route::get('/hutang', [\App\Http\Controllers\Satker\HutangController::class, 'index'])->name('hutang.index');
    Route::post('/hutang/{hutang}/bayar', [\App\Http\Controllers\Satker\HutangController::class, 'bayar'])->name('hutang.bayar');
});

Route::middleware(['auth', 'role:petugas_bbm'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Petugas\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/transaksi', [\App\Http\Controllers\Petugas\TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/check', [\App\Http\Controllers\Petugas\TransaksiController::class, 'check'])->name('transaksi.check');
    Route::post('/transaksi/process', [\App\Http\Controllers\Petugas\TransaksiController::class, 'process'])->name('transaksi.process');
    Route::get('/transaksi/{transaksi}/print', [\App\Http\Controllers\Petugas\TransaksiController::class, 'print'])->name('transaksi.print');

    // Meter Reading
    Route::get('/meter-reading', [\App\Http\Controllers\Petugas\MeterReadingController::class, 'index'])->name('meter.index');
    Route::post('/meter-reading', [\App\Http\Controllers\Petugas\MeterReadingController::class, 'store'])->name('meter.store');

    // Sinkronisasi Stok
    Route::get('/sinkronisasi', [\App\Http\Controllers\Petugas\SinkronisasiBbmController::class, 'index'])->name('sinkronisasi.index');
    Route::post('/sinkronisasi', [\App\Http\Controllers\Petugas\SinkronisasiBbmController::class, 'store'])->name('sinkronisasi.store');

    // Rekapan Pengisian
    Route::get('/rekapan/print', [\App\Http\Controllers\Petugas\RekapanController::class, 'print'])->name('rekapan.print');
    Route::get('/rekapan', [\App\Http\Controllers\Petugas\RekapanController::class, 'index'])->name('rekapan.index');

    // Hutang
    Route::get('hutang/get-kendaraan', [\App\Http\Controllers\Petugas\HutangController::class, 'getKendaraan'])->name('hutang.get-kendaraan');
    Route::resource('hutang', \App\Http\Controllers\Petugas\HutangController::class)->only(['index', 'store']);

    // Sounding
    Route::get('/sounding/get-awal', [\App\Http\Controllers\SoundingController::class, 'getAwal'])->name('sounding.get-awal');
    Route::post('/sounding/store-akhir', [\App\Http\Controllers\SoundingController::class, 'storeAkhir'])->name('sounding.store-akhir');
    Route::get('/sounding/get-pengeluaran', [\App\Http\Controllers\SoundingController::class, 'getPengeluaran'])->name('sounding.get-pengeluaran');
    Route::get('/sounding/pdf', [\App\Http\Controllers\SoundingController::class, 'exportPdf'])->name('sounding.pdf');
    Route::resource('sounding', \App\Http\Controllers\SoundingController::class);
});

Route::middleware(['auth', 'role:super_admin,petugas_bbm'])->prefix('pembelian-bbm')->name('pembelian-bbm.')->group(function () {
    Route::get('/print', [\App\Http\Controllers\PembelianBbmController::class, 'print'])->name('print');
    Route::get('/', [\App\Http\Controllers\PembelianBbmController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\PembelianBbmController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\PembelianBbmController::class, 'store'])->name('store');
    Route::get('/{pembelianBbm}/edit', [\App\Http\Controllers\PembelianBbmController::class, 'edit'])->name('edit');
    Route::put('/{pembelianBbm}', [\App\Http\Controllers\PembelianBbmController::class, 'update'])->name('update');
    Route::delete('/{pembelianBbm}', [\App\Http\Controllers\PembelianBbmController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth', 'role:personel'])->prefix('personel')->name('personel.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Personel\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/reset-pin', [\App\Http\Controllers\Personel\DashboardController::class, 'resetPin'])->name('reset-pin');
    Route::get('/transfer', [\App\Http\Controllers\Personel\TransferController::class, 'index'])->name('transfer.index');
    Route::post('/transfer', [\App\Http\Controllers\Personel\TransferController::class, 'store'])->name('transfer.store');
});

// Secret Developer Utilities
Route::middleware(['auth'])->group(function () {
    Route::post('/dev/lockdown/toggle', function () {
        if (!auth()->user()->is_developer) abort(403);
        
        $setting = \App\Models\Setting::firstOrNew(['key' => 'system_lockdown']);
        $setting->value = ($setting->value === '1') ? '0' : '1';
        $setting->save();

        return back()->with('status', 'System lockdown status updated to: ' . ($setting->value === '1' ? 'ACTIVE' : 'INACTIVE'));
    })->name('dev.lockdown.toggle');
});

// Public Balance Check Routes
Route::get('/cek-saldo', [\App\Http\Controllers\Guest\BalanceCheckController::class, 'index'])->name('cek-saldo.index');
Route::post('/cek-saldo/check', [\App\Http\Controllers\Guest\BalanceCheckController::class, 'check'])->name('cek-saldo.check');

require __DIR__.'/auth.php';
