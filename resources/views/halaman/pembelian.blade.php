@extends('layouts.app')

@section('judul-halaman', 'Pembelian Barang')
@section('isi-content')
<main class="flex-1 p-6 overflow-auto" x-data="{ showAddForm: false }">

    @if (session('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
        {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
        {{ session('error') }}
        </div>
    @endif
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-lg font-bold">Pembelian Barang</h1>
        
        <button 
            x-show="!showAddForm" 
            @click="showAddForm = true"
            class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition duration-150"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90"
        >
            <i class="fa-solid fa-plus mr-1"></i> Tambah Produk
        </button>
    </div>
    <div 
        x-show="showAddForm" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform -translate-y-4"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-4"
        class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6"
    >
        <div class="flex justify-between items-center mb-4">
            <h4 class="font-semibold text-gray-700">Tambah Produk Baru</h4>
            <button @click="showAddForm = false" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark fa-lg"></i>
            </button>
        </div>
        
        <form action="{{ route('pembelian.tambah-barang') }}" method="POST"> 
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                    <input type="text" id="nama_barang" name="nama_barang" class="w-full border rounded-md p-2 text-sm" placeholder="Contoh: Semen Gresik">
                </div>
                <div>
                    <label for="supplier" class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                    <select type="number" id="supplier" name="supplier" class="w-full border rounded-md p-2 text-sm" placeholder="Contoh: 10" min="0">
                        @foreach ($kategori as $kat)
                            <option value="{{ $kat['id_kategori'] }}" name="kategori">{{ $kat['nama_kategori'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="harga_beli" class="block text-sm font-medium text-gray-700 mb-1">Harga Beli(Rp)</label>
                    <input type="number" id="harga_beli" name="harga_beli" class="w-full border rounded-md p-2 text-sm" placeholder="Contoh: 50000">
                </div>
                <div>
                    <label for="harga_jual" class="block text-sm font-medium text-gray-700 mb-1">Harga Jual(Rp)</label>
                    <input type="number" id="harga_jual" name="harga_jual" class="w-full border rounded-md p-2 text-sm" placeholder="Contoh: 50000">
                </div>
                <div>
                    <label for="stok" class="block text-sm font-medium text-gray-700 mb-1">Stok Awal</label>
                    <input type="number" id="stok" name="stok" class="w-full border rounded-md p-2 text-sm" placeholder="Contoh: 100" min="0">
                </div>
                <div>
                    <label for="min_stok" class="block text-sm font-medium text-gray-700 mb-1">Stok Minimum</label>
                    <input type="number" id="min_stok" name="min_stok" class="w-full border rounded-md p-2 text-sm" placeholder="Contoh: 10" min="0">
                </div>
                <div>
                    <label for="satuan" class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                    <input type="text" id="satuan" name="satuan" class="w-full border rounded-md p-2 text-sm" placeholder="Contoh: kg/set/pickup" min="0">
                </div>
                <div>
                    <label for="supplier" class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                    <select type="number" id="supplier" name="supplier" class="w-full border rounded-md p-2 text-sm" placeholder="Contoh: 10" min="0">
                        @foreach ($supplier as $usaha)
                            <option value="{{ $usaha['id_supplier'] }}" name="supplier">{{ $usaha['nama_supplier'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t text-right">
                <!-- Tombol Batal: Mengubah 'showAddForm' kembali ke 'false' -->
                <button type="button" @click="showAddForm = false" class="border px-4 py-2 rounded-md text-sm mr-2 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-700">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>

    @if (count($barang_restok) > 0)
    <div class="border border-red-200 bg-red-50 text-red-600 rounded-lg p-4 mb-6">
        <p class="font-medium">Peringatan: Stok Hampir Habis ({{ count($barang_restok) }} item)</p>
        <div class="grid grid-cols-2 gap-3 mt-2">
            @foreach($barang_restok as $item)
            <div class="p-3 border rounded-md flex justify-between items-center">
                <div>
                    <h5 class="font-semibold">{{ $item['nama_barang'] }}</h5>
                    <p class="text-xs text-gray-400">Stok: {{ $item['jumlah_stok_barang'] }}</p>
                    <p class="text-gray-500 text-sm">Rp {{ number_format($item['harga_jual'],0,",",".") }}</p>
                </div>
                <div class="flex gap-2">
                    <div class="mt-3 flex gap-2">
                        <form action="{{ route('pembelian.store') }}" method="POST" class="mt-3 flex gap-2">
                            @csrf
                            <input type="hidden" name="id_barang" value="{{ $item['id_barang'] }}">

                            <input 
                                type="number" 
                                name="jumlah" 
                                value="1" 
                                min="1"
                                max="{{ $item['jumlah_stok_barang'] }}"
                                class="w-16 border rounded-md p-1 text-sm text-center" 
                                required 
                            >
                            
                            <button 
                                type="submit" 
                                class="bg-red-600 text-white rounded-md px-3 py-1 text-sm hover:bg-red-500"
                            >
                                Tambah
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid gap-6">
        <!-- Produk -->
        <section class="col-span-2 bg-white p-5 rounded-xl shadow-sm border border-gray-100">
        <h4 class="font-semibold text-gray-700 mb-3">Pilih Produk</h4>
        <input 
            type="text" 
            placeholder="Cari produk..." 
            id="search-produk" 
            class="w-full border rounded-md p-2 text-sm mb-4" />
        
        <div class="grid grid-cols-3 gap-4">
            @foreach($barang as $item)
            <div class="produk-card bg-white p-6 sm:p-8 rounded-xl border border-black-100 w-full mx-auto mb-8 flex justify-between items-start gap-4">
            <div>
                <h5 class="font-semibold">{{ $item['nama_barang'] }}</h5>
                <p class="nama-produk text-sm text-gray-500">{{ $item['nama_kategori'] }}</p>
                <p class="mt-1">Stok: <span class="font-medium">{{ $item['jumlah_stok_barang'] }}</span></p>
                <p class="mt-1 text-gray-700 font-semibold">Rp {{ number_format($item['harga_jual'],0,",",".") }}</p>
                
                <div class="mt-3 flex gap-2">
                    <form action="{{ route('pembelian.store') }}" method="POST" class="mt-3 flex gap-2">
                    @csrf
                    <input type="hidden" name="id_barang" value="{{ $item['id_barang'] }}">

                    <input 
                        type="number" 
                        name="jumlah" 
                        value="1" 
                        min="1"
                        max="{{ $item['jumlah_stok_barang'] }}"
                        class="w-16 border rounded-md p-1 text-sm text-center" 
                        required 
                    >
                    
                    <button 
                        type="submit" 
                        class="bg-red-600 text-white rounded-md px-3 py-1 text-sm hover:bg-red-500"
                    >
                        Tambah
                    </button>
                    </form>
                </div>
            </div>

            <aside class="flex-shrink-0">
                <img class="h-20 w-20 rounded-md object-cover" src="{{ asset('/build/assets/image/logo.png') }}" alt="">
            </aside>
            </div>
            @endforeach
        </div>
        </section>
    </div>
</main>
@endsection

@push('script')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-produk');
    const productCards = document.querySelectorAll('.produk-card');
    searchInput.addEventListener('input', function() {
      const searchTerm = searchInput.value.toLowerCase();

      productCards.forEach(function(card) {
        const productName = card.querySelector('.nama-produk').textContent.toLowerCase();

        if (productName.includes(searchTerm)) {
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });
</script>
@endpush