<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Kursi - {{ $film->title }} - BioskopKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Zoom in untuk tampilan lebih besar */
        .main-container {
            zoom: 1.15;
        }
    </style>
</head>
<body class="bg-[#1E1E1E] text-white font-sans min-h-screen">

    @include('layouts.navbar')

    <!-- Main Content with 3 Column Layout -->
    <div class="container mx-auto px-6 py-8 max-w-[1400px]">
        
        <!-- Alert Error -->
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-600 text-white shadow-lg">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div id="errorAlert" class="hidden mb-6 p-4 rounded-lg bg-red-600 text-white text-center shadow-lg">
            Silakan pilih kursi terlebih dahulu!
        </div>

        <!-- 3 Column Grid Layout -->
        <div class="grid grid-cols-12 gap-6">
            
            <!-- LEFT COLUMN - Film Poster (3 cols) -->
            <div class="col-span-3">
                <div class="bg-[#2A2A2A] rounded-2xl p-6 shadow-xl sticky top-10">
                    <img src="{{ asset($film->poster) }}" alt="{{ $film->title }}" 
                         class="w-full rounded-lg shadow-lg mb-4">
                    
                    <h2 class="text-2xl font-bold mb-3">{{ $film->title }}</h2>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="text-gray-400">🎭 Genre:</span>
                            <span class="font-semibold">{{ $film->genre }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-gray-400">⏱️ Durasi:</span>
                            <span class="font-semibold">{{ $film->duration }} menit</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-gray-400">⭐ Rating:</span>
                            <span class="font-semibold">{{ $film->rating }}</span>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-700">
                        <div class="bg-green-600 inline-block px-4 py-2 rounded-lg text-sm font-semibold">
                            🎬 Sedang Tayang
                        </div>
                    </div>

                    <!-- Jam Tayang -->
                    <div class="mt-6 pt-6 border-t border-gray-700">
                        <p class="text-gray-400 text-sm mb-2">📅 Jadwal Dipilih</p>
                        <div class="bg-red-600 px-6 py-3 rounded-lg text-center">
                            <p class="text-2xl font-bold">{{ $showtime }}</p>
                            <p class="text-xs mt-1">{{ now()->format('d M Y') }}</p>
                        </div>
                    </div>

                    <!-- Back Button -->
                    <a href="{{ route('film.detail', $film->id) }}" 
                       class="mt-6 block bg-gray-700 hover:bg-gray-600 py-3 rounded-lg text-center font-bold 
                              transition-all duration-200">
                        ← Kembali
                    </a>
                </div>
            </div>

            <!-- CENTER COLUMN - Seat Selection (6 cols) -->
            <div class="col-span-6">
                <div class="bg-[#2A2A2A] rounded-2xl p-8 shadow-xl">
                    
                    <!-- Screen -->
                    <div class="mb-8">
                        <div class="bg-gradient-to-b from-gray-500 to-transparent h-3 rounded-t-full mx-auto w-3/4 mb-2 shadow-lg"></div>
                        <p class="text-center text-gray-400 text-sm font-bold tracking-widest">L A Y A R</p>
                    </div>

                    <!-- Seat Layout -->
                    <div class="flex flex-col gap-2.5 items-center mb-8">
                        @php
                            $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
                            $seatsPerRow = 12;
                        @endphp

                        @foreach($rows as $row)
                        <div class="flex gap-2 items-center">
                            <span class="text-gray-400 font-bold w-6 text-sm">{{ $row }}</span>
                            @for($i = 1; $i <= $seatsPerRow; $i++)
                                @php
                                    $seatNumber = $row . $i;
                                    $isBooked = in_array($seatNumber, $bookedSeats);
                                @endphp
                                
                                @if($i == 7)
                                    <div class="w-6"></div>
                                @endif
                                
                                <button 
                                    onclick="toggleSeat('{{ $seatNumber }}')" 
                                    data-seat="{{ $seatNumber }}"
                                    title="{{ $seatNumber }}"
                                    class="seat-btn {{ $isBooked ? 'booked bg-red-600 cursor-not-allowed' : 'available bg-green-600 hover:bg-green-500' }} 
                                           w-10 h-10 rounded-lg transition-all duration-200 text-xs font-bold
                                           flex items-center justify-center
                                           {{ !$isBooked ? 'hover:scale-110 hover:shadow-lg' : '' }}"
                                    {{ $isBooked ? 'disabled' : '' }}>
                                    {{ $i }}
                                </button>
                            @endfor
                            <span class="text-gray-400 font-bold w-6 text-right text-sm">{{ $row }}</span>
                        </div>
                        @endforeach
                    </div>

                    <!-- Legend -->
                    <div class="flex justify-center gap-6 pt-6 border-t border-gray-700">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded bg-green-600 text-xs flex items-center justify-center font-bold">1</div>
                            <span class="text-sm font-medium">Tersedia</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded bg-blue-600 text-xs flex items-center justify-center font-bold">2</div>
                            <span class="text-sm font-medium">Dipilih</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded bg-red-600 text-xs flex items-center justify-center font-bold">3</div>
                            <span class="text-sm font-medium">Terisi</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN - Payment Summary (3 cols) -->
            <div class="col-span-3">
                <div class="bg-[#2A2A2A] rounded-2xl p-6 shadow-xl sticky top-10">
                    <h3 class="text-2xl font-bold mb-6 text-center">💰 Ringkasan</h3>
                    
                    <!-- Selected Seats -->
                    <div class="mb-4">
                        <p class="text-gray-400 text-sm mb-2">🪑 Kursi Dipilih</p>
                        <div class="bg-[#1E1E1E] rounded-lg p-4 min-h-[70px]">
                            <p id="selectedSeatsDisplay" class="text-base font-bold text-gray-500">-</p>
                        </div>
                    </div>

                    <!-- Showtime -->
                    <div class="mb-4">
                        <p class="text-gray-400 text-sm mb-2">🕐 Jam Tayang</p>
                        <div class="bg-[#1E1E1E] rounded-lg p-4">
                            <p class="text-lg font-bold">{{ $showtime }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ now()->format('d M Y') }}</p>
                        </div>
                    </div>

                    <!-- Ticket Count -->
                    <div class="mb-4">
                        <p class="text-gray-400 text-sm mb-2">🎫 Jumlah Tiket</p>
                        <div class="bg-[#1E1E1E] rounded-lg p-4">
                            <p id="ticketCount" class="text-lg font-bold">0 Tiket</p>
                        </div>
                    </div>

                    <!-- Price per Ticket -->
                    <div class="mb-4">
                        <p class="text-gray-400 text-sm mb-2">💵 Harga per Tiket</p>
                        <div class="bg-[#1E1E1E] rounded-lg p-4">
                            <p class="text-lg font-bold">Rp 50.000</p>
                        </div>
                    </div>

                    <!-- Total Price -->
                    <div class="mb-6 pt-6 border-t border-gray-700">
                        <p class="text-gray-400 text-sm mb-2">💰 Total Pembayaran</p>
                        <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-lg p-5 text-center">
                            <p id="totalPrice" class="text-4xl font-bold">Rp 0</p>
                        </div>
                    </div>

