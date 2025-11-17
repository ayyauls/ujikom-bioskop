@extends('layouts.owner')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Riwayat Transaksi</h2>
         <script src="https://cdn.tailwindcss.com"></script>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($transactions as $trx)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $trx->transaction_code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $trx->user?->name ?? 'Guest User' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $trx->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $trx->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($trx->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-red-100 text-red-800') }}">
                                {{ ucfirst($trx->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($transactions->isEmpty())
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="mt-2 text-gray-500">Tidak ada riwayat transaksi.</p>
        </div>
        @endif

        <!-- Pagination -->
        @if($transactions->hasPages())
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection