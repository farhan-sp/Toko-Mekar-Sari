<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\ {
    BarangModel,
    TransaksiPenjualanModel,
    TransaksiPembelianModel,
    DetailTransaksiPenjualanModel,
    PelangganModel
};

use Exception;

class PenjualanController extends Controller
{
    public function index() {
        $barang = BarangModel::all();
        
        return view('halaman.penjualan', ['barang' => $barang]); 
    }
    
    // Penjualan
    public function daftarTransaksiPenjualan() {
        $data = TransaksiPenjualanModel::with('pelanggan', 'pengguna')->get();
        return view('halaman.daftar-transaksi-penjualan', ['daftar_penjualan' => $data]);
    }
    public function detailTransaksiPenjualan(Request $request) {
        $data = DetailTransaksiPenjualanModel::find($request->id_penjualan);
        
        return view('halaman.daftar-transaksi-penjualan');
    }
    public function addToCart(Request $request)
    {   
        $barang = BarangModel::find($request->id_barang);

        // Jika stok habis
        if ($barang->jumlah_stok_barang <= 0) {
            return back()->with('error', 'Stok barang telah habis!');
        }

        $cart = session()->get('cart', []); // Ambil cart dari session, atau buat array kosong

        // Cek jika barang sudah ada di keranjang, tambah jumlahnya
        if(isset($cart[$barang->id_barang])) {
            // Cek apakah jumlah di keranjang melebihi stok
            if ($cart[$barang->id_barang]['jumlah'] + 1 > $barang->jumlah_stok_barang) {
                return back()->with('error', 'Jumlah di keranjang melebihi stok yang tersedia.');
            }
            $cart[$barang->id_barang]['jumlah']+=$request->jumlah;
        } else {
            $cart[$barang->id_barang] = [
                "nama" => $barang->nama_barang,
                "jumlah" => $request->jumlah,
                "harga" => $barang->harga_jual
            ];
        }

        session()->put('cart', $cart); // Simpan kembali cart ke session
        return back()->with('success', 'Barang berhasil ditambahkan ke keranjang!');
    }
    public function clearCart()
    {
        session()->forget('cart');
        return back()->with('success', 'Keranjang berhasil dikosongkan.');
    }
    public function storeTransaction(Request $request)
    {
        $cart = session()->get('cart');

        // Jika keranjang kosong
        if(!$cart || count($cart) == 0) {
            return back()->with('error', 'Keranjang belanja kosong, tidak ada transaksi yang disimpan.');
        }

        // Mulai Database Transaction
        DB::beginTransaction();

        try {
            $urutan_pelanggan = PelangganModel::count() + 1;
            $format_urutan = sprintf("%03d", $urutan_pelanggan);
            $id_pelanggan = "PEL-" . $format_urutan;

            $pelanggan = PelangganModel::firstOrCreate([
                'kontak_pelanggan' => $request->telepon,
            ],
            [
                'id_pelanggan' =>  $id_pelanggan,
                'nama_pelanggan' => $request->nama,
                'alamat' => $request->alamat
            ]);

            $total_harga = 0;
            foreach ($cart as $id => $details) {
                $total_harga += $details['harga'] * $details['jumlah'];
            }
            
            $urutan_transaksi = TransaksiPenjualanModel::count() + 1;
            $format_urutan = sprintf("%03d", $urutan_transaksi);
            $id_transaksi = "TRJ-" . $format_urutan;

            $pengguna_pembuat = Auth::user()->getAttribute('id_pengguna');
            $penjualan = TransaksiPenjualanModel::create([
                'id_transaksi_penjualan' =>  $id_transaksi,
                'id_pengguna_pembuat' => $pengguna_pembuat,
                'id_pelanggan'  => $pelanggan->id_pelanggan,
                'total_harga'   => $total_harga,
                'tanggal_transaksi_penjualan' => now(),
            ]);
            
            $urutan_detail_transaksi = DetailTransaksiPenjualanModel::count() + 1;
            foreach ($cart as $id_barang => $details) {
                $format_urutan = sprintf("%03d", $urutan_detail_transaksi);
                $id_detail_transaksi = "DTRJ-" . $format_urutan;
                $cek = DetailTransaksiPenjualanModel::create([
                    'id_detail_transaksi_penjualan' => $id_detail_transaksi,
                    'id_transaksi_penjualan' => $penjualan->id_transaksi_penjualan, 
                    'id_barang' => $id_barang,
                    'jumlah_barang' => $details['jumlah'],
                    'harga_perbarang' => $details['harga'],
                    'subtotal' => $details['harga'] * $details['jumlah'],
                ]);
                $urutan_detail_transaksi += 1;
            }

            DB::commit();

            session()->forget('cart');
            return redirect()->route('penjualan.index')->with('success', 'Transaksi berhasil disimpan!');

        } catch (Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Terjadi kesalahan saat menyimpan transaksi: ' . $e->getMessage());
        }
    }
    public function hapusTransaksi(TransaksiPenjualanModel $penjualan) {
        try {
            $penjualan->delete();

            return redirect()->route('daftar.penjualan')->with('success', 'Transaksi berhasil dihapus!');
        }catch(Exception $e) {
            return back()->with('error', 'Error : ' . $e->getMessage());
        }
    }
}
