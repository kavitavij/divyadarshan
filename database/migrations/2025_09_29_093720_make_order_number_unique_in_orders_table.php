<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $indexExists = collect(DB::select("SHOW INDEXES FROM orders"))
                ->pluck('Key_name')
                ->contains('orders_order_number_unique');

            // Only add the index if it doesn't already exist
            if (! $indexExists) {
                $table->string('order_number')->unique()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // To make it reversible, we just drop the index
            $table->dropUnique(['order_number']);
        });
    }
};