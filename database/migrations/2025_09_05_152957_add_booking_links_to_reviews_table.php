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
        Schema::table('reviews', function (Blueprint $table) {
            // Only add a column if it does not already exist
            if (!Schema::hasColumn('reviews', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('reviews', 'hotel_id')) {
                $table->foreignId('hotel_id')->nullable()->after('user_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('reviews', 'stay_booking_id')) {
                $table->foreignId('stay_booking_id')->nullable()->unique()->after('hotel_id')->constrained()->onDelete('cascade');
            }

            // Make old columns nullable
            if (Schema::hasColumn('reviews', 'name')) {
                $table->string('name')->nullable()->change();
            }
            if (Schema::hasColumn('reviews', 'email')) {
                $table->string('email')->nullable()->change();
            }
            if (Schema::hasColumn('reviews', 'message')) {
                $table->text('message')->nullable()->change(); // Changed to text for consistency
            }
            if (Schema::hasColumn('reviews', 'review_type')) {
                $table->string('review_type')->nullable()->change();
            }
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            //
        });
    }
};
