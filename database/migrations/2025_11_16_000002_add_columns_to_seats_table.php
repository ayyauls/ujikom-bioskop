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
        Schema::table('seats', function (Blueprint $table) {
            $table->string('seat_number')->after('id');
            $table->string('row_letter')->after('seat_number');
            $table->integer('seat_position')->after('row_letter');
            $table->enum('type', ['regular', 'premium', 'vip'])->default('regular')->after('seat_position');
            $table->integer('price')->default(50000)->after('type');
            $table->boolean('is_available')->default(true)->after('price');
            $table->foreignId('studio_id')->constrained('studios')->onDelete('cascade')->after('is_available');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seats', function (Blueprint $table) {
            $table->dropForeign(['studio_id']);
            $table->dropColumn(['seat_number', 'row_letter', 'seat_position', 'type', 'price', 'is_available', 'studio_id']);
        });
    }
};
