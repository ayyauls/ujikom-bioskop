<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $film->title }} - BioskopKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#1E1E1E] text-white font-sans flex flex-col min-h-screen">
    @include('layouts.navbar')

    <!-- ==========================
         🎬 FILM DETAIL SECTION
    =========================== -->
    <section class="flex-1 py-16 px-10">
        <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-start">

            <!-- Poster -->
            <div class="sticky top-10">
                <img src="{{ asset($film->poster) }}"
                     alt="{{ $film->title }}"
                     class="rounded-2xl w-full shadow-2xl shadow-black/50 object-cover">
            </div>

            <!-- Info Film -->
            <div>
                <!-- Judul & Info Dasar -->
                <h1 class="text-6xl font-extrabold mb-5">{{ $film->title }}</h1>

                <div class="flex gap-3 text-gray-400 mb-6 text-xl">
                    <span>{{ $film->genre }}</span>
                    @if($film->duration)
                        <span>•</span>
                        <span>{{ $film->duration }} menit</span>
                    @endif
                </div>

                <!-- Status + Rating -->
                <div class="flex items-center gap-3 mb-6 flex-wrap">
                    @if($film->status === 'now_playing')
                        <span class="inline-block bg-green-600 px-5 py-2 rounded-full text-base font-semibold">
                            🎬 Sedang Tayang
                        </span>
                    @else
                        <span class="inline-block bg-gray-600 px-5 py-2 rounded-full text-base font-semibold">
                            🔜 Coming Soon
                        </span>
                    @endif

                    @if($film->rating)
                        <span class="inline-block bg-red-600 text-base px-5 py-2 rounded-full font-semibold">
                            {{ $film->rating }}
                        </span>
                    @endif
                </div>

                <!-- Sinopsis -->
                <div class="mb-10">
                    <h3 class="text-3xl font-semibold mb-4">Sinopsis</h3>
                    <p class="text-gray-300 text-lg leading-relaxed text-justify">
                        {{ $film->description ?? 'Sinopsis tidak tersedia.' }}
                    </p>
                </div>

                <!-- Jadwal Hari Ini -->
                <div class="mb-10">
                    <h3 class="text-3xl font-semibold mb-4">Jadwal Hari Ini</h3>
                    <div class="bg-[#2A2A2A] text-lg px-5 py-3 rounded-xl font-medium">
                        <div class="text-sm text-gray-400">{{ now()->translatedFormat('l') }}</div>
                        <div class="font-bold">{{ now()->format('d M Y') }}</div>
                    </div>
                </div>

                <!-- Jam Tayang -->
                <div class="mb-10">
                    <h3 class="text-3xl font-semibold mb-4">Pilih Jam Tayang</h3>

                    @if($todaySchedules->count() > 0)
                        <div id="showtime-container" class="flex gap-3 flex-wrap">
                            @foreach ($todaySchedules as $schedule)
                                <a href="#"
                                   data-showtime="{{ $schedule->show_time->format('H:i') }}"
                                   data-studio="{{ $schedule->studio->name }}"
                                   onclick="selectShowtime(event, '{{ $schedule->id }}')"
                                   class="showtime-link bg-[#2A2A2A] hover:bg-red-600 text-lg px-6 py-3 rounded-xl font-medium transition-all duration-200 inline-block text-center hover:scale-105 hover:shadow-lg hover:shadow-red-900/30">
                                    <div class="font-bold">{{ $schedule->show_time->format('H:i') }}</div>
                                    <div class="text-xs text-gray-400">{{ $schedule->studio->name }}</div>
                                </a>
                            @endforeach
                        </div>
                        <p class="text-gray-400 text-sm mt-3">👆 Klik jam tayang untuk memilih kursi</p>
                    @else
                        <div class="text-gray-400 text-center py-8">
                            <div class="text-4xl mb-2">📅</div>
                            <div>Tidak ada jadwal hari ini</div>
                            <div class="text-sm mt-1">Silakan cek kembali besok</div>
                        </div>
                    @endif
                </div>

                <!-- Tombol Aksi -->
                <div class="flex gap-4 mt-10">
                    <a href="{{ route('home') }}"
                       class="bg-[#2A2A2A] hover:bg-[#3A3A3A] px-10 py-4 rounded-full text-xl font-bold transition-all duration-200 inline-block text-center">
                        ← Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </section>

    @include('layouts.footer')

    <!-- ==========================
         📜 SCRIPT SECTION
    =========================== -->
    <script>
        /** Klik jam tayang → redirect ke halaman booking.select_seat */
        function selectShowtime(event, scheduleId) {
            event.preventDefault();
            const filmId = {{ $film->id }};
            const targetUrl = `/booking/select-seat/${filmId}?schedule_id=${scheduleId}`;
            window.location.href = targetUrl;
        }
    </script>
</body>
</html>
