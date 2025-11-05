<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Saya - BioskopKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#1E1E1E] text-white font-sans min-h-screen">

    @include('layouts.navbar')

    <div class="container mx-auto px-6 py-10">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold mb-2">🎫 Tiket Saya</h1>
            <p class="text-gray-400">Kelola dan lihat semua tiket pemesanan Anda</p>
        </div>

        <!-- Tabs -->
        <div class="flex gap-4 mb-8 border-b border-gray-700">
            <button onclick="showTab('active')" id="tab-active" class="tab-btn active px-6 py-3 font-semibold border-b-2 border-red-600 text-red-600">
                Tiket Aktif
            </button>
            <button onclick="showTab('history')" id="tab-history" class="tab-btn px-6 py-3 font-semibold border-b-2 border-transparent text-gray-400 hover:text-white">
                Riwayat
            </button>
        </div>

        <!-- Tiket Aktif -->
        <div id="content-active" class="tab-content">
            @if(count(array_filter($bookings, fn($b) => strtotime($b['booking_date']) >= strtotime('today') && $b['status'] != 'cancelled')) > 0)
                <div class="grid gap-6">
                    @foreach($bookings as $booking)
                        @if(strtotime($booking['booking_date']) >= strtotime('today') && $booking['status'] != 'cancelled')
                        <div class="bg-[#2A2A2A] rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-200">
                            <div class="flex gap-6">
                                <!-- Poster -->
                                @if($booking['poster'])
                                <img src="{{ asset($booking['poster']) }}" alt="{{ $booking['film_title'] }}" 
                                     class="w-32 h-48 object-cover rounded-lg shadow-lg flex-shrink-0">
                                @endif
                                
                                <!-- Details -->
                                <div class="flex-1">
                                    <!-- Status Badge -->
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="bg-green-600 px-4 py-1 rounded-full text-sm font-semibold">
                                            ✓ {{ ucfirst($booking['status']) }}
                                        </span>
                                    </div>

                                    <!-- Film Title -->
                                    <h3 class="text-2xl font-bold mb-3">{{ $booking['film_title'] }}</h3>

                                    <!-- Booking Info -->
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <p class="text-gray-400 text-sm">Kode Booking</p>
                                            <p class="text-lg font-bold text-red-400">{{ $booking['booking_code'] }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-400 text-sm">Tanggal Tayang</p>
                                            <p class="font-semibold">{{ \Carbon\Carbon::parse($booking['booking_date'])->format('d M Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-400 text-sm">Jam Tayang</p>
                                            <p class="font-semibold">{{ $booking['showtime'] }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-400 text-sm">Kursi</p>
                                            <p class="font-semibold text-blue-400">{{ implode(', ', $booking['seats']) }}</p>
                                        </div>
                                    </div>

                                    <!-- Total -->
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-700">
                                        <div>
                                            <p class="text-gray-400 text-sm">Total Pembayaran</p>
                                            <p class="text-2xl font-bold text-red-500">Rp {{ number_format($booking['total_price'], 0, ',', '.') }}</p>
                                        </div>
                                        <div class="flex gap-3">
                                            <a href="{{ route('ticket.detail', $booking['booking_code']) }}" 
                                               class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-lg font-semibold transition-all duration-200">
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="bg-[#2A2A2A] rounded-2xl p-12 text-center">
                    <div class="text-6xl mb-4">🎟️</div>
                    <h3 class="text-2xl font-bold mb-2">Belum Ada Tiket Aktif</h3>
                    <p class="text-gray-400 mb-6">Yuk booking film favoritmu sekarang!</p>
                    <a href="{{ route('home') }}" class="inline-block bg-red-600 hover:bg-red-700 px-8 py-3 rounded-full font-bold transition-all duration-200">
                        Jelajahi Film
                    </a>
                </div>
            @endif
        </div>

        <!-- Riwayat -->
        <div id="content-history" class="tab-content hidden">
            @if(count(array_filter($bookings, fn($b) => strtotime($b['booking_date']) < strtotime('today') || $b['status'] == 'cancelled')) > 0)
                <div class="grid gap-6">
                    @foreach($bookings as $booking)
                        @if(strtotime($booking['booking_date']) < strtotime('today') || $booking['status'] == 'cancelled')
                        <div class="bg-[#2A2A2A] rounded-2xl p-6 shadow-xl opacity-75">
                            <div class="flex gap-6">
                                <!-- Poster -->
                                @if($booking['poster'])
                                <img src="{{ asset($booking['poster']) }}" alt="{{ $booking['film_title'] }}" 
                                     class="w-32 h-48 object-cover rounded-lg shadow-lg flex-shrink-0 grayscale">
                                @endif
                                
                                <!-- Details -->
                                <div class="flex-1">
                                    <!-- Status Badge -->
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="bg-gray-600 px-4 py-1 rounded-full text-sm font-semibold">
                                            {{ $booking['status'] === 'cancelled' ? '✗ Dibatalkan' : '✓ Selesai' }}
                                        </span>
                                    </div>

                                    <!-- Film Title -->
                                    <h3 class="text-2xl font-bold mb-3 text-gray-300">{{ $booking['film_title'] }}</h3>

                                    <!-- Booking Info -->
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <p class="text-gray-400 text-sm">Kode Booking</p>
                                            <p class="text-lg font-bold text-gray-400">{{ $booking['booking_code'] }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-400 text-sm">Tanggal Tayang</p>
                                            <p class="font-semibold text-gray-400">{{ \Carbon\Carbon::parse($booking['booking_date'])->format('d M Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-400 text-sm">Jam Tayang</p>
                                            <p class="font-semibold text-gray-400">{{ $booking['showtime'] }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-400 text-sm">Kursi</p>
                                            <p class="font-semibold text-gray-400">{{ implode(', ', $booking['seats']) }}</p>
                                        </div>
                                    </div>

                                    <!-- Total -->
                                    <div class="pt-4 border-t border-gray-700">
                                        <p class="text-gray-400 text-sm">Total Pembayaran</p>
                                        <p class="text-2xl font-bold text-gray-500">Rp {{ number_format($booking['total_price'], 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="bg-[#2A2A2A] rounded-2xl p-12 text-center">
                    <div class="text-6xl mb-4">📋</div>
                    <h3 class="text-2xl font-bold mb-2">Belum Ada Riwayat</h3>
                    <p class="text-gray-400">Riwayat pemesanan Anda akan muncul di sini</p>
                </div>
            @endif
        </div>

    </div>

    @include('layouts.footer')

    <script>
        function showTab(tab) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-red-600', 'text-red-600');
                btn.classList.add('border-transparent', 'text-gray-400');
            });
            
            // Show selected tab
            document.getElementById('content-' + tab).classList.remove('hidden');
            
            // Add active to selected button
            const activeBtn = document.getElementById('tab-' + tab);
            activeBtn.classList.add('border-red-600', 'text-red-600');
            activeBtn.classList.remove('border-transparent', 'text-gray-400');
        }
    </script>

</body>
</html>