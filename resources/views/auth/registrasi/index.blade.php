<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - e-SIPUS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-purple-50 to-pink-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <img src="{{ asset('assets/Logo_E-Sipus.png') }}" alt="Logo e-SIPUS" class="h-16 mx-auto mb-4">
            <h1 class="text-3xl font-bold text-gray-900">Buat Akun Baru</h1>
            <p class="text-gray-600 mt-2">Bergabunglah dengan e-SIPUS sekarang</p>
        </div>

        <!-- Register Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role Selection -->
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Daftar Sebagai</label>
                    <select id="role" name="role" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                        <option value="">Pilih Role</option>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                    </select>
                    @error('role')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-2">
                        Catatan: Jika memilih "Petugas", akun akan terdaftar sebagai User dan perlu persetujuan admin.
                    </p>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input id="password" type="password" name="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-purple-600 text-white py-3 px-4 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition duration-200 font-semibold">
                    Daftar Sekarang
                </button>
            </form>

            <!-- Login Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-500 font-medium transition">
                        Login di sini
                    </a>
                </p>
            </div>

            <!-- Back to Home -->
            <div class="mt-4 text-center">
                <a href="{{ route('landingPage') }}" class="text-sm text-gray-500 hover:text-gray-700 transition">
                    ← Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>
