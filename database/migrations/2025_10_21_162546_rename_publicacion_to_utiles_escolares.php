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
        // Primero eliminar foreign key de compras hacia publicacion
        Schema::table('compras', function (Blueprint $table) {
            $table->dropForeign('compras_cod_publicacion_foreign');
        });

        // Eliminar foreign key de publicacion
        Schema::table('publicacion', function (Blueprint $table) {
            $table->dropForeign(['cod_usuario']);
        });

        // Renombrar la tabla
        Schema::rename('publicacion', 'utiles_escolares');

        // Modificar la tabla renombrada
        Schema::table('utiles_escolares', function (Blueprint $table) {
            // Renombrar columna principal
            $table->renameColumn('cod_publicacion', 'cod_util');

            // Eliminar columnas innecesarias
            $table->dropColumn([
                'cod_usuario',
                'moneda',
                'calidad',
                'region',
                'provincia',
                'distrito',
                'foto2',
                'foto3',
                'fecha_p',
                'fecha_u',
                'vistas'
            ]);

            // Renombrar foto1 a simplemente foto
            $table->renameColumn('foto1', 'foto');
        });

        // Recrear foreign key en compras apuntando a la nueva tabla y columna
        Schema::table('compras', function (Blueprint $table) {
            $table->foreign('cod_publicacion')
                ->references('cod_util')
                ->on('utiles_escolares')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir los cambios de columnas
        Schema::table('utiles_escolares', function (Blueprint $table) {
            // Renombrar de vuelta
            $table->renameColumn('foto', 'foto1');
            $table->renameColumn('cod_util', 'cod_publicacion');

            // Restaurar columnas eliminadas
            $table->unsignedBigInteger('cod_usuario')->nullable();
            $table->string('moneda', 200)->nullable();
            $table->string('calidad', 200)->nullable();
            $table->string('region', 200)->nullable();
            $table->string('provincia', 200)->nullable();
            $table->string('distrito', 200)->nullable();
            $table->string('foto2', 250)->nullable();
            $table->string('foto3', 250)->nullable();
            $table->string('fecha_p', 200)->nullable();
            $table->string('fecha_u', 200)->nullable();
            $table->double('vistas')->default(0);
        });

        // Renombrar la tabla de vuelta
        Schema::rename('utiles_escolares', 'publicacion');
    }
};
