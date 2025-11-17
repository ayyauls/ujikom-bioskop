<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - BioskopKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#1E1E1E] text-white font-sans min-h-screen">

    @include('layouts.navbar')

    <div class="container mx-auto px-6 py-16">
        <div class="max-w-6xl mx-auto">
            
            <!-- Header -->
            <div class="mb-10">
                <h1 class="text-4xl font-bold mb-2">📜 Riwayat Transaksi</h1>
                <p class="text-gray-400">Lihat semua transaksi pembelian tiket Anda</p>
            </div>

            @if($transactions->isEmpty())
                <!-- Empty State -->
                <div class="bg-[#2A2A2A] rounded-2xl p-16 text-center shadow-xl">
                    <div class="inline-block bg-gray-800 rounded-full p-8 mb-6">
                        <svg class="w-24 h-24 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Belum Ada Transaksi</h3>
                    <p class="text-gray-400 mb-8">Anda belum melakukan pembelian tiket</p>
                    <a href="{{ route('home') }}" 
                       class="inline-block bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 
                       px-8 py-3 rounded-full font-bold transition-all duration-200 hover:scale-105">
                        🎬 Pilih Film Sekarang
                    </a>
                </div>
            @else
                <!-- Transaction List -->
                <div class="space-y-6">
                    @foreach($transactions as $transaction)
                    <div class="bg-[#2A2A2A] rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-[1.02]">
                        <div class="p-6">
                            <!-- Transaction Header -->
                            <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-700">
                                <div>
                                    <p class="text-sm text-gray-400 mb-1">Kode Transaksi</p>
                                    <p class="text-xl font-bold text-blue-400">{{ $transaction->transaction_code }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-400 mb-1">Tanggal</p>
                                    <p class="font-semibold">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>

                            <!-- Transaction Info -->
                            <div class="flex gap-6 mb-4">
                                @if($transaction->film)
                                <img src="{{ asset($transaction->film->poster ?? 'images/default.jpg') }}" 
                                     alt="{{ $transaction->film->title }}" 
                                     class="w-24 h-36 object-cover rounded-lg shadow-lg">
                                @endif
                                <div class="flex-1">
                                    <h3 class="text-2xl font-bold mb-2">{{ $transaction->film->title ?? 'Film' }}</h3>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center gap-2">
                                            <span class="text-gray-400">👤</span>
                                            <span>{{ $transaction->customer_name ?? $transaction->user->name }}</span>
                                        </div>
                                        @if($transaction->showtime)
                                        <div class="flex items-center gap-2">
                                            <span class="text-gray-400">🕐</span>
                                            <span>{{ $transaction->showtime }}</span>
                                        </div>
                                        @endif
                                        @if($transaction->seats)
                                        <div class="flex items-center gap-2">
                                            <span class="text-gray-400">🪑</span>
                                            <span>Kursi: {{ is_array($transaction->seats) ? implode(', ', $transaction->seats) : $transaction->seats }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Transaction Footer -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-700">
                                <div>
                                    <p class="text-sm text-gray-400 mb-1">Total Pembayaran</p>
                                    <p class="text-2xl font-bold text-red-500">
                                        Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <!-- Status Badge -->
                                    @if($transaction->status === 'paid')
                                        <span class="px-4 py-2 bg-green-600 text-white rounded-full text-sm font-bold">
                                            ✓ Lunas
                                        </span>
                                    @elseif($transaction->status === 'pending')
                                        <span class="px-4 py-2 bg-yellow-600 text-white rounded-full text-sm font-bold">
                                            ⏳ Pending
                                        </span>
                                    @else
                                        <span class="px-4 py-2 bg-red-700 text-white rounded-full text-sm font-bold">
                                            ✗ {{ ucfirst($transaction->status) }}
                                        </span>
                                    @endif

                                    <!-- Action Button -->
                                    <a href="{{ route('transaction.show', $transaction->transaction_code) }}" 
                                       class="bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 
                                       px-6 py-2 rounded-full font-bold transition-all duration-200 hover:scale-105">
                                        Detail →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination (jika ada) -->
                @if(method_exists($transactions, 'links'))
                <div class="mt-8">
                    {{ $transactions->links() }}
                </div>
                @endif
            @endif
        </div>
    </div>

    @include('layouts.footer')

</body>
</html>