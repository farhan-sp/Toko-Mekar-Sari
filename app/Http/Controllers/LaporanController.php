<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\ {
    BarangModel,
    TransaksiPenjualanModel,
    TransaksiPembelianModel,
    DetailTransaksiPembelianModel,
    DetailTransaksiPenjualanModel,
};

use Exception;

class LaporanController extends Controller
{
    public function dashboard() {
        $barang = BarangModel::all();

        $query_pembelian = TransaksiPembelianModel::query();
        $query_penjualan = TransaksiPenjualanModel::query();

        $total_pendapatan_raw = (clone $query_penjualan)->sum('total_harga');
        $total_pengeluaran_raw = (clone $query_pembelian)->sum('total_harga');

        $total_transaksi = (clone $query_penjualan)->count() + (clone $query_pembelian)->count();

        // Mengubah Format
        $total_pendapatan = "Rp " . number_format($total_pendapatan_raw, 0, ',', '.');
        $total_pengeluaran = "Rp " . number_format($total_pengeluaran_raw, 0, ',', '.');
        $total_keuntungan = "Rp " . number_format($total_pendapatan_raw - $total_pengeluaran_raw, 0, ',', '.');
        
        $transaksi_pembelian = $query_pembelian->get();

        return view('halaman.dashboard', [
            'barang' => $barang,
            'total_transaksi' => $total_transaksi,
            'total_pendapatan' => $total_pendapatan,
            'total_keuntungan' => $total_keuntungan,
            'total_pengeluaran' => $total_pengeluaran,
            'transaksi_pembelian' => $transaksi_pembelian
        ]);
    }
    public function laporan(Request $request) {
        $periode = $request->input('periode', 'tahunan');

        $query_pembelian = TransaksiPembelianModel::query();
        $query_penjualan = TransaksiPenjualanModel::query();

        $total_pendapatan_raw = (clone $query_penjualan)->sum('total_harga');
        $total_pengeluaran_raw = (clone $query_pembelian)->sum('total_harga');

        $total_transaksi = (clone $query_penjualan)->count() + (clone $query_pembelian)->count();

        $total_pendapatan = "Rp " . number_format($total_pendapatan_raw, 0, ',', '.');
        $total_pengeluaran = "Rp " . number_format($total_pengeluaran_raw, 0, ',', '.');
        $total_keuntungan = "Rp " . number_format($total_pendapatan_raw - $total_pengeluaran_raw, 0, ',', '.');

        $chart_labels = [];
        $chart_data_penjualan = [];
        $chart_data_pembelian = [];

        if ($periode == 'tahunan') {
            $query_penjualan->whereYear('tanggal_transaksi_penjualan', date('Y'));
            $query_pembelian->whereYear('tanggal_transaksi_pembelian', date('Y'));
            
            $data_penjualan_grup = (clone $query_penjualan)
                ->select(
                    DB::raw('MONTH(tanggal_transaksi_penjualan) as grup'),
                    DB::raw('SUM(total_harga) as total')
                )
                ->groupBy('grup')
                ->pluck('total', 'grup')
                ->all();
            
            $data_pembelian_grup = (clone $query_pembelian)
                ->select(
                    DB::raw('MONTH(tanggal_transaksi_pembelian) as grup'),
                    DB::raw('SUM(total_harga) as total')
                )
                ->groupBy('grup')
                ->pluck('total', 'grup')
                ->all();

            // Format Chart
            for ($i = 1; $i <= 12; $i++) {
                $chart_labels[] = date('M', mktime(0, 0, 0, $i, 1));
                // Gunakan nama variabel yang konsisten
                $chart_data_penjualan[] = (float)($data_penjualan_grup[$i] ?? 0);
                $chart_data_pembelian[] = (float)($data_pembelian_grup[$i] ?? 0);
            }

        } elseif ($periode == 'bulanan') {
            $query_penjualan->whereYear('tanggal_transaksi_penjualan', date('Y'))
                        ->whereMonth('tanggal_transaksi_penjualan', date('m'));
            $query_pembelian->whereYear('tanggal_transaksi_pembelian', date('Y'))
                        ->whereMonth('tanggal_transaksi_pembelian', date('m'));
            
            $data_penjualan_grup = (clone $query_penjualan)
                ->select(
                    DB::raw('DAY(tanggal_transaksi_penjualan) as grup'),
                    DB::raw('SUM(total_harga) as total')
                )
                ->groupBy('grup')
                ->pluck('total', 'grup')
                ->all();
            
            $data_pembelian_grup = (clone $query_pembelian)
                ->select(
                    DB::raw('DAY(tanggal_transaksi_pembelian) as grup'),
                    DB::raw('SUM(total_harga) as total')
                )
                ->groupBy('grup')
                ->pluck('total', 'grup')
                ->all();

            // Format Chart
            $daysInMonth = now()->daysInMonth;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $chart_labels[] = $i;
                $chart_data_penjualan[] = (float)($data_penjualan_grup[$i] ?? 0);
                $chart_data_pembelian[] = (float)($data_pembelian_grup[$i] ?? 0);
            }
        }

        $transaksi_penjualan = $query_penjualan->get();
        $transaksi_pembelian = $query_pembelian->get();

        return view('halaman.laporan', [
            'total_keuntungan' => $total_keuntungan,
            'total_transaksi' => $total_transaksi,
            'total_pendapatan' => $total_pendapatan,
            'total_pengeluaran' => $total_pengeluaran,

            'transaksi_pembelian' => $transaksi_pembelian,
            'transaksi_penjualan' => $transaksi_penjualan,

            'chart_label' => $chart_labels,
            'chart_penjualan' => $chart_data_penjualan,
            'chart_pembelian' => $chart_data_pembelian,

            'periode' => $periode
        ]);
    }

    // Penjualan
    public function daftarTransaksiPenjualan() {
        $data = TransaksiPenjualanModel::with('data_pelanggan', 'data_pengguna')->get();
        return view('halaman.daftar-transaksi-penjualan', ['daftar_penjualan' => $data]);
    }
    public function detailTransaksiPenjualan(Request $request) {
        $data = DetailTransaksiPenjualanModel::find($request->id_penjualan);
        
        return view('halaman.daftar-transaksi-penjualan');
    }

    // Pembelian
    public function daftarTransaksiPembelian() {
        $data = TransaksiPembelianModel::with('data_supplier', 'data_pengguna')->get();
        return view('halaman.daftar-transaksi-pembelian', ['daftar_pembelian' => $data]);
    }
    public function detailTransaksiPembelian(Request $request) {
        $data = DetailTransaksiPembelianModel::find($request->id_pembelian);
        
        return view('halaman.daftar-transaksi-Pembelian');
    }
}
