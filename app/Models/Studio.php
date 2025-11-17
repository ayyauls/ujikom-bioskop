<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer',
    ];

    /**
     * Relasi ke Seats
     */
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    /**
     * Relasi ke Films
     */
    public function films()
    {
        return $this->hasMany(Film::class);
    }

    /**
     * Scope untuk studio aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
