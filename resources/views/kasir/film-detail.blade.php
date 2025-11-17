<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Film - {{ $film->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#1E1E1E] text-white font-sans min-h-screen">

    @include('kasir.layouts.nnavbar')

    <div class="container mx-auto px-6 py-8">
        <div class="max-w-6xl mx-auto">
            
            <!-- Back Button -->
            <a href="{{ route('kasir.pesan-tiket') }}" 
               class="inline-flex items-center gap-2 mb-6 text-gray-400 hover:text-white transition-colors group">
                <span class="group-hover:-translate-x-1 transition-transform">←</span>
                Kembali ke Daftar Film
            </a>

            <div class="grid grid-cols-12 gap-8">
                
                <!-- Film Poster -->
                <div class="col-span-4">
                    <div class="sticky top-10">
                        <img src="{{ asset($film->poster) }}" alt="{{ $film->title }}" 
                             class="w-full rounded-2xl shadow-2xl">
                        <div class="mt-4 bg-[#2A2A2A] rounded-xl p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-400 text-sm">Rating</span>
                                <span class="text-xl font-bold text-yellow-400">⭐ {{ $film->rating }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400 text-sm">Harga Tiket</span>
                                <span class="text-xl font-bold text-green-400">Rp 50.000</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Film Details & Showtimes -->
                <div class="col-span-8">
                    <div class="bg-[#2A2A2A] rounded-2xl p-8 shadow-xl">
                        <h1 class="text-4xl font-bold mb-6">{{ $film->title }}</h1>
                        
                        <!-- Film Info -->
                        <div class="grid grid-cols-3 gap-6 mb-8 pb-8 border-b border-gray-700">
                            <div class="text-center">
                                <div class="text-4xl mb-2">🎭</div>
                                <p class="text-gray-400 text-sm mb-1">Genre</p>
                                <p class="font-semibold text-lg">{{ $film->genre }}</p>
                            </div>
                            <div class="text-center">
                                <div class="text-4xl mb-2">⏱️</div>
                                <p class="text-gray-400 text-sm mb-1">Durasi</p>
                                <p class="font-semibold text-lg">{{ $film->duration }} menit</p>
                            </div>
                            <div class="text-center">
                                <div class="text-4xl mb-2">🔞</div>
                                <p class="text-gray-400 text-sm mb-1">Rating Umur</p>
                                <p class="font-semibold text-lg">{{ $film->age_rating }}</p>
                            </div>
                        </div>

                        <!-- Showtimes -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <h2 class="text-2xl font-bold">🕐 Jadwal Tayang</h2>
                                <span class="px-3 py-1 bg-blue-600 rounded-full text-sm font-semibold">
                                    {{ now()->format('d F Y') }}
                                </span>
                            </div>
                            
                            @if($schedules->count() > 0)
                            <div class="grid grid-cols-3 gap-4">
                                @foreach($schedules as $schedule)
                                <a href="{{ route('kasir.pilih-kursi', ['id' => $film->id, 'schedule_id' => $schedule->id]) }}" 
                                   class="group bg-gradient-to-br from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 
                                          rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 
                                          transform hover:scale-105 overflow-hidden">
                                    <div class="p-6 text-center">
                                        <div class="text-3xl font-bold mb-2">{{ $schedule->show_time }}</div>
                                        <div class="text-xs text-red-200 uppercase tracking-wide">{{ $schedule->studio->name }}</div>
                                    </div>
                                    <div class="bg-black/20 py-2 text-center text-xs font-semibold">
                                        Pilih Kursi →
                                    </div>
                                </a>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-8">
                                <div class="text-6xl mb-4 opacity-50">📅</div>
                                <p class="text-gray-400 text-lg font-semibold mb-2">Tidak Ada Jadwal Hari Ini</p>
                                <p class="text-gray-500 text-sm">Silakan hubungi admin untuk menambah jadwal</p>
                            </div>
                            @endif
                        </div>

                        <!-- Additional Info -->
                        <div class="mt-8 pt-8 border-t border-gray-700">
                            <div class="bg-blue-900/30 border border-blue-700 rounded-xl p-4">
                                <div class="flex items-start gap-3">
                                    <div class="text-2xl">ℹ️</div>
                                    <div>
                                        <p class="font-semibold mb-1">Informasi Penting</p>
                                        <ul class="text-sm text-gray-300 space-y-1">
                                            <li>• Harap datang 15 menit sebelum film dimulai</li>
                                            <li>• Tiket yang sudah dibeli tidak dapat dibatalkan</li>
                                            <li>• Dilarang membawa makanan & minuman dari luar</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>