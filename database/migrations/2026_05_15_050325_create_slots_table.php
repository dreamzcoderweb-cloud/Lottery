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
        Schema::create('slots', function (Blueprint $table) {

            $table->id('slot_id');

            $table->string('main_title');
            $table->date('draw_date');
            $table->time('booking_close_time');
            $table->time('draw_time');

            $table->string('short_title');

            $table->string('title');

            $table->decimal('win_amount', 10, 2);

            $table->decimal('ticket_amt', 10, 2);

            $table->enum('status', ['Active', 'Inactive'])
                ->default('Active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slots');
    }
};
