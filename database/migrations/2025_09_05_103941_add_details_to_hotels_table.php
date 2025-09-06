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
        Schema::table('hotels', function (Blueprint $table) {
            // THE FIX IS HERE:
            // We have removed the ->after('has_food') constraint.
            // This allows the database to simply add these new columns to the
            // end of the 'hotels' table, resolving the error.
            $table->json('policies')->nullable();
            $table->json('nearby_attractions')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn(['policies', 'nearby_attractions']);
        });
    }
};
