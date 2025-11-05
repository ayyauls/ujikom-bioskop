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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->string('seat_number'); // A1, A2, B1, etc
            $table->string('row_letter'); // A, B, C, etc
            $table->integer('seat_position'); // 1, 2, 3, etc
            $table->enum('type', ['regular', 'premium', 'vip'])->default('regular');
            $table->integer('price')->default(50000);
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            // Index
            $table->unique('seat_number');
            $table->index(['row_letter', 'seat_position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};