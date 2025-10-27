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
        Schema::table('publicacion', function (Blueprint $table) {
            // Remove foreign key constraints first
            $table->dropForeign(['categoria']);
            $table->dropForeign(['sub_categoria']);

            // Drop category-related columns
            $table->dropColumn(['categoria', 'sub_categoria']);

            // Rename 'precio' to be clearer it's in Recipuntos
            // Keep 'precio' column but it will represent Recipuntos value
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publicacion', function (Blueprint $table) {
            // Add back category columns
            $table->unsignedBigInteger('categoria')->nullable();
            $table->unsignedBigInteger('sub_categoria')->nullable();

            // Add back foreign keys
            $table->foreign('categoria')
                ->references('cod_categoria')
                ->on('categoria')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreign('sub_categoria')
                ->references('cod_categoria_hijo')
                ->on('categoria_hijo')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }
};
