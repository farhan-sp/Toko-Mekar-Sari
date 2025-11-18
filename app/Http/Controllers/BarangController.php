<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
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

        $kategori = KategoriModel::all();
        $supplier = SupplierModel::all();

        return view('halaman.daftar-barang', ['data' => $data, 'supplier_list' => $supplier, 'kategori_list' => $kategori]);
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
            return redirect()->route('pembelian.index')->with('success', 'Barang berhasil disimpan!');
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
            return redirect()->route('pembelian.index')->with('success', 'Kategori berhasil disimpan!');
        } catch (Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error : ' . $e->getMessage());
        }
    }
    public function update(Request $request, $id_barang)
    {
        try {
            $barang = BarangModel::findOrFail($id_barang);

            $barang->update([
                'id_kategori' => $request['id_kategori'],
                'id_supplier' => $request['id_supplier'],
                'nama_barang' => $request['nama_barang'],
                'harga_jual' => $request['harga_jual'],
                'harga_beli' => $request['harga_beli'],
                'jumlah_stok_barang' => $request['stok'],
                'stok_minimal' => $request['stok_minimal'],
                'satuan' => $request['satuan'],
            ]);

            return back()->with('success', 'Data barang berhasil diperbarui.');

        } catch (Exception $e) {
            return back()->with('error', 'Gagal memperbarui barang: ' . $e->getMessage());
        }
    }

    public function delete($id_barang)
    {
        try {
            $barang = BarangModel::findOrFail($id_barang);
            $barang->delete();

            return back()->with('success', 'Barang berhasil dihapus.');
            
        } catch (Exception $e) {
            return back()->with('error', 'Gagal menghapus barang: ' . $e->getMessage());
        }
    }
}
