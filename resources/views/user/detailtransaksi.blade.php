<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi - BioskopKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#1E1E1E] text-white font-sans min-h-screen">

    @include('layouts.navbar')

    <div class="container mx-auto px-6 py-16">
        <div class="max-w-4xl mx-auto">
            
            <!-- Back Button -->
            <a href="{{ route('transaction.index') }}" 
               class="inline-flex items-center gap-2 text-gray-400 hover:text-white mb-8 transition-colors group">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Riwayat Transaksi
            </a>

            <!-- Header -->
            <div class="text-center mb-10">
                <div class="inline-block bg-gradient-to-r from-red-600 to-red-500 rounded-full p-6 mb-4 shadow-xl">
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold mb-3">📋 Detail Transaksi</h1>
                <p class="text-xl text-gray-400 font-mono tracking-wider">{{ $transaction->transaction_code }}</p>
            </div>

            <!-- Status Card -->
            <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-2xl p-8 mb-8 text-center shadow-2xl">
                <p class="text-sm text-gray-200 mb-3 uppercase tracking-wide">Status Pembayaran</p>
                @if($transaction->status === 'paid')
                    <div class="flex items-center justify-center gap-3 mb-3">
                        <svg class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-5xl font-bold tracking-wider">LUNAS</p>
                    </div>
                    <p class="text-sm text-gray-200">Pembayaran berhasil pada {{ $transaction->paid_at ? $transaction->paid_at->format('d M Y, H:i') : $transaction->updated_at->format('d M Y, H:i') }}</p>
                @elseif($transaction->status === 'pending')
                    <div class="flex items-center justify-center gap-3 mb-3">
                        <svg class="w-12 h-12 text-yellow-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-5xl font-bold tracking-wider">PENDING</p>
                    </div>
                    <p class="text-sm text-gray-200">Menunggu pembayaran</p>
                @elseif($transaction->status === 'expired')
                    <div class="flex items-center justify-center gap-3 mb-3">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-5xl font-bold tracking-wider">EXPIRED</p>
                    </div>
                    <p class="text-sm text-gray-200">Transaksi telah kedaluwarsa</p>
                @else
                    <div class="flex items-center justify-center gap-3 mb-3">
                        <svg class="w-12 h-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-5xl font-bold tracking-wider">{{ strtoupper($transaction->status) }}</p>
                    </div>
                @endif
            </div>

            <!-- Film Info Card -->
            @if(isset($film) && $film)
            <div class="bg-[#2A2A2A] rounded-2xl p-8 mb-8 shadow-2xl">
                <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                    <span>🎬</span> Informasi Film
                </h2>
                
                <div class="flex gap-6 mb-6 pb-6 border-b border-gray-700">
                    <img src="{{ asset($film->poster) }}" alt="{{ $film->title }}" 
                         class="w-40 h-56 object-cover rounded-xl shadow-xl">
                    <div class="flex-1">
                        <h3 class="text-3xl font-bold mb-4 text-red-500">{{ $film->title }}</h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <span class="text-gray-400 w-24">🎭 Genre:</span>
                                <span class="font-semibold text-lg">{{ $film->genre }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-gray-400 w-24">⏱️ Durasi:</span>
                                <span class="font-semibold text-lg">{{ $film->duration }} menit</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-gray-400 w-24">🔞 Rating:</span>
                                <span class="font-semibold text-lg">{{ $film->rating }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if(isset($bookings) && $bookings->isNotEmpty())
                    <!-- Date & Time -->
                    <div class="bg-[#1E1E1E] rounded-xl p-5">
                        <p class="text-gray-400 text-sm mb-2 flex items-center gap-2">
                            <span>📅</span> Tanggal & Waktu
                        </p>
                        <p class="text-xl font-bold">
                            {{ \Carbon\Carbon::parse($bookings->first()->booking_date)->format('d M Y') }}
                        </p>
                        <p class="text-2xl font-bold text-red-500 mt-1">
                            {{ $bookings->first()->showtime }}
                        </p>
                    </div>
                    
                    <!-- Ticket Count -->
                    <div class="bg-[#1E1E1E] rounded-xl p-5">
                        <p class="text-gray-400 text-sm mb-2 flex items-center gap-2">
                            <span>🎫</span> Jumlah Tiket
                        </p>
                        <p class="text-4xl font-bold text-red-500">{{ $bookings->count() }}</p>
                        <p class="text-sm text-gray-400 mt-1">Tiket</p>
                    </div>
                    @endif
                </div>

                <!-- Seats -->
                @if(isset($bookings) && $bookings->isNotEmpty())
                <div class="mt-6 bg-[#1E1E1E] rounded-xl p-5">
                    <p class="text-gray-400 text-sm mb-3 flex items-center gap-2">
                        <span>🪑</span> Kursi yang Dipesan
                    </p>
                    <div class="flex flex-wrap gap-3">
                        @foreach($bookings as $booking)
                            <span class="bg-gradient-to-br from-red-600 to-red-700 px-5 py-3 rounded-xl font-bold text-lg shadow-lg">
                                {{ $booking->seat_number }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Total Price -->
                <div class="mt-6 pt-6 border-t border-gray-700">
                    <div class="flex justify-between items-center">
                        <p class="text-gray-400 text-lg">💰 Total Pembayaran</p>
                        <p class="text-4xl font-bold text-red-500">
                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </p>
                    </div>
                    
                    @if($transaction->payment_method)
                    <div class="flex justify-between items-center mt-4">
                        <p class="text-gray-400">💳 Metode Pembayaran</p>
                        <p class="text-lg font-bold capitalize">{{ str_replace('_', ' ', $transaction->payment_method) }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Customer Info Card -->
            <div class="bg-[#2A2A2A] rounded-2xl p-8 mb-8 shadow-2xl">
                <h3 class="text-2xl font-bold mb-6 flex items-center gap-2">
                    <span>👤</span> Informasi Pemesan
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-gray-700">
                        <span class="text-gray-400">Nama</span>
                        <span class="font-bold text-lg">{{ $transaction->customer_name ?? ($transaction->user ? $transaction->user->name : '-') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-700">
                        <span class="text-gray-400">Email</span>
                        <span class="font-semibold">{{ $transaction->customer_email ?? ($transaction->user ? $transaction->user->email : '-') }}</span>
                    </div>
                    @if($transaction->booking_code)
                    <div class="flex justify-between items-center py-3">
                        <span class="text-gray-400">Kode Booking</span>
                        <span class="font-bold text-xl text-blue-400 font-mono">{{ $transaction->booking_code }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-900/30 border-2 border-blue-700 rounded-2xl p-6 mb-8 shadow-xl">
                <h3 class="font-bold text-lg mb-4 text-blue-400 flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informasi Penting
                </h3>
                <ul class="text-sm text-gray-300 space-y-3">
                    <li class="flex items-start gap-2">
                        <span class="text-blue-400 mt-0.5">•</span>
                        <span>Harap datang <strong>15 menit</strong> sebelum jam tayang dimulai</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-400 mt-0.5">•</span>
                        <span>Tunjukkan <strong class="text-yellow-400">Kode Transaksi ({{ $transaction->transaction_code }})</strong> di loket</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-400 mt-0.5">•</span>
                        <span>Simpan screenshot halaman ini sebagai <strong>bukti pemesanan</strong></span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-400 mt-0.5">•</span>
                        <span>Tiket yang sudah dibeli <strong>tidak dapat dikembalikan</strong></span>
                    </li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @if($transaction->status === 'pending')
                <a href="{{ route('transaction.payment', $transaction->booking_code) }}" 
                   class="bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 
                   py-4 rounded-full text-lg font-bold text-center transition-all duration-200 hover:scale-105 shadow-xl
                   flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Bayar Sekarang
                </a>
                @endif
                
                <button onclick="window.print()" 
                   class="bg-gray-700 hover:bg-gray-600 py-4 rounded-full text-lg font-bold 
                   transition-all duration-200 hover:scale-105 shadow-xl
                   flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak Tiket
                </button>
                
                <a href="{{ route('transaction.index') }}" 
                   class="bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 
                   py-4 rounded-full text-lg font-bold text-center transition-all duration-200 hover:scale-105 shadow-xl
                   flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Riwayat Transaksi
                </a>
            </div>
        </div>
    </div>

    @include('layouts.footer')

    <!-- Print Styles -->
    <style>
        @media print {
            nav, footer, button, a[href*="payment"], a[href*="transaction"] {
                display: none !important;
            }
            body {
                background: white;
                color: black;
            }
            .bg-\[\#1E1E1E\], .bg-\[\#2A2A2A\], .bg-blue-900\/30, .bg-gradient-to-r, .bg-gradient-to-br {
                background: white !important;
                border: 1px solid #ddd !important;
                color: black !important;
            }
            .text-white, .text-gray-400, .text-gray-300, .text-blue-400 {
                color: black !important;
            }
            .shadow-xl, .shadow-2xl, .shadow-lg {
                box-shadow: none !important;
            }
            h1, h2, h3 {
                color: black !important;
            }
            .text-red-500, .text-red-600, .text-red-700 {
                color: #dc2626 !important;
            }
        }
    </style>

</body>
</html>