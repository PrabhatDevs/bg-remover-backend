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
        Schema::create('payments', function (Blueprint $table) {

            $table->id();
            // 🔹 User relation
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('razorpay_payment_id')->unique();
            $table->string('plan_name')->nullable();
            $table->integer('credits_added')->default(0);
            // 🔹 Plan purchased
            $table->string('plan_type'); // starter / pdf_bundle / bundle_pro
            // 🔹 Financial
            $table->bigInteger('amount'); // stored in paise / cents
            $table->string('currency', 10);
            $table->bigInteger('fee')->nullable();
            $table->bigInteger('tax')->nullable();
            // 🔹 Status
            $table->string('status'); // created / authorized / captured / failed / refunded
            $table->string('method')->nullable(); // card / upi / netbanking
            $table->string('upi_transaction_id')->nullable();
            $table->boolean('international')->default(false);
            // 🔹 Customer
            $table->string('email')->nullable();
            $table->string('contact')->nullable();
            // 🔹 Razorpay identifiers
            $table->string('razorpay_order_id');
            $table->string('razorpay_signature')->nullable();
            // 🔹 Extra Data (very important)
            $table->json('raw_response')->nullable();
            // 🔹 Payment timestamp from Razorpay
            $table->timestamp('payment_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
