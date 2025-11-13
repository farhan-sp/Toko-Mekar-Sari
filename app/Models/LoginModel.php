<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginModel extends Model
{
    protected $table = "login_pengguna";
    protected $primaryKey = "id_login";
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_login',
        'id_pengguna',
        'username',
        'password'
    ];
    protected $hidden = [
        'password'
    ];
}
