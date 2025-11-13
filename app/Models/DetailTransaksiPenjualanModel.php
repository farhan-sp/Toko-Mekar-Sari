<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksiPenjualanModel extends Model
{
    protected $table = "detail_transaksi_penjualan";
    protected $primaryKey = "id_detail_transaksi_penjualan";
    public $timestamps = false;
    protected $fillable = [
        'id_detail_transaksi_penjualan',
        'id_transaksi_penjualan',
        'id_barang',
        'jumlah_barang',
        'harga_perbarang',
        'subtotal'
    ];
}
