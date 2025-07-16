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
        Schema::create('whatsapp_envios_logs', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->string('status'); // enviado, no_registrado, error, sin_id
            $table->text('caption')->nullable();
            $table->text('link')->nullable();
            $table->string('media_filename')->nullable();
            $table->string('message_id')->nullable();
            $table->text('response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_envios_logs');
    }
};
