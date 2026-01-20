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
        Schema::create('peleas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_gallo');
            $table->foreign('id_gallo')->references('id')->on('gallos')->onDelete('cascade');
            $table->dateTime('fecha');
            $table->string('lugar', 255);
            $table->string('estado', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peleas');
    }
};
