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
        Schema::create('gallos', function (Blueprint $table) {
            $table->id();
            $table->string('placa', 20)->unique();
            $table->string('name', 100);
            $table->char('sexo', 1);
            $table->date('fecha_nacimiento');
            $table->string('url_imagen', 500)->nullable();
            $table->string('color', 50);
            $table->decimal('peso', 5, 2);
            $table->decimal('talla', 5, 2);
            $table->string('color_patas', 50);
            $table->string('tipo_cresta', 50);
            $table->unsignedBigInteger('id_padre')->nullable();
            $table->foreign('id_padre')->references('id')->on('gallos')->onDelete('set null');
            $table->unsignedBigInteger('id_madre')->nullable();
            $table->foreign('id_madre')->references('id')->on('gallos')->onDelete('set null');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallos');
    }
};
