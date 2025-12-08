@extends('layouts.app')

@section('judul-halaman', 'Pembelian Barang')
@section('isi-content')
<main class="flex-1 p-4 lg:p-6 overflow-hidden flex flex-col h-full relative" x-data="{ showAddForm: false }">

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="flex items-center p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50" role="alert">
            <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col h-full overflow-hidden">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4 flex-shrink-0">
            <div>
                <h1 class="text-xl lg:text-2xl font-bold text-gray-800">Pembelian Stok</h1>
                <p class="text-xs lg:text-sm text-gray-500">Restock barang dari supplier.</p>
            </div>
            
            <div class="flex gap-2 w-full md:w-auto">
                {{-- Search Bar --}}
                <form action="{{ route('pembelian.index') }}" method="GET" class="relative flex-1 md:w-64">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input 
                        type="text" 
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari produk..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all"
                    >
                </form>

                <button 
                    @click="showAddForm = !showAddForm"
                    class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition shadow-sm flex items-center gap-2 whitespace-nowrap"
                >
                    <i class="fa-solid" :class="showAddForm ? 'fa-minus' : 'fa-plus'"></i>
                    <span class="hidden sm:inline" x-text="showAddForm ? 'Tutup' : 'Produk Baru'"></span>
                    <span class="sm:hidden">Baru</span>
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
            class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 mb-6 overflow-y-auto max-h-[80vh] custom-scrollbar"
            style="display: none;"
        >
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
                <h4 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-box-open text-blue-600"></i> Input Data Produk Baru
                </h4>
                <button @click="showAddForm = false" class="text-gray-400 hover:text-gray-600 transition"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            
            <form action="{{ route('pembelian.tambah-barang') }}" method="POST" enctype="multipart/form-data" class="space-y-6"> 
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    {{-- Bagian Kiri: Upload Foto --}}
                    <div class="lg:col-span-4" x-data="{ imagePreview: null }">
                        <label class="block text-sm font-bold text-gray-800 mb-3">Foto Barang</label>
                        <div class="relative w-full h-64 border-2 border-dashed border-gray-300 rounded-2xl bg-gray-50 hover:bg-blue-50 hover:border-blue-400 transition-all duration-300 group flex flex-col items-center justify-center text-center cursor-pointer overflow-hidden">
                            <input type="file" name="gambar" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" @change="const file = $event.target.files[0]; if(file) { const reader = new FileReader(); reader.onload = (e) => { imagePreview = e.target.result }; reader.readAsDataURL(file); }">
                            <div x-show="!imagePreview" class="group-hover:scale-110 transition-transform duration-300 pointer-events-none">
                                <div class="w-16 h-16 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto mb-3"><i class="fa-regular fa-image text-3xl text-blue-500"></i></div>
                                <p class="text-sm font-semibold text-gray-700">Klik untuk upload foto</p><p class="text-xs text-gray-400 mt-1">Format: JPG, PNG (Max 2MB)</p>
                            </div>
                            <div x-show="imagePreview" class="absolute inset-0 z-10 w-full h-full bg-white" style="display: none;">
                                <img :src="imagePreview" class="w-full h-full object-contain p-2">
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300"><p class="text-white text-sm font-medium"><i class="fa-solid fa-pen mr-2"></i>Ganti Foto</p></div>
                            </div>
                        </div>
                    </div>

                    {{-- Bagian Kanan: Form Input --}}
                    <div class="lg:col-span-8 space-y-6">
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">Informasi Produk</h5>
                            <div class="space-y-4">
                                <div><label class="block text-sm font-semibold text-gray-700 mb-1">Nama Barang</label><div class="relative rounded-md shadow-sm"><input type="text" name="nama_barang" class="px-4 border border-gray-300 w-full rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2.5" placeholder="Contoh: Semen Gresik 40kg" required></div></div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div><label class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label><div class="relative"><select name="kategori" class="px-2 w-full border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2.5 appearance-none" required>@foreach ($kategori as $kat)<option value="{{ $kat['id_kategori'] }}">{{ $kat['nama_kategori'] }}</option>@endforeach</select><div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500"><i class="fa-solid fa-chevron-down text-xs"></i></div></div></div>
                                    <div><label class="block text-sm font-semibold text-gray-700 mb-1">Supplier</label><div class="relative"><select name="supplier" class="px-2 w-full border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2.5 appearance-none" required>@foreach ($supplier as $sup)<option value="{{ $sup['id_supplier'] }}">{{ $sup['nama_supplier'] }}</option>@endforeach</select><div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500"><i class="fa-solid fa-chevron-down text-xs"></i></div></div></div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">Harga</h5>
                                <div class="space-y-4">
                                    <div><label class="block text-sm font-semibold text-gray-700 mb-1">Harga Beli</label><div class="relative rounded-md shadow-sm"><div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"><span class="text-gray-500 sm:text-sm font-bold">Rp</span></div><input type="number" name="harga_beli" class="block w-full rounded-lg border-gray-300 pl-10 focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5" placeholder="0" required></div></div>
                                    <div><label class="block text-sm font-semibold text-gray-700 mb-1">Harga Jual</label><div class="relative rounded-md shadow-sm"><div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"><span class="text-gray-500 sm:text-sm font-bold">Rp</span></div><input type="number" name="harga_jual" class="block w-full rounded-lg border-gray-300 pl-10 focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5" placeholder="0" required></div></div>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">Stok</h5>
                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div><label class="block text-sm font-semibold text-gray-700 mb-1">Awal</label><div class="relative rounded-md shadow-sm"><input type="number" name="stok" class="px-2 border border-gray-300 w-full rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2.5" placeholder="0" required></div></div>
                                        <div><label class="block text-sm font-semibold text-gray-700 mb-1">Min.</label><div class="relative rounded-md shadow-sm"><input type="number" name="min_stok" class="px-2 border border-gray-300 w-full rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2.5" placeholder="10" required></div></div>
                                    </div>
                                    <div><label class="block text-sm font-semibold text-gray-700 mb-1">Satuan</label><div class="relative rounded-md shadow-sm"><input type="text" name="satuan" class="px-2 border border-gray-300 w-full rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2.5" placeholder="Pcs" required></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" @click="showAddForm = false" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-sm">Batal</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition shadow-md flex items-center gap-2"><i class="fa-solid fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>

        <div class="flex-1 overflow-y-auto p-1 custom-scrollbar">
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-4 gap-3 lg:gap-4 pb-6">
                @forelse($barang as $item)
                <div class="bg-white rounded-lg lg:rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden group">
                    
                    {{-- Gambar (Square Aspect Ratio) --}}
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
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded shadow-sm {{ $item['jumlah_stok_barang'] > $item['stok_minimal'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $item['jumlah_stok_barang'] }}
                            </span>
                        </div>
                    </div>

                    {{-- Info Produk --}}
                    <div class="p-3 flex-1 flex flex-col">
                        <p class="text-[10px] text-gray-400 uppercase font-semibold mb-0.5 truncate">{{ $item->kategori->nama_kategori ?? '-' }}</p>
                        <h5 class="font-bold text-gray-800 text-xs sm:text-sm leading-tight mb-2 line-clamp-2 min-h-[2.5em]" title="{{ $item['nama_barang'] }}">
                            {{ $item['nama_barang'] }}
                        </h5>
                        
                        <div class="mt-auto flex items-center justify-between border-t border-gray-100 pt-2">
                            <div class="flex flex-col">
                                <span class="text-[10px] text-gray-400">Harga Beli</span>
                                <span class="font-bold text-gray-800 text-xs sm:text-sm">Rp {{ number_format($item['harga_beli'], 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Form Restock --}}
                        <form action="{{ route('pembelian.store') }}" method="POST" class="mt-2 flex gap-1">
                            @csrf
                            <input type="hidden" name="id_barang" value="{{ $item['id_barang'] }}">
                            <input type="number" name="jumlah" value="1" min="1" class="w-12 px-1 py-1.5 text-center text-xs border border-gray-300 rounded focus:ring-blue-500" required>
                            <button type="submit" class="flex-1 bg-blue-600 text-white text-xs font-medium rounded py-1.5 hover:bg-blue-700 transition-colors flex items-center justify-center gap-1 shadow-sm">
                                <i class="fa-solid fa-plus"></i> <span class="hidden sm:inline">Restock</span>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="col-span-full flex flex-col items-center justify-center py-20 text-gray-400">
                    <i class="fa-solid fa-magnifying-glass text-4xl mb-3"></i>
                    <p>Produk tidak ditemukan</p>
                </div>
                @endforelse
            </div>
        </div>
        
        <div class="mt-4 mb-8">
            {{ $barang->appends(['search' => request('search')])->links() }}
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