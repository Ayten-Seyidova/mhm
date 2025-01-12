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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id');
            $table->text('title');
            $table->text('A');
            $table->text('B');
            $table->text('C');
            $table->text('D');
            $table->text('E');
            $table->enum('title_type', ['text', 'image'])->default('text');
            $table->enum('variant_type', ['text', 'image'])->default('text');
            $table->enum('correct', ['A', 'B', 'C', 'D', 'E']);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('is_deleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
