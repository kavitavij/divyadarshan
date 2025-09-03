<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stay_bookings', function (Blueprint $table) {
            // Add the new hotel_id column after room_id
            // It's nullable for now so we can add it to an existing table
            $table->foreignId('hotel_id')->nullable()->after('room_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('stay_bookings', function (Blueprint $table) {
            $table->dropForeign(['hotel_id']);
            $table->dropColumn('hotel_id');
        });
    }
};
