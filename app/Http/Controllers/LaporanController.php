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
        $now = \Carbon\Carbon::now();

        // PERIODE SAAT INI (Tahun Ini)
        $current_year = $now->year;
        
        // PERIODE SEBELUMNYA (Tahun Lalu)
        $prev_year = $now->copy()->subYear()->year;

        // Label untuk View
        $label_periode_ini = "Tahun " . $current_year;
        $label_periode_lalu = "Tahun " . $prev_year;
        
        $q_jual_now = TransaksiPenjualanModel::query();
        $q_beli_now = TransaksiPembelianModel::query();

        $q_jual_now->whereYear('tanggal_transaksi_penjualan', $current_year);
        $q_beli_now->whereYear('tanggal_transaksi_pembelian', $current_year);

        // Eksekusi Query Utama (Untuk Data Table & Total Card)
        $transaksi_penjualan = $q_jual_now->get();
        $transaksi_pembelian = $q_beli_now->get();

        // Hitung Total Saat Ini
        $total_pendapatan_now = $transaksi_penjualan->sum('total_harga');
        $total_pengeluaran_now = $transaksi_pembelian->sum('total_harga');
        $total_profit_now = $total_pendapatan_now - $total_pengeluaran_now;
        $total_transaksi_now = $transaksi_penjualan->count() + $transaksi_pembelian->count();

        $q_jual_prev = TransaksiPenjualanModel::query();
        $q_beli_prev = TransaksiPembelianModel::query();

        $q_jual_prev->whereYear('tanggal_transaksi_penjualan', $prev_year);
        $q_beli_prev->whereYear('tanggal_transaksi_pembelian', $prev_year);

        // Kita hanya butuh sum & count, tidak perlu get() semua data (biar ringan)
        $total_pendapatan_prev = $q_jual_prev->sum('total_harga');
        $total_pengeluaran_prev = $q_beli_prev->sum('total_harga');
        $total_profit_prev = $total_pendapatan_prev - $total_pengeluaran_prev;
        $total_transaksi_prev = $q_jual_prev->count() + $q_beli_prev->count();

        $hitungPersentase = function($now, $prev) {
            if ($prev == 0) {
                return $now > 0 ? 100 : 0;
            }
            return (($now - $prev) / $prev) * 100;
        };

        $persen_pendapatan = $hitungPersentase($total_pendapatan_now, $total_pendapatan_prev);
        $persen_pengeluaran = $hitungPersentase($total_pengeluaran_now, $total_pengeluaran_prev);
        $persen_profit = $hitungPersentase($total_profit_now, $total_profit_prev);
        $persen_transaksi = $hitungPersentase($total_transaksi_now, $total_transaksi_prev);

        return view('halaman.dashboard', [
            'barang' => $barang,

            // Data Utama
            'label_periode_ini' => $label_periode_ini,
            'label_periode_lalu' => $label_periode_lalu,

            // Angka Format Rupiah (String)
            'total_pendapatan' => "Rp " . number_format($total_pendapatan_now, 0, ',', '.'),
            'total_pengeluaran' => "Rp " . number_format($total_pengeluaran_now, 0, ',', '.'),
            'total_keuntungan' => "Rp " . number_format($total_profit_now, 0, ',', '.'),
            'total_transaksi' => $total_transaksi_now,

            // Data Persentase (Float/Int)
            'persen_pendapatan' => round($persen_pendapatan, 1),
            'persen_pengeluaran' => round($persen_pengeluaran, 1),
            'persen_profit' => round($persen_profit, 1),
            'persen_transaksi' => round($persen_transaksi, 1),

            // List Data Tabel
            'transaksi_pembelian' => $transaksi_pembelian,
            'transaksi_penjualan' => $transaksi_penjualan,
        ]);
    }

    public function laporan(Request $request) {
        $periode = $request->input('periode', 'tahunan');
    
        // 1. Tentukan Rentang Waktu (Current vs Previous)
        $now = \Carbon\Carbon::now();
        
        if ($periode == 'tahunan') {
            // PERIODE SAAT INI (Tahun Ini)
            $current_year = $now->year;
            
            // PERIODE SEBELUMNYA (Tahun Lalu)
            $prev_year = $now->copy()->subYear()->year;

            // Label untuk View
            $label_periode_ini = "Tahun " . $current_year;
            $label_periode_lalu = "Tahun " . $prev_year;

        } elseif ($periode == 'bulanan') {
            // PERIODE SAAT INI (Bulan Ini)
            $current_month = $now->month;
            $current_year = $now->year;

            // PERIODE SEBELUMNYA (Bulan Lalu)
            $prev_date = $now->copy()->subMonth();
            $prev_month = $prev_date->month;
            $prev_year_of_month = $prev_date->year; // Handle jika Januari -> Desember tahun lalu

            // Label untuk View
            $label_periode_ini = $now->translatedFormat('F Y');
            $label_periode_lalu = $prev_date->translatedFormat('F Y');
        }

        $q_jual_now = TransaksiPenjualanModel::query();
        $q_beli_now = TransaksiPembelianModel::query();

        if ($periode == 'tahunan') {
            $q_jual_now->whereYear('tanggal_transaksi_penjualan', $current_year);
            $q_beli_now->whereYear('tanggal_transaksi_pembelian', $current_year);
        } else {
            $q_jual_now->whereYear('tanggal_transaksi_penjualan', $current_year)
                    ->whereMonth('tanggal_transaksi_penjualan', $current_month);
            $q_beli_now->whereYear('tanggal_transaksi_pembelian', $current_year)
                    ->whereMonth('tanggal_transaksi_pembelian', $current_month);
        }

        // Eksekusi Query Utama (Untuk Data Table & Total Card)
        $transaksi_penjualan = $q_jual_now->get();
        $transaksi_pembelian = $q_beli_now->get();

        // Hitung Total Saat Ini
        $total_pendapatan_now = $transaksi_penjualan->sum('total_harga');
        $total_pengeluaran_now = $transaksi_pembelian->sum('total_harga');
        $total_profit_now = $total_pendapatan_now - $total_pengeluaran_now;
        $total_transaksi_now = $transaksi_penjualan->count() + $transaksi_pembelian->count();

        $q_jual_prev = TransaksiPenjualanModel::query();
        $q_beli_prev = TransaksiPembelianModel::query();

        if ($periode == 'tahunan') {
            $q_jual_prev->whereYear('tanggal_transaksi_penjualan', $prev_year);
            $q_beli_prev->whereYear('tanggal_transaksi_pembelian', $prev_year);
        } else {
            $q_jual_prev->whereYear('tanggal_transaksi_penjualan', $prev_year_of_month)
                        ->whereMonth('tanggal_transaksi_penjualan', $prev_month);
            $q_beli_prev->whereYear('tanggal_transaksi_pembelian', $prev_year_of_month)
                        ->whereMonth('tanggal_transaksi_pembelian', $prev_month);
        }

        // Kita hanya butuh sum & count, tidak perlu get() semua data (biar ringan)
        $total_pendapatan_prev = $q_jual_prev->sum('total_harga');
        $total_pengeluaran_prev = $q_beli_prev->sum('total_harga');
        $total_profit_prev = $total_pendapatan_prev - $total_pengeluaran_prev;
        $total_transaksi_prev = $q_jual_prev->count() + $q_beli_prev->count();

        $hitungPersentase = function($now, $prev) {
            if ($prev == 0) {
                return $now > 0 ? 100 : 0; // Jika sebelumnya 0, sekarang ada, maka naik 100%
            }
            return (($now - $prev) / $prev) * 100;
        };

        $persen_pendapatan = $hitungPersentase($total_pendapatan_now, $total_pendapatan_prev);
        $persen_pengeluaran = $hitungPersentase($total_pengeluaran_now, $total_pengeluaran_prev);
        $persen_profit = $hitungPersentase($total_profit_now, $total_profit_prev);
        $persen_transaksi = $hitungPersentase($total_transaksi_now, $total_transaksi_prev);

        $chart_labels = [];
        $chart_data_penjualan = [];
        $chart_data_pembelian = [];

        if ($periode == 'tahunan') {
            $data_penjualan_grup = (clone $q_jual_now)
                ->select(DB::raw('MONTH(tanggal_transaksi_penjualan) as grup'), DB::raw('SUM(total_harga) as total'))
                ->groupBy('grup')->pluck('total', 'grup')->all();
            
            $data_pembelian_grup = (clone $q_beli_now)
                ->select(DB::raw('MONTH(tanggal_transaksi_pembelian) as grup'), DB::raw('SUM(total_harga) as total'))
                ->groupBy('grup')->pluck('total', 'grup')->all();

            for ($i = 1; $i <= 12; $i++) {
                $chart_labels[] = date('M', mktime(0, 0, 0, $i, 1));
                $chart_data_penjualan[] = (float)($data_penjualan_grup[$i] ?? 0);
                $chart_data_pembelian[] = (float)($data_pembelian_grup[$i] ?? 0);
            }
        } elseif ($periode == 'bulanan') {
            $data_penjualan_grup = (clone $q_jual_now)
                ->select(DB::raw('DAY(tanggal_transaksi_penjualan) as grup'), DB::raw('SUM(total_harga) as total'))
                ->groupBy('grup')->pluck('total', 'grup')->all();
            
            $data_pembelian_grup = (clone $q_beli_now)
                ->select(DB::raw('DAY(tanggal_transaksi_pembelian) as grup'), DB::raw('SUM(total_harga) as total'))
                ->groupBy('grup')->pluck('total', 'grup')->all();

            $daysInMonth = $now->daysInMonth;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $chart_labels[] = $i;
                $chart_data_penjualan[] = (float)($data_penjualan_grup[$i] ?? 0);
                $chart_data_pembelian[] = (float)($data_pembelian_grup[$i] ?? 0);
            }
        }

        return view('halaman.laporan', [
            // Data Utama
            'periode' => $periode,
            'label_periode_ini' => $label_periode_ini,
            'label_periode_lalu' => $label_periode_lalu,

            // Angka Format Rupiah (String)
            'total_pendapatan' => "Rp " . number_format($total_pendapatan_now, 0, ',', '.'),
            'total_pengeluaran' => "Rp " . number_format($total_pengeluaran_now, 0, ',', '.'),
            'total_keuntungan' => "Rp " . number_format($total_profit_now, 0, ',', '.'),
            'total_transaksi' => $total_transaksi_now,

            // Data Persentase (Float/Int)
            'persen_pendapatan' => round($persen_pendapatan, 1),
            'persen_pengeluaran' => round($persen_pengeluaran, 1),
            'persen_profit' => round($persen_profit, 1),
            'persen_transaksi' => round($persen_transaksi, 1),

            // List Data Tabel
            'transaksi_pembelian' => $transaksi_pembelian,
            'transaksi_penjualan' => $transaksi_penjualan,

            // Chart
            'chart_label' => $chart_labels,
            'chart_penjualan' => $chart_data_penjualan,
            'chart_pembelian' => $chart_data_pembelian,
        ]);
    }
}
