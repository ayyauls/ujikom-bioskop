<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Studio;

class StudioSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus dulu jika ada
        Studio::truncate();

        // Buat 4 studio
        for ($i = 1; $i <= 4; $i++) {
            Studio::create([
                'name' => 'Studio ' . $i,
                'capacity' => 120,
                'is_active' => true,
            ]);
        }
    }
}
