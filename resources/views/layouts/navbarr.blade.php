<nav class="bg-gray-900 shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex items-center justify-between">

            <a href="{{ route('owner.dashboard') }}" class="flex items-center space-x-2">
                <span class="text-2xl">🎬</span>
                <span class="text-2xl font-bold text-red-500">Owner Panel</span>
            </a>

            <div class="hidden md:flex items-center space-x-6">

                <a href="{{ route('owner.dashboard') }}" class="text-gray-300 hover:text-white">
                    📊 Dashboard
                </a>

                <a href="{{ route('owner.history') }}" class="text-gray-300 hover:text-white">
                    📜 History
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="text-red-400 hover:text-red-300">
                        🚪 Logout
                    </button>
                </form>

            </div>

        </div>
    </div>
</nav>
