<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stay_bookings', function (Blueprint $table) {
            // Add this line to create the column
            $table->decimal('total_amount', 8, 2)->after('phone_number');
        });
    }

    public function down(): void
    {
        Schema::table('stay_bookings', function (Blueprint $table) {
            // This allows you to reverse the migration
            $table->dropColumn('total_amount');
        });
    }
};
