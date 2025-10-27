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
        // Tabla de materiales reciclables (botellas, papel, etc.)
        Schema::create('materiales_reciclables', function (Blueprint $table) {
            $table->id('cod_material');
            $table->string('nombre', 200); // Ej: Botella PET, Papel, Cartón, Vidrio
            $table->text('descripcion')->nullable();
            $table->string('unidad_medida', 50); // Ej: unidad, kilogramo
            $table->double('recipuntos_por_unidad')->default(0); // Recipuntos que vale cada unidad
            $table->string('imagen', 250)->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
        });

        // Tabla para registrar el reciclaje de los alumnos
        Schema::create('registro_reciclaje', function (Blueprint $table) {
            $table->id('cod_registro');
            $table->unsignedBigInteger('cod_alumno');
            $table->unsignedBigInteger('cod_material');
            $table->unsignedBigInteger('cod_profesor'); // Profesor que registra
            $table->double('cantidad'); // Cantidad reciclada
            $table->double('recipuntos_ganados'); // Recipuntos otorgados
            $table->text('observaciones')->nullable();
            $table->date('fecha_registro');
            $table->timestamps();

            $table->foreign('cod_alumno')
                ->references('cod_usuario')
                ->on('usuario')
                ->onDelete('cascade');

            $table->foreign('cod_material')
                ->references('cod_material')
                ->on('materiales_reciclables')
                ->onDelete('cascade');

            $table->foreign('cod_profesor')
                ->references('cod_usuario')
                ->on('usuario')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_reciclaje');
        Schema::dropIfExists('materiales_reciclables');
    }
};
