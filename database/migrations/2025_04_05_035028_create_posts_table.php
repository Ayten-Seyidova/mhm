<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('sub_direction_id')->nullable();
            $table->string('image')->nullable();
            $table->text('content')->nullable();
            $table->string('video')->nullable();
            $table->enum('type', ['image', 'text', 'video', 'question'])->default('text');
            $table->enum('correct', ['A', 'B', 'C', 'D', 'E'])->nullable();
            $table->tinyInteger('is_deleted')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
