<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - BioskopKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#1E1E1E] text-white font-sans min-h-screen">

   @include('kasir.layouts.nnavbar') 

    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-2">📈 Laporan Keuangan</h1>
            <p class="text-gray-400">Ringkasan pendapatan dan transaksi</p>
        </div>

        <!-- Filter -->
        <div class="bg-[#2A2A2A] rounded-2xl p-6 shadow-xl mb-6">
            <form action="{{ route('kasir.laporan') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                        🔍 Filter Laporan
                    </button>
                    <a href="{{ route('kasir.laporan') }}" 
                       class="px-6 py-3 bg-gray-700 hover:bg-gray-600 rounded-lg font-bold transition-all">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Total Transaksi -->
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-8 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm mb-2 font-semibold">Total Transaksi</p>
                        <p class="text-6xl font-bold mb-2">{{ $totalTransactions }}</p>
                        <p class="text-blue-100 text-sm">Transaksi Berhasil</p>
                        <p class="text-blue-200 text-xs mt-2">
                            Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                        </p>
                    </div>
                    <div class="text-8xl opacity-20">💰</div>
                </div>
            </div>

            <!-- Total Pendapatan -->
            <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-2xl p-8 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm mb-2 font-semibold">Total Pendapatan</p>
                        <p class="text-5xl font-bold mb-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        <p class="text-green-100 text-sm">Pendapatan Kotor</p>
                        <p class="text-green-200 text-xs mt-2">
                            Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                        </p>
                    </div>
                    <div class="text-8xl opacity-20">💵</div>
                </div>
            </div>
        </div>

        <!-- Pendapatan Harian -->
        <div class="bg-[#2A2A2A] rounded-2xl shadow-xl overflow-hidden">
            <div class="p-6 border-b border-gray-700">
                <h2 class="text-2xl font-bold">📊 Pendapatan Harian</h2>
                <p class="text-gray-400 text-sm mt-1">Detail transaksi per hari</p>
            </div>
            
            @if($dailyRevenue->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-800">
                        <tr>
                            <th class="text-left py-4 px-6 text-gray-400 font-semibold text-sm">Tanggal</th>
                            <th class="text-left py-4 px-6 text-gray-400 font-semibold text-sm">Hari</th>
                            <th class="text-left py-4 px-6 text-gray-400 font-semibold text-sm">Jumlah Transaksi</th>
                            <th class="text-left py-4 px-6 text-gray-400 font-semibold text-sm">Total Pendapatan</th>
                            <th class="text-left py-4 px-6 text-gray-400 font-semibold text-sm">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dailyRevenue as $daily)
                        <tr class="border-b border-gray-800 hover:bg-gray-800 transition-colors">
                            <td class="py-4 px-6 text-sm font-semibold">
                                {{ \Carbon\Carbon::parse($daily->date)->format('d F Y') }}
                            </td>
                            <td class="py-4 px-6 text-sm">
                                <span class="px-3 py-1 bg-gray-700 rounded-full text-xs font-semibold">
                                    {{ \Carbon\Carbon::parse($daily->date)->locale('id')->dayName }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-sm">
                                <div class="flex items-center gap-2">
                                    <span class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg font-bold">
                                        {{ $daily->transaction_count }}
                                    </span>
                                    <span class="text-gray-400 text-xs">transaksi</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-lg font-bold text-green-400">
                                Rp {{ number_format($daily->revenue, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6">
                                <a href="{{ route('kasir.riwayat', ['start_date' => $daily->date, 'end_date' => $daily->date]) }}" 
                                   class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-xs font-semibold transition-all inline-block">
                                    Lihat Detail →
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-800">
                        <tr>
                            <td colspan="2" class="py-4 px-6 text-right font-bold text-lg">TOTAL</td>
                            <td class="py-4 px-6">
                                <span class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg">
                                    {{ $totalTransactions }} transaksi
                                </span>
                            </td>
                            <td class="py-4 px-6 text-xl font-bold text-green-400">
                                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="text-center py-20">
                <div class="text-8xl mb-4 opacity-50">📊</div>
                <p class="text-gray-400 text-xl font-semibold mb-2">Tidak Ada Data Pendapatan</p>
                <p class="text-gray-500 text-sm">Coba ubah filter tanggal</p>
            </div>
            @endif
        </div>
    </div>

</body>
</html>