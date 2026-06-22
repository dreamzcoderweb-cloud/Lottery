<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('slot_items', function (Blueprint $table) {
            $table->decimal('first_price', 10, 2)->nullable()->after('ticket_amt');
            $table->decimal('second_price', 10, 2)->nullable()->after('first_price');
            $table->decimal('third_price', 10, 2)->nullable()->after('second_price');
        });
    }

    public function down(): void
    {
        Schema::table('slot_items', function (Blueprint $table) {
            $table->dropColumn(['first_price', 'second_price', 'third_price']);
        });
    }
};
