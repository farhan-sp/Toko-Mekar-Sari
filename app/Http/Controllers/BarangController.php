<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\ {
    KategoriModel,
    BarangModel,
};

use Exception;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class BarangController
{
    public function index() {
        $data_barang = BarangModel::with('kategori')
                        ->orderBy('nama_barang', 'asc')
                        ->get();

        $pengurutan_data = $data_barang->groupBy(function($item) {
            return optional($item->kategori)->nama_kategori ?? 'Tanpa Kategori';
        });

        $data = $pengurutan_data->sortKeys();

        return view('halaman.daftar-barang', ['data' => $data]);
    }
    public function tambahBarang(Request $request) {
        DB::beginTransaction();

        try {
            $urutan_barang = BarangModel::count() + 1;
            $format_urutan = sprintf("%03d", $urutan_barang);
            $id_barang = "BRG-" . $format_urutan;

            $data = BarangModel::create([
                'id_barang' => $id_barang,
                'id_kategori' => $request->kategori,
                'nama_barang' => $request->nama_barang,
                'harga_jual' => $request->harga_jual,
                'harga_beli' => $request->harga_beli,
                'jumlah_stok_barang' => $request->stok,
                'stok_minimal' => $request->min_stok,
                'satuan' => $request->satuan,
                'id_supplier' => $request->supplier
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error : ' . $e->getMessage());
        }
    }
    public function tambahKategori(Request $request) {
        DB::beginTransaction();

        try {
            $urutan_kategori = KategoriModel::count() + 1;
            $format_urutan = sprintf("%03d", $urutan_kategori);
            $id_kategori = "BRG-" . $format_urutan;

            $data = KategoriModel::create([
                'id_kategori' => $id_kategori,
                'nama_kategori' => $request->nama_kategori,
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error : ' . $e->getMessage());
        }
    }
}
