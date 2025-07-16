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
        Schema::create('whatsapp_envios', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->text('caption')->nullable();
            $table->text('link')->nullable();
            $table->string('media_filename')->nullable();
            $table->string('status')->default('pendiente'); // pendiente, enviado, error
            $table->text('response')->nullable(); // JSON con la respuesta del envío
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_envios');
    }
};
