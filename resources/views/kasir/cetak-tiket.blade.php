<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Tiket - {{ $transaction->transaction_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 20px;
            }
            .no-print {
                display: none !important;
            }
            .ticket-container {
                page-break-inside: avoid;
                page-break-after: always;
            }
            .ticket-container:last-child {
                page-break-after: auto;
            }
        }
        @page {
            size: A4;
            margin: 0;
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Print Button -->
    <div class="no-print fixed top-4 right-4 z-50 flex gap-2">
        <button onclick="window.print()" 
                class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-bold shadow-lg transition-all flex items-center gap-2">
            🖨️ Print Tiket
        </button>
        <button onclick="window.close()" 
                class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-bold shadow-lg transition-all flex items-center gap-2">
            ✕ Tutup
        </button>
    </div>

    <div class="container mx-auto px-4 py-8">
        @php
            // Pastikan seats adalah array
            $seats = [];
            
            if (isset($transaction->seats)) {
                if (is_array($transaction->seats)) {
                    $seats = $transaction->seats;
                } elseif (is_string($transaction->seats)) {
                    $decoded = json_decode($transaction->seats, true);
                    $seats = is_array($decoded) ? $decoded : [];
                }
            }
            
            // Jika tidak ada seats di transaction, ambil dari bookings
            if (empty($seats)) {
                $bookings = \App\Models\Booking::where('booking_code', $transaction->booking_code)->get();
                $seats = $bookings->pluck('seat_number')->toArray();
            }
        @endphp

        @if(count($seats) > 0)
            @foreach($seats as $index => $seat)
            <div class="ticket-container mb-8 bg-white rounded-2xl shadow-2xl overflow-hidden max-w-2xl mx-auto">
                <!-- Header -->
                <div class="bg-gradient-to-r from-red-600 to-red-700 text-white p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold">🎬 BioskopKu</h1>
                            <p class="text-red-100 text-sm mt-1">E-Ticket Bioskop</p>
                        </div>
                        <div class="text-right">
                            <p class="text-red-100 text-sm">Kode Booking</p>
                            <p class="text-2xl font-bold font-mono">{{ $transaction->transaction_code }}</p>
                        </div>
                    </div>
                </div>

                <!-- Barcode/QR Placeholder -->
                <div class="bg-gray-50 py-6 flex justify-center border-b-2 border-dashed border-gray-300">
                    <div class="bg-white p-4 rounded-lg shadow-md">
                        <div class="w-48 h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                            <div class="text-center">
                                <p class="text-6xl mb-2">🎫</p>
                                <p class="text-xs text-gray-600 font-mono">{{ $transaction->transaction_code }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ticket Details -->
                <div class="p-8">
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <!-- Left Column -->
                        <div>
                            <div class="mb-4">
                                <p class="text-gray-500 text-sm mb-1 font-semibold">🎬 Film</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $transaction->film ? $transaction->film->title : 'N/A' }}</p>
                            </div>
                            <div class="mb-4">
                                <p class="text-gray-500 text-sm mb-1 font-semibold">📅 Tanggal</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $transaction->created_at->format('d F Y') }}</p>
                            </div>
                            <div class="mb-4">
                                <p class="text-gray-500 text-sm mb-1 font-semibold">🕐 Jam Tayang</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $transaction->showtime ?? '-' }} WIB</p>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div>
                            <div class="mb-4">
                                <p class="text-gray-500 text-sm mb-1 font-semibold">🏢 Studio</p>
                                <p class="text-lg font-semibold text-gray-900">Studio 1</p>
                            </div>
                            <div class="mb-4">
                                <p class="text-gray-500 text-sm mb-1 font-semibold">🪑 Kursi</p>
                                <p class="text-4xl font-bold text-red-600">{{ $seat }}</p>
                            </div>
                            <div class="mb-4">
                                <p class="text-gray-500 text-sm mb-1 font-semibold">⭐ Rating</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $transaction->film ? $transaction->film->rating : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="border-t-2 border-dashed border-gray-300 pt-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <p class="text-gray-500 text-sm mb-1 font-semibold">👤 Nama</p>
                                <p class="font-semibold text-gray-900">{{ $transaction->customer_name ?? ($transaction->user ? $transaction->user->name : 'Guest') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm mb-1 font-semibold">💳 Metode Pembayaran</p>
                                <p class="font-semibold text-gray-900">
                                    @if($transaction->payment_method == 'cash')
                                        💵 Cash
                                    @elseif($transaction->payment_method == 'qris')
                                        📱 QRIS
                                    @else
                                        💳 {{ ucfirst($transaction->payment_method ?? 'N/A') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="mt-6 bg-green-50 border-2 border-green-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <p class="text-green-800 font-semibold">Harga Tiket</p>
                            <p class="text-2xl font-bold text-green-600">Rp 50.000</p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between text-xs text-gray-600 mb-2">
                        <p>Tiket {{ $index + 1 }} dari {{ count($seats) }}</p>
                        <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                    <p class="text-xs text-gray-500 text-center">
                        Harap tunjukkan tiket ini di pintu masuk • Datang 15 menit sebelum film dimulai
                    </p>
                </div>
            </div>
            @endforeach
        @else
            <!-- Jika tidak ada seats -->
            <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-2xl p-12 text-center">
                <div class="text-6xl mb-4">⚠️</div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Tidak Ada Data Kursi</h2>
                <p class="text-gray-600 mb-6">Tidak dapat menemukan informasi kursi untuk transaksi ini.</p>
                <button onclick="window.close()" 
                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold transition-all">
                    Tutup Halaman
                </button>
            </div>
        @endif

        <!-- Additional Info (No Print) -->
        @if(count($seats) > 0)
        <div class="no-print text-center mt-8 text-gray-600">
            <p class="text-sm font-semibold">Total {{ count($seats) }} tiket akan dicetak</p>
            <p class="text-xs mt-2">Klik tombol Print di atas untuk mencetak semua tiket</p>
        </div>
        @endif
    </div>

</body>
</html>