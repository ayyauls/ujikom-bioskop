<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - BioskopKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>
<body class="bg-[#1E1E1E] text-white font-sans min-h-screen">

    @include('layouts.navbar')

    <div class="container mx-auto px-6 py-16">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-block bg-blue-600 rounded-full p-6 mb-4">
                    <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold mb-2">💳 Pembayaran</h1>
                <p class="text-gray-400">Silakan selesaikan pembayaran Anda</p>
            </div>

            <!-- Detail Transaksi -->
            <div class="bg-[#2A2A2A] rounded-2xl p-8 mb-8 shadow-xl">
                <div class="space-y-4">
                    <div class="flex justify-between pb-4 border-b border-gray-700">
                        <span class="text-gray-400">Kode Transaksi:</span>
                        <span class="text-xl font-bold text-green-400">{{ $transaction->transaction_code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Kode Booking:</span>
                        <span class="font-bold">{{ $transaction->booking_code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Status:</span>
                        <span class="font-bold capitalize text-yellow-400">{{ $transaction->status }}</span>
                    </div>
                    <div class="flex justify-between pt-4 border-t border-gray-700">
                        <span class="text-xl text-gray-400">Total Pembayaran:</span>
                        <span class="text-3xl font-bold text-green-400">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Tombol Bayar -->
            <div class="text-center">
                <button id="pay-button" 
                    class="w-full bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 
                    py-5 rounded-2xl text-xl font-bold shadow-lg transition-all duration-200 hover:scale-105 flex items-center justify-center gap-3">
                    <span>💳</span> Bayar Sekarang
                </button>
                <p class="text-gray-500 text-sm mt-4">
                    Dengan klik tombol di atas, popup pembayaran Midtrans akan muncul
                </p>
            </div>

            <!-- Loading -->
            <div id="loading" class="hidden text-center mt-8">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-green-500"></div>
                <p class="text-gray-400 mt-4">Memproses pembayaran...</p>
            </div>
        </div>
    </div>

    @include('layouts.footer')

    <script type="text/javascript">
        const payButton = document.getElementById('pay-button');
        const loading = document.getElementById('loading');

        payButton.onclick = function() {
            loading.classList.remove('hidden');
            payButton.disabled = true;
            payButton.classList.add('opacity-50', 'cursor-not-allowed');

            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    fetch('{{ route("transaction.update-status") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order_id: '{{ $transaction->transaction_code }}',
                            transaction_status: 'settlement',
                        })
                    });

                    alert('✅ Pembayaran berhasil!');
                    window.location.href = "{{ route('transaction.index') }}";
                },
                onPending: function(result){
                    alert('⏳ Menunggu pembayaran Anda...');
                    window.location.href = "{{ route('transaction.index') }}";
                },
                onError: function(result){
                    alert('❌ Pembayaran gagal! Silakan coba lagi.');
                    loading.classList.add('hidden');
                    payButton.disabled = false;
                    payButton.classList.remove('opacity-50', 'cursor-not-allowed');
                },
                onClose: function(){
                    alert('⚠️ Anda menutup popup pembayaran.');
                    loading.classList.add('hidden');
                    payButton.disabled = false;
                    payButton.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            });
        };

        // Auto buka popup setelah 1 detik
        window.onload = function() {
            setTimeout(() => payButton.click(), 1000);
        };

        // Handle callback dari Midtrans
        window.addEventListener('message', function(event) {
            if (event.origin !== 'https://app.sandbox.midtrans.com' && event.origin !== 'https://app.midtrans.com') {
                return;
            }

            if (event.data && event.data.type === 'payment_success') {
                fetch('{{ route("transaction.update-status") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        order_id: '{{ $transaction->transaction_code }}',
                        transaction_status: 'settlement',
                    })
                }).then(() => {
                    alert('✅ Pembayaran berhasil!');
                    window.location.href = "{{ route('transaction.index') }}";
                });
            }
        });
    </script>

</body>
</html>
