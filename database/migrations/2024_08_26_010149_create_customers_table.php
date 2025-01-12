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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('image')->default('postImage/noUser.png');
            $table->string('username')->unique();
            $table->string('name')->nullable();
            $table->string('password');
            $table->string('password_text');
            $table->string('email')->nullable();
            $table->string('class')->nullable();
            $table->string('device_id')->nullable();
            $table->text('blocked_subject_ids')->nullable();
            $table->text('group_ids')->nullable();
            $table->date('date')->nullable();
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
        Schema::dropIfExists('customers');
    }
};
