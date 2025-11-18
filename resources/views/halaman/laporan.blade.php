@extends('layouts.app')

@section('judul-halaman', 'Laporan Statistik')
@section('isi-content')
<main class="flex-1 p-6 overflow-auto">
    @php
        $style_aktif = 'bg-gray-900 text-white';
        $style_default = 'border bg-white text-gray-700 hover:bg-gray-100';
    @endphp

    <!-- Pilihan Periode -->
    <div class="flex justify-end mb-4 gap-2">
        <a href="{{ route('laporan') }}?periode=bulanan" 
        class="px-3 py-1 rounded-md text-sm {{ $periode == 'bulanan' ? $style_aktif : $style_default }}">
            Bulanan
        </a>
        <a href="{{ route('laporan') }}?periode=tahunan" 
        class="px-3 py-1 rounded-md text-sm {{ $periode == 'tahunan' ? $style_aktif : $style_default }}">
            Tahunan
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-xl w-full mx-auto mb-8">
            <p class="text-gray-500 text-sm">Total Penjualan</p>
            <h3 class="font-bold text-2xl">
                {{ $total_pendapatan }}
            </h3>
            <p class="text-green-500 text-xs mt-2">+12% dari periode sebelumnya</p>
        </div>
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-xl w-full mx-auto mb-8">
            <p class="text-gray-500 text-sm">Total Pembelian</p>
            <h3 class="font-bold text-2xl">
                {{ $total_pengeluaran }}
            </h3>
            <p class="text-blue-500 text-xs mt-2">+8% dari periode sebelumnya</p>
        </div>
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-xl w-full mx-auto mb-8">
            <p class="text-gray-500 text-sm">Profit</p>
            <h3 class="font-bold text-2xl">
                {{ $total_keuntungan }}
            </h3>
            <p class="text-green-500 text-xs mt-2">Margin: 33%</p>
        </div>
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-xl w-full mx-auto mb-8">
            <p class="text-gray-500 text-sm">Transaksi</p>
            <h3 class="font-bold text-2xl">
                {{ $total_transaksi }}
            </h3>
            <p class="text-purple-500 text-xs mt-2">+23% dari periode sebelumnya</p>
        </div>
    </div>

    <!-- Grafik -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-xl w-full mx-auto mb-8">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Tren Penjualan & Pembelian (Juta Rupiah)</h2>
        
        <div class="h-80 w-full">
            <canvas id="salesChart"></canvas>
        </div>
        
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Grid 1: Transaksi Penjualan -->
        <div class="bg-white rounded-xl p-6 shadow-xl h-96 flex flex-col justify-between">
            <h4 class="font-semibold text-lg mb-4 text-green-700">Daftar Transaksi Penjualan Terbaru</h4>
            <div class="flex-grow overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Transaksi</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($transaksi_penjualan as $penjualan)
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ $penjualan['id_transaksi_penjualan'] }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($penjualan['tanggal_transaksi_pembelian'])->format('d-m-Y') }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-green-600 font-semibold">{{ $penjualan['total_harga'] }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ $penjualan['id_pelanggan'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <a href="{{ route('daftar.penjualan') }}" class="w-full text-sm font-medium text-green-600 hover:text-green-800">Lihat Semua Penjualan &rarr;</a>
            </div>
        </div>

        <!-- Grid 2: Transaksi Pembelian -->
        <div class="bg-white rounded-xl p-6 shadow-xl h-96 flex flex-col justify-between">
            <h4 class="font-semibold text-lg mb-4 text-blue-700">Daftar Transaksi Pembelian Terbaru</h4>
            <div class="flex-grow overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Transaksi</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemasok</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($transaksi_pembelian as $pembelian)
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ $pembelian['id_transaksi_pembelian'] }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($pembelian['tanggal_transaksi_pembelian'])->format('d-m-Y') }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-blue-600 font-semibold">{{ $pembelian['total_harga'] }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ $pembelian['id_supplier'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <a href="{{ route('daftar.pembelian') }}" class="w-full text-sm font-medium text-blue-600 hover:text-blue-800">Lihat Semua Pembelian &rarr;</a>
            </div>
        </div>
    </div>
</main>
@endsection

@push('script')
<script src=" https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js "></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const labels = @json($chart_label);
        const dataPenjualan = @json($chart_penjualan);
        const dataPembelian = @json($chart_pembelian);

        const ctx = document.getElementById('salesChart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                    label: 'Penjualan',
                    data: dataPenjualan,
                    borderColor: 'rgb(16, 185, 129)', // Warna garis (Emerald 500)
                    backgroundColor: 'rgba(0, 124, 85, 0.38)', // Warna area di bawah garis
                    tension: 0.4, // Membuat garis terlihat melengkung (smoothing)
                    fill: true, // Mengisi area di bawah garis
                    pointBackgroundColor: 'rgb(16, 185, 129)', // Warna titik data
                    pointRadius: 5, // Ukuran titik data
                    pointHoverRadius: 7, // Ukuran titik saat di-hover
                    },
                    {
                    label: 'Pembelian',
                    data: dataPembelian,
                    borderColor: 'rgb(59, 130, 246)', // Warna garis Biru (Blue 500)
                    backgroundColor: 'rgba(59, 131, 246, 0.56)', // Warna area Biru
                    tension: 0.4, 
                    fill: true, // Diubah menjadi 'false'
                    pointBackgroundColor: 'rgb(59, 130, 246)',
                    pointRadius: 5, // Ukuran titik data
                    pointHoverRadius: 7, // Ukuran titik saat di-hover
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, 
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                }
            }
        });
    });
</script>
@endpush