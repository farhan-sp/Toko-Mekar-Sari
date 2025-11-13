<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksiPembelianModel extends Model
{
    protected $table = "detail_transaksi_pembelian";
    protected $primaryKey = "id_detail_transaksi_pembelian";
    public $timestamps = false;
    protected $fillable = [
        'id_detail_transaksi_pembelian',
        'id_transaksi_pembelian',
        'id_barang',
        'jumlah_barang',
        'harga_perbarang',
        'subtotal'
    ];
}
