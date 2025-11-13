@extends('layouts.app')

@section('judul-halaman', 'Penjualan Barang')
@section('isi-content')
<!-- Content Section -->
<main class="flex-1 p-6 overflow-auto">

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

  <h3 class="text-lg font-bold mb-4">Transaksi Penjualan</h3>

  <div class="grid grid-cols-3 gap-6">
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
                <form action="{{ route('penjualan.cart.add') }}" method="POST" class="mt-3 flex gap-2">
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

    <aside class="col-span-1 flex flex-col"> 
    @php $total_harga = 0; @endphp
      @if(session('cart') && count(session('cart')) > 0)
        <form action="{{ route('penjualan.store') }}" method="POST" class="flex flex-col space-y-3">
            @csrf

            <section class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <h4 class="font-semibold text-gray-700 mb-3">Data Pelanggan</h4>
                <div class="space-y-3">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
                        <input 
                            type="text" 
                            name="nama" 
                            id="nama" 
                            class="mt-1 w-full border rounded-md p-2 text-sm" 
                            placeholder="Nama Pelanggan">
                    </div>
                    <div>
                        <label for="telepon" class="block text-sm font-medium text-gray-700">No. Telepon</label>
                        <input 
                            type="text" 
                            name="telepon" 
                            id="telepon" 
                            class="mt-1 w-full border rounded-md p-2 text-sm" 
                            placeholder="081234567890">
                    </div>
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat Lokasi</label>
                        <input 
                            type="text" 
                            name="alamat" 
                            id="alamat" 
                            class="mt-1 w-full border rounded-md p-2 text-sm" 
                            placeholder="Jl. Maling Kundang 2">
                    </div>
                </div>
            </section>

            <section class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col">
                <h4 class="font-semibold text-gray-700 mb-3">Keranjang Belanja</h4>
                
                <div class="flex-grow mb-4"> 
                    <ul class="space-y-3 text-sm max-h-60 overflow-y-auto">
                        @foreach (session('cart') as $id => $details)
                        <li class="font-bold flex justify-between p-4 rounded-xl bg-gray-200">
                            <div class="flex-1">
                                <p class="font-semibold">{{ $details['nama'] }}</p>
                                <p class="text-xs text-gray-500">Rp {{ number_format($details['harga'], 0, ',', '.') }}</p>
                            </div>
                            <p class="text-gray-500">
                                Jumlah: {{ $details['jumlah'] }}
                            </p>
                            @php
                                $total_harga += $details['jumlah'] * $details['harga'];
                            @endphp
                        </li>
                        @endforeach
                    </ul>
                </div>
                
                <hr class="my-3">
                <div class="flex justify-between font-bold text-md mb-4">
                    <span>Total:</span>
                    <span>Rp {{ number_format($total_harga, 0, ',', '.') }}</span>
                </div>
            </section>

            <section class="bg-white p-2 rounded-t-xl border-x border-t border-gray-100">
              <button type="submit" class="
                bg-green-600 text-white py-2 rounded-md w-full 
                hover:bg-green-500 text-center hover:-translate-y-1 transition-all duration-300">
                  Simpan Transaksi
              </button>
            </section>
        </form> 
        
        <section class="bg-white p-2 rounded-b-xl border-x border-b border-gray-100">
            <form action="{{ route('penjualan.cart.clear') }}" method="POST" class="">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md w-full hover:bg-red-500 text-center hover:-translate-y-1 transition-all duration-300">
                    Kosongkan Keranjang
                </button>
            </form>
        </section>

      @else
        <h4 class="font-semibold text-gray-700 mb-3">Keranjang Belanja</h4>
        <section class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <div class="text-center text-gray-400 flex flex-col items-center justify-center h-40">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.2 6h12.4M7 13L5.4 5M16 19a2 2 0 11-4 0" />
                </svg>
                <p>Keranjang kosong</p>
            </div>
        </section>
      @endif

    </aside>
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