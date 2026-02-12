<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');
    Route::get('/topup', [\App\Http\Controllers\Admin\AdminController::class, 'topup'])->name('topup');
    Route::post('/topup', [\App\Http\Controllers\Admin\AdminController::class, 'processTopup'])->name('topup.process');
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'generate'])->name('reports.generate');
    Route::resource('satkers', \App\Http\Controllers\Admin\SatkerController::class);
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::get('/kendaraans', [\App\Http\Controllers\Admin\KendaraanController::class, 'index'])->name('kendaraans.index');
    Route::post('/kendaraans/{kendaraan}/topup', [\App\Http\Controllers\Admin\KendaraanController::class, 'topup'])->name('kendaraans.topup');
    Route::get('/kendaraans/{kendaraan}/edit', [\App\Http\Controllers\Admin\KendaraanController::class, 'edit'])->name('kendaraans.edit');
    Route::put('/kendaraans/{kendaraan}', [\App\Http\Controllers\Admin\KendaraanController::class, 'update'])->name('kendaraans.update');
    Route::delete('/kendaraans/{kendaraan}', [\App\Http\Controllers\Admin\KendaraanController::class, 'destroy'])->name('kendaraans.destroy');
});

Route::middleware(['auth', 'role:admin_satker'])->prefix('satker')->name('satker.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Satker\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/kendaraans/{kendaraan}/print', [\App\Http\Controllers\Satker\KendaraanController::class, 'print'])->name('kendaraans.print');
    Route::resource('kendaraans', \App\Http\Controllers\Satker\KendaraanController::class);
    Route::resource('personels', \App\Http\Controllers\Satker\PersonelController::class);
});

Route::middleware(['auth', 'role:petugas_bbm'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Petugas\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/transaksi', [\App\Http\Controllers\Petugas\TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/check', [\App\Http\Controllers\Petugas\TransaksiController::class, 'check'])->name('transaksi.check');
    Route::post('/transaksi/process', [\App\Http\Controllers\Petugas\TransaksiController::class, 'process'])->name('transaksi.process');
    Route::get('/transaksi/{transaksi}/print', [\App\Http\Controllers\Petugas\TransaksiController::class, 'print'])->name('transaksi.print');
});

Route::middleware(['auth', 'role:personel'])->prefix('personel')->name('personel.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Personel\DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
