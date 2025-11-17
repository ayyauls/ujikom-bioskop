<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir - BioskopKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#1E1E1E] text-white font-sans min-h-screen">

    @include('kasir.layouts.nnavbar')

    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-2">📊 Dashboard Kasir</h1>
            <p class="text-gray-400">Ringkasan aktivitas hari ini - {{ now()->format('d F Y') }}</p>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <a href="{{ route('kasir.pesan-tiket') }}" 
               class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 rounded-2xl p-6 shadow-xl transition-all transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm mb-1">Buat Pesanan Baru</p>
                        <h3 class="text-2xl font-bold">Pesan Tiket</h3>
                    </div>
                    <div class="text-5xl opacity-80">🎫</div>
                </div>
            </a>

            <a href="{{ route('kasir.riwayat') }}" 
               class="bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 rounded-2xl p-6 shadow-xl transition-all transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm mb-1">Lihat Semua Transaksi</p>
                        <h3 class="text-2xl font-bold">Riwayat Transaksi</h3>
                    </div>
                    <div class="text-5xl opacity-80">📜</div>
                </div>
            </a>

            <a href="{{ route('kasir.laporan') }}" 
               class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 rounded-2xl p-6 shadow-xl transition-all transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm mb-1">Analisis Pendapatan</p>
                        <h3 class="text-2xl font-bold">Laporan Keuangan</h3>
                    </div>
                    <div class="text-5xl opacity-80">📈</div>
                </div>
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Transaksi Hari Ini -->
            <div class="bg-[#2A2A2A] rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm mb-2">Transaksi Hari Ini</p>
                        <p class="text-4xl font-bold">{{ $todayTransactions }}</p>
                        <p class="text-gray-500 text-xs mt-2">Total transaksi</p>
                    </div>
                    <div class="text-5xl opacity-50">💰</div>
                </div>
            </div>

            <!-- Tiket Terjual -->
            <div class="bg-[#2A2A2A] rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm mb-2">Tiket Terjual</p>
                        <p class="text-4xl font-bold text-blue-400">{{ $todayTickets }}</p>
                        <p class="text-gray-500 text-xs mt-2">Kursi terisi</p>
                    </div>
                    <div class="text-5xl opacity-50">🎫</div>
                </div>
            </div>

            <!-- Pendapatan Hari Ini -->
            <div class="bg-[#2A2A2A] rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm mb-2">Pendapatan Hari Ini</p>
                        <p class="text-2xl font-bold text-green-400">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                        <p class="text-gray-500 text-xs mt-2">Total pemasukan</p>
                    </div>
                    <div class="text-5xl opacity-50">💵</div>
                </div>
            </div>

            <!-- Menunggu Pembayaran -->
            <div class="bg-[#2A2A2A] rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm mb-2">Menunggu Pembayaran</p>
                        <p class="text-4xl font-bold text-orange-400">{{ $pendingPayments }}</p>
                        <p class="text-gray-500 text-xs mt-2">Status pending</p>
                    </div>
                    <div class="text-5xl opacity-50">⏳</div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-[#2A2A2A] rounded-2xl shadow-xl overflow-hidden">
            <div class="p-6 border-b border-gray-700 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold">📋 Transaksi Terbaru Hari Ini</h2>
                    <p class="text-gray-400 text-sm mt-1">10 transaksi terakhir</p>
                </div>
                <a href="{{ route('kasir.riwayat') }}" 
                   class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-sm font-semibold transition-all">
                    Lihat Semua
                </a>
            </div>
            
            @if($recentTransactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-800">
                        <tr>
                            <th class="text-left py-4 px-6 text-gray-400 font-semibold text-sm">ID</th>
                            <th class="text-left py-4 px-6 text-gray-400 font-semibold text-sm">Customer</th>
                            <th class="text-left py-4 px-6 text-gray-400 font-semibold text-sm">Film</th>
                            <th class="text-left py-4 px-6 text-gray-400 font-semibold text-sm">Studio</th>
                            <th class="text-left py-4 px-6 text-gray-400 font-semibold text-sm">Total</th>
                            <th class="text-left py-4 px-6 text-gray-400 font-semibold text-sm">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTransactions as $transaction)
                        <tr class="border-b border-gray-800 hover:bg-gray-800 transition-colors cursor-pointer"
                            onclick="window.location='{{ route('kasir.detail-transaksi', $transaction->id) }}'">
                            <td class="py-4 px-6 text-sm font-mono text-gray-400">#{{ $transaction->id }}</td>
                            <td class="py-4 px-6 text-sm font-semibold">{{ $transaction->customer_name ?? ($transaction->user ? $transaction->user->name : 'Guest') }}</td>
                            <td class="py-4 px-6 text-sm">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold">{{ $transaction->film ? $transaction->film->title : 'N/A' }}</span>
                                    <span class="text-xs text-gray-500">{{ $transaction->showtime ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-400">Studio 1</td>
                            <td class="py-4 px-6 text-sm font-bold text-green-500">Rp {{ number_format($transaction->total_price ?? 0, 0, ',', '.') }}</td>
                            <td class="py-4 px-6">
                                @if($transaction->status == 'paid')
                                    <span class="px-3 py-1 bg-green-600 text-white text-xs rounded-full font-semibold inline-flex items-center gap-1">
                                        <span class="w-2 h-2 bg-green-300 rounded-full animate-pulse"></span>
                                        Selesai
                                    </span>
                                @elseif($transaction->status == 'pending')
                                    <span class="px-3 py-1 bg-orange-600 text-white text-xs rounded-full font-semibold inline-flex items-center gap-1">
                                        <span class="w-2 h-2 bg-orange-300 rounded-full animate-pulse"></span>
                                        Pending
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-600 text-white text-xs rounded-full font-semibold">
                                        Gagal
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-20">
                <div class="text-8xl mb-4 opacity-50">📭</div>
                <p class="text-gray-400 text-xl font-semibold mb-2">Belum Ada Transaksi Hari Ini</p>
                <p class="text-gray-500 text-sm mb-6">Mulai buat pesanan tiket untuk pelanggan</p>
                <a href="{{ route('kasir.pesan-tiket') }}" 
                   class="inline-block px-6 py-3 bg-red-600 hover:bg-red-700 rounded-lg font-semibold transition-all">
                    🎫 Pesan Tiket Sekarang
                </a>
            </div>
            @endif
        </div>
    </div>

</body>
</html>