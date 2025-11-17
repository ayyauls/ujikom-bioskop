<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Kursi - {{ $film->title }} - BioskopKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#1E1E1E] text-white font-sans min-h-screen">

    @include('kasir.layouts.nnavbar')

    <div class="container mx-auto px-6 py-8 max-w-[1400px]">
        
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-600 text-white shadow-lg animate-pulse">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div id="errorAlert" class="hidden mb-6 p-4 rounded-lg bg-red-600 text-white text-center shadow-lg font-semibold"></div>

        <div class="grid grid-cols-12 gap-6">
            
            <!-- LEFT COLUMN - Film Info -->
            <div class="col-span-3">
                <div class="bg-[#2A2A2A] rounded-2xl p-6 shadow-xl sticky top-10">
                    <img src="{{ asset($film->poster) }}" alt="{{ $film->title }}" 
                         class="w-full rounded-lg shadow-lg mb-4 hover:scale-105 transition-transform">
                    <h2 class="text-2xl font-bold mb-4 line-clamp-2">{{ $film->title }}</h2>
                    
                    <div class="space-y-3 text-sm mb-6">
                        <div class="flex items-center gap-2 bg-[#1E1E1E] p-2 rounded">
                            <span class="text-gray-400">🎭</span>
                            <span class="font-semibold">{{ $film->genre }}</span>
                        </div>
                        <div class="flex items-center gap-2 bg-[#1E1E1E] p-2 rounded">
                            <span class="text-gray-400">⏱️</span>
                            <span class="font-semibold">{{ $film->duration }} menit</span>
                        </div>
                        <div class="flex items-center gap-2 bg-[#1E1E1E] p-2 rounded">
                            <span class="text-gray-400">⭐</span>
                            <span class="font-semibold">{{ $film->rating }}</span>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-700">
                        <p class="text-gray-400 text-sm mb-2">📅 Jadwal Dipilih</p>
                        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 rounded-lg text-center shadow-lg">
                            <p class="text-3xl font-bold">{{ $schedule->show_time }}</p>
                            <p class="text-xs mt-2 text-red-100">{{ $schedule->studio->name }} - {{ \Carbon\Carbon::parse($schedule->show_date)->format('d M Y') }}</p>
                        </div>
                    </div>

                    <a href="{{ route('kasir.film-detail', $film->id) }}" 
                       class="mt-6 block bg-gray-700 hover:bg-gray-600 py-3 rounded-lg text-center font-bold transition-all duration-200">
                        ← Kembali
                    </a>
                </div>
            </div>

            <!-- CENTER COLUMN - Seat Selection -->
            <div class="col-span-6">
                <div class="bg-[#2A2A2A] rounded-2xl p-8 shadow-xl">
                    <!-- Screen -->
                    <div class="mb-8">
                        <div class="bg-gradient-to-b from-gray-500 to-transparent h-4 rounded-t-full mx-auto w-3/4 mb-3 shadow-lg"></div>
                        <p class="text-center text-gray-400 text-sm font-bold tracking-widest">L A Y A R</p>
                    </div>

                    <!-- Seats Grid -->
                    <div class="flex flex-col gap-3 items-center mb-8">
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
                                    $isDisabled = in_array($seatNumber, $disabledSeats);
                                @endphp
                                
                                @if($i == 7)
                                    <div class="w-8"></div>
                                @endif
                                
                                <button 
                                    onclick="toggleSeat('{{ $seatNumber }}')" 
                                    data-seat="{{ $seatNumber }}"
                                    title="{{ $seatNumber }}"
                                    class="seat-btn {{ $isBooked ? 'booked bg-red-600 cursor-not-allowed' : ($isDisabled ? 'disabled bg-gray-600 cursor-not-allowed' : 'available bg-green-600 hover:bg-green-500') }} 
                                           w-11 h-11 rounded-lg transition-all duration-200 text-sm font-bold
                                           flex items-center justify-center shadow-md
                                           {{ !$isBooked && !$isDisabled ? 'hover:scale-110 hover:shadow-xl' : 'opacity-50' }}"
                                    {{ $isBooked || $isDisabled ? 'disabled' : '' }}>
                                    {{ $i }}
                                </button>
                            @endfor
                            <span class="text-gray-400 font-bold w-6 text-right text-sm">{{ $row }}</span>
                        </div>
                        @endforeach
                    </div>

                    <!-- Legend -->
                    <div class="flex justify-center gap-8 pt-6 border-t border-gray-700">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded bg-green-600 text-xs flex items-center justify-center font-bold shadow-md">1</div>
                            <span class="text-sm font-medium">Tersedia</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded bg-blue-600 text-xs flex items-center justify-center font-bold shadow-md">2</div>
                            <span class="text-sm font-medium">Dipilih</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded bg-red-600 text-xs flex items-center justify-center font-bold shadow-md opacity-50">3</div>
                            <span class="text-sm font-medium">Terisi</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded bg-gray-600 text-xs flex items-center justify-center font-bold shadow-md opacity-50">4</div>
                            <span class="text-sm font-medium">Tidak Tersedia</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN - Payment Summary -->
            <div class="col-span-3">
                <div class="bg-[#2A2A2A] rounded-2xl p-6 shadow-xl sticky top-10">
                    <h3 class="text-2xl font-bold mb-6 text-center">💰 Ringkasan</h3>
                    
                    <!-- Customer Info -->
                    <div class="mb-4">
                        <label class="text-gray-400 text-sm mb-2 block">👤 Nama Customer *</label>
                        <input type="text" id="customerName" placeholder="Nama lengkap..." 
                               class="w-full bg-[#1E1E1E] rounded-lg p-3 text-white border border-gray-700 focus:border-red-600 focus:outline-none transition-colors">
                    </div>



                    <!-- Selected Seats -->
                    <div class="mb-4">
                        <label class="text-gray-400 text-sm mb-2 block">🪑 Kursi Dipilih</label>
                        <div class="bg-[#1E1E1E] rounded-lg p-4 min-h-[70px] flex items-center justify-center">
                            <p id="selectedSeatsDisplay" class="text-base font-bold text-gray-500 text-center">
                                Belum ada kursi dipilih
                            </p>
                        </div>
                    </div>

                    <!-- Ticket Count -->
                    <div class="mb-4">
                        <label class="text-gray-400 text-sm mb-2 block">🎫 Jumlah Tiket</label>
                        <div class="bg-[#1E1E1E] rounded-lg p-4">
                            <p id="ticketCount" class="text-xl font-bold text-center">0 Tiket</p>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-4">
                        <label class="text-gray-400 text-sm mb-2 block">💳 Metode Pembayaran *</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 bg-[#1E1E1E] rounded-lg p-4 cursor-pointer hover:bg-gray-800 transition-all border-2 border-transparent has-[:checked]:border-red-600">
                                <input type="radio" name="payment_method" value="cash" class="w-5 h-5 text-red-600" checked>
                                <div class="flex items-center gap-3">
                                    <span class="text-3xl">💵</span>
                                    <div>
                                        <span class="font-semibold block">Cash</span>
                                        <span class="text-xs text-gray-400">Pembayaran tunai</span>
                                    </div>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 bg-[#1E1E1E] rounded-lg p-4 cursor-pointer hover:bg-gray-800 transition-all border-2 border-transparent has-[:checked]:border-red-600">
                                <input type="radio" name="payment_method" value="qris" class="w-5 h-5 text-red-600">
                                <div class="flex items-center gap-3">
                                    <span class="text-3xl">📱</span>
                                    <div>
                                        <span class="font-semibold block">QRIS</span>
                                        <span class="text-xs text-gray-400">Scan QR Code</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Total Price -->
                    <div class="mb-6 pt-6 border-t border-gray-700">
                        <label class="text-gray-400 text-sm mb-2 block">💰 Total Pembayaran</label>
                        <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-xl p-6 text-center shadow-xl">
                            <p id="totalPrice" class="text-4xl font-bold">Rp 0</p>
                            <p class="text-xs text-red-100 mt-2">@ Rp 50.000 per kursi</p>
                        </div>
                    </div>

                    <button onclick="submitBooking()" 
                        class="w-full bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 
                        py-4 rounded-lg text-lg font-bold shadow-lg transition-all duration-200 hover:scale-105 hover:shadow-xl">
                        🎟️ Proses Pembayaran
                    </button>
                </div>
            </div>

        </div>
    </div>

<script>
    let selectedSeats = [];
    const selectedScheduleId = '{{ $schedule->id }}';
    const pricePerSeat = 50000;

    function toggleSeat(seatNumber) {
        const button = event.target.closest('button');
        if (button.classList.contains('booked') || button.classList.contains('disabled')) return;

        if (selectedSeats.includes(seatNumber)) {
            selectedSeats = selectedSeats.filter(s => s !== seatNumber);
            button.classList.remove('bg-blue-600', 'scale-110');
            button.classList.add('bg-green-600');
        } else {
            selectedSeats.push(seatNumber);
            button.classList.remove('bg-green-600');
            button.classList.add('bg-blue-600', 'scale-110');
        }
        updateSummary();
    }

    function updateSummary() {
        const display = document.getElementById('selectedSeatsDisplay');
        const total = document.getElementById('totalPrice');
        const ticketCount = document.getElementById('ticketCount');
        
        if (selectedSeats.length === 0) {
            display.textContent = 'Belum ada kursi dipilih';
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
        const customerName = document.getElementById('customerName').value.trim();
        
        if (selectedSeats.length === 0) {
            errorAlert.textContent = '⚠️ Silakan pilih kursi terlebih dahulu!';
            errorAlert.classList.remove('hidden');
            setTimeout(() => errorAlert.classList.add('hidden'), 3000);
            return;
        }

        if (!customerName) {
            errorAlert.textContent = '⚠️ Silakan masukkan nama customer!';
            errorAlert.classList.remove('hidden');
            setTimeout(() => errorAlert.classList.add('hidden'), 3000);
            document.getElementById('customerName').focus();
            return;
        }

        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("kasir.process-booking", $film->id) }}';

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

        const scheduleInput = document.createElement('input');
        scheduleInput.type = 'hidden';
        scheduleInput.name = 'schedule_id';
        scheduleInput.value = selectedScheduleId;
        form.appendChild(scheduleInput);

        const paymentInput = document.createElement('input');
        paymentInput.type = 'hidden';
        paymentInput.name = 'payment_method';
        paymentInput.value = paymentMethod;
        form.appendChild(paymentInput);

        const nameInput = document.createElement('input');
        nameInput.type = 'hidden';
        nameInput.name = 'customer_name';
        nameInput.value = customerName;
        form.appendChild(nameInput);



        document.body.appendChild(form);
        form.submit();
    }
</script>

</body>
</html>