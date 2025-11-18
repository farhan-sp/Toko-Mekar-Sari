@extends('layouts.app')

@section('judul-halaman', 'Daftar Barang')
@section('isi-content')

<main class="space-y-6" x-data="{ showEditBarangModal: false, selectedBarang: null }">

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

    @forelse($data as $kategori => $daftar_barang)
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            
            <h2 class="text-lg font-semibold text-gray-700 p-5 border-b border-gray-200">
                {{ $kategori }}
                ({{ $daftar_barang->count() }} item)
                
                <p class="text-md text-gray-400 font-normal">
                    ID: {{ $daftar_barang->first()->id_kategori }}
                </p>
            </h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Barang</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Stok</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Stok Minimal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Satuan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Harga Jual</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Harga Beli</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Supplier</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($daftar_barang as $barang)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $barang->nama_barang }}
                                    <div class="text-xs text-gray-400">ID: {{ $barang->id_barang }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $barang->jumlah_stok_barang }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $barang->stok_minimal }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $barang->satuan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium">Rp {{ number_format($barang->harga_jual, 0, ",", ".") }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium">Rp {{ number_format($barang->harga_beli, 0, ",", ".") }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{-- Asumsi Anda sudah meload relasi supplier --}}
                                    {{ $barang->supplier->nama_supplier ?? 'N/A' }}
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm flex gap-3">
                                    <button 
                                        type="button"
                                        @click='selectedBarang = @json($barang); showEditBarangModal = true'
                                        class="text-blue-600 hover:text-blue-800 font-medium"
                                    >
                                        Edit
                                    </button>
                                    
                                    <form action="{{ route('barang.hapus', $barang->id_barang) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus barang ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    
    @empty
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
            <p class="text-gray-500">Tidak ada barang untuk ditampilkan.</p>
        </div>
    @endforelse

    <div class="flex justify-between items-center">
         <a href="{{ route('pembelian.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">&larr; Kembali</a>
    </div>

    <!-- Update Barang Modal -->
    <div 
        x-show="showEditBarangModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4"
    >
        <div 
            @click.outside="showEditBarangModal = false"
            x-data="{ formData: {} }" 
            x-init="
                formData = {
                    id: selectedBarang.id_barang,
                    nama: selectedBarang.nama_barang,
                    id_kategori: selectedBarang.id_kategori,
                    id_supplier: selectedBarang.id_supplier,
                    harga_beli: selectedBarang.harga_beli,
                    harga_jual: selectedBarang.harga_jual,
                    stok: selectedBarang.jumlah_stok_barang,
                    stok_minimal: selectedBarang.stok_minimal,
                    satuan: selectedBarang.satuan
                }
            "
            {{-- 3. x-show di div dalam ini DIHAPUS untuk menghindari konflik --}}
            x-transition
            class="bg-white rounded-lg shadow-xl w-full max-w-lg" {{-- Dibuat sedikit lebih lebar (max-w-lg) --}}
        >
            <div class="flex justify-between items-center p-4 border-b">
                <h4 class="font-semibold text-gray-700">Update Barang</h4>
                <button @click="showEditBarangModal = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark fa-lg"></i>
                </button>
            </div>

            {{-- 4. Form 'action' sekarang akan berfungsi karena 'formData.id' ada --}}
            <form :action="'/barang/update/' + selectedBarang.id_barang" method="POST"> 
                @csrf
                @method('PUT')
                
                {{-- 5. 'x-show="step === 1"' dihapus, layout diubah ke grid --}}
                <div class="p-5 grid grid-cols-2 gap-4">
                    {{-- Nama Barang (dibuat lebar) --}}
                    <div class="col-span-2">
                        <label for="edit_nama_barang" class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                        {{-- 6. x-model ditambahkan --}}
                        <input type="text" id="edit_nama_barang" name="nama_barang" :value="selectedBarang.nama_barang" x-model="formData.nama" class="w-full border rounded-md p-2 text-sm" required>
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label for="edit_kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select id="edit_kategori" name="id_kategori" x-model="formData.id_kategori" class="w-full border rounded-md p-2 text-sm bg-white" required>
                            <option value="">Pilih Kategori</option>
                            @foreach ($kategori_list as $kategori)
                                <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Supplier --}}
                    <div>
                        <label for="edit_supplier" class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                        <select id="edit_supplier" name="id_supplier" x-model="formData.id_supplier" class="w-full border rounded-md p-2 text-sm bg-white" required>
                            <option value="">Pilih Supplier</option>
                            @foreach ($supplier_list as $supplier)
                                <option value="{{ $supplier->id_supplier }}">{{ $supplier->nama_supplier }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Harga Beli --}}
                    <div>
                        <label for="edit_harga_beli" class="block text-sm font-medium text-gray-700 mb-1">Harga Beli(Rp)</label>
                        <input type="number" id="edit_harga_beli" name="harga_beli" :value="selectedBarang.harga_beli" x-model="formData.harga_beli" class="w-full border rounded-md p-2 text-sm" required>
                    </div>

                    {{-- Harga Jual --}}
                    <div>
                        <label for="edit_harga_jual" class="block text-sm font-medium text-gray-700 mb-1">Harga Jual(Rp)</label>
                        <input type="number" id="edit_harga_jual" name="harga_jual" :value="selectedBarang.harga_jual" x-model="formData.harga_jual" class="w-full border rounded-md p-2 text-sm" required>
                    </div>

                    {{-- Stok --}}
                    <div>
                        <label for="edit_stok" class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                        <input type="number" id="edit_stok" name="stok" :value="selectedBarang.jumlah_stok_barang" x-model="formData.stok" class="w-full border rounded-md p-2 text-sm" required min="0">
                    </div>

                    {{-- Stok Minimum --}}
                    <div>
                        <label for="edit_min_stok" class="block text-sm font-medium text-gray-700 mb-1">Stok Minimum</label>
                        <input type="number" id="edit_min_stok" name="stok_minimal" :value="selectedBarang.stok_minimal" x-model="formData.stok_minimal" class="w-full border rounded-md p-2 text-sm" required min="0">
                    </div>

                    {{-- Satuan (dibuat lebar) --}}
                    <div class="col-span-2">
                        <label for="edit_satuan" class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                        <input type="text" id="edit_satuan" name="satuan" :value="selectedBarang.satuan" x-model="formData.satuan" class="w-full border rounded-md p-2 text-sm">
                    </div>
                </div>
                
                <div class="p-4 border-t text-right bg-gray-50 rounded-b-lg">
                    <button type="button" @click="showEditBarangModal = false" class="border px-4 py-2 rounded-md text-sm mr-2 hover:bg-gray-100">
                        Batal
                    </button>
                    <button type="submit" class="bg-black text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-800">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection