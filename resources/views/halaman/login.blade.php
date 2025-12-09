<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Toko Mekar Sari</title>
  
  {{-- Tailwind CSS --}}
  @vite(['resources/css/app.css'])
  
  {{-- Alpine.js untuk Interaktivitas --}}
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="min-h-screen flex flex-col items-center justify-center bg-[#eef3ff] text-gray-800 font-sans p-4">

    <!-- Animasi Masuk -->
    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" 
         :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-5'" 
         class="w-full max-w-md transition-all duration-700 ease-out">

        <!-- Header Logo -->
        <div class="text-center mb-8">
            <img src="{{ asset('/build/assets/images/logo.png') }}" alt="Logo" class="mx-auto w-24 h-24 mb-4 rounded-xl shadow-sm object-cover" />
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Toko Mekar Sari</h1>
            <p class="text-gray-500 text-sm mt-1">Sistem Informasi Material Bahan Bangunan</p>
        </div>

        <!-- Card Login -->
        <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100" x-data="{ isLoading: false, showPassword: false }">
            
            <h2 class="text-xl font-semibold text-center mb-6 flex items-center justify-center gap-2 text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-blue-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
                Silahkan Masuk
            </h2>

            <form action="{{ route('autentikasi') }}" method="POST" class="space-y-5" @submit="isLoading = true">
                @csrf
                
                @if(session('error') || $errors->has('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md text-sm flex items-start gap-2 animate-pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 mt-0.5 shrink-0">
                        <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('error') ?? $errors->first('error') }}</span>
                </div>
                @endif

                {{-- Input Username --}}
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <div class="group flex items-center bg-gray-50 border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 focus-within:bg-white transition-all duration-200 overflow-hidden">
                        <span class="pl-3 pr-2 text-gray-400 group-focus-within:text-blue-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </span>
                        <input type="text" id="username" name="username"
                            placeholder="Masukkan username Anda"
                            class="w-full bg-transparent border-none focus:ring-0 py-2.5 text-gray-900 placeholder-gray-400 text-sm" 
                            required 
                            autocomplete="username"
                        />
                    </div>
                </div>

                {{-- Input Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="group flex items-center bg-gray-50 border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 focus-within:bg-white transition-all duration-200 overflow-hidden relative">
                        <span class="pl-3 pr-2 text-gray-400 group-focus-within:text-blue-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </span>
                        
                        {{-- Logika Toggle Password: ubah tipe input berdasarkan state showPassword --}}
                        <input :type="showPassword ? 'text' : 'password'" id="password" name="password"
                            placeholder="Masukkan password Anda"
                            class="w-full bg-transparent border-none focus:ring-0 py-2.5 text-gray-900 placeholder-gray-400 text-sm pr-10" 
                            required 
                        />

                        {{-- Tombol Mata (Toggle) --}}
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-3 text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer transition-colors">
                            <!-- Mata Terbuka -->
                            <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <!-- Mata Tertutup -->
                            <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Tombol Login --}}
                <button type="submit" 
                        :disabled="isLoading"
                        class="w-full bg-gray-900 hover:bg-gray-800 disabled:bg-gray-600 disabled:cursor-not-allowed text-white font-medium py-2.5 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 shadow-md hover:shadow-lg transform active:scale-[0.98]">
                    
                    <svg x-show="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>

                    <span x-text="isLoading ? 'Memproses...' : 'Masuk'"></span>
                </button>
            </form>
        </div>

        <footer class="mt-8 text-center text-gray-400 text-xs">
            © {{ date('Y') }} Toko Mekar Sari. Semua hak dilindungi.<br>
            Sistem Versi 1.0
        </footer>
    </div>
</body>
</html>