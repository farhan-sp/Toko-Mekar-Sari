<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('judul-halaman') - Toko Mekar Sari</title>
    
    {{-- Menggunakan CDN FontAwesome untuk Ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

{{-- x-data untuk mengontrol state sidebar di mobile --}}
<body class="bg-gray-50 text-gray-800 font-sans antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">

        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/50 z-40 md:hidden"></div>

        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-0 shadow-lg md:shadow-none flex flex-col"
        >
            <div class="flex items-center gap-3 p-6 border-b border-gray-100 h-20">
                <img src="{{ asset('/build/assets/image/logo.png') }}" alt="Logo" class="w-10 h-10 rounded-lg object-cover">
                <div>
                    <h1 class="font-bold text-lg leading-tight text-gray-800">Mekar Sari</h1>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider">Toko Material Bangunan</p>
                </div>
                <button @click="sidebarOpen = false" class="ml-auto md:hidden text-gray-500">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto p-4 space-y-1">
                {{-- Helper component untuk link agar codingan rapi --}}
                @php
                    function activeClass($route) {
                        return request()->routeIs($route) 
                            ? 'bg-blue-50 text-blue-700 border-blue-600' 
                            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent';
                    }
                @endphp

                <a href="{{ route('dashboard') }}" class="{{ activeClass('dashboard') }} group flex items-center px-3 py-2.5 text-sm font-medium border-l-4 rounded-r-md transition-colors">
                    <i class="fa-solid fa-house w-6 text-center mr-2 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Dashboard
                </a>

                <a href="{{ route('laporan') }}" class="{{ activeClass('laporan') }} group flex items-center px-3 py-2.5 text-sm font-medium border-l-4 rounded-r-md transition-colors">
                    <i class="fa-solid fa-chart-line w-6 text-center mr-2 {{ request()->routeIs('laporan') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Laporan
                </a>

                <div class="pt-4 pb-2">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Transaksi</p>
                </div>

                <a href="{{ route('pembelian.index') }}" class="{{ activeClass('pembelian.*') }} group flex items-center px-3 py-2.5 text-sm font-medium border-l-4 rounded-r-md transition-colors">
                    <i class="fa-solid fa-cart-shopping w-6 text-center mr-2 {{ request()->routeIs('pembelian.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Pembelian
                </a>

                <a href="{{ route('penjualan.index') }}" class="{{ activeClass('penjualan.*') }} group flex items-center px-3 py-2.5 text-sm font-medium border-l-4 rounded-r-md transition-colors">
                    <i class="fa-solid fa-cash-register w-6 text-center mr-2 {{ request()->routeIs('penjualan.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Penjualan
                </a>

                <a href="{{ route('barang.index') }}" class="{{ activeClass('barang.*') }} group flex items-center px-3 py-2.5 text-sm font-medium border-l-4 rounded-r-md transition-colors">
                    <i class="fa-solid fa-boxes-stacked w-6 text-center mr-2 {{ request()->routeIs('barang.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Data Barang
                </a>

                <div class="pt-4 pb-2">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Pengaturan</p>
                </div>

                <a href="{{ route('pengguna') }}" class="{{ activeClass('pengguna') }} group flex items-center px-3 py-2.5 text-sm font-medium border-l-4 rounded-r-md transition-colors">
                    <i class="fa-solid fa-users-gear w-6 text-center mr-2 {{ request()->routeIs('pengguna') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Pengguna
                </a>
            </nav>

            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center gap-3">
                    <img src='/build/assets/image/blank-profile.png' alt="User" class="w-9 h-9 rounded-full border border-gray-200">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">Administrator</p>
                        <p class="text-xs text-gray-500 truncate">Pemilik Toko</p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <header class="bg-white border-b border-gray-200 h-20 flex items-center justify-between px-6 shadow-sm z-10">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none md:hidden hover:text-gray-700">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    
                    <h2 class="font-semibold text-xl text-gray-800">@yield('judul-halaman')</h2>
                </div>

                <div class="flex items-center gap-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium flex items-center gap-2 transition-colors">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span class="hidden sm:inline">Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                @yield('isi-content')
            </main>
        </div>
    </div>

    @stack('script')
</body>
</html>