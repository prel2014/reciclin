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
        Schema::create('examenes', function (Blueprint $table) {
            $table->id('cod_examen');
            $table->unsignedBigInteger('cod_alumno'); // Alumno que rindió el examen
            $table->unsignedBigInteger('cod_profesor'); // Profesor que registró el examen
            $table->enum('tipo_examen', ['comunicacion', 'matematica', 'general']); // Tipo de examen
            $table->integer('preguntas_correctas')->default(0); // Preguntas acertadas (0-20)
            $table->integer('recipuntos_obtenidos')->default(0); // Recipuntos ganados
            $table->date('fecha_examen'); // Fecha en que se rindió
            $table->text('observaciones')->nullable(); // Notas adicionales
            $table->timestamps();

            // Foreign keys
            $table->foreign('cod_alumno')->references('cod_usuario')->on('usuario')->onDelete('cascade');
            $table->foreign('cod_profesor')->references('cod_usuario')->on('usuario')->onDelete('cascade');

            // Index para búsquedas rápidas
            $table->index(['cod_alumno', 'tipo_examen', 'fecha_examen']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examenes');
    }
};
