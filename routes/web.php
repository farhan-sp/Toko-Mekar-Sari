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

Route::middleware(['auth', 'hapus-sesi'])->group(function() {
    // Pemilik Toko
    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna');
    Route::post('/pengguna/tambah-pengguna', [PenggunaController::class, 'tambahPengguna'])->name('pengguna.tambah');
    Route::put('/pengguna/update/{pengguna}/{login}', [PenggunaController::class, 'update'])->name('pengguna.update');
    Route::delete('/pengguna/nonaktif/{pengguna}', [PenggunaController::class, 'statusUpdate'])->name('pengguna.hapus');

    // Kepala Toko
    Route::get('/dashboard', [LaporanController::class, 'dashboard'])->name('dashboard');
    Route::get('/laporan', [LaporanController::class, 'laporan'])->name('laporan');

    Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
    Route::post('/pembelian/store', [PembelianController::class, 'storeTransaction'])->name('pembelian.store');
    Route::post('/pembelian/tambah/barang', [BarangController::class, 'tambahBarang'])->name('pembelian.tambah-barang');

    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::post('/barang/tambah/kategori', [BarangController::class, 'tambahKategori'])->name('barang.tambah-kategori');
    Route::post('/barang/tambah/supplier', [BarangController::class, 'tambahSupplier'])->name('barang.tambah-supplier');
    Route::delete('/barang/hapus/{barang}', [BarangController::class, 'delete'])->name('barang.hapus');
    Route::put('/barang/update/{barang}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/hapus/supplier/{id}', [BarangController::class, 'hapusSupplier'])->name('barang.hapus-supplier');
    Route::delete('/barang/hapus/kategori/{id}', [BarangController::class, 'hapusKategori'])->name('barang.hapus-kategori');

    Route::get('/daftar/pembelian', [PembelianController::class, 'daftarTransaksiPembelian'])->name('daftar.pembelian');
    Route::post('/daftar/pembelian/detail', [PembelianController::class, 'detailTransaksiPembelian'])->name('detail.pembelian');
    Route::delete('/daftar/pembelian/hapus/{pembelian}', [PembelianController::class, 'hapusTransaksi'])->name('hapus.pembelian');

    // Kasir
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::post('/penjualan/cart/add', [PenjualanController::class, 'addToCart'])->name('penjualan.cart.add');
    Route::post('/penjualan/cart/clear', [PenjualanController::class, 'clearCart'])->name('penjualan.cart.clear');
    Route::post('/penjualan/store', [PenjualanController::class, 'storeTransaction'])->name('penjualan.store');
    Route::get('/penjualan/cetak-struk/{id}', [PenjualanController::class, 'cetakStruk'])->name('penjualan.cetak_struk');
    Route::get('/penjualan/detail-json/{id}', [PenjualanController::class, 'getDetail']);
    Route::post('/penjualan/cart/remove/{id}', [App\Http\Controllers\PenjualanController::class, 'hapusItem'])->name('penjualan.cart.remove');

    Route::get('/daftar/penjualan', [PenjualanController::class, 'daftarTransaksiPenjualan'])->name('daftar.penjualan');
    Route::post('/daftar/penjualan/detail', [PenjualanController::class, 'detailTransaksiPenjualan'])->name('detail.penjualan');
    Route::delete('/daftar/penjualan/hapus/{penjualan}', [PenjualanController::class, 'hapusTransaksi'])->name('hapus.penjualan');
});