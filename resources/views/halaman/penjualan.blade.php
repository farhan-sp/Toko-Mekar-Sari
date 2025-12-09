@extends('layouts.app')

@section('judul-halaman', 'Penjualan')
@section('isi-content')

<main class="flex-1 p-4 lg:p-6 overflow-hidden flex flex-col h-full relative" 
    x-data="{ 
        showCartMobile: false,
        showDetailModal: false, 
        selectedTransaksi: null,
        lastTransactionId: '{{ session('last_transaction_id') }}',

        // Fungsi fetch data transaksi (AJAX)
        async fetchTransaction(id) {
            if(!id) return;
            try {
                let response = await fetch('/penjualan/detail-json/' + id);
                let data = await response.json();
                this.selectedTransaksi = data;
                this.showDetailModal = true;
            } catch (error) {
                console.error('Gagal mengambil data transaksi', error);
            }
        },

        // Auto open modal jika ada session success
        init() {
            if(this.lastTransactionId) {
                this.fetchTransaction(this.lastTransactionId);
            }
        }
    }"
>

    {{-- Notifikasi Sukses dengan Tombol Lihat Struk --}}
    @if (session('success'))
        <div class="flex flex-col sm:flex-row items-center justify-between p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 shadow-sm" role="alert">
            <div class="flex items-center mb-2 sm:mb-0">
                <i class="fa-solid fa-circle-check mr-2 text-lg"></i>
                <div>
                    <span class="font-bold block">Berhasil!</span>
                    {{ session('success') }}
                </div>
            </div>
            
            {{-- Tombol Pemicu Modal Manual --}}
            @if(session('last_transaction_id'))
                <button @click="fetchTransaction('{{ session('last_transaction_id') }}')" class="bg-green-700 text-white px-3 py-1.5 rounded-md hover:bg-green-800 transition text-xs font-bold shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-receipt"></i> Cetak Struk
                </button>
            @endif
        </div>
    @endif

    @if (session('error'))
        <div class="flex items-center p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50" role="alert">
            <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col lg:flex-row h-full gap-6 overflow-hidden">
        <section class="flex-1 flex flex-col min-w-0 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden h-full relative">
            
            <div class="p-4 lg:p-5 border-b border-gray-100 bg-white z-10">
                <form action="{{ route('penjualan.index') }}" method="GET" class="flex gap-2">
                    <div class="relative w-full">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input 
                            type="text" 
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari produk & enter..." 
                            class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                        >
                    </div>
                    <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800">
                        Cari
                    </button>
                </form>
            </div>
            
            <div class="flex-1 overflow-y-auto p-4 lg:p-5 bg-gray-50 custom-scrollbar pb-32 lg:pb-20"> 
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 lg:gap-4">
                    @forelse($barang as $item)
                    <div class="bg-white rounded-lg lg:rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden group">
                        <div class="aspect-square bg-white relative overflow-hidden flex items-center justify-center p-2"> 
                            @if($item['gambar_barang'])
                                <img src="{{ asset('storage/' . $item['gambar_barang']) }}" class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-110" alt="{{ $item['nama_barang'] }}">
                            @else
                                <div class="flex flex-col items-center justify-center text-gray-300">
                                    <i class="fa-regular fa-image text-3xl"></i>
                                </div>
                            @endif
                            
                            {{-- Badge Stok --}}
                            <div class="absolute top-2 right-2">
                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded shadow-sm {{ $item['jumlah_stok_barang'] > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $item['jumlah_stok_barang'] }}
                                </span>
                            </div>
                        </div>

                        <div class="p-3 flex-1 flex flex-col">
                            <p class="text-[10px] text-gray-400 uppercase font-semibold mb-0.5 truncate">{{ $item['nama_kategori'] }}</p>
                            <h5 class="font-bold text-gray-800 text-xs sm:text-sm leading-tight mb-2 line-clamp-2 min-h-[2.5em]" title="{{ $item['nama_barang'] }}">
                                {{ $item['nama_barang'] }}
                            </h5>
                            
                            <div class="mt-auto flex items-center justify-between border-t border-gray-100 pt-2">
                                <p class="font-bold text-green-600 text-xs sm:text-sm">Rp {{ number_format($item['harga_jual'], 0, ',', '.') }}</p>
                            </div>

                            <form action="{{ route('penjualan.cart.add') }}" method="POST" class="mt-2 flex gap-1">
                                @csrf
                                <input type="hidden" name="id_barang" value="{{ $item['id_barang'] }}">
                                <input type="number" name="jumlah" value="1" min="1" max="{{ $item['jumlah_stok_barang'] }}" class="w-10 px-1 py-1 text-center text-xs border border-gray-300 rounded focus:ring-blue-500" {{ $item['jumlah_stok_barang'] == 0 ? 'disabled' : '' }}>
                                <button type="submit" class="flex-1 bg-green-600 text-white text-xs font-medium rounded py-1.5 hover:bg-green-500 disabled:bg-gray-300 transition-colors" {{ $item['jumlah_stok_barang'] == 0 ? 'disabled' : '' }}>
                                    <span class="hidden sm:inline">Tambah</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-10 text-gray-400">
                        <i class="fa-solid fa-magnifying-glass text-4xl mb-3"></i>
                        <p>Produk tidak ditemukan</p>
                    </div>
                    @endforelse
                </div>                
            </div>

            <div class="absolute bottom-0 left-0 w-full bg-white border-t border-gray-200 p-3 z-20 pb-24 lg:pb-3">
                {{ $barang->appends(['search' => request('search')])->links() }}
            </div>
        </section>

        <div 
            x-show="showCartMobile"
     
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm lg:static lg:bg-transparent lg:p-0 lg:z-auto lg:w-96 lg:!flex lg:!opacity-100 lg:!scale-100"
            style="display: none;" {{-- Mencegah FOUC --}}
        >            
            <div 
                @click.outside="showCartMobile = false"
                class="bg-white w-full max-w-md h-[85vh] rounded-2xl shadow-2xl flex flex-col overflow-hidden lg:h-full lg:w-full lg:max-w-none lg:shadow-sm lg:rounded-xl lg:border lg:border-gray-200"
            >
                {{-- Header Keranjang --}}
                <div class="flex justify-between items-center p-4 border-b border-gray-100 bg-gray-50">
                    <h4 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-cart-shopping text-green-600"></i> Keranjang
                    </h4>
                    
                    {{-- Tombol Close (Hanya muncul di Mobile) --}}
                    <button @click="showCartMobile = false" class="text-gray-400 hover:text-red-500 lg:hidden bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                @if(session('cart') && count(session('cart')) > 0)
                    @php $total_harga = 0; @endphp
                    <form action="{{ route('penjualan.store') }}" method="POST" class="flex flex-col h-full overflow-hidden" >
                        @csrf
                        
                        {{-- Scrollable Content --}}
                        <div class="flex-1 overflow-y-auto p-4 custom-scrollbar space-y-4">
                            
                            {{-- Form Pelanggan --}}
                            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 shadow-sm">
                                <h5 class="text-xs font-bold text-blue-800 uppercase mb-2 flex items-center gap-1">
                                    <i class="fa-regular fa-id-card"></i> Info Pelanggan
                                </h5>
                                <input type="text" name="nama" class="w-full border-gray-200 rounded-lg text-sm mb-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Nama Pelanggan" required>
                                <div class="flex gap-2">
                                    <input type="text" name="telepon" class="w-1/2 border-gray-200 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="No HP">
                                    <input type="text" name="alamat" class="w-1/2 border-gray-200 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Alamat">
                                </div>
                            </div>

                            {{-- List Item --}}
                            <ul class="space-y-3">
                                @foreach (session('cart') as $id => $details)
                                <li class="flex gap-3 p-3 rounded-xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow relative group">
                                    {{-- Gambar Kecil --}}
                                    <div class="w-12 h-12 bg-gray-50 rounded-lg border border-gray-100 flex-shrink-0 flex items-center justify-center">
                                        <i class="fa-solid fa-box text-gray-300"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-gray-800 text-sm line-clamp-1">{{ $details['nama'] }}</p>
                                        <div class="flex justify-between items-center mt-1">
                                            <div class="flex items-center text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">
                                                {{ $details['jumlah'] }} x {{ number_format($details['harga']/1000, 0) }}k
                                            </div>
                                            <span class="font-bold text-blue-600 text-sm">Rp {{ number_format($details['jumlah'] * $details['harga'], 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    @php $total_harga += $details['jumlah'] * $details['harga']; @endphp

                                    <button 
                                        type="submit" 
                                        formaction="{{ route('penjualan.cart.remove', $id) }}"
                                        class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                        title="Hapus Barang"
                                        formnovalidate
                                    >
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Footer Total & Button --}}
                        <div class="p-4 border-t border-gray-100 bg-gray-50 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.02)]">
                            <div class="flex justify-between items-end mb-4">
                                <span class="text-gray-500 text-sm font-medium">Total Tagihan</span>
                                <span class="text-2xl font-bold text-gray-800">Rp {{ number_format($total_harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="flex-1 bg-gray-900 hover:bg-gray-800 text-white py-3 rounded-lg font-bold shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2" onclick="return confirm('Yakin ingin menyimpan transaksi ini?')">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    {{-- State Kosong --}}
                    <div class="flex-1 flex flex-col items-center justify-center text-gray-400 p-8 text-center">
                        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-4 animate-pulse">
                            <i class="fa-solid fa-basket-shopping text-4xl text-gray-300"></i>
                        </div>
                        <h5 class="text-gray-600 font-bold mb-1">Keranjang Kosong</h5>
                        <p class="text-xs">Pilih produk disamping untuk transaksi.</p>
                        <button @click="showCartMobile = false" class="mt-6 px-6 py-2 bg-blue-50 text-blue-600 rounded-full text-sm font-bold lg:hidden">
                            Kembali Belanja
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if(session('cart') && count(session('cart')) > 0)
        @php
            $total_mobile = 0;
            foreach(session('cart') as $item) {
                $total_mobile += $item['harga'] * $item['jumlah'];
            }
        @endphp

        <div class="fixed bottom-6 left-4 right-4 z-40 lg:hidden flex justify-center pointer-events-none">
            <button 
                type="button"
                @click="showCartMobile = true" 
                class="pointer-events-auto w-full max-w-md bg-gray-900 text-white p-3 pr-5 rounded-full shadow-2xl flex justify-between items-center group active:scale-95 transition-all border border-gray-700 hover:bg-gray-800"
            >
                {{-- Bagian Kiri: Icon & Count --}}
                <div class="flex items-center gap-3">
                    <div class="bg-white text-gray-900 w-10 h-10 rounded-full flex items-center justify-center relative shadow-sm">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold h-5 w-5 flex items-center justify-center rounded-full border-2 border-gray-900">
                            {{ count(session('cart')) }}
                        </span>
                    </div>
                    <div class="flex flex-col items-start">
                        <span class="text-[10px] text-gray-400 uppercase font-semibold tracking-wider leading-none mb-0.5">Total Belanja</span>
                        <span class="font-bold text-sm leading-none">Rp {{ number_format($total_mobile, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Bagian Kanan: Text Action --}}
                <div class="flex items-center gap-2 text-gray-300 group-hover:text-white transition-colors">
                    <span class="text-xs font-semibold">Lihat</span>
                    <div class="bg-gray-800 h-8 w-8 rounded-full flex items-center justify-center group-hover:bg-gray-700 transition">
                        <i class="fa-solid fa-chevron-up text-xs"></i>
                    </div>
                </div>
            </button>
        </div>
    @endif

    {{-- ================= MODAL DETAIL & CETAK STRUK ================= --}}
    <div 
        x-show="showDetailModal" 
        class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
        x-transition
        style="display: none;"
    >
        <div 
            @click.outside="showDetailModal = false"
            class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]"
        >
            <div class="p-5 border-b bg-gray-50 flex justify-between items-center">
                <div>
                    <h4 class="font-bold text-gray-800 text-lg">Cetak Struk</h4>
                    <p class="text-xs text-gray-500 font-mono mt-1">
                        ID: #<span x-text="selectedTransaksi?.id_transaksi_penjualan"></span>
                    </p>
                </div>
                <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <div class="p-6 overflow-y-auto custom-scrollbar">
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-green-50 p-3 rounded-lg border border-green-100">
                        <p class="text-xs text-green-600 font-bold uppercase mb-1">Pelanggan</p>
                        <p class="font-semibold text-gray-800" x-text="selectedTransaksi?.pelanggan?.nama_pelanggan || 'Pelanggan Umum'"></p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <p class="text-xs text-gray-500 font-bold uppercase mb-1">Tanggal</p>
                        <p class="font-semibold text-gray-800" x-text="selectedTransaksi ? new Date(selectedTransaksi.tanggal_transaksi_penjualan).toLocaleDateString('id-ID') : '-'"></p>
                    </div>
                </div>

                <h5 class="font-bold text-gray-700 mb-3 border-b pb-2 text-sm">Rincian Barang</h5>
                
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-600 text-xs uppercase">
                        <tr>
                            <th class="px-3 py-2 rounded-l-md">Barang</th>
                            <th class="px-3 py-2 text-center">Qty</th>
                            <th class="px-3 py-2 text-right rounded-r-md">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-if="selectedTransaksi && selectedTransaksi.detail_penjualan">
                            <template x-for="item in selectedTransaksi.detail_penjualan">
                                <tr>
                                    <td class="px-3 py-3 font-medium text-gray-800">
                                        <span x-text="item.barang ? item.barang.nama_barang : 'Barang dihapus'"></span>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <span class="bg-gray-100 px-2 py-1 rounded text-xs font-bold" x-text="item.jumlah_barang"></span>
                                    </td>
                                     <td class="px-3 py-3 text-right font-bold text-gray-800">
                                        Rp <span x-text="new Intl.NumberFormat('id-ID').format(item.harga_perbarang * item.jumlah_barang)"></span>
                                    </td>
                                </tr>
                            </template>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t bg-gray-50 flex justify-between items-center">
                <div>
                    <span class="text-gray-500 font-medium text-sm block">Total Tagihan</span>
                    <span class="text-xl font-bold text-green-600">
                        Rp <span x-text="new Intl.NumberFormat('id-ID').format(selectedTransaksi?.total_harga || 0)"></span>
                    </span>
                </div>

                {{-- TOMBOL CETAK STRUK --}}
                <a 
                    :href="'/penjualan/cetak-struk/' + (selectedTransaksi ? selectedTransaksi.id_transaksi_penjualan : '')" 
                    target="_blank"
                    class="bg-gray-900 text-white px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-gray-800 transition flex items-center gap-2 shadow-lg"
                >
                    <i class="fa-solid fa-print"></i> Cetak Sekarang
                </a>
            </div>
        </div>
    </div>

</main>
@endsection

@push('script')
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #bbb; }
</style>
@endpush