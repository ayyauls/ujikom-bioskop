<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - BioskopKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#1E1E1E] text-white font-sans min-h-screen">

    @include('kasir.layouts.nnavbar')

    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-2">📜 Riwayat Transaksi</h1>
            <p class="text-gray-400">Daftar semua transaksi pemesanan tiket</p>
        </div>

        <!-- Filter -->
        <div class="bg-[#2A2A2A] rounded-2xl p-6 shadow-xl mb-6">
            <form action="{{ route('kasir.riwayat') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="text-gray-400 text-sm mb-2 block font-semibold">📅 Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" 
                           class="w-full bg-[#1E1E1E] rounded-lg p-3 text-white border border-gray-700 focus:border-red-600 focus:outline-none transition-colors">
                </div>
                <div>
                    <label class="text-gray-400 text-sm mb-2 block font-semibold">📅 Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" 
                           class="w-full bg-[#1E1E1E] rounded-lg p-3 text-white border border-gray-700 focus:border-red-600 focus:outline-none transition-colors">
                </div>
                <div class="md:col-span-2 flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 py-3 rounded-lg font-bold transition-all shadow-lg hover:shadow-xl">
                        🔍 Filter Data
                    </button>
                    <a href="{{ route('kasir.riwayat') }}" 
                       class="px-6 py-3 bg-gray-700 hover:bg-gray-600 rounded-lg font-bold transition-all">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Transactions Table -->
        <div class="bg-[#2A2A2A] rounded-2xl shadow-xl overflow-hidden">
            @if($transactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-800">
                        <tr>
                            <th class="text-left py-4 px-4 text-gray-400 font-semibold text-sm">ID</th>
                            <th class="text-left py-4 px-4 text-gray-400 font-semibold text-sm">Kode Tiket</th>
                            <th class="text-left py-4 px-4 text-gray-400 font-semibold text-sm">User</th>
                            <th class="text-left py-4 px-4 text-gray-400 font-semibold text-sm">Email</th>
                            <th class="text-left py-4 px-4 text-gray-400 font-semibold text-sm">Film</th>
                            <th class="text-left py-4 px-4 text-gray-400 font-semibold text-sm">Tanggal & Jam</th>
                            <th class="text-left py-4 px-4 text-gray-400 font-semibold text-sm">Total</th>
                            <th class="text-left py-4 px-4 text-gray-400 font-semibold text-sm">Metode</th>
                            <th class="text-left py-4 px-4 text-gray-400 font-semibold text-sm">Status</th>
                            <th class="text-left py-4 px-4 text-gray-400 font-semibold text-sm">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr class="border-b border-gray-800 hover:bg-gray-800 transition-colors">
                            <td class="py-4 px-4 text-sm font-mono text-gray-400">#{{ $transaction->id }}</td>
                            <td class="py-4 px-4 text-sm">
                                <span class="font-mono text-blue-400 font-semibold">{{ $transaction->transaction_code }}</span>
                            </td>
                            <td class="py-4 px-4 text-sm font-semibold">{{ $transaction->customer_name ?? $transaction->user->name }}</td>
                            <td class="py-4 px-4 text-sm text-gray-400">{{ Str::limit($transaction->customer_email ?? $transaction->user->email, 20) }}</td>
                            <td class="py-4 px-4 text-sm">
                                <div class="font-semibold">{{ $transaction->film->title }}</div>
                            </td>
                            <td class="py-4 px-4 text-sm">
                                <div class="font-semibold">{{ $transaction->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $transaction->showtime }}</div>
                            </td>
                            <td class="py-4 px-4 text-sm font-bold text-green-500">
                                Rp {{ number_format($transaction->total_price ?? $transaction->total_amount ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-4 text-sm">
                                @if($transaction->payment_method == 'cash')
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
                                        ✗ Gagal
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('kasir.detail-transaksi', $transaction->id) }}" 
                                       class="px-3 py-1 bg-blue-600 hover:bg-blue-700 rounded text-xs font-semibold transition-all">
                                        Detail
                                    </a>
                                    @if($transaction->status == 'pending')
                                        <a href="{{ route('kasir.payment', $transaction->id) }}" 
                                           class="px-3 py-1 bg-orange-600 hover:bg-orange-700 rounded text-xs font-semibold transition-all">
                                            Bayar
                                        </a>
                                        <form method="POST" action="{{ route('kasir.mark-paid', $transaction->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-3 py-1 bg-green-600 hover:bg-green-700 rounded text-xs font-semibold transition-all"
                                                    onclick="return confirm('Tandai sebagai sudah dibayar?')">
                                                Paid
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('kasir.mark-failed', $transaction->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-3 py-1 bg-red-600 hover:bg-red-700 rounded text-xs font-semibold transition-all"
                                                    onclick="return confirm('Tandai sebagai gagal? Kursi akan tersedia kembali.')">
                                                Gagal
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-6 border-t border-gray-700">
                {{ $transactions->links() }}
            </div>
            @else
            <div class="text-center py-20">
                <div class="text-8xl mb-4 opacity-50">📭</div>
                <p class="text-gray-400 text-xl font-semibold mb-2">Tidak Ada Transaksi Ditemukan</p>
                <p class="text-gray-500 text-sm">Coba ubah filter tanggal atau buat transaksi baru</p>
            </div>
            @endif
        </div>
    </div>

</body>
</html>