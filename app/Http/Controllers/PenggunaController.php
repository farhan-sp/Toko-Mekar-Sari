<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Exception;

use App\Models\ {
    PenggunaModel,
    LoginModel,
    User
};

class PenggunaController extends Controller
{
    public function index() {
        $data = PenggunaModel::get();

        return view('halaman.pengguna', ['pengguna' => $data]);
    }
    public function login() {
        return view('halaman.login');
    }
    public function autentikasi(Request $request) {
        $kredensial = $request->only('username', 'password');

        if(Auth::attempt($kredensial)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'error' => 'username atau password salah!'
        ]);
    }
    public function tambahPengguna(Request $request) {
        DB::beginTransaction();

        try {
            $urutan_pengguna = PenggunaModel::count() + 1;
            $format_urutan = sprintf("%03d", $urutan_pengguna);
            $id_pengguna = "PGN-" . $format_urutan;

            $pengguna = PenggunaModel::create([
                'id_pengguna' => $id_pengguna,
                'nama_pengguna' => $request->nama,
                'tipe_pekerjaan' => $request->pekerjaan,
                'kontak_pengguna' => $request->telepon,
                'tanggal_daftar' => now(),
            ]);

            $urutan_login = LoginModel::count() + 1;
            $format_urutan = sprintf("%03d", $urutan_login);
            $id_login = "LGN-" . $format_urutan;

            $login = LoginModel::create([
                'id_login' => $id_login,
                'id_pengguna' => $pengguna->id_pengguna,
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);

            DB::commit();
            return redirect()->route('pengguna')->with('success', 'Pengguna berhasil disimpan!');
        }catch(Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error : ' . $e->getMessage());
        }
    }
    public function hapusPengguna(PenggunaModel $pengguna) {
        try {
            $pengguna->delete();

            return redirect()->route('pengguna')->with('success', 'Pengguna berhasil dihapus!');
        }catch(Exception $e) {
            return back()->with('error', 'Error : ' . $e->getMessage());
        }
    }
    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}
