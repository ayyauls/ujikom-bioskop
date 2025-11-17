<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'seat_number',
        'row_letter',
        'seat_position',
        'type',
        'price',
        'is_available',
        'studio_id',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'integer',
    ];

    /**
     * Scope untuk kursi yang tersedia
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope berdasarkan tipe kursi
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Generate semua kursi untuk cinema
     * Call this in seeder
     */
    public static function generateSeats()
    {
        $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
        $seatsPerRow = 12;
        $studios = \App\Models\Studio::all();

        foreach ($studios as $studio) {
            foreach ($rows as $row) {
                for ($i = 1; $i <= $seatsPerRow; $i++) {
                    // Tentukan tipe kursi
                    $type = 'regular';
                    $price = 50000;

                    // Baris belakang (H, I, J) = Premium
                    if (in_array($row, ['H', 'I', 'J'])) {
                        $type = 'premium';
                        $price = 75000;
                    }

                    // Kursi tengah (5, 6, 7, 8) di baris premium = VIP
                    if (in_array($row, ['H', 'I', 'J']) && in_array($i, [5, 6, 7, 8])) {
                        $type = 'vip';
                        $price = 100000;
                    }

                    self::create([
                        'seat_number' => $row . $i,
                        'row_letter' => $row,
                        'seat_position' => $i,
                        'type' => $type,
                        'price' => $price,
                        'is_available' => true,
                        'studio_id' => $studio->id,
                    ]);
                }
            }
        }
    }
}