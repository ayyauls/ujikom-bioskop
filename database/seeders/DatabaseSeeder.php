<?php

namespace Database\Seeders;

use App\Models\Film;
use App\Models\Booking;
use App\Models\User;
use App\Models\Studio;
use App\Models\Seat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Skip foreign key checks for SQLite compatibility
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        $this->call([
        PriceSeeder::class,
        // ... seeder lainnya
    ]);

        // Hapus data lama agar tidak duplikat
        Booking::truncate();
        Film::truncate();
        Seat::truncate();
        Studio::truncate();
        User::truncate();

        // Skip foreign key checks for SQLite compatibility
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // === DATA USER & ADMIN ===
        $users = [
            [
                'name' => 'Admin Utama',
                'email' => 'admin@gmail.com',
                'phone' => '081234567890',
                'password' => Hash::make('123123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ayu',
                'email' => 'ayuu@gmail.com',
                'phone' => '083869112233',
                'password' => Hash::make('123123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Owner Cinema',
                'email' => 'meimei@gmail.com',
                'phone' => '0880',
                'password' => Hash::make('123123'),
                'role' => 'owner',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Kasir Utama',
                'email' => 'kasir@gmail.com',
                'phone' => '081234567893',
                'password' => Hash::make('123123'),
                'role' => 'kasir',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        // === STUDIO & KURSI PERMANEN ===
        $studios = [
            ['name' => 'Studio 1', 'capacity' => 120, 'is_active' => true],
            ['name' => 'Studio 2', 'capacity' => 120, 'is_active' => true],
            ['name' => 'Studio 3', 'capacity' => 120, 'is_active' => true],
            ['name' => 'Studio 4', 'capacity' => 120, 'is_active' => true],
        ];

        foreach ($studios as $studioData) {
            $studio = Studio::create($studioData);
            
            // Buat kursi untuk setiap studio (A1-J12 = 120 kursi)
            $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
            foreach ($rows as $row) {
                for ($i = 1; $i <= 12; $i++) {
                    Seat::create([
                        'studio_id' => $studio->id,
                        'seat_number' => $row . $i,
                        'row_letter' => $row,
                        'seat_position' => $i,
                        'type' => 'regular',
                        'price' => 50000,
                        'is_available' => true
                    ]);
                }
            }
        }

        // Film akan dibuat manual di admin

        $this->command->info('✅ All data seeded successfully!');
        $this->command->info('');
        $this->command->info('👤 Login Accounts:');
        $this->command->info('   🔑 Admin  : admin@gmail.com / 123123');
        $this->command->info('   🔑 Owner  : meimei@gmail.com / 123123');
        $this->command->info('   🔑 Kasir  : kasir@gmail.com / 123123');
        $this->command->info('   🔑 User   : ayuu@gmail.com / 123123');
    }
}