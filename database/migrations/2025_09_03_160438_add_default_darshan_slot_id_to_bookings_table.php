<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Add the new column to store bookings made against a default slot template
            // It is nullable because bookings against overridden slots will leave it empty.
            $table->foreignId('default_darshan_slot_id')->nullable()->after('darshan_slot_id')->constrained()->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['default_darshan_slot_id']);
            $table->dropColumn('default_darshan_slot_id');
        });
    }
};
