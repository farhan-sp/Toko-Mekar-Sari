@extends('layouts.app')

@section('judul-halaman', 'Riwayat Pembelian')
@section('isi-content')
<main class="flex-1 p-6 overflow-auto" x-data="{ 
    showDetailModal: false, 
    selectedTransaksi: null,
    searchQuery: ''
}">

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50" role="alert">
            <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="flex items-center p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50" role="alert">
            <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        
        <!-- HEADER: Judul & Search -->
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h4 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice-dollar text-blue-600"></i>
                    Riwayat Pembelian Stok
                </h4>
                <p class="text-sm text-gray-500">Daftar transaksi barang masuk dari supplier.</p>
            </div>
            
            <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input 
                    type="text" 
                    x-model="searchQuery"
                    placeholder="Cari Supplier / ID..." 
                    class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                >
            </div>
        </div>

        <!-- TABEL -->
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase font-bold text-xs">
                    <tr>
                        <th class="px-6 py-3">ID Transaksi</th>
                        <th class="px-6 py-3">Supplier</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3 text-right">Total</th>
                        <th class="px-6 py-3">Petugas</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($daftar_pembelian as $pembelian)
                        {{-- Logika Search Sederhana dengan x-show --}}
                        <tr class="hover:bg-gray-50 transition-colors" 
                            x-show="
                                '{{ strtolower(optional($pembelian->supplier)->nama_supplier) }}'.includes(searchQuery.toLowerCase()) || 
                                '{{ $pembelian->id_transaksi_pembelian }}'.includes(searchQuery)
                            ">
                            
                            <td class="px-6 py-4 font-mono text-xs text-gray-500">
                                #{{ $pembelian->id_transaksi_pembelian }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-800">
                                {{ optional($pembelian->supplier)->nama_supplier ?? 'Supplier Terhapus' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                <div class="flex items-center gap-2">
                                    <i class="fa-regular fa-calendar text-gray-400"></i>
                                    {{ \Carbon\Carbon::parse($pembelian->tanggal_transaksi_pembelian)->format('d M Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-blue-600">
                                Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs border border-gray-200">
                                    {{ optional($pembelian->pengguna)->nama_pengguna ?? 'Admin' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <button 
                                        @click='selectedTransaksi = @json($pembelian); showDetailModal = true'
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                        title="Lihat Detail Barang"
                                    >
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    <form 
                                        action="{{ route('hapus.pembelian', $pembelian['id_transaksi_pembelian']) }}" 
                                        method="POST" 
                                        onsubmit="return confirm('Hapus riwayat transaksi ini? Stok barang akan dikembalikan (jika logika disiapkan).');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus Data">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-file-circle-xmark text-4xl mb-3 opacity-50"></i>
                                    <p>Belum ada riwayat transaksi pembelian.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Footer / Pagination -->
        <div class="p-4 border-t border-gray-100 bg-gray-50 flex justify-between items-center">
            <a href="{{ route('laporan') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800 flex items-center gap-1 transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Laporan
            </a>
            
            {{-- Jika Anda menggunakan pagination laravel ($daftar_pembelian->links()) bisa taruh disini --}}
        </div>
    </div>

    {{-- ================= MODAL DETAIL TRANSAKSI ================= --}}
    <div 
        x-show="showDetailModal" 
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
        style="display: none;"
    >
        <div 
            @click.outside="showDetailModal = false"
            class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]"
        >
            <!-- Header Modal -->
            <div class="p-5 border-b bg-gray-50 flex justify-between items-center">
                <div>
                    <h4 class="font-bold text-gray-800 text-lg">Detail Transaksi</h4>
                    <p class="text-xs text-gray-500 font-mono mt-1">
                        ID: #<span x-text="selectedTransaksi?.id_transaksi_pembelian"></span>
                    </p>
                </div>
                <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <!-- Body Modal (Scrollable) -->
            <div class="p-6 overflow-y-auto custom-scrollbar">
                
                <!-- Info Supplier -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-blue-50 p-3 rounded-lg border border-blue-100">
                        <p class="text-xs text-blue-600 font-bold uppercase mb-1">Supplier</p>
                        <p class="font-semibold text-gray-800" x-text="selectedTransaksi?.supplier?.nama_supplier || 'Supplier dihapus'"></p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <p class="text-xs text-gray-500 font-bold uppercase mb-1">Tanggal Transaksi</p>
                        {{-- Format tanggal sederhana via JS --}}
                        <p class="font-semibold text-gray-800" x-text="new Date(selectedTransaksi?.tanggal_transaksi_pembelian).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })"></p>
                    </div>
                </div>

                <!-- Tabel Item Barang -->
                <h5 class="font-bold text-gray-700 mb-3 border-b pb-2">Item yang Dibeli</h5>
                
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-600 text-xs uppercase">
                        <tr>
                            <th class="px-3 py-2 rounded-l-md">Nama Barang</th>
                            <th class="px-3 py-2 text-center">Jumlah</th>
                            <th class="px-3 py-2 text-center rounded-r-md">Harga Perbarang</th>
                            <th class="px-3 py-2 text-right rounded-r-md">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <!-- Looping Detail menggunakan Alpine x-for -->
                        <!-- Catatan: Pastikan Anda memuat relasi 'detail_pembelian.barang' di Controller -->
                        <template x-if="selectedTransaksi && selectedTransaksi.detail_pembelian">
                            <template x-for="item in selectedTransaksi.detail_pembelian" :key="item.id_detail_pembelian">
                                <tr>
                                    <td class="px-3 py-3 font-medium text-gray-800">
                                        <span x-text="item.barang ? item.barang.nama_barang : 'Barang Dihapus'"></span>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <span class="bg-gray-100 px-2 py-1 rounded text-xs font-bold" x-text="item.jumlah_barang"></span>
                                    </td>
                                    <td class="px-3 py-3 text-center text-gray-600">
                                        Rp <span x-text="new Intl.NumberFormat('id-ID').format(item.harga_perbarang)"></span>
                                    </td>
                                     <td class="px-3 py-3 text-right font-bold text-gray-800">
                                        Rp <span x-text="new Intl.NumberFormat('id-ID').format(item.subtotal)"></span>
                                    </td>
                                </tr>
                            </template>
                        </template>
                        
                        <!-- Fallback jika detail tidak di-load -->
                        <template x-if="!selectedTransaksi?.detail_pembelian">
                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-400 italic">
                                    Detail item tidak tersedia (Pastikan controller me-load relasi).
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Footer Modal (Total) -->
            <div class="p-5 border-t bg-gray-50 flex justify-between items-center">
                <span class="text-gray-500 font-medium">Total Tagihan</span>
                <span class="text-xl font-bold text-blue-600">
                    Rp <span x-text="new Intl.NumberFormat('id-ID').format(selectedTransaksi?.total_harga || 0)"></span>
                </span>
            </div>
        </div>
    </div>
</main>
@endsection