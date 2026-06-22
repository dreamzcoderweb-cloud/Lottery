<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('slots', function (Blueprint $table) {
            if (!Schema::hasIndex('slots', 'slots_draw_date_index')) {
                $table->index('draw_date', 'slots_draw_date_index');
            }

            if (!Schema::hasIndex('slots', 'slots_slug_index')) {
                $table->index('slug', 'slots_slug_index');
            }
        });

        Schema::table('slot_items', function (Blueprint $table) {
            if (!Schema::hasIndex('slot_items', ['slot_id'])) {
                $table->index('slot_id', 'slot_items_slot_id_index');
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasIndex('bookings', 'bookings_slot_item_index')) {
                $table->index(['slot_id', 'slot_items_id'], 'bookings_slot_item_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasIndex('bookings', 'bookings_slot_item_index')) {
                $table->dropIndex('bookings_slot_item_index');
            }
        });

        Schema::table('slot_items', function (Blueprint $table) {
            if (Schema::hasIndex('slot_items', 'slot_items_slot_id_index')) {
                $table->dropIndex('slot_items_slot_id_index');
            }
        });

        Schema::table('slots', function (Blueprint $table) {
            if (Schema::hasIndex('slots', 'slots_draw_date_index')) {
                $table->dropIndex('slots_draw_date_index');
            }

            if (Schema::hasIndex('slots', 'slots_slug_index')) {
                $table->dropIndex('slots_slug_index');
            }
        });
    }
};
