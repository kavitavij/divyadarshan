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
    Schema::table('stay_bookings', function (Blueprint $table) {
        // Add this line
        $table->string('status')->default('pending')->after('total_amount');
    });
}

public function down(): void
{
    Schema::table('stay_bookings', function (Blueprint $table) {
        // This allows you to reverse the migration
        $table->dropColumn('status');
    });
}
};
