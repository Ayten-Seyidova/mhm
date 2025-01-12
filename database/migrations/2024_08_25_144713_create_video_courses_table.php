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
        Schema::create('video_courses', function (Blueprint $table) {
            $table->id();
            $table->string('image')->default('postImage/noPhoto.png');
            $table->string('name');
            $table->text('group_ids')->nullable();
            $table->string('duration')->nullable();
            $table->enum('type', ['video', 'live'])->default('video');
            $table->longText('description');
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
        Schema::dropIfExists('video_courses');
    }
};
