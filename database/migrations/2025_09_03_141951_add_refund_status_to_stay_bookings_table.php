<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stay_bookings', function (Blueprint $table) {
            // Add the new column after the existing 'status' column
            $table->string('refund_status')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('stay_bookings', function (Blueprint $table) {
            $table->dropColumn('refund_status');
        });
    }
};
