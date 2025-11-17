<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'genre',
        'duration',
        'director',
        'cast',
        'release_date',
        'poster',
        'trailer_url',
        'rating',
        'age_rating',
        'status',
        'studio_id',
        'showtimes'
    ];

    protected $casts = [
        'release_date' => 'datetime',
        'showtimes' => 'array',
    ];

    /**
     * Relasi ke Studio
     */
    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }
}