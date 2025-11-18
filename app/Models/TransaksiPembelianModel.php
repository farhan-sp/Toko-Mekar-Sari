<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\ {
    SupplierModel,
    PenggunaModel,
    DetailTransaksiPembelianModel
};

class TransaksiPembelianModel extends Model
{
    protected $table = "transaksi_pembelian";
    protected $primaryKey = "id_transaksi_pembelian";
    public $timestamps = false;
    public $incrementing  = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_transaksi_pembelian',
        'id_pengguna_pembuat',
        'id_supplier',
        'tanggal_transaksi_pembelian',
        'total_harga',
        'tanggal_perubahan',
        'id_pengguna_pembaru'
    ];

    public function supplier() {
        return $this->belongsTo(SupplierModel::class, 'id_supplier', 'id_supplier');
    }
    public function pengguna() {
        return $this->belongsTo(PenggunaModel::class, 'id_pengguna_pembuat', 'id_pengguna');
    }
    public function detailPembelian(): HasMany {
        return $this->hasMany(DetailTransaksiPembelianModel::class, 'id_transaksi_pembelian', 'id_transaksi_pembelian');
    }
    protected static function booted(): void {
        static::deleting(function(TransaksiPembelianModel $pembelian) {
            $pembelian->detailPembelian()->delete();
        });
    }
}
