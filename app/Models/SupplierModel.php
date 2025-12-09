<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\BarangModel;

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
    public function barang(): HasMany {
        return $this->hasMany(BarangModel::class, 'id_supplier', 'id_supplier');
    }
}
