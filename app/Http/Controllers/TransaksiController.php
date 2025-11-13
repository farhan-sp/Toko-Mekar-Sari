<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ { 
    DetailTransaksiPembelianModel, 
    DetailTransaksiPenjualanModel, 
    TransaksiPembelianModel,
    TransaksiPenjualanModel
};

class TransaksiController
{
    public function penjualan() {
        return view('halaman.penjualan');
    }
    public function dashboard() {
        return view('halaman.dashboard');
    }
    public function pembelian() {
        return view('halaman.pembelian');
    }
    public function laporan(Request $request) {
        // Pembuatan Query
        $transaksi_pembelian = TransaksiPembelianModel::query();
        $transaksi_penjualan = TransaksiPenjualanModel::query();

        // Data Grafik
        $periode = $request->input('periode', 'bulanan');

        $chart_labels = [];
        $chart_data_penjualan = [];
        $chart_data_pembelian = [];

        if ($periode == 'tahunan') {
            // --- Filter TAHUNAN ---
            $transaksi_penjualan->whereYear('tanggal_transaksi_penjualan', date('Y'));
            $transaksi_pembelian->whereYear('tanggal_transaksi_pembelian', date('Y'));
            
            // Kueri Chart (sama seperti kode asli Anda: Group by BULAN)
            $data_penjualan = TransaksiPenjualanModel::select(
                DB::raw('MONTH(tanggal_transaksi_penjualan) as penjualan'), 
                DB::raw('SUM(total_harga) as total')
            )
            ->whereYear('tanggal_transaksi_penjualan', date('Y'))
            ->groupBy('penjualan')
            ->pluck('total', 'penjualan')
            ->all();
            
            $data_pembelian = TransaksiPembelianModel::select(
                DB::raw('MONTH(tanggal_transaksi_pembelian) as pembelian'), 
                DB::raw('SUM(total_harga) as total')
            )
            ->whereYear('tanggal_transaksi_pembelian', date('Y'))
            ->groupBy('pembelian')
            ->pluck('total', 'pembelian')
            ->all();

            // Format Chart
            for ($i = 1; $i <= 12; $i++) {
                $chart_labels[] = date('M', mktime(0, 0, 0, $i, 1)); // Jan, Feb, Mar
                $chart_data_penjualan[] = (float)($data_penjualan[$i] ?? 0);
                $chart_data_pembelian[] = (float)($data_pembelian[$i] ?? 0);
            }

        } elseif ($periode == 'bulanan') {
            $transaksi_penjualan
                ->whereYear('tanggal_transaksi_penjualan', date('Y'))
                ->whereMonth('tanggal_transaksi_penjualan', date('m'));
            $transaksi_pembelian
                ->whereYear('tanggal_transaksi_pembelian', date('Y'))
                ->whereMonth('tanggal_transaksi_pembelian', date('m'));
            
            // Kueri Chart (Group by HARI dalam bulan ini)
            $data_penjualan_grup = TransaksiPenjualanModel::select(
                DB::raw('DAY(tanggal_transaksi_penjualan) as penjualan'), 
                DB::raw('SUM(total_harga) as total')
            )
            ->whereYear('tanggal_transaksi_penjualan', date('Y'))
            ->whereMonth('tanggal_transaksi_penjualan', date('m'))
            ->groupBy('penjualan')
            ->pluck('total', 'penjualan')
            ->all();
            
            $data_pembelian_grup = TransaksiPembelianModel::select(
                DB::raw('DAY(tanggal_transaksi_pembelian) as pembelian'), 
                DB::raw('SUM(total_harga) as total')
            )
            ->whereYear('tanggal_transaksi_pembelian', date('Y'))
            ->whereMonth('tanggal_transaksi_pembelian', date('m'))
            ->groupBy('pembelian')
            ->pluck('total', 'pembelian')
            ->all();

            // Format Chart
            $daysInMonth = now()->daysInMonth; // Jumlah hari di bulan ini
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $chart_labels[] = $i; // 1, 2, 3...
                $chart_data_penjualan[] = (float)($data_penjualan_grup[$i] ?? 0);
                $chart_data_pembelian[] = (float)($data_pembelian_grup[$i] ?? 0);
            }
        }
        
        $total_pendapatan_raw = (clone $transaksi_penjualan)->sum('total_harga');
        $total_pengeluaran_raw = (clone $transaksi_pembelian)->sum('total_harga');
        $total_transaksi = (clone $transaksi_penjualan)->count() + (clone $transaksi_pembelian)->count();
        
        // Ambil data untuk tabel (10 terbaru dalam periode yg difilter)
        // Kode asli Anda '::all()' akan mengambil SEMUA data, ini lebih efisien:
        $penjualan = (clone $transaksi_penjualan)->get();
        $pembelian = (clone $transaksi_pembelian)->get();

        // Mengubah Format
        $total_pendapatan = "Rp " . number_format($total_pendapatan_raw, 0, ',', '.');
        $total_pengeluaran = "Rp " . number_format($total_pengeluaran_raw, 0, ',', '.');
        $total_keuntungan = "Rp " . number_format($total_pendapatan_raw - $total_pengeluaran_raw, 0, ',', '.');

        return view('halaman.laporan', [
            'transaksi_pembelian' => $pembelian, 
            'transaksi_penjualan' => $penjualan,

            'total_pendapatan' => $total_pendapatan,
            'total_pengeluaran' => $total_pengeluaran,
            'total_transaksi' => $total_transaksi,
            'total_keuntungan' => $total_keuntungan,
            
            'chart_label' => $chart_labels,
            'chart_penjualan' => $chart_data_penjualan,
            'chart_pembelian' => $chart_data_pembelian,

            'periode_aktif' => $periode
        ]);
    }
}
