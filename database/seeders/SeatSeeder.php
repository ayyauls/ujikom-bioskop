<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seat;

class SeatSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus dulu agar tidak double
        Seat::truncate();

        // Generate seat otomatis
        Seat::generateSeats();
    }
}
