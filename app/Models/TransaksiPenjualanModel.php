<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiPenjualanModel extends Model
{
    use HasFactory;

    protected $table = "transaksi_penjualan";
    protected $primaryKey = "id_transaksi_penjualan";
    public $timestamps = false;
    public $incrementing  = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_transaksi_penjualan',
        'id_pengguna_pembuat',
        'id_pelanggan',
        'tanggal_transaksi_penjualan',
        'total_harga',
        'tanggal_perubahan',
        'id_pengguna_pembaru'
    ];

    public function data_pelanggan(): BelongsTo {
        return $this->belongsTo(PelangganModel::class, 'id_pelanggan');
    }
    public function data_pengguna(): BelongsTo {
        return $this->belongsTo(PenggunaModel::class, 'id_pengguna_pembuat');
    }
}
