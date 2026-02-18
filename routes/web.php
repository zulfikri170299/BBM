<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'super_admin') {
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
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/location', [ProfileController::class, 'updateLocation'])->name('profile.location.update');

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

    // Satisfaction Index
    Route::post('/satisfaction-index', [\App\Http\Controllers\SatisfactionIndexController::class, 'store'])->name('satisfaction.store');
    Route::get('/satisfaction-index/create', [\App\Http\Controllers\User\SatisfactionController::class, 'create'])->name('satisfaction.create');
});

Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');
    Route::get('/topup', [\App\Http\Controllers\Admin\AdminController::class, 'topup'])->name('topup');
    Route::post('/topup', [\App\Http\Controllers\Admin\AdminController::class, 'processTopup'])->name('topup.process');
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'generate'])->name('reports.generate');
    Route::resource('satkers', \App\Http\Controllers\Admin\SatkerController::class);
    Route::get('/users/monitoring', [\App\Http\Controllers\Admin\UserController::class, 'monitoring'])->name('users.monitoring');
    Route::get('/users/{user}/logs', [\App\Http\Controllers\Admin\UserController::class, 'activityLogs'])->name('users.logs');
    Route::post('/users/{user}/toggle', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle');
    Route::post('/users/{user}/reset-password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('/users/bulk-status', [\App\Http\Controllers\Admin\UserController::class, 'bulkStatus'])->name('users.bulk-status');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('personels', \App\Http\Controllers\Admin\PersonelController::class)->only(['index', 'destroy']);
    Route::post('/personels/{personel}/reset-pin', [\App\Http\Controllers\Admin\PersonelController::class, 'resetPin'])->name('personels.reset-pin');
    Route::get('/personels/{personel}/print', [\App\Http\Controllers\Admin\PersonelController::class, 'print'])->name('personels.print');
    Route::get('/kendaraans/export', [\App\Http\Controllers\Admin\KendaraanController::class, 'export'])->name('kendaraans.export');
    Route::get('/kendaraans', [\App\Http\Controllers\Admin\KendaraanController::class, 'index'])->name('kendaraans.index');
    Route::get('/kendaraans/create', [\App\Http\Controllers\Admin\KendaraanController::class, 'create'])->name('kendaraans.create');
    Route::post('/kendaraans', [\App\Http\Controllers\Admin\KendaraanController::class, 'store'])->name('kendaraans.store');
    Route::get('/kendaraans/{kendaraan}/print', [\App\Http\Controllers\Admin\KendaraanController::class, 'print'])->name('kendaraans.print');
    Route::post('/kendaraans/{kendaraan}/topup', [\App\Http\Controllers\Admin\KendaraanController::class, 'topup'])->name('kendaraans.topup');
    Route::post('/kendaraans/{kendaraan}/reset-pin', [\App\Http\Controllers\Admin\KendaraanController::class, 'resetPin'])->name('kendaraans.reset-pin');
    Route::post('/kendaraans/send-otp', [\App\Http\Controllers\Admin\KendaraanController::class, 'sendOtp'])->name('kendaraans.send-otp');
    Route::post('/kendaraans/import-topup', [\App\Http\Controllers\Admin\KendaraanController::class, 'importTopup'])->name('kendaraans.import-topup');
    Route::get('/kendaraans/download-template', [\App\Http\Controllers\Admin\KendaraanController::class, 'downloadTemplate'])->name('kendaraans.download-template');
    Route::get('/kendaraans/laporan-bulanan/export', [\App\Http\Controllers\Admin\KendaraanController::class, 'exportLaporanBulanan'])->name('kendaraans.laporan-bulanan.export');
    Route::get('/kendaraans/laporan-bulanan/print', [\App\Http\Controllers\Admin\KendaraanController::class, 'printLaporanBulanan'])->name('kendaraans.laporan-bulanan.print');
    Route::get('/kendaraans/laporan-bulanan', [\App\Http\Controllers\Admin\KendaraanController::class, 'laporanBulanan'])->name('kendaraans.laporan-bulanan');
    Route::get('/kendaraans/{kendaraan}/edit', [\App\Http\Controllers\Admin\KendaraanController::class, 'edit'])->name('kendaraans.edit');
    Route::put('/kendaraans/{kendaraan}', [\App\Http\Controllers\Admin\KendaraanController::class, 'update'])->name('kendaraans.update');
    Route::delete('/kendaraans/{kendaraan}', [\App\Http\Controllers\Admin\KendaraanController::class, 'destroy'])->name('kendaraans.destroy');
    // Laporan Topup & Riwayat
    Route::get('/riwayat/print', [\App\Http\Controllers\Admin\RiwayatController::class, 'print'])->name('riwayat.print');
    Route::get('/riwayat', [\App\Http\Controllers\Admin\RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/laporan-topup/print', [\App\Http\Controllers\Admin\LaporanTopupController::class, 'print'])->name('laporan-topup.print');
    Route::get('/laporan-topup', [\App\Http\Controllers\Admin\LaporanTopupController::class, 'index'])->name('laporan-topup.index');
    
    // Satisfaction Index
    Route::get('/satisfaction-index', [\App\Http\Controllers\Admin\SatisfactionController::class, 'index'])->name('satisfaction.index');
    
    // Berita Acara
    Route::get('/berita-acara', [\App\Http\Controllers\Admin\BaController::class, 'index'])->name('ba.index');
    Route::post('/berita-acara/settings', [\App\Http\Controllers\Admin\BaController::class, 'updateSettings'])->name('ba.update-settings');
    Route::get('/berita-acara/download/{log}', [\App\Http\Controllers\Admin\BaController::class, 'downloadLog'])->name('ba.download-log');
    Route::delete('/berita-acara/{log}', [\App\Http\Controllers\Admin\BaController::class, 'destroy'])->name('ba.destroy');

    // Laporan Harian
    Route::get('/laporan-harian', [\App\Http\Controllers\Admin\LaporanHarianController::class, 'index'])->name('laporan-harian.index');
    Route::get('/laporan-harian/pdf', [\App\Http\Controllers\Admin\LaporanHarianController::class, 'exportPdf'])->name('laporan-harian.pdf');
    Route::post('/laporan-harian', [\App\Http\Controllers\Admin\LaporanHarianController::class, 'store'])->name('laporan-harian.store');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

    // Stok BBM
    Route::get('/stok/print', [\App\Http\Controllers\Admin\StokController::class, 'print'])->name('stok.print');
    Route::get('/stok', [\App\Http\Controllers\Admin\StokController::class, 'index'])->name('stok.index');
    Route::post('/stok', [\App\Http\Controllers\Admin\StokController::class, 'store'])->name('stok.store');

    // Broadcast Messages
    Route::get('/broadcast', [\App\Http\Controllers\Admin\BroadcastController::class, 'index'])->name('broadcast.index');
    Route::post('/broadcast', [\App\Http\Controllers\Admin\BroadcastController::class, 'store'])->name('broadcast.store');
});

Route::middleware(['auth', 'role:admin_satker'])->prefix('satker')->name('satker.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Satker\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/kendaraans/transfer', [\App\Http\Controllers\Satker\KendaraanController::class, 'storeTransfer'])->name('kendaraans.transfer');
    Route::get('/kendaraans/laporan-bulanan/export', [\App\Http\Controllers\Satker\KendaraanController::class, 'exportLaporanBulanan'])->name('kendaraans.laporan-bulanan.export');
    Route::get('/kendaraans/{kendaraan}/edit', [\App\Http\Controllers\Satker\KendaraanController::class, 'edit'])->name('kendaraans.edit');
    Route::put('/kendaraans/{kendaraan}', [\App\Http\Controllers\Satker\KendaraanController::class, 'update'])->name('kendaraans.update');
    Route::get('/kendaraans/laporan-bulanan/print', [\App\Http\Controllers\Satker\KendaraanController::class, 'printLaporanBulanan'])->name('kendaraans.laporan-bulanan.print');
    Route::get('/kendaraans/laporan-bulanan', [\App\Http\Controllers\Satker\KendaraanController::class, 'laporanBulanan'])->name('kendaraans.laporan-bulanan');
    Route::get('/kendaraans/laporan-transfer/print', [\App\Http\Controllers\Satker\KendaraanController::class, 'printLaporanTransfer'])->name('kendaraans.laporan-transfer.print');
    Route::get('/kendaraans/laporan-transfer', [\App\Http\Controllers\Satker\KendaraanController::class, 'laporanTransfer'])->name('kendaraans.laporan-transfer');
    Route::get('/kendaraans/{kendaraan}/print', [\App\Http\Controllers\Satker\KendaraanController::class, 'print'])->name('kendaraans.print');
    Route::resource('kendaraans', \App\Http\Controllers\Satker\KendaraanController::class)->except(['edit', 'update']);
    
    // Personel Routes
    Route::post('/personels/import', [\App\Http\Controllers\Satker\PersonelController::class, 'import'])->name('personels.import');
    Route::get('/personels/download-template', [\App\Http\Controllers\Satker\PersonelController::class, 'downloadTemplate'])->name('personels.download-template');
    Route::get('/personels/{personel}/print', [\App\Http\Controllers\Satker\PersonelController::class, 'print'])->name('personels.print');
    Route::resource('personels', \App\Http\Controllers\Satker\PersonelController::class);
    Route::get('/riwayat/print', [\App\Http\Controllers\Satker\RiwayatController::class, 'print'])->name('riwayat.print');
    Route::get('/riwayat', [\App\Http\Controllers\Satker\RiwayatController::class, 'index'])->name('riwayat.index');
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

    // Rekapan Pengisian
    Route::get('/rekapan/print', [\App\Http\Controllers\Petugas\RekapanController::class, 'print'])->name('rekapan.print');
    Route::get('/rekapan', [\App\Http\Controllers\Petugas\RekapanController::class, 'index'])->name('rekapan.index');
});

Route::middleware(['auth', 'role:personel'])->prefix('personel')->name('personel.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Personel\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/transfer', [\App\Http\Controllers\Personel\TransferController::class, 'index'])->name('transfer.index');
    Route::post('/transfer', [\App\Http\Controllers\Personel\TransferController::class, 'store'])->name('transfer.store');
});

require __DIR__.'/auth.php';
