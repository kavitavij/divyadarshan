<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seva_bookings', function (Blueprint $table) {
            $table->date('booking_date')->after('seva_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('seva_bookings', function (Blueprint $table) {
            $table->dropColumn('booking_date');
        });
    }
};