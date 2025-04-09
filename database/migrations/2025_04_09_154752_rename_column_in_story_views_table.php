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
        Schema::table('story_views', function (Blueprint $table) {
            $table->unsignedBigInteger('guest_id');
            $table->dropColumn('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('story_views', function (Blueprint $table) {
            $table->dropColumn('guest_id');
            $table->unsignedBigInteger('customer_id');
        });
    }
};
