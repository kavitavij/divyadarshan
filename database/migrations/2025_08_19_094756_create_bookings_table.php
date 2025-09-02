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
            $table->id();
            $table->string('type')->default('darshan'); // To distinguish between 'darshan', 'seva', 'stay' etc.

            // Foreign Keys
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('temple_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('hotel_id')->nullable()->constrained()->onDelete('cascade'); // <-- ADD THIS
            $table->foreignId('darshan_slot_id')->nullable()->constrained()->onDelete('set null');

            // Booking Details
            $table->date('booking_date')->nullable();
            $table->integer('number_of_people');
            $table->json('devotee_details')->nullable();
            $table->string('status')->default('confirmed'); // e.g., confirmed, cancelled

            // Refund Details for cancellations
            $table->string('refund_status')->default('not_applicable'); // e.g., not_applicable, not_refunded, refunded

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
