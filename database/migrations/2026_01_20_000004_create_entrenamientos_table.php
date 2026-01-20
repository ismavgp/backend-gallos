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
        Schema::create('entrenamientos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_gallo');
            $table->foreign('id_gallo')->references('id')->on('gallos')->onDelete('cascade');
            $table->dateTime('fecha');
            $table->integer('duracion_minutos');
            $table->string('tipo_entrenamiento', 100);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrenamientos');
    }
};
