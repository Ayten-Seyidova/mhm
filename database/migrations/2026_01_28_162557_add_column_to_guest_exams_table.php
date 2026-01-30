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
        Schema::table('guest_exams', function (Blueprint $table) {
            $table->string('desc_video3')->nullable();
            $table->text('name_video1')->nullable();
            $table->text('name_video2')->nullable();
            $table->text('name_video3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guest_exams', function (Blueprint $table) {
           $table->dropColumn('desc_video3');
           $table->dropColumn('name_video1');
           $table->dropColumn('name_video2');
           $table->dropColumn('name_video3');
        });
    }
};
