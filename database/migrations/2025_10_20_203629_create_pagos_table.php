<?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  return new class extends Migration
  {
      public function up(): void
      {
          Schema::create('pago', function (Blueprint $table) {
              $table->id('cod_pago');
              $table->unsignedBigInteger('cod_publicacion')->nullable();
              $table->double('precio_p')->nullable();
              $table->string('fecha_pago', 200)->nullable();

              $table->foreign('cod_publicacion')
                  ->references('cod_publicacion')
                  ->on('publicacion')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
          });
      }

      public function down(): void
      {
          Schema::dropIfExists('pago');
      }
  };