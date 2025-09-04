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
    Schema::create('spiritual_help_requests', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('contact_info'); // For email or phone
        $table->string('city');
        $table->string('query_type');
        $table->foreignId('temple_id')->nullable()->constrained()->onDelete('set null');
        $table->string('preferred_time');
        $table->text('message');
        $table->boolean('is_resolved')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spiritual_help_requests');
    }
};
