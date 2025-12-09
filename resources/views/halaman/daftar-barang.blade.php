@extends('layouts.app')

@section('judul-halaman', 'Daftar Barang')
@section('isi-content')

<main class="space-y-6 p-6" x-data="{ 
    showEditBarangModal: false, 
    showAddKategoriModal: false, 
    showAddBarangModal: false,
    showAddSupplierModal: false,
    selectedBarang: null,
    defaultKategoriId: '',
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

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Inventaris Barang</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola stok, kategori, dan supplier toko Anda.</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">   
            <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input 
                    type="text" 
                    x-model="searchQuery"
                    placeholder="Cari barang..." 
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 transition-shadow"
                >
            </div>
            
            <div class="flex gap-2">
                <button 
                    type="button" 
                    @click="showAddSupplierModal = true"
                    class="flex-1 sm:flex-none group relative inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-white border border-gray-300 text-gray-700 text-sm font-medium shadow-sm transition-all hover:bg-gray-50 hover:text-blue-600"
                >
                    <i class="fa-solid fa-truck-field"></i>
                    <span>Supplier</span>
                </button>

                <button 
                    type="button" 
                    @click="showAddKategoriModal = true"
                    class="flex-1 sm:flex-none group relative inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium shadow-sm transition-all hover:bg-blue-700"
                >
                    <i class="fa-solid fa-layer-group"></i>
                    <span>Kategori</span>
                </button>
            </div>
        </div>
    </div>
    
    @forelse($data as $itemKategori)
        @php
            $nama_kategori = $itemKategori->nama_kategori;
            $daftar_barang = $itemKategori->barang;
        @endphp

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" 
             x-show="'{{ strtolower($nama_kategori) }}'.includes(searchQuery.toLowerCase()) || $el.querySelectorAll('tbody tr:not(.hidden)').length > 0">
            
            <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gray-50">
                <div>
                    <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        {{ $nama_kategori }}
                        <span class="text-xs font-normal text-gray-500 bg-white border border-gray-200 px-2 py-0.5 rounded-full">
                            {{ $daftar_barang->count() }} item
                        </span>
                    </h2>
                    <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider">ID Kategori: {{ $itemKategori->id_kategori }}</p>
                </div>

                <button 
                    type="button"
                    @click="showAddBarangModal = true; defaultKategoriId = '{{ $itemKategori->id_kategori }}'"
                    class="text-sm bg-white border border-blue-200 text-blue-600 hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-2 shadow-sm"
                >
                    </i>
                    <span>Tambah Barang</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Supplier</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @if($daftar_barang->isEmpty())
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="fa-regular fa-folder-open text-2xl opacity-50"></i>
                                        <p class="text-sm">Belum ada barang di kategori ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach ($daftar_barang as $barang)
                                <tr class="hover:bg-gray-50 transition-colors" x-show="'{{ strtolower($barang->nama_barang) }}'.includes(searchQuery.toLowerCase())">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0">
                                                @if($barang->gambar_barang)
                                                    <img class="h-10 w-10 rounded-lg object-cover border border-gray-200" src="storage/app/public/{{ $barang->gambar_barang }} }}" alt="">
                                                @else
                                                    <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 border border-gray-200">
                                                        <i class="fa-solid fa-image"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $barang->nama_barang }}</div>
                                                <div class="text-xs text-gray-400">ID: {{ $barang->id_barang }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold {{ $barang->jumlah_stok_barang <= $barang->stok_minimal ? 'text-red-600' : 'text-gray-700' }}">
                                            {{ $barang->jumlah_stok_barang }} <span class="text-xs font-normal text-gray-500">{{ $barang->satuan }}</span>
                                        </div>
                                        <div class="text-[10px] text-gray-400">Min: {{ $barang->stok_minimal }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">Jual: <span class="font-medium">Rp {{ number_format($barang->harga_jual, 0, ",", ".") }}</span></div>
                                        <div class="text-xs text-gray-500">Beli: Rp {{ number_format($barang->harga_beli, 0, ",", ".") }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $barang->supplier->nama_supplier ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            <button 
                                                type="button"
                                                @click='selectedBarang = @json($barang); showEditBarangModal = true'
                                                class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors"
                                                title="Edit"
                                            >
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </button>
                                            
                                            <form action="{{ route('barang.hapus', $barang->id_barang) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus barang ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors" title="Hapus">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="bg-white py-16 px-6 rounded-xl shadow-sm border border-gray-200 text-center flex flex-col items-center justify-center">
            <div class="bg-gray-50 p-4 rounded-full mb-4">
                <i class="fa-solid fa-box-open text-4xl text-gray-300"></i> 
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Inventaris Kosong</h3>
            <p class="text-gray-500 mb-6 max-w-sm">
                Belum ada data barang atau kategori yang tersedia. Mulailah dengan menambahkan kategori baru.
            </p>
            <button 
                type="button" 
                @click="showAddKategoriModal = true"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition-all"
            >
                </i>
                <span>Tambah Kategori Baru</span>
            </button>
        </div>
    @endforelse

    {{-- ================= MODALS ================= --}}

    <div 
        x-show="showAddKategoriModal" 
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm" 
        style="display: none;"
        {{-- Reset ke mode list saat modal ditutup --}}
        x-transition.opacity
    >
        <div 
            x-data="{ mode: 'list' }"
            @click.outside="showAddKategoriModal = false; setTimeout(() => mode = 'list', 300)" 
            class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col max-h-[90vh]"
        >
            <div x-show="mode === 'list'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                
                {{-- Header List --}}
                <div class="flex justify-between items-center p-4 border-b bg-gray-50">
                    <h4 class="font-bold text-gray-700">Daftar Kategori</h4>
                    <button @click="showAddKategoriModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                {{-- Body List (Scrollable) --}}
                <div class="p-0 overflow-y-auto max-h-[60vh]">
                    <ul class="divide-y divide-gray-100">
                        @forelse($kategori_list as $k)
                        <li class="p-4 hover:bg-gray-50 transition flex justify-between items-center group">
                            <div class="flex-1">
                                <p class="font-bold text-gray-800 text-sm">{{ $k->nama_kategori }}</p>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex items-center gap-2">
                                
                                {{-- Form Hapus --}}
                                {{-- Pastikan route dan nama parameter ID sesuai dengan controller Anda --}}
                                <form 
                                    action="{{ route('barang.hapus-kategori', $k->id_kategori) }}" 
                                    method="POST" 
                                    onsubmit="return confirm('Yakin ingin menghapus kategori {{ $k->nama_kategori }}? Data barang yang terhubung mungkin akan terpengaruh.');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    
                                    <button 
                                        type="submit" 
                                        title="Hapus Kategori"
                                        class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all"
                                    >
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>

                            </div>
                        </li>
                        @empty
                        <li class="p-8 text-center text-gray-400 flex flex-col items-center">
                            <i class="fa-regular fa-folder-open text-3xl mb-2"></i>
                            <span class="text-sm">Belum ada data kategori</span>
                        </li>
                        @endforelse
                    </ul>
                </div>

                {{-- Footer List --}}
                <div class="p-4 border-t bg-gray-50">
                    <button 
                        @click="mode = 'add'" 
                        class="w-full py-2.5 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium shadow-sm flex items-center justify-center gap-2 transition-colors"
                    >
                        </i> Tambah Kategori Baru
                    </button>
                </div>
            </div>


            {{-- =======================
                TAMPILAN 2: FORM TAMBAH 
                ======================= --}}
            <div x-show="mode === 'add'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                <div @click.outside="showAddKategoriModal = false" class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
                    <div class="flex justify-between items-center p-4 border-b bg-gray-50">
                        <h4 class="font-bold text-gray-700">Tambah Kategori</h4>
                        <button @click="showAddKategoriModal = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <form action="{{ route('barang.tambah-kategori') }}" method="POST"> 
                        @csrf
                        <div class="p-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                            <input type="text" name="nama_kategori" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Semen, Cat" required>
                        </div>
                        <div class="p-4 border-t bg-gray-50 flex justify-end gap-2">
                            <button type="button" @click="showAddKategoriModal = false" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-200 rounded-lg">Batal</button>
                            <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div 
        x-show="showAddSupplierModal" 
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm" 
        style="display: none;"
        x-transition.opacity
    >
        <div 
            x-data="{ mode: 'list' }"
            @click.outside="showAddSupplierModal = false; setTimeout(() => mode = 'list', 300)" 
            class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col max-h-[90vh]"
        >
            
            {{-- =======================
                TAMPILAN 1: DAFTAR SUPPLIER 
                ======================= --}}
            <div x-show="mode === 'list'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                
                {{-- Header List --}}
                <div class="flex justify-between items-center p-4 border-b bg-gray-50">
                    <h4 class="font-bold text-gray-700">Daftar Supplier</h4>
                    <button @click="showAddSupplierModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                {{-- Body List (Scrollable) --}}
                <div class="p-0 overflow-y-auto max-h-[60vh]">
                    <ul class="divide-y divide-gray-100">
                        {{-- Pastikan controller mengirim variabel $suppliers --}}
                        @forelse($supplier_list as $s)
                        <li class="p-4 hover:bg-gray-50 transition flex justify-between items-center group">
                            {{-- Info Supplier --}}
                            <div class="flex-1">
                                <p class="font-bold text-gray-800 text-sm">{{ $s->nama_supplier }}</p>
                                <p class="text-xs text-gray-500"><i class="fa-solid fa-phone mr-1"></i> {{ $s->kontak_supplier }}</p>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex items-center gap-2">
                                
                                {{-- Form Hapus --}}
                                {{-- Pastikan route dan nama parameter ID sesuai dengan controller Anda --}}
                                <form 
                                    action="{{ route('barang.hapus-supplier', $s->id_supplier) }}" 
                                    method="POST" 
                                    onsubmit="return confirm('Yakin ingin menghapus supplier {{ $s->nama_supplier }}? Data barang yang terhubung mungkin akan terpengaruh.');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    
                                    <button 
                                        type="submit" 
                                        title="Hapus Supplier"
                                        class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all"
                                    >
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>

                            </div>
                        </li>
                        @empty
                        <li class="p-8 text-center text-gray-400 flex flex-col items-center">
                            <i class="fa-regular fa-folder-open text-3xl mb-2"></i>
                            <span class="text-sm">Belum ada data supplier</span>
                        </li>
                        @endforelse
                    </ul>
                </div>

                {{-- Footer List --}}
                <div class="p-4 border-t bg-gray-50">
                    <button 
                        @click="mode = 'add'" 
                        class="w-full py-2.5 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium shadow-sm flex items-center justify-center gap-2 transition-colors"
                    >
                        </i> Tambah Supplier Baru
                    </button>
                </div>
            </div>


            {{-- =======================
                TAMPILAN 2: FORM TAMBAH 
                ======================= --}}
            <div x-show="mode === 'add'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                
                {{-- Header Form --}}
                <div class="flex justify-between items-center p-4 border-b bg-gray-50">
                    <div class="flex items-center gap-2">
                        {{-- Tombol Kembali ke List --}}
                        <button @click="mode = 'list'" class="text-gray-500 hover:text-blue-600 transition-colors">
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
                        <h4 class="font-bold text-gray-700">Supplier Baru</h4>
                    </div>
                    <button @click="showAddSupplierModal = false; setTimeout(() => mode = 'list', 300)" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                {{-- Form Input --}}
                <form action="{{ route('barang.tambah-supplier') }}" method="POST"> 
                    @csrf
                    <div class="p-6 space-y-4">
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 mb-2 flex gap-3 items-start">
                            <i class="fa-solid fa-info-circle text-blue-500 mt-0.5"></i>
                            <p class="text-xs text-blue-700 leading-relaxed">
                                Supplier baru akan otomatis ditambahkan ke daftar setelah disimpan.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Supplier <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400" placeholder="Contoh: PT. Semen Gresik" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kontak / No HP <span class="text-red-500">*</span></label>
                            <input type="text" name="kontak" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400" placeholder="0812..." required>
                        </div>
                    </div>

                    {{-- Footer Form --}}
                    <div class="p-4 border-t bg-gray-50 flex justify-end gap-2">
                        <button type="button" @click="mode = 'list'" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-200 rounded-lg transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium shadow-sm transition-colors">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div x-show="showAddBarangModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm" style="display: none;">
        <div @click.outside="showAddBarangModal = false" class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b bg-gray-50">
                <h4 class="font-bold text-gray-700">Tambah Barang Baru</h4>
                <button @click="showAddBarangModal = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('pembelian.tambah-barang') }}" method="POST" enctype="multipart/form-data"> 
                @csrf
                <div class="p-6 grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Barang</label>
                        <input type="file" name="gambar" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg">
                    </div>
                    
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                        <input type="text" name="nama_barang" class="w-full border-gray-300 rounded-lg text-sm" required>
                    </div>

                    {{-- Kategori (Auto-select dari tombol yang diklik) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="id_kategori" x-model="defaultKategoriId" class="w-full border-gray-300 rounded-lg text-sm" required>
                            @foreach ($kategori_list as $kategori)
                                <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                        <select name="id_supplier" class="w-full border-gray-300 rounded-lg text-sm" required>
                            @foreach ($supplier_list as $supplier)
                                <option value="{{ $supplier->id_supplier }}">{{ $supplier->nama_supplier }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli</label><input type="number" name="harga_beli" class="w-full border-gray-300 rounded-lg text-sm" required></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual</label><input type="number" name="harga_jual" class="w-full border-gray-300 rounded-lg text-sm" required></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Stok Awal</label><input type="number" name="jumlah_stok_barang" class="w-full border-gray-300 rounded-lg text-sm" required></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Stok Min</label><input type="number" name="stok_minimal" class="w-full border-gray-300 rounded-lg text-sm" required></div>
                    <div class="col-span-2"><label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label><input type="text" name="satuan" class="w-full border-gray-300 rounded-lg text-sm" placeholder="Pcs, Kg, Box"></div>
                </div>
                <div class="p-4 border-t bg-gray-50 flex justify-end gap-2">
                    <button type="button" @click="showAddBarangModal = false" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-200 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium">Simpan Barang</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="showEditBarangModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm" style="display: none;">
        <div @click.outside="showEditBarangModal = false" x-data="{ formData: {} }" 
            x-init="formData = {
                id: selectedBarang.id_barang,
                nama: selectedBarang.nama_barang,
                id_kategori: selectedBarang.id_kategori,
                id_supplier: selectedBarang.id_supplier,
                harga_beli: selectedBarang.harga_beli,
                harga_jual: selectedBarang.harga_jual,
                stok: selectedBarang.jumlah_stok_barang,
                stok_minimal: selectedBarang.stok_minimal,
                satuan: selectedBarang.satuan
            }"
            class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden">
            
            <div class="flex justify-between items-center p-4 border-b bg-gray-50">
                <h4 class="font-bold text-gray-700">Edit Barang</h4>
                <button @click="showEditBarangModal = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form :action="'/barang/update/' + selectedBarang.id_barang" method="POST" enctype="multipart/form-data"> 
                @csrf @method('PUT')
                <div class="p-6 grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Barang (Opsional)</label>
                        <input type="file" name="gambar" accept="image/*" :value="selectedBarang.nama_gambar" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                        <input type="text" name="nama_barang" x-model="formData.nama" class="w-full border-gray-300 rounded-lg text-sm" :value="selectedBarang.nama_barang" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="id_kategori" x-model="formData.id_kategori" class="w-full border-gray-300 rounded-lg text-sm">
                            @foreach ($kategori_list as $kategori)
                                <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                        <select name="id_supplier" x-model="formData.id_supplier" class="w-full border-gray-300 rounded-lg text-sm">
                            @foreach ($supplier_list as $supplier)
                                <option value="{{ $supplier->id_supplier }}">{{ $supplier->nama_supplier }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli</label><input type="number" name="harga_beli" :value="selectedBarang.harga_beli" x-model="formData.harga_beli" class="w-full border-gray-300 rounded-lg text-sm" required></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual</label><input type="number" name="harga_jual" :value="selectedBarang.harga_jual" x-model="formData.harga_jual" class="w-full border-gray-300 rounded-lg text-sm" required></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Stok</label><input type="number" name="jumlah_stok_barang" :value="selectedBarang.jumlah_stok_barang" x-model="formData.stok" class="w-full border-gray-300 rounded-lg text-sm" required></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Stok Min</label><input type="number" name="stok_minimal" :value="selectedBarang.stok_minimal" x-model="formData.stok_minimal" class="w-full border-gray-300 rounded-lg text-sm" required></div>
                    <div class="col-span-2"><label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label><input type="text" name="satuan" :value="selectedBarang.satuan" x-model="formData.satuan" class="w-full border-gray-300 rounded-lg text-sm"></div>
                </div>
                <div class="p-4 border-t bg-gray-50 flex justify-end gap-2">
                    <button type="button" @click="showEditBarangModal = false" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-200 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-black hover:bg-gray-800 rounded-lg font-medium">Update</button>
                </div>
            </form>
        </div>
    </div>

</main>
@endsection