<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('seva_bookings', function (Blueprint $table) {
        // Add the new column, typically after 'amount'
        $table->integer('quantity')->default(1)->after('amount');
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
