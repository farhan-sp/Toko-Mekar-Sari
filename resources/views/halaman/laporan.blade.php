@extends('layouts.app')

@section('judul-halaman', 'Laporan Statistik')
@section('isi-content')
<main class="flex-1 p-6 overflow-auto space-y-8">
    
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Ringkasan Performa</h2>
            <p class="text-sm text-gray-500">Analisis kinerja toko berdasarkan periode yang dipilih</p>
        </div>
        
        <div class="bg-white p-1 rounded-lg border border-gray-200 flex shadow-sm">
            @php
                $style_aktif = 'bg-gray-900 text-white shadow';
                $style_default = 'text-gray-600 hover:bg-gray-50';
            @endphp
            <a href="{{ route('laporan') }}?periode=bulanan" 
               class="px-4 py-1.5 rounded-md text-sm font-medium transition-all {{ $periode == 'bulanan' ? $style_aktif : $style_default }}">
                Bulanan
            </a>
            <a href="{{ route('laporan') }}?periode=tahunan" 
               class="px-4 py-1.5 rounded-md text-sm font-medium transition-all {{ $periode == 'tahunan' ? $style_aktif : $style_default }}">
                Tahunan
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-green-50 rounded-lg text-green-600">
                    <i class="fa-solid fa-money-bill-wave text-xl"></i>
                </div>
                {{-- Indikator Naik/Turun --}}
                <span class="text-xs font-bold px-2 py-1 rounded-full {{ $persen_pendapatan >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $persen_pendapatan >= 0 ? '+' : '' }}{{ $persen_pendapatan }}%
                </span>
            </div>
            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Pendapatan</p>
            <h3 class="font-bold text-2xl text-gray-800 mt-1">{{ $total_pendapatan }}</h3>
            <p class="text-gray-400 text-xs mt-2">vs periode sebelumnya</p>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-red-50 rounded-lg text-red-600">
                    <i class="fa-solid fa-cart-shopping text-xl"></i>
                </div>
                {{-- Pengeluaran naik = Buruk (Merah), Turun = Bagus (Hijau) --}}
                <span class="text-xs font-bold px-2 py-1 rounded-full {{ $persen_pengeluaran <= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $persen_pengeluaran > 0 ? '+' : '' }}{{ $persen_pengeluaran }}%
                </span>
            </div>
            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Pengeluaran</p>
            <h3 class="font-bold text-2xl text-gray-800 mt-1">{{ $total_pengeluaran }}</h3>
            <p class="text-gray-400 text-xs mt-2">vs periode sebelumnya</p>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                    <i class="fa-solid fa-chart-line text-xl"></i>
                </div>
                <span class="text-xs font-bold px-2 py-1 rounded-full {{ $persen_profit >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $persen_profit >= 0 ? '+' : '' }}{{ $persen_profit }}%
                </span>
            </div>
            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Net Profit</p>
            <h3 class="font-bold text-2xl text-gray-800 mt-1">{{ $total_keuntungan }}</h3>
            <p class="text-gray-400 text-xs mt-2">Margin Profit: <span class="text-gray-600 font-semibold">{{ $persen_profit }}%</span></p>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                    <i class="fa-solid fa-receipt text-xl"></i>
                </div>
                <span class="text-xs font-bold px-2 py-1 rounded-full {{ $persen_transaksi >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $persen_transaksi >= 0 ? '+' : '' }}{{ $persen_transaksi }}%
                </span>
            </div>
            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Transaksi</p>
            <h3 class="font-bold text-2xl text-gray-800 mt-1">{{ $total_transaksi }}</h3>
            <p class="text-gray-400 text-xs mt-2">vs periode sebelumnya</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold text-gray-800">Analisis Tren Keuangan</h2>
            <div class="flex gap-4 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <span class="text-gray-600">Pemasukan</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                    <span class="text-gray-600">Pengeluaran</span>
                </div>
            </div>
        </div>
        <div class="h-80 w-full relative">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="bg-white rounded-xl border border-gray-100 shadow-lg flex flex-col h-[32rem]">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                <h4 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-arrow-up text-green-500"></i> Penjualan Terbaru
                </h4>
                <a href="{{ route('daftar.penjualan') }}" class="text-xs font-semibold text-green-600 hover:text-green-800 bg-green-50 px-3 py-1 rounded-full transition">
                    Lihat Semua
                </a>
            </div>
            
            <div class="flex-grow overflow-auto custom-scrollbar">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 sticky top-0 z-10">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Pelanggan</th>
                            <th class="px-5 py-3 font-semibold text-right">Total</th>
                            <th class="px-5 py-3 font-semibold text-right">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($transaksi_penjualan->take(10) as $penjualan)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3 font-medium text-gray-900">
                                    {{ $penjualan->pelanggan->nama_pelanggan ?? 'Umum / No Name' }}
                                    <div class="text-xs text-gray-400 font-normal">ID: {{ $penjualan['id_transaksi_penjualan'] }}</div>
                                </td>
                                <td class="px-5 py-3 text-right font-bold text-green-600 font-mono">
                                    Rp {{ number_format($penjualan['total_harga'], 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3 text-right text-gray-500 text-xs">
                                    {{ \Carbon\Carbon::parse($penjualan['tanggal_transaksi_penjualan'])->format('d M Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center py-10 text-gray-400">Belum ada data penjualan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-lg flex flex-col h-[32rem]">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                <h4 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-arrow-down text-blue-500"></i> Pembelian Terbaru
                </h4>
                <a href="{{ route('daftar.pembelian') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-1 rounded-full transition">
                    Lihat Semua
                </a>
            </div>

            <div class="flex-grow overflow-auto custom-scrollbar">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 sticky top-0 z-10">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Supplier</th>
                            <th class="px-5 py-3 font-semibold text-right">Total</th>
                            <th class="px-5 py-3 font-semibold text-right">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($transaksi_pembelian->take(10) as $pembelian)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3 font-medium text-gray-900">
                                    {{ $pembelian->supplier->nama_supplier ?? 'Supplier ID: ' . $pembelian['id_supplier'] }}
                                    <div class="text-xs text-gray-400 font-normal">ID: {{ $pembelian['id_transaksi_pembelian'] }}</div>
                                </td>
                                <td class="px-5 py-3 text-right font-bold text-red-500 font-mono">
                                    Rp {{ number_format($pembelian['total_harga'], 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3 text-right text-gray-500 text-xs">
                                    {{ \Carbon\Carbon::parse($pembelian['tanggal_transaksi_pembelian'])->format('d M Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center py-10 text-gray-400">Belum ada data pembelian</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>
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
                        label: 'Pemasukan',
                        data: dataPenjualan,
                        // Warna Hijau Solid (Lebih Gelap & Tegas)
                        borderColor: '#059669', // Emerald 600 (Garis lebih gelap)
                        backgroundColor: 'rgba(16, 185, 129, 0.6)', // Emerald 500 (Isi 60% solid)
                        tension: 0.3, // Sedikit melengkung tapi kaku
                        fill: true,
                    },
                    {
                        label: 'Pengeluaran',
                        data: dataPembelian,
                        // Warna Biru Solid
                        borderColor: '#2563eb', // Blue 600
                        backgroundColor: 'rgba(59, 130, 246, 0.6)', // Blue 500 (Isi 60% solid)
                        tension: 0.3,
                        fill: true, // Sekarang diisi warna juga
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#e5e7eb',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                weight: 'bold'
                            },
                            // --- BAGIAN INI YANG DIPERBAIKI ---
                            callback: function(value) {
                                if (value >= 1000000) {
                                    // Jika jutaan (misal 1.500.000 -> 1.5 Jt)
                                    return 'Rp ' + (value / 1000000).toFixed(1).replace(/\.0$/, '') + ' Jt';
                                } else if (value >= 1000) {
                                    // Jika ribuan (misal 45.000 -> 45 Rb)
                                    return 'Rp ' + (value / 1000).toFixed(0) + ' Rb';
                                }
                                // Jika kecil (misal 500 perak)
                                return 'Rp ' + value;
                            }
                            // ----------------------------------
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false 
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    // Format Rupiah lengkap di tooltip agar detail (Rp 45.000)
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>

<style>
    /* Custom Scrollbar untuk Tabel */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f9fafb; 
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1; 
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8; 
    }
</style>
@endpush