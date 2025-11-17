<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    @include('layouts.navbarr')

    <div class="container mx-auto px-4 py-8">
        {{-- ===========================
             FILTER PERIODE
        ============================ --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h5 class="text-lg font-semibold text-gray-800 mb-4">Filter Periode</h5>

            <form action="{{ route('owner.report.filter') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input type="date" name="start_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input type="date" name="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>

                    <div class="flex items-end">
                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- ===========================
             STATISTIK DASHBOARD
        ============================ --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h6 class="text-gray-500 text-sm font-medium mb-2">Total Pendapatan Bulan Ini</h6>
                <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h6 class="text-gray-500 text-sm font-medium mb-2">Total Transaksi</h6>
                <h3 class="text-2xl font-bold text-gray-800">{{ $totalTransactions }}</h3>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h6 class="text-gray-500 text-sm font-medium mb-2">Rata-rata Transaksi</h6>
                <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($averageTransaction, 0, ',', '.') }}</h3>
            </div>
        </div>

        {{-- ===========================
             GRAFIK 7 HARI TERAKHIR
        ============================ --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h5 class="text-lg font-semibold text-gray-800 mb-4">Pendapatan 7 Hari Terakhir</h5>
            <div class="h-64">
                <canvas id="chart7Days" 
                    data-labels="{{ json_encode($last7DaysLabels) }}"
                    data-values="{{ json_encode($last7DaysData) }}">
                </canvas>
            </div>
        </div>

        {{-- ===========================
             GRAFIK 12 BULAN TERAKHIR
        ============================ --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h5 class="text-lg font-semibold text-gray-800 mb-4">Pendapatan 12 Bulan Terakhir</h5>
            <div class="h-64">
                <canvas id="chart12Months"
                    data-labels="{{ json_encode($monthsLabels) }}"
                    data-values="{{ json_encode($monthsData) }}">
                </canvas>
            </div>
        </div>

        {{-- ===========================
             FILM TERPOPULER
        ============================ --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h5 class="text-lg font-semibold text-gray-800 mb-4">Film Terpopuler Akhir-akhir Ini</h5>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Film</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tiket Terjual</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($popularFilms as $film)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $film->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $film->tickets_sold }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($film->total_revenue, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                Tidak ada data film
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===========================
             TRANSAKSI TERBARU
        ============================ --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h5 class="text-lg font-semibold text-gray-800 mb-4">Transaksi Terbaru</h5>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentTransactions as $trx)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border border-gray-200">{{ $trx->transaction_code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">
                                {{ $trx->user?->name ?? 'Guest User' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">{{ $trx->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 border border-gray-200">
                                Tidak ada transaksi terbaru
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // =======================
        // GRAFIK 7 HARI TERAKHIR
        // =======================
        document.addEventListener('DOMContentLoaded', function() {
            const ctx7 = document.getElementById('chart7Days');
            const labels7 = JSON.parse(ctx7.dataset.labels);
            const values7 = JSON.parse(ctx7.dataset.values);

            const chart7Days = new Chart(ctx7, {
                type: "line",
                data: {
                    labels: labels7,
                    datasets: [{
                        label: "Pendapatan Harian",
                        data: values7,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2
                    }]
                },
                options: { 
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        }
                    }
                }
            });

            // =======================
            // GRAFIK 12 BULAN TERAKHIR
            // =======================
            const ctx12 = document.getElementById('chart12Months');
            const labels12 = JSON.parse(ctx12.dataset.labels);
            const values12 = JSON.parse(ctx12.dataset.values);

            const chart12Months = new Chart(ctx12, {
                type: "bar",
                data: {
                    labels: labels12,
                    datasets: [{
                        label: "Pendapatan Bulanan",
                        data: values12,
                        backgroundColor: '#10b981',
                        borderColor: '#10b981',
                        borderWidth: 1
                    }]
                },
                options: { 
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value/1000000).toFixed(0) + ' jt';
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>