<!-- Book Button -->
<button onclick="submitBooking()" 
    class="w-full bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 
    py-4 rounded-lg text-lg font-bold shadow-lg transition-all duration-200 hover:scale-105">
    🎟️ Booking Sekarang
</button>
                </div>
            </div>

        </div>
    </div>

    @include('layouts.footer')

<script>
    let selectedSeats = [];
    const selectedShowtime = '{{ $showtime }}';
    const pricePerSeat = 50000;

    function toggleSeat(seatNumber) {
        const button = event.target;
        if (button.classList.contains('booked')) return;

        if (selectedSeats.includes(seatNumber)) {
            selectedSeats = selectedSeats.filter(s => s !== seatNumber);
            button.classList.remove('bg-blue-600');
            button.classList.add('bg-green-600');
        } else {
            selectedSeats.push(seatNumber);
            button.classList.remove('bg-green-600');
            button.classList.add('bg-blue-600');
        }
        updateSummary();
    }

    function updateSummary() {
        const display = document.getElementById('selectedSeatsDisplay');
        const total = document.getElementById('totalPrice');
        const ticketCount = document.getElementById('ticketCount');
        
        if (selectedSeats.length === 0) {
            display.textContent = '-';
            display.classList.remove('text-blue-400');
            display.classList.add('text-gray-500');
            total.textContent = 'Rp 0';
            ticketCount.textContent = '0 Tiket';
        } else {
            const sortedSeats = selectedSeats.sort((a, b) => {
                const rowA = a[0], rowB = b[0];
                const numA = parseInt(a.substring(1)), numB = parseInt(b.substring(1));
                if (rowA !== rowB) return rowA.localeCompare(rowB);
                return numA - numB;
            });
            
            display.textContent = sortedSeats.join(', ');
            display.classList.add('text-blue-400');
            display.classList.remove('text-gray-500');
            
            const totalPrice = selectedSeats.length * pricePerSeat;
            total.textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
            ticketCount.textContent = selectedSeats.length + ' Tiket';
        }
    }

    function submitBooking() {
        const errorAlert = document.getElementById('errorAlert');
        if (selectedSeats.length === 0) {
            errorAlert.textContent = '⚠️ Silakan pilih kursi terlebih dahulu!';
            errorAlert.classList.remove('hidden');
            setTimeout(() => errorAlert.classList.add('hidden'), 3000);
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("booking.book", $film->id) }}'; // ✅ sudah disesuaikan!

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);

        selectedSeats.forEach(seat => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'seats[]';
            input.value = seat;
            form.appendChild(input);
        });

        const showtimeInput = document.createElement('input');
        showtimeInput.type = 'hidden';
        showtimeInput.name = 'showtime';
        showtimeInput.value = selectedShowtime;
        form.appendChild(showtimeInput);

        document.body.appendChild(form);
        form.submit();
    }
</script>

</body>
</html>