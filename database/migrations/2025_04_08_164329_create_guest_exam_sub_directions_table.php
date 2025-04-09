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
        Schema::create('guest_exam_sub_directions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guest_exam_id');
            $table->unsignedBigInteger('sub_direction_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_exam_sub_directions');
    }
};
