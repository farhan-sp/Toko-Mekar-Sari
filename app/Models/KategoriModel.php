<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\BarangModel;

class KategoriModel extends Model
{
    protected $table = "kategori";
    protected $primaryKey = "id_kategori";
    public $timestamps = false;
    public $incrementing  = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_kategori',
        'nama_kategori',
    ];
    public function barang(): HasMany {
        return $this->hasMany(BarangModel::class, 'id_kategori', 'id_kategori');
    }
}
