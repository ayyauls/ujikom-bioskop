<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'film_id',
        'transaction_code',
        'customer_name',
        'customer_email',
        'seats',
        'showtime',
        'ticket_count',
        'total_amount',
        'payment_method',
        'status',
        'snap_token',
        'paid_at',
        'expired_at',
        'notes'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'total_amount' => 'integer',
    ];

    /**
     * Relasi ke User
     */
    // Di Model Transaction
public function user()
{
    return $this->belongsTo(User::class)->withDefault([
        'name' => 'Guest User',
        'email' => 'guest@example.com'
    ]);
}

    /**
     * Relasi ke Film
     */
    public function film()
    {
        return $this->belongsTo(Film::class);
    }

    /**
     * Relasi ke Bookings (one to many)
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'booking_code', 'booking_code');
    }

    /**
     * Scope untuk transaksi pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk transaksi paid
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Check if transaction is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if transaction is paid
     */
    public function isPaid()
    {
        return $this->status === 'paid';
    }

    /**
     * Mark transaction as paid
     */
    public function markAsPaid($paymentMethod = null)
    {
        $this->update([
            'status' => 'paid',
            'payment_method' => $paymentMethod,
            'paid_at' => now(),
        ]);

        // Update booking status
        Booking::where('booking_code', $this->booking_code)->update(['status' => 'confirmed']);
    }

    /**
     * Mark transaction as failed
     */
    public function markAsFailed()
    {
        $this->update(['status' => 'failed']);
        Booking::where('booking_code', $this->booking_code)->update(['status' => 'cancelled']);
    }

    /**
     * Generate transaction code
     */
    public static function generateTransactionCode()
    {
        do {
            $randomNumber = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
            $code = 'TRX' . date('Ymd') . $randomNumber;
            $exists = self::where('transaction_code', $code)->exists();
        } while ($exists);
        
        return $code;
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }
}