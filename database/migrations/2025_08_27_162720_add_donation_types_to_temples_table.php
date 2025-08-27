<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('temples', function (Blueprint $table) {
        if (!Schema::hasColumn('temples', 'donation_types')) {
            $table->json('donation_types')->nullable()->after('offered_services');
        }
    });
}

public function down()
{
    Schema::table('temples', function (Blueprint $table) {
        if (Schema::hasColumn('temples', 'donation_types')) {
            $table->dropColumn('donation_types');
        }
    });
}

};
