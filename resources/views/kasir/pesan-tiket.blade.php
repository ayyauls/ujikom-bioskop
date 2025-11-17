<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Tiket - BioskopKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#1E1E1E] text-white font-sans min-h-screen">

    @include('kasir.layouts.nnavbar')

    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-2">🎫 Pesan Tiket</h1>
            <p class="text-gray-400">Pilih film untuk pemesanan tiket offline</p>
        </div>

        <!-- Film Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($films as $film)
            <div class="bg-[#2A2A2A] rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300">
                <!-- Poster -->
                <div class="relative overflow-hidden">
                    <img src="{{ asset($film->poster) }}" alt="{{ $film->title }}" 
                         class="w-full h-80 object-cover hover:scale-110 transition-transform duration-300">
                    <div class="absolute top-4 right-4 bg-red-600 px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                        ⭐ {{ $film->rating }}
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                        <h3 class="text-xl font-bold line-clamp-1">{{ $film->title }}</h3>
                    </div>
                </div>

                <!-- Film Info -->
                <div class="p-5">
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center gap-2 text-sm text-gray-400">
                            <span>🎭</span>
                            <span>{{ $film->genre }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-400">
                            <span>⏱️</span>
                            <span>{{ $film->duration }} menit</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-400">
                            <span>🔞</span>
                            <span>{{ $film->age_rating }}</span>
                        </div>
                    </div>

                    <a href="{{ route('kasir.film-detail', $film->id) }}" 
                       class="block w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 py-3 rounded-lg text-center font-bold transition-all shadow-lg hover:shadow-xl">
                        Pilih Film
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-20">
                <div class="text-8xl mb-4 opacity-50">🎬</div>
                <p class="text-gray-400 text-xl font-semibold mb-2">Tidak Ada Film yang Sedang Tayang</p>
                <p class="text-gray-500 text-sm">Silakan cek kembali nanti</p>
            </div>
            @endforelse
        </div>
    </div>

</body>
</html>