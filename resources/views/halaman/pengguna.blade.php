@extends('layouts.app')

@section('judul-halaman', 'Pengaturan Pengguna')
@section('isi-content')

<main class="flex-1 p-6 overflow-auto" x-data="{ 
    showAddModal: false, 
    showEditModal: false, 
    selectedPengguna: null,
}">

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50" role="alert">
            <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="flex items-center p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50" role="alert">
            <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Pengguna</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola akses staff dan administrator toko.</p>
        </div>
        <button 
            @click="showAddModal = true"
            class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition shadow-sm flex items-center gap-2"
        >
            <i class="fa-solid fa-user-plus"></i> Tambah Pengguna
        </button>
    </div>

    <!-- GRID CARD PENGGUNA -->
    @php
        $currentId = Auth::user()->pengguna()->get()->first()->id_pengguna; // Sesuaikan dengan primary key di auth
        
        // Cari akun saya dari collection/array
        $akunSaya = $pengguna->first(function($item) use ($currentId) {
            return $item['id_pengguna'] == $currentId;
        });

        // Filter akun lain
        $akunLain = $pengguna->filter(function($item) use ($currentId) {
            return $item['id_pengguna'] != $currentId;
        });
    @endphp

    <div class="space-y-8">
        
        {{-- BAGIAN 1: PROFIL SAYA --}}
        @if($akunSaya)
        <div>
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-user-circle text-blue-600"></i> Profil Saya
            </h3>
            
            {{-- Grid Wrapper (Hanya 1 item, tapi tetap pakai grid agar lebar konsisten) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-blue-50/50 p-6 rounded-xl shadow-sm border border-blue-200 hover:shadow-md transition-shadow relative overflow-hidden">
                    {{-- Penanda Akun Aktif --}}
                    <div class="absolute top-0 right-0 bg-blue-600 text-white text-[10px] px-2 py-1 rounded-bl-lg font-bold">
                        ANDA
                    </div>

                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xl font-bold border border-blue-200">
                                {{ substr($akunSaya['nama_pengguna'], 0, 1) }}
                            </div>
                            <div>
                                <h5 class="font-bold text-gray-800">{{ $akunSaya['nama_pengguna'] }}</h5>
                                <p class="text-xs text-gray-500 font-mono">{{ $akunSaya['username'] }}</p>
                            </div>
                        </div>
                        
                        {{-- Badge Role --}}
                        @php
                            $roleColor = match($akunSaya['tipe_pekerjaan']) {
                                'Pemilik' => 'bg-purple-100 text-purple-700 border-purple-200',
                                'Kepala Toko' => 'bg-blue-100 text-blue-700 border-blue-200',
                                default => 'bg-orange-100 text-orange-700 border-orange-200',
                            };
                        @endphp
                        <span class="px-2 py-1 text-[10px] font-bold uppercase rounded-md border {{ $roleColor }} mr-8">
                            {{ $akunSaya['tipe_pekerjaan'] }}
                        </span>
                    </div>

                    <div class="space-y-2 text-sm text-gray-600 mb-6">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-phone text-gray-400 w-4"></i>
                            <span>{{ $akunSaya['kontak_pengguna'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-regular fa-calendar text-gray-400 w-4"></i>
                            <span>Terdaftar: {{ \Carbon\Carbon::parse($akunSaya['tanggal_daftar'])->format('d M Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="flex gap-2 pt-4 border-t border-blue-100">
                        <button 
                            type="button"
                            @click='selectedPengguna = @json($akunSaya); showEditModal = true'
                            class="w-full bg-blue-600 border border-blue-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors"
                        >
                            Edit Profil
                        </button>
                        {{-- Tombol Hapus Dihilangkan untuk Akun Sendiri --}}
                    </div>
                </div>
            </div>
        </div>
        @endif

        <hr class="border-gray-200">

        {{-- BAGIAN 2: PENGGUNA LAIN --}}
        <div>
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-users text-gray-500"></i> Pengguna Lainnya
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($akunLain as $user)
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 text-xl font-bold border border-gray-200">
                                {{ substr($user['nama_pengguna'], 0, 1) }}
                            </div>
                            <div>
                                <h5 class="font-bold text-gray-800">{{ $user['nama_pengguna'] }}</h5>
                                <p class="text-xs text-gray-400 font-mono">{{ $user['username'] }}</p>
                            </div>
                        </div>
                        
                        {{-- Badge Role --}}
                        @php
                            $roleColor = match($user['tipe_pekerjaan']) {
                                'Pemilik' => 'bg-purple-100 text-purple-700 border-purple-200',
                                'Kepala Toko' => 'bg-blue-100 text-blue-700 border-blue-200',
                                default => 'bg-orange-100 text-orange-700 border-orange-200',
                            };
                        @endphp
                        <span class="px-2 py-1 text-[10px] font-bold uppercase rounded-md border {{ $roleColor }}">
                            {{ $user['tipe_pekerjaan'] }}
                        </span>
                    </div>

                    <div class="space-y-2 text-sm text-gray-600 mb-6">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-phone text-gray-400 w-4"></i>
                            <span>{{ $user['kontak_pengguna'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-regular fa-calendar text-gray-400 w-4"></i>
                            <span>Terdaftar: {{ \Carbon\Carbon::parse($user['tanggal_daftar'])->format('d M Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="flex gap-2 pt-4 border-t border-gray-100">
                        <button 
                            type="button"
                            @click='selectedPengguna = @json($user); showEditModal = true'
                            class="flex-1 bg-blue-600 border border-gray-300 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-blue-500 transition-colors"
                        >
                            Edit
                        </button>

                        <form 
                            action="{{ route('pengguna.hapus', $user->id_pengguna) }}" 
                            method="POST" 
                            class="flex-1"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus akses pengguna ini?');"
                        >
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit" 
                                class="w-full bg-red-500 text-white border border-red-100 px-3 py-2 rounded-lg text-sm font-medium hover:bg-red-400 transition-colors"
                            >
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ================= MODAL TAMBAH (MULTI-STEP) ================= --}}
    <div 
        x-show="showAddModal" 
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
        style="display: none;"
    >    
        <div 
            @click.outside="showAddModal = false"
            x-data="{ step: 1 }"
            class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden"
        >
            <div class="flex justify-between items-center p-4 border-b bg-gray-50">
                <h4 class="font-bold text-gray-700">Tambah Staff Baru</h4>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark fa-lg"></i>
                </button>
            </div>

            <form action="{{ route('pengguna.tambah') }}" method="POST"> 
                @csrf
                
                {{-- Step 1: Data Diri --}}
                <div x-show="step === 1" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500" placeholder="Contoh: Budi Santoso" required>
                    </div>
                    @php
                        // Pastikan relasi data_diri sudah ada (seperti langkah sebelumnya)
                        $role_login = strtolower(Auth::user()->pengguna()->get()->first()->tipe_pekerjaan ?? '');
                    @endphp

                    <div class="mb-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Posisi / Jabatan</label>
                        
                        <select name="pekerjaan" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500" required>
                            
                            {{-- Opsi KASIR: Bisa dilihat oleh Pemilik maupun Kepala Toko --}}
                            <option value="Kasir">Kasir</option>

                            {{-- Opsi TINGKAT TINGGI: Hanya bisa dilihat oleh PEMILIK --}}
                            @if($role_login === 'pemilik')
                                <option value="Kepala Toko">Kepala Toko</option>
                                <option value="Pemilik">Pemilik</option>
                            @endif

                        </select>
                        
                        {{-- Feedback Visual (Opsional) --}}
                        @if($role_login === 'kepala toko')
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fa-solid fa-info-circle"></i> Kepala toko hanya dapat menambahkan Kasir.
                            </p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp</label>
                        <input type="tel" name="telepon" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500" placeholder="08..." required>
                    </div>
                </div>

                {{-- Step 2: Akun Login --}}
                <div x-show="step === 2" class="p-6 space-y-4">
                    <div class="bg-blue-50 p-3 rounded-lg text-xs text-blue-700 border border-blue-200 mb-2">
                        <i class="fa-solid fa-info-circle mr-1"></i> Buat kredensial untuk login sistem.
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500" placeholder="username_login" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500" placeholder="Min. 6 karakter" required>
                    </div>
                </div>
                
                {{-- Footer Tombol Step 1 --}}
                <div x-show="step === 1" class="p-4 border-t bg-gray-50 flex justify-end gap-2">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-200 rounded-lg transition">Batal</button>
                    <button type="button" @click="step = 2" class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium transition">
                        Lanjut <i class="fa-solid fa-arrow-right ml-1"></i>
                    </button>
                </div>

                {{-- Footer Tombol Step 2 --}}
                <div x-show="step === 2" class="p-4 border-t bg-gray-50 flex justify-between">
                    <button type="button" @click="step = 1" class="text-sm text-gray-500 hover:text-gray-800 underline">Kembali</button>
                    <div class="flex gap-2">
                        <button type="button" @click="showAddModal = false" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-200 rounded-lg transition">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm text-white bg-green-600 hover:bg-green-700 rounded-lg font-medium transition">
                            Simpan Pengguna
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= MODAL EDIT PENGGUNA ================= --}}
    <div 
        x-show="showEditModal" 
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
        style="display: none;"
    >    
        <div 
            @click.outside="showEditModal = false"
            class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden"
        >
            <div class="flex justify-between items-center p-4 border-b bg-gray-50">
                <h4 class="font-bold text-gray-700">Edit Data Pengguna</h4>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark fa-lg"></i>
                </button>
            </div>

            <form :action="'/pengguna/update/' + (selectedPengguna ? selectedPengguna.id_pengguna + '/' + selectedPengguna.id_login : '')" method="POST"> 
                @csrf
                @method('PUT')
                
                <div class="p-6 space-y-4" x-data>
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500" 
                            x-model="selectedPengguna && selectedPengguna.nama_pengguna" required>
                    </div>
                    
                    <!-- Jenis Pekerjaan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Posisi / Jabatan</label>
                        <select name="pekerjaan" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500" 
                            x-model="selectedPengguna && selectedPengguna.tipe_pekerjaan" required>
                            <option value="Pemilik">Pemilik</option>
                            <option value="Kepala Toko">Kepala Toko</option>
                            <option value="Kasir">Kasir</option>
                        </select>
                    </div>
                    
                    <!-- Telepon -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="tel" name="telepon" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500" 
                            x-model="selectedPengguna && selectedPengguna.kontak_pengguna" required>
                    </div>

                    {{-- Container khusus Login Info dengan State Alpine.js --}}
                    <div x-data="{ ubahLogin: false }" class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-4">
                        
                        <div class="flex items-center justify-between">
                            <div>
                                <h6 class="font-bold text-gray-700 text-sm">Informasi Login</h6>
                                <p class="text-xs text-gray-500" x-show="!ubahLogin">Username & Password aman.</p>
                            </div>
                            
                            {{-- Tombol Toggle --}}
                            <button 
                                type="button" 
                                @click="ubahLogin = !ubahLogin"
                                class="text-xs font-bold px-3 py-1.5 rounded transition-colors"
                                :class="ubahLogin ? 'bg-gray-200 text-gray-700 hover:bg-gray-300' : 'bg-blue-100 text-blue-700 hover:bg-blue-200'"
                            >
                                <span x-text="ubahLogin ? 'Batal Ubah' : 'Ubah Akun'"></span>
                            </button>
                        </div>

                        {{-- Form Input (Muncul hanya jika ubahLogin == true) --}}
                        <div x-show="ubahLogin" x-transition.opacity class="mt-4 pt-4 border-t border-gray-200 space-y-4">
                            
                            {{-- Input Username --}}
                            <div>
                                <label class="block text-gray-700 text-xs font-bold mb-1">Username Baru</label>
                                <input 
                                    type="text" 
                                    name="username" 
                                    x-model="selectedPengguna.username"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 text-sm leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required
                                >
                            </div>

                            {{-- Input Password --}}
                            <div>
                                <label class="block text-gray-700 text-xs font-bold mb-1">Password Baru</label>
                                <input 
                                    type="password" 
                                    name="password" 
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 text-sm leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Biarkan kosong jika tidak ingin mengganti"
                                >
                                <p class="text-[10px] text-gray-500 mt-1">
                                    <i class="fa-solid fa-circle-info mr-1"></i>
                                    Isi hanya jika Anda ingin mereset password user ini.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 border-t bg-gray-50 flex justify-end gap-2">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-200 rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-black hover:bg-gray-800 rounded-lg font-medium transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</main>
@endsection