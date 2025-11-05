<nav class="bg-[#2A2A2A] shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                <span class="text-2xl">🎬</span>
                <span class="text-2xl font-bold text-red-500">BioskopKu</span>
            </a>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors duration-200">
                    Home
                </a>

                @auth
                    <!-- Tiket Aktif Menu (Hanya untuk user yang login) -->
                    <a href="#" class="flex items-center gap-2 text-gray-300 hover:text-white transition-colors duration-200">
                        <span>🎫</span>
                        <span>Tiket Saya</span>
                        @php
                            $activeTicketsCount = \App\Models\Booking::where('user_id', auth()->id())
                                ->where('booking_date', '>=', now()->format('Y-m-d'))
                                ->where('status', '!=', 'cancelled')
                                ->count();
                        @endphp
                        @if($activeTicketsCount > 0)
                            <span class="bg-red-600 text-white text-xs px-2 py-0.5 rounded-full">{{ $activeTicketsCount }}</span>
                        @endif
                    </a>

                    <!-- Profile Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center gap-2 text-gray-300 hover:text-white transition-colors duration-200">
                            <span>👤</span>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-48 bg-[#1E1E1E] rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <div class="py-2">
                                <a href="#" class="block px-4 py-2 text-gray-300 hover:bg-[#2A2A2A] hover:text-white transition-colors duration-200">
                                    ⚙️ Edit Profile
                                </a>
                                <hr class="border-gray-700 my-2">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-400 hover:bg-[#2A2A2A] hover:text-red-300 transition-colors duration-200">
                                        🚪 Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Login & Register (untuk user yang belum login) -->
                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors duration-200">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-red-600 hover:bg-red-700 px-6 py-2 rounded-full font-semibold transition-all duration-200">
                        Register
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden mt-4 pb-4">
            <div class="flex flex-col space-y-3">
                <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors duration-200">
                    Home
                </a>
                
                @auth
                    <a href="#" class="flex items-center gap-2 text-gray-300 hover:text-white transition-colors duration-200">
                        <span>🎫</span>
                        <span>Tiket Saya</span>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                        ⚙️ Edit Profile
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-red-400 hover:text-red-300 transition-colors duration-200">
                            🚪 Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors duration-200">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-red-600 hover:bg-red-700 px-6 py-2 rounded-full font-semibold text-center transition-all duration-200">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-btn').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>