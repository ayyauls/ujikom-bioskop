<?php

namespace Database\Seeders;

use App\Models\Film;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Hapus data bookings dulu (karena ada foreign key ke films)
        Booking::truncate();
        
        // Baru hapus data films
        Film::truncate();
        
        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $films = [
            [
                'title' => 'Chainsaw Man',
                'genre' => 'Animation, Fantasy',
                'poster' => 'images/chainsawman.jpg',
                'duration' => 100,
                'rating' => '17+',
                'description' => 'A dark fantasy anime full of action and emotion.',
                'status' => 'now_playing',
            ],
            [
                'title' => 'Your Name',
                'genre' => 'Animation, Romance, Fantasy',
                'poster' => 'images/yourname.jpg',
                'duration' => 106,
                'rating' => '13+',
                'description' => 'A beautiful story about two teenagers who mysteriously swap bodies.',
                'status' => 'now_playing',
            ],
            [
                'title' => 'Jujutsu Kaisen',
                'genre' => 'Animation, Action, Supernatural',
                'poster' => 'images/jujutsukaisen.jpg',
                'duration' => 120,
                'rating' => '17+',
                'description' => 'A thrilling supernatural action anime with incredible fight scenes.',
                'status' => 'now_playing',
            ],
            [
                'title' => 'Spirited Away',
                'genre' => 'Animation, Fantasy, Adventure',
                'poster' => 'images/spiritedaway.jpg',
                'duration' => 125,
                'rating' => 'SU',
                'description' => 'A masterpiece from Studio Ghibli about a girl trapped in a spirit world.',
                'status' => 'now_playing',
            ],
            [
                'title' => 'One Piece',
                'genre' => 'Animation, Adventure, Comedy',
                'poster' => 'images/onepiece.jpg',
                'duration' => 115,
                'rating' => '13+',
                'description' => 'Join Luffy and his crew on an epic adventure to find the One Piece.',
                'status' => 'now_playing',
            ],
            [
                'title' => 'Demon Slayer: Mugen Train',
                'genre' => 'Animation, Action, Dark Fantasy',
                'poster' => 'images/demonslayermt.jpg',
                'duration' => 117,
                'rating' => '17+',
                'description' => 'An emotional journey featuring breathtaking animation and intense battles.',
                'status' => 'coming_soon',
            ],
            [
                'title' => 'Howl\'s Moving Castle',
                'genre' => 'Animation, Fantasy, Romance',
                'poster' => 'images/howlscastle.jpg',
                'duration' => 119,
                'rating' => 'SU',
                'description' => 'A magical tale of love and transformation from Studio Ghibli.',
                'status' => 'coming_soon',
            ],
            [
                'title' => 'Suzume',
                'genre' => 'Animation, Adventure, Fantasy',
                'poster' => 'images/suzume.jpg',
                'duration' => 122,
                'rating' => '13+',
                'description' => 'A coming-of-age story mixed with supernatural elements and adventure.',
                'status' => 'coming_soon',
            ],
            [
                'title' => 'Weathering With You',
                'genre' => 'Animation, Romance, Fantasy',
                'poster' => 'images/weathering.jpg',
                'duration' => 112,
                'rating' => '13+',
                'description' => 'A romantic fantasy about a boy who meets a girl with the power to control weather.',
                'status' => 'coming_soon',
            ],
            [
                'title' => 'The Garden of Words',
                'genre' => 'Animation, Drama, Romance',
                'poster' => 'images/gardenwords.jpg',
                'duration' => 46,
                'rating' => '13+',
                'description' => 'A beautiful short film about an unlikely relationship that develops on rainy days.',
                'status' => 'coming_soon',
            ],
        ];

        foreach ($films as $film) {
            Film::create($film);
        }

        $this->command->info('✅ Films seeded successfully!');
    }
}