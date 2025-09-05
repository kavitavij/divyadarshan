<?php

// In database/migrations/xxxx_add_details_to_hotels_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            // Adding a rating column
            $table->decimal('rating', 2, 1)->nullable()->after('location');
            // JSON columns to store structured data
            $table->json('policies')->nullable()->after('has_food');
            $table->json('nearby_attractions')->nullable()->after('policies');
        });
    }

    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn(['rating', 'policies', 'nearby_attractions']);
        });
    }
};
