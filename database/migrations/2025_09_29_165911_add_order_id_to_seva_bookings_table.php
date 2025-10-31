<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('seva_bookings', function (Blueprint $table) {
            // Add the new column
            $table->foreignId('order_id')->nullable()->constrained()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seva_bookings', function (Blueprint $table) {
            //
        });
    }
};
