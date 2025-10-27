<?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  return new class extends Migration
  {
      public function up(): void
      {
          Schema::create('compras', function (Blueprint $table) {
              $table->id('cod_compra');
              $table->unsignedBigInteger('cod_usuario')->nullable();
              $table->unsignedBigInteger('cod_publicacion')->nullable();
              $table->double('cantidad')->nullable();
              $table->double('precio_v')->nullable();
              $table->double('precio_t')->nullable();
              $table->string('fecha', 200)->nullable();
              $table->string('status', 200)->nullable();

              $table->foreign('cod_usuario')
                  ->references('cod_usuario')
                  ->on('usuario')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

              $table->foreign('cod_publicacion')
                  ->references('cod_publicacion')
                  ->on('publicacion')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
          });
      }

      public function down(): void
      {
          Schema::dropIfExists('compras');
      }
  };