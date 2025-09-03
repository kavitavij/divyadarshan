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
    Schema::create('default_darshan_slots', function (Blueprint $table) {
        $table->id();
        $table->foreignId('temple_id')->constrained()->onDelete('cascade');
        $table->time('start_time');
        $table->time('end_time');
        $table->integer('capacity')->default(1000);
        $table->boolean('is_active')->default(true); // To toggle a default slot on/off
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('default_darshan_slots');
    }
};
