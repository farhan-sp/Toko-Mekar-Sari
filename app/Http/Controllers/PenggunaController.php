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
        $id_saya = Auth::user()->id_login; 
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
            $user = Auth::user()->pengguna()->get()->first();

            $role = strtolower($user->tipe_pekerjaan ?? '');

            if ($role === 'kasir') {
                return redirect()->route('penjualan.index');
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'error' => 'username atau password salah!'
        ]);
    }
    public function tambahPengguna(Request $request) {
        DB::beginTransaction();

        try {
            $cekNamaPengguna = PenggunaModel::where('nama_pengguna', $request->nama)->exists();
            $cekUsername = LoginModel::where('username', $request->username)->exists();

            if ($cekNamaPengguna) {
                return redirect()->route('pengguna')->with('error', 'Gagal! Nama Pengguna sudah digunakan.');
            }

            if ($cekUsername) {
                return redirect()->route('pengguna')->with('error', 'Gagal! Username sudah digunakan.');
            }

            $login = LoginModel::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);

            $pengguna = PenggunaModel::create([
                'id_login' => $login->id_login,
                'nama_pengguna' => $request->nama,
                'tipe_pekerjaan' => $request->pekerjaan,
                'kontak_pengguna' => $request->telepon,
                'tanggal_terdaftar' => now(),
            ]);

            DB::commit();
            return redirect()->route('pengguna')->with('success', 'Pengguna berhasil disimpan!');
        }catch(Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error : ' . $e->getMessage());
        }
    }
    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
    public function statusUpdate($id_pengguna)
    {
        try {
            $pengguna = PenggunaModel::findOrFail($id_pengguna);
            $hasHistory = $pengguna->penjualan()->exists() || $pengguna->pembelian()->exists();

            if ($hasHistory) {
                $pengguna->delete(); 

                return redirect()->back()->with('success', 'Pengguna diarsipkan karena memiliki riwayat transaksi.');
            
            } else {
                if ($pengguna->gambar_Pengguna && \Storage::disk('public')->exists($pengguna->gambar_Pengguna)) {
                    \Storage::disk('public')->delete($pengguna->gambar_Pengguna);
                }

                $pengguna->forceDelete();

                return redirect()->back()->with('success', 'Pengguna berhasil dihapus permanen.');
            }            
        } catch (Exception $e) {
            return back()->with('error', 'Gagal menghapus Pengguna: ' . $e->getMessage());
        }
    }
    public function update(Request $request, PenggunaModel $pengguna, LoginModel $login)
    {
        try {
            $pengguna->nama_pengguna = $request['nama'];
            $pengguna->tipe_pekerjaan = $request['pekerjaan'];
            $pengguna->kontak_pengguna = $request['telepon'];

            if ($request->filled('password', 'username')) {
                $login->username = $request->username;
                $login->password = Hash::make($request->password);
            } 

            $pengguna->save();
            $login->save();

            return back()->with('success', 'Data pengguna berhasil diperbarui.');
        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memperbarui: ' . $e->getMessage());
        }
    }
}
