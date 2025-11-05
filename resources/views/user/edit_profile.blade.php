<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - BioskopKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#1E1E1E] text-white font-sans min-h-screen">

    @include('layouts.navbar')

    <div class="container mx-auto px-6 py-10">
        <div class="max-w-2xl mx-auto">
            
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold mb-2">⚙️ Edit Profile</h1>
                <p class="text-gray-400">Kelola informasi akun Anda</p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-lg bg-green-600 text-white">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-600 text-white">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Profile Form -->
            <div class="bg-[#2A2A2A] rounded-2xl p-8 shadow-xl mb-8">
                <h2 class="text-2xl font-bold mb-6">Informasi Personal</h2>
                
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="mb-6">
                        <label class="block text-gray-400 text-sm mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" 
                               class="w-full bg-[#1E1E1E] border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-red-600 focus:outline-none"
                               required>
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label class="block text-gray-400 text-sm mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" 
                               class="w-full bg-[#1E1E1E] border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-red-600 focus:outline-none"
                               required>
                    </div>

                    <!-- Phone -->
                    <div class="mb-6">
                        <label class="block text-gray-400 text-sm mb-2">Nomor Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" 
                               class="w-full bg-[#1E1E1E] border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-red-600 focus:outline-none"
                               required>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-red-600 hover:bg-red-700 py-3 rounded-lg font-bold transition-all duration-200">
                        💾 Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-[#2A2A2A] rounded-2xl p-8 shadow-xl">
                <h2 class="text-2xl font-bold mb-6">Ubah Password</h2>
                
                <form action="{{ route('profile.update.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div class="mb-6">
                        <label class="block text-gray-400 text-sm mb-2">Password Lama</label>
                        <input type="password" name="current_password" 
                               class="w-full bg-[#1E1E1E] border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-red-600 focus:outline-none"
                               required>
                    </div>

                    <!-- New Password -->
                    <div class="mb-6">
                        <label class="block text-gray-400 text-sm mb-2">Password Baru</label>
                        <input type="password" name="password" 
                               class="w-full bg-[#1E1E1E] border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-red-600 focus:outline-none"
                               required>
                        <p class="text-gray-500 text-xs mt-1">Minimal 6 karakter</p>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label class="block text-gray-400 text-sm mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" 
                               class="w-full bg-[#1E1E1E] border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-red-600 focus:outline-none"
                               required>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 py-3 rounded-lg font-bold transition-all duration-200">
                        🔒 Ubah Password
                    </button>
                </form>
            </div>

            <!-- Back Button -->
            <a href="{{ route('home') }}" 
               class="block mt-6 text-center text-gray-400 hover:text-white transition-colors duration-200">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>

    @include('layouts.footer')

</body>
</html>