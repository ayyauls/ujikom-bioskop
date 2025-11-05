<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tiket - BioskopKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#1E1E1E] text-white font-sans min-h-screen">

    @include('layouts.navbar')

    <div class="container mx-auto px-6 py-10">
        <div class="max-w-3xl mx-auto">
            
            <!-- Back Button -->
            <a href="{{ route('my.tickets') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-white mb-6 transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Tiket Saya
            </a>

            <!-- Ticket Card -->
            <div class="bg-[#2A2A2A] rounded-2xl overflow-hidden shadow-2xl">
                
                <!-- Header dengan Booking Code -->
                <div class="bg-gradient-to-r from-red-600 to-red-700 p-6 text-center">
                    <p class="text-sm text-gray-200 mb-2">Kode Booking</p>
                    <p class="text-4xl font-bold tracking-wider">{{ $booking_code }}</p>
                    <p class="text-xs text-gray-200 mt-2">Tunjukkan kode ini di loket</p>
                </div>

                <!-- Content -->
                <div class="p-8">
                    <!-- Film Info -->
                    <div class="flex gap-6 mb-6 pb-6 border-b border-gray-700">
                        @if($booking->film && $booking->film->poster)
                        <img src="{{ asset($booking->film->poster) }}" alt="{{ $booking->film->title }}" 
                             class="w-32 h-48 object-cover rounded-lg shadow-lg">
                        @endif
                        <div class="flex-1">
                            <h2 class="text-3xl font-bold mb-2">{{ $booking->film->title ?? 'Film Title' }}</h2>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-400">🎭 Genre:</span>
                                    <span>{{ $booking->film->genre ?? '-' }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-400">⏱️ Durasi:</span>
                                    <span>{{ $booking->film->duration ?? '-' }} menit</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-400">⭐ Rating:</span>
                                    <span>{{ $booking->film->rating ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Details -->
                    <div class="space-y-4 mb-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-400 text-sm mb-1">📅 Tanggal Tayang</p>
                                <p class="text-xl font-semibold">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm mb-1">🕐 Jam Tayang</p>
                                <p class="text-xl font-semibold">{{ $booking->showtime }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-gray-400 text-sm mb-1">👤 Nama Pemesan</p>
                            <p class="text-lg font-semibold">{{ $booking->customer_name }}</p>
                        </div>

                        <div>
                            <p class="text-gray-400 text-sm mb-1">🪑 Kursi</p>
                            <p class="text-2xl font-bold text-blue-400">{{ implode(', ', $seats) }}</p>
                        </div>

                        <div>
                            <p class="text-gray-400 text-sm mb-1">🎫 Jumlah Tiket</p>
                            <p class="text-lg font-semibold">{{ count($seats) }} Tiket</p>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="pt-6 border-t border-gray-700">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-400 text-sm mb-1">💰 Total Pembayaran</p>
                                <p class="text-3xl font-bold text-red-500">Rp {{ number_format($total_price, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-400 text-sm mb-1">Status</p>
                                <span class="inline-block bg-green-600 px-4 py-2 rounded-lg font-semibold">
                                    ✓ {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="bg-blue-900/30 border-t border-blue-700 p-6">
                    <h3 class="font-bold mb-2 text-blue-400">ℹ️ Informasi Penting:</h3>
                    <ul class="text-sm text-gray-300 space-y-2">
                        <li>• Harap datang 15 menit sebelum jam tayang dimulai</li>
                        <li>• Tunjukkan <strong>Kode Booking ({{ $booking_code }})</strong> di loket untuk mendapatkan tiket fisik</li>
                        <li>• Simpan screenshot halaman ini sebagai bukti pemesanan</li>
                        <li>• Tiket yang sudah dibeli tidak dapat dikembalikan</li>
                    </ul>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 mt-6">
                <button onclick="window.print()" 
                   class="flex-1 bg-gray-700 hover:bg-gray-600 py-3 rounded-lg font-bold transition-all duration-200">
                    🖨️ Cetak Tiket
                </button>
                <a href="{{ route('home') }}" 
                   class="flex-1 bg-red-600 hover:bg-red-700 py-3 rounded-lg font-bold text-center transition-all duration-200">
                    🏠 Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    @include('layouts.footer')

    <style>
        @media print {
            nav, footer, button, a {
                display: none !important;
            }
            body {
                background: white;
                color: black;
            }
        }
    </style>

</body>
</html>