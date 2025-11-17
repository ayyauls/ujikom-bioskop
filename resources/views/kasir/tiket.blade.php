<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Terjual - BioskopKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#1E1E1E] text-white font-sans min-h-screen">

    @include('kasir.layouts.nnavbar')

    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-2">🎟️ Tiket Terjual Hari Ini</h1>
            <p class="text-gray-400">{{ now()->format('l, d F Y') }}</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-600 text-white shadow-lg flex items-center gap-3 animate-pulse">
            <span class="text-2xl">✓</span>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
        @endif

        <!-- Tickets Table -->
        <div class="bg-[#2A2A2A] rounded-2xl shadow-xl overflow-hidden">
            @if($tickets->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-800">
                        <tr>
                            <th class="text-left py-4 px-4 text-gray-400 font-semibold text-sm">Kode Tiket</th>
                            <th class="text-left py-4 px-4 text-gray-400 font-semibold text-sm">Customer</th>
                            <th class="text-left py-4 px-4 text-gray-400 font-semibold text-sm">Film</th>
                            <th class="text-left py-4 px-4 text-gray-400 font-semibold text-sm">Studio</th>
                            <th class="text-left py-4 px-4 text-gray-400 font-semibold text-sm">Kursi</th>
                            <th class="text-left py-4 px-4 text-gray-400 font-semibold text-sm">Status</th>
                            <th class="text-left py-4 px-4 text-gray-400 font-semibold text-sm">Metode</th>
                            <th class="text-left py-4 px-4 text-gray-400 font-semibold text-sm">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                        @php
                            $seats = is_array($ticket->seats) ? $ticket->seats : json_decode($ticket->seats, true);
                            if (!is_array($seats)) {
                                $seats = [];
                            }
                        @endphp
                        <tr class="border-b border-gray-800 hover:bg-gray-800 transition-colors">
                            <td class="py-4 px-4 text-sm font-mono text-blue-400 font-semibold">
                                {{ $ticket->transaction_code }}
                            </td>
                            <td class="py-4 px-4 text-sm font-semibold">
                                {{ $ticket->customer_name ?? ($ticket->user ? $ticket->user->name : 'Walk-in Customer') }}
                            </td>
                            <td class="py-4 px-4 text-sm">
                                <div class="font-semibold">{{ $ticket->film->title ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-400">{{ $ticket->showtime ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-4 text-sm text-gray-400">Studio 1</td>
                            <td class="py-4 px-4 text-sm">
                                <div class="flex flex-wrap gap-1">
                                    @if(count($seats) > 0)
                                        @foreach($seats as $seat)
                                            <span class="px-2 py-1 bg-blue-600 text-white text-xs rounded font-semibold">
                                                {{ $seat }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-gray-500 text-xs">Tidak ada kursi</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                @if($ticket->status == 'paid')
                                    <span class="px-3 py-1 bg-green-600 text-white text-xs rounded-full font-semibold inline-flex items-center gap-1">
                                        <span class="w-2 h-2 bg-green-300 rounded-full animate-pulse"></span>
                                        Selesai
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-600 text-white text-xs rounded-full font-semibold">
                                        ✗ Gagal
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-sm">
                                @if($ticket->payment_method == 'cash')
                                    <span class="px-3 py-1 bg-blue-600 text-white text-xs rounded-full font-semibold inline-flex items-center gap-1">
                                        💵 Cash
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-purple-600 text-white text-xs rounded-full font-semibold inline-flex items-center gap-1">
                                        📱 QRIS
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <a href="{{ route('kasir.cetak-tiket', $ticket->id) }}" 
                                   target="_blank"
                                   class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg text-xs font-semibold transition-all inline-flex items-center gap-2">
                                    🖨️ Cetak
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-20">
                <div class="text-8xl mb-4 opacity-50">🎫</div>
                <p class="text-gray-400 text-xl font-semibold mb-2">Tidak Ada Tiket Terjual Hari Ini</p>
                <p class="text-gray-500 text-sm mb-6">Mulai buat pesanan tiket untuk pelanggan</p>
                <a href="{{ route('kasir.pesan-tiket') }}" 
                   class="inline-block px-6 py-3 bg-red-600 hover:bg-red-700 rounded-lg font-semibold transition-all">
                    🎫 Pesan Tiket Sekarang
                </a>
            </div>
            @endif
        </div>

        <!-- Summary -->
        @if($tickets->count() > 0)
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-[#2A2A2A] rounded-xl p-6 shadow-xl">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-3xl">📊</span>
                    <p class="text-gray-400 text-sm font-semibold">Total Transaksi</p>
                </div>
                <p class="text-4xl font-bold">{{ $tickets->count() }}</p>
            </div>
            <div class="bg-[#2A2A2A] rounded-xl p-6 shadow-xl">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-3xl">🪑</span>
                    <p class="text-gray-400 text-sm font-semibold">Total Kursi Terjual</p>
                </div>
                <p class="text-4xl font-bold text-blue-400">
                    {{ $tickets->sum(function($ticket) {
                        $seats = is_array($ticket->seats) ? $ticket->seats : json_decode($ticket->seats);
                        return count($seats);
                    }) }}
                </p>
            </div>
            <div class="bg-[#2A2A2A] rounded-xl p-6 shadow-xl">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-3xl">💰</span>
                    <p class="text-gray-400 text-sm font-semibold">Total Pendapatan</p>
                </div>
                <p class="text-3xl font-bold text-green-400">
                    Rp {{ number_format($tickets->where('status', 'paid')->sum(function($ticket) { return $ticket->total_price ?? $ticket->total_amount ?? 0; }), 0, ',', '.') }}
                </p>
            </div>
        </div>
        @endif
    </div>

</body>
</html>