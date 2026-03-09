<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_book_accesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('library_book_id')->constrained('library_books')->onDelete('cascade');
            $table->unsignedBigInteger('guest_id');
            $table->timestamps();

            $table->unique(['library_book_id', 'guest_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_book_accesses');
    }
};
