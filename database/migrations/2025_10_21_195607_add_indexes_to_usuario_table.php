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
        Schema::table('usuario', function (Blueprint $table) {
            // Índice para búsqueda por correo (login)
            $table->index('correo', 'idx_usuario_correo');

            // Índice para búsqueda por nick (login alternativo)
            $table->index('nick', 'idx_usuario_nick');

            // Índice para búsqueda por tipo de usuario
            $table->index('tipo', 'idx_usuario_tipo');

            // Índice para búsqueda por estado
            $table->index('estado', 'idx_usuario_estado');

            // Índice compuesto para búsqueda de alumnos por profesor
            $table->index(['cod_profesor', 'tipo'], 'idx_usuario_profesor_tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuario', function (Blueprint $table) {
            $table->dropIndex('idx_usuario_correo');
            $table->dropIndex('idx_usuario_nick');
            $table->dropIndex('idx_usuario_tipo');
            $table->dropIndex('idx_usuario_estado');
            $table->dropIndex('idx_usuario_profesor_tipo');
        });
    }
};
