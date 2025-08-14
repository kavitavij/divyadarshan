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
        Schema::table('bookings', function (Blueprint $table) {
            // This line removes the rule that links to the darshan_slots table
            $table->dropForeign(['darshan_slot_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // This allows you to add the rule back if you ever need it
            $table->foreign('darshan_slot_id')->references('id')->on('darshan_slots')->onDelete('cascade');
        });
    }
};