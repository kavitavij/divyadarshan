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
        Schema::table('stay_bookings', function (Blueprint $table) {
            $table->string('payment_method')->default('online')->after('status');
            $table->string('payment_status')->default('paid')->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('stay_bookings', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_status']);
        });
    }
};
