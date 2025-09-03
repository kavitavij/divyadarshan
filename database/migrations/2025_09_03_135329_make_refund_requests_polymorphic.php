<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            // 1. Drop the old foreign key constraint first
            $table->dropForeign(['booking_id']);

            // 2. Add the new columns for the polymorphic relationship
            $table->string('booking_type')->after('id');

            // 3. Add an index for better performance
            $table->index(['booking_id', 'booking_type']);
        });
    }

    public function down(): void
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            $table->dropIndex(['booking_id', 'booking_type']);
            $table->dropColumn('booking_type');

            // Re-add the old foreign key if you ever roll back
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });
    }
};
