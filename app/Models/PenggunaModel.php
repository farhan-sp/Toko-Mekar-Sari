<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LoginModel;

class PenggunaModel extends Model
{
    protected $table = "pengguna";
    protected $primaryKey = "id_pengguna";
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = "string";
    protected $fillable = [
        'id_pengguna',
        'nama_pengguna',
        'tipe_pekerjaan',
        'kontak_pengguna',
        'tanggal_daftar'
    ];

    public function data_login() {
        return $this->hasOne(LoginModel::class, 'id_pengguna', 'id_pengguna');
    }
    protected static function booted(): void {
        static::deleting(function(PenggunaModel $pengguna) {
            $pengguna->data_login()?->delete();
        });
    }
}
