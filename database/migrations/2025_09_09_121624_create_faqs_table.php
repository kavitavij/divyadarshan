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
    Schema::create('faqs', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email');
        $table->text('question');
        $table->text('answer')->nullable(); // The admin's answer
        $table->boolean('is_published')->default(false); // To control visibility
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
