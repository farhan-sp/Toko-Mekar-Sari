<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\ {
    BarangModel,
    SupplierModel
};

class Test
{
    public function index() {
        $barang = SupplierModel::all();

        return view('halaman.test', ['user' => $barang]);
    }

    public function terima(Request $request) {
        $data = $request->supplier;
        dd($data);
    }
}
