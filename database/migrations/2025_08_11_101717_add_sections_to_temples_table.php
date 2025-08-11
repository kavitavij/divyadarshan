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
        $table->longText('about')->nullable();
        $table->longText('online_services')->nullable();
        $table->longText('Slot Booking')->nullable();
        $table->longText('news')->nullable();
        $table->longText('social_services')->nullable();
    });
}

public function down()
{
    Schema::table('temples', function (Blueprint $table) {
        $table->dropColumn(['about', 'online_services', 'ebooks', 'news', 'social_services']);
    });
}

};
