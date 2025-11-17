<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 20);
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('film_id')->constrained('films')->onDelete('cascade');
            $table->string('seat_number', 10);
            $table->time('showtime');
            $table->date('booking_date');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone', 20);
            $table->decimal('price', 10, 2)->default(50000);
            $table->enum('status', ['pending', 'paid', 'failed', 'cancelled', 'expired'])->default('pending');
            $table->string('payment_type', 50)->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->timestamps();

            // Indexes untuk performa
            $table->index('booking_code');
            $table->index(['film_id', 'booking_date', 'showtime']);
            $table->index('status');
            $table->index('user_id');
            
            // Unique constraint untuk kombinasi film, seat, date, showtime
            $table->unique(['film_id', 'seat_number', 'booking_date', 'showtime'], 'unique_booking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};