<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Material Jaya</title>
  @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen flex flex-col items-center justify-center bg-[#eef3ff] text-gray-800">
    <div class="text-center mb-8">
        <img src="{{ asset('/build/assets/image/logo.png') }}" alt="Logo" class="mx-auto w-30 h-30 mb-3" />
        <h1 class="text-2xl font-bold text-3xl">Toko Mekar Sari</h1>
        <p class="text-gray-500 text-md">Toko Material Bahan Bangunan</p>
    </div>

    <div class="bg-white w-full max-w-md p-8 rounded-2xl shadow-lg">
        
        <h2 class="text-xl font-semibold text-center mb-6 flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
            </svg>
            Login
        </h2>

        <form action="{{ route('autentikasi') }}" method="POST" class="space-y-4">
            @csrf
            
            @error('error')
            <div style="color: red;">
                {{ $message }}  
            </div>
            @enderror

            <div>
                <label for="username" class="text-sm font-medium">Username</label>
                <div class="flex items-center mt-1 bg-gray-100 rounded-md h-10">
                <span class="px-3 text-gray-500">
                    <!-- Menambahkan gambar -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </span>
                <input type="text" id="username" name="username"
                        placeholder="Masukkan username"
                        class="w-full bg-transparent border-none focus:ring-0 px-2 py-2 text-sm" required />
                </div>
            </div>

            <div>
                <label for="password" class="text-sm font-medium">Password</label>
                <div class="flex items-center mt-1 bg-gray-100 rounded-md">
                <span class="px-3 text-gray-500">
                    <!-- Menambahkan gambar -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </span>
                <input type="password" id="password" name="password"
                        placeholder="Masukkan password"
                        class="w-full bg-transparent border-none focus:ring-0 px-2 py-2 text-sm" required />
                </div>
            </div>

            <button type="submit" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-medium py-2 rounded-md transition flex items-center justify-center gap-2">
                <a href="{{ route('dashboard') }}"></a>
                    Masuk
                </a>
            </button>
        </form>
    </div>

    <footer class="mt-8 text-center text-gray-500 text-sm">
        © 2025 Toko Mekar Sari. Semua hak dilindungi.
    </footer>
</body>

</html>
