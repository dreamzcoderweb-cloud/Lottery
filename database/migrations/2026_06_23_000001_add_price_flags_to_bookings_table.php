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
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'first_price_flag')) {
                $table->string('first_price_flag', 30)->nullable()->after('win_amount');
            }
            if (!Schema::hasColumn('bookings', 'second_price_flag')) {
                $table->string('second_price_flag', 30)->nullable()->after('first_price_flag');
            }
            if (!Schema::hasColumn('bookings', 'third_price_flag')) {
                $table->string('third_price_flag', 30)->nullable()->after('second_price_flag');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['first_price_flag', 'second_price_flag', 'third_price_flag']);
        });
    }
};
