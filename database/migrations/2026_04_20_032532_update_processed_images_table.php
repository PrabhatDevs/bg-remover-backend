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
        Schema::table('processed_images', function (Blueprint $table) {
            // We use string(45) to support both IPv4 and IPv6 addresses
            // We make it nullable so old records don't break
            $table->string('ip_address', 45)->nullable()->after('user_id');

            // Also, since you want to support guests, we MUST make user_id nullable
            // If it isn't already nullable in your current database
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('processed_images', function (Blueprint $table) {
            $table->dropColumn('ip_address');

            // Revert user_id to non-nullable if necessary
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
