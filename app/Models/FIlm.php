<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    protected $table = 'films';
    
    protected $fillable = [
        'title',
        'genre',
        'poster',
        'duration',
        'rating',
        'description',
        'status'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'duration' => 'integer',
    ];

    /**
     * Relasi ke Bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Scope untuk film yang sedang tayang
     */
    public function scopeNowPlaying($query)
    {
        return $query->where('status', 'now_playing');
    }

    /**
     * Scope untuk film yang akan datang
     */
    public function scopeComingSoon($query)
    {
        return $query->where('status', 'coming_soon');
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) return '-';
        
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;
        
        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }
        return $minutes . 'm';
    }
}