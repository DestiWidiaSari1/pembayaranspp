<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\SPPController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/laporan-pembayaran', [LaporanController::class, 'index'])->name('laporan-pembayaran');

Route::get('/transaksi-pembayaran', [TransaksiController::class, 'index'])->name('transaksi-pembayaran');
Route::get('/transaksi/cari-siswa', [TransaksiController::class, 'cariSiswa'])->name('transaksi.cari');
Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');
Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');

Route::get('/data-spp', [SPPController::class, 'index'])->name('data-spp');
Route::post('/spp', [SPPController::class, 'store'])->name('spp.store');
Route::put('/spp/{spp}', [SPPController::class, 'update'])->name('spp.update');
Route::delete('/spp/{spp}', [SPPController::class, 'destroy'])->name('spp.destroy');

Route::get('/data-siswa', [SiswaController::class, 'index'])->name('data-siswa');
Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
Route::put('/data-siswa/update/{id}', [SiswaController::class, 'update'])->name('siswa.update');
Route::delete('/data-siswa/delete/{id}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('login.submit');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');
});