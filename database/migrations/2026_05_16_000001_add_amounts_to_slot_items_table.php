<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('slot_items', function (Blueprint $table) {
            $table->decimal('win_amount', 10, 2)->nullable()->after('color');
            $table->decimal('ticket_amt', 10, 2)->nullable()->after('win_amount');
        });

        // Backfill from slots table for existing rows (initially same amounts per item).
        // Use a DB-agnostic UPDATE so tests can run on sqlite too.
        DB::statement('
            UPDATE slot_items
            SET win_amount = (SELECT win_amount FROM slots WHERE slots.slot_id = slot_items.slot_id),
                ticket_amt = (SELECT ticket_amt FROM slots WHERE slots.slot_id = slot_items.slot_id)
        ');
    }

    public function down(): void
    {
        Schema::table('slot_items', function (Blueprint $table) {
            $table->dropColumn(['win_amount', 'ticket_amt']);
        });
    }
};
