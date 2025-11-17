<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Console\Command;

class CheckExpiredBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and expire bookings that have passed their expiration time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired bookings...');

        // Ambil semua booking pending yang sudah expired
        $expiredBookings = Booking::where('status', 'pending')
            ->where('expires_at', '<=', now())
            ->get();

        if ($expiredBookings->isEmpty()) {
            $this->info('No expired bookings found.');
            return;
        }

        $this->info("Found {$expiredBookings->count()} expired bookings.");

        // Update status booking menjadi expired
        Booking::where('status', 'pending')
            ->where('expires_at', '<=', now())
            ->update(['status' => 'expired']);

        // Update status transaction terkait menjadi expired
        $bookingCodes = $expiredBookings->pluck('booking_code')->unique();
        Transaction::whereIn('booking_code', $bookingCodes)
            ->where('status', 'pending')
            ->update(['status' => 'expired']);

        $this->info("Successfully expired {$expiredBookings->count()} bookings and their transactions.");
    }
}
