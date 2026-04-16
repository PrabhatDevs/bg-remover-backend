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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();

            // --- Subscription & Credits ---
            $table->string('plan_type')->default('free'); // free, pro, business
            $table->integer('credits_total')->default(20); // For "Pay-as-you-go" or free daily limit
            $table->integer('credits_used')->default(0); // Track when to reset daily free credits
            $table->boolean('paid_before')->default(false);

            // --- AI Tool Specifics ---
            $table->integer('total_removals')->default(0); // Lifetime stats
            $table->timestamp('last_used_at')->nullable(); // To track active users

            // --- Security & Auth ---
            $table->string('avatar')->nullable(); // Profile picture
            $table->string('provider')->nullable(); // 'google', 'github' (for Socialite)
          

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
