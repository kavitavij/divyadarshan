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
    Schema::create('stay_booking_guests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('stay_booking_id')->constrained()->onDelete('cascade');
        $table->string('name');
        $table->string('id_type'); // e.g., 'aadhar', 'pan', 'passport'
        $table->string('id_number');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stay_booking_guests');
    }
};
