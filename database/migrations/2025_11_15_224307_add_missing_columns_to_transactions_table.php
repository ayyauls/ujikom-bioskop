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
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('transactions', 'customer_email')) {
                $table->string('customer_email')->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('transactions', 'seats')) {
                $table->json('seats')->nullable()->after('customer_email');
            }
            if (!Schema::hasColumn('transactions', 'showtime')) {
                $table->string('showtime')->nullable()->after('seats');
            }
            if (!Schema::hasColumn('transactions', 'ticket_count')) {
                $table->integer('ticket_count')->nullable()->after('showtime');
            }
            if (!Schema::hasColumn('transactions', 'total_price')) {
                $table->integer('total_price')->nullable()->after('ticket_count');
            }
            // payment_method already exists, skip
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'customer_name')) {
                $table->dropColumn('customer_name');
            }
            if (Schema::hasColumn('transactions', 'customer_email')) {
                $table->dropColumn('customer_email');
            }
            if (Schema::hasColumn('transactions', 'seats')) {
                $table->dropColumn('seats');
            }
            if (Schema::hasColumn('transactions', 'showtime')) {
                $table->dropColumn('showtime');
            }
            if (Schema::hasColumn('transactions', 'ticket_count')) {
                $table->dropColumn('ticket_count');
            }
            if (Schema::hasColumn('transactions', 'total_price')) {
                $table->dropColumn('total_price');
            }
            // payment_method already exists, skip
        });
    }
};
