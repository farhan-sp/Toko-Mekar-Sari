<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\PenggunaModel;

class LoginModel extends Model
{
    protected $table = "login_pengguna";
    protected $primaryKey = "id_login";
    public $timestamps = false;
    protected $fillable = [
        'id_pengguna',
        'username',
        'password'
    ];
    protected $hidden = [
        'password'
    ];

    public function pengguna(): HasOne {
        return $this->hasOne(PenggunaModel::class, 'id_pengguna', 'id_pengguna');
    }
}
