<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\DetailTransaksiPenjualanModel;

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

    public function pelanggan(): BelongsTo {
        return $this->belongsTo(PelangganModel::class, 'id_pelanggan');
    }
    public function pengguna() {
        return $this->belongsTo(PenggunaModel::class, 'id_pengguna_pembuat');
    }
    public function detailPenjualan(): HasMany {
        return $this->hasMany(DetailTransaksiPenjualanModel::class, 'id_transaksi_penjualan', 'id_transaksi_penjualan');
    }
    protected static function booted(): void {
        static::deleting(function(TransaksiPenjualanModel $penjualan) {
            $penjualan->detailPenjualan()->delete();
        });
    }
}
