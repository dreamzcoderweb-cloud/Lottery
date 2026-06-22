<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('slots', function (Blueprint $table) {
            $table->dropColumn(['win_amount', 'ticket_amt']);
        });
    }

    public function down(): void
    {
        Schema::table('slots', function (Blueprint $table) {
            $table->decimal('win_amount', 10, 2)->after('title');
            $table->decimal('ticket_amt', 10, 2)->after('win_amount');
        });
    }
};

