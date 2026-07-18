<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('slot_items', function (Blueprint $table) {
            $table->string('digit')->change();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->string('digits')->change();
        });

        // Pad existing digits based on the title count / title_id
        DB::table('slot_items')->whereNotNull('title')->where('title', '>', 0)->update([
            'digit' => DB::raw("LPAD(digit, title, '0')")
        ]);

        DB::table('bookings')->whereNotNull('title_id')->where('title_id', '>', 0)->update([
            'digits' => DB::raw("LPAD(digits, title_id, '0')")
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slot_items', function (Blueprint $table) {
            $table->integer('digit')->change();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('digits')->change();
        });
    }
};
