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
        Schema::create('payment_gateway_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // 'razorpay' or 'stripe'
            $table->string('key')->nullable(); // Public Key
            $table->string('secret')->nullable(); // Secret Key
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        // Seed the table with default (empty) settings
        \App\Models\PaymentGatewaySetting::create([
            'name' => 'razorpay',
            'key' => env('RAZORPAY_KEY', ''),
            'secret' => env('RAZORPAY_SECRET', ''),
            'is_active' => false
        ]);


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateway_settings');
    }
};
