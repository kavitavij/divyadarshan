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
        // Change the news column to JSON to store multiple items
        $table->json('news')->nullable()->change();
    });
}

public function down(): void
{
    Schema::table('temples', function (Blueprint $table) {
        // Revert back to text if migration is rolled back
        $table->text('news')->nullable()->change();
    });
}
};
