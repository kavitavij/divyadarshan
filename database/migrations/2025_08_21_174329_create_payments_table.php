<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // linked user
            $table->string('type'); // darshan, seva, donation, ebook, stay
            $table->unsignedBigInteger('reference_id')->nullable(); // booking_id / donation_id etc.
            $table->string('order_id'); // Razorpay order_id
            $table->string('payment_id')->nullable(); // Razorpay payment_id
            $table->string('signature')->nullable(); // Razorpay signature
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['created', 'success', 'failed'])->default('created');
            $table->json('payload')->nullable(); // store full Razorpay response
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
