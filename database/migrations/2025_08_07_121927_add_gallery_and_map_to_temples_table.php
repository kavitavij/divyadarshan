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
    Schema::table('temples', function (Blueprint $table) {
        $table->json('gallery')->nullable();
        $table->text('map_embed')->nullable();
    });
}

public function down(): void
{
    Schema::table('temples', function (Blueprint $table) {
        $table->dropColumn(['gallery', 'map_embed']);
    });
}

};
