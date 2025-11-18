<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\{
    SupplierModel,
    KategoriModel
};

class BarangModel extends Model
{
    protected $table = "barang";
    protected $primaryKey = "id_barang";
    public $timestamps = false;
    public $incrementing  = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_barang',
        'id_kategori',
        'nama_barang',
        'harga_jual',
        'harga_beli',
        'jumlah_stok_barang',
        'stok_minimal',
        'satuan',
        'id_supplier'
    ];
    public function kategori(): BelongsTo {
        return $this->belongsTo(KategoriModel::class, 'id_kategori', 'id_kategori');
    }
    public function supplier(): BelongsTo {
        return $this->belongsTo(SupplierModel::class, 'id_supplier', 'id_supplier');
    }
}
