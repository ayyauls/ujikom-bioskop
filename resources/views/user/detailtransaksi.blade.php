<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi - BioskopKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">

    <!-- Container -->
    <div class="max-w-3xl mx-auto mt-10 bg-white shadow-xl rounded-2xl p-8">

        <!-- Judul -->
        <h1 class="text-3xl font-bold text-center mb-6">Detail Transaksi</h1>

        <!-- Detail Transaksi -->
        <div class="bg-gray-100 rounded-lg p-6 mb-6 border border-gray-300">
            <p class="text-gray-700 mb-2">Kode Transaksi: 
                <strong>{{ $transaction->transaction_code }}</strong>
            </p>
            <p class="text-gray-700 mb-2">Kode Booking: 
                <strong>{{ $transaction->booking_code }}</strong>
            </p>
            <p class="text-gray-700 mb-2">Total Pembayaran: 
                <strong>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong>
            </p>
            <p class="text-gray-700 mb-2">Status: 
                <span class="font-semibold 
                    {{ $transaction->status == 'paid' ? 'text-green-600' : 
                       ($transaction->status == 'pending' ? 'text-yellow-600' : 'text-red-600') }}">
                    {{ ucfirst($transaction->status) }}
                </span>
            </p>
            <p class="text-gray-700">Tanggal Transaksi: 
                <strong>{{ $transaction->created_at->format('d M Y H:i') }}</strong>
            </p>
        </div>

        <!-- Detail Booking -->
        <div class="mb-6 border-t border-gray-300 pt-6">
            <h2 class="text-2xl font-bold mb-4">🎬 Detail Booking</h2>
            <p><strong>Film:</strong> {{ $film->title ?? '-' }}</p>
            <p><strong>Jam Tayang:</strong> {{ $bookings->first()->showtime ?? '-' }}</p>
            <p><strong>Kursi:</strong> {{ $bookings->pluck('seat_number')->implode(', ') }}</p>
            <p><strong>Jumlah Tiket:</strong> {{ $bookings->count() }} Tiket</p>
        </div>

        <!-- Tombol Aksi Berdasarkan Status -->
        @if($transaction->status == 'pending')
            <div class="text-center mt-6">
                <a href="{{ route('transaction.payment', $transaction->booking_code) }}"
                    class="bg-green-600 hover:bg-green-500 text-white px-6 py-3 rounded-lg font-bold transition-all duration-200">
                    💳 Lanjutkan Pembayaran
                </a>
            </div>
        @elseif($transaction->status == 'paid')
            <div class="text-center mt-6">
                <span class="bg-green-600 text-white px-6 py-3 rounded-lg font-bold inline-block">
                    ✅ Pembayaran Berhasil
                </span>
            </div>
        @elseif($transaction->status == 'expired')
            <div class="text-center mt-6">
                <span class="bg-gray-500 text-white px-6 py-3 rounded-lg font-bold inline-block">
                    ⏰ Transaksi Kedaluwarsa
                </span>
            </div>
        @elseif($transaction->status == 'failed')
            <div class="text-center mt-6">
                <span class="bg-red-600 text-white px-6 py-3 rounded-lg font-bold inline-block">
                    ❌ Transaksi Gagal
                </span>
            </div>
        @endif

        <!-- Tombol Kembali -->
        <div class="text-center mt-8">
            <a href="{{ route('transaction.index') }}" 
                class="text-blue-600 hover:underline font-semibold">
                ← Kembali ke Riwayat Transaksi
            </a>
        </div>

    </div>

</body>
</html>
