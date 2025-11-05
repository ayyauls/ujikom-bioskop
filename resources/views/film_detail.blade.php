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

<section class="flex-1 py-16 px-10">
    <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-start">
        <!-- Poster -->
        <div class="sticky top-10">
            <img src="{{ asset($film->poster) }}" alt="{{ $film->title }}"
                 class="rounded-2xl w-full shadow-2xl shadow-black/50 object-cover">
        </div>

        <!-- Info Film -->
        <div>
            <h1 class="text-6xl font-extrabold mb-5">{{ $film->title }}</h1>
            
            <div class="flex gap-3 text-gray-400 mb-6 text-xl">
                <span>{{ $film->genre }}</span>
                @if($film->duration)
                    <span>•</span>
                    <span>{{ $film->duration }} menit</span>
                @endif
            </div>

            <!-- Status Badge + Rating -->
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

            <!-- Jadwal Tanggal -->
            <div class="mb-10">
                <h3 class="text-3xl font-semibold mb-4">Tanggal Tayang</h3>
                
                @if($film->status === 'now_playing')
                    <div class="flex gap-3 flex-wrap" id="date-options">
                        @php
                            $today = now();
                            $dates = [];
                            for ($i = 0; $i < 7; $i++) {
                                $date = $today->copy()->addDays($i);
                                $dates[] = [
                                    'date' => $date->format('Y-m-d'),
                                    'display' => $date->format('d M'),
                                    'day' => $date->translatedFormat('D'),
                                    'is_today' => $i === 0
                                ];
                            }
                        @endphp
                        
                        @foreach($dates as $dateInfo)
                            <button 
                                onclick="selectDate('{{ $dateInfo['date'] }}')"
                                data-date="{{ $dateInfo['date'] }}"
                                class="date-btn bg-[#2A2A2A] hover:bg-red-600 text-lg px-5 py-3 rounded-xl font-medium 
                                       transition-all duration-200 {{ $dateInfo['is_today'] ? 'bg-red-600' : '' }}">
                                <div class="text-sm text-gray-400">{{ $dateInfo['day'] }}</div>
                                <div class="font-bold">{{ $dateInfo['display'] }}</div>
                                @if($dateInfo['is_today'])
                                    <div class="text-xs text-gray-300">Hari Ini</div>
                                @endif
                            </button>
                        @endforeach
                    </div>
                    <p class="text-gray-400 text-sm mt-3">
                        👆 Pilih tanggal pemesanan
                    </p>
                @else
                    <div class="bg-[#2A2A2A] text-lg px-5 py-3 rounded-xl font-medium opacity-50">
                        Film belum tersedia
                    </div>
                @endif
            </div>

            <!-- Jam Tayang -->
            <div class="mb-10">
                <h3 class="text-3xl font-semibold mb-4">Pilih Jam Tayang</h3>
                
                @if($film->status === 'now_playing')
                    <div id="showtime-container" class="flex gap-3 flex-wrap">
                        @foreach (['10:00', '13:00', '16:00', '19:00', '21:30'] as $jam)
                            <a href="#" 
                               data-showtime="{{ $jam }}"
                               onclick="selectShowtime(event, '{{ $jam }}')"
                               class="showtime-link bg-[#2A2A2A] hover:bg-red-600 text-lg px-6 py-3 rounded-xl font-medium 
                                      transition-all duration-200 inline-block text-center hover:scale-105 hover:shadow-lg hover:shadow-red-900/30">
                                {{ $jam }}
                            </a>
                        @endforeach
                    </div>
                    <p class="text-gray-400 text-sm mt-3">
                        👆 Klik jam tayang untuk memilih kursi
                    </p>
                @else
                    <div class="flex gap-3 flex-wrap">
                        @foreach (['10:00', '13:00', '16:00', '19:00', '21:30'] as $jam)
                            <div class="bg-gray-700 text-lg px-6 py-3 rounded-xl font-medium opacity-50 cursor-not-allowed">
                                {{ $jam }}
                            </div>
                        @endforeach
                    </div>
                    <p class="text-gray-400 text-sm mt-3">
                        Film belum tersedia
                    </p>
                @endif
            </div>

            <!-- Tombol Aksi -->
            <div class="flex gap-4 mt-10">
                <a href="{{ route('home') }}" 
                   class="bg-[#2A2A2A] hover:bg-[#3A3A3A] px-10 py-4 rounded-full text-xl font-bold 
                          transition-all duration-200 inline-block text-center">
                    ← Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</section>

@include('layouts.footer')

<script>
    // Default: tanggal hari ini
    let selectedDate = '{{ now()->format("Y-m-d") }}';

    // URL dasar halaman booking
    const bookingUrl = "{{ route('booking.select_seat', ['id' => $film->id]) }}";

    /**
     * Pilih tanggal tayang
     */
    function selectDate(date) {
        selectedDate = date;

        // Reset semua tombol
        document.querySelectorAll('.date-btn').forEach(btn => {
            btn.classList.remove('bg-red-600');
            btn.classList.add('bg-[#2A2A2A]');
        });

        // Aktifkan tombol yang dipilih
        const selectedBtn = document.querySelector(`[data-date="${date}"]`);
        if (selectedBtn) {
            selectedBtn.classList.add('bg-red-600');
            selectedBtn.classList.remove('bg-[#2A2A2A]');
        }
    }

    /**
     * Klik jam tayang → redirect ke halaman booking.select_seat
     */
function selectShowtime(event, showtime) {
    event.preventDefault();
    const filmId = {{ $film->id }};
    const targetUrl = `/booking/select-seat/${filmId}?date=${selectedDate}&time=${showtime}`;
    window.location.href = targetUrl;
}


    // Saat halaman load
    document.addEventListener('DOMContentLoaded', function() {
        selectDate(selectedDate);
    });
</script>

</body>
</html>
