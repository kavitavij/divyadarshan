<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
public function up(): void
{
    Schema::create('temples', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('location');
        $table->decimal('darshan_charge', 8, 2)->default(0);
        $table->string('image')->nullable();

        // All text sections
        $table->text('description')->nullable();
        $table->text('about')->nullable();
        $table->text('culture')->nullable();
        $table->text('history')->nullable();
        $table->text('best_time')->nullable();

        // Contact info
        $table->string('address')->nullable();
        $table->string('phone')->nullable();
        $table->string('email')->nullable();

        // JSON columns for structured data
        $table->json('news')->nullable();
        $table->json('online_services')->nullable();
        $table->json('social_services')->nullable();
        $table->json('donation_types')->nullable();
        $table->json('offered_services')->nullable();
        $table->json('slot_data')->nullable();

        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('temples');
    }
};
