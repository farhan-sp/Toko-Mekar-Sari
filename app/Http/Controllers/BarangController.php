<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\ {
    KategoriModel,
    BarangModel,
    SupplierModel,
};

use Exception;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class BarangController
{
    public function index() {
        $data_barang = KategoriModel::with(['barang' => function($query) {
                $query->orderBy('nama_barang', 'asc');
            }])
            ->orderBy('nama_kategori', 'asc')
            ->get();

        $data = $data_barang->sortKeys();

        $kategori = KategoriModel::all();
        $supplier = SupplierModel::all();

        return view('halaman.daftar-barang', ['data' => $data, 'supplier_list' => $supplier, 'kategori_list' => $kategori]);
    }
    public function tambahBarang(Request $request) {
        DB::beginTransaction();

        try {
            $id_temp = "TMP-" . uniqid();
            $barang = BarangModel::create([
                'kode_barang' => $id_temp,
                'id_kategori' => $request->id_kategori,
                'nama_barang' => $request->nama_barang,
                'gambar_barang' => $request->gambar,
                'harga_jual' => $request->harga_jual,
                'harga_beli' => $request->harga_beli,
                'jumlah_stok_barang' => $request->jumlah_stok_barang,
                'stok_minimal' => $request->stok_minimal,
                'satuan' => $request->satuan,
                'id_supplier' => $request->id_supplier
            ]);

            if ($request->hasFile('gambar')) {
                if ($barang->gambar_barang && \Storage::disk('public_html')->exists($barang->gambar_barang)) {
                    \Storage::disk('public_html')->delete($barang->gambar_barang);
                }

                $path = $request->file('gambar')->store('produk', 'public_html');
                $barang->update([
                    'gambar_barang' => $path
                ]);
            }

            $kode = $barang->id_barang;
            $kode_barang = 'BAR-' . sprintf('%10d', $kode);

            $barang->update([
                'kode_barang' => $kode_barang
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
            $id_temp = "TEMP-" . uniqid();
            $kategori = KategoriModel::create([
                'kode_kategori' => $id_temp,
                'nama_kategori' => $request->nama_kategori,
            ]);

            $kode = $kategori->id_kategori;
            $kode_kategori = 'KAT-' . sprintf('%10d', $kode);

            $kategori->update([
                'kode_kategori' => $kode_kategori
            ]);

            DB::commit();
            return redirect()->route('barang.index')->with('success', 'Kategori berhasil disimpan!');
        } catch (Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error : ' . $e->getMessage());
        }
    }
    public function tambahSupplier(Request $request) {
        DB::beginTransaction();

        try {
            $id_temp = "TEMP-" . uniqid();
            $supplier = SupplierModel::create([
                'nama_supplier' => $request->nama,
                'kontak_supplier' => $request->kontak,
                'tanggal_terdaftar' => now(),
            ]);
            
            DB::commit();
            return redirect()->route('barang.index')->with('success', 'supplier berhasil disimpan!');
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
                'jumlah_stok_barang' => $request['jumlah_stok_barang'],
                'stok_minimal' => $request['stok_minimal'],
                'satuan' => $request['satuan'],
            ]);

            if ($request->hasFile('gambar')) {
                if ($barang->gambar_barang && \Storage::disk('public')->exists($barang->gambar_barang)) {
                    \Storage::disk('public')->delete($barang->gambar_barang);
                }

                $path = $request->file('gambar')->store('produk', 'public');
                $barang->update([
                    'gambar_barang' => $path
                ]);
            }

            return back()->with('success', 'Data barang berhasil diperbarui.');

        } catch (Exception $e) {
            return back()->with('error', 'Gagal memperbarui barang: ' . $e->getMessage());
        }
    }

    public function delete($id_barang)
    {
        try {
            $barang = BarangModel::findOrFail($id_barang);
            $hasHistory = $barang->penjualan()->exists() || $barang->pembelian()->exists();

            if ($hasHistory) {
                $barang->delete(); 

                return redirect()->back()->with('success', 'Barang diarsipkan karena memiliki riwayat transaksi.');
            
            } else {
                if ($barang->gambar_barang && \Storage::disk('public')->exists($barang->gambar_barang)) {
                    \Storage::disk('public')->delete($barang->gambar_barang);
                }

                $barang->forceDelete();

                return redirect()->back()->with('success', 'Barang berhasil dihapus permanen.');
            }            
        } catch (Exception $e) {
            return back()->with('error', 'Gagal menghapus barang: ' . $e->getMessage());
        }
    }
    public function hapusSupplier($id) {
        try {
            $supplier = SupplierModel::findOrFail($id);
            
            if ($supplier->barang()->count() > 0) {
                return back()->with('error', 'Gagal menghapus! Supplier ini masih terhubung dengan data barang.');
            }

            $supplier->delete();
            
            return back()->with('success', 'Supplier berhasil dihapus.');
            
        } catch (Exception $e) {
            return back()->with('error', $e);
        }
    }

    public function hapusKategori($id) {
        try {
            $kategori = KategoriModel::findOrFail($id);
            
            if ($kategori->barang()->count() > 0) {
                return back()->with('error', 'Gagal menghapus! kategori ini masih terhubung dengan data barang.');
            }

            $kategori->delete();
            
            return back()->with('success', 'kategori berhasil dihapus.');
            
        } catch (Exception $e) {
            return back()->with('error', $e);
        }
    }
}
