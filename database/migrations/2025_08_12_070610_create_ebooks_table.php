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
    Schema::create('ebooks', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('author')->nullable();
        $table->string('cover_image_path')->nullable(); // Will store path to cover image
        $table->string('ebook_file_path');      // Will store path to the PDF file
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebooks');
    }
};
