@extends('layouts.app')

@section('judul-halaman', 'Kasir Penjualan')
@section('isi-content')
<main class="flex-1 p-6 overflow-hidden flex flex-col h-full">

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

    <div class="flex flex-col lg:flex-row h-full gap-6 overflow-hidden">
        
        <section class="flex-1 flex flex-col min-w-0 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden h-full">
            
            <div class="p-5 border-b border-gray-100 bg-white z-10">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-box-open text-blue-600"></i> Katalog Produk
                    </h3>
                    <span class="text-xs font-semibold bg-gray-100 text-gray-500 px-2 py-1 rounded-md">
                        Total: {{ count($barang) }} Item
                    </span>
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input 
                        type="text" 
                        id="search-produk" 
                        placeholder="Cari nama barang atau kategori..." 
                        class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                    />
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto p-5 bg-gray-50 custom-scrollbar">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4" id="product-grid">
                    @foreach($barang as $item)
                    <div class="produk-card bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden group">
                        
                        <div class="h-40 bg-white relative overflow-hidden group flex items-center justify-center"> 
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
                          
                          <div class="absolute top-2 right-2">
                              <span class="text-[10px] font-bold px-2 py-1 rounded-full shadow-sm {{ $item['jumlah_stok_barang'] > 0 ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                  Stok: {{ $item['jumlah_stok_barang'] }}
                              </span>
                          </div>
                        </div>

                        <div class="p-4 flex-1 flex flex-col">
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide mb-1">{{ $item['nama_kategori'] }}</p>
                            {{-- Class 'nama-produk' dipindah kesini agar pencarian benar --}}
                            <h5 class="nama-produk font-bold text-gray-800 text-sm leading-tight mb-2 line-clamp-2" title="{{ $item['nama_barang'] }}">
                                {{ $item['nama_barang'] }}
                            </h5>
                            
                            <div class="mt-auto pt-3 border-t border-gray-100 flex items-center justify-between">
                                <p class="font-bold text-blue-600 text-sm">Rp {{ number_format($item['harga_jual'], 0, ',', '.') }}</p>
                            </div>

                            <form action="{{ route('penjualan.cart.add') }}" method="POST" class="mt-3 flex gap-2">
                                @csrf
                                <input type="hidden" name="id_barang" value="{{ $item['id_barang'] }}">
                                
                                {{-- Input Jumlah --}}
                                <div class="relative w-16">
                                    <input 
                                        type="number" 
                                        name="jumlah" 
                                        value="1" 
                                        min="1"
                                        max="{{ $item['jumlah_stok_barang'] }}"
                                        class="w-full pl-2 pr-1 py-1.5 text-center text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                        {{ $item['jumlah_stok_barang'] == 0 ? 'disabled' : '' }}
                                    >
                                </div>

                                <button 
                                    type="submit" 
                                    class="flex-1 bg-gray-900 text-white text-xs font-bold uppercase tracking-wide rounded-lg hover:bg-gray-700 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed"
                                    {{ $item['jumlah_stok_barang'] == 0 ? 'disabled' : '' }}
                                >
                                    {{ $item['jumlah_stok_barang'] == 0 ? 'Habis' : 'Tambah' }}
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                {{-- Pesan jika pencarian kosong (Hidden by default) --}}
                <div id="no-result" class="hidden flex-col items-center justify-center py-20 text-gray-400">
                    <i class="fa-solid fa-magnifying-glass text-4xl mb-3"></i>
                    <p>Produk tidak ditemukan</p>
                </div>
            </div>
        </section>

        <aside class="w-full lg:w-96 flex flex-col gap-4 h-full overflow-hidden">
            
            @if(session('cart') && count(session('cart')) > 0)
                @php $total_harga = 0; @endphp
                <form action="{{ route('penjualan.store') }}" method="POST" class="flex flex-col h-full gap-4">
                    @csrf
                    
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex-shrink-0">
                        <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2 text-sm uppercase tracking-wide">
                            <i class="fa-solid fa-user text-blue-500"></i> Data Pelanggan
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <input type="text" name="nama" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Nama Pelanggan (Wajib)" required>
                            </div>
                            <div class="flex gap-2">
                                <input type="text" name="telepon" class="w-1/2 border-gray-300 rounded-lg text-sm" placeholder="No. HP">
                                <input type="text" name="alamat" class="w-1/2 border-gray-300 rounded-lg text-sm" placeholder="Alamat Singkat">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col flex-1 overflow-hidden">
                        <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                            <h4 class="font-bold text-gray-800 text-sm uppercase tracking-wide">
                                <i class="fa-solid fa-cart-shopping text-blue-500 mr-1"></i> Keranjang
                            </h4>
                            <span class="text-xs font-semibold bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">
                                {{ count(session('cart')) }} Item
                            </span>
                        </div>
                        
                        <div class="flex-1 overflow-y-auto p-4 custom-scrollbar">
                            <ul class="space-y-3">
                                @foreach (session('cart') as $id => $details)
                                <li class="flex justify-between items-start gap-3 p-3 rounded-lg border border-dashed border-gray-300 bg-gray-50 hover:bg-white transition-colors">
                                    <div class="flex-1">
                                        <p class="font-bold text-gray-800 text-sm line-clamp-1">{{ $details['nama'] }}</p>
                                        <div class="flex items-center text-xs text-gray-500 mt-1">
                                            <span>{{ $details['jumlah'] }} x Rp {{ number_format($details['harga'], 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-blue-600 text-sm">
                                            Rp {{ number_format($details['jumlah'] * $details['harga'], 0, ',', '.') }}
                                        </p>
                                        
                                        {{-- Tombol Hapus Item (Opsional: Butuh route hapus per item) --}}
                                        {{-- <a href="#" class="text-[10px] text-red-500 hover:underline mt-1 block">Hapus</a> --}}
                                    </div>
                                    @php $total_harga += $details['jumlah'] * $details['harga']; @endphp
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="p-5 border-t border-gray-200 bg-gray-50">
                            <div class="flex justify-between items-end mb-4">
                                <span class="text-gray-500 text-sm font-medium">Total Bayar</span>
                                <span class="text-2xl font-bold text-gray-800">Rp {{ number_format($total_harga, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="space-y-2">
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-bold shadow-md hover:shadow-lg transform active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-check-circle"></i> Proses Transaksi
                                </button>
                                
                                {{-- Tombol Kosongkan (Diluar form utama, pakai formaction atau JS submit form lain) --}}
                            </div>
                        </div>
                    </div>
                </form>
                
                <form action="{{ route('penjualan.cart.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-white border border-red-200 text-red-600 hover:bg-red-50 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fa-solid fa-trash-can mr-1"></i> Batal / Kosongkan Keranjang
                    </button>
                </form>

            @else
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200 text-center flex flex-col items-center justify-center h-full">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400">
                        <i class="fa-solid fa-basket-shopping text-3xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg">Keranjang Kosong</h3>
                    <p class="text-gray-500 text-sm mt-1 max-w-[200px]">
                        Pilih produk di sebelah kiri untuk memulai transaksi.
                    </p>
                </div>
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
    const noResultMsg = document.getElementById('no-result');

    searchInput.addEventListener('keyup', function() {
      const searchTerm = searchInput.value.toLowerCase();
      let hasVisibleCard = false;

      productCards.forEach(function(card) {
        // Selector Benar: Mencari class 'nama-produk'
        const titleElement = card.querySelector('.nama-produk');
        
        if (titleElement) {
            const productName = titleElement.textContent.toLowerCase();
            // Cari juga berdasarkan kategori (opsional)
            // const categoryName = card.querySelector('p.text-gray-400').textContent.toLowerCase();

            if (productName.includes(searchTerm)) {
              card.style.display = ''; // Reset display (flex/block)
              hasVisibleCard = true;
            } else {
              card.style.display = 'none';
            }
        }
      });

      // Tampilkan pesan "Tidak ditemukan" jika semua card hidden
      if (!hasVisibleCard) {
        noResultMsg.classList.remove('hidden');
        noResultMsg.classList.add('flex');
      } else {
        noResultMsg.classList.add('hidden');
        noResultMsg.classList.remove('flex');
      }
    });
  });
</script>

<style>
    /* Custom Scrollbar untuk area produk dan keranjang */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f3f4f6; 
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db; 
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af; 
    }
</style>
@endpush