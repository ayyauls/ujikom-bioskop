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
               class="inline-flex items-center gap-2 text-gray-400 hover:text-white mb-8 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>

            <!-- Header -->
            <div class="text-center mb-10">
                <div class="inline-block bg-gradient-to-r from-red-600 to-red-500 rounded-full p-6 mb-4">
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold mb-2">📋 Detail Transaksi</h1>
                <p class="text-gray-400">{{ $transaction->transaction_code }}</p>
            </div>

            <!-- Status Card -->
            <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-2xl p-6 mb-8 text-center shadow-xl">
                <p class="text-sm text-gray-200 mb-2">Status Pembayaran</p>
                @if($transaction->status === 'paid')
                    <p class="text-4xl font-bold tracking-wider mb-2">✓ LUNAS</p>
                    <p class="text-xs text-gray-200">Pembayaran berhasil pada {{ $transaction->paid_at?->format('d M Y, H:i') ?? '-' }}</p>
                @elseif($transaction->status === 'pending')
                    <p class="text-4xl font-bold tracking-wider mb-2">⏳ PENDING</p>
                    <p class="text-xs text-gray-200">Menunggu pembayaran</p>
                @else
                    <p class="text-4xl font-bold tracking-wider mb-2">{{ strtoupper($transaction->status) }}</p>
                @endif
            </div>

            <!-- Film Info -->
            @if($film)
            <div class="bg-[#2A2A2A] rounded-2xl p-8 mb-8 shadow-xl">
                <div class="flex gap-6 mb-6 pb-6 border-b border-gray-700">
                    <img src="{{ asset($film->poster) }}" alt="{{ $film->title }}" 
                         class="w-32 h-48 object-cover rounded-lg shadow-lg">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold mb-4">{{ $film->title }}</h2>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="text-gray-400">🎭 Genre:</span>
                                <span class="font-semibold">{{ $film->genre }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-400">⏱️ Durasi:</span>
                                <span class="font-semibold">{{ $film->duration }} menit</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-400">🔞 Rating:</span>
                                <span class="font-semibold">{{ $film->rating }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Details -->
                <div class="space-y-4">
                    @if($bookings && $bookings->isNotEmpty())
                    <div>
                        <p class="text-gray-400 text-sm mb-1">📅 Tanggal & Waktu</p>
                        <p class="text-xl font-bold">
                            {{ \Carbon\Carbon::parse($bookings->first()->booking_date)->format('d M Y') }} 
                            - {{ $bookings->first()->showtime }}
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-gray-400 text-sm mb-1">🪑 Kursi yang Dipesan</p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($bookings as $booking)
                                <span class="bg-red-600 px-4 py-2 rounded-full font-bold">
                                    {{ $booking->seat_number }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-gray-400 text-sm mb-1">🎫 Jumlah Tiket</p>
                        <p class="text-xl font-bold">{{ $bookings->count() }} Tiket</p>
                    </div>
                    @endif
                    
                    <div class="pt-4 border-t border-gray-700">
                        <p class="text-gray-400 text-sm mb-1">💰 Total Pembayaran</p>
                        <p class="text-3xl font-bold text-red-500">
                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </p>
                    </div>

                    @if($transaction->payment_method)
                    <div>
                        <p class="text-gray-400 text-sm mb-1">💳 Metode Pembayaran</p>
                        <p class="text-lg font-bold capitalize">{{ $transaction->payment_method }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Customer Info -->
            <div class="bg-[#2A2A2A] rounded-2xl p-8 mb-8 shadow-xl">
                <h3 class="text-xl font-bold mb-4">👤 Informasi Pemesan</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Nama:</span>
                        <span class="font-semibold">{{ $transaction->customer_name ?? $transaction->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Email:</span>
                        <span class="font-semibold">{{ $transaction->customer_email ?? $transaction->user->email }}</span>
                    </div>
                    @if($transaction->booking_code)
                    <div class="flex justify-between">
                        <span class="text-gray-400">Kode Booking:</span>
                        <span class="font-bold text-blue-400">{{ $transaction->booking_code }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-900/30 border border-blue-700 rounded-xl p-6 mb-8">
                <h3 class="font-bold mb-2 text-blue-400">ℹ️ Informasi Penting:</h3>
                <ul class="text-sm text-gray-300 space-y-2">
                    <li>• Harap datang 15 menit sebelum jam tayang dimulai</li>
                    <li>• Tunjukkan <strong>Kode Transaksi ({{ $transaction->transaction_code }})</strong> di loket</li>
                    <li>• Simpan screenshot halaman ini sebagai bukti pemesanan</li>
                    <li>• Tiket yang sudah dibeli tidak dapat dikembalikan</li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 flex-col sm:flex-row">
                @if($transaction->status === 'pending')
                <a href="{{ route('transaction.payment', $transaction->booking_code) }}" 
                   class="flex-1 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 
                   py-4 rounded-full text-lg font-bold text-center transition-all duration-200 hover:scale-105">
                    💳 Bayar Sekarang
                </a>
                @endif
                
                <button onclick="window.print()" 
                   class="flex-1 bg-gray-700 hover:bg-gray-600 py-4 rounded-full text-lg font-bold 
                   transition-all duration-200 hover:scale-105">
                    🖨️ Cetak Tiket
                </button>
                
                <a href="{{ route('transaction.index') }}" 
                   class="flex-1 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 
                   py-4 rounded-full text-lg font-bold text-center transition-all duration-200 hover:scale-105">
                    📜 Riwayat Transaksi
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
            .bg-\[\#2A2A2A\], .bg-blue-900\/30, .bg-gradient-to-r {
                background: white !important;
                border: 1px solid #ddd;
            }
        }
    </style>

</body>
</html>