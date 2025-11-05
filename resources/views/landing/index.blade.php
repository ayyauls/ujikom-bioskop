<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BioskopKu - Landing Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#1E1E1E] text-white">

    @include('layouts.navbar')

    <!-- Banner -->
    <section class="relative w-full h-[600px]">
        <img src="{{ asset('images/banner.jpg') }}" alt="Banner" class="object-cover w-full h-full brightness-75">
        <div class="absolute inset-0 flex items-center justify-center">
            <h2 class="text-4xl font-bold">Sedang Tayang di BioskopKu</h2>
        </div>
    </section>

    <!-- Now Playing -->
    <section id="now-playing" class="px-10 py-16">
        <div class="flex justify-between items-center mb-10">
            <h2 class="text-3xl font-bold uppercase tracking-wide">Now Playing</h2>
            <a href="#now-playing" class="text-red-400 font-semibold hover:underline">Lihat Semua →</a>
        </div>

        <!-- Grid Film -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-8 justify-items-center">
            @foreach ($nowPlaying as $film)
                <a href="{{ route('film.detail', $film->id) }}" 
                   class="bg-[#2A2A2A] rounded-2xl overflow-hidden shadow-lg shadow-black/40 hover:scale-110 transition-transform duration-300 w-[340px]">
                    <img src="{{ asset($film->poster) }}" alt="{{ $film->title }}" class="w-full h-[480px] object-cover">
                    <div class="p-4 text-center">
                        <h3 class="text-lg font-semibold">{{ $film->title }}</h3>
                        <p class="text-gray-400 text-sm mt-1">{{ $film->genre }} @if($film->duration) • {{ $film->duration }} menit @endif</p>
                        @if($film->rating)
                            <span class="inline-block bg-red-600 text-xs px-3 py-1 mt-2 rounded-full">{{ $film->rating }}</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Up Coming -->
    <section id="up-coming" class="px-10 pb-20">
        <div class="flex justify-between items-center mb-10">
            <h2 class="text-3xl font-bold uppercase tracking-wide">Up Coming</h2>
            <a href="#up-coming" class="text-red-400 font-semibold hover:underline">Lihat Semua →</a>
        </div>

        <!-- Grid Film Up Coming -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-8 justify-items-center">
            @foreach ($upComing as $film)
                <div class="bg-[#2A2A2A] rounded-2xl overflow-hidden shadow-lg shadow-black/40 hover:scale-105 transition-transform duration-300 w-[340px]">
                    <img src="{{ asset($film->poster) }}" alt="{{ $film->title }}" class="w-full h-[480px] object-cover">
                    <div class="p-4 text-center">
                        <h3 class="text-lg font-semibold">{{ $film->title }}</h3>
                        <p class="text-gray-400 text-sm mt-1">{{ $film->genre }} • Coming Soon</p>
                        <span class="inline-block bg-gray-500 text-xs px-3 py-1 mt-2 rounded-full">Coming Soon</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    @include('layouts.footer')

</body>
</html>
