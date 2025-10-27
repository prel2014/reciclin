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
            $table->double('recipuntos')->default(0)->after('publicaciones');
            $table->unsignedBigInteger('cod_profesor')->nullable()->after('recipuntos');

            // Relación con el profesor que lo registró (solo para alumnos)
            $table->foreign('cod_profesor')
                ->references('cod_usuario')
                ->on('usuario')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuario', function (Blueprint $table) {
            $table->dropForeign(['cod_profesor']);
            $table->dropColumn(['recipuntos', 'cod_profesor']);
        });
    }
};
