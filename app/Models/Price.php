<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_type',
        'price',
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Scope berdasarkan tipe hari
     */
    public function scopeByDayType($query, $dayType)
    {
        return $query->where('day_type', $dayType);
    }
}
