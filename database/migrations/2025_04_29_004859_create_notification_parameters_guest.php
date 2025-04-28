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
        Schema::create('notification_parameters_guest', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer("notificationType");
            $table->string("token");
            $table->string("deviceId");
            $table->integer("user_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_parameters_guest');
    }
};
