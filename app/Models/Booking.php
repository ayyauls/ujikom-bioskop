<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'film_id',
        'schedule_id',
        'booking_code',
        'seat_number',
        'showtime',
        'booking_date',
        'customer_name',
        'customer_email',
        'customer_phone',
        'price',
        'status',
        'payment_type',
        'transaction_time',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'price' => 'decimal:2',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Film
     */
    public function film()
    {
        return $this->belongsTo(Film::class);
    }

    /**
     * Relasi ke Schedule
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Relasi ke Transaction via booking_code
     */
    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'booking_code', 'booking_code');
    }

    /**
     * Scope untuk booking hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('booking_date', today());
    }

    /**
     * Scope untuk booking paid
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope untuk booking pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Get formatted seat
     */
    public function getFormattedSeatAttribute()
    {
        return $this->seat_number;
    }
}