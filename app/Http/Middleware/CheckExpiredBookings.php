<?php

namespace App\Http\Middleware;

use App\Models\Booking;
use App\Models\Transaction;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckExpiredBookings
{
    /**
     * Handle an incoming request.
     * Auto-expire bookings yang sudah lewat waktu
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil semua booking pending yang sudah expired
        $expiredBookings = Booking::where('status', 'pending')
            ->where('expires_at', '<=', now())
            ->get();

        if ($expiredBookings->isNotEmpty()) {
            // Update status booking menjadi expired
            Booking::where('status', 'pending')
                ->where('expires_at', '<=', now())
                ->update(['status' => 'expired']);

            // Update status transaction terkait menjadi expired
            $bookingCodes = $expiredBookings->pluck('booking_code')->unique();
            Transaction::whereIn('booking_code', $bookingCodes)
                ->where('status', 'pending')
                ->update(['status' => 'expired']);
        }

        return $next($request);
    }
}