<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    HasMany,
};
use App\Models\LoginModel;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\{
    TransaksiPembelianModel,
    TransaksiPenjualanModel,
};

class PenggunaModel extends Model
{
    use SoftDeletes;
    protected $table = "pengguna";
    protected $primaryKey = "id_pengguna";
    public $timestamps = false;
    protected $fillable = [
        'id_pengguna',
        'id_login',
        'status',
        'nama_pengguna',
        'tipe_pekerjaan',
        'kontak_pengguna',
        'tanggal_terdaftar',
        'tanggal_nonaktif'
    ];

    public function login(): BelongsTo {
        return $this->belongsTo(LoginModel::class, 'id_login', 'id_login');
    }
    public function penjualan(): HasMany {
        return $this->hasMany(TransaksiPenjualanModel::class, 'id_pengguna_pembuat', 'id_pengguna');
    }
    public function pembelian(): HasMany {
        return $this->hasMany(TransaksiPembelianModel::class, 'id_pengguna_pembuat', 'id_pengguna');
    }
}
