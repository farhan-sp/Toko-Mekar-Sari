<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ {
    BarangController,
    PembelianController,
    PenggunaController,
    PenjualanController,
    LaporanController,
    Test
};

Route::get('/', function () {
    return view('halaman.login');
});

Route::get('/login', [PenggunaController::class, 'login'])->name('login');
Route::post('/login/autentikasi', [PenggunaController::class, 'autentikasi'])->name('autentikasi');
Route::post('/logout', [PenggunaController::class, 'logout'])->name('logout');

Route::get('/maintenance', function() {
    return view('halaman.maintenance');
})->name('maintenance');
Route::get('/test', [Test::class, 'index'])->name('test');
Route::post('/test/percobaan', [Test::class, 'terima'])->name('test.coba-1');

Route::middleware(['auth', 'hapus-sesi'])->group(function() {
    // Pemilik Toko
    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna');
    Route::post('/pengguna/tambah-pengguna', [PenggunaController::class, 'tambahPengguna'])->name('pengguna.tambah-pengguna');
    Route::delete('/pengguna/hapus-pengguna/{pengguna}', [PenggunaController::class, 'hapusPengguna'])->name('pengguna.hapus-pengguna');

    // Kepala Toko
    Route::get('/dashboard', [LaporanController::class, 'dashboard'])->name('dashboard');
    Route::get('/laporan', [LaporanController::class, 'laporan'])->name('laporan');

    Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
    Route::post('/pembelian/store', [PembelianController::class, 'storeTransaction'])->name('pembelian.store');
    Route::post('/pembelian/tambah-barang', [BarangController::class, 'tambahBarang'])->name('pembelian.tambah-barang');

    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');

    Route::get('/daftar/pembelian', [LaporanController::class, 'daftarTransaksiPembelian'])->name('daftar.pembelian');
    Route::post('/daftar/pembelian/detail', [LaporanController::class, 'detailTransaksiPembelian'])->name('detail.pembelian');

    // Kasir
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::post('/penjualan/cart/add', [PenjualanController::class, 'addToCart'])->name('penjualan.cart.add');
    Route::post('/penjualan/cart/clear', [PenjualanController::class, 'clearCart'])->name('penjualan.cart.clear');
    Route::post('/penjualan/store', [PenjualanController::class, 'storeTransaction'])->name('penjualan.store');

    Route::get('/daftar/penjualan', [LaporanController::class, 'daftarTransaksiPenjualan'])->name('daftar.penjualan');
    Route::post('/daftar/penjualan/detail', [LaporanController::class, 'detailTransaksiPenjualan'])->name('detail.penjualan');
});