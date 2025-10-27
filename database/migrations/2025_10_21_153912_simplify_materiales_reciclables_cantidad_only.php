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
        Schema::table('materiales_reciclables', function (Blueprint $table) {
            // Remove unidad_medida - only use cantidad (quantity)
            $table->dropColumn('unidad_medida');

            // Rename recipuntos_por_unidad to recipuntos_por_cantidad for clarity
            $table->renameColumn('recipuntos_por_unidad', 'recipuntos_por_cantidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materiales_reciclables', function (Blueprint $table) {
            // Add back unidad_medida
            $table->string('unidad_medida', 50)->nullable();

            // Rename back to recipuntos_por_unidad
            $table->renameColumn('recipuntos_por_cantidad', 'recipuntos_por_unidad');
        });
    }
};
