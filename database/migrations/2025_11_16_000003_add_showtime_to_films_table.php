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
        Schema::table('films', function (Blueprint $table) {
            $table->json('showtimes')->nullable()->after('status'); // Array of times like ['10:00', '14:00', '18:00']
            $table->foreignId('studio_id')->nullable()->constrained('studios')->onDelete('set null')->after('showtimes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('films', function (Blueprint $table) {
            $table->dropForeign(['studio_id']);
            $table->dropColumn(['showtimes', 'studio_id']);
        });
    }
};
