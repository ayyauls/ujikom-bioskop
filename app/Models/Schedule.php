<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'film_id',
        'studio_id', 
        'show_date',
        'show_time',
        'is_active'
    ];

    protected $casts = [
        'show_date' => 'date',
        'show_time' => 'datetime:H:i',
    ];

    public function film()
    {
        return $this->belongsTo(Film::class);
    }

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }
}