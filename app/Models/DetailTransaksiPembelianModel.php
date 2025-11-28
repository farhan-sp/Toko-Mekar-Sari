<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\BarangModel;

class DetailTransaksiPembelianModel extends Model
{
    protected $table = "detail_transaksi_pembelian";
    protected $primaryKey = "id_detail_transaksi_pembelian";
    public $timestamps = false;
    protected $fillable = [
        'kode_detail_transaksi_pembelian',
        'id_detail_transaksi_pembelian',
        'id_transaksi_pembelian',
        'id_barang',
        'jumlah_barang',
        'harga_perbarang',
        'subtotal'
    ];

    public function barang(): BelongsTo {
        return $this->belongsTo(BarangModel::class, 'id_barang', 'id_barang');
    }
}
