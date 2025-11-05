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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique(); // TRX20241104001
            $table->string('booking_code'); // Link ke booking_code di bookings table
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('total_amount'); // Total pembayaran
            $table->enum('status', ['pending', 'paid', 'expired', 'failed', 'cancelled'])->default('pending');
            $table->string('payment_method')->nullable(); // gopay, bca_va, etc
            $table->string('snap_token')->nullable(); // Midtrans snap token
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Index
            $table->index('booking_code');
            $table->index('transaction_code');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};