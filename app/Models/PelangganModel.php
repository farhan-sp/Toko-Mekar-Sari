<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PelangganModel extends Model
{
    protected $table = "pelanggan";
    protected $primaryKey = "id_pelanggan";
    public $timestamps = false;
    protected $fillable = [
        'id_pelanggan',
        'nama_pelanggan',
        'alamat',
        'kontak_pelanggan'
    ];
}
