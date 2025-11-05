<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'film_id',
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

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'booking_date' => 'date',
        'transaction_time' => 'datetime',
        'price' => 'decimal:2',
    ];

    /**
     * Relasi ke Film
     */
    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk filter booking berdasarkan film, tanggal, dan showtime
     */
    public function scopeForFilmShowtime($query, $filmId, $date, $showtime)
    {
        return $query->where('film_id', $filmId)
                     ->where('booking_date', $date)
                     ->where('showtime', $showtime)
                     ->whereIn('status', ['pending', 'paid']);
    }

    /**
     * Scope untuk booking aktif (belum expired)
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'paid'])
                     ->where('booking_date', '>=', now()->format('Y-m-d'));
    }

    /**
     * Generate booking code unik
     */
    public static function generateBookingCode()
    {
        $date = date('Ymd');
        $lastBooking = self::where('booking_code', 'LIKE', "BK{$date}%")
            ->orderBy('booking_code', 'DESC')
            ->first();

        if ($lastBooking) {
            $lastNumber = intval(substr($lastBooking->booking_code, -5));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'BK' . $date . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get formatted date (Accessor)
     */
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->booking_date)->format('d M Y');
    }

    /**
     * Get formatted price (Accessor)
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get formatted showtime (Accessor)
     */
    public function getFormattedShowtimeAttribute()
    {
        return Carbon::parse($this->showtime)->format('H:i');
    }

    /**
     * Get status badge (Accessor)
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="bg-yellow-600 px-3 py-1 rounded-full text-xs font-bold">⏳ Pending</span>',
            'paid' => '<span class="bg-green-600 px-3 py-1 rounded-full text-xs font-bold">✓ Paid</span>',
            'failed' => '<span class="bg-red-600 px-3 py-1 rounded-full text-xs font-bold">✗ Failed</span>',
            'cancelled' => '<span class="bg-gray-600 px-3 py-1 rounded-full text-xs font-bold">✗ Cancelled</span>',
        ];

        return $badges[$this->status] ?? $this->status;
    }

    /**
     * Check if seat is available
     */
    public static function isSeatAvailable($filmId, $seatNumber, $date, $showtime)
    {
        return !self::where('film_id', $filmId)
            ->where('seat_number', $seatNumber)
            ->where('booking_date', $date)
            ->where('showtime', $showtime)
            ->whereIn('status', ['pending', 'paid'])
            ->exists();
    }
}