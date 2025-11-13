<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierModel extends Model
{
    protected $table = "supplier";
    protected $primaryKey = "id_supplier";
    public $timestamps = false;
    protected $hidden = [
        'kontak_supplier'
    ];
    protected $fillable = [
        'id_supplier',
        'nama_supplier',
        'kontak_supplier'
    ];
    protected $keyType = "string";
}
