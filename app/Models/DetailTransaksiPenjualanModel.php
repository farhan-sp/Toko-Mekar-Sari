<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\BarangModel;

class DetailTransaksiPenjualanModel extends Model
{
    protected $table = "detail_transaksi_penjualan";
    protected $primaryKey = "id_detail_transaksi_penjualan";
    public $timestamps = false;
    protected $fillable = [
        'kode_detail_transaksi_penjualan',
        'id_detail_transaksi_penjualan',
        'id_transaksi_penjualan',
        'id_barang',
        'jumlah_barang',
        'harga_perbarang',
        'subtotal'
    ];

    public function barang(): BelongsTo {
        return $this->belongsTo(BarangModel::class, 'id_barang', 'id_barang');
    }
}
