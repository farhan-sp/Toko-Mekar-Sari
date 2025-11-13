<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function data_supplier(): BelongsTo {
        return $this->belongsTo(SupplierModel::class, 'id_supplier');
    }
    public function data_pengguna(): BelongsTo {
        return $this->belongsTo(PenggunaModel::class, 'id_pengguna_pembuat');
    }
}
