<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - BioskopKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

    <div class="max-w-5xl mx-auto mt-10 bg-white rounded-2xl shadow-xl p-8">
        <h1 class="text-3xl font-bold text-center mb-8">💳 Riwayat Transaksi Anda</h1>

        @if($transactions->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">Belum ada transaksi yang dilakukan.</p>
                <a href="/" class="text-blue-600 hover:underline mt-4 inline-block">🎬 Lihat Film Sekarang</a>
            </div>
        @else
            <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left px-4 py-3 border-b">Kode Transaksi</th>
                        <th class="text-left px-4 py-3 border-b">Kode Booking</th>
                        <th class="text-left px-4 py-3 border-b">Total</th>
                        <th class="text-left px-4 py-3 border-b">Status</th>
                        <th class="text-left px-4 py-3 border-b">Tanggal</th>
                        <th class="text-center px-4 py-3 border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $trx)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 border-b font-semibold">{{ $trx->transaction_code }}</td>
                            <td class="px-4 py-3 border-b">{{ $trx->booking_code }}</td>
                            <td class="px-4 py-3 border-b">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 border-b">
                                <span class="font-semibold 
                                    {{ $trx->status == 'paid' ? 'text-green-600' : 
                                       ($trx->status == 'pending' ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ ucfirst($trx->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 border-b">{{ $trx->created_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3 border-b text-center">
                                <a href="{{ route('transaction.show', $trx->transaction_code) }}"
                                   class="text-blue-600 hover:underline font-semibold">
                                    🔍 Lihat
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="text-center mt-8">
            <a href="/" class="text-blue-600 hover:underline">← Kembali ke Beranda</a>
        </div>
    </div>

</body>
</html>
