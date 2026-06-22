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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('booking_id');
            $table->integer('customer_id');
            $table->integer('slot_id');
            $table->integer('slot_items_id');
            $table->string('title_id');
            $table->integer('qty');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'success', 'cancelled','settled'])->default('pending');
            $table->string('payment_status')->nullable();
            $table->time('booking_time');
            $table->time('close_time');
            $table->string('is_winner')->nullable();
            $table->decimal('win_amount', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
