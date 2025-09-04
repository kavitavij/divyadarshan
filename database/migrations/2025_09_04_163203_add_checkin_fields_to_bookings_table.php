<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // A unique token for the QR code URL. Un-guessable.
            $table->string('check_in_token')->unique()->nullable()->after('status');

            // To record the exact time of check-in
            $table->timestamp('checked_in_at')->nullable()->after('check_in_token');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['check_in_token', 'checked_in_at']);
        });
    }
};
