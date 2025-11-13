@extends('layouts.app')

@section('judul-halaman', 'Dashboard')
@section('isi-content')
<!-- Konten Utama -->
<main class="p-6 overflow-y-auto">
    <!-- Bagian 1: Kartu Statistik (Tidak Diubah) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Total Penjualan</p>
            <h3 class="text-2xl font-bold mt-2">{{ $total_pendapatan }}</h3>
            <p class="text-green-600 text-xs mt-1">+12% dari bulan lalu</p>
        </div>
        <div class="bg-white rounded-xl border p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Total Pembelian</p>
            <h3 class="text-2xl font-bold mt-2">{{ $total_pengeluaran }}</h3>
            <p class="text-green-600 text-xs mt-1">+8% dari bulan lalu</p>
        </div>
        <div class="bg-white rounded-xl border p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Profit</p>
            <h3 class="font-bold text-2xl">{{ $total_keuntungan }}</h3>
            <p class="text-green-500 text-xs mt-2">Margin: 33%</p>
        </div>
        <div class="bg-white rounded-xl border p-4 shadow-sm">
            <p class="text-gray-500 text-sm">Transaksi Bulan Ini</p>
            <h3 class="text-2xl font-bold mt-2">{{ $total_transaksi }}</h3>
            <p class="text-green-600 text-xs mt-1">+23% dari bulan lalu</p>
        </div>
    </div>

    <!-- Bagian 2: Tampilan Aksi Cepat (Lihat Stok, Beli, Statistik) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white border rounded-xl p-4 shadow-sm">
            <h3 class="font-semibold mb-2">Lihat Stok Barang</h3>
            <p class="text-sm text-gray-600 mb-4">Kelola dan pantau stok barang material Anda</p>
            <a href="{{ route('pembelian.index') }}">
                <button class="bg-gray-900 text-white px-4 py-2 rounded-md w-full hover:bg-gray-700">
                    Kelola Stok
                </button>
            </a>
        </div>
        <div class="bg-white border rounded-xl p-4 shadow-sm">
            <h3 class="font-semibold mb-2">Catat Pesanan Pelanggan</h3>
            <p class="text-sm text-gray-600 mb-4">Buat Pesanan untuk Pelanggan</p>
            <a href="{{ route('penjualan.index') }}">
                <button class="bg-gray-900 text-white px-4 py-2 rounded-md w-full hover:bg-gray-700">
                    Buat Pesanan
                </button>
            </a>
        </div>
        <div class="bg-white border rounded-xl p-4 shadow-sm">
            <h3 class="font-semibold mb-2">Statistik Penjualan</h3>
            <p class="text-sm text-gray-600 mb-4">Analisis penjualan dan pembelian</p>
            <a href="{{ route('laporan') }}">
                <button class="bg-gray-900 text-white px-4 py-2 rounded-md w-full hover:bg-gray-700">
                    Lihat Statistik
                </button>
            </a>
        </div>
    </div>

    <!-- BAGIAN BARU: Grid 2 Kolom (Restock & Transaksi) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

        <!-- Kolom Kiri: Daftar Barang Perlu Restock -->
        <div class="bg-white border rounded-xl p-6 shadow-lg h-96 flex flex-col justify-between">
            <h3 class="text-xl font-bold text-red-600 mb-4">
                <i class="fas fa-warehouse mr-2"></i> Daftar Barang Perlu Restock
            </h3>
            
            <div class="flex-grow"> 
                <ul class="space-y-3 text-sm max-h-60 overflow-y-auto"> 
                    @foreach ($barang as $item)
                        <li class="font-bold flex justify-between p-4 rounded-xl bg-gray-200">
                            <span>{{ $item['nama_barang'] }}</span>
                            <span class="font-semibold text-red-500">Stok: {{ $item['jumlah_stok_barang'] }} ({{ $item['satuan'] . ")" }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            <a href="{{ route('pembelian.index') }}" class="bg-gray-900 text-white px-4 py-2 rounded-md w-full hover:bg-gray-700 text-center">
                Lihat Semua Barang
            </a>
        </div>

        <!-- Kolom Kanan: Daftar Transaksi Pembelian Terbaru -->
        <div class="bg-white border rounded-xl p-6 shadow-lg h-96 flex flex-col justify-between">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                Transaksi Pembelian Terbaru
            </h3>
            
            <div class="flex-grow"> 
                <ul class="space-y-3 text-sm max-h-60 overflow-y-auto">
                    @foreach ($transaksi_pembelian as $pembelian)
                    <li class="font-bold flex justify-between p-4 rounded-xl bg-gray-200">
                        <div class="flex-1">
                            <p class="font-medium">{{ $pembelian['id_supplier'] }}</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($pembelian['tanggal_transaksi_pembelian'])->format('d-m-Y') }}</p>
                        </div>
                        <span class="font-semibold text-green-600">{{ "Rp " . number_format($pembelian['total_harga'], 0, ',', '.') }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            
            <a href="{{ route('maintenance') }}" class="bg-gray-900 text-white px-4 py-2 rounded-md w-full hover:bg-gray-700 text-center">
                Lihat Semua Transaksi
            </a>
        </div>
    </div>
</main>
@endsection