<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table
                ->unsignedBigInteger('referred_by_customer_id')
                ->nullable()
                ->after('reference_code');

            $table->index('referred_by_customer_id');

            $table
                ->foreign('referred_by_customer_id')
                ->references('customer_id')
                ->on('customers')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['referred_by_customer_id']);
            $table->dropIndex(['referred_by_customer_id']);
            $table->dropColumn('referred_by_customer_id');
        });
    }
};

