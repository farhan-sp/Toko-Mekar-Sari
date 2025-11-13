@extends('layouts.app')

@section('judul-halaman', 'Daftar Transaksi')
@section('isi-content')
<main>
    <div class="rounded-xl p-6 flex flex-col justify-between">
        <h4 class="font-semibold text-lg mb-4 text-blue-700">Daftar Transaksi Pembelian</h4>
        <div class="flex-grow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                <thead class="bg-white">
                    <tr>
                        <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Supplier</th>
                        <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total Transaksi</th>
                        <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Petugas</th>
                        <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Opsi</th>
                        <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($daftar_pembelian as $pembelian)
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ optional($pembelian->data_supplier)->nama_supplier }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($pembelian->tanggal_transaksi_pembelian)->format('d-m-Y') }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-blue-500 font-semibold">{{ $pembelian->total_harga }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ optional($pembelian->data_pengguna)->nama_pengguna }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                <button>Hapus</button>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                <button>Edit</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 text-left">
            <a href="{{ route('laporan') }}" class="w-full text-sm font-medium text-blue-600 hover:text-blue-800">&larr; Kembali</a>
        </div>
    </div>
</main>
@endsection