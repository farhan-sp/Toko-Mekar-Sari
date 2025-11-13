<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\ {
    KategoriModel,
    BarangModel,
    TransaksiPenjualanModel,
    TransaksiPembelianModel,
    DetailTransaksiPembelianModel,
    SupplierModel
};

use Exception;

class PembelianController extends Controller
{
    public function index() {
        $kategori = KategoriModel::all();
        $barang = BarangModel::all();
        $supplier = SupplierModel::all();
        
        $perlu_restok = [];
        foreach($barang as $id) {
            if($id['jumlah_stok_barang'] <= $id['stok_minimal']) {
                $perlu_restok[] = $id;
            }
        }
        
        return view('halaman.pembelian', ['kategori' => $kategori, 'barang' => $barang, 'barang_restok' => $perlu_restok, 'supplier' => $supplier]); 
    }

    public function storeTransaction(Request $request)
    {
        $barang = BarangModel::find($request->id_barang);
        DB::beginTransaction();

        try {
            $total_harga = $request->jumlah * $barang->harga_jual;
            
            $urutan_transaksi = TransaksiPembelianModel::count() + 1;
            $format_urutan = sprintf("%03d", $urutan_transaksi);
            $id_transaksi = "TRB-" . $format_urutan;

            $pengguna_pembuat = Auth::user()->getAttribute('id_pengguna');
            $pembelian = TransaksiPembelianModel::create([
                'id_transaksi_pembelian' =>  $id_transaksi,
                'id_pengguna_pembuat' => $pengguna_pembuat,
                'id_supplier'  => $barang->id_supplier,
                'total_harga'   => $total_harga,
                'tanggal_transaksi_pembelian' => now(),
            ]);
            
            $urutan_detail_transaksi = DetailTransaksiPembelianModel::count() + 1;
            $format_urutan = sprintf("%03d", $urutan_detail_transaksi);
            $id_detail_transaksi = "DTRB-" . $format_urutan;
            $cek = DetailTransaksiPembelianModel::create([
                'id_detail_transaksi_pembelian' => $id_detail_transaksi,
                'id_transaksi_pembelian' => $pembelian->id_transaksi_pembelian, 
                'id_barang' => $request->id_barang,
                'jumlah_barang' => $barang['jumlah_stok_barang'],
                'harga_perbarang' => $barang['harga_jual'],
                'subtotal' => $total_harga,
            ]);

            DB::commit();

            session()->forget('cart');
            return redirect()->route('pembelian.index')->with('success', 'Transaksi berhasil disimpan!');

        } catch (Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Terjadi kesalahan saat menyimpan transaksi: ' . $e->getMessage());
        }
    }
}
