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
        Schema::create('slot_items', function (Blueprint $table) {

            $table->id('slot_items_id');

            $table->unsignedBigInteger('slot_id');

            $table->string('group_name');

            $table->integer('digit');

            $table->string('color')->nullable();

            $table->timestamps();

            $table->foreign('slot_id')
                ->references('slot_id')
                ->on('slots')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slot_items');
    }
};
