<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Berhasil - BioskopKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#1E1E1E] text-white font-sans min-h-screen">

    @include('layouts.navbar')

    <div class="container mx-auto px-6 py-16">
        <div class="max-w-3xl mx-auto">
            
            <!-- Success Icon -->
            <div class="text-center mb-8">
                <div class="inline-block bg-green-600 rounded-full p-6 mb-4">
                    <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold mb-2">🎉 Booking Berhasil!</h1>
                <p class="text-gray-400">Terima kasih telah melakukan pemesanan di BioskopKu</p>
            </div>

            <!-- Booking Code -->
            @if(session()->has('booking_code'))
            <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-2xl p-6 mb-8 text-center shadow-xl">
                <p class="text-sm text-gray-200 mb-2">Kode Booking</p>
                <p class="text-4xl font-bold tracking-wider">{{ session('booking_code') }}</p>
                <p class="text-xs text-gray-200 mt-2">Simpan kode ini untuk mengambil tiket di loket</p>
            </div>
            @endif

            <!-- Booking Details -->
            <div class="bg-[#2A2A2A] rounded-2xl p-8 mb-8 shadow-xl">
                
                @if(session()->has('poster'))
                <div class="flex gap-6 mb-6 pb-6 border-b border-gray-700">
                    <img src="{{ asset(session('poster')) }}" alt="{{ session('film') }}" 
                         class="w-32 h-48 object-cover rounded-lg shadow-lg">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold mb-2">{{ session('film') }}</h2>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="text-gray-400">📅 Tanggal:</span>
                                <span class="font-semibold">{{ session('booking_date') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-400">🕐 Jam:</span>
                                <span class="font-semibold">{{ session('showtime') }}</span>
                            </div>
                            @if(session()->has('customer_name'))
                            <div class="flex items-center gap-2">
                                <span class="text-gray-400">👤 Nama:</span>
                                <span class="font-semibold">{{ session('customer_name') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">🪑 Kursi yang Dipesan:</p>
                        <p class="text-xl font-bold text-blue-400">{{ implode(', ', session('seats')) }}</p>
                    </div>
                    
                    <div>
                        <p class="text-gray-400 text-sm mb-1">🎫 Jumlah Tiket:</p>
                        <p class="text-xl font-bold">{{ count(session('seats')) }} Tiket</p>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-700">
                        <p class="text-gray-400 text-sm mb-1">💰 Total Pembayaran:</p>
                        <p class="text-3xl font-bold text-red-500">Rp {{ number_format(session('total'), 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Info -->
            <div class="bg-blue-900/30 border border-blue-700 rounded-xl p-6 mb-8">
                <h3 class="font-bold mb-2 text-blue-400">ℹ️ Informasi Penting:</h3>
                <ul class="text-sm text-gray-300 space-y-2">
                    <li>• Harap datang 15 menit sebelum jam tayang dimulai</li>
                    @if(session()->has('booking_code'))
                    <li>• Tunjukkan <strong>Kode Booking ({{ session('booking_code') }})</strong> di loket untuk mendapatkan tiket fisik</li>
                    @else
                    <li>• Tunjukkan konfirmasi booking ini di loket untuk mendapatkan tiket fisik</li>
                    @endif
                    <li>• Simpan screenshot halaman ini sebagai bukti pemesanan</li>
                    <li>• Tiket yang sudah dibeli tidak dapat dikembalikan</li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 flex-col sm:flex-row">
                <a href="{{ route('home') }}" 
                   class="flex-1 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 
                   py-4 rounded-full text-lg font-bold text-center transition-all duration-200 hover:scale-105">
                    🏠 Kembali ke Beranda
                </a>
                <button onclick="window.print()" 
                   class="flex-1 bg-gray-700 hover:bg-gray-600 py-4 rounded-full text-lg font-bold 
                   transition-all duration-200">
                    🖨️ Cetak Bukti
                </button>
            </div>
        </div>
    </div>

    @include('layouts.footer')

    <style>
        @media print {
            nav, footer, button {
                display: none !important;
            }
            body {
                background: white;
                color: black;
            }
            .bg-\[\#2A2A2A\], .bg-blue-900\/30 {
                background: white !important;
                border: 1px solid #ddd;
            }
        }
    </style>

</body>
</html>