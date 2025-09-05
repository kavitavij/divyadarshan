<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // We no longer need these columns
            $table->dropColumn('line_number');

            // Drop foreign key before the column
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['default_darshan_slot_id']);
            }
            $table->dropColumn('default_darshan_slot_id');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Re-add columns if you ever roll back
            $table->tinyInteger('line_number')->nullable()->after('time_slot');
            $table->foreignId('default_darshan_slot_id')->nullable()->after('darshan_slot_id')->constrained()->onDelete('set null');
        });
    }
};
