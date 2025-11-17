<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Price;

class PriceSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus dulu jika ada
        Price::truncate();

        // Buat harga weekday (Senin - Jumat)
        Price::create([
            'day_type' => 'weekday',
            'price' => 40000, // 40K untuk weekday
        ]);

        // Buat harga weekend (Sabtu - Minggu)
        Price::create([
            'day_type' => 'weekend',
            'price' => 50000, // 50K untuk weekend
        ]);
    }
}