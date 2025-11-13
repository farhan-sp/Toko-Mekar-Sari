@extends('layouts.app')

@section('judul-halaman', 'Daftar Barang')
@section('isi-content')
<main>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Supplier</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 flex gap-3">
                                    <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                    
                                    <form action="#" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus barang ini?');">
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
</main>
@endsection