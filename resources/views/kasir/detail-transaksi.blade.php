<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi - {{ $transaction->transaction_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#1E1E1E] text-white font-sans min-h-screen">

    @include('kasir.layouts.nnavbar')

    <div class="container mx-auto px-6 py-8">
        
        <!-- Back Button -->
        <a href="{{ route('kasir.riwayat') }}" 
           class="inline-flex items-center gap-2 mb-6 text-gray-400 hover:text-white transition-colors group">
            <span class="group-hover:-translate-x-1 transition-transform">←</span>
            Kembali ke Riwayat
        </a>

        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-2xl p-6 mb-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm mb-1">Kode Transaksi</p>
                        <h1 class="text-3xl font-bold font-mono">{{ $transaction->transaction_code }}</h1>
                    </div>
                    <div class="text-right">
                        @if($transaction->status == 'success')
                            <span class="px-4 py-2 bg-green-500 text-white text-sm rounded-full font-semibold inline-flex items-center gap-2">
                                <span class="w-2 h-2 bg-green-200 rounded-full animate-pulse"></span>
                                Transaksi Berhasil
                            </span>
                        @else
                            <span class="px-4 py-2 bg-red-500 text-white text-sm rounded-full font-semibold">
                                Transaksi Gagal
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-6">
                
                <!-- Informasi Film -->
                <div class="bg-[#2A2A2A] rounded-2xl p-6 shadow-xl">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2 border-b border-gray-700 pb-3">
                        <span class="text-2xl">🎬</span>
                        Informasi Film
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Judul Film</p>
                            <p class="text-xl font-semibold">{{ $transaction->film->title }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-400 text-sm mb-1">Tanggal Tayang</p>
                                <p class="text-lg font-semibold">{{ $transaction->created_at->format('d F Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm mb-1">Jam Tayang</p>
                                <p class="text-lg font-semibold">{{ $transaction->showtime }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Studio</p>
                            <p class="text-lg font-semibold">Studio 1</p>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Pembayaran -->
                <div class="bg-[#2A2A2A] rounded-2xl p-6 shadow-xl">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2 border-b border-gray-700 pb-3">
                        <span class="text-2xl">💰</span>
                        Ringkasan Pembayaran
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Kode Booking</p>
                            <p class="text-lg font-semibold font-mono text-blue-400">{{ $transaction->transaction_code }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Metode Pembayaran</p>
                            <p class="text-lg font-semibold">
                                @if($transaction->payment_method == 'cash')
                                    <span class="inline-flex items-center gap-2">
                                        <span class="text-2xl">💵</span>
                                        Cash
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2">
                                        <span class="text-2xl">📱</span>
                                        QRIS
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-lg p-4">
                            <p class="text-green-100 text-sm mb-1">Total Harga</p>
                            <p class="text-3xl font-bold">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm mb-2">Status Pembayaran</p>
                            @if($transaction->status == 'success')
                                <span class="inline-block px-4 py-2 bg-green-600 text-white text-sm rounded-lg font-semibold">
                                    ✓ Pembayaran Selesai
                                </span>
                            @else
                                <span class="inline-block px-4 py-2 bg-red-600 text-white text-sm rounded-lg font-semibold">
                                    ✗ Pembayaran Gagal
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-2 gap-6 mb-6">
                
                <!-- Kursi yang Dipesan -->
                <div class="bg-[#2A2A2A] rounded-2xl p-6 shadow-xl">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2 border-b border-gray-700 pb-3">
                        <span class="text-2xl">🪑</span>
                        Kursi yang Dipesan
                    </h2>
                    <div class="bg-[#1E1E1E] rounded-lg p-6">
                        @php
                            $seats = is_array($transaction->seats) ? $transaction->seats : json_decode($transaction->seats, true);
                            if (!is_array($seats)) {
                                $seats = [];
                            }
                        @endphp
                        @if(count($seats) > 0)
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach($seats as $seat)
                                <span class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg text-lg">
                                    {{ $seat }}
                                </span>
                            @endforeach
                        </div>
                        <p class="text-gray-400 text-sm text-center pt-4 border-t border-gray-700">
                            Total: <span class="text-white font-bold">{{ count($seats) }} kursi</span>
                        </p>
                        @else
                        <p class="text-gray-500 text-center">Tidak ada kursi yang dipesan</p>
                        @endif
                    </div>
                </div>

                <!-- Informasi Pembeli -->
                <div class="bg-[#2A2A2A] rounded-2xl p-6 shadow-xl">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2 border-b border-gray-700 pb-3">
                        <span class="text-2xl">👤</span>
                        Informasi Pembeli
                    </h2>
                    <div class="space-y-4">
                        <div class="bg-[#1E1E1E] rounded-lg p-4">
                            <p class="text-gray-400 text-sm mb-1">Nama Lengkap</p>
                            <p class="text-xl font-semibold">{{ $transaction->customer_name ?? $transaction->user->name }}</p>
                        </div>
                        <div class="bg-[#1E1E1E] rounded-lg p-4">
                            <p class="text-gray-400 text-sm mb-1">Email</p>
                            <p class="text-lg font-semibold">{{ $transaction->customer_email ?? $transaction->user->email }}</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Status Transaksi Timeline -->
            <div class="bg-[#2A2A2A] rounded-2xl p-6 shadow-xl">
                <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                    <span class="text-2xl">📊</span>
                    Status Transaksi
                </h2>
                
                <div class="space-y-6">
                    <!-- Transaksi Dibuat -->
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-14 h-14 bg-blue-600 rounded-full flex items-center justify-center text-2xl shadow-lg">
                            📝
                        </div>
                        <div class="flex-1 bg-[#1E1E1E] rounded-lg p-4">
                            <p class="font-bold text-lg mb-1">Transaksi Dibuat</p>
                            <p class="text-gray-400 text-sm">{{ $transaction->created_at->format('d F Y, H:i') }} WIB</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="px-4 py-2 bg-blue-600 text-white text-xs rounded-full font-semibold inline-flex items-center gap-2">
                                <span class="w-2 h-2 bg-blue-300 rounded-full animate-pulse"></span>
                                Completed
                            </span>
                        </div>
                    </div>

                    <!-- Pembayaran Berhasil -->
                    @if($transaction->status == 'success')
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-14 h-14 bg-green-600 rounded-full flex items-center justify-center text-2xl shadow-lg">
                            💳
                        </div>
                        <div class="flex-1 bg-[#1E1E1E] rounded-lg p-4">
                            <p class="font-bold text-lg mb-1">Pembayaran Berhasil</p>
                            <p class="text-gray-400 text-sm">{{ $transaction->created_at->format('d F Y, H:i') }} WIB</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="px-4 py-2 bg-green-600 text-white text-xs rounded-full font-semibold inline-flex items-center gap-2">
                                <span class="w-2 h-2 bg-green-300 rounded-full animate-pulse"></span>
                                Completed
                            </span>
                        </div>
                    </div>

                    <!-- Transaksi Selesai -->
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-14 h-14 bg-green-600 rounded-full flex items-center justify-center text-2xl shadow-lg">
                            ✅
                        </div>
                        <div class="flex-1 bg-[#1E1E1E] rounded-lg p-4">
                            <p class="font-bold text-lg mb-1">Transaksi Selesai</p>
                            <p class="text-gray-400 text-sm">{{ $transaction->created_at->format('d F Y, H:i') }} WIB</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="px-4 py-2 bg-green-600 text-white text-xs rounded-full font-semibold inline-flex items-center gap-2">
                                <span class="w-2 h-2 bg-green-300 rounded-full animate-pulse"></span>
                                Completed
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Action Button -->
            <div class="mt-6 flex gap-4">
                <a href="{{ route('kasir.cetak-tiket', $transaction->id) }}" 
                   target="_blank"
                   class="flex-1 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 py-4 rounded-lg text-center font-bold transition-all shadow-lg hover:shadow-xl">
                    🖨️ Cetak Tiket
                </a>
                <a href="{{ route('kasir.riwayat') }}" 
                   class="px-8 bg-gray-700 hover:bg-gray-600 py-4 rounded-lg font-bold transition-all">
                    Kembali
                </a>
            </div>
        </div>
    </div>

</body>
</html>