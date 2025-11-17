@extends('kasir.layouts.app')

@section('title', 'Pembayaran QRIS')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-qrcode"></i> Pembayaran QRIS</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <h5 class="text-primary">{{ $transaction->film->title }}</h5>
                        <p class="mb-1"><strong>Kursi:</strong> {{ implode(', ', $transaction->seats) }}</p>
                        <p class="mb-1"><strong>Jadwal:</strong> {{ $transaction->showtime }}</p>
                        <p class="mb-1"><strong>Customer:</strong> {{ $transaction->customer_name }}</p>
                        <hr>
                        <h3 class="text-success">Total: Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</h3>
                    </div>
                    
                    <button id="pay-button" class="btn btn-success btn-lg px-5">
                        <i class="fas fa-mobile-alt"></i> Bayar Sekarang
                    </button>
                    
                    <div class="mt-3">
                        <a href="{{ route('kasir.pilih-kursi', $transaction->film_id) }}?schedule_id={{ request('schedule_id') }}" 
                           class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const payButton = document.getElementById('pay-button');
    
    payButton.onclick = function(){
        console.log('Snap Token:', '{{ $snapToken }}');
        
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                window.location.href = '{{ route("kasir.payment.success", $transaction->id) }}';
            },
            onPending: function(result){
                alert('Pembayaran sedang diproses...');
                window.location.href = '{{ route("kasir.riwayat") }}';
            },
            onError: function(result){
                alert('Pembayaran gagal!');
            },
            onClose: function(){
                window.location.href = '{{ route("kasir.riwayat") }}';
            }
        });
    };
});
</script>
@endsection