<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

// Halaman publik (tamu)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::get('/', function () {
    return redirect(auth()->check() ? '/dashboard' : '/login');
});

// Halaman yang butuh login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Barang
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');

    // Transaksi
    Route::get('/transaksi/masuk', [TransaksiController::class, 'masukForm'])->name('transaksi.masuk');
    Route::post('/transaksi/masuk', [TransaksiController::class, 'masukStore'])->name('transaksi.masuk.store');

    Route::get('/transaksi/keluar', [TransaksiController::class, 'keluarForm'])->name('transaksi.keluar');
    Route::post('/transaksi/keluar', [TransaksiController::class, 'keluarStore'])->name('transaksi.keluar.store');

    Route::get('/transaksi/riwayat', [TransaksiController::class, 'riwayat'])->name('transaksi.riwayat');
    Route::get('/transaksi/riwayat/export', [TransaksiController::class, 'riwayatExport'])->name('transaksi.riwayat.export');
});
