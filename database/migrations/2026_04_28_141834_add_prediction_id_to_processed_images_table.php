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
            $table->string('prediction_id')->nullable()->after('original_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('processed_images', function (Blueprint $table) {
            $table->dropColumn('prediction_id');
        });
    }
};
