@extends('layouts.app')

@section('judul-halaman', 'Dashboard')
@section('isi-content')

<main class="p-6 overflow-y-auto space-y-6">
    
    {{-- 1. KARTU STATISTIK --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        {{-- Card: Pendapatan --}}
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Total Penjualan</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $total_pendapatan }}</h3>
                </div>
                <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                    <i class="fa-solid fa-wallet text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs">
                <span class="{{ $persen_pendapatan >= 0 ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50' }} px-2 py-1 rounded font-medium flex items-center gap-1">
                    <i class="fa-solid {{ $persen_pendapatan >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                    {{ abs($persen_pendapatan) }}%
                </span>
                <span class="text-gray-400 ml-2">vs bulan lalu</span>
            </div>
        </div>

        {{-- Card: Pengeluaran --}}
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Total Pembelian</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $total_pengeluaran }}</h3>
                </div>
                <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                    <i class="fa-solid fa-cart-shopping text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs">
                {{-- Logika: Pengeluaran naik itu "merah" (waspada), turun itu "hijau" (hemat) --}}
                <span class="{{ $persen_pengeluaran <= 0 ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50' }} px-2 py-1 rounded font-medium flex items-center gap-1">
                    <i class="fa-solid {{ $persen_pengeluaran <= 0 ? 'fa-arrow-trend-down' : 'fa-arrow-trend-up' }}"></i>
                    {{ abs($persen_pengeluaran) }}%
                </span>
                <span class="text-gray-400 ml-2">vs bulan lalu</span>
            </div>
        </div>

        {{-- Card: Profit --}}
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Net Profit</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $total_keuntungan }}</h3>
                </div>
                <div class="p-2 bg-green-50 rounded-lg text-green-600">
                    <i class="fa-solid fa-sack-dollar text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs">
                <span class="{{ $persen_profit >= 0 ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50' }} px-2 py-1 rounded font-medium flex items-center gap-1">
                    <i class="fa-solid {{ $persen_profit >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                    {{ abs($persen_profit) }}%
                </span>
                <span class="text-gray-400 ml-2">Margin: {{ $persen_profit }}%</span>
            </div>
        </div>

        {{-- Card: Transaksi --}}
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Total Transaksi</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $total_transaksi }}</h3>
                </div>
                <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                    <i class="fa-solid fa-receipt text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs">
                <span class="{{ $persen_transaksi >= 0 ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50' }} px-2 py-1 rounded font-medium flex items-center gap-1">
                    <i class="fa-solid {{ $persen_transaksi >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                    {{ abs($persen_transaksi) }}%
                </span>
                <span class="text-gray-400 ml-2">vs bulan lalu</span>
            </div>
        </div>
    </div>

    {{-- 2. SHORTCUT BUTTONS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Tombol 1 --}}
        <a href="{{ route('barang.index') }}" class="group bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:border-blue-500 hover:shadow-md transition-all duration-200 flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">Kelola Barang</h3>
                <p class="text-xs text-gray-500 mt-1">Cek stok & tambah produk</p>
            </div>
            <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-600 group-hover:bg-blue-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-box"></i>
            </div>
        </a>

        {{-- Tombol 2 --}}
        <a href="{{ route('penjualan.index') }}" class="group bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:border-blue-500 hover:shadow-md transition-all duration-200 flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">Kasir / Penjualan</h3>
                <p class="text-xs text-gray-500 mt-1">Buat pesanan baru</p>
            </div>
            <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-600 group-hover:bg-blue-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-cash-register"></i>
            </div>
        </a>

        {{-- Tombol 3 --}}
        <a href="{{ route('laporan') }}" class="group bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:border-blue-500 hover:shadow-md transition-all duration-200 flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">Laporan Lengkap</h3>
                <p class="text-xs text-gray-500 mt-1">Analisis performa toko</p>
            </div>
            <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-600 group-hover:bg-blue-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
        </a>
    </div>

    {{-- 3. GRID UTAMA (Restock & Recent) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- KOLOM KIRI: Restock Barang --}}
        <div class="bg-white border rounded-xl shadow-lg flex flex-col h-[28rem]">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                    Perlu Restock
                </h3>
                <a href="{{ route('pembelian.index') }}" class="text-xs font-medium text-blue-600 hover:text-blue-800">
                    Isi Stok &rarr;
                </a>
            </div>
            
            <div class="flex-grow overflow-y-auto p-4 custom-scrollbar"> 
                <ul class="space-y-3"> 
                    @php $ada_barang_habis = false; @endphp
                    
                    @foreach ($barang as $item)
                        @if ($item->jumlah_stok_barang <= $item->stok_minimal)
                            @php $ada_barang_habis = true; @endphp
                            
                            {{-- Indikator keparahan: Jika 0 maka merah gelap, jika masih ada dikit merah terang --}}
                            <li class="flex justify-between items-center p-3 rounded-lg border {{ $item->jumlah_stok_barang == 0 ? 'bg-red-50 border-red-200' : 'bg-orange-50 border-orange-200' }}">
                                <div>
                                    <div class="font-semibold text-gray-800 text-sm">{{ $item->nama_barang }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">Min: {{ $item->stok_minimal }} {{ $item->satuan }}</div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-2 py-1 rounded text-xs font-bold {{ $item->jumlah_stok_barang == 0 ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700' }}">
                                        {{ $item->jumlah_stok_barang == 0 ? 'HABIS' : 'Sisa: ' . $item->jumlah_stok_barang }}
                                    </span>
                                </div>
                            </li>
                        @endif
                    @endforeach

                    @if(!$ada_barang_habis)
                        <div class="h-full flex flex-col items-center justify-center text-gray-400 mt-10">
                            <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center mb-3">
                                <i class="fa-solid fa-check text-green-500 text-xl"></i>
                            </div>
                            <p class="text-sm">Stok aman terkendali!</p>
                        </div>
                    @endif
                </ul>
            </div>
        </div>

        {{-- KOLOM KANAN: Transaksi Terbaru --}}
        <div class="bg-white border rounded-xl shadow-lg flex flex-col h-[28rem]">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-history text-blue-500"></i>
                    Pembelian Terbaru
                </h3>
                <a href="{{ route('daftar.pembelian') }}" class="text-xs font-medium text-blue-600 hover:text-blue-800">
                    Lihat Semua &rarr;
                </a>
            </div>
            
            <div class="flex-grow overflow-y-auto p-4 custom-scrollbar"> 
                <ul class="space-y-3">
                    @forelse ($transaksi_pembelian->take(6) as $pembelian)
                    <li class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 border border-transparent hover:border-gray-100 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                                <i class="fa-solid fa-truck-ramp-box text-sm"></i>
                            </div>
                            <div>
                                {{-- PERBAIKAN LOGIKA: Menggunakan property relasi (supplier) bukan method query --}}
                                <p class="font-semibold text-sm text-gray-800">
                                    {{ $pembelian->supplier->nama_supplier ?? 'Supplier dihapus' }}
                                </p>
                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                    <i class="fa-regular fa-calendar"></i>
                                    {{ \Carbon\Carbon::parse($pembelian->tanggal_transaksi_pembelian)->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                        <span class="font-bold text-sm text-gray-700">
                            {{ "Rp " . number_format($pembelian->total_harga, 0, ',', '.') }}
                        </span>
                    </li>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center text-gray-400 mt-10">
                            <i class="fa-solid fa-receipt text-3xl mb-2 opacity-50"></i>
                            <p class="text-sm">Belum ada transaksi pembelian.</p>
                        </div>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</main>

{{-- Style tambahan untuk scrollbar agar rapi (Opsional) --}}
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1; 
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db; 
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af; 
    }
</style>
@endsection