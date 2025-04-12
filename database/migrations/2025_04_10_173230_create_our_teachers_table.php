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
        Schema::create('our_teachers', function (Blueprint $table) {
            $table->id();
            $table->string('image')->default('postImage/noUser.png');
            $table->string('name')->nullable();
            $table->string('link')->nullable();
            $table->string('subject')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('our_teachers');
    }
};
