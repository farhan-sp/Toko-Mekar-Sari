<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\{
    SupplierModel,
    KategoriModel,
    DetailTransaksiPenjualanModel,
    DetailTransaksiPembelianModel,
};

class BarangModel extends Model {
    use SoftDeletes;
    protected $table = "barang";
    protected $primaryKey = "id_barang";
    public $timestamps = false;
    protected $fillable = [
        'kode_barang',
        'id_barang',
        'id_kategori',
        'nama_barang',
        'harga_jual',
        'harga_beli',
        'jumlah_stok_barang',
        'stok_minimal',
        'satuan',
        'id_supplier',
        'gambar_barang',
    ];
    public function kategori(): BelongsTo {
        return $this->belongsTo(KategoriModel::class, 'id_kategori', 'id_kategori');
    }
    public function supplier(): BelongsTo {
        return $this->belongsTo(SupplierModel::class, 'id_supplier', 'id_supplier');
    }
    public function penjualan(): HasMany {
        return $this->hasMany(DetailTransaksiPenjualanModel::class, 'id_barang', 'id_barang');
    }
    public function pembelian(): HasMany {
        return $this->hasMany(DetailTransaksiPembelianModel::class, 'id_barang', 'id_barang');
    }
}
