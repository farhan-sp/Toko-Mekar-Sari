<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\ {
    KategoriModel,
    BarangModel,
    TransaksiPembelianModel,
    TransaksiPenjualanModel,
    DetailTransaksiPembelianModel,
    SupplierModel,
    PenggunaModel
};

use Exception;

class PembelianController extends Controller
{
    public function index() {
        $kategori = KategoriModel::all();
        $barang = BarangModel::all();
        $supplier = SupplierModel::all();
        
        return view('halaman.pembelian', ['kategori' => $kategori, 'barang' => $barang, 'supplier' => $supplier]); 
    }

    // Pembelian
    public function daftarTransaksiPembelian() {
        $daftar_pembelian = TransaksiPembelianModel::with([
            'supplier', 
            'pengguna', 
            'detailPembelian.barang'
        ])->orderBy('tanggal_transaksi_pembelian', 'desc')->get();

        return view('halaman.daftar-transaksi-pembelian', ['daftar_pembelian' => $daftar_pembelian]);
    }
    public function storeTransaction(Request $request)
    {
        $barang = BarangModel::find($request->id_barang);
        DB::beginTransaction();

        try {
            $total_harga = $request->jumlah * $barang->harga_jual;
            
            $id_temp = "TEMP-" . uniqid();
            
            $user = Auth::user();
            $transaksi_pembelian = TransaksiPembelianModel::create([
                'kode_transaksi_pembelian' => $id_temp,
                'id_pengguna_pembuat' => $user->pengguna()->get()->first()->id_pengguna,
                'id_supplier'  => $barang->id_supplier,
                'total_harga'   => $total_harga,
                'tanggal_transaksi_pembelian' => now(),
            ]);

            $kode = $transaksi_pembelian->id_transaksi_pembelian;
            $kode_transaksi_pembelian = 'PEM-' . sprintf('%10d', $kode);

            $transaksi_pembelian->update([
                'kode_transaksi_pembelian' => $kode_transaksi_pembelian
            ]);
            
            $id_temp = "TEMP-" . uniqid();
            $detail_transaksi_pembelian = DetailTransaksiPembelianModel::create([
                'kode_detail_transaksi_pembelian' => $id_temp,
                'id_transaksi_pembelian' => $transaksi_pembelian->id_transaksi_pembelian, 
                'id_barang' => $request->id_barang,
                'jumlah_barang' => $request->jumlah,
                'harga_perbarang' => $barang['harga_beli'],
                'subtotal' => $total_harga,
            ]);

            $kode = $detail_transaksi_pembelian->id_detail_transaksi_pembelian;
            $kode_detail_transaksi_pembelian = 'DPEM-' . sprintf('%10d', $kode);

            $detail_transaksi_pembelian->update([
                'kode_detail_transaksi_pembelian' => $kode_detail_transaksi_pembelian
            ]);

            DB::commit();

            session()->forget('cart');
            return redirect()->route('pembelian.index')->with('success', 'Transaksi berhasil disimpan!');

        } catch (Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Terjadi kesalahan saat menyimpan transaksi: ' . $e->getMessage());
        }
    }
    public function hapusTransaksi(TransaksiPembelianModel $pembelian) {
        try {
            $pembelian->delete();

            return redirect()->route('daftar.pembelian')->with('success', 'Transaksi berhasil dihapus!');
        }catch(Exception $e) {
            return back()->with('error', 'Error : ' . $e->getMessage());
        }
    }
}
