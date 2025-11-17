<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'booking_code',
        'user_id',
        'customer_name',
        'customer_email',
        'seats',
        'showtime',
        'ticket_count',
        'total_price',
        'total_amount',
        'film_id',
        'status',
        'payment_method',
        'snap_token',
        'paid_at',
        'expired_at',
        'notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'seats' => 'array',
        'total_amount' => 'integer',
        'total_price' => 'integer',
    ];

    /**
     * Relasi ke Bookings via booking_code
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'booking_code', 'booking_code');
    }

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
     * Scope untuk transaksi hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope untuk transaksi paid
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope untuk transaksi pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk transaksi failed
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    /**
     * Check if payment is cash
     */
    public function isCash()
    {
        return $this->payment_method === 'cash';
    }

    /**
     * Check if payment is QRIS
     */
    public function isQris()
    {
        return $this->payment_method === 'qris';
    }

    /**
     * Mark transaction as paid
     */
    public function markAsPaid()
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    /**
     * Generate unique transaction code
     */
    public static function generateTransactionCode()
    {
        do {
            $code = 'TRX' . date('Ymd') . rand(10000, 99999);
        } while (self::where('transaction_code', $code)->exists());

        return $code;
    }
}