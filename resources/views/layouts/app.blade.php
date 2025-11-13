<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('judul-halaman') - Toko Mekar Sari</title>
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800">
<div class="flex-1 flex flex-col">
    <!-- Top Bar -->
    <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200">
        <h2 class="font-semibold text-2xl">@yield('judul-halaman')</h2>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <img src='/build/assets/image/blank-profile.png' alt="User" class="w-8 h-8 rounded-full">
                <div class="text-sm">
                    <p class="font-semibold">Administrator</p>
                    <p class="text-gray-500 text-xs">Pemilik</p>
                </div>
            </div>
            <div class="bg-gray-800 text-white px-3 py-1 rounded-md text-sm">
                <form  action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>
    </header>
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 p-5 flex flex-col justify-between">
    <div>
        <div class="flex items-center gap-3 mb-8">
        <img src="{{ asset('/build/assets/image/logo.png') }}" alt="Logo Usaha" class="w-12 h-12 rounded-lg">
        <div>
            <h1 class="font-bold text-lg">Toko Mekar Sari</h1>
            <p class="text-xs text-gray-500">Toko Material Bahan Bangunan</p>
        </div>
        </div>

        <nav class="space-y-1">
            <a href="{{ route('dashboard') }}">
                <p class="block text-sm font-semibold text-gray-600 hover:bg-gray-200 rounded-md px-3 py-2 cursor-pointer transition border-b border-gray-300">
                    Dashboard
                </p>
            </a>
            <a href="{{ route('laporan') }}">
                <p class="block text-sm font-semibold text-gray-600 hover:bg-gray-200 rounded-md px-3 py-2 cursor-pointer transition border-b border-gray-300">
                    Laporan
                </p>
            </a>
            <a href="{{ route('pembelian.index') }}">
                <p class="block text-sm font-semibold text-gray-600 hover:bg-gray-200 rounded-md px-3 py-2 cursor-pointer transition border-b border-gray-300">
                    Pembelian Barang
                </p>
            </a>
            <a href="{{ route('penjualan.index') }}">
                <p class="block text-sm font-semibold text-gray-600 hover:bg-gray-200 rounded-md px-3 py-2 cursor-pointer transition border-b border-gray-300">
                    Penjualan Barang
                </p>
            </a>
            <a href="{{ route('pengguna') }}">
                <p class="block text-sm font-semibold text-gray-600 hover:bg-gray-200 rounded-md px-3 py-2 cursor-pointer transition border-b border-gray-300">
                    Pengaturan Pengguna
                </p>
            </a>
        </nav>
    </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col">
        @yield('isi-content')
    </div>
    @stack('script')
</body>

</html>
