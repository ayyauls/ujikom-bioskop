<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Kasir BioskopKu</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body {
            background-color: #1a1a1a;
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="bg-[#2A2A2A] shadow-lg border-b border-gray-700 sticky top-0 z-50">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-8">
                    <a href="{{ route('kasir.dashboard') }}" class="text-2xl font-bold text-red-600 hover:text-red-500 transition-colors">
                        🎬 BioskopKu
                    </a>
                    
                    <!-- Navigation Menu -->
                    <div class="hidden md:flex space-x-1">
                        <a href="{{ route('kasir.dashboard') }}" 
                           class="px-4 py-2 rounded-lg font-semibold transition-all duration-200 
                                  {{ request()->routeIs('kasir.dashboard') ? 'bg-red-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700' }}">
                            📊 Dashboard
                        </a>
                        <a href="{{ route('kasir.pesan-tiket') }}" 
                           class="px-4 py-2 rounded-lg font-semibold transition-all duration-200 
                                  {{ request()->routeIs('kasir.pesan-tiket*') || request()->routeIs('kasir.film-detail') || request()->routeIs('kasir.pilih-kursi') ? 'bg-red-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700' }}">
                            🎫 Pesan Tiket
                        </a>
                        <a href="{{ route('kasir.riwayat') }}" 
                           class="px-4 py-2 rounded-lg font-semibold transition-all duration-200 
                                  {{ request()->routeIs('kasir.riwayat*') || request()->routeIs('kasir.detail-transaksi') ? 'bg-red-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700' }}">
                            📜 Riwayat
                        </a>
                        <a href="{{ route('kasir.laporan') }}" 
                           class="px-4 py-2 rounded-lg font-semibold transition-all duration-200 
                                  {{ request()->routeIs('kasir.laporan') ? 'bg-red-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700' }}">
                            📈 Laporan
                        </a>
                        <a href="{{ route('kasir.tiket') }}" 
                           class="px-4 py-2 rounded-lg font-semibold transition-all duration-200 
                                  {{ request()->routeIs('kasir.tiket*') ? 'bg-red-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700' }}">
                            🎟️ Tiket
                        </a>
                    </div>
                </div>
                
                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-gray-200 text-sm font-semibold">{{ auth()->user()->name }}</p>
                        <p class="text-gray-500 text-xs">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                    <form action="{{ route('kasir.logout') }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-white text-sm font-semibold 
                                       transition-all duration-200 shadow-lg hover:shadow-xl">
                            🚪 Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
        
        @yield('content')
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>