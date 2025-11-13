@extends('layouts.app')


@section('judul-halaman', 'Pengaturan Pengguna')
@section('isi-content')

<main class="flex-1 p-6 overflow-auto" x-data="{ showAddModal: false }">
    @if (session('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
        {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
        {{ session('error') }}
        </div>
    @endif

    <h3 class="text-lg font-bold mb-1">Pengaturan Pengguna</h3>
    <p class="text-gray-500 mb-4 text-sm">Kelola pengguna sistem dan hak akses</p>

    <div class="flex justify-end mb-4">
        <button 
            @@click="showAddModal = true"
            class="bg-black text-white px-4 py-2 rounded-md text-sm"
        >
            Tambah Pengguna
        </button>
    </div>

    <div class="grid grid-cols-3 gap-6">
        @foreach($pengguna as $user)
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-md w-full mx-auto mb-8">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h5 class="font-semibold">{{ $user['nama_pengguna'] }}</h5>
                    <span class="mt-1 inline-block px-2 py-1 text-xs text-white rounded 
                    {{ $user['tipe_pekerjaan'] == 'pemilik' ? 'bg-purple-400' : ($user['tipe_pekerjaan'] == 'kepala toko' ? 'bg-green-500' : 'bg-yellow-400') }}">{{ $user['tipe_pekerjaan'] }}</span>
                </div>
            </div>
            <p class="text-gray-500 text-sm mt-2">Telepon: {{ $user['kontak_pengguna'] }}</p>
            <p class="text-gray-500 text-sm">Terdaftar: {{ $user['terdaftar'] }}</p>
            
            <div class="mt-4 flex gap-2">
                <!-- Update Pengguna -->
                <a href="#" class="border px-2 py-1 rounded text-sm flex-1 text-center">Edit</a>
                
                <!-- Hapus Pengguna -->
                <form 
                    action="{{ route('pengguna.hapus-pengguna', $user['id_pengguna']) }}" 
                    method="POST" 
                    class="flex-1"
                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');"
                >
                    @csrf
                    @method('DELETE')
                    
                    <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded text-sm text-center w-full">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Daftar Pengguna -->
    <div 
        x-show="showAddModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4"
        style="display: none;"
    >    
        <div 
            @click.outside="showAddModal = false; step = 1; nama = ''; role = ''; telepon = ''"
            x-data="{ step: 1, nama: '', role: '', telepon: '' }"

            x-show="showAddModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90"
            class="bg-white rounded-lg shadow-xl w-full max-w-md"
        >
            <div class="flex justify-between items-center p-4 border-b">
                <h4 class="font-semibold text-gray-700">Tambah Pengguna Baru</h4>
                <button @click="showAddModal = false; step = 1; nama = ''; role = ''; telepon = ''" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark fa-lg"></i>
                </button>
            </div>

            <form action="{{ route('pengguna.tambah-pengguna') }}" method="POST"> 
                @csrf
                
                <div x-show="step === 1" class="p-5 space-y-4">
                    <!-- Nama -->
                    <div>
                        <label for="nama_pengguna" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" id="nama_pengguna" name="nama" x-model="nama" class="w-full border rounded-md p-2 text-sm" placeholder="Masukkan nama lengkap" required>
                    </div>
                    
                    <!-- Jenis Pekerjaan -->
                    <div>
                        <label for="jenis_pekerjaan" class="block text-sm font-medium text-gray-700 mb-1">Jenis Pekerjaan</label>
                        <select id="jenis_pekerjaan" name="pekerjaan" x-model="role" class="w-full border rounded-md p-2 text-sm bg-white" required>
                            <option value="" disabled selected>Pilih jenis pekerjaan</option>
                            @foreach ($pengguna as $tipe)
                                @if ($tipe->tipe_pekerjaan != 'pemilik')
                                    <option value="{{ $tipe->tipe_pekerjaan }}">{{ $tipe->tipe_pekerjaan }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Telepon -->
                    <div>
                        <label for="telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="tel" id="telepon" name="telepon" x-model="telepon" class="w-full border rounded-md p-2 text-sm" placeholder="Contoh: 08123456789" required>
                    </div>
                </div>

                <div x-show="step === 2" class="p-5 space-y-4">
                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" id="username" name="username" class="w-full border rounded-md p-2 text-sm" placeholder="Username untuk login" required>
                    </div>
                    
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" id="password" name="password" class="w-full border rounded-md p-2 text-sm" placeholder="Minimal 8 karakter" required>
                    </div>
                </div>
                
                <div x-show="step === 1" class="p-4 border-t text-right bg-gray-50 rounded-b-lg">
                    <button type="button" @click="showAddModal = false; step = 1; nama = ''; role = ''; telepon = ''" class="border px-4 py-2 rounded-md text-sm mr-2 hover:bg-gray-100">
                        Batal
                    </button>
                    <button 
                        type="button" 
                        @click="step = 2" 
                        :disabled="nama === '' || role === '' || telepon === ''"
                        :class="{ 'opacity-50 cursor-not-allowed': nama === '' || role === '' || telepon === '' }"
                        class="bg-black text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-800 transition-opacity"
                    >
                        Selanjutnya
                    </button>
                </div>

                <div x-show="step === 2" class="p-4 border-t text-right bg-gray-50 rounded-b-lg flex justify-between">
                    <button type="button" @click="step = 1" class="border px-4 py-2 rounded-md text-sm hover:bg-gray-100">
                        Kembali
                    </button>
                    <div>
                        <button type="button" @click="showAddModal = false; step = 1; nama = ''; role = ''; telepon = ''" class="border px-4 py-2 rounded-md text-sm mr-2 hover:bg-gray-100">
                            Batal
                        </button>
                        <button type="submit" class="bg-black text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-800">
                            Simpan Pengguna
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

@endsection

