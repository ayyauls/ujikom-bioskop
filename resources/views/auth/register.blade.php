<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - BioskopKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#1E1E1E] text-white font-sans flex flex-col min-h-screen">

    @include('layouts.navbar')

    <!-- Register Section -->
    <section class="flex flex-1 justify-center items-center px-6 py-10">
        <div class="bg-[#2A2A2A] p-10 rounded-2xl shadow-2xl shadow-red-900/25 w-full max-w-lg transition-transform hover:scale-[1.01]">
            <h2 class="text-4xl font-extrabold text-center mb-8">Buat Akun</h2>

            <!-- Alert error -->
            @if ($errors->any())
                <div class="mb-4 p-4 rounded-lg bg-red-600 text-white text-base shadow-md shadow-red-900/30">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Register -->
            <form action="{{ route('register') }}" method="POST">
                @csrf

                <!-- Nama -->
                <div class="mb-5">
                    <label for="name" class="block text-base font-semibold mb-2">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap"
                        class="w-full px-5 py-3 rounded-lg bg-[#1E1E1E] border border-gray-600 text-lg
                        focus:outline-none focus:ring-2 focus:ring-red-500" required>
                    @error('name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-5">
                    <label for="email" class="block text-base font-semibold mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email aktif"
                        class="w-full px-5 py-3 rounded-lg bg-[#1E1E1E] border border-gray-600 text-lg
                        focus:outline-none focus:ring-2 focus:ring-red-500" required>
                    @error('email')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Telepon -->
                <div class="mb-5">
                    <label for="phone" class="block text-base font-semibold mb-2">Nomor Telepon</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Masukkan nomor telepon"
                        class="w-full px-5 py-3 rounded-lg bg-[#1E1E1E] border border-gray-600 text-lg
                        focus:outline-none focus:ring-2 focus:ring-red-500" required>
                    @error('phone')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-5">
                    <label for="password" class="block text-base font-semibold mb-2">Kata Sandi</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan kata sandi"
                        class="w-full px-5 py-3 rounded-lg bg-[#1E1E1E] border border-gray-600 text-lg
                        focus:outline-none focus:ring-2 focus:ring-red-500" required>
                    @error('password')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div class="mb-8">
                    <label for="password_confirmation" class="block text-base font-semibold mb-2">Konfirmasi Kata Sandi</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi kata sandi"
                        class="w-full px-5 py-3 rounded-lg bg-[#1E1E1E] border border-gray-600 text-lg
                        focus:outline-none focus:ring-2 focus:ring-red-500" required>
                </div>

                <!-- Tombol Daftar -->
                <button type="submit"
                    class="mt-4 w-full bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 
                    transition-all duration-200 py-3 text-lg rounded-full font-semibold shadow-lg shadow-red-900/30">
                    Daftar
                </button>

                <!-- Link ke Login -->
                <p class="text-center text-base text-gray-400 mt-6">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-red-400 hover:underline font-semibold">Masuk di sini</a>
                </p>
            </form>
        </div>
    </section>

    @include('layouts.footer')

</body>
</html>