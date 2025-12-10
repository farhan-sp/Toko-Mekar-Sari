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
    public function index(Request $request) {
        $keyword = $request->input('search');
    
        $barang = BarangModel::query()
            ->when($keyword, function($q) use ($keyword) {
                return $q->where('nama_barang', 'like', "%{$keyword}%")
                        ->orWhere('kode_barang', 'like', "%{$keyword}%"); // Opsional
            })
            ->orderBy('nama_barang', 'asc')
            ->simplePaginate(12); // Menampilkan 12 item per halaman

        return view('halaman.penjualan', [
            'barang' => $barang
        ]);
    }
    
    // Penjualan
    public function daftarTransaksiPenjualan() {
        $user = Auth::user()->pengguna()->get()->first();

        $query = TransaksiPenjualanModel::with([
            'pelanggan', 
            'pengguna', 
            'detailPenjualan.barang'
        ]);

        if ($user->tipe_pekerjaan === 'Kasir') {
            $query->where('id_pengguna', $user->id_pengguna); 
        }

        $daftar_penjualan = $query->orderBy('tanggal_transaksi_penjualan', 'desc')->get();

        return view('halaman.daftar-transaksi-penjualan', ['daftar_penjualan' => $daftar_penjualan]);
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

        if(!$cart || count($cart) == 0) {
            return back()->with('error', 'Keranjang belanja kosong, tidak ada transaksi yang disimpan.');
        }

        DB::beginTransaction();

        try {
            $pelanggan = PelangganModel::firstOrCreate([
                'kontak_pelanggan' => $request->telepon,
            ],
            [
                'nama_pelanggan' => $request->nama,
                'alamat' => $request->alamat
            ]);

            $total_harga = 0;
            foreach ($cart as $id => $details) {
                $total_harga += $details['harga'] * $details['jumlah'];
            }
            
            $id_temp = "TEMP-" . uniqid();
            $user = Auth::user();
            $transaksi_penjualan = TransaksiPenjualanModel::create([
                'kode_transaksi_penjualan' => $id_temp,
                'id_pengguna_pembuat' => $user->pengguna()->get()->first()->id_pengguna,
                'id_pelanggan'  => $pelanggan->id_pelanggan,
                'total_harga'   => $total_harga,
                'tanggal_transaksi_penjualan' => now(),
            ]);
            
            $kode = $transaksi_penjualan->id_transaksi_penjualan;
            $kode_transaksi_penjualan = 'PEN-' . sprintf('%10d', $kode);

            $transaksi_penjualan->update([
                'kode_transaksi_penjualan' => $kode_transaksi_penjualan
            ]);
            
            foreach ($cart as $id_barang => $details) {
                $id_temp = "TEMP-" . uniqid();
                $detail_transaksi_penjualan = DetailTransaksiPenjualanModel::create([
                    'kode_detail_transaksi_penjualan' => $id_temp,
                    'id_transaksi_penjualan' => $transaksi_penjualan->id_transaksi_penjualan, 
                    'id_barang' => $id_barang,
                    'jumlah_barang' => $details['jumlah'],
                    'harga_perbarang' => $details['harga'],
                    'subtotal' => $details['harga'] * $details['jumlah'],
                ]);

                $kode = $detail_transaksi_penjualan->id_detail_transaksi_penjualan;
                $kode_detail_transaksi_penjualan = 'PEN-' . sprintf('%10d', $kode);

                $detail_transaksi_penjualan->update([
                    'kode_detail_transaksi_penjualan' => $kode_detail_transaksi_penjualan
                ]);
            }

            DB::commit();

            session()->forget('cart');
            return redirect()->route('penjualan.index')
                ->with('success', 'Transaksi berhasil disimpan!')
                ->with('last_transaction_id', $transaksi_penjualan->id_transaksi_penjualan);

        } catch (Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Terjadi kesalahan saat menyimpan transaksi: ' . $e->getMessage());
        }
    }
    public function hapusItem($id) {
        $cart = session()->get('cart');

        if(isset($cart[$id])) {
            unset($cart[$id]); // Hapus item dari array
            session()->put('cart', $cart); // Simpan kembali ke session
        }

        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }
    public function hapusTransaksi(TransaksiPenjualanModel $penjualan) {
        try {
            $penjualan->delete();

            return redirect()->route('daftar.penjualan')->with('success', 'Transaksi berhasil dihapus!');
        }catch(Exception $e) {
            return back()->with('error', 'Error : ' . $e->getMessage());
        }
    }

    public function cetakStruk($id) {
        // Ambil data transaksi beserta relasinya
        $transaksi = TransaksiPenjualanModel::with(['pelanggan', 'pengguna', 'detailPenjualan.barang'])
            ->findOrFail($id);

        return view('halaman.cetak-struk', ['transaksi' => $transaksi]);
    }

    public function getDetail($id) {
        $transaksi = TransaksiPenjualanModel::with(['pelanggan', 'detailPenjualan.barang'])
            ->find($id);
            
        return response()->json($transaksi);
    }
}
