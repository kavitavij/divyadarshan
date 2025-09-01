<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('temples', function (Blueprint $table) {
            // Add the new column after the 'description' column, for example
            $table->longText('terms_and_conditions')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('temples', function (Blueprint $table) {
            $table->dropColumn('terms_and_conditions');
        });
    }
};
