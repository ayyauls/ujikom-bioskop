<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - BioskopKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-[#1E1E1E] text-white font-sans flex flex-col min-h-screen">

    <!-- Navbar Placeholder -->
    <nav class="bg-[#2A2A2A] py-4 px-6 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-red-500">BioskopKu</a>
            <div class="space-x-4">
                <a href="/" class="text-gray-300 hover:text-white transition-colors">Home</a>
                <a href="#" class="text-gray-300 hover:text-white transition-colors">Movies</a>
                <a href="#" class="text-gray-300 hover:text-white transition-colors">About</a>
            </div>
        </div>
    </nav>

    <!-- Login Section -->
    <section class="flex flex-1 justify-center items-center px-6 py-10">
        <div class="bg-[#2A2A2A] p-10 rounded-2xl shadow-2xl shadow-red-900/25 w-full max-w-lg transition-transform hover:scale-[1.01]">
            <h2 class="text-4xl font-extrabold text-center mb-8">Admin Login</h2>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mb-4 p-4 rounded-lg bg-green-600 text-white text-base text-center shadow-md shadow-green-900/30">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-4 rounded-lg bg-red-600 text-white text-base shadow-md shadow-red-900/30">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Login -->
            <form action="{{ route('admin.login') }}" method="POST" class="flex flex-col gap-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-base font-semibold mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email admin"
                        class="w-full px-5 py-3 rounded-lg bg-[#1E1E1E] border border-gray-600 text-lg
                        focus:outline-none focus:ring-2 focus:ring-red-500 @error('email') border-red-500 @enderror" required>
                    @error('email')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-base font-semibold mb-2">Kata Sandi</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan kata sandi admin"
                        class="w-full px-5 py-3 rounded-lg bg-[#1E1E1E] border border-gray-600 text-lg
                        focus:outline-none focus:ring-2 focus:ring-red-500 @error('password') border-red-500 @enderror" required>
                    @error('password')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tombol Login -->
                <button type="submit"
                    class="mt-6 w-full bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 
                    transition-all duration-200 py-3 text-lg rounded-full font-semibold shadow-lg shadow-red-900/30">
                    Login
                </button>
            </form>

            <!-- Link ke Home -->
            <p class="text-center text-base text-gray-400 mt-6">
                <a href="/" class="text-red-400 hover:underline font-semibold">← Kembali ke Home</a>
            </p>
        </div>
    </section>

    <!-- Footer Placeholder -->
   SEMANGAT AYUNGGGG<3 

</body>
</html>