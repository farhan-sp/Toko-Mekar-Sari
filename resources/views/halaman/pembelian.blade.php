@extends('layouts.app')

@section('judul-halaman', 'Pembelian Barang')
@section('isi-content')
<main class="flex-1 p-6 overflow-auto" x-data="{ showAddForm: false }">

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50" role="alert">
            <i class="fa-solid fa-circle-check mr-2"></i>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="flex items-center p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50" role="alert">
            <i class="fa-solid fa-circle-exclamation mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Header & Toolbar --}}
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pembelian Stok</h1>
            <p class="text-sm text-gray-500">Beli barang dari supplier untuk menambah stok</p>
        </div>
        
        <div class="flex gap-3 w-full md:w-auto">
            <div class="relative w-full md:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input 
                    type="text" 
                    id="search-produk" 
                    placeholder="Cari nama barang..." 
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all"
                >
            </div>

            <button 
                @click="showAddForm = !showAddForm"
                class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition shadow-sm flex items-center gap-2 whitespace-nowrap"
            >
                <i class="fa-solid" :class="showAddForm ? 'fa-minus' : 'fa-plus'"></i>
                <span x-text="showAddForm ? 'Tutup Form' : 'Produk Baru'"></span>
            </button>
        </div>
    </div>

    <div 
        x-show="showAddForm" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 mb-8"
        style="display: none;"
    >
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
            <h4 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-box-open text-blue-600"></i>
                Input Data Produk Baru
            </h4>
            <button @click="showAddForm = false" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('pembelian.tambah-barang') }}" method="POST" enctype="multipart/form-data" class="space-y-6"> 
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-4" x-data="{ imagePreview: null }">
                    <label class="block text-sm font-bold text-gray-800 mb-3">Foto Barang</label>
                    
                    <div class="relative w-full h-64 border-2 border-dashed border-gray-300 rounded-2xl bg-gray-50 hover:bg-blue-50 hover:border-blue-400 transition-all duration-300 group flex flex-col items-center justify-center text-center cursor-pointer overflow-hidden">
                        
                        {{-- INPUT FILE --}}
                        {{-- Kita tambahkan event @change untuk membaca file dan membuat preview --}}
                        <input 
                            type="file" 
                            name="gambar" 
                            accept="image/*" 
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                            @change="
                                const file = $event.target.files[0];
                                if(file) {
                                    const reader = new FileReader();
                                    reader.onload = (e) => { imagePreview = e.target.result };
                                    reader.readAsDataURL(file);
                                }
                            "
                        >
                        
                        {{-- TAMPILAN 1: Placeholder (Muncul jika BELUM ada gambar) --}}
                        <div x-show="!imagePreview" class="group-hover:scale-110 transition-transform duration-300 pointer-events-none">
                            <div class="w-16 h-16 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto mb-3">
                                <i class="fa-regular fa-image text-3xl text-blue-500"></i>
                            </div>
                            <p class="text-sm font-semibold text-gray-700">Klik untuk upload foto</p>
                            <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG (Max 2MB)</p>
                        </div>

                        {{-- TAMPILAN 2: Preview Gambar (Muncul jika SUDAH ada gambar) --}}
                        <div x-show="imagePreview" class="absolute inset-0 z-10 w-full h-full bg-white" style="display: none;">
                            <img :src="imagePreview" class="w-full h-full object-contain p-2">
                            
                            {{-- Tombol Ganti (Overlay saat hover) --}}
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <p class="text-white text-sm font-medium"><i class="fa-solid fa-pen mr-2"></i>Ganti Foto</p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">Informasi Produk</h5>
                        
                        <div class="space-y-4">
                            <!-- Nama Barang -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Barang</label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="text" name="nama_barang" class="px-2 border border-gray-300 w-full rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2.5" placeholder="Contoh: Semen Gresik 40kg" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <!-- Kategori -->
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
                                    <div class="relative">
                                        <div class="rounded-md shadow-sm">
                                            <select name="kategori" class="px-2 w-full border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2.5 appearance-none" required>
                                                @foreach ($kategori as $kat)
                                                    <option value="{{ $kat['id_kategori'] }}">{{ $kat['nama_kategori'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                            <i class="fa-solid fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <!-- Supplier -->
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Supplier</label>
                                    <div class="relative">
                                        <div class="rounded-md shadow-sm">
                                            <select name="supplier" class="px-2 w-full border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2.5 appearance-none" required>
                                                @foreach ($supplier as $sup)
                                                    <option value="{{ $sup['id_supplier'] }}">{{ $sup['nama_supplier'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                            <i class="fa-solid fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">Penetapan Harga</h5>
                            
                            <div class="space-y-4">
                                <div>
                                    <!-- Harga Beli -->
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Beli</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-gray-500 sm:text-sm font-bold">Rp</span>
                                        </div>
                                        <input type="number" name="harga_beli" class="block w-full rounded-lg border-gray-300 pl-10 focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5" placeholder="0" required>
                                    </div>
                                </div>
                                <div>
                                    <!-- Harga Jual -->
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Jual</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-gray-500 sm:text-sm font-bold">Rp</span>
                                        </div>
                                        <input type="number" name="harga_jual" class="block w-full rounded-lg border-gray-300 pl-10 focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5" placeholder="0" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">Inventaris</h5>
                            
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Stok Awal -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Stok Awal</label>
                                        <div class="relative rounded-md shadow-sm">
                                            <input type="number" name="stok" class="px-2 border border-gray-300 w-full rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2.5" placeholder="0" required>
                                        </div>
                                    </div>
                                    <!-- Stok Minimal -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Min. Stok</label>
                                        <div class="relative rounded-md shadow-sm">
                                            <input type="number" name="min_stok" class="px-2 border border-gray-300 w-full rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2.5" placeholder="10" required>
                                        </div>
                                    </div>
                                </div>
                                <!-- Satuan Unit -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Satuan Unit</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="text" name="satuan" class="px-2 border border-gray-300 w-full rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2.5" placeholder="Contoh: Pcs, Sak, Kg" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end gap-3">
                <button type="button" @click="showAddForm = false" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-sm">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-save"></i> Simpan Produk
                </button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="product-grid">
        @foreach($barang as $item)
        <div class="produk-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300 flex flex-col">    
            <div class="relative h-48 bg-white overflow-hidden group">
                @if($item['gambar_barang'])
                    <img 
                        src="{{ asset('storage/' . $item['gambar_barang']) }}" 
                        class="w-full h-full object-contain p-2 transition-transform duration-500 group-hover:scale-110" 
                        alt="{{ $item['nama_barang'] }}"
                    >
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-50">
                        <i class="fa-regular fa-image text-3xl mb-1"></i>
                        <span class="text-xs">No Image</span>
                    </div>
                @endif

                {{-- Badge Stok --}}
                <div class="absolute top-2 right-2">
                    @if($item['jumlah_stok_barang'] <= $item['stok_minimal'])
                        <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-full shadow-sm">
                            KRITIS
                        </span>
                    @else
                        <span class="bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded-full shadow-sm">
                            AMAN
                        </span>
                    @endif
                </div>
            </div>

            <div class="p-4 flex-1 flex flex-col justify-between">
                <div>
                    <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-semibold">{{ $item['nama_kategori'] }}</div>
                    {{-- Class 'nama-produk' ditambahkan disini agar script JS jalan --}}
                    <h3 class="nama-produk font-bold text-gray-800 text-lg leading-tight mb-2 truncate" title="{{ $item['nama_barang'] }}">
                        {{ $item['nama_barang'] }}
                    </h3>
                    
                    <div class="flex justify-between items-end mb-4">
                        <div>
                            <p class="text-xs text-gray-400">Harga Beli</p>
                            <p class="font-bold text-gray-700">Rp {{ number_format($item['harga_beli'], 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-400">Stok</p>
                            <p class="font-bold {{ $item['jumlah_stok_barang'] <= $item['stok_minimal'] ? 'text-red-600' : 'text-blue-600' }}">
                                {{ $item['jumlah_stok_barang'] }} <span class="text-[10px] text-gray-400 font-normal">{{ $item['satuan'] }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('pembelian.store') }}" method="POST" class="mt-auto pt-3 border-t border-gray-100">
                    @csrf
                    <input type="hidden" name="id_barang" value="{{ $item['id_barang'] }}">
                    
                    <div class="flex gap-2">
                        <input 
                            type="number" 
                            name="jumlah" 
                            value="1" 
                            min="1"
                            class="w-16 px-2 py-1.5 text-center text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                            required 
                        >
                        <button 
                            type="submit" 
                            class="flex-1 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors flex items-center justify-center gap-2"
                        >
                            <i class="fa-solid fa-cart-plus"></i> Beli
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    @if(count($barang) == 0)
        <div class="text-center py-16">
            <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-box-open text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Belum ada data produk</h3>
            <p class="text-gray-500">Silakan tambahkan produk baru terlebih dahulu.</p>
        </div>
    @endif

</main>
@endsection

@push('script')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-produk');
    // Ambil container grid untuk menghandle "No Result" view nanti (opsional)
    const productGrid = document.getElementById('product-grid');
    const productCards = document.querySelectorAll('.produk-card');

    searchInput.addEventListener('keyup', function() {
      const searchTerm = searchInput.value.toLowerCase();
      let hasVisibleCard = false;

      productCards.forEach(function(card) {
        // Selector diperbaiki: Mencari elemen dengan class 'nama-produk'
        const titleElement = card.querySelector('.nama-produk');
        
        if (titleElement) {
            const productName = titleElement.textContent.toLowerCase();
            if (productName.includes(searchTerm)) {
              card.style.display = ''; // Tampilkan (reset display)
              hasVisibleCard = true;
            } else {
              card.style.display = 'none'; // Sembunyikan
            }
        }
      });
    });
  });
</script>
@endpush