<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('publisher')->nullable();
            $table->string('cover')->nullable();
            $table->text('description')->nullable();
            $table->string('language')->nullable();
            $table->integer('page_count')->nullable();
            $table->string('year')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('demo_pdf_url')->nullable();  // Bunny CDN
            $table->string('full_pdf_url')->nullable();  // Bunny CDN
            $table->tinyInteger('is_featured')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_books');
    }
};
