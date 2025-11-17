<nav class="bg-gray-900 shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex items-center justify-between">

            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                <span class="text-2xl">🎬</span>
                <span class="text-2xl font-bold text-red-500">BioskopKu</span>
            </a>

            <div class="hidden md:flex items-center space-x-6">

                <a href="{{ route('home') }}" class="text-gray-300 hover:text-white">
                    Home
                </a>

                <a href="{{ route('my.tickets') }}" class="text-gray-300 hover:text-white">
                    🎫 Tiket Saya
                </a>

                <div class="relative group">
                    <button class="flex items-center gap-2 text-gray-300 hover:text-white">
                        👤 {{ Auth::user()?->name ?? 'Guest' }}
                    </button>

                    <div class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-lg opacity-0 invisible
                                group-hover:opacity-100 group-hover:visible transition-all duration-200">
                        <div class="py-2">
                            <a href="{{ route('profile.edit') }}"
                               class="block px-4 py-2 text-gray-300 hover:bg-gray-700">
                                ⚙️ Edit Profile
                            </a>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="w-full text-left px-4 py-2 text-red-400 hover:bg-gray-700">
                                    🚪 Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</nav>
