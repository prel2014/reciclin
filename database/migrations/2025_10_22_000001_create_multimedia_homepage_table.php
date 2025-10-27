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
        Schema::create('multimedia_homepage', function (Blueprint $table) {
            $table->id('cod_multimedia');
            $table->enum('tipo', ['imagen', 'video']); // Tipo de contenido
            $table->string('titulo', 200);
            $table->text('descripcion')->nullable();
            $table->string('archivo', 500)->nullable(); // Ruta de la imagen
            $table->string('url_video', 500)->nullable(); // URL de YouTube, Vimeo, etc.
            $table->integer('orden')->default(0); // Orden de visualización
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->enum('seccion', ['banner', 'galeria', 'destacado'])->default('galeria'); // Sección donde aparece
            $table->timestamps();

            // Índices para mejorar rendimiento
            $table->index(['estado', 'orden']);
            $table->index('seccion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('multimedia_homepage');
    }
};
