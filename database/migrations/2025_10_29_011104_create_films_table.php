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
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('genre');
            $table->string('poster')->nullable();
            $table->integer('duration')->nullable();
            $table->string('rating', 10)->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['now_playing', 'coming_soon'])->default('now_playing');
            $table->timestamps();
            
            // Indexes untuk performa
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